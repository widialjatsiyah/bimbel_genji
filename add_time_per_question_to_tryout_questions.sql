-- Tambahkan field untuk waktu per soal di tabel tryout_questions
ALTER TABLE `tryout_questions` 
ADD COLUMN `time_limit` INT DEFAULT 0 COMMENT 'Batas waktu pengerjaan soal dalam detik, 0 untuk unlimited';