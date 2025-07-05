<?php
require_once("comunes/encabezado.php");
require_once("comunes/sidebar.php");
require_once("comunes/notificaciones.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes Parametrizados</title>
    <link rel="stylesheet" href="css/reportes.css">
</head>
<body>

    <div class="container texto-bienvenida h2 text-center py-4 text-zinc-800 bg-stone-100 mb-4">
        Reportes Parametrizados
    </div>

    <div class="container espacio">


        <div class="container busqueda card" style="width: 90%;">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0"><i class="fas fa-search me-2"></i>Búsqueda Parametrizada</h5>
            </div>


            <div class="row mb-3">

                <div class="col-md-3">
                    <label for="modulo" class="form-label">Módulo a buscar:</label>
                    <select class="form-select" id="modulo" name="modulo" required>
                        <option value="">Seleccione un módulo...</option>
                        <option value="pacientes">Pacientes</option>
                        <option value="personal">Personal</option>
                        <option value="emergencias">Emergencias</option>
                        <option value="consultas">Consultas</option>
                        <option value="inventario">Inventario</option>
                        <option value="pasantias">Pasantías</option>
                        <option value="jornadas">Jornadas Médicas</option>
                        <option value="examenes">Exámenes</option>
                        <option value="patologias">Patologías</option>
                    </select>
                </div>
                

                <div class="col-md-3">
                    <label for="selectParametro" class="form-label">Parametro a buscar:</label>
                    <input id="selectParametro" class="form-control" type="text" value="">
                </div>
                <div class="col-md-3">
                    <label for="selectMes" class="form-label">Mes:</label>
                    <select id="selectMes" class="form-select">
                        <option value="">Seleccione un mes...</option>
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
                <div class="col-md-3">
                    <label for="selectAnio" class="form-label">Año:</label>
                    <input id="selectAnio" class="form-control" type="number" min="1900" max="2100">
                </div>

                <div class="row mb-3 mt-4">
                       <div class="col-md-12">
                            <div class="d-flex justify-content-center">
                                <button id="busqueda" class="btn btn-primary mb-3">Hacer búsqueda</button>
                            </div>
                        </div> 
                </div>
               
            </div>

        </div>

           

















        <!-- <div class="container">
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card shadow-sm" style="white= 100%;">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-search me-2"></i>Búsqueda Parametrizada</h5>
                        </div>
                        <div class="card-body">
                            <form id="formBusqueda" autocomplete="off">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="modulo" class="form-label">Módulo a buscar:</label>
                                        <select class="form-select" id="modulo" name="modulo" required>
                                            <option value="">Seleccione un módulo...</option>
                                            <option value="pacientes">Pacientes</option>
                                            <option value="personal">Personal</option>
                                            <option value="emergencias">Emergencias</option>
                                            <option value="consultas">Consultas</option>
                                            <option value="inventario">Inventario</option>
                                            <option value="pasantias">Pasantías</option>
                                            <option value="jornadas">Jornadas Médicas</option>
                                            <option value="examenes">Exámenes</option>
                                            <option value="patologias">Patologías</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="texto" class="form-label">Texto a buscar:</label>
                                        <input type="text" class="form-control" id="texto" name="texto" placeholder="Ingrese texto para buscar...">
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <label for="mes" class="form-label">Mes:</label>
                                        <select class="form-select" id="mes" name="mes">
                                            <option value="">Todos</option>
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
                                    
                                    <div class="col-md-2">
                                        <label for="ano" class="form-label">Año:</label>
                                        <select class="form-select" id="ano" name="ano">
                                            <option value="">Todos</option>
                                            <?php /* 
                                            $currentYear = date('Y');
                                            for($i = $currentYear; $i >= $currentYear - 5; $i--) {
                                                echo "<option value='$i'>$i</option>";
                                            } */
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn btn-primary" id="btnBuscar">
                                            <i class="fas fa-search me-2"></i>Buscar
                                        </button>
                                        <button type="reset" class="btn btn-secondary ms-2">
                                            <i class="fas fa-eraser me-2"></i>Limpiar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="fas fa-table me-2"></i>Resultados</h5>
                            <div id="exportButtons" style="display:none;">
                                <button class="btn btn-sm btn-success" id="btnExportExcel">
                                    <i class="fas fa-file-excel me-1"></i> Excel
                                </button>
                                <button class="btn btn-sm btn-danger ms-2" id="btnExportPDF">
                                    <i class="fas fa-file-pdf me-1"></i> PDF
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="tablaResultados">
                                    <thead class="table-dark">
                                        <tr id="cabeceraResultados">
                                            <th class="text-center">Seleccione un módulo y realice una búsqueda</th>
                                        </tr>
                                    </thead>
                                    <tbody id="resultadosBusqueda">
                                        <tr>
                                            <td colspan="1" class="text-center text-muted">No hay resultados para mostrar</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>

    <?php require_once("comunes/modal.php"); ?>
    
    <!-- JavaScript -->
    <script src="js/reportes_p.js"></script>
    <!-- Librerías para exportación -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    
</body>
</html>