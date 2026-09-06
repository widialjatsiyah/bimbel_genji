ALTER TABLE `transactions`
  ADD COLUMN `manual_proof` varchar(255) DEFAULT NULL AFTER `midtrans_response`,
  ADD COLUMN `manual_note` text DEFAULT NULL AFTER `manual_proof`,
  ADD COLUMN `manual_verification_status` enum('pending','approved','rejected') DEFAULT NULL AFTER `manual_note`,
  ADD COLUMN `manual_verified_by` int DEFAULT NULL AFTER `manual_verification_status`,
  ADD COLUMN `manual_verified_at` datetime DEFAULT NULL AFTER `manual_verified_by`,
  ADD KEY `manual_verified_by` (`manual_verified_by`),
  ADD CONSTRAINT `transactions_manual_verified_by_fk` FOREIGN KEY (`manual_verified_by`) REFERENCES `user` (`id`) ON DELETE SET NULL;
