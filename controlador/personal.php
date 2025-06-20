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
                echo  json_encode($o->incluir());
                bitacora::registrar('incluir', 'Incluyó un nuevo personal con cédula: '.$_POST['cedula_personal']);
              }
              elseif($accion=='modificar'){
                echo  json_encode($o->modificar());
                bitacora::registrar('modificar', 'Modificó un personal con cédula: '.$_POST['cedula_personal']);
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