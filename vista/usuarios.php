<?php 
require_once("comunes/encabezado.php"); 
require_once("comunes/sidebar.php");    
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    
    <div class="container espacio">
        <section class="content">
        <div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
            Gestión de Usuarios y Permisos
        </div>

            <div class="container-fluid">
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
                        <button class="nav-link" id="permisos-tab" data-bs-toggle="tab" data-bs-target="#permisos" type="button" role="tab">
                            Permisos
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="usuariosTabContent">
                    <!-- Tab de Usuarios -->
                    <div class="tab-pane fade show active" id="usuarios" role="tabpanel">
                        <div class="card mt-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 class="card-title">Listado de Usuarios</h3>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-success" onclick="mostrarModalUsuario('incluir')">
                                            <i class="fas fa-plus mr-1"></i> Nuevo Usuario
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="tablaUsuarios" style="width:100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Acciones</th>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Email</th>
                                                <th>Rol</th>
                                                <th>Fecha Creación</th>
                                                <th>Estado</th>
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
                        <div class="card mt-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 class="card-title">Listado de Roles</h3>
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
                                                <th>Acciones</th>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="resultadoRoles"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab de Permisos -->
                    <!-- Tab de Permisos -->
                    <div class="tab-pane fade" id="permisos" role="tabpanel">
                        <div class="card mt-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 class="card-title">Asignación de Permisos</h3>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-primary" id="btnGuardarPermisos" onclick="guardarPermisos()">
                                            <i class="fas fa-save mr-1"></i> Guardar Permisos
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="selectRolPermisos">Seleccione Rol:</label>
                                            <select class="form-control" id="selectRolPermisos" onchange="cargarPermisos()">
                                                <option value="">-- Seleccione --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle mr-2"></i> Marque los módulos a los que este rol tendrá acceso
                                        </div>
                                        <div id="listaModulos" class="list-group">
                                            <!-- Los módulos se cargarán dinámicamente -->
                                        </div>
                                    </div>
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
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalUsuarioLabel">Registrar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formUsuario" autocomplete="off">
                        <input type="hidden" id="accionUsuario" name="accion" value="">
                        <input type="hidden" id="idUsuario" name="id" value="">
                        <div class="form-group">
                            <label for="nombreUsuario">Nombre</label>
                            <input type="text" class="form-control" id="nombreUsuario" name="nombre" required>
                            <small id="snombreUsuario" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="emailUsuario">Email</label>
                            <input type="email" class="form-control" id="emailUsuario" name="email" required>
                            <small id="semailUsuario" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="passwordUsuario">Contraseña</label>
                            <input type="password" class="form-control" id="passwordUsuario" name="password">
                            <small id="spasswordUsuario" class="form-text text-danger"></small>
                            <small class="form-text text-muted">Dejar en blanco para no cambiar</small>
                        </div>
                        <div class="form-group">
                            <label for="rolUsuario">Rol</label>
                            <select class="form-control" id="rolUsuario" name="rol_id" required>
                                <option value="">Seleccione un rol</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fotoPerfil">Foto de Perfil</label>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarUsuario">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Roles -->
    <div class="modal fade" id="modalRol" tabindex="-1" role="dialog" aria-labelledby="modalRolLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalRolLabel">Registrar Rol</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formRol" autocomplete="off">
                        <input type="hidden" id="accionRol" name="accion" value="">
                        <input type="hidden" id="idRol" name="id" value="">
                        <div class="form-group">
                            <label for="nombreRol">Nombre del Rol</label>
                            <input type="text" class="form-control" id="nombreRol" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcionRol">Descripción</label>
                            <textarea class="form-control" id="descripcionRol" name="descripcion" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnGuardarRol">Guardar</button>
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