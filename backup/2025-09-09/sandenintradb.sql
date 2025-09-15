-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: localhost    Database: sandenintradb
-- ------------------------------------------------------
-- Server version	8.0.43-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_count` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
INSERT INTO `activities` VALUES (1,'Sanden Fitness Jogging','Fitness Jogging','km',3,'2025-09-09 05:57:26','2025-09-09 05:57:26');
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `challenge_levels`
--

DROP TABLE IF EXISTS `challenge_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `challenge_levels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `activity_id` bigint unsigned NOT NULL,
  `activity_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_number` int NOT NULL,
  `required_value` double(8,2) NOT NULL,
  `team_size` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `challenge_levels_activity_id_foreign` (`activity_id`),
  CONSTRAINT `challenge_levels_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `challenge_levels`
--

LOCK TABLES `challenge_levels` WRITE;
/*!40000 ALTER TABLE `challenge_levels` DISABLE KEYS */;
INSERT INTO `challenge_levels` VALUES (1,1,'Sanden Fitness Jogging',1,24.00,2,'2025-09-09 05:57:26','2025-09-09 05:57:26'),(2,1,'Sanden Fitness Jogging',2,100.00,5,'2025-09-09 05:57:26','2025-09-09 05:57:26'),(3,1,'Sanden Fitness Jogging',3,200.00,10,'2025-09-09 05:57:26','2025-09-09 05:57:26');
/*!40000 ALTER TABLE `challenge_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doc_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upload_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `control_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES (1,'ADMPOL001.pdf','Safety Control Procedure','ADM','uncontrolled','POL','2025-08-18 06:39:55','non_confidential'),(2,'ADMPOL002.pdf','Emergency Preparedness and Response Procedure','ADM','uncontrolled','POL','2025-08-18 06:39:55','non_confidential'),(3,'ADMPOL003.pdf','Security Control Procedure','ADM','uncontrolled','POL','2025-08-18 06:39:55','non_confidential'),(4,'ADMPOL004.pdf','Permit to Work Control Work Instruction','ADM','uncontrolled','POL','2025-08-18 06:39:55','non_confidential'),(5,'ADMPOL005.pdf','Personal Protective Equipment Management','ADM','uncontrolled','POL','2025-08-18 06:39:55','non_confidential'),(6,'ADMPOL006.pdf','Health and Wellness','ADM','uncontrolled','POL','2025-08-18 06:39:55','non_confidential'),(7,'ADMPOL007.pdf','Ergonomic Management','ADM','uncontrolled','POL','2025-08-18 06:39:55','non_confidential'),(8,'ADMPOL008.pdf','Drug Free Work Place','ADM','uncontrolled','POL','2025-08-18 06:39:55','non_confidential'),(9,'ADMPOL009.pdf','Hepatitis B Program','ADM','uncontrolled','POL','2025-08-18 06:39:55','non_confidential'),(10,'ADMPOL010.pdf','PTB Prevention and Control Procedure','ADM','uncontrolled','POL','2025-08-18 06:39:55','non_confidential'),(11,'ADMPOL011.pdf','Pregnancy and Reproductive Health','ADM','uncontrolled','POL','2025-08-18 06:39:55','non_confidential'),(12,'ADMPOL012.pdf','HIV and AIDS Program','ADM','uncontrolled','POL','2025-08-18 06:39:55','non_confidential'),(13,'ADMPOL013.pdf','Breastfeeding Procedure','ADM','uncontrolled','POL','2025-08-18 06:39:55','non_confidential'),(14,'ADMPOL014.pdf','Occupational Health Requirements','ADM','uncontrolled','POL','2025-08-18 06:39:55','non_confidential'),(15,'ADMPOL015.pdf','Domestic Air Travel','ADM','uncontrolled','POL','2025-08-18 06:39:55','non_confidential'),(16,'ADMPOL016.pdf','Accommodation','ADM','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(17,'ADMPOL017.pdf','Shuttle and Vehicle Request Guideline','ADM','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(18,'ADMPOL018.pdf','Driver Authorization','ADM','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(19,'ADMPOL019.pdf','Transportation Reimbursement','ADM','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(20,'ADMPOL020.pdf','Staff House Accommodation','ADM','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(21,'ADMPOL021.pdf','Company-Issued Mobile Device','ADM','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(22,'ADMPOL022.pdf','Company-Issued Mobile Device Upgrade','ADM','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(23,'FINPOL001.pdf','Finance Disbursement Process','FIN','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(24,'FINPOL002.pdf','Petty Cash Fund','FIN','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(25,'FINPOL003.pdf','Cash Advance','FIN','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(26,'FINPOL004.pdf','Asset Management','FIN','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(27,'FINPOL005.pdf','Cash and Check Collection Procedure','FIN','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(28,'FINPOL006.pdf','Credit Policy','FIN','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(29,'FINPOL007.pdf','Promodiser Incentive','FIN','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(30,'FINPOL008.pdf','Material Board Review','FIN','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(31,'FINPOL009.pdf','Sales Discount','FIN','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(32,'FINPOL010.pdf','Inventory Counting','FIN','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(33,'FINPOL011.pdf','Sell Out Incentives for Stores without Promodiser','FIN','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(34,'HRPOL001.pdf','Recruitment, Selection and Employment','HR','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(35,'HRPOL002.pdf','Performance Management','HR','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(36,'HRPOL003.pdf','Promotion','HR','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(37,'HRPOL004.pdf','Payroll Administration','HR','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(38,'HRPOL005.pdf','Meal Allowance and Per Diem','HR','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(39,'HRPOL006.pdf','Retirement Plant','HR','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(40,'MISPOL001.pdf','Network Information','MIS','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(41,'MISPOL002.pdf','System Monitoring','MIS','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(42,'MISPOL003.pdf','Back-up of Data','MIS','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(43,'MISPOL004.pdf','Equipment Issuance, Assignment and Usage','MIS','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(44,'MISPOL005.pdf','Technology Hardware Disposal','MIS','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(45,'MISPOL006.pdf','SAP-ERP','MIS','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(46,'PURPOL001.pdf','Vendor Selection and Accreditation','PUR','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(47,'PURPOL002.pdf','Domestic Procedure','PUR','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(48,'PURPOL003.pdf','Import Procedure','PUR','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(49,'PURPOL004.pdf','Project Code Registration','PUR','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(50,'SCMPOL001.pdf','Procedure on Inbound Shipment','SCM','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(51,'SCMPOL002.pdf','Procedure on Outbound Shipment','SCM','uncontrolled','POL','2025-08-18 06:39:56','non_confidential'),(52,'SCMPOL003.pdf','Sales Order Utilization','SCM','uncontrolled','POL','2025-08-18 06:39:56','non_confidential');
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `agenda` text COLLATE utf8mb4_unicode_ci,
  `pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (4,'Ninoy Aquino Day',NULL,'2025-08-21 00:00:00','2025-08-21 23:59:00','2025-08-07 03:46:23','2025-08-07 03:46:23','Holiday','jeffrey.salagubang.js@sanden-rs.com'),(5,'Sales Training','Sales Training','2025-08-18 00:00:00','2025-08-19 23:59:00','2025-08-07 03:51:03','2025-08-07 03:51:03','Training & Seminars','jeffrey.salagubang.js@sanden-rs.com'),(6,'Sales Training','Sales Training','2025-08-26 00:00:00','2025-08-27 23:59:00','2025-08-07 03:54:04','2025-08-07 03:54:04','Training & Seminars','jeffrey.salagubang.js@sanden-rs.com'),(7,'Sanden 6th Anniversary','Sanden 6th Anniversary','2025-08-22 10:00:00','2025-08-22 13:00:00','2025-08-07 03:55:48','2025-08-07 03:55:48','Events','jeffrey.salagubang.js@sanden-rs.com'),(13,'National Heroes\' Day',NULL,'2025-08-25 13:26:00','2025-08-25 13:26:00','2025-08-07 05:27:21','2025-08-07 05:27:21','Holiday','Euvy.Marable.df@sanden-rs.com');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `features`
--

DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `features_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `features`
--

LOCK TABLES `features` WRITE;
/*!40000 ALTER TABLE `features` DISABLE KEYS */;
INSERT INTO `features` VALUES (13,'features.delete','Delete Features','2025-07-27 21:04:48','2025-07-27 21:04:48'),(18,'features.assign','Feature Assign','2025-07-27 21:41:31','2025-07-27 21:41:31'),(19,'features.update_sftp_sql','Update Policy File','2025-07-27 21:48:55','2025-07-27 21:48:55'),(20,'features.upload_sftp_sql','Upload New Policy','2025-07-27 23:18:28','2025-07-27 23:18:28'),(21,'features.policy.delete','Policy Delete','2025-07-28 17:00:33','2025-07-28 17:00:33'),(22,'form.deposit','Deposit Form','2025-07-28 19:55:46','2025-07-28 19:55:46'),(23,'agenda.create','Create Agenda Calendar','2025-08-07 03:57:51','2025-08-07 03:57:51'),(24,'agenda.delete','Delete Agenda Calendar','2025-08-07 04:01:35','2025-08-07 04:01:35'),(25,'activities.create_view','Activity Create','2025-09-09 06:06:34','2025-09-09 06:06:34');
/*!40000 ALTER TABLE `features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intranet_version`
--

DROP TABLE IF EXISTS `intranet_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `intranet_version` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updates` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_release` date NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intranet_version`
--

LOCK TABLES `intranet_version` WRITE;
/*!40000 ALTER TABLE `intranet_version` DISABLE KEYS */;
INSERT INTO `intranet_version` VALUES (1,'1.0.0.0','Initial release of Sanden Intranet Apps','2025-08-22','Jeffrey Salagubang',NULL,NULL);
/*!40000 ALTER TABLE `intranet_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_tbl`
--

DROP TABLE IF EXISTS `inventory_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `computer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dept_region` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tagged` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tag_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tagged_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `domain_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `antivirus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ms_office` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `processor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hdd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ssd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `memory` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warranty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monitor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mouse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bios_admin_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bios_user_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `donated_disposed` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_tbl`
--

LOCK TABLES `inventory_tbl` WRITE;
/*!40000 ALTER TABLE `inventory_tbl` DISABLE KEYS */;
INSERT INTO `inventory_tbl` VALUES (1,'LT-SCP01-2019','56000','19-May','2019','SERVICE UNIT','','MIS','LENOVO THINKPAD','S5CDGTF','YES','July-27-2023','LT-SCP01-2019','WIN 10','Sanden.NET','ESET','2019','INTEL I7','1 TB','N/A','16 GB','NONE','LENOVO THINKVISION 21\"','LOGITEC WIRELESS ','','','','PREVIOUSLY OWNED BY CHARLES GELOTIN'),(2,'LT-SCP02-2019','N/A','N/A','2019','DISPOSAL','','MIS','LENOVO IDEAPAD 130','MP1HA5EH','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3-7020U','1 TB','N/A','8 GB','1 YEAR','NONE','LOGITEC WIRELESS ','','','','DEFECTIVE (IT SERVICE PULLED OUT)'),(3,'LT-SCP03-2019','36700','19-Oct','2019','SERVICE UNIT','','MIS','HP 14S-CF1057TX','5CG93355MP','YES','July-27-2023','LT-SCP03-2019','WIN 10','Sanden.NET','ESET','2013','INTEL I5','1 TB','N/A','4 GB','NONE','NONE','LOGITEC WIRELESS ','','','','PREVIOUSLY OWNED BY RHIZA CAPATI'),(4,'LT-SCP04-2019','45920','19-Apr','2019','SERVICE UNIT','','FIN','LENOVO THINKPAD','PF1HSL0B','YES','July-27-2023','LT-SCP04-2019','WIN 10','Sanden.NET','ESET','2019','INTEL I5','1 TB','N/A','12 GB','NONE','NONE','LOGITEC WIRELESS ','Success2019','fhd06022021','','PREVIOUSLY OWNED BY FERNAN DICHOSO and MARK DE LEON'),(5,'LT-SCP05-2019','24000','19-Aug','2019','DISPOSAL','','MS','LENOVO IDEAPAD','FADED','NO','N/A','N/A','WIN 10','Sanden.NET','NONE','2013','INTEL I3','1 TB','N/A','4 GB','NONE','NONE','A4TECH','success2019','lenovo','FOR DISPOSAL','PREVIOUSLY OWNED BY ANGELITO DELA PAZ (Defective Keys)'),(6,'LT-SCP06-2019','N/A','N/A','2018','TRANFER OF OWNERSHIP','','MIS','LENOVO IDEAPAD 320','PF0XY0NJ','YES','','LT-SCP04-2019','WIN 10','Sanden.NET','ESET','2013','INTEL I5','500 GB','N/A','6 GB','NONE','NONE','A4TECH','success2019','lenovo','','PREVIOUSLY OWNED BYALVIN CAPUNO'),(7,'LT-SCP07-2019','N/A','N/A','2018','DAVAO TECHNICIAN','','DAV','LENOVO IDEAPAD 320','PF12NVLZ','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5','500 GB','N/A','4 GB','NONE','NONE','NONE','','','','PREVIOUSLY OWNED BY ELLA VILLA'),(8,'LT-SCP08-2019','N/A','N/A','2018','DARYL DELFIN','','DAV','LENOVO THINKPAD','PF-OTH9QY','YES','September-11-2023','LT-SCP08-2019','WIN 10','Sanden.NET','ESET','2019','INTEL I5','1 TB','N/A','4 GB','NONE','NONE','A4TECH','','','','PREVIOUSLY OWNED BY BEN ONE AND MA\'AM EMIRLYN'),(9,'LT-SCP09-2019','N/A','N/A','2017','DISPOSAL','','CC','LENOVO IDEAPAD 300','PF0FGEJ5','YES','September-11-2023','LT-SCP13-2019','WIN 8.1','Sanden.NET','NONE','2013','INTEL I7-6500','1 TB','N/A','4 GB','NONE','NONE','A4TECH','','','FOR DISPOSAL','PREVIOUSLY OWNED BY NEMICIO BALANI AND ADEL BARLAN'),(10,'LT-SCP10-2019','','N/A','2018','DISPOSAL','','SL','TOSHIBA TECRA','3E133598S','','','','WIN 7','Sanden.NET','ESET','2007','INTEL I5-3230','500 GB','N/A','4 GB','NONE','NONE','','','','DISPOSED (SEPTEMBER 2022)','PREVIOUSLY OWNED BY KENVYLOU PALLADO'),(11,'LT-SCP11-2019','N/A','N/A','2018','UNDECIDED','','MS','LENOVO IDEAPAD 320','PF0W3WW7','YES','August-30-2023','LT-SCP11-2019','WIN 10','Sanden.NET','ESET','2013','INTEL I3','500 GB','N/A','4 GB','NONE','NONE','CLIPTECH','success2019','lenovo','','PREVIOUSLY OWNED BY  MA\'AM BABYLYN'),(12,'LT-SCP12-2019','N/A','N/A','2018','DISPOSAL','','CC','DELL VOSTRO','G8FL7H2','','','','WIN 10','Sanden.NET','NONE','2013','INTEL I7','500 GB','N/A','8 GB','NONE','NONE','A4TECH','','','FOR DISPOSAL','PREVIOUSLY OWN BY CLARENCE PANAMBO'),(13,'LT-SCP13-2019','N/A','N/A','2019','DISPOSAL','','MIS','LENOVO IDEAPAD 330','PF17FVBS','YES','September-11-2023','LT-SCP13-2019','WIN 10','Sanden.NET','ESET','2013','INTEL I3','1 TB','N/A','4 GB','NONE','NONE','NONE','','','DISPOSED (SEPTEMBER 2022)','PREVIOUSLY OWNED BY NEMICIO BALANI'),(14,'LT-SCP14-2019','24500','19-Jan','2017','UNDECIDED','','WRTY','LENOVO IDEAPAD 330','PF17FN1C','YES','July-27-2023','LT-SCP14-2019','WIN 10','Sanden.NET','ESET','2013','INTEL I3 -7020U','1 TB','N/A','4 GB','NONE','NONE','A1TECH','success2019','lenovo','','PREVIOUSLY OWNED BY MARK GARCIA'),(15,'LT-SCP15-2019','N/A','N/A','2018','SERVICE UNIT','','MIS','LENOVO IDEAPAD 320','PF0RPLBT','YES','August-30-2023','LT-SCP15-2019','WIN 10','Sanden.NET','ESET','2013','INTEL I3','500 GB','N/A','4 GB','NONE','NONE','LENOVO','sanden123','lenovo','','PREVIOUSLY OWNED BY IAN LLANETA'),(16,'LT-SCP16-2019','N/A','N/A','2018','SERVICE UNIT','','NL','LENOVO IDEAPAD','PF0X7X5Q','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5','1 TB','N/A','6 GB','NONE','NONE','LOGITECH','success@2019','lenovo','','PREVIOUSLY OWNED BY JORDAN GALDONES'),(17,'LT-SCP17-2019','N/A','N/A','2018','DISPOSAL','','SL','HP DYNABOOK','5CD5510CWX','','','','WIN 10','Sanden.NET','NONE','2013','INTEL I5','1 TB','N/A','6 GB','NONE','NONE','LOGITECH','success@2019','lenovo','FOR DISPOSAL','PREVIOUSLY OWNED BY JULIETA ALCOS AND RHEA VILLA'),(18,'LT-SCP18-2019','N/A','N/A','2018','UNDECIDED','','NL','LENOVO IDEAPAD','PF0X7X5Q','','','','WIN 10','Sanden.NET','NONE','2013','INTEL I5','1 TB','N/A','6 GB','NONE','NONE','LOGITECH','success@2019','lenovo','','DEFECTIVE MOUSE PAD & SUBJECT FOR REPLACEMENT NEXT YEAR'),(19,'LT-SCP01-2020','N/A','N/A','2017','SERVICE UNIT','','HQ','LENOVO THINKPAD E480','PF0MC724','','','','WIN 10','Sanden.NET','NONE','2013','INTEL I5','1 TB','N/A','4 GB','NONE','NONE','HP','','','','PREVIOUSLY OWNED BY LARRY CRUZ and HAROLD DELA CRUZ'),(20,'LT-SCP02-2020','N/A','N/A','2016','DONATION','','MIS','TOSHIBA TECRA','2J070512H','','','','WIN 10','Sanden.NET','NONE','2013','INTEL I7','1 TB','N/A','4 GB','NONE','NONE','LOGITEC WIRELESS ','N/A','N/A','','PREVIOUSLY USED BY RAMIL BASCON'),(21,'LT-SCP03-2020','48000','18-Sep','2018','SERVICE UNIT','','MIS','LENOVO THINKPAD E480','SL10Q37361/ PF17XMUL','YES','August-30-2023','LT-SCP03-2020','WIN 10','Sanden.NET','ESET','2013','INTEL CORE I5','1 TB','N/A','8 GB','NONE','NONE','LOGITECH','','','','PREVIOUSLY USED BY JONATHAN LUCES & CASSANDRAE JOICE VILLAVERDE'),(22,'LT-SCP04-2020','N/A','N/A','2017','DONATION','','MIS','LENOVO IDEAPAD 330','FADED','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3-7020U','1 TB','N/A','4 GB','NONE','NONE','NONE','N/A','N/A','( DONATED TO NFSTI)','PREVIOUSLY USED BY CHRISTOPHER CARREON DEFECTIVE SELECTED KEYS (UNABLE TO PUT BIOS LOGIN) AND DANVEL'),(23,'LT-SCP05-2020','24000','19-Aug','2019','SERVICE UNIT','','CL','LENOVO IDEAPAD','MP1HA6S6','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3-7020U','1 TB','N/A','4 GB','NONE','NONE','NONE','success2019','lenovo','','PREVIOUSLY USED BY JOHN MARK SANTIAGO and TONY GONZALES(2ND)'),(24,'LT-SCP06-2020','24000','19-Aug','2019','TRANFER OF OWNERSHIP','','NL','LENOVO IDEAPAD 130','MP1HA9JF','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3-7020U','1 TB','N/A','8 GB','NONE','NONE','A4TECH','N/A','N/A','','PREVIOUSLY USED BY RODNEY CASTRO'),(25,'LT-SCP07-2020','39984','N/A','2018','KAREN MATARLO','','DAV','LENOVO IDEAPAD 320','PF12NMA8','','','','WIN 10','Sanden.NET','NONE','2013','INTEL i5-8250U','2 TB','N/A','4 GB','NONE','NONE','NONE','','','','RESIGNED'),(26,'LT-SCP08-2020','N/A','N/A','2018','SERVICE UNIT','','MIS','LENOVO G40-70','YB01144268','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5-4210U','512 GB','N/A','4 GB','NONE','NONE','NONE','success','lenovo','','PREVIOUSLY USED BY IRICK SALINAS'),(27,'LT-SCP09-2020','N/A','N/A','2018','DISPOSAL','','DAV','DELL P75G','4SJQ5H2','','','','WIN 10','Sanden.NET','NONE','2013','INTEL I7-7500U','500 GB','N/A','8GB','NONE','NONE','BANANA DIGITAL','','','','PREVIOUSLY OWNED BY BENJE OCA'),(28,'LT-SCP10-2020','N/A','N/A','2018','MARIA ADRENEE BATAG','','DAV','LENOVO IDEAPAD 330','PF1J0NER','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3-7020U','1 TB','N/A','4 GB','NONE','NONE','HYTAC','','','','PREVIOUSLY OWNED BY JOAN HEGRIMOSA (DEFECTIVE KEYS)'),(29,'LT-SCP11-2020','N/A','N/A','2018','TRANFER OF OWNERSHIP','','DAV','LENOVO IDEAPAD 320','PF0Z0TRD','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5','500 GB','N/A','4 GB','NONE','NONE','N. LOGIC','','','','PREVIOUSLY OWNED BY ESTHER REQUINTA'),(30,'LT-SCP12-2020','N/A','N/A','2015','TRANFER OF OWNERSHIP','','DAV','HP PROBOOK 430 G2','CND5191H3B','','','','WIN 7','Sanden.NET','ESET','2013','INTEL I5-5200U','500 GB','N/A','4GB','NONE','NONE','NONE','','','','PREVIOUSLY USED BY GERALD GERSALINA'),(31,'LT-SCP13-2020','N/A','N/A','2018','DONATION','','MIS','DELL INSPIRON 15-7559','4Y04KD2','','','','WIN 8.1','Sanden.NET','ESET','2013','INTEL I7','1 TB','N/A','8 GB','NONE','NONE','HP TRAVEL','','','','PREVIOUSLY USED BY EDMAR FERNANDEZ'),(32,'LT-SCP14-2020','24500','19-Jul','2019','DISPOSAL','','CB','LENOVO IDEAPAD','MP1HA94J.','YES','June-22-2023','LT-SCP14-2020','WIN 10','Sanden.NET','NONE','2013','INTEL I3-7020U','1 TB','N/A','8 GB','NONE','NONE','NONE','','','','PREVIOUSLY USED BY ROMERIE LACO NO HDD and RAM'),(33,'LT-SCP15-2020','N/A','N/A','2018','DISPOSAL','','CB','LENOVO IDEAPAD 320','FADED','YES','June-22-2023','LT-SCP15-2020','WIN 10','Sanden.NET','ESET','2013','INTEL I3','500 GB','N/A','4 GB','NONE','NONE','HYTAC','','','','PREVIOUSLY USED BY CHERRY ESTARES NO HDD and RAM'),(34,'LT-SCP16-2020','N/A','N/A','2018','JORDAN GALDONES (Safe Keep)','','CB','LENOVO IDEAPAD','FADED','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5-7200U','1 TB','N/A','6 GB','NONE','NONE','A4TECH','','','','DEFECTIVE KEYBOARD \"P\" PREVIOUSLY USED BY RITCHIE DIA UNA'),(35,'LT-SCP17-2020','N/A','N/A','2018','DISPOSAL','','CB','LENOVO IDEAPAD 310','FPF0M8N9B','','','','WIN 10','Sanden.NET','NONE','2013','INTEL I5-7200U','1 TB','N/A','6 GB','NONE','NONE','NONE','','','','PREVIOUSLY OWNED BYJOYCE REUYAN (BROKEN HINGE) NO HDD and RAM'),(36,'LT-SCP18-2020','N/A','N/A','2018','DISPOSAL','','CB','LENOVO IDEAPAD 320','PF0XJEH3','','','','WIN 10','Sanden.NET','NONE','2013','INTEL I5','1 TB','N/A','4 GB','NONE','NONE','A4TECH','','','DISPOSED','PREVIOUSLY OWNED BY LEVIE LERASAN (DAMAGED BY CEBU TYPHOON)'),(37,'LT-SCP19-2020','N/A','N/A','2018','DISPOSAL','','DAV','LENOVO IDEAPAD 320','PFOTAMPJ','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5','1 TB','N/A','6 GB','NONE','NONE','PROMATE','','','','PREVIOUSLY OWNED BY ABE QUILINGUING NO HDD and RAM'),(38,'LT-SCP20-2020','N/A','N/A','2018','JORDAN GALDONES (Safe Keep)','','CB','LENOVO IDEAPAD 310','PF0MCZ24','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5-7200U','1 TB','N/A','6 GB','NONE','NONE','A4TECH','','','','PREVIOUSLY OWNED BY GLADYS BULAMBOT'),(39,'LT-SCP21-2020','N/A','N/A','2017','JOLLEN BALDECANAS(OLD UNIT) FOR CHECKING','','CB','LENOVO IDEAPAD 320 80XL','PF97528D42','','','','WIN 8.1','Sanden.NET','ESET','2013','INTEL I5-6200U','500 GB','N/A','4 GB','NONE','NONE','A4TECH','','','','OK'),(40,'LT-SCP22-2020','','N/A','2018','SERVICE UNIT','','MS','LENOVO IDEA PAD','','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3 -7020U','1 TB','N/A','4 GB','NONE','NONE','','','','','PREVIOUSLY OWNED BAY CELESTIAL BERAN'),(41,'LT-SCP23-2020','N/A','N/A','2017','SERVICE UNIT','','NL','LENOVO IDEAPAD','PF17FJWN','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3-7020U','1TB','N/A','4 GB','NONE','NONE','NONE','','','','PREVIOUSLY USED BY 1.KANE CYRIL GONZALES  2. NERILYN QUIMSON'),(42,'LT-SCP24-2020','N/A','N/A','2012','FOR CHECKING','','NL','LENOVO THINKPAD EDGE S4330','MPILY3M','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5','1 TB','N/A','4 GB','NONE','NONE','NONE','','','','PREVIOUSLY OWNED BY RHEA VILLAR AND EDGARDO HORTILANO JR'),(43,'LT-SCP25-2020','','N/A','2018','DISPOSAL','','CC','DELL VOSTRO','','','','','','','','','','','','','','','','','','DISPOSED (SEPTEMBER 2022)','PREVIOUSLY OWNED BY NOEL MENDOZA (DISPOSED)'),(44,'LT-SCP26-2020','N/A','N/A','2016','DISPOSAL','','DAV','LENOVO G40-80','PF098BV7','','','','WIN 8.1','Sanden.NET','ESET','2007','INTEL I5-5200U','500 GB','N/A','4 GB','NONE','NONE','NONE','','','FOR DISPOSAL','PREVIOUSLY OWNED BY NUL JAI'),(45,'LT-SCP27-2020','24000','19-Aug','2019','DONATION','','CL','LENOVO IDEAPAD','MP1HAAK8','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3 -7020U','1 TB','N/A','4 GB','NONE','NONE','A4TECH','success2019','lenovo','','PREVIOUSLY OWNED BY MICKO PANADO'),(46,'LT-SCP28-2020','N/A','N/A','2015','DONATION','','NL','HP DYNABOOK','FADED','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5-5200U','1 TB','N/A','4 GB','NONE','NONE','NONE','success@2019','N/A','DONATED TO NFSTI','PREVIOUSLY OWNED BY RYAN GABUYO '),(47,'LT-SCP29-2020','N/A','N/A','2014','DONATION','','MIS','LENOVO IDEAPAD','YB04482037','YES','August-11-2023','LT-SCP29-2020','WIN 7','Sanden.NET','ESET','2013','INTEL I5-4210U','500GB','N/A','4 GB','NONE','NONE','A4TECH','','','FOR DONATION','PREVIOUSLY USED BY LOIDA BANTIGUE'),(48,'LT-SCP30-2020','24000','19-Aug','2019','TRANFER OF OWNERSHIP','','HQ','LENOVO IDEAPAD 130','MP1HA7LA','YES','August-11-2023','LT-SCP30-2020','WIN 10','Sanden.NET','ESET','2013','INTEL I3 -7020U','1 TB','N/A','8 GB ','NONE','NONE','A4TECH','','','','PREVIOUSLY USED BY RICKY SALAMANTE'),(49,'LT-SCP31-2020','N/A','N/A','2018','SERVICE UNIT','','MIS','DELL VOSTRO','G8FT7H2','YES','August-11-2023','LT-SCP31-2020','WIN 10','Sanden.NET','NONE','2013','INTEL I7','1 TB','N/A','8 GB','NONE','NONE','A4TECH','sanden123','dell','','PREVIOUSLY USED BY CHRISTIAN ALCANTARA'),(50,'LT-SCP32-2020','N/A','N/A','-','OSAMU IKEDA','','EXEC','TOHSIBA','6G084152H','YES','June-9-2023','LT-SCP32-2020','WIN 10','Sanden.NET','ESET','2019','INTEL I5','125 GB','N/A','4 GB','NONE','NONE','NONE','','','','OK'),(51,'LT-SCP33-2020','45920','19-Apr','2019','SERVICE UNIT','','SCM','LENOVO THINKPAD E480','PF1HSEU6','YES','July-27-2023','LT-SCP33-2020','WIN 10','Sanden.NET','ESET','2019','INTEL I5','1 TB','N/A','8 GB','NONE','NONE','NONE','','','','PREVIOUSLY USED BY JAY AR REMARIM'),(52,'LT-SCP34-2020','N/A','N/A','2015','DISPOSAL','','WRTY','LENOVO Z40-70','YB06936860','WIN 7','Sanden.NET','NONE','2007','INTEL I5-4210U','500 GB','N/A','4 GB','NONE','NONE','NONE','N/A','N/A','JOANA PABILLO OLD UNIT','','','FOR DISPOSAL','PREVIOUSLY USED BY JOANA PABILLO'),(53,'LT-SCP35-2020','37500','20-Aug','2020','SERVICE UNIT','','MIS','LENOVO IDEAPAD 3','PF29BZYE','YES','June-9-2023','LT-SCP35-2020','WIN 10','Sanden.NET','ESET','2013','INTEL I5','1TB','256GB','4GB','NONE','NONE','TRAVEL WHEEL MOUSE','','','','PREVIOUSLY USED BY Alvin Joven'),(54,'LT-SCP36-2020','37500','20-Aug','2020','DISPOSAL','','CC','LENOVO IDEAPAD 3','PF29C8JZ','YES','June-9-2023','LT-SCP36-2020','WIN 10','Sanden.NET','ESET','2013','INTEL I5','1TB','256GB','8GB','NONE','NONE','A4TECH','sanden123','lenovo','','PREVIOUSLY USED BY JOLINA GERMINA AND MIKHU MAGBITANG AND RENNIEL CAPUSO'),(55,'LT-SCP37-2020','55000','20-Jun','2020','BENEFIT','','EXEC','DELL INSPIRON 15 5000','9NN8KT2','','','','WIN 10','Sanden.NET','ESET','2019','INTEL I7','1 TB','128 GB','8 GB','NONE','NONE','LOGITECH WIRELESS','','','DUE TO RETRENCH','PREVIOUSLY USED BY RAMIL BASCON'),(56,'LT-SCP38-2020','55000','20-Jun','2020','TRANFER OF OWNERSHIP','','WTY','DELL INSPIRON 15 5000','9SN8KT2','YES','June-9-2023','LT-SCP38-2020','WIN 10','Sanden.NET','ESET','2019','INTEL I7','1 TB','128 GB','8 GB','NONE','NONE','FROM OLD LAPTOP','success2019','dell','','PREVIOUSLY USED BY JULIUS APIGO'),(57,'LT-SCP39-2020','55000','20-Jun','2020','EUVY ANGELO MARABLE','','MIS','DELL INSPIRON 15 5000','CPN8KT2','YES','June-9-2023','LT-SCP39-2020','WIN 10','Sanden.NET','ESET','2019','INTEL I7-8565U','1 TB','128 GB','12 GB','NONE','LENOVO THINKVISION 21\"','LENOVO','sanden123','dell','','SWAP LAPTOP TO DESKTOP AND REPLACE YCA DESKTOP WITH EUVY DESKTOP'),(58,'LT-SCP40-2020','N/A','N/A','2018','SERVICE UNIT','','MIS','LENOVO IDEAPAD 320 80XL','PF97528D42','YES','June-13-2023','LT-SCP40-2020','WIN 10','Sanden.NET','ESET','2013','INTEL I7','2 TB','N/A','8 GB','NONE','NONE','NONE','N/A','N/A','','PREVIOUSLY USED BY JULIUS APIGO'),(59,'LT-SCP41-2020','','','2015','DISPOSAL','','MS','LENOVO  Z40-70','YB06851224','WIN','Sanden.NET','ESET','WIN 10','INTEL I5','500 GB','N/A','4  GB','NONE','NONE','NONE','FOR REPLACEMENT BATTERY','','','','','FOR DISPOSAL','PREVIOUSLY USED BY ROWEL JUMAQUIO'),(60,'LT-SCP42-2020','55000','20-Jun','2020','UNDECIDED','','CC','DELL INSPIRON 15 5000','SNN8KT2','YES','June-13-2023','LT-SCP42-2020','WIN 10','Sanden.NET','NONE','2019','INTEL I7','1 TB','128 GB','8 GB','NONE','NONE',' NONE','sanden123','dell','','PREVIOUSLY USED BY ALMAR MARUGGAY'),(61,'LT-SCP43-2020','N/A','N/A','2020','DISPOSAL','','WRTY','LENOVO THINKPAD','PF1LRMJU','','','','WIN 10','Sanden.NET','NONE','2013','INTEL I7-7500U','1 TB','N/A','8 GB','NONE','NONE','A4TECH','success2019','lenovo','FOR DISPOSAL','PREVIOUSLY USED BY NOEL BUSTAMANTE'),(62,'LT-SCP44-2020','N/A','N/A','2010','DONATION','','CL','HP PROBOOK 430','1588-3003','','','','WIN 7','Sanden.NET','NONE','2013','INTEL I5','500 GB','N/A','4 GB','NONE','NONE','NONE','','','DONATED TO NFSTI','PREVIOUSLY USED BY MARK DELOS REYES'),(63,'LT-SCP45-2020','N/A','N/A','2018','SERVICE UNIT','','MIS','LENOVO IDEAPAD 80XL','PF0ULKQG','','June-13-2023','LT-SCP45-2020','WIN 10','Sanden.NET','NONE','2013','INTEL I7-7500','1 TB','N/A','4 GB','NONE','NONE','A1TECH','N/A','N/A','','PREVIOUSLY USED BY CELESTIAL BERAN'),(64,'LT-SCP46-2020','N/A','N/A','2018','SERVICE UNIT','','CC','DELL VOSTRO','GL2W7H2','YES','June-13-2023','LT-SCP46-2020','WIN 10','Sanden.NET','ESET','2013','INTEL I7','500 GB','N/A','8 GB','NONE','LENOVO THINKVISION 18\"','NONE','','','','PREVIOUSLY USED BY ROBERT ALPAJARO and ADEL BARLAN(2nd)'),(65,'LT-SCP47-2020','N/A','N/A','2017','DONATION','','MS','LENOVO IDEAPAD 330','PF17NAUS','YES','August-30-2023','LT-SCP47-2020','WIN 10','Sanden.NET','ESET','2013','INTEL I3','1 TB','N/A','4 GB','NONE','NONE','TRAVEL WHEEL MOUSE','success2019','lenovo','','PREVIOUSLY OWNED BY KENVYLOU'),(66,'LT-SCP48-2020','37913','20-Sep','2020','TRANFER OF OWNERSHIP','','WTY','LENOVO IDEAPAD 3','PF2DA0CQ','YES','June-8-2023','LT-SCP48-2020','WIN 10','Sanden.NET','ESET','2013','INTEL I5','1 TB','125 GB','8 GB','NONE','NONE','LENOVO','sanden123','lenovo','','PREVIOUSLY OWNED BY JOANA PABILLO'),(67,'LT-SCP49-2020','37913','20-Sep','2020','TRANFER OF OWNERSHIP','','FIN','LENOVO IDEAPAD 3','','YES','June-8-2023','LT-SCP49-2020','WIN 10','Sanden.NET','ESET','2013','INTEL I5','1 TB','125 GB','12 GB','NONE','NONE','TRAVEL WHEEL MOUSE','sanden123','lenovo','','PREVIOUSLY USED BY MAICA HERNANDEZ & JOLINA GERMINA'),(68,'LT-SCP50-2020','37913','20-Sep','2020','MONETTE SALAZAR','','FIN','LENOVO IDEAPAD 3','PF2D7VAS','YES','June-13-2023','LT-SCP50-2020','WIN 10','Sanden.NET','ESET','2013','INTEL I5','1 TB','125 GB','8 GB','NONE','NONE','TRAVEL WHEEL MOUSE','sanden123','lenovo','','OK'),(69,'LT-SCP51-2020','37913','20-Sep','2020','TRANFER OF OWNERSHIP','','MS','LENOVO IDEAPAD 3','PF2D91S2','YES','June-13-2023','LT-SCP51-2020','WIN 10','Sanden.NET','ESET','2013','INTEL I5','1 TB','125 GB','8 GB','NONE','NONE','NONE','','','','PREVIOUSLY USED BY  JULIETA ALCOS'),(70,'LT-SCP52-2020','24000','20-Sep','2020','DISPOSAL','','MIS','LENOVO IDEAPAD 3','PF2D9QMD','YES','June-9-2023','LT-SCP52-2020','WIN 10','Sanden.NET','ESET','2013','INTEL I5','1 TB','125 GB','8 GB','NONE','NONE','TRAVEL WHEEL MOUSE','','','','PREVIOUSLY USED BY  KEAM VELOSO'),(71,'LT-SCP53-2020','44166','20-Dec','2020','JUN PEROCHO','','DAV','LENOVO IDEAPAD 3','PF2913BS','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5-1035G4','1 TB','125 GB','4 GB','NONE','NONE','A4TECH','','','','OK'),(72,'LT-SCP54-2020','44166','20-Dec','2020','NICOLAI ASISTIDO','','DAV','LENOVO IDEAPAD 3','PF293RHE','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5-1035G4','1 TB','125 GB','4GB ','NONE','NONE','A4TECH','','','','PREVIOUSLY USED BY JUMAR JAMINDANG'),(73,'LT-SCP55-2020','44166','20-Dec','2020','PEARL PACILAN','','DAV','LENOVO IDEAPAD 3','PF2934MB','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5-1035G4','1 TB','125 GB','4GB ','NONE','NONE','A4TECH','','','','PREVIOUSLY USED BY NUL JAI'),(74,'LT-SCP01-2021','36211','FEBUARY 2021','2021','NEMESIS GODOY','','FIN','LENOVO IDEAPAD 3 (81WE)','PF1WWC7W','YES','June-8-2023','LT-SCP01-2021','WIN 10','Sanden.NET','ESET','2013','INTEL I5-1035G1','1 TB','256 GB','4GB ','1 YEAR','NONE','THINKPAD','sanden123','lenovo','','PREVIOUSLY USED BY KLARRISSE LANTICAN'),(75,'LT-SCP02-2021','36211','FEBUARY 2021','2021','SERVICE UNIT','','MIS','LENOVO IDEAPAD 3 (81WE)','PF1WY11H','YES','June-8-2023','LT-SCP02-2021','WIN 10','Sanden.NET','ESET','2013','INTEL I5-1035G1','1 TB','256 GB','4GB ','1 YEAR','NONE','NONE','success2019','lenovo','','PREVIOUSLY USED BY JOSEF MAMARADLO AND GERRYCA JOVES'),(76,'LT-SCP03-2021','36211','FEBUARY 2021','2021','JOSUAH REPOMANTA','','CC','LENOVO IDEAPAD 3 (81WE)','PF1WX1HL','YES','June-8-2023','LT-SCP03-2021','WIN 10','Sanden.NET','ESET','2013','INTEL I5-1035G1','1 TB','256 GB','4GB ','1 YEAR','NONE','NONE','sanden123','lenovo','','PREVIOUSLY USED BY ARVIN SAUNAR'),(77,'LT-SCP04-2021','36211','21-Mar','2021','RJ KENNETH BATAYON','','FIN','LENOVO IDEAPAD 3 (81WE)','PF1WXAKV','YES','June-8-2023','LT-SCP04-2021','WIN 10','Sanden.NET','ESET','2013','INTEL I5-1035G1','1 TB','256 GB','8GB ','1 YEAR','NONE','NONE','','','','PREVIOUSLY USER BY JOHN LOUIE GAVINO'),(78,'LT-SCP05-2021','37913','21-Mar','2021','JOYCE ANNE DELA CRUZ','','CL','LENOVO IDEAPAD 3 (81WE)','PF1WY393','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5-1035G1','1 TB','256 GB','8GB','1 YEAR','NONE','NONE','success2019','lenovo','','OK'),(79,'LT-SCP06-2021','37913','21-Mar','2021','JOHN CHAVEZ','','CB','LENOVO IDEAPAD 3 (81WE)','PF1WEEDV','YES','June-8-2023','LT-SCP06-2021','WIN 10','Sanden.NET','ESET','2013','INTEL I5-1035G1','1 TB','256 GB','8GB','1 YEAR','NONE','NONE','','','','PREVIOUSLY USED BY NICK FLORES'),(80,'LT-SCP07-2021','36211','21-Mar','2021','SABRINA MOSQUERA','','SCM','LENOVO IDEAPAD 3 (81WE)','PF1WWG1D','YES','June-8-2023','LT-SCP07-2021','WIN 10','Sanden.NET','ESET','2013','INTEL I5-1035G1','1 TB','256 GB','8GB','1 YEAR','NONE','NONE','sanden123','lenovo','','OK'),(81,'LT-SCP08-2021','45570','21-Mar','2021','LARRY CRUZ','','EXEC','DELL INSPIRON 3501','D12TGB3','YES','June-8-2023','LT-SCP08-2021','WIN 10','Sanden.NET','ESET','2013','INTEL I7-116567','1 TB','512 GB','8GB','1 YEAR','NONE','NONE','','','','OK'),(82,'LT-SCP09-2021','37913','21-Mar','2021','NEIL OLIVERA','','HRAD','LENOVO IDEAPAD 3 (81WE)','PF1WWNT6','YES','June-9-2023','LT-SCP09-2021','WIN 10','Sanden.NET','ESET','2013','INTEL I5-1035G1','1 TB','256 GB','8GB','1 YEAR','NONE','NONE','sanden123','lenovo','','PREVOUSLY USED BY CYRELLE MERCADO'),(83,'LT-SCP10-2021','37913','21-Mar','2021','RHEA VILLAR','','MS','LENOVO IDEAPAD 3 (81WE)','PF1WXNVM','YES','June-8-2023','LT-SCP10-2021','WIN 10','Sanden.NET','ESET','2013','INTEL I5-1035G1','1 TB','256 GB','8GB','1 YEAR','NONE','NONE','sanden123','lenovo','','OK'),(84,'LT-SCP11-2021','37913','21-Mar','2021','KANE CYRIL GONZALES','','HQ','LENOVO IDEAPAD 3 (81WE)','PF1WYFNM','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5-1035G1','1 TB','256 GB','8GB','1 YEAR','NONE','NONE','','','','PREVOUSLY USED BY KANE CYRIL GONZALES'),(85,'LT-SCP12-2021','45570','21-Mar','2021','BENEFIT','','PULLEDOUT','DELL INSPIRON 3501','52RSGB3','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I7-116567','1 TB','256 GB','8GB','1 YEAR','NONE','NONE','success2019','dell','DUE TO RETRENCH','PREVOUSLY USED BY TANYA TICZON'),(86,'LT-SCP13-2021','37913','21-Mar','2021','MARY JOY M. CINCO','','FIN','LENOVO IDEAPAD 3 (81WE)','PF1WWLMX','YES','June-8-2023','LT-SCP13-2021','WIN 10','Sanden.NET','ESET','2013','INTEL I5-1035G1','1 TB','512GB','8GB','1 YEAR','NONE','NONE','','','','OK'),(87,'LT-SCP14-2021','45570','21-Mar','2021','EDD BRANDON MALABANAN','','CC','DELL INSPIRON 3501','7GZSGB3','YES','June-8-2023','LT-SCP14-2021','WIN 10','Sanden.NET','ESET','2013','INTEL I7-116567','1TB','512 GB','8GB','1 YEAR','NONE','NONE','sanden123','dell','','PREVIOUSLY USED BY ROBERT ALPAJARO'),(88,'LT-SCP15-2021','45570','21-Mar','2021','DANICA ANIDO','','LOG','LENOVO IDEAPAD 3 (81WE)','PF1WWT04','YES','June-8-2023','LT-SCP15-2021','WIN 10','Sanden.NET','ESET','2013','INTEL I5-1035G1','1 TB','256GB','8GB','1 YEAR','NONE','NONE','','','','OK'),(89,'LT-SCP16-2021','37913','30-Apr-21','2021','RONALYN PULUTAN','','LOG','LENOVO IDEAPAD 3 (81WE)','PFWW96H','YES','June-8-2023','LT-SCP16-2021','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1035G1','1 TB','256GB','8GB','1 YEAR','NONE','NONE','','','','OK'),(90,'LT-SCP17-2021','37913','30-Apr-21','2021','MARK DELOS REYES','','HQ','LENOVO IDEAPAD 3 (81WE)','PF1WXLX2','YES','August-11-2023','LT-SCP17-2021','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1035G1','1 TB','256GB','8GB','1 YEAR','NONE','NONE','','','','OK'),(91,'LT-SCP18-2021','37913','30-Apr-21','2021','BILLY JOEL MANALO','','CC','LENOVO IDEAPAD 3 (81WE)','PF1WWVN8','YES','June-8-2023','LT-SCP18-2021','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1035G1','1 TB','256GB','8GB','1 YEAR','NONE','NONE','','','','OK'),(92,'LT-SCP19-2021','37913','30-Apr-21','2021','DISPOSAL','','CL','LENOVO IDEAPAD 3 (81WE)','PF1WX11P','','','','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1035G1','1 TB','256GB','8GB','1 YEAR','NONE','NONE','','','','PREVIOUSLY USED BY JON EDWARD ALHAMBRA'),(93,'LT-SCP20-2021','37913','30-Apr-21','2021','LUEL MONDEJAR','','CB','HP NOTEBOOK','5CG1034061','YES','June-22-2023','LT-SCP20-2021','WIN 10','Sanden.NET','ESET','2019','INTEL I3-10110U','1 TB','128GB','4GB','1 YEAR','NONE','NONE','','','','PREVIOUSLY USED BY JAN EARL FRANCISCO'),(94,'LT-SCP21-2021','37913','30-Apr-21','2021','ANGELIE POSTRERO','','PUR','LENOVO IDEAPAD 3 (81WE)','PF1WY88Z','YES','June-8-2023','LT-SCP21-2021','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1035G1','1 TB','256GB','8GB','1 YEAR','NONE','NONE','','','','PREVIOUSLY USED BY (1ST) ROWEL JUMAQUIO AND (2ND) MARIEL JAVIER'),(95,'LT-SCP22-2021','37913','30-Apr-21','2021','LEVIE LERASAN','','CB','HP 14S-CF2xxx','5CG1033K07','','','','WIN 10','','','2019','','','','','','','','','','','PREVIOUSLY USED BY MARK ANTHONY SUSON'),(96,'LT-SCP23-2021','26400','10-Jun-21','2021','JAY MEDELLIN','','CB','HP NOTEBOOK','5CG1032WKN','YES','Sept-3-2024','LT-SCP23-2021','WIN 10','Sanden.NET','ESET','2019','INTEL I3-10110U','1 TB','128GB','4GB','1 YEAR','NONE','NONE','','','','PREVIOUSLY USED BY JOYCE REUYAN'),(97,'LT-SCP24-2021','61000','22-Oct-19','2021','ALMAR MARUGGAY','','CC','DELL INSPIRON 15 5500','4KSDGG3','','','','WIN 10','Sanden.NET','ESET','2019','INTEL I7-11370H','NONE','512GB','8GB','1 YEAR','NONE','NONE','Success2019','dell','','OK'),(98,'LT-SCP25-2021','61000','22-Oct-19','2021','CHRISTIAN ALCANTARA','','CC','DELL INSPIRON 15 5500','1KSDGG3','YES','September-11-2023','LT-SCP25-2021','WIN 10','Sanden.NET','ESET','2019','INTEL I7-11370H','NONE','512GB','8GB','1 YEAR','NONE','NONE','Success2019','dell','','OK'),(99,'LT-SCP26-2021','','22-Oct-19','2021','CLARENCE PANAMBO','','CC','DELL INSPIRON 15 5500','8B2BPH3','YES','September-11-2023','LT-SCP26-2021','WIN 10','Sanden.NET','ESET','2019','INTEL I7-11370H','NONE','512GB','8GB','1 YEAR','NONE','NONE','Success2019','dell','','OK'),(100,'LT-SCP01-2022','39281','22-Jan','2022','EMIRLYN PALOMILLO','','CC','LENOVO IDEAPAD 3 (81WE)','PF32YP20','YES','August-11-2023','LT-SCP01-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','','','','NEW'),(101,'LT-SCP02-2022','39281','22-Jan','2022','MART JOSHUA LANTICAN','','CC','LENOVO IDEAPAD 3 (81WE)','PF32VRMT','YES','August-11-2023','LT-SCP02-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','Success2019','dell','','NEW'),(102,'LT-SCP03-2022','39281','22-Jan','2022','SERVICE UNIT','','CC','LENOVO IDEAPAD 3 (81WE)','PF32VG0T','YES','August-11-2023','LT-SCP03-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','','','','PREVIOUSLY USED BY JADEENREV MONTANO'),(103,'LT-SCP04-2022','39281','22-Mar','2022','JAMAICA BUCU','','CL','LENOVO IDEAPAD 3 (81WE)','PF3F9BSB','','','','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','success2019','lenovo','','PREVIOUSLY USED BY BONIFACIO HERAMIS'),(104,'LT-SCP05-2022','39281','22-Mar','2022','WILLIAM LLOSALA','','MS','LENOVO IDEAPAD 3 (81WE)','PF3FMAFN','','','','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','success2019','lenovo','','NEW'),(105,'LT-SCP06-2022','69000','22-Mar','2022','FERNAN DICHOSO','','FIN','DELL INSPIRON 5 SERIES',' ','','','','WIN 10','Sanden.NET','NONE','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','','','','NEW'),(106,'LT-SCP07-2022','','22-Mar','2022','ROBERT ALPAJARO','','CC','DELL INSPIRON','YX03ENYY','YES','August-11-2023','LT-SCP07-2022','WIN 10','Sanden.NET','ESET','2013','INTEL CELERON?','NONE','512GB','8GB','1 YEAR','NONE','NONE','success2019','lenovo','','NEW'),(107,'LT-SCP08-2022','','22-Mar','2022','VAZZIM  SORIANO','','WTY','LENOVO IDEAPAD 3 (81WE)','PF3F6ENM','YES','July-27-2023','LT-SCP08-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','success2019','lenovo','','PREVIOUSLY USED BY JEFFERSON CASTULO'),(108,'LT-SCP09-2022','','22-Mar','2022','NELSON CUBIO','','HRAD','LENOVO IDEAPAD 3 (81WE)','PF3FLZSP','YES','July-27-2023','LT-SCP09-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','success2019','lenovo','','NEW'),(109,'LT-SCP10-2022','','22-Mar','2022','DEN MARK AMURAO','','PUR','LENOVO IDEAPAD 3 (81WE)','PF3FL99T','YES','July-27-2023','LT-SCP10-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','success2019','lenovo','','NEW'),(110,'LT-SCP11-2022','','22-Mar','2022','ELLA VILLA(new)','','DAV','','','','','','WIN 10','Sanden.NET','ESET','2019','','NONE','','8GB','1 YEAR','NONE','NONE','','','','NEW'),(111,'LT-SCP12-2022','','22-Mar','2022','HERSHEY NIKKI JOLLEN SAFE KEEP FOR CHEKCING','','CB','LENOVO IDEAPAD 3 (81WE)','PF39P7WL','','','','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','','','','NEW'),(112,'LT-SCP13-2022','','APRIL','2022','JORDAN GALDONES','','CB','LENOVO IDEAPAD 3 (81WE)','PF3G8DLK','YES','June-22-2023','LT-SCP13-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','1TB','512GB','8GB','1 YEAR','NONE','NONE','success2019','lenovo','','NEW'),(113,'LT-SCP14-2022','','APRIL','2022','RYAN GABUYO','','NL','LENOVO IDEAPAD 3 (81WE)','PF3HLXF1','','','','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','1TB','512GB','8GB','1 YEAR','NONE','NONE','success2019','lenovo','','NEW'),(114,'LT-SCP15-2022','','APRIL','2022','LOIDA BANTIGUE','','HQ','LENOVO IDEAPAD 3 (81WE)','','YES','September-11-2023','LT-SCP15-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','1TB','512GB','8GB','1 YEAR','NONE','NONE','success2019','lenovo','','NEW'),(115,'LT-SCP16-2022','','APRIL','2022','ARDIE SOLIS','','CB','LENOVO IDEAPAD 3 (81WE)','','YES','June-22-2023','LT-SCP16-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','','','','PREVIOUSLY USED BY ROMERIE LACO'),(116,'LT-SCP17-2022','','MAY','2022','NEMICIO A.BALANI','','CC','LENOVO IDEAPAD 3 (81WE)','PF3EFYRL','','','','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','success2019','lenovo','','NEW'),(117,'LT-SCP18-2022','','MAY','2022','MERRIE ANN PUNDANO','','HRAD','LENOVO IDEAPAD 3 (81WE)','PF3EG5KB','YES','July-27-2023','LT-SCP18-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','success2019','lenovo','','PREVIOUSLY USED BY BABYLYN MUNLAWIN'),(118,'LT-SCP19-2022','','MAY','2022','CELESTIAL BERAN','','MS','LENOVO IDEAPAD 3 (81WE)','PF3HL2H2','YES','August-30-2023','LT-SCP21-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','','','','NEW'),(119,'LT-SCP20-2022','','MAY','2022','RUSSEL BLAKE LEYSON','','MIS','LENOVO IDEAPAD 3 (81WE)','PF3EG5LH','YES','July-27-2023','LT-SCP20-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','success2019','lenovo','','PREVIOUSLY USED BY KENVYLOU AND NOEL BUSTAMANTE AND EDISON MIRANDA (3rd:JOHN BIENJO MAILOM)'),(120,'LT-SCP21-2022','','MAY','2022','IAN LLANETA','','MS','LENOVO IDEAPAD 3 (81WE)','PF3EG7VB','YES','August-30-2023','LT-SCP21-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','success2019','lenovo','','NEW'),(121,'LT-SCP22-2022','','JULY','2022','MELCON SANTANDER','','CB','HP LAPTOP 15S','','','','','WIN 10','Sanden.NET','','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','','','','PREVIOUSLY USED BY JOLLEN BALDECANAS'),(122,'LT-SCP23-2022','','JULY','2022','SERVICE UNIT','','CB','HP LAPTOP 15S','','','','','WIN 10','Sanden.NET','','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','','','','PREVIOUSLY USED BY GLADYS BULAMBOT'),(123,'LT-SCP24-2022','49900','22-Dec','2022','MARK GARCIA','','WTY','DELL INSPIRON 15 5510','52SJMG3','YES','August-30-2023','LT-SCP24-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','Success2019','dell','','NEW'),(124,'LT-SCP25-2022','49900','22-Dec','2022','ROMMEL UMANDAP','','FIN','DELL INSPIRON 15 5510','30SJMG3','YES','August-30-2023','LT-SCP25-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','Success2019','dell','','PREVIOUSLY USED BY MARK DE LEON'),(125,'LT-SCP26-2022','49900','22-Dec','2022','VINSIE LAVENA','','FIN','DELL INSPIRON 15 5510','11SJMG3','YES','August-30-2023','LT-SCP26-2022','WIN 10','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','Success2019','dell','','PREVIOUSLY USED BY MIKHU MAGBITANG'),(126,'LT-SCP01-2023','49900','23-Jan','2023','ALVIN JOVEN','','LOG','DELL INSPIRON 15 5510','2CRJMG3','YES','August-30-2023','LT-SCP01-2023','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','Success2019','dell','','NEW'),(127,'LT-SCP02-2023','49900','23-Jan','2023','ADEL BARLAN','','CC','DELL INSPIRON 15 5620','38XDFS3','','','','WIN 11','Sanden.NET','NONE','2019','INTEL I5-1240P','NONE','512GB','8GB','1 YEAR','NONE','NONE','Success@2019','dell','','NEW'),(128,'LT-SCP01-2024','35640','24-Mar','2024','ALVIN CAPUNO','','NL','DELL INSPIRON 15 3511','DLFMRK3','','','','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','16Gb','1 YEAR','NONE','NONE','Success@2019','dell','',''),(129,'LT-SCP02-2024','35640','24-Mar','2024','RENNIEL CAPUSO','','CC','DELL INSPIRON 15 3511','DYSKRK3','YES','March-15-2024','LT-SCP02-2024','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','16Gb','1 YEAR','NONE','NONE','Success@2019','dell','',''),(130,'LT-SCP03-2024','62395','24-Apr','2024','KEAM VELOSO','','MIS','HP VICTUS 15-FA0180TX','5CD3220ZJH','YES','April-29-2024','LT-SCP03-2024','WIN 11','Sanden.NET','ESET','2013','INTEL i5-12450H','NONE','512GB','32GB','1 YEAR','NONE','NONE','','','',''),(131,'LT-SCP04-2024','59995','24-May','2024','FINANCE MANAGER','','FIN','DELL INSPIRON 5430','4DL6NX3','YES','','','WIN 11','Sanden.NET','ESET','2019','INTEL i7-1360P','NONE','512GB','','1 YEAR','NONE','NONE','Success@2019','dell','',''),(132,'LT-SCP05-2024','35640','24-May','2024','MARIEL JUSTINE REYES','','FIN','DELL INSPIRON 15 3511','3N6WRK3','YES','May-17-2024','LT-SCP05-2024','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','16Gb','1 YEAR','NONE','NONE','Success@2019','dell','',''),(133,'LT-SCP06-2024','33990','24-May','2024','MARIA ESTHER REQUINTA','','DAV','DELL INSPIRON 15 3511','64K84L3','','','','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','','','',''),(134,'LT-SCP07-2024','33990','24-May','2024','GERALD GERSALINA','','DAV','DELL INSPIRON 15 3511','F5K84L3','','','','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','8GB','1 YEAR','NONE','NONE','','','',''),(135,'LT-SCP08-2024','35640','24-May','2024','MARK JOSEPH ALGIRE','','HRAD','DELL INSPIRON 15 3511','7TGH4L3','YES','May-27-2024','LT-SCP08-2024','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','16Gb','1 YEAR','NONE','NONE','Success@2019','dell','','PREVIOUSLY USED BY PATRICK REYES'),(136,'LT-SCP09-2024','35640','24-May','2024','BEA PATRICIA BRIONES','','CC','DELL INSPIRON 15 3511','HNRWRK3','YES','May-28-2024','LT-SCP09-2024','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','16Gb','1 YEAR','NONE','NONE','Success@2019','dell','',''),(137,'LT-SCP10-2024','35640','24-May','2024','RODNEY CASTRO','','CL','DELL INSPIRON 15 3511','C43YRK3','YES','July-08-2024','LT-SCP08-2024','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','16Gb','1 YEAR','NONE','NONE','Success@2019','dell','',''),(138,'LT-SCP11-2024','35640','24-May','2024','MARY ROSE ILAGAN','','CL','DELL INSPIRON 15 3511','7ZHYRK3','YES','July-09-2024','LT-SCP09-2024','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','16Gb','1 YEAR','NONE','NONE','Success@2019','dell','',''),(139,'LT-SCP12-2024','35640','24-May','2024','TONY GONZALES','','CL','DELL INSPIRON 15 3511','HTMZRK3','YES','July-10-2024','LT-SCP10-2024','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','16Gb','1 YEAR','NONE','NONE','Success@2019','dell','',''),(140,'LT-SCP13-2024','','','2024','BENJE OCA','','','','','','','','','','','','','','','','','','','','','',''),(141,'LT-SCP14-2024','','','2024','CHERRY ESTARES','','CB','DELL INSPIRON 15 3511','','','','','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','16Gb','1 YEAR','NONE','NONE','','','',''),(142,'LT-SCP15-2024','','','2024','NEIL FORTES','','HQ','DELL INSPIRON 15 3511','C4RYYJ3','YES','August-14-2024','LT-SCP15-2024','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','16Gb','1 YEAR','NONE','NONE','Success@2019','dell','',''),(143,'LT-SCP16-2024','','','2024','BiILLY NATIVIDAD','','FIN','DELL INSPIRON 15 3511','42N84L3','YES','November-11-2024','LT-SCP16-2024','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1135G7','NONE','512GB','16Gb','1 YEAR','NONE','NONE','Success@2019','dell','',''),(144,'LT-SCP17-2024','','','2024','JULIUS APIGO','','WTY','HP VICTUS 16-R0xxx','CND3222CST','YES','December-26-2024','LT-SCP17-2024','WIN 11','Sanden.NET','ESET','2019','INTEL I5-13500HX','1TB','512GB','16Gb','1 YEAR','NONE','NONE','Success@2019','hpvictus','',''),(145,'LT-SCP01-2025','','FEBUARY','2025','RICKY SALAMANTE','','SL','DELL INSPIRON 15 3520','HBNH134','YES','JANUARY-20-2025','LT-SCP01-2025','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1235U','NONE','512GB','16Gb','1 YEAR','NONE','NONE','Success@2019','dell','',''),(146,'LT-SCP02-2025','','FEBUARY','2025','KANE CYRIL GONZALES','','SL','DELL INSPIRON 14 5440','FP6BP54','YES','February-25-2025','LT-SCP02-2025','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1235U','NONE','1TB','16Gb','1 YEAR','NONE','NONE','Success@2019','dell','',''),(147,'LT-SCP03-2025','','MARCH','2025','IKEDA OSAMU','','','TOSHIBA PROT?G? X30-L','','YES','MARCH-07-2025','LT-SCP03-2025','WIN 11','Sanden.NET','ESET','2019','INTEL I5-1235U','NONE','1TB','32Gb','1 YEAR','NONE','NONE','','','',''),(148,'LT-SCP04-2025','','MARCH','2025','GERRYCA JOVES','','HRAD','DELL INSPIRON 5640','60DHR34','YES','MARCH-11-2025','LT-SCP04-2025','WIN 11','Sanden.NET','ESET','2019','Intel Core 5 proc 120U','NONE','1TB','32Gb','1 YEAR','NONE','NONE','Success@2019','dell','',''),(149,'LT-SCP05-2025','','MARCH','2025','EUVY ANGELO MARABLE','','MIS','HP VICTUS 16-R0xxx','CDN4513G1C','YES','MARCH-11-2025','LT-SCP05-2025','WIN 11','Sanden.NET','ESET','2019','Core i7-13700HX','NONE','1TB','16GB','1 YEAR','NONE','NONE','Success@2019','','',''),(150,'LT-SCP06-2025','','MARCH','2025','JOANA PABILLO','','WTY','DELL INSPIRON 5640','DJ1LR34','YES','MARCH-11-2025','LT-SCP06-2025','WIN 11','Sanden.NET','ESET','2019','Intel Core 5 proc 120U','NONE','1TB','32Gb','1 YEAR','NONE','NONE','Success@2019','dell','',''),(151,'LT-SCP07-2025','','MARCH','2025','ALMAR MARUGGAY','','WTY','DELL INSPIRON 5640','JD1LR34','YES','','LT-SCP07-2025','WIN 11','Sanden.NET','ESET','2019','Intel Core 5 proc 120U','NONE','1TB','32Gb','1 YEAR','NONE','NONE','Success@2019','dell','',''),(152,'LT-SCP08-2025','','MARCH','2025','JULIETA ALCOS','','SL','DELL INSPIRON 5640','FBC5V34','YES','April-29-2025','LT-SCP08-2025','WIN 11','Sanden.NET','ESET','2019','INTEL CORE I7-150U','NONE','1TB','16GB','1 YEAR','NONE','NONE','Success@2019','dell','',''),(153,'LT-SCP09-2025','','','','MIS STAFF','','MIS','HP VICTUS 16-R0xxx','5CD427466T','','','','','','','','','','','','','','','','','',''),(154,'LT-SCP10-2025','','','','MONETTE SALAZAR','','FIN','DELL INSPIRON 16 5640','25QKR34','YES','MAY-23-2025','LT-SCP10-2025','WIN 11','Sanden.NET','ESET','2019','INTEL CORE 5 120U','NONE','1TB','16GB','1 YEAR','NONE','NONE','Success@2019','dell','',''),(155,'LT-SCP11-2025','','','','NEMISIS GODOY','','FIN','DELL INSPIRON 16 5640','6GTKR34','YES','MAY-23-2025','LT-SCP11-2025','WIN 11','Sanden.NET','ESET','2019','INTEL CORE 5 120U','NONE','1TB','16GB','1 YEAR','NONE','NONE','Success@2019','dell','',''),(156,'LT-SCP12-2025','','MAY','','JEFFREY A. DEALINO','','','DELL INSPIRON 15 3520','B80K554','','','','WIN 11','Sanden.NET','ESET','2019','INTEL CORE 7 1255U','NONE','1TB','16GB','1 YEAR','NONE','NONE','Success@2019','dell','',''),(157,'LT-SCP13-2025','','MAY','','','','','DELL INSPIRON 15 3520','PWBK554','','','','WIN 11','Sanden.NET','ESET','2019','INTEL CORE 7 1255U','NONE','1TB','16GB','1 YEAR','NONE','NONE','Success@2019','dell','',''),(158,'PC-SCP01-2019','','20-Feb','2020','SERVICE UNIT','','HRAD','LENOVO','YL00KRR7','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3','1 TB','4 GB','LENOVO THINKVISION 21\"','HP','LENOVO','NONE','1 YEAR','','','PREVIOUSLY USED BY CYRELLE MERCADO AND JOHN MICHAEL ABLIR'),(159,'PC-SCP02-2019','','19-Sep','2019','CONFERENCE ROOM','','MIS','LENOVO V530 - I3','PC12EXJT','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3-8100','1 TB','4 GB','LENOVO THINKVISION 21\"','LENOVO','LENOVO','NONE','1 YEAR','','','PREVIOUSLY USED BY DANICA ANIDO'),(160,'PC-SCP03-2019','','19-Nov','2019','SERVICE UNIT','','SCM','LENOVO V530 - I3','PC12EXHX','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3-8100','1 TB','4 GB','AOC','LENOVO','LENOVO','APC','1 YEAR','','','PREVIOSLY USED BY RONALYN PULUTAN AND CATHERINE BAGA'),(161,'PC-SCP04-2019','','19-Nov','2019','JEFFREY DEALINO','','SCM','LENOVO V530 - I3','PC12EXJM','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3-8100','1 TB','4 GB','AOC','HP','A4TECH','NONE','1 YEAR','','','PREVIOSLY USED BY CLARENCE BUAN'),(162,'PC-SCP05-2019','','19-Nov','2019','SERVICE UNIT','','SCM','LENOVO V530 - I3','PC12EXJW','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3-8100','1 TB','4 GB','AOC','LENOVO','LOGITECH','NONE','1 YEAR','','','PREVIOSLY USED BY RUSSEL AWITAN'),(163,'PC-SCP07-2019','','19-Nov','2019','CHARLES GELOTIN','','MIS','LENOVO V530 - I5','YL00FR1A','','','','WIN 10','Sanden.NET','ESET','2019','INTEL I5-8400','1 TB','8 GB','LENOVO THINKVISION 21\"','LENOVO','LENOVO','NONE','1 YEAR','','','FOR DTR USE AND TROUBLESHOOTING'),(164,'PC-SCP08-2019','N/A','N/A','2014','WTY WARRANTY TECHNICIAN','','WRTY','HP COMPAQ 4000','SGH207SWQ3','','','','WIN 7','Sanden.NET','ESET','2007','INTER CORE 2 DUO','300 GB','2 GB','AOC','LENOVO','','NONE','NONE','','','PREVIOUSLY USED BY RONALYN PULUTAN AND COMMON PC - TECH'),(165,'PC-SCP09-2019','','19-Jul','2019','VEEJAY MANALO','','CC','LENOVO V530 - I3','PC0WNES6','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3-8100','1 TB','16 GB','AOC','HP','A4TECH','NONE','1 YEAR','','','INSTALLED - HP OFFICEJET 7610'),(166,'PC-SCP10-2019','N/A','N/A','2017','JOHN LOUIE GAVINO','','CC','LENOVO SFF','PC0QTGGZ','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5','500 GB','4 GB','AOC','LENOVO','CLIPTECH','NONE','NONE','','','PREVIOUSLY USED BY CATHLEEN MENDOZA'),(167,'PC-SCP11-2019','','','2019','PANTRY ISI','','HRAD','LENOVO V530 - I5','YL00G44X','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5-8400','1 TB','8 GB','AOC','LENOVO','A4TECH','NONE','NONE','','','PREVIOUSLY USED BY MONETTE SALAZAR'),(168,'PC-SCP12-2019','','19-Jul','2019','SERVICE UNIT','','HRAD','LENOVO V530 - I3','PC100NTK','','','','WIN 10','Sanden.NET','ESET','2019','INTEL I3-8100','1 TB','8 GB','LENOVO THINKVISION 21\", AOC','LENOVO','LENOVO','APC','1 YEAR','','','PREVIOUSLY USED BY GERRYCA JOVES'),(169,'PC-SCP13-2019','N/A','N/A','2017','SCM WAREHOUSE','','SCM','LENOVO V520S','PC0QTGHL','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5-7400','500 GB','4 GB','AOC','A4TECH','A4TECH','NONE','1 YEAR','','','PREVIOUSLY USED BY SABRINA MOSQUERA'),(170,'PC-SCP14-2019','N/A','N/A','2016','MIS PULLED OUT','','MIS','LENOVO THINKCENTRE','S5CD5TN','YES','JUNE-15-2023','PC-SCP14-2019','WIN 10','Sanden.NET','ESET','2013','INTEL CORE 2 DUO','500 GB','4 GB','AOC','A4TECH','SILVERTECH','NONE','NONE','','','PREVIOUSLY USED BY ERWIN MALABUYOC AND ALREADY IN MIS STOCK ROOM'),(171,'PC-SCP15-2019','N/A','N/A','2013','NL WARRANTY TECHNICIAN','','NL','LENOVO THINKCENTRE','S5CDGYY','','','','WIN 7','Sanden.NET','ESET','2007','INTEL CORE 2 DUO','500 GB','2 GB','LENOVO','LENOVO','A4TECH','NONE','NONE','','','PREVIOUSLY USED BY ERICA PACIOLCO / WTY TECHNICIAN'),(172,'PC-SCP16-2019','','19-Nov','2019','ALFREDO  LUNA JR.','','WRTY','LENOVO V530 - I5','YL00FR2B','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I5-8400','1 TB','8 GB','LENOVO','LENOVO','LENOVO','NONE','1 YEAR','','','PREVIOUSLY DEPLOYED TO MAKATI FOR ADMIN USE'),(173,'PC-SCP01-2020','','19-Aug','2019','WTY WARRANTY TECHNICIAN 1','','WRTY','LENOVO V530','PC10QNRK','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3-8100','1 TB','4GB','AOC','LENOVO','LENOVO','NONE','1 YEAR','','','INSTALLED PRINTER COLORED WTY (NEAR NOEL BUSTAMANTE)'),(174,'PC-SCP02-2020','N/A','N/A','2015','OLD PC JAMAICA','','CL','LENOVO THINKCENTRE E73','PC056MW4','','','','WIN 7','Sanden.NET','ESET','2013','INTEL I5-4590S','512 GB','4 GB','AOC','LENOVO','LENOVO','APC','NONE','','','OK'),(175,'PC-SCP04-2020','','19-Aug','2019','RUBY AUTIDA','','DV','LENOVO V530','PC10QNTN','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3-8100','1 TB','4 GB','LENOVO','LENOVO','LENOVO','NONE','1 YEAR','','',''),(176,'PC-SCP05-2020','N/A','N/A','-','CEBU WAREHOUSE','','CB','TRENDSONIC','FADED','','','','WIN 7','Sanden.NET','ESET','2013','INTEL I5','500 GB','4 GB','AOC','A4TECH','A4TECH','BOOST','NONE','','','OK'),(177,'PC-SCP06-2020','N/A','N/A','2013','SECURITY (OB & CCTV\')','','MIS','LENOVO THINKCENTRE','S5CDGTF','','','','WIN 10','Sanden.NET','ESET','2013','INTEL CORE 2 DUO','500 GB','4 GB','NONE','NONE','NONE','NONE','','','','OLD UNIT OF GERRYCA JOVES'),(178,'PC-SCP07-2020','','20-Feb','2020','SERVICE UNIT','','NL','LENOVO','YL00KRR0','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3','1 TB','4 GB','LENOVO THINKVISION 21\"','LENOVO','LENOVO','NONE','1 YEAR','','','PREVIOUSLY FOR FINANCE ANALYST'),(179,'PC-SCP08-2020','?33,000.00','20-Feb','2020','ISI MANAGER','','HRAD','LENOVO','YL00KRSH','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3','1 TB','4 GB','LENOVO THINKVISION 21\"','LENOVO','LENOVO','NONE','1 YEAR','','','PREVIOUSLY USED BY KLARISSE LANTICAN'),(180,'PC-SCP01-2022','','22-Jan','2022','MIS PULLED OUT','','HQ','LENOVO','YL00KRSH','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3','1 TB','4 GB','LENOVO THINKVISION 21\"','LENOVO','LENOVO','NONE','1 YEAR','','','PREVIOUSLY USED BY CASSANDRA AND JERWHYNE'),(181,'PC-SCP02-2022','','22-Mar','2022','KARLSON CRISOSTOMO','','CC','HP Z2 G8 TOWER WORKSTATION','4CE150BFC8','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3','1 TB','4 GB','LENOVO THINKVISION 21\"','LENOVO','LENOVO','NONE','1 YEAR','','autocadhp','PREVIOUSLY USED BY JEFFREY DELGADO'),(182,'PC-SCP03-2022','','','2022','RUBY AUTIDA','','DAV','','','','','','WIN 10','Sanden.NET','ESET','2013','INTEL I3','1 TB','4 GB','LENOVO THINKVISION 21\"','LENOVO','LENOVO','NONE','1 YEAR','','',''),(183,'PC-SCP01-2025','','25-Jun','2025','','','','HP Z2 Tower','4CE517BY8Z','','','','WIN 11','Sanden.NET','ESET','2019','Intel(R) Core(TM) i7-14700K','500GB HDD\n1TB SSD','32 GB','','','','','2 YEAR','','','');
/*!40000 ALTER TABLE `inventory_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `it_request_approval`
--

DROP TABLE IF EXISTS `it_request_approval`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `it_request_approval` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approved_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approver_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_approver_role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Pending','Approved','Rejected','Completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `it_request_approval_reference_no_index` (`reference_no`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `it_request_approval`
--

LOCK TABLES `it_request_approval` WRITE;
/*!40000 ALTER TABLE `it_request_approval` DISABLE KEYS */;
INSERT INTO `it_request_approval` VALUES (1,'RR-202509-001','Jeffrey C. Salagubang','','','','Completed','fsdfghj','2025-09-04 06:54:07','2025-09-05 05:19:46'),(2,'RR-202509-001','Jeffrey C. Salagubang','','','','Completed','dsaasd','2025-09-04 07:10:02','2025-09-05 05:26:30'),(11,'NIS-202509-001','Dichoso Fernan','Manager','fernan.dichoso.cj@sanden-rs.com','Manager','Pending','','2025-09-04 07:53:31','2025-09-04 07:53:31'),(12,'RR-202509-003','MIS SM','IT','sanden.mis.sm@sanden-rs.com','IT','Pending','','2025-09-04 08:03:32','2025-09-04 08:03:32'),(13,'NIS-202509-001','Dichoso Fernan','Manager','fernan.dichoso.cj@sanden-rs.com','Manager','Pending','','2025-09-04 07:53:31','2025-09-04 07:53:31'),(15,'NIS-202509-001','Dichoso Fernan','Manager','fernan.dichoso.cj@sanden-rs.com','Manager','Pending','','2025-09-04 07:53:31','2025-09-04 07:53:31'),(16,'NIS-202509-001','Dichoso Fernan','Manager','fernan.dichoso.cj@sanden-rs.com','Manager','Pending','','2025-09-04 07:53:31','2025-09-04 07:53:31'),(17,'NIS-202509-001','Dichoso Fernan','Manager','fernan.dichoso.cj@sanden-rs.com','Manager','Pending','','2025-09-04 07:53:31','2025-09-04 07:53:31'),(18,'NIS-202509-001','Dichoso Fernan','Manager','fernan.dichoso.cj@sanden-rs.com','Manager','Pending','','2025-09-04 07:53:31','2025-09-04 07:53:31'),(19,'RR-202509-004','MIS SM','IT','sanden.mis.sm@sanden-rs.com','IT','Pending','','2025-09-05 03:47:12','2025-09-05 03:47:12'),(20,'RR-202509-004','MIS SM','IT','sanden.mis.sm@sanden-rs.com','IT','Pending','','2025-09-05 03:47:12','2025-09-05 03:47:12');
/*!40000 ALTER TABLE `it_request_approval` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `it_request_tbl`
--

DROP TABLE IF EXISTS `it_request_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `it_request_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requestor_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requestor_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_needed` date DEFAULT NULL,
  `plan_return_date` date DEFAULT NULL,
  `purchase_item_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subsystem_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manager_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `change_request_intranet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_request` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_of_request` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` enum('Low','Medium','High') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Medium',
  `status` enum('Pending','In Progress','Completed','Rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `it_request_tbl_reference_no_unique` (`reference_no`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `it_request_tbl`
--

LOCK TABLES `it_request_tbl` WRITE;
/*!40000 ALTER TABLE `it_request_tbl` DISABLE KEYS */;
INSERT INTO `it_request_tbl` VALUES (1,'RR-202509-001','Jeffrey C. Salagubang','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','CCTV Concerns',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Repair_Request','dasdasdas','High','Completed','2025-09-04 05:31:13','2025-09-04 07:10:02'),(2,'RR-202509-002','Jeffrey C. Salagubang','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Repair_Request','dasdasd','Medium','Pending','2025-09-04 05:31:41','2025-09-04 05:31:41'),(17,'NIS-202509-001','Jeffrey C. Salagubang','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT',NULL,NULL,NULL,NULL,NULL,NULL,'test sub system','fernan.dichoso.cj@sanden-rs.com',NULL,'New_Intranet_Subsystem','dasd asd as','Medium','Pending','2025-09-04 07:53:31','2025-09-04 07:53:31'),(19,'RR-202509-003','Jeffrey C. Salagubang','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','CCTV Concerns',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Repair_Request','hhj','Medium','Pending','2025-09-04 08:03:32','2025-09-04 08:03:32'),(20,'RR-202509-004','Jeffrey C. Salagubang','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Desktop',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Repair_Request','sira','High','Pending','2025-09-05 03:47:12','2025-09-05 03:47:12');
/*!40000 ALTER TABLE `it_request_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_request_tbl`
--

DROP TABLE IF EXISTS `item_request_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_request_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `date_needed` date NOT NULL,
  `date_plan_return` date NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requestor_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purpose` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_done` date DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_request_tbl`
--

LOCK TABLES `item_request_tbl` WRITE;
/*!40000 ALTER TABLE `item_request_tbl` DISABLE KEYS */;
INSERT INTO `item_request_tbl` VALUES (1,'Desktop',12,'2025-07-30','2025-08-01','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'test',NULL,'pending','2025-07-30 22:03:13','2025-07-30 22:03:13'),(2,'Flashdrive',1,'2025-07-31','2025-08-01','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'test a',NULL,'pending','2025-07-30 22:06:27','2025-07-30 22:06:27'),(3,'Desktop',2,'2025-07-31','2025-08-01','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'test 22',NULL,'pending','2025-07-30 22:39:39','2025-07-30 22:39:39'),(4,'Flashdrive',1,'2025-07-31','2025-08-01','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'addda',NULL,'pending','2025-07-30 22:42:43','2025-07-30 22:42:43'),(5,'External Drive',2,'2025-07-17','2025-08-08','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'ad',NULL,'pending','2025-07-30 22:47:49','2025-07-30 22:47:49'),(6,'Pocket Wifi',1,'2025-07-31','2025-08-01','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'dasdas',NULL,'pending','2025-07-30 22:49:30','2025-07-30 22:49:30'),(7,'Desktop',1,'2025-08-27','2025-08-29','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'adasd test etst',NULL,'pending','2025-07-31 17:34:37','2025-07-31 17:34:37'),(8,'External Drive',1,'2025-08-07','2025-08-27','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'dasdasdasd',NULL,'pending','2025-07-31 18:06:50','2025-07-31 18:06:50'),(9,'External Drive',1,'2025-08-07','2025-08-27','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'dasdasdasd',NULL,'pending','2025-07-31 18:09:44','2025-07-31 18:09:44'),(10,'Flashdrive',222,'2025-07-31','2025-07-31','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'adsdasd',NULL,'pending','2025-07-31 18:18:00','2025-07-31 18:18:00'),(11,'Laptop',1,'2025-08-01','2025-08-02','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'test aijsdasjdkj',NULL,'pending','2025-07-31 18:19:25','2025-07-31 18:19:25'),(12,'Pocket Wifi',2,'2025-08-08','2025-08-15','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'dasdas222222',NULL,'pending','2025-07-31 18:23:51','2025-07-31 18:23:51'),(13,'Laptop',2,'2025-08-02','2025-08-08','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'ad22e43253525325',NULL,'pending','2025-07-31 18:26:04','2025-07-31 18:26:04'),(14,'Wired Keyboard',2,'2025-08-06','2025-08-27','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'adsdasd',NULL,'pending','2025-07-31 18:28:16','2025-07-31 18:28:16'),(15,'Flashdrive',2,'2025-07-31','2025-08-02','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'dasdasd',NULL,'pending','2025-07-31 18:33:41','2025-07-31 18:33:41'),(16,'Flashdrive',2,'2025-08-15','2025-08-22','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'gvbsdfx',NULL,'pending','2025-07-31 18:34:23','2025-07-31 18:34:23'),(17,'External Drive',1,'2025-08-01','2025-08-02','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'test',NULL,'pending','2025-07-31 18:35:37','2025-07-31 18:35:37'),(18,'Desktop',21,'2025-08-01','2025-08-02','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'agagaga',NULL,'pending','2025-07-31 18:40:40','2025-07-31 18:40:40'),(19,'Laptop',1,'2025-08-01','2025-08-02','neil.olivera.no@sanden-rs.com','(SCP) RS-HRAD','Neil Erzon E. Olivera',NULL,'test',NULL,'pending','2025-07-31 21:32:27','2025-07-31 21:32:27'),(30,'Flashdrive',2,'2025-08-08','2025-08-08','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Jeffrey C. Salagubang',NULL,'2addsasd',NULL,'pending','2025-08-08 01:32:53','2025-08-08 01:32:53');
/*!40000 ALTER TABLE `item_request_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_07_18_050407_add_role_to_users_table',2),(8,'2025_07_18_060109_create_inventory_tbl_table',3),(9,'2025_07_18_063304_create_documents_table',4),(10,'2025_07_24_032636_add_file_type_to_documents_table',5),(11,'2025_07_25_024851_create_special_access_users_table',6),(12,'2025_07_25_062957_create_features_table',7),(13,'2025_07_25_065058_create_special_access_users_table',8),(14,'2025_07_31_020840_add_department_to_users_table',9),(15,'2025_07_31_021108_add_status_position_to_users_table',10),(18,'2025_07_31_050724_create_item_request_tbl',11),(19,'2025_08_01_072303_add_doc_type_upload_date_control_type_to_documents_table',12),(20,'2025_08_06_134319_create_events_table',13),(21,'2025_08_07_113631_add_agenda_and_pic_to_events_table',14),(22,'2025_08_15_094921_create_intranet_version_table',15),(23,'2025_08_26_152352_create_sessions_table',16),(25,'2025_09_01_110405_create_it_request_tbl',17),(26,'2025_09_04_144247_create_it_request_approval_table',18),(27,'2025_09_09_132629_create_activity_team_tables',19);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('wxAAMeIX3NHS3hqWJpMHCQvgzd2w6YPzj1YCUHkg',119,'192.168.5.156','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZnJkcHlMem1zeTFkcmVKcUpFZnQ2S3BpWFlTZnJOTmx5dUR5dGQwYSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjUzOiJodHRwczovL3NhbmRlbmludHJhbmV0LmxvY2FsL2FjdGl2aXRpZXMvc2VsZWN0LWZpZWxkcyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjExOTt9',1757401966);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `special_access_users`
--

DROP TABLE IF EXISTS `special_access_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `special_access_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `feature_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `special_access_users_user_id_foreign` (`user_id`),
  KEY `special_access_users_feature_id_foreign` (`feature_id`),
  CONSTRAINT `special_access_users_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE,
  CONSTRAINT `special_access_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `special_access_users`
--

LOCK TABLES `special_access_users` WRITE;
/*!40000 ALTER TABLE `special_access_users` DISABLE KEYS */;
INSERT INTO `special_access_users` VALUES (1,119,18,NULL,NULL),(2,119,13,NULL,NULL),(3,119,19,NULL,NULL),(4,119,22,NULL,NULL),(5,119,20,NULL,NULL),(6,69,20,NULL,NULL),(7,119,21,NULL,NULL),(8,23,20,NULL,NULL),(9,23,23,NULL,NULL),(10,119,23,NULL,NULL),(11,119,24,NULL,NULL),(12,23,24,NULL,NULL),(13,119,25,NULL,NULL);
/*!40000 ALTER TABLE `special_access_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `submissions`
--

DROP TABLE IF EXISTS `submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `activity_id` bigint unsigned NOT NULL,
  `activity_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `progress_value` double(8,2) NOT NULL,
  `status` enum('pending','approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `submissions_team_id_foreign` (`team_id`),
  KEY `submissions_user_id_foreign` (`user_id`),
  KEY `submissions_activity_id_foreign` (`activity_id`),
  CONSTRAINT `submissions_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `submissions_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `submissions`
--

LOCK TABLES `submissions` WRITE;
/*!40000 ALTER TABLE `submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_user`
--

DROP TABLE IF EXISTS `team_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role` enum('captain','member') COLLATE utf8mb4_unicode_ci NOT NULL,
  `progress_value` double(8,2) NOT NULL DEFAULT '0.00',
  `joined_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_user_team_id_foreign` (`team_id`),
  KEY `team_user_user_id_foreign` (`user_id`),
  CONSTRAINT `team_user_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_user`
--

LOCK TABLES `team_user` WRITE;
/*!40000 ALTER TABLE `team_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_id` bigint unsigned NOT NULL,
  `activity_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_id` bigint unsigned NOT NULL,
  `captain_id` bigint unsigned NOT NULL,
  `invite_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','active','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teams_invite_code_unique` (`invite_code`),
  KEY `teams_activity_id_foreign` (`activity_id`),
  KEY `teams_level_id_foreign` (`level_id`),
  KEY `teams_captain_id_foreign` (`captain_id`),
  CONSTRAINT `teams_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teams_captain_id_foreign` FOREIGN KEY (`captain_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teams_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `challenge_levels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Cherry Estares','cherry.estares.ym@sanden-rs.com','(SCP) RS-Cebu','Active','Sales and Admin Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(2,'Gladys Bulambot','gladys.bulambot.nn@sanden-rs.com','(SCP) RS-Cebu','Active','Sales Coordinator',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(3,'Jollen Baldecanas','jollen.baldecanas.lz@sanden-rs.com','(SCP) RS-Cebu','Active','Sales Coordinator',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(4,'Levie Lerasan','levie.lerasan.ew@sanden-rs.com','(SCP) RS-Cebu','Active','Warehouseman',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(5,'Bucu Jamaica','jamaica.bucu.jc@sanden-rs.com','(SCP) RS-Central Luzon','Active','Sales and Admin Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(6,'Almar Maruggay','almar.maruggay.dd@sanden-rs.com','(SCP) RS-Cold Chain','Active','Supervisor',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(7,'Christian Alcantara','christian.alcantara.ut@sanden-rs.com','(SCP) RS-Cold Chain','Active','Project Engineer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(8,'Palomillo Emirlyn','Emirlyn.Palomillo.gi@sanden-rs.com','(SCP) RS-Cold Chain','Active','Manager',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(9,'Robert Alpajaro','robert.alpajaro.vf@sanden-rs.com','(SCP) RS-Cold Chain','Active','Senior Manager',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(10,'Ella Villa','ella.villa.vo@sanden-rs.com','(SCP) RS-Davao','Active','Ella',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(11,'Esther Requinta','esther.requinta.rk@sanden-rs.com','(SCP) RS-Davao','Active','Sales Coordinator',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(12,'Pacilan Pearl','pearl.pacilan.df@sanden-rs.com','(SCP) RS-Davao','Active','Sales and Admin Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(13,'Dela Cruz Joyce Ann','Joyce.Delacruz.jb@sanden-rs.com','(SCP) RS-Central Luzon','Active','Supervisor',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(14,'Dichoso Fernan','fernan.dichoso.cj@sanden-rs.com','(SCP) RS-Finance','Active','Manager',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','user_s3',NULL,NULL,NULL),(15,'Jolina Germina','jolina.germina.rz@sanden-rs.com','(SCP) RS-Finance','Active','Finance Analyst',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(16,'Ramil R. Bascon','ramil.r.bascon.df@sanden-rs.com','(SCP) RS-HRAD','Active','VP-Finance and Gen Admin',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(17,'Salazar Monette','monette.salazar.ms@sanden-rs.com','(SCP) RS-Finance','Active','Finance Analyst',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(18,'Larry Cruz','larry.cruz.jz@sanden-rs.com','(SCP) RS-General Administration','Active','COO',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(19,'Bantigue Loida','loida.bantigue.tg@sanden-rs.com','(SCP) RS-GMA','Active','Sales Coordinator',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(20,'Mark Delos Reyes','mark.delosreyes.ir@sanden-rs.com','(SCP) RS-GMA','Active','Manager',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(21,'Beran celestial','celestial.m.beran.qr@sanden-rs.com','(SCP) RS-Makati','Active','Sales Executive',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(22,'Gonzales Kane Cyril','kane.gonzales.kc@sanden-rs.com','(SCP) RS-Makati','Active','Product Specialist',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(23,'Marable Euvy Angelo','Euvy.Marable.df@sanden-rs.com','(SCP) RS-IT','Active','IT Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','admin',NULL,NULL,NULL),(24,'Jordan Galdones','jordan.galdones.wd@sanden-rs.com','(SCP) RS-North Luzon','Active','Manager',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(25,'Osamu Ikeda','osamu.ikeda.iv@sanden-rs.com','(SCP) RS-President','Active','????',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(26,'Apigo Julius','julius.apigo.dw@sanden-rs.com','(SCP) RS-Service Warranty','Active','Senior Manager',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(27,'Alcos Julieta','julieta.alcos.qp@sanden-rs.com','(SCP) RS-South Luzon','Active','Manager',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(28,'Ian Llaneta','ian.llaneta.ih@sanden-rs.com','(SCP) RS-South Luzon','Active','Sales Coordinator',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(29,'Pulutan Ronalyn','ronalyn.pulutan.df@sanden-rs.com','(SCP) RS-Supply Chain','Active','Importation Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(30,'Pabillo Joana','Joana.Pabillo.fa@sanden-rs.com','(SCP) RS-Service Warranty','Active','Sales and Admin Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(31,'Rodney Castro','Rodney.Castro.xz@sanden-rs.com','(SCP) RS-North Luzon','Active','Warehouseman',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(32,'Ricky Salamante','ricky.salamante.rs@sanden-rs.com','(SCP) RS-HQ','Active','Sales Executive',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(33,'Gerald D. Gersalina','gerald.gersalina.dc@sanden-rs.com','(SCP) RS-Davao','Active','Technician',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(34,'William A. Llosala','william.llosala.wl@sanden-rs.com','(SCP) RS-South Luzon','Active','Sales Coordinator',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(35,'Danica M. Anido','danica.anido.da@sanden-rs.com','(SCP) RS-Finance','Active','Accounting Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(36,'Rhiza Capati','Rhiza.Capati.bd@sanden-rs.com','(SCP) RS-HRAD','Active','Manager',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(37,'Sabrina Moquera','sabrina.m.mosquera.sb@sanden-rs.com','(SCP) RS-Supply Chain','Active','Senior Purchasing Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(38,'Alvin C. Capuno','alvin.capuno.ac@sanden-rs.com','(SCP) RS-Central Luzon','Active','Sales Coordinator',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(39,'Ryan R. Gabuyo','ryan.gabuyo.rg@sanden-rs.com','(SCP) RS-North Luzon','Active','Sales Coordinator',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(40,'Benje Oca','benje.oca.bo@sanden-rs.com','(SCP) RS-Davao','Active','Sales Coordinator',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(41,'Adel Paolo D. Barlan','adel.barlan.ab@sanden-rs.com','(SCP) RS-Cold Chain','Active','Project Engineer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(42,'Mark Marlo M. Garcia','mark.garcia.mg@sanden-rs.com','(SCP) RS-Service Warranty','Active','Leadman Technician',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(43,'Gerryca Syra H. Joves','gerryca.joves.gj@sanden-rs.com','(SCP) RS-HRAD','Active','HRAD Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(44,'Rhea Mae R. Villar','rhea.villar.rv@sanden-rs.com','(SCP) RS-South Luzon','Active','Sales Office Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(45,'Sanden Cold Chain Philippines','sanden.coldchain.ph@sanden-rs.com','(SCP) RS-IT','Active','Sales Advertisement',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(46,'Sanden Cebu Technician','cebu.technician.ph@sanden-rs.com','(SCP) RS-Cebu','Active','Warranty Technician',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(47,'Sanden Service Warranty','sanden.service.ph@sanden-rs.com','(SCP) RS-Service Warranty','Active','Service Warranty',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(48,'Jun T. Perocho','jun.perocho.jp@sanden-rs.com','(SCP) RS-Davao','Active','Sales Coordinator',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(49,'Clarence D. Panambo','clarence.panambo.bc@sanden-rs.com','(SCP) RS-Cold Chain','Active','Sales Engineer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(50,'Jayson D. Orcine','jayson.orcine.cd@sanden-rs.com','(SCP) RS-Cold Chain','Active','Technician',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(51,'Klarrisse E. Lantican','klarrisse.lantican.be@sanden-rs.com','(SCP) RS-Finance','Active','AR Analyst',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(52,'Jeffrey A. Dealino','jeffrey.dealino.cd@sanden-rs.com','(SCP) RS-Supply Chain','Active','SCM Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(53,'Sanden CL Technician','centralluzon.technician.ph@sanden-rs.com','(SCP) RS-Central Luzon','Active','Warranty Technician',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(54,'Sanden NL Technician','northluzon.technician.ph@sanden-rs.com','(SCP) RS-North Luzon','Active','Warranty Technician',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(55,'Keam Luwys Veloso','keam.luwys.kl@sanden-rs.com','(SCP) RS-IT','Active','MIS Reliever',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(56,'E-Commerce','shop.scp.ph@sanden-rs.com','(SCP) RS-Makati','Active','Sales ',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(57,'Alvin S. Joven','alvin.joven.fi@sanden-rs.com','(SCP) RS-Finance','Active','Finance Analyst- Cost',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s1',NULL,NULL,NULL),(58,'Mary Joy M. Cinco','maryjoy.cinco.il@sanden-rs.com','(SCP) RS-Finance','Active','Accounts Payable',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(59,'Billy Joel C. Manalo','billy.manalo.nq@sanden-rs.com','(SCP) RS-Makati','Active','Sales Manager F&B',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(60,'Daryl Glenn G. Delfin','daryl.delfin.sv@sanden-rs.com','(SCP) RS-Davao','Active','Warehouse Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(61,'Cold Chain Technician','coldchain.technician.ph@sanden-rs.com','(SCP) RS-Cold Chain','Active','ColdChain Technician',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(62,'Cassandrae Joice O. Villaverde','cassandrae.villaverde.vy@sanden-rs.com','(SCP) RS-Makati','Active','Marketing Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(63,'Nemicio A. Balani','nemicio.balani.wz@sanden-rs.com','(SCP) RS-Cold Chain','Active','Cadet Engineer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(64,'Mary Rose C. Ilagan','maryrose.ilagan.mi@sanden-rs.com','(SCP) RS-Central Luzon','Active','Sales and Admin Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(65,'Cold Chain Operator','coldchain.operator.ph@sanden-rs.com','(SCP) RS-Cold Chain','Active','CAD Operator',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(66,'Iloilo Technician','iloilo.technician.ph@sanden-rs.com','(SCP) RS-Cebu','Active','Iloilo Technician',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(67,'Mart Joshua P. Lantican','mart.lantican.ml@sanden-rs.com','(SCP) RS-Cold Chain','Active','Sales Engineer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(68,'Nelson M. Cubio','nelson.cubio.nc@sanden-rs.com','(SCP) RS-HRAD','Active','Safety Officer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(69,'Neil Erzon E. Olivera','neil.olivera.no@sanden-rs.com','(SCP) RS-HRAD','Active','HRAD Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s1',NULL,NULL,NULL),(70,'Mark Anthony C. De Leon','mark.deleon.md@sanden-rs.com','(SCP) RS-Finance','Active','Finance Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(71,'Den Mark M. Amurao','den.amurao.da@sanden-rs.com','(SCP) RS-Supply Chain','Active','Impex Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(72,'Edd Brandon G. Malabanan','edd.malabanan.em@sanden-rs.com','(SCP) RS-Cold Chain','Active','Cadet Engineer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(73,'Sanden Repair Warranty','sanden.repair.ph@sanden-rs.com','(SCP) RS-Service Warranty','Active','Service Warranty',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(74,'Josuah A. Repomanta','josuah.repomanta.jr@sanden-rs.com','(SCP) RS-Cold Chain','Active','Project Engineer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(75,'Renniel A. Capuso','renniel.capuso.rc@sanden-rs.com','(SCP) RS-Cold Chain','Active','Sales Engineer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(76,'Ramill C. Manese','ramill.manese.rm@sanden-rs.com','(SCP) RS-Central Luzon','Active','Sales Coordinator',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(77,'Jay E. Medellin','jay.medellin.jm@sanden-rs.com','(SCP) RS-Cebu','Active','Sales & Admin Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(78,'Mariel G. Javier','mariel.javier.mj@sanden-rs.com','(SCP) RS-Supply Chain','Active','Purchasing Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(79,'HRAD Security','hrad.security.ph@sanden-rs.com','(SCP) RS-HRAD','Active','HR Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(80,'Charnelian Agtong','charnelian.agtong.ca@sanden-rs.com','(SCP) RS-Cebu','Active','Sales Executive',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(81,'Merrie Ann F. Pundano','merrie.pundano.mp@sanden-rs.com','(SCP) RS-HRAD','Active','HR Payroll Analyst',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(82,'Luel E.Mondejar','luel.mondejar.lm@sanden-rs.com','(SCP) RS-Cebu','Active','Cebu Technician',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(83,'Edward Lalaguna','edward.lalaguna.el@sanden-rs.com','(SCP) RS-Central Luzon','Active','Warehouse Man',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(84,'Vazzim J. Soriano','vazzim.soriano.vs@sanden-rs.com','(SCP) RS-Service Warranty','Active','Cadet Engineer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(85,'Ruby Mae R. Autida','ruby.autida.ra@sanden-rs.com','(SCP) RS-Davao','Active','Sales and Admin Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(86,'John Louie T. Gavino','john.gavino.jg@sanden-rs.com','(SCP) RS-Finance','Active','Warehouse & Inventory Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(88,'Jerwhyne J. Sayco','jerwhyne.sayco.js@sanden-rs.com','(SCP) RS-Makati','Active','Marketing Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(89,'Sanden App','sanden.app.ph@sanden-rs.com','(SCP) RS-IT','Active','MIS',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(90,'Vinsie Lavena','vince.lavena.vl@sanden-rs.com','(SCP) RS-Finance','Active',' \nProject Material Planner',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(91,'Raizel Magampon','raizel.magampon.rm@sanden-rs.com','(SCP) RS-Cold Chain','Active','Sales and Admin Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(92,'John Chavez','john.chavez.jc@sanden-rs.com','(SCP) RS-Cold Chain','Active','Sales Engineer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(93,'Sherwin Salazar','sherwin.salazar.ss@sanden-rs.com','(SCP) RS-Finance','Active','Treasury Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(94,'Bea Briones','bea.briones.bb@sanden-rs.com','(SCP) RS-Cold Chain','Active','Sales Engineer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(95,'Romir Kevin Abesamis','romir.abesamis.ra@sanden-rs.com','(SCP) RS-HRAD','Active','Admin Specialist',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(96,'Mariel Justine Reyes','mariel.reyes.mr@sanden-rs.com','(SCP) RS-Finance','Active','Account Payable Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(97,'AR Finance','ar.finance.ph@sanden-rs.com','(SCP) RS-Finance','Active','AR Clerk',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(98,'Mark Louie Dinorog','mark.dinorog.md@sanden-rs.com','(SCP) RS-Cebu','Active','Warehouseman',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(99,'Liza Quilao','liza.quilao.lq@sanden-rs.com','(SCP) RS-HRAD','Active','HR Generalist',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(100,'Rommel Umandap','rommel.umandap.ru@sanden-rs.com','(SCP) RS-Finance','Active','Financial Analyst Accounts Receivable',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(101,'MIS Support','mis.support.ms@sanden-rs.com','(SCP) RS-IT','Active','MIS Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(102,'Nemesis Godoy','nemesis.godoy.ng@sanden-rs.com','(SCP) RS-Finance','Active','Cost and Tax Analyst',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(103,'Maria Adrenee Batag','adrenee.batag.ab@sanden-rs.com','(SCP) RS-Davao','Active','Sales Associate',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(104,'Neil Fortes','neil.fortes.nf@sanden-rs.com','(SCP) RS-Makati','Active','Customer Service Coordinator',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(105,'Ardie Solis','ardie.solis.as@sanden-rs.com','(SCP) RS-Cebu','Active','Sales Executive',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(106,'Karlson Crisostomo','karlson.crisostomo.kc@sanden-rs.com','(SCP) RS-Cold Chain','Active','CAD Operator',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(107,'RJ Kenneth Batayon','rj.batayon.rb@sanden-rs.com','(SCP) RS-Supply Chain','Active',' Inventory Planning Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(108,'Nicolai Asistido','nicolai.asistido.na@sanden-rs.com','(SCP) RS-Davao','Active','Sales Executive',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(109,'Billy Gil Natividad','billy.natividad.bn@sanden-rs.com','(SCP) RS-Finance','Active','Disbursement Analyst',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(110,'Russel Blake Leyson','russel.leyson.rl@sanden-rs.com','(SCP) RS-Cold Chain','Active',' Cadet Engineer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(111,'Raffy June Candolada','raffy.candolada.rc@sanden-rs.com','(SCP) RS-Davao','Active',' Sales Engineer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(112,'Riezee Sajorda','riezee.sajorda.rs@sanden-rs.com','(SCP) RS-Cebu','Active','Sales Executive',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(113,'Angelie Elarco','angelie.elarco.ae@sanden-rs.com','(SCP) RS-Supply Chain','Active','Purchasing Analyst',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(114,'Angelie E. Postrero','angelie.postrero.ap@sanden-rs.com','(SCP) RS-Supply Chain','Active','Purchasing Analyst',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(115,'Patrick P. Perez','patrick.perez.pp@sanden-rs.com','(SCP) RS-Cold Chain','Active','Sales Engineer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(116,'Richie C.Rabandaban','richie.rabandaban.rr@sanden-rs.com','(SCP) RS-Finance','Active','Finance Manager',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(117,'Dessa Q.Frial','dessa.frial.df@sanden-rs.com','(SCP) RS-HRAD','Active','Admin Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(118,'Melcon Joy D. Santander','melcon.santander.ms@sanden-rs.com','(SCP) RS-Cebu','Active','Sales Coordinator',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(119,'Jeffrey C. Salagubang','jeffrey.salagubang.js@sanden-rs.com','(SCP) RS-IT','Active','Mis Analyst',NULL,'$2y$12$lqf2j/Hxn5MefzGUgJUJHOtJww4UMs2LbQ.NH/SfY4QDhZZs/D8aa','developer',NULL,NULL,'2025-08-12 04:48:57'),(120,'Mark Joseph G. Algire','mark.algire.ma@sanden-rs.com','(SCP) RS-Cold Chain','Active','Sales Engineer',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL),(121,'Jeravy De Castro','jeravy.decastro.jd@sanden-rs.com','(SCP) RS-Cold Chain','Active',' Warehouse & Inventory Staff',NULL,'$2y$12$SUSmksis0jiW2/t0GgH/COQoJ2pCcl1.qgv6e66MQt2LOP7EyJB42','users_s2',NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-09 15:15:02
