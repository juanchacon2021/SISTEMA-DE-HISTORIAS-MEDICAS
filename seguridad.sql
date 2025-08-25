-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: seguridad
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bitacora`
--

DROP TABLE IF EXISTS `bitacora`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bitacora` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `modulo_id` int(11) NOT NULL,
  `accion` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `modulo_id` (`modulo_id`),
  CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`),
  CONSTRAINT `bitacora_ibfk_2` FOREIGN KEY (`modulo_id`) REFERENCES `modulo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora`
--

LOCK TABLES `bitacora` WRITE;
/*!40000 ALTER TABLE `bitacora` DISABLE KEYS */;
INSERT INTO `bitacora` VALUES (32,32014004,1,'modificar','Modificó una historia clínica con cédula: 4234235','2025-05-30 00:22:28'),(34,32014004,5,'Modificar','Se ha modificado una publicación con codigo: 110','2025-05-30 00:30:12'),(36,32014004,2,'modificar','Modificó un personal con cédula: 23045014','2025-05-30 02:00:30'),(40,32014004,9,'eliminar','Eliminó una jornada con código: 3','2025-05-30 03:19:07'),(41,32014004,9,'incluir','Incluyó una nueva jornada con ubicacion: Barquisimeto y fecha: 2025-05-14','2025-05-30 03:19:47'),(42,32014004,9,'modificar','Modificó una jornada con código:  y ubicacion: Barquisimeto','2025-05-30 03:20:00'),(43,32014004,9,'modificar','Modificó una jornada con código:  y ubicacion: Barquisimeto','2025-05-30 03:20:24'),(44,32014004,9,'modificar','Modificó una jornada con código:  y ubicacion: Barquisimeto','2025-05-30 03:20:57'),(45,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:31:16'),(46,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:31:33'),(48,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:46:50'),(49,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:56:54'),(50,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:57:04'),(51,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:59:18'),(52,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:59:22'),(53,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:59:28'),(54,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 04:00:33'),(55,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 04:00:52'),(56,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 04:07:57'),(62,32014004,2,'incluir','Incluyó un nuevo personal con cédula: 12345678','2025-06-02 02:55:06'),(63,32014004,2,'incluir','Incluyó un nuevo personal con cédula: 1234567','2025-06-02 03:05:47'),(64,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-02 03:16:53'),(65,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-02 03:16:55'),(66,32014004,7,'Registrar','Se ha registrado un área con nombre: cardiologia','2025-06-02 03:37:17'),(68,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-05 02:21:36'),(69,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-05 02:21:50'),(70,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-05 02:21:59'),(71,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-05 02:22:01'),(72,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-05 02:22:27'),(73,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-05 02:23:15'),(74,32014004,7,'Modificar','Se ha modificado un área con código: 1','2025-06-05 02:23:27'),(75,32014004,7,'Registrar','Se ha registrado un estudiante con cédula: 31111553','2025-06-05 02:24:18'),(76,32014004,7,'Modificar','Se ha modificado un estudiante con cédula: 31111553','2025-06-05 02:24:52'),(77,32014004,7,'Modificar','Se ha modificado un estudiante con cédula: 31111553','2025-06-05 02:25:32'),(78,32014004,7,'Registrar','Se ha registrado un estudiante con cédula: 32123213','2025-06-05 02:28:26'),(79,32014004,7,'Modificar','Se ha modificado un estudiante con cédula: 32123213','2025-06-05 02:29:09'),(80,32014004,7,'Eliminar','Se ha eliminado un estudiante con cédula: 31111553','2025-06-05 02:37:56'),(81,32014004,7,'Eliminar','Se ha eliminado un estudiante con cédula: 32123213','2025-06-05 02:37:58'),(82,32014004,7,'Registrar','Se ha registrado un estudiante con cédula: 31111553','2025-06-05 02:38:39'),(83,32014004,7,'Modificar','Se ha modificado un estudiante con cédula: 31111553','2025-06-05 02:38:58'),(84,32014004,7,'Modificar','Se ha modificado un estudiante con cédula: 31111553','2025-06-05 02:39:12'),(86,32014004,7,'Registrar','Se ha registrado un estudiante con cédula: 31111553','2025-06-06 01:30:16'),(87,32014004,7,'Registrar','Se ha registrado asistencia para estudiante: 31111553','2025-06-06 01:43:57'),(88,32014004,7,'Eliminar','Se ha eliminado una asistencia del estudiante con cédula: 31111553','2025-06-06 01:44:01'),(89,32014004,7,'Eliminar','Se ha eliminado una asistencia del estudiante con cédula: 31111553','2025-06-06 01:44:03'),(90,32014004,7,'Eliminar','Se ha eliminado una asistencia del estudiante con cédula: 31111553','2025-06-06 01:44:05'),(91,32014004,7,'Eliminar','Se ha eliminado una asistencia del estudiante con cédula: 31111553','2025-06-06 01:44:08'),(92,32014004,7,'Modificar','Se ha modificado un estudiante con cédula: 31111553','2025-06-06 01:44:37');
/*!40000 ALTER TABLE `bitacora` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'Pacientes','Gestion de Pacientes'),(2,'Personal','Gestion de Personal'),(3,'Examenes','Gestion de Examenes'),(4,'Emergencias','Gestion de Emergencias'),(5,'Planificacion','Gestion de Planificacion'),(6,'Consultas','Gestion de Consultas'),(7,'Pasantías','Gestion de Pasantias'),(8,'Pacientes crónicos','Gestion de Consultas'),(9,'Jornadas','Gestion de Jornadas'),(10,'Inventario','Gestion de Inventario'),(11,'Bitácora','Registro de actividades del sistema'),(12,'Usuarios','Gestion de Usuarios'),(13,'Estadistica','Visualizacion de estadisticas del sistema');
/*!40000 ALTER TABLE `modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso`
--

DROP TABLE IF EXISTS `permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permiso` (
  `rol_id` int(11) NOT NULL,
  `modulo_id` int(11) NOT NULL,
  `registrar` tinyint(1) DEFAULT 0,
  `modificar` tinyint(1) DEFAULT 0,
  `eliminar` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`rol_id`,`modulo_id`),
  KEY `modulo_id` (`modulo_id`),
  CONSTRAINT `permiso_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permiso_ibfk_2` FOREIGN KEY (`modulo_id`) REFERENCES `modulo` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso`
