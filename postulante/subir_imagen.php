<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../usuario.php"); // Redirigir al login si no ha iniciado sesión
    exit();
}

// Obtener el ID del usuario desde la URL, si está presente
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : $_SESSION['usuario_id'];

// Datos de conexión a la base de datos
$host = 'localhost';  // Cambia si tu host es diferente
$dbname = 'multitrabajos'; // Nombre de la base de datos
$usuario = 'root'; // Usuario de la base de datos
$contrasena = ''; // Contraseña del usuario de la base de datos

// Crear el DSN para MySQL
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
    // Crear la conexión PDO
    $pdo = new PDO($dsn, $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verificar si se ha subido una imagen
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            // Obtener el contenido del archivo
            $foto = file_get_contents($_FILES['image']['tmp_name']);
            $fotoTipo = $_FILES['image']['type'];

            // Verificar que sea un tipo de archivo de imagen permitido
            $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($fotoTipo, $permitidos)) {
                echo json_encode(['success' => false, 'message' => 'Tipo de archivo no permitido.']);
                exit;
            }

            // Preparar la consulta SQL para actualizar la imagen del usuario
            $sql = "UPDATE usuarios SET foto = :foto WHERE id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':foto', $foto, PDO::PARAM_LOB);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            // Ejecutar la consulta y verificar si se actualizó correctamente
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Imagen subida con éxito.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar la imagen.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Por favor, selecciona una imagen para subir.']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
}
?>
