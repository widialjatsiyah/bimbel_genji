-- Tambahkan indeks untuk mendukung pengelompokan soal
-- Indeks untuk mengoptimalkan pencarian berdasarkan group_id dan group_order
ALTER TABLE `questions` 
ADD INDEX `idx_questions_group_and_order` (`group_id`, `group_order`);

-- Indeks untuk mendukung join antara tryout_questions dan questions
ALTER TABLE `tryout_questions` 
ADD INDEX `idx_tryout_questions_session_question` (`tryout_session_id`, `question_id`);

-- Pastikan juga bahwa foreign key constraint sudah ada
ALTER TABLE `questions` 
ADD CONSTRAINT `fk_questions_group` 
FOREIGN KEY (`group_id`) REFERENCES `questions` (`id`) 
ON DELETE CASCADE 
ON UPDATE CASCADE;