-- Tambahkan kolom untuk pengacakan soal dan metode perhitungan skor
ALTER TABLE `tryout_sessions`
ADD COLUMN `is_random` TINYINT(1) DEFAULT 0 COMMENT 'Apakah soal diacak (0=tidak, 1=ya)' AFTER `description`,
ADD COLUMN `scoring_method` ENUM('correct_incorrect', 'points_per_question') DEFAULT 'correct_incorrect' COMMENT 'Metode perhitungan skor (benar/salah atau poin per soal)' AFTER `is_random`;