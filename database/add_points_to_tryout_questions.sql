-- Tambahkan kolom untuk poin per soal dalam sesi tryout
ALTER TABLE `tryout_questions`
ADD COLUMN `points` DECIMAL(5,2) DEFAULT 1.00 COMMENT 'Jumlah poin untuk soal ini dalam sesi tryout' AFTER `question_order`;