-- Tambahkan kolom untuk menyimpan ID sesi tryout
ALTER TABLE `user_tryouts`
ADD COLUMN `tryout_session_id` INT(11) NULL COMMENT 'ID sesi tryout, NULL jika masih menggunakan versi lama' AFTER `tryout_id`;

-- Tambahkan indeks untuk kolom tryout_session_id
CREATE INDEX idx_user_tryouts_session_id ON user_tryouts(tryout_session_id);

-- Update data yang sudah ada agar sesuai dengan constraint foreign key
-- Kita perlu mendapatkan tryout_id dari session yang sesuai
UPDATE user_tryouts ut
JOIN tryout_sessions ts ON ts.id = ut.tryout_session_id
SET ut.tryout_id = ts.tryout_id
WHERE ut.tryout_session_id IS NOT NULL AND ut.tryout_id IS NULL;

-- Buat trigger untuk secara otomatis mengisi tryout_id saat membuat entri baru dengan tryout_session_id
DELIMITER $$
CREATE TRIGGER before_insert_user_tryouts
BEFORE INSERT ON user_tryouts
FOR EACH ROW
BEGIN
    IF NEW.tryout_session_id IS NOT NULL AND NEW.tryout_id IS NULL THEN
        SET NEW.tryout_id = (SELECT tryout_id FROM tryout_sessions WHERE id = NEW.tryout_session_id);
    END IF;
END$$
DELIMITER ;