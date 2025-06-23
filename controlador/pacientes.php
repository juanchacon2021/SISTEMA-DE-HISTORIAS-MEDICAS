<?php
if (!is_file("src/modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}  
use Shm\Shm\modelo\pacientes;  
require_once("modelo/bitacora.php");

if(is_file("vista/".$pagina.".php")){
    // Desactivar warnings en producción
    error_reporting(0);
    ini_set('display_errors', 0);
    ob_start();

    if(!empty($_POST)){
        $o = new pacientes();   
        $accion = $_POST['accion'];
        
        if($accion=='consultar'){
            while(ob_get_length()) ob_end_clean();
            echo json_encode($o->consultar());  
            exit;
        }
        elseif($accion=='consultarAntecedentes'){
            $o->set_cedula_paciente($_POST['cedula_paciente']);
            while(ob_get_length()) ob_end_clean();
            echo json_encode($o->consultarAntecedentes());
            exit;
        }
        elseif($accion=='agregarFamiliar'){
            $o->set_cedula_paciente($_POST['cedula_paciente'] ?? '');
            $o->set_nom_familiar($_POST['nom_familiar'] ?? '');
            $o->set_ape_familiar($_POST['ape_familiar'] ?? '');
            $o->set_relacion_familiar($_POST['relacion_familiar'] ?? '');
            $o->set_observaciones($_POST['observaciones'] ?? '');
            $resultado = $o->agregarFamiliar();
            while(ob_get_length()) ob_end_clean();
            echo json_encode($resultado);
            exit;
        }
        elseif($accion=='eliminarFamiliar'){
            $o->set_id_familiar($_POST['id_familiar'] ?? 0);
            $resultado = $o->eliminarFamiliar();
            while(ob_get_length()) ob_end_clean();
            echo json_encode($resultado);
            exit;
        }
        else{          
            // Campos básicos del paciente
            $o->set_cedula_paciente($_POST['cedula_paciente'] ?? '');
            $o->set_nombre($_POST['nombre'] ?? '');
            $o->set_apellido($_POST['apellido'] ?? '');
            $o->set_fecha_nac($_POST['fecha_nac'] ?? '');
            $o->set_edad($_POST['edad'] ?? '');
            $o->set_estadocivil($_POST['estadocivil'] ?? '');
            $o->set_ocupacion($_POST['ocupacion'] ?? '');
            $o->set_direccion($_POST['direccion'] ?? '');
            $o->set_telefono($_POST['telefono'] ?? '');
            $o->set_hda($_POST['hda'] ?? '');
            $o->set_alergias($_POST['alergias'] ?? '');
            $o->set_alergias_med($_POST['alergias_med'] ?? '');
            $o->set_quirurgico($_POST['quirurgico'] ?? '');
            $o->set_transsanguineo($_POST['transsanguineo'] ?? '');
            $o->set_psicosocial($_POST['psicosocial'] ?? '');
            $o->set_habtoxico($_POST['habtoxico'] ?? '');

            // Procesar familiares si existen
            if(!empty($_POST['familiares'])) {
                $familiares = json_decode($_POST['familiares'], true);
                if(json_last_error() === JSON_ERROR_NONE) {
                    $o->set_familiares($familiares);
                }
            }
            
            if($accion=='incluir'){
                try {
                    $resultado = $o->incluir();

                    // 1. Registrar en bitácora
                    $usuario_id = $_SESSION['usuario'];
                    $modulo_id = 1; // Cambia esto por el id real del módulo pacientes
                    $accion_bitacora = 'Registrar';
                    $descripcion = 'Se ha registrado un paciente: '.$_POST['nombre'].' '.$_POST['apellido'];

                    $co = new PDO('mysql:host=localhost;dbname=seguridad', 'root', '123456');
                    $stmt = $co->prepare("INSERT INTO bitacora (usuario_id, modulo_id, accion, descripcion, fecha_hora) VALUES (?, ?, ?, ?, NOW())");
                    $stmt->execute([$usuario_id, $modulo_id, $accion_bitacora, $descripcion]);

                    // 2. Limitar a 10 notificaciones en la bitácora (solo para el módulo pacientes)
                    $co->query("DELETE FROM bitacora WHERE modulo_id = $modulo_id AND id NOT IN (SELECT id FROM (SELECT id FROM bitacora WHERE modulo_id = $modulo_id ORDER BY fecha_hora DESC LIMIT 10) AS t)");

                    // 3. Obtener datos del usuario activo
                    $stmt = $co->prepare("SELECT nombre, foto_perfil FROM usuario WHERE cedula_personal = ?");
                    $stmt->execute([$usuario_id]);
                    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                    // 4. Construir mensaje de notificación
                    $mensaje = [
                        'nombre' => $usuario['nombre'],
                        'foto' => $usuario['foto_perfil'] ? 'img/perfiles/'.$usuario['foto_perfil'] : 'img/default-user.png',
                        'descripcion' => $descripcion
                    ];

                    // 5. Enviar por WebSocket
                    require_once __DIR__ . '/../vendor/autoload.php';
                    try {
                        $ws = new WebSocket\Client("ws://localhost:8080");
                        $ws->send(json_encode($mensaje));
                        $ws->close();
                    } catch(Exception $e) {
                        // Si falla el WebSocket, no afecta el registro
                    }

                    echo json_encode($resultado);
                    exit;
                } catch(Exception $e) {
                    while(ob_get_length()) ob_end_clean();
                    bitacora::registrar($accion, $descripcion);
                    echo json_encode([
                        'resultado' => 'error',
                        'mensaje' => 'Error en el servidor: ' . $e->getMessage()
                    ]);
                    exit;
                }
            }
            elseif($accion=='modificar'){
                $resultado = $o->modificar();

                // 1. Registrar en bitácora
                $usuario_id = $_SESSION['usuario'];
                $modulo_id = 1; // Cambia esto por el id real del módulo pacientes
                $accion_bitacora = 'Modificar';
                $descripcion = 'Se ha modificado el paciente: '.$_POST['nombre'].' '.$_POST['apellido'];

                $co = new PDO('mysql:host=localhost;dbname=seguridad', 'root', '123456');
                $stmt = $co->prepare("INSERT INTO bitacora (usuario_id, modulo_id, accion, descripcion, fecha_hora) VALUES (?, ?, ?, ?, NOW())");
                $stmt->execute([$usuario_id, $modulo_id, $accion_bitacora, $descripcion]);

                // Limitar a 10 notificaciones en la bitácora (solo para el módulo pacientes)
                $co->query("DELETE FROM bitacora WHERE modulo_id = $modulo_id AND id NOT IN (SELECT id FROM (SELECT id FROM bitacora WHERE modulo_id = $modulo_id ORDER BY fecha_hora DESC LIMIT 10) AS t)");

                // Obtener datos del usuario activo
                $stmt = $co->prepare("SELECT nombre, foto_perfil FROM usuario WHERE cedula_personal = ?");
                $stmt->execute([$usuario_id]);
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                // Construir mensaje de notificación
                $mensaje = [
                    'nombre' => $usuario['nombre'],
                    'foto' => $usuario['foto_perfil'] ? 'img/perfiles/'.$usuario['foto_perfil'] : 'img/default-user.png',
                    'descripcion' => $descripcion
                ];

                // Enviar por WebSocket
                require_once __DIR__ . '/../vendor/autoload.php';
                try {
                    $ws = new WebSocket\Client("ws://localhost:8080");
                    $ws->send(json_encode($mensaje));
                    $ws->close();
                } catch(Exception $e) {
                    // Si falla el WebSocket, no afecta el registro
                }

                while(ob_get_length()) ob_end_clean();
                echo json_encode($resultado);
                exit;
            }
            elseif($accion=='eliminar'){
                $resultado = $o->eliminar();
                while(ob_get_length()) ob_end_clean();
                echo json_encode($resultado);
                exit;
            }
        }
        exit;
    }
    
    $o = new pacientes();
    $datos = $o->consultar(); 
    require_once("vista/".$pagina.".php");
}
else{
    echo "pagina en construccion";
}
