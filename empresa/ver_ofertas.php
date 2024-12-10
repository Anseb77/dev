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

// Determina la acción a realizar (editar, eliminar, listar)
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Acción para editar una oferta existente
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titulo_cargo = $_POST['titulo_cargo'];
    $area = $_POST['area'];
    $descripcion_funciones = $_POST['descripcion_funciones'];
    $pais = $_POST['pais'];
    $ciudad = $_POST['ciudad'];
    $provincia = $_POST['provincia'];
    $jornada_laboral = $_POST['jornada_laboral'];
    $tipo_contrato = $_POST['tipo_contrato'];
    $sueldo = $_POST['sueldo'];
    $fecha_contratacion = $_POST['fecha_contratacion'];
    $cantidad_vacantes = $_POST['cantidad_vacantes'];

    // Verifica que la oferta pertenece a la empresa del usuario
    $stmt = $mysqli->prepare("SELECT * FROM ofertas WHERE id = ? AND empresa_id = ?");
    $stmt->bind_param('ii', $id, $empresa_id);
    $stmt->execute();
    $oferta = $stmt->get_result()->fetch_assoc();

    if ($oferta) {
        // Actualiza la oferta si pertenece a la empresa
        $stmt = $mysqli->prepare("UPDATE ofertas SET titulo_cargo = ?, area = ?, descripcion_funciones = ?, pais = ?, ciudad = ?, 
                                  provincia = ?, jornada_laboral = ?, tipo_contrato = ?, sueldo = ?, fecha_contratacion = ?, 
                                  cantidad_vacantes = ? WHERE id = ? AND empresa_id = ?");
        $stmt->bind_param('ssssssssdsiii', $titulo_cargo, $area, $descripcion_funciones, $pais, $ciudad, 
                          $provincia, $jornada_laboral, $tipo_contrato, $sueldo, $fecha_contratacion, 
                          $cantidad_vacantes, $id, $empresa_id);

        if ($stmt->execute()) {
            $message = "Oferta actualizada con éxito.";
        } else {
            $message = "Error al actualizar la oferta: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "No tienes permiso para editar esta oferta.";
    }

    header('Location: ?action=list');
    exit;
}

// Acción para eliminar una oferta
if ($action === 'delete') {
    $id = $_GET['id'];

    // Verifica que la oferta pertenece a la empresa del usuario
    $stmt = $mysqli->prepare("SELECT * FROM ofertas WHERE id = ? AND empresa_id = ?");
    $stmt->bind_param('ii', $id, $empresa_id);
    $stmt->execute();
    $oferta = $stmt->get_result()->fetch_assoc();

    if ($oferta) {
        // Elimina la oferta si pertenece a la empresa
        $stmt = $mysqli->prepare("DELETE FROM ofertas WHERE id = ? AND empresa_id = ?");
        $stmt->bind_param('ii', $id, $empresa_id);

        if ($stmt->execute()) {
            $message = "Oferta eliminada con éxito.";
        } else {
            $message = "Error al eliminar la oferta: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "No tienes permiso para eliminar esta oferta.";
    }

    header('Location: ?action=list');
    exit;
}

// Acción para editar una oferta (cargar datos para el formulario)
if ($action === 'edit') {
    $id = $_GET['id'];
    $stmt = $mysqli->prepare("SELECT * FROM ofertas WHERE id = ? AND empresa_id = ?");
    $stmt->bind_param('ii', $id, $empresa_id);
    $stmt->execute();
    $oferta = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Acción para listar todas las ofertas
if ($action === 'list') {
    $stmt = $mysqli->prepare("SELECT * FROM ofertas WHERE empresa_id = ?");
    $stmt->bind_param('i', $empresa_id);
    $stmt->execute();
    $ofertas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$mysqli->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ofertas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilos adicionales para el header y footer */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .header {
            background-color: #004A9F;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-link {
            display: flex;
            align-items: center;
        }
        .header-logo {
            height: 40px;
        }
        .center-logo {
            margin-left: auto;
            margin-right: auto;
        }
        .footer-section {
            background-color: #004A9F;
            color: white;
            padding: 20px 0;
            width: 100%;
            text-align: center;
            position: relative;
        }
        .content-wrapper {
            flex: 1;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="header">
        <a href="indexempresa.php" class="header-link">
            <img src="../imagenes/atras.png" alt="Logo Atras" class="header-logo">
        </a>
        <img src="../imagenes/logoremake.png" alt="Logo Multitrabajos" class="header-logo center-logo">
    </header>

    <div class="content-wrapper">
        <div class="container mt-4">
            <?php if ($action === 'list'): ?>
                <h2 class="text-center">Listado de Ofertas</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Área</th>
                            <th>Ciudad</th>
                            <th>Provincia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ofertas as $oferta): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($oferta['id']); ?></td>
                                <td><?php echo htmlspecialchars($oferta['titulo_cargo']); ?></td>
                                <td><?php echo htmlspecialchars($oferta['area']); ?></td>
                                <td><?php echo htmlspecialchars($oferta['ciudad']); ?></td>
                                <td><?php echo htmlspecialchars($oferta['provincia']); ?></td>
                                <td>
                                    <a href="?action=edit&id=<?php echo htmlspecialchars($oferta['id']); ?>" class="btn btn-warning">Editar</a>
                                    <a href="?action=delete&id=<?php echo htmlspecialchars($oferta['id']); ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta oferta?');">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif ($action === 'edit'): ?>
                <h2 class="text-center">Editar Oferta</h2>
                <form action="?action=edit&id=<?php echo htmlspecialchars($oferta['id']); ?>" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($oferta['id']); ?>">
                    <div class="mb-3">
                        <label for="titulo_cargo" class="form-label">Título del Cargo</label>
                        <input type="text" class="form-control" id="titulo_cargo" name="titulo_cargo" value="<?php echo htmlspecialchars($oferta['titulo_cargo']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="area" class="form-label">Área</label>
                        <input type="text" class="form-control" id="area" name="area" value="<?php echo htmlspecialchars($oferta['area']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_funciones" class="form-label">Descripción de Funciones</label>
                        <textarea class="form-control" id="descripcion_funciones" name="descripcion_funciones" rows="4" required><?php echo htmlspecialchars($oferta['descripcion_funciones']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="pais" class="form-label">País</label>
                        <input type="text" class="form-control" id="pais" name="pais" value="<?php echo htmlspecialchars($oferta['pais']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" class="form-control" id="ciudad" name="ciudad" value="<?php echo htmlspecialchars($oferta['ciudad']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="provincia" class="form-label">Provincia</label>
                        <input type="text" class="form-control" id="provincia" name="provincia" value="<?php echo htmlspecialchars($oferta['provincia']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="jornada_laboral" class="form-label">Jornada Laboral</label>
                        <input type="text" class="form-control" id="jornada_laboral" name="jornada_laboral" value="<?php echo htmlspecialchars($oferta['jornada_laboral']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_contrato" class="form-label">Tipo de Contrato</label>
                        <input type="text" class="form-control" id="tipo_contrato" name="tipo_contrato" value="<?php echo htmlspecialchars($oferta['tipo_contrato']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="sueldo" class="form-label">Sueldo</label>
                        <input type="number" step="0.01" class="form-control" id="sueldo" name="sueldo" value="<?php echo htmlspecialchars($oferta['sueldo']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_contratacion" class="form-label">Fecha de Creación</label>
                        <input type="date" class="form-control" id="fecha_contratacion" name="fecha_contratacion" value="<?php echo htmlspecialchars($oferta['fecha_contratacion']); ?>" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad_vacantes" class="form-label">Cantidad de Vacantes</label>
                        <input type="number" class="form-control" id="cantidad_vacantes" name="cantidad_vacantes" value="<?php echo htmlspecialchars($oferta['cantidad_vacantes']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-section">
        <div class="footer-content">
        <p>&copy; 2024 JobTec. Todos los derechos reservados.</p>
        <p>Desarrollado por Anseb</p>
        </div>
    </footer>

</body>
</html>
