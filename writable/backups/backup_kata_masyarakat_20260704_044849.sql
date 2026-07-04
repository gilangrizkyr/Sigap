/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.1-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: kata_masyarakat
-- ------------------------------------------------------
-- Server version	11.8.1-MariaDB-4deepin1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `complaint_attachments`
--

DROP TABLE IF EXISTS `complaint_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `complaint_attachments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `complaint_id` int(11) unsigned NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `complaint_attachments_complaint_id_foreign` (`complaint_id`),
  CONSTRAINT `complaint_attachments_complaint_id_foreign` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `complaint_attachments`
--

LOCK TABLES `complaint_attachments` WRITE;
/*!40000 ALTER TABLE `complaint_attachments` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `complaint_attachments` VALUES
(1,9,'uploads/complaints/1783073406_5f2df4c9ddf46027e06d.png','image/png');
/*!40000 ALTER TABLE `complaint_attachments` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `complaint_categories`
--

DROP TABLE IF EXISTS `complaint_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `complaint_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(11) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `complaint_categories_location_id_foreign` (`location_id`),
  CONSTRAINT `complaint_categories_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `complaint_categories`
--

LOCK TABLES `complaint_categories` WRITE;
/*!40000 ALTER TABLE `complaint_categories` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `complaint_categories` VALUES
(1,1,'Perizinan'),
(2,1,'OSS/NIB'),
(3,1,'Investasi'),
(4,1,'Petugas'),
(5,1,'Fasilitas'),
(6,1,'Lainnya'),
(7,2,'Perizinan/Layanan'),
(8,2,'Petugas'),
(9,2,'Fasilitas'),
(10,2,'Antrean'),
(12,2,'Lainnya');
/*!40000 ALTER TABLE `complaint_categories` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `complaint_replies`
--

DROP TABLE IF EXISTS `complaint_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `complaint_replies` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `complaint_id` int(11) unsigned NOT NULL,
  `admin_id` int(11) unsigned DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `complaint_replies_complaint_id_foreign` (`complaint_id`),
  KEY `complaint_replies_admin_id_foreign` (`admin_id`),
  CONSTRAINT `complaint_replies_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `complaint_replies_complaint_id_foreign` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `complaint_replies`
--

LOCK TABLES `complaint_replies` WRITE;
/*!40000 ALTER TABLE `complaint_replies` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `complaint_replies` VALUES
(1,11,3,'Terima kasih atas aspirasi anda, salam hormat','2026-07-03 13:06:10'),
(2,11,3,'Terima kasih atas aspirasi anda, salam hormat','2026-07-03 13:06:19'),
(3,7,2,'Sedang di proses','2026-07-04 04:28:55');
/*!40000 ALTER TABLE `complaint_replies` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `complaint_status_logs`
--

DROP TABLE IF EXISTS `complaint_status_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `complaint_status_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `complaint_id` int(11) unsigned NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) NOT NULL,
  `changed_by` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `complaint_status_logs_complaint_id_foreign` (`complaint_id`),
  CONSTRAINT `complaint_status_logs_complaint_id_foreign` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `complaint_status_logs`
--

LOCK TABLES `complaint_status_logs` WRITE;
/*!40000 ALTER TABLE `complaint_status_logs` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `complaint_status_logs` VALUES
(3,1,NULL,'submitted','Pelapor (System)','2026-07-03 09:57:20'),
(4,2,NULL,'submitted','Pelapor (System)','2026-07-03 10:00:46'),
(5,3,NULL,'submitted','Pelapor (System)','2026-07-03 10:00:53'),
(6,4,NULL,'submitted','Pelapor (System)','2026-07-03 10:02:50'),
(7,5,NULL,'submitted','Pelapor (System)','2026-07-03 10:03:41'),
(8,6,NULL,'submitted','Pelapor (System)','2026-07-03 10:03:56'),
(9,7,NULL,'submitted','Pelapor (System)','2026-07-03 10:06:16'),
(10,8,NULL,'submitted','Pelapor (System)','2026-07-03 10:06:38'),
(11,9,NULL,'submitted','Pelapor (System)','2026-07-03 10:10:06'),
(12,10,NULL,'submitted','Pelapor (System)','2026-07-03 10:11:49'),
(13,11,NULL,'submitted','Pelapor (System)','2026-07-03 10:20:20'),
(14,11,'submitted','verified','Admin MPP','2026-07-03 13:05:06'),
(15,11,'verified','processing','Admin MPP','2026-07-03 13:05:22'),
(16,11,'processing','waiting_response','Admin MPP','2026-07-03 13:05:42'),
(17,11,'waiting_response','resolved','Admin MPP','2026-07-03 13:06:36'),
(18,12,NULL,'submitted','Pelapor (System)','2026-07-04 03:49:03'),
(19,7,'submitted','verified','Admin DPMPTSP','2026-07-04 04:28:44'),
(20,7,'verified','processing','Admin DPMPTSP','2026-07-04 04:28:55'),
(21,7,'processing','resolved','Admin DPMPTSP','2026-07-04 04:39:06');
/*!40000 ALTER TABLE `complaint_status_logs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `complaints`
--

DROP TABLE IF EXISTS `complaints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `complaints` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_number` varchar(50) NOT NULL,
  `secret_pin` varchar(10) NOT NULL,
  `complaint_type` enum('Pengaduan','Aspirasi','Saran','Apresiasi') NOT NULL,
  `location_id` int(11) unsigned NOT NULL,
  `service_unit_id` int(11) unsigned DEFAULT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `assigned_to` int(11) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `complainant_name` varchar(255) DEFAULT NULL,
  `complainant_phone` varchar(50) DEFAULT NULL,
  `complainant_email` varchar(255) DEFAULT NULL,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('submitted','verified','processing','waiting_response','resolved','rejected') NOT NULL DEFAULT 'submitted',
  `ip_address` varchar(45) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ticket_number` (`ticket_number`),
  KEY `complaints_location_id_foreign` (`location_id`),
  KEY `complaints_service_unit_id_foreign` (`service_unit_id`),
  KEY `complaints_category_id_foreign` (`category_id`),
  KEY `complaints_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `complaints_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `complaints_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `complaint_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `complaints_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `complaints_service_unit_id_foreign` FOREIGN KEY (`service_unit_id`) REFERENCES `service_units` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `complaints`
--

LOCK TABLES `complaints` WRITE;
/*!40000 ALTER TABLE `complaints` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `complaints` VALUES
(1,'KM-2026-000001','708501','Pengaduan',2,1,1,NULL,'Test pengaduan baru','Ini adalah test deskripsi pengaduan yang cukup panjang','John Doe','08123456789',NULL,0,'submitted','::1','2026-07-03 09:57:20','2026-07-03 09:57:20'),
(2,'KM-2026-000002','398473','Pengaduan',1,NULL,1,NULL,'Test pengaduan baru','Ini adalah test deskripsi pengaduan yang cukup panjang untuk memenuhi minimal 10 karakter','John Doe','08123456789','john@example.com',0,'submitted','::1','2026-07-03 10:00:46','2026-07-03 10:00:46'),
(3,'KM-2026-000003','597268','Pengaduan',1,NULL,1,NULL,'Test pengaduan baru','Ini adalah test deskripsi pengaduan yang cukup panjang untuk memenuhi minimal 10 karakter','John Doe','08123456789','john@example.com',0,'submitted','::1','2026-07-03 10:00:53','2026-07-03 10:00:53'),
(4,'KM-2026-000004','887232','Pengaduan',1,NULL,1,NULL,'Test pengaduan baru','Ini adalah test deskripsi pengaduan yang cukup panjang untuk memenuhi minimal 10 karakter','John Doe','08123456789','john@example.com',0,'submitted','::1','2026-07-03 10:02:50','2026-07-03 10:02:50'),
(5,'KM-2026-000005','722526','Pengaduan',2,1,7,NULL,'Test pengaduan baru mpp','Ini adalah test deskripsi pengaduan mpp yang cukup panjang untuk memenuhi minimal 10 karakter','John Doe','08123456789',NULL,0,'submitted','::1','2026-07-03 10:03:41','2026-07-03 10:03:41'),
(6,'KM-2026-000006','392371','Pengaduan',1,NULL,1,NULL,'Test pengaduan anonim','Ini adalah test deskripsi pengaduan anonim yang cukup panjang untuk memenuhi minimal 10 karakter','Anonymous',NULL,NULL,1,'submitted','::1','2026-07-03 10:03:56','2026-07-03 10:03:56'),
(7,'KM-2026-000007','584456','Pengaduan',1,NULL,1,NULL,'a','b','Anonymous',NULL,NULL,1,'resolved','::1','2026-07-03 10:06:16','2026-07-04 04:39:06'),
(8,'KM-2026-000008','937610','Pengaduan',2,1,7,NULL,'tst','tst','Anonymous',NULL,NULL,1,'submitted','::1','2026-07-03 10:06:38','2026-07-03 10:06:38'),
(9,'KM-2026-000009','197982','Aspirasi',2,1,7,NULL,'test','tst','test','098432432','tst@gmail.com',0,'submitted','::1','2026-07-03 10:10:06','2026-07-03 10:10:06'),
(10,'KM-2026-000010','151239','Pengaduan',2,1,7,NULL,'tst','tst','test','3473298','tst@gmail.com',0,'submitted','::1','2026-07-03 10:11:49','2026-07-03 10:11:49'),
(11,'KM-2026-000011','109387','Pengaduan',2,2,8,NULL,'sts','tsts','Anonymous',NULL,NULL,1,'resolved','::1','2026-07-03 10:20:20','2026-07-03 13:06:36'),
(12,'KM-2026-000012','748780','Saran',2,3,7,NULL,'aa','asas','Anonymous',NULL,NULL,1,'submitted','::1','2026-07-04 03:49:03','2026-07-04 03:49:03');
/*!40000 ALTER TABLE `complaints` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `locations` VALUES
(1,'DPMPTSP'),
(2,'MPP');
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `migrations` VALUES
(3,'2026-07-03-010211','App\\Database\\Migrations\\CreateKataMasyarakatTables','default','App',1783041322,1),
(4,'2026-07-04-122000','App\\Database\\Migrations\\CreateUserComplaintReadsTable','default','App',1783138820,2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `service_units`
--

DROP TABLE IF EXISTS `service_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_units` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(11) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `service_units_location_id_foreign` (`location_id`),
  CONSTRAINT `service_units_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_units`
--

LOCK TABLES `service_units` WRITE;
/*!40000 ALTER TABLE `service_units` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `service_units` VALUES
(1,2,'Front Office'),
(2,2,'Tenant BPJS'),
(3,2,'Tenant BRI'),
(4,2,'Tenant Dukcapil'),
(5,2,'Lainnya');
/*!40000 ALTER TABLE `service_units` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `user_complaint_reads`
--

DROP TABLE IF EXISTS `user_complaint_reads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_complaint_reads` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `complaint_id` int(11) unsigned NOT NULL,
  `read_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_complaint_id` (`user_id`,`complaint_id`),
  KEY `user_complaint_reads_complaint_id_foreign` (`complaint_id`),
  CONSTRAINT `user_complaint_reads_complaint_id_foreign` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_complaint_reads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_complaint_reads`
--

LOCK TABLES `user_complaint_reads` WRITE;
/*!40000 ALTER TABLE `user_complaint_reads` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `user_complaint_reads` VALUES
(1,1,12,'2026-07-04 04:21:51'),
(2,1,11,'2026-07-04 04:21:58'),
(3,1,10,'2026-07-04 04:22:03'),
(4,1,9,'2026-07-04 04:22:05'),
(5,1,8,'2026-07-04 04:22:07'),
(6,1,5,'2026-07-04 04:22:10'),
(7,1,2,'2026-07-04 04:24:39'),
(8,2,7,'2026-07-04 04:27:44'),
(9,2,6,'2026-07-04 04:34:51'),
(10,2,4,'2026-07-04 04:38:14'),
(11,1,7,'2026-07-04 04:39:56');
/*!40000 ALTER TABLE `user_complaint_reads` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin_dpmptsp','admin_mpp','pic_unit') NOT NULL,
  `location_id` int(11) unsigned DEFAULT NULL,
  `service_unit_id` int(11) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `users_location_id_foreign` (`location_id`),
  KEY `users_service_unit_id_foreign` (`service_unit_id`),
  CONSTRAINT `users_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `users_service_unit_id_foreign` FOREIGN KEY (`service_unit_id`) REFERENCES `service_units` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'Global Super Admin','superadmin@katamasyarakat.local','$2y$12$Mjeb/9oTBssC3XtZA8uqjOgLkMqIAFTUNUmWPTLGeRGh2zhypJaAS','superadmin',NULL,NULL,1,'2026-07-03 01:15:22','2026-07-03 01:15:22'),
(2,'Admin DPMPTSP','admin.dpmptsp@katamasyarakat.go.id','$2y$12$Mjeb/9oTBssC3XtZA8uqjOgLkMqIAFTUNUmWPTLGeRGh2zhypJaAS','admin_dpmptsp',1,NULL,1,'2026-07-03 01:15:22','2026-07-04 04:20:55'),
(3,'Admin MPP','admin.mpp@katamasyarakat.go.id','$2y$12$Mjeb/9oTBssC3XtZA8uqjOgLkMqIAFTUNUmWPTLGeRGh2zhypJaAS','admin_mpp',2,NULL,1,'2026-07-03 01:15:22','2026-07-03 01:15:22');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-07-04 12:48:49
