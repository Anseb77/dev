<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../usuario.php"); // Redirigir al login si no ha iniciado sesión
    exit();
}

// Obtener el ID del usuario desde la URL, si está presente
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : $_SESSION['usuario_id'];

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

// Recuperar datos del usuario
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Comprobar si hay resultados
if ($result->num_rows > 0) {
    // Obtener los datos del usuario
    $user = $result->fetch_assoc();
} else {
    echo "No se encontraron datos.";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Usuario</title>
    <link rel="stylesheet" href="../css/perfil.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <style>
        /* Estilos previos... */
        .view-only p {
            margin: 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-group textarea {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-group input[type="file"] {
            border: none;
            padding: 0;
        }
        .separator {
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }
        .tabs button.active {
            background-color: #007bff;
            color: white;
        }
        .tab-content > div {
            display: none;
        }
        .tab-content > div.active {
            display: block;
        }
        .view-only p {
    margin: 0;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: rgba(0, 255, 0, 0.2); /* Verde transparente */
    color: #333; /* Asegúrate de que el texto sea legible */
}
.header-logo {
    width: 25px; /* Ajusta el ancho según tus necesidades */
    height: auto; /* Mantiene la proporción original de la imagen */
}

.center-logo {
    width: 100px; /* Tamaño más grande para el logo central */
    height: auto;
}


    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <a href="../usuarios.php" class="header-link">
                <img src="../imagenes/atras.png" alt="Logo Atras" class="header-logo">
            </a>
            <img src="../imagenes/logoremake.png" alt="Logo Multitrabajos" class="header-logo center-logo">
            <a href="bolsaempleo.php" class="header-link">BOLSA DE EMPLEO</a>
        </div>
    </header>

    <div class="container">
        <!-- Left Column: User Profile -->
        <div class="profile-section">
            <div class="profile-header">
                <img src="../imagenes/usuario (1).png" alt="Profile Picture" class="profile-pic">
                <a href="upload_image.php" class="btn btn-primary" onclick="toggleForm('editPersonalInfo')">Editar Imagen</a>
                <div class="profile-name"><?php echo htmlspecialchars($user['nombre']) . " " . htmlspecialchars($user['apellido']); ?></div>
                <br>
            </div>
            <a href="editPersonalInfo.php" class="btn btn-primary" onclick="toggleForm('editPersonalInfo')">Editar Datos Personales</a>
            <div class="profile-details">
                <h3>Datos personales</h3>
                <p><strong>Nacionalidad:</strong> <?php echo htmlspecialchars($user['nacionalidad']); ?></p>
                <p><strong>Fecha de nacimiento:</strong> <?php echo htmlspecialchars($user['fecha_nacimiento']); ?></p>
                <p><strong>Género:</strong> <?php echo htmlspecialchars($user['genero']); ?></p>
                <p><strong>Estado civil:</strong> <?php echo htmlspecialchars($user['estado_civil']); ?></p>
                <p><strong>Número de Documento:</strong> <?php echo htmlspecialchars($user['numero_documento']); ?></p>
                <div class="separator"></div>
            </div>
            <div class="contact-details">
                <h3>Datos de contacto</h3>
                <p><img src="../imagenes/celular.png"> <?php echo htmlspecialchars($user['telefono_codigo']) . " " . htmlspecialchars($user['telefono_numero']); ?></p>
                <p><img src="../imagenes/correo.png"> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><img src="../imagenes/direccion.png"> <?php echo htmlspecialchars($user['direccion']); ?></p>
                <a href="editContactInfo.php" class="btn btn-primary" onclick="toggleForm('editContactInfo')">Editar Datos de Contacto</a>
                <div class="separator"></div>
            </div>
        </div>

        <!-- Middle Column: User Information -->
        <div class="info-section">
            <div class="tabs">
                <button class="tab active" onclick="showTab('education')">Educación</button>
                <button class="tab" onclick="showTab('experience')">Experiencia</button>
                <button class="tab" onclick="showTab('profile')">Perfil</button>
            </div>
            <div class="tab-content">
                <div id="education" class="active">
                    <h3>Formación académica</h3>
                    <div class="form-group view-only">
                        <p><strong>Estudios:</strong> <?php echo htmlspecialchars($user['education']); ?></p>
                    </div>
                    
                    <form id="educationForm" action="update_education.php" method="post">
        <div class="form-group">
            <label for="education">Estudios:</label>
            <textarea id="education" name="education" rows="4"><?php echo htmlspecialchars($user['education']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>

                    </>
                    <br>
                    <h3>¿Qué idiomas hablas?</h3>
                    <div class="form-group view-only">
                        <p><strong>Idiomas:</strong> <?php echo htmlspecialchars($user['languages']); ?></p>
                    </div>
                    <form id="languageForm" action="update_language.php" method="post">
    <div class="form-group">
        <label for="languages">Idiomas:</label>
        <textarea id="languages" name="languages" rows="4"><?php echo htmlspecialchars($user['languages']); ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
</form>

                    <br>
                    <h3>Conocimientos y habilidades</h3>
                    <div class="form-group view-only">
                        <p><strong>Conocimientos y habilidades:</strong> <?php echo htmlspecialchars($user['skills']); ?></p>
                    </div>
                    <form id="skillsForm" action="update_skills.php" method="post">
    <div class="form-group">
        <label for="skills">Conocimientos y habilidades:</label>
        <textarea id="skills" name="skills" rows="4"><?php echo htmlspecialchars($user['skills']); ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
</form>

                </div>
                <div id="experience">
                    <h3>Datos Experiencia laboral</h3>
                    <div class="form-group view-only">
                        <p><strong>Experiencia laboral:</strong> <?php echo htmlspecialchars($user['experience']); ?></p>
                    </div>
                    <form id="experienceForm" action="update_experience.php" method="post">
    <div class="form-group">
        <label for="experience">Ingrese su Experiencia laboral:</label>
        <textarea id="experience" name="experience" rows="4"><?php echo htmlspecialchars($user['experience']); ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
</form>

                </div>
                <div id="profile">
                    <h3>Perfil</h3>
                    <div class="form-group view-only">
                        <p><strong>Objetivo Laboral:</strong> <?php echo htmlspecialchars($user['objective']); ?></p>
                    </div>
                    <form id="profileForm" action="update_profile.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="objective">Ingrese su Objetivo Laboral:</label>
                            <textarea id="objective" name="objective" rows="4"><?php echo htmlspecialchars($user['objective']); ?></textarea>
                        </div>
                        <div class="form-group view-only">
                            <p><strong>Preferencia Salarial:</strong> <?php echo htmlspecialchars($user['salaryPreference']); ?></p>
                        </div>
                        <div class="form-group">
    <label for="salaryPreference">Preferencia Salarial:</label>
    <input type="number" id="salaryPreference" name="salaryPreference" value="<?php echo htmlspecialchars($user['salaryPreference']); ?>" min="0" step="1" required class="form-control">
</div>

<div class="form-group">
    <label for="resume">Adjuntar CV (PDF):</label>
    <input type="file" id="resume" name="resume" accept=".pdf" onchange="validatePDF()">
    <?php if ($user['resume']) { ?>
        <p>Archivo actual: <?php echo htmlspecialchars($user['resume']); ?></p>
    <?php } ?>
</div>

<script>
    function validatePDF() {
        var fileInput = document.getElementById('resume');
        var filePath = fileInput.value;
        var allowedExtension = /(\.pdf)$/i;
        
        if (!allowedExtension.exec(filePath)) {
            alert('Por favor, selecciona solo archivos PDF.');
            fileInput.value = ''; // Limpiar el campo de archivo si no es PDF
            return false;
        }
    }
</script>


                        <div class="form-group view-only">
                            <p><strong>Discapacidad:</strong> <?php echo htmlspecialchars($user['disability']); ?></p>
                        </div>
                        <div class="form-group">
    <label for="disability">Discapacidad:</label>
    <select id="disability" name="disability" class="form-control" required>
        <option value="Sí" <?php echo ($user['disability'] == 'Sí') ? 'selected' : ''; ?>>Sí</option>
        <option value="No" <?php echo ($user['disability'] == 'No') ? 'selected' : ''; ?>>No</option>
    </select>
</div>

                        <!-- <div class="form-group view-only">
                            <p><strong>Test de personalidad:</strong> <a href="<?php echo htmlspecialchars($user['personalityTestLink']); ?>" target="_blank">Ver Test</a></p>
                        </div>
                        <div class="form-group">
                            <label for="personalityTest">Test de personalidad:</label>
                            <input type="text" id="personalityTest" name="personalityTest" value="<?php echo htmlspecialchars($user['personalityTestLink']); ?>" readonly>
                        </div> -->
                        <div class="form-group">
    <label for="video">Adjuntar video de presentación:</label>
    <input type="file" id="video" name="video" accept="video/*" onchange="validateVideo()">
    <?php if ($user['video']) { ?>
        <p>Archivo actual: <?php echo htmlspecialchars($user['video']); ?></p>
    <?php } ?>
</div>

<script>
    function validateVideo() {
        var fileInput = document.getElementById('video');
        var filePath = fileInput.value;
        var allowedExtensions = /(\.mp4|\.avi|\.mov|\.wmv|\.flv|\.mkv)$/i; // Extensiones de video más comunes
        
        if (!allowedExtensions.exec(filePath)) {
            alert('Por favor, selecciona solo archivos de video (mp4, avi, mov, wmv, flv, mkv).');
            fileInput.value = ''; // Limpiar el campo de archivo si no es un video
            return false;
        }
    }
</script>

                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Improve Profile -->
        <div class="improve-section">
            <h3>Mejora tu HV agregando:</h3>
            <p><img src="../imagenes/foto.png"> Foto</p>
            <p><img src="../imagenes/direccion.png"> Lugar de residencia</p>
            <p><img src="../imagenes/experiencia.png"> Experiencia laboral</p>
            <p><img src="../imagenes/dato.png"> Datos personales</p>
            <p><img src="../imagenes/estudios.png"> Estudios</p>
            <p><img src="../imagenes/objetivo.png"> Objetivo</p>
            <p><img src="../imagenes/salario.png"> Preferencia Salarial</p>
            <p><img src="../imagenes/idioma.png"> Idiomas</p>
            <p><img src="../imagenes/conocimientos.png"> Conocimientos y habilidades</p>
            <p><img src="../imagenes/Test.png"> Test de personalidad</p>
            <div class="separator"></div>
            <a href="generate_pdf.php" class="btn btn-primary">Descargar mi HV</a>



        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
        <p>&copy; 2024 JobTec. Todos los derechos reservados.</p>
        <p>Desarrollado por Anseb</p>
        </div>
    </footer>

    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab-content > div').forEach(function(div) {
                div.classList.remove('active');
            });
            document.querySelectorAll('.tabs button').forEach(function(button) {
                button.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
            document.querySelector('.tabs button[onclick="showTab(\'' + tabId + '\')"]').classList.add('active');
        }

        function toggleForm(formId) {
            var form = document.getElementById(formId);
            if (form) {
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            }
        }
    </script>
</body>
</html>
<?php
