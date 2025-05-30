<?php
require_once('modelo/datos.php');

class inventario extends datos {
    private $cod_medicamento;
    private $nombre;
    private $descripcion;
    private $cantidad;
    private $unidad_medida;
    private $fecha_vencimiento;
    private $lote;
    private $proveedor;
    private $cod_transaccion;
    private $tipo_transaccion;
    private $id_usuario;

    // Setters
    public function set_cod_medicamento($valor) { $this->cod_medicamento = $valor; }
    public function set_nombre($valor) { $this->nombre = $valor; }
    public function set_descripcion($valor) { $this->descripcion = $valor; }
    public function set_cantidad($valor) { $this->cantidad = $valor; }
    public function set_unidad_medida($valor) { $this->unidad_medida = $valor; }
    public function set_fecha_vencimiento($valor) { $this->fecha_vencimiento = $valor; }
    public function set_lote($valor) { $this->lote = $valor; }
    public function set_proveedor($valor) { $this->proveedor = $valor; }
    public function set_cod_transaccion($valor) { $this->cod_transaccion = $valor; }
    public function set_tipo_transaccion($valor) { $this->tipo_transaccion = $valor; }
    public function set_id_usuario($valor) { $this->id_usuario = $valor; }

    // Getters
    public function get_cod_medicamento() { return $this->cod_medicamento; }
    public function get_nombre() { return $this->nombre; }
    public function get_descripcion() { return $this->descripcion; }
    public function get_cantidad() { return $this->cantidad; }
    public function get_unidad_medida() { return $this->unidad_medida; }
    public function get_fecha_vencimiento() { return $this->fecha_vencimiento; }
    public function get_lote() { return $this->lote; }
    public function get_proveedor() { return $this->proveedor; }
    public function get_id_usuario() { return $this->id_usuario; }

    // Métodos CRUD
    public function consultar_medicamentos() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("SELECT * FROM medicamentos");
            if($resultado) {
                $r['resultado'] = 'consultar_medicamentos';
                $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $r['resultado'] = 'consultar_medicamentos';
                $r['datos'] = array();
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function consultar_transacciones() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("SELECT 
                    t.*, 
                    u.nombre as nombre_usuario,
                    m.nombre as medicamento, 
                    i.cantidad
                FROM transaccion t
                JOIN seguridad.usuario u ON t.id_usuario = u.id
                JOIN insumos i ON t.cod_transaccion = i.cod_transaccion
                JOIN medicamentos m ON i.cod_medicamento = m.cod_medicamento
                ORDER BY t.fecha DESC, t.hora DESC");
                
            if($resultado) {
                $r['resultado'] = 'consultar_transacciones';
                $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $r['resultado'] = 'consultar_transacciones';
                $r['datos'] = array();
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function obtener_medicamento() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("SELECT * FROM medicamentos WHERE cod_medicamento = '$this->cod_medicamento'");
            if($resultado) {
                $fila = $resultado->fetch(PDO::FETCH_ASSOC);
                if($fila) {
                    $r['resultado'] = 'obtener_medicamento';
                    $r['datos'] = $fila;
                } else {
                    $r['resultado'] = 'error';
                    $r['mensaje'] = 'Medicamento no encontrado';
                }
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Error al consultar medicamento';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function incluir() {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $co->beginTransaction();
            
            $co->query("INSERT INTO medicamentos(
                nombre, descripcion, cantidad, unidad_medida, 
                fecha_vencimiento, lote, proveedor
            ) VALUES(
                '$this->nombre', '$this->descripcion', '$this->cantidad', 
                '$this->unidad_medida', '$this->fecha_vencimiento', 
                '$this->lote', '$this->proveedor'
            )");
            
            $cod_medicamento = $co->lastInsertId();
            
            $co->query("INSERT INTO transaccion(
                tipo_transaccion, fecha, hora, id_usuario
            ) VALUES(
                'entrada', CURDATE(), TIME_FORMAT(NOW(), '%H:%i'), '$this->id_usuario'
            )");
            
            $cod_transaccion = $co->lastInsertId();
            
            $co->query("INSERT INTO insumos(
                cod_transaccion, cod_medicamento, cantidad
            ) VALUES(
                '$cod_transaccion', '$cod_medicamento', '$this->cantidad'
            )");
            
            $co->commit();
            $r['resultado'] = 'incluir_medicamento';
            $r['mensaje'] = 'Medicamento registrado con éxito';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function modificar() {
        $r = array();
        if($this->existe($this->cod_medicamento)) {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            try {
                $cant_anterior = $co->query("SELECT cantidad FROM medicamentos WHERE cod_medicamento = '$this->cod_medicamento'")
                                    ->fetchColumn();
                
                $co->beginTransaction();
                
                $co->query("UPDATE medicamentos SET
                    nombre = '$this->nombre',
                    descripcion = '$this->descripcion',
                    cantidad = '$this->cantidad',
                    unidad_medida = '$this->unidad_medida',
                    fecha_vencimiento = '$this->fecha_vencimiento',
                    lote = '$this->lote',
                    proveedor = '$this->proveedor'
                    WHERE cod_medicamento = '$this->cod_medicamento'");
                
                if($cant_anterior != $this->cantidad) {
                    $diferencia = $this->cantidad - $cant_anterior;
                    $tipo = $diferencia > 0 ? 'ajuste_positivo' : 'ajuste_negativo';
                    
                
                    $co->query("INSERT INTO transaccion(
                        tipo_transaccion, fecha, hora, id_usuario
                    ) VALUES(
                        '$tipo', CURDATE(), TIME_FORMAT(NOW(), '%H:%i'), '$this->id_usuario'
                    )");
                    
                    $cod_transaccion = $co->lastInsertId();
                    
                    $co->query("INSERT INTO insumos(
                        cod_transaccion, cod_medicamento, cantidad
                    ) VALUES(
                        '$cod_transaccion', '$this->cod_medicamento', '".abs($diferencia)."'
                    )");
                }
                
                $co->commit();
                $r['resultado'] = 'modificar_medicamento';
                $r['mensaje'] = 'Medicamento actualizado con éxito';
            } catch(Exception $e) {
                $co->rollBack();
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Medicamento no existe';
        }
        return $r;
    }

    public function eliminar() {
        $r = array();
        if($this->existe($this->cod_medicamento)) {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            try {
                $co->beginTransaction();

                // 1. Primero eliminar registros en insumos que referencian este medicamento
                $co->prepare("DELETE FROM insumos WHERE cod_medicamento = ?")
                ->execute([$this->cod_medicamento]);
                
                // 2. Luego eliminar el medicamento
                $co->prepare("DELETE FROM medicamentos WHERE cod_medicamento = ?")
                ->execute([$this->cod_medicamento]);

                $co->query("INSERT INTO transaccion(
                    tipo_transaccion, fecha, hora, id_usuario
                ) VALUES(
                    'salida', CURDATE(), TIME_FORMAT(NOW(), '%H:%i'), '$this->id_usuario'
                )");
                $co->commit();
                
                $r['resultado'] = 'eliminar_medicamento';
                $r['mensaje'] = 'Medicamento eliminado con éxito';
            } catch(Exception $e) {
                $co->rollBack();
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Error al eliminar: ' . $e->getMessage();
            }
        } else {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Medicamento no existe';
        }
        return $r;
    }

    private function existe($cod_medicamento) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $resultado = $co->query("SELECT * FROM medicamentos WHERE cod_medicamento = '$cod_medicamento'");
            return $resultado->rowCount() > 0;
        } catch(Exception $e) {
            return false;
        }
    }
}