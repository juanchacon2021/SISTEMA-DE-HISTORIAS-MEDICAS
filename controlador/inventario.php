<?php
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}  

require_once("modelo/".$pagina.".php");  

if(is_file("vista/".$pagina.".php")) {
    if(!empty($_POST)){
        $o = new inventario();
        
        // Asignar valores comunes
        if(isset($_POST['cod_medicamento'])) $o->set_cod_medicamento($_POST['cod_medicamento']);
        if(isset($_POST['nombre'])) $o->set_nombre($_POST['nombre']);
        if(isset($_POST['descripcion'])) $o->set_descripcion($_POST['descripcion']);
        if(isset($_POST['cantidad'])) $o->set_cantidad($_POST['cantidad']);
        if(isset($_POST['unidad_medida'])) $o->set_unidad_medida($_POST['unidad_medida']);
        if(isset($_POST['fecha_vencimiento'])) $o->set_fecha_vencimiento($_POST['fecha_vencimiento']);
        if(isset($_POST['lote'])) $o->set_lote($_POST['lote']);
        if(isset($_POST['proveedor'])) $o->set_proveedor($_POST['proveedor']);
        
        $o->set_id_usuario($_SESSION['usuario']); 
        
        $accion = $_POST['accion'];
        
        if($accion == 'consultar_medicamentos'){
            echo json_encode($o->consultar_medicamentos());
            exit;
        }
        elseif($accion == 'consultar_transacciones'){
            echo json_encode($o->consultar_transacciones());
            exit;
        }
        elseif($accion == 'obtener_medicamento'){
            echo json_encode($o->obtener_medicamento());
            exit;
        }
        elseif($accion == 'incluir_medicamento'){
            echo json_encode($o->incluir());
            bitacora::registrar('Registrar', 'Se ha registrado un medicamento con codigo: '.$_POST['cod_medicamento']);
            exit;
        }
        elseif($accion == 'modificar_medicamento'){
            echo json_encode($o->modificar());
            bitacora::registrar('Modificar', 'Se ha modificado un medicamento con codigo: '.$_POST['cod_medicamento']);
            exit;
        }
        elseif($accion == 'eliminar_medicamento'){
            echo json_encode($o->eliminar());
            bitacora::registrar('Eliminar', 'Se ha eliminado un medicamento con codigo: '.$_POST['cod_medicamento']);
            exit;
        }
        exit;
    }
    
 
    $o = new inventario();
    $medicamentos = $o->consultar_medicamentos();
    $transacciones = $o->consultar_transacciones();
    
    require_once("vista/".$pagina.".php");
}
else{
    echo "Página en construcción";
}
?>