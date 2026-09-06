/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `ae_kabupaten` (
  `kabupaten_id` int NOT NULL,
  `id_provinsi` int NOT NULL,
  `nama_kabupaten` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `kode` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `ae_kecamatan` (
  `id_kecamatan` int NOT NULL,
  `kabupaten_id` int NOT NULL,
  `kecamatan` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `kode` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ongkir_reg` int NOT NULL,
  `ongkir_oke` int NOT NULL,
  `ongkir_yes` int NOT NULL,
  `rpx` int NOT NULL,
  `jnt` int NOT NULL,
  `est_reg` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `est_oke` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `ae_provinsi` (
  `id_provinsi` int NOT NULL,
  `nama_provinsi` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `bookmarks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `question_id` int NOT NULL,
  `user_tryout_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_bookmark` (`user_id`,`question_id`,`user_tryout_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_question_id` (`question_id`),
  KEY `idx_user_tryout_id` (`user_tryout_id`),
  KEY `idx_bookmarks_user_tryout_question` (`user_id`,`user_tryout_id`,`question_id`),
  CONSTRAINT `fk_bookmarks_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bookmarks_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bookmarks_user_tryout` FOREIGN KEY (`user_tryout_id`) REFERENCES `user_tryouts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Tabel untuk menyimpan bookmark soal oleh pengguna';

CREATE TABLE IF NOT EXISTS `certificates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `tryout_id` int DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `certificate_number` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `file_url` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `issued_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certificate_number` (`certificate_number`),
  KEY `user_id` (`user_id`),
  KEY `tryout_id` (`tryout_id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `certificates_ibfk_2` FOREIGN KEY (`tryout_id`) REFERENCES `tryouts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `certificates_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `chapters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `order_num` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `chapters_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `classes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `school_id` int NOT NULL,
  `teacher_id` int DEFAULT NULL,
  `academic_year` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `grade_level` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `school_id` (`school_id`),
  KEY `teacher_id` (`teacher_id`),
  CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `class_meetings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `class_id` int NOT NULL,
  `title` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `meeting_link` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `order_num` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `class_meetings_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `daily_checklist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `date` date NOT NULL,
  `checklist_data` json DEFAULT NULL,
  `mood_rating` int DEFAULT NULL,
  `notes` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`date`),
  CONSTRAINT `daily_checklist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `document` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `ref` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ref_id` tinyint DEFAULT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `file_raw_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `file_raw_name_thumb` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `file_name_thumb` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `file_size` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `file_type` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `file_ext` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `is_verified` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `essay_answers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_tryout_id` int NOT NULL,
  `question_id` int NOT NULL,
  `answer_text` text NOT NULL,
  `score` decimal(5,2) DEFAULT '0.00',
  `evaluated_by` int DEFAULT NULL,
  `evaluated_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_unsure` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `idx_user_tryout_question` (`user_tryout_id`,`question_id`),
  CONSTRAINT `essay_answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `example` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `input_text` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `input_number` tinyint NOT NULL,
  `input_money` decimal(12,0) DEFAULT NULL,
  `input_date` date NOT NULL,
  `input_textarea` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_by` tinyint DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `input_combobox` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `question` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `order_num` int DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `features` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `icon` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `order_num` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `galleries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `image` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `category` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `order_num` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `layanan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_layanan` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb3_unicode_ci,
  `file_imgae` text COLLATE utf8mb3_unicode_ci,
  `created_by` int DEFAULT NULL,
  `created_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_active` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `materials` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` enum('video','pdf','modul') COLLATE utf8mb3_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `subject_id` int DEFAULT NULL,
  `chapter_id` int DEFAULT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `duration_seconds` int DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`),
  KEY `chapter_id` (`chapter_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `materials_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `materials_ibfk_2` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `materials_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `meeting_materials` (
  `id` int NOT NULL AUTO_INCREMENT,
  `meeting_id` int NOT NULL,
  `material_id` int NOT NULL,
  `order_num` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `meeting_id` (`meeting_id`,`material_id`),
  KEY `material_id` (`material_id`),
  CONSTRAINT `meeting_materials_ibfk_1` FOREIGN KEY (`meeting_id`) REFERENCES `class_meetings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `meeting_materials_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `meeting_quizzes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `meeting_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `order_num` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `meeting_id` (`meeting_id`,`quiz_id`),
  KEY `quiz_id` (`quiz_id`),
  CONSTRAINT `meeting_quizzes_ibfk_1` FOREIGN KEY (`meeting_id`) REFERENCES `class_meetings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `meeting_quizzes_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `tryouts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `menu` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT NULL,
  `name` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `order_pos` int DEFAULT NULL,
  `link` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `link_tobase` varchar(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `icon` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `is_newtab` varchar(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `role_pic` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` tinyint DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `notification` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_from` tinyint NOT NULL,
  `user_to` tinyint NOT NULL,
  `ref` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ref_id` tinyint DEFAULT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `link` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `is_read` varchar(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `packages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `price` decimal(10,2) DEFAULT NULL,
  `duration_days` int DEFAULT NULL,
  `features` json DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `layanan_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `package_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `package_id` int NOT NULL,
  `item_type` enum('tryout','class','material','quiz') COLLATE utf8mb3_unicode_ci NOT NULL,
  `item_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `package_id` (`package_id`,`item_type`,`item_id`),
  CONSTRAINT `package_items_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `parent_student` (
  `parent_id` int NOT NULL,
  `student_id` int NOT NULL,
  `relationship` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`parent_id`,`student_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `parent_student_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `parent_student_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject_id` int NOT NULL,
  `chapter_id` int DEFAULT NULL,
  `topic_id` int DEFAULT NULL,
  `group_id` int DEFAULT NULL COMMENT 'ID grup soal yang saling terkait, NULL jika soal tunggal',
  `group_order` int DEFAULT '1' COMMENT 'Urutan soal dalam grup, 1 untuk soal pertama dalam grup',
  `is_group_main` tinyint(1) DEFAULT '0' COMMENT 'Apakah soal ini adalah soal utama dalam grup (memiliki narasi)',
  `difficulty` enum('mudah','sedang','sulit') COLLATE utf8mb3_unicode_ci DEFAULT 'sedang',
  `curriculum` enum('Kurikulum Merdeka','K13','Lainnya') COLLATE utf8mb3_unicode_ci DEFAULT 'Kurikulum Merdeka',
  `question_text` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `option_a` text COLLATE utf8mb3_unicode_ci,
  `option_b` text COLLATE utf8mb3_unicode_ci,
  `option_c` text COLLATE utf8mb3_unicode_ci,
  `option_d` text COLLATE utf8mb3_unicode_ci,
  `option_e` text COLLATE utf8mb3_unicode_ci,
  `correct_option` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `explanation` text COLLATE utf8mb3_unicode_ci,
  `video_explanation_url` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `question_image` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL COMMENT 'Path untuk gambar soal',
  `question_type` enum('multiple_choice','essay') COLLATE utf8mb3_unicode_ci DEFAULT 'multiple_choice' COMMENT 'Jenis soal: pilihan ganda atau esai',
  `expected_keywords` text COLLATE utf8mb3_unicode_ci COMMENT 'Kata kunci yang diharapkan dalam jawaban esai',
  `min_keyword_matches` int DEFAULT '1' COMMENT 'Jumlah minimum kata kunci yang harus cocok untuk jawaban esai',
  `option_type` enum('text','image') COLLATE utf8mb3_unicode_ci DEFAULT 'text',
  `updated_by` int DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `created_by` (`created_by`),
  KEY `subject_id` (`subject_id`,`difficulty`),
  KEY `chapter_id` (`chapter_id`),
  KEY `idx_questions_group_id` (`group_id`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `questions_ibfk_3` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE SET NULL,
  CONSTRAINT `questions_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `recommendations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `recommendation_text` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` enum('remedial','latihan','motivasi','spiritual') COLLATE utf8mb3_unicode_ci DEFAULT 'latihan',
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`is_read`),
  CONSTRAINT `recommendations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `role` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` tinyint DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `schools` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb3_unicode_ci,
  `contact_email` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `logo_url` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `school_partnerships` (
  `id` int NOT NULL AUTO_INCREMENT,
  `school_name` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `contact_person` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb3_unicode_ci,
  `status` enum('pending','contacted','approved','rejected') COLLATE utf8mb3_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `session_results` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_tryout_id` int NOT NULL,
  `tryout_session_id` int NOT NULL,
  `correct_count` int DEFAULT '0',
  `wrong_count` int DEFAULT '0',
  `skipped_count` int DEFAULT '0',
  `score` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_tryout_id` (`user_tryout_id`,`tryout_session_id`),
  KEY `tryout_session_id` (`tryout_session_id`),
  CONSTRAINT `session_results_ibfk_1` FOREIGN KEY (`user_tryout_id`) REFERENCES `user_tryouts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `session_results_ibfk_2` FOREIGN KEY (`tryout_session_id`) REFERENCES `tryout_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `setting` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `data` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb3_unicode_ci,
  `type` enum('text','textarea','image','file') COLLATE utf8mb3_unicode_ci DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `slides` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `subtitle` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `button_text` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `button_link` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `order_num` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `student_class` (
  `student_id` int NOT NULL,
  `class_id` int NOT NULL,
  `joined_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`student_id`,`class_id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `student_class_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `student_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `nis` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `asal_sekolah` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pilihan_kampus1` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pilihan_kampus2` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `nama_orang_tua` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `kontak_orang_tua` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `student_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `student_progress` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `snapshot_date` date NOT NULL,
  `skor_akademik` decimal(5,2) DEFAULT NULL,
  `skor_konsistensi` decimal(5,2) DEFAULT NULL,
  `skor_psikologis` decimal(5,2) DEFAULT NULL,
  `skor_spiritual` decimal(5,2) DEFAULT NULL,
  `skor_kesiapan` decimal(5,2) DEFAULT NULL,
  `status_kesiapan` enum('Siap','Perlu Penguatan','Perlu Pendampingan Intensif') COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `catatan_tutor` text COLLATE utf8mb3_unicode_ci,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`snapshot_date`),
  CONSTRAINT `student_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `sub_unit` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `unit_id` tinyint NOT NULL,
  `nama_sub_unit` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `surat_nomor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `unit` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `sub_unit` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `format_nomor` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ref` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `teacher_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `nip` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `bidang_keahlian` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pendidikan_terakhir` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `teacher_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `teams` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `position` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb3_unicode_ci,
  `photo` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `social_facebook` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `social_twitter` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `social_instagram` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `social_linkedin` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `order_num` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `content` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `rating` int DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` int DEFAULT '0',
  `order_num` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `testimonials_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `topics` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chapter_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `order_num` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chapter_id` (`chapter_id`),
  CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` varchar(100) NOT NULL,
  `user_id` int NOT NULL,
  `package_id` int DEFAULT NULL,
  `gross_amount` decimal(10,2) NOT NULL,
  `payment_type` varchar(50) DEFAULT NULL,
  `transaction_status` enum('pending','settlement','capture','deny','cancel','expire') DEFAULT 'pending',
  `snap_token` text,
  `midtrans_response` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `package_id` (`package_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `tryouts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `type` enum('UTBK','TKA','Jurusan','Sekolah','Lainnya') COLLATE utf8mb3_unicode_ci DEFAULT 'UTBK',
  `mode` enum('resmi','latihan','evaluasi') COLLATE utf8mb3_unicode_ci DEFAULT 'resmi',
  `total_duration` int DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT '0',
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `tryouts_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `tryout_class` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tryout_id` int NOT NULL,
  `class_id` int NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tryout_id` (`tryout_id`,`class_id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `tryout_class_ibfk_1` FOREIGN KEY (`tryout_id`) REFERENCES `tryouts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tryout_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `tryout_questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tryout_session_id` int NOT NULL,
  `question_id` int NOT NULL,
  `question_order` int NOT NULL,
  `points` decimal(5,2) DEFAULT '1.00' COMMENT 'Jumlah poin untuk soal ini dalam sesi tryout',
  `time_limit` int DEFAULT '0' COMMENT 'Batas waktu pengerjaan soal dalam detik, 0 untuk unlimited',
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `tryout_questions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `tryout_sessions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tryout_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `session_order` int NOT NULL,
  `duration_minutes` int NOT NULL,
  `question_count` int NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `is_random` tinyint(1) DEFAULT '0' COMMENT 'Apakah soal diacak (0=tidak, 1=ya)',
  `scoring_method` enum('correct_incorrect','points_per_question') COLLATE utf8mb3_unicode_ci DEFAULT 'correct_incorrect' COMMENT 'Metode perhitungan skor (benar/salah atau poin per soal)',
  `enable_time_per_question` tinyint(1) DEFAULT '0' COMMENT 'Aktifkan batas waktu per soal',
  `time_per_question` int DEFAULT '0' COMMENT 'Waktu pengerjaan per soal dalam detik, 0 untuk nonaktif',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tryout_id` (`tryout_id`,`session_order`),
  CONSTRAINT `tryout_sessions_ibfk_1` FOREIGN KEY (`tryout_id`) REFERENCES `tryouts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `unit` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nama_unit` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `universitas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb3_unicode_ci,
  `nilai` float(11,2) DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `username` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `role` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `unit` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `sub_unit` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `profile_photo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_by` tinyint DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `is_active` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_answers` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_tryout_id` int NOT NULL,
  `question_id` int NOT NULL,
  `answer` char(1) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `time_spent_seconds` int DEFAULT NULL,
  `is_unsure` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_essay_answer` tinyint(1) DEFAULT '0' COMMENT 'Menandai apakah ini jawaban esai yang disimpan di tabel essay_answers',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_tryout_id` (`user_tryout_id`,`question_id`),
  KEY `question_id` (`question_id`),
  KEY `user_tryout_id_2` (`user_tryout_id`),
  CONSTRAINT `user_answers_ibfk_1` FOREIGN KEY (`user_tryout_id`) REFERENCES `user_tryouts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_material_progress` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `material_id` int NOT NULL,
  `status` enum('not_started','in_progress','completed') COLLATE utf8mb3_unicode_ci DEFAULT 'not_started',
  `progress_percent` int DEFAULT '0',
  `last_accessed` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`material_id`),
  KEY `material_id` (`material_id`),
  CONSTRAINT `user_material_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_material_progress_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_packages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `package_id` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','expired','cancelled') COLLATE utf8mb3_unicode_ci DEFAULT 'active',
  `payment_status` enum('pending','paid','failed') COLLATE utf8mb3_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `package_id` (`package_id`),
  KEY `user_id` (`user_id`,`status`),
  CONSTRAINT `user_packages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_packages_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_tryouts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `tryout_id` int NOT NULL,
  `tryout_session_id` int DEFAULT NULL COMMENT 'ID sesi tryout, NULL jika masih menggunakan versi lama',
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `status` enum('in_progress','completed','abandoned') COLLATE utf8mb3_unicode_ci DEFAULT 'in_progress',
  `total_score` decimal(5,2) DEFAULT NULL,
  `ranking_national` int DEFAULT NULL,
  `ranking_school` int DEFAULT NULL,
  `ranking_bimbel` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`status`),
  KEY `tryout_id` (`tryout_id`,`total_score`),
  KEY `idx_user_tryouts_session_id` (`tryout_session_id`),
  CONSTRAINT `user_tryouts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_tryouts_ibfk_2` FOREIGN KEY (`tryout_id`) REFERENCES `tryouts` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `view_menu` (
	`id` BIGINT(19) NOT NULL,
	`parent_id` INT(10) NULL,
	`name` VARCHAR(150) NULL COLLATE 'utf8mb3_unicode_ci',
	`parent_name` VARCHAR(150) NULL COLLATE 'utf8mb3_unicode_ci',
	`link` TEXT NOT NULL COLLATE 'utf8mb3_unicode_ci',
	`icon` VARCHAR(30) NULL COLLATE 'utf8mb3_unicode_ci',
	`link_tobase` VARCHAR(1) NULL COLLATE 'utf8mb3_unicode_ci',
	`is_newtab` VARCHAR(1) NULL COLLATE 'utf8mb3_unicode_ci',
	`role_pic` TEXT NULL COLLATE 'utf8mb3_unicode_ci',
	`order_pos` INT(10) NULL,
	`created_at` TIMESTAMP NULL,
	`created_by` TINYINT(3) NULL,
	`updated_at` TIMESTAMP NULL,
	`updated_by` TINYINT(3) NULL
) ENGINE=MyISAM;

CREATE TABLE `view_notification` (
	`id` BIGINT(19) NOT NULL,
	`user_from` TINYINT(3) NOT NULL,
	`user_from_nama_lengkap` VARCHAR(255) NULL COLLATE 'utf8mb3_unicode_ci',
	`user_from_role` VARCHAR(255) NULL COLLATE 'utf8mb3_unicode_ci',
	`user_to` TINYINT(3) NOT NULL,
	`user_to_nama_lengkap` VARCHAR(255) NULL COLLATE 'utf8mb3_unicode_ci',
	`user_to_role` VARCHAR(255) NULL COLLATE 'utf8mb3_unicode_ci',
	`ref` VARCHAR(150) NULL COLLATE 'utf8mb3_unicode_ci',
	`ref_id` TINYINT(3) NULL,
	`description` TEXT NOT NULL COLLATE 'utf8mb3_unicode_ci',
	`link` TEXT NOT NULL COLLATE 'utf8mb3_unicode_ci',
	`is_read` VARCHAR(1) NULL COLLATE 'utf8mb3_unicode_ci',
	`created_date` TIMESTAMP NOT NULL
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `view_menu`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `view_menu` AS select distinct `m1`.`id` AS `id`,`m1`.`parent_id` AS `parent_id`,`m1`.`name` AS `name`,`m2`.`name` AS `parent_name`,`m1`.`link` AS `link`,`m1`.`icon` AS `icon`,`m1`.`link_tobase` AS `link_tobase`,`m1`.`is_newtab` AS `is_newtab`,`m1`.`role_pic` AS `role_pic`,`m1`.`order_pos` AS `order_pos`,`m1`.`created_at` AS `created_at`,`m1`.`created_by` AS `created_by`,`m1`.`updated_at` AS `updated_at`,`m1`.`updated_by` AS `updated_by` from (`menu` `m1` left join `menu` `m2` on((`m1`.`parent_id` = `m2`.`id`))) order by `m1`.`parent_id` desc,`m1`.`order_pos`;

DROP TABLE IF EXISTS `view_notification`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `view_notification` AS select `t`.`id` AS `id`,`t`.`user_from` AS `user_from`,`u1`.`nama_lengkap` AS `user_from_nama_lengkap`,`u1`.`role` AS `user_from_role`,`t`.`user_to` AS `user_to`,`u2`.`nama_lengkap` AS `user_to_nama_lengkap`,`u2`.`role` AS `user_to_role`,`t`.`ref` AS `ref`,`t`.`ref_id` AS `ref_id`,`t`.`description` AS `description`,`t`.`link` AS `link`,`t`.`is_read` AS `is_read`,`t`.`created_date` AS `created_date` from ((`notification` `t` left join `user` `u1` on((`u1`.`id` = `t`.`user_from`))) left join `user` `u2` on((`u2`.`id` = `t`.`user_to`))) order by `t`.`id` desc;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
