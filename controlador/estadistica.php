<?php

if (!is_file("src/modelo/".$pagina.".php")){
	
	echo "Falta definir la clase ".$pagina;
	exit;
}  

require_once("src/modelo/".$pagina.".php");
use Shm\Shm\modelo\estadisticas;



if(is_file("vista/".$pagina.".php")){
  
    if(!empty($_POST)){
        $o = new estadisticas();   
        $accion = $_POST['accion'];
        
        if($accion=='consultar'){
            echo json_encode($o->consultar());
        } elseif($accion == 'consultar_cronicos') {
            echo json_encode($o->consultarCronicos());
        }
        elseif($accion == 'totales_generales') {
            echo json_encode($o->totalesGenerales());
        }
        elseif($accion == 'emergencias_mes') {
            echo json_encode($o->emergenciasPorDiaMesActual());
        }
        elseif($accion == 'consultas_mes') {
            echo json_encode($o->consultasPorDiaMesActual());
        }
        elseif($accion == 'mes_con_mas_emergencias') {
            echo json_encode($o->mesConMasEmergencias());
        }
        elseif($accion == 'mes_con_mas_consultas') {
            echo json_encode($o->mesConMasConsultas());
        }
        elseif($accion == 'medicamentosMasUsados') {
            echo json_encode($o->medicamentosMasUsados());
        }
        elseif($accion == 'medicamentosPorVencer') {
            echo json_encode($o->medicamentosPorVencer());
        }
        
        exit;
    }
   
    require_once("vista/".$pagina.".php"); 
}
else{
    echo "pagina en construccion";
}
?>