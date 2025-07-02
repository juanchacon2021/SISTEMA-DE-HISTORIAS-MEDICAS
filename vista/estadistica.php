<html>
<?php 
    require_once("comunes/encabezado.php"); 
    require_once("comunes/sidebar.php");    
?>
<body>
<?php  
    if (!isset($_SESSION['permisos']) || !is_array($_SESSION['permisos'])) {
        header("Location: ?pagina=login");
        exit();
    } elseif (!isset($_SESSION['permisos']['modulos']) || !in_array('Estadistica', $_SESSION['permisos']['modulos'])) {
        http_response_code(403);
        die('<div class="container text-center py-5">
                <h1 class="text-danger">403 - Acceso prohibido</h1>
                <p class="lead">No tienes permiso para acceder a este módulo</p>
                <a href="?pagina=principal" class="btn btn-primary">Volver al inicio</a>
             </div>');
    }
?>
<h1 class="fw-bold fs-1 text-center mt-4">Estadística</h1>

<div class="container mt-3" style="margin-left: 300px;">

<div id="carrusel" class="d-flex align-items-stretch" style="overflow:hidden; width:105%; height: 80%; margin-top: 5%;">
  <div id="carrusel-inner" class="d-flex" style="transition: transform 0.5s;">
    <!-- Aquí van tus cards -->
    <div class="card m-2" style="width: 630px;">

       <div class="h-100  p-4 rounded shadow-sm">
        <h1 class="fw-bold fs-3">Información sobre la población</h1>

        <div>
          <h2 class="fw-bold mt-3">
            Número total de historias registradas: 
            <span id="totalHistorias">...</span>
          </h2>

          <div class="mt-4">
            <h2 class="fw-bold text-center">Distribución por Rango de Edad</h2>
            <canvas class="mt-3" id="graficoEdad" style="width: 90%;"></canvas>
          </div>
          <p class="fw-bold text-center" id="rango_mayor"></p>
        </div>
      </div>

    </div>
    <div class="card m-2" style="width: 630px;">

      <div class="h-100  p-4 rounded shadow-sm">
        <h1 class="fw-bold fs-3">Información sobre los pacientes crónicos</h1>

        <div>
          <h2 class="fw-bold mt-3">
            Número total de pacientes crónicos registrados: 
            <span id="total_cronicos">...</span>
          </h2>

          <div class="mt-4">
            <h3 class="fw-bold text-center">Distribución por Padecimiento Crónico</h3>
            <canvas class="mt-3" id="graficoCronicos" style="width: 90%;"></canvas>
          </div>
          <p class="fw-bold text-center" id="cronico_mayor"></p>
        </div>
      </div>

    </div>
    <div class="card m-2" style="width: 630px;">

       <div class="h-100  p-4 rounded shadow-sm">
        <h1 class="fw-bold fs-3">Información sobre los medicamentos</h1>

        <div>
          <h2 class="fw-bold mt-3">
            Número total de medicamentos registrados: 
            <span id="total_medicamentos">...</span>
          </h2>

          <div class="mt-4">
            <h2 class="fw-bold text-center">Distribución por uso de medicamento</h2>
            <canvas class="mt-4" style="width: 70%; min-width: 500px; height: 70%;" id="graficoMedicamentos"></canvas>
          </div>
          <p class="fw-bold text-center" id="Medicamento_mas_usado"></p>
        </div>
      </div>

    </div>
    <div class="card m-2" style="width: 630px;">

      <div class="h-100  p-4 rounded shadow-sm">
        <h1 class="fw-bold fs-3">Información sobre los Lotes por caducar</h1>

        <div>
          <h2 class="fw-bold mt-3">
            Número total de lotes: 
            <span id="total_lotes">...</span>
          </h2>

          <div class="mt-4">
            <h3 class="fw-bold text-center">Distribución por Fecha de Caducidad</h3>
            <canvas class="mt-4" style="width: 70%; min-width: 500px; height: 70%;" id="graficoMedicamentosPorVencer"></canvas>
          </div>
          <p class="fw-bold text-center" id="lote_por_expirar"></p> 
        </div>
      </div>

    </div>
    <div class="card m-2" style="width: 630px;">

      <div class="h-100  p-4 rounded shadow-sm">
        <h1 class="fw-bold fs-3">Información sobre las emergencias</h1>

        <div>
          <h2 class="fw-bold mt-3">
              Número total de emergencias registradas: 
              <span id="total_emergencias">...</span>
          </h2>

          <div class="mt-4" >
            <h2 class="fw-bold text-center">Estadisticas de emergencias al mes</h2>
            
            <!-- Selectores de mes y año -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="selectMes" class="form-label">Mes:</label>
                <select id="selectMes" class="form-select">
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="selectAnio" class="form-label">Año:</label>
                <input id="selectAnio" class="form-control" type="number" min="1900" max="2100" value="<?php echo date('Y'); ?>">
              </div>
            </div>
            
            <button id="btnActualizarGrafico" class="btn btn-primary mb-3">Actualizar Gráfico</button>

            <canvas class="mt-1" style="width: 70%; min-width: 400px; height: 40%;" id="graficolinealemergencias"></canvas>
            <p id="mes_mayor_emergencias"></p>
          </div>
        </div>
      </div>

    </div>
    <div class="card m-2" style="width: 630px;">

      <div class="h-100 p-4 rounded shadow-sm">
        <h1 class="fw-bold fs-3">Información sobre las consultas</h1>

        <div>
          <h2 class="fw-bold mt-3">
            Número total de consultas registradas: 
            <span id="total_consultas">...</span>
          </h2>

          <div class="mt-4">
            <h3 class="fw-bold text-center">Estadísticas de consultas al mes</h3>
    
            <!-- Selectores de mes y año para consultas -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="selectMesConsultas" class="form-label">Mes:</label>
                <select id="selectMesConsultas" class="form-select">
                  <option value="1">Enero</option>
                  <option value="2">Febrero</option>
                  <option value="3">Marzo</option>
                  <option value="4">Abril</option>
                  <option value="5">Mayo</option>
                  <option value="6">Junio</option>
                  <option value="7">Julio</option>
                  <option value="8">Agosto</option>
                  <option value="9">Septiembre</option>
                  <option value="10">Octubre</option>
                  <option value="11">Noviembre</option>
                  <option value="12">Diciembre</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="selectAnioConsultas" class="form-label">Año:</label>
                <input id="selectAnioConsultas" class="form-control" type="number" min="1900" max="2100" value="<?php echo date('Y'); ?>">
              </div>
            </div>
    
            <button id="btnActualizarGraficoConsultas" class="btn btn-primary mb-3">Actualizar Gráfico</button>
            
            <canvas class="mt-3" style="width: 70%; min-width: 400px; height: 40%;" id="graficolinealconsultas"></canvas>
            <p id="mes_mayor_consultas"></p>
          </div>
        </div>
      </div>

    </div>
    <!-- ...más cards... -->
  </div>
</div>
<div class="mt-3 text-center">
  <button id="prevBtn" class="btn btn-secondary">Anterior</button>
  <button id="nextBtn" class="btn btn-secondary">Siguiente</button>
</div>











  

 
</div>











	




<?php require_once("comunes/modal.php"); ?>
<script type="text/javascript" src="js/estadistica.js"></script> 

</body>
</html>