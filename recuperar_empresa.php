<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 50px;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            margin: 0 auto;
            width: 300px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h3 {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        .center {
            text-align: center;
        }
        p {
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h3>Recuperar Contraseña</h3>
        <form method="POST" action="">
            <label for="email">Ingresa tu email:</label>
            <input type="email" name="email" required>
            <button type="submit">Generar Token</button>
        </form>
    </div>
</body>
</html>

<?php
// Conexión a la base de datos
$host = 'localhost';
$db = 'multitrabajos';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Lógica para manejar el proceso de recuperación y cambio de contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && !isset($_POST['reset_password'])) {
        // Paso 1: Solicitar cambio de contraseña
        $email = $_POST['email'];

        // Buscar el usuario en la base de datos
        $stmt = $pdo->prepare("SELECT id FROM empresa WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Mostrar el formulario para cambiar la contraseña
            echo "<h3>Cambiar Contraseña</h3>";
            echo "<form method='POST' action=''>
                    <input type='hidden' name='email' value='$email'>
                    <label for='new_password'>Nueva Contraseña</label>
                    <input type='password' name='new_password' required>
                    <label for='confirm_password'>Confirmar Contraseña</label>
                    <input type='password' name='confirm_password' required>
                    <button type='submit' name='reset_password'>Cambiar Contraseña</button>
                  </form>";
        } else {
            echo "<p style='color:red;'>No se encontró ningún usuario con ese email.</p>";
        }
    } elseif (isset($_POST['reset_password'])) {
        // Paso 2: Cambiar la contraseña
        $email = $_POST['email'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword === $confirmPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hashing de la nueva contraseña

            // Buscar el usuario en la base de datos
            $stmt = $pdo->prepare("SELECT id FROM empresa WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                // Actualizar la contraseña y desbloquear la cuenta
                $stmt = $pdo->prepare("UPDATE empresa SET password = ?, bloqueado = 0 WHERE id = ?");
                $stmt->execute([$hashedPassword, $user['id']]);

                // Eliminar todos los intentos de login fallidos
                $stmt = $pdo->prepare("DELETE FROM intentos_login_empresa WHERE empresa_id = ?");
                $stmt->execute([$user['id']]);

                echo "<p style='color:green;'>Tu contraseña ha sido actualizada con éxito.</p>";
                echo "<div class='center'><a href='usuarios.php'><button>Regresar al Login</button></a></div>";
            } else {
                echo "<p style='color:red;'>No se encontró ningún usuario con ese email.</p>";
            }
        } else {
            echo "<p style='color:red;'>Las contraseñas no coinciden.</p>";
        }
    }
}
?>
