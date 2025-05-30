<?php

if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}  
require_once("modelo/".$pagina.".php");  

if(is_file("vista/".$pagina.".php")){
  
    if(!empty($_POST)){
        $o = new estadisticas();   
        $accion = $_POST['accion'];
        
        if($accion=='consultar'){
            echo json_encode($o->consultar());
        } elseif($accion == 'consultar_cronicos') {
            echo json_encode($o->consultarCronicos());
        }
		elseif($accion == 'total_historias') {
			echo json_encode($o->totalHistorias());
        }
		elseif($accion == 'total_cronicos') {
			echo json_encode($o->totalp_cronicos());
        }
        elseif($accion == 'total_emergencias') {
			echo json_encode($o->total_emergencias());
        }
        elseif($accion == 'total_consultas') {
			echo json_encode($o->total_consultas());
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
        
        exit;
    }
   
    require_once("vista/".$pagina.".php"); 
}
else{
    echo "pagina en construccion";
}
?>