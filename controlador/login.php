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
                session_destroy();
                session_start();
                $_SESSION['nivel'] = $m['mensaje']; // rol_id
                $_SESSION['usuario'] = $m['usuario']; // id usuario
                $_SESSION['nombre'] = $m['nombre']; // nombre usuario
                $_SESSION['permisos'] = $m['permisos']; // array de permisos
                
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