<?php
 require_once("comunes/encabezado.php");
 require_once("comunes/sidebar.php");
 require_once("comunes/notificaciones.php");
?>


<div class="container texto-bienvenida h2 text-center py-8 text-zinc-800 bg-stone-100 mb-4">
    Historias Médicas
</div>
<div class="container espacio">
    <div class="container">
        <div class="row mt-3 botones">
            <div class="btn botonrojo">    
                <a href="?pagina=principal">Volver</a>
            </div>
        </div>
    </div>
    <div class="container">
       <div class="table-responsive">
        <table class="table table-striped table-hover" id="tablapersonal">
            <thead>
                <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Edad</th>
                        <th>Teléfono</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="resultadoHistorias"></tbody>
            </table>
        </div>
    </div>
</div>
<script src="js/historias.js"></script>
</body>
</html>