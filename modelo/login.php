<?php
require_once('modelo/datos.php');

class entrada extends datos{
    private $email; 
    private $clave;
    private $rol_id;
    
    function set_email($valor){
        $this->email = $valor; 
    }
    
    function set_clave($valor){
        $this->clave = $valor;
    }
    
    function get_email(){
        return $this->email;
    }
    
    function get_clave(){
        return $this->clave;
    }
    
    function existe() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            // Consulta para obtener el rol_id y nombre del usuario
            $p = $co->prepare("SELECT rol_id, nombre, id FROM usuario 
                             WHERE email = :email 
                             AND password = :clave");
            $p->bindParam(':email', $this->email);        
            $p->bindParam(':clave', $this->clave);
            $p->execute();
            
            $fila = $p->fetchAll(PDO::FETCH_BOTH);
            if ($fila) {
                $r['resultado'] = 'existe';
                $r['mensaje'] = $fila[0]['rol_id']; // ID del rol del usuario
                $r['usuario'] = $fila[0]['id']; // ID del usuario
                $r['nombre'] = $fila[0]['nombre']; // Nombre del usuario
            } else {
                $r['resultado'] = 'noexiste';
                $r['mensaje'] = "Error en email y/o clave !!!";
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
}
?>