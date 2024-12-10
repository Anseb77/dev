<?php
// Conexión a la base de datos con PDO
$dsn = 'mysql:host=localhost;dbname=multitrabajos';
$username = 'root';
$password = '';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// CRUD para Empresa, Usuarios, Ofertas y Postulaciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_empresa'])) {
        $stmt = $pdo->prepare("INSERT INTO empresa (nombre, apellido, email, password, nombre_empresa, razon_social) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['nombre'], $_POST['apellido'], $_POST['email'], password_hash($_POST['password'], PASSWORD_BCRYPT), $_POST['nombre_empresa'], $_POST['razon_social']]);
    } elseif (isset($_POST['update_empresa'])) {
        $stmt = $pdo->prepare("UPDATE empresa SET nombre=?, apellido=?, email=?, nombre_empresa=?, razon_social=? WHERE id=?");
        $stmt->execute([$_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['nombre_empresa'], $_POST['razon_social'], $_POST['id']]);
    } elseif (isset($_POST['delete_empresa'])) {
        $stmt = $pdo->prepare("DELETE FROM empresa WHERE id=?");
        $stmt->execute([$_POST['id']]);
    }

    if (isset($_POST['create_usuario'])) {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, contrasena) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['nombre'], $_POST['apellido'], $_POST['email'], password_hash($_POST['contrasena'], PASSWORD_BCRYPT)]);
    } elseif (isset($_POST['update_usuario'])) {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre=?, apellido=?, email=? WHERE id=?");
        $stmt->execute([$_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['id']]);
    } elseif (isset($_POST['delete_usuario'])) {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id=?");
        $stmt->execute([$_POST['id']]);
    }

    if (isset($_POST['create_oferta'])) {
        $stmt = $pdo->prepare("INSERT INTO ofertas (empresa_id, titulo_cargo, area, descripcion_funciones, sueldo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['empresa_id'], $_POST['titulo_cargo'], $_POST['area'], $_POST['descripcion_funciones'], $_POST['sueldo']]);
    } elseif (isset($_POST['update_oferta'])) {
        $stmt = $pdo->prepare("UPDATE ofertas SET titulo_cargo=?, area=?, descripcion_funciones=?, sueldo=? WHERE id=?");
        $stmt->execute([$_POST['titulo_cargo'], $_POST['area'], $_POST['descripcion_funciones'], $_POST['sueldo'], $_POST['id']]);
    } elseif (isset($_POST['delete_oferta'])) {
        $stmt = $pdo->prepare("DELETE FROM ofertas WHERE id=?");
        $stmt->execute([$_POST['id']]);
    }

    if (isset($_POST['create_postulacion'])) {
        $stmt = $pdo->prepare("INSERT INTO postulaciones (usuario_id, oferta_id, estado) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['usuario_id'], $_POST['oferta_id'], $_POST['estado']]);
    } elseif (isset($_POST['update_postulacion'])) {
        $stmt = $pdo->prepare("UPDATE postulaciones SET estado=? WHERE id=?");
        $stmt->execute([$_POST['estado'], $_POST['id']]);
    } elseif (isset($_POST['delete_postulacion'])) {
        $stmt = $pdo->prepare("DELETE FROM postulaciones WHERE id=?");
        $stmt->execute([$_POST['id']]);
    }
}

// Consultas para mostrar los datos de las tablas
$empresas = $pdo->query("SELECT * FROM empresa")->fetchAll(PDO::FETCH_ASSOC);
$usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
$ofertas = $pdo->query("SELECT * FROM ofertas")->fetchAll(PDO::FETCH_ASSOC);
$postulaciones = $pdo->query("SELECT * FROM postulaciones")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Multitrabajos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        
        .card-header {
            font-size: 1.5rem;
        }
        .form-control {
            margin-bottom: 1rem;
        }
        .footer-section {
            background-color: rgba(0, 74, 159, 0.8); /* Fondo semitransparente para mejor visibilidad */
            color: white;
            padding: 10px;
            text-align: center;
            margin-top: auto; /* Asegura que el footer esté al final */
        }

.footer-content p {
    margin: 5px 0;
}
.main-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: rgba(0, 74, 159, 0.8); /* Fondo semitransparente para mejor visibilidad */
    padding: 10px;
}

.header-logo {
    max-width: 100px;
    height: auto;
}

.left-logo {
    max-width: 50px;
}

.center-logo {
    max-width: 150px;
}

.header-link {
    color: white;
    text-decoration: none;
    font-weight: bold;
}
    </style>
</head>
<body>
<header class="main-header">
        <a href="index.php" class="header-link">
            <img src="imagenes/atras.png" alt="Logo Atras" class="header-logo left-logo">
        </a>
        <img src="imagenes/logoremake.png" alt="Logo Multitrabajos" class="header-logo center-logo">
        <a href="empresa.php" class="header-link"></a>
    </header>
    <div class="container mt-5">
        <h1 class="mb-4">BIENVENIDO ADMINISTRADOR</h1>

        <div class="accordion" id="accordionExample">

            <!-- CRUD Empresa -->
            <div class="card mb-4">
                <div class="card-header" id="headingEmpresa">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseEmpresa" aria-expanded="true" aria-controls="collapseEmpresa">
                            CRUD Empresa
                        </button>
                    </h5>
                </div>
                <div id="collapseEmpresa" class="collapse show" aria-labelledby="headingEmpresa" data-parent="#accordionExample">
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" id="id_empresa">
                            <div class="form-group">
                                <label for="nombre_empresa">Nombre:</label>
                                <input type="text" class="form-control" id="nombre_empresa" name="nombre">
                            </div>
                            <div class="form-group">
                                <label for="apellido_empresa">Apellido:</label>
                                <input type="text" class="form-control" id="apellido_empresa" name="apellido">
                            </div>
                            <div class="form-group">
                                <label for="email_empresa">Email:</label>
                                <input type="email" class="form-control" id="email_empresa" name="email">
                            </div>
                            <div class="form-group">
                                <label for="password_empresa">Contraseña:</label>
                                <input type="password" class="form-control" id="password_empresa" name="password">
                            </div>
                            <div class="form-group">
                                <label for="nombre_empresa">Nombre Empresa:</label>
                                <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa">
                            </div>
                            <div class="form-group">
                                <label for="razon_social_empresa">Razón Social:</label>
                                <input type="text" class="form-control" id="razon_social_empresa" name="razon_social">
                            </div>
                            <button type="submit" class="btn btn-primary" name="create_empresa">Crear Empresa</button>
                            <button type="submit" class="btn btn-warning" name="update_empresa">Actualizar Empresa</button>
                            <button type="submit" class="btn btn-danger" name="delete_empresa">Eliminar Empresa</button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <h5>Lista de Empresas</h5>
                        <ul class="list-group" id="lista_empresas">
                            <?php foreach ($empresas as $empresa): ?>
                                <li class="list-group-item">
                                    <span class="empresa-nombre"><?php echo htmlspecialchars($empresa['nombre_empresa']); ?> (<?php echo htmlspecialchars($empresa['email']); ?>)</span>
                                    <button class="btn btn-info btn-sm float-right ml-2" onclick="editarEmpresa(<?php echo $empresa['id']; ?>)">Editar</button>
                                    <button class="btn btn-danger btn-sm float-right" onclick="eliminarEmpresa(<?php echo $empresa['id']; ?>)">Eliminar</button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- CRUD Usuarios -->
            <div class="card mb-4">
                <div class="card-header" id="headingUsuario">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseUsuario" aria-expanded="true" aria-controls="collapseUsuario">
                            CRUD Usuarios
                        </button>
                    </h5>
                </div>
                <div id="collapseUsuario" class="collapse show" aria-labelledby="headingUsuario" data-parent="#accordionExample">
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" id="id_usuario">
                            <div class="form-group">
                                <label for="nombre_usuario">Nombre:</label>
                                <input type="text" class="form-control" id="nombre_usuario" name="nombre">
                            </div>
                            <div class="form-group">
                                <label for="apellido_usuario">Apellido:</label>
                                <input type="text" class="form-control" id="apellido_usuario" name="apellido">
                            </div>
                            <div class="form-group">
                                <label for="email_usuario">Email:</label>
                                <input type="email" class="form-control" id="email_usuario" name="email">
                            </div>
                            <div class="form-group">
                                <label for="contrasena_usuario">Contraseña:</label>
                                <input type="password" class="form-control" id="contrasena_usuario" name="contrasena">
                            </div>
                            <button type="submit" class="btn btn-primary" name="create_usuario">Crear Usuario</button>
                            <button type="submit" class="btn btn-warning" name="update_usuario">Actualizar Usuario</button>
                            <button type="submit" class="btn btn-danger" name="delete_usuario">Eliminar Usuario</button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <h5>Lista de Usuarios</h5>
                        <ul class="list-group" id="lista_usuarios">
                            <?php foreach ($usuarios as $usuario): ?>
                                <li class="list-group-item">
                                    <span class="usuario-nombre"><?php echo htmlspecialchars($usuario['nombre']); ?> (<?php echo htmlspecialchars($usuario['email']); ?>)</span>
                                    <button class="btn btn-info btn-sm float-right ml-2" onclick="editarUsuario(<?php echo $usuario['id']; ?>)">Editar</button>
                                    <button class="btn btn-danger btn-sm float-right" onclick="eliminarUsuario(<?php echo $usuario['id']; ?>)">Eliminar</button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- CRUD Ofertas -->
            <div class="card mb-4">
                <div class="card-header" id="headingOferta">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOferta" aria-expanded="true" aria-controls="collapseOferta">
                            CRUD Ofertas
                        </button>
                    </h5>
                </div>
                <div id="collapseOferta" class="collapse show" aria-labelledby="headingOferta" data-parent="#accordionExample">
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" id="id_oferta">
                            <div class="form-group">
                                <label for="empresa_id_oferta">ID Empresa:</label>
                                <input type="text" class="form-control" id="empresa_id_oferta" name="empresa_id">
                            </div>
                            <div class="form-group">
                                <label for="titulo_cargo_oferta">Título Cargo:</label>
                                <input type="text" class="form-control" id="titulo_cargo_oferta" name="titulo_cargo">
                            </div>
                            <div class="form-group">
                                <label for="area_oferta">Área:</label>
                                <input type="text" class="form-control" id="area_oferta" name="area">
                            </div>
                            <div class="form-group">
                                <label for="descripcion_funciones_oferta">Descripción Funciones:</label>
                                <textarea class="form-control" id="descripcion_funciones_oferta" name="descripcion_funciones"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="sueldo_oferta">Sueldo:</label>
                                <input type="number" step="0.01" class="form-control" id="sueldo_oferta" name="sueldo">
                            </div>
                            <button type="submit" class="btn btn-primary" name="create_oferta">Crear Oferta</button>
                            <button type="submit" class="btn btn-warning" name="update_oferta">Actualizar Oferta</button>
                            <button type="submit" class="btn btn-danger" name="delete_oferta">Eliminar Oferta</button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <h5>Lista de Ofertas</h5>
                        <ul class="list-group" id="lista_ofertas">
                            <?php foreach ($ofertas as $oferta): ?>
                                <li class="list-group-item">
                                    <span class="oferta-titulo"><?php echo htmlspecialchars($oferta['titulo_cargo']); ?> (<?php echo htmlspecialchars($oferta['area']); ?>)</span>
                                    <button class="btn btn-info btn-sm float-right ml-2" onclick="editarOferta(<?php echo $oferta['id']; ?>)">Editar</button>
                                    <button class="btn btn-danger btn-sm float-right" onclick="eliminarOferta(<?php echo $oferta['id']; ?>)">Eliminar</button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- CRUD Postulaciones -->
            <div class="card mb-4">
                <div class="card-header" id="headingPostulacion">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapsePostulacion" aria-expanded="true" aria-controls="collapsePostulacion">
                            CRUD Postulaciones
                        </button>
                    </h5>
                </div>
                <div id="collapsePostulacion" class="collapse show" aria-labelledby="headingPostulacion" data-parent="#accordionExample">
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" id="id_postulacion">
                            <div class="form-group">
                                <label for="usuario_id_postulacion">ID Usuario:</label>
                                <input type="text" class="form-control" id="usuario_id_postulacion" name="usuario_id">
                            </div>
                            <div class="form-group">
                                <label for="oferta_id_postulacion">ID Oferta:</label>
                                <input type="text" class="form-control" id="oferta_id_postulacion" name="oferta_id">
                            </div>
                            <div class="form-group">
                                <label for="estado_postulacion">Estado:</label>
                                <select class="form-control" id="estado_postulacion" name="estado">
                                    <option value="Aceptada">Aceptada</option>
                                    <option value="Rechazada">Rechazada</option>
                                    <option value="Pendiente">Pendiente</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" name="create_postulacion">Crear Postulación</button>
                            <button type="submit" class="btn btn-warning" name="update_postulacion">Actualizar Postulación</button>
                            <button type="submit" class="btn btn-danger" name="delete_postulacion">Eliminar Postulación</button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <h5>Lista de Postulaciones</h5>
                        <ul class="list-group" id="lista_postulaciones">
                            <?php foreach ($postulaciones as $postulacion): ?>
                                <li class="list-group-item">
                                    <span class="postulacion-estado"><?php echo htmlspecialchars($postulacion['estado']); ?></span>
                                    <button class="btn btn-info btn-sm float-right ml-2" onclick="editarPostulacion(<?php echo $postulacion['id']; ?>)">Editar</button>
                                    <button class="btn btn-danger btn-sm float-right" onclick="eliminarPostulacion(<?php echo $postulacion['id']; ?>)">Eliminar</button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function editarEmpresa(id) {
            // Código para llenar los campos de la empresa para editar
        }

        function eliminarEmpresa(id) {
            // Código para eliminar la empresa
        }

        function editarUsuario(id) {
            // Código para llenar los campos del usuario para editar
        }

        function eliminarUsuario(id) {
            // Código para eliminar el usuario
        }

        function editarOferta(id) {
            // Código para llenar los campos de la oferta para editar
        }

        function eliminarOferta(id) {
            // Código para eliminar la oferta
        }

        function editarPostulacion(id) {
            // Código para llenar los campos de la postulación para editar
        }

        function eliminarPostulacion(id) {
            // Código para eliminar la postulación
        }
    </script>
  <div class="container form-container">
    <div class="row">
        <div class="col-md-4 d-flex justify-content-center">
            <form action="emitirreporte.php" method="get">
                <button type="submit" class="btn btn-primary">EMITIR REPORTES USUARIOS</button>
            </form>
        </div>
        <div class="col-md-4 d-flex justify-content-center">
            <form action="reporteofertas.php" method="get">
                <button type="submit" class="btn btn-primary">EMITIR REPORTES OFERTAS</button>
            </form>
        </div>
        <div class="col-md-4 d-flex justify-content-center">
            <form action="reportepostulaciones.php" method="get">
                <button type="submit" class="btn btn-primary">EMITIR REPORTES POSTULACIONES</button>
            </form>
        </div>
    </div>
</div>
<br>
<footer class="footer-section">
        <div class="footer-content">
            <p>&copy; 2024 Multitrabajos. Todos los derechos reservados.</p>
            <p>Desarrollado por Anseb</p>
        </div>
    </footer>
</body>
</html>
