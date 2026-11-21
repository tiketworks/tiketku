-- Migration 001: role-based access
-- Run once, AFTER importing database/tiketku.sql.
-- Roles: admin (everything), organizer (only events of own organizer_id), user (default).

ALTER TABLE `users`
  ADD COLUMN `role` ENUM('admin','organizer','user') NOT NULL DEFAULT 'user' AFTER `password`,
  ADD COLUMN `organizer_id` INT(11) NULL DEFAULT NULL AFTER `role`,
  ADD KEY `idx_users_organizer` (`organizer_id`),
  ADD CONSTRAINT `fk_users_organizer` FOREIGN KEY (`organizer_id`) REFERENCES `organizers` (`id`) ON DELETE SET NULL;

-- The old code treated this email as admin; make it an explicit role instead.
UPDATE `users` SET `role` = 'admin' WHERE `email` = 'admin@tiketku.com';

-- Demo organizer accounts for testing (password: organizer123, stored as bcrypt hash).
-- organizer.pufa -> organizer_id 1 (PUFA), organizer.pumais -> organizer_id 2 (PUMA IS).
-- Run 002 afterwards so organizers 1 and 2 are actually named PUFA and PUMA IS.
INSERT INTO `users` (`name`, `email`, `phone`, `password`, `role`, `organizer_id`) VALUES
('Organizer PUFA', 'organizer.pufa@tiketku.com', '081200000001', '$2y$10$33jZGHlvHkxLfo7y2uxcq.om0eGNfs6xC1HOiW9VhEU10JhkN7/L2', 'organizer', 1),
('Organizer PUMA IS', 'organizer.pumais@tiketku.com',    '081200000002', '$2y$10$33jZGHlvHkxLfo7y2uxcq.om0eGNfs6xC1HOiW9VhEU10JhkN7/L2', 'organizer', 2);
