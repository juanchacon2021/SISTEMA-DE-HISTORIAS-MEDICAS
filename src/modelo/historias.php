<?php

namespace Shm\Shm\modelo;

use Shm\Shm\modelo\datos;
use PDO;

class historias extends datos
{
    function consultar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT * FROM paciente ORDER BY apellido, nombre");
            $r['resultado'] = 'consultar';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Consultar emergencias de un paciente
    function consultarEmergencias($cedula_paciente)
    {
        // Validar cédula antes de consultar
        $val = $this->validar_cedula($cedula_paciente);
        if (!is_array($val) || $val['codigo'] !== 0) {
            return [];
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $co->prepare("SELECT e.*, p.nombre AS nombre_personal, p.apellido AS apellido_personal
                                  FROM emergencia e
                                  INNER JOIN personal p ON e.cedula_personal = p.cedula_personal
                                  WHERE e.cedula_paciente = ?
                                  ORDER BY e.fechaingreso DESC, e.horaingreso DESC");
            $stmt->execute([$cedula_paciente]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }

    // Consultar consultas médicas de un paciente
    function consultarConsultas($cedula_paciente)
    {
        // Validar cédula antes de consultar
        $val = $this->validar_cedula($cedula_paciente);
        if (!is_array($val) || $val['codigo'] !== 0) {
            return [];
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $co->prepare("SELECT c.*, p.nombre AS nombre_personal, p.apellido AS apellido_personal
                                  FROM consulta c
                                  INNER JOIN personal p ON c.cedula_personal = p.cedula_personal
                                  WHERE c.cedula_paciente = ?
                                  ORDER BY c.fechaconsulta DESC, c.Horaconsulta DESC");
            $stmt->execute([$cedula_paciente]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }

    // Consultar exámenes de un paciente (con tipo de examen)
    function consultarExamenes($cedula_paciente)
    {
        // Validar cédula antes de consultar
        $val = $this->validar_cedula($cedula_paciente);
        if (!is_array($val) || $val['codigo'] !== 0) {
            return [];
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $co->prepare("SELECT e.*, t.nombre_examen, t.descripcion_examen
                                  FROM examen e
                                  INNER JOIN tipo_de_examen t ON e.cod_examen = t.cod_examen
                                  WHERE e.cedula_paciente = ?
                                  ORDER BY e.fecha_e DESC, e.hora_e DESC");
            $stmt->execute([$cedula_paciente]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }

    // Puedes agregar aquí métodos auxiliares si necesitas para el PDF

    // Validar formato y existencia de la cédula de paciente
    private function validar_cedula($cedula_paciente)
    {
        $r = array('codigo' => 0, 'mensaje' => 'Cédula válida');

        // Formato: 7-8 dígitos (coherente con otras validaciones del proyecto)
        if (!isset($cedula_paciente) || !preg_match('/^[0-9]{7,8}$/', $cedula_paciente)) {
            $r['codigo'] = 1;
            $r['mensaje'] = 'Formato de cédula inválido';
            return $r;
        }

        // Verificar existencia en la base de datos
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $co->prepare("SELECT 1 FROM paciente WHERE cedula_paciente = ? LIMIT 1");
            $stmt->execute([$cedula_paciente]);
            $existe = ($stmt->fetchColumn() !== false);
            if (!$existe) {
                $r['codigo'] = 2;
                $r['mensaje'] = 'La cédula del paciente no existe';
                return $r;
            }
        } catch (\Exception $e) {
            $r['codigo'] = 3;
            $r['mensaje'] = 'Error al validar cédula: ' . $e->getMessage();
            return $r;
        }

        return $r;
    }
}
