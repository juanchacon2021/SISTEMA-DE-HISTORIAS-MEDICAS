<?php
class inventario {
    private $db;
    
    public function __construct() {
        require_once("modelo/datos.php");
        if (!function_exists('conecta')) {
            function conecta() {
                try {
                    $dsn = "mysql:host=localhost;dbname=shm-cdi.2;charset=utf8";
                    $username = "root";
                    $password = "123456";
                    return new PDO($dsn, $username, $password, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                } catch (PDOException $e) {
                    die("Database connection failed: " . $e->getMessage());
                }
            }
        }
        $this->db = conecta();
    }
    
    public function agregarMedicamento($nombre, $descripcion, $cantidad, $unidad_medida, $fecha_vencimiento, $lote, $proveedor, $cedula_p) {
        $stmt = $this->db->prepare("INSERT INTO medicamentos 
                                   (nombre, descripcion, cantidad, unidad_medida, fecha_vencimiento, lote, proveedor, cedula_p) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $cantidad, $unidad_medida, $fecha_vencimiento, $lote, $proveedor, $cedula_p]);
        return $this->db->lastInsertId();
    }
    
    public function modificarMedicamento($cod_medicamento, $nombre, $descripcion, $cantidad, $unidad_medida, $fecha_vencimiento, $lote, $proveedor) {
        $stmt = $this->db->prepare("UPDATE medicamentos 
                                   SET nombre = ?, descripcion = ?, cantidad = ?, unidad_medida = ?, 
                                       fecha_vencimiento = ?, lote = ?, proveedor = ? 
                                   WHERE cod_medicamento = ?");
        return $stmt->execute([$nombre, $descripcion, $cantidad, $unidad_medida, $fecha_vencimiento, $lote, $proveedor, $cod_medicamento]);
    }
    
    public function eliminarMedicamento($cod_medicamento) {
        $stmt = $this->db->prepare("DELETE FROM medicamentos WHERE cod_medicamento = ?");
        return $stmt->execute([$cod_medicamento]);
    }
    
    public function obtenerMedicamentos() {
        $stmt = $this->db->query("SELECT m.*, p.nombre as nombre_personal, p.apellido 
                                 FROM medicamentos m 
                                 JOIN personal p ON m.cedula_p = p.cedula_personal 
                                 ORDER BY m.nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerMedicamento($cod_medicamento) {
        $stmt = $this->db->prepare("SELECT * FROM medicamentos WHERE cod_medicamento = ?");
        $stmt->execute([$cod_medicamento]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function obtenerTotalMedicamentos() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM medicamentos");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    public function obtenerTotalStock() {
        $stmt = $this->db->query("SELECT SUM(cantidad) as total FROM medicamentos");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>