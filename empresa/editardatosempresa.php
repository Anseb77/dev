<?php
// Inicia la sesión
session_start();

// Verifica si el usuario ha iniciado sesión y tiene un ID de empresa
if (!isset($_SESSION['user_id'])) {
    die("Error: Usuario no autenticado.");
}

$empresa_id = $_SESSION['user_id'];  // ID de la empresa basado en el usuario logueado

// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "multitrabajos");

// Verifica la conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Variable para almacenar el mensaje de éxito o error
$message = "";

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $nombre_empresa = $_POST['nombre_empresa'];
    $razon_social = $_POST['razon_social'];
    $condicion_fiscal = $_POST['condicion_fiscal'];
    $documento = $_POST['documento'];
    $calle = $_POST['calle'];
    $numero = $_POST['numero'];
    $codigo_postal = $_POST['codigo_postal'];
    $telefono = $_POST['telefono'];
    $industria = $_POST['industria'];
    $cantidad_empleados = $_POST['cantidad_empleados'];

   // Validaciones
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "Email inválido.";
} elseif ($condicion_fiscal == 'DNI' && !preg_match('/^\d{10}$/', $documento)) {
    $message = "El número de documento DNI debe tener 10 dígitos.";
} elseif ($condicion_fiscal == 'RUC' && !preg_match('/^\d{13}$/', $documento)) {
    $message = "El número de documento RUC debe tener 13 dígitos.";
} else {
    // Encriptar la contraseña si se ha cambiado
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE empresa SET nombre='$nombre', apellido='$apellido', email='$email', password='$password', nombre_empresa='$nombre_empresa', razon_social='$razon_social', condicion_fiscal='$condicion_fiscal', documento='$documento', calle='$calle', numero='$numero', codigo_postal='$codigo_postal', telefono='$telefono', industria='$industria', cantidad_empleados='$cantidad_empleados' WHERE id='$empresa_id'";
    } else {
        $sql = "UPDATE empresa SET nombre='$nombre', apellido='$apellido', email='$email', nombre_empresa='$nombre_empresa', razon_social='$razon_social', condicion_fiscal='$condicion_fiscal', documento='$documento', calle='$calle', numero='$numero', codigo_postal='$codigo_postal', telefono='$telefono', industria='$industria', cantidad_empleados='$cantidad_empleados' WHERE id='$empresa_id'";
    }

    if ($mysqli->query($sql) === TRUE) {
        $message = "Datos actualizados correctamente.";
    } else {
        $message = "Error al actualizar los datos: " . $mysqli->error;
    }
}

}

// Obtener datos actuales para mostrar en el formulario
$sql = "SELECT * FROM empresa WHERE id='$empresa_id'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $empresa = $result->fetch_assoc();
} else {
    die("No se encontraron datos para la empresa.");
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Datos de Empresa</title>
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos adicionales */
        .container {
            max-width: 900px;
            margin-top: 30px;
        }

        .form-section {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-section h2 {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .readonly-select {
            background-color: #e9ecef;
            pointer-events: none;
        }

        .main-header {
            background-color: #343a40;
            padding: 10px;
            text-align: center;
        }

        .header-link img {
            height: 40px;
        }

        .header-logo.center-logo {
            height: 60px;
        }

        .footer-section {
            background-color: #343a40;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <a href="indexempresa.php" class="header-link">
            <img src="../imagenes/atras.png" alt="Logo Atras" class="header-logo left-logo">
        </a>
        <img src="../imagenes/logoremake.png" alt="Logo Multitrabajos" class="header-logo center-logo">
    </header>

    <div class="container">
        <div class="form-section">
            <h2>Editar Datos de Empresa</h2>

            <?php if ($message): ?>
                <div class="alert alert-info">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <!-- Campo oculto para el ID de la empresa -->
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($empresa['id']); ?>">

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($empresa['nombre']); ?>">
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($empresa['apellido']); ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" pattern=".+@.+\..+" required value="<?php echo htmlspecialchars($empresa['email']); ?>">
                    <small class="form-text text-muted">Debe contener un '@' y un dominio.</small>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <small class="form-text text-muted">Deja este campo vacío si no deseas cambiar la contraseña.</small>
                </div>

                <div class="form-group">
                    <label for="nombre_empresa">Nombre de la Empresa:</label>
                    <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" value="<?php echo htmlspecialchars($empresa['nombre_empresa']); ?>">
                </div>

                <div class="form-group">
                    <label for="razon_social">Razón Social:</label>
                    <input type="text" class="form-control" id="razon_social" name="razon_social" value="<?php echo htmlspecialchars($empresa['razon_social']); ?>">
                </div>

                <div class="form-group">
                    <label for="condicion_fiscal">Condición Fiscal:</label>
                    <select class="form-control" id="condicion_fiscal" name="condicion_fiscal">
                        <option value="DNI" <?php echo ($empresa['condicion_fiscal'] == 'DNI') ? 'selected' : ''; ?>>DNI</option>
                        <option value="RUC" <?php echo ($empresa['condicion_fiscal'] == 'RUC') ? 'selected' : ''; ?>>RUC</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="documento">Documento:</label>
                    <input type="text" class="form-control" id="documento" name="documento" pattern="\d*" maxlength="13" value="<?php echo htmlspecialchars($empresa['documento']); ?>">
                </div>

                <div class="form-group">
                    <label for="calle">Calle:</label>
                    <input type="text" class="form-control" id="calle" name="calle" value="<?php echo htmlspecialchars($empresa['calle']); ?>">
                </div>

                <div class="form-group">
    <label for="numero">Número:</label>
    <input type="text" class="form-control" id="numero" name="numero" maxlength="255" value="<?php echo htmlspecialchars($empresa['numero']); ?>">
</div>



                <div class="form-group">
                    <label for="codigo_postal">Código Postal:</label>
                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" value="<?php echo htmlspecialchars($empresa['codigo_postal']); ?>">
                </div>

                <div class="form-group">
    <label for="telefono">Teléfono:</label>
    <input type="text" class="form-control" id="telefono" name="telefono" maxlength="255" value="<?php echo htmlspecialchars($empresa['telefono']); ?>">
</div>


                <div class="form-group">
                    <label for="industria">Industria:</label>
                    <input type="text" class="form-control" id="industria" name="industria" value="<?php echo htmlspecialchars($empresa['industria']); ?>">
                </div>

                <div class="form-group">
    <label for="cantidad_empleados">Cantidad de Empleados:</label>
    <select class="form-control" id="cantidad_empleados" name="cantidad_empleados">
        <option value="1-10" <?php echo ($empresa['cantidad_empleados'] == '1-10') ? 'selected' : ''; ?>>1-10</option>
        <option value="11-50" <?php echo ($empresa['cantidad_empleados'] == '11-50') ? 'selected' : ''; ?>>11-50</option>
        <option value="51-100" <?php echo ($empresa['cantidad_empleados'] == '51-100') ? 'selected' : ''; ?>>51-100</option>
        <option value="101-500" <?php echo ($empresa['cantidad_empleados'] == '101-500') ? 'selected' : ''; ?>>101-500</option>
        <option value="501-1000" <?php echo ($empresa['cantidad_empleados'] == '501-1000') ? 'selected' : ''; ?>>501-1000</option>
        <option value="1000+" <?php echo ($empresa['cantidad_empleados'] == '1000+') ? 'selected' : ''; ?>>1000+</option>
    </select>
</div>


                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-section">
    <p>&copy; 2024 JobTec. Todos los derechos reservados.</p>
    <p>Desarrollado por Anseb</p>
    </footer>
</body>
</html>
