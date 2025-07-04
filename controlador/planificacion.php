<?php
if (!is_file("src/modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}

use Shm\Shm\modelo\planificacion;
require_once("modelo/bitacora.php");

if(is_file("vista/".$pagina.".php")) {
    if(!empty($_POST)){
        $o = new planificacion();
        $accion = $_POST['accion'];
        $datos = $_POST;

        // Si hay imagen, agregarla al array
        if(isset($_FILES['imagen'])) {
            $datos['imagen'] = $_FILES['imagen'];
        }

        // Agregar cedula_personal desde la sesión
        $datos['cedula_personal'] = $_SESSION['usuario'];

        switch($accion) {
            case 'consultar_publicaciones':
                $publicaciones = $o->consultar_publicaciones($datos);
                $publicaciones['cedula_actual'] = $_SESSION['usuario'];
                echo json_encode($publicaciones);
                exit;
            case 'obtener_publicacion':
                echo json_encode($o->obtener_publicacion($datos));
                exit;
            case 'incluir_publicacion':
                $resultado = $o->incluir($datos);
                echo json_encode($resultado);
                bitacora::registrarYNotificar(
                    'Registrar',
                    'Se ha registrado una publicación con codigo: '.$resultado['cod_pub'],
                    $_SESSION['usuario']
                );
                exit;
            case 'modificar_publicacion':
                $resultado = $o->modificar($datos);
                echo json_encode($resultado);
                bitacora::registrarYNotificar(
                    'Modificar',
                    'Se ha modificado una publicación con codigo: '.$datos['cod_pub'],
                    $_SESSION['usuario']
                );
                exit;
            case 'eliminar_publicacion':
                // Verificar permisos antes de eliminar
                $verificar = $o->verificar_autor($datos);
                if(!$verificar) {
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'No tienes permisos para esta acción'
                    ]);
                    exit;
                }
                $resultado = $o->eliminar($datos);
                echo json_encode($resultado);
                bitacora::registrarYNotificar(
                    'Eliminar',
                    'Se ha eliminado una publicación con codigo: '.$datos['cod_pub'],
                    $_SESSION['usuario']
                );
                exit;
        }
    }

    // Cargar datos iniciales para la vista
    $o = new planificacion();
    $datos = ['cedula_personal' => $_SESSION['usuario']];
    $publicaciones = $o->consultar_publicaciones($datos);

    require_once("vista/".$pagina.".php");
} else {
    echo "Página en construcción";
}
?>