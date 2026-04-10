-- Tambahkan kolom untuk mengelompokkan soal
ALTER TABLE `questions`
ADD COLUMN `group_id` INT UNSIGNED DEFAULT NULL COMMENT 'ID grup soal yang saling terkait, NULL jika soal tunggal' AFTER `topic_id`;

-- Tambahkan indeks untuk kolom group_id
CREATE INDEX idx_questions_group_id ON questions(group_id);

-- Tambahkan kolom urutan dalam grup
ALTER TABLE `questions`
ADD COLUMN `group_order` INT UNSIGNED DEFAULT 1 COMMENT 'Urutan soal dalam grup, 1 untuk soal pertama dalam grup' AFTER `group_id`;

-- Tambahkan kolom untuk menandai soal utama dalam grup
ALTER TABLE `questions`
ADD COLUMN `is_group_main` TINYINT(1) DEFAULT 0 COMMENT 'Apakah soal ini adalah soal utama dalam grup (memiliki narasi)' AFTER `group_order`;