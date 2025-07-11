<?php
namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;
use Exception;

class inventario extends datos {

    // Consultar todos los medicamentos
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
            $r['resultado'] = 'consultar_medicamentos';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Consultar lotes de un medicamento
    public function consultar_lotes_medicamento($cod_medicamento) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $stmt = $co->prepare("
                SELECT l.*, p.nombre as nombre_personal, p.apellido as apellido_personal
                FROM lotes l
                JOIN personal p ON l.cedula_personal = p.cedula_personal
                WHERE l.cod_medicamento = ?
                AND l.cantidad > 0
                ORDER BY l.fecha_vencimiento ASC
            ");
            $stmt->execute([$cod_medicamento]);
            $r['resultado'] = 'consultar_lotes';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Consultar movimientos (entradas y salidas)
    public function consultar_movimientos() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            // Entradas
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

            // Salidas
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

            $movimientos = array_merge($entradas, $salidas);
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

    // Obtener un medicamento
    public function obtener_medicamento($cod_medicamento) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $stmt = $co->prepare("SELECT * FROM medicamentos WHERE cod_medicamento = ?");
            $stmt->execute([$cod_medicamento]);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            if($fila) {
                $r['resultado'] = 'obtener_medicamento';
                $r['datos'] = $fila;
                // Lotes
                $lotes = $this->consultar_lotes_medicamento($cod_medicamento);
                if($lotes['resultado'] == 'consultar_lotes') {
                    $r['lotes'] = $lotes['datos'];
                }
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Medicamento no encontrado';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Incluir medicamento
    public function incluir($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $co->beginTransaction();

            // Llamar al procedimiento almacenado con stock_min
            $stmt = $co->prepare("CALL insertar_medicamento(:nombre, :descripcion, :unidad_medida, :stock_min, @cod_generado)");
            $stmt->execute([
                ':nombre' => $datos['nombre'] ?? '',
                ':descripcion' => $datos['descripcion'] ?? '',
                ':unidad_medida' => $datos['unidad_medida'] ?? '',
                ':stock_min' => intval($datos['stock_min'] ?? 0)
            ]);

            // Obtener el código generado
            $cod = $co->query("SELECT @cod_generado AS cod_medicamento")->fetch(PDO::FETCH_ASSOC);
            $cod_medicamento = $cod['cod_medicamento'] ?? null;

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

    // Modificar medicamento
    public function modificar($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $stmt = $co->prepare("UPDATE medicamentos SET nombre = ?, descripcion = ?, unidad_medida = ?, stock_min = ? WHERE cod_medicamento = ?");
            $stmt->execute([
                $datos['nombre'] ?? '',
                $datos['descripcion'] ?? '',
                $datos['unidad_medida'] ?? '',
                intval($datos['stock_min'] ?? 0),
                $datos['cod_medicamento']
            ]);
            $r['resultado'] = 'modificar_medicamento';
            $r['mensaje'] = 'Medicamento actualizado con éxito';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Registrar entrada de medicamento (nuevo lote)
    public function registrar_entrada($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            // Calcular stock actual
            $stmt = $co->prepare("SELECT COALESCE(SUM(cantidad),0) FROM lotes WHERE cod_medicamento = ?");
            $stmt->execute([$datos['cod_medicamento']]);
            $stock_actual = $stmt->fetchColumn();

            $cantidad_nueva = intval($datos['cantidad']);
            $stock_maximo = 250;

            if (($stock_actual + $cantidad_nueva) > $stock_maximo) {
                $cantidad_permitida = $stock_maximo - $stock_actual;
                $r['resultado'] = 'error';
                $r['mensaje'] = "El stock máximo es de 250. Solo puedes agregar $cantidad_permitida unidades.";
                return $r;
            }

            $co->beginTransaction();
            $stmt = $co->prepare("INSERT INTO lotes(cantidad, fecha_vencimiento, proveedor, cod_medicamento, cedula_personal) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $datos['cantidad'],
                $datos['fecha_vencimiento'],
                $datos['proveedor'],
                $datos['cod_medicamento'],
                $datos['cedula_personal']
            ]);
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

    // Registrar salida de medicamento
    public function registrar_salida($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $co->beginTransaction();
            // Verificar stock actual y stock mínimo
            $stmt = $co->prepare("SELECT SUM(cantidad) as stock, (SELECT stock_min FROM medicamentos WHERE cod_medicamento = ?) as stock_min FROM lotes WHERE cod_medicamento = ?");
            $stmt->execute([$datos['cod_medicamento'], $datos['cod_medicamento']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stock = intval($row['stock']);
            $stock_min = intval($row['stock_min']);
            $cantidad_salida = intval($datos['cantidad']);

            // DEPURACIÓN: log temporal
            file_put_contents('debug_stock.txt', date('Y-m-d H:i:s')." | cod_medicamento: {$datos['cod_medicamento']} | stock: $stock | stock_min: $stock_min | cantidad_salida: $cantidad_salida\n", FILE_APPEND);

            if($stock < $cantidad_salida) {
                throw new Exception("No hay suficiente stock disponible (Stock actual: $stock)");
            }
            if(($stock - $cantidad_salida) < $stock_min) {
                throw new Exception("No puedes realizar esta salida porque el stock quedaría por debajo del mínimo permitido ($stock_min). Stock actual: $stock");
            }
            // Registrar salida usando procedure
            $stmt = $co->prepare("CALL registrar_salida_medicamento(CURDATE(), TIME_FORMAT(NOW(), '%H:%i'), ?, @cod_salida)");
            $stmt->execute([$datos['cedula_personal']]);
            $cod_salida = $co->query("SELECT @cod_salida AS cod_salida")->fetch(PDO::FETCH_ASSOC)['cod_salida'];
            // FIFO para lotes
            $lotes = $co->prepare("SELECT cod_lote, cantidad FROM lotes WHERE cod_medicamento = ? AND cantidad > 0 ORDER BY fecha_vencimiento ASC, cod_lote ASC");
            $lotes->execute([$datos['cod_medicamento']]);
            $cantidad = $datos['cantidad'];
            foreach($lotes as $lote) {
                if($cantidad <= 0) break;
                $usar = min($cantidad, $lote['cantidad']);
                // Registrar insumo
                $co->prepare("INSERT INTO insumos(cod_movimiento, cod_lote, cantidad) VALUES (?, ?, ?)
                ")->execute([$cod_salida, $lote['cod_lote'], $usar]);
                // Actualizar lote
                $co->prepare("UPDATE lotes SET cantidad = cantidad - ? WHERE cod_lote = ?")
                    ->execute([$usar, $lote['cod_lote']]);
                $cantidad -= $usar;
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

    // Eliminar medicamento
    public function eliminar($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            // Verificar stock
            $stmt = $co->prepare("SELECT SUM(cantidad) FROM lotes WHERE cod_medicamento = ?");
            $stmt->execute([$datos['cod_medicamento']]);
            $stock = $stmt->fetchColumn();
            if($stock > 0) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar el medicamento porque aún tiene stock disponible';
                return $r;
            }
            $co->beginTransaction();
            // Eliminar insumos
            $co->prepare("DELETE i FROM insumos i JOIN lotes l ON i.cod_lote = l.cod_lote WHERE l.cod_medicamento = ?")
                ->execute([$datos['cod_medicamento']]);
            // Eliminar lotes
            $co->prepare("DELETE FROM lotes WHERE cod_medicamento = ?")
                ->execute([$datos['cod_medicamento']]);
            // Eliminar medicamento
            $co->prepare("DELETE FROM medicamentos WHERE cod_medicamento = ?")
                ->execute([$datos['cod_medicamento']]);
            $co->commit();
            $r['resultado'] = 'eliminar_medicamento';
            $r['mensaje'] = 'Medicamento eliminado con éxito';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Error al eliminar: ' . $e->getMessage();
        }
        return $r;
    }

    // Registrar entrada múltiple
    public function registrar_entrada_multiple($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        $codigos_generados = [];
        try {
            // Calcular stock actual
            $stmt = $co->prepare("SELECT COALESCE(SUM(cantidad),0) FROM lotes WHERE cod_medicamento = ?");
            $stmt->execute([$datos['lotes'][0]['cod_medicamento']]);
            $stock_actual = $stmt->fetchColumn();

            // Calcular total a ingresar
            $total_a_ingresar = 0;
            foreach($datos['lotes'] as $lote) {
                $total_a_ingresar += intval($lote['cantidad']);
            }
            $stock_maximo = 250;
            if (($stock_actual + $total_a_ingresar) > $stock_maximo) {
                $cantidad_permitida = $stock_maximo - $stock_actual;
                $r['resultado'] = 'error';
                $r['mensaje'] = "El stock máximo es de 250. Solo puedes agregar $cantidad_permitida unidades.";
                return $r;
            }

            $co->beginTransaction();
            foreach($datos['lotes'] as $lote) {
                // Llamar al procedimiento almacenado para cada lote
                $stmt = $co->prepare("CALL insertar_lote(:cantidad, :fecha_vencimiento, :proveedor, :cod_medicamento, :cedula_personal, @cod_generado)");
                $stmt->execute([
                    ':cantidad' => $lote['cantidad'],
                    ':fecha_vencimiento' => $lote['fecha_vencimiento'],
                    ':proveedor' => $lote['proveedor'],
                    ':cod_medicamento' => $lote['cod_medicamento'],
                    ':cedula_personal' => $datos['cedula_personal']
                ]);
                // Obtener el código generado para este lote
                $cod = $co->query("SELECT @cod_generado AS cod_lote")->fetch(PDO::FETCH_ASSOC);
                $codigos_generados[] = $cod['cod_lote'] ?? null;
            }
            $co->commit();
            $r['resultado'] = 'registrar_entrada';
            $r['mensaje'] = 'Entradas de lotes registradas con éxito';
            $r['codigos_lotes'] = $codigos_generados;
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Registrar salida múltiple
    public function registrar_salida_multiple($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $co->beginTransaction();
            // Generar cod_salida usando el procedure
            $stmt = $co->prepare("CALL registrar_salida_medicamento(CURDATE(), TIME_FORMAT(NOW(), '%H:%i'), ?, @cod_salida)");
            $stmt->execute([$datos['cedula_personal']]);
            $cod_salida = $co->query("SELECT @cod_salida AS cod_salida")->fetch(PDO::FETCH_ASSOC)['cod_salida'];

            foreach($datos['salidas'] as $salida) {
                $cod_lote = $salida['cod_lote'];
                $cantidad = intval($salida['cantidad']);
                // Verificar stock del lote
                $stock = $co->query("SELECT cantidad FROM lotes WHERE cod_lote = '$cod_lote'")->fetchColumn();
                if ($stock === false || $stock < $cantidad) {
                    throw new Exception("Stock insuficiente en el lote $cod_lote (Stock actual: $stock)");
                }
                // Registrar insumo
                $co->prepare("INSERT INTO insumos(cod_movimiento, cod_lote, cantidad) VALUES (?, ?, ?)
                ")->execute([$cod_salida, $cod_lote, $cantidad]);
                // Actualizar lote
                $co->prepare("UPDATE lotes SET cantidad = cantidad - ? WHERE cod_lote = ?")
                    ->execute([$cantidad, $cod_lote]);
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
}