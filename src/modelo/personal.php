<?php

namespace Shm\Shm\modelo;

use Shm\Shm\modelo\datos;
use PDO;
use Exception;

class personal extends datos
{
    private $cedula_personal;
    private $nombre;
    private $apellido;
    private $correo;
    private $telefonos = array();
    private $cargo;

    // Setters
    public function set_cedula_personal($valor)
    {
        $this->cedula_personal = $valor;
    }

    public function set_apellido($valor)
    {
        $this->apellido = $valor;
    }

    public function set_nombre($valor)
    {
        $this->nombre = $valor;
    }

    public function set_correo($valor)
    {
        $this->correo = $valor;
    }

    public function set_telefonos($telefonos)
    {
        $this->telefonos = $telefonos;
    }

    public function set_cargo($valor)
    {
        $this->cargo = $valor;
    }

    // Getters
    public function get_cedula_personal()
    {
        return $this->cedula_personal;
    }

    public function get_apellido()
    {
        return $this->apellido;
    }

    public function get_nombre()
    {
        return $this->nombre;
    }

    public function get_correo()
    {
        return $this->correo;
    }

    public function get_telefonos()
    {
        return $this->telefonos;
    }

    public function get_cargo()
    {
        return $this->cargo;
    }

    // Método unificado para gestionar personal
    public function gestionar_personal($datos)
    {
        $this->set_cedula_personal($datos['cedula_personal'] ?? '');
        $this->set_nombre($datos['nombre'] ?? '');
        $this->set_apellido($datos['apellido'] ?? '');
        $this->set_correo($datos['correo'] ?? '');
        $this->set_cargo($datos['cargo'] ?? '');

        // Manejar teléfonos - ahora viene como JSON string o array
        $telefonos = [];
        if (!empty($datos['telefonos'])) {
            if (is_string($datos['telefonos'])) {
                $telefonos = json_decode($datos['telefonos'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $telefonos = [$datos['telefonos']];
                }
            } elseif (is_array($datos['telefonos'])) {
                $telefonos = $datos['telefonos'];
            }
        }
        $this->set_telefonos($telefonos);

        switch ($datos['accion']) {
            case 'incluir':
                return $this->incluir();
            case 'modificar':
                return $this->modificar();
            case 'eliminar':
                return $this->eliminar();
            default:
                return array("resultado" => "error", "mensaje" => "Acción no válida");
        }
    }

    private function incluir()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Iniciar transacción
            $co->beginTransaction();

            // Insertar datos básicos del personal
            $sql = "INSERT INTO personal(
                cedula_personal,
                apellido,
                nombre,
                correo,
                cargo
                )
                VALUES(
                :cedula,
                :apellido,
                :nombre,
                :correo,
                :cargo
                )";

            $stmt = $co->prepare($sql);
            $stmt->execute(array(
                ':cedula' => $this->cedula_personal,
                ':apellido' => $this->apellido,
                ':nombre' => $this->nombre,
                ':correo' => $this->correo,
                ':cargo' => $this->cargo
            ));

            // Insertar teléfonos
            foreach ($this->telefonos as $telefono) {
                $sql = "INSERT INTO telefonos_personal(
                    cedula_personal,
                    telefono
                    )
                    VALUES(
                    :cedula,
                    :telefono
                    )";

                $stmt = $co->prepare($sql);
                $stmt->execute(array(
                    ':cedula' => $this->cedula_personal,
                    ':telefono' => $telefono
                ));
            }

            $co->commit();
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Registro Incluido';
        } catch (Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    private function modificar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $co->beginTransaction();

            // Actualizar datos básicos
            $sql = "UPDATE personal SET 
                apellido = :apellido,
                nombre = :nombre,
                correo = :correo,
                cargo = :cargo
                WHERE
                cedula_personal = :cedula";

            $stmt = $co->prepare($sql);
            $stmt->execute(array(
                ':apellido' => $this->apellido,
                ':nombre' => $this->nombre,
                ':correo' => $this->correo,
                ':cargo' => $this->cargo,
                ':cedula' => $this->cedula_personal
            ));

            // Eliminar teléfonos antiguos
            $sql = "DELETE FROM telefonos_personal WHERE cedula_personal = :cedula";
            $stmt = $co->prepare($sql);
            $stmt->execute([':cedula' => $this->cedula_personal]);

            // Insertar nuevos teléfonos
            foreach ($this->telefonos as $telefono) {
                $sql = "INSERT INTO telefonos_personal(
                    cedula_personal,
                    telefono
                    )
                    VALUES(
                    :cedula,
                    :telefono
                    )";

                $stmt = $co->prepare($sql);
                $stmt->execute(array(
                    ':cedula' => $this->cedula_personal,
                    ':telefono' => $telefono
                ));
            }

            $co->commit();
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Registro Modificado';
        } catch (Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    private function eliminar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $co->beginTransaction();

            // Eliminar teléfonos primero
            $sql = "DELETE FROM telefonos_personal WHERE cedula_personal = :cedula";
            $stmt = $co->prepare($sql);
            $stmt->execute([':cedula' => $this->cedula_personal]);

            // Luego eliminar el personal
            $sql = "DELETE FROM personal WHERE cedula_personal = :cedula";
            $stmt = $co->prepare($sql);
            $stmt->execute([':cedula' => $this->cedula_personal]);

            $co->commit();
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Registro Eliminado';
        } catch (Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    public function consultar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $sql = "SELECT p.*, 
                   GROUP_CONCAT(tp.telefono SEPARATOR ', ') as telefonos 
                   FROM personal p
                   LEFT JOIN telefonos_personal tp ON p.cedula_personal = tp.cedula_personal
                   GROUP BY p.cedula_personal
                   ORDER BY p.apellido, p.nombre";

            $stmt = $co->prepare($sql);
            $stmt->execute();

            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    public function obtener_doctores()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $sql = "SELECT cedula_personal, CONCAT(nombre, ' ', apellido) as nombre_completo
                    FROM personal 
                    WHERE cargo = 'Doctor' 
                    ORDER BY apellido, nombre";

            $stmt = $co->prepare($sql);
            $stmt->execute();

            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    public static function obtenerUsuarioPersonal($cedula_personal)
    {
        $co = (new self())->conecta();
        $stmt = $co->prepare("SELECT nombre, apellido FROM personal WHERE cedula_personal = ?");
        $stmt->execute([$cedula_personal]);
        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$usuario) {
            $usuario = ['nombre' => 'Desconocido', 'apellido' => '', 'foto_perfil' => ''];
        }
        return $usuario;
    }
}
