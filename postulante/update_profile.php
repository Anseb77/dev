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
$objective = $_POST['objective'];
$salaryPreference = $_POST['salaryPreference'];
$disability = $_POST['disability'];
$personalityTest = $_POST['personalityTest'];

// Preparar la consulta de actualización
$sql = "UPDATE usuarios SET 
            objective = ?, 
            salaryPreference = ?, 
            disability = ?, 
            personalityTestLink = ? 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $objective, $salaryPreference, $disability, $personalityTest, $user_id);

if ($stmt->execute()) {
    // Manejo de archivos
    $uploadDir = 'uploads/'; // Asegúrate de que este directorio exista y sea escribible

    // Manejo de CV
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == UPLOAD_ERR_OK) {
        $resumeTmpName = $_FILES['resume']['tmp_name'];
        $resumeName = basename($_FILES['resume']['name']);
        $resumePath = $uploadDir . $resumeName;
        move_uploaded_file($resumeTmpName, $resumePath);

        // Actualizar el nombre del archivo en la base de datos
        $stmt = $conn->prepare("UPDATE usuarios SET resume = ? WHERE id = ?");
        $stmt->bind_param("si", $resumeName, $user_id);
        $stmt->execute();
    }

    // Manejo de video
    if (isset($_FILES['video']) && $_FILES['video']['error'] == UPLOAD_ERR_OK) {
        $videoTmpName = $_FILES['video']['tmp_name'];
        $videoName = basename($_FILES['video']['name']);
        $videoPath = $uploadDir . $videoName;
        move_uploaded_file($videoTmpName, $videoPath);

        // Actualizar el nombre del archivo en la base de datos
        $stmt = $conn->prepare("UPDATE usuarios SET video = ? WHERE id = ?");
        $stmt->bind_param("si", $videoName, $user_id);
        $stmt->execute();
    }

    // Redirigir a la página de perfil con un mensaje de éxito
    header("Location: indexpostulante.php?user_id=" . $user_id . "&status=success");
} else {
    // Redirigir a la página de perfil con un mensaje de error
    header("Location: indexpostulante.php?user_id=" . $user_id . "&status=error");
}

$stmt->close();
$conn->close();
?>