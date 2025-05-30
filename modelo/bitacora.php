<?php
require_once('modelo/datos.php');

class bitacora extends datos {
    public static function registrar($accion, $descripcion = null) {
        if(!isset($_SESSION['usuario'])) return false;
        
        $obj = new self();
        $co = $obj->conecta2(); 
        if(!$co) return false;
        
        try {
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pagina = $_GET['pagina'] ?? 'principal';
            $modulo_id = self::mapearModulo($pagina);
            
            $stmt = $co->prepare("INSERT INTO bitacora (
                usuario_id, modulo_id, accion, descripcion
            ) VALUES (
                :usuario_id, :modulo_id, :accion, :descripcion
            )");
            
            $stmt->execute(array(
                ':usuario_id' => $_SESSION['usuario'],
                ':modulo_id' => $modulo_id,
                ':accion' => $accion,
                ':descripcion' => $descripcion
            ));
            
            return true;
        } catch(Exception $e) {
            error_log("Error en bitácora: " . $e->getMessage());
            return false;
        }
    }
    
    public static function consultar($filtro = '', $limit = 100) {
        $obj = new self();
        $co = $obj->conecta2();
        
        if(!$co) return array();
        
        try {
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT b.*, u.nombre as usuario_nombre, m.nombre as modulo_nombre 
                    FROM bitacora b
                    JOIN usuario u ON b.usuario_id = u.id
                    JOIN modulo m ON b.modulo_id = m.id";
            
            $params = array();
            
            if(!empty($filtro)) {
                $sql .= " WHERE (u.nombre LIKE :filtro OR m.nombre LIKE :filtro OR b.accion LIKE :filtro OR b.descripcion LIKE :filtro)";
                $params[':filtro'] = "%$filtro%";
            }
            
            $sql .= " ORDER BY b.fecha_hora DESC LIMIT :limit";
            $params[':limit'] = $limit;
            
            $stmt = $co->prepare($sql);
            
            foreach($params as $key => $value) {
                $paramType = ($key == ':limit') ? PDO::PARAM_INT : PDO::PARAM_STR;
                $stmt->bindValue($key, $value, $paramType);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch(Exception $e) {
            error_log("Error consultando bitácora: " . $e->getMessage());
            return array();
        }
    }
    
    private static function mapearModulo($pagina) {
        $modulos = array(
            'principal' => 0,
            'pasantias' => 7,
            'consultas' => 6,
            'emergencias' => 4,
            'examenes' => 3,
            'p_cronicos' => 8,
            'jornadas' => 9,
            'inventario' => 10,
            'personal' => 2,
            'pacientes' => 1,
            'planificacion' => 5,
            'bitacora' => 11,
            'login' => 0,
            'salida' => 0,
            'usuarios' => 12,
            'estadistica' => 13
        );
        
        return $modulos[$pagina] ?? 0;
    }
}
?>