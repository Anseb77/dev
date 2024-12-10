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

// Verificar si se ha proporcionado un ID de postulación
if (isset($_GET['id'])) {
    $postulacion_id = intval($_GET['id']);

    // Preparar la consulta para eliminar la postulación
    $sql = "DELETE FROM postulaciones WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $postulacion_id, $_SESSION['usuario_id']);
    
    if ($stmt->execute()) {
        // Redirigir de vuelta a la página de postulaciones con un mensaje de éxito
        header("Location: mis_postulaciones.php?mensaje=Postulación cancelada con éxito");
    } else {
        // Redirigir con un mensaje de error si la eliminación falla
        header("Location: mis_postulaciones.php?mensaje=Error al cancelar la postulación");
    }

    $stmt->close();
} else {
    // Redirigir si no se proporciona un ID válido
    header("Location: mis_postulaciones.php?mensaje=ID de postulación no válido");
}

$conn->close();
?>
