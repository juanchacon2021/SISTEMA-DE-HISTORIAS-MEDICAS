<?php
require_once("modelo/datos.php");
class verifica extends datos{
    function leesesion(){
        if(empty($_SESSION)){
            session_start();
        }
        if(isset($_SESSION['nivel'])){
            $s = $_SESSION['nivel'];
        }      
        else{
            $s = "";
        }
        return $s;
    }
    
    function destruyesesion(){
        session_start();
        session_destroy();
        header("Location: . ");
    }
    
    // Función para verificar permisos
   // En modelo/verifica.php
function verificarPermiso($modulo, $rol_id) {
    $co = $this->conecta2();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    try {
        $consulta = "SELECT COUNT(*) as tiene_permiso FROM permiso 
                     WHERE rol_id = :rol_id AND modulo_id = 
                     (SELECT id FROM modulo WHERE nombre = :modulo)";
        $stmt = $co->prepare($consulta);
        $stmt->bindParam(':rol_id', $rol_id);
        $stmt->bindParam(':modulo', $modulo);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['tiene_permiso'] > 0;
    } catch (Exception $e) {
        // Para depuración
        error_log("Error al verificar permiso: " . $e->getMessage());
        return false;
    }
}
}
?>