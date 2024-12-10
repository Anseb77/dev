<?php
// Inicia la sesión
session_start();

// Verifica si el usuario ha iniciado sesión y tiene un ID de empresa
if (!isset($_SESSION['user_id'])) {
    die("Error: Usuario no autenticado.");
}

$empresa_id = $_SESSION['user_id'];  // ID de la empresa basado en el usuario logueado

// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "multitrabajos");

// Verifica la conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Solo procesa el formulario si se ha enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepara la consulta de inserción
    $stmt = $mysqli->prepare("INSERT INTO ofertas (
        empresa_id, 
        titulo_cargo, 
        area, 
        descripcion_funciones, 
        pais, 
        ciudad, 
        provincia, 
        jornada_laboral, 
        tipo_contrato, 
        sueldo, 
        fecha_contratacion, 
        cantidad_vacantes, 
        requisitos_contrato, 
        anos_experiencia, 
        edad, 
        estudios_minimos, 
        idiomas, 
        destrezas_conocimientos, 
        tipo_licencia_conducir, 
        disponibilidad_viajar, 
        disponibilidad_cambio_residencia, 
        discapacidad
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Asigna valores a las variables desde $_POST
    $titulo_cargo = $_POST['titulo_cargo'] ?? '';
    $area = $_POST['area'] ?? '';
    $descripcion_funciones = $_POST['descripcion_funciones'] ?? '';
    $pais = $_POST['pais'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';
    $provincia = $_POST['provincia'] ?? '';
    $jornada_laboral = $_POST['jornada_laboral'] ?? '';
    $tipo_contrato = $_POST['tipo_contrato'] ?? '';
    $sueldo = $_POST['sueldo'] ?? 0;
    $fecha_contratacion = date('Y-m-d'); // Fecha actual
    $cantidad_vacantes = $_POST['cantidad_vacantes'] ?? 0;
    $requisitos_contrato = $_POST['requisitos_contrato'] ?? '';
    $anos_experiencia = $_POST['anos_experiencia'] ?? 0;
    $edad = $_POST['edad'] ?? 0;
    $estudios_minimos = $_POST['estudios_minimos'] ?? '';
    $idiomas = $_POST['idiomas'] ?? '';
    $destrezas_conocimientos = $_POST['destrezas_conocimientos'] ?? '';
    $tipo_licencia_conducir = $_POST['tipo_licencia_conducir'] ?? '';
    $disponibilidad_viajar = isset($_POST['disponibilidad_viajar']) ? 1 : 0;
    $disponibilidad_cambio_residencia = isset($_POST['disponibilidad_cambio_residencia']) ? 1 : 0;
    $discapacidad = isset($_POST['discapacidad']) ? 1 : 0;

    // Asigna los valores a la consulta preparada
    $stmt->bind_param(
        "issssssssdsississssiii", 
        $empresa_id, 
        $titulo_cargo, 
        $area, 
        $descripcion_funciones, 
        $pais, 
        $ciudad, 
        $provincia, 
        $jornada_laboral, 
        $tipo_contrato, 
        $sueldo, 
        $fecha_contratacion, 
        $cantidad_vacantes, 
        $requisitos_contrato, 
        $anos_experiencia, 
        $edad, 
        $estudios_minimos, 
        $idiomas, 
        $destrezas_conocimientos, 
        $tipo_licencia_conducir, 
        $disponibilidad_viajar, 
        $disponibilidad_cambio_residencia, 
        $discapacidad
    );

    // Ejecuta la consulta
    if ($stmt->execute()) {
        echo "Oferta creada exitosamente.";
    } else {
        echo "Error al crear la oferta: " . $stmt->error;
    }

    // Cierra la consulta y la conexión
    $stmt->close();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Oferta de Trabajo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            background-image: url('https://img.freepik.com/vector-gratis/gradiente-desenfoque-fondo-abstracto-azul-rosa_53876-117324.jpg?w=1060&t=st=1723673309~exp=1723673909~hmac=64a80d4be443ef6e532e0d1c770a23d83d05f2988a9090e06e8416d532f5a6f1');
            background-size: cover;
            background-position: center;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-link {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .header-logo {
            height: 40px;
        }

        .center-logo {
            margin: 0 auto;
            height: 50px;
        }

        .footer-section {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            margin-top: auto;
        }

        .footer-content p {
            margin: 5px 0;
        }

        .form-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin: 20px auto;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 35px;
        }

        label {
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            background-color: #f9f9f9;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
            grid-column: span 2;
        }

        input[type="submit"] {
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            grid-column: span 2;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            grid-column: span 2;
        }

        .checkbox-group label {
            margin-left: 10px;
            font-weight: normal;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
            }

            form {
                grid-template-columns: 1fr;
            }

            .header-logo,
            .center-logo {
                height: 35px;
            }
        }
    </style>
    <script>
        // Provincias de Ecuador y sus respectivas ciudades
        const provinciasCiudades = {
            "Azuay": ["Cuenca", "Girón", "Gualaceo"],
            "Bolívar": ["Guaranda", "San Miguel", "Chillanes"],
            "Cañar": ["Azogues", "Biblián", "La Troncal"],
            "Carchi": ["Tulcán", "San Gabriel", "El Ángel"],
            "Chimborazo": ["Riobamba", "Alausí", "Colta"],
            "Cotopaxi": ["Latacunga", "Pujilí", "Salcedo"],
            "El Oro": ["Machala", "Pasaje", "Santa Rosa"],
            "Esmeraldas": ["Esmeraldas", "Atacames", "Quinindé"],
            "Galápagos": ["Puerto Ayora", "Puerto Baquerizo Moreno"],
            "Guayas": ["Guayaquil", "Durán", "Samborondón"],
            "Imbabura": ["Ibarra", "Otavalo", "Cotacachi"],
            "Loja": ["Loja", "Catamayo", "Macará"],
            "Los Ríos": ["Babahoyo", "Quevedo", "Vinces"],
            "Manabí": ["Portoviejo", "Manta", "Bahía de Caráquez"],
            "Morona Santiago": ["Macas", "Sucúa", "Gualaquiza"],
            "Napo": ["Tena", "Archidona", "El Chaco"],
            "Orellana": ["Coca", "Joya de los Sachas"],
            "Pastaza": ["Puyo", "Mera", "Santa Clara"],
            "Pichincha": ["Quito", "Cayambe", "Rumiñahui"],
            "Santa Elena": ["La Libertad", "Salinas", "Santa Elena"],
            "Santo Domingo de los Tsáchilas": ["Santo Domingo"],
            "Sucumbíos": ["Nueva Loja", "Shushufindi"],
            "Tungurahua": ["Ambato", "Baños", "Cevallos"],
            "Zamora Chinchipe": ["Zamora", "Yantzaza", "Zumbi"]
        };

        // Actualizar el campo de ciudades cuando se seleccione una provincia
        document.addEventListener('DOMContentLoaded', function () {
            const provinciaSelect = document.getElementById('provincia');
            const ciudadSelect = document.getElementById('ciudad');

            provinciaSelect.addEventListener('change', function () {
                const ciudades = provinciasCiudades[provinciaSelect.value] || [];
                ciudadSelect.innerHTML = ciudades.map(ciudad => `<option value="${ciudad}">${ciudad}</option>`).join('');
            });
        });
    </script>
</head>
<body>
<header>
    <a href="indexempresa.php" class="header-link">
        <img src="../imagenes/atras.png" alt="Logo Atras" class="header-logo">
    </a>
    <img src="../imagenes/logoremake.png" alt="Logo Multitrabajos" class="header-logo center-logo">
</header>

    <div class="form-container">
        <h2>Crear Oferta de Trabajo</h2>
        <form method="POST">
            <div class="form-group">
                <label for="titulo_cargo">Título del Cargo</label>
                <input type="text" id="titulo_cargo" name="titulo_cargo" required>
            </div>

            <div class="form-group">
                <label for="area">Área</label>
                <select id="area" name="area" required>
                    <option value="">Seleccione un área</option>
                    <option value="Tecnología">Tecnología</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Recursos Humanos">Recursos Humanos</option>
                    <option value="Administración">Administración</option>
                    <option value="Finanzas">Finanzas</option>
                    <option value="Ventas">Ventas</option>
                    <option value="Logística">Logística</option>
                </select>
            </div>

            <div class="form-group">
                <label for="descripcion_funciones">Descripción de Funciones</label>
                <textarea id="descripcion_funciones" name="descripcion_funciones" required></textarea>
            </div>
            <div class="form-group">
    <label for="pais">País</label>
    <select id="pais" name="pais" required readonly>
        <option value="Ecuador" selected>Ecuador</option>
    </select>
</div>

            <div class="form-group">
                <label for="provincia">Provincia</label>
                <select id="provincia" name="provincia" required>
                    <option value="">Seleccione una provincia</option>
                    <option value="Azuay">Azuay</option>
                    <option value="Bolívar">Bolívar</option>
                    <option value="Cañar">Cañar</option>
                    <option value="Carchi">Carchi</option>
                    <option value="Chimborazo">Chimborazo</option>
                    <option value="Cotopaxi">Cotopaxi</option>
                    <option value="El Oro">El Oro</option>
                    <option value="Esmeraldas">Esmeraldas</option>
                    <option value="Galápagos">Galápagos</option>
                    <option value="Guayas">Guayas</option>
                    <option value="Imbabura">Imbabura</option>
                    <option value="Loja">Loja</option>
                    <option value="Los Ríos">Los Ríos</option>
                    <option value="Manabí">Manabí</option>
                    <option value="Morona Santiago">Morona Santiago</option>
                    <option value="Napo">Napo</option>
                    <option value="Orellana">Orellana</option>
                    <option value="Pastaza">Pastaza</option>
                    <option value="Pichincha">Pichincha</option>
                    <option value="Santa Elena">Santa Elena</option>
                    <option value="Santo Domingo de los Tsáchilas">Santo Domingo de los Tsáchilas</option>
                    <option value="Sucumbíos">Sucumbíos</option>
                    <option value="Tungurahua">Tungurahua</option>
                    <option value="Zamora Chinchipe">Zamora Chinchipe</option>
                </select>
            </div>

            <div class="form-group">
                <label for="ciudad">Ciudad</label>
                <select id="ciudad" name="ciudad" required>
                    <option value="">Seleccione una ciudad</option>
                </select>
            </div>
            
            <div class="form-group">
        <label for="jornada_laboral">Jornada laboral:</label>
        <select id="jornada_laboral" name="jornada_laboral" required>
            <option value="">Seleccione una opción</option>
            <option value="tiempo_completo">Tiempo completo</option>
            <option value="medio_tiempo">Medio tiempo</option>
            <option value="freelance">Freelance</option>
            <option value="temporal">Temporal</option>
            <option value="por_horas">Por horas</option>
        </select>
    </div>

    <!-- Campo para el tipo de contrato -->
    <div class="form-group">
        <label for="tipo_contrato">Tipo de contrato:</label>
        <select id="tipo_contrato" name="tipo_contrato" required>
            <option value="">Seleccione una opción</option>
            <option value="indefinido">Indefinido</option>
            <option value="temporal">Temporal</option>
            <option value="por_proyecto">Por proyecto</option>
            <option value="practicas">Prácticas</option>
        </select>
    </div>

    <!-- Campo para destrezas -->
    <div class="form-group">
        <label for="destrezas">Destrezas:</label>
        <textarea id="destrezas" name="destrezas" rows="3" placeholder="Escriba las destrezas requeridas..." required></textarea>
    </div>

            <div class="form-group">
    <label for="sueldo">Sueldo (en dólares)</label>
    <input type="number" id="sueldo" name="sueldo" step="0.01" placeholder="Ej: 500.00" min="0" required>
</div>


            <div class="form-group">
                <label for="cantidad_vacantes">Cantidad de Vacantes</label>
                <input type="number" id="cantidad_vacantes" name="cantidad_vacantes" min="1" required>
            </div>

            <div class="form-group">
                <label for="anos_experiencia">Años de Experiencia</label>
                <input type="number" id="anos_experiencia" name="anos_experiencia" min="0" required>
            </div>

            <div class="form-group">
                <label for="edad">Edad Mínima</label>
                <input type="number" id="edad" name="edad" min="17" required>
            </div>

            <div class="form-group">
                <label for="estudios_minimos">Estudios Mínimos</label>
                <select id="estudios_minimos" name="estudios_minimos" required>
                    <option value="">Seleccione una opción</option>
                    <option value="Bachillerato">Bachillerato</option>
                    <option value="Universitario">Universitario</option>
                    <option value="Técnico">Técnico</option>
                    <option value="No aplica">No aplica</option>
                </select>
            </div>

            <div class="form-group">
                <label for="idiomas">Idioma Adicional</label>
                <select id="idiomas" name="idiomas" required>
                    <option value="">Seleccione un idioma</option>
                    <option value="Inglés">Inglés</option>
                    <option value="Español">Español</option>
                    <option value="Francés">Francés</option>
                    <option value="Chino">Chino</option>
                    <option value="Chino">No aplica</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tipo_licencia_conducir">Tipo de Licencia de Conducir</label>
                <select id="tipo_licencia_conducir" name="tipo_licencia_conducir" required>
                    <option value="">Seleccione una opción</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                    <option value="G">G</option>
                    <option value="No aplica">No aplica</option>
                </select>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="disponibilidad_viajar" name="disponibilidad_viajar">
                <label for="disponibilidad_viajar">Disponibilidad para viajar</label>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="disponibilidad_cambio_residencia" name="disponibilidad_cambio_residencia">
                <label for="disponibilidad_cambio_residencia">Disponibilidad para cambio de residencia</label>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="discapacidad" name="discapacidad">
                <label for="discapacidad">Tiene discapacidad</label>
            </div>

            <input type="submit" value="Crear Oferta">
        </form>
    </div>

    <footer>
    <p>&copy; 2024 JobTec. Todos los derechos reservados.</p>
            <p>Desarrollado por Anseb</p>
</footer>

</body>
</html>
