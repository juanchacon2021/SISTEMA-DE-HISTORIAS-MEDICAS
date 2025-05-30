<?php
require_once('../modelo/datos.php');

class notificaciones extends datos {
    public function obtener_notificaciones($limite = 10) {
        $co = $this->conecta2(); 
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $limite = intval($limite); 
            $stmt = $co->prepare("SELECT descripcion FROM bitacora ORDER BY fecha_hora ASC LIMIT $limite");
            $stmt->execute();
            $r['resultado'] = 'exito';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
           
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
            
        }
        return $r;
    }
}
?>