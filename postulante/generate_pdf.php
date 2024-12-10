<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../usuario.php"); // Redirigir al login si no ha iniciado sesión
    exit();
}

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['usuario_id'];

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "multitrabajos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recuperar datos del usuario
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Comprobar si hay resultados
if ($result->num_rows > 0) {
    // Obtener los datos del usuario
    $user = $result->fetch_assoc();
} else {
    echo "No se encontraron datos.";
    exit();
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoja de Vida</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        h1, h2 {
            color: #333;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .section p {
            margin: 5px 0;
        }
        .header img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
</head>
<body>
    <div class="container" id="resumeContent">
        <div class="header">
            <img src="../imagenes/usuario (1).png" alt="Profile Picture">
            <h1><?php echo htmlspecialchars($user['nombre']) . " " . htmlspecialchars($user['apellido']); ?></h1>
        </div>
        <div class="section">
            <h2>Información Personal</h2>
            <p><strong>Nacionalidad:</strong> <?php echo htmlspecialchars($user['nacionalidad']); ?></p>
            <p><strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars($user['fecha_nacimiento']); ?></p>
            <p><strong>Género:</strong> <?php echo htmlspecialchars($user['genero']); ?></p>
            <p><strong>Estado Civil:</strong> <?php echo htmlspecialchars($user['estado_civil']); ?></p>
            <p><strong>Número de Documento:</strong> <?php echo htmlspecialchars($user['numero_documento']); ?></p>
        </div>
        <div class="section">
            <h2>Datos de Contacto</h2>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($user['telefono_codigo']) . " " . htmlspecialchars($user['telefono_numero']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Dirección:</strong> <?php echo htmlspecialchars($user['direccion']); ?></p>
        </div>
        <div class="section">
            <h2>Formación Académica</h2>
            <p><strong>Estudios:</strong> <?php echo htmlspecialchars($user['education']); ?></p>
        </div>
        <div class="section">
            <h2>Experiencia Laboral</h2>
            <p><strong>Experiencia:</strong> <?php echo htmlspecialchars($user['experience']); ?></p>
        </div>
        <div class="section">
            <h2>Perfil Profesional</h2>
            <p><strong>Objetivo Laboral:</strong> <?php echo htmlspecialchars($user['objective']); ?></p>
            <p><strong>Preferencia Salarial:</strong> <?php echo htmlspecialchars($user['salaryPreference']); ?></p>
            <p><strong>Discapacidad:</strong> <?php echo htmlspecialchars($user['disability']); ?></p>
            <!-- <p><strong>Test de Personalidad:</strong> <a href="<?php echo htmlspecialchars($user['personalityTestLink']); ?>" target="_blank">Ver Test</a></p> -->
        </div>
        <div class="section">
            <h2>Archivos Adjuntos</h2>
            <?php if ($user['resume']) { ?><p><strong>CV:</strong> <?php echo htmlspecialchars($user['resume']); ?></p><?php } ?>
            <?php if ($user['video']) { ?><p><strong>Video de Presentación:</strong> <?php echo htmlspecialchars($user['video']); ?></p><?php } ?>
        </div>
    </div>

    <button id="downloadPDF" class="btn">Descargar mi HV en PDF</button>
    <a href="indexpostulante.php" class="btn btn-primary">Cancelar</a>


    <script>
        document.getElementById('downloadPDF').addEventListener('click', function() {
            const element = document.getElementById('resumeContent');
            html2pdf().from(element).save('hoja_de_vida.pdf');
        });
    </script>
</body>
</html>
