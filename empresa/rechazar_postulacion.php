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

// Obtener el ID de la postulación desde la URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Actualizar el estado de la postulación a 'Rechazada'
$sql = "UPDATE postulaciones SET estado = 'Rechazada' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<p>Postulación rechazada correctamente.</p>";
} else {
    echo "<p>Error al rechazar la postulación.</p>";
}

$stmt->close();
$conn->close();
?>

<a href="view-applications.php">Volver al listado de postulaciones</a>
