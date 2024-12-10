<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registra tu empresa en Multitrabajos</title>
    <link rel="stylesheet" href="css/registro.css">
    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var passwordRepeat = document.getElementById("password-repeat").value;
            if (password !== passwordRepeat) {
                alert("Las contraseñas no coinciden.");
                return false;
            }

            var docType = document.getElementById("condicion-fiscal").value;
            var documentInput = document.getElementById("documento").value;
            var pattern;
            if (docType === "RUC") {
                pattern = /^\d{13}$/;
            } else if (docType === "DNI") {
                pattern = /^\d{10}$/;
            } else {
                pattern = /^$/; // No validation for other options
            }

            if (!pattern.test(documentInput)) {
                alert("Número de documento no válido.");
                return false;
            }

            return true;
        }

        function togglePassword(id, btnId) {
            var passwordField = document.getElementById(id);
            var toggleBtn = document.getElementById(btnId);
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleBtn.innerText = "Ocultar";
            } else {
                passwordField.type = "password";
                toggleBtn.innerText = "Mostrar";
            }
        }
    </script>
</head>
<body>
<header>
    <a href="index.php" class="header-link">
        <img src="imagenes/atras.png" alt="Logo Atras" class="header-logo">
    </a>
    <img src="imagenes/logoremake.png" alt="Logo Multitrabajos" class="header-logo center-logo">
    <a href="registrarU.php" class="header-link">Registrate como postulante</a>
</header>

<main>
    <form class="registration-form" action="registroE.php" method="POST" onsubmit="return validateForm()">
        <h2>Completa la información de tu usuario</h2>
        <div class="form-group">
            <label for="nombre">Nombre(s)</label>
            <input type="text" id="nombre" name="nombre" placeholder="Stalin" required>
        </div>
        <div class="form-group">
            <label for="apellido">Apellido(s)</label>
            <input type="text" id="apellido" name="apellido" placeholder="Mejia" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="stalin9116@gmail.com" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            <button type="button" id="toggle-password" onclick="togglePassword('password', 'toggle-password')">Mostrar</button>
        </div>
        <div class="form-group">
            <label for="password-repeat">Repetir contraseña</label>
            <input type="password" id="password-repeat" name="password-repeat" placeholder="Repetir contraseña" required>
            <button type="button" id="toggle-password-repeat" onclick="togglePassword('password-repeat', 'toggle-password-repeat')">Mostrar</button>
        </div>

        <h2>Ingresa los datos de la empresa</h2>
        <div class="form-group">
            <label for="nombre-empresa">Nombre de la empresa</label>
            <input type="text" id="nombre-empresa" name="nombre-empresa" placeholder="Nombre de la empresa" required>
        </div>
        <div class="form-group">
            <label for="razon-social">Razón social</label>
            <input type="text" id="razon-social" name="razon-social" placeholder="Razón social" required>
        </div>
        <div class="form-group">
            <label for="condicion-fiscal">Condición fiscal</label>
            <select id="condicion-fiscal" name="condicion-fiscal" required onchange="validateForm()">
                <option value="">Seleccione una opción</option>
                <option value="RUC">RUC</option>
                <option value="DNI">DNI</option>
            </select>
        </div>
        <div class="form-group">
            <label for="documento">Documento</label>
            <input type="text" id="documento" name="documento" placeholder="Ingresar solo números" required pattern="\d+" maxlength="13">
        </div>
        <div class="form-group">
            <label for="calle">Calle</label>
            <input type="text" id="calle" name="calle" placeholder="Calle" required>
        </div>
        <div class="form-group">
            <label for="numero">Número</label>
            <input type="text" id="numero" name="numero" placeholder="Número" required>
        </div>
        <div class="form-group">
            <label for="codigo-postal">Código Postal</label>
            <input type="text" id="codigo-postal" name="codigo-postal" placeholder="Código Postal" required pattern="\d+">
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <div class="telefono-group">
                <select id="codigo-pais" name="codigo-pais" required>
                    <option value="+593">+593 (Ecuador)</option>
                    <option value="+34">+34 (España)</option>
                    <option value="+1">+1 (EE.UU.)</option>
                </select>
                <input type="text" id="telefono" name="telefono" placeholder="9 99123457" required pattern="\d{1,10}">
            </div>
        </div>
        <div class="form-group">
            <label for="industria">Industria</label>
            <select id="industria" name="industria" required>
                <option value="">Seleccione una opción</option>
                <option value="Tecnología">Tecnología</option>
                <option value="Salud">Salud</option>
                <option value="Finanzas">Finanzas</option>
                <option value="Educación">Educación</option>
                <option value="Comercio">Comercio</option>
            </select>
        </div>
        <div class="form-group">
            <label for="cantidad-empleados">Cantidad de empleados</label>
            <select id="cantidad-empleados" name="cantidad-empleados" required>
                <option value="">Seleccione una opción</option>
                <option value="1-10">1-10</option>
                <option value="11-50">11-50</option>
                <option value="51-200">51-200</option>
                <option value="201-500">201-500</option>
                <option value="500+">Más de 500</option>
            </select>
        </div>

        <div class="form-group terms">
            <input type="checkbox" id="terminos" name="terminos" required>
            <label for="terminos">Acepto los <a href="#">Términos y Condiciones</a>, <a href="#">Política de Privacidad</a>, <a href="#">Condiciones de contratación</a>.</label>
        </div>

        <button type="submit" class="submit-btn">Registrar empresa</button>

        <p class="login-link">¿Ya tienes cuenta? <a href="empresa.php">Ingresa aquí</a></p>
    </form>

    <div class="form-image">
        <img src="imagenes/registroempresa.png" alt="Imagen de registro">
    </div>
</main>
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="success-message">
        <p>¡Registro completado con éxito! La empresa ha sido creada.</p>
    </div>
<?php endif; ?>

<footer class="footer-section">
    <div class="footer-content">
        <p>&copy; 2024 JobTec. Todos los derechos reservados.</p>
        <p>Desarrollado por Anseb</p>
    </div>
</footer>

</body>
</html>
