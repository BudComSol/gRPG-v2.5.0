-- Migration: Add Theatre plugin (YouTube video collection)
-- Run this on existing installations that already have the base schema installed.

CREATE TABLE IF NOT EXISTS `theatre_videos`
(
    `id`         int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `user_id`    int(11)      NOT NULL,
    `title`      varchar(191) NOT NULL DEFAULT '',
    `youtube_id` varchar(20)  NOT NULL DEFAULT '',
    `added_at`   int(11)      NOT NULL DEFAULT 0,
    KEY (`user_id`),
    KEY (`added_at`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
