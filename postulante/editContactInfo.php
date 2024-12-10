<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../usuario.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

$host = 'localhost';
$dbname = 'multitrabajos';
$user = 'root'; // Cambia según tu configuración
$pass = ''; // Cambia según tu configuración

$mensaje = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consultar los datos actuales del usuario
    $sql = "SELECT telefono_codigo, telefono_numero, email, direccion FROM usuarios WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $telefono_codigo = $_POST['telefono_codigo'];
        $telefono_numero = $_POST['telefono_numero'];
        $email = $_POST['email'];
        $direccion = $_POST['direccion'];
        $nueva_contrasena = $_POST['nueva_contrasena'];

        // Validar el número de teléfono (solo 10 dígitos)
        if (!preg_match('/^\d{9}$/', $telefono_numero)) {
            $mensaje = "El número de teléfono debe contener exactamente 9 dígitos.";
        }
        // Validar el correo electrónico (debe contener '@' y '.com')
        elseif (!preg_match('/^[^@]+@[^@]+\.com$/', $email)) {
            $mensaje = "El correo electrónico debe ser válido y terminar en .com.";
        }
        // Validar la nueva contraseña si es que se desea cambiar
        elseif (!empty($nueva_contrasena) && strlen($nueva_contrasena) < 8) {
            $mensaje = "La nueva contraseña debe tener al menos 8 caracteres.";
        } else {
            // Iniciar la transacción
            $pdo->beginTransaction();

            // Actualizar los datos de contacto
            $sql = "UPDATE usuarios SET telefono_codigo = ?, telefono_numero = ?, email = ?, direccion = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$telefono_codigo, $telefono_numero, $email, $direccion, $usuario_id]);

            // Actualizar la contraseña solo si se ha introducido una nueva
            if (!empty($nueva_contrasena)) {
                $hash_contrasena = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios SET contrasena = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$hash_contrasena, $usuario_id]);
            }

            // Confirmar la transacción
            $pdo->commit();

            $mensaje = "Datos guardados con éxito.";
        }
    }
} catch (PDOException $e) {
    // Revertir la transacción si hay un error
    $pdo->rollBack();
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Datos de Contacto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Datos de Contacto</h2>
        <?php if ($mensaje): ?>
    <div class="alert alert-success" role="alert">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
<?php endif; ?>

        <form action="editContactInfo.php" method="post">
            <div class="form-group">
                <label for="telefono_codigo">Código de Teléfono:</label>
                <select id="telefono_codigo" name="telefono_codigo" required class="form-control">
                    <option value="+593" <?php echo ($usuario['telefono_codigo'] == '+593') ? 'selected' : ''; ?>>+593 (Ecuador)</option>
                    <option value="+1" <?php echo ($usuario['telefono_codigo'] == '+1') ? 'selected' : ''; ?>>+1 (Estados Unidos)</option>
                    <option value="+34" <?php echo ($usuario['telefono_codigo'] == '+34') ? 'selected' : ''; ?>>+34 (España)</option>
                    <option value="+44" <?php echo ($usuario['telefono_codigo'] == '+44') ? 'selected' : ''; ?>>+44 (Reino Unido)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="telefono_numero">Número de Teléfono:</label>
                <input type="text" id="telefono_numero" name="telefono_numero" value="<?php echo htmlspecialchars($usuario['telefono_numero']); ?>" required class="form-control" pattern="\d{9}" title="El número debe tener 9 dígitos.">
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required class="form-control" pattern="^[^@]+@[^@]+\.com$" title="El correo electrónico debe incluir @ y terminar en .com">
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label for="nueva_contrasena">Nueva Contraseña (opcional):</label>
                <input type="password" id="nueva_contrasena" name="nueva_contrasena" class="form-control" placeholder="Deja en blanco si no deseas cambiarla" minlength="8" title="La contraseña debe tener al menos 8 caracteres">
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="indexpostulante.php" class="btn btn-secondary">Regresar</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
