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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora`
--

LOCK TABLES `bitacora` WRITE;
/*!40000 ALTER TABLE `bitacora` DISABLE KEYS */;
INSERT INTO `bitacora` VALUES (1,30128924,10,'Eliminar','Se ha eliminado un medicamento','2025-05-20 16:34:22'),(2,30128924,10,'Modificar','Se ha modificado un medicamento','2025-05-20 16:44:25'),(3,30128924,10,'Modificar','Se ha modificado un medicamento','2025-05-20 16:44:31'),(4,30128924,10,'Registrar','Se ha registrado un medicamento','2025-05-20 16:44:44'),(5,30128924,10,'Eliminar','Se ha eliminado un medicamento','2025-05-20 16:49:44'),(6,30128924,10,'Eliminar','Se ha eliminado un medicamento','2025-05-20 16:49:51'),(7,30128924,10,'Eliminar','Se ha eliminado un medicamento','2025-05-20 16:54:33'),(8,30128924,10,'Registrar','Se ha registrado un medicamento','2025-05-20 16:54:48'),(9,30128924,10,'Eliminar','Se ha eliminado un medicamento','2025-05-20 16:56:02'),(10,30128924,10,'Registrar','Se ha registrado un medicamento','2025-05-20 16:56:53'),(11,30128924,10,'Eliminar','Se ha eliminado un medicamento','2025-05-20 16:56:57'),(12,30128924,5,'Registrar','Se ha registrado una publicación','2025-05-20 16:59:46'),(13,30128924,5,'Modificar','Se ha modificado una publicación','2025-05-21 05:13:30'),(14,30128924,5,'Modificar','Se ha modificado una publicación','2025-05-21 05:13:44'),(15,30128924,5,'Registrar','Se ha registrado una publicación','2025-05-21 06:27:41'),(16,30128924,5,'Registrar','Se ha registrado una publicación','2025-05-21 06:28:32'),(17,30128924,5,'Eliminar','Se ha eliminado un medicamento','2025-05-21 06:28:34'),(18,30128924,5,'Eliminar','Se ha eliminado un medicamento','2025-05-21 06:28:36'),(19,30128924,5,'Registrar','Se ha registrado una publicación','2025-05-21 14:36:01'),(20,30128924,5,'Eliminar','Se ha eliminado un medicamento','2025-05-21 14:36:05'),(21,30128924,5,'Registrar','Se ha registrado una publicación','2025-05-21 14:40:48');
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'Pacientes','Gestion de Pacientes'),(2,'Personal','Gestion de Personal'),(3,'Examenes','Gestion de Examenes'),(4,'Emergencias','Gestion de Emergencias'),(5,'Planificacion','Gestion de Planificacion'),(6,'Consultas','Gestion de Consultas'),(7,'Pasantías','Gestion de Pasantias'),(8,'Pacientes crónicos','Gestion de Consultas'),(9,'Jornadas','Gestion de Jornadas'),(10,'Inventario','Gestion de Inventario'),(11,'Bitácora','Registro de actividades del sistema');
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
INSERT INTO `permiso` VALUES (1,1),(3,1),(1,2),(3,2),(1,3),(3,3),(1,4),(3,4),(1,5),(3,5),(1,6),(3,6),(4,6),(1,7),(3,7),(1,8),(3,8),(1,9),(3,9),(1,10),(3,10);
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
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rol_id` int NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32014008 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (30128924,'Juan Esteban','juanchacon@gmail.com','$2y$10$x4r96.20FzIaeM75XPtiQ.v63OvvRduWOmDI85eff7hrzxq/Q39HS',1,'2025-05-19 16:49:46'),(31111513,'Anthoan','anthoangonzalez@gmail.com','$2y$10$zw0QFC5OGzv1Oe.MNgC7Eu3DMTHF.HJprcpOhX9zcnA76pWaR8kIm',1,'2025-05-21 02:46:19'),(32014004,'Eduin','eduinmeneses@gmail.com','$2y$10$PgIc9yAtsajStfxliESh4.HKw8Yf0TK/Cw3trtdNDPwgd8vobqu/O',3,'2025-05-15 04:00:00'),(32014007,'Jose','correito@correo.com','$2y$10$tEnaUz8W14jQxLXBH/rUFOY.J.l5tXim/8FEu0ehPr1Dqhv7GhjFq',1,'2025-05-19 16:49:46');
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

-- Dump completed on 2025-05-21 11:46:59
