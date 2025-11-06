<?php

namespace Shm\Shm\modelo;

use Shm\Shm\modelo\datos;
use PDO;
use Exception;


class consultasm extends datos
{

    private function validar_campos($datos)
    {
        $r = array('codigo' => 0, 'mensaje' => 'Campos válidos');

        if (!isset($datos['cedula_paciente']) || !preg_match('/^[0-9]{7,8}$/', $datos['cedula_paciente'])) {
            $r['codigo'] = 1;
            $r['mensaje'] = 'Formato de cédula de paciente incorrecto (debe ser 7 u 8 dígitos).';
            return $r;
        }

        if (!isset($datos['cedula_personal']) || !preg_match('/^[0-9]{7,8}$/', $datos['cedula_personal'])) {
            $r['codigo'] = 2;
            $r['mensaje'] = 'Formato de cédula de personal incorrecto (debe ser 7 u 8 dígitos).';
            return $r;
        }

        if (!isset($datos['consulta']) || !preg_match('/^[A-Za-z\sñÑáéíóúÁÉÍÓÚ]{3,300}$/', $datos['consulta'])) {
            $r['codigo'] = 3;
            $r['mensaje'] = 'La descripción de la consulta es inválida (solo letras y espacios, 3-300 caracteres).';
            return $r;
        }

        if (!isset($datos['diagnostico']) || !preg_match('/^[A-Za-z\sñÑáéíóúÁÉÍÓÚ]{3,300}$/', $datos['diagnostico'])) {
            $r['codigo'] = 4;
            $r['mensaje'] = 'El diagnóstico es inválido (solo letras y espacios, 3-300 caracteres).';
            return $r;
        }

        if (!isset($datos['tratamientos']) || !preg_match('/^[A-Za-z\sñÑáéíóúÁÉÍÓÚ]{3,300}$/', $datos['tratamientos'])) {
            $r['codigo'] = 5;
            $r['mensaje'] = 'El tratamiento es inválido (solo letras y espacios, 3-300 caracteres).';
            return $r;
        }

        // Validación de fecha: formato YYYY-MM-DD
        if (!isset($datos['fechaconsulta']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $datos['fechaconsulta']) || !\DateTime::createFromFormat('Y-m-d', $datos['fechaconsulta'])) {
            $r['codigo'] = 6;
            $r['mensaje'] = 'El formato de la fecha de consulta es incorrecto.';
            return $r;
        }

        if (
            !isset($datos['Horaconsulta']) ||
            !preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $datos['Horaconsulta'])
        ) {
            $r['codigo'] = 7;
            $r['mensaje'] = 'El formato de la hora de consulta es incorrecto. Debe ser HH:MM en formato 24 horas.';
            return $r;
        }

        return $r;
    }


    function listadopersonal()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            // Consulta preparada
            $stmt = $co->prepare("SELECT * FROM personal");
            // Ejecutar la consulta
            $stmt->execute();
            // Obtener resultados
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($resultados) {
                $r['resultado'] = 'listadopersonal';
                $r['datos'] = $resultados;
            } else {
                $r['resultado'] = 'listadopersonal';
                $r['datos'] = array();
            }
            // Cerrar el cursor
            $stmt->closeCursor();
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }


    function listadopacientes()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            // Consulta preparada
            $stmt = $co->prepare("SELECT * FROM paciente");
            // Ejecutar la consulta
            $stmt->execute();
            // Obtener resultados
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($resultados) {
                $r['resultado'] = 'listadopacientes';
                $r['datos'] = $resultados;
            } else {
                $r['resultado'] = 'listadopacientes';
                $r['datos'] = array();
            }
            // Cerrar el cursor
            $stmt->closeCursor();
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    function incluir($datos, $observaciones = array())
    {
        $r = array();

        // 1. Validar formato de campos
        $val_campos = $this->validar_campos($datos);
        if ($val_campos['codigo'] !== 0) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $val_campos['mensaje'];
            return $r;
        }

        // 2. Validar existencia de cédulas en BD
        $val = $this->validar_cedulas($datos['cedula_paciente'], $datos['cedula_personal']);
        if (!is_array($val) || !isset($val['codigo'])) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Error al validar cédulas';
            return $r;
        }
        if ($val['codigo'] !== 0) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $val['mensaje'];
            return $r;
        }

        if (!$this->existe($datos['cod_consulta'])) {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try {
                $co->beginTransaction();

                // 1. Llamar al procedimiento almacenado para insertar la consulta
                $stmtConsulta = $co->prepare("CALL insertar_consulta(?, ?, ?, ?, ?, ?, ?, @cod_generado)");
                $stmtConsulta->execute([
                    $datos['fechaconsulta'],
                    $datos['Horaconsulta'],
                    $datos['consulta'],
                    $datos['diagnostico'],
                    $datos['tratamientos'],
                    $datos['cedula_personal'],
                    $datos['cedula_paciente']
                ]);

                // Obtener el código generado por el procedimiento
                $stmtCodigo = $co->query("SELECT @cod_generado as cod_consulta");
                $resultadoCodigo = $stmtCodigo->fetch(PDO::FETCH_ASSOC);
                $cod_consulta = $resultadoCodigo['cod_consulta'];

                // 2. Insertar observaciones (si existen)
                if (!empty($observaciones) && is_array($observaciones)) {
                    $stmtObservaciones = $co->prepare("INSERT INTO observacion_consulta 
                        (cod_consulta, cod_observacion, observacion) 
                        VALUES (?, ?, ?)");
                    foreach ($observaciones as $obs) {
                        // Validación simple de observación
                        if (!empty($obs['cod_observacion']) && isset($obs['observacion'])) {
                            // Validar longitud de la observación antes de insertar
                            $obs_text = $obs['observacion'];
                            if (strlen($obs_text) > 500) { // Límite arbitrario de 500 caracteres
                                $obs_text = substr($obs_text, 0, 500);
                            }
                            $stmtObservaciones->execute([
                                $cod_consulta,
                                $obs['cod_observacion'],
                                $obs_text
                            ]);
                        }
                    }
                }

                $co->commit();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Registro Incluido';
                $r['cod_consulta'] = $cod_consulta;
            } catch (Exception $e) {
                $co->rollBack();
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Ya existe el Cod de Consulta';
        }
        return $r;
    }

    function modificar($datos, $observaciones = array())
    {
        $r = array();

        // 1. Validar formato de campos
        $val_campos = $this->validar_campos($datos);
        if ($val_campos['codigo'] !== 0) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $val_campos['mensaje'];
            return $r;
        }

        // 2. Validar existencia de cédulas en BD
        $val = $this->validar_cedulas($datos['cedula_paciente'], $datos['cedula_personal']);
        if (!is_array($val) || !isset($val['codigo'])) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Error al validar cédulas';
            return $r;
        }
        if ($val['codigo'] !== 0) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $val['mensaje'];
            return $r;
        }

        if ($this->existe($datos['cod_consulta'])) {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try {
                // Iniciar transacción
                $co->beginTransaction();

                // 1. Actualizar la consulta principal (CONSULTA PREPARADA)
                $stmtUpdate = $co->prepare("UPDATE consulta SET
                    fechaconsulta = ?,
                    Horaconsulta = ?,
                    consulta = ?,
                    diagnostico = ?,
                    tratamientos = ?,
                    cedula_personal = ?,
                    cedula_paciente = ?
                    WHERE cod_consulta = ?");

                $stmtUpdate->execute([
                    $datos['fechaconsulta'],
                    $datos['Horaconsulta'],
                    $datos['consulta'],
                    $datos['diagnostico'],
                    $datos['tratamientos'],
                    $datos['cedula_personal'],
                    $datos['cedula_paciente'],
                    $datos['cod_consulta']
                ]);

                // 2. Eliminar observaciones anteriores (CONSULTA PREPARADA)
                $stmtDelete = $co->prepare("DELETE FROM observacion_consulta WHERE cod_consulta = ?");
                $stmtDelete->execute([$datos['cod_consulta']]);

                // 3. Insertar nuevas observaciones (si existen)
                if (!empty($observaciones) && is_array($observaciones)) {
                    $stmtInsert = $co->prepare("INSERT INTO observacion_consulta 
                        (cod_consulta, cod_observacion, observacion) 
                        VALUES (?, ?, ?)");

                    foreach ($observaciones as $obs) {
                        if (!empty($obs['cod_observacion']) && isset($obs['observacion'])) {
                            // Validar longitud de la observación antes de insertar
                            $obs_text = $obs['observacion'];
                            if (strlen($obs_text) > 500) { // Límite arbitrario de 500 caracteres
                                $obs_text = substr($obs_text, 0, 500);
                            }
                            $stmtInsert->execute([
                                $datos['cod_consulta'],
                                $obs['cod_observacion'],
                                $obs_text
                            ]);
                        }
                    }
                }

                // Confirmar transacción
                $co->commit();

                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Registro Modificado';
                $r['cod_consulta'] = $datos['cod_consulta'];
            } catch (Exception $e) {
                // Revertir en caso de error
                $co->rollBack();
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'No existe el Cod de Consulta';
        }
        return $r;
    }

    function obtener_observaciones_consulta($cod_consulta)
    {
        $co = $this->conecta();
        $stmt = $co->prepare("SELECT oc.cod_observacion, tobs.nom_observaciones, oc.observacion
							FROM observacion_consulta oc
							JOIN tipo_observacion tobs ON oc.cod_observacion = tobs.cod_observacion
							WHERE oc.cod_consulta = ?");
        $stmt->execute([$cod_consulta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function eliminar($datos)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if ($this->existe($datos['cod_consulta'])) {
            try {
                // Iniciar transacción para asegurar atomicidad
                $co->beginTransaction();

                // 1. Eliminar observaciones primero (por integridad referencial)
                $stmt1 = $co->prepare("DELETE FROM observacion_consulta WHERE cod_consulta = ?");
                $stmt1->execute([$datos['cod_consulta']]);

                // 2. Eliminar la consulta
                $stmt2 = $co->prepare("DELETE FROM consulta WHERE cod_consulta = ?");
                $stmt2->execute([$datos['cod_consulta']]);

                // Confirmar cambios
                $co->commit();

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Registro Eliminado';
            } catch (Exception $e) {
                // Revertir en caso de error
                $co->rollBack();
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'No existe el registro';
        }
        return $r;
    }

    function consultar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            // Consulta preparada
            $stmt = $co->prepare("SELECT *, h.nombre as nombre_h, h.apellido as apellido_h  
									FROM consulta c 
									INNER JOIN paciente h ON c.cedula_paciente = h.cedula_paciente
									INNER JOIN personal p ON c.cedula_personal = p.cedula_personal");

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener resultados
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultados) {
                $r['resultado'] = 'consultar';
                $r['datos'] = $resultados;
            } else {
                $r['resultado'] = 'consultar';
                $r['datos'] = array();
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }





    private function existe($cod_consulta)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Consulta preparada
            $stmt = $co->prepare("SELECT * FROM consulta WHERE cod_consulta = ?");
            $stmt->execute([$cod_consulta]);

            // Verificar si existe al menos un registro
            return $stmt->fetch() !== false;
        } catch (Exception $e) {
            return false;
        }
    }


    public function validar_cedulas($cedula_paciente, $cedula_personal)
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Verificar existencia paciente
            $stmt = $co->prepare("SELECT 1 FROM paciente WHERE cedula_paciente = ? LIMIT 1");
            $stmt->execute([$cedula_paciente]);
            $existePaciente = ($stmt->fetchColumn() !== false);

            // Verificar existencia personal
            $stmt = $co->prepare("SELECT cedula_personal FROM personal WHERE cedula_personal = ? LIMIT 1");
            $stmt->execute([$cedula_personal]);
            $existePersonal = ($stmt->fetchColumn() !== false);

            // Código: 0 = ambas existen, 1 = paciente no existe, 2 = personal no existe, 3 = ninguna existe
            if ($existePaciente && $existePersonal) {
                $codigo = 0;
                $mensaje = 'Ambas cédulas existen';
            } elseif (!$existePaciente && $existePersonal) {
                $codigo = 1;
                $mensaje = 'La cédula del paciente no existe';
            } elseif ($existePaciente && !$existePersonal) {
                $codigo = 2;
                $mensaje = 'La cédula del personal no existe';
            } else {
                $codigo = 3;
                $mensaje = 'Ninguna de las cédulas existe';
            }

            $r['resultado'] = 'validar_cedulas';
            $r['codigo'] = $codigo;
            $r['mensaje'] = $mensaje;
            $r['existe_paciente'] = $existePaciente;
            $r['existe_personal'] = $existePersonal;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }
}
class observaciones extends datos
{


    function listado_observaciones()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            // Consulta preparada
            $stmt = $co->prepare("SELECT * FROM tipo_observacion");

            // Ejecutar consulta
            $stmt->execute();

            // Obtener resultados
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultados) {
                $r['resultado'] = 'listado_observaciones';
                $r['datos'] = $resultados;
            } else {
                $r['resultado'] = 'listado_observaciones';
                $r['datos'] = array();
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }


    private function validar_observacion_nombre($nombre)
    {
        $r = array('codigo' => 0, 'mensaje' => 'Nombre de observación válido');

        if (!isset($nombre) || !preg_match('/^[A-Za-z0-9\sñÑáéíóúÁÉÍÓÚ]{3,30}$/', $nombre)) {
            $r['codigo'] = 1;
            $r['mensaje'] = 'El nombre de la observación es inválido (3-30 letras, números o espacios).';
            return $r;
        }

        return $r;
    }


    function incluir2($datos)
    {
        $r = array();

        // Validar el nombre de la observación
        $val_nombre = $this->validar_observacion_nombre($datos['nom_observaciones']);
        if ($val_nombre['codigo'] !== 0) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $val_nombre['mensaje'];
            return $r;
        }


        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Llamar al procedimiento almacenado para insertar la observación y generar el código
            $stmt = $co->prepare("CALL sp_insertar_observacion_simple(?, @cod_generado)");
            $stmt->execute([$datos['nom_observaciones']]);
            $stmt->closeCursor();

            // Obtener el código generado por el procedimiento
            $stmtCod = $co->query("SELECT @cod_generado AS cod_observacion");
            $cod_generado = $stmtCod->fetchColumn();

            // Verificar si realmente se insertó
            if ($this->existe2($cod_generado)) {
                $r['resultado'] = 'agregar';
                $r['mensaje'] = 'Registro Incluido';
                $r['cod_observacion'] = $cod_generado;
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se pudo generar el código';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }


    function eliminar2($datos)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if (!isset($datos['cod_observacion']) || !is_numeric($datos['cod_observacion'])) {
            $r['resultado'] = 'error';
            $r['mensaje'] =  'Código de observación inválido';
            return $r;
        }

        if ($this->existe2($datos['cod_observacion'])) {
            try {
                $stmt = $co->prepare("DELETE FROM tipo_observacion WHERE cod_observacion = :cod_observacion");
                $stmt->execute([':cod_observacion' => $datos['cod_observacion']]);
                $r['resultado'] = 'descartar';
                $r['mensaje'] =  'Registro Eliminado';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] =  $e->getMessage();
            }
        } else {
            $r['resultado'] = 'descartar';
            $r['mensaje'] =  'No existe el registro';
        }
        return $r;
    }

    function modificar2($datos)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        // Validar el nombre de la observación
        $val_nombre = $this->validar_observacion_nombre($datos['nom_observaciones']);
        if ($val_nombre['codigo'] !== 0) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $val_nombre['mensaje'];
            return $r;
        }

        if (!isset($datos['cod_observacion']) || !is_numeric($datos['cod_observacion'])) {
            $r['resultado'] = 'error';
            $r['mensaje'] =  'Código de observación inválido';
            return $r;
        }

        if ($this->existe2($datos['cod_observacion'])) {
            try {
                // Consulta preparada
                $stmt = $co->prepare("UPDATE tipo_observacion SET 
                                    nom_observaciones = ?
                                    WHERE cod_observacion = ?");
                // Ejecutar con parámetros
                $stmt->execute([
                    $datos['nom_observaciones'],
                    $datos['cod_observacion']
                ]);
                $r['resultado'] = 'actualizar';
                $r['mensaje'] = 'Registro Modificado';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'actualizar';
            $r['mensaje'] = 'Código no registrado';
        }

        return $r;
    }

    private function existe2($cod_observacion)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Consulta preparada
            $stmt = $co->prepare("SELECT * FROM tipo_observacion WHERE cod_observacion = ?");
            $stmt->execute([$cod_observacion]);

            // Verificar si existe al menos un registro
            return $stmt->fetch() !== false;
        } catch (Exception $e) {
            return false;
        }
    }
}
