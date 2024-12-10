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

// Verificar si el usuario está autenticado

$usuario_id = $_SESSION['usuario_id'];
$oferta_id = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;

// Verificar si la oferta existe
$sql = "SELECT id FROM ofertas WHERE id = $oferta_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Inserción en la tabla de postulaciones
    $stmt = $conn->prepare("INSERT INTO postulaciones (usuario_id, oferta_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $usuario_id, $oferta_id);

    if ($stmt->execute()) {
        echo "<script>alert('Postulación realizada con éxito.'); window.location.href = 'bolsaempleo.php';</script>";
    } else {
        echo "<script>alert('Error al realizar la postulación.'); window.location.href = 'bolsaempleo.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('La oferta no existe.'); window.location.href = 'bolsaempleo.php';</script>";
}

$conn->close();
?>
