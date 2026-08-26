-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: dishub_probolinggo_db
-- ------------------------------------------------------
-- Server version	8.4.3

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

CREATE DATABASE IF NOT EXISTS `dishub_probolinggo_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `dishub_probolinggo_db`;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'guest',
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baru',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
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
-- Table structure for table `gallery_albums`
--

DROP TABLE IF EXISTS `gallery_albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_albums` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photos` json DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gallery_albums_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_albums`
--

LOCK TABLES `gallery_albums` WRITE;
/*!40000 ALTER TABLE `gallery_albums` DISABLE KEYS */;
INSERT INTO `gallery_albums` VALUES (1,'Operasi Gabungan Penertiban Angkutan Barang & Uji Emisi','operasi-gabungan-penertiban-angkutan-barang','Dokumentasi penertiban kelebihan muatan (ODOL) dan uji emisi kendaraan barang di jalur Pantura Probolinggo.','https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800','[\"https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800\", \"https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=800\", \"https://images.unsplash.com/photo-1570125909232-eb263c188f7e?w=800\", \"https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800\", \"https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=800\", \"https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800\"]',2,'2026-08-24 03:36:43','2026-08-24 03:36:43'),(2,'Apel Kesiapsiagaan Pos Pelayanan Terpadu LLAJ DISHUB','apel-kesiapsiagaan-pos-pelayanan-terpadu-llaj','Apel bersama petugas posko pemantauan arus mudik dan rekayasa jalur wisata.','https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800','[\"https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800\", \"https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=800\", \"https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=800\", \"https://images.unsplash.com/photo-1570125909232-eb263c188f7e?w=800\"]',2,'2026-08-24 03:36:43','2026-08-24 03:36:43');
/*!40000 ALTER TABLE `gallery_albums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hero_sliders`
--

DROP TABLE IF EXISTS `hero_sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hero_sliders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hero_sliders`
--

LOCK TABLES `hero_sliders` WRITE;
/*!40000 ALTER TABLE `hero_sliders` DISABLE KEYS */;
/*!40000 ALTER TABLE `hero_sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `informasi_tabs`
--

DROP TABLE IF EXISTS `informasi_tabs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `informasi_tabs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'fas fa-newspaper',
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `filter_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `filter_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `informasi_tabs_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `informasi_tabs`
--

LOCK TABLES `informasi_tabs` WRITE;
/*!40000 ALTER TABLE `informasi_tabs` DISABLE KEYS */;
INSERT INTO `informasi_tabs` VALUES (1,'Semua Berita','semua-berita','fas fa-newspaper',1,1,'all',NULL,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(2,'Pemerintahan','pemerintahan','fas fa-landmark',2,1,'category','Pemerintahan','2026-08-24 03:36:42','2026-08-24 03:36:42'),(3,'Lalu Lintas','lalu-lintas','fas fa-traffic-light',3,1,'category','Lalu Lintas','2026-08-24 03:36:42','2026-08-24 03:36:42'),(4,'Pelayanan','pelayanan','fas fa-car-side',4,1,'category','Pelayanan Publik','2026-08-24 03:36:42','2026-08-24 03:36:42');
/*!40000 ALTER TABLE `informasi_tabs` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2026_08_03_000001_create_site_settings_table',1),(6,'2026_08_03_000002_create_navigation_menus_table',1),(7,'2026_08_03_000003_create_hero_sliders_table',1),(8,'2026_08_03_000004_create_news_items_table',1),(9,'2026_08_03_000005_create_sidebar_widgets_table',1),(10,'2026_08_03_000006_create_related_links_table',1),(11,'2026_08_03_000007_create_pages_table',1),(12,'2026_08_03_000008_create_public_documents_table',1),(13,'2026_08_03_000009_create_contact_messages_table',1),(14,'2026_08_04_000001_create_activity_logs_table',1),(15,'2026_08_10_000001_create_org_charts_table',1),(16,'2026_08_18_000001_create_services_table',1),(17,'2026_08_18_000002_create_informasi_tabs_table',1),(18,'2026_08_18_000003_add_created_by_to_news_and_documents',1),(19,'2026_08_19_000001_add_is_active_and_avatar_to_users_table',1),(20,'2026_08_19_000002_add_missing_columns_to_public_documents',1),(21,'2026_08_19_000003_create_gallery_and_video_tables',1),(22,'2026_08_20_000001_add_image_url_and_pdf_url_to_pages_table',1),(23,'2026_08_20_000002_add_missing_columns_to_navigation_menus_table',1),(24,'2026_08_24_000001_add_whatsapp_and_referral_code_to_users_table',1),(25,'2026_08_24_000002_add_pdf_url_to_services_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `navigation_menus`
--

DROP TABLE IF EXISTS `navigation_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `navigation_menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#',
  `parent_id` bigint unsigned DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `target` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `navigation_menus_parent_id_foreign` (`parent_id`),
  CONSTRAINT `navigation_menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `navigation_menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `navigation_menus`
--

LOCK TABLES `navigation_menus` WRITE;
/*!40000 ALTER TABLE `navigation_menus` DISABLE KEYS */;
INSERT INTO `navigation_menus` VALUES (1,'HOME','/',NULL,1,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(2,'PROFIL','#',NULL,2,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(3,'Struktur Organisasi','/halaman/struktur-organisasi',2,1,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(4,'Visi Misi','/halaman/visi-misi',2,2,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(5,'Tugas dan Fungsi','/halaman/tugas-dan-fungsi',2,3,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(6,'Survei Kepuasan Masyarakat','/halaman/survei-kepuasan-masyarakat',2,4,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(7,'LAYANAN','#',NULL,3,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(8,'Semua Layanan Publik DISHUB','/layanan',7,1,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(9,'Pendaftaran Uji Berkala KIR Online','/layanan/pendaftaran-uji-kir-online',7,2,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(10,'Permohonan Izin Trayek Angkutan','/layanan/izin-trayek-angkutan-umum',7,3,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(11,'Pengaduan Lalu Lintas & PJU','/layanan/pengaduan-lalu-lintas',7,4,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(12,'DOKUMEN','#',NULL,4,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(13,'Perencanaan Kinerja','/dokumen/perencanaan-kinerja',12,1,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(14,'Pengukuran Kinerja','/dokumen/pengukuran-kinerja',12,2,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(15,'Pelaporan Kinerja','/dokumen/pelaporan-kinerja',12,3,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(16,'Evaluasi Kinerja','/dokumen/evaluasi-kinerja',12,4,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(17,'INFORMASI','#',NULL,5,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(18,'Berita','/informasi',17,1,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(19,'PPID','https://ppid.probolinggokab.go.id/',17,2,'_blank',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(20,'Video','/video',17,3,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(21,'Galery','/galery',17,4,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(22,'HUBUNGI','#',NULL,6,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(23,'Lapor SP4N','https://www.lapor.go.id/',22,1,'_blank',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(24,'Kontak','/kontak',22,2,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(25,'LOGIN','/login',NULL,7,'_self',NULL,NULL,NULL,1,'2026-08-24 03:36:42','2026-08-24 03:36:42');
/*!40000 ALTER TABLE `navigation_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_items`
--

DROP TABLE IF EXISTS `news_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Berita',
  `published_at` date DEFAULT NULL,
  `views` int NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `news_items_slug_unique` (`slug`),
  KEY `news_items_created_by_foreign` (`created_by`),
  CONSTRAINT `news_items_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_items`
--

LOCK TABLES `news_items` WRITE;
/*!40000 ALTER TABLE `news_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `news_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `org_charts`
--

DROP TABLE IF EXISTS `org_charts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `org_charts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line_type` enum('command','coordination') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'command',
  `order_no` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `org_charts_parent_id_foreign` (`parent_id`),
  CONSTRAINT `org_charts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `org_charts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `org_charts`
--

LOCK TABLES `org_charts` WRITE;
/*!40000 ALTER TABLE `org_charts` DISABLE KEYS */;
/*!40000 ALTER TABLE `org_charts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
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
-- Table structure for table `public_documents`
--

DROP TABLE IF EXISTS `public_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `public_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'perencanaan-kinerja',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Rencana Strategis',
  `tahun` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_zip_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_zip_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `download_count` int NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `public_documents_created_by_foreign` (`created_by`),
  CONSTRAINT `public_documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `public_documents`
--

LOCK TABLES `public_documents` WRITE;
/*!40000 ALTER TABLE `public_documents` DISABLE KEYS */;
INSERT INTO `public_documents` VALUES (1,'Rencana Strategis (Renstra) Dinas Perhubungan Tahun 2024-2029','perencanaan-kinerja','Rencana Strategis','2026',NULL,'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',NULL,NULL,185,2,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(2,'Pohon Kinerja Dinas Perhubungan Tahun 2026','perencanaan-kinerja','Pohon Kinerja','2026',NULL,'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',NULL,NULL,98,2,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(3,'Capaian Kinerja Dinas Perhubungan Triwulan IV Tahun 2025','pengukuran-kinerja','Capaian Kinerja','2025',NULL,'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',NULL,NULL,120,2,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(4,'Laporan Akuntabilitas Kinerja Instansi Pemerintah (LAKIP / LKjIP) Tahun 2025','pelaporan-kinerja','LAKIP / LKjIP','2025',NULL,'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',NULL,NULL,340,2,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(5,'LHE AKIP 2025 (Lembar Hasil Evaluasi AKIP)','evaluasi-kinerja','Lembar Hasil Evaluasi (LHE)','2025',NULL,'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',NULL,NULL,275,2,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(6,'Laporan Hasil Evaluasi Akuntabilitas Kinerja Tahun 2024','evaluasi-kinerja','Lembar Hasil Evaluasi (LHE)','2024',NULL,'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',NULL,NULL,150,2,'2026-08-24 03:36:42','2026-08-24 03:36:42');
/*!40000 ALTER TABLE `public_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `related_links`
--

DROP TABLE IF EXISTS `related_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `related_links` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#',
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `related_links`
--

LOCK TABLES `related_links` WRITE;
/*!40000 ALTER TABLE `related_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `related_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'fas fa-cogs',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Umum',
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`),
  KEY `services_created_by_foreign` (`created_by`),
  CONSTRAINT `services_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Pendaftaran Uji Berkala Kendaraan (KIR) Online','pendaftaran-uji-kir-online','Daftarkan kendaraan Anda untuk uji berkala (KIR) secara online tanpa harus antri di kantor.','Layanan Uji Berkala Kendaraan Bermotor (KIR) adalah kewajiban bagi setiap kendaraan angkutan umum dan kendaraan barang.\n\nSyarat Pendaftaran:\n1. Fotokopi STNK\n2. Fotokopi BPKB\n3. KTP pemilik kendaraan\n4. Buku Uji lama (perpanjangan)\n\nProsedur:\n1. Daftar secara online melalui portal ini\n2. Pilih jadwal dan slot waktu yang tersedia\n3. Datang ke kantor DISHUB pada jadwal yang telah dipilih\n4. Kendaraan akan diuji oleh petugas teknis\n\nBiaya: Sesuai Perda Kab. Probolinggo','https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600',NULL,'fas fa-car-side','Pengujian Kendaraan',1,1,2,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(2,'Permohonan Izin Trayek Angkutan Umum','izin-trayek-angkutan-umum','Ajukan permohonan izin trayek angkutan umum (bus, angkot, angdes) resmi di wilayah Kabupaten Probolinggo.','Izin Trayek adalah dokumen wajib bagi operator angkutan umum yang beroperasi di wilayah Kabupaten Probolinggo.\n\nSyarat Dokumen:\n1. Akta pendirian perusahaan\n2. NPWP perusahaan\n3. Daftar kendaraan dengan STNK\n4. Sertifikat uji berkala kendaraan\n5. KTP direktur/pemilik\n\nBiaya: Gratis (sesuai kebijakan berlaku)','https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=600',NULL,'fas fa-bus','Perizinan',2,1,2,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(3,'Pengaduan Lalu Lintas & Infrastruktur Jalan','pengaduan-lalu-lintas','Laporkan permasalahan lalu lintas, rambu rusak, atau infrastruktur jalan yang membahayakan keselamatan.','Layanan pengaduan lalu lintas tersedia untuk masyarakat yang ingin melaporkan:\n\n- Rambu lalu lintas rusak/hilang\n- Lampu penerangan jalan mati (PJU)\n- Jalan rusak yang membahayakan\n- Kendaraan angkutan tidak laik jalan\n- Pelanggaran trayek angkutan umum\n\nCara Lapor:\n1. Layanan Cepat WhatsApp Halo SAE: 0821 3100 1001\n2. Isi form di halaman Kontak DISHUB\n3. Atau hubungi telepon: 0335-421554\n4. Atau melalui kanal SP4N LAPOR! online','https://images.unsplash.com/photo-1508873696983-2df515122519?w=600',NULL,'fas fa-exclamation-triangle','Pengaduan',3,1,2,'2026-08-24 03:36:42','2026-08-24 03:36:42');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sidebar_widgets`
--

DROP TABLE IF EXISTS `sidebar_widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sidebar_widgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sidebar_widgets`
--

LOCK TABLES `sidebar_widgets` WRITE;
/*!40000 ALTER TABLE `sidebar_widgets` DISABLE KEYS */;
/*!40000 ALTER TABLE `sidebar_widgets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
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
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'aditya','aditya','aditya@dishub.probolinggokab.go.id','082131001001','ADITYA2026',NULL,'$2y$12$DgSKakxzLubu2ltPO9RLwOVS8OCI4Plmbd0431ASg7sYTV/3mteTW','super_admin',1,1,NULL,NULL,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(2,'Administrator DISHUB','admin','admin@dishub.probolinggokab.go.id','081234567890','ADMIN2026',NULL,'$2y$12$RR9SE87QHnPdi4v1QoGrReMQai6RhpmgXVa6Wab5zBkLH/SCRFuJS','admin',0,1,NULL,NULL,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(3,'Sukma Anggota Staf','sukma','sukma@dishub.probolinggokab.go.id','089876543210','SUKMA2026',NULL,'$2y$12$.VTngCioufxfZG1jHIVz3eG9hdI29b.lKZZhrBhu.uk4zhC3FUyn6','anggota',0,1,NULL,NULL,'2026-08-24 03:36:42','2026-08-24 03:36:42');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video_items`
--

DROP TABLE IF EXISTS `video_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `video_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `published_at` date DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `video_items_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video_items`
--

LOCK TABLES `video_items` WRITE;
/*!40000 ALTER TABLE `video_items` DISABLE KEYS */;
INSERT INTO `video_items` VALUES (1,'Sosialisasi Keselamatan Berlalulintas & Uji KIR Kendaraan Bermotor DISHUB Kabupaten Probolinggo','sosialisasi-keselamatan-berlalulintas-uji-kir','https://www.youtube.com/watch?v=dQw4w9WgXcQ','https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=600','Dokumentasi kegiatan sosialisasi tertib berlalu lintas serta pentingnya uji berkala kelaikan kendaraan bermotor.','2026-08-19',2,'2026-08-24 03:36:42','2026-08-24 03:36:42'),(2,'Ramp Check Kesiapan Bus Antarkota Menjelang Libur Panjang di Terminal Tipe B Kraksaan','ramp-check-kesiapan-bus-antarkota','https://www.youtube.com/watch?v=dQw4w9WgXcQ','https://images.unsplash.com/photo-1570125909232-eb263c188f7e?w=600','Pemeriksaan teknis dan kelaikan operasional bus penumpang umum demi menjamin kenyamanan penumpang.','2026-08-12',2,'2026-08-24 03:36:42','2026-08-24 03:36:42');
/*!40000 ALTER TABLE `video_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'dishub_probolinggo_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-24 10:36:57
