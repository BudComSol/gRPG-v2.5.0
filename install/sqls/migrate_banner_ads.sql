-- Migration: Add banner_ads table and banner_ads_enabled setting
-- Run this migration if upgrading from a version before rotating banner ads were added.

CREATE TABLE IF NOT EXISTS `banner_ads`
(
    `id`              int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `ad_code`         text      NOT NULL,
    `display_seconds` int(11)   NOT NULL DEFAULT 5,
    `sort_order`      int(11)   NOT NULL DEFAULT 0,
    `created_at`      timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

INSERT IGNORE INTO `settings` (`conf_name`, `conf_value`) VALUES ('banner_ads_enabled', 'off');
