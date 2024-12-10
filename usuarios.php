<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'multitrabajos';
$user = 'root'; // Cambia según tu configuración
$pass = ''; // Cambia según tu configuración

// Conectar a la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("No se pudo conectar a la base de datos: " . $e->getMessage());
}

$email = $contrasena = "";
$emailErr = $contrasenaErr = "";
$loginErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailErr = "El email es obligatorio";
    } else {
        $email = test_input($_POST["email"]);
    }

    if (empty($_POST["contrasena"])) {
        $contrasenaErr = "La contraseña es obligatoria";
    } else {
        $contrasena = test_input($_POST["contrasena"]);
    }

    // Si no hay errores, procesar el login
    if (empty($emailErr) && empty($contrasenaErr)) {
        try {
            // Verificar si el usuario está bloqueado
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND bloqueado = 1");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $loginErr = "Tu cuenta está bloqueada por intentos fallidos.";
            } else {
                // Verificar las credenciales
                $stmt = $pdo->prepare("SELECT id, contrasena FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($contrasena, $user['contrasena'])) {
                    // Iniciar sesión y almacenar el ID del usuario en la sesión
                    session_start();
                    $_SESSION['usuario_id'] = $user['id'];
                    
                    // Limpiar los intentos fallidos
                    $stmt = $pdo->prepare("DELETE FROM intentos_login WHERE usuario_id = ?");
                    $stmt->execute([$user['id']]);

                    // Asegurarse de que el usuario no esté bloqueado después de limpiar los intentos
                    $stmt = $pdo->prepare("UPDATE usuarios SET bloqueado = 0 WHERE id = ?");
                    $stmt->execute([$user['id']]);

                    // Redirigir al perfil del usuario
                    header("Location: postulante/indexpostulante.php");
                    exit();
                } else {
                    // Registrar el intento fallido
                    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
                    $stmt->execute([$email]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($user) {
                        $stmt = $pdo->prepare("INSERT INTO intentos_login (usuario_id) VALUES (?)");
                        $stmt->execute([$user['id']]);
                    }

                    // Verificar si el usuario debe ser bloqueado
                    $stmt = $pdo->prepare("SELECT COUNT(*) AS intentos FROM intentos_login WHERE usuario_id = ? AND fecha_intento > NOW() - INTERVAL 1 HOUR");
                    $stmt->execute([$user['id']]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($result['intentos'] >= 3) {
                        // Bloquear al usuario
                        $stmt = $pdo->prepare("UPDATE usuarios SET bloqueado = 1 WHERE id = ?");
                        $stmt->execute([$user['id']]);
                        $loginErr = "Tu cuenta ha sido bloqueada por intentos fallidos.";
                    } else {
                        $loginErr = "Email o contraseña incorrectos";
                    }
                }
            }
        } catch (PDOException $e) {
            echo "<p>Error al autenticar: " . $e->getMessage() . "</p>";
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/login.css"> <!-- Puedes agregar estilos si lo deseas -->
    <style>
        .password-container {
            position: relative;
            width: 100%;
        }
        .password-container input[type="password"],
        .password-container input[type="text"] {
            width: calc(100% - 30px); /* Deja espacio para el botón del ojo */
            padding-right: 30px;
        }
        .password-container .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <a href="index.php" class="header-link">
            <img src="imagenes/atras.png" alt="Logo Atras" class="header-logo left-logo">
        </a>
        <img src="imagenes/logoremake.png" alt="Logo Multitrabajos" class="header-logo center-logo">
        <a href="empresa.php" class="header-link">Ingresa como empresa</a>
    </header>

    <div class="container">
        <h2>Iniciar sesión Postulante</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <span class="error"><?php echo htmlspecialchars($emailErr); ?></span>
            </div>
            <div class="form-group password-container">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
                <span class="eye-icon" id="togglePassword">&#128065;</span>
                <span class="error"><?php echo htmlspecialchars($contrasenaErr); ?></span>
                <div class="forgot-password">
                    <a href="recuperar_contrasena.php">¿Olvidaste tu contraseña?</a>
                </div>
            </div>
            <button type="submit" class="submit-button">Ingresar</button>
            <a href="registrarU.php">¿No tienes cuenta? Regístrate como candidato</a>
            <p class="error"><?php echo htmlspecialchars($loginErr); ?></p>
        </form>
    </div>

    <footer class="footer-section">
        <div class="footer-content">
            <p>&copy; 2024 JobTec. Todos los derechos reservados.</p>
            <p>Desarrollado por Anseb</p>
        </div>
    </footer>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            var passwordField = document.getElementById('contrasena');
            var type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
</body>
</html>
