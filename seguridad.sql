-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: seguridad
-- ------------------------------------------------------
-- Server version	8.4.0

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
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `modulo_id` int NOT NULL,
  `accion` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `fecha_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `modulo_id` (`modulo_id`),
  CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`),
  CONSTRAINT `bitacora_ibfk_2` FOREIGN KEY (`modulo_id`) REFERENCES `modulo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=461 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora`
--

LOCK TABLES `bitacora` WRITE;
/*!40000 ALTER TABLE `bitacora` DISABLE KEYS */;
INSERT INTO `bitacora` VALUES (34,32014004,5,'Modificar','Se ha modificado una publicación con codigo: 110','2025-05-30 00:30:12'),(36,32014004,2,'modificar','Modificó un personal con cédula: 23045014','2025-05-30 02:00:30'),(40,32014004,9,'eliminar','Eliminó una jornada con código: 3','2025-05-30 03:19:07'),(41,32014004,9,'incluir','Incluyó una nueva jornada con ubicacion: Barquisimeto y fecha: 2025-05-14','2025-05-30 03:19:47'),(42,32014004,9,'modificar','Modificó una jornada con código:  y ubicacion: Barquisimeto','2025-05-30 03:20:00'),(43,32014004,9,'modificar','Modificó una jornada con código:  y ubicacion: Barquisimeto','2025-05-30 03:20:24'),(44,32014004,9,'modificar','Modificó una jornada con código:  y ubicacion: Barquisimeto','2025-05-30 03:20:57'),(45,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:31:16'),(46,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:31:33'),(48,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:46:50'),(49,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:56:54'),(50,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:57:04'),(51,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:59:18'),(52,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:59:22'),(53,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 03:59:28'),(54,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 04:00:33'),(55,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 04:00:52'),(56,32014004,9,'modificar','Modificó una jornada con código: 4 y ubicacion: Barquisimeto','2025-05-30 04:07:57'),(62,32014004,2,'incluir','Incluyó un nuevo personal con cédula: 12345678','2025-06-02 02:55:06'),(63,32014004,2,'incluir','Incluyó un nuevo personal con cédula: 1234567','2025-06-02 03:05:47'),(64,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-02 03:16:53'),(65,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-02 03:16:55'),(66,32014004,7,'Registrar','Se ha registrado un área con nombre: cardiologia','2025-06-02 03:37:17'),(68,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-05 02:21:36'),(69,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-05 02:21:50'),(70,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-05 02:21:59'),(71,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-05 02:22:01'),(72,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-05 02:22:27'),(73,32014004,7,'Registrar','Se ha registrado un área con nombre: Cardiologia','2025-06-05 02:23:15'),(74,32014004,7,'Modificar','Se ha modificado un área con código: 1','2025-06-05 02:23:27'),(75,32014004,7,'Registrar','Se ha registrado un estudiante con cédula: 31111553','2025-06-05 02:24:18'),(76,32014004,7,'Modificar','Se ha modificado un estudiante con cédula: 31111553','2025-06-05 02:24:52'),(77,32014004,7,'Modificar','Se ha modificado un estudiante con cédula: 31111553','2025-06-05 02:25:32'),(78,32014004,7,'Registrar','Se ha registrado un estudiante con cédula: 32123213','2025-06-05 02:28:26'),(79,32014004,7,'Modificar','Se ha modificado un estudiante con cédula: 32123213','2025-06-05 02:29:09'),(80,32014004,7,'Eliminar','Se ha eliminado un estudiante con cédula: 31111553','2025-06-05 02:37:56'),(81,32014004,7,'Eliminar','Se ha eliminado un estudiante con cédula: 32123213','2025-06-05 02:37:58'),(82,32014004,7,'Registrar','Se ha registrado un estudiante con cédula: 31111553','2025-06-05 02:38:39'),(83,32014004,7,'Modificar','Se ha modificado un estudiante con cédula: 31111553','2025-06-05 02:38:58'),(84,32014004,7,'Modificar','Se ha modificado un estudiante con cédula: 31111553','2025-06-05 02:39:12'),(86,32014004,7,'Registrar','Se ha registrado un estudiante con cédula: 31111553','2025-06-06 01:30:16'),(87,32014004,7,'Registrar','Se ha registrado asistencia para estudiante: 31111553','2025-06-06 01:43:57'),(88,32014004,7,'Eliminar','Se ha eliminado una asistencia del estudiante con cédula: 31111553','2025-06-06 01:44:01'),(89,32014004,7,'Eliminar','Se ha eliminado una asistencia del estudiante con cédula: 31111553','2025-06-06 01:44:03'),(90,32014004,7,'Eliminar','Se ha eliminado una asistencia del estudiante con cédula: 31111553','2025-06-06 01:44:05'),(91,32014004,7,'Eliminar','Se ha eliminado una asistencia del estudiante con cédula: 31111553','2025-06-06 01:44:08'),(92,32014004,7,'Modificar','Se ha modificado un estudiante con cédula: 31111553','2025-06-06 01:44:37'),(94,32014004,10,'Registrar','Se ha registrado un medicamento: perebron','2025-06-08 15:29:07'),(95,32014004,10,'Eliminar','Se ha eliminado un medicamento: 4','2025-06-08 15:54:06'),(96,32014004,10,'Registrar','Se ha registrado un medicamento: taurina','2025-06-08 15:59:07'),(97,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-06-08 16:01:55'),(101,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-06-16 14:53:37'),(102,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-06-16 14:57:24'),(103,32014004,5,'Modificar','Se ha modificado una publicación con codigo: 1','2025-06-16 15:24:30'),(104,32014004,5,'Modificar','Se ha modificado una publicación con codigo: 1','2025-06-16 15:24:47'),(105,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-06-16 15:31:02'),(106,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-06-16 15:41:14'),(143,32014004,5,'Modificar','Se ha modificado una publicación con codigo: 2','2025-06-22 16:32:53'),(152,32014004,1,'Modificar','Se ha modificado el paciente: yyyyyyy YYYYY','2025-06-23 13:59:03'),(153,32014004,1,'Modificar','Se ha modificado el paciente: WWWWWW WWW','2025-06-23 14:22:31'),(155,32014004,1,'Modificar','Se ha modificado el paciente: yyy YYYYY','2025-06-23 17:52:06'),(156,32014004,1,'Modificar','Se ha modificado el paciente: yyyyy YYYYY','2025-06-23 17:52:22'),(157,32014004,1,'Modificar','Se ha modificado el paciente: lolololo YYYYY','2025-06-23 17:53:13'),(158,32014004,1,'Modificar','Se ha modificado el paciente: loooooooooooooooo YYYYY','2025-06-23 17:55:51'),(159,32014004,1,'Modificar','Se ha modificado el paciente: looooooooooooooooo YYYYY','2025-06-23 18:00:13'),(160,32014004,1,'Modificar','Se ha modificado el paciente: looooooooooooooooo YYYYY','2025-06-23 18:00:27'),(161,32014004,1,'Modificar','Se ha modificado el paciente: loooooo YYYYY','2025-06-23 18:00:38'),(163,32014004,1,'Modificar','Se ha modificado el paciente: noooooooo YYYYY','2025-06-24 13:48:20'),(164,32014004,1,'Modificar','Se ha modificado el paciente: siiiiiiii YYYYY','2025-06-24 15:48:40'),(165,32014004,1,'Modificar','Se ha modificado el paciente: siiiiiiii YYYYY','2025-06-24 15:49:02'),(166,32014004,1,'Modificar','Se ha modificado el paciente: siiiiiiii YYYYY','2025-06-24 15:49:34'),(167,32014004,1,'Modificar','Se ha modificado el paciente: noooononono YYYYY','2025-06-24 15:52:15'),(168,32014004,1,'Modificar','Se ha modificado el paciente: siiiiiiiiiiiii YYYYY','2025-06-24 15:53:56'),(169,32014004,1,'Modificar','Se ha modificado el paciente: noooooo YYYYY','2025-06-24 15:54:31'),(170,32014004,2,'modificar','Modificó un personal con cédula: 20000001','2025-06-24 17:27:35'),(171,32014004,2,'modificar','Modificó un personal con cédula: 20000001','2025-06-24 17:27:38'),(172,32014004,2,'modificar','Modificó un personal con cédula: 20000001','2025-06-24 17:28:32'),(173,32014004,2,'modificar','Modificó un personal con cédula: 20000001','2025-06-24 17:28:44'),(174,32014004,2,'modificar','Modificó un personal con cédula: 20000001','2025-06-24 17:29:00'),(175,32014004,2,'modificar','Modificó un personal con cédula: 20000001','2025-06-24 17:30:19'),(176,32014004,2,'modificar','Modificó un personal con cédula: 20000001','2025-06-24 17:39:53'),(177,32014004,2,'modificar','Modificó un personal con cédula: 20000001','2025-06-24 17:41:34'),(178,32014004,2,'modificar','Modificó un personal con cédula: 20000001','2025-06-24 17:43:23'),(179,32014004,2,'modificar','Modificó un personal con cédula: 20000001','2025-06-24 17:58:48'),(180,32014004,2,'modificar','Modificó un personal con cédula: 20000001','2025-06-24 18:01:10'),(181,32014004,1,'Modificar','Se ha modificado el paciente: sisisi YYYYY','2025-06-24 18:01:32'),(182,32014004,2,'modificar','Modificó un personal con cédula: 20000001','2025-06-24 18:06:10'),(183,32014004,1,'Modificar','Se ha modificado el paciente: yyyyyy YYYYY','2025-06-24 18:08:58'),(184,32014004,1,'Modificar','Se ha modificado el paciente: MAMI YYYYY','2025-06-24 18:16:36'),(185,32014004,2,'modificar','Modificó un personal con cédula: 20000001','2025-06-24 18:19:31'),(186,32014004,1,'Modificar','Se ha modificado el paciente: abusamadres YYYYY','2025-06-24 18:21:37'),(187,32014004,1,'Modificar','Se ha modificado el paciente: johnes YYYYY','2025-06-24 18:28:23'),(189,32014004,2,'modificar','Modificó un personal con cédula: 20000001','2025-06-24 18:33:05'),(190,32014004,1,'Modificar','Se ha modificado el paciente: abusmadres YYYYY','2025-06-24 18:41:50'),(191,32014004,2,'eliminar','Eliminó un personal con cédula: 20000010','2025-06-24 18:47:11'),(192,32014004,2,'incluir','Incluyó un nuevo personal con cédula: 5468654','2025-06-24 19:00:13'),(193,32014004,2,'incluir','Incluyó un nuevo personal con cédula: 5468654','2025-06-24 19:00:16'),(194,32014004,2,'modificar','Modificó un personal con cédula: 5468654','2025-06-24 19:05:54'),(196,32014004,2,'modificar','Modificó un personal con cédula: 5468654','2025-06-26 12:21:16'),(197,32014004,2,'modificar','Modificó un personal con cédula: 5468654','2025-06-26 12:21:50'),(217,32014004,1,'Modificar','Se ha modificado el paciente: Laura Fernández','2025-06-29 01:28:40'),(219,32014004,1,'Modificar','Se ha modificado el paciente: Laura Fernández','2025-06-29 15:31:48'),(220,32014004,1,'Modificar','Se ha modificado el paciente: Carlos Ramírez','2025-06-29 15:35:11'),(221,32014004,1,'Modificar','Se ha modificado el paciente: Carlos Ramírez','2025-06-29 16:29:39'),(226,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-06-30 17:13:15'),(227,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-06-30 17:13:18'),(228,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-06-30 17:13:22'),(229,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-06-30 17:19:21'),(230,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-06-30 17:19:34'),(231,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-06-30 17:19:43'),(232,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-06-30 17:19:52'),(233,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-07-01 01:55:39'),(236,32014004,5,'Eliminar','Se ha eliminado una publicación con codigo: 6','2025-07-01 02:25:07'),(237,32014004,5,'Eliminar','Se ha eliminado una publicación con codigo: 4','2025-07-01 02:25:10'),(238,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-07-01 02:25:25'),(240,32014004,4,'Modificar','Se ha modificado la emergencia del paciente: 10000003','2025-07-01 03:05:15'),(242,32014004,11,'Consultar','Consulta de registros de bitácora','2025-07-02 01:17:41'),(243,32014004,1,'Modificar','Se ha modificado el paciente: Ana Martínez','2025-07-02 01:37:47'),(248,32014004,4,'Registrar','Se ha registrado una emergencia para el paciente: 10000004','2025-07-02 01:58:41'),(249,32014004,3,'Registrar','Se ha registrado el tipo de examen: ','2025-07-02 02:12:02'),(250,32014004,3,'Registrar','Se ha registrado el tipo de examen: ','2025-07-02 02:12:04'),(251,32014004,3,'Registrar','Se ha registrado el tipo de examen: ','2025-07-02 02:12:06'),(252,32014004,3,'Registrar','Se ha registrado un examen para el paciente: 10000004','2025-07-02 02:12:29'),(253,32014004,4,'Registrar','Se ha registrado una emergencia para el paciente: 10000004','2025-07-02 02:15:06'),(254,32014004,1,'Modificar','Se ha modificado el paciente: Ana Martínez','2025-07-02 02:23:37'),(255,32014004,1,'Modificar','Se ha modificado el paciente: Ana Martínez','2025-07-02 02:23:51'),(256,32014004,1,'Modificar','Se ha modificado el paciente: Ana Martínez','2025-07-02 02:26:38'),(257,32014004,12,'Modificar','Se han actualizado los permisos del rol ID: 1','2025-07-02 03:28:24'),(258,32014004,1,'Registrar','Se ha registrado un paciente: yyyyyyyyy yyyyyyyyyyyyyyy','2025-07-02 03:56:37'),(260,32014004,1,'Registrar','Se ha registrado un paciente: ggggggg ggggg','2025-07-02 15:00:02'),(261,32014004,1,'Registrar','Se ha registrado un paciente: ggggggg ggggggggggggg','2025-07-02 15:01:11'),(262,32014004,1,'Registrar','Se ha registrado un paciente: xxxxxxxx xxxxxxxxx','2025-07-02 15:06:57'),(263,32014004,1,'Registrar','Se ha registrado un paciente: xxxxxx xxxxxxxxxxx','2025-07-02 15:10:34'),(264,32014004,1,'Registrar','Se ha registrado un paciente: xxxxxx xxxxxxx','2025-07-02 15:11:31'),(265,32014004,1,'Registrar','Se ha registrado un paciente: xxxxxx xxxxxxx','2025-07-02 15:17:02'),(266,32014004,1,'Modificar','Se ha modificado el paciente: Lauraa Fernández','2025-07-02 15:22:13'),(267,32014004,1,'Modificar','Se ha modificado el paciente: Lauraaa Fernández','2025-07-02 15:22:53'),(268,32014004,1,'Modificar','Se ha modificado el paciente: Lauraaa Fernández','2025-07-02 15:23:35'),(269,32014004,1,'Modificar','Se ha modificado el paciente: Lauraaaaaa Fernández','2025-07-02 15:26:23'),(270,32014004,1,'Registrar','Se ha registrado un paciente: Maria Salazar','2025-07-02 15:27:10'),(271,32014004,1,'Registrar','Se ha registrado un paciente: hhhhhh hhhhh','2025-07-02 15:34:51'),(272,32014004,1,'Registrar','Se ha registrado un paciente: XZCCZXCZX ZXCCZ','2025-07-02 15:36:40'),(273,32014004,1,'Registrar','Se ha registrado un paciente: fffffff fffffffffffff','2025-07-02 15:40:31'),(274,32014004,2,'Eliminar','Se ha eliminado el personal con cédula: 20000001','2025-07-03 02:28:14'),(275,32014004,2,'Eliminar','Se ha eliminado el personal con cédula: 20000001','2025-07-03 02:28:28'),(276,32014004,10,'Entrada','Entrada múltiple de medicamentos','2025-07-03 03:07:12'),(277,32014004,10,'Entrada','Entrada múltiple de medicamentos','2025-07-03 03:07:35'),(278,32014004,10,'Entrada','Entrada múltiple de medicamentos','2025-07-03 03:10:41'),(280,32014004,10,'Eliminar','Se ha eliminado un medicamento: 1','2025-07-03 16:14:12'),(281,32014004,10,'Salida','Salida múltiple de medicamentos','2025-07-03 16:14:34'),(282,32014004,10,'Eliminar','Se ha eliminado un medicamento: 1','2025-07-03 16:14:42'),(283,32014004,10,'Registrar','Se ha registrado un medicamento: Lamotrigina','2025-07-03 16:15:31'),(284,32014004,10,'Entrada','Entrada múltiple de medicamentos','2025-07-03 16:18:25'),(285,32014004,10,'Registrar','Se ha registrado un medicamento: AAAAAAAA','2025-07-03 16:23:24'),(286,32014004,10,'Entrada','Entrada múltiple de medicamentos','2025-07-03 16:23:58'),(287,32014004,10,'Modificar','Se ha modificado un medicamento: AAAAAAAAAA','2025-07-03 16:26:25'),(288,32014004,10,'Salida','Salida múltiple de medicamentos','2025-07-03 16:26:37'),(289,32014004,10,'Salida','Salida múltiple de medicamentos','2025-07-03 16:26:59'),(290,32014004,10,'Eliminar','Se ha eliminado un medicamento: 13','2025-07-03 16:27:06'),(291,32014004,10,'Eliminar','Se ha eliminado un medicamento: 7','2025-07-03 16:28:52'),(292,32014004,10,'Eliminar','Se ha eliminado un medicamento: 11','2025-07-03 16:30:18'),(293,32014004,10,'Registrar','Se ha registrado un medicamento: aaaaaaaaaa','2025-07-03 16:30:58'),(294,32014004,10,'Entrada','Entrada múltiple de medicamentos','2025-07-03 16:31:14'),(295,32014004,10,'Salida','Salida múltiple de medicamentos','2025-07-03 16:31:29'),(296,32014004,10,'Eliminar','Se ha eliminado un medicamento: 14','2025-07-03 16:31:35'),(299,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-07-03 17:55:45'),(300,32014004,5,'Eliminar','Se ha eliminado una publicación con codigo: 1','2025-07-03 18:02:09'),(301,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-07-03 18:11:38'),(302,32014004,5,'Eliminar','Se ha eliminado una publicación con codigo: 2','2025-07-03 18:24:25'),(303,32014004,5,'Registrar','Se ha registrado una publicación con codigo: ','2025-07-03 18:24:37'),(356,32014004,5,'Eliminar','Se ha eliminado una publicación con codigo: 3','2025-07-04 12:36:15'),(357,32014004,5,'Registrar','Se ha registrado una publicación con codigo: 21','2025-07-04 12:36:50'),(358,32014004,5,'Modificar','Se ha modificado una publicación con codigo: 21','2025-07-04 12:39:39'),(359,32014004,5,'Registrar','Se ha registrado una publicación con codigo: 22','2025-07-04 12:39:59'),(360,32014004,5,'Eliminar','Se ha eliminado una publicación con codigo: 21','2025-07-04 12:43:46'),(361,32014004,5,'Registrar','Se ha registrado una publicación con codigo: 23','2025-07-04 12:44:03'),(362,32014004,5,'Modificar','Se ha modificado una publicación con codigo: 23','2025-07-04 12:50:40'),(363,32014004,5,'Modificar','Se ha modificado una publicación con codigo: 23','2025-07-04 12:55:09'),(364,32014004,5,'Eliminar','Se ha eliminado una publicación con codigo: 23','2025-07-04 12:55:22'),(365,32014004,5,'Registrar','Se ha registrado una publicación con codigo: 24','2025-07-04 12:56:29'),(366,32014004,5,'Eliminar','Se ha eliminado una publicación con codigo: 24','2025-07-04 12:57:50');
/*!40000 ALTER TABLE `bitacora` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'Pacientes','Gestion de Pacientes'),(2,'Personal','Gestion de Personal'),(3,'Examenes','Gestion de Examenes'),(4,'Emergencias','Gestion de Emergencias'),(5,'Planificacion','Gestion de Planificacion'),(6,'Consultas','Gestion de Consultas'),(7,'Pasantías','Gestion de Pasantias'),(8,'Pacientes crónicos','Gestion de Consultas'),(9,'Jornadas','Gestion de Jornadas'),(10,'Inventario','Gestion de Inventario'),(11,'Bitácora','Registro de actividades del sistema'),(12,'Usuarios','Gestion de Usuarios'),(13,'Estadistica','Visualizacion de estadisticas del sistema'),(14,'Historias','Visualizacion de Historias Medicas');
/*!40000 ALTER TABLE `modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso`
--

DROP TABLE IF EXISTS `permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permiso` (
  `rol_id` int NOT NULL,
  `modulo_id` int NOT NULL,
  `registrar` tinyint(1) DEFAULT '0',
  `modificar` tinyint(1) DEFAULT '0',
  `eliminar` tinyint(1) DEFAULT '0',
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
INSERT INTO `permiso` VALUES (1,1,1,1,1),(1,2,1,1,0),(1,3,1,1,1),(1,4,1,1,1),(1,5,1,1,1),(1,6,1,1,1),(1,7,1,1,1),(1,8,1,1,1),(1,9,1,1,1),(1,10,1,1,1),(1,12,1,1,1),(1,13,1,1,1),(1,14,1,1,1),(3,1,1,1,1),(3,2,1,1,1),(3,3,1,1,1),(3,4,1,1,1),(3,5,1,1,1),(3,6,1,1,1),(3,7,1,1,1),(3,8,1,1,1),(3,9,1,1,1),(3,10,1,1,1),(3,11,1,1,1),(3,12,1,1,1),(3,13,1,1,1),(3,14,1,1,1),(4,3,0,0,0),(4,4,0,0,0),(4,5,0,0,0),(4,6,0,0,0),(4,7,0,0,0),(4,8,0,0,0),(4,9,0,0,0),(4,10,0,0,0),(4,13,0,0,0);
/*!40000 ALTER TABLE `permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
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
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `cedula_personal` int NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rol_id` int NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `foto_perfil` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`cedula_personal`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32014010 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (32014004,'Eduin Meneses',32014004,'$2y$10$PgIc9yAtsajStfxliESh4.HKw8Yf0TK/Cw3trtdNDPwgd8vobqu/O',3,'2025-05-15 04:00:00','6858224043e3a.jpeg'),(32014008,'Juan Esteban',32014005,'$2y$10$poFyi/Un9D1YKKvbw.LkEuZJcglSeqiH4vn0C28AAfW/ncbBQfRBC',3,'2025-06-21 14:56:03','6856c803aa93e.png'),(32014009,'Anthoan Gonzalez',20000006,'$2y$10$8GAM1BfE05eEr6ifZhiw/ui16Sw0tJMuRqSaeXe151aAQAt6QbnV.',1,'2025-06-30 02:28:41','6861f658f09a9.png');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'seguridad'
--

--
-- Dumping routines for database 'seguridad'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-18 21:51:45
