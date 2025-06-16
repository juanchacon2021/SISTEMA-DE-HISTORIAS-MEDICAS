<?php
namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;
use Exception;

class inventario extends datos {
    private $cod_medicamento;
    private $nombre;
    private $descripcion;
    private $unidad_medida;
    private $cod_lote;
    private $cantidad_lote;
    private $fecha_vencimiento;
    private $proveedor;
    private $cod_salida;
    private $cedula_personal;

    // Setters
    public function set_cod_medicamento($valor) { $this->cod_medicamento = $valor; }
    public function set_nombre($valor) { $this->nombre = $valor; }
    public function set_descripcion($valor) { $this->descripcion = $valor; }
    public function set_unidad_medida($valor) { $this->unidad_medida = $valor; }
    public function set_cod_lote($valor) { $this->cod_lote = $valor; }
    public function set_cantidad_lote($valor) { $this->cantidad_lote = $valor; }
    public function set_fecha_vencimiento($valor) { $this->fecha_vencimiento = $valor; }
    public function set_proveedor($valor) { $this->proveedor = $valor; }
    public function set_cod_salida($valor) { $this->cod_salida = $valor; }
    public function set_cedula_personal($valor) { $this->cedula_personal = $valor; }

    // Getters
    public function get_cod_medicamento() { return $this->cod_medicamento; }
    public function get_nombre() { return $this->nombre; }
    public function get_descripcion() { return $this->descripcion; }
    public function get_unidad_medida() { return $this->unidad_medida; }
    public function get_cod_lote() { return $this->cod_lote; }
    public function get_cantidad_lote() { return $this->cantidad_lote; }
    public function get_fecha_vencimiento() { return $this->fecha_vencimiento; }
    public function get_proveedor() { return $this->proveedor; }
    public function get_cedula_personal() { return $this->cedula_personal; }

