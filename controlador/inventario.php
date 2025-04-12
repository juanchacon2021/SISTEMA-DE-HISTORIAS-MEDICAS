<?php
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}

require_once("modelo/".$pagina.".php");  

if(is_file("vista/".$pagina.".php")){
    $cedula_p = $_SESSION['usuario'] ?? null;
    
    if(!empty($_POST)){
        $o = new inventario();   
        $accion = $_POST['accion'];
        
        switch($accion){
            case 'consultar':
                echo json_encode($o->obtenerMedicamentos());
                break;
                
            case 'agregar':
                if(!$cedula_p) {
                    echo json_encode(["resultado" => "error", "mensaje" => "Usuario no autenticado"]);
                    exit;
                }
                
                $resultado = $o->agregarMedicamento(
                    $_POST['nombre'],
                    $_POST['descripcion'],
                    $_POST['cantidad'],
                    $_POST['unidad_medida'],
                    $_POST['fecha_vencimiento'],
                    $_POST['lote'],
                    $_POST['proveedor'],
                    $cedula_p
                );
                echo json_encode(["resultado" => "success", "id" => $resultado]);
                break;
                
            case 'modificar':
                $resultado = $o->modificarMedicamento(
                    $_POST['cod_medicamento'],
                    $_POST['nombre'],
                    $_POST['descripcion'],
                    $_POST['cantidad'],
                    $_POST['unidad_medida'],
                    $_POST['fecha_vencimiento'],
                    $_POST['lote'],
                    $_POST['proveedor']
                );
                echo json_encode(["resultado" => $resultado ? "success" : "error"]);
                break;
                
            case 'eliminar':
                $resultado = $o->eliminarMedicamento($_POST['cod_medicamento']);
                echo json_encode(["resultado" => $resultado ? "success" : "error"]);
                break;
                
            case 'total_medicamentos':
                echo json_encode(["total" => $o->obtenerTotalMedicamentos()]);
                break;
                
            case 'total_stock':
                echo json_encode(["total" => $o->obtenerTotalStock()]);
                break;
                
            default:
                echo json_encode(["resultado" => "error", "mensaje" => "Acción no válida"]);
        }
        exit;
    }
    
    require_once("vista/".$pagina.".php"); 
}
else{
    echo "Página en construcción";
}
?>