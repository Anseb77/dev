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

// Obtener los datos del formulario
$experience = $_POST['experience'];

// Preparar la consulta de actualización
$sql = "UPDATE usuarios SET experience = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $experience, $user_id);

if ($stmt->execute()) {
    // Redirigir a la página de perfil con un mensaje de éxito
    header("Location: indexpostulante.php"); // Cambia a la página de destino deseada
} else {
    // Redirigir a la página de perfil con un mensaje de error
    header("Location: pagina_de_error.php"); // Cambia a la página de destino deseada
}

$stmt->close();
$conn->close();
?>
