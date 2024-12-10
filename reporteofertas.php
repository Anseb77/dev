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

// Consulta para obtener las ofertas de empleo
$sql = "SELECT 
            o.id, e.nombre_empresa, o.titulo_cargo, o.sueldo, o.tipo_contrato
        FROM ofertas o
        JOIN empresa e ON o.empresa_id = e.id";
$result = $conn->query($sql);

// Consulta para obtener la cantidad de ofertas por empresa
$company_sql = "SELECT e.nombre_empresa, COUNT(o.id) as cantidad_ofertas 
                FROM ofertas o
                JOIN empresa e ON o.empresa_id = e.id
                GROUP BY e.nombre_empresa";
$company_result = $conn->query($company_sql);

// Consulta para obtener datos estadísticos por tipo de contrato
$contract_sql = "SELECT tipo_contrato, COUNT(*) as count FROM ofertas GROUP BY tipo_contrato";
$contract_result = $conn->query($contract_sql);

// Consulta para obtener la cantidad de empresas
$company_count_sql = "SELECT COUNT(DISTINCT id) as cantidad_empresas FROM empresa";
$company_count_result = $conn->query($company_count_sql);
$company_count = $company_count_result->fetch_assoc()['cantidad_empresas'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ofertas de Empleo</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            padding-top: 20px;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background-color: #ffffff;
            padding: 20px;
        }
        .header {
            margin-bottom: 30px;
            text-align: center;
        }
        .header h2 {
            font-size: 2rem;
            color: #343a40;
            font-weight: 700;
        }
        .header p {
            font-size: 1.2rem;
            color: #6c757d;
        }
        .chart-container {
            margin-bottom: 30px;
            margin-top: 30px;
            padding: 20px;
            background-color: #e9ecef;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .chart-title {
            margin-bottom: 15px;
            font-size: 1.5rem;
            color: #343a40;
            font-weight: 600;
            text-align: center;
        }
        .table-responsive {
            margin-top: 30px;
            padding: 20px;
            background-color: #e9ecef;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .table thead {
            background-color: #007bff;
            color: white;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }
        .table td {
            vertical-align: middle;
        }
        .table th, .table td {
            text-align: center;
        }
        .table td a {
            color: #007bff;
        }
        .table td a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Reporte de Ofertas de Empleo</h2>
            <p>Cantidad de Empresas: <?php echo number_format($company_count); ?></p>
        </div>

        <!-- Gráfico de barras para cantidad de ofertas por empresa -->
        <div class="chart-container">
            <div class="chart-title">Cantidad de Ofertas por Empresa</div>
            <canvas id="companyChart"></canvas>
        </div>

        <!-- Gráfico de pastel para distribución por tipo de contrato -->
        <div class="chart-container">
            <div class="chart-title">Distribución por Tipo de Contrato</div>
            <canvas id="contractChart"></canvas>
        </div>

        <!-- Tabla de ofertas de empleo -->
        <div class="table-responsive">
            <h3 class="text-center mb-4">Detalles de Ofertas de Empleo</h3>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Empresa</th>
                        <th>Título del Cargo</th>
                        <th>Sueldo</th>
                        <th>Tipo de Contrato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_empresa']); ?></td>
                            <td><?php echo htmlspecialchars($row['titulo_cargo']); ?></td>
                            <td><?php echo htmlspecialchars($row['sueldo']); ?></td>
                            <td><?php echo htmlspecialchars($row['tipo_contrato']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        // Datos para el gráfico de barras (cantidad de ofertas por empresa)
        const companyData = <?php echo json_encode($company_result->fetch_all(MYSQLI_ASSOC)); ?>;
        const companyLabels = companyData.map(data => data.nombre_empresa);
        const companyValues = companyData.map(data => data.cantidad_ofertas);

        const ctxCompany = document.getElementById('companyChart').getContext('2d');
        const companyChart = new Chart(ctxCompany, {
            type: 'bar',
            data: {
                labels: companyLabels,
                datasets: [{
                    label: 'Cantidad de Ofertas por Empresa',
                    data: companyValues,
                    backgroundColor: '#007bff',
                    borderColor: 'black',
                    borderWidth: 1
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
                        ticks: {
                            autoSkip: false,
                            maxRotation: 90
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value;
                            }
                        }
                    }
                }
            }
        });

        // Datos para el gráfico de pastel (distribución por tipo de contrato)
        const contractData = <?php echo json_encode($contract_result->fetch_all(MYSQLI_ASSOC)); ?>;
        const contractLabels = contractData.map(data => data.tipo_contrato);
        const contractValues = contractData.map(data => data.count);

        const ctxContract = document.getElementById('contractChart').getContext('2d');
        const contractChart = new Chart(ctxContract, {
            type: 'pie',
            data: {
                labels: contractLabels,
                datasets: [{
                    label: 'Distribución por Tipo de Contrato',
                    data: contractValues,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
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
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
