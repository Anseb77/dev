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

// Lógica para manejar la postulación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oferta_id = $_POST['oferta_id'];

    // Aquí agregarías la lógica para registrar la postulación en la base de datos
    // Ejemplo: insertar en una tabla de postulaciones

    // Mostrar mensaje de éxito y redirigir después de unos segundos
    echo "<!DOCTYPE html>";
    echo "<html lang='es'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Postulación Exitosa</title>";
    echo "<style>";
    echo "body { text-align: center; padding: 100px; font-family: Arial, sans-serif; }";
    echo ".success-message { font-size: 24px; margin-bottom: 20px; color: green; }";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    echo "<p class='success-message'>¡Tu postulación ha sido exitosa!</p>";
    echo "<p>Serás redirigido al inicio en breve...</p>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = 'indexpostulante.php'; }, 3000);";
    echo "</script>";
    echo "</body>";
    echo "</html>";
}

$conn->close();
?>
