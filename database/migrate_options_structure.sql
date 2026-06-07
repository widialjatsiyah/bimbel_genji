-- Skrip migrasi untuk menggabungkan field option dan option_image menjadi satu field
-- Menambahkan field baru untuk menyimpan konten opsi (teks atau gambar)
ALTER TABLE questions 
ADD COLUMN option_a_content TEXT DEFAULT NULL,
ADD COLUMN option_b_content TEXT DEFAULT NULL,
ADD COLUMN option_c_content TEXT DEFAULT NULL,
ADD COLUMN option_d_content TEXT DEFAULT NULL,
ADD COLUMN option_e_content TEXT DEFAULT NULL;

-- Memindahkan data dari field lama ke field baru
UPDATE questions 
SET 
    option_a_content = CASE 
        WHEN option_a IS NOT NULL AND option_a != '' THEN option_a
        WHEN option_a_image IS NOT NULL AND option_a_image != '' THEN option_a_image
        ELSE NULL
    END,
    option_b_content = CASE 
        WHEN option_b IS NOT NULL AND option_b != '' THEN option_b
        WHEN option_b_image IS NOT NULL AND option_b_image != '' THEN option_b_image
        ELSE NULL
    END,
    option_c_content = CASE 
        WHEN option_c IS NOT NULL AND option_c != '' THEN option_c
        WHEN option_c_image IS NOT NULL AND option_c_image != '' THEN option_c_image
        ELSE NULL
    END,
    option_d_content = CASE 
        WHEN option_d IS NOT NULL AND option_d != '' THEN option_d
        WHEN option_d_image IS NOT NULL AND option_d_image != '' THEN option_d_image
        ELSE NULL
    END,
    option_e_content = CASE 
        WHEN option_e IS NOT NULL AND option_e != '' THEN option_e
        WHEN option_e_image IS NOT NULL AND option_e_image != '' THEN option_e_image
        ELSE NULL
    END;

-- Menghapus field lama
ALTER TABLE questions 
DROP COLUMN option_a_image,
DROP COLUMN option_b_image,
DROP COLUMN option_c_image,
DROP COLUMN option_d_image,
DROP COLUMN option_e_image;

-- Mengganti nama field option_x ke option_x_content (jika sebelumnya hanya teks)
-- (Karena field option_a-e sudah ada, kita biarkan nama option_a_content sebagai pengganti kombinasi)