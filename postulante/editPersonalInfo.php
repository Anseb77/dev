<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../usuario.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

$host = 'localhost';
$dbname = 'multitrabajos';
$user = 'root'; // Cambia según tu configuración
$pass = ''; // Cambia según tu configuración

$mensaje = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener los datos actuales del usuario
    $sql = "SELECT nacionalidad, fecha_nacimiento, genero, estado_civil, numero_documento FROM usuarios WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Procesar el formulario para actualizar los datos personales
        $nacionalidad = $_POST['nacionalidad'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $genero = $_POST['genero'];
        $estado_civil = $_POST['estado_civil'];
        $numero_documento = $_POST['numero_documento'];

        $sql = "UPDATE usuarios SET nacionalidad = ?, fecha_nacimiento = ?, genero = ?, estado_civil = ?, numero_documento = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nacionalidad, $fecha_nacimiento, $genero, $estado_civil, $numero_documento, $usuario_id]);

        $mensaje = 'Datos personales guardados exitosamente.';
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Datos Personales</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Datos Personales</h2>
        <?php if ($mensaje): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        <form action="editPersonalInfo.php" method="post">
        <div class="form-group">
    <label for="nacionalidad">Nacionalidad:</label>
    <select id="nacionalidad" name="nacionalidad" class="form-control" required>
        <option value="">Seleccione su nacionalidad</option>
        <option value="Ecuador" <?php echo ($usuario['nacionalidad'] == 'Ecuador') ? 'selected' : ''; ?>>Ecuador</option>
        <option value="Colombia" <?php echo ($usuario['nacionalidad'] == 'Colombia') ? 'selected' : ''; ?>>Colombia</option>
        <option value="Peru" <?php echo ($usuario['nacionalidad'] == 'Peru') ? 'selected' : ''; ?>>Perú</option>
        <option value="Argentina" <?php echo ($usuario['nacionalidad'] == 'Argentina') ? 'selected' : ''; ?>>Argentina</option>
        <option value="Chile" <?php echo ($usuario['nacionalidad'] == 'Chile') ? 'selected' : ''; ?>>Chile</option>
        <option value="Mexico" <?php echo ($usuario['nacionalidad'] == 'Mexico') ? 'selected' : ''; ?>>México</option>
        <!-- Agrega más opciones según sea necesario -->
    </select>
</div>
            <div class="form-group">
    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($usuario['fecha_nacimiento']); ?>" required class="form-control" max="">
</div>

<div class="form-group">
    <label for="genero">Género:</label>
    <select id="genero" name="genero" class="form-control" required>
        <option value="">Seleccione su género</option>
        <option value="Masculino" <?php echo ($usuario['genero'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
        <option value="Femenino" <?php echo ($usuario['genero'] == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
        <option value="Otro" <?php echo ($usuario['genero'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
    </select>
</div>
<div class="form-group">
    <label for="estado_civil">Estado Civil:</label>
    <select id="estado_civil" name="estado_civil" class="form-control" required>
        <option value="">Seleccione su estado civil</option>
        <option value="Soltero" <?php echo ($usuario['estado_civil'] == 'Soltero') ? 'selected' : ''; ?>>Soltero</option>
        <option value="Casado" <?php echo ($usuario['estado_civil'] == 'Casado') ? 'selected' : ''; ?>>Casado</option>
        <option value="Divorciado" <?php echo ($usuario['estado_civil'] == 'Divorciado') ? 'selected' : ''; ?>>Divorciado</option>
        <option value="Viudo" <?php echo ($usuario['estado_civil'] == 'Viudo') ? 'selected' : ''; ?>>Viudo</option>
    </select>
</div>
<div class="form-group">
    <label for="numero_documento">Número de Documento:</label>
    <input type="text" id="numero_documento" name="numero_documento" value="<?php echo htmlspecialchars($usuario['numero_documento']); ?>" 
           required class="form-control" pattern="\d{10}" maxlength="10" title="Debe ingresar 10 dígitos numéricos">
</div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="indexpostulante.php" class="btn btn-secondary">Regresar</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    // Obtener la fecha actual
    const today = new Date().toISOString().split('T')[0];
    // Establecer el atributo 'max' para que sea la fecha actual
    document.getElementById('fecha_nacimiento').setAttribute('max', today);
</script>

</body>
</html>
