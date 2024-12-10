<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../usuario.php"); // Redirigir al login si no ha iniciado sesión
    exit();
}

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

// Configuración de paginación
$limit = 3; // Número de ofertas por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Obtener palabra clave
$keyword = isset($_GET['keyword']) ? $conn->real_escape_string($_GET['keyword']) : '';

// Obtener filtros adicionales
$area = isset($_GET['area']) ? $conn->real_escape_string($_GET['area']) : '';
$provincia = isset($_GET['provincia']) ? $conn->real_escape_string($_GET['provincia']) : '';
$ciudad = isset($_GET['ciudad']) ? $conn->real_escape_string($_GET['ciudad']) : '';

// Obtener fecha
$date = isset($_GET['date']) ? $conn->real_escape_string($_GET['date']) : '';

// Construir la consulta SQL con filtros
$sql = "SELECT * FROM ofertas WHERE (titulo_cargo LIKE '%$keyword%' OR descripcion_funciones LIKE '%$keyword%')";

if (!empty($date)) {
    $sql .= " AND fecha_contratacion <= '$date'";
}

if (!empty($area)) {
    $sql .= " AND area LIKE '%$area%'";
}

if (!empty($provincia)) {
    $sql .= " AND provincia LIKE '%$provincia%'";
}

if (!empty($ciudad)) {
    $sql .= " AND ciudad LIKE '%$ciudad%'";
}

$sql .= " LIMIT $start, $limit";
$result = $conn->query($sql);

// Consulta para contar el total de ofertas con el filtro aplicado
$countSql = "SELECT COUNT(*) AS total FROM ofertas WHERE (titulo_cargo LIKE '%$keyword%' OR descripcion_funciones LIKE '%$keyword%')";

if (!empty($date)) {
    $countSql .= " AND fecha_contratacion <= '$date'";
}

if (!empty($area)) {
    $countSql .= " AND area LIKE '%$area%'";
}

if (!empty($provincia)) {
    $countSql .= " AND provincia LIKE '%$provincia%'";
}

if (!empty($ciudad)) {
    $countSql .= " AND ciudad LIKE '%$ciudad%'";
}

$countResult = $conn->query($countSql);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bolsa de Empleo</title>
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
        .job-search-container {
            padding: 20px;
            display: flex;
        }
        .filters-section {
            width: 300px;
            margin-right: 20px;
        }
        .filter-box {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 20px;
        }
        .keyword-filter {
            margin-bottom: 20px;
        }
        .job-listings-section {
            flex-grow: 1;
        }
        .sorting-options {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 20px;
        }
        .sorting-btn {
            background-color: #0066cc;
            color: white;
            border: none;
            padding: 8px 15px;
            margin-left: 10px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
        .job-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }
        .job-title {
            color: #0066cc;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
        }
        .company-name {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .job-description {
            color: #333;
            font-size: 14px;
        }
        .location, .work-mode {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }
        .work-mode {
            color: #0066cc;
            font-weight: bold;
        }
        .pagination-btn {
            background-color: #0066cc;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            margin: 0;
        }
        .container.job-search-container {
            flex: 1;
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
        .search-box {
            display: none;
            margin-bottom: 20px;
        }
        .search-box.active {
            display: block;
        }
        .filter-box input {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <a href="indexpostulante.php" class="header-link">
                <img src="../imagenes/atras.png" alt="Logo Atras" class="header-logo">
            </a>
            <img src="../imagenes/logoremake.png" alt="Logo Multitrabajos" class="header-logo center-logo">
            <a href="mis_postulaciones.php" class="btn btn-light">Ver estado de mis postulaciones</a>
        </div>
    </header>

    <div class="container job-search-container">
        <div class="filters-section">
            <!-- Filtros -->
            <div class="filter-box">
                <h4>Buscar por palabra clave</h4>
                <form method="GET" action="">
                    <div class="form-group">
                        <input type="text" name="keyword" class="form-control" placeholder="Buscar..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="search-area">Buscar por área</label>
                        <input type="text" name="area" id="search-area" class="form-control" placeholder="Área..." value="<?php echo isset($_GET['area']) ? htmlspecialchars($_GET['area']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="search-provincia">Buscar por provincia</label>
                        <input type="text" name="provincia" id="search-provincia" class="form-control" placeholder="Provincia..." value="<?php echo isset($_GET['provincia']) ? htmlspecialchars($_GET['provincia']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="search-ciudad">Buscar por ciudad</label>
                        <input type="text" name="ciudad" id="search-ciudad" class="form-control" placeholder="Ciudad..." value="<?php echo isset($_GET['ciudad']) ? htmlspecialchars($_GET['ciudad']) : ''; ?>">
                    </div>
                    <!-- <div class="form-group">
                        <label for="date">Fecha de contratación</label>
                        <input type="date" name="date" id="date" class="form-control" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
                    </div> -->
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
            </div>
        </div>

        <div class="job-listings-section">
            <!-- Opciones de ordenamiento -->
            <div class="sorting-options">
                <button class="sorting-btn">Recientes</button>
                <button class="sorting-btn">Mas Relevantes</button>
                <!-- Puedes agregar más botones de ordenamiento aquí -->
            </div>

            <!-- Listado de ofertas -->
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="job-card">
                        <div>
                            <div class="job-title"><?php echo htmlspecialchars($row['titulo_cargo']); ?></div>
                            <div class="funtions"><?php echo htmlspecialchars($row['descripcion_funciones']); ?></div>
                            <div class="job-description"><?php echo htmlspecialchars($row['sueldo']); ?></div>
                            <div class="job-description">Area:<?php echo htmlspecialchars($row['area']); ?></div>
                            <div class="location">Provincia: <?php echo htmlspecialchars($row['provincia']); ?>, Ciudad: <?php echo htmlspecialchars($row['ciudad']); ?></div>
                            <div class="work-mode"><?php echo htmlspecialchars($row['jornada_laboral']); ?></div>
                        </div>
                        <div>
                        <button class="show-more-btn" onclick="window.location.href='job-details.php?job_id=<?php echo $row['id']; ?>'">Ver más</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No se encontraron ofertas con los criterios de búsqueda.</p>
            <?php endif; ?>

            <!-- Paginación -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&keyword=<?php echo htmlspecialchars($keyword); ?>&area=<?php echo htmlspecialchars($area); ?>&provincia=<?php echo htmlspecialchars($provincia); ?>&ciudad=<?php echo htmlspecialchars($ciudad); ?>&date=<?php echo htmlspecialchars($date); ?>" class="pagination-btn">Anterior</a>
                <?php endif; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&keyword=<?php echo htmlspecialchars($keyword); ?>&area=<?php echo htmlspecialchars($area); ?>&provincia=<?php echo htmlspecialchars($provincia); ?>&ciudad=<?php echo htmlspecialchars($ciudad); ?>&date=<?php echo htmlspecialchars($date); ?>" class="pagination-btn">Siguiente</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        &copy; <?php echo date("Y"); ?> JobTec
        <br>
         Desarrollado por Anseb
    </footer>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
