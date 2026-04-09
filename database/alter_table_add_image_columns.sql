-- Menambahkan kolom untuk menyimpan path gambar soal dan pilihan jawaban
ALTER TABLE `questions`
ADD COLUMN `question_image` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Path untuk gambar soal',
ADD COLUMN `option_a_image` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Path untuk gambar pilihan A',
ADD COLUMN `option_b_image` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Path untuk gambar pilihan B',
ADD COLUMN `option_c_image` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Path untuk gambar pilihan C',
ADD COLUMN `option_d_image` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Path untuk gambar pilihan D',
ADD COLUMN `option_e_image` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Path untuk gambar pilihan E';

-- Catatan: Kolom-kolom ini harus sesuai dengan implementasi di QuestionModel
-- Kolom akan menyimpan path relatif ke file gambar yang diupload