<?php
if (!is_file("src/modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}  

use Shm\Shm\modelo\inventario;
require_once("modelo/bitacora.php");

if(is_file("vista/".$pagina.".php")) {
    if(!empty($_POST)){
        $o = new inventario();

        $accion = $_POST['accion'];

        // Solo validar cantidad para entrada/salida
        if(in_array($accion, ['registrar_entrada', 'registrar_salida'])) {
            if(isset($_POST['cantidad'])) {
                $cantidad = filter_var($_POST['cantidad'], FILTER_VALIDATE_INT, [
                    'options' => ['min_range' => 1]
                ]);
                if($cantidad === false) {
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'La cantidad debe ser un número entero positivo'
                    ]);
                    exit;
                }
                $o->set_cantidad_lote($cantidad);
            } else {
                echo json_encode([
                    'resultado' => 'error',
                    'mensaje' => 'Debe indicar la cantidad'
                ]);
                exit;
            }
        }

        // El resto de los campos
        if(isset($_POST['cod_medicamento'])) $o->set_cod_medicamento($_POST['cod_medicamento']);
        if(isset($_POST['nombre'])) $o->set_nombre($_POST['nombre']);
        if(isset($_POST['descripcion'])) $o->set_descripcion($_POST['descripcion']);
        if(isset($_POST['unidad_medida'])) $o->set_unidad_medida($_POST['unidad_medida']);
        if(isset($_POST['cod_lote'])) $o->set_cod_lote($_POST['cod_lote']);
        if(isset($_POST['fecha_vencimiento'])) $o->set_fecha_vencimiento($_POST['fecha_vencimiento']);
        if(isset($_POST['proveedor'])) $o->set_proveedor($_POST['proveedor']);
        if(isset($_SESSION['cedula_personal'])) $o->set_cedula_personal($_SESSION['cedula_personal']);

        $accion = $_POST['accion'];
        
        switch($accion) {
            case 'consultar_medicamentos':
                echo json_encode($o->consultar_medicamentos());
                exit;
                
            case 'consultar_lotes':
                echo json_encode($o->consultar_lotes_medicamento($_POST['cod_medicamento']));
                exit;
                
            case 'consultar_movimientos':
                echo json_encode($o->consultar_movimientos());
                exit;
                
            case 'obtener_medicamento':
                echo json_encode($o->obtener_medicamento());
                exit;
                
            case 'incluir_medicamento':
                try {
                    $resultado = $o->incluir();
                    while(ob_get_length()) ob_end_clean();
                    header('Content-Type: application/json');
                    echo json_encode($resultado);
                    bitacora::registrarYNotificar(
                        'Registrar',
                        'Se ha registrado un medicamento: '.$_POST['nombre'],
                        $_SESSION['usuario']
                    );
                    exit;
                } catch(Exception $e) {
                    while(ob_get_length()) ob_end_clean();
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'Error en el servidor: ' . $e->getMessage()
                    ]);
                    exit;
                }
                
            case 'modificar_medicamento':
                try {
                    $resultado = $o->modificar();
                    echo json_encode($resultado);
                    bitacora::registrarYNotificar(
                        'Modificar',
                        'Se ha modificado un medicamento: '.$_POST['nombre'],
                        $_SESSION['usuario']
                    );
                    exit;
                } catch(Exception $e) {
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'Error en el servidor: ' . $e->getMessage()
                    ]);
                    exit;
                }
                
            case 'registrar_entrada':
                try {
                    $o->set_cod_medicamento($_POST['cod_medicamento']);
                    $o->set_cantidad_lote($_POST['cantidad']);
                    $o->set_fecha_vencimiento($_POST['fecha_vencimiento']);
                    $o->set_proveedor($_POST['proveedor']);
                    $o->set_cedula_personal($_SESSION['cedula_personal']);
                    $resultado = $o->registrar_entrada();
                    echo json_encode($resultado);
                    bitacora::registrarYNotificar(
                        'Entrada',
                        'Entrada de medicamento: '.$_POST['cod_medicamento'],
                        $_SESSION['usuario']
                    );
                    exit;
                } catch(Exception $e) {
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'Error en el servidor: ' . $e->getMessage()
                    ]);
                    exit;
                }
                
            case 'registrar_salida':
                try {
                    $o = new inventario();
                    $o->set_cod_medicamento($_POST['cod_medicamento']);
                    $o->set_cantidad_lote($_POST['cantidad']);
                    $o->set_cedula_personal($_SESSION['cedula_personal']);
                    $resultado = $o->registrar_salida();
                    echo json_encode($resultado);
                    bitacora::registrarYNotificar(
                        'Salida',
                        'Salida de medicamento: '.$_POST['cod_medicamento'],
                        $_SESSION['usuario']
                    );
                    exit;
                } catch(Exception $e) {
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'Error en el servidor: ' . $e->getMessage()
                    ]);
                    exit;
                }
                
            case 'registrar_salida_multiple':
                if (!isset($_POST['salidas'])) {
                    exit;
                }
                $salidas = json_decode($_POST['salidas'], true);
                if (!is_array($salidas) || count($salidas) == 0) {
                    echo json_encode(['resultado' => 'error', 'mensaje' => 'No hay lotes válidos para salida']);
                    exit;
                }
                $o->set_cedula_personal($_SESSION['usuario']);
                $resultado = $o->registrar_salida_multiple($salidas);
                echo json_encode($resultado);
                bitacora::registrarYNotificar(
                    'Salida',
                    'Salida múltiple de medicamentos',
                    $_SESSION['usuario']
                );
                exit;
                
            case 'eliminar_medicamento':
                try {
                    $resultado = $o->eliminar();
                    echo json_encode($resultado);
                    bitacora::registrarYNotificar(
                        'Eliminar',
                        'Se ha eliminado un medicamento: '.$_POST['cod_medicamento'],
                        $_SESSION['usuario']
                    );
                    exit;
                } catch(Exception $e) {
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'Error en el servidor: ' . $e->getMessage()
                    ]);
                    exit;
                }
                
            case 'registrar_entrada_multiple':
                if (!isset($_POST['lotes'])) {
                    exit;
                }
                $lotes = json_decode($_POST['lotes'], true);
                if (!is_array($lotes) || count($lotes) == 0) {
                    echo json_encode(['resultado' => 'error', 'mensaje' => 'No hay lotes válidos para entrada']);
                    exit;
                }
                $o->set_cedula_personal($_SESSION['usuario']);
                $resultado = $o->registrar_entrada_multiple($lotes);
                echo json_encode($resultado);
                bitacora::registrarYNotificar(
                    'Entrada',
                    'Entrada múltiple de medicamentos',
                    $_SESSION['usuario']
                );
                exit;
        }
        exit;
    }
    
    // Cargar datos iniciales para la vista
    $o = new inventario();
    $medicamentos = $o->consultar_medicamentos();
    $movimientos = $o->consultar_movimientos();
    
    require_once("vista/".$pagina.".php");
}
else{
    echo "Página en construcción";
}
?>