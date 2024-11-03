-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: sss
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
  `antec_padre` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `antec_madre` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `antec_hermano` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cedula_h` int NOT NULL,
  PRIMARY KEY (`cod_antecedente`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `antecedentes_ibfk_1` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `antecedentes`
--

LOCK TABLES `antecedentes` WRITE;
/*!40000 ALTER TABLE `antecedentes` DISABLE KEYS */;
INSERT INTO `antecedentes` VALUES (1,'','','',30128924),(32,'','','',12345678),(33,'A','A','A',88888888),(34,'B','B','B',9999999),(35,NULL,NULL,NULL,8564289),(36,'','','',85642892),(37,'NO POSEE','NO POSEE','NO POSEE',7856209),(38,'L','L','L',5465489);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultas`
--

LOCK TABLES `consultas` WRITE;
/*!40000 ALTER TABLE `consultas` DISABLE KEYS */;
INSERT INTO `consultas` VALUES (2,'2024-10-01','Primera consulta','Diarrea','Resogal',31111553,30128924);
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emergencias`
--

LOCK TABLES `emergencias` WRITE;
/*!40000 ALTER TABLE `emergencias` DISABLE KEYS */;
INSERT INTO `emergencias` VALUES (19,'21:12','2024-10-01','AAAAAAAA','AAAAAAAA','AAAAA',31111111,88888888),(20,'21:13','2024-10-03','NBBBBBB','BBBBBBBB','BBBBBBBBBBBBBBBBBBBBBB',31111553,30128924),(21,'21:15','2024-10-05','BBBBBBBBBBB','BBBBBBBBB','BBBBBBB',31111553,8564289);
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
  `nombre_examen` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion_examen` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`cod_examenes`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examenes`
--

LOCK TABLES `examenes` WRITE;
/*!40000 ALTER TABLE `examenes` DISABLE KEYS */;
INSERT INTO `examenes` VALUES (1,'','');
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
  `general` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cedula_h` int NOT NULL,
  PRIMARY KEY (`cod_examen_f`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `examenes_f_ibfk_1` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examenes_f`
--

LOCK TABLES `examenes_f` WRITE;
/*!40000 ALTER TABLE `examenes_f` DISABLE KEYS */;
INSERT INTO `examenes_f` VALUES (1,'',30128924),(7,'',12345678),(8,'A',88888888),(9,'B',9999999),(10,NULL,8564289),(11,'',85642892),(12,'NORMAL',7856209),(13,'L',5465489);
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
  `cabeza_craneo` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ojos` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nariz` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `oidos` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `boca_cerrada` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `boca_abierta` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tiroides` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `extremidades` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cedula_h` int NOT NULL,
  PRIMARY KEY (`cod_examen_r`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `examenes_r_ibfk_1` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examenes_r`
--

LOCK TABLES `examenes_r` WRITE;
/*!40000 ALTER TABLE `examenes_r` DISABLE KEYS */;
INSERT INTO `examenes_r` VALUES (1,'','','','','','','','',30128924),(25,'','','','','','','','',12345678),(26,'A','A','A','A','A','A','A','A',88888888),(27,'B','B','B','B','B','B','B','B',9999999),(28,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8564289),(29,'','','','','','','','',85642892),(30,'COMUN','CAFES','NORMAL','NORMALES','NORMAL','NORMAL','NO POSEE','NORMAL',7856209),(31,'L','L','L','L','L','L','L','L',5465489);
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
  `respiratorio` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cardiovascular` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `abdomen` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `extremidades_s` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `neurologicos` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cedula_h` int NOT NULL,
  PRIMARY KEY (`cod_examen_s`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `examenes_s_ibfk_1` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examenes_s`
--

LOCK TABLES `examenes_s` WRITE;
/*!40000 ALTER TABLE `examenes_s` DISABLE KEYS */;
INSERT INTO `examenes_s` VALUES (1,'','','','undefined','',30128924),(9,'','','','undefined','',12345678),(10,'A','A','A','undefined','A',88888888),(11,'B','B','B','undefined','B',9999999),(12,NULL,NULL,NULL,NULL,NULL,8564289),(13,'','','','undefined','',85642892),(14,'NORMAL','SIN ANOMALIA','NORMAL','undefined','NORMAL',7856209),(15,'L','L','L','L','L',5465489);
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
  `nombre` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `apellido` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_nac` date DEFAULT NULL,
  `edad` int DEFAULT NULL,
  `estadocivil` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ocupacion` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hda` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alergias` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alergias_med` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quirurgico` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `transsanguineo` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `psicosocial` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `habtoxico` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`cedula_historia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historias`
--

LOCK TABLES `historias` WRITE;
/*!40000 ALTER TABLE `historias` DISABLE KEYS */;
INSERT INTO `historias` VALUES (5465489,'LUIS MIGUEL','GALLEGO BASTERI','1970-04-19',54,'CASADO','L','MEXICO','351351351','L','L','L','L','L','L','L'),(7856209,'JOE ALEX','CHACON VARGAS','1966-04-28',58,'DIVORCIADO','LOCUTOR','CERRITOS BLANCO','4125105446','NO POSEE','SOL','NO POSEE','NO POSEE','NO POSEE','NO POSEE','NO POSEE'),(8564289,'JURGEN','KLINSMANN','2024-10-01',21,'','PPPPP','asdadsasdads','351351351','PPPPP','P','P','P','P','P','P'),(9999999,'ANTHOAN','PATINNO','2004-04-30',258,'DIVORCIADO','B','BBBBBBBBBB','352131','B','B','B','B','B','B','B'),(12345678,'CRISTIANO ','RONALDO','2024-09-03',12,'','HOLA','HOLA','133513','HOLA','HOLA','HOLA','HOLA','HOLA','HOLA','HOLA'),(30128924,'SALOMON','RONDON','2024-09-02',21,'','asdas','asdsda','35135153','asddd','adsadsadsads','dsaaa','adsdasas','das','ads','dasasas'),(85642892,'SHOHEI','OHTANI','2024-10-01',21,'','undefined','asdadsasdads','351351351','undefined','undefined','undefined','undefined','undefined','undefined','undefined'),(88888888,'JUAN','CHACON','2024-09-03',21,'CASADO','A','AAAAAAAAA','35135131','A','A','A','A','A','A','A');
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
INSERT INTO `personal` VALUES (31111111,'anthoan','gonzalez','nose','3111','doctor'),(31111553,'franchesco2','gonzalez','nose','3111','enfermero');
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
  `observacion_examen` varchar(100) COLLATE utf8mb4_general_ci GENERATED ALWAYS AS (NULL) VIRTUAL,
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

-- Dump completed on 2024-11-03 10:38:57
