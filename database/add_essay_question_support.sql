-- Menambahkan kolom untuk mendukung soal esai
ALTER TABLE `questions`
ADD COLUMN `question_type` ENUM('multiple_choice', 'essay') DEFAULT 'multiple_choice' COMMENT 'Jenis soal: pilihan ganda atau esai',
ADD COLUMN `expected_keywords` TEXT NULL COMMENT 'Kata kunci yang diharapkan dalam jawaban esai',
ADD COLUMN `min_keyword_matches` INT DEFAULT 1 COMMENT 'Jumlah minimum kata kunci yang harus cocok untuk jawaban esai';

-- Membuat tabel untuk menyimpan jawaban esai
CREATE TABLE `essay_answers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_tryout_id` INT NOT NULL,
  `question_id` INT NOT NULL,
  `answer_text` TEXT NOT NULL,
  `score` DECIMAL(5,2) DEFAULT 0,
  `evaluated_by` INT NULL,
  `evaluated_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`is_unsure` int(2) default 0,
  FOREIGN KEY (`question_id`) REFERENCES `questions`(`id`),
  INDEX `idx_user_tryout_question` (`user_tryout_id`, `question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Update tabel user_answers untuk menandai jawaban yang bukan pilihan ganda
ALTER TABLE `user_answers`
ADD COLUMN `is_essay_answer` BOOLEAN DEFAULT FALSE COMMENT 'Menandai apakah ini jawaban esai yang disimpan di tabel essay_answers';
