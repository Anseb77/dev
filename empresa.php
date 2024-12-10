<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicia sesión en Multitrabajos</title>
    <link rel="stylesheet" href="css/loginE.css">
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
        <a href="usuarios.php" class="header-link">Ingresa como postulante</a>
    </header>

    <div class="container">
        <h2>Iniciar sesión Empresa</h2>
        <form action="loginE.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group password-container">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                <span class="eye-icon" id="togglePassword">&#128065;</span>
                <div class="forgot-password">
                    <a href="recuperar_empresa.php">¿Olvidaste tu contraseña?</a>
                </div>
            </div>
            <button type="submit" class="submit-button">Ingresar</button>
            <div class="register-link">
                <a href="Crearempresa.php">¿No tienes cuenta? Regístrate como empresa</a>
            </div>
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
            var passwordField = document.getElementById('password');
            var type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
</body>
</html>
