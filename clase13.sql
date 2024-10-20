-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: clase13
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
-- Table structure for table `antecedentes`
--

DROP TABLE IF EXISTS `antecedentes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `antecedentes` (
  `cod_antecedente` int NOT NULL AUTO_INCREMENT,
  `antec_padre` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `antec_madre` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `antec_hermano` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cedula_h` int NOT NULL,
  PRIMARY KEY (`cod_antecedente`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `antecedentes_ibfk_1` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `antecedentes`
--

LOCK TABLES `antecedentes` WRITE;
/*!40000 ALTER TABLE `antecedentes` DISABLE KEYS */;
/*!40000 ALTER TABLE `antecedentes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consultas`
--

DROP TABLE IF EXISTS `consultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consultas` (
  `cod_consulta` int NOT NULL AUTO_INCREMENT,
  `fechaconsulta` date DEFAULT NULL,
  `consulta` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `diagnostico` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tratamientos` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cedula_p` int NOT NULL,
  `cedula_h` int NOT NULL,
  PRIMARY KEY (`cod_consulta`),
  KEY `cedula_p` (`cedula_p`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `consultas_ibfk_1` FOREIGN KEY (`cedula_p`) REFERENCES `personal` (`cedula_personal`),
  CONSTRAINT `consultas_ibfk_2` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultas`
--

LOCK TABLES `consultas` WRITE;
/*!40000 ALTER TABLE `consultas` DISABLE KEYS */;
INSERT INTO `consultas` VALUES (2,'2024-10-20','hola','hola','hola',12345678,33333333),(3,'2024-10-08','ss','ss','ss',31111553,12122122),(4,'2024-10-18','ss','sss','sss',12345678,33333333),(5,'2024-10-21','rr','rrr','rrrr',31111553,33333333),(6,'2024-10-10','tt','tt','tt',12345678,33333333),(7,'2024-11-02','yy','yyy','yyy',12345678,33333333),(8,'2024-10-26','uu','uuu','uuu',12345678,24222543),(9,'2024-10-11','tt','tt','ttt',31111553,12122122),(10,'2024-10-14','jjjjjjjjjjjjjjjjj','jjjjjjjjjjjjjjjjj','jjjjjjjjjjjjj',31111553,24222543),(11,'2024-10-22','yyyyyyyyyyyyyyyyyy','yyyyyyyyyyyy','yyyyyyyyyy',31111553,33333333),(12,'2024-10-15','llego marico','le guta el pene','dejar de ser marico y una buena totona',12345678,12122122),(13,'2024-10-15','ggg','ggg','ggg',31111553,12122122),(14,'2024-10-15','yyy','yyy','yyyy',31111553,24222543),(15,'2024-10-15','uuu','uuu','uuuu',31111553,33333333),(16,'2024-10-15','uu','uuu','uuu',31111553,12122122);
/*!40000 ALTER TABLE `consultas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emergencias`
--

DROP TABLE IF EXISTS `emergencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `emergencias` (
  `cod_emergencia` int NOT NULL AUTO_INCREMENT,
  `horaingreso` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fechaingreso` date DEFAULT NULL,
  `motingreso` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `diagnostico_e` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tratamientos` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cedula_p` int NOT NULL,
  `cedula_h` int NOT NULL,
  PRIMARY KEY (`cod_emergencia`),
  KEY `cedula_p` (`cedula_p`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `emergencias_ibfk_1` FOREIGN KEY (`cedula_p`) REFERENCES `personal` (`cedula_personal`),
  CONSTRAINT `emergencias_ibfk_2` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emergencias`
--

LOCK TABLES `emergencias` WRITE;
/*!40000 ALTER TABLE `emergencias` DISABLE KEYS */;
INSERT INTO `emergencias` VALUES (14,'22:40','2024-08-28','chaooo','es pato ','patosida',31111553,33333333),(16,'23:34','2024-08-14','es marico','que es marico','dejar de ser marico',31111553,33333333),(19,'12:36','2024-09-04','eduin lo apoñalo','metale el dedo en el culo','metanle dos dedos en el culo',12345678,33333333),(20,'18:42','2024-10-26','gg','gg','gg',31111553,33333333),(23,'20:57','2024-10-21','','','',12345678,24222543),(25,'06:06','2024-10-30','hhhhh','hhhhh','hhhh',31111553,24222543),(29,'06:11','2024-10-09','ssss','ssss','ssss',31111553,24222543),(30,'09:06','2024-10-23','sss','sss','ss',31111553,24222543),(31,'09:06','2024-10-23','sss','sss','ss',31111553,24222543),(32,'06:20','2024-10-28','qqqq','qqq','qqq',31111553,24222543),(33,'06:19','2024-10-28','aaaa','aaa','aaa',12345678,24222543),(34,'06:26','2024-10-29','rrrrrr','rrrr','rrrr',31111553,24222543),(36,'08:11','2024-10-29','rr','rr','rr',31111553,33333333),(37,'08:11','2024-10-13','ss','ss','ss',12345679,24222543),(41,'22:13','2024-10-09','see','eee','eee',12345678,24222543),(42,'06:40','2024-10-08','ss','ss','ssss',12345678,33333333),(43,'15:47','2024-11-09','porque es marico ','en verdad es marico','dejar de ser marico',31111553,24222543),(44,'11:11','2024-10-07','ddd','fff','fff',12345678,33333333),(46,'05:35','2024-10-06','le duele el cuerpo','es un flojo','penesilina',31111553,12122122),(47,'06:18','2024-10-09','ss','ss','sss',12345678,33333333),(48,'18:29','2024-10-08','tt','ttt','tt',31111553,33333333),(49,'06:29','2024-10-29','rr','rr','rrr',31111553,24222543),(50,'10:14','2024-10-15','tttt','ggg','ggg',31111553,12122122);
/*!40000 ALTER TABLE `emergencias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `examenes`
--

DROP TABLE IF EXISTS `examenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `examenes` (
  `cod_examenes` int NOT NULL AUTO_INCREMENT,
  `nombre_examen` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion_examen` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`cod_examenes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examenes`
--

LOCK TABLES `examenes` WRITE;
/*!40000 ALTER TABLE `examenes` DISABLE KEYS */;
/*!40000 ALTER TABLE `examenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `examenes_f`
--

DROP TABLE IF EXISTS `examenes_f`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `examenes_f` (
  `cod_examen_f` int NOT NULL AUTO_INCREMENT,
  `general` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cedula_h` int NOT NULL,
  PRIMARY KEY (`cod_examen_f`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `examenes_f_ibfk_1` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examenes_f`
--

LOCK TABLES `examenes_f` WRITE;
/*!40000 ALTER TABLE `examenes_f` DISABLE KEYS */;
/*!40000 ALTER TABLE `examenes_f` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `examenes_r`
--

DROP TABLE IF EXISTS `examenes_r`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `examenes_r` (
  `cod_examen_r` int NOT NULL AUTO_INCREMENT,
  `cabeza_craneo` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ojos` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nariz` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `oidos` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `boca_cerrada` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `boca_abierta` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tiroides` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `extremidades` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cedula_h` int NOT NULL,
  PRIMARY KEY (`cod_examen_r`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `examenes_r_ibfk_1` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examenes_r`
--

LOCK TABLES `examenes_r` WRITE;
/*!40000 ALTER TABLE `examenes_r` DISABLE KEYS */;
/*!40000 ALTER TABLE `examenes_r` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `examenes_s`
--

DROP TABLE IF EXISTS `examenes_s`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `examenes_s` (
  `cod_examen_s` int NOT NULL AUTO_INCREMENT,
  `respiratorio` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cardiovascular` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `abdomen` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `extremidades` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `neurologicos` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cedula_h` int NOT NULL,
  PRIMARY KEY (`cod_examen_s`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `examenes_s_ibfk_1` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examenes_s`
--

LOCK TABLES `examenes_s` WRITE;
/*!40000 ALTER TABLE `examenes_s` DISABLE KEYS */;
/*!40000 ALTER TABLE `examenes_s` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historias`
--

DROP TABLE IF EXISTS `historias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historias` (
  `cedula_historia` int NOT NULL,
  `nombre` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `apellido` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_nac` date DEFAULT NULL,
  `edad` int DEFAULT NULL,
  `estadocivil` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ocupacion` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hda` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alergias` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alergias_med` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quirurgico` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `transsanguineo` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `psicosocial` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `habtoxico` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`cedula_historia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historias`
--

LOCK TABLES `historias` WRITE;
/*!40000 ALTER TABLE `historias` DISABLE KEYS */;
INSERT INTO `historias` VALUES (12122122,'cambur','verde','2012-12-17',23,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(24222543,'mago','pinton','2012-12-17',23,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(33333333,'jose','jose','2012-12-17',23,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `historias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal`
--

DROP TABLE IF EXISTS `personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal` (
  `cedula_personal` int NOT NULL,
  `nombre` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `apellido` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `correo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cargo` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`cedula_personal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal`
--

LOCK TABLES `personal` WRITE;
/*!40000 ALTER TABLE `personal` DISABLE KEYS */;
INSERT INTO `personal` VALUES (31,'anthoan','gonzalez','nose','3111','doctor'),(12345678,'diego','vergaline','nose@gmail.com','000000000','Doctor'),(12345679,'anthoan','gonzalez','aa','04145842747','Enfermera'),(31111553,'franchesco2','gonzalez','nose','3111','enfermero');
/*!40000 ALTER TABLE `personal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registro`
--

DROP TABLE IF EXISTS `registro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registro` (
  `fecha_r` date DEFAULT NULL,
  `cedula_h` int NOT NULL,
  `cod_examenes` int NOT NULL,
  `observacion_examen` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  KEY `cedula_h` (`cedula_h`),
  KEY `cod_examenes` (`cod_examenes`),
  CONSTRAINT `registro_ibfk_1` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`),
  CONSTRAINT `registro_ibfk_2` FOREIGN KEY (`cod_examenes`) REFERENCES `examenes` (`cod_examenes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registro`
--

LOCK TABLES `registro` WRITE;
/*!40000 ALTER TABLE `registro` DISABLE KEYS */;
/*!40000 ALTER TABLE `registro` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-20 18:43:35
