<?php
if (!is_file("src/modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}

use Shm\Shm\modelo\inventario;
require_once("modelo/bitacora.php");

if(is_file("vista/".$pagina.".php")) {
    if(!empty($_POST)){
        $accion = $_POST['accion'];
        $datos = $_POST;
        if(isset($_SESSION['cedula_personal'])) {
            $datos['cedula_personal'] = $_SESSION['cedula_personal'];
        }

        // Validación de cantidad para entrada/salida
        if(in_array($accion, ['registrar_entrada', 'registrar_salida'])) {
            if(isset($datos['cantidad'])) {
                $cantidad = filter_var($datos['cantidad'], FILTER_VALIDATE_INT, [
                    'options' => ['min_range' => 1]
                ]);
                if($cantidad === false) {
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'La cantidad debe ser un número entero positivo'
                    ]);
                    exit;
                }
                $datos['cantidad'] = $cantidad;
            } else {
                echo json_encode([
                    'resultado' => 'error',
                    'mensaje' => 'Debe indicar la cantidad'
                ]);
                exit;
            }
        }

        $o = new inventario();

        switch($accion) {
            case 'consultar_medicamentos':
                echo json_encode($o->consultar_medicamentos());
                exit;

            case 'consultar_lotes':
                echo json_encode($o->consultar_lotes_medicamento($datos['cod_medicamento']));
                exit;

            case 'consultar_movimientos':
                echo json_encode($o->consultar_movimientos());
                exit;

            case 'obtener_medicamento':
                echo json_encode($o->obtener_medicamento($datos['cod_medicamento']));
                exit;

            case 'incluir_medicamento':
                try {
                    $resultado = $o->incluir($datos);
                    echo json_encode($resultado);
                    if ($resultado['resultado'] !== 'error') {
                        bitacora::registrarYNotificar(
                            'Registrar',
                            'Se ha registrado un medicamento: '.$datos['nombre'],
                            $_SESSION['usuario']
                        );
                    }
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
                    $resultado = $o->modificar($datos);
                    echo json_encode($resultado);
                    if ($resultado['resultado'] !== 'error') {
                        bitacora::registrarYNotificar(
                            'Modificar',
                            'Se ha modificado un medicamento: '.$datos['nombre'],
                            $_SESSION['usuario']
                        );
                    }
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
                    $resultado = $o->registrar_entrada($datos);
                    echo json_encode($resultado);
                    if ($resultado['resultado'] !== 'error') {
                        bitacora::registrarYNotificar(
                            'Entrada',
                            'Entrada de medicamento: '.$datos['cod_medicamento'],
                            $_SESSION['usuario']
                        );
                    }
                    exit;
                } catch(Exception $e) {
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'Error en el servidor: ' . $e->getMessage()
                    ]);
                    exit;
                }

            case 'registrar_entrada_multiple':
                if (!isset($datos['lotes'])) {
                    exit;
                }
                $lotes = json_decode($datos['lotes'], true);
                if (!is_array($lotes) || count($lotes) == 0) {
                    echo json_encode(['resultado' => 'error', 'mensaje' => 'No hay lotes válidos para entrada']);
                    exit;
                }
                $datos['lotes'] = $lotes;
                $resultado = $o->registrar_entrada_multiple($datos);
                echo json_encode($resultado);
                if ($resultado['resultado'] !== 'error') {
                    bitacora::registrarYNotificar(
                        'Entrada',
                        'Entrada múltiple de medicamentos',
                        $_SESSION['usuario']
                    );
                }
                exit;

            case 'registrar_salida':
                try {
                    $resultado = $o->registrar_salida($datos);
                    echo json_encode($resultado);
                    if ($resultado['resultado'] !== 'error') {
                        bitacora::registrarYNotificar(
                            'Salida',
                            'Salida de medicamento: '.$datos['cod_medicamento'],
                            $_SESSION['usuario']
                        );
                    }
                    exit;
                } catch(Exception $e) {
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'Error en el servidor: ' . $e->getMessage()
                    ]);
                    exit;
                }

            case 'registrar_salida_multiple':
                if (!isset($datos['salidas'])) {
                    exit;
                }
                $salidas = json_decode($datos['salidas'], true);
                if (!is_array($salidas) || count($salidas) == 0) {
                    echo json_encode(['resultado' => 'error', 'mensaje' => 'No hay lotes válidos para salida']);
                    exit;
                }
                $datos['salidas'] = $salidas;
                $resultado = $o->registrar_salida_multiple($datos);
                echo json_encode($resultado);
                bitacora::registrarYNotificar(
                    'Salida',
                    'Salida múltiple de medicamentos',
                    $_SESSION['usuario']
                );
                exit;

            case 'eliminar_medicamento':
                try {
                    $resultado = $o->eliminar($datos);
                    echo json_encode($resultado);
                    if (isset($resultado['resultado']) && $resultado['resultado'] === 'eliminar_medicamento') {
                        bitacora::registrarYNotificar(
                            'Eliminar',
                            'Se ha eliminado un medicamento: '.$datos['cod_medicamento'],
                            $_SESSION['usuario']
                        );
                    }
                    exit;
                } catch(Exception $e) {
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'Error en el servidor: ' . $e->getMessage()
                    ]);
                    exit;
                }
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