<html>
<?php 
    require_once("comunes/encabezado.php"); 
    require_once("comunes/sidebar.php");    
?>
<body>
<?php  // Verificar permisos
    if (!isset($permisos)) {
        // Si no existe $permisos, redirigir a login
        header("Location: ?pagina=login");
        exit();
    } elseif (!in_array('Estadística', $permisos)) {
        // Si existe $permisos pero no tiene acceso al módulo, mostrar error 403
        http_response_code(403);
        die('<div class="container text-center py-5">
                <h1 class="text-danger">403 - Acceso prohibido</h1>
                <p class="lead">No tienes permiso para acceder a este módulo</p>
                <a href="?pagina=principal" class="btn btn-primary">Volver al inicio</a>
             </div>');
    } ?>

<h1 class="fw-bold fs-1 text-center mt-4">Estadística</h1>

<div class="container mt-4" style="margin-left: 300px;">
  <div class="row align-items-stretc">
    <!-- Tarjeta 1 -->
    <div class="col-md-5">
      <div class="h-100 border border-secondary p-4 rounded shadow-sm">
        <h1 class="fw-bold fs-3">Información sobre la población</h1>

        <div>
          <h2 class="fw-bold mt-3">
            Número total de historias registradas: 
            <span id="totalHistorias">...</span>
          </h2>

          <div class="mt-4">
            <h2 class="fw-bold text-center">Distribución por Rango de Edad</h2>
            <canvas class="mt-3" id="graficoEdad"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Tarjeta 2 -->
    <div class="col-md-5">
      <div class="h-100 border border-secondary p-4 rounded shadow-sm">
        <h1 class="fw-bold fs-3">Información sobre los pacientes crónicos</h1>

        <div>
          <h2 class="fw-bold mt-3">
            Número total de pacientes crónicos registrados: 
            <span id="total_cronicos">...</span>
          </h2>

          <div class="mt-4">
            <h3 class="fw-bold text-center">Distribución por Padecimiento Crónico</h3>
            <canvas class="mt-3" id="graficoCronicos"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row align-items-stretc">
    <!-- Tarjeta 1 -->
    <div class="col-md-5 mt-4">
      <div class="h-100 border border-secondary p-4 rounded shadow-sm">
        <h1 class="fw-bold fs-3">Información sobre las emergencias</h1>

        <div>
          <h2 class="fw-bold mt-3">
            Número total de emergencias registradas: 
            <span id="total_emergencias">...</span>
          </h2>

          <div class="mt-4">
            <h2 class="fw-bold text-center">Estadisticas de emergencias al mes</h2>
            <canvas class="mt-3" id="graficolinealemergencias"></canvas>
          </div>
          


          

        </div>
      </div>
    </div>

    <!-- Tarjeta 2 -->
    <div class="col-md-5 mt-4">
      <div class="h-100 border border-secondary p-4 rounded shadow-sm">
        <h1 class="fw-bold fs-3">Información sobre las consultas</h1>

        <div>
          <h2 class="fw-bold mt-3">
            Número total de consultas registradas: 
            <span id="total_consultas">...</span>
          </h2>

          <div class="mt-4">
            <h3 class="fw-bold text-center">Estadisticas de consultas al mes</h3>
            <canvas class="mt-3" id="graficolinealconsultas"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>











	




<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/estadistica.js"></script> 

</body>
</html>