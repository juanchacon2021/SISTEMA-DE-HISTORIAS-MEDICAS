<?php
/**
 Eduin te dejo el código de la función para mostrar publicaciones.
 * @param array $publicacion Datos de la publicación
 * @param bool $esPropietario Indica si el usuario actual es el dueño de la publicación
 */
function mostrarPublicacion($publicacion, $esPropietario = false) {
    // Formatear fecha
    $fecha = new DateTime($publicacion['fecha']);
    $fechaFormateada = $fecha->format('l, j F Y \a \l\a\s H:i');
    
    // Mostrar imagen si existe
    $imagenHtml = '';
    if (!empty($publicacion['imagen'])) {
        $imagenHtml = '
            <div class="publicacion-imagen">
                <img src="'.$publicacion['imagen'].'" alt="Imagen publicación" class="img-fluid rounded">
            </div>
        ';
    }
    
    // Botones de acciones (solo para el propietario)
    $accionesHtml = '';
    if ($esPropietario) {
        $accionesHtml = '
            <div class="publicacion-acciones">
                <button class="btn btn-sm btn-primary" onclick="editarPublicacion('.$publicacion['cod_pub'].')">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="confirmarEliminacion('.$publicacion['cod_pub'].')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        ';
    }
    
    echo '
    <div class="card publicacion mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">';
                if (!empty($publicacion['foto_perfil'])) {
                    echo '<img src="img/perfiles/' . htmlspecialchars($publicacion['foto_perfil']) . '" class="rounded-circle me-2" style="width:40px;height:40px;object-fit:cover;" alt="Foto de perfil">';
                } else {
                    echo '<div class="avatar bg-primary text-white rounded-circle me-2">'
                        .substr($publicacion['nombre_usuario'], 0, 1).
                        '</div>';
                }
                echo '
                <div>
                    <h6 class="mb-0">'.$publicacion['nombre_usuario'].'</h6>
                    <small class="text-muted">'.$fechaFormateada.'</small>
                </div>
            </div>
            '.$accionesHtml.'
        </div>
        <div class="card-body">
            <p class="card-text">'.$publicacion['contenido'].'</p>
            '.$imagenHtml.'
        </div>
    </div>
    ';
}
?>