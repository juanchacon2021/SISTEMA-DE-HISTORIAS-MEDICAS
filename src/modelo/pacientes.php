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
        // Validar nombre de patología
        $nombre = trim($datos['nombre_patologia'] ?? '');
        if ($nombre === '' || mb_strlen($nombre) > 150) {
            return ['resultado' => 'error', 'mensaje' => 'Nombre de patología inválido'];
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
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
        // Validar campos antes de operar
        $val = $this->validar_campos($datos);
        if (!is_array($val) || !isset($val['codigo'])) {
            return ['resultado' => 'error', 'mensaje' => 'Error al validar campos'];
        }
        if ($val['codigo'] !== 0) {
            return ['resultado' => 'error', 'mensaje' => $val['mensaje']];
        }

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
        // Validar campos antes de operar
        $val = $this->validar_campos($datos);
        if (!is_array($val) || !isset($val['codigo'])) {
            return ['resultado' => 'error', 'mensaje' => 'Error al validar campos'];
        }
        if ($val['codigo'] !== 0) {
            return ['resultado' => 'error', 'mensaje' => $val['mensaje']];
        }

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
            // Actualizar patologías
            if (!empty($datos['patologias']) && is_array($datos['patologias'])) {
                $co->prepare("DELETE FROM padece WHERE cedula_paciente = :cedula_paciente")
                    ->execute(['cedula_paciente' => $datos['cedula_paciente']]);
                foreach ($datos['patologias'] as $pat) {
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
        // Validar cédula antes de eliminar
        $cedula = $datos['cedula_paciente'] ?? '';
        if (!preg_match('/^[0-9]{7,8}$/', $cedula)) {
            return ['resultado' => 'error', 'mensaje' => 'Formato de cédula inválido'];
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $co->beginTransaction();
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
        // Validar campos del familiar
        $cedula = $datos['cedula_paciente'] ?? '';
        $nom = trim($datos['nom_familiar'] ?? '');
        $ape = trim($datos['ape_familiar'] ?? '');
        if (!preg_match('/^[0-9]{7,8}$/', $cedula)) {
            return ['resultado' => 'error', 'mensaje' => 'Cédula de paciente inválida'];
        }
        if ($nom === '' || mb_strlen($nom) > 60 || $ape === '' || mb_strlen($ape) > 60) {
            return ['resultado' => 'error', 'mensaje' => 'Nombre o apellido del familiar inválido'];
        }

        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $stmt = $co->prepare("INSERT INTO antecedentes_familiares (cedula_paciente, nom_familiar, ape_familiar, relacion_familiar, observaciones) VALUES (:cedula_paciente, :nom_familiar, :ape_familiar, :relacion_familiar, :observaciones)");
            $stmt->execute([
                'cedula_paciente' => $cedula,
                'nom_familiar' => $nom,
                'ape_familiar' => $ape,
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

    public function obtener_patologias_paciente($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $cedula = $datos['cedula_paciente'] ?? '';
            $stmt = $co->prepare("SELECT p.cod_patologia, pa.nombre_patologia, p.tratamiento, p.administracion_t 
                FROM padece p 
                JOIN patologia pa ON pa.cod_patologia = p.cod_patologia 
                WHERE p.cedula_paciente = :cedula");
            $stmt->execute(['cedula' => $cedula]);
            $r['resultado'] = 'obtener_patologias_paciente';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Validaciones de campos (similar a consultasm::validar_campos)
    private function validar_campos($datos) {
        $r = array('codigo' => 0, 'mensaje' => 'Campos válidos');

        // Cédula paciente requerida y formato
        if (!isset($datos['cedula_paciente']) || !preg_match('/^[0-9]{7,8}$/', $datos['cedula_paciente'])) {
            $r['codigo'] = 1;
            $r['mensaje'] = 'Formato de cédula de paciente inválido (7-8 dígitos)';
            return $r;
        }

        // Nombre y apellido
        if (!isset($datos['nombre']) || !preg_match('/^[A-Za-z\sñÑáéíóúÁÉÍÓÚ]{3,30}$/u', $datos['nombre'])) {
            $r['codigo'] = 2;
            $r['mensaje'] = 'Nombre inválido (3-30 letras)';
            return $r;
        }
        if (!isset($datos['apellido']) || !preg_match('/^[A-Za-z\sñÑáéíóúÁÉÍÓÚ]{3,30}$/u', $datos['apellido'])) {
            $r['codigo'] = 3;
            $r['mensaje'] = 'Apellido inválido (3-30 letras)';
            return $r;
        }

        // Fecha de nacimiento YYYY-MM-DD
        if (!isset($datos['fecha_nac']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $datos['fecha_nac']) || !\DateTime::createFromFormat('Y-m-d', $datos['fecha_nac'])) {
            $r['codigo'] = 4;
            $r['mensaje'] = 'Formato de fecha inválido (YYYY-MM-DD)';
            return $r;
        }

        // Edad numérica razonable
        if (!isset($datos['edad']) || !is_numeric($datos['edad']) || $datos['edad'] < 0 || $datos['edad'] > 130) {
            $r['codigo'] = 5;
            $r['mensaje'] = 'Edad inválida';
            return $r;
        }

        // Teléfono opcional pero si existe debe tener 11 dígitos
        if (isset($datos['telefono']) && trim($datos['telefono']) !== '' && !preg_match('/^[0-9]{11}$/', $datos['telefono'])) {
            $r['codigo'] = 6;
            $r['mensaje'] = 'Teléfono inválido (11 dígitos)';
            return $r;
        }

        // Longitudes para textos libres
        $campos_largos = ['ocupacion'=>100,'direccion'=>300,'hda'=>1000,'alergias'=>1000,'alergias_med'=>1000,'quirurgico'=>1000,'transsanguineo'=>1000,'psicosocial'=>1000,'habtoxico'=>1000];
        foreach ($campos_largos as $campo => $max) {
            if (isset($datos[$campo]) && mb_strlen($datos[$campo]) > $max) {
                $r['codigo'] = 7;
                $r['mensaje'] = "El campo {$campo} excede la longitud permitida ({$max})";
                return $r;
            }
        }

        // Validar antecedentes_familiares si vienen
        if (!empty($datos['antecedentes_familiares']) && is_array($datos['antecedentes_familiares'])) {
            foreach ($datos['antecedentes_familiares'] as $f) {
                if (isset($f['nom_familiar']) && (!preg_match('/^[A-Za-z\s]{1,60}$/u', $f['nom_familiar']))) {
                    $r['codigo'] = 8;
                    $r['mensaje'] = 'Nombre de familiar inválido';
                    return $r;
                }
                if (isset($f['ape_familiar']) && (!preg_match('/^[A-Za-z\s]{1,60}$/u', $f['ape_familiar']))) {
                    $r['codigo'] = 9;
                    $r['mensaje'] = 'Apellido de familiar inválido';
                    return $r;
                }
            }
        }

        // Validar patologías si vienen
        if (!empty($datos['patologias']) && is_array($datos['patologias'])) {
            foreach ($datos['patologias'] as $p) {
                if (!isset($p['cod_patologia']) || !is_numeric($p['cod_patologia'])) {
                    $r['codigo'] = 10;
                    $r['mensaje'] = 'Código de patología inválido';
                    return $r;
                }
                if (isset($p['tratamiento']) && mb_strlen($p['tratamiento']) > 1000) {
                    $r['codigo'] = 11;
                    $r['mensaje'] = 'Tratamiento demasiado largo';
                    return $r;
                }
            }
        }

        return $r;
    }
}
?>