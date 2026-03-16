-- Migration: Add gamble_daily column to users table and create foolsgamble_log table
-- Run this on existing installations that already have the base schema installed.

ALTER TABLE `users`
    ADD COLUMN IF NOT EXISTS `gamble_daily` int(11) NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `foolsgamble_log`
(
    `id`        int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid`    int(11)      NOT NULL DEFAULT 0,
    `timestamp` int(11)      NOT NULL DEFAULT 0,
    `text`      varchar(191) NOT NULL DEFAULT '',
    KEY `idx_timestamp` (`timestamp`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
