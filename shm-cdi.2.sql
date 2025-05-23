-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: shm-cdi.2
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
-- Table structure for table `areas_pasantia`
--

DROP TABLE IF EXISTS `areas_pasantia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `areas_pasantia` (
  `cod_area` int NOT NULL AUTO_INCREMENT,
  `nombre_area` varchar(50) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `responsable_id` int NOT NULL COMMENT 'Doctor a cargo',
  PRIMARY KEY (`cod_area`),
  KEY `responsable_id` (`responsable_id`),
  CONSTRAINT `areas_pasantia_ibfk_1` FOREIGN KEY (`responsable_id`) REFERENCES `personal` (`cedula_personal`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `areas_pasantia`
--

LOCK TABLES `areas_pasantia` WRITE;
/*!40000 ALTER TABLE `areas_pasantia` DISABLE KEYS */;
INSERT INTO `areas_pasantia` VALUES (1,'Fisioterapia','Area de Fisioterapia',30128924),(2,'Pediatria','Area de Pediatria',23045014);
/*!40000 ALTER TABLE `areas_pasantia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asistencia`
--

DROP TABLE IF EXISTS `asistencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asistencia` (
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `activo` varchar(30) DEFAULT NULL,
  `cedula_estudiante` int NOT NULL,
  `cod_area` int NOT NULL,
  PRIMARY KEY (`cedula_estudiante`,`cod_area`),
  KEY `cod_area` (`cod_area`),
  CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`cedula_estudiante`) REFERENCES `estudiantes_pasantia` (`cedula_estudiante`),
  CONSTRAINT `asistencia_ibfk_2` FOREIGN KEY (`cod_area`) REFERENCES `areas_pasantia` (`cod_area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asistencia`
--

LOCK TABLES `asistencia` WRITE;
/*!40000 ALTER TABLE `asistencia` DISABLE KEYS */;
/*!40000 ALTER TABLE `asistencia` ENABLE KEYS */;
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
  `general` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `respiratorio` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cardiovascular` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `abdomen` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `extremidades_s` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `neurologicos` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cabeza_craneo` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ojos` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nariz` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `oidos` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `boca_abierta` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `boca_cerrada` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `extremidades_r` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`cod_consulta`),
  KEY `cedula_p` (`cedula_p`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `consultas_ibfk_1` FOREIGN KEY (`cedula_p`) REFERENCES `personal` (`cedula_personal`),
  CONSTRAINT `consultas_ibfk_2` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultas`
--

LOCK TABLES `consultas` WRITE;
/*!40000 ALTER TABLE `consultas` DISABLE KEYS */;
INSERT INTO `consultas` VALUES (9,'2024-11-18','WWWss','WWW','WWW',31111553,12345678,'aaaaaaaaaa','aaaaaaaaaaaaa','aaaaaaa','aaaaaaaaaaaa','aaaaaaaa','aaaaaaa','aaaaaaaaaa','aaaa','sss','aaaaaaaa','aaaaaaa','aaaaaaaaa','aaaaaaa'),(14,'2024-11-19','AAA','AAA','AAA',31111553,9999999,'','','','','','','','','','','','',''),(16,'2024-11-19','ttttttttttttttt','tttttttttttttttttt','tttttttttttttttttt',12345678,9999999,'ttttttttttttttttt','ttttttttttttttttt','ttttttttttttttt','tttttttttttttt','tttttttttttttttttt','ttttttttttttttttttt','ttttttttttttttttttttt','tttttttttttttt','ttttttttttttt','ttttttttttttt','ttttttttttttttt','ttttttttttttt','ttttttttttttttttttttt'),(17,'2024-11-03','hola','hola','hola',12345678,9999999,'hola','hola','hola','hola','hola','hola','hola','hola','hola','hola','hola','hola','hola');
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
  `motingreso` varchar(3000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `diagnostico_e` varchar(3000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tratamientos` varchar(3000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `cedula_p` int NOT NULL,
  `cedula_h` int NOT NULL,
  PRIMARY KEY (`cod_emergencia`),
  KEY `cedula_p` (`cedula_p`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `emergencias_ibfk_1` FOREIGN KEY (`cedula_p`) REFERENCES `personal` (`cedula_personal`),
  CONSTRAINT `emergencias_ibfk_2` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emergencias`
--

LOCK TABLES `emergencias` WRITE;
/*!40000 ALTER TABLE `emergencias` DISABLE KEYS */;
INSERT INTO `emergencias` VALUES (22,'02:49','2024-11-12','llego con un dolor de cabesa','aaaaaaaaaaaa','aaaaaaaaaaaaaaa',31111553,7856209),(24,'05:51','2024-11-12','Como modelo de lenguaje, no puedo proporcionarte un diagnóstico médico.\r\n\r\nDiagnosticar una enfermedad requiere de un profesional de la salud capacitado, quien evaluará tus síntomas, realizará un examen físico y, si es necesario, ordenará pruebas de laboratorio o de imagen.\r\n\r\n¿Por qué no puedo darte un diagnóstico?\r\n\r\nInformación limitada: A través de una conversación en línea, no tengo acceso a tu historial médico completo, resultados de exámenes o a una exploración física.\r\nComplejidad de las enfermedades: Muchas enfermedades comparten síntomas similares, lo que hace que un diagnóstico preciso requiera una evaluación exhaustiva por parte de un médico.\r\nResponsabilidad profesional: Proporcionar un diagnóstico médico es una responsabilidad seria que requiere de conocimientos y habilidades específicas.\r\n¿Qué puedo hacer si estoy enfermo?\r\n\r\nSi tienes alguna preocupación sobre tu salud, te recomiendo:\r\n\r\nConsulta a un médico: Es la persona más capacitada para evaluar tus síntomas y realizar un diagnóstico preciso.\r\nDescribe tus síntomas con claridad: Incluye cuándo comenzaron, con qué frecuencia ocurren y qué los empeora o mejora.\r\nSé honesto sobre tu historial médico: Informa a tu médico sobre cualquier enfermedad que hayas tenido, medicamentos que estés tomando y alergias.\r\nRecuerda:\r\n\r\nNo te automediques: Tomar medicamentos sin receta médica puede empeorar tu condición o interactuar negativamente con otros medicamentos.\r\nBusca información confiable: Si quieres investigar sobre alguna enfermedad, consulta fuentes confiables como la Organización Mundial de la Salud (OMS) o sitios web de instituciones médicas reconocidas.\r\n¿Te gustaría que te proporcione información sobre alguna enfermedad en particular?\r\n\r\nPuedo ofrecerte información general sobre diversas enfermedades, sus síntomas, causas y tratamientos. Sin embargo, esta información no debe reemplazar el consejo médico profesional.\r\n\r\nPor favor, ten en cuenta que mi función es proporcionar información y no reemplazar la atención médica.','aaaaaaaaaaaaa','aaaaaaaaaaaaaaaaaa',31111553,12345678),(25,'05:51','2024-11-12','Como modelo de lenguaje, no puedo proporcionarte un diagnóstico médico.\r\n\r\nDiagnosticar una enfermedad requiere de un profesional de la salud capacitado, quien evaluará tus síntomas, realizará un examen físico y, si es necesario, ordenará pruebas de laboratorio o de imagen.\r\n\r\n¿Por qué no puedo darte un diagnóstico?\r\n\r\nInformación limitada: A través de una conversación en línea, no tengo acceso a tu historial médico completo, resultados de exámenes o a una exploración física.\r\nComplejidad de las enfermedades: Muchas enfermedades comparten síntomas similares, lo que hace que un diagnóstico preciso requiera una evaluación exhaustiva por parte de un médico.\r\nResponsabilidad profesional: Proporcionar un diagnóstico médico es una responsabilidad seria que requiere de conocimientos y habilidades específicas.\r\n¿Qué puedo hacer si estoy enfermo?\r\n\r\nSi tienes alguna preocupación sobre tu salud, te recomiendo:\r\n\r\nConsulta a un médico: Es la persona más capacitada para evaluar tus síntomas y realizar un diagnóstico preciso.\r\nDescribe tus síntomas con claridad: Incluye cuándo comenzaron, con qué frecuencia ocurren y qué los empeora o mejora.\r\nSé honesto sobre tu historial médico: Informa a tu médico sobre cualquier enfermedad que hayas tenido, medicamentos que estés tomando y alergias.\r\nRecuerda:\r\n\r\nNo te automediques: Tomar medicamentos sin receta médica puede empeorar tu condición o interactuar negativamente con otros medicamentos.\r\nBusca información confiable: Si quieres investigar sobre alguna enfermedad, consulta fuentes confiables como la Organización Mundial de la Salud (OMS) o sitios web de instituciones médicas reconocidas.\r\n¿Te gustaría que te proporcione información sobre alguna enfermedad en particular?\r\n\r\nPuedo ofrecerte información general sobre diversas enfermedades, sus síntomas, causas y tratamientos. Sin embargo, esta información no debe reemplazar el consejo médico profesional.\r\n\r\nPor favor, ten en cuenta que mi función es proporcionar información y no reemplazar la atención médica.','Como modelo de lenguaje, no puedo proporcionarte un diagnóstico médico.\r\n\r\nDiagnosticar una enfermedad requiere de un profesional de la salud capacitado, quien evaluará tus síntomas, realizará un examen físico y, si es necesario, ordenará pruebas de laboratorio o de imagen.\r\n\r\n¿Por qué no puedo darte un diagnóstico?\r\n\r\nInformación limitada: A través de una conversación en línea, no tengo acceso a tu historial médico completo, resultados de exámenes o a una exploración física.\r\nComplejidad de las enfermedades: Muchas enfermedades comparten síntomas similares, lo que hace que un diagnóstico preciso requiera una evaluación exhaustiva por parte de un médico.\r\nResponsabilidad profesional: Proporcionar un diagnóstico médico es una responsabilidad seria que requiere de conocimientos y habilidades específicas.\r\n¿Qué puedo hacer si estoy enfermo?\r\n\r\nSi tienes alguna preocupación sobre tu salud, te recomiendo:\r\n\r\nConsulta a un médico: Es la persona más capacitada para evaluar tus síntomas y realizar un diagnóstico preciso.\r\nDescribe tus síntomas con claridad: Incluye cuándo comenzaron, con qué frecuencia ocurren y qué los empeora o mejora.\r\nSé honesto sobre tu historial médico: Informa a tu médico sobre cualquier enfermedad que hayas tenido, medicamentos que estés tomando y alergias.\r\nRecuerda:\r\n\r\nNo te automediques: Tomar medicamentos sin receta médica puede empeorar tu condición o interactuar negativamente con otros medicamentos.\r\nBusca información confiable: Si quieres investigar sobre alguna enfermedad, consulta fuentes confiables como la Organización Mundial de la Salud (OMS) o sitios web de instituciones médicas reconocidas.\r\n¿Te gustaría que te proporcione información sobre alguna enfermedad en particular?\r\n\r\nPuedo ofrecerte información general sobre diversas enfermedades, sus síntomas, causas y tratamientos. Sin embargo, esta información no debe reemplazar el consejo médico profesional.\r\n\r\nPor favor, ten en cuenta que mi función es proporcionar información y no reemplazar la atención médica.','Como modelo de lenguaje, no puedo proporcionarte un diagnóstico médico.\r\n\r\nDiagnosticar una enfermedad requiere de un profesional de la salud capacitado, quien evaluará tus síntomas, realizará un examen físico y, si es necesario, ordenará pruebas de laboratorio o de imagen.\r\n\r\n¿Por qué no puedo darte un diagnóstico?\r\n\r\nInformación limitada: A través de una conversación en línea, no tengo acceso a tu historial médico completo, resultados de exámenes o a una exploración física.\r\nComplejidad de las enfermedades: Muchas enfermedades comparten síntomas similares, lo que hace que un diagnóstico preciso requiera una evaluación exhaustiva por parte de un médico.\r\nResponsabilidad profesional: Proporcionar un diagnóstico médico es una responsabilidad seria que requiere de conocimientos y habilidades específicas.\r\n¿Qué puedo hacer si estoy enfermo?\r\n\r\nSi tienes alguna preocupación sobre tu salud, te recomiendo:\r\n\r\nConsulta a un médico: Es la persona más capacitada para evaluar tus síntomas y realizar un diagnóstico preciso.\r\nDescribe tus síntomas con claridad: Incluye cuándo comenzaron, con qué frecuencia ocurren y qué los empeora o mejora.\r\nSé honesto sobre tu historial médico: Informa a tu médico sobre cualquier enfermedad que hayas tenido, medicamentos que estés tomando y alergias.\r\nRecuerda:\r\n\r\nNo te automediques: Tomar medicamentos sin receta médica puede empeorar tu condición o interactuar negativamente con otros medicamentos.\r\nBusca información confiable: Si quieres investigar sobre alguna enfermedad, consulta fuentes confiables como la Organización Mundial de la Salud (OMS) o sitios web de instituciones médicas reconocidas.\r\n¿Te gustaría que te proporcione información sobre alguna enfermedad en particular?\r\n\r\nPuedo ofrecerte información general sobre diversas enfermedades, sus síntomas, causas y tratamientos. Sin embargo, esta información no debe reemplazar el consejo médico profesional.\r\n\r\nPor favor, ten en cuenta que mi función es proporcionar información y no reemplazar la atención médica.',31111553,5465489),(28,'19:02','2024-11-12','L','L','L',31111553,88888888),(33,'05:03','2025-03-26','LLLL','LKLLLL','LLLLLL',31111111,88888888),(35,'02:24','2025-03-27','wwwwwwwwwwwwwwwwwwww','wwwwwwwwwwwwwwwwwwwwwwww','wwwwwwwwwwwwwwwwwww',31111553,30128924),(36,'00:12','2025-03-27','ttttttttttttttttttttttt','ttttttttttttttttttttttt','tttttttttttttttttttttttttttttttttt',31111553,5465489),(37,'11:20','2025-03-27','lllllllllllllllllll','lllllllllllllllllllllllllll','llllllllllllllllllllllllllllllllllll',31111553,5465489),(38,'02:49','2025-04-07','holaaaaaaa','llllllllllll','lllllllllllllll',31111553,7856209),(39,'10:15','2025-04-16','LLLLLLLLLLLLLLL','LLlllllllllllllllllllll','llllllllllllllllllllllll',31111553,5465489),(40,'11:18','2025-04-15','llllllllllll','llllllllllllllll','lllllllllllllllllllllll',31111553,4234235),(41,'11:17','2025-04-16','lllllllllllll','llllllllllllllllll','llllllllllllllllllllll',31111111,5465489),(42,'20:17','2025-04-11','lllllllllllllllll','llllllllllllllllllll','lllllllllllllllllllllll',31111553,5465489),(43,'11:17','2025-04-13','lllllllllllllllll','lllllllllllllllllll','lllllllllllll',31111553,7856209);
/*!40000 ALTER TABLE `emergencias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estudiantes_pasantia`
--

DROP TABLE IF EXISTS `estudiantes_pasantia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estudiantes_pasantia` (
  `cedula_estudiante` int NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `institucion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`cedula_estudiante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiantes_pasantia`
--

LOCK TABLES `estudiantes_pasantia` WRITE;
/*!40000 ALTER TABLE `estudiantes_pasantia` DISABLE KEYS */;
/*!40000 ALTER TABLE `estudiantes_pasantia` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examenes`
--

LOCK TABLES `examenes` WRITE;
/*!40000 ALTER TABLE `examenes` DISABLE KEYS */;
INSERT INTO `examenes` VALUES (1,'orina','provee datos con la orina del paciente'),(4,'heces','examina las heces del paciente'),(7,'sangre','sangre'),(13,'resonancia','resonancia'),(14,'placa','foto del los huesos'),(15,'Examen de Orina','Examen de Orina');
/*!40000 ALTER TABLE `examenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feed`
--

DROP TABLE IF EXISTS `feed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feed` (
  `cod_pub` int NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `contenido` varchar(300) DEFAULT NULL,
  `imagen` text,
  `id_usuario` int NOT NULL,
  PRIMARY KEY (`cod_pub`),
  KEY `feed_ibfk_idx` (`id_usuario`),
  CONSTRAINT `feed_ibfk` FOREIGN KEY (`id_usuario`) REFERENCES `personal` (`cedula_personal`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feed`
--

LOCK TABLES `feed` WRITE;
/*!40000 ALTER TABLE `feed` DISABLE KEYS */;
INSERT INTO `feed` VALUES (2,'2025-05-12 06:08:23','ATENCIÓN, Doctores atentos con la jornadas de salud que se realizará en la Av. Venezuela con Av. Bracamonte este Miercoles 12 de Mayo','img/publicaciones/682695e1be1cb_istockphoto-949812160-612x612.jpg',30128924),(3,'2025-05-20 18:59:46','ASSSAASAS','img/publicaciones/682cb5026b880_image2.png',30128924);
/*!40000 ALTER TABLE `feed` ENABLE KEYS */;
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
  `antc_padre` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `antc_hermano` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `antc_madre` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`cedula_historia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historias`
--

LOCK TABLES `historias` WRITE;
/*!40000 ALTER TABLE `historias` DISABLE KEYS */;
INSERT INTO `historias` VALUES (4234235,'LUIS','GONZALEZ','1995-04-02',30,'CASADO','CARPINTERO','BARQUISIMETO','0412000000','NO POSEE','NO POSEE','NO POSEE','NO POSEE','NO POSEE','NO POSEE','NO POSEE','NO POSEE','NO POSEE','NO POSEE'),(5465489,'LUIS MIGUEL','GALLEGO BASTERI','1970-04-19',54,'DIVORCIADO','L','MEXICO','351351351','L','L','L','L','L','L','L',NULL,NULL,NULL),(7856209,'JOE ALEX','CHACON VARGAS','1966-04-28',58,'DIVORCIADO','LOCUTOR','CERRITOS BLANCO','4125105446','NO POSEE','SOL','NO POSEE','NO POSEE','NO POSEE','NO POSEE','NO POSEE',NULL,NULL,NULL),(8564289,'JURGEN','KLINSMANN','2024-10-01',21,'','PPPPP','asdadsasdads','351351351','PPPPP','P','P','P','P','P','P',NULL,NULL,NULL),(9999999,'ANTHONY JOSE','GONZALEZ COLINA','2004-04-30',258,'DIVORCIADO','B','BBBBBBBBBB','352131','B','B','B','B','B','B','B','null','null','null'),(12345678,'CRISTIANO ','RONALDO','2024-09-03',12,'','HOLA','HOLA','133513','HOLA','HOLA','HOLA','HOLA','HOLA','HOLA','HOLA',NULL,NULL,NULL),(30128924,'SALOMON','RONDON','2024-09-02',21,'','asdas','asdsda','35135153','asddd','adsadsadsads','dsaaa','adsdasas','das','ads','dasasas',NULL,NULL,NULL),(85642892,'SHOHEI','OHTANI','2024-10-01',21,'','undefined','asdadsasdads','351351351','undefined','undefined','undefined','undefined','undefined','undefined','undefined',NULL,NULL,NULL),(88888888,'JUAN','CHACON','2024-09-03',14,'','A','AAAAAAAAA','35135131','A','A','A','A','A','A','A','null','null','null');
/*!40000 ALTER TABLE `historias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `insumos`
--

DROP TABLE IF EXISTS `insumos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `insumos` (
  `cod_transaccion` int NOT NULL,
  `cod_medicamento` int NOT NULL,
  `cantidad` int NOT NULL,
  PRIMARY KEY (`cod_transaccion`,`cod_medicamento`),
  KEY `cod_medicamento` (`cod_medicamento`),
  CONSTRAINT `insumos_ibfk_1` FOREIGN KEY (`cod_transaccion`) REFERENCES `transaccion` (`cod_transaccion`),
  CONSTRAINT `insumos_ibfk_2` FOREIGN KEY (`cod_medicamento`) REFERENCES `medicamentos` (`cod_medicamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insumos`
--

LOCK TABLES `insumos` WRITE;
/*!40000 ALTER TABLE `insumos` DISABLE KEYS */;
INSERT INTO `insumos` VALUES (38,9,3),(46,10,7),(47,9,20);
/*!40000 ALTER TABLE `insumos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jornadas_medicas`
--

DROP TABLE IF EXISTS `jornadas_medicas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jornadas_medicas` (
  `cod_jornada` int NOT NULL AUTO_INCREMENT,
  `fecha_jornada` date NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `descripcion` text,
  `total_pacientes` int NOT NULL DEFAULT '0',
  `pacientes_masculinos` int NOT NULL DEFAULT '0',
  `pacientes_femeninos` int NOT NULL DEFAULT '0',
  `pacientes_embarazadas` int NOT NULL DEFAULT '0',
  `cedula_responsable` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cod_jornada`),
  KEY `cedula_responsable` (`cedula_responsable`),
  CONSTRAINT `jornadas_medicas_ibfk_1` FOREIGN KEY (`cedula_responsable`) REFERENCES `personal` (`cedula_personal`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jornadas_medicas`
--

LOCK TABLES `jornadas_medicas` WRITE;
/*!40000 ALTER TABLE `jornadas_medicas` DISABLE KEYS */;
INSERT INTO `jornadas_medicas` VALUES (3,'2025-05-12','AV. VENEZUELA CON AV. BRACAMONTE','JORNADA DE VACUNACIÓN',10,7,3,2,31111111,'2025-04-14 20:43:40');
/*!40000 ALTER TABLE `jornadas_medicas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medicamentos`
--

DROP TABLE IF EXISTS `medicamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medicamentos` (
  `cod_medicamento` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `cantidad` int NOT NULL DEFAULT '0',
  `unidad_medida` varchar(20) NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `lote` varchar(30) DEFAULT NULL,
  `proveedor` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`cod_medicamento`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medicamentos`
--

LOCK TABLES `medicamentos` WRITE;
/*!40000 ALTER TABLE `medicamentos` DISABLE KEYS */;
INSERT INTO `medicamentos` VALUES (9,'LORATADINA','es un fármaco antihistamínico',52,'mg','2028-04-30','23132231','FARMATODO'),(10,'ACETAMINOFEN','fármaco con propiedades analgésicas',25,'unidades','2026-08-25','213312','EPITRAL');
/*!40000 ALTER TABLE `medicamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p_cronicos`
--

DROP TABLE IF EXISTS `p_cronicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `p_cronicos` (
  `cod_cronico` int NOT NULL AUTO_INCREMENT,
  `patologia_cronica` varchar(30) NOT NULL,
  `Tratamiento` varchar(300) DEFAULT NULL,
  `admistracion_t` varchar(300) DEFAULT NULL,
  `cedula_h` int DEFAULT NULL,
  PRIMARY KEY (`cod_cronico`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `p_cronicos_ibfk_1` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p_cronicos`
--

LOCK TABLES `p_cronicos` WRITE;
/*!40000 ALTER TABLE `p_cronicos` DISABLE KEYS */;
INSERT INTO `p_cronicos` VALUES (5,'Cardiopatía, Asmático','xxxxxxxxx','xxxxxxxxxx',85642892),(7,'Asmático, Renal','LLLL','LLLL',9999999);
/*!40000 ALTER TABLE `p_cronicos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participantes_jornadas`
--

DROP TABLE IF EXISTS `participantes_jornadas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `participantes_jornadas` (
  `cod_participacion` int NOT NULL AUTO_INCREMENT,
  `cod_jornada` int NOT NULL,
  `cedula_personal` int NOT NULL,
  `tipo_participante` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`cod_participacion`),
  UNIQUE KEY `cod_jornada` (`cod_jornada`,`cedula_personal`),
  KEY `cedula_personal` (`cedula_personal`),
  CONSTRAINT `participantes_jornadas_ibfk_1` FOREIGN KEY (`cod_jornada`) REFERENCES `jornadas_medicas` (`cod_jornada`),
  CONSTRAINT `participantes_jornadas_ibfk_2` FOREIGN KEY (`cedula_personal`) REFERENCES `personal` (`cedula_personal`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participantes_jornadas`
--

LOCK TABLES `participantes_jornadas` WRITE;
/*!40000 ALTER TABLE `participantes_jornadas` DISABLE KEYS */;
INSERT INTO `participantes_jornadas` VALUES (12,3,23045014,NULL),(13,3,31111111,NULL);
/*!40000 ALTER TABLE `participantes_jornadas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal`
--

DROP TABLE IF EXISTS `personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal` (
  `cedula_personal` int NOT NULL,
  `nombre` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `apellido` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `correo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cargo` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `clave` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`cedula_personal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal`
--

LOCK TABLES `personal` WRITE;
/*!40000 ALTER TABLE `personal` DISABLE KEYS */;
INSERT INTO `personal` VALUES (12345678,'admin','admin','admin@gmail.com','12345678910','Doctor','12345678'),(23045014,'Esteban','Salazar','correo@gmail.com','04145842747','Doctor','Dinosaurio123'),(30128924,'Juan','Chacon','correo@correo.com','04120754296','Doctor','Dinosaurio123'),(31111111,'Anthoan','Gonzalez','correo@gmail.com','0412785948','Doctor','Dinosaurio123'),(31111553,'Francheska','Gonzalez','correocorreo@gmail.com','04241875864','Enfermera','Dinosaurio123');
/*!40000 ALTER TABLE `personal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registro`
--

DROP TABLE IF EXISTS `registro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registro` (
  `cod_registro` int NOT NULL AUTO_INCREMENT,
  `fecha_r` date DEFAULT NULL,
  `cedula_h` int NOT NULL,
  `cod_examenes` int NOT NULL,
  `observacion_examen` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ruta_imagen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`cod_registro`),
  KEY `cedula_h` (`cedula_h`),
  KEY `cod_examenes` (`cod_examenes`),
  CONSTRAINT `registro_ibfk_1` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`),
  CONSTRAINT `registro_ibfk_2` FOREIGN KEY (`cod_examenes`) REFERENCES `examenes` (`cod_examenes`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registro`
--

LOCK TABLES `registro` WRITE;
/*!40000 ALTER TABLE `registro` DISABLE KEYS */;
INSERT INTO `registro` VALUES (21,'2025-05-11',5465489,1,'Se observa un resultado normal',NULL);
/*!40000 ALTER TABLE `registro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaccion`
--

DROP TABLE IF EXISTS `transaccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaccion` (
  `cod_transaccion` int NOT NULL AUTO_INCREMENT,
  `tipo_transaccion` varchar(30) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` varchar(7) DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  PRIMARY KEY (`cod_transaccion`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaccion`
--

LOCK TABLES `transaccion` WRITE;
/*!40000 ALTER TABLE `transaccion` DISABLE KEYS */;
INSERT INTO `transaccion` VALUES (38,'ajuste_negativo','2025-05-20','11:55',30128924),(39,'entrada','2025-05-20','12:17',30128924),(40,'salida','2025-05-20','12:20',30128924),(41,'entrada','2025-05-20','12:21',30128924),(43,'salida','2025-05-20','12:25',30128924),(44,'entrada','2025-05-20','12:26',30128924),(45,'salida','2025-05-20','12:34',30128924),(46,'ajuste_negativo','2025-05-20','12:44',30128924),(47,'ajuste_positivo','2025-05-20','12:44',30128924),(48,'entrada','2025-05-20','12:44',30128924),(49,'entrada','2025-05-20','12:54',30128924),(50,'salida','2025-05-20','12:56',30128924),(51,'entrada','2025-05-20','12:56',30128924),(52,'salida','2025-05-20','12:56',30128924);
/*!40000 ALTER TABLE `transaccion` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-21  0:21:44
