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

// Obtener el ID de la oferta de forma segura
$job_id = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;

// Consultar la oferta de trabajo de forma segura
$sql = $conn->prepare("SELECT * FROM ofertas WHERE id = ?");
$sql->bind_param("i", $job_id);
$sql->execute();
$result = $sql->get_result();
$job = $result->fetch_assoc();

$conn->close();
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
        }
        .header {
            background-color: #0066cc;
            padding: 10px 0;
            color: white;
            text-align: center;
        }
        .job-details {
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .job-title {
            color: #0066cc;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        .job-description, .job-requirements, .job-salary, .job-date {
            margin-bottom: 15px;
            font-size: 16px;
        }
        .btn-apply {
            background-color: #0066cc;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Detalles de la Oferta</h1>
    </header>

    <div class="container">
        <?php if ($job): ?>
            <div class="job-details">
                <div class="job-title"><?php echo htmlspecialchars($job['titulo_cargo']); ?></div>
                <div class="job-description">
                    <strong>Descripción:</strong>
                    <p><?php echo htmlspecialchars($job['descripcion_funciones']); ?></p>
                </div>
                <div class="job-requirements">
                    <strong>Vacantes Disponibles:</strong>
                    <p><?php echo htmlspecialchars($job['cantidad_vacantes']); ?></p>
                </div>
                <div class="job-salary">
                    <strong>Sueldo:</strong>
                    <p><?php echo htmlspecialchars($job['sueldo']); ?></p>
                </div>
                <div class="job-date">
                    <strong>Años de experiencia:</strong>
                    <p><?php echo htmlspecialchars($job['anos_experiencia']); ?></p>
                </div>
                <div class="job-date">
                    <strong>Estudios Mínimos:</strong>
                    <p><?php echo htmlspecialchars($job['estudios_minimos']); ?></p>
                </div>
                <div class="job-date">
                    <strong>Pais:</strong>
                    <p><?php echo htmlspecialchars($job['pais']); ?></p>
                </div>
                <div class="job-date">
                    <strong>Provincia:</strong>
                    <p><?php echo htmlspecialchars($job['provincia']); ?></p>
                </div>
                <div class="job-date">
                    <strong>Ciudad:</strong>
                    <p><?php echo htmlspecialchars($job['ciudad']); ?></p>
                </div>
                <a href="apply.php?job_id=<?php echo $job['id']; ?>" class="btn btn-apply">Postular</a>
                <a href="bolsaempleo.php" class="btn btn-apply">Regresar</a>
            </div>
        <?php else: ?>
            <p>No se encontraron detalles para esta oferta.</p>
        <?php endif; ?>
    </div>
</body>
</html>
