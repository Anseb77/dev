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

$nombre = $apellido = $documento = $telefono = $email = $contrasena = "";
$nombreErr = $apellidoErr = $documentoErr = $telefonoErr = $emailErr = $contrasenaErr = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["nombre"])) {
        $nombreErr = "El nombre es obligatorio";
    } else {
        $nombre = test_input($_POST["nombre"]);
    }

    if (empty($_POST["apellido"])) {
        $apellidoErr = "El apellido es obligatorio";
    } else {
        $apellido = test_input($_POST["apellido"]);
    }

    if (empty($_POST["documento"])) {
        $documentoErr = "La cédula es obligatoria";
    } else {
        $documento = test_input($_POST["documento"]);
        if (!preg_match("/^[0-9]{10}$/", $documento)) {
            $documentoErr = "La cédula debe tener 10 dígitos numéricos";
        } else {
            // Verificar si la cédula ya existe
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE numero_documento = ?");
            $stmt->execute([$documento]);
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                $documentoErr = "La cédula ya está registrada";
            }
        }
    }

    if (empty($_POST["telefono"])) {
        $telefonoErr = "El teléfono es obligatorio";
    } else {
        $telefono = test_input($_POST["telefono"]);
        if (!preg_match("/^[0-9]{9}$/", $telefono)) {
            $telefonoErr = "El teléfono debe contener 9 dígitos numéricos después del prefijo +593";
        } else {
            
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "El email es obligatorio";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Formato de email inválido";
        }
    }

    if (empty($_POST["contrasena"])) {
        $contrasenaErr = "La contraseña es obligatoria";
    } else {
        $contrasena = test_input($_POST["contrasena"]);
    }

    // Si no hay errores, procesa el formulario
    if (empty($nombreErr) && empty($apellidoErr) && empty($documentoErr) && empty($telefonoErr) && empty($emailErr) && empty($contrasenaErr)) {
        // Insertar datos en la base de datos
        try {
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, numero_documento, telefono_numero, email, contrasena, acepta_terminos, acepta_privacidad, acepta_novedades) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $apellido, $documento, $telefono, $email, password_hash($contrasena, PASSWORD_DEFAULT), isset($_POST['terminos']) ? 1 : 0, isset($_POST['politica']) ? 1 : 0, isset($_POST['notificaciones']) ? 1 : 0]);

            // Mensaje de éxito
            $successMsg = "Usuario creado con éxito";

            // Limpiar los campos
            $nombre = $apellido = $documento = $telefono = $email = $contrasena = "";
            $_POST = array(); // Limpiar los datos del formulario
        } catch (PDOException $e) {
            echo "<p>Error al registrar: " . $e->getMessage() . "</p>";
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
    <title>Registro</title>
    <link rel="stylesheet" href="css/registroU.css">
    <style>
        .phone-input {
            display: flex;
            align-items: center;
        }

        .phone-prefix {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            padding: 10px;
            margin-right: 5px;
            border-radius: 4px 0 0 4px;
        }

        #telefono {
            border: 1px solid #ccc;
            border-radius: 0 4px 4px 0;
            padding: 10px;
            width: 100%;
        }

        .success-message {
            color: green;
            font-weight: bold;
        }
    </style>
    <script>
        function togglePassword() {
            var passwordField = document.getElementById('contrasena');
            var passwordToggle = document.getElementById('password-toggle');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordToggle.innerText = 'Ocultar';
            } else {
                passwordField.type = 'password';
                passwordToggle.innerText = 'Mostrar';
            }
        }
    </script>
</head>
<body>
    <header class="main-header">
        <a href="index.php" class="header-link">
            <img src="imagenes/atras.png" alt="Logo Atras" class="header-logo left-logo">
        </a>
        <img src="imagenes/logoremake.png" alt="Logo Multitrabajos" class="header-logo center-logo">
        <a href="Crearempresa.php" class="header-link">
            Regístrate como empresa
        </a>
    </header>

    <div class="container">
        <h2>Crea tu cuenta y encuentra tu empleo ideal</h2>

        <!-- Formulario HTML -->
        <?php if (!empty($successMsg)) { echo "<p class='success-message'>$successMsg</p>"; } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre(s)*</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                <span class="error"><?php echo $nombreErr; ?></span>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido(s)*</label>
                <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($apellido); ?>" required>
                <span class="error"><?php echo $apellidoErr; ?></span>
            </div>
            <div class="form-group">
                <label for="documento">Cédula*</label>
                <input type="text" id="documento" name="documento" value="<?php echo htmlspecialchars($documento); ?>" required pattern="\d{10}" title="Debe contener 10 dígitos numéricos">
                <span class="error"><?php echo $documentoErr; ?></span>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono celular*</label>
                <div class="phone-input">
                    <span class="phone-prefix">+593</span>
                    <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars(substr($telefono, 4)); ?>" required pattern="[0-9]{9}" title="Debe contener 9 dígitos numéricos después del prefijo +593">
                </div>
                <span class="error"><?php echo $telefonoErr; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <span class="error"><?php echo $emailErr; ?></span>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña*</label>
                <div class="password-container">
                    <input type="password" id="contrasena" name="contrasena" value="<?php echo htmlspecialchars($contrasena); ?>" required>
                    <button type="button" id="password-toggle" onclick="togglePassword()">Mostrar</button>
                </div>
                <span class="error"><?php echo $contrasenaErr; ?></span>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="terminos" name="terminos" required>
                <label for="terminos">Acepto las <a href="#">Condiciones de uso</a> de Multitrabajos.</label>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="politica" name="politica" required>
                <label for="politica">He leído y comprendo la <a href="#">Política de protección de datos</a> personales y privacidad de Multitrabajos.</label>
            </div>
            <div class="terms-group">
                <input type="checkbox" id="notificaciones" name="notificaciones">
                <label for="notificaciones">Acepto recibir novedades, promociones y actualizaciones.</label>
            </div>
            <button type="submit" class="submit-button">Crear cuenta</button>
            <p class="login-link">¿Ya tienes cuenta? <a href="usuarios.php">Ingresa aquí</a></p>
        </form>
    </div>

    <footer class="footer-section">
        <div class="footer-content">
            <p>&copy; 2024 Jobtec. Todos los derechos reservados.</p>
            <p>Desarrollado por Anseb</p>
        </div>
    </footer>
</body>
</html>
