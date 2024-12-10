<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multitrabajos</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.2.7/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://unpkg.com/flowbite@1.6.3/dist/flowbite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/lottie-web@5.7.14/build/player/lottie.min.js"></script>
    <style>
        /* Estilos para la pantalla de carga */
        .loading-screen {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.9)), url('https://th.bing.com/th/id/R.d939449a2f7daddfe36e82e87a8ff1cd?rik=YznYj9ccizWuBw&pid=ImgRaw&r=0') no-repeat center center fixed;
            background-size: cover;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-family: 'Roboto', sans-serif;
            overflow: hidden;
            transition: opacity 0.5s ease-out;
        }
        .loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .loading-screen .spinner {
            border: 8px solid rgba(255, 255, 255, 0.3);
            border-top-color: #00d084;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .loading-screen .loading-text {
            font-size: 1.75rem;
            color: #ffffff;
            margin-top: 1rem;
            font-weight: 700;
            text-shadow: 1px 1px 20px rgba(0, 0, 0, 0.8);
            animation: fadeIn 2s ease-in-out;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        .lottie-animation {
            width: 100px;
            height: 100px;
            margin-bottom: 1rem;
        }
        /* Estilos adicionales */
        .hidden {
            display: none;
        }
        
    </style>
</head>

<body>
    <!-- Pantalla de carga -->
    <div id="loading-screen" class="loading-screen">
        <div id="lottie-animation" class="lottie-animation"></div>
        <p class="loading-text neon animate__animated animate__fadeIn animate__delay-1s">Cargando tus oportunidades... Estás a punto de entrar en acción...</p>
        <div class="spinner"></div>
    </div>

    <!-- Contenido principal -->
    <div id="content" class="hidden transition-opacity duration-1000 ease-in-out">
        <header>
            <nav>
                <div class="logo">
                    <img src="imagenes/logoremake.png" alt="Imagen logo">
                </div>
                <div class="menu">
                    <a href="#">Buscar empresas</a>
                    <a href="#">Jóvenes profesionales</a>
                    <a href="#">Puestos ejecutivos</a>
                    <a href="#">Publicar gratis</a>
                    <a href="registrarU.php" class="btn create-account">Crear cuenta</a>
                    <a href="usuarios.php" class="btn login">Ingresar</a>
                </div>
            </nav>
        </header>

        <section class="hero">
            <div class="hero-text">
                <h2>Hay <span>4.291</span> trabajos esperándote en Ecuador</h2>
                <form>
                    <input type="text" placeholder="Puesto, empresa o palabra clave">
                    <select>
                        <option>Todo el país</option>
                    </select>
                    <button type="submit">Buscar empleo</button>
                </form>
            </div>
            <div class="hero-image">
                <img src="imagenes/postulateindex.png" alt="Postúlate">
            </div>
        </section>

        <section class="categories">
            <div class="category">
                <h3>Jóvenes profesionales</h3>
            </div>
            <div class="category">
                <h3>Puestos ejecutivos y directivos</h3>
            </div>
        </section>

        <section class="create-account-section">
            <div class="create-account-content">
                <h2>Créate una cuenta y encuentra el trabajo que buscas</h2>
                <ul>
                    <li><img src="imagenes/cohete.png" alt="Icono de cohete"> Ingresa en la opción Crear cuenta, escribe tus datos y confírmalos.</li>
                    <li><img src="imagenes/lapiz.png" alt="Icono de lápiz"> Completa la información principal de tu perfil a través de las preguntas por pasos que te haremos inmediatamente después de que te registres.</li>
                    <li><img src="imagenes/correcto.png" alt="Icono de checkbox"> Postúlate a los trabajos que más te interesen y sigue el proceso de tus postulaciones.</li>
                    <li><img src="imagenes/guardar.png" alt="Icono de diskette"> Recuerda mantener tu información actualizada desde la sección de tu perfil.</li>
                </ul>
                <div class="menu">
                    <a href="#" class="btn create-account">Crear cuenta</a>
                </div>
            </div>
            <div class="create-account-image">
                <img src="imagenes/imagenindex.png" alt="Imagen de perfil en móvil">
            </div>
        </section>

        <footer class="footer-section">
            <div class="footer-content">
                <p>&copy; 2024 JobTec. Todos los derechos reservados.</p>
                <p>Desarrollado por Anseb</p>
                <a href="admin.php" class="btn create-account">Administrador</a>
            </div>
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.js"></script>
    <script>
        // Inicializar AOS
        AOS.init();

        // Inicializar Lottie Animation
        var animation = bodymovin.loadAnimation({
            container: document.getElementById('lottie-animation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://assets9.lottiefiles.com/packages/lf20_HsMJ8s.json' // URL de la animación Lottie
        });

        // Mostrar el contenido principal después de la pantalla de carga
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                document.getElementById('loading-screen').classList.add('hidden');
                document.getElementById('content').classList.remove('hidden');
            }, 3000); // Cambia el tiempo para ajustar la duración de la pantalla de carga
        });
    </script>
</body>

</html>
