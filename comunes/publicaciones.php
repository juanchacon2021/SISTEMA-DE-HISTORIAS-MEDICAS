<?php
if (!isset($o)) {
    require_once("modelo/planificacion.php");
    $o = new planificacion();
}

$publicaciones = $o->obtenerPublicaciones();

if (count($publicaciones) > 0) {
    foreach ($publicaciones as $publicacion) {
        $imagenHTML = '';
        if ($publicacion['imagen']) {
            $imagenHTML = '<div class="text-center mt-2">
                            <center><img src="'.$publicacion['imagen'].'" class="img-fluid rounded" style="max-height: 300px;"></center>
                          </div>';
        }
        
        $accionesHTML = '';
        if ($publicacion['cedula_p'] == $_SESSION['usuario']) { // Asume que el usuario está en la sesión
            $accionesHTML = '<div class="d-flex justify-content-end mt-2">
                              <button class="btn btn-sm btn-outline-primary me-2 btn-editar" 
                                      data-id="'.$publicacion['cod_pub'].'">
                                  Editar
                              </button>
                              <button class="btn btn-sm btn-outline-danger btn-eliminar" 
                                      data-id="'.$publicacion['cod_pub'].'">
                                  Eliminar
                              </button>
                           </div>';
        }
        echo '<div class="card-pub" id="publicacion-'.$publicacion['cod_pub'].'">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <img src="img/user-1.svg" alt="Usuario" class="rounded-circle" width="40">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h1 class="font-bold">'.$publicacion['nombre'].' '.$publicacion['apellido'].'</h1>
                            <small class="text-muted">'.date('d/m/Y H:i', strtotime($publicacion['fecha'])).'</small>
                        </div>
                    </div>
                    <p class="card-text">'.($publicacion['contenido'] ?? '').'</p>
                    '.$imagenHTML.'
                    '.$accionesHTML.'
                </div>
              </div>';
    }
} else {
    echo '<p class="text-center">No hay publicaciones</p>';
}
?>