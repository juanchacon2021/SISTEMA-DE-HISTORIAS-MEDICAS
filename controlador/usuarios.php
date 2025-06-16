<?php
if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    if (!empty($_POST)) {
        $o = new usuarios();
        $accion = $_POST['accion'];

        switch ($accion) {
            // Acciones para usuarios
            case 'consultar_usuarios':
                echo json_encode($o->consultar_usuarios());
                break;

            case 'incluir_usuario':
                $o->set_nombre($_POST['nombre']);
                $o->set_cedula($_POST['cedula']);
                $o->set_password($_POST['password']);
                $o->set_rol_id($_POST['rol_id']);
                if (!empty($_POST['foto_perfil'])) {
                    $o->set_foto_perfil($_POST['foto_perfil']);
                }
                echo json_encode($o->incluir_usuario());
                break;

            case 'modificar_usuario':
                $o->set_id($_POST['id']);
                $o->set_nombre($_POST['nombre']);
                $o->set_cedula($_POST['cedula']);
                if (!empty($_POST['password'])) {
                    $o->set_password($_POST['password']);
                }
                $o->set_rol_id($_POST['rol_id']);
                if (!empty($_POST['foto_perfil'])) {
                    $o->set_foto_perfil($_POST['foto_perfil']);
                }
                echo json_encode($o->modificar_usuario());
                break;

            case 'eliminar_usuario':
                $o->set_id($_POST['id']);
                echo json_encode($o->eliminar_usuario());
                break;

            // Acciones para roles
            case 'consultar_roles':
                echo json_encode($o->consultar_roles());
                break;

            case 'obtener_roles_select':
                echo json_encode($o->obtener_roles_select());
                break;

            case 'incluir_rol':
                $o->set_nombre_rol($_POST['nombre']);
                $o->set_descripcion_rol($_POST['descripcion']);
                echo json_encode($o->incluir_rol());
                break;

            case 'modificar_rol':
                $o->set_id($_POST['id']);
                $o->set_nombre_rol($_POST['nombre']);
                $o->set_descripcion_rol($_POST['descripcion']);
                echo json_encode($o->modificar_rol());
                break;

            case 'eliminar_rol':
                $o->set_id($_POST['id']);
                echo json_encode($o->eliminar_rol());
                break;

            // Acciones para permisos
            case 'consultar_modulos':
                echo json_encode($o->consultar_modulos());
                break;

            case 'consultar_modulos_tabla':
                echo json_encode($o->consultar_modulos_tabla());
                break;

            case 'consultar_permisos_rol':
                echo json_encode($o->consultar_permisos_rol($_POST['rol_id']));
                break;

           case 'actualizar_permisos':
                $permisos = isset($_POST['permisos']) ? json_decode($_POST['permisos'], true) : array();
                echo json_encode($o->actualizar_permisos($_POST['rol_id'], $permisos));
                break;

            // Acciones para módulos
            case 'incluir_modulo':
                $o->set_nombre_modulo($_POST['nombre']);
                $o->set_descripcion_modulo($_POST['descripcion']);
                echo json_encode($o->incluir_modulo());
                break;

            case 'modificar_modulo':
                $o->set_id_modulo($_POST['id']);
                $o->set_nombre_modulo($_POST['nombre']);
                $o->set_descripcion_modulo($_POST['descripcion']);
                echo json_encode($o->modificar_modulo());
                break;

            case 'eliminar_modulo':
                $o->set_id_modulo($_POST['id']);
                echo json_encode($o->eliminar_modulo());
                break;

            // Acciones para fotos de perfil
            case 'subir_foto':
                echo json_encode(subirFotoUsuario());
                break;

            case 'eliminar_foto':
                $o->set_id($_POST['id']);
                echo json_encode($o->eliminar_foto_perfil($_POST['id']));
                break;
            case 'obtener_personal':
                 echo json_encode($o->obtener_personal());
                 break;
            default:
                echo json_encode(array("resultado" => "error", "mensaje" => "Acción no válida"));
        }
        exit;
    }

    require_once("vista/" . $pagina . ".php");
} else {
    echo "Página en construcción";
}

function subirFotoUsuario() {
    $r = array();

    // Verificar si se subió un archivo
    if (empty($_FILES['foto_perfil'])) {
        $r['resultado'] = 'error';
        $r['mensaje'] = 'No se ha seleccionado ningún archivo';
        return $r;
    }

    $file = $_FILES['foto_perfil'];

    // Validar tamaño (50MB máximo)
    if ($file['size'] > 52428800) {
        $r['resultado'] = 'error';
        $r['mensaje'] = 'El archivo es demasiado grande (máximo 50MB)';
        return $r;
    }

    // Validar tipo de archivo
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        $r['resultado'] = 'error';
        $r['mensaje'] = 'Solo se permiten imágenes JPEG, PNG o GIF';
        return $r;
    }

    // Crear directorio si no existe
    $uploadDir = 'img/perfiles/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generar nombre único para el archivo
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $destination = $uploadDir . $filename;

    // Mover el archivo
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $r['resultado'] = 'exito';
        $r['mensaje'] = 'Foto subida correctamente';
        $r['nombre_archivo'] = $filename;
    } else {
        $r['resultado'] = 'error';
        $r['mensaje'] = 'Error al subir la foto';
    }

    return $r;
}
?>