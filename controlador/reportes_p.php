<?php
if (!is_file("src/modelo/reportes_p.php")) {
    echo "Falta definir la clase reportes";
    exit;
}

require_once("src/modelo/reportes_p.php");
use \Shm\Shm\modelo\reportes; 

if(is_file("vista/".$pagina.".php")) {
    // Verificar permisos antes de cualquier acción
    // Instanciar el modelo
   $o = new reportes();

    // Manejo de solicitudes AJAX
    if(!empty($_POST)) {
        $respuesta = array();
        $modulo = $_POST['modulo'];
        $datos = $_POST;
        
        
        try {

            switch($modulo) {
                case 'emergencias':
                    // Lógica específica para el módulo de emergencias
                    if ($datos['mes'] && $datos['ano']) {

                        $respuesta = $o->buscar_emergencias_date($datos);
                        echo json_encode($respuesta);
                        exit;

                    } else {

                      
                        $respuesta = $o->buscar_emergencias($datos);
                        echo json_encode($respuesta);
                        exit;
                    }
                    break;
                    
                default:
                    $respuesta = array(
                        'resultado' => 'error',
                        'mensaje' => 'Acción no reconocida'
                    );
            }



           /*  $accion = $_POST['accion'] ?? '';
            
            switch ($accion) {
                case 'buscar':
                    // Validar y sanitizar datos de entrada
                    $datosBusqueda = array(
                        'modulo' => limpiarEntrada($_POST['modulo'] ?? ''),
                        'texto' => limpiarEntrada($_POST['texto'] ?? ''),
                        'mes' => isset($_POST['mes']) && is_numeric($_POST['mes']) ? (int)$_POST['mes'] : null,
                        'ano' => isset($_POST['ano']) && is_numeric($_POST['ano']) ? (int)$_POST['ano'] : null
                    );
                    
                    // Validar módulo
                    $modulosValidos = ['pacientes', 'personal', 'emergencias', 'consultas', 
                                      'inventario', 'pasantias', 'jornadas', 'examenes', 'patologias'];
                    
                    if(!in_array($datosBusqueda['modulo'], $modulosValidos)) {
                        throw new Exception("Módulo no válido");
                    }
                    
                    // Validar que haya criterios de búsqueda
                    if(empty($datosBusqueda['texto']) && (empty($datosBusqueda['mes']) || empty($datosBusqueda['ano']))) {
                        throw new Exception("Debe ingresar un texto de búsqueda o seleccionar mes y año");
                    }
                    
                    $respuesta = $reportesModel->buscar($datosBusqueda);
                    break;
                    
                default:
                    $respuesta = array(
                        'resultado' => 'error',
                        'mensaje' => 'Acción no reconocida'
                    );
            } */
            
        } catch (Exception $e) {
            $respuesta = array(
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            );
        }
        
        // Enviar respuesta JSON
        header('Content-Type: application/json');
        echo json_encode($respuesta);
        exit;
    } 
    
    // Cargar la vista si no es una solicitud AJAX
    require_once("vista/".$pagina.".php");
} else {
    echo "Página en construcción";
}


/* function limpiarEntrada($dato) {
    return htmlspecialchars(strip_tags(trim($dato)), ENT_QUOTES, 'UTF-8');
} */
?>