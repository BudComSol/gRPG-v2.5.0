-- Migration: Add theatre video votes table (likes/dislikes)
-- Run this on existing installations that already have the theatre_videos table installed.

CREATE TABLE IF NOT EXISTS `theatre_video_votes`
(
    `id`         int(11)    NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `video_id`   int(11)    NOT NULL,
    `user_id`    int(11)    NOT NULL,
    `vote`       tinyint(1) NOT NULL COMMENT '1 = like, -1 = dislike',
    `created_at` int(11)    NOT NULL DEFAULT 0,
    UNIQUE KEY `uq_video_user` (`video_id`, `user_id`),
    KEY (`user_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
