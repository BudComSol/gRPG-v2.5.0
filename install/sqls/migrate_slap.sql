-- Migration: Add slap columns to users table
-- Run this on existing installations that already have the base schema installed.

ALTER TABLE `users`
    ADD COLUMN IF NOT EXISTS `slapping` int(11) NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS `slapped`  int(11) NOT NULL DEFAULT 0;
