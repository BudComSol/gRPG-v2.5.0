-- Migration: Add Gang Bar plugin tables and columns
-- Run this on existing installations that already have the base schema installed.

-- Table to track each gang's accumulated points during the current hourly contest
CREATE TABLE IF NOT EXISTS `gangattacks`
(
    `id`   int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `gang` int(11) NOT NULL DEFAULT 0,
    `no`   int(11) NOT NULL DEFAULT 0,
    UNIQUE KEY `gang` (`gang`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Table to record the hourly Gang Bar contest winners (Gang of the Hour)
CREATE TABLE IF NOT EXISTS `goth`
(
    `id`    int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `gang`  int(11)   NOT NULL DEFAULT 0,
    `kills` int(11)   NOT NULL DEFAULT 0,
    `time`  timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Add barpoints column to users table to track each user's contribution to the bar contest
ALTER TABLE `users`
    ADD COLUMN IF NOT EXISTS `barpoints` int(11) NOT NULL DEFAULT 0;
