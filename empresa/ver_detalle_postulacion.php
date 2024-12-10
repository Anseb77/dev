<?php
// Conexión a la base de datos
$host = "localhost";
$username = "root";
$password = "";
$dbname = "multitrabajos";
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el ID de la postulación desde la URL
$postulacion_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Consultar los detalles de la postulación
$sql = "SELECT p.*, u.nombre AS nombre, u.email AS email, u.telefono AS telefono_numero, o.descripcion AS descripcion_funciones 
        FROM postulaciones p
        JOIN usuarios u ON p.usuario_id = u.id
        JOIN ofertas o ON p.oferta_id = o.id
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $postulacion_id);
$stmt->execute();
$result = $stmt->get_result();
$postulacion = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Postulación</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f4f8;
            font-family: Arial, sans-serif;
            padding-top: 70px;
        }
        .header {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: #0066cc;
            padding: 10px 0;
            z-index: 1000;
        }
        .header .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header-link {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }
        .header-logo {
            height: 40px;
        }
        .center-logo {
            margin: 0 auto;
        }
        .footer {
            background-color: #0066cc;
            color: white;
            padding: 15px 0;
            text-align: center;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        .application-detail {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .application-detail h2 {
            color: #0066cc;
            margin-bottom: 20px;
        }
        .application-detail p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .btn-back {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <a href="ver_postulaciones.php" class="header-link">
                <img src="../imagenes/atras.png" alt="Logo Atras" class="header-logo">
            </a>
            <img src="../imagenes/logo.png" alt="Logo Multitrabajos" class="header-logo center-logo">
        </div>
    </header>

    <div class="container">
        <h1 class="my-4">Detalle de Postulación</h1>
        <?php if ($postulacion): ?>
            <div class="application-detail">
                <h2><?php echo htmlspecialchars($postulacion['titulo_cargo']); ?></h2>
                <p><strong>Nombre del Usuario:</strong> <?php echo htmlspecialchars($postulacion['nombre']); ?></p>
                <p><strong>Email del Usuario:</strong> <?php echo htmlspecialchars($postulacion['email']); ?></p>
                <p><strong>Teléfono del Usuario:</strong> <?php echo htmlspecialchars($postulacion['telefono_numero']); ?>
                <p><strong>Descripción de la Oferta:</strong> <?php echo htmlspecialchars($postulacion['descripcion_funciones']); ?></p>
            </div>
        <?php else: ?>
            <p>No se encontró la postulación.</p>
        <?php endif; ?>

        <a href="ver_postulaciones.php" class="btn btn-secondary btn-back">Volver</a>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Multitrabajos. Todos los derechos reservados.</p>
    </footer>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
