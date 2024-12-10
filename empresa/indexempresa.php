<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Empresa</title>
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos Generales */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            
            background-color: #f5f5f5;
            background-image: url('https://img.freepik.com/vector-gratis/gradiente-desenfoque-fondo-abstracto-azul-rosa_53876-117324.jpg?w=1060&t=st=1723673309~exp=1723673909~hmac=64a80d4be443ef6e532e0d1c770a23d83d05f2988a9090e06e8416d532f5a6f1'); /* Imagen de fondo */
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
        }

        .container {
            flex: 1;
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Encabezado */
        .header {
            background-color: #004A9F;
            color: white;
            padding: 10px 0;
        }

        .header h1 {
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .header nav ul {
            list-style: none;
            padding: 0;
            text-align: center;
        }

        .header nav ul li {
            display: inline;
            margin: 0 10px;
        }

        .header nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .header nav ul li a:hover {
            text-decoration: underline;
        }

        /* Contenido Principal */
        .main-content {
            padding: 20px;
        }

        .main-content h2 {
            color: #004A9F;
        }

        .dashboard {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .dashboard-item {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .dashboard-item:hover {
            background-color: #f0f0f0;
        }

        .dashboard-item h3 {
            margin-top: 0;
        }

        /* Pie de Página */
        .footer {
            background-color: #004A9F;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Panel de Empresa</h1>
            <nav>
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="indexempresa.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="ofertas.php">Crear Oferta de Trabajo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="ver_ofertas.php">Ver Ofertas Publicadas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../empresa.php">Cerrar sesión</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <h2 class="text-primary">Bienvenido al Panel de Control</h2>
        <p>Utiliza el menú de navegación para gestionar tus ofertas de trabajo.</p>
        <div class="dashboard">
        <a href="editardatosempresa.php" class="dashboard-item d-block p-3 text-decoration-none text-dark rounded shadow-sm">
                <h3 class="h5">Editar Información</h3>
                <p>Edita los datos acerca de la empresa.</p>
            </a>
            <a href="ofertas.php" class="dashboard-item d-block p-3 text-decoration-none text-dark rounded shadow-sm">
                <h3 class="h5">Crear Oferta de Trabajo</h3>
                <p>Publica una nueva oferta de trabajo para atraer candidatos.</p>
            </a>
            <a href="ver_ofertas.php" class="dashboard-item d-block p-3 text-decoration-none text-dark rounded shadow-sm">
                <h3 class="h5">Ver Ofertas Publicadas</h3>
                <p>Consulta y administra las ofertas de trabajo que has publicado.</p>
            </a>
            <a href="view-applications.php" class="dashboard-item d-block p-3 text-decoration-none text-dark rounded shadow-sm">
                <h3 class="h5">Postulaciones a Ofertas</h3>
                <p>Mira los postulantes a tus diferentes ofertas.</p>
            </a>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
        <p>&copy; 2024 JobTec. Todos los derechos reservados.</p>
        <p>Desarrollado por Anseb</p>
        </div>
    </footer>

    <!-- Enlace a Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
