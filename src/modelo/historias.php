<?php
namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;

class historias extends datos {
    function consultar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT * FROM paciente ORDER BY apellido, nombre");
            $r['resultado'] = 'consultar';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(\Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Consultar emergencias de un paciente
    function consultarEmergencias($cedula_paciente) {
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
        } catch(\Exception $e) {
            return [];
        }
    }

    // Consultar consultas médicas de un paciente
    function consultarConsultas($cedula_paciente) {
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
        } catch(\Exception $e) {
            return [];
        }
    }

    // Consultar exámenes de un paciente (con tipo de examen)
    function consultarExamenes($cedula_paciente) {
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
        } catch(\Exception $e) {
            return [];
        }
    }

    // Puedes agregar aquí métodos auxiliares si necesitas para el PDF
}