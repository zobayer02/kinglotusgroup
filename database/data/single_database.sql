-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: kinglotusgroup_auth
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
--



--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'super_admin',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `session_version` int(10) unsigned NOT NULL DEFAULT 1,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'Super Admin','A S M Zobayer','zobayer1084@gmail.com','01700000000','$2y$12$S.vvzNR5k4m7OMCHj6HsL.bPn0wYzo0gB3zhqCiaEOSVUfJeCd43C','super_admin','2026-09-27 04:19:28','127.0.0.1',NULL,4,NULL,NULL,NULL,'2026-06-25 09:55:14','2026-09-27 04:19:28');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('site.home.data','a:10:{s:6:\"notice\";O:21:\"App\\Models\\SiteNotice\":33:{s:13:\"\0*\0connection\";s:7:\"content\";s:8:\"\0*\0table\";s:12:\"site_notices\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:1;s:5:\"title\";N;s:7:\"message\";s:970:\"📢 বিশেষ ডিসকাউন্ট অফার !! আমাদের সম্মানিত বিনিয়োগকারীদের জন্য এই মুহূর্তে আকর্ষণীয় ডিসকাউন্ট অফার চলছে । ক্রমান্বয়ে শেয়ারের মূল্য ও পরিমাণ দেওয়া হলো : ৪,৪৫,০০০ টাকা মূল্যে — ২,০০০ শেয়ার | ৫,৬৫,০০০ টাকা মূল্যে — ২,০০০ শেয়ার | ৭,৪৫,০০০ টাকা মূল্যে — ৫,০০০ শেয়ার | ৯,২৫,০০০ টাকা মূল্যে — শেষ ১০,০০০ শেয়ার | অফারটি সীমিত সময়ের জন্য ! আজই আপনার কাঙ্ক্ষিত শেয়ারটি নিশ্চিত করুন ।\";s:9:\"is_active\";i:1;s:20:\"hero_background_path\";s:54:\"uploads/hero/ea46b741-58db-4081-861a-606d0901deb3.webp\";s:10:\"created_at\";s:19:\"2026-06-26 18:57:22\";s:10:\"updated_at\";s:19:\"2026-09-26 21:39:09\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:1;s:5:\"title\";N;s:7:\"message\";s:970:\"📢 বিশেষ ডিসকাউন্ট অফার !! আমাদের সম্মানিত বিনিয়োগকারীদের জন্য এই মুহূর্তে আকর্ষণীয় ডিসকাউন্ট অফার চলছে । ক্রমান্বয়ে শেয়ারের মূল্য ও পরিমাণ দেওয়া হলো : ৪,৪৫,০০০ টাকা মূল্যে — ২,০০০ শেয়ার | ৫,৬৫,০০০ টাকা মূল্যে — ২,০০০ শেয়ার | ৭,৪৫,০০০ টাকা মূল্যে — ৫,০০০ শেয়ার | ৯,২৫,০০০ টাকা মূল্যে — শেষ ১০,০০০ শেয়ার | অফারটি সীমিত সময়ের জন্য ! আজই আপনার কাঙ্ক্ষিত শেয়ারটি নিশ্চিত করুন ।\";s:9:\"is_active\";i:1;s:20:\"hero_background_path\";s:54:\"uploads/hero/ea46b741-58db-4081-861a-606d0901deb3.webp\";s:10:\"created_at\";s:19:\"2026-06-26 18:57:22\";s:10:\"updated_at\";s:19:\"2026-09-26 21:39:09\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:5:\"title\";i:1;s:7:\"message\";i:2;s:9:\"is_active\";i:3;s:20:\"hero_background_path\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}s:12:\"aboutSection\";O:23:\"App\\Models\\AboutSection\":33:{s:13:\"\0*\0connection\";s:7:\"content\";s:8:\"\0*\0table\";s:14:\"about_sections\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:10:{s:2:\"id\";i:1;s:5:\"title\";s:22:\"About King Lotus Group\";s:8:\"subtitle\";s:50:\"A Luxury & Signature Destination in Cox’s Bazar.\";s:11:\"description\";s:369:\"King Lotus International, under construction in the heart of Cox’s Bazar, is a luxury signature destination designed to redefine world-class hospitality. With elegant architecture, breathtaking sea views, premium facilities, and personalized service, we are creating an exclusive lifestyle experience where every moment reflects comfort, beauty, trust and excellence.\";s:14:\"left_video_url\";s:48:\"https://youtu.be/Ghyljd1crBk?si=6d313vXfPsiVAoor\";s:15:\"right_video_url\";s:48:\"https://youtu.be/IJH-LOwfOhk?si=-NkVzUfz7pW_LGYK\";s:19:\"left_thumbnail_path\";s:55:\"uploads/about/b9e3f459-b627-4927-b861-bf59db445355.webp\";s:20:\"right_thumbnail_path\";s:55:\"uploads/about/fa668884-ba56-49d9-a3f5-82766225639f.webp\";s:10:\"created_at\";s:19:\"2026-06-26 20:00:06\";s:10:\"updated_at\";s:19:\"2026-06-26 21:14:53\";}s:11:\"\0*\0original\";a:10:{s:2:\"id\";i:1;s:5:\"title\";s:22:\"About King Lotus Group\";s:8:\"subtitle\";s:50:\"A Luxury & Signature Destination in Cox’s Bazar.\";s:11:\"description\";s:369:\"King Lotus International, under construction in the heart of Cox’s Bazar, is a luxury signature destination designed to redefine world-class hospitality. With elegant architecture, breathtaking sea views, premium facilities, and personalized service, we are creating an exclusive lifestyle experience where every moment reflects comfort, beauty, trust and excellence.\";s:14:\"left_video_url\";s:48:\"https://youtu.be/Ghyljd1crBk?si=6d313vXfPsiVAoor\";s:15:\"right_video_url\";s:48:\"https://youtu.be/IJH-LOwfOhk?si=-NkVzUfz7pW_LGYK\";s:19:\"left_thumbnail_path\";s:55:\"uploads/about/b9e3f459-b627-4927-b861-bf59db445355.webp\";s:20:\"right_thumbnail_path\";s:55:\"uploads/about/fa668884-ba56-49d9-a3f5-82766225639f.webp\";s:10:\"created_at\";s:19:\"2026-06-26 20:00:06\";s:10:\"updated_at\";s:19:\"2026-06-26 21:14:53\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:5:\"title\";i:1;s:8:\"subtitle\";i:2;s:11:\"description\";i:3;s:14:\"left_video_url\";i:4;s:15:\"right_video_url\";i:5;s:19:\"left_thumbnail_path\";i:6;s:20:\"right_thumbnail_path\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}s:10:\"whySection\";O:21:\"App\\Models\\WhySection\":33:{s:13:\"\0*\0connection\";s:7:\"content\";s:8:\"\0*\0table\";s:12:\"why_sections\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:10:{s:2:\"id\";i:1;s:5:\"title\";s:20:\"Why King Lotus Group\";s:11:\"description\";s:288:\"King Lotus International is an upcoming mega hospitality project designed with a halal, safe and family-friendly concept. Spread across 50 acres, the project will feature a modern five-star hotel, luxury rooms, premium facilities and a secure environment for comfortable family vacations.\";s:14:\"feature_points\";s:128:\"Prime Tourism Location\r\nModern Luxury Living\r\nSecure Investment Opportunity\r\nFamily-Friendly Facilities\r\nWorld-Class Hospitality\";s:9:\"cta_label\";s:30:\"Investment Opportunity Details\";s:7:\"cta_url\";N;s:9:\"video_url\";s:48:\"https://youtu.be/Z6e_jTDr_YM?si=m9YLs5vgXsrQapwF\";s:14:\"thumbnail_path\";s:53:\"uploads/why/5c901a55-acc8-4adb-80fb-85c9064836f0.webp\";s:10:\"created_at\";s:19:\"2026-06-27 11:18:51\";s:10:\"updated_at\";s:19:\"2026-06-27 15:51:00\";}s:11:\"\0*\0original\";a:10:{s:2:\"id\";i:1;s:5:\"title\";s:20:\"Why King Lotus Group\";s:11:\"description\";s:288:\"King Lotus International is an upcoming mega hospitality project designed with a halal, safe and family-friendly concept. Spread across 50 acres, the project will feature a modern five-star hotel, luxury rooms, premium facilities and a secure environment for comfortable family vacations.\";s:14:\"feature_points\";s:128:\"Prime Tourism Location\r\nModern Luxury Living\r\nSecure Investment Opportunity\r\nFamily-Friendly Facilities\r\nWorld-Class Hospitality\";s:9:\"cta_label\";s:30:\"Investment Opportunity Details\";s:7:\"cta_url\";N;s:9:\"video_url\";s:48:\"https://youtu.be/Z6e_jTDr_YM?si=m9YLs5vgXsrQapwF\";s:14:\"thumbnail_path\";s:53:\"uploads/why/5c901a55-acc8-4adb-80fb-85c9064836f0.webp\";s:10:\"created_at\";s:19:\"2026-06-27 11:18:51\";s:10:\"updated_at\";s:19:\"2026-06-27 15:51:00\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:5:\"title\";i:1;s:11:\"description\";i:2;s:14:\"feature_points\";i:3;s:9:\"cta_label\";i:4;s:7:\"cta_url\";i:5;s:9:\"video_url\";i:6;s:14:\"thumbnail_path\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}s:14:\"projectSection\";O:25:\"App\\Models\\ProjectSection\":33:{s:13:\"\0*\0connection\";s:7:\"content\";s:8:\"\0*\0table\";s:16:\"project_sections\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:10:{s:2:\"id\";i:1;s:9:\"top_title\";s:61:\"Luxury, Comfort and Exceptional Service — All In One Place.\";s:16:\"top_button_label\";N;s:14:\"top_button_url\";N;s:9:\"top_cards\";s:429:\"[{\"order\":1,\"title\":\"Luxury Studio Hotel\",\"location\":\"Cox Bazar\",\"image_path\":\"uploads\\/projects\\/1b61a7f1-b573-45f1-a2d5-b31b788998fe.webp\"},{\"order\":2,\"title\":\"King Lotus International\",\"location\":\"Cox Bazar\",\"image_path\":\"uploads\\/projects\\/a692328a-277f-4ae9-9dd5-ec5b9985977d.webp\"},{\"order\":3,\"title\":\"Luxury 3 Star Hotel\",\"location\":\"Cox Bazar\",\"image_path\":\"uploads\\/projects\\/4d7cfa1e-780c-4ad7-83c2-b31a9867a1e5.webp\"}]\";s:12:\"bottom_title\";s:34:\"Premium Destination for Relaxation\";s:12:\"bottom_cards\";s:1407:\"[{\"order\":1,\"title\":\"Sea World\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/bfe0758b-97a1-46ff-b996-0af877b0115c.webp\"},{\"order\":2,\"title\":\"Ladies Exclusive Zone\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/ac5dad34-0d5f-4642-9987-677105c9440a.webp\"},{\"order\":3,\"title\":\"Gents Zone\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/3007965c-02c3-4b44-a86a-d50b756e4d7f.webp\"},{\"order\":4,\"title\":\"Family Fun Zone\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/ce704a95-5548-494d-bff9-d474a2061f7a.webp\"},{\"order\":5,\"title\":\"Competition Pool\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/ff9e1ae7-896e-489b-9a21-148741672276.webp\"},{\"order\":6,\"title\":\"Relax pool\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/cc9c26a3-6c98-4f24-aa20-c880ef48b7bb.webp\"},{\"order\":7,\"title\":\"Elegant Mosque\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/b5f891e1-6584-4da3-955d-aa36c6950d90.webp\"},{\"order\":8,\"title\":\"Baby Entertaining Point\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/6702a38b-c1ea-4497-8f2b-32a4ef30d5e3.webp\"},{\"order\":9,\"title\":\"Shopping mall\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/c402215c-faff-424a-ace4-06e65d2c87e5.webp\"},{\"order\":10,\"title\":\"Live BBQ Village\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/626e7653-eaca-4616-9078-dfd636d2ef4f.webp\"},{\"order\":11,\"title\":\"Indoor stadium\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/63b39ba4-bf9c-4286-85d0-747f1dade7fc.webp\"}]\";s:5:\"cards\";N;s:10:\"created_at\";s:19:\"2026-06-27 21:12:27\";s:10:\"updated_at\";s:19:\"2026-09-26 15:45:01\";}s:11:\"\0*\0original\";a:10:{s:2:\"id\";i:1;s:9:\"top_title\";s:61:\"Luxury, Comfort and Exceptional Service — All In One Place.\";s:16:\"top_button_label\";N;s:14:\"top_button_url\";N;s:9:\"top_cards\";s:429:\"[{\"order\":1,\"title\":\"Luxury Studio Hotel\",\"location\":\"Cox Bazar\",\"image_path\":\"uploads\\/projects\\/1b61a7f1-b573-45f1-a2d5-b31b788998fe.webp\"},{\"order\":2,\"title\":\"King Lotus International\",\"location\":\"Cox Bazar\",\"image_path\":\"uploads\\/projects\\/a692328a-277f-4ae9-9dd5-ec5b9985977d.webp\"},{\"order\":3,\"title\":\"Luxury 3 Star Hotel\",\"location\":\"Cox Bazar\",\"image_path\":\"uploads\\/projects\\/4d7cfa1e-780c-4ad7-83c2-b31a9867a1e5.webp\"}]\";s:12:\"bottom_title\";s:34:\"Premium Destination for Relaxation\";s:12:\"bottom_cards\";s:1407:\"[{\"order\":1,\"title\":\"Sea World\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/bfe0758b-97a1-46ff-b996-0af877b0115c.webp\"},{\"order\":2,\"title\":\"Ladies Exclusive Zone\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/ac5dad34-0d5f-4642-9987-677105c9440a.webp\"},{\"order\":3,\"title\":\"Gents Zone\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/3007965c-02c3-4b44-a86a-d50b756e4d7f.webp\"},{\"order\":4,\"title\":\"Family Fun Zone\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/ce704a95-5548-494d-bff9-d474a2061f7a.webp\"},{\"order\":5,\"title\":\"Competition Pool\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/ff9e1ae7-896e-489b-9a21-148741672276.webp\"},{\"order\":6,\"title\":\"Relax pool\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/cc9c26a3-6c98-4f24-aa20-c880ef48b7bb.webp\"},{\"order\":7,\"title\":\"Elegant Mosque\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/b5f891e1-6584-4da3-955d-aa36c6950d90.webp\"},{\"order\":8,\"title\":\"Baby Entertaining Point\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/6702a38b-c1ea-4497-8f2b-32a4ef30d5e3.webp\"},{\"order\":9,\"title\":\"Shopping mall\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/c402215c-faff-424a-ace4-06e65d2c87e5.webp\"},{\"order\":10,\"title\":\"Live BBQ Village\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/626e7653-eaca-4616-9078-dfd636d2ef4f.webp\"},{\"order\":11,\"title\":\"Indoor stadium\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/63b39ba4-bf9c-4286-85d0-747f1dade7fc.webp\"}]\";s:5:\"cards\";N;s:10:\"created_at\";s:19:\"2026-06-27 21:12:27\";s:10:\"updated_at\";s:19:\"2026-09-26 15:45:01\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:9:\"top_cards\";s:5:\"array\";s:12:\"bottom_cards\";s:5:\"array\";s:5:\"cards\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:9:\"top_title\";i:1;s:16:\"top_button_label\";i:2;s:14:\"top_button_url\";i:3;s:12:\"bottom_title\";i:4;s:9:\"top_cards\";i:5;s:12:\"bottom_cards\";i:6;s:5:\"cards\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}s:17:\"prospectusSection\";O:28:\"App\\Models\\ProspectusSection\":33:{s:13:\"\0*\0connection\";s:7:\"content\";s:8:\"\0*\0table\";s:19:\"prospectus_sections\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:1;s:13:\"section_title\";s:29:\"Project Prospectus & Brochure\";s:16:\"section_subtitle\";s:146:\"কিং লোটাস ইন্টারন্যাশনালের পূর্ণাঙ্গ প্রকল্প রূপরেখা\";s:10:\"is_visible\";i:1;s:9:\"brochures\";s:3524:\"[{\"title\":\"5-Star Luxury in Royal\",\"subtitle\":\"\\u0995\\u09bf\\u0982 \\u09b2\\u09cb\\u099f\\u09be\\u09b8 \\u0987\\u09a8\\u09cd\\u099f\\u09be\\u09b0\\u09a8\\u09cd\\u09af\\u09be\\u09b6\\u09a8\\u09be\\u09b2 \\u2014 \\u09ae\\u09c7\\u09b0\\u09bf\\u09a8 \\u09a1\\u09cd\\u09b0\\u09be\\u0987\\u09ad \\u09b0\\u09cb\\u09a1, \\u0995\\u0995\\u09cd\\u09b8\\u09ac\\u09be\\u099c\\u09be\\u09b0\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-01-cover.jpg\"},{\"title\":\"\\u09ad\\u09bf\\u09b6\\u09a8, \\u09ae\\u09bf\\u09b6\\u09a8 \\u0993 \\u098f\\u09ae\\u09a1\\u09bf\\u09b0 \\u09ac\\u09be\\u09b0\\u09cd\\u09a4\\u09be\",\"subtitle\":\"\\u0986\\u09a8\\u09cd\\u09a4\\u09b0\\u09cd\\u099c\\u09be\\u09a4\\u09bf\\u0995 \\u09ae\\u09be\\u09a8\\u09c7\\u09b0 \\u0986\\u09a4\\u09bf\\u09a5\\u09c7\\u09af\\u09bc\\u09a4\\u09be \\u0993 \\u099f\\u09c7\\u0995\\u09b8\\u0987 \\u09aa\\u09b0\\u09cd\\u09af\\u099f\\u09a8\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-02-vision-md-message.jpg\"},{\"title\":\"\\u09a8\\u09bf\\u09b0\\u09cd\\u09ae\\u09be\\u09a3\\u09be\\u09a7\\u09c0\\u09a8 \\u09ab\\u09be\\u0987\\u09ad \\u09b8\\u09cd\\u099f\\u09be\\u09b0 \\u09b9\\u09cb\\u099f\\u09c7\\u09b2\",\"subtitle\":\"Luxury Suites, Deluxe Rooms, Dining & Wellness\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-03-5-star-hotel.jpg\"},{\"title\":\"\\u09ac\\u09bf\\u09b6\\u09cd\\u09ac\\u09ae\\u09be\\u09a8\\u09c7\\u09b0 \\u09b8\\u09c1\\u09ac\\u09bf\\u09a7\\u09be\\u09b8\\u09ae\\u09c2\\u09b9\",\"subtitle\":\"Recreation, Corporate Facilities, Lifestyle & Kids Zone\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-04-world-class-amenities.jpg\"},{\"title\":\"\\u09ae\\u09c7\\u0997\\u09be \\u0986\\u0995\\u09b0\\u09cd\\u09b7\\u09a3 (\\u0985\\u0982\\u09b6 \\u09e7)\",\"subtitle\":\"\\u09a5\\u09cd\\u09b0\\u09bf-\\u09b8\\u09cd\\u099f\\u09be\\u09b0, \\u09b8\\u09cd\\u099f\\u09c1\\u09a1\\u09bf\\u0993 \\u09b9\\u09cb\\u099f\\u09c7\\u09b2, \\u09ac\\u09c7\\u09ac\\u09bf \\u098f\\u09a8\\u09cd\\u099f\\u09be\\u09b0\\u099f\\u09c7\\u0987\\u09a8\\u09ae\\u09c7\\u09a8\\u09cd\\u099f \\u0993 \\u09ac\\u09be\\u09b0\\u09ac\\u09bf\\u0995\\u09bf\\u0989 \\u09ad\\u09bf\\u09b2\\u09c7\\u099c\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-05-mega-attractions-part-1.jpg\"},{\"title\":\"\\u09ae\\u09c7\\u0997\\u09be \\u0986\\u0995\\u09b0\\u09cd\\u09b7\\u09a3 (\\u0985\\u0982\\u09b6 \\u09e8)\",\"subtitle\":\"\\u09ae\\u09c7\\u0997\\u09be \\u09b6\\u09aa\\u09bf\\u0982 \\u09ae\\u09b2 \\u0993 \\u09b8\\u09bf\\u09a8\\u09c7\\u09aa\\u09cd\\u09b2\\u09c7\\u0995\\u09cd\\u09b8, \\u09b8\\u09bf \\u0993\\u09af\\u09bc\\u09be\\u09b0\\u09cd\\u09b2\\u09cd\\u09a1 \\u0993 \\u0993\\u09af\\u09bc\\u09be\\u099f\\u09be\\u09b0 \\u09aa\\u09be\\u09b0\\u09cd\\u0995\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-06-mega-attractions-part-2.jpg\"},{\"title\":\"\\u09ae\\u09c7\\u0997\\u09be \\u0986\\u0995\\u09b0\\u09cd\\u09b7\\u09a3 (\\u0985\\u0982\\u09b6 \\u09e9)\",\"subtitle\":\"\\u09ab\\u09cd\\u09af\\u09be\\u09ae\\u09bf\\u09b2\\u09bf \\u09aa\\u09be\\u09b0\\u09cd\\u0995, \\u09b8\\u09c1\\u0987\\u09ae\\u09bf\\u0982 \\u099c\\u09cb\\u09a8, \\u0987\\u09a8\\u09a1\\u09cb\\u09b0 \\u09b8\\u09cd\\u099f\\u09c7\\u09a1\\u09bf\\u09af\\u09bc\\u09be\\u09ae \\u0993 \\u0995\\u09c7\\u09a8\\u09cd\\u09a6\\u09cd\\u09b0\\u09c0\\u09af\\u09bc \\u09ae\\u09b8\\u099c\\u09bf\\u09a6\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-07-mega-attractions-part-3.jpg\"},{\"title\":\"\\u09b6\\u09b0\\u09cd\\u09a4\\u09be\\u09ac\\u09b2\\u09bf, \\u09b6\\u09c7\\u09af\\u09bc\\u09be\\u09b0 \\u0993 \\u09ac\\u09c1\\u0995\\u09bf\\u0982\",\"subtitle\":\"\\u09b6\\u09c7\\u09af\\u09bc\\u09be\\u09b0\\u09b9\\u09cb\\u09b2\\u09cd\\u09a1\\u09be\\u09b0 \\u09b8\\u09c1\\u09ac\\u09bf\\u09a7\\u09be, \\u09b8\\u09be\\u09ac-\\u0995\\u09ac\\u09b2\\u09be \\u09b0\\u09c7\\u099c\\u09bf\\u09b8\\u09cd\\u099f\\u09cd\\u09b0\\u09c7\\u09b6\\u09a8 \\u0993 \\u0995\\u09b0\\u09cd\\u09aa\\u09cb\\u09b0\\u09c7\\u099f \\u0985\\u09ab\\u09bf\\u09b8\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-08-terms-and-booking.jpg\"}]\";s:10:\"created_at\";s:19:\"2026-09-26 17:23:22\";s:10:\"updated_at\";s:19:\"2026-09-26 18:05:15\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:1;s:13:\"section_title\";s:29:\"Project Prospectus & Brochure\";s:16:\"section_subtitle\";s:146:\"কিং লোটাস ইন্টারন্যাশনালের পূর্ণাঙ্গ প্রকল্প রূপরেখা\";s:10:\"is_visible\";i:1;s:9:\"brochures\";s:3524:\"[{\"title\":\"5-Star Luxury in Royal\",\"subtitle\":\"\\u0995\\u09bf\\u0982 \\u09b2\\u09cb\\u099f\\u09be\\u09b8 \\u0987\\u09a8\\u09cd\\u099f\\u09be\\u09b0\\u09a8\\u09cd\\u09af\\u09be\\u09b6\\u09a8\\u09be\\u09b2 \\u2014 \\u09ae\\u09c7\\u09b0\\u09bf\\u09a8 \\u09a1\\u09cd\\u09b0\\u09be\\u0987\\u09ad \\u09b0\\u09cb\\u09a1, \\u0995\\u0995\\u09cd\\u09b8\\u09ac\\u09be\\u099c\\u09be\\u09b0\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-01-cover.jpg\"},{\"title\":\"\\u09ad\\u09bf\\u09b6\\u09a8, \\u09ae\\u09bf\\u09b6\\u09a8 \\u0993 \\u098f\\u09ae\\u09a1\\u09bf\\u09b0 \\u09ac\\u09be\\u09b0\\u09cd\\u09a4\\u09be\",\"subtitle\":\"\\u0986\\u09a8\\u09cd\\u09a4\\u09b0\\u09cd\\u099c\\u09be\\u09a4\\u09bf\\u0995 \\u09ae\\u09be\\u09a8\\u09c7\\u09b0 \\u0986\\u09a4\\u09bf\\u09a5\\u09c7\\u09af\\u09bc\\u09a4\\u09be \\u0993 \\u099f\\u09c7\\u0995\\u09b8\\u0987 \\u09aa\\u09b0\\u09cd\\u09af\\u099f\\u09a8\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-02-vision-md-message.jpg\"},{\"title\":\"\\u09a8\\u09bf\\u09b0\\u09cd\\u09ae\\u09be\\u09a3\\u09be\\u09a7\\u09c0\\u09a8 \\u09ab\\u09be\\u0987\\u09ad \\u09b8\\u09cd\\u099f\\u09be\\u09b0 \\u09b9\\u09cb\\u099f\\u09c7\\u09b2\",\"subtitle\":\"Luxury Suites, Deluxe Rooms, Dining & Wellness\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-03-5-star-hotel.jpg\"},{\"title\":\"\\u09ac\\u09bf\\u09b6\\u09cd\\u09ac\\u09ae\\u09be\\u09a8\\u09c7\\u09b0 \\u09b8\\u09c1\\u09ac\\u09bf\\u09a7\\u09be\\u09b8\\u09ae\\u09c2\\u09b9\",\"subtitle\":\"Recreation, Corporate Facilities, Lifestyle & Kids Zone\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-04-world-class-amenities.jpg\"},{\"title\":\"\\u09ae\\u09c7\\u0997\\u09be \\u0986\\u0995\\u09b0\\u09cd\\u09b7\\u09a3 (\\u0985\\u0982\\u09b6 \\u09e7)\",\"subtitle\":\"\\u09a5\\u09cd\\u09b0\\u09bf-\\u09b8\\u09cd\\u099f\\u09be\\u09b0, \\u09b8\\u09cd\\u099f\\u09c1\\u09a1\\u09bf\\u0993 \\u09b9\\u09cb\\u099f\\u09c7\\u09b2, \\u09ac\\u09c7\\u09ac\\u09bf \\u098f\\u09a8\\u09cd\\u099f\\u09be\\u09b0\\u099f\\u09c7\\u0987\\u09a8\\u09ae\\u09c7\\u09a8\\u09cd\\u099f \\u0993 \\u09ac\\u09be\\u09b0\\u09ac\\u09bf\\u0995\\u09bf\\u0989 \\u09ad\\u09bf\\u09b2\\u09c7\\u099c\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-05-mega-attractions-part-1.jpg\"},{\"title\":\"\\u09ae\\u09c7\\u0997\\u09be \\u0986\\u0995\\u09b0\\u09cd\\u09b7\\u09a3 (\\u0985\\u0982\\u09b6 \\u09e8)\",\"subtitle\":\"\\u09ae\\u09c7\\u0997\\u09be \\u09b6\\u09aa\\u09bf\\u0982 \\u09ae\\u09b2 \\u0993 \\u09b8\\u09bf\\u09a8\\u09c7\\u09aa\\u09cd\\u09b2\\u09c7\\u0995\\u09cd\\u09b8, \\u09b8\\u09bf \\u0993\\u09af\\u09bc\\u09be\\u09b0\\u09cd\\u09b2\\u09cd\\u09a1 \\u0993 \\u0993\\u09af\\u09bc\\u09be\\u099f\\u09be\\u09b0 \\u09aa\\u09be\\u09b0\\u09cd\\u0995\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-06-mega-attractions-part-2.jpg\"},{\"title\":\"\\u09ae\\u09c7\\u0997\\u09be \\u0986\\u0995\\u09b0\\u09cd\\u09b7\\u09a3 (\\u0985\\u0982\\u09b6 \\u09e9)\",\"subtitle\":\"\\u09ab\\u09cd\\u09af\\u09be\\u09ae\\u09bf\\u09b2\\u09bf \\u09aa\\u09be\\u09b0\\u09cd\\u0995, \\u09b8\\u09c1\\u0987\\u09ae\\u09bf\\u0982 \\u099c\\u09cb\\u09a8, \\u0987\\u09a8\\u09a1\\u09cb\\u09b0 \\u09b8\\u09cd\\u099f\\u09c7\\u09a1\\u09bf\\u09af\\u09bc\\u09be\\u09ae \\u0993 \\u0995\\u09c7\\u09a8\\u09cd\\u09a6\\u09cd\\u09b0\\u09c0\\u09af\\u09bc \\u09ae\\u09b8\\u099c\\u09bf\\u09a6\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-07-mega-attractions-part-3.jpg\"},{\"title\":\"\\u09b6\\u09b0\\u09cd\\u09a4\\u09be\\u09ac\\u09b2\\u09bf, \\u09b6\\u09c7\\u09af\\u09bc\\u09be\\u09b0 \\u0993 \\u09ac\\u09c1\\u0995\\u09bf\\u0982\",\"subtitle\":\"\\u09b6\\u09c7\\u09af\\u09bc\\u09be\\u09b0\\u09b9\\u09cb\\u09b2\\u09cd\\u09a1\\u09be\\u09b0 \\u09b8\\u09c1\\u09ac\\u09bf\\u09a7\\u09be, \\u09b8\\u09be\\u09ac-\\u0995\\u09ac\\u09b2\\u09be \\u09b0\\u09c7\\u099c\\u09bf\\u09b8\\u09cd\\u099f\\u09cd\\u09b0\\u09c7\\u09b6\\u09a8 \\u0993 \\u0995\\u09b0\\u09cd\\u09aa\\u09cb\\u09b0\\u09c7\\u099f \\u0985\\u09ab\\u09bf\\u09b8\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-08-terms-and-booking.jpg\"}]\";s:10:\"created_at\";s:19:\"2026-09-26 17:23:22\";s:10:\"updated_at\";s:19:\"2026-09-26 18:05:15\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:10:\"is_visible\";s:7:\"boolean\";s:9:\"brochures\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:13:\"section_title\";i:1;s:16:\"section_subtitle\";i:2;s:10:\"is_visible\";i:3;s:9:\"brochures\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}s:14:\"gallerySection\";O:25:\"App\\Models\\GallerySection\":33:{s:13:\"\0*\0connection\";s:7:\"content\";s:8:\"\0*\0table\";s:16:\"gallery_sections\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:10:{s:2:\"id\";i:1;s:13:\"section_title\";s:14:\"Recent Gallery\";s:16:\"section_subtitle\";s:16:\"Featured Moments\";s:14:\"view_all_label\";s:8:\"View All\";s:10:\"page_title\";s:14:\"Gallery Albums\";s:13:\"page_subtitle\";s:66:\"Explore curated albums and featured moments from King Lotus Group.\";s:15:\"featured_images\";s:680:\"[{\"order\":1,\"image_path\":\"uploads\\/gallery\\/featured\\/a0e72959-0d8c-4558-a105-867d5a0b1503.webp\"},{\"order\":2,\"image_path\":\"uploads\\/gallery\\/featured\\/a30415c1-3684-4354-9b16-4b605b7968f1.webp\"},{\"order\":3,\"image_path\":\"uploads\\/gallery\\/featured\\/3fa60a4c-ac22-496c-8900-27e1c78eec25.webp\"},{\"order\":4,\"image_path\":\"uploads\\/gallery\\/featured\\/8301b0ad-79e1-4b60-b838-0c74bb9beb88.webp\"},{\"order\":5,\"image_path\":\"uploads\\/gallery\\/featured\\/9ad98d0c-87f9-469c-b901-712351dfd85e.webp\"},{\"order\":6,\"image_path\":\"uploads\\/gallery\\/featured\\/96eb81e0-ff69-464c-8c4a-fe87a3b73a5c.webp\"},{\"order\":7,\"image_path\":\"uploads\\/gallery\\/featured\\/5f8a37c5-d6ca-478f-9b31-b50466bd6111.webp\"}]\";s:6:\"albums\";s:6796:\"[{\"title\":\"\\u09aa\\u09cd\\u09b0\\u099c\\u09c7\\u0995\\u09cd\\u099f \\u09ad\\u09bf\\u099c\\u09bf\\u099f\",\"subtitle\":\"\\u09b8\\u09cd\\u09ac\\u09aa\\u09cd\\u09a8\\u09c7\\u09b0 \\u09b8\\u09cd\\u09a5\\u09be\\u09aa\\u09a8\\u09be\\u09af\\u09bc \\u0986\\u09ae\\u09be\\u09a6\\u09c7\\u09b0 \\u09b8\\u09a6\\u09b8\\u09cd\\u09af\\u09b0\\u09be\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/f07d6fa2-ef1e-4450-bab7-e9bb324025ca.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/4215ec1b-34a4-4337-bae0-331094100835.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/de30a0e9-df64-4c9f-abb8-3dde1605c8ff.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/9369e497-5e32-4b71-b43b-06ec50647e1e.webp\"}]},{\"title\":\"\\u09b8\\u09a6\\u09b8\\u09cd\\u09af \\u09b8\\u09ae\\u09cd\\u09ae\\u09c7\\u09b2\\u09a8\",\"subtitle\":\"\\u09a8\\u09c7\\u09a4\\u09c3\\u09a4\\u09cd\\u09ac \\u0993 \\u09b8\\u09a6\\u09b8\\u09cd\\u09af\\u09a6\\u09c7\\u09b0 \\u09ae\\u09bf\\u09b2\\u09a8\\u09ae\\u09c7\\u09b2\\u09be\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/c13e64c2-8755-4449-8ae1-b7695293eb9d.webp\"}]},{\"title\":\"\\u09b8\\u09cd\\u09ac\\u09aa\\u09cd\\u09a8\\u09c7\\u09b0 \\u09a8\\u09bf\\u09b0\\u09cd\\u09ae\\u09be\\u09a3\\u09af\\u09be\\u09a4\\u09cd\\u09b0\\u09be\",\"subtitle\":\"\\u09b8\\u09cd\\u09ac\\u09aa\\u09cd\\u09a8 \\u09aa\\u09c2\\u09b0\\u09a3\\u09c7\\u09b0 \\u09a8\\u09bf\\u09b0\\u09cd\\u09ae\\u09be\\u09a3\\u09af\\u09be\\u09a4\\u09cd\\u09b0\\u09be\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/35ff816e-ba22-4e29-a5b6-32019c3ec79c.webp\"}]},{\"title\":\"King Lotus International (5 Star Hotel)\",\"subtitle\":\"Flagship 5-Star Luxury Resort & Suites in Cox\'s Bazar\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/7efcb82c-16f3-423b-9597-9f37f9d24c39.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/66822633-cc91-4af7-9220-e5bd7abc4754.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/a6dfe8c2-0839-4131-b237-6ab814e21d73.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/9ca9bdf1-6d48-4a57-abaa-b220366e5485.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/e4d2f2d2-e552-4077-92a9-4978584f123c.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/4014f674-9ee9-46a2-b3a8-478ba49a6f3c.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/9d90266a-6411-4f11-af56-cca61f24de0c.webp\"}]},{\"title\":\"Luxury 3 Star Hotel\",\"subtitle\":\"Modern Comfort and Premium Hospitality\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/216267e0-c486-451f-8808-c6cd3920131a.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/0dc79fb2-af2d-4acf-9026-e048da533b82.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/6a37178e-b278-4f51-bbe6-2f57c3863327.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/67e44253-c9e0-4587-aaad-a722f359aa8e.webp\"}]},{\"title\":\"Luxury Studio Hotel\",\"subtitle\":\"Exclusive Studio Living Residences\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/2880b9d1-517e-4a77-8829-b76d544ba76f.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/aafd7d6e-44e9-4a62-91f0-7440694117e7.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/a4b2c647-b62e-4072-96e4-df4fac2e5190.webp\"}]},{\"title\":\"Triple Zone Water Park\",\"subtitle\":\"Ladies Exclusive, Family Fun & Gents Adventure Zones\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/d2da56be-56c0-4f72-9f08-295377272b02.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/b8b9a9b1-9a0a-47ef-bdcc-fe92ed99df7b.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/42aa5d00-88a1-4b68-9d0f-01f10ee9e80c.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/0039c021-ae7c-4e06-9761-b85417c136d7.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/ce67f5f7-01c1-42a1-b502-2f43f3e8f1cf.webp\"}]},{\"title\":\"Sea World Underwater Tunnel\",\"subtitle\":\"Ocean Wonders and Marine Life Experience\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/4ec63875-9784-47d6-80d6-0f4020aee45d.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/0cf4ffde-47e0-4825-8e94-6e01bca23d98.webp\"}]},{\"title\":\"Dual Swimming Zone\",\"subtitle\":\"Competition Pool & Relaxation Pool\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/e21c8ed6-ad68-45c5-adb4-e7e3cdf96487.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/827243a8-2080-4ea8-b91d-70ea924267cb.webp\"}]},{\"title\":\"The Central Mosque\",\"subtitle\":\"Serene and Architectural Spiritual Landmark\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/1f60d787-9ed0-42eb-a4b2-4601c87e6da7.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/bd6257b2-1107-41b1-a3f1-874f96c3e468.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/fcdcc5de-6e18-44e5-9516-141cface9079.webp\"}]},{\"title\":\"Shopping Mall & Cineplex\",\"subtitle\":\"World-Class Retail, Dining & Entertainment Complex\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/34f72cdb-ca31-4f96-be9e-3364c3aabc99.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/a924d740-6b3c-4e13-999a-e37778437727.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/48dc7f98-165a-4d1c-9135-1c7341d63a70.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/f31f55f4-d65e-4af3-934b-86df51c44676.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/5b4df207-9dbc-439e-b321-69b9845f8fc0.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/479e6d7d-71d4-4f29-9742-e0f44d6bc79c.webp\"}]},{\"title\":\"Baby Entertainment Point\",\"subtitle\":\"Interactive Kids Play & Educational Wonderland\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/a5e374b6-8758-4d92-ad34-673b6293fdf6.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/dc030ca5-686f-4716-83db-39148cbaa7fd.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/e9333a5a-4b0a-4c4a-8b25-05df1112a1aa.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/6a9e964c-5486-4f56-860f-c081c02d83a0.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/46c096ce-51d4-4a87-80ff-667acc940b9b.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/2ce6a46f-eca3-447f-8b7b-0faacc7175b1.webp\"}]},{\"title\":\"Live BBQ Village\",\"subtitle\":\"Authentic Open-Air Culinary Destination\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/e6523175-1abe-41df-92fa-518766cc6bc5.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/0a342dd5-b2d2-4d6f-bd75-ecac2dc0136a.webp\"}]},{\"title\":\"Indoor Stadium\",\"subtitle\":\"Multi-Sport Arena & Athletic Facilities\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/05e521c8-5eaf-47d2-afa8-d672b0a01e74.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/ec59cf10-71de-4c09-9f8c-c8356808a75a.webp\"}]},{\"title\":\"Project Site & Land\",\"subtitle\":\"Strategic Prime Location in Cox\'s Bazar\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/5e2023a7-0637-4dee-8ed3-b5f4301d3bdf.webp\"}]},{\"title\":\"Brand & Corporate Identity\",\"subtitle\":\"Executive Boardroom & Architectural Identity\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/63bfbe2f-4241-473e-86d1-9dfef4835491.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/5e18434e-86f9-4574-9068-2f0251ed6b48.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/ff68c678-75b8-4b5a-80a1-d929fea92e94.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/03737284-f848-45b5-bd17-c5162ed7e6c1.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/fd192fb1-75f3-4318-b58c-efb34cb3a75c.webp\"}]}]\";s:10:\"created_at\";s:19:\"2026-07-01 08:07:56\";s:10:\"updated_at\";s:19:\"2026-09-26 16:07:10\";}s:11:\"\0*\0original\";a:10:{s:2:\"id\";i:1;s:13:\"section_title\";s:14:\"Recent Gallery\";s:16:\"section_subtitle\";s:16:\"Featured Moments\";s:14:\"view_all_label\";s:8:\"View All\";s:10:\"page_title\";s:14:\"Gallery Albums\";s:13:\"page_subtitle\";s:66:\"Explore curated albums and featured moments from King Lotus Group.\";s:15:\"featured_images\";s:680:\"[{\"order\":1,\"image_path\":\"uploads\\/gallery\\/featured\\/a0e72959-0d8c-4558-a105-867d5a0b1503.webp\"},{\"order\":2,\"image_path\":\"uploads\\/gallery\\/featured\\/a30415c1-3684-4354-9b16-4b605b7968f1.webp\"},{\"order\":3,\"image_path\":\"uploads\\/gallery\\/featured\\/3fa60a4c-ac22-496c-8900-27e1c78eec25.webp\"},{\"order\":4,\"image_path\":\"uploads\\/gallery\\/featured\\/8301b0ad-79e1-4b60-b838-0c74bb9beb88.webp\"},{\"order\":5,\"image_path\":\"uploads\\/gallery\\/featured\\/9ad98d0c-87f9-469c-b901-712351dfd85e.webp\"},{\"order\":6,\"image_path\":\"uploads\\/gallery\\/featured\\/96eb81e0-ff69-464c-8c4a-fe87a3b73a5c.webp\"},{\"order\":7,\"image_path\":\"uploads\\/gallery\\/featured\\/5f8a37c5-d6ca-478f-9b31-b50466bd6111.webp\"}]\";s:6:\"albums\";s:6796:\"[{\"title\":\"\\u09aa\\u09cd\\u09b0\\u099c\\u09c7\\u0995\\u09cd\\u099f \\u09ad\\u09bf\\u099c\\u09bf\\u099f\",\"subtitle\":\"\\u09b8\\u09cd\\u09ac\\u09aa\\u09cd\\u09a8\\u09c7\\u09b0 \\u09b8\\u09cd\\u09a5\\u09be\\u09aa\\u09a8\\u09be\\u09af\\u09bc \\u0986\\u09ae\\u09be\\u09a6\\u09c7\\u09b0 \\u09b8\\u09a6\\u09b8\\u09cd\\u09af\\u09b0\\u09be\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/f07d6fa2-ef1e-4450-bab7-e9bb324025ca.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/4215ec1b-34a4-4337-bae0-331094100835.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/de30a0e9-df64-4c9f-abb8-3dde1605c8ff.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/9369e497-5e32-4b71-b43b-06ec50647e1e.webp\"}]},{\"title\":\"\\u09b8\\u09a6\\u09b8\\u09cd\\u09af \\u09b8\\u09ae\\u09cd\\u09ae\\u09c7\\u09b2\\u09a8\",\"subtitle\":\"\\u09a8\\u09c7\\u09a4\\u09c3\\u09a4\\u09cd\\u09ac \\u0993 \\u09b8\\u09a6\\u09b8\\u09cd\\u09af\\u09a6\\u09c7\\u09b0 \\u09ae\\u09bf\\u09b2\\u09a8\\u09ae\\u09c7\\u09b2\\u09be\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/c13e64c2-8755-4449-8ae1-b7695293eb9d.webp\"}]},{\"title\":\"\\u09b8\\u09cd\\u09ac\\u09aa\\u09cd\\u09a8\\u09c7\\u09b0 \\u09a8\\u09bf\\u09b0\\u09cd\\u09ae\\u09be\\u09a3\\u09af\\u09be\\u09a4\\u09cd\\u09b0\\u09be\",\"subtitle\":\"\\u09b8\\u09cd\\u09ac\\u09aa\\u09cd\\u09a8 \\u09aa\\u09c2\\u09b0\\u09a3\\u09c7\\u09b0 \\u09a8\\u09bf\\u09b0\\u09cd\\u09ae\\u09be\\u09a3\\u09af\\u09be\\u09a4\\u09cd\\u09b0\\u09be\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/35ff816e-ba22-4e29-a5b6-32019c3ec79c.webp\"}]},{\"title\":\"King Lotus International (5 Star Hotel)\",\"subtitle\":\"Flagship 5-Star Luxury Resort & Suites in Cox\'s Bazar\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/7efcb82c-16f3-423b-9597-9f37f9d24c39.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/66822633-cc91-4af7-9220-e5bd7abc4754.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/a6dfe8c2-0839-4131-b237-6ab814e21d73.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/9ca9bdf1-6d48-4a57-abaa-b220366e5485.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/e4d2f2d2-e552-4077-92a9-4978584f123c.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/4014f674-9ee9-46a2-b3a8-478ba49a6f3c.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/9d90266a-6411-4f11-af56-cca61f24de0c.webp\"}]},{\"title\":\"Luxury 3 Star Hotel\",\"subtitle\":\"Modern Comfort and Premium Hospitality\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/216267e0-c486-451f-8808-c6cd3920131a.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/0dc79fb2-af2d-4acf-9026-e048da533b82.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/6a37178e-b278-4f51-bbe6-2f57c3863327.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/67e44253-c9e0-4587-aaad-a722f359aa8e.webp\"}]},{\"title\":\"Luxury Studio Hotel\",\"subtitle\":\"Exclusive Studio Living Residences\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/2880b9d1-517e-4a77-8829-b76d544ba76f.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/aafd7d6e-44e9-4a62-91f0-7440694117e7.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/a4b2c647-b62e-4072-96e4-df4fac2e5190.webp\"}]},{\"title\":\"Triple Zone Water Park\",\"subtitle\":\"Ladies Exclusive, Family Fun & Gents Adventure Zones\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/d2da56be-56c0-4f72-9f08-295377272b02.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/b8b9a9b1-9a0a-47ef-bdcc-fe92ed99df7b.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/42aa5d00-88a1-4b68-9d0f-01f10ee9e80c.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/0039c021-ae7c-4e06-9761-b85417c136d7.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/ce67f5f7-01c1-42a1-b502-2f43f3e8f1cf.webp\"}]},{\"title\":\"Sea World Underwater Tunnel\",\"subtitle\":\"Ocean Wonders and Marine Life Experience\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/4ec63875-9784-47d6-80d6-0f4020aee45d.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/0cf4ffde-47e0-4825-8e94-6e01bca23d98.webp\"}]},{\"title\":\"Dual Swimming Zone\",\"subtitle\":\"Competition Pool & Relaxation Pool\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/e21c8ed6-ad68-45c5-adb4-e7e3cdf96487.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/827243a8-2080-4ea8-b91d-70ea924267cb.webp\"}]},{\"title\":\"The Central Mosque\",\"subtitle\":\"Serene and Architectural Spiritual Landmark\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/1f60d787-9ed0-42eb-a4b2-4601c87e6da7.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/bd6257b2-1107-41b1-a3f1-874f96c3e468.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/fcdcc5de-6e18-44e5-9516-141cface9079.webp\"}]},{\"title\":\"Shopping Mall & Cineplex\",\"subtitle\":\"World-Class Retail, Dining & Entertainment Complex\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/34f72cdb-ca31-4f96-be9e-3364c3aabc99.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/a924d740-6b3c-4e13-999a-e37778437727.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/48dc7f98-165a-4d1c-9135-1c7341d63a70.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/f31f55f4-d65e-4af3-934b-86df51c44676.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/5b4df207-9dbc-439e-b321-69b9845f8fc0.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/479e6d7d-71d4-4f29-9742-e0f44d6bc79c.webp\"}]},{\"title\":\"Baby Entertainment Point\",\"subtitle\":\"Interactive Kids Play & Educational Wonderland\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/a5e374b6-8758-4d92-ad34-673b6293fdf6.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/dc030ca5-686f-4716-83db-39148cbaa7fd.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/e9333a5a-4b0a-4c4a-8b25-05df1112a1aa.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/6a9e964c-5486-4f56-860f-c081c02d83a0.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/46c096ce-51d4-4a87-80ff-667acc940b9b.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/2ce6a46f-eca3-447f-8b7b-0faacc7175b1.webp\"}]},{\"title\":\"Live BBQ Village\",\"subtitle\":\"Authentic Open-Air Culinary Destination\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/e6523175-1abe-41df-92fa-518766cc6bc5.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/0a342dd5-b2d2-4d6f-bd75-ecac2dc0136a.webp\"}]},{\"title\":\"Indoor Stadium\",\"subtitle\":\"Multi-Sport Arena & Athletic Facilities\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/05e521c8-5eaf-47d2-afa8-d672b0a01e74.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/ec59cf10-71de-4c09-9f8c-c8356808a75a.webp\"}]},{\"title\":\"Project Site & Land\",\"subtitle\":\"Strategic Prime Location in Cox\'s Bazar\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/5e2023a7-0637-4dee-8ed3-b5f4301d3bdf.webp\"}]},{\"title\":\"Brand & Corporate Identity\",\"subtitle\":\"Executive Boardroom & Architectural Identity\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/63bfbe2f-4241-473e-86d1-9dfef4835491.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/5e18434e-86f9-4574-9068-2f0251ed6b48.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/ff68c678-75b8-4b5a-80a1-d929fea92e94.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/03737284-f848-45b5-bd17-c5162ed7e6c1.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/fd192fb1-75f3-4318-b58c-efb34cb3a75c.webp\"}]}]\";s:10:\"created_at\";s:19:\"2026-07-01 08:07:56\";s:10:\"updated_at\";s:19:\"2026-09-26 16:07:10\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:15:\"featured_images\";s:5:\"array\";s:6:\"albums\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:13:\"section_title\";i:1;s:16:\"section_subtitle\";i:2;s:14:\"view_all_label\";i:3;s:10:\"page_title\";i:4;s:13:\"page_subtitle\";i:5;s:15:\"featured_images\";i:6;s:6:\"albums\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}s:24:\"shareholderReviewSection\";O:35:\"App\\Models\\ShareholderReviewSection\":33:{s:13:\"\0*\0connection\";s:7:\"content\";s:8:\"\0*\0table\";s:27:\"shareholder_review_sections\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:2:\"id\";i:1;s:13:\"section_title\";s:19:\"Shareholder Reviews\";s:16:\"section_subtitle\";s:48:\"Real stories from King Lotus Group shareholders.\";s:7:\"reviews\";s:1610:\"[{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/2yNBzBVRqFg?si=VjzwNLUILO3KbKg8\",\"thumbnail_path\":\"uploads\\/reviews\\/ad6d65d0-dcef-40ec-9f0b-aaeb9b3ba9ec.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/It3qa-dA04s?si=6iyJcC7WTYGDhnTV\",\"thumbnail_path\":\"uploads\\/reviews\\/f94046c9-5034-42d1-9322-6d641c7838a3.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/Fd05_jdMdFg?si=gd7pzmZQ2_c_y-00\",\"thumbnail_path\":\"uploads\\/reviews\\/88608bf0-94d9-418b-9d24-e590a8da4daf.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/DGrpXPYQrqU?si=YFsvrUtqIGSMNkSH\",\"thumbnail_path\":\"uploads\\/reviews\\/616dc181-69f7-4802-96da-22330348c914.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/9IyPQtzFWew?si=jzOEhEKyqPYVi-Ue\",\"thumbnail_path\":\"uploads\\/reviews\\/b8f553d3-7133-4a9f-855f-b4e90cf25009.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/FprxZZHsxtE?si=F5A7NJfWnT-rA6US\",\"thumbnail_path\":\"uploads\\/reviews\\/d03a6739-a147-446c-9f1d-e70a124628f8.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/ht6O4LP9HFs?si=Dgdr4il8kefdFp4b\",\"thumbnail_path\":\"uploads\\/reviews\\/7a8487e1-ae5e-4e69-ac8f-97e4c154031c.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/V9jijYq8sdg?si=xgsiMuP1Xpv58w0o\",\"thumbnail_path\":\"uploads\\/reviews\\/a5377e29-42c0-4c2f-8d41-30aa8152283a.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/Gq3NLNelGo0?si=GZvF1iez8vSao_G8\",\"thumbnail_path\":\"uploads\\/reviews\\/23ed1b84-b640-4ffd-994d-f66cc5a2bd1e.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/_LmxMWRmAic?si=DcN4locXDL2hXX5j\",\"thumbnail_path\":\"\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/rmT_Oaak3Aw?si=6LSLVu0pzo9MholF\",\"thumbnail_path\":\"\"}]\";s:10:\"created_at\";s:19:\"2026-07-01 18:33:12\";s:10:\"updated_at\";s:19:\"2026-07-02 20:09:33\";}s:11:\"\0*\0original\";a:6:{s:2:\"id\";i:1;s:13:\"section_title\";s:19:\"Shareholder Reviews\";s:16:\"section_subtitle\";s:48:\"Real stories from King Lotus Group shareholders.\";s:7:\"reviews\";s:1610:\"[{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/2yNBzBVRqFg?si=VjzwNLUILO3KbKg8\",\"thumbnail_path\":\"uploads\\/reviews\\/ad6d65d0-dcef-40ec-9f0b-aaeb9b3ba9ec.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/It3qa-dA04s?si=6iyJcC7WTYGDhnTV\",\"thumbnail_path\":\"uploads\\/reviews\\/f94046c9-5034-42d1-9322-6d641c7838a3.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/Fd05_jdMdFg?si=gd7pzmZQ2_c_y-00\",\"thumbnail_path\":\"uploads\\/reviews\\/88608bf0-94d9-418b-9d24-e590a8da4daf.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/DGrpXPYQrqU?si=YFsvrUtqIGSMNkSH\",\"thumbnail_path\":\"uploads\\/reviews\\/616dc181-69f7-4802-96da-22330348c914.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/9IyPQtzFWew?si=jzOEhEKyqPYVi-Ue\",\"thumbnail_path\":\"uploads\\/reviews\\/b8f553d3-7133-4a9f-855f-b4e90cf25009.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/FprxZZHsxtE?si=F5A7NJfWnT-rA6US\",\"thumbnail_path\":\"uploads\\/reviews\\/d03a6739-a147-446c-9f1d-e70a124628f8.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/ht6O4LP9HFs?si=Dgdr4il8kefdFp4b\",\"thumbnail_path\":\"uploads\\/reviews\\/7a8487e1-ae5e-4e69-ac8f-97e4c154031c.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/V9jijYq8sdg?si=xgsiMuP1Xpv58w0o\",\"thumbnail_path\":\"uploads\\/reviews\\/a5377e29-42c0-4c2f-8d41-30aa8152283a.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/Gq3NLNelGo0?si=GZvF1iez8vSao_G8\",\"thumbnail_path\":\"uploads\\/reviews\\/23ed1b84-b640-4ffd-994d-f66cc5a2bd1e.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/_LmxMWRmAic?si=DcN4locXDL2hXX5j\",\"thumbnail_path\":\"\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/rmT_Oaak3Aw?si=6LSLVu0pzo9MholF\",\"thumbnail_path\":\"\"}]\";s:10:\"created_at\";s:19:\"2026-07-01 18:33:12\";s:10:\"updated_at\";s:19:\"2026-07-02 20:09:33\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:7:\"reviews\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:3:{i:0;s:13:\"section_title\";i:1;s:16:\"section_subtitle\";i:2;s:7:\"reviews\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}s:17:\"leadershipSection\";O:28:\"App\\Models\\LeadershipSection\":33:{s:13:\"\0*\0connection\";s:7:\"content\";s:8:\"\0*\0table\";s:19:\"leadership_sections\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:15:{s:2:\"id\";i:1;s:13:\"section_title\";s:18:\"Board of Directors\";s:12:\"founder_name\";s:18:\"MD. Mizanur Rahman\";s:16:\"founder_position\";s:23:\"Managing Director & CEO\";s:19:\"founder_description\";s:123:\"Our commitment is to shape King Lotus into a world-class 5-star condominium resort built on trust, elegance and excellence.\";s:18:\"founder_image_path\";s:68:\"uploads/leadership/founder/d2aa4666-b05b-45b4-9ace-4f169145fe6d.webp\";s:21:\"secondary_leader_name\";s:14:\"MD. Nurul Amin\";s:25:\"secondary_leader_position\";s:26:\"Sales & Marketing Director\";s:28:\"secondary_leader_description\";s:148:\"Committed to driving sales growth and delivering luxury living experiences through innovative marketing strategies for King Lotus International Ltd.\";s:27:\"secondary_leader_image_path\";s:68:\"uploads/leadership/founder/27ebf8d7-6369-4337-80a5-a6b7a19d0c66.webp\";s:13:\"board_members\";s:154:\"[{\"name\":\"MD. Nurul Amin\",\"position\":\"Sales & Marketing Director\",\"image_path\":\"uploads\\/leadership\\/members\\/3533e652-cac8-4a98-ac06-1c3e719803c8.webp\"}]\";s:10:\"is_visible\";i:1;s:21:\"board_members_visible\";i:0;s:10:\"created_at\";s:19:\"2026-07-03 14:40:39\";s:10:\"updated_at\";s:19:\"2026-09-27 10:00:15\";}s:11:\"\0*\0original\";a:15:{s:2:\"id\";i:1;s:13:\"section_title\";s:18:\"Board of Directors\";s:12:\"founder_name\";s:18:\"MD. Mizanur Rahman\";s:16:\"founder_position\";s:23:\"Managing Director & CEO\";s:19:\"founder_description\";s:123:\"Our commitment is to shape King Lotus into a world-class 5-star condominium resort built on trust, elegance and excellence.\";s:18:\"founder_image_path\";s:68:\"uploads/leadership/founder/d2aa4666-b05b-45b4-9ace-4f169145fe6d.webp\";s:21:\"secondary_leader_name\";s:14:\"MD. Nurul Amin\";s:25:\"secondary_leader_position\";s:26:\"Sales & Marketing Director\";s:28:\"secondary_leader_description\";s:148:\"Committed to driving sales growth and delivering luxury living experiences through innovative marketing strategies for King Lotus International Ltd.\";s:27:\"secondary_leader_image_path\";s:68:\"uploads/leadership/founder/27ebf8d7-6369-4337-80a5-a6b7a19d0c66.webp\";s:13:\"board_members\";s:154:\"[{\"name\":\"MD. Nurul Amin\",\"position\":\"Sales & Marketing Director\",\"image_path\":\"uploads\\/leadership\\/members\\/3533e652-cac8-4a98-ac06-1c3e719803c8.webp\"}]\";s:10:\"is_visible\";i:1;s:21:\"board_members_visible\";i:0;s:10:\"created_at\";s:19:\"2026-07-03 14:40:39\";s:10:\"updated_at\";s:19:\"2026-09-27 10:00:15\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:13:\"board_members\";s:5:\"array\";s:10:\"is_visible\";s:7:\"boolean\";s:21:\"board_members_visible\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:12:{i:0;s:13:\"section_title\";i:1;s:12:\"founder_name\";i:2;s:16:\"founder_position\";i:3;s:19:\"founder_description\";i:4;s:18:\"founder_image_path\";i:5;s:21:\"secondary_leader_name\";i:6;s:25:\"secondary_leader_position\";i:7;s:28:\"secondary_leader_description\";i:8;s:27:\"secondary_leader_image_path\";i:9;s:13:\"board_members\";i:10;s:10:\"is_visible\";i:11;s:21:\"board_members_visible\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}s:24:\"valuedShareholderSection\";O:35:\"App\\Models\\ValuedShareholderSection\":33:{s:13:\"\0*\0connection\";s:7:\"content\";s:8:\"\0*\0table\";s:27:\"valued_shareholder_sections\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:2:\"id\";i:1;s:13:\"section_title\";s:23:\"Our Valued Shareholders\";s:12:\"shareholders\";s:2:\"[]\";s:10:\"is_visible\";i:1;s:10:\"created_at\";s:19:\"2026-07-03 18:21:54\";s:10:\"updated_at\";s:19:\"2026-09-26 20:30:29\";}s:11:\"\0*\0original\";a:6:{s:2:\"id\";i:1;s:13:\"section_title\";s:23:\"Our Valued Shareholders\";s:12:\"shareholders\";s:2:\"[]\";s:10:\"is_visible\";i:1;s:10:\"created_at\";s:19:\"2026-07-03 18:21:54\";s:10:\"updated_at\";s:19:\"2026-09-26 20:30:29\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:2:{s:12:\"shareholders\";s:5:\"array\";s:10:\"is_visible\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:3:{i:0;s:13:\"section_title\";i:1;s:12:\"shareholders\";i:2;s:10:\"is_visible\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}s:13:\"footerSetting\";O:24:\"App\\Models\\FooterSetting\":33:{s:13:\"\0*\0connection\";s:7:\"content\";s:8:\"\0*\0table\";s:15:\"footer_settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:20:{s:2:\"id\";i:1;s:11:\"youtube_url\";s:70:\"https://youtube.com/@kinglotusinternationalltd-x2v?si=Hj8U8b8PIOlX5Dk8\";s:12:\"facebook_url\";s:37:\"https://www.facebook.com/nurulamind2k\";s:13:\"contact_email\";s:22:\"kinglotuscox@gmail.com\";s:13:\"contact_phone\";s:34:\"+880 1816-236822\r\n+880 1815-004959\";s:14:\"location_title\";s:18:\"Visit Our Location\";s:17:\"location_subtitle\";s:91:\"Open our Google Maps location to plan your arrival and explore the surrounding destination.\";s:16:\"location_map_url\";s:41:\"https://maps.app.goo.gl/23NPMkWX8JADhU7t9\";s:20:\"office_section_title\";s:36:\"Get A Quote - No Cost, No Commitment\";s:23:\"office_section_subtitle\";s:31:\"Transparent & Competitive Rates\";s:11:\"office_name\";s:11:\"Head Office\";s:14:\"office_address\";s:66:\"Dhaka Office\r\n54 - Motijheel, Elite House, Motijheel, Dhaka - 1000\";s:14:\"office_map_url\";s:41:\"https://maps.app.goo.gl/WypUJpRgC84Rw2uB7\";s:12:\"office_cards\";s:641:\"[{\"name\":\"Head Office\",\"address\":\"Dhaka Office\\r\\n54 - Motijheel, Elite House, Motijheel, Dhaka - 1000\",\"map_url\":\"https:\\/\\/maps.app.goo.gl\\/WypUJpRgC84Rw2uB7\",\"phone\":\"+88 01897672250\",\"email\":\"support@kinglotusgroup.com\"},{\"name\":\"Branch Office\",\"address\":\"Cumilla Office\\r\\n491\\/437 Artision Nasir Center, Kandirpar, Cumilla-3500\",\"map_url\":\"https:\\/\\/maps.app.goo.gl\\/YaRLtrNbBUwCiqEo9\",\"phone\":\"+88 01816236822\",\"email\":\"cumilla@kinglotusgroup.com\"},{\"name\":\"Foreign Office\",\"address\":\"Qatar Office\\r\\nRawdat Al Khail, B Ring Road, Zone No 24, Street No 220, 2nd Floor, Doha, Qatar\",\"map_url\":\"\",\"phone\":\"+974 77 93 92 88\",\"email\":\"\"}]\";s:11:\"terms_title\";s:20:\"Terms and Conditions\";s:14:\"terms_subtitle\";s:90:\"কিং লোটাস ইন্টারন্যাশনাল লিমিটেড\";s:11:\"terms_intro\";N;s:13:\"terms_content\";s:7294:\"<p><strong>শেয়ারের সুবিধা প্রাপ্তি এবং কোম্পানির নীতিমালাঃ</strong>\r\n</p><p><span style=\"background-color: rgb(60, 120, 216); color: rgb(239, 239, 239);\">প্রস্তাবিত পাঁচ তারকা হোটেলের প্রতিটি শেয়ারের মূল্য =&gt; ৯,২৫,০০০/= টাকা। (বর্তমান নির্ধারিত শেয়ার মূল্য)</span></p><ul><li><strong>বুকিং মানিঃ</strong><br>\r\n৩৫,০০০ টাকা কনফার্ম করিয়া প্রতি মাসে EMI জমা দিতে হবে নিম্নে উল্লিখিত পরিমাণে =৭০০০/৯০০০/১২০০০/১৫০০০=৬০/৫৮ মাসে মূল্য পরিশোধ করার সুযোগ রয়েছে।</li></ul><p><strong style=\"background-color: rgb(255, 229, 153);\">এই মুহূর্তে ডিসকাউন্ট অফার চলছে ক্রমান্বয়েঃ</strong>\r\n</p><ul><li>৩,৭৫,০০০ টাকা করিয়া :- ১,০০০ শেয়ার (শেষ)\r\n</li><li>৪,৪৫,০০০ টাকা করিয়া :- ২,০০০ শেয়ার (চলমান)\r\n</li><li>৫,৬৫,০০০ টাকা করিয়া :- ২,০০০ শেয়ার\r\n</li><li>৭,৪৫,০০০ টাকা করিয়া :- ৫,০০০ শেয়ার\r\n</li><li>৯,২৫,০০০ টাকা করিয়া :- শেষ ১০,০০০ শেয়ারের মূল্য নির্ধারণ করা আছে। ভবিষ্যতে প্রয়োজন অনুযায়ী/কোম্পানীর Board of Director-এর সিদ্ধান্ত অনুযায়ী শেয়ার মূল্য বৃদ্ধি হতে পারে যাহা ১২,০০০,০০ ( বারো লক্ষ টাকা) পর্যন্ত নির্ধারণ করা হতে পারে।</li></ul><p><strong style=\"background-color: rgb(255, 229, 153);\">একজন শেয়ারহোল্ডার ভূমির সাব কবলা রেজিস্ট্রি সহ নিম্নোলিখিত সুযোগ সুবিধা পাবেনঃ</strong>\r\n</p><ul><li>ভূমির মালিকানা পাবেন (আজীবনের জন্য) ১০ স্কয়ার ফিট+ভবনে পাবেন ১০ স্কয়ার ফিট।\r\n</li><li>প্রতি মাসে ভাড়া আয় করার সুযোগ পাবেন। সকল শেয়ার হোল্ডার সমহারে ইনকাম পাবেন। যা প্রত্যেক শেয়ার হোল্ডারের ব্যাংক একাউন্টে সমন্বয় করা হবে, একটা শেয়ারের বিপরীতে বছরে ১,০০,০০০ থেকে ২,৫০,০০০ এর অধিক আয় হতে পারে।\r\n</li><li>প্রতি বছরে একবার পরিবার নিয়ে, ৩ রাত ৪ দিন নিজ মালিকানাধীন পাঁচ তারকা হোটেলে যাপন করার সুযোগ পাবেন। বছরে বারং বার যেতে চাইলে প্রতিবছরই শেয়ারহোল্ডার পাবেন রুম ভাড়ার উপর ৫০% ডিসকাউন্ট সুবিধা। কোন শেয়ারহোল্ডার যদি কাউকে রেফার করে তাহলে ঐ ব্যক্তি হোটেলের রুম ভাড়ার ওপর ৩০% সুবিধা পাবেন।\r\n</li><li>এছাড়াও প্রত্যেক শেয়ারহোল্ডারকে একটা Ownership Card প্রদান করা হবে। যার মাধ্যমে প্রজেক্টের অন্যান্য সকল সুযোগ-সুবিধা ভোগ করতে পারবেন।</li></ul><p><strong><span style=\"background-color: rgb(255, 229, 153);\">বিপদে পরিবারের একটা নিরাপত্তা পাবেন।</span></strong></p><ul><li>বুকিং কনফার্ম করিয়া পর পর ১২ মাসের EMI যদি পরিশোধ করা থাকে সেই ক্ষেত্রে কোন শেয়ারহোল্ডারের যে কোন কারণবশত মৃত্যুবরণ করেন, (তবে সেটা যেন আত্মহত্যা না হয়), অথবা কোন শেয়ারহোল্ডার দুর্ঘটনা জনিত কোন কারণে যদি মৃত্যু বরণ করে তাহলে বকেয়া থাকা EMI পরিশোধ করা ছাড়াই সময়াত্তে তার পরিবার জমির সাব কবলা রেজিস্ট্রি সহ শেয়ারের চুক্তিনামা বুঝিয়া পাবেন এবং বৃদ্ধিমূল্য ছাড়া তৎক্ষণাৎ সকল EMI মাফ/ফেরত পাবেন।</li></ul><p><strong><span style=\"color: rgb(225, 55, 55);\">বিঃদ্রঃ শেয়ারহোল্ডারের বিনিয়োগ কৃত অর্থ প্রকল্প বাস্তবায়নের পূর্বে ফেরত চাওয়া বা পাওয়ার কোনো সুযোগ থাকবে না। প্রকল্প বাস্তবায়নের পরে নিয়ম অনুযায়ী শেয়ার হস্তান্তর করতে পারবেন বা অধিক মূল্যে বিক্রয় করিতে পারবেন (শর্ত প্রযোজ্য)</span></strong></p><p><strong>দলিল এবং সার্টিফিকেটঃ</strong><br>\r\nকোম্পানির বোর্ড অফ ডিরেক্টর সিদ্ধান্ত অনুযায়ী যাদের শেয়ারের সম্পূর্ণ টাকা পরিশোধ করা থাকবে তাদেরকে ০১/০১/২০২৭ তারিখ থেকে শেয়ার সার্টিফিকেট সহ জমির সাব কবলা রেজিস্ট্রি বুঝিয়ে দেওয়া হবে।</p><p>প্রকল্প পরিপূর্ণ হওয়ার পর মেরিন ড্রাইভ রোড জিরো পয়েন্ট থেকে এয়ারপোর্ট পর্যন্ত প্রকল্পের নিজস্ব পরিবহন ব্যবস্থাপনা থাকবে।</p>\";s:10:\"created_at\";s:19:\"2026-06-26 22:24:13\";s:10:\"updated_at\";s:19:\"2026-09-26 20:14:38\";}s:11:\"\0*\0original\";a:20:{s:2:\"id\";i:1;s:11:\"youtube_url\";s:70:\"https://youtube.com/@kinglotusinternationalltd-x2v?si=Hj8U8b8PIOlX5Dk8\";s:12:\"facebook_url\";s:37:\"https://www.facebook.com/nurulamind2k\";s:13:\"contact_email\";s:22:\"kinglotuscox@gmail.com\";s:13:\"contact_phone\";s:34:\"+880 1816-236822\r\n+880 1815-004959\";s:14:\"location_title\";s:18:\"Visit Our Location\";s:17:\"location_subtitle\";s:91:\"Open our Google Maps location to plan your arrival and explore the surrounding destination.\";s:16:\"location_map_url\";s:41:\"https://maps.app.goo.gl/23NPMkWX8JADhU7t9\";s:20:\"office_section_title\";s:36:\"Get A Quote - No Cost, No Commitment\";s:23:\"office_section_subtitle\";s:31:\"Transparent & Competitive Rates\";s:11:\"office_name\";s:11:\"Head Office\";s:14:\"office_address\";s:66:\"Dhaka Office\r\n54 - Motijheel, Elite House, Motijheel, Dhaka - 1000\";s:14:\"office_map_url\";s:41:\"https://maps.app.goo.gl/WypUJpRgC84Rw2uB7\";s:12:\"office_cards\";s:641:\"[{\"name\":\"Head Office\",\"address\":\"Dhaka Office\\r\\n54 - Motijheel, Elite House, Motijheel, Dhaka - 1000\",\"map_url\":\"https:\\/\\/maps.app.goo.gl\\/WypUJpRgC84Rw2uB7\",\"phone\":\"+88 01897672250\",\"email\":\"support@kinglotusgroup.com\"},{\"name\":\"Branch Office\",\"address\":\"Cumilla Office\\r\\n491\\/437 Artision Nasir Center, Kandirpar, Cumilla-3500\",\"map_url\":\"https:\\/\\/maps.app.goo.gl\\/YaRLtrNbBUwCiqEo9\",\"phone\":\"+88 01816236822\",\"email\":\"cumilla@kinglotusgroup.com\"},{\"name\":\"Foreign Office\",\"address\":\"Qatar Office\\r\\nRawdat Al Khail, B Ring Road, Zone No 24, Street No 220, 2nd Floor, Doha, Qatar\",\"map_url\":\"\",\"phone\":\"+974 77 93 92 88\",\"email\":\"\"}]\";s:11:\"terms_title\";s:20:\"Terms and Conditions\";s:14:\"terms_subtitle\";s:90:\"কিং লোটাস ইন্টারন্যাশনাল লিমিটেড\";s:11:\"terms_intro\";N;s:13:\"terms_content\";s:7294:\"<p><strong>শেয়ারের সুবিধা প্রাপ্তি এবং কোম্পানির নীতিমালাঃ</strong>\r\n</p><p><span style=\"background-color: rgb(60, 120, 216); color: rgb(239, 239, 239);\">প্রস্তাবিত পাঁচ তারকা হোটেলের প্রতিটি শেয়ারের মূল্য =&gt; ৯,২৫,০০০/= টাকা। (বর্তমান নির্ধারিত শেয়ার মূল্য)</span></p><ul><li><strong>বুকিং মানিঃ</strong><br>\r\n৩৫,০০০ টাকা কনফার্ম করিয়া প্রতি মাসে EMI জমা দিতে হবে নিম্নে উল্লিখিত পরিমাণে =৭০০০/৯০০০/১২০০০/১৫০০০=৬০/৫৮ মাসে মূল্য পরিশোধ করার সুযোগ রয়েছে।</li></ul><p><strong style=\"background-color: rgb(255, 229, 153);\">এই মুহূর্তে ডিসকাউন্ট অফার চলছে ক্রমান্বয়েঃ</strong>\r\n</p><ul><li>৩,৭৫,০০০ টাকা করিয়া :- ১,০০০ শেয়ার (শেষ)\r\n</li><li>৪,৪৫,০০০ টাকা করিয়া :- ২,০০০ শেয়ার (চলমান)\r\n</li><li>৫,৬৫,০০০ টাকা করিয়া :- ২,০০০ শেয়ার\r\n</li><li>৭,৪৫,০০০ টাকা করিয়া :- ৫,০০০ শেয়ার\r\n</li><li>৯,২৫,০০০ টাকা করিয়া :- শেষ ১০,০০০ শেয়ারের মূল্য নির্ধারণ করা আছে। ভবিষ্যতে প্রয়োজন অনুযায়ী/কোম্পানীর Board of Director-এর সিদ্ধান্ত অনুযায়ী শেয়ার মূল্য বৃদ্ধি হতে পারে যাহা ১২,০০০,০০ ( বারো লক্ষ টাকা) পর্যন্ত নির্ধারণ করা হতে পারে।</li></ul><p><strong style=\"background-color: rgb(255, 229, 153);\">একজন শেয়ারহোল্ডার ভূমির সাব কবলা রেজিস্ট্রি সহ নিম্নোলিখিত সুযোগ সুবিধা পাবেনঃ</strong>\r\n</p><ul><li>ভূমির মালিকানা পাবেন (আজীবনের জন্য) ১০ স্কয়ার ফিট+ভবনে পাবেন ১০ স্কয়ার ফিট।\r\n</li><li>প্রতি মাসে ভাড়া আয় করার সুযোগ পাবেন। সকল শেয়ার হোল্ডার সমহারে ইনকাম পাবেন। যা প্রত্যেক শেয়ার হোল্ডারের ব্যাংক একাউন্টে সমন্বয় করা হবে, একটা শেয়ারের বিপরীতে বছরে ১,০০,০০০ থেকে ২,৫০,০০০ এর অধিক আয় হতে পারে।\r\n</li><li>প্রতি বছরে একবার পরিবার নিয়ে, ৩ রাত ৪ দিন নিজ মালিকানাধীন পাঁচ তারকা হোটেলে যাপন করার সুযোগ পাবেন। বছরে বারং বার যেতে চাইলে প্রতিবছরই শেয়ারহোল্ডার পাবেন রুম ভাড়ার উপর ৫০% ডিসকাউন্ট সুবিধা। কোন শেয়ারহোল্ডার যদি কাউকে রেফার করে তাহলে ঐ ব্যক্তি হোটেলের রুম ভাড়ার ওপর ৩০% সুবিধা পাবেন।\r\n</li><li>এছাড়াও প্রত্যেক শেয়ারহোল্ডারকে একটা Ownership Card প্রদান করা হবে। যার মাধ্যমে প্রজেক্টের অন্যান্য সকল সুযোগ-সুবিধা ভোগ করতে পারবেন।</li></ul><p><strong><span style=\"background-color: rgb(255, 229, 153);\">বিপদে পরিবারের একটা নিরাপত্তা পাবেন।</span></strong></p><ul><li>বুকিং কনফার্ম করিয়া পর পর ১২ মাসের EMI যদি পরিশোধ করা থাকে সেই ক্ষেত্রে কোন শেয়ারহোল্ডারের যে কোন কারণবশত মৃত্যুবরণ করেন, (তবে সেটা যেন আত্মহত্যা না হয়), অথবা কোন শেয়ারহোল্ডার দুর্ঘটনা জনিত কোন কারণে যদি মৃত্যু বরণ করে তাহলে বকেয়া থাকা EMI পরিশোধ করা ছাড়াই সময়াত্তে তার পরিবার জমির সাব কবলা রেজিস্ট্রি সহ শেয়ারের চুক্তিনামা বুঝিয়া পাবেন এবং বৃদ্ধিমূল্য ছাড়া তৎক্ষণাৎ সকল EMI মাফ/ফেরত পাবেন।</li></ul><p><strong><span style=\"color: rgb(225, 55, 55);\">বিঃদ্রঃ শেয়ারহোল্ডারের বিনিয়োগ কৃত অর্থ প্রকল্প বাস্তবায়নের পূর্বে ফেরত চাওয়া বা পাওয়ার কোনো সুযোগ থাকবে না। প্রকল্প বাস্তবায়নের পরে নিয়ম অনুযায়ী শেয়ার হস্তান্তর করতে পারবেন বা অধিক মূল্যে বিক্রয় করিতে পারবেন (শর্ত প্রযোজ্য)</span></strong></p><p><strong>দলিল এবং সার্টিফিকেটঃ</strong><br>\r\nকোম্পানির বোর্ড অফ ডিরেক্টর সিদ্ধান্ত অনুযায়ী যাদের শেয়ারের সম্পূর্ণ টাকা পরিশোধ করা থাকবে তাদেরকে ০১/০১/২০২৭ তারিখ থেকে শেয়ার সার্টিফিকেট সহ জমির সাব কবলা রেজিস্ট্রি বুঝিয়ে দেওয়া হবে।</p><p>প্রকল্প পরিপূর্ণ হওয়ার পর মেরিন ড্রাইভ রোড জিরো পয়েন্ট থেকে এয়ারপোর্ট পর্যন্ত প্রকল্পের নিজস্ব পরিবহন ব্যবস্থাপনা থাকবে।</p>\";s:10:\"created_at\";s:19:\"2026-06-26 22:24:13\";s:10:\"updated_at\";s:19:\"2026-09-26 20:14:38\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:12:\"office_cards\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:17:{i:0;s:11:\"youtube_url\";i:1;s:12:\"facebook_url\";i:2;s:13:\"contact_email\";i:3;s:13:\"contact_phone\";i:4;s:14:\"location_title\";i:5;s:17:\"location_subtitle\";i:6;s:16:\"location_map_url\";i:7;s:20:\"office_section_title\";i:8;s:23:\"office_section_subtitle\";i:9;s:11:\"office_name\";i:10;s:14:\"office_address\";i:11;s:14:\"office_map_url\";i:12;s:12:\"office_cards\";i:13;s:11:\"terms_title\";i:14;s:14:\"terms_subtitle\";i:15;s:11:\"terms_intro\";i:16;s:13:\"terms_content\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}',1790510033);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_06_25_000003_create_admins_table',1),(5,'2026_06_26_000004_add_mobile_to_admins_table',2),(6,'2026_06_26_000005_add_full_name_to_admins_table',3),(7,'2026_06_27_000006_create_site_notices_table',4),(8,'2026_06_27_000007_create_about_sections_table',5),(9,'2026_06_27_000008_add_subtitle_to_about_sections_table',6),(10,'2026_06_27_000009_create_footer_settings_table',7),(11,'2026_06_27_000010_add_contact_phone_to_footer_settings_table',8),(12,'2026_06_27_000011_create_why_sections_table',9),(13,'2026_06_27_000012_add_terms_fields_to_footer_settings_table',10),(14,'2026_06_27_000013_add_terms_subtitle_to_footer_settings_table',11),(15,'2026_06_28_000014_create_project_sections_table',12),(16,'2026_06_28_000015_add_grouped_cards_to_project_sections_table',13),(17,'2026_07_01_000016_add_location_fields_to_footer_settings_table',14),(18,'2026_07_01_000017_add_office_fields_to_footer_settings_table',15),(19,'2026_07_01_000018_add_office_cards_to_footer_settings_table',16),(20,'2026_07_01_000019_create_gallery_sections_table',17),(21,'2026_07_01_000020_create_shareholder_review_sections_table',18),(22,'2026_07_03_000021_create_leadership_sections_table',19),(23,'2026_07_03_000022_add_founder_description_to_leadership_sections_table',20),(24,'2026_07_03_000023_expand_founder_description_length',21),(25,'2026_07_04_000024_create_valued_shareholder_sections_table',22),(26,'2026_07_04_000001_add_hero_background_path_to_site_notices_table',23),(27,'2026_07_05_000001_add_session_version_to_admins_table',23),(28,'2026_07_06_000001_create_valued_shareholders_table',24),(29,'2026_07_07_000001_create_prospectus_sections_table',25),(30,'2026_07_08_000001_create_faqs_table',26),(31,'2026_07_09_000001_add_two_factor_columns_to_admins_table',27),(32,'2026_07_10_000001_add_board_members_visible_to_leadership_sections_table',27),(33,'2026_07_11_000001_add_secondary_leader_to_leadership_sections_table',28);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
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
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
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
INSERT INTO `sessions` VALUES ('1aXwbn3n6bWmX9PrfE6jgUg1IK6DvpLzGSMXcNQv',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUHEyY3drNDdqUkpoV1lseXVLUHNtZE9sVG1XS1Y3THoyMWtHc3NBZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9mb3Jnb3QtcGFzc3dvcmQiO3M6NToicm91dGUiO3M6MTY6InBhc3N3b3JkLnJlcXVlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1790506509),('2c9sZznzG52HAXu7ZpwEJ44ZM0Jb3VbiZ9v9kItH',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZm90NnBQckNEenIzbkdxU1Qyczlmc3ZTVTg0YWR0TnlodUNsTThMOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODA4MC9mb3Jnb3QtcGFzc3dvcmQiO3M6NToicm91dGUiO3M6MTY6InBhc3N3b3JkLnJlcXVlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YToxOntpOjA7czo2OiJlcnJvcnMiO31zOjM6Im5ldyI7YTowOnt9fXM6NjoiZXJyb3JzIjtPOjMxOiJJbGx1bWluYXRlXFN1cHBvcnRcVmlld0Vycm9yQmFnIjoxOntzOjc6IgAqAGJhZ3MiO2E6MTp7czo3OiJkZWZhdWx0IjtPOjI5OiJJbGx1bWluYXRlXFN1cHBvcnRcTWVzc2FnZUJhZyI6Mjp7czoxMToiACoAbWVzc2FnZXMiO2E6MTp7czo1OiJlbWFpbCI7YToxOntpOjA7czo2NjoiVG9vIG1hbnkgcGFzc3dvcmQgcmVzZXQgYXR0ZW1wdHMuIFBsZWFzZSB0cnkgYWdhaW4gaW4gMyBtaW51dGUocykuIjt9fXM6OToiACoAZm9ybWF0IjtzOjg6IjptZXNzYWdlIjt9fX19',1790506245),('2fG1NAy06xtn3Q9JLa29QtZ2h3vYJAK8TPwEY2tm',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoibFBGTHk1emdvcFc3bGxEVWU2MEoyY3hhMWg4cTJqaTU4RzRWbjhvVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9jb250ZW50LW1hbmFnZW1lbnQiO3M6NToicm91dGUiO3M6MTk6ImFkbWluLmNvbnRlbnQuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1790502548),('5C9t6UL5vuHuJbKyJgJCNjYrBTzIlcrx7LLBG6iP',NULL,'127.0.0.1','Symfony','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN0tXSnJPUHFLcEY3cHRpdlJqUnlqWVZDSTJhQ1FUYW1FaXVIdnFyNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1790508395),('9xJOxcNE3ONPkWoXKiEYZl6ygCHxEv7VoLHRTdHM',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ0lrZlVtNVRvTnluSGpsVUQ3TnpRUHNCcVJ3VHltSkJjdENOTjhMbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9nYWxsZXJ5IjtzOjU6InJvdXRlIjtzOjEzOiJnYWxsZXJ5LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1790501130),('b9hZNVrJVY5VEtFiqkkejTDF11ovKqvrvowlSNPp',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY2o1Rk5mY21MRnJVY2MzREo0Vml5YkRhMnVpeGdOM0h2ODFQRFl4QyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODA4MCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czoyMDoicGFzc3dvcmRfcmVzZXRfZW1haWwiO3M6MjE6InpvYmF5ZXIxMDg0QGdtYWlsLmNvbSI7fQ==',1790508430),('bWmsp8mboyLqbb0XGHK0lhfM966V3QWb3rgUsSde',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMmNVbE9MN0FhTnVidHg3Y01NT0JOV1Jwd3Frbm5DVHZDV29hT05PcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1790500297),('EbPIzDkOQSt5t7H2lT9bpAMW2tlLFY4sjalFEFvP',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiaGk0UVY3ZHpXUnVOc2g0S3U3bVBib2pZMnFNdEdPYlEwZ0JST2tUVCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODA4MCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MjE6ImFkbWluX3Nlc3Npb25fdmVyc2lvbiI7aTo0O3M6MjI6ImFkbWluX2xhc3RfYWN0aXZpdHlfYXQiO2k6MTc5MDUwNjAwNDt9',1790508410),('Kdt6ZbKuHaxfaYn0rkBOfKV4tupLudrMmBZoWwd8',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444','YTozOntzOjY6Il90b2tlbiI7czo0MDoia2hENENCOWVzS2VubEU2U0FBU0lhZTl6dXQ2TmZIbW5sOExjMlNCVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1790500278),('qLsqZgxdBG85zpmrBHeVAD1EcvfYNIng9Flpn5J5',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMjhjN2VsWGxLREprMTFzcnlyejh6b0l3MVhQajJHbEptSTMySm9SaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9nYWxsZXJ5IjtzOjU6InJvdXRlIjtzOjEzOiJnYWxsZXJ5LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1790501113),('qMu7qijnzY95m5vg69MMd6eBubUWsikFlMWlOZb2',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZnNPdFdQRktmaUxqZHZIT1NiUlREdE8xaFN0ZXJEQXp0Y3BUYU9OSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1790502378),('QpvVzKTxtfNfeYspt7qWVrqyktL8AqNLldkwVzch',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTUFQOFczOHd3dkF2VlQwRVA5aWRXZnFpWVdySzZ4bW02aG1TT3pKZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9mb3Jnb3QtcGFzc3dvcmQiO3M6NToicm91dGUiO3M6MTY6InBhc3N3b3JkLnJlcXVlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YToxOntpOjA7czo2OiJzdGF0dXMiO31zOjM6Im5ldyI7YTowOnt9fXM6MjA6InBhc3N3b3JkX3Jlc2V0X2VtYWlsIjtzOjIxOiJ6b2JheWVyMTA4NEBnbWFpbC5jb20iO3M6Njoic3RhdHVzIjtzOjQxOiJXZSBoYXZlIGVtYWlsZWQgeW91ciBwYXNzd29yZCByZXNldCBsaW5rLiI7fQ==',1790505771),('T4zHifnmijidyHgy2Q65ltV6Bv5oSn0P4eM4G0Fa',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSW9YcnZSOE02emUzRnVCcVJLY2p1ZjhUY0tNNmRGUmtyWTJhaDA4YiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1790502402),('vWQvl16JXFkk05tkQUo6ComjcyGghTGeuyTYhFH5',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTUxoYjFXT2ZpTlVwMjJ3NDB4VEdlU0l6ZjV3cFNDTUFWM05IUEpyNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9mb3Jnb3QtcGFzc3dvcmQiO3M6NToicm91dGUiO3M6MTY6InBhc3N3b3JkLnJlcXVlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1790505436),('WGTi5wQEadQfO6Xp4jM0SUzUNUW0BBr90hPs411G',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicEN5RUZESmpZa3JDMzZESXhuYjhBZXBNTFJjRXhBWGFKUWRrRVpmTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1790502303),('xyp2pfI2HHvor5Lj1paT8ro1waZK99TPAv69nVc2',NULL,'127.0.0.1','curl/8.21.0','YToyOntzOjY6Il90b2tlbiI7czo0MDoiQXUwWWpveEM1MVBFTllDc21IZko4VjZZM2x2ckZ2VlVLRTBGMlRRQyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1790501896),('XZqHwrpaDrwSC0D1k1ZU3lMrNv5LlRLU9FImyrTE',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiR0xxcVViVGVmOE1qVTdPUEV2OEo4T0x2UzVVbHhTc20zdGFKMnlnZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9mb3Jnb3QtcGFzc3dvcmQiO3M6NToicm91dGUiO3M6MTY6InBhc3N3b3JkLnJlcXVlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YToxOntpOjA7czo2OiJzdGF0dXMiO31zOjM6Im5ldyI7YTowOnt9fXM6MjA6InBhc3N3b3JkX3Jlc2V0X2VtYWlsIjtzOjIxOiJ6b2JheWVyMTA4NEBnbWFpbC5jb20iO3M6Njoic3RhdHVzIjtzOjQxOiJXZSBoYXZlIGVtYWlsZWQgeW91ciBwYXNzd29yZCByZXNldCBsaW5rLiI7fQ==',1790505476),('Yf1wY4v8ZBdNTxR6TvbKs4MEjhepff0Ejz9Dw7bG',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444','YTo1OntzOjY6Il90b2tlbiI7czo0MDoicmRidDM2ZlNoeWttUUlEZ2NuajFhMFM0R1V5a1FES2t0MXB5Yk5WQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9mb3Jnb3QtcGFzc3dvcmQiO3M6NToicm91dGUiO3M6MTY6InBhc3N3b3JkLnJlcXVlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YToxOntpOjA7czo2OiJzdGF0dXMiO31zOjM6Im5ldyI7YTowOnt9fXM6MjA6InBhc3N3b3JkX3Jlc2V0X2VtYWlsIjtzOjIxOiJ6b2JheWVyMTA4NEBnbWFpbC5jb20iO3M6Njoic3RhdHVzIjtzOjQxOiJXZSBoYXZlIGVtYWlsZWQgeW91ciBwYXNzd29yZCByZXNldCBsaW5rLiI7fQ==',1790505931),('ZML8dKz6rSLr4f9RJddRAMrhy6pydt1OSpIMqERQ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiN2p6TEdrWHlHUU5xdjM5OEpUUHFQN1M1d0lIaEFBQXZVbXR5Q0ZaeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODA4MC9mb3Jnb3QtcGFzc3dvcmQiO3M6NToicm91dGUiO3M6MTY6InBhc3N3b3JkLnJlcXVlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YToxOntpOjA7czo2OiJzdGF0dXMiO31zOjM6Im5ldyI7YTowOnt9fXM6MjA6InBhc3N3b3JkX3Jlc2V0X2VtYWlsIjtzOjIxOiJ6b2JheWVyMTA4NEBnbWFpbC5jb20iO3M6Njoic3RhdHVzIjtzOjQxOiJXZSBoYXZlIGVtYWlsZWQgeW91ciBwYXNzd29yZCByZXNldCBsaW5rLiI7fQ==',1790506312),('ZpGEmI7F7UV28ofkjlwImKZwLlA9D5LEPTWOsu6F',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRThPV0lKUGV0TVJMR3RBTExnd2pPM2d2NFg0ajVoR1pVRVJrR3k5QSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9nYWxsZXJ5IjtzOjU6InJvdXRlIjtzOjEzOiJnYWxsZXJ5LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1790500656);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
--



--
-- Table structure for table `about_sections`
--

DROP TABLE IF EXISTS `about_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `about_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `left_video_url` varchar(2048) DEFAULT NULL,
  `right_video_url` varchar(2048) DEFAULT NULL,
  `left_thumbnail_path` varchar(255) DEFAULT NULL,
  `right_thumbnail_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `about_sections`
--

LOCK TABLES `about_sections` WRITE;
/*!40000 ALTER TABLE `about_sections` DISABLE KEYS */;
INSERT INTO `about_sections` VALUES (1,'About King Lotus Group','A Luxury & Signature Destination in Cox’s Bazar.','King Lotus International, under construction in the heart of Cox’s Bazar, is a luxury signature destination designed to redefine world-class hospitality. With elegant architecture, breathtaking sea views, premium facilities, and personalized service, we are creating an exclusive lifestyle experience where every moment reflects comfort, beauty, trust and excellence.','https://youtu.be/Ghyljd1crBk?si=6d313vXfPsiVAoor','https://youtu.be/IJH-LOwfOhk?si=-NkVzUfz7pW_LGYK','uploads/about/b9e3f459-b627-4927-b861-bf59db445355.webp','uploads/about/fa668884-ba56-49d9-a3f5-82766225639f.webp','2026-06-26 14:00:06','2026-06-26 15:14:53');
/*!40000 ALTER TABLE `about_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,'What is King Lotus International hotel share ownership?','It is an exclusive opportunity to purchase fractional ownership shares in prime luxury hotel and resort developments. Shareholders own certified property equity and earn proportional revenue from hotel operations without the burden of daily management.',1,1,'2026-09-26 15:22:37','2026-09-26 15:22:37'),(2,'How and when do shareholders receive profits or dividends?','Dividend returns are generated from hotel room bookings, dining, events, and overall resort operations. Audited earnings are distributed directly to each shareholder\'s registered bank account on a structured annual or bi-annual schedule, backed by transparent financial reporting.',2,1,'2026-09-26 15:22:37','2026-09-26 15:22:37'),(3,'Are shareholders entitled to complimentary room stays and discounts?','Yes. Every valued shareholder enjoys dedicated complimentary room nights annually, priority suite reservations, VIP lounge access, and exclusive discounts on resort dining, spa services, and events across all King Lotus properties.',3,1,'2026-09-26 15:22:37','2026-09-26 15:22:37'),(4,'What legal documents guarantee my share investment?','Every shareholder receives an official, legally notarized Share Ownership Certificate and investment deed registered under King Lotus International, alongside verified credentials for the online Shareholder Portal to track investment valuation and updates.',4,1,'2026-09-26 15:22:37','2026-09-26 15:22:37'),(5,'Can I transfer, sell, or pass my shares to my family or nominees?','Absolutely. King Lotus hotel shares are legally recognized, transferable assets. You may transfer ownership, assign designated nominees or legal heirs, or sell shares through our official shareholder buy-back and exchange program.',5,1,'2026-09-26 15:22:37','2026-09-26 15:22:37');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `footer_settings`
--

