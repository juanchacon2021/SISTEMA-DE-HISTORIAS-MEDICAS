<?php
namespace Shm\Shm\modelo;
require_once __DIR__ . '/datos.php';

use Shm\Shm\modelo\datos;
use PDO;
use Exception;

class principal extends datos {
    public static function obtenerNotificaciones($limite = 10) {
        $co = (new self())->conecta2();
        $stmt = $co->query("SELECT b.descripcion, u.nombre, u.foto_perfil, b.fecha_hora 
                            FROM bitacora b 
                            LEFT JOIN usuario u ON b.usuario_id = u.cedula_personal 
                            ORDER BY b.fecha_hora DESC LIMIT $limite");
        $notificaciones = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $notificaciones[] = [
                'nombre' => trim($row['nombre'] ?? ''),
                'foto' => !empty($row['foto_perfil']) ? 'img/perfiles/'.$row['foto_perfil'] : 'img/user.png',
                'descripcion' => $row['descripcion'],
                'fecha_hora' => $row['fecha_hora']
            ];
        }
        return $notificaciones;
    }
    public static function obtenerDatosUsuario($cedula) {
        $co = (new self())->conecta2();
        $stmt = $co->prepare("SELECT nombre, foto_perfil, cedula_personal FROM usuario WHERE cedula_personal = :cedula");
        $stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultado) {
            $resultado['foto_perfil'] = !empty($resultado['foto_perfil']) ? "img/perfiles/" . $resultado['foto_perfil'] : "img/user.png";
        } else {
            $resultado = [
                'nombre' => 'Usuario no encontrado',
                'foto_perfil' => 'img/user.png',
                'cedula_personal' => ''
            ];
        }
        return $resultado;
    }
    public static function obtenerTotales($cedula_usuario) {
        $co = (new self())->conecta(); // Usa conecta() como en los otros modelos

        // Total pacientes
        $stmt = $co->query("SELECT COUNT(*) AS total FROM paciente");
        $totalPacientes = $stmt->fetchColumn();

        // Total personal activo (ajusta si no tienes estatus)
        $stmt = $co->query("SELECT COUNT(*) AS total FROM personal");
        $totalPersonal = $stmt->fetchColumn();
        return [
            'pacientes' => $totalPacientes,
            'personal' => $totalPersonal
        ];
    }
}
?>