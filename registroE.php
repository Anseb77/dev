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

    // Verificar si el formulario ha sido enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recuperar datos del formulario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar contraseña
        $nombre_empresa = $_POST['nombre-empresa'];
        $razon_social = $_POST['razon-social'];
        $condicion_fiscal = $_POST['condicion-fiscal'];
        $documento = $_POST['documento'];
        $calle = $_POST['calle'];
        $numero = $_POST['numero'];
        $codigo_postal = $_POST['codigo-postal'];
        $telefono = $_POST['codigo-pais'] . $_POST['telefono'];
        $industria = $_POST['industria'];
        $cantidad_empleados = $_POST['cantidad-empleados'];
        $terminos = isset($_POST['terminos']) ? 1 : 0; // 1 para aceptado, 0 para no aceptado

        // Comprobar si el email ya está registrado
        $sql = "SELECT COUNT(*) FROM empresa WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            // Mostrar mensaje de error si el email ya está registrado
            echo "<p style='color: red;'>El correo electrónico ya está en uso. Por favor, utiliza otro.</p>";
        } else {
            // Insertar datos en la base de datos si el email no existe
            $sql = "INSERT INTO empresa (
                        nombre, apellido, email, password, nombre_empresa, razon_social, condicion_fiscal, documento, calle, numero, codigo_postal, telefono, industria, cantidad_empleados, terminos
                    ) VALUES (
                        :nombre, :apellido, :email, :password, :nombre_empresa, :razon_social, :condicion_fiscal, :documento, :calle, :numero, :codigo_postal, :telefono, :industria, :cantidad_empleados, :terminos
                    )";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':email' => $email,
                ':password' => $password,
                ':nombre_empresa' => $nombre_empresa,
                ':razon_social' => $razon_social,
                ':condicion_fiscal' => $condicion_fiscal,
                ':documento' => $documento,
                ':calle' => $calle,
                ':numero' => $numero,
                ':codigo_postal' => $codigo_postal,
                ':telefono' => $telefono,
                ':industria' => $industria,
                ':cantidad_empleados' => $cantidad_empleados,
                ':terminos' => $terminos
            ]);

            // Redirigir con un mensaje de éxito
            header("Location: Crearempresa.php?success=1");
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