DROP TABLE IF EXISTS `footer_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `footer_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `youtube_url` varchar(2048) DEFAULT NULL,
  `facebook_url` varchar(2048) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `location_title` varchar(180) DEFAULT NULL,
  `location_subtitle` varchar(255) DEFAULT NULL,
  `location_map_url` varchar(2048) DEFAULT NULL,
  `office_section_title` varchar(180) DEFAULT NULL,
  `office_section_subtitle` varchar(255) DEFAULT NULL,
  `office_name` varchar(180) DEFAULT NULL,
  `office_address` text DEFAULT NULL,
  `office_map_url` varchar(2048) DEFAULT NULL,
  `office_cards` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`office_cards`)),
  `terms_title` varchar(180) DEFAULT NULL,
  `terms_subtitle` varchar(255) DEFAULT NULL,
  `terms_intro` text DEFAULT NULL,
  `terms_content` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `footer_settings`
--

LOCK TABLES `footer_settings` WRITE;
/*!40000 ALTER TABLE `footer_settings` DISABLE KEYS */;
INSERT INTO `footer_settings` VALUES (1,'https://youtube.com/@kinglotusinternationalltd-x2v?si=Hj8U8b8PIOlX5Dk8','https://www.facebook.com/nurulamind2k','kinglotuscox@gmail.com','+880 1816-236822\r\n+880 1815-004959','Visit Our Location','Open our Google Maps location to plan your arrival and explore the surrounding destination.','https://maps.app.goo.gl/23NPMkWX8JADhU7t9','Get A Quote - No Cost, No Commitment','Transparent & Competitive Rates','Head Office','Dhaka Office\r\n54 - Motijheel, Elite House, Motijheel, Dhaka - 1000','https://maps.app.goo.gl/WypUJpRgC84Rw2uB7','[{\"name\":\"Head Office\",\"address\":\"Dhaka Office\\r\\n54 - Motijheel, Elite House, Motijheel, Dhaka - 1000\",\"map_url\":\"https:\\/\\/maps.app.goo.gl\\/WypUJpRgC84Rw2uB7\",\"phone\":\"+88 01897672250\",\"email\":\"support@kinglotusgroup.com\"},{\"name\":\"Branch Office\",\"address\":\"Cumilla Office\\r\\n491\\/437 Artision Nasir Center, Kandirpar, Cumilla-3500\",\"map_url\":\"https:\\/\\/maps.app.goo.gl\\/YaRLtrNbBUwCiqEo9\",\"phone\":\"+88 01816236822\",\"email\":\"cumilla@kinglotusgroup.com\"},{\"name\":\"Foreign Office\",\"address\":\"Qatar Office\\r\\nRawdat Al Khail, B Ring Road, Zone No 24, Street No 220, 2nd Floor, Doha, Qatar\",\"map_url\":\"\",\"phone\":\"+974 77 93 92 88\",\"email\":\"\"}]','Terms and Conditions','কিং লোটাস ইন্টারন্যাশনাল লিমিটেড',NULL,'<p><strong>শেয়ারের সুবিধা প্রাপ্তি এবং কোম্পানির নীতিমালাঃ</strong>\r\n</p><p><span style=\"background-color: rgb(60, 120, 216); color: rgb(239, 239, 239);\">প্রস্তাবিত পাঁচ তারকা হোটেলের প্রতিটি শেয়ারের মূল্য =&gt; ৯,২৫,০০০/= টাকা। (বর্তমান নির্ধারিত শেয়ার মূল্য)</span></p><ul><li><strong>বুকিং মানিঃ</strong><br>\r\n৩৫,০০০ টাকা কনফার্ম করিয়া প্রতি মাসে EMI জমা দিতে হবে নিম্নে উল্লিখিত পরিমাণে =৭০০০/৯০০০/১২০০০/১৫০০০=৬০/৫৮ মাসে মূল্য পরিশোধ করার সুযোগ রয়েছে।</li></ul><p><strong style=\"background-color: rgb(255, 229, 153);\">এই মুহূর্তে ডিসকাউন্ট অফার চলছে ক্রমান্বয়েঃ</strong>\r\n</p><ul><li>৩,৭৫,০০০ টাকা করিয়া :- ১,০০০ শেয়ার (শেষ)\r\n</li><li>৪,৪৫,০০০ টাকা করিয়া :- ২,০০০ শেয়ার (চলমান)\r\n</li><li>৫,৬৫,০০০ টাকা করিয়া :- ২,০০০ শেয়ার\r\n</li><li>৭,৪৫,০০০ টাকা করিয়া :- ৫,০০০ শেয়ার\r\n</li><li>৯,২৫,০০০ টাকা করিয়া :- শেষ ১০,০০০ শেয়ারের মূল্য নির্ধারণ করা আছে। ভবিষ্যতে প্রয়োজন অনুযায়ী/কোম্পানীর Board of Director-এর সিদ্ধান্ত অনুযায়ী শেয়ার মূল্য বৃদ্ধি হতে পারে যাহা ১২,০০০,০০ ( বারো লক্ষ টাকা) পর্যন্ত নির্ধারণ করা হতে পারে।</li></ul><p><strong style=\"background-color: rgb(255, 229, 153);\">একজন শেয়ারহোল্ডার ভূমির সাব কবলা রেজিস্ট্রি সহ নিম্নোলিখিত সুযোগ সুবিধা পাবেনঃ</strong>\r\n</p><ul><li>ভূমির মালিকানা পাবেন (আজীবনের জন্য) ১০ স্কয়ার ফিট+ভবনে পাবেন ১০ স্কয়ার ফিট।\r\n</li><li>প্রতি মাসে ভাড়া আয় করার সুযোগ পাবেন। সকল শেয়ার হোল্ডার সমহারে ইনকাম পাবেন। যা প্রত্যেক শেয়ার হোল্ডারের ব্যাংক একাউন্টে সমন্বয় করা হবে, একটা শেয়ারের বিপরীতে বছরে ১,০০,০০০ থেকে ২,৫০,০০০ এর অধিক আয় হতে পারে।\r\n</li><li>প্রতি বছরে একবার পরিবার নিয়ে, ৩ রাত ৪ দিন নিজ মালিকানাধীন পাঁচ তারকা হোটেলে যাপন করার সুযোগ পাবেন। বছরে বারং বার যেতে চাইলে প্রতিবছরই শেয়ারহোল্ডার পাবেন রুম ভাড়ার উপর ৫০% ডিসকাউন্ট সুবিধা। কোন শেয়ারহোল্ডার যদি কাউকে রেফার করে তাহলে ঐ ব্যক্তি হোটেলের রুম ভাড়ার ওপর ৩০% সুবিধা পাবেন।\r\n</li><li>এছাড়াও প্রত্যেক শেয়ারহোল্ডারকে একটা Ownership Card প্রদান করা হবে। যার মাধ্যমে প্রজেক্টের অন্যান্য সকল সুযোগ-সুবিধা ভোগ করতে পারবেন।</li></ul><p><strong><span style=\"background-color: rgb(255, 229, 153);\">বিপদে পরিবারের একটা নিরাপত্তা পাবেন।</span></strong></p><ul><li>বুকিং কনফার্ম করিয়া পর পর ১২ মাসের EMI যদি পরিশোধ করা থাকে সেই ক্ষেত্রে কোন শেয়ারহোল্ডারের যে কোন কারণবশত মৃত্যুবরণ করেন, (তবে সেটা যেন আত্মহত্যা না হয়), অথবা কোন শেয়ারহোল্ডার দুর্ঘটনা জনিত কোন কারণে যদি মৃত্যু বরণ করে তাহলে বকেয়া থাকা EMI পরিশোধ করা ছাড়াই সময়াত্তে তার পরিবার জমির সাব কবলা রেজিস্ট্রি সহ শেয়ারের চুক্তিনামা বুঝিয়া পাবেন এবং বৃদ্ধিমূল্য ছাড়া তৎক্ষণাৎ সকল EMI মাফ/ফেরত পাবেন।</li></ul><p><strong><span style=\"color: rgb(225, 55, 55);\">বিঃদ্রঃ শেয়ারহোল্ডারের বিনিয়োগ কৃত অর্থ প্রকল্প বাস্তবায়নের পূর্বে ফেরত চাওয়া বা পাওয়ার কোনো সুযোগ থাকবে না। প্রকল্প বাস্তবায়নের পরে নিয়ম অনুযায়ী শেয়ার হস্তান্তর করতে পারবেন বা অধিক মূল্যে বিক্রয় করিতে পারবেন (শর্ত প্রযোজ্য)</span></strong></p><p><strong>দলিল এবং সার্টিফিকেটঃ</strong><br>\r\nকোম্পানির বোর্ড অফ ডিরেক্টর সিদ্ধান্ত অনুযায়ী যাদের শেয়ারের সম্পূর্ণ টাকা পরিশোধ করা থাকবে তাদেরকে ০১/০১/২০২৭ তারিখ থেকে শেয়ার সার্টিফিকেট সহ জমির সাব কবলা রেজিস্ট্রি বুঝিয়ে দেওয়া হবে।</p><p>প্রকল্প পরিপূর্ণ হওয়ার পর মেরিন ড্রাইভ রোড জিরো পয়েন্ট থেকে এয়ারপোর্ট পর্যন্ত প্রকল্পের নিজস্ব পরিবহন ব্যবস্থাপনা থাকবে।</p>','2026-06-26 16:24:13','2026-09-26 14:14:38');
/*!40000 ALTER TABLE `footer_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_sections`
--

DROP TABLE IF EXISTS `gallery_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section_title` varchar(255) DEFAULT NULL,
  `section_subtitle` varchar(180) DEFAULT NULL,
  `view_all_label` varchar(120) DEFAULT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `page_subtitle` varchar(255) DEFAULT NULL,
  `featured_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`featured_images`)),
  `albums` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`albums`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_sections`
--

LOCK TABLES `gallery_sections` WRITE;
/*!40000 ALTER TABLE `gallery_sections` DISABLE KEYS */;
INSERT INTO `gallery_sections` VALUES (1,'Recent Gallery','Featured Moments','View All','Gallery Albums','Explore curated albums and featured moments from King Lotus Group.','[{\"order\":1,\"image_path\":\"uploads\\/gallery\\/featured\\/a0e72959-0d8c-4558-a105-867d5a0b1503.webp\"},{\"order\":2,\"image_path\":\"uploads\\/gallery\\/featured\\/a30415c1-3684-4354-9b16-4b605b7968f1.webp\"},{\"order\":3,\"image_path\":\"uploads\\/gallery\\/featured\\/3fa60a4c-ac22-496c-8900-27e1c78eec25.webp\"},{\"order\":4,\"image_path\":\"uploads\\/gallery\\/featured\\/8301b0ad-79e1-4b60-b838-0c74bb9beb88.webp\"},{\"order\":5,\"image_path\":\"uploads\\/gallery\\/featured\\/9ad98d0c-87f9-469c-b901-712351dfd85e.webp\"},{\"order\":6,\"image_path\":\"uploads\\/gallery\\/featured\\/96eb81e0-ff69-464c-8c4a-fe87a3b73a5c.webp\"},{\"order\":7,\"image_path\":\"uploads\\/gallery\\/featured\\/5f8a37c5-d6ca-478f-9b31-b50466bd6111.webp\"}]','[{\"title\":\"\\u09aa\\u09cd\\u09b0\\u099c\\u09c7\\u0995\\u09cd\\u099f \\u09ad\\u09bf\\u099c\\u09bf\\u099f\",\"subtitle\":\"\\u09b8\\u09cd\\u09ac\\u09aa\\u09cd\\u09a8\\u09c7\\u09b0 \\u09b8\\u09cd\\u09a5\\u09be\\u09aa\\u09a8\\u09be\\u09af\\u09bc \\u0986\\u09ae\\u09be\\u09a6\\u09c7\\u09b0 \\u09b8\\u09a6\\u09b8\\u09cd\\u09af\\u09b0\\u09be\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/f07d6fa2-ef1e-4450-bab7-e9bb324025ca.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/4215ec1b-34a4-4337-bae0-331094100835.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/de30a0e9-df64-4c9f-abb8-3dde1605c8ff.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/9369e497-5e32-4b71-b43b-06ec50647e1e.webp\"}]},{\"title\":\"\\u09b8\\u09a6\\u09b8\\u09cd\\u09af \\u09b8\\u09ae\\u09cd\\u09ae\\u09c7\\u09b2\\u09a8\",\"subtitle\":\"\\u09a8\\u09c7\\u09a4\\u09c3\\u09a4\\u09cd\\u09ac \\u0993 \\u09b8\\u09a6\\u09b8\\u09cd\\u09af\\u09a6\\u09c7\\u09b0 \\u09ae\\u09bf\\u09b2\\u09a8\\u09ae\\u09c7\\u09b2\\u09be\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/c13e64c2-8755-4449-8ae1-b7695293eb9d.webp\"}]},{\"title\":\"\\u09b8\\u09cd\\u09ac\\u09aa\\u09cd\\u09a8\\u09c7\\u09b0 \\u09a8\\u09bf\\u09b0\\u09cd\\u09ae\\u09be\\u09a3\\u09af\\u09be\\u09a4\\u09cd\\u09b0\\u09be\",\"subtitle\":\"\\u09b8\\u09cd\\u09ac\\u09aa\\u09cd\\u09a8 \\u09aa\\u09c2\\u09b0\\u09a3\\u09c7\\u09b0 \\u09a8\\u09bf\\u09b0\\u09cd\\u09ae\\u09be\\u09a3\\u09af\\u09be\\u09a4\\u09cd\\u09b0\\u09be\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/35ff816e-ba22-4e29-a5b6-32019c3ec79c.webp\"}]},{\"title\":\"King Lotus International (5 Star Hotel)\",\"subtitle\":\"Flagship 5-Star Luxury Resort & Suites in Cox\'s Bazar\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/7efcb82c-16f3-423b-9597-9f37f9d24c39.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/66822633-cc91-4af7-9220-e5bd7abc4754.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/a6dfe8c2-0839-4131-b237-6ab814e21d73.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/9ca9bdf1-6d48-4a57-abaa-b220366e5485.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/e4d2f2d2-e552-4077-92a9-4978584f123c.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/4014f674-9ee9-46a2-b3a8-478ba49a6f3c.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/9d90266a-6411-4f11-af56-cca61f24de0c.webp\"}]},{\"title\":\"Luxury 3 Star Hotel\",\"subtitle\":\"Modern Comfort and Premium Hospitality\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/216267e0-c486-451f-8808-c6cd3920131a.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/0dc79fb2-af2d-4acf-9026-e048da533b82.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/6a37178e-b278-4f51-bbe6-2f57c3863327.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/67e44253-c9e0-4587-aaad-a722f359aa8e.webp\"}]},{\"title\":\"Luxury Studio Hotel\",\"subtitle\":\"Exclusive Studio Living Residences\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/2880b9d1-517e-4a77-8829-b76d544ba76f.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/aafd7d6e-44e9-4a62-91f0-7440694117e7.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/a4b2c647-b62e-4072-96e4-df4fac2e5190.webp\"}]},{\"title\":\"Triple Zone Water Park\",\"subtitle\":\"Ladies Exclusive, Family Fun & Gents Adventure Zones\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/d2da56be-56c0-4f72-9f08-295377272b02.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/b8b9a9b1-9a0a-47ef-bdcc-fe92ed99df7b.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/42aa5d00-88a1-4b68-9d0f-01f10ee9e80c.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/0039c021-ae7c-4e06-9761-b85417c136d7.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/ce67f5f7-01c1-42a1-b502-2f43f3e8f1cf.webp\"}]},{\"title\":\"Sea World Underwater Tunnel\",\"subtitle\":\"Ocean Wonders and Marine Life Experience\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/4ec63875-9784-47d6-80d6-0f4020aee45d.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/0cf4ffde-47e0-4825-8e94-6e01bca23d98.webp\"}]},{\"title\":\"Dual Swimming Zone\",\"subtitle\":\"Competition Pool & Relaxation Pool\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/e21c8ed6-ad68-45c5-adb4-e7e3cdf96487.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/827243a8-2080-4ea8-b91d-70ea924267cb.webp\"}]},{\"title\":\"The Central Mosque\",\"subtitle\":\"Serene and Architectural Spiritual Landmark\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/1f60d787-9ed0-42eb-a4b2-4601c87e6da7.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/bd6257b2-1107-41b1-a3f1-874f96c3e468.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/fcdcc5de-6e18-44e5-9516-141cface9079.webp\"}]},{\"title\":\"Shopping Mall & Cineplex\",\"subtitle\":\"World-Class Retail, Dining & Entertainment Complex\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/34f72cdb-ca31-4f96-be9e-3364c3aabc99.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/a924d740-6b3c-4e13-999a-e37778437727.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/48dc7f98-165a-4d1c-9135-1c7341d63a70.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/f31f55f4-d65e-4af3-934b-86df51c44676.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/5b4df207-9dbc-439e-b321-69b9845f8fc0.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/479e6d7d-71d4-4f29-9742-e0f44d6bc79c.webp\"}]},{\"title\":\"Baby Entertainment Point\",\"subtitle\":\"Interactive Kids Play & Educational Wonderland\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/a5e374b6-8758-4d92-ad34-673b6293fdf6.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/dc030ca5-686f-4716-83db-39148cbaa7fd.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/e9333a5a-4b0a-4c4a-8b25-05df1112a1aa.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/6a9e964c-5486-4f56-860f-c081c02d83a0.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/46c096ce-51d4-4a87-80ff-667acc940b9b.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/2ce6a46f-eca3-447f-8b7b-0faacc7175b1.webp\"}]},{\"title\":\"Live BBQ Village\",\"subtitle\":\"Authentic Open-Air Culinary Destination\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/e6523175-1abe-41df-92fa-518766cc6bc5.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/0a342dd5-b2d2-4d6f-bd75-ecac2dc0136a.webp\"}]},{\"title\":\"Indoor Stadium\",\"subtitle\":\"Multi-Sport Arena & Athletic Facilities\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/05e521c8-5eaf-47d2-afa8-d672b0a01e74.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/ec59cf10-71de-4c09-9f8c-c8356808a75a.webp\"}]},{\"title\":\"Project Site & Land\",\"subtitle\":\"Strategic Prime Location in Cox\'s Bazar\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/5e2023a7-0637-4dee-8ed3-b5f4301d3bdf.webp\"}]},{\"title\":\"Brand & Corporate Identity\",\"subtitle\":\"Executive Boardroom & Architectural Identity\",\"images\":[{\"image_path\":\"uploads\\/gallery\\/albums\\/63bfbe2f-4241-473e-86d1-9dfef4835491.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/5e18434e-86f9-4574-9068-2f0251ed6b48.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/ff68c678-75b8-4b5a-80a1-d929fea92e94.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/03737284-f848-45b5-bd17-c5162ed7e6c1.webp\"},{\"image_path\":\"uploads\\/gallery\\/albums\\/fd192fb1-75f3-4318-b58c-efb34cb3a75c.webp\"}]}]','2026-07-01 02:07:56','2026-09-26 10:07:10');
/*!40000 ALTER TABLE `gallery_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leadership_sections`
--

DROP TABLE IF EXISTS `leadership_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leadership_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section_title` varchar(180) DEFAULT NULL,
  `founder_name` varchar(180) DEFAULT NULL,
  `founder_position` varchar(180) DEFAULT NULL,
  `founder_description` varchar(200) DEFAULT NULL,
  `founder_image_path` varchar(2048) DEFAULT NULL,
  `secondary_leader_name` varchar(180) DEFAULT NULL,
  `secondary_leader_position` varchar(180) DEFAULT NULL,
  `secondary_leader_description` varchar(200) DEFAULT NULL,
  `secondary_leader_image_path` varchar(2048) DEFAULT NULL,
  `board_members` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`board_members`)),
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `board_members_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leadership_sections`
--

LOCK TABLES `leadership_sections` WRITE;
/*!40000 ALTER TABLE `leadership_sections` DISABLE KEYS */;
INSERT INTO `leadership_sections` VALUES (1,'Board of Directors','MD. Mizanur Rahman','Managing Director & CEO','Our commitment is to shape King Lotus into a world-class 5-star condominium resort built on trust, elegance and excellence.','uploads/leadership/founder/d2aa4666-b05b-45b4-9ace-4f169145fe6d.webp','MD. Nurul Amin','Sales & Marketing Director','Committed to driving sales growth and delivering luxury living experiences through innovative marketing strategies for King Lotus International Ltd.','uploads/leadership/founder/27ebf8d7-6369-4337-80a5-a6b7a19d0c66.webp','[{\"name\":\"MD. Nurul Amin\",\"position\":\"Sales & Marketing Director\",\"image_path\":\"uploads\\/leadership\\/members\\/3533e652-cac8-4a98-ac06-1c3e719803c8.webp\"}]',1,0,'2026-07-03 08:40:39','2026-09-27 04:00:15');
/*!40000 ALTER TABLE `leadership_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_sections`
--

DROP TABLE IF EXISTS `project_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `top_title` varchar(255) DEFAULT NULL,
  `top_button_label` varchar(120) DEFAULT NULL,
  `top_button_url` varchar(2048) DEFAULT NULL,
  `top_cards` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`top_cards`)),
  `bottom_title` varchar(255) DEFAULT NULL,
  `bottom_cards` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bottom_cards`)),
  `cards` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cards`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_sections`
--

LOCK TABLES `project_sections` WRITE;
/*!40000 ALTER TABLE `project_sections` DISABLE KEYS */;
INSERT INTO `project_sections` VALUES (1,'Luxury, Comfort and Exceptional Service — All In One Place.',NULL,NULL,'[{\"order\":1,\"title\":\"Luxury Studio Hotel\",\"location\":\"Cox Bazar\",\"image_path\":\"uploads\\/projects\\/1b61a7f1-b573-45f1-a2d5-b31b788998fe.webp\"},{\"order\":2,\"title\":\"King Lotus International\",\"location\":\"Cox Bazar\",\"image_path\":\"uploads\\/projects\\/a692328a-277f-4ae9-9dd5-ec5b9985977d.webp\"},{\"order\":3,\"title\":\"Luxury 3 Star Hotel\",\"location\":\"Cox Bazar\",\"image_path\":\"uploads\\/projects\\/4d7cfa1e-780c-4ad7-83c2-b31a9867a1e5.webp\"}]','Premium Destination for Relaxation','[{\"order\":1,\"title\":\"Sea World\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/bfe0758b-97a1-46ff-b996-0af877b0115c.webp\"},{\"order\":2,\"title\":\"Ladies Exclusive Zone\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/ac5dad34-0d5f-4642-9987-677105c9440a.webp\"},{\"order\":3,\"title\":\"Gents Zone\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/3007965c-02c3-4b44-a86a-d50b756e4d7f.webp\"},{\"order\":4,\"title\":\"Family Fun Zone\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/ce704a95-5548-494d-bff9-d474a2061f7a.webp\"},{\"order\":5,\"title\":\"Competition Pool\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/ff9e1ae7-896e-489b-9a21-148741672276.webp\"},{\"order\":6,\"title\":\"Relax pool\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/cc9c26a3-6c98-4f24-aa20-c880ef48b7bb.webp\"},{\"order\":7,\"title\":\"Elegant Mosque\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/b5f891e1-6584-4da3-955d-aa36c6950d90.webp\"},{\"order\":8,\"title\":\"Baby Entertaining Point\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/6702a38b-c1ea-4497-8f2b-32a4ef30d5e3.webp\"},{\"order\":9,\"title\":\"Shopping mall\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/c402215c-faff-424a-ace4-06e65d2c87e5.webp\"},{\"order\":10,\"title\":\"Live BBQ Village\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/626e7653-eaca-4616-9078-dfd636d2ef4f.webp\"},{\"order\":11,\"title\":\"Indoor stadium\",\"location\":\"\",\"image_path\":\"uploads\\/projects\\/63b39ba4-bf9c-4286-85d0-747f1dade7fc.webp\"}]',NULL,'2026-06-27 15:12:27','2026-09-26 09:45:01');
/*!40000 ALTER TABLE `project_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prospectus_sections`
--

DROP TABLE IF EXISTS `prospectus_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prospectus_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section_title` varchar(255) DEFAULT NULL,
  `section_subtitle` varchar(255) DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `brochures` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`brochures`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prospectus_sections`
--

LOCK TABLES `prospectus_sections` WRITE;
/*!40000 ALTER TABLE `prospectus_sections` DISABLE KEYS */;
INSERT INTO `prospectus_sections` VALUES (1,'Project Prospectus & Brochure','কিং লোটাস ইন্টারন্যাশনালের পূর্ণাঙ্গ প্রকল্প রূপরেখা',1,'[{\"title\":\"5-Star Luxury in Royal\",\"subtitle\":\"\\u0995\\u09bf\\u0982 \\u09b2\\u09cb\\u099f\\u09be\\u09b8 \\u0987\\u09a8\\u09cd\\u099f\\u09be\\u09b0\\u09a8\\u09cd\\u09af\\u09be\\u09b6\\u09a8\\u09be\\u09b2 \\u2014 \\u09ae\\u09c7\\u09b0\\u09bf\\u09a8 \\u09a1\\u09cd\\u09b0\\u09be\\u0987\\u09ad \\u09b0\\u09cb\\u09a1, \\u0995\\u0995\\u09cd\\u09b8\\u09ac\\u09be\\u099c\\u09be\\u09b0\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-01-cover.jpg\"},{\"title\":\"\\u09ad\\u09bf\\u09b6\\u09a8, \\u09ae\\u09bf\\u09b6\\u09a8 \\u0993 \\u098f\\u09ae\\u09a1\\u09bf\\u09b0 \\u09ac\\u09be\\u09b0\\u09cd\\u09a4\\u09be\",\"subtitle\":\"\\u0986\\u09a8\\u09cd\\u09a4\\u09b0\\u09cd\\u099c\\u09be\\u09a4\\u09bf\\u0995 \\u09ae\\u09be\\u09a8\\u09c7\\u09b0 \\u0986\\u09a4\\u09bf\\u09a5\\u09c7\\u09af\\u09bc\\u09a4\\u09be \\u0993 \\u099f\\u09c7\\u0995\\u09b8\\u0987 \\u09aa\\u09b0\\u09cd\\u09af\\u099f\\u09a8\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-02-vision-md-message.jpg\"},{\"title\":\"\\u09a8\\u09bf\\u09b0\\u09cd\\u09ae\\u09be\\u09a3\\u09be\\u09a7\\u09c0\\u09a8 \\u09ab\\u09be\\u0987\\u09ad \\u09b8\\u09cd\\u099f\\u09be\\u09b0 \\u09b9\\u09cb\\u099f\\u09c7\\u09b2\",\"subtitle\":\"Luxury Suites, Deluxe Rooms, Dining & Wellness\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-03-5-star-hotel.jpg\"},{\"title\":\"\\u09ac\\u09bf\\u09b6\\u09cd\\u09ac\\u09ae\\u09be\\u09a8\\u09c7\\u09b0 \\u09b8\\u09c1\\u09ac\\u09bf\\u09a7\\u09be\\u09b8\\u09ae\\u09c2\\u09b9\",\"subtitle\":\"Recreation, Corporate Facilities, Lifestyle & Kids Zone\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-04-world-class-amenities.jpg\"},{\"title\":\"\\u09ae\\u09c7\\u0997\\u09be \\u0986\\u0995\\u09b0\\u09cd\\u09b7\\u09a3 (\\u0985\\u0982\\u09b6 \\u09e7)\",\"subtitle\":\"\\u09a5\\u09cd\\u09b0\\u09bf-\\u09b8\\u09cd\\u099f\\u09be\\u09b0, \\u09b8\\u09cd\\u099f\\u09c1\\u09a1\\u09bf\\u0993 \\u09b9\\u09cb\\u099f\\u09c7\\u09b2, \\u09ac\\u09c7\\u09ac\\u09bf \\u098f\\u09a8\\u09cd\\u099f\\u09be\\u09b0\\u099f\\u09c7\\u0987\\u09a8\\u09ae\\u09c7\\u09a8\\u09cd\\u099f \\u0993 \\u09ac\\u09be\\u09b0\\u09ac\\u09bf\\u0995\\u09bf\\u0989 \\u09ad\\u09bf\\u09b2\\u09c7\\u099c\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-05-mega-attractions-part-1.jpg\"},{\"title\":\"\\u09ae\\u09c7\\u0997\\u09be \\u0986\\u0995\\u09b0\\u09cd\\u09b7\\u09a3 (\\u0985\\u0982\\u09b6 \\u09e8)\",\"subtitle\":\"\\u09ae\\u09c7\\u0997\\u09be \\u09b6\\u09aa\\u09bf\\u0982 \\u09ae\\u09b2 \\u0993 \\u09b8\\u09bf\\u09a8\\u09c7\\u09aa\\u09cd\\u09b2\\u09c7\\u0995\\u09cd\\u09b8, \\u09b8\\u09bf \\u0993\\u09af\\u09bc\\u09be\\u09b0\\u09cd\\u09b2\\u09cd\\u09a1 \\u0993 \\u0993\\u09af\\u09bc\\u09be\\u099f\\u09be\\u09b0 \\u09aa\\u09be\\u09b0\\u09cd\\u0995\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-06-mega-attractions-part-2.jpg\"},{\"title\":\"\\u09ae\\u09c7\\u0997\\u09be \\u0986\\u0995\\u09b0\\u09cd\\u09b7\\u09a3 (\\u0985\\u0982\\u09b6 \\u09e9)\",\"subtitle\":\"\\u09ab\\u09cd\\u09af\\u09be\\u09ae\\u09bf\\u09b2\\u09bf \\u09aa\\u09be\\u09b0\\u09cd\\u0995, \\u09b8\\u09c1\\u0987\\u09ae\\u09bf\\u0982 \\u099c\\u09cb\\u09a8, \\u0987\\u09a8\\u09a1\\u09cb\\u09b0 \\u09b8\\u09cd\\u099f\\u09c7\\u09a1\\u09bf\\u09af\\u09bc\\u09be\\u09ae \\u0993 \\u0995\\u09c7\\u09a8\\u09cd\\u09a6\\u09cd\\u09b0\\u09c0\\u09af\\u09bc \\u09ae\\u09b8\\u099c\\u09bf\\u09a6\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-07-mega-attractions-part-3.jpg\"},{\"title\":\"\\u09b6\\u09b0\\u09cd\\u09a4\\u09be\\u09ac\\u09b2\\u09bf, \\u09b6\\u09c7\\u09af\\u09bc\\u09be\\u09b0 \\u0993 \\u09ac\\u09c1\\u0995\\u09bf\\u0982\",\"subtitle\":\"\\u09b6\\u09c7\\u09af\\u09bc\\u09be\\u09b0\\u09b9\\u09cb\\u09b2\\u09cd\\u09a1\\u09be\\u09b0 \\u09b8\\u09c1\\u09ac\\u09bf\\u09a7\\u09be, \\u09b8\\u09be\\u09ac-\\u0995\\u09ac\\u09b2\\u09be \\u09b0\\u09c7\\u099c\\u09bf\\u09b8\\u09cd\\u099f\\u09cd\\u09b0\\u09c7\\u09b6\\u09a8 \\u0993 \\u0995\\u09b0\\u09cd\\u09aa\\u09cb\\u09b0\\u09c7\\u099f \\u0985\\u09ab\\u09bf\\u09b8\",\"image_path\":\"uploads\\/prospectus\\/prospectus-page-08-terms-and-booking.jpg\"}]','2026-09-26 11:23:22','2026-09-26 12:05:15');
/*!40000 ALTER TABLE `prospectus_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shareholder_review_sections`
--

DROP TABLE IF EXISTS `shareholder_review_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shareholder_review_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section_title` varchar(255) DEFAULT NULL,
  `section_subtitle` varchar(255) DEFAULT NULL,
  `reviews` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`reviews`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shareholder_review_sections`
--

LOCK TABLES `shareholder_review_sections` WRITE;
/*!40000 ALTER TABLE `shareholder_review_sections` DISABLE KEYS */;
INSERT INTO `shareholder_review_sections` VALUES (1,'Shareholder Reviews','Real stories from King Lotus Group shareholders.','[{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/2yNBzBVRqFg?si=VjzwNLUILO3KbKg8\",\"thumbnail_path\":\"uploads\\/reviews\\/ad6d65d0-dcef-40ec-9f0b-aaeb9b3ba9ec.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/It3qa-dA04s?si=6iyJcC7WTYGDhnTV\",\"thumbnail_path\":\"uploads\\/reviews\\/f94046c9-5034-42d1-9322-6d641c7838a3.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/Fd05_jdMdFg?si=gd7pzmZQ2_c_y-00\",\"thumbnail_path\":\"uploads\\/reviews\\/88608bf0-94d9-418b-9d24-e590a8da4daf.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/DGrpXPYQrqU?si=YFsvrUtqIGSMNkSH\",\"thumbnail_path\":\"uploads\\/reviews\\/616dc181-69f7-4802-96da-22330348c914.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/9IyPQtzFWew?si=jzOEhEKyqPYVi-Ue\",\"thumbnail_path\":\"uploads\\/reviews\\/b8f553d3-7133-4a9f-855f-b4e90cf25009.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/FprxZZHsxtE?si=F5A7NJfWnT-rA6US\",\"thumbnail_path\":\"uploads\\/reviews\\/d03a6739-a147-446c-9f1d-e70a124628f8.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/ht6O4LP9HFs?si=Dgdr4il8kefdFp4b\",\"thumbnail_path\":\"uploads\\/reviews\\/7a8487e1-ae5e-4e69-ac8f-97e4c154031c.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/V9jijYq8sdg?si=xgsiMuP1Xpv58w0o\",\"thumbnail_path\":\"uploads\\/reviews\\/a5377e29-42c0-4c2f-8d41-30aa8152283a.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/Gq3NLNelGo0?si=GZvF1iez8vSao_G8\",\"thumbnail_path\":\"uploads\\/reviews\\/23ed1b84-b640-4ffd-994d-f66cc5a2bd1e.webp\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/_LmxMWRmAic?si=DcN4locXDL2hXX5j\",\"thumbnail_path\":\"\"},{\"name\":\"\",\"video_url\":\"https:\\/\\/youtu.be\\/rmT_Oaak3Aw?si=6LSLVu0pzo9MholF\",\"thumbnail_path\":\"\"}]','2026-07-01 12:33:12','2026-07-02 14:09:33');
/*!40000 ALTER TABLE `shareholder_review_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_notices`
--

DROP TABLE IF EXISTS `site_notices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_notices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `hero_background_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_notices`
--

LOCK TABLES `site_notices` WRITE;
/*!40000 ALTER TABLE `site_notices` DISABLE KEYS */;
INSERT INTO `site_notices` VALUES (1,NULL,'📢 বিশেষ ডিসকাউন্ট অফার !! আমাদের সম্মানিত বিনিয়োগকারীদের জন্য এই মুহূর্তে আকর্ষণীয় ডিসকাউন্ট অফার চলছে । ক্রমান্বয়ে শেয়ারের মূল্য ও পরিমাণ দেওয়া হলো : ৪,৪৫,০০০ টাকা মূল্যে — ২,০০০ শেয়ার | ৫,৬৫,০০০ টাকা মূল্যে — ২,০০০ শেয়ার | ৭,৪৫,০০০ টাকা মূল্যে — ৫,০০০ শেয়ার | ৯,২৫,০০০ টাকা মূল্যে — শেষ ১০,০০০ শেয়ার | অফারটি সীমিত সময়ের জন্য ! আজই আপনার কাঙ্ক্ষিত শেয়ারটি নিশ্চিত করুন ।',1,'uploads/hero/ea46b741-58db-4081-861a-606d0901deb3.webp','2026-06-26 12:57:22','2026-09-26 15:39:09');
/*!40000 ALTER TABLE `site_notices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `valued_shareholder_sections`
--

DROP TABLE IF EXISTS `valued_shareholder_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `valued_shareholder_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section_title` varchar(180) DEFAULT NULL,
  `shareholders` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`shareholders`)),
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `valued_shareholder_sections`
--

LOCK TABLES `valued_shareholder_sections` WRITE;
/*!40000 ALTER TABLE `valued_shareholder_sections` DISABLE KEYS */;
INSERT INTO `valued_shareholder_sections` VALUES (1,'Our Valued Shareholders','[]',1,'2026-07-03 12:21:54','2026-09-26 14:30:29');
/*!40000 ALTER TABLE `valued_shareholder_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `valued_shareholders`
--

DROP TABLE IF EXISTS `valued_shareholders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `valued_shareholders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(180) NOT NULL,
  `position` varchar(180) DEFAULT NULL,
  `image_path` varchar(2048) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `valued_shareholders_name_index` (`name`),
  KEY `valued_shareholders_sort_order_index` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `valued_shareholders`
--

LOCK TABLES `valued_shareholders` WRITE;
/*!40000 ALTER TABLE `valued_shareholders` DISABLE KEYS */;
INSERT INTO `valued_shareholders` VALUES (1,'A.S.M. Zobayer Tanveer','Software Engineer','uploads/valued-shareholders/946c3259-01b4-453f-82a5-3b3dca2ac287.webp',0,'2026-09-26 14:34:28','2026-09-26 14:35:41'),(3,'Meheron Nesa Surovi','Software Engineer','uploads/valued-shareholders/00b6f588-79da-4e58-af47-d37790f11a3d.webp',0,'2026-09-27 02:17:47','2026-09-27 02:17:47'),(4,'Salauddin Majumder','Ai Engineer','uploads/valued-shareholders/9e60a242-87cd-4e48-8762-2ecf5105c9db.webp',0,'2026-09-27 02:21:49','2026-09-27 02:21:49');
/*!40000 ALTER TABLE `valued_shareholders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `why_sections`
--

DROP TABLE IF EXISTS `why_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `why_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `feature_points` text DEFAULT NULL,
  `cta_label` varchar(120) DEFAULT NULL,
  `cta_url` varchar(2048) DEFAULT NULL,
  `video_url` varchar(2048) DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `why_sections`
--

LOCK TABLES `why_sections` WRITE;
/*!40000 ALTER TABLE `why_sections` DISABLE KEYS */;
INSERT INTO `why_sections` VALUES (1,'Why King Lotus Group','King Lotus International is an upcoming mega hospitality project designed with a halal, safe and family-friendly concept. Spread across 50 acres, the project will feature a modern five-star hotel, luxury rooms, premium facilities and a secure environment for comfortable family vacations.','Prime Tourism Location\r\nModern Luxury Living\r\nSecure Investment Opportunity\r\nFamily-Friendly Facilities\r\nWorld-Class Hospitality','Investment Opportunity Details',NULL,'https://youtu.be/Z6e_jTDr_YM?si=m9YLs5vgXsrQapwF','uploads/why/5c901a55-acc8-4adb-80fb-85c9064836f0.webp','2026-06-27 05:18:51','2026-06-27 09:51:00');
/*!40000 ALTER TABLE `why_sections` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-27 17:27:21
