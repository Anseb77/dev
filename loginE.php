<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'multitrabajos';
$user = 'root'; // Cambia según tu configuración
$pass = ''; // Cambia según tu configuración

// Conexión a la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    session_start(); // Asegúrate de iniciar la sesión

    // Recuperar datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consultar la empresa por email
    $sql = "SELECT * FROM empresa WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verificar si la empresa está bloqueada
        if ($user['bloqueado']) {
            echo "Tu cuenta está bloqueada debido a intentos fallidos excesivos.";
            exit();
        }

        // Verificar la contraseña
        if (password_verify($password, $user['password'])) {
            // Inicio de sesión exitoso, iniciar sesión y redirigir a otra página
            $_SESSION['user_id'] = $user['id']; // Guarda el ID del usuario en la sesión
            
            // Eliminar los registros de intentos fallidos
            $query = $pdo->prepare("DELETE FROM intentos_login_empresa WHERE empresa_id = :empresa_id");
            $query->execute(['empresa_id' => $user['id']]);

            header("Location: empresa/indexempresa.php"); 
            exit(); 
        } else {
            // Contraseña incorrecta
            // Registrar el intento de login fallido
            $query = $pdo->prepare("INSERT INTO intentos_login_empresa (empresa_id) VALUES (:empresa_id)");
            $query->execute(['empresa_id' => $user['id']]);

            // Contar los intentos fallidos en la última hora
            $query = $pdo->prepare("SELECT COUNT(*) FROM intentos_login_empresa WHERE empresa_id = :empresa_id AND fecha_intento >= NOW() - INTERVAL 1 HOUR");
            $query->execute(['empresa_id' => $user['id']]);
            $intentos = $query->fetchColumn();

            // Bloquear la cuenta después de 3 intentos fallidos
            if ($intentos >= 3) {
                $query = $pdo->prepare("UPDATE empresa SET bloqueado = 1 WHERE id = :id");
                $query->execute(['id' => $user['id']]);
                echo "Tu cuenta ha sido bloqueada debido a intentos fallidos excesivos.";
            } else {
                echo "Email o contraseña incorrectos. Intentos fallidos: $intentos";
            }
        }
    } else {
        echo "Email no encontrado.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
