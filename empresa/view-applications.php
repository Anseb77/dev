<?php
// Inicia la sesión
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    die("Error: Usuario no autenticado.");
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
$limit = 10; // Número de postulaciones por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Obtener parámetros de búsqueda
$searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$genderFilter = isset($_GET['gender']) ? $conn->real_escape_string($_GET['gender']) : '';
$orderBy = isset($_GET['order_by']) ? $conn->real_escape_string($_GET['order_by']) : 'p.fecha_postulacion DESC';

// Construir la consulta SQL
$sql = "SELECT p.*, u.nombre AS usuario_nombre, u.apellido AS usuario_apellido, u.numero_documento, u.email, u.genero, o.titulo_cargo, 
                u.education, u.languages, u.experience, u.objective
        FROM postulaciones p
        JOIN usuarios u ON p.usuario_id = u.id
        JOIN ofertas o ON p.oferta_id = o.id
        WHERE (u.nombre LIKE '%$searchTerm%' OR u.apellido LIKE '%$searchTerm%' OR u.email LIKE '%$searchTerm%' OR o.titulo_cargo LIKE '%$searchTerm%')
        AND ('$genderFilter' = '' OR u.genero = '$genderFilter')
        AND o.empresa_id = ?  -- Filtra por la empresa del usuario
        ORDER BY $orderBy
        LIMIT $start, $limit";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $_SESSION['user_id']);  // Bind el ID de empresa del usuario
$stmt->execute();
$result = $stmt->get_result();

// Consultar el total de postulaciones para la paginación
$countSql = "SELECT COUNT(*) AS total 
             FROM postulaciones p
             JOIN usuarios u ON p.usuario_id = u.id
             JOIN ofertas o ON p.oferta_id = o.id
             WHERE (u.nombre LIKE '%$searchTerm%' OR u.apellido LIKE '%$searchTerm%' OR u.email LIKE '%$searchTerm%' OR o.titulo_cargo LIKE '%$searchTerm%')
             AND ('$genderFilter' = '' OR u.genero = '$genderFilter')
             AND o.empresa_id = ?";  // Filtra por la empresa del usuario

$countStmt = $conn->prepare($countSql);
$countStmt->bind_param('i', $_SESSION['user_id']);  // Bind el ID de empresa del usuario
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Cerrar conexiones
$stmt->close();
$countStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Postulaciones</title>
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
        
.container {
    flex: 1;
}

.footer {
    background-color: #0066cc;
    color: white;
    padding: 15px 0;
    text-align: center;
    width: 100%;
    position: fixed;
    bottom: 0;
    left: 0;
}
        .application-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .application-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .job-title {
            color: #0066cc;
            margin-bottom: 10px;
            font-size: 20px;
            font-weight: bold;
        }
        .application-date {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .user-name, .user-email, .user-gender, .user-education, .user-languages, .user-experience, .user-objective {
            font-size: 14px;
            color: #333;
            margin-bottom: 10px;
        }
        .btn-accept {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }
        .btn-accept:hover {
            background-color: #218838;
        }
        .btn-reject {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }
        .btn-reject:hover {
            background-color: #c82333;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            color: #0066cc;
            text-decoration: none;
            padding: 10px 15px;
            border: 1px solid #0066cc;
            border-radius: 5px;
            margin: 0 5px;
            font-size: 14px;
        }
        .pagination a:hover {
            background-color: #0066cc;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <a href="indexempresa.php" class="header-link">
                <img src="../imagenes/atras.png" alt="Logo Atras" class="header-logo">
            </a>
            <img src="../imagenes/logoremake.png" alt="Logo Multitrabajos" class="header-logo center-logo">
        </div>
    </header>

    <div class="container">
        <h1 class="my-4">Listado de Postulaciones</h1>

        <!-- Mensaje de confirmación -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de búsqueda -->
        <form method="get" class="mb-4">
            <div class="form-row align-items-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <input type="text" name="search" class="form-control" placeholder="Buscar..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <select name="gender" class="form-control">
                        <option value="">Género (Todos)</option>
                        <option value="Masculino" <?php echo $genderFilter == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                        <option value="Femenino" <?php echo $genderFilter == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <select name="order_by" class="form-control">
                        <option value="p.fecha_postulacion DESC" <?php echo $orderBy == 'p.fecha_postulacion DESC' ? 'selected' : ''; ?>>Más Reciente</option>
                        <option value="p.fecha_postulacion ASC" <?php echo $orderBy == 'p.fecha_postulacion ASC' ? 'selected' : ''; ?>>Más Antiguo</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="application-card">
                    <h4 class="job-title"><?php echo htmlspecialchars($row['titulo_cargo']); ?></h4>
                    <p class="application-date">Fecha de Postulación: <?php echo htmlspecialchars($row['fecha_postulacion']); ?></p>
                    <p class="user-name">Nombre: <?php echo htmlspecialchars($row['usuario_nombre']) . ' ' . htmlspecialchars($row['usuario_apellido']); ?></p>
                    <p class="user-email">Email: <?php echo htmlspecialchars($row['email']); ?></p>
                    <p class="user-gender">Género: <?php echo htmlspecialchars($row['genero']); ?></p>
                    <p class="user-education">Educación: <?php echo htmlspecialchars($row['education']); ?></p>
                    <p class="user-languages">Idiomas: <?php echo htmlspecialchars($row['languages']); ?></p>
                    <p class="user-experience">Experiencia: <?php echo htmlspecialchars($row['experience']); ?></p>
                    <p class="user-objective">Objetivo: <?php echo htmlspecialchars($row['objective']); ?></p>
                    <div class="text-right">
                        <a href="aceptar_postulacion.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-accept">Aceptar</a>
                        <a href="rechazar_postulacion.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-reject">Rechazar</a>
                        <a href="eliminar_postulacion.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-danger">Eliminar</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No se encontraron postulaciones.</p>
        <?php endif; ?>

        <!-- Paginación -->
        <div class="pagination">
            <!-- Agregar enlaces de paginación aquí -->
        </div>
    </div>

    <!-- Footer -->
    <p>&copy; 2024 JobTec. Todos los derechos reservados.</p>
    <p>Desarrollado por Anseb</p>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
