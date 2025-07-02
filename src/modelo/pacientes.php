<?php
namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;
use Exception;

class pacientes extends datos {

    // CONSULTAR TODOS LOS PACIENTES
    public function consultar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT * FROM paciente");
            if($resultado){
                $r['resultado'] = 'consultar';
                $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = 'No hay pacientes registrados';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // INCLUIR PACIENTE
    public function incluir($datos) {
        return $this->registrarPaciente($datos);
    }

    // MODIFICAR PACIENTE
    public function modificar($datos) {
        return $this->modificarPaciente($datos);
    }

    // ELIMINAR PACIENTE
    public function eliminar($datos) {
        return $this->eliminarPaciente($datos);
    }

    // CONSULTAR ANTECEDENTES
    public function consultarAntecedentes($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $cedula = $datos['cedula_paciente'] ?? '';
            $stmt = $co->prepare("SELECT * FROM antecedentes_familiares WHERE cedula_paciente = :cedula");
            $stmt->execute(['cedula' => $cedula]);
            $r['resultado'] = 'consultarAntecedentes';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // AGREGAR FAMILIAR
    public function agregarFamiliar($datos) {
        return $this->registrarFamiliar($datos);
    }

    // ELIMINAR FAMILIAR
    public function eliminarFamiliar($datos) {
        return $this->eliminarFamiliarPaciente($datos);
    }

    // LISTADO DE PATOLOGÍAS
    public function listado_patologias() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT * FROM patologia");
            $r['resultado'] = 'listado_patologias';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // AGREGAR PATOLOGÍA
    public function agregar_patologia($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $nombre = $datos['nombre_patologia'] ?? '';
            $stmt = $co->prepare("INSERT INTO patologia(nombre_patologia) VALUES(:nombre_patologia)");
            $stmt->execute(['nombre_patologia' => $nombre]);
            $r['resultado'] = 'agregar_patologia';
            $r['mensaje'] = 'Patología agregada';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // ================= MÉTODOS PRIVADOS ===================

    private function registrarPaciente($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $co->beginTransaction();
            $sql = "INSERT INTO paciente (
                cedula_paciente, nombre, apellido, fecha_nac, edad, estadocivil, ocupacion, direccion, telefono,
                hda, alergias, alergias_med, quirurgico, transsanguineo, psicosocial, habtoxico
            ) VALUES (
                :cedula_paciente, :nombre, :apellido, :fecha_nac, :edad, :estadocivil, :ocupacion, :direccion, :telefono,
                :hda, :alergias, :alergias_med, :quirurgico, :transsanguineo, :psicosocial, :habtoxico
            )";
            $stmt = $co->prepare($sql);
            $stmt->execute([
                'cedula_paciente' => $datos['cedula_paciente'] ?? '',
                'nombre' => $datos['nombre'] ?? '',
                'apellido' => $datos['apellido'] ?? '',
                'fecha_nac' => $datos['fecha_nac'] ?? '',
                'edad' => $datos['edad'] ?? '',
                'estadocivil' => $datos['estadocivil'] ?? '',
                'ocupacion' => $datos['ocupacion'] ?? '',
                'direccion' => $datos['direccion'] ?? '',
                'telefono' => $datos['telefono'] ?? '',
                'hda' => $datos['hda'] ?? '',
                'alergias' => $datos['alergias'] ?? '',
                'alergias_med' => $datos['alergias_med'] ?? '',
                'quirurgico' => $datos['quirurgico'] ?? '',
                'transsanguineo' => $datos['transsanguineo'] ?? '',
                'psicosocial' => $datos['psicosocial'] ?? '',
                'habtoxico' => $datos['habtoxico'] ?? ''
            ]);
            // antecedentes_familiares
            if (!empty($datos['antecedentes_familiares']) && is_array($datos['antecedentes_familiares'])) {
                foreach ($datos['antecedentes_familiares'] as $familiar) {
                    $stmtF = $co->prepare("INSERT INTO antecedentes_familiares (cedula_paciente, nom_familiar, ape_familiar, relacion_familiar, observaciones) VALUES (:cedula_paciente, :nom_familiar, :ape_familiar, :relacion_familiar, :observaciones)");
                    $stmtF->execute([
                        'cedula_paciente' => $datos['cedula_paciente'],
                        'nom_familiar' => $familiar['nom_familiar'] ?? '',
                        'ape_familiar' => $familiar['ape_familiar'] ?? '',
                        'relacion_familiar' => $familiar['relacion_familiar'] ?? '',
                        'observaciones' => $familiar['observaciones'] ?? ''
                    ]);
                }
            }
            // Patologías
            if (!empty($datos['patologias']) && is_array($datos['patologias'])) {
                foreach ($datos['patologias'] as $pat) {
                    // $pat debe ser un array con cod_patologia, tratamiento y administracion_t
                    $stmtP = $co->prepare("INSERT INTO padece (cedula_paciente, cod_patologia, tratamiento, administracion_t) VALUES (:cedula_paciente, :cod_patologia, :tratamiento, :administracion_t)");
                    $stmtP->execute([
                        'cedula_paciente' => $datos['cedula_paciente'],
                        'cod_patologia' => $pat['cod_patologia'],
                        'tratamiento' => $pat['tratamiento'] ?? '',
                        'administracion_t' => $pat['administracion_t'] ?? ''
                    ]);
                }
            }
            $co->commit();
            $r['resultado'] = 'success';
            $r['mensaje'] = 'Paciente registrado exitosamente';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    private function modificarPaciente($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $co->beginTransaction();
            $sql = "UPDATE paciente SET
                nombre = :nombre, apellido = :apellido, fecha_nac = :fecha_nac, edad = :edad, estadocivil = :estadocivil,
                ocupacion = :ocupacion, direccion = :direccion, telefono = :telefono, hda = :hda, alergias = :alergias,
                alergias_med = :alergias_med, quirurgico = :quirurgico, transsanguineo = :transsanguineo,
                psicosocial = :psicosocial, habtoxico = :habtoxico
                WHERE cedula_paciente = :cedula_paciente";
            $stmt = $co->prepare($sql);
            $stmt->execute([
                'cedula_paciente' => $datos['cedula_paciente'] ?? '',
                'nombre' => $datos['nombre'] ?? '',
                'apellido' => $datos['apellido'] ?? '',
                'fecha_nac' => $datos['fecha_nac'] ?? '',
                'edad' => $datos['edad'] ?? '',
                'estadocivil' => $datos['estadocivil'] ?? '',
                'ocupacion' => $datos['ocupacion'] ?? '',
                'direccion' => $datos['direccion'] ?? '',
                'telefono' => $datos['telefono'] ?? '',
                'hda' => $datos['hda'] ?? '',
                'alergias' => $datos['alergias'] ?? '',
                'alergias_med' => $datos['alergias_med'] ?? '',
                'quirurgico' => $datos['quirurgico'] ?? '',
                'transsanguineo' => $datos['transsanguineo'] ?? '',
                'psicosocial' => $datos['psicosocial'] ?? '',
                'habtoxico' => $datos['habtoxico'] ?? ''
            ]);
            // Actualizar antecedentes_familiares
            if (!empty($datos['antecedentes_familiares']) && is_array($datos['antecedentes_familiares'])) {
                $co->prepare("DELETE FROM antecedentes_familiares WHERE cedula_paciente = :cedula_paciente")
                    ->execute(['cedula_paciente' => $datos['cedula_paciente']]);
                foreach ($datos['antecedentes_familiares'] as $familiar) {
                    $stmtF = $co->prepare("UPDATE INTO antecedentes_familiares (cedula_paciente, nom_familiar, ape_familiar, relacion_familiar, observaciones) VALUES (:cedula_paciente, :nom_familiar, :ape_familiar, :relacion_familiar, :observaciones)");
                    $stmtF->execute([
                        'cedula_paciente' => $datos['cedula_paciente'],
                        'nom_familiar' => $familiar['nom_familiar'] ?? '',
                        'ape_familiar' => $familiar['ape_familiar'] ?? '',
                        'relacion_familiar' => $familiar['relacion_familiar'] ?? '',
                        'observaciones' => $familiar['observaciones'] ?? ''
                    ]);
                }
            }
            // Actualizar patologías
            if (!empty($datos['patologias']) && is_array($datos['patologias'])) {
                foreach ($datos['patologias'] as $patologia) {
                    $stmtP = $co->prepare("UPDATE INTO padece (cedula_paciente, cod_patologia, tratamiento, administracion_t) VALUES (:cedula_paciente, :cod_patologia, :tratamiento, :administracion_t)");
                    $stmtP->execute([
                        'cedula_paciente' => $datos['cedula_paciente'],
                        'cod_patologia' => $patologia,
                        'tratamiento' => '', // O puedes poner NULL si la columna lo permite
                        'administracion_t' => ''
                    ]);
                }
            }
            $co->commit();
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Paciente modificado exitosamente';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    private function eliminarPaciente($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $co->beginTransaction();
            $cedula = $datos['cedula_paciente'] ?? '';
            $co->prepare("DELETE FROM antecedentes_familiares WHERE cedula_paciente = :cedula_paciente")
                ->execute(['cedula_paciente' => $cedula]);
            $co->prepare("DELETE FROM padece WHERE cedula_paciente = :cedula_paciente")
                ->execute(['cedula_paciente' => $cedula]);
            $co->prepare("DELETE FROM paciente WHERE cedula_paciente = :cedula_paciente")
                ->execute(['cedula_paciente' => $cedula]);
            $co->commit();
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Paciente eliminado exitosamente';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    private function registrarFamiliar($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $stmt = $co->prepare("INSERT INTO antecedentes_familiares (cedula_paciente, nom_familiar, ape_familiar, relacion_familiar, observaciones) VALUES (:cedula_paciente, :nom_familiar, :ape_familiar, :relacion_familiar, :observaciones)");
            $stmt->execute([
                'cedula_paciente' => $datos['cedula_paciente'] ?? '',
                'nom_familiar' => $datos['nom_familiar'] ?? '',
                'ape_familiar' => $datos['ape_familiar'] ?? '',
                'relacion_familiar' => $datos['relacion_familiar'] ?? '',
                'observaciones' => $datos['observaciones'] ?? ''
            ]);
            $r['resultado'] = 'agregarFamiliar';
            $r['mensaje'] = 'Familiar agregado';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    private function eliminarFamiliarPaciente($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $stmt = $co->prepare("DELETE FROM antecedentes_familiares WHERE id_familiar = :id_familiar");
            $stmt->execute([
                'id_familiar' => $datos['id_familiar'] ?? 0
            ]);
            $r['resultado'] = 'eliminarFamiliar';
            $r['mensaje'] = 'Familiar eliminado';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
}
?>