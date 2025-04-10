CREATE DATABASE  IF NOT EXISTS `shm-cdi.2` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `shm-cdi.2`;
-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: shm-cdi.2
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
-- Table structure for table `areas_pasantia`
--

DROP TABLE IF EXISTS `areas_pasantia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `areas_pasantia` (
  `cod_area` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_area` varchar(50) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `responsable_id` int(11) NOT NULL,
  PRIMARY KEY (`cod_area`),
  KEY `responsable_id` (`responsable_id`),
  CONSTRAINT `areas_pasantia_ibfk_1` FOREIGN KEY (`responsable_id`) REFERENCES `personal` (`cedula_personal`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `areas_pasantia`
--

LOCK TABLES `areas_pasantia` WRITE;
/*!40000 ALTER TABLE `areas_pasantia` DISABLE KEYS */;
INSERT INTO `areas_pasantia` VALUES (1,'Emergencias','aqui se atienden todos los pacientes que necesitan atencion inmediatas',31111111);
/*!40000 ALTER TABLE `areas_pasantia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consultas`
--

DROP TABLE IF EXISTS `consultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consultas` (
  `cod_consulta` int(11) NOT NULL AUTO_INCREMENT,
  `fechaconsulta` date DEFAULT NULL,
  `consulta` varchar(100) DEFAULT NULL,
  `diagnostico` varchar(100) DEFAULT NULL,
  `tratamientos` varchar(100) DEFAULT NULL,
  `cedula_p` int(11) NOT NULL,
  `cedula_h` int(11) NOT NULL,
  `general` varchar(300) DEFAULT NULL,
  `respiratorio` varchar(300) DEFAULT NULL,
  `cardiovascular` varchar(300) DEFAULT NULL,
  `abdomen` varchar(300) DEFAULT NULL,
  `extremidades_s` varchar(300) DEFAULT NULL,
  `neurologicos` varchar(300) DEFAULT NULL,
  `cabeza_craneo` varchar(300) DEFAULT NULL,
  `ojos` varchar(300) DEFAULT NULL,
  `nariz` varchar(300) DEFAULT NULL,
  `oidos` varchar(300) DEFAULT NULL,
  `boca_abierta` varchar(300) DEFAULT NULL,
  `boca_cerrada` varchar(300) DEFAULT NULL,
  `extremidades_r` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`cod_consulta`),
  KEY `cedula_p` (`cedula_p`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `consultas_ibfk_1` FOREIGN KEY (`cedula_p`) REFERENCES `personal` (`cedula_personal`),
  CONSTRAINT `consultas_ibfk_2` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultas`
--

LOCK TABLES `consultas` WRITE;
/*!40000 ALTER TABLE `consultas` DISABLE KEYS */;
INSERT INTO `consultas` VALUES (4,'2024-11-28','AAAA','AAAA','AAAA',31111111,12345678,'AAA','AAA','AAA','AAA','AAA','AAA','AAA','AAA','AAA','AAA','AAA','AAA','AAA');
/*!40000 ALTER TABLE `consultas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emergencias`
--

DROP TABLE IF EXISTS `emergencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `emergencias` (
  `cod_emergencia` int(11) NOT NULL AUTO_INCREMENT,
  `horaingreso` varchar(7) DEFAULT NULL,
  `fechaingreso` date DEFAULT NULL,
  `motingreso` varchar(3000) DEFAULT NULL,
  `diagnostico_e` varchar(3000) DEFAULT NULL,
  `tratamientos` varchar(3000) DEFAULT NULL,
  `cedula_p` int(11) NOT NULL,
  `cedula_h` int(11) NOT NULL,
  PRIMARY KEY (`cod_emergencia`),
  KEY `cedula_p` (`cedula_p`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `emergencias_ibfk_1` FOREIGN KEY (`cedula_p`) REFERENCES `personal` (`cedula_personal`),
  CONSTRAINT `emergencias_ibfk_2` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emergencias`
--

LOCK TABLES `emergencias` WRITE;
/*!40000 ALTER TABLE `emergencias` DISABLE KEYS */;
INSERT INTO `emergencias` VALUES (31,'22:28','2024-11-27','PIERNA ROTA','FRACTURA','ACETAMINOFEN',31111111,30128924);
/*!40000 ALTER TABLE `emergencias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estudiantes_pasantia`
--

DROP TABLE IF EXISTS `estudiantes_pasantia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estudiantes_pasantia` (
  `cedula_estudiante` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `institucion` varchar(50) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `cod_area` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`cedula_estudiante`),
  KEY `cod_area` (`cod_area`),
  CONSTRAINT `estudiantes_pasantia_ibfk_1` FOREIGN KEY (`cod_area`) REFERENCES `areas_pasantia` (`cod_area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiantes_pasantia`
--

LOCK TABLES `estudiantes_pasantia` WRITE;
/*!40000 ALTER TABLE `estudiantes_pasantia` DISABLE KEYS */;
INSERT INTO `estudiantes_pasantia` VALUES (11696101,'Eddie','Meneses','UPTAEB','04145154142',1,'2025-04-02','2025-04-23',0);
/*!40000 ALTER TABLE `estudiantes_pasantia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `examenes`
--

DROP TABLE IF EXISTS `examenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `examenes` (
  `cod_examenes` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_examen` varchar(20) NOT NULL,
  `descripcion_examen` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`cod_examenes`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examenes`
--

LOCK TABLES `examenes` WRITE;
/*!40000 ALTER TABLE `examenes` DISABLE KEYS */;
INSERT INTO `examenes` VALUES (1,'orina','provee datos con la orina del paciente'),(4,'heces','examina las heces del paciente'),(7,'sangre','sangre'),(13,'resonancia','resonancia'),(14,'placa','foto del los huesos');
/*!40000 ALTER TABLE `examenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historias`
--

DROP TABLE IF EXISTS `historias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historias` (
  `cedula_historia` int(11) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `apellido` varchar(15) NOT NULL,
  `fecha_nac` date DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `estadocivil` varchar(15) DEFAULT NULL,
  `ocupacion` varchar(15) DEFAULT NULL,
  `direccion` varchar(300) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `hda` varchar(300) DEFAULT NULL,
  `alergias` varchar(300) DEFAULT NULL,
  `alergias_med` varchar(300) DEFAULT NULL,
  `quirurgico` varchar(300) DEFAULT NULL,
  `transsanguineo` varchar(300) DEFAULT NULL,
  `psicosocial` varchar(300) DEFAULT NULL,
  `habtoxico` varchar(300) DEFAULT NULL,
  `antc_padre` varchar(300) DEFAULT NULL,
  `antc_hermano` varchar(300) DEFAULT NULL,
  `antc_madre` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`cedula_historia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historias`
--

LOCK TABLES `historias` WRITE;
/*!40000 ALTER TABLE `historias` DISABLE KEYS */;
INSERT INTO `historias` VALUES (5465489,'LUIS MIGUEL','GALLEGO BASTERI','1970-04-19',54,'DIVORCIADO','L','MEXICO','351351351','L','L','L','L','L','L','L',NULL,NULL,NULL),(12345678,'CRISTIANO ','RONALDO','2024-09-03',12,'CASADO','HOLA','HOLA','133513','HOLA','HOLA','HOLA','HOLA','HOLA','HOLA','HOLA',NULL,NULL,NULL),(30128924,'SALOMON','RONDON','2024-09-02',21,'SOLTERO','asdas','asdsda','35135153','asddd','adsadsadsads','dsaaa','adsdasas','das','ads','dasasas',NULL,NULL,NULL);
/*!40000 ALTER TABLE `historias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jornadas_medicas`
--

DROP TABLE IF EXISTS `jornadas_medicas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jornadas_medicas` (
  `cod_jornada` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_jornada` date NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `total_pacientes` int(11) NOT NULL DEFAULT 0,
  `pacientes_masculinos` int(11) NOT NULL DEFAULT 0,
  `pacientes_femeninos` int(11) NOT NULL DEFAULT 0,
  `pacientes_embarazadas` int(11) NOT NULL DEFAULT 0,
  `cedula_responsable` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cod_jornada`),
  KEY `cedula_responsable` (`cedula_responsable`),
  CONSTRAINT `jornadas_medicas_ibfk_1` FOREIGN KEY (`cedula_responsable`) REFERENCES `personal` (`cedula_personal`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jornadas_medicas`
--

LOCK TABLES `jornadas_medicas` WRITE;
/*!40000 ALTER TABLE `jornadas_medicas` DISABLE KEYS */;
INSERT INTO `jornadas_medicas` VALUES (1,'2025-04-11','carora','jornada medica en liceo',10,3,7,3,12345678,'2025-04-07 01:38:39');
/*!40000 ALTER TABLE `jornadas_medicas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medicamentos_jornadas`
--

DROP TABLE IF EXISTS `medicamentos_jornadas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medicamentos_jornadas` (
  `cod_medicamento_jornada` int(11) NOT NULL AUTO_INCREMENT,
  `cod_jornada` int(11) NOT NULL,
  `nombre_medicamento` varchar(100) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `unidad_medida` varchar(20) NOT NULL,
  `lote` varchar(50) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  PRIMARY KEY (`cod_medicamento_jornada`),
  KEY `cod_jornada` (`cod_jornada`),
  CONSTRAINT `medicamentos_jornadas_ibfk_1` FOREIGN KEY (`cod_jornada`) REFERENCES `jornadas_medicas` (`cod_jornada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medicamentos_jornadas`
--

LOCK TABLES `medicamentos_jornadas` WRITE;
/*!40000 ALTER TABLE `medicamentos_jornadas` DISABLE KEYS */;
/*!40000 ALTER TABLE `medicamentos_jornadas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p_cronicos`
--

DROP TABLE IF EXISTS `p_cronicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `p_cronicos` (
  `cod_cronico` int(11) NOT NULL AUTO_INCREMENT,
  `patologia_cronica` varchar(30) NOT NULL,
  `Tratamiento` varchar(300) DEFAULT NULL,
  `admistracion_t` varchar(300) DEFAULT NULL,
  `cedula_h` int(11) DEFAULT NULL,
  PRIMARY KEY (`cod_cronico`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `p_cronicos_ibfk_1` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p_cronicos`
--

LOCK TABLES `p_cronicos` WRITE;
/*!40000 ALTER TABLE `p_cronicos` DISABLE KEYS */;
INSERT INTO `p_cronicos` VALUES (6,'VIH','MEDICAMENTOS PARA AUMENTAR LAS DEFENSAS','DINOSAURIOS',12345678);
/*!40000 ALTER TABLE `p_cronicos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participantes_jornadas`
--

DROP TABLE IF EXISTS `participantes_jornadas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `participantes_jornadas` (
  `cod_participacion` int(11) NOT NULL AUTO_INCREMENT,
  `cod_jornada` int(11) NOT NULL,
  `cedula_personal` int(11) NOT NULL,
  PRIMARY KEY (`cod_participacion`),
  UNIQUE KEY `cod_jornada` (`cod_jornada`,`cedula_personal`),
  KEY `cedula_personal` (`cedula_personal`),
  CONSTRAINT `participantes_jornadas_ibfk_1` FOREIGN KEY (`cod_jornada`) REFERENCES `jornadas_medicas` (`cod_jornada`),
  CONSTRAINT `participantes_jornadas_ibfk_2` FOREIGN KEY (`cedula_personal`) REFERENCES `personal` (`cedula_personal`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participantes_jornadas`
--

LOCK TABLES `participantes_jornadas` WRITE;
/*!40000 ALTER TABLE `participantes_jornadas` DISABLE KEYS */;
INSERT INTO `participantes_jornadas` VALUES (1,1,12345678),(2,1,31111111);
/*!40000 ALTER TABLE `participantes_jornadas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal`
--

DROP TABLE IF EXISTS `personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal` (
  `cedula_personal` int(11) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `apellido` varchar(15) NOT NULL,
  `correo` varchar(30) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `cargo` varchar(15) DEFAULT NULL,
  `clave` varchar(20) NOT NULL,
  PRIMARY KEY (`cedula_personal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal`
--

LOCK TABLES `personal` WRITE;
/*!40000 ALTER TABLE `personal` DISABLE KEYS */;
INSERT INTO `personal` VALUES (12345678,'admin','admin','admin@gmail.com','12345678910','Doctor','Dino1234'),(31111111,'anthoan','gonzalez','anthoangonzalez@gmail.com','0412785948','Doctor','Dinosaurio'),(31111553,'franchesco','gonzalez','franchescovernouli@gmail.com','04241875864','Enfermera','Dinosaurio');
/*!40000 ALTER TABLE `personal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registro`
--

DROP TABLE IF EXISTS `registro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registro` (
  `cod_registro` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_r` date DEFAULT NULL,
  `cedula_h` int(11) NOT NULL,
  `cod_examenes` int(11) NOT NULL,
  `observacion_examen` varchar(100) DEFAULT NULL,
  `ruta_imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cod_registro`),
  KEY `cedula_h` (`cedula_h`),
  KEY `cod_examenes` (`cod_examenes`),
  CONSTRAINT `registro_ibfk_1` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`),
  CONSTRAINT `registro_ibfk_2` FOREIGN KEY (`cod_examenes`) REFERENCES `examenes` (`cod_examenes`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registro`
--

LOCK TABLES `registro` WRITE;
/*!40000 ALTER TABLE `registro` DISABLE KEYS */;
INSERT INTO `registro` VALUES (25,'2024-11-28',5465489,1,'NINGUNA',NULL);
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

-- Dump completed on 2025-04-09 22:12:26
