<?php
if (!is_file("src/modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}  
use Shm\Shm\modelo\personal;  
require_once("modelo/bitacora.php");

if(is_file("vista/".$pagina.".php")){
    if(!empty($_POST)){
        $o = new personal();   
        $accion = $_POST['accion'];
        
        if($accion=='consultar'){
            echo  json_encode($o->consultar());  
        }
        elseif($accion=='eliminar'){
            $o->set_cedula_personal($_POST['cedula_personal']);
            $resultado = $o->eliminar();
            bitacora::registrarYNotificar(
                'Eliminar',
                'Se ha eliminado el personal con cédula: '.$_POST['cedula_personal'],
                $_SESSION['usuario']
            );
            echo json_encode($resultado);
            exit;
        }
        else{          
            $o->set_cedula_personal($_POST['cedula_personal']);
            $o->set_apellido($_POST['apellido']);
            $o->set_nombre($_POST['nombre']);
            $o->set_correo($_POST['correo']);
            $o->set_cargo($_POST['cargo']);
            
            // Manejar teléfonos
            $telefonos = isset($_POST['telefonos']) ? $_POST['telefonos'] : array();
            $telefonos = array_filter($telefonos); // Eliminar valores vacíos
            $o->set_telefonos($telefonos);
            
            if($accion=='incluir'){
                try {
                    $resultado = $o->incluir();
                    bitacora::registrarYNotificar(
                        'Registrar',
                        'Se ha registrado el personal: '.$_POST['nombre'].' '.$_POST['apellido'],
                        $_SESSION['usuario']
                    );
                    echo json_encode($resultado);
                    exit;
                } catch(Exception $e) {
                    while(ob_get_length()) ob_end_clean();
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'Error en el servidor: ' . $e->getMessage()
                    ]);
                    exit;
                }
            }
            elseif($accion=='modificar'){
                try {
                    $resultado = $o->modificar();
                    bitacora::registrarYNotificar(
                        'Modificar',
                        'Se ha modificado el personal: '.$_POST['nombre'].' '.$_POST['apellido'],
                        $_SESSION['usuario']
                    );
                    echo json_encode($resultado);
                    exit;
                } catch(Exception $e) {
                    while(ob_get_length()) ob_end_clean();
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'Error en el servidor: ' . $e->getMessage()
                    ]);
                    exit;
                }
            }
        }
        exit;
    }
    
    $o = new personal();
    $datos = $o->consultar(); 
    require_once("vista/".$pagina.".php"); 
}
else{
    echo "pagina en construccion";
}
?>