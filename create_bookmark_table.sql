-- Membuat tabel untuk menyimpan bookmark soal
CREATE TABLE `bookmarks` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `question_id` INT(11) NOT NULL,
  `user_tryout_id` INT(11) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_bookmark` (`user_id`, `question_id`, `user_tryout_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_question_id` (`question_id`),
  KEY `idx_user_tryout_id` (`user_tryout_id`),
  CONSTRAINT `fk_bookmarks_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bookmarks_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bookmarks_user_tryout` FOREIGN KEY (`user_tryout_id`) REFERENCES `user_tryouts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Tabel untuk menyimpan bookmark soal oleh pengguna';

-- Tambahkan indeks untuk performansi pencarian
CREATE INDEX idx_bookmarks_user_tryout_question ON bookmarks(user_id, user_tryout_id, question_id);