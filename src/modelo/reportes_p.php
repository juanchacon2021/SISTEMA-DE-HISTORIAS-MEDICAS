<?php

namespace Shm\Shm\modelo;

use Shm\Shm\modelo\datos;
use PDO;
use Exception;

class reportes_p extends datos
{
    public function obtenerEstructuraTabla($tabla)
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "DESCRIBE $tabla";
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
            // Construir SELECT
            if (empty($campos)) {
                $sql = "SELECT * FROM $tabla";
            } else {
                $sql = "SELECT " . implode(', ', $campos) . " FROM $tabla";
            }

            // Construir WHERE
            $where = array();
            $params = array();
            foreach ($filtros as $filtro) {
                if (!empty($filtro['valor'])) {
                    $where[] = $filtro['campo'] . ' ' . $filtro['operador'] . ' ?';
                    $params[] = $filtro['valor'];
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