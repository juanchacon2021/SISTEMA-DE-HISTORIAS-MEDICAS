<?php
if (!is_file("src/modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}  
use Shm\Shm\modelo\personal;  
  if(is_file("vista/".$pagina.".php")){
      if(!empty($_POST)){
        $o = new personal();   
          $accion = $_POST['accion'];
          
          if($accion=='consultar'){
             echo  json_encode($o->consultar());  
          }
          elseif($accion=='eliminar'){
             $o->set_cedula_personal($_POST['cedula_personal']);
             echo  json_encode($o->eliminar());
             bitacora::registrar('eliminar', 'Eliminó un personal con cédula: '.$_POST['cedula_personal']);
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
                  bitacora::registrar('incluir', 'Incluyó un nuevo personal con cédula: '.$_POST['cedula_personal']);

                  // Usar el modelo para obtener los datos del usuario activo
                  $usuario = personal::obtenerUsuarioPersonal($_SESSION['usuario']);
                  $foto = isset($usuario['foto_perfil']) && $usuario['foto_perfil'] ? 'img/perfiles/'.$usuario['foto_perfil'] : 'img/user.png';
                  $mensaje = [
                      'nombre' => $usuario['nombre'] . ' ' . $usuario['apellido'],
                      'foto' => $foto,
                      'descripcion' => 'Se ha Registrado el personal: '.$_POST['nombre'].' '.$_POST['apellido'],
                      'fecha_hora' => date('Y-m-d H:i:s')
                  ];
                  require_once __DIR__ . '/../vendor/autoload.php';
                  try {
                        $ws = new \WebSocket\Client("ws://localhost:8080");
                        $ws->send(json_encode($mensaje));
                        $ws->close();
                    } catch(Exception $e) {}

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
                  bitacora::registrar('modificar', 'Modificó un personal con cédula: '.$_POST['cedula_personal']);
                  
                  // Usar el modelo para obtener los datos del usuario activo
                  $usuario = personal::obtenerUsuarioPersonal($_SESSION['usuario']);
                  $foto = isset($usuario['foto_perfil']) && $usuario['foto_perfil'] ? 'img/perfiles/'.$usuario['foto_perfil'] : 'img/user.png';
                  $mensaje = [
                      'nombre' => $usuario['nombre'] . ' ' . $usuario['apellido'],
                      'foto' => $foto,
                      'descripcion' => 'Se ha modificado el personal: '.$_POST['nombre'].' '.$_POST['apellido'],
                      'fecha_hora' => date('Y-m-d H:i:s')
                  ];
                  require_once __DIR__ . '/../vendor/autoload.php';
                  try {
                        $ws = new \WebSocket\Client("ws://localhost:8080");
                        $ws->send(json_encode($mensaje));
                        $ws->close();
                    } catch(Exception $e) {}

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