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

// Verificar si se ha pasado el ID de la postulación
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Consulta para eliminar la postulación
    $sql = "DELETE FROM postulaciones WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Redirigir a la página de vista de la aplicación con un mensaje de éxito
        header("Location: view-applications.php?message=" . urlencode("Postulación eliminada con éxito"));
    } else {
        // Redirigir a la página de vista de la aplicación con un mensaje de error
        header("Location: view-applications.php?message=" . urlencode("Error al eliminar la postulación"));
    }

    $stmt->close();
}

$conn->close();
?>