    // Métodos CRUD
    public function consultar_medicamentos() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("
                SELECT m.*, COALESCE(SUM(l.cantidad), 0) as stock_total
                FROM medicamentos m
                LEFT JOIN lotes l ON m.cod_medicamento = l.cod_medicamento
                GROUP BY m.cod_medicamento
                ORDER BY m.nombre
            ");
            
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

    public function consultar_lotes_medicamento($cod_medicamento) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("
                SELECT l.*, p.nombre as nombre_personal, p.apellido as apellido_personal
                FROM lotes l
                JOIN personal p ON l.cedula_personal = p.cedula_personal
                WHERE l.cod_medicamento = '$cod_medicamento'
                AND l.cantidad > 0
                ORDER BY l.fecha_vencimiento ASC
            ");
            
            if($resultado) {
                $r['resultado'] = 'consultar_lotes';
                $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $r['resultado'] = 'consultar_lotes';
                $r['datos'] = array();
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function consultar_movimientos() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            // Consulta para entradas (lotes)
            $entradas = $co->query("
                SELECT 
                    l.cod_lote as codigo,
                    m.nombre as medicamento,
                    l.cantidad,
                    m.unidad_medida,
                    l.fecha_vencimiento,
                    l.proveedor,
                    p.nombre as nombre_personal,
                    p.apellido as apellido_personal,
                    'Entrada' as tipo,
                    CURRENT_DATE() as fecha,
                    TIME_FORMAT(NOW(), '%H:%i') as hora
                FROM lotes l
                JOIN medicamentos m ON l.cod_medicamento = m.cod_medicamento
                JOIN personal p ON l.cedula_personal = p.cedula_personal
                ORDER BY l.cod_lote DESC
            ")->fetchAll(PDO::FETCH_ASSOC);
            
            // Consulta para salidas - CORREGIDA usando cod_movimiento en lugar de cod_salida
            $salidas = $co->query("
                SELECT 
                    s.cod_salida as codigo,
                    m.nombre as medicamento,
                    i.cantidad,
                    m.unidad_medida,
                    l.fecha_vencimiento,
                    l.proveedor,
                    p.nombre as nombre_personal,
                    p.apellido as apellido_personal,
                    'Salida' as tipo,
                    s.fecha,
                    s.hora
                FROM salida_medicamento s
                JOIN insumos i ON s.cod_salida = i.cod_movimiento
                JOIN lotes l ON i.cod_lote = l.cod_lote
                JOIN medicamentos m ON l.cod_medicamento = m.cod_medicamento
                JOIN personal p ON s.cedula_personal = p.cedula_personal
                ORDER BY s.fecha DESC, s.hora DESC
            ")->fetchAll(PDO::FETCH_ASSOC);
            
            // Combinar resultados
            $movimientos = array_merge($entradas, $salidas);
            
            // Ordenar por fecha y hora (más reciente primero)
            usort($movimientos, function($a, $b) {
                $fechaA = strtotime($a['fecha'] . ' ' . $a['hora']);
                $fechaB = strtotime($b['fecha'] . ' ' . $b['hora']);
                return $fechaB - $fechaA;
            });
            
            $r['resultado'] = 'consultar_movimientos';
            $r['datos'] = $movimientos;
            
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
                    
                    // Obtener lotes del medicamento
                    $lotes = $this->consultar_lotes_medicamento($this->cod_medicamento);
                    if($lotes['resultado'] == 'consultar_lotes') {
                        $r['lotes'] = $lotes['datos'];
                    }
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
                nombre, descripcion, unidad_medida
            ) VALUES(
                '$this->nombre', '$this->descripcion', '$this->unidad_medida'
            )");
            $cod_medicamento = $co->lastInsertId();
            $co->commit();
            $r['resultado'] = 'incluir_medicamento';
            $r['mensaje'] = 'Medicamento registrado con éxito';
            $r['cod_medicamento'] = $cod_medicamento;
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
                $co->beginTransaction();
                
                $co->query("UPDATE medicamentos SET
                    nombre = '$this->nombre',
                    descripcion = '$this->descripcion',
                    unidad_medida = '$this->unidad_medida'
                    WHERE cod_medicamento = '$this->cod_medicamento'");
                
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

    public function registrar_entrada() {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $co->beginTransaction();

            // Insertar el lote con el personal que lo registró
            $co->query("INSERT INTO lotes(
                cantidad, fecha_vencimiento, proveedor, cod_medicamento, cedula_personal
            ) VALUES(
                '$this->cantidad_lote', '$this->fecha_vencimiento', 
                '$this->proveedor', '$this->cod_medicamento', '$this->cedula_personal'
            )");

            $co->commit();
            $r['resultado'] = 'registrar_entrada';
            $r['mensaje'] = 'Entrada de medicamento registrada con éxito';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function registrar_salida() {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $co->beginTransaction();
            
            // Verificar que hay suficiente stock
            $stock = $co->query("SELECT SUM(cantidad) FROM lotes WHERE cod_medicamento = '$this->cod_medicamento'")
                        ->fetchColumn();
            
            if($stock < $this->cantidad_lote) {
                throw new Exception("No hay suficiente stock disponible (Stock actual: $stock)");
            }
            
            // Registrar la salida
            $co->query("INSERT INTO salida_medicamento(
                fecha, hora, cedula_personal
            ) VALUES(
                CURDATE(), TIME_FORMAT(NOW(), '%H:%i'), '$this->cedula_personal'
            )");
            
            $cod_salida = $co->lastInsertId();
            
            // Aplicar FIFO (First In First Out) para las salidas
            $lotes = $co->query("
                SELECT cod_lote, cantidad 
                FROM lotes 
                WHERE cod_medicamento = '$this->cod_medicamento' 
                AND cantidad > 0
                ORDER BY fecha_vencimiento ASC
            ")->fetchAll(PDO::FETCH_ASSOC);
            
            $cantidad_restante = $this->cantidad_lote;
            
            foreach($lotes as $lote) {
                if($cantidad_restante <= 0) break;
                
                $cantidad_a_descontar = min($lote['cantidad'], $cantidad_restante);
                
                // Registrar insumo en la salida - CORRECCIÓN: usar cod_movimiento en lugar de cod_salida
                $co->query("INSERT INTO insumos(
                    cod_movimiento, cod_lote, cantidad
                ) VALUES(
                    '$cod_salida', '{$lote['cod_lote']}', '$cantidad_a_descontar'
                )");
                
                // Actualizar el lote
                $co->query("UPDATE lotes SET 
                    cantidad = cantidad - $cantidad_a_descontar 
                    WHERE cod_lote = '{$lote['cod_lote']}'");
                
                $cantidad_restante -= $cantidad_a_descontar;
            }
            
            $co->commit();
            $r['resultado'] = 'registrar_salida';
            $r['mensaje'] = 'Salida de medicamento registrada con éxito';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function registrar_salida_multiple($salidas) {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $co->beginTransaction();

            // Registrar la salida global
            $co->query("INSERT INTO salida_medicamento(
                fecha, hora, cedula_personal
            ) VALUES(
                CURDATE(), TIME_FORMAT(NOW(), '%H:%i'), '$this->cedula_personal'
            )");
            $cod_salida = $co->lastInsertId();

            foreach ($salidas as $salida) {
                $cod_lote = intval($salida['cod_lote']);
                $cantidad = intval($salida['cantidad']);

                // Verificar stock del lote
                $stock = $co->query("SELECT cantidad FROM lotes WHERE cod_lote = '$cod_lote'")->fetchColumn();
                if ($stock === false || $stock < $cantidad) {
                    throw new Exception("Stock insuficiente en el lote $cod_lote (Stock actual: $stock)");
                }

                // Registrar insumo en la salida
                $co->query("INSERT INTO insumos(
                    cod_movimiento, cod_lote, cantidad
                ) VALUES(
                    '$cod_salida', '$cod_lote', '$cantidad'
                )");

                // Actualizar el lote
                $co->query("UPDATE lotes SET 
                    cantidad = cantidad - $cantidad 
                    WHERE cod_lote = '$cod_lote'");
            }

            $co->commit();
            $r['resultado'] = 'registrar_salida';
            $r['mensaje'] = 'Salida de medicamentos registrada con éxito';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function registrar_entrada_multiple($lotes) {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $co->beginTransaction();

            foreach ($lotes as $lote) {
                $cod_medicamento = intval($lote['cod_medicamento']);
                $cantidad = intval($lote['cantidad']);
                $fecha_vencimiento = $lote['fecha_vencimiento'];
                $proveedor = $lote['proveedor'];

                // Insertar el lote con el personal que lo registró
                $co->query("INSERT INTO lotes(
                    cantidad, fecha_vencimiento, proveedor, cod_medicamento, cedula_personal
                ) VALUES(
                    '$cantidad', '$fecha_vencimiento', '$proveedor', '$cod_medicamento', '$this->cedula_personal'
                )");
            }

            $co->commit();
            $r['resultado'] = 'registrar_entrada';
            $r['mensaje'] = 'Entradas de lotes registradas con éxito';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function eliminar() {
        $r = array();
        if($this->existe($this->cod_medicamento)) {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            try {
                // Verificar si hay stock antes de eliminar
                $stock = $co->query("SELECT SUM(cantidad) FROM lotes WHERE cod_medicamento = '$this->cod_medicamento'")
                            ->fetchColumn();
                
                if($stock > 0) {
                    $r['resultado'] = 'error';
                    $r['mensaje'] = 'No se puede eliminar el medicamento porque aún tiene stock disponible';
                    return $r;
                }
                
                $co->beginTransaction();
                
                // Eliminar registros relacionados en insumos (a través de lotes)
                $co->query("DELETE i FROM insumos i
                            JOIN lotes l ON i.cod_lote = l.cod_lote
                            WHERE l.cod_medicamento = '$this->cod_medicamento'");
                
                // Eliminar lotes del medicamento
                $co->query("DELETE FROM lotes WHERE cod_medicamento = '$this->cod_medicamento'");
                
                // Finalmente eliminar el medicamento
                $co->query("DELETE FROM medicamentos WHERE cod_medicamento = '$this->cod_medicamento'");
                
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