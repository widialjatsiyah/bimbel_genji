-- Tambahkan field untuk pengaturan waktu pengerjaan per soal
ALTER TABLE `tryout_sessions` 
ADD COLUMN `enable_time_per_question` TINYINT(1) DEFAULT 0 COMMENT 'Aktifkan batas waktu per soal',
ADD COLUMN `time_per_question` INT DEFAULT 0 COMMENT 'Waktu pengerjaan per soal dalam detik, 0 untuk nonaktif';