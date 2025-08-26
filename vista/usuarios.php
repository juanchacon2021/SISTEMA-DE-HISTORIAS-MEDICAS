<?php 
require_once("comunes/encabezado.php"); 
require_once("comunes/sidebar.php");  
require_once("comunes/notificaciones.php");  
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <?php
    if (!isset($_SESSION['permisos']) || !is_array($_SESSION['permisos'])) {
        header("Location: ?pagina=login");
        exit();
    } elseif (!isset($_SESSION['permisos']['modulos']) || !in_array('Usuarios', $_SESSION['permisos']['modulos'])) {
        http_response_code(403);
        die('<div class="container text-center py-5">
                <h1 class="text-danger">403 - Acceso prohibido</h1>
                <p class="lead">No tienes permiso para acceder a este módulo</p>
                <a href="?pagina=principal" class="btn btn-primary">Volver al inicio</a>
             </div>');
    }
    ?>

    <div class="wrapper">
        <div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
            Gestión de Usuarios y Permisos
        </div>

        <div class="container espacio">
            <section class="content">

                <div class="container-fluid d-flex">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="usuariosTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="usuarios-tab" data-bs-toggle="tab" data-bs-target="#usuarios" type="button" role="tab">
                                Usuarios
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">
                                Roles
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="modulos-tab" data-bs-toggle="tab" data-bs-target="#modulos" type="button" role="tab">
                                Módulos
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="usuariosTabContent">
                    <!-- Tab de Usuarios -->
                    <div class="tab-pane fade show active" id="usuarios" role="tabpanel">
                        <div class="cardd mt-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-12 botones">
                                        <button type="button" class="btn botonverde nuevousuario" onclick="mostrarModalUsuario('incluir')">
                                            <i class="fas fa-plus mr-1"></i> Nuevo Usuario
                                        </button>
                                        
                                        <button id="btnTutorial" class="botonverde">Comenzar Tutorial</button>

                                        <div class="btn botonrojo">
                                            <a href="?pagina=principal">Volver</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="tablaUsuarios" style="width:100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Cédula</th>
                                                <th>Rol</th>
                                                <th>Fecha Creación</th>
                                                <th>Estado</th>
                                                <th style="text-align: right;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="resultadoUsuarios"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab de Roles -->
                    <div class="tab-pane fade" id="roles" role="tabpanel">
                        <div class="cardd mt-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-success" onclick="mostrarModalRol('incluir')">
                                            <i class="fas fa-plus mr-1"></i> Nuevo Rol
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="tablaRoles" style="width:100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                                <th style="text-align: right;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="resultadoRoles"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab de Módulos -->
                    <div class="tab-pane fade" id="modulos" role="tabpanel">
                        <div class="cardd mt-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-success" onclick="mostrarModalModulo('incluir')">
                                            <i class="fas fa-plus mr-1"></i> Nuevo Módulo
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="tablaModulos" style="width:100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                                <th style="text-align: right;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="resultadoModulos"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </section>
    </div>

    <!-- Modal para Usuarios -->
    <div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div style="margin: 0em 2em 0em 2em;">
                    <h1 class="text-2xl font-bold mb-2">Registrar Usuario</h1>

                    <div>
                        <div class="col-md-2 mb-4 p-1 rounded-full" style="background-color: rgb(220 38 38);">
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <form id="formUsuario" autocomplete="off" style="margin: 0em 1em 0em 1em;">
                        <input type="hidden" id="accionUsuario" name="accion" value="">
                        <input type="hidden" id="idUsuario" name="id" value="">
                        <div class="form-group">
                            <label class="texto-inicio font-medium mt-2" for="nombreUsuario">Nombre</label>
                            <input type="text" class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Nombre" id="nombreUsuario" name="nombre" required>
                            <small id="snombreUsuario" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label class="texto-inicio font-medium mt-2" for="cedulaUsuario">Personal</label>
                            <select class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="cedulaUsuario" name="cedula" required>
                                <option value="">Seleccione un personal</option>
                            </select>
                            <small id="scedulaUsuario" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label class="texto-inicio font-medium mt-2" for="passwordUsuario">Contraseña</label>
                            <input type="password" class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Contraseña" id="passwordUsuario" name="password">
                            <small id="spasswordUsuario" class="form-text text-danger"></small>
                            <small class="form-text text-muted">Dejar en blanco para no cambiar</small>
                        </div>
                        <div class="form-group">
                            <label class="texto-inicio font-medium mt-2" for="rolUsuario">Rol</label>
                            <select class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Rol" id="rolUsuario" name="rol_id" required>
                                <option value="">Seleccione un rol</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="texto-inicio font-medium mt-2" placeholder="Foto de Perfil" for="fotoPerfil">Foto de Perfil</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="fotoPerfil" name="foto_perfil" accept="image/jpeg,image/png,image/gif">
                                <label class="custom-file-label" for="fotoPerfil" id="fotoPerfilLabel">Seleccionar archivo (máx. 50MB)</label>
                            </div>
                            <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF</small>
                            <div id="previewFoto" class="mt-2 text-center" style="display:none;">
                                <img id="fotoPreview" src="" alt="Vista previa" class="img-thumbnail" style="max-height: 150px;">
                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="eliminarFoto()">
                                    <i class="fas fa-trash"></i> Eliminar Foto
                                </button>
                            </div>
                            <input type="hidden" id="fotoActual" name="foto_actual" value="">
                        </div>
                    </form>
                </div>
                <div class="modal-footer botones">
                    <button type="button" class="btn boton" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn botonverde" id="btnGuardarUsuario">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Roles -->
    <div class="modal fade" id="modalRol" tabindex="-1" role="dialog" aria-labelledby="modalRolLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div style="margin: 0em 2em 0em 2em;">
                    <h1 class="text-2xl font-bold mb-2">Registrar Rol</h1>

                    <div>
                        <div class="col-md-2 mb-4 p-1 rounded-full" style="background-color: rgb(220 38 38);">
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <form id="formRol" autocomplete="off" style="margin: 0em 1em 0em 1em;">
                        <input type="hidden" id="accionRol" name="accion" value="">
                        <input type="hidden" id="idRol" name="id" value="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="texto-inicio font-medium mt-2" for="nombreRol">Nombre del Rol</label>
                                    <input class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Nombre del Rol" type="text" class="form-control" id="nombreRol" name="nombre" required>
                                </div>
                                <div class="form-group">
                                    <label class="texto-inicio font-medium mt-2" for="descripcionRol">Descripción</label>
                                    <textarea class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="descripcionRol" name="descripcion" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i> Permisos del Rol
                                </div>
                                <div id="listaModulos" class="permisos-modulos"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer botones">
                    <button type="button" class="btn boton" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn botonverde" id="btnGuardarRol">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Módulos -->
    <div class="modal fade" id="modalModulo" tabindex="-1" role="dialog" aria-labelledby="modalModuloLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="text-light text-end" style="margin: 20px 20px 0px 0px;">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div style="margin: 0em 2em 0em 2em;">
                    <h1 class="text-2xl font-bold mb-2">Registrar Módulo</h1>

                    <div>
                        <div class="col-md-2 mb-4 p-1 rounded-full" style="background-color: rgb(220 38 38);">
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <form id="formModulo" autocomplete="off" style="margin: 0em 1em 0em 1em;">
                        <input type="hidden" id="accionModulo" name="accion" value="">
                        <input type="hidden" id="idModulo" name="id" value="">
                        <div class="form-group">
                            <label class="texto-inicio font-medium mt-2" for="nombreModulo">Nombre del Módulo</label>
                            <input class="form-control bg-gray-200 rounded-lg border-white p-3 text" placeholder="Nombre del Módulo" type="text" class="form-control" id="nombreModulo" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label class="texto-inicio font-medium mt-2" for="descripcionModulo">Descripción</label>
                            <textarea class="form-control bg-gray-200 rounded-lg border-white p-3 text" id="descripcionModulo" name="descripcion" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer botones">
                    <button type="button" class="btn boton" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn botonverde" id="btnGuardarModulo">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Confirmación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="mensajeConfirmacion">
                    ¿Está seguro de eliminar este registro?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("comunes/modal.php"); ?>
    <?php require_once("comunes/footer.php"); ?>
    </div>

    <script src="js/usuarios.js"></script>
</body>

</html>