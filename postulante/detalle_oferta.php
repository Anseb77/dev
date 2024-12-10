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

// Obtener los detalles de la oferta de trabajo
$oferta_id = $_GET['oferta_id'];
$sql = "SELECT * FROM ofertas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $oferta_id);
$stmt->execute();
$result = $stmt->get_result();
$oferta = $result->fetch_assoc();

if ($oferta) {
    ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detalles de la Oferta</title>
        <!-- Bootstrap CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f2f4f8;
                font-family: Arial, sans-serif;
                padding-top: 70px;
            }
            .container {
                margin-top: 30px;
            }
            .job-details-card {
                background-color: white;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                padding: 30px;
                margin-bottom: 20px;
            }
            .job-title {
                color: #0066cc;
                margin-bottom: 20px;
                font-size: 24px;
                font-weight: bold;
            }
            .company-name, .location, .work-mode, .description-label {
                color: #666;
                font-size: 16px;
                margin-bottom: 10px;
            }
            .description-label {
                font-weight: bold;
            }
            .description-content {
                color: #333;
                font-size: 16px;
                margin-bottom: 20px;
            }
            .postular-btn {
                background-color: #0066cc;
                color: white;
                padding: 10px 20px;
                font-size: 16px;
                border-radius: 5px;
                border: none;
                cursor: pointer;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="job-details-card">
                <h2 class="job-title"><?php echo htmlspecialchars($oferta['titulo_cargo']); ?></h2>
                <p class="company-name">Área: <?php echo htmlspecialchars($oferta['area']); ?></p>
                <p class="location">Ciudad: <?php echo htmlspecialchars($oferta['ciudad']); ?></p>
                <p class="work-mode">Jornada Laboral: <?php echo htmlspecialchars($oferta['jornada_laboral']); ?></p>
                <p class="description-label">Descripción de Funciones:</p>
                <p class="description-content"><?php echo nl2br(htmlspecialchars($oferta['descripcion_funciones'])); ?></p>
                <form action="postular.php" method="POST">
                    <input type="hidden" name="oferta_id" value="<?php echo htmlspecialchars($oferta['id']); ?>">
                    <button type="submit" class="postular-btn">Postularme</button>
                </form>
            </div>
        </div>
        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>

    </html>
    <?php
} else {
    echo "<p>No se encontró la oferta de trabajo.</p>";
}
$conn->close();
?>
