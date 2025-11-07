<?php

namespace Shm\Shm\modelo;

use Shm\Shm\modelo\datos;
use PDO;
use Exception;

class reportes_p extends datos
{
    // Nueva función privada para validar tabla, columnas y filtros
    private function validarTablaYColumnas($conexion, $tabla, $campos = array(), $filtros = array(), &$columnasDisponibles = array())
    {
        // Nombre de tabla seguro (solo alfanumérico y guion bajo)
        if (!preg_match('/^[A-Za-z0-9_]+$/', $tabla)) {
            return ['ok' => false, 'mensaje' => 'Nombre de tabla inválido'];
        }

        // Verificar existencia de la tabla
        $stmt = $conexion->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$tabla]);
        if (!$stmt->fetch(PDO::FETCH_NUM)) {
            return ['ok' => false, 'mensaje' => "Tabla '{$tabla}' no encontrada"];
        }

        // Obtener columnas reales de la tabla
        $stmt = $conexion->prepare("DESCRIBE `$tabla`");
        $stmt->execute();
        $columnasDisponibles = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $columnasDisponibles[] = $fila['Field'];
        }

        // Validar campos solicitados (si hay)
        if (!empty($campos)) {
            if (!is_array($campos)) {
                return ['ok' => false, 'mensaje' => 'Campos inválidos'];
            }
            foreach ($campos as $c) {
                if (!in_array($c, $columnasDisponibles)) {
                    return ['ok' => false, 'mensaje' => "Campo solicitado '{$c}' no existe en la tabla"];
                }
            }
        }

        // Validar filtros
        $operadoresPermitidos = ['LIKE', '=', '!=', '>', '<', '>=', '<='];
        if (!empty($filtros)) {
            if (!is_array($filtros)) {
                return ['ok' => false, 'mensaje' => 'Filtros inválidos'];
            }
            foreach ($filtros as $f) {
                if (!isset($f['campo']) || !isset($f['operador']) || !array_key_exists('valor', $f)) {
                    return ['ok' => false, 'mensaje' => 'Estructura de filtro inválida'];
                }
                if (!in_array($f['campo'], $columnasDisponibles)) {
                    return ['ok' => false, 'mensaje' => "Campo de filtro '{$f['campo']}' no existe"];
                }
                if (!in_array(strtoupper($f['operador']), $operadoresPermitidos)) {
                    return ['ok' => false, 'mensaje' => "Operador '{$f['operador']}' no permitido"];
                }
            }
        }

        return ['ok' => true];
    }

    public function obtenerEstructuraTabla($tabla)
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            // validar nombre de tabla básico
            if (!preg_match('/^[A-Za-z0-9_]+$/', $tabla)) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Nombre de tabla inválido';
                return $r;
            }

            $sql = "DESCRIBE `$tabla`";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();

            $campos = array();
            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $campos[] = array(
                    'nombre' => $fila['Field'],
                    'tipo' => $fila['Type'],
                    'nulo' => $fila['Null'],
                    'clave' => $fila['Key'],
                    'default' => $fila['Default'],
                    'extra' => $fila['Extra']
                );
            }

            $r['resultado'] = 'consultar';
            $r['datos'] = $campos;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    public function obtenerDatosTabla($tabla, $filtros = array(), $campos = array())
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            // Validar tabla, campos y filtros antes de construir la consulta
            $columnasDisponibles = [];
            $val = $this->validarTablaYColumnas($conexion, $tabla, $campos, $filtros, $columnasDisponibles);
            if (!$val['ok']) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $val['mensaje'];
                return $r;
            }

            // Construir SELECT
            if (empty($campos)) {
                $sql = "SELECT * FROM `$tabla`";
            } else {
                // ya validados: se puede mapear con backticks
                $camposSeguros = array_map(function ($c) {
                    return "`$c`";
                }, $campos);
                $sql = "SELECT " . implode(', ', $camposSeguros) . " FROM `$tabla`";
            }

            // Construir WHERE
            $where = array();
            $params = array();
            foreach ($filtros as $filtro) {
                // Sólo procesar filtros con valor no vacío
                if ($filtro['valor'] !== '' && $filtro['valor'] !== null) {
                    $operador = strtoupper($filtro['operador']);
                    // Campo ya validado; proteger con backticks
                    $campoSeguro = "`" . $filtro['campo'] . "`";

                    // Para LIKE, se espera que el frontend ya haya añadido %
                    if ($operador === 'LIKE') {
                        $where[] = $campoSeguro . ' LIKE ?';
                        $params[] = $filtro['valor'];
                    } else {
                        $where[] = $campoSeguro . ' ' . $operador . ' ?';
                        $params[] = $filtro['valor'];
                    }
                }
            }

            if (!empty($where)) {
                $sql .= ' WHERE ' . implode(' AND ', $where);
            }

            // Ejecutar consulta
            $stmt = $conexion->prepare($sql);
            $stmt->execute($params);

            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $r['sql'] = $sql; // Para depuración
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    public function obtenerTablasDisponibles()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "SHOW TABLES";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();

            $tablas = array();
            while ($fila = $stmt->fetch(PDO::FETCH_NUM)) {
                $tablas[] = $fila[0];
            }

            $r['resultado'] = 'consultar';
            $r['datos'] = $tablas;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    private function cerrar_conexion(&$conexion)
    {
        if ($conexion) {
            $conexion = null;
        }
    }
}