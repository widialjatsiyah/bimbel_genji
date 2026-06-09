-- Skrip migrasi untuk menyederhanakan field type_option menjadi satu field
-- Menambahkan field baru untuk menyimpan tipe semua opsi
ALTER TABLE questions 
ADD COLUMN option_type ENUM('text', 'image') DEFAULT 'text';

