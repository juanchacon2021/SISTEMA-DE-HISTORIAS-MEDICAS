<?php
if (!is_file("src/modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

use Shm\Shm\modelo\usuarios;

if (is_file("vista/" . $pagina . ".php")) {
    if (!empty($_POST)) {
        $o = new usuarios();
        $accion = $_POST['accion'];

        // Convertir $_POST a array y filtrar datos
        $datos = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        switch ($accion) {
            // Acciones para usuarios
            case 'consultar_usuarios':
                echo json_encode($o->consultar_usuarios());
                break;

            case 'incluir_usuario':
            case 'modificar_usuario':
            case 'eliminar_usuario':
                echo json_encode($o->gestionar_usuario($datos));
                break;

            // Acciones para roles
            case 'consultar_roles':
                echo json_encode($o->consultar_roles());
                break;

            case 'obtener_roles_select':
                echo json_encode($o->obtener_roles_select());
                break;

            case 'incluir_rol':
            case 'modificar_rol':
            case 'eliminar_rol':
                echo json_encode($o->gestionar_rol($datos));
                break;

            // Acciones para módulos
            case 'consultar_modulos':
                echo json_encode($o->consultar_modulos());
                break;

            case 'consultar_modulos_tabla':
                echo json_encode($o->consultar_modulos_tabla());
                break;

            case 'incluir_modulo':
            case 'modificar_modulo':
            case 'eliminar_modulo':
                echo json_encode($o->gestionar_modulo($datos));
                break;

            // Acciones para permisos
            case 'consultar_permisos_rol':
                echo json_encode($o->consultar_permisos_rol($datos['rol_id']));
                break;

            case 'actualizar_permisos':
                $permisos = isset($_POST['permisos']) ? json_decode($_POST['permisos'], true) : array();
                echo json_encode($o->actualizar_permisos($datos['rol_id'], $permisos));
                break;

            // Acciones para fotos de perfil
            case 'subir_foto':
                echo json_encode(subirFotoUsuario());
                break;

            case 'eliminar_foto':
                echo json_encode($o->eliminar_foto_perfil($datos['id']));
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

function subirFotoUsuario()
{
    $r = array();

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