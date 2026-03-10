-- Migration: Add NPCs/Robots system
-- Run this on existing installations that already have the base schema installed.

CREATE TABLE IF NOT EXISTS `npcs`
(
    `id`            int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`          varchar(191) NOT NULL DEFAULT '',
    `description`   text         NOT NULL,
    `image`         varchar(191) NOT NULL DEFAULT 'images/noimage.png',
    `strength`      int(11)      NOT NULL DEFAULT 10,
    `defense`       int(11)      NOT NULL DEFAULT 10,
    `speed`         int(11)      NOT NULL DEFAULT 10,
    `hp`            int(11)      NOT NULL DEFAULT 100,
    `max_hp`        int(11)      NOT NULL DEFAULT 100,
    `level`         int(11)      NOT NULL DEFAULT 1,
    `money`         bigint(25)   NOT NULL DEFAULT 500,
    `city`          int(11)      NOT NULL DEFAULT 1,
    `enabled`       tinyint(1)   NOT NULL DEFAULT 1,
    `can_mug`       tinyint(1)   NOT NULL DEFAULT 0,
    `can_attack`    tinyint(1)   NOT NULL DEFAULT 0,
    `hp_regen_time` int(11)      NOT NULL DEFAULT 3600,
    `last_defeated` int(11)      NOT NULL DEFAULT 0,
    KEY (`enabled`),
    KEY (`city`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Insert 6 default NPCs (only if the table is empty)
INSERT INTO `npcs` (`name`, `description`, `image`, `strength`, `defense`, `speed`, `hp`, `max_hp`, `level`, `money`, `city`, `enabled`, `can_mug`, `can_attack`, `hp_regen_time`)
SELECT * FROM (SELECT
    'Vagrant'         AS `name`,
    'A desperate homeless person scraping by on the streets. Easy prey for beginners.' AS `description`,
    'images/noimage.png' AS `image`,
    8 AS `strength`, 5 AS `defense`, 6 AS `speed`, 40 AS `hp`, 40 AS `max_hp`, 1 AS `level`, 150 AS `money`,
    1 AS `city`, 1 AS `enabled`, 0 AS `can_mug`, 0 AS `can_attack`, 1800 AS `hp_regen_time`
) AS tmp WHERE NOT EXISTS (SELECT 1 FROM `npcs` LIMIT 1);

INSERT INTO `npcs` (`name`, `description`, `image`, `strength`, `defense`, `speed`, `hp`, `max_hp`, `level`, `money`, `city`, `enabled`, `can_mug`, `can_attack`, `hp_regen_time`)
SELECT * FROM (SELECT
    'Street Thug'     AS `name`,
    'A low-level criminal who hangs around street corners looking for easy targets.' AS `description`,
    'images/noimage.png' AS `image`,
    15 AS `strength`, 12 AS `defense`, 14 AS `speed`, 80 AS `hp`, 80 AS `max_hp`, 3 AS `level`, 400 AS `money`,
    1 AS `city`, 1 AS `enabled`, 1 AS `can_mug`, 0 AS `can_attack`, 2400 AS `hp_regen_time`
) AS tmp WHERE NOT EXISTS (SELECT 1 FROM `npcs` LIMIT 1);

INSERT INTO `npcs` (`name`, `description`, `image`, `strength`, `defense`, `speed`, `hp`, `max_hp`, `level`, `money`, `city`, `enabled`, `can_mug`, `can_attack`, `hp_regen_time`)
SELECT * FROM (SELECT
    'Drug Dealer'     AS `name`,
    'A mid-level pusher who guards his stash and cash with ruthless efficiency.' AS `description`,
    'images/noimage.png' AS `image`,
    22 AS `strength`, 18 AS `defense`, 20 AS `speed`, 120 AS `hp`, 120 AS `max_hp`, 5 AS `level`, 900 AS `money`,
    1 AS `city`, 1 AS `enabled`, 1 AS `can_mug`, 0 AS `can_attack`, 3000 AS `hp_regen_time`
) AS tmp WHERE NOT EXISTS (SELECT 1 FROM `npcs` LIMIT 1);

INSERT INTO `npcs` (`name`, `description`, `image`, `strength`, `defense`, `speed`, `hp`, `max_hp`, `level`, `money`, `city`, `enabled`, `can_mug`, `can_attack`, `hp_regen_time`)
SELECT * FROM (SELECT
    'Crime Boss'      AS `name`,
    'A seasoned crime lord who has clawed his way up the ranks through violence and cunning.' AS `description`,
    'images/noimage.png' AS `image`,
    35 AS `strength`, 30 AS `defense`, 28 AS `speed`, 200 AS `hp`, 200 AS `max_hp`, 8 AS `level`, 2000 AS `money`,
    1 AS `city`, 1 AS `enabled`, 1 AS `can_mug`, 1 AS `can_attack`, 3600 AS `hp_regen_time`
) AS tmp WHERE NOT EXISTS (SELECT 1 FROM `npcs` LIMIT 1);

INSERT INTO `npcs` (`name`, `description`, `image`, `strength`, `defense`, `speed`, `hp`, `max_hp`, `level`, `money`, `city`, `enabled`, `can_mug`, `can_attack`, `hp_regen_time`)
SELECT * FROM (SELECT
    'Gang Lieutenant' AS `name`,
    'A loyal enforcer for one of the city''s most feared gangs. Dangerous and trigger-happy.' AS `description`,
    'images/noimage.png' AS `image`,
    50 AS `strength`, 42 AS `defense`, 45 AS `speed`, 300 AS `hp`, 300 AS `max_hp`, 12 AS `level`, 4000 AS `money`,
    1 AS `city`, 1 AS `enabled`, 1 AS `can_mug`, 1 AS `can_attack`, 4800 AS `hp_regen_time`
) AS tmp WHERE NOT EXISTS (SELECT 1 FROM `npcs` LIMIT 1);

INSERT INTO `npcs` (`name`, `description`, `image`, `strength`, `defense`, `speed`, `hp`, `max_hp`, `level`, `money`, `city`, `enabled`, `can_mug`, `can_attack`, `hp_regen_time`)
SELECT * FROM (SELECT
    'The Enforcer'    AS `name`,
    'A legendary street warrior. Only the bravest — or most foolish — dare to challenge The Enforcer.' AS `description`,
    'images/noimage.png' AS `image`,
    70 AS `strength`, 60 AS `defense`, 65 AS `speed`, 500 AS `hp`, 500 AS `max_hp`, 15 AS `level`, 8000 AS `money`,
    1 AS `city`, 1 AS `enabled`, 1 AS `can_mug`, 1 AS `can_attack`, 7200 AS `hp_regen_time`
) AS tmp WHERE NOT EXISTS (SELECT 1 FROM `npcs` LIMIT 1);
