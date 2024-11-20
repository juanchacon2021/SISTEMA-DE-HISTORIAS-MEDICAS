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
  `general` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `respiratorio` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cardivascular` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `abdomen` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `extremidades_s` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `neurologicos` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cabeza_craneo` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ojos` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nariz` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `oidos` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `boca_abierta` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `boca_cerrada` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `extremidades_r` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`cod_consulta`),
  KEY `cedula_p` (`cedula_p`),
  KEY `cedula_h` (`cedula_h`),
  CONSTRAINT `consultas_ibfk_1` FOREIGN KEY (`cedula_p`) REFERENCES `personal` (`cedula_personal`),
  CONSTRAINT `consultas_ibfk_2` FOREIGN KEY (`cedula_h`) REFERENCES `historias` (`cedula_historia`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultas`
--

LOCK TABLES `consultas` WRITE;
/*!40000 ALTER TABLE `consultas` DISABLE KEYS */;
INSERT INTO `consultas` VALUES (2,'2024-10-01','Primera consulta','Diarrea','Resogal',31111553,30128924,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,NULL,NULL,NULL,NULL,31111553,85642892,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emergencias`
--

LOCK TABLES `emergencias` WRITE;
/*!40000 ALTER TABLE `emergencias` DISABLE KEYS */;
INSERT INTO `emergencias` VALUES (22,'05:49','2024-11-12','llego con un dolor de cabesa','aaaaaaaaaaaa','aaaaaaaaaaaaaaa',31111553,7856209),(23,'05:49','2024-11-12','aaaaaaaaaaaaaaaaaaaaaaaaa','aaaaaaaaaaaaaaaaaaaaaaaa','aaaaaaaaaaaaaaaaaaaaaaaaaaaa',31111553,5465489),(24,'05:51','2024-11-12','Como modelo de lenguaje, no puedo proporcionarte un diagnóstico médico.\r\n\r\nDiagnosticar una enfermedad requiere de un profesional de la salud capacitado, quien evaluará tus síntomas, realizará un examen físico y, si es necesario, ordenará pruebas de laboratorio o de imagen.\r\n\r\n¿Por qué no puedo darte un diagnóstico?\r\n\r\nInformación limitada: A través de una conversación en línea, no tengo acceso a tu historial médico completo, resultados de exámenes o a una exploración física.\r\nComplejidad de las enfermedades: Muchas enfermedades comparten síntomas similares, lo que hace que un diagnóstico preciso requiera una evaluación exhaustiva por parte de un médico.\r\nResponsabilidad profesional: Proporcionar un diagnóstico médico es una responsabilidad seria que requiere de conocimientos y habilidades específicas.\r\n¿Qué puedo hacer si estoy enfermo?\r\n\r\nSi tienes alguna preocupación sobre tu salud, te recomiendo:\r\n\r\nConsulta a un médico: Es la persona más capacitada para evaluar tus síntomas y realizar un diagnóstico preciso.\r\nDescribe tus síntomas con claridad: Incluye cuándo comenzaron, con qué frecuencia ocurren y qué los empeora o mejora.\r\nSé honesto sobre tu historial médico: Informa a tu médico sobre cualquier enfermedad que hayas tenido, medicamentos que estés tomando y alergias.\r\nRecuerda:\r\n\r\nNo te automediques: Tomar medicamentos sin receta médica puede empeorar tu condición o interactuar negativamente con otros medicamentos.\r\nBusca información confiable: Si quieres investigar sobre alguna enfermedad, consulta fuentes confiables como la Organización Mundial de la Salud (OMS) o sitios web de instituciones médicas reconocidas.\r\n¿Te gustaría que te proporcione información sobre alguna enfermedad en particular?\r\n\r\nPuedo ofrecerte información general sobre diversas enfermedades, sus síntomas, causas y tratamientos. Sin embargo, esta información no debe reemplazar el consejo médico profesional.\r\n\r\nPor favor, ten en cuenta que mi función es proporcionar información y no reemplazar la atención médica.','aaaaaaaaaaaaa','aaaaaaaaaaaaaaaaaa',31111553,12345678),(25,'05:51','2024-11-12','Como modelo de lenguaje, no puedo proporcionarte un diagnóstico médico.\r\n\r\nDiagnosticar una enfermedad requiere de un profesional de la salud capacitado, quien evaluará tus síntomas, realizará un examen físico y, si es necesario, ordenará pruebas de laboratorio o de imagen.\r\n\r\n¿Por qué no puedo darte un diagnóstico?\r\n\r\nInformación limitada: A través de una conversación en línea, no tengo acceso a tu historial médico completo, resultados de exámenes o a una exploración física.\r\nComplejidad de las enfermedades: Muchas enfermedades comparten síntomas similares, lo que hace que un diagnóstico preciso requiera una evaluación exhaustiva por parte de un médico.\r\nResponsabilidad profesional: Proporcionar un diagnóstico médico es una responsabilidad seria que requiere de conocimientos y habilidades específicas.\r\n¿Qué puedo hacer si estoy enfermo?\r\n\r\nSi tienes alguna preocupación sobre tu salud, te recomiendo:\r\n\r\nConsulta a un médico: Es la persona más capacitada para evaluar tus síntomas y realizar un diagnóstico preciso.\r\nDescribe tus síntomas con claridad: Incluye cuándo comenzaron, con qué frecuencia ocurren y qué los empeora o mejora.\r\nSé honesto sobre tu historial médico: Informa a tu médico sobre cualquier enfermedad que hayas tenido, medicamentos que estés tomando y alergias.\r\nRecuerda:\r\n\r\nNo te automediques: Tomar medicamentos sin receta médica puede empeorar tu condición o interactuar negativamente con otros medicamentos.\r\nBusca información confiable: Si quieres investigar sobre alguna enfermedad, consulta fuentes confiables como la Organización Mundial de la Salud (OMS) o sitios web de instituciones médicas reconocidas.\r\n¿Te gustaría que te proporcione información sobre alguna enfermedad en particular?\r\n\r\nPuedo ofrecerte información general sobre diversas enfermedades, sus síntomas, causas y tratamientos. Sin embargo, esta información no debe reemplazar el consejo médico profesional.\r\n\r\nPor favor, ten en cuenta que mi función es proporcionar información y no reemplazar la atención médica.','Como modelo de lenguaje, no puedo proporcionarte un diagnóstico médico.\r\n\r\nDiagnosticar una enfermedad requiere de un profesional de la salud capacitado, quien evaluará tus síntomas, realizará un examen físico y, si es necesario, ordenará pruebas de laboratorio o de imagen.\r\n\r\n¿Por qué no puedo darte un diagnóstico?\r\n\r\nInformación limitada: A través de una conversación en línea, no tengo acceso a tu historial médico completo, resultados de exámenes o a una exploración física.\r\nComplejidad de las enfermedades: Muchas enfermedades comparten síntomas similares, lo que hace que un diagnóstico preciso requiera una evaluación exhaustiva por parte de un médico.\r\nResponsabilidad profesional: Proporcionar un diagnóstico médico es una responsabilidad seria que requiere de conocimientos y habilidades específicas.\r\n¿Qué puedo hacer si estoy enfermo?\r\n\r\nSi tienes alguna preocupación sobre tu salud, te recomiendo:\r\n\r\nConsulta a un médico: Es la persona más capacitada para evaluar tus síntomas y realizar un diagnóstico preciso.\r\nDescribe tus síntomas con claridad: Incluye cuándo comenzaron, con qué frecuencia ocurren y qué los empeora o mejora.\r\nSé honesto sobre tu historial médico: Informa a tu médico sobre cualquier enfermedad que hayas tenido, medicamentos que estés tomando y alergias.\r\nRecuerda:\r\n\r\nNo te automediques: Tomar medicamentos sin receta médica puede empeorar tu condición o interactuar negativamente con otros medicamentos.\r\nBusca información confiable: Si quieres investigar sobre alguna enfermedad, consulta fuentes confiables como la Organización Mundial de la Salud (OMS) o sitios web de instituciones médicas reconocidas.\r\n¿Te gustaría que te proporcione información sobre alguna enfermedad en particular?\r\n\r\nPuedo ofrecerte información general sobre diversas enfermedades, sus síntomas, causas y tratamientos. Sin embargo, esta información no debe reemplazar el consejo médico profesional.\r\n\r\nPor favor, ten en cuenta que mi función es proporcionar información y no reemplazar la atención médica.','Como modelo de lenguaje, no puedo proporcionarte un diagnóstico médico.\r\n\r\nDiagnosticar una enfermedad requiere de un profesional de la salud capacitado, quien evaluará tus síntomas, realizará un examen físico y, si es necesario, ordenará pruebas de laboratorio o de imagen.\r\n\r\n¿Por qué no puedo darte un diagnóstico?\r\n\r\nInformación limitada: A través de una conversación en línea, no tengo acceso a tu historial médico completo, resultados de exámenes o a una exploración física.\r\nComplejidad de las enfermedades: Muchas enfermedades comparten síntomas similares, lo que hace que un diagnóstico preciso requiera una evaluación exhaustiva por parte de un médico.\r\nResponsabilidad profesional: Proporcionar un diagnóstico médico es una responsabilidad seria que requiere de conocimientos y habilidades específicas.\r\n¿Qué puedo hacer si estoy enfermo?\r\n\r\nSi tienes alguna preocupación sobre tu salud, te recomiendo:\r\n\r\nConsulta a un médico: Es la persona más capacitada para evaluar tus síntomas y realizar un diagnóstico preciso.\r\nDescribe tus síntomas con claridad: Incluye cuándo comenzaron, con qué frecuencia ocurren y qué los empeora o mejora.\r\nSé honesto sobre tu historial médico: Informa a tu médico sobre cualquier enfermedad que hayas tenido, medicamentos que estés tomando y alergias.\r\nRecuerda:\r\n\r\nNo te automediques: Tomar medicamentos sin receta médica puede empeorar tu condición o interactuar negativamente con otros medicamentos.\r\nBusca información confiable: Si quieres investigar sobre alguna enfermedad, consulta fuentes confiables como la Organización Mundial de la Salud (OMS) o sitios web de instituciones médicas reconocidas.\r\n¿Te gustaría que te proporcione información sobre alguna enfermedad en particular?\r\n\r\nPuedo ofrecerte información general sobre diversas enfermedades, sus síntomas, causas y tratamientos. Sin embargo, esta información no debe reemplazar el consejo médico profesional.\r\n\r\nPor favor, ten en cuenta que mi función es proporcionar información y no reemplazar la atención médica.',31111553,5465489),(26,'05:00','2024-11-12','qaaaaaaaaaa','aaaaaaaaaaa','Como modelo de lenguaje, no puedo proporcionarte un diagnóstico médico.\r\n\r\nDiagnosticar una enfermedad requiere de un profesional de la salud capacitado, quien evaluará tus síntomas, realizará un examen físico y, si es necesario, ordenará pruebas de laboratorio o de imagen.\r\n\r\n¿Por qué no puedo darte un diagnóstico?\r\n\r\nInformación limitada: A través de una conversación en línea, no tengo acceso a tu historial médico completo, resultados de exámenes o a una exploración física.\r\nComplejidad de las enfermedades: Muchas enfermedades comparten síntomas similares, lo que hace que un diagnóstico preciso requiera una evaluación exhaustiva por parte de un médico.\r\nResponsabilidad profesional: Proporcionar un diagnóstico médico es una responsabilidad seria que requiere de conocimientos y habilidades específicas.\r\n¿Qué puedo hacer si estoy enfermo?\r\n\r\nSi tienes alguna preocupación sobre tu salud, te recomiendo:\r\n\r\nConsulta a un médico: Es la persona más capacitada para evaluar tus síntomas y realizar un diagnóstico preciso.\r\nDescribe tus síntomas con claridad: Incluye cuándo comenzaron, con qué frecuencia ocurren y qué los empeora o mejora.\r\nSé honesto sobre tu historial médico: Informa a tu médico sobre cualquier enfermedad que hayas tenido, medicamentos que estés tomando y alergias.\r\nRecuerda:\r\n\r\nNo te automediques: Tomar medicamentos sin receta médica puede empeorar tu condición o interactuar negativamente con otros medicamentos.\r\nBusca información confiable: Si quieres investigar sobre alguna enfermedad, consulta fuentes confiables como la Organización Mundial de la Salud (OMS) o sitios web de instituciones médicas reconocidas.\r\n¿Te gustaría que te proporcione información sobre alguna enfermedad en particular?\r\n\r\nPuedo ofrecerte información general sobre diversas enfermedades, sus síntomas, causas y tratamientos. Sin embargo, esta información no debe reemplazar el consejo médico profesional.\r\n\r\nPor favor, ten en cuenta que mi función es proporcionar información y no reemplazar la atención médica.',31111553,88888888),(27,'05:00','2024-10-29','aaaaaaaaaa','aaaaaaaaaaaaa','aaaaaaaaaaaaaaaa',31111111,85642892),(28,'19:02','2024-11-12','L','L','L',31111553,88888888),(30,'09:08','2024-11-12','L','L','L',31111111,88888888);
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
INSERT INTO `historias` VALUES (5465489,'LUIS MIGUEL','GALLEGO BASTERI','1970-04-19',54,'DIVORCIADO','L','MEXICO','351351351','L','L','L','L','L','L','L',NULL,NULL,NULL),(7856209,'JOE ALEX','CHACON VARGAS','1966-04-28',58,'DIVORCIADO','LOCUTOR','CERRITOS BLANCO','4125105446','NO POSEE','SOL','NO POSEE','NO POSEE','NO POSEE','NO POSEE','NO POSEE',NULL,NULL,NULL),(8564289,'JURGEN','KLINSMANN','2024-10-01',21,'','PPPPP','asdadsasdads','351351351','PPPPP','P','P','P','P','P','P',NULL,NULL,NULL),(9999999,'ANTHOAN','PATINNO','2004-04-30',258,'DIVORCIADO','B','BBBBBBBBBB','352131','B','B','B','B','B','B','B',NULL,NULL,NULL),(12345678,'CRISTIANO ','RONALDO','2024-09-03',12,'','HOLA','HOLA','133513','HOLA','HOLA','HOLA','HOLA','HOLA','HOLA','HOLA',NULL,NULL,NULL),(30128924,'SALOMON','RONDON','2024-09-02',21,'','asdas','asdsda','35135153','asddd','adsadsadsads','dsaaa','adsdasas','das','ads','dasasas',NULL,NULL,NULL),(85642892,'SHOHEI','OHTANI','2024-10-01',21,'','undefined','asdadsasdads','351351351','undefined','undefined','undefined','undefined','undefined','undefined','undefined',NULL,NULL,NULL),(88888888,'JUAN','CHACON','2024-09-03',21,'','A','AAAAAAAAA','35135131','A','A','A','A','A','A','A',NULL,NULL,NULL);
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
INSERT INTO `personal` VALUES (12345678,'admin','admin','admin@gmail.com','12345678910','Doctor','12345678'),(31111111,'anthoan','gonzalez','anthoangonzalez@gmail.com','0412785948','Doctor','Dinosaurio'),(31111553,'franchesco','gonzalez','franchescovernouli@gmail.com','04241875864','Enfermera','Dinosaurio');
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registro`
--

LOCK TABLES `registro` WRITE;
/*!40000 ALTER TABLE `registro` DISABLE KEYS */;
INSERT INTO `registro` VALUES (1,'2024-10-10',9999999,1,'HOLA LOCO1',NULL),(4,'2024-11-19',7856209,1,'nada',NULL),(5,'2024-11-12',8564289,1,'nada',NULL),(20,'2024-11-12',30128924,4,'prueba',NULL);
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

-- Dump completed on 2024-11-18  9:24:56
