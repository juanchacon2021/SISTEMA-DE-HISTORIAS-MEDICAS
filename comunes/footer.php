<?php
/**
 * Footer común para todas las páginas del sistema
 */
?>
<!-- Footer -->
<footer class="main-footer text-sm">
    <div class="float-right d-none d-sm-inline-block">
        <strong>Versión</strong> 1.0.0
        <?php if(isset($_SESSION['nivel'])): ?>
            | <span class="text-info">Usuario: <?php echo $_SESSION['nivel']; ?></span>
        <?php endif; ?>
    </div>
    
    
    <!-- Información del sistema (solo visible para administradores) -->
    <?php if(isset($_SESSION['nivel']) && $_SESSION['nivel'] == 'Administrador'): ?>
        <div class="mt-1 small text-muted">
            <span class="mr-2">Memoria usada: <?php echo round(memory_get_usage()/1024/1024, 2); ?> MB</span>
            | 
            <span class="mx-2">Tiempo: <?php echo round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 3); ?> seg</span>
            |
            <span class="ml-2">PHP: <?php echo phpversion(); ?></span>
        </div>
    <?php endif; ?>
</footer>

<!-- Scripts comunes al final del documento -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="assets/dist/js/adminlte.min.js"></script>

<!-- Scripts personalizados -->
<script>
$(document).ready(function() {
    // Activar tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Configuración común para SweetAlert2
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
    
    // Mostrar notificaciones si existen
    <?php if(isset($_SESSION['notificacion'])): ?>
        Toast.fire({
            icon: '<?php echo $_SESSION['notificacion']['tipo']; ?>',
            title: '<?php echo $_SESSION['notificacion']['mensaje']; ?>'
        });
        <?php unset($_SESSION['notificacion']); ?>
    <?php endif; ?>
    
    // Inicializar DataTables en todas las tablas con clase .dataTable
    $('.dataTable').DataTable({
        "language": {
            "url": "assets/plugins/datatables/Spanish.json"
        },
        "responsive": true,
        "autoWidth": false
    });
});

// Función para confirmar eliminaciones
function confirmarEliminacion(event, mensaje = "¿Está seguro de eliminar este registro?") {
    event.preventDefault();
    const form = event.target.closest('form');
    
    Swal.fire({
        title: 'Confirmar',
        text: mensaje,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>
</body>
</html>