<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!is_file("src/modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}

use Shm\Shm\modelo\pacientes;
require_once("modelo/bitacora.php");
require_once("src/modelo/datos.php");

if(is_file("vista/".$pagina.".php")){
    error_reporting(0);
    ini_set('display_errors', 0);
    ob_start();

    if(!empty($_POST)){
        $o = new pacientes();
        $accion = $_POST['accion'];

        // Armar array de datos
        $datos = $_POST;
        // Procesar familiares si existen (JSON)
        if(!empty($_POST['antecedentes_familiares'])) {
            $familiares = json_decode($_POST['antecedentes_familiares'], true);
            if(json_last_error() === JSON_ERROR_NONE) {
                $datos['antecedentes_familiares'] = $familiares;
            }
        }
        // Procesar patologías si existen
        if(isset($_POST['patologias'])) {
            $datos['patologias'] = $_POST['patologias'];
        }

        switch($accion){
            case 'consultar':
                while(ob_get_length()) ob_end_clean();
                echo json_encode($o->consultar());
                exit;
            case 'consultarAntecedentes':
                echo json_encode($o->consultarAntecedentes($datos));
                exit;
            case 'agregarFamiliar':
                echo json_encode($o->agregarFamiliar($datos));
                exit;
            case 'eliminarFamiliar':
                echo json_encode($o->eliminarFamiliar($datos));
                exit;
            case 'listado_patologias':
                echo json_encode($o->listado_patologias());
                exit;
            case 'agregar_patologia':
                echo json_encode($o->agregar_patologia($datos));
                exit;
            case 'obtener_patologias_paciente':
                require_once("src/modelo/p_cronicos.php");
                $pat = new \Shm\Shm\modelo\p_cronicos();
                echo json_encode([
                    "resultado" => "success",
                    "datos" => $pat->obtener_patologias_paciente($datos['cedula_paciente'])
                ]);
                exit;
            case 'incluir':
                try {
                    $resultado = $o->incluir($datos);
                    bitacora::registrarYNotificar(
                        'Registrar',
                        'Se ha registrado un paciente: '.$datos['nombre'].' '.$datos['apellido'],
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
            case 'modificar':
                try {
                    $resultado = $o->modificar($datos);
                    bitacora::registrarYNotificar(
                        'Modificar',
                        'Se ha modificado el paciente: '.$datos['nombre'].' '.$datos['apellido'],
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
            case 'eliminar':
                $resultado = $o->eliminar($datos);
                bitacora::registrarYNotificar(
                    'Eliminar',
                    'Se ha eliminado el paciente: '.$datos['nombre'].' '.$datos['apellido'],
                    $_SESSION['usuario']
                );
                while(ob_get_length()) ob_end_clean();
                echo json_encode($resultado);
                exit;
        }
        exit;
    }

    $o = new pacientes();
    $datos = $o->consultar();
    require_once("vista/".$pagina.".php");
} else {
    echo "pagina en construccion";
}