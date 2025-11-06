-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: sgm
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
-- Table structure for table `antecedentes_familiares`
--

DROP TABLE IF EXISTS `antecedentes_familiares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `antecedentes_familiares` (
  `id_familiar` int NOT NULL,
  `nom_familiar` varchar(50) DEFAULT NULL,
  `ape_familiar` varchar(50) DEFAULT NULL,
  `observaciones` varchar(300) DEFAULT NULL,
  `relacion_familiar` varchar(50) DEFAULT NULL,
  `cedula_paciente` int DEFAULT NULL,
  PRIMARY KEY (`id_familiar`),
  KEY `cedula_paciente` (`cedula_paciente`),
  CONSTRAINT `antecedentes_familiares_ibfk_1` FOREIGN KEY (`cedula_paciente`) REFERENCES `paciente` (`cedula_paciente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `antecedentes_familiares`
--

LOCK TABLES `antecedentes_familiares` WRITE;
/*!40000 ALTER TABLE `antecedentes_familiares` DISABLE KEYS */;
/*!40000 ALTER TABLE `antecedentes_familiares` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `areas_pasantias`
--

DROP TABLE IF EXISTS `areas_pasantias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `areas_pasantias` (
  `cod_area` varchar(50) NOT NULL,
  `nombre_area` varchar(50) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `cedula_responsable` int NOT NULL,
  PRIMARY KEY (`cod_area`),
  KEY `areas_pasantias_ibfk_1` (`cedula_responsable`),
  CONSTRAINT `areas_pasantias_ibfk_1` FOREIGN KEY (`cedula_responsable`) REFERENCES `personal` (`cedula_personal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `areas_pasantias`
--

LOCK TABLES `areas_pasantias` WRITE;
/*!40000 ALTER TABLE `areas_pasantias` DISABLE KEYS */;
INSERT INTO `areas_pasantias` VALUES ('1a','TTTa','SSSSSSSSSSSSSSS',32014004),('Ax00000002','SSSdd','SSSSSSSSSSS',32014004),('Ax00000003','Área Test A68f915b3bc0a7','Descripción Modificada 68f915b3bc0ab',31111553),('Ax00000004','Area Modificada 68f915ce4dca3','Nueva Descripción Modificada',31111553),('Ax00000005','Area Modificada 68fb9b56c2545','Nueva Descripción Modificada',31111553),('Ax001','FFF','SSSSS',32014004);
/*!40000 ALTER TABLE `areas_pasantias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consulta`
--

DROP TABLE IF EXISTS `consulta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consulta` (
  `cod_consulta` varchar(30) NOT NULL,
  `fechaconsulta` date NOT NULL,
  `Horaconsulta` varchar(7) NOT NULL,
  `consulta` varchar(100) DEFAULT NULL,
  `diagnostico` varchar(100) DEFAULT NULL,
  `tratamientos` varchar(100) DEFAULT NULL,
  `cedula_paciente` int NOT NULL,
  `cedula_personal` int NOT NULL,
  PRIMARY KEY (`cod_consulta`),
  KEY `cedula_personal` (`cedula_personal`),
  KEY `consulta_ibfk_1` (`cedula_paciente`),
  CONSTRAINT `consulta_ibfk_1` FOREIGN KEY (`cedula_paciente`) REFERENCES `paciente` (`cedula_paciente`),
  CONSTRAINT `consulta_ibfk_2` FOREIGN KEY (`cedula_personal`) REFERENCES `personal` (`cedula_personal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consulta`
--

LOCK TABLES `consulta` WRITE;
/*!40000 ALTER TABLE `consulta` DISABLE KEYS */;
INSERT INTO `consulta` VALUES ('Cx00000001','2025-06-18','01:00','TTTs','tTTT','TTT',10000001,20000010),('Cx00000003','2025-06-19','03:12','SSs','TTT','TTT',10000003,20000004),('Cx00000004','2025-06-28','01:00','consulta','Diagnostico','TTT',10000007,20000010),('Cx00000005','2025-06-28','02:15','SSS','ssss','sssss',10000010,20000010),('Cx00000006','2025-07-03','01:10','CCC','DDD','TTT',10000001,20000001);
/*!40000 ALTER TABLE `consulta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emergencia`
--

DROP TABLE IF EXISTS `emergencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `emergencia` (
  `horaingreso` varchar(7) NOT NULL,
  `fechaingreso` date NOT NULL,
  `motingreso` varchar(3000) DEFAULT NULL,
  `diagnostico_e` varchar(3000) DEFAULT NULL,
  `tratamientos` varchar(3000) DEFAULT NULL,
  `cedula_paciente` int NOT NULL,
  `cedula_personal` int NOT NULL,
  `procedimiento` varchar(3000) DEFAULT NULL,
  PRIMARY KEY (`cedula_paciente`,`cedula_personal`,`fechaingreso`,`horaingreso`),
  KEY `cedula_personal` (`cedula_personal`),
  CONSTRAINT `emergencia_ibfk_1` FOREIGN KEY (`cedula_paciente`) REFERENCES `paciente` (`cedula_paciente`),
  CONSTRAINT `emergencia_ibfk_2` FOREIGN KEY (`cedula_personal`) REFERENCES `personal` (`cedula_personal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emergencia`
--

LOCK TABLES `emergencia` WRITE;
/*!40000 ALTER TABLE `emergencia` DISABLE KEYS */;
INSERT INTO `emergencia` VALUES ('08:30','2025-05-30','Dolor torácico súbito','Angina inestable','Aspirina, monitoreo ECG, traslado a UCI',10000001,20000001,NULL),('10:20','2025-06-01','TTT','TTT','ggg',10000001,20000001,NULL),('13:01','2025-06-01','ggg','GGGG','GGGGG',10000001,20000001,NULL),('00:34','2025-06-06','gggggggggg','gggggggggggggggg','ggggggggggggggg',10000001,20000001,'gggggggggggggggf'),('12:45','2025-06-18','SSS','ssss','sssss',10000001,20000001,'sssss'),('01:00','2025-06-28','motivo de ingresobd','Diagnostico bd','TRatamientos  bbd',10000001,20000001,'Procedimiento bd'),('13:01','2025-06-01','GGGG','ggGGGG','GGGG',10000001,20000005,NULL),('20:52','2025-06-24','TTTT','TTTT','TTTT',10000001,20000009,'TTTT'),('14:15','2025-05-29','Fiebre alta y dificultad respiratoria','Neumonía aguda','Antibióticos IV, oxigenoterapia',10000002,20000004,NULL),('10:01','2025-06-11','qqqq','sss','ytuytuyt',10000003,20000003,'ttttgggggggggggg'),('13:00','2025-06-01','TTTTTtss','tTTTTT','TTTTT',10000003,20000005,'nyyyyyyyyyy'),('22:40','2025-06-04','hhhhhhhhhh','hhhhhhhhhhhhh','hhhhhhhhhhhhh',10000003,20000006,'yttttt'),('22:50','2025-05-28','Dolor abdominal intenso','Apendicitis aguda','Cirugía de emergencia',10000003,20000007,NULL),('17:45','2025-05-30','Hipoglucemia severa','Crisis hipoglucémica','Glucosa IV, observación',10000005,20000001,NULL),('01:30','2025-05-25','Corte profundo en mano con cuchillo','Herida cortante','Limpieza, sutura y antibióticos',10000007,20000003,'anthoan'),('04:38','2025-06-18','es pato ','pato cronico','maricosilina de treinta miligramos  y bitifarra',10000007,20000004,'le metimos el guevo'),('15:00','2025-05-25','Dolor en pecho y ansiedad','Crisis de pánico','Ansiolíticos y observación',10000008,20000009,NULL),('19:44','2025-05-24','Ataque de asma','Exacerbación asmática','Broncodilatadores y oxígeno GG',10000009,20000002,NULL),('01:01','2025-07-03','Motivi ingreso','Diagnostico','Tratamiento',10000009,20000009,'Procedimiento'),('11:30','2025-06-02','LLLLL','LLLL','LLLLL',10000010,20000001,NULL),('07:50','2025-05-23','Mareos y palpitaciones','Taquicardia sinusal','Reposo, líquidos IV, monitoreo',10000010,20000010,NULL),('21:42','2025-06-28','SSSttt','ssssttt','sssstt',10000010,20000010,'ssssstt');
/*!40000 ALTER TABLE `emergencia` ENABLE KEYS */;
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
-- Table structure for table `examen`
--

DROP TABLE IF EXISTS `examen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `examen` (
  `fecha_e` date NOT NULL,
  `hora_e` time NOT NULL,
  `cedula_paciente` int NOT NULL,
  `cod_examen` varchar(30) NOT NULL,
  `observacion_examen` varchar(100) DEFAULT NULL,
  `ruta_imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cedula_paciente`,`cod_examen`,`fecha_e`,`hora_e`),
  KEY `cod_examen` (`cod_examen`),
  CONSTRAINT `examen_ibfk_1` FOREIGN KEY (`cedula_paciente`) REFERENCES `paciente` (`cedula_paciente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examen`
--

LOCK TABLES `examen` WRITE;
/*!40000 ALTER TABLE `examen` DISABLE KEYS */;
INSERT INTO `examen` VALUES ('2025-07-03','00:00:00',10000007,'1','SSSSSS',NULL);
/*!40000 ALTER TABLE `examen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feed`
--

DROP TABLE IF EXISTS `feed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feed` (
  `cod_pub` varchar(30) NOT NULL,
  `fecha` datetime NOT NULL,
  `contenido` varchar(300) DEFAULT NULL,
  `imagen` text,
  `cedula_personal` int NOT NULL,
  PRIMARY KEY (`cod_pub`),
  KEY `cedula_personal` (`cedula_personal`),
  CONSTRAINT `feed_ibfk_1` FOREIGN KEY (`cedula_personal`) REFERENCES `personal` (`cedula_personal`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feed`
--

LOCK TABLES `feed` WRITE;
/*!40000 ALTER TABLE `feed` DISABLE KEYS */;
INSERT INTO `feed` VALUES ('1','2025-07-04 20:34:09','SSSSSSSSSSSSSS','',32014004),('Px00000001','2025-07-04 20:49:14','hola viqui','',32014004);
/*!40000 ALTER TABLE `feed` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `insumos`
--

DROP TABLE IF EXISTS `insumos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `insumos` (
  `cod_movimiento` varchar(30) NOT NULL,
  `cod_lote` varchar(30) NOT NULL,
  `cantidad` int NOT NULL,
  PRIMARY KEY (`cod_movimiento`,`cod_lote`),
  KEY `cod_lote` (`cod_lote`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insumos`
--

LOCK TABLES `insumos` WRITE;
/*!40000 ALTER TABLE `insumos` DISABLE KEYS */;
INSERT INTO `insumos` VALUES ('1','2',5),('1','3',70),('10','10',160),('2','2',2),('3','3',140),('4','4',1),('5','5',2),('6','6',100),('7','7',2),('8','8',200),('9','9',1),('Sx00000001','2',75),('Sx00000013','Lx00000001',200),('Sx00000014','Lx00000003',250),('Sx00000015','2',25),('Sx00000015','Lx00000002',200),('Sx00000016','Lx00000004',250),('Sx00000017','Lx00000005',250),('Sx00000018','Lx00000007',250),('Sx00000019','Lx00000006',250),('Sx00000020','Lx00000008',250),('Sx00000021','Lx00000009',250),('Sx00000022','Lx00000010',250),('Sx00000023','Lx00000011',250),('Sx00000024','Lx00000013',250),('Sx00000025','Lx00000014',250),('Sx00000026','5',22);
/*!40000 ALTER TABLE `insumos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jornadas_medicas`
--

DROP TABLE IF EXISTS `jornadas_medicas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jornadas_medicas` (
  `cod_jornada` varchar(30) NOT NULL,
  `fecha_jornada` date NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `descripcion` text,
  `total_pacientes` int NOT NULL DEFAULT '0',
  `pacientes_masculinos` int NOT NULL DEFAULT '0',
  `pacientes_femeninos` int NOT NULL DEFAULT '0',
  `pacientes_embarazadas` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cod_jornada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jornadas_medicas`
--

LOCK TABLES `jornadas_medicas` WRITE;
/*!40000 ALTER TABLE `jornadas_medicas` DISABLE KEYS */;
INSERT INTO `jornadas_medicas` VALUES ('1','2025-07-04','TTTT','TTTT',8,4,4,2,'2025-07-05 01:04:39'),('Jx00000001','2025-07-04','yucatan','mamamamamama',7,5,2,1,'2025-07-05 01:22:13');
/*!40000 ALTER TABLE `jornadas_medicas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lotes`
--

DROP TABLE IF EXISTS `lotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lotes` (
  `cod_lote` varchar(30) NOT NULL,
  `cantidad` int NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `proveedor` varchar(50) NOT NULL,
  `cod_medicamento` varchar(30) NOT NULL,
  `cedula_personal` int NOT NULL,
  PRIMARY KEY (`cod_lote`),
  KEY `cod_medicamento` (`cod_medicamento`),
  KEY `cedula_personal_idx` (`cedula_personal`),
  CONSTRAINT `lotes_fk2` FOREIGN KEY (`cedula_personal`) REFERENCES `personal` (`cedula_personal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lotes`
--

LOCK TABLES `lotes` WRITE;
/*!40000 ALTER TABLE `lotes` DISABLE KEYS */;
INSERT INTO `lotes` VALUES ('10',90,'2025-10-10','Distribuidora Farmacéutica','9',20000009),('11',110,'2026-01-30','Farmacéutica Continental','10',20000010),('2',0,'2024-06-30','Farmacéutica Nacional S.A.','1',20000001),('3',50,'2025-12-15','MediSupply Corp','2',20000002),('4',200,'2024-11-30','BioFarma Internacional','3',20000003),('5',53,'2025-08-20','Genéricos del Sur','4',20000004),('6',150,'2024-09-10','Farmacéutica Global','5',20000005),('7',80,'2026-03-25','MediHealth Solutions','6',20000006),('8',120,'2025-05-15','Farmacéutica Innovadora','7',20000007),('9',60,'2024-12-31','Suministros Médicos S.A.','8',20000008),('Lx00000001',0,'2050-04-30','aaaaaaaaaaaa','Mx00000001',32014005),('Lx00000002',0,'2028-04-30','aaaaaaaaaa','1',32014005),('Lx00000003',0,'2030-04-30','sfdsadas','Mx00000001',32014005),('Lx00000004',0,'2030-04-30','dsfsdsfd','1',32014005),('Lx00000005',0,'2030-04-30','asdasas','1',32014005),('Lx00000006',0,'2500-04-30','asdasdsa','Mx00000001',32014005),('Lx00000007',0,'2030-04-30','asddsaas','1',32014005),('Lx00000008',0,'2060-04-30','aaaaaaaa','1',32014005),('Lx00000009',0,'2050-04-30','aaaaaaaa','Mx00000001',32014005),('Lx00000010',0,'2050-04-30','aaaaaaaaa','1',32014005),('Lx00000011',0,'2025-04-30','aaaaaaaaaa','Mx00000001',32014005),('Lx00000012',250,'2050-04-30','assaas','Mx00000001',32014005),('Lx00000013',0,'2058-04-30','sadsadsda','1',32014005),('Lx00000014',0,'2050-04-30','sdasdaasdds','1',32014005);
/*!40000 ALTER TABLE `lotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medicamentos`
--

DROP TABLE IF EXISTS `medicamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medicamentos` (
  `cod_medicamento` varchar(30) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `unidad_medida` varchar(20) NOT NULL,
  `stock_min` int NOT NULL,
  PRIMARY KEY (`cod_medicamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medicamentos`
--

LOCK TABLES `medicamentos` WRITE;
/*!40000 ALTER TABLE `medicamentos` DISABLE KEYS */;
INSERT INTO `medicamentos` VALUES ('1','AAA','SSSSSS','mg',10),('10','Diazepam','Ansiolítico y relajante muscular','mg',20),('11','Hidroclorotiazida','Diurético para el tratamiento de la hipertensión arterial','mg',20),('2','Paracetamol','Analgésico y antipirético para aliviar el dolor y reducir la fiebre','mg',0),('3','Ibuprofeno','Antiinflamatorio no esteroideo para dolor e inflamación','mg',20),('4','Amoxicilina','Antibiótico de amplio espectro para infecciones bacterianas','mg',20),('5','Omeprazol','Inhibidor de la bomba de protones para reducir el ácido estomacal','mg',0),('6','Loratadina','Antihistamínico para el alivio de síntomas de alergia','mg',0),('7','Atorvastatina','Medicamento para reducir el colesterol en sangre','mg',20),('8','Metformina','Antidiabético oral para el tratamiento de la diabetes tipo 2','mg',0),('9','Salbutamol','Broncodilatador para el tratamiento del asma','mcg',0),('Mx00000001','ABCCCC','aaaaaaaaaaa','mg',15),('Mx00000002','Medicamento PHPUnit T68f84847e1c0e','Descripción del medicamento de prueba','Tabletas',10),('Mx00000003','Medicamento PHPUnit T68f8497694e68','Descripción del medicamento de prueba','Tabletas',5),('Mx00000004','Med Entrada Inválida 68f84976a7865','Prueba entrada con personal inválido','Unid',0),('Mx00000005','Med Sin Stock 68f84976ad9f9','Prueba salida sin stock','Unid',0),('Mx00000006','Medicamento PHPUnit T68fb9b1009e40','Descripción del medicamento de prueba','Tabletas',5),('Mx00000007','Med Entrada Inválida 68fb9b101fbe8','Prueba entrada con personal inválido','Unid',0),('Mx00000008','Med Sin Stock 68fb9b1027cd4','Prueba salida sin stock','Unid',0);
/*!40000 ALTER TABLE `medicamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `observacion_consulta`
--

DROP TABLE IF EXISTS `observacion_consulta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `observacion_consulta` (
  `cod_consulta` varchar(30) NOT NULL,
  `cod_observacion` varchar(30) NOT NULL,
  `observacion` varchar(3000) NOT NULL,
  PRIMARY KEY (`cod_consulta`,`cod_observacion`),
  KEY `cod_observacion` (`cod_observacion`),
  CONSTRAINT `fk_cod_consulta` FOREIGN KEY (`cod_consulta`) REFERENCES `consulta` (`cod_consulta`),
  CONSTRAINT `fk_observacion_consulta_tipo` FOREIGN KEY (`cod_observacion`) REFERENCES `tipo_observacion` (`cod_observacion`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `observacion_consulta`
--

LOCK TABLES `observacion_consulta` WRITE;
/*!40000 ALTER TABLE `observacion_consulta` DISABLE KEYS */;
INSERT INTO `observacion_consulta` VALUES ('Cx00000001','Ox00000002','FFFF'),('Cx00000001','Ox00000003','ffff'),('Cx00000003','Ox00000002','TTT'),('Cx00000003','Ox00000003','FFFF'),('Cx00000004','Ox00000002','prueba_2'),('Cx00000004','Ox00000003','prueba_3'),('Cx00000004','Ox00000004','Prueba_1'),('Cx00000005','Ox00000005','TTT'),('Cx00000006','Ox00000005','$$$'),('Cx00000006','Ox00000006','TTTT');
/*!40000 ALTER TABLE `observacion_consulta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paciente`
--

DROP TABLE IF EXISTS `paciente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paciente` (
  `cedula_paciente` int NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `apellido` varchar(15) NOT NULL,
  `fecha_nac` date DEFAULT NULL,
  `edad` int DEFAULT NULL,
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
  PRIMARY KEY (`cedula_paciente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paciente`
--

LOCK TABLES `paciente` WRITE;
/*!40000 ALTER TABLE `paciente` DISABLE KEYS */;
INSERT INTO `paciente` VALUES (10000001,'Carlos macapito','Ramírez','1985-07-12',39,'null','Ingeniero','Av. Bolívar 123, Caracas','04141234567','HTA','Polvo','Penicilina','Apendicectomía','Ninguna','Ansiedad leve','Café'),(10000002,'María','Pérez','1990-01-23',35,'Soltera','Docente','Calle 5, Maracaibo','04161234567','Asma','Pólen','Ibuprofeno','Cesárea','Una vez en 2010','Sin antecedentes','Ninguno'),(10000003,'Luis','Gómez','1978-11-10',46,'Divorciado','Contador','Urb. Los Palos Grandes, Caracas','04121234567','Diabetes tipo 2','Ninguna','Aspirina','Hernia inguinal','Negado','Depresión controlada','Tabaco'),(10000004,'Ana','Martínez','2000-05-05',25,'Soltera','Estudiante','El Paraíso, Caracas','04241234567','Sin antecedentes','Maní','Ninguna','Ninguna','No aplica','Estres académico','Alcohol ocasional'),(10000005,'José','Torres','1965-09-18',59,'Casado','Abogado','Barquisimeto Centro','04121876543','Hipotiroidismo','Polvo','Penicilina','Cirugía de tiroides','Sí, en 2005','Sin alteraciones','Café y cigarro'),(10000006,'Laura','Fernández','1988-03-30',37,'SOLTERO','Diseñadora','Naguanagua, Edo. Carabobo','04149382716','Migrañas frecuentes','Perfumes','Ketorolaco','Cirugía ocular','Una vez en 2015','Ansiedad leve','Alcohol social'),(10000007,'Pedro','López','1995-12-01',29,'Soltero','Programador','Calle 8, Mérida','04261324567','Sin antecedentes','Ninguna','Ninguna','Ninguna','No','Sin observaciones','Ninguno'),(10000008,'Isabel','Morales','1973-06-14',51,'Viuda','Ama de casa','Zona Industrial, Valencia','04169874512','Artritis','Polvo, moho','Metamizol','Histerectomía','Sí, 1999','Duelo por pérdida','Ninguno'),(10000009,'Andrés','Silva','1982-02-19',43,'Casado','Mecánico','Av. Principal, Puerto Ordaz','04124569871','Asma crónica','Ácaros','Ninguna','Cirugía nasal','No','Sin antecedentes','Cigarrillo'),(10000010,'Claudia','Rivas','1998-08-25',26,'Soltera','Enfermera','Callejón 7, Cumaná','04249281734','Sin antecedentes','Látex','Amoxicilina','Ninguna','No','Sin afectaciones','Ninguno');
/*!40000 ALTER TABLE `paciente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `padece`
--

DROP TABLE IF EXISTS `padece`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `padece` (
  `cedula_paciente` int NOT NULL,
  `cod_patologia` varchar(30) NOT NULL,
  `tratamiento` varchar(300) DEFAULT NULL,
  `administracion_t` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`cedula_paciente`,`cod_patologia`),
  KEY `cod_patologia` (`cod_patologia`),
  CONSTRAINT `padece_ibfk_1` FOREIGN KEY (`cedula_paciente`) REFERENCES `paciente` (`cedula_paciente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `padece`
--

LOCK TABLES `padece` WRITE;
/*!40000 ALTER TABLE `padece` DISABLE KEYS */;
INSERT INTO `padece` VALUES (10000001,'26','DDD','DDD'),(10000002,'2','xXX','Xxx'),(10000002,'3','ZZZt','zzz'),(10000003,'10','fff','gggg'),(10000003,'11','sss','ddd'),(10000004,'10','gggg','gggg'),(10000004,'16','TTT','TTT'),(10000004,'2','111','4444'),(10000004,'21','dddd','ddd'),(10000004,'22','gggg','gggg'),(10000004,'23','SSS','SSS'),(10000005,'18','SSS','SSSS'),(10000005,'19','SSS','ssSSS'),(10000006,'14','rrr','rrrr'),(10000006,'2','VVV','vvv'),(10000007,'16','TTTT','TTTT'),(10000007,'2','EEEEEEEEEE','eeeeeeeeeeeeee'),(10000007,'3','eeeeeeeeeeee','eeeeeeee'),(10000008,'2','fafafaf','gagaga'),(10000008,'24','tttt','tttt'),(10000008,'25','XXX','XXs'),(10000008,'26','sss','ssss'),(10000009,'18','tttt','ttttt'),(10000009,'19','tttt','tttt'),(10000009,'20','ttttt','tttt'),(10000009,'21','TTT','tttt'),(10000009,'22','ttttt','ttttt'),(10000010,'20','SSSS','SSSS'),(10000010,'21','SSS','SSS'),(10000010,'22','SSSS','SSSs');
/*!40000 ALTER TABLE `padece` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participantes_jornadas`
--

DROP TABLE IF EXISTS `participantes_jornadas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `participantes_jornadas` (
  `cod_jornada` varchar(30) NOT NULL,
  `cedula_personal` int NOT NULL,
  `tipo_participante` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`cod_jornada`,`cedula_personal`),
  KEY `cedula_personal` (`cedula_personal`),
  CONSTRAINT `participantes_jornadas_ibfk_2` FOREIGN KEY (`cedula_personal`) REFERENCES `personal` (`cedula_personal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participantes_jornadas`
--

LOCK TABLES `participantes_jornadas` WRITE;
/*!40000 ALTER TABLE `participantes_jornadas` DISABLE KEYS */;
INSERT INTO `participantes_jornadas` VALUES ('1',20000001,'participante'),('1',20000006,'participante'),('1',20000008,'participante'),('1',32014004,'responsable'),('Jx00000001',20000001,'participante'),('Jx00000001',20000009,'participante'),('Jx00000001',20000010,'participante'),('Jx00000001',32014004,'responsable');
/*!40000 ALTER TABLE `participantes_jornadas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patologia`
--

DROP TABLE IF EXISTS `patologia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patologia` (
  `cod_patologia` varchar(30) NOT NULL,
  `nombre_patologia` varchar(100) NOT NULL,
  PRIMARY KEY (`cod_patologia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patologia`
--

LOCK TABLES `patologia` WRITE;
/*!40000 ALTER TABLE `patologia` DISABLE KEYS */;
INSERT INTO `patologia` VALUES ('10','prueba_1'),('11','prueba_2'),('14','rrr'),('16','pruebaaa_3'),('17','prueba_4'),('18','prueba_5'),('19','prueba_6'),('2','B'),('20','prueba_7'),('21','prueba_8'),('22','prueba_9'),('23','Prueba_223'),('24','ttt'),('25','XXX'),('26','Prueba_11'),('3','C4'),('Px00000001','ddd');
/*!40000 ALTER TABLE `patologia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patologias_familiares`
--

DROP TABLE IF EXISTS `patologias_familiares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patologias_familiares` (
  `id_familiar` int NOT NULL,
  `cod_patologia` varchar(30) NOT NULL,
  PRIMARY KEY (`id_familiar`,`cod_patologia`),
  KEY `cod_patologia` (`cod_patologia`),
  CONSTRAINT `patologias_familiares_ibfk_1` FOREIGN KEY (`id_familiar`) REFERENCES `antecedentes_familiares` (`id_familiar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patologias_familiares`
--

LOCK TABLES `patologias_familiares` WRITE;
/*!40000 ALTER TABLE `patologias_familiares` DISABLE KEYS */;
/*!40000 ALTER TABLE `patologias_familiares` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `periodo_pasantias`
--

DROP TABLE IF EXISTS `periodo_pasantias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `periodo_pasantias` (
  `cod_area` varchar(50) NOT NULL,
  `cedula_estudiante` int NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`cod_area`,`cedula_estudiante`,`fecha_inicio`),
  KEY `cedula_estudiante` (`cedula_estudiante`),
  CONSTRAINT `periodo_pasantias_ibfk_1` FOREIGN KEY (`cod_area`) REFERENCES `areas_pasantias` (`cod_area`),
  CONSTRAINT `periodo_pasantias_ibfk_2` FOREIGN KEY (`cedula_estudiante`) REFERENCES `estudiantes_pasantia` (`cedula_estudiante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `periodo_pasantias`
--

LOCK TABLES `periodo_pasantias` WRITE;
/*!40000 ALTER TABLE `periodo_pasantias` DISABLE KEYS */;
/*!40000 ALTER TABLE `periodo_pasantias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal`
--

DROP TABLE IF EXISTS `personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal` (
  `cedula_personal` int NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `apellido` varchar(15) NOT NULL,
  `correo` varchar(30) DEFAULT NULL,
  `cargo` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`cedula_personal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal`
--

LOCK TABLES `personal` WRITE;
/*!40000 ALTER TABLE `personal` DISABLE KEYS */;
INSERT INTO `personal` VALUES (20000001,'Juan','Sánchez','jsanchez@clinicavida.com','Médico'),(20000002,'Elena','García','egarcia@clinicavida.com','Enfermera'),(20000003,'Marco','Díaz','mdiaz@clinicavida.com','Recepcionista'),(20000004,'Lucía','Ortega','lortega@clinicavida.com','Médico'),(20000005,'Raúl','Castro','rcastro@clinicavida.com',''),(20000006,'Patricia','Mendoza','pmendoza@clinicavida.com','Administrador'),(20000007,'Daniel','Fernández','dfernandez@clinicavida.com','Médico'),(20000008,'Carmen','Salas','csalas@clinicavida.com','Secretaria'),(20000009,'José','Ríos','jrios@clinicavida.com','Enfermero'),(20000010,'Sofía','Lugo','slugo@clinicavida.com','Nutricionista'),(31111553,'FRanchesc','vergoline','anthoan.g23@gmail.com','Doctor'),(32014004,'Eduin','Meneses','eduin@gmail.om','Doctor'),(32014005,'Juan','Esteban','correo@correo.com','Doctor');
/*!40000 ALTER TABLE `personal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salida_medicamento`
--

DROP TABLE IF EXISTS `salida_medicamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `salida_medicamento` (
  `cod_salida` varchar(30) NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` varchar(7) DEFAULT NULL,
  `cedula_personal` int NOT NULL,
  PRIMARY KEY (`cod_salida`),
  KEY `cedula_personal` (`cedula_personal`),
  CONSTRAINT `cedula_personal` FOREIGN KEY (`cedula_personal`) REFERENCES `personal` (`cedula_personal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salida_medicamento`
--

LOCK TABLES `salida_medicamento` WRITE;
/*!40000 ALTER TABLE `salida_medicamento` DISABLE KEYS */;
INSERT INTO `salida_medicamento` VALUES ('1','2023-11-15','08:30',20000001),('10','2023-11-22','09:50',20000010),('2','2023-11-15','09:15',20000002),('3','2023-11-16','10:00',20000003),('4','2023-11-16','11:45',20000004),('5','2023-11-17','14:20',20000005),('6','2023-11-18','16:00',20000006),('7','2023-11-19','08:45',20000007),('8','2023-11-20','13:10',20000008),('9','2023-11-21','15:30',20000009),('Sx00000001','2025-07-06','19:23',32014005),('Sx00000002','2025-07-06','19:52',32014005),('Sx00000003','2025-07-06','19:56',32014005),('Sx00000004','2025-07-06','19:56',32014005),('Sx00000005','2025-07-06','19:57',32014005),('Sx00000006','2025-07-06','20:17',32014005),('Sx00000007','2025-07-06','20:18',32014005),('Sx00000008','2025-07-06','20:19',32014005),('Sx00000009','2025-07-06','20:20',32014005),('Sx00000010','2025-07-06','20:21',32014005),('Sx00000011','2025-07-06','20:24',32014005),('Sx00000012','2025-07-06','20:30',32014005),('Sx00000013','2025-07-06','20:31',32014005),('Sx00000014','2025-07-06','20:48',32014005),('Sx00000015','2025-07-06','20:50',32014005),('Sx00000016','2025-07-06','20:50',32014005),('Sx00000017','2025-07-06','20:52',32014005),('Sx00000018','2025-07-06','20:54',32014005),('Sx00000019','2025-07-06','20:55',32014005),('Sx00000020','2025-07-06','21:04',32014005),('Sx00000021','2025-07-06','21:35',32014005),('Sx00000022','2025-07-06','21:44',32014005),('Sx00000023','2025-07-06','21:48',32014005),('Sx00000024','2025-07-06','21:56',32014005),('Sx00000025','2025-07-06','21:57',32014005),('Sx00000026','2025-10-22','12:49',32014004);
/*!40000 ALTER TABLE `salida_medicamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telefonos_personal`
--

DROP TABLE IF EXISTS `telefonos_personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telefonos_personal` (
  `cedula_personal` int NOT NULL,
  `telefono` varchar(15) NOT NULL,
  PRIMARY KEY (`cedula_personal`,`telefono`),
  CONSTRAINT `telefonos_personal_ibfk_1` FOREIGN KEY (`cedula_personal`) REFERENCES `personal` (`cedula_personal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telefonos_personal`
--

LOCK TABLES `telefonos_personal` WRITE;
/*!40000 ALTER TABLE `telefonos_personal` DISABLE KEYS */;
INSERT INTO `telefonos_personal` VALUES (20000005,'04145654554'),(31111553,'04145555555'),(32014004,'04145842747');
/*!40000 ALTER TABLE `telefonos_personal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_de_examen`
--

DROP TABLE IF EXISTS `tipo_de_examen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_de_examen` (
  `cod_examen` varchar(30) NOT NULL,
  `nombre_examen` varchar(100) NOT NULL,
  `descripcion_examen` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`cod_examen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_de_examen`
--

LOCK TABLES `tipo_de_examen` WRITE;
/*!40000 ALTER TABLE `tipo_de_examen` DISABLE KEYS */;
INSERT INTO `tipo_de_examen` VALUES ('1','xxxx','XXXX'),('Ex00000001','sss','ssss');
/*!40000 ALTER TABLE `tipo_de_examen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_observacion`
--

DROP TABLE IF EXISTS `tipo_observacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_observacion` (
  `cod_observacion` varchar(30) NOT NULL,
  `nom_observaciones` varchar(30) NOT NULL,
  PRIMARY KEY (`cod_observacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_observacion`
--

LOCK TABLES `tipo_observacion` WRITE;
/*!40000 ALTER TABLE `tipo_observacion` DISABLE KEYS */;
INSERT INTO `tipo_observacion` VALUES ('Ox00000002','pureba_2'),('Ox00000003','Prueba_3'),('Ox00000004','Prueba_1'),('Ox00000005','prueba_4'),('Ox00000006','prueba'),('Ox00000007','cardiovacular');
/*!40000 ALTER TABLE `tipo_observacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'sgm'
--

--
-- Dumping routines for database 'sgm'
--
/*!50003 DROP PROCEDURE IF EXISTS `insertar_area_pasantia` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_area_pasantia`(
    IN p_nombre_area VARCHAR(50),
    IN p_descripcion VARCHAR(200),
    IN p_cedula_responsable INT,
    OUT p_cod_generado VARCHAR(10)
)
BEGIN
    DECLARE v_ultimo_numero INT DEFAULT 0;
    DECLARE v_nuevo_codigo VARCHAR(10);
    
    -- Obtener el último número usado en códigos que empiezan con 'AP'
    SELECT IFNULL(MAX(CAST(SUBSTRING(cod_area, 3) AS UNSIGNED)), 0)
    INTO v_ultimo_numero
    FROM areas_pasantias
    WHERE cod_area LIKE 'Ax%';
    
    -- Generar nuevo código (AP001, AP002, etc.)
    SET v_nuevo_codigo = CONCAT('Ax', LPAD(v_ultimo_numero + 1, 8, '0'));
    
    -- Insertar nueva área de pasantía
    INSERT INTO areas_pasantias(
        cod_area,
        nombre_area,
        descripcion,
        cedula_responsable
    ) VALUES (
        v_nuevo_codigo,
        p_nombre_area,
        p_descripcion,
        p_cedula_responsable
    );
    
    -- Devolver el código generado
    SET p_cod_generado = v_nuevo_codigo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insertar_consulta` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_consulta`(
    IN p_fecha DATE,
    IN p_hora VARCHAR(7),
    IN p_consulta TEXT,
    IN p_diagnostico TEXT,
    IN p_tratamiento TEXT,
    IN p_cedula_personal INT,
    IN p_cedula_paciente INT,
    OUT p_cod_generado VARCHAR(30)
)
BEGIN

    DECLARE v_ultimo_numero INT DEFAULT 0;
    DECLARE v_nuevo_codigo VARCHAR(10);
    
	SELECT IFNULL(MAX(CAST(SUBSTRING(cod_consulta, 3) AS UNSIGNED)), 0)
    INTO v_ultimo_numero
    FROM consulta
    WHERE cod_consulta LIKE 'Cx%';
    
     SET v_nuevo_codigo = CONCAT('Cx', LPAD(v_ultimo_numero + 1, 8, '0'));

    
    -- Insertar consulta principal
    INSERT INTO consulta(
        cod_consulta,
        fechaconsulta,
        Horaconsulta,
        consulta,
        diagnostico,
        tratamientos,
        cedula_personal,
        cedula_paciente
    ) VALUES (
        v_nuevo_codigo,
        p_fecha,
        p_hora,
        p_consulta,
        p_diagnostico,
        p_tratamiento,
        p_cedula_personal,
        p_cedula_paciente
    );
    
    -- Las observaciones se insertarán después desde PHP
    SET p_cod_generado = v_nuevo_codigo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insertar_jornada_medica` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_jornada_medica`(
    IN p_fecha_jornada DATE,
    IN p_ubicacion VARCHAR(255),
    IN p_descripcion TEXT,
    IN p_total_pacientes INT,
    IN p_pacientes_masculinos INT,
    IN p_pacientes_femeninos INT,
    IN p_pacientes_embarazadas INT,
    OUT p_cod_generado VARCHAR(30)
)
BEGIN
    DECLARE v_ultimo_numero INT DEFAULT 0;
    DECLARE v_nuevo_codigo VARCHAR(30);
    
    -- Obtener el último número usado en códigos que empiezan con 'JM'
    SELECT IFNULL(MAX(CAST(SUBSTRING(cod_jornada, 3) AS UNSIGNED)), 0)
    INTO v_ultimo_numero
    FROM jornadas_medicas
    WHERE cod_jornada LIKE 'Jx%';
    
    -- Generar nuevo código (JM000001, JM000002, etc.)
    SET v_nuevo_codigo = CONCAT('Jx', LPAD(v_ultimo_numero + 1, 8, '0'));
    
    -- Insertar nueva jornada médica
    INSERT INTO jornadas_medicas(
        cod_jornada,
        fecha_jornada,
        ubicacion,
        descripcion,
        total_pacientes,
        pacientes_masculinos,
        pacientes_femeninos,
        pacientes_embarazadas
    ) VALUES (
        v_nuevo_codigo,
        p_fecha_jornada,
        p_ubicacion,
        p_descripcion,
        p_total_pacientes,
        p_pacientes_masculinos,
        p_pacientes_femeninos,
        p_pacientes_embarazadas
    );
    
    -- Devolver el código generado
    SET p_cod_generado = v_nuevo_codigo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insertar_lote` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_lote`(
    IN p_cantidad INT,
    IN p_fecha_vencimiento DATE,
    IN p_proveedor VARCHAR(50),
    IN p_cod_medicamento VARCHAR(30),
    IN p_cedula_personal INT,
    OUT p_cod_generado VARCHAR(30)
)
BEGIN
    DECLARE v_ultimo_numero INT DEFAULT 0;
    DECLARE v_nuevo_codigo VARCHAR(30);
    
    -- Obtener el último número de lote
    SELECT IFNULL(MAX(CAST(SUBSTRING(cod_lote, 3) AS UNSIGNED)), 0)
    INTO v_ultimo_numero
    FROM lotes
    WHERE cod_lote LIKE 'Lx%';
    
    -- Generar nuevo código (LOT0001, LOT0002, etc.)
    SET v_nuevo_codigo = CONCAT('Lx', LPAD(v_ultimo_numero + 1, 8, '0'));
    
    -- Insertar nuevo lote
    INSERT INTO lotes(
        cod_lote,
        cantidad,
        fecha_vencimiento,
        proveedor,
        cod_medicamento,
        cedula_personal
    ) VALUES (
        v_nuevo_codigo,
        p_cantidad,
        p_fecha_vencimiento,
        p_proveedor,
        p_cod_medicamento,
        p_cedula_personal
    );
    
    -- Devolver el código generado
    SET p_cod_generado = v_nuevo_codigo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insertar_medicamento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_medicamento`(
    IN p_nombre VARCHAR(50),
    IN p_descripcion VARCHAR(200),
    IN p_unidad_medida VARCHAR(20),
    IN p_stock_min INT,
    OUT p_cod_generado VARCHAR(30)
)
BEGIN
    DECLARE v_ultimo_numero INT DEFAULT 0;
    DECLARE v_nuevo_codigo VARCHAR(30);

    SELECT IFNULL(MAX(CAST(SUBSTRING(cod_medicamento, 3) AS UNSIGNED)), 0)
    INTO v_ultimo_numero
    FROM medicamentos
    WHERE cod_medicamento LIKE 'Mx%';

    SET v_nuevo_codigo = CONCAT('Mx', LPAD(v_ultimo_numero + 1, 8, '0'));

    INSERT INTO medicamentos(
        cod_medicamento,
        nombre,
        descripcion,
        unidad_medida,
        stock_min
    ) VALUES (
        v_nuevo_codigo,
        p_nombre,
        p_descripcion,
        p_unidad_medida,
        p_stock_min
    );

    SET p_cod_generado = v_nuevo_codigo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insertar_patologia` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_patologia`(
    IN p_nombre_patologia VARCHAR(100),
    OUT p_cod_generado VARCHAR(30)
)
BEGIN
    DECLARE v_ultimo_numero INT DEFAULT 0;
    DECLARE v_nuevo_codigo VARCHAR(30);
    
    -- Obtener el último número usado en códigos que empiezan con 'Px'
    SELECT IFNULL(MAX(CAST(SUBSTRING(cod_patologia, 3) AS UNSIGNED)), 0)
    INTO v_ultimo_numero
    FROM patologia
    WHERE cod_patologia LIKE 'Px%';
    
    -- Generar nuevo código (Px00000001, Px00000002, etc.)
    SET v_nuevo_codigo = CONCAT('Px', LPAD(v_ultimo_numero + 1, 8, '0'));
    
    -- Insertar nueva patología
    INSERT INTO patologia(
        cod_patologia,
        nombre_patologia
    ) VALUES (
        v_nuevo_codigo,
        p_nombre_patologia
    );
    
    -- Devolver el código generado
    SET p_cod_generado = v_nuevo_codigo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insertar_publicacion_feed` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_publicacion_feed`(
    IN p_contenido VARCHAR(300),
    IN p_imagen TEXT,
    IN p_cedula_personal INT,
    OUT p_cod_generado VARCHAR(30)
)
BEGIN
    DECLARE v_ultimo_numero INT DEFAULT 0;
    DECLARE v_nuevo_codigo VARCHAR(30);
    DECLARE v_fecha_actual DATETIME;
    
    -- Obtener el último número usado en códigos que empiezan con 'Px'
    SELECT IFNULL(MAX(CAST(SUBSTRING(cod_pub, 3) AS UNSIGNED)), 0)
    INTO v_ultimo_numero
    FROM feed
    WHERE cod_pub LIKE 'fx%';
    
    -- Generar nuevo código (Px00000001, Px00000002, etc.)
    SET v_nuevo_codigo = CONCAT('fx', LPAD(v_ultimo_numero + 1, 8, '0'));
    
    -- Obtener fecha y hora actual
    SET v_fecha_actual = NOW();
    
    -- Insertar nueva publicación
    INSERT INTO feed(
        cod_pub,
        fecha,
        contenido,
        imagen,
        cedula_personal
    ) VALUES (
        v_nuevo_codigo,
        v_fecha_actual,
        p_contenido,
        p_imagen,
        p_cedula_personal
    );
    
    -- Devolver el código generado
    SET p_cod_generado = v_nuevo_codigo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insertar_tipo_examen` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_tipo_examen`(
    IN p_nombre_examen VARCHAR(100),
    IN p_descripcion_examen VARCHAR(300),
    OUT p_cod_generado VARCHAR(30)
)
BEGIN
    DECLARE v_ultimo_numero INT DEFAULT 0;
    DECLARE v_nuevo_codigo VARCHAR(30);
    
    -- Obtener el último número usado en códigos que empiezan con 'EX'
    SELECT IFNULL(MAX(CAST(SUBSTRING(cod_examen, 3) AS UNSIGNED)), 0)
    INTO v_ultimo_numero
    FROM tipo_de_examen
    WHERE cod_examen LIKE 'Ex%';
    
    -- Generar nuevo código (EX000001, EX000002, etc.)
    SET v_nuevo_codigo = CONCAT('Ex', LPAD(v_ultimo_numero + 1, 8, '0'));
    
    -- Insertar nuevo tipo de examen
    INSERT INTO tipo_de_examen(
        cod_examen,
        nombre_examen,
        descripcion_examen
    ) VALUES (
        v_nuevo_codigo,
        p_nombre_examen,
        p_descripcion_examen
    );
    
    -- Devolver el código generado
    SET p_cod_generado = v_nuevo_codigo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `registrar_salida_medicamento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `registrar_salida_medicamento`(
    IN p_fecha DATE,
    IN p_hora VARCHAR(7),
    IN p_cedula_personal INT,
    OUT p_cod_salida VARCHAR(30)
)
BEGIN
    DECLARE v_ultimo_numero INT DEFAULT 0;
    DECLARE v_nuevo_codigo VARCHAR(30);
    
    -- Obtener el último número de salida
    SELECT IFNULL(MAX(CAST(SUBSTRING(cod_salida, 3) AS UNSIGNED)), 0)
    INTO v_ultimo_numero
    FROM salida_medicamento
    WHERE cod_salida LIKE 'Sx%';
    
    -- Generar nuevo código (SAL0001, SAL0002, etc.)
    SET v_nuevo_codigo = CONCAT('Sx', LPAD(v_ultimo_numero + 1, 8, '0'));
    
    -- Insertar nueva salida
    INSERT INTO salida_medicamento(
        cod_salida,
        fecha,
        hora,
        cedula_personal
    ) VALUES (
        v_nuevo_codigo,
        p_fecha,
        p_hora,
        p_cedula_personal
    );
    
    -- Devolver el código generado
    SET p_cod_salida = v_nuevo_codigo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_insertar_observacion_simple` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insertar_observacion_simple`(
    IN p_nom_observaciones VARCHAR(30),
    OUT p_cod_generado VARCHAR(10)
)
BEGIN
    DECLARE v_ultimo_numero INT DEFAULT 0;
    DECLARE v_nuevo_codigo VARCHAR(10);
    
    -- Obtener el último número usado
    SELECT IFNULL(MAX(CAST(SUBSTRING(cod_observacion, 3) AS UNSIGNED)), 0)
    INTO v_ultimo_numero
    FROM tipo_observacion
    WHERE cod_observacion LIKE 'Ox%';
    
    -- Generar nuevo código
    SET v_nuevo_codigo = CONCAT('Ox', LPAD(v_ultimo_numero + 1, 8, '0'));
    
    -- Insertar solo si no existe (por si acaso)
    INSERT INTO tipo_observacion(cod_observacion, nom_observaciones)
    SELECT v_nuevo_codigo, p_nom_observaciones
    FROM dual
    WHERE NOT EXISTS (
        SELECT 1 FROM tipo_observacion 
        WHERE cod_observacion = v_nuevo_codigo
    );
    
    -- Devolver el código generado (aunque no se haya insertado)
    SET p_cod_generado = v_nuevo_codigo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-24 11:34:29
