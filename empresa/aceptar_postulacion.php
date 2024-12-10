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

// Actualizar el estado de la postulación a 'Aceptada'
$sql = "UPDATE postulaciones SET estado = 'Aceptada' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<p>Postulación aceptada correctamente.</p>";
} else {
    echo "<p>Error al aceptar la postulación.</p>";
}

$stmt->close();
$conn->close();
?>

<a href="indexempresa.php">Volver al Menu</a>
