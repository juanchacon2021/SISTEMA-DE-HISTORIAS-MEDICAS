<?php
// Obtener las últimas 10 notificaciones de la bitácora del módulo pacientes
$co = new PDO('mysql:host=localhost;dbname=seguridad', 'root', '123456');
$stmt = $co->query("SELECT b.descripcion, p.nombre, p.foto_perfil, b.fecha_hora 
                    FROM bitacora b 
                    LEFT JOIN usuario p ON b.usuario_id = p.cedula_personal 
                    WHERE b.modulo_id = 1 
                    ORDER BY b.fecha_hora DESC LIMIT 10");
$notificaciones = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $notificaciones[] = [
        'nombre' => trim(($row['nombre'] ?? '') . ' ' . ($row['apellido'] ?? '')),
        'foto' => !empty($row['foto_perfil']) ? 'img/perfiles/'.$row['foto_perfil'] : 'img/default-user.png',
        'descripcion' => $row['descripcion'],
        'fecha_hora' => $row['fecha_hora']
    ];
}
$_SESSION['notificaciones'] = $notificaciones;

if(is_file("vista/".$pagina.".php")){
    require_once("vista/".$pagina.".php"); 
}
else{
    echo "pagina en construccion";
}
?>