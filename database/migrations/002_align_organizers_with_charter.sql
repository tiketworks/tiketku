-- Migration 002: align seed organizers with the Project Charter (only PUFA and PUMA IS).
-- Run once, after 001. Contact emails are placeholders; replace with the real ones.

UPDATE `organizers` SET `name` = 'PUFA',    `contact_email` = 'pufa@example.com'   WHERE `id` = 1;
UPDATE `organizers` SET `name` = 'PUMA IS', `contact_email` = 'pumais@example.com' WHERE `id` = 2;

-- The old third seed organizer (BEM FT) is out of scope: move its demo event, then remove it.
UPDATE `events`     SET `organizer_id` = 2 WHERE `organizer_id` = 3;
UPDATE `users`      SET `organizer_id` = NULL WHERE `organizer_id` = 3;
DELETE FROM `organizers` WHERE `id` = 3;
