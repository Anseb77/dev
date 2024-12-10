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

// Consulta para obtener los usuarios
$sql = "SELECT 
            id, nombre, apellido, tipo_documento, numero_documento, 
            CONCAT(telefono_codigo, telefono_numero) AS telefono, 
            email, nacionalidad, fecha_nacimiento, genero, estado_civil, direccion, 
            resume, video, bloqueado 
        FROM usuarios";
$result = $conn->query($sql);

// Consulta para obtener datos estadísticos por género
$gender_sql = "SELECT genero, COUNT(*) as count FROM usuarios GROUP BY genero";
$gender_result = $conn->query($gender_sql);

$gender_data = [];
$total_users = 0;
while ($row = $gender_result->fetch_assoc()) {
    $gender_data[] = $row;
    $total_users += $row['count'];
}

// Consulta para obtener el número de usuarios bloqueados y no bloqueados
$block_status_sql = "SELECT bloqueado, COUNT(*) as count FROM usuarios GROUP BY bloqueado";
$block_status_result = $conn->query($block_status_sql);

$block_status_data = [];
while ($row = $block_status_result->fetch_assoc()) {
    $block_status_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Usuarios</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
        }
        .container {
            margin-top: 30px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            margin-bottom: 30px;
            text-align: center;
        }
        .header h2 {
            color: #343a40;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .header p {
            font-size: 1.2rem;
            color: #6c757d;
        }
        .chart-section {
            margin-bottom: 40px;
        }
        .chart-title {
            margin-bottom: 20px;
            font-size: 1.6rem;
            color: #343a40;
            font-weight: bold;
            text-align: center;
        }
        .chart-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .table th, .table td {
            text-align: center;
        }
        .table thead {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }
        .table td a {
            color: #007bff;
        }
        .table td a:hover {
            text-decoration: underline;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6c757d;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .card-body {
            padding: 20px;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <div class="header">
            <h2>Reporte de Usuarios</h2>
            <p>Total de Usuarios: <?php echo number_format($total_users); ?></p>
        </div>

        <!-- Gráfico de pastel para distribución por género -->
        <div class="chart-section">
            <div class="card">
                <div class="card-header">
                    Distribución por Género
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de barras para estado de bloqueo -->
        <div class="chart-section">
            <div class="card">
                <div class="card-header">
                    Usuarios Bloqueados vs No Bloqueados
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="blockStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="table-responsive">
            <div class="card">
                <div class="card-header">
                    Lista de Usuarios
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Tipo de Documento</th>
                                <th>Número de Documento</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Nacionalidad</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Género</th>
                                <th>Bloqueado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tipo_documento']); ?></td>
                                    <td><?php echo htmlspecialchars($row['numero_documento']); ?></td>
                                    <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nacionalidad']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fecha_nacimiento']); ?></td>
                                    <td><?php echo htmlspecialchars($row['genero']); ?></td>
                                    <td><?php echo htmlspecialchars($row['bloqueado'] == 1 ? 'Sí' : 'No'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> Reporte de Usuarios</p>
        </div>
    </div>
    
    <script>
        // Datos para el gráfico de pastel (género)
        const genderData = <?php echo json_encode($gender_data); ?>;
        const labels = genderData.map(data => data.genero);
        const values = genderData.map(data => data.count);

        const ctxGender = document.getElementById('genderChart').getContext('2d');
        const genderChart = new Chart(ctxGender, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Distribución por Género',
                    data: values,
                    backgroundColor: ['blue', 'pink', '#FFCE56'],
                    borderColor: 'black',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return `${tooltipItem.label}: ${tooltipItem.raw}`;
                            }
                        }
                    }
                }
            }
        });

        // Datos para el gráfico de barras (estado de bloqueo)
        const blockStatusData = <?php echo json_encode($block_status_data); ?>;
        const blockLabels = blockStatusData.map(data => data.bloqueado === '1' ? 'Bloqueado' : 'No Bloqueado');
        const blockValues = blockStatusData.map(data => data.count);

        const ctxBlockStatus = document.getElementById('blockStatusChart').getContext('2d');
        const blockStatusChart = new Chart(ctxBlockStatus, {
            type: 'bar',
            data: {
                labels: blockLabels,
                datasets: [{
                    label: 'Usuarios Bloqueados vs No Bloqueados',
                    data: blockValues,
                    backgroundColor: ['green', 'red'],
                    borderColor: 'black',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return `${tooltipItem.label}: ${tooltipItem.raw}`;
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

<?php
$conn->close();
?>
