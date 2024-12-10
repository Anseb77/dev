<?php
// Configuración de la conexión a la base de datos
$host = 'localhost';
$db = 'multitrabajos';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener las postulaciones
$sql = "SELECT 
            p.id, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario, 
            o.titulo_cargo, p.fecha_postulacion, p.estado 
        FROM postulaciones p
        JOIN usuarios u ON p.usuario_id = u.id
        JOIN ofertas o ON p.oferta_id = o.id";
$result = $conn->query($sql);

// Consulta para obtener conteo de postulaciones por oferta
$sql_postulaciones_por_oferta = "SELECT o.titulo_cargo, COUNT(*) AS cantidad 
                                 FROM postulaciones p
                                 JOIN ofertas o ON p.oferta_id = o.id
                                 GROUP BY o.titulo_cargo";
$result_postulaciones_por_oferta = $conn->query($sql_postulaciones_por_oferta);

// Consulta para obtener conteo de postulaciones aceptadas y rechazadas
$sql_estado = "SELECT estado, COUNT(*) AS cantidad 
               FROM postulaciones 
               GROUP BY estado";
$result_estado = $conn->query($sql_estado);

// Prepara datos para los gráficos
$estado_labels = [];
$estado_data = [];
while ($row_estado = $result_estado->fetch_assoc()) {
    $estado_labels[] = $row_estado['estado'];
    $estado_data[] = $row_estado['cantidad'];
}

$postulaciones_por_oferta_labels = [];
$postulaciones_por_oferta_data = [];
while ($row_postulaciones_por_oferta = $result_postulaciones_por_oferta->fetch_assoc()) {
    $postulaciones_por_oferta_labels[] = $row_postulaciones_por_oferta['titulo_cargo'];
    $postulaciones_por_oferta_data[] = $row_postulaciones_por_oferta['cantidad'];
}

// Contar el total de postulaciones
$total_postulaciones = $result->num_rows;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Postulaciones</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
        }
        .header {
            margin-bottom: 20px;
            text-align: center;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
        }
        .card-body {
            padding: 20px;
        }
        .table thead th {
            background-color: #007bff;
            color: #fff;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }
        .table td, .table th {
            text-align: center;
            vertical-align: middle;
        }
        .table td a {
            color: #007bff;
        }
        .table td a:hover {
            text-decoration: underline;
        }
        .chart-container {
            margin-top: 30px;
        }
        .chart-container canvas {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 class="text-primary">Reporte de Postulaciones</h2>
        </div>
        
        <!-- Estadísticas Generales -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Estadísticas Generales</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="alert alert-info text-center" role="alert">
                            <h4>Total de Postulaciones:</h4>
                            <h2 class="font-weight-bold"><?php echo $total_postulaciones; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Postulaciones -->
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Detalle de Postulaciones</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Usuario</th>
                                <th>Apellido del Usuario</th>
                                <th>Título del Cargo</th>
                                <th>Fecha de Postulación</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre_usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($row['apellido_usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($row['titulo_cargo']); ?></td>
                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($row['fecha_postulacion']))); ?></td>
                                    <td>
                                        <?php 
                                        // Resaltar estado
                                        switch ($row['estado']) {
                                            case 'Aceptada':
                                                echo '<span class="badge badge-success">Aceptada</span>';
                                                break;
                                            case 'Rechazada':
                                                echo '<span class="badge badge-danger">Rechazada</span>';
                                                break;
                                            case 'Pendiente':
                                                echo '<span class="badge badge-warning">Pendiente</span>';
                                                break;
                                            default:
                                                echo htmlspecialchars($row['estado']);
                                                break;
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="chart-container">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Postulaciones por Estado</h3>
                </div>
                <div class="card-body">
                    <canvas id="estadoChart"></canvas>
                </div>
            </div>
        </div>
        <div class="chart-container mt-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Postulaciones por Oferta de Empleo</h3>
                </div>
                <div class="card-body">
                    <canvas id="ofertaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de postulaciones por estado
        var ctxEstado = document.getElementById('estadoChart').getContext('2d');
        var estadoChart = new Chart(ctxEstado, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($estado_labels); ?>,
                datasets: [{
                    label: 'Cantidad de Postulaciones por Estado',
                    data: <?php echo json_encode($estado_data); ?>,
                    backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
                    borderColor: 'black',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });

        // Gráfico de postulaciones por oferta de empleo
        var ctxOferta = document.getElementById('ofertaChart').getContext('2d');
        var ofertaChart = new Chart(ctxOferta, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($postulaciones_por_oferta_labels); ?>,
                datasets: [{
                    label: 'Cantidad de Postulaciones por Oferta',
                    data: <?php echo json_encode($postulaciones_por_oferta_data); ?>,
                    backgroundColor: '#007bff',
                    borderColor: 'black',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
