<?php
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}  

require_once("modelo/".$pagina.".php");  

if(is_file("vista/".$pagina.".php")) {
    $o = new planificacion();

    if(!empty($_POST)){
        if(isset($_POST['cod_pub'])) $o->set_cod_pub($_POST['cod_pub']);
        if(isset($_POST['contenido'])) $o->set_contenido($_POST['contenido']);
        if(isset($_FILES['imagen'])) $o->set_imagen($_FILES['imagen']);

        // Cambia esto:
        // $o->set_id_usuario($_SESSION['usuario']);
        // Por esto:
        $o->set_cedula_personal($_SESSION['usuario']);

        $accion = $_POST['accion'];

        switch($accion) {
            case 'consultar_publicaciones':
                $publicaciones = $o->consultar_publicaciones();
                $publicaciones['cedula_actual'] = $_SESSION['usuario']; // Agrega esto
                echo json_encode($publicaciones);
                exit;
            case 'obtener_publicacion':
                echo json_encode($o->obtener_publicacion());
                exit;
            case 'incluir_publicacion':
                echo json_encode($o->incluir());
                bitacora::registrar('Registrar', 'Se ha registrado una publicación con codigo: '.$o->get_cod_pub());
                exit;
            case 'modificar_publicacion':
                echo json_encode($o->modificar());
                bitacora::registrar('Modificar', 'Se ha modificado una publicación con codigo: '.$o->get_cod_pub());
                exit;
            case 'eliminar_publicacion':
                $co = $o->conecta();
                $verificar = $co->query("SELECT cedula_personal FROM feed WHERE cod_pub = '".$o->get_cod_pub()."'");
                $publicacion = $verificar->fetch(PDO::FETCH_ASSOC);

                if(!$publicacion || $publicacion['cedula_personal'] != $o->get_cedula_personal()) {
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'No tienes permisos para esta acción'
                    ]);
                    exit;
                }
                echo json_encode($o->eliminar());
                bitacora::registrar('Eliminar', 'Se ha eliminado una publicación con codigo: '.$o->get_cod_pub());
                exit;
        }
    }

    // Cargar datos iniciales para la vista (opcional, si quieres cargar publicaciones por PHP)
    $o->set_id_usuario($_SESSION['usuario']);
    $publicaciones = $o->consultar_publicaciones();

    require_once("vista/".$pagina.".php");
}
else{
    echo "Página en construcción";
}
?>