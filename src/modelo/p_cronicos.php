<?php

namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;
use Exception;

class p_cronicos extends datos{

    function listadopacientes() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $sql = "SELECT * FROM paciente";
            $stmt = $co->prepare($sql);
            $stmt->execute();
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $r['resultado'] = 'listadopacientes';
            $r['datos'] = $datos ? $datos : array();
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function incluir($datos, $patologias = array()) {
        $r = array();

        // Validar formato de cédula
        if (!isset($datos['cedula_paciente']) || !preg_match('/^[0-9]{7,8}$/', $datos['cedula_paciente'])) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Cédula de paciente inválida';
            return $r;
        }

        // Validar que la cédula del paciente exista en la tabla paciente
        $val = $this->validar_paciente($datos['cedula_paciente']);
        if (!is_array($val) || !isset($val['codigo'])) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Error al validar paciente';
            return $r;
        }
        if ($val['codigo'] !== 0) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $val['mensaje'];
            return $r;
        }

        // Validar lista de patologías
        if (empty($patologias) || !is_array($patologias)) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'No se recibieron patologías válidas';
            return $r;
        }

        // Validar estructura de cada patología
        foreach ($patologias as $i => $pat) {
            if (!isset($pat['cod_patologia']) || !is_numeric($pat['cod_patologia']) || intval($pat['cod_patologia']) <= 0) {
                $r['resultado'] = 'error';
                $r['mensaje'] = "cod_patologia inválido en índice $i";
                return $r;
            }
            if (isset($pat['tratamiento']) && mb_strlen($pat['tratamiento']) > 1000) {
                $r['resultado'] = 'error';
                $r['mensaje'] = "Tratamiento demasiado largo en índice $i";
                return $r;
            }
            if (isset($pat['administracion_t']) && mb_strlen($pat['administracion_t']) > 255) {
                $r['resultado'] = 'error';
                $r['mensaje'] = "Administración demasiado larga en índice $i";
                return $r;
            }
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if(!$this->existe($datos['cedula_paciente'])) {
            try {
                $co->beginTransaction();
                $stmt = $co->prepare("INSERT INTO padece (cedula_paciente, cod_patologia, tratamiento, administracion_t) VALUES (?, ?, ?, ?)");
                foreach ($patologias as $pat) {
                    $stmt->execute([
                        $datos['cedula_paciente'],
                        intval($pat['cod_patologia']),
                        isset($pat['tratamiento']) ? $pat['tratamiento'] : null,
                        isset($pat['administracion_t']) ? $pat['administracion_t'] : null
                    ]);
                }
                $co->commit();
                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Registro Incluido';
            } catch(Exception $e) {
                if ($co->inTransaction()) $co->rollBack();
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Error al insertar: ' . $e->getMessage();
            } finally {
                $co = null;
            }
        }
        else {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'El paciente ya está registrado';
        }

        return $r;
    }

    function modificar($datos, $patologias = array()) {
        $r = array();

        // Validar formato de cédula
        if (!isset($datos['cedula_paciente']) || !preg_match('/^[0-9]{7,8}$/', $datos['cedula_paciente'])) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Cédula de paciente inválida';
            return $r;
        }

        // Validar existencia del paciente
        $val = $this->validar_paciente($datos['cedula_paciente']);
        if (!is_array($val) || !isset($val['codigo'])) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Error al validar paciente';
            return $r;
        }
        if ($val['codigo'] !== 0) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $val['mensaje'];
            return $r;
        }

        try {
            // Verificar si el paciente tiene patologías registradas
            if(!$this->existe($datos['cedula_paciente'])) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'El paciente no tiene patologías registradas para modificar';
                return $r;
            }

            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $co->beginTransaction();

            // 1. Eliminar todas las patologías actuales del paciente
            $stmtDelete = $co->prepare("DELETE FROM padece WHERE cedula_paciente = ?");
            $stmtDelete->execute([$datos['cedula_paciente']]);

            // 2. Insertar las nuevas patologías (si las hay)
            if (!empty($patologias) && is_array($patologias)) {
                $stmtInsert = $co->prepare("INSERT INTO padece (cedula_paciente, cod_patologia, tratamiento, administracion_t) VALUES (?, ?, ?, ?)");
                foreach ($patologias as $i => $pat) {
                    if (!isset($pat['cod_patologia']) || !is_numeric($pat['cod_patologia']) || intval($pat['cod_patologia']) <= 0) {
                        $co->rollBack();
                        $r['resultado'] = 'error';
                        $r['mensaje'] = "cod_patologia inválido en índice $i";
                        return $r;
                    }
                    $stmtInsert->execute([
                        $datos['cedula_paciente'],
                        intval($pat['cod_patologia']),
                        $pat['tratamiento'] ?? null,
                        $pat['administracion_t'] ?? null
                    ]);
                }
            }

            $co->commit();
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Registro Modificado';
        } catch(Exception $e) {
            if (isset($co) && $co->inTransaction()) $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Error al modificar patologías: ' . $e->getMessage();
        } finally {
            if (isset($co)) $co = null;
        }

        return $r;
    }

    function eliminar($datos) {
        $r = array();

        // Validar formato de cédula
        if (!isset($datos['cedula_paciente']) || !preg_match('/^[0-9]{7,8}$/', $datos['cedula_paciente'])) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Cédula de paciente inválida';
            return $r;
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Verificar si el paciente tiene patologías registradas
            if($this->existe($datos['cedula_paciente'])) {
                // Eliminar todas las patologías del paciente
                $stmtDelete = $co->prepare("DELETE FROM padece WHERE cedula_paciente = ?");
                $stmtDelete->execute([$datos['cedula_paciente']]);

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Registro Eliminado';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'El paciente no tiene patologías registradas';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Error al eliminar: ' . $e->getMessage();
        } finally {
            $co = null;
        }

        return $r;
    }

    function consultar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $sql = "SELECT p.cedula_paciente, p.nombre, p.apellido, GROUP_CONCAT(pa.nombre_patologia SEPARATOR ', ') AS patologias
                    FROM paciente p 
                    JOIN padece pd ON p.cedula_paciente = pd.cedula_paciente
                    JOIN patologia pa ON pd.cod_patologia = pa.cod_patologia
                    GROUP BY p.cedula_paciente, p.nombre, p.apellido";
            $stmt = $co->prepare($sql);
            $stmt->execute();
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $r['resultado'] = 'consultar';
            $r['datos'] = $datos ? $datos : array();
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $co = null;
        }
        return $r;
    }

    function obtener_patologias_paciente($cedula_paciente) {
        // Validar cédula
        if (!isset($cedula_paciente) || !preg_match('/^[0-9]{7,8}$/', $cedula_paciente)) {
            return [];
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->prepare("SELECT p.cedula_paciente, p.cod_patologia, 
                                        pa.nombre_patologia, p.tratamiento, p.administracion_t
                                FROM padece p 
                                JOIN patologia pa ON p.cod_patologia = pa.cod_patologia
                                WHERE p.cedula_paciente = ?");
            $stmt->execute([$cedula_paciente]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            return [];
        } finally {
            $co = null;
        }
    }


    private function existe($cedula_paciente) {
        if (!isset($cedula_paciente) || !preg_match('/^[0-9]{7,8}$/', $cedula_paciente)) {
            return false;
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->prepare("SELECT 1 FROM padece WHERE cedula_paciente = ? LIMIT 1");
            $stmt->execute([$cedula_paciente]);
            $fila = $stmt->fetch();
            return (bool)$fila;
        } catch(Exception $e) {
            return false;
        } finally {
            $co = null;
        }
    }

    public function validar_paciente($cedula_paciente) {
        $r = array('resultado' => 'validar_paciente', 'codigo' => 1, 'mensaje' => 'Paciente no encontrado', 'existe_paciente' => false);
        if (!isset($cedula_paciente) || !preg_match('/^[0-9]{7,8}$/', $cedula_paciente)) {
            $r['mensaje'] = 'Formato de cédula inválido';
            return $r;
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->prepare("SELECT 1 FROM paciente WHERE cedula_paciente = ? LIMIT 1");
            $stmt->execute([$cedula_paciente]);
            $existe = ($stmt->fetchColumn() !== false);

            $r['codigo'] = $existe ? 0 : 1;
            $r['mensaje'] = $existe ? 'Paciente existe' : 'Paciente no encontrado';
            $r['existe_paciente'] = $existe;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['codigo'] = 2;
            $r['mensaje'] = 'Error al validar paciente: ' . $e->getMessage();
            $r['existe_paciente'] = false;
        } finally {
            $co = null;
        }

        return $r;
    }

}

class patologias extends datos{

    function listado_patologias() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            // Preparamos la consulta
            $stmt = $co->prepare("SELECT * FROM patologia");
            // Ejecutamos la consulta
            $stmt->execute();

            $resultado = $stmt;

            if ($resultado) {
                $r['resultado'] = 'listado_patologias';
                $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $r['resultado'] = 'listado_patologias';
                $r['datos'] = array();
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $co = null;
        }

        return $r;
    }

    function incluir2($datos) {
        $r = array();

        // Validar nombre de patología
        if (!isset($datos['nombre_patologia']) || !preg_match('/^[A-Za-z0-9\sñÑáéíóúÁÉÍÓÚ]{3,100}$/u', $datos['nombre_patologia'])) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Nombre de patología inválido';
            $r['cod_patologia'] = null;
            return $r;
        }

        // Verifica si ya existe una patología con ese nombre
        if(!$this->existeNombrePatologia($datos['nombre_patologia'])) {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try {
                // Llamar al procedimiento almacenado
                $stmt = $co->prepare("CALL insertar_patologia(:nombre, @cod_generado)");
                $stmt->execute([':nombre' => $datos['nombre_patologia']]);

                // Obtener el código generado
                $stmt = $co->query("SELECT @cod_generado as codigo");
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                $cod_patologia = $resultado['codigo'] ?? null;

                $r['resultado'] = 'agregar';
                $r['mensaje'] = 'Patología registrada exitosamente';
                $r['cod_patologia'] = $cod_patologia;
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
                $r['cod_patologia'] = null;
            } finally {
                $co = null;
            }
        } else {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Ya existe una patología con ese nombre';
            $r['cod_patologia'] = null;
        }

        return $r;
    }

    function modificar2($datos) {
        $r = array();

        if (!isset($datos['cod_patologia']) || !is_numeric($datos['cod_patologia']) || intval($datos['cod_patologia']) <= 0) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Código de patología inválido';
            return $r;
        }
        if (!isset($datos['nombre_patologia']) || !preg_match('/^[A-Za-z0-9\sñÑáéíóúÁÉÍÓÚ]{3,100}$/u', $datos['nombre_patologia'])) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Nombre de patología inválido';
            return $r;
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if($this->existe2($datos['cod_patologia'])) {
            try {
                $stmt = $co->prepare("UPDATE patologia SET nombre_patologia = :nombre WHERE cod_patologia = :cod");
                $stmt->execute([
                    ':nombre' => $datos['nombre_patologia'],
                    ':cod' => intval($datos['cod_patologia'])
                ]);
                $r['resultado'] = 'actualizar';
                $r['mensaje'] = 'Registro Modificado';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            } finally {
                $co = null;
            }
        } else {
            $r['resultado'] = 'actualizar';
            $r['mensaje'] = 'Código de patología no registrado';
        }
        return $r;
    }

    function eliminar2($datos) {
        $r = array();

        if (!isset($datos['cod_patologia']) || !is_numeric($datos['cod_patologia']) || intval($datos['cod_patologia']) <= 0) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Código de patología inválido';
            return $r;
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if($this->existe2($datos['cod_patologia'])){
            try {
                $stmt = $co->prepare("DELETE FROM patologia WHERE cod_patologia = :cod_patologia");
                $stmt->execute([':cod_patologia' => intval($datos['cod_patologia'])]);
                $r['resultado'] = 'descartar';
                $r['mensaje'] =  'Registro Eliminado';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] =  $e->getMessage();
            } finally {
                $co = null;
            }
        }
        else{
            $r['resultado'] = 'descartar';
            $r['mensaje'] =  'No existe el registro';
        }
        return $r;
    }

    private function existe2($cod_patologia) {
        if (!isset($cod_patologia) || !is_numeric($cod_patologia)) return false;

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->prepare("SELECT 1 FROM patologia c WHERE c.cod_patologia = :cod LIMIT 1");
            $stmt->execute([':cod' => intval($cod_patologia)]);
            $fila = $stmt->fetch();
            return !empty($fila);
        } catch(Exception $e) {
            return false;
        } finally {
            $co = null;
        }
    }

    private function existeNombrePatologia($nombre_patologia) {
        if (!isset($nombre_patologia) || trim($nombre_patologia) === '') return false;

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->prepare("SELECT 1 FROM patologia WHERE nombre_patologia = :nombre LIMIT 1");
            $stmt->execute([':nombre' => $nombre_patologia]);
            $fila = $stmt->fetch();
            return (bool)$fila;
        } catch(Exception $e) {
            return false;
        } finally {
            $co = null;
        }
    }


}
?>