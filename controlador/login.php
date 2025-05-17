<?php
if(!is_file("modelo/".$pagina.".php")){
    echo "Falta el modelo";
    exit;
}
require_once("modelo/".$pagina.".php"); 
if(is_file("vista/".$pagina.".php")){ 
    if(!empty($_POST)){
        
        $o = new entrada();
        
        if($_POST['accion']=='entrar'){
            $o->set_email($_POST['email']);
            $o->set_clave($_POST['clave']);  
            $m = $o->existe();
            if($m['resultado']=='existe'){
                session_destroy(); //elimina cualquier version anterior de sesion    
                session_start(); //inicia el entorno de sesion
                //asigna variables de sesión
                $_SESSION['nivel'] = $m['mensaje']; // rol_id
                $_SESSION['usuario'] = $m['usuario']; // id del usuario
                $_SESSION['nombre'] = $m['nombre']; // nombre del usuario
                
                header('Location: . ');
                die();
            }
            else{
                $mensaje = $m['mensaje'];
            }
        }
    }
    
    require_once("vista/".$pagina.".php"); 
}
else{
    echo "Falta la vista";
}
?>