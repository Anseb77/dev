<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Subida</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
            margin: 0;
        }
        .header {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 15px 0;
        }
        .header img {
            max-width: 100%;
            height: auto;
        }
        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
        }
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 500px;
            width: 100%;
        }
        .alert {
            margin-top: 20px;
        }
        .redirecting {
            margin-top: 20px;
            font-size: 1.2rem;
            color: #6c757d;
        }
        .spinner-border {
            margin-top: 20px;
        }
        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <header class="header">
        <img src="../imagenes/logoremake.png" alt="Logo de la Aplicación">
    </header>

    <div class="content">
        <div class="form-container">
            <h2 class="mb-4 text-center">Subir Imagen de Perfil</h2>
            <form id="uploadForm">
                <div class="form-group">
                    <label for="image">Selecciona una imagen:</label>
                    <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
                </div>
                <button type="button" class="btn btn-primary btn-block" onclick="submitForm()">Subir Imagen</button>
            </form>
            <div id="feedback"></div> <!-- Contenedor para mensajes de feedback -->
        </div>
    </div>

    <footer>
        <p>&copy; 2024 JobTec. Todos los derechos reservados.</p>
        <p>Desarrollado por Anseb</p>
    </footer>

    <script>
        function submitForm() {
            const form = document.getElementById('uploadForm');
            const formData = new FormData(form);
            
            // Limpiar cualquier mensaje previo
            document.getElementById('feedback').innerHTML = '';
            
            fetch('subir_imagen.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    document.getElementById('feedback').innerHTML = `
                        <div class="alert alert-success" role="alert">
                            Imagen subida con éxito.
                        </div>
                        <p class="redirecting">Redirigiendo en breve...</p>
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                    `;
                    
                    // Redirigir después de 2 segundos
                    setTimeout(function() {
                        window.location.href = 'indexpostulante.php';
                    }, 2000);
                } else {
                    // Mostrar mensaje de error
                    document.getElementById('feedback').innerHTML = `
                        <div class="alert alert-danger" role="alert">
                            Error al subir la imagen: ${data.message}
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('feedback').innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        Error de red o del servidor: ${error.message}
                    </div>
                `;
            });
        }
    </script>

    <!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