--

LOCK TABLES `permiso` WRITE;
/*!40000 ALTER TABLE `permiso` DISABLE KEYS */;
INSERT INTO `permiso` VALUES (1,1,0,0,0),(1,2,0,0,0),(1,3,0,0,0),(1,4,0,0,0),(1,5,0,0,0),(1,6,0,0,0),(1,7,0,0,0),(1,8,0,0,0),(1,9,0,0,0),(1,10,0,0,0),(1,13,0,0,0),(3,1,0,0,0),(3,2,0,0,0),(3,3,0,0,0),(3,4,0,0,0),(3,5,0,0,0),(3,6,0,0,0),(3,7,0,0,0),(3,8,0,0,0),(3,9,0,0,0),(3,10,0,0,0),(3,11,0,0,0),(3,12,0,0,0),(3,13,0,0,0),(4,3,0,0,0),(4,4,0,0,0),(4,5,0,0,0),(4,6,0,0,0),(4,7,0,0,0),(4,8,0,0,0),(4,9,0,0,0),(4,10,0,0,0),(4,13,0,0,0);
/*!40000 ALTER TABLE `permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'Doctor','Doctor a cargo del CDI'),(3,'Admin','Administrador'),(4,'Enfermera','Enfermera del cdi');
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `cedula_personal` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto_perfil` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`cedula_personal`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32014008 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (32014004,'Eduin Meneses',32014004,'$2y$10$PgIc9yAtsajStfxliESh4.HKw8Yf0TK/Cw3trtdNDPwgd8vobqu/O',3,'2025-05-15 04:00:00','6831d82f81b40.png');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-07 21:14:02
