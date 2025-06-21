<?php
// Conexión a la base de datos de seguridad
$co = new PDO('mysql:host=localhost;dbname=seguridad', 'root', '123456');
$stmt = $co->query("SELECT descripcion, fecha_hora FROM bitacora ORDER BY fecha_hora DESC LIMIT 10");
$notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
$_SESSION['notificaciones'] = $notificaciones;

  if(is_file("vista/".$pagina.".php")){
	  require_once("vista/".$pagina.".php"); 
  }
  else{
	  echo "pagina en construccion";
  }
?>