<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
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

// Obtener postulaciones del usuario
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT p.id, o.titulo_cargo AS titulo, e.nombre_empresa AS empresa, p.fecha_postulacion, p.estado
        FROM postulaciones p
        JOIN ofertas o ON p.oferta_id = o.id
        JOIN empresa e ON o.empresa_id = e.id
        WHERE p.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Mis Postulaciones</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
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
        .container {
            max-width: 1200px;
        }
        .table th, .table td {
            text-align: center;
        }
        .status-pending {
            color: orange;
        }
        .status-accepted {
            color: green;
        }
        .status-rejected {
            color: red;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <a href="bolsaempleo.php" class="header-link">
                <img src="../imagenes/atras.png" alt="Logo Atras" class="header-logo">
            </a>
            <img src="../imagenes/logoremake.png" alt="Logo Multitrabajos" class="header-logo center-logo">
            <a href="mis_postulaciones.php" class="btn btn-light"></a>
        </div>
    </header>

    <div class="container">
        <h1 class="my-4">Estado de Mis Postulaciones</h1>
        
        <!-- Mensaje de éxito o error -->
        <?php
        if (isset($_GET['mensaje'])) {
            echo '<div class="alert alert-info">' . htmlspecialchars($_GET['mensaje']) . '</div>';
        }
        ?>
        
        <!-- Tabla de postulaciones -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Título del Trabajo</th>
                    <th>Empresa</th>
                    <th>Fecha de Postulación</th>
                    <th>Estado</th>
                    <th>Acción</th> <!-- Nueva columna para el botón -->
                </tr>
            </thead>
            <tbody>
                <?php
                while ($postulacion = $result->fetch_assoc()) {
                    $estadoClass = '';
                    switch ($postulacion['estado']) {
                        case 'Pendiente':
                            $estadoClass = 'status-pending';
                            break;
                        case 'Aceptada':
                            $estadoClass = 'status-accepted';
                            break;
                        case 'Rechazada':
                            $estadoClass = 'status-rejected';
                            break;
                    }
                    echo "<tr>
                            <td>{$postulacion['titulo']}</td>
                            <td>{$postulacion['empresa']}</td>
                            <td>{$postulacion['fecha_postulacion']}</td>
                            <td class='{$estadoClass}'>{$postulacion['estado']}</td>
                            <td><a href='cancelar_postulacion.php?id={$postulacion['id']}' class='btn btn-danger'>Cancelar</a></td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
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

<?php
$stmt->close();
$conn->close();
?>
