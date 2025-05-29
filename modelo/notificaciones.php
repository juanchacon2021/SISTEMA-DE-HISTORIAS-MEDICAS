<?php
require_once('../modelo/datos.php');

class notificaciones extends datos {
    public function obtener_notificaciones($limite = 10) {
        $co = $this->conecta2(); // Conexión a la BD seguridad
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $limite = intval($limite); // Seguridad: asegurarse que sea entero
            $stmt = $co->prepare("SELECT descripcion FROM bitacora ORDER BY fecha_hora ASC LIMIT $limite");
            $stmt->execute();
            $r['resultado'] = 'exito';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Depuración:
            // file_put_contents('debug.txt', print_r($r, true));
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
            // file_put_contents('debug.txt', $e->getMessage());
        }
        return $r;
    }
}
?>