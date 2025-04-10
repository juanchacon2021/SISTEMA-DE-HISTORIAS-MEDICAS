<?php
$o = new planificacion();
$publicaciones = $o->obtenerPublicaciones();

foreach ($publicaciones as $publicacion):
    $fecha = date('d/m/Y H:i', strtotime($publicacion['fecha']));
    $puedeEditar = ($_SESSION['cedula'] ?? null) == $publicacion['cedula_p'];
?>
<div class="card mb-3 publicacion" data-id="<?= $publicacion['cod_pub'] ?>">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <img src="img/user-default.png" alt="Usuario" width="40" height="40" class="rounded-circle">
            </div>
            <div>
                <h6 class="mb-0"><?= htmlspecialchars($publicacion['nombre'] . ' ' . $publicacion['apellido']) ?></h6>
                <small class="text-muted"><?= $fecha ?></small>
            </div>
        </div>
        
        <?php if($puedeEditar): ?>
        <div class="dropdown">
            <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-ellipsis-h"></i>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item editar-publicacion" href="#" data-id="<?= $publicacion['cod_pub'] ?>">Editar</a></li>
                <li><a class="dropdown-item eliminar-publicacion" href="#" data-id="<?= $publicacion['cod_pub'] ?>">Eliminar</a></li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="card-body">
        <p class="card-text"><?= nl2br(htmlspecialchars($publicacion['contenido'])) ?></p>
        
        <?php if($publicacion['imagen']): ?>
        <div class="text-center mt-2">
            <img src="<?= $publicacion['imagen'] ?>" alt="Imagen publicación" class="img-fluid rounded">
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>

<?php if(empty($publicaciones)): ?>
<div class="alert alert-info">No hay publicaciones aún. Sé el primero en compartir algo.</div>
<?php endif; ?>