SET AUTOCOMMIT = 0;
START TRANSACTION;

DROP TABLE IF EXISTS `5050game`;
CREATE TABLE IF NOT EXISTS `5050game`
(
    `id`      int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `owner`   int(11) NOT NULL DEFAULT 0,
    `amount`  int(11) NOT NULL DEFAULT 0,
    `pamount` int(11) NOT NULL DEFAULT 0

) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `addptmarketlog`;
CREATE TABLE IF NOT EXISTS `addptmarketlog`
(
    `id`         int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `owner`      int(11)   NOT NULL DEFAULT 0,
    `amount`     int(11)   NOT NULL DEFAULT 0,
    `price`      int(11)   NOT NULL DEFAULT 0,
    `time_added` timestamp NOT NULL DEFAULT current_timestamp(),
    KEY (`owner`),
    KEY (`time_added`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `ads`;
CREATE TABLE IF NOT EXISTS `ads`
(
    `id`         int(10)       NOT NULL AUTO_INCREMENT,
    `time_added` timestamp    NOT NULL DEFAULT current_timestamp(),
    `poster`     int(10)      NOT NULL,
    `title`      varchar(100) NOT NULL,
    `message`    text         NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `banner_ads`;
CREATE TABLE IF NOT EXISTS `banner_ads`
(
    `id`              int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `ad_code`         text      NOT NULL,
    `display_seconds` int(11)   NOT NULL DEFAULT 5,
    `sort_order`      int(11)   NOT NULL DEFAULT 0,
    `created_at`      timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `attlog`;
CREATE TABLE IF NOT EXISTS `attlog`
(
    `id`         int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `gangid`     int(11)   NOT NULL DEFAULT 0,
    `attacker`   int(11)   NOT NULL DEFAULT 0,
    `defender`   int(11)   NOT NULL DEFAULT 0,
    `winner`     int(11)   NOT NULL DEFAULT 0,
    `gangexp`    int(11)   NOT NULL DEFAULT 0,
    `active`     int(11)   NOT NULL DEFAULT 0,
    `time_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `bans`;
CREATE TABLE IF NOT EXISTS `bans`
(
    `uni_id`     int(11)                               NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `id`         int(11)                               NOT NULL DEFAULT 0,
    `days`       int(11)                               NOT NULL DEFAULT 0,
    `type`       enum ('perm','freeze','mail','forum') NOT NULL,
    `reason`     varchar(191)                          NOT NULL DEFAULT '',
    `time_added` timestamp                             NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `buyptmarketlog`;
CREATE TABLE IF NOT EXISTS `buyptmarketlog`
(
    `id`         int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `owner`      int(11)   NOT NULL DEFAULT 0,
    `amount`     int(11)   NOT NULL DEFAULT 0,
    `price`      int(11)   NOT NULL DEFAULT 0,
    `buyer`      int(11)   NOT NULL DEFAULT 0,
    `time_added` timestamp NOT NULL DEFAULT current_timestamp(),
    KEY (`owner`),
    KEY (`buyer`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `carlot`;
CREATE TABLE IF NOT EXISTS `carlot`
(
    `id`          int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`        varchar(191) NOT NULL DEFAULT '',
    `cost`        int(11)      NOT NULL DEFAULT 0,
    `image`       varchar(191) NOT NULL DEFAULT 'images/noimage.png',
    `buyable`     tinyint(1)   NOT NULL DEFAULT 0,
    `description` text         NOT NULL,
    `basemod`     int(11)      NOT NULL DEFAULT 0,
    `level`       int(11)      NOT NULL DEFAULT 0

) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

INSERT INTO `carlot` (`name`, `cost`, `image`, `buyable`, `description`, `basemod`, `level`)
VALUES ('Model T', 500, 'images/cars/model-t.png', 1, 'An old time classic Model T Ford.', 1, 1);
INSERT INTO `carlot` (`name`, `cost`, `image`, `buyable`, `description`, `basemod`, `level`)
VALUES ('Ford XR GT', 100000, 'images/cars/xr-gt.png', 1, 'An old time classic Ford muscle car, the XR GT.', 5, 10);

DROP TABLE IF EXISTS `cars`;
CREATE TABLE IF NOT EXISTS `cars`
(
    `id`     int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid` int(11) NOT NULL DEFAULT 0,
    `carid`  int(11) NOT NULL DEFAULT 0

) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `cash5050game`;
CREATE TABLE IF NOT EXISTS `cash5050game`
(
    `id`      int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `owner`   int(11) NOT NULL DEFAULT 0,
    `amount`  int(11) NOT NULL DEFAULT 0,
    `pamount` int(11) NOT NULL DEFAULT 0

) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `cash5050log`;
CREATE TABLE IF NOT EXISTS `cash5050log`
(
    `id`         int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `betterip`   varchar(191) NOT NULL DEFAULT '0.0.0.0',
    `matcherip`  varchar(191) NOT NULL DEFAULT '0.0.0.0',
    `winner`     int(11)      NOT NULL DEFAULT 0,
    `better`     int(11)      NOT NULL DEFAULT 0,
    `matcher`    int(11)      NOT NULL DEFAULT 0,
    `amount`     int(11)      NOT NULL DEFAULT 0,
    `time_added` timestamp    NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `cashlottery`;
CREATE TABLE IF NOT EXISTS `cashlottery`
(
    `id`     int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid` int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities`
(
    `id`          int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`        varchar(191) NOT NULL DEFAULT '',
    `levelreq`    int(11)      NOT NULL DEFAULT 0,
    `landleft`    int(11)      NOT NULL DEFAULT 0,
    `landprice`   int(11)      NOT NULL DEFAULT 0,
    `description` text         NOT NULL,
    `price`       int(11)      NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
INSERT INTO `cities` (`name`, `levelreq`, `landleft`, `landprice`, `description`, `price`)
VALUES ('Generica', 0, 100, 1000, 'A generic city perfect for starting your journey. This bustling metropolis offers all the basic amenities and opportunities for newcomers.', 0),
       ('Novara', 5, 150, 2000, 'A vibrant city for intermediate players. Known for its thriving markets and challenging opportunities for those ready to advance.', 50000),
       ('Valoria', 10, 200, 3000, 'A prestigious city that attracts experienced individuals. Here, the stakes are higher and the rewards greater.', 100000),
       ('Centara', 15, 250, 4000, 'The ultimate destination for elite players. This sophisticated city offers the most exclusive opportunities and challenges.', 200000);

DROP TABLE IF EXISTS `contactlist`;
CREATE TABLE IF NOT EXISTS `contactlist`
(
    `id`        int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `playerid`  int(11) NOT NULL DEFAULT 0,
    `contactid` int(11) NOT NULL DEFAULT 0,
    `type`      int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `contactmessages`;
CREATE TABLE IF NOT EXISTS `contactmessages`
(
    `id`         int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `email`      varchar(191) NOT NULL,
    `subject`    varchar(75)  NOT NULL,
    `message`    text         NOT NULL,
    `timeposted` timestamp    NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries`
(
    `id`          int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`        varchar(191) NOT NULL DEFAULT '',
    `levelreq`    int(11)      NOT NULL DEFAULT 0,
    `rmonly`      int(11)      NOT NULL DEFAULT 0,
    `description` text         NOT NULL,
    `show`        int(11)      NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses`
(
    `id`     int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `course` int(11) NOT NULL,
    `user`   int(11) NOT NULL,
    KEY (`course`),
    KEY (`user`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `crimes`;
CREATE TABLE IF NOT EXISTS `crimes`
(
    `id`    int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`  varchar(191) NOT NULL DEFAULT '',
    `nerve` int(11)      NOT NULL DEFAULT 0,
    `stext` text         NOT NULL,
    `ftext` text         NOT NULL,
    `ctext` text         NOT NULL

) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

INSERT INTO `crimes` (`name`, `nerve`, `stext`, `ftext`, `ctext`)
VALUES ('Pickpocket', 1, 
        'You bump into a distracted tourist and slip your hand into their pocket.^You expertly lift a wallet from an unsuspecting businessman.^A crowded subway car provides perfect cover as you snatch some cash.',
        'Your target notices your hand reaching for their pocket.^The mark turns around just as you make your move.^A nearby security guard gives you a warning look.',
        'A plainclothes officer grabs your wrist mid-theft.^Your victim screams for the police who are conveniently nearby.^You\'re caught on camera and security arrives quickly.'),
       ('Shoplift', 2,
        'You casually walk out with merchandise hidden under your jacket.^The security tag removal goes perfectly and you stroll out unnoticed.^You blend in with a crowd of shoppers while carrying concealed goods.',
        'The alarm goes off as you try to leave the store.^A store employee spots you acting suspicious.^The item won\'t fit in your bag without being obvious.',
        'Loss prevention officers surround you at the exit.^Store security reviews the cameras and identifies you.^An undercover security guard catches you red-handed.'),
       ('Steal Car Radio', 3,
        'You quickly pop the dashboard and yank out an expensive stereo system.^The car alarm doesn\'t go off and you extract a premium sound system.^You work efficiently and get away with a high-end radio.',
        'The car alarm starts blaring as soon as you touch the dashboard.^Someone walks by and you have to abort the theft.^The radio is bolted in too well to remove quickly.',
        'The car owner returns mid-theft and calls the cops.^A patrol car happens to be passing by.^A neighbor sees you and reports the license plate of your getaway car.'),
       ('Break Into House', 5,
        'You jimmy the lock and quickly grab valuables before leaving undetected.^A window on the second floor provides easy access and great loot.^The security system is disabled and you take your time collecting valuables.',
        'A dog starts barking and you retreat empty-handed.^The lock is too sophisticated for your tools.^A neighbor becomes suspicious of the noise.',
        'The homeowner arrives home early and traps you inside.^A silent alarm alerts the police who arrive within minutes.^A vigilant neighborhood watch member reports suspicious activity.'),
       ('Rob Convenience Store', 7,
        'You brandish a weapon and the clerk hands over the cash quickly.^Your intimidating presence makes the robbery quick and successful.^You get away with the cash drawer before anyone can react.',
        'The clerk hits the panic button before you can react.^Your weapon jams and you have to flee.^A customer walks in mid-robbery forcing you to abort.',
        'The store has an armed security guard you didn\'t notice.^Police were already nearby responding to another call.^Multiple cameras capture your face clearly despite your disguise.'),
       ('Grand Theft Auto', 10,
        'You hotwire a luxury vehicle and speed away.^The car is unlocked with keys inside - too easy!^Your skills allow you to bypass the ignition in seconds.',
        'The steering wheel lock won\'t budge.^The car has a kill switch you can\'t locate.^Someone sees you trying to break in and yells.',
        'The car has a GPS tracker and police intercept you a few blocks away.^An off-duty cop witnesses the theft.^The owner has a remote kill switch and stops the car.'),
       ('Bank Robbery', 15,
        'Your careful planning pays off with a massive haul.^You crack the vault and escape before alarms can summon help.^Your team executes the heist flawlessly.',
        'The vault timer hasn\'t expired yet and you can\'t open it.^A customer triggers a silent alarm.^Your inside contact failed to disable the security.',
        'The FBI was already investigating and catches you in the act.^An armed security guard triggers a lockdown.^Your getaway driver abandons you when police arrive.');

DROP TABLE IF EXISTS `deflog`;
CREATE TABLE IF NOT EXISTS `deflog`
(
    `id`         int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `gangid`     int(11)   NOT NULL DEFAULT 0,
    `attacker`   int(11)   NOT NULL DEFAULT 0,
    `defender`   int(11)   NOT NULL DEFAULT 0,
    `winner`     int(11)   NOT NULL DEFAULT 0,
    `gangexp`    int(11)   NOT NULL DEFAULT 0,
    `active`     int(11)   NOT NULL DEFAULT 0,
    `time_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `druglords`;
CREATE TABLE IF NOT EXISTS `druglords`
(
    `id`          int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`        varchar(191) NOT NULL DEFAULT '0',
    `description` text         NOT NULL,
    `image`       varchar(191) NOT NULL DEFAULT '',
    `cost`        int(11)      NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `effects`;
CREATE TABLE IF NOT EXISTS `effects`
(
    `id`       int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid`   int(11)      NOT NULL DEFAULT 0,
    `effect`   varchar(191) NOT NULL DEFAULT '',
    `timeleft` int(11)      NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events`
(
    `id`         int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `recipient`  int(11)   NOT NULL DEFAULT 0,
    `time_added` timestamp NOT NULL DEFAULT current_timestamp(),
    `content`    text      NOT NULL,
    `extra`      int(11)   NOT NULL DEFAULT 0,
    `viewed`     int(11)   NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `forgot_password`;
CREATE TABLE IF NOT EXISTS `forgot_password`
(
    `id`         int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid`     int(11)      NOT NULL DEFAULT 0,
    `email`      varchar(191) NOT NULL DEFAULT '',
    `token`      varchar(191) NOT NULL DEFAULT '',
    `time_added` timestamp    NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `foolsgamble_log`;
CREATE TABLE IF NOT EXISTS `foolsgamble_log`
(
    `id`        int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid`    int(11)      NOT NULL DEFAULT 0,
    `timestamp` int(11)      NOT NULL DEFAULT 0,
    `text`      varchar(191) NOT NULL DEFAULT '',
    KEY `idx_timestamp` (`timestamp`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `forum_boards`;
CREATE TABLE IF NOT EXISTS `forum_boards`
(
    `fb_id`            int(11)                          NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `fb_name`          varchar(191)                     NOT NULL,
    `fb_desc`          varchar(191)                     NOT NULL,
    `fb_auth`          enum ('public','staff','family') NOT NULL DEFAULT 'public',
    `fb_bin`           tinyint(1)                       NOT NULL DEFAULT 0,
    `fb_topics`        int(11)                          NOT NULL DEFAULT 0,
    `fb_posts`         int(11)                          NOT NULL DEFAULT 0,
    `fb_latest_topic`  int(11)                          NOT NULL DEFAULT 0,
    `fb_latest_post`   int(11)                          NOT NULL DEFAULT 0,
    `fb_latest_poster` int(11)                          NOT NULL DEFAULT 0,
    `fb_latest_time`   timestamp                        NULL,
    `fb_owner`         int(11)                          NOT NULL DEFAULT 0,
    KEY (`fb_name`),
    KEY (`fb_bin`),
    KEY (`fb_auth`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

INSERT INTO `forum_boards` (`fb_name`, `fb_desc`, `fb_auth`)
VALUES ('Game News', 'Official news and announcements.', 'public'),
       ('General Chat', 'Chat about anything and everything.', 'public'),
       ('Game Discussion', 'Discussion about game related issues.', 'public'),
       ('Help & Support', 'Help with any support issues you have.', 'public'),
       ('Staff Discussion', 'Private area for game staff members.', 'staff');

DROP TABLE IF EXISTS `forum_browsers`;
CREATE TABLE IF NOT EXISTS `forum_browsers`
(
    `id`     int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid` int(11)      NOT NULL DEFAULT 0,
    `name`   varchar(191) NOT NULL DEFAULT ''
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `forum_posts`;
CREATE TABLE IF NOT EXISTS `forum_posts`
(
    `fp_id`          int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `fp_board`       int(11)      NOT NULL DEFAULT 0,
    `fp_topic`       int(11)      NOT NULL DEFAULT 0,
    `fp_time`        timestamp    NOT NULL DEFAULT current_timestamp(),
    `fp_poster`      int(11)      NOT NULL DEFAULT 0,
    `fp_text`        text         NOT NULL,
    `fp_edit_times`  smallint(8)  NOT NULL DEFAULT 0,
    `fp_edit_reason` varchar(191) NOT NULL DEFAULT '',
    `fp_edit_time`   timestamp    NULL,
    KEY (`fp_board`),
    KEY (`fp_topic`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `forum_subscriptions`;
CREATE TABLE IF NOT EXISTS `forum_subscriptions`
(
    `id`          int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid`      int(11)   NOT NULL DEFAULT 0,
    `topic`       int(11)   NOT NULL DEFAULT 0,
    `date_subbed` timestamp NOT NULL DEFAULT current_timestamp(),
    KEY (`userid`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `forum_topics`;
CREATE TABLE IF NOT EXISTS `forum_topics`
(
    `ft_id`            int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `ft_board`         int(11)      NOT NULL DEFAULT 0,
    `ft_name`          varchar(191) NOT NULL,
    `ft_creation_time` timestamp    NOT NULL DEFAULT current_timestamp(),
    `ft_creation_user` int(11)      NOT NULL DEFAULT 0,
    `ft_latest_time`   timestamp    NULL,
    `ft_latest_user`   int(11)      NOT NULL DEFAULT 0,
    `ft_latest_post`   int(11)      NOT NULL DEFAULT 0,
    `ft_pinned`        tinyint(1)   NOT NULL DEFAULT 0,
    `ft_locked`        tinyint(1)   NOT NULL DEFAULT 0,
    KEY (`ft_board`),
    KEY (`ft_pinned`),
    KEY (`ft_locked`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

INSERT INTO `forum_topics` (`ft_board`, `ft_name`, `ft_creation_user`, `ft_latest_user`, `ft_pinned`, `ft_locked`)
VALUES (1, 'Welcome to the Game!', 1, 1, 1, 0),
       (1, 'Rules & Guidelines', 1, 1, 1, 1),
       (1, 'Getting Started Guide', 1, 1, 1, 0);

INSERT INTO `forum_posts` (`fp_board`, `fp_topic`, `fp_poster`, `fp_text`)
VALUES (1, 1, 1, 'Welcome to the game! We are glad to have you here.\n\nFeel free to explore, meet other players, and enjoy everything the game has to offer. Check out the Rules & Guidelines and Getting Started Guide topics for helpful information.'),
       (1, 2, 1, 'Please follow these rules when using the forum and the game:\n\n1. Be respectful to all players.\n2. No spam, advertising, or offensive content.\n3. Keep discussions on-topic.\n4. Do not exploit bugs - report them to staff.\n5. Decisions made by staff are final.\n\nViolations may result in a ban.'),
       (1, 3, 1, 'Getting started is easy!\n\n1. Complete your profile and choose your character name.\n2. Visit the city to explore locations and activities.\n3. Earn money through jobs, crimes, and other activities.\n4. Level up by gaining experience points.\n5. Join a gang or create your own.\n\nIf you need help, post in the Help & Support board.');

UPDATE `forum_topics` SET `ft_latest_post` = `ft_id`, `ft_latest_time` = NOW() WHERE `ft_id` IN (1, 2, 3);

UPDATE `forum_boards`
SET `fb_topics` = 3, `fb_posts` = 3, `fb_latest_topic` = 3, `fb_latest_post` = 3, `fb_latest_poster` = 1, `fb_latest_time` = NOW()
WHERE `fb_id` = 1;

DROP TABLE IF EXISTS `gangattacks`;
CREATE TABLE IF NOT EXISTS `gangattacks`
(
    `id`   int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `gang` int(11) NOT NULL DEFAULT 0,
    `no`   int(11) NOT NULL DEFAULT 0,
    UNIQUE KEY `gang` (`gang`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `gangarmory`;
CREATE TABLE IF NOT EXISTS `gangarmory`
(
    `id`       int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `gangid`   int(11) NOT NULL DEFAULT 0,
    `itemid`   int(11) NOT NULL DEFAULT 0,
    `quantity` int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `gangcrime`;
CREATE TABLE IF NOT EXISTS `gangcrime`
(
    `id`        int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`      varchar(191) NOT NULL DEFAULT '',
    `duration`  int(11)      NOT NULL DEFAULT 0,
    `reward`    int(11)      NOT NULL DEFAULT 0,
    `members`   int(11)      NOT NULL DEFAULT 0,
    `expreward` int(11)      NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `gangevents`;
CREATE TABLE IF NOT EXISTS `gangevents`
(
    `id`       int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `gang`     int(11) NOT NULL DEFAULT 0,
    `timesent` int(11) NOT NULL DEFAULT 0,
    `text`     text    NOT NULL,
    `extra`    int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `ganginvites`;
CREATE TABLE IF NOT EXISTS `ganginvites`
(
    `id`       int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `playerid` int(11) NOT NULL DEFAULT 0,
    `gangid`   int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `gangmail`;
CREATE TABLE IF NOT EXISTS `gangmail`
(
    `id`         int(100)  NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `gangid`     int(11)   NOT NULL,
    `playerid`   int(11)   NOT NULL,
    `time_added` timestamp NOT NULL DEFAULT current_timestamp(),
    `subject`    text      NOT NULL,
    `body`       text      NOT NULL,
    KEY (`playerid`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `gangs`;
CREATE TABLE IF NOT EXISTS `gangs`
(
    `id`           int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`         varchar(191) NOT NULL DEFAULT '',
    `banner`       varchar(191) NOT NULL DEFAULT '',
    `description`  text         NOT NULL DEFAULT '',
    `publicpage`   text         NOT NULL DEFAULT '',
    `boughtbanner` int(11)      NOT NULL DEFAULT 0,
    `leader`       int(11)      NOT NULL DEFAULT 0,
    `capacity`     int(11)      NOT NULL DEFAULT 5,
    `tag`          varchar(3)   NOT NULL DEFAULT '',
    `level`        int(11)      NOT NULL DEFAULT 1,
    `experience`   bigint(25)   NOT NULL DEFAULT 0,
    `moneyvault`   bigint(25)   NOT NULL DEFAULT 0,
    `pointsvault`  bigint(25)   NOT NULL DEFAULT 0,
    `crime`        int(11)      NOT NULL DEFAULT 0,
    `ending`       int(11)      NOT NULL DEFAULT 0,
    `ghouse`       int(11)      NOT NULL DEFAULT 0,
    `tmstats`      int(11)      NOT NULL DEFAULT 0,
    `tax`          int(11)      NOT NULL DEFAULT 0,
    `crimestarter` int(11)      NOT NULL DEFAULT 0,
    `kills`        int(11)      NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `gangwars`;
CREATE TABLE IF NOT EXISTS `gangwars`
(
    `id`         int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `gang1`      int(11)   NOT NULL DEFAULT 0,
    `gang2`      int(11)   NOT NULL DEFAULT 0,
    `accepted`   int(11)   NOT NULL DEFAULT 0,
    `time_added` timestamp NOT NULL DEFAULT current_timestamp(),
    `bet`        int(20)   NOT NULL DEFAULT 0,
    `time_ended` timestamp NULL,
    `warid`      int(11)   NOT NULL DEFAULT 0,
    `gang1score` int(100)  NOT NULL DEFAULT 0,
    `gang2score` int(100)  NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `goth`;
CREATE TABLE IF NOT EXISTS `goth`
(
    `id`    int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `gang`  int(11)   NOT NULL DEFAULT 0,
    `kills` int(11)   NOT NULL DEFAULT 0,
    `time`  timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `gang_loans`;
CREATE TABLE IF NOT EXISTS `gang_loans`
(
    `id`       int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `to`       int(11) NOT NULL DEFAULT 0,
    `item`     int(11) NOT NULL DEFAULT 0,
    `quantity` int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `gcrimelog`;
CREATE TABLE IF NOT EXISTS `gcrimelog`
(
    `id`        int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `gangid`    int(11)      NOT NULL DEFAULT 0,
    `timestamp` int(11)      NOT NULL DEFAULT 0,
    `text`      text         NOT NULL,
    `reward`    varchar(191) NOT NULL DEFAULT '',
    `userid`    int(11)      NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `ghouses`;
CREATE TABLE IF NOT EXISTS `ghouses`
(
    `id`    int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`  varchar(191) NOT NULL DEFAULT '',
    `awake` int(11)      NOT NULL DEFAULT 0,
    `cost`  int(11)      NOT NULL DEFAULT 0,
    `tax`   int(11)      NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `growing`;
CREATE TABLE IF NOT EXISTS `growing`
(
    `id`         int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `amount`     int(11)      NOT NULL DEFAULT 0,
    `cropamount` int(11)      NOT NULL DEFAULT 0,
    `userid`     int(11)      NOT NULL DEFAULT 0,
    `croptype`   varchar(191) NOT NULL DEFAULT '',
    `cityid`     int(11)      NOT NULL DEFAULT 0,
    `time_ended` TIMESTAMP    NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `houses`;
CREATE TABLE IF NOT EXISTS `houses`
(
    `id`      int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`    varchar(191) NOT NULL DEFAULT '',
    `image`   varchar(191) NOT NULL DEFAULT '',
    `awake`   int(11)      NOT NULL DEFAULT 100,
    `cost`    int(11)      NOT NULL DEFAULT 0,
    `buyable` int(11)      NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

INSERT INTO `houses` (`name`, `image`, `awake`, `cost`, `buyable`)
VALUES ('Shack', 'images/houses/shack.png', 100, 500, 1),
       ('House', 'images/houses/house.png', 200, 5000, 1),
       ('Fortress', 'images/houses/fortress.png', 300, 50000, 1);

DROP TABLE IF EXISTS `ignorelist`;
CREATE TABLE IF NOT EXISTS `ignorelist`
(
    `id`         int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `blocker`    int(11)   NOT NULL DEFAULT 0,
    `blocked`    int(11)   NOT NULL DEFAULT 0,
    `time_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory`
(
    `id`       int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid`   int(11) NOT NULL DEFAULT 0,
    `itemid`   int(11) NOT NULL DEFAULT 0,
    `quantity` int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `ipn`;
CREATE TABLE IF NOT EXISTS `ipn`
(
    `id`            int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `user_id`       int(11)      NOT NULL DEFAULT 0,
    `creditsbought` int(11)      NOT NULL DEFAULT 0,
    `paymentamount` int(11)      NOT NULL DEFAULT 0,
    `payeremail`    varchar(191) NOT NULL DEFAULT '',
    `date`          int(11)      NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `itemmarket`;
CREATE TABLE IF NOT EXISTS `itemmarket`
(
    `id`         int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `itemid`     int(11)   NOT NULL DEFAULT 0,
    `userid`     int(11)   NOT NULL DEFAULT 0,
    `cost`       int(11)   NOT NULL DEFAULT 0,
    `time_added` timestamp NOT NULL DEFAULT current_timestamp(),
    KEY (`itemid`),
    KEY (`userid`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items`
(
    `id`          int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`        varchar(191) NOT NULL DEFAULT '',
    `description` text         NOT NULL,
    `image`       varchar(191) NOT NULL DEFAULT '',
    `speed`       int(11)      NOT NULL DEFAULT 0,
    `defense`     int(11)      NOT NULL DEFAULT 0,
    `cost`        int(11)      NOT NULL DEFAULT 0,
    `offense`     int(11)      NOT NULL DEFAULT 0,
    `buyable`     int(11)      NOT NULL DEFAULT 0,
    `heal`        int(11)      NOT NULL DEFAULT 0,
    `level`       int(11)      NOT NULL DEFAULT 0,
    `drugstr`     int(11)      NOT NULL DEFAULT 0,
    `drugspe`     int(11)      NOT NULL DEFAULT 0,
    `drugdef`     int(11)      NOT NULL DEFAULT 0,
    `drugstime`   int(11)      NOT NULL DEFAULT 0,
    `reduce`      int(11)      NOT NULL DEFAULT 0,
    `petupgrades` int(11)      NOT NULL DEFAULT 0,
    `rare`        int(11)      NOT NULL DEFAULT 0,
    `rmdays`      int(11)      NOT NULL DEFAULT 0,
    `money`       int(11)      NOT NULL DEFAULT 0,
    `points`      int(11)      NOT NULL DEFAULT 0,
    `cid`         int(11)      NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

INSERT INTO `items` (`name`, `description`, `image`, `speed`, `defense`, `cost`, `offense`, `buyable`, `heal`, `level`, `reduce`)
VALUES ('Energy Drink', 'A fizzy drink that gives you a small boost of energy.', 'images/items/energy-drink.png', 1, 0, 50, 0, 1, 5, 1, 0),
       ('Trumpet',      'A shiny brass instrument that makes a lot of noise.',   'images/items/trumpet.png',      0, 1, 75, 1, 1, 0, 1, 0);

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs`
(
    `id`       int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`     varchar(191) NOT NULL DEFAULT '',
    `money`    int(11)      NOT NULL DEFAULT 0,
    `strength` int(11)      NOT NULL DEFAULT 0,
    `defense`  int(11)      NOT NULL DEFAULT 0,
    `speed`    int(11)      NOT NULL DEFAULT 0,
    `level`    int(11)      NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

INSERT INTO `jobs` (`name`, `money`, `strength`, `defense`, `speed`, `level`) VALUES
('Street Sweeper',      100,    0,   0,   0,  1),
('Delivery Driver',     350,    5,   0,   5,  3),
('Grocery Store Clerk', 750,    5,   5,   5,  5),
('Security Guard',     1500,   15,  10,   5, 10),
('Mechanic',           3000,   20,  10,  10, 15),
('Factory Worker',     6000,   30,  20,  15, 20),
('Police Officer',    12000,   40,  35,  25, 30),
('Firefighter',       22000,   50,  45,  35, 40),
('Construction Foreman',40000, 65,  55,  40, 50),
('Bank Manager',       75000,  70,  70,  50, 65),
('Corporate Executive',150000, 80,  80,  65, 80),
('Crime Lord',         400000, 100, 100, 100, 100);

DROP TABLE IF EXISTS `land`;
CREATE TABLE IF NOT EXISTS `land`
(
    `id`     int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid` int(11) NOT NULL DEFAULT 0,
    `city`   int(11) NOT NULL DEFAULT 0,
    `amount` int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `lottery`;
CREATE TABLE IF NOT EXISTS `lottery`
(
    `id`     int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid` int(20) NOT NULL

) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `monthly_referrals`;
CREATE TABLE IF NOT EXISTS `monthly_referrals`
(
    `id`       int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `MONTH_1`  int(11) NOT NULL DEFAULT 0,
    `MONTH_2`  int(11) NOT NULL DEFAULT 0,
    `MONTH_3`  int(11) NOT NULL DEFAULT 0,
    `MONTH_4`  int(11) NOT NULL DEFAULT 0,
    `MONTH_5`  int(11) NOT NULL DEFAULT 0,
    `MONTH_6`  int(11) NOT NULL DEFAULT 0,
    `MONTH_7`  int(11) NOT NULL DEFAULT 0,
    `MONTH_8`  int(11) NOT NULL DEFAULT 0,
    `MONTH_9`  int(11) NOT NULL DEFAULT 0,
    `MONTH_10` int(11) NOT NULL DEFAULT 0,
    `MONTH_11` int(11) NOT NULL DEFAULT 0,
    `MONTH_12` int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `pending_validations`;
CREATE TABLE IF NOT EXISTS `pending_validations`
(
    `id`              int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `username`        varchar(191) NOT NULL DEFAULT '',
    `ip`              varchar(191) NOT NULL DEFAULT '',
    `password`        text         NOT NULL,
    `email`           varchar(191) NOT NULL DEFAULT '',
    `class`           varchar(191) NOT NULL DEFAULT '',
    `validation_code` varchar(191) NOT NULL DEFAULT '',
    `time_added`      timestamp    NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

DROP TABLE IF EXISTS `pms`;
CREATE TABLE IF NOT EXISTS `pms`
(
    `id`        int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `parent`    int(11)      NOT NULL DEFAULT 0,
    `sender`    int(11)      NOT NULL DEFAULT 0,
    `recipient` int(11)      NOT NULL DEFAULT 0,
    `timesent`  int(11)      NOT NULL DEFAULT 0,
    `subject`   varchar(191) NOT NULL DEFAULT '',
    `msgtext`   text         NOT NULL,
    `viewed`    int(11)      NOT NULL DEFAULT 0,
    `bomb`      int(11)      NOT NULL DEFAULT 0,
    `bombed`    int(11)      NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `pointsmarket`;
CREATE TABLE IF NOT EXISTS `pointsmarket`
(
    `id`     int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `owner`  int(11) NOT NULL DEFAULT 0,
    `amount` int(11) NOT NULL DEFAULT 0,
    `price`  int(11) NOT NULL DEFAULT 0,
    KEY (`owner`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `poll1`;
CREATE TABLE IF NOT EXISTS `poll1`
(
    `optionid` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `votes`    int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `pts5050game`;
CREATE TABLE IF NOT EXISTS `pts5050game`
(
    `id`      int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `owner`   int(11) NOT NULL DEFAULT 0,
    `amount`  int(11) NOT NULL DEFAULT 0,
    `pamount` int(11) NOT NULL DEFAULT 0,
    `live`    int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `pts5050log`;
CREATE TABLE IF NOT EXISTS `pts5050log`
(
    `id`         int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `betterip`   varchar(191) NOT NULL DEFAULT '0.0.0.0',
    `matcherip`  varchar(191) NOT NULL DEFAULT '0.0.0.0',
    `winner`     int(11)      NOT NULL DEFAULT 0,
    `better`     int(11)      NOT NULL DEFAULT 0,
    `matcher`    int(11)      NOT NULL DEFAULT 0,
    `amount`     int(11)      NOT NULL DEFAULT 0,
    `time_added` timestamp    NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `ptslottery`;
CREATE TABLE IF NOT EXISTS `ptslottery`
(
    `id`     int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid` int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `ranks`;
CREATE TABLE IF NOT EXISTS `ranks`
(
    `id`           int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `gang`         int(11)      NOT NULL DEFAULT 0,
    `title`        varchar(191) NOT NULL DEFAULT '',
    `members`      int(11)      NOT NULL DEFAULT 0,
    `crime`        int(11)      NOT NULL DEFAULT 0,
    `vault`        int(11)      NOT NULL DEFAULT 0,
    `ranks`        int(11)      NOT NULL DEFAULT 0,
    `massmail`     int(11)      NOT NULL DEFAULT 0,
    `applications` int(11)      NOT NULL DEFAULT 0,
    `appearance`   int(11)      NOT NULL DEFAULT 0,
    `invite`       int(11)      NOT NULL DEFAULT 0,
    `houses`       int(11)      NOT NULL DEFAULT 0,
    `upgrade`      int(11)      NOT NULL DEFAULT 0,
    `gforum`       int(11)      NOT NULL DEFAULT 0,
    `polls`        int(11)      NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `rating`;
CREATE TABLE IF NOT EXISTS `rating`
(
    `id`    int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `user`  int(11) NOT NULL DEFAULT 0,
    `rater` int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `referrals`;
CREATE TABLE IF NOT EXISTS `referrals`
(
    `id`         int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `referrer`   int(11)   NOT NULL DEFAULT 0,
    `referred`   int(11)   NOT NULL DEFAULT 0,
    `credited`   int(11)   NOT NULL DEFAULT 0,
    `viewed`     int(11)   NOT NULL DEFAULT 0,
    `time_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `removeptmarketlog`;
CREATE TABLE IF NOT EXISTS `removeptmarketlog`
(
    `id`         int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `owner`      int(11)   NOT NULL DEFAULT 0,
    `amount`     int(11)   NOT NULL DEFAULT 0,
    `price`      int(11)   NOT NULL DEFAULT 0,
    `time_added` timestamp NOT NULL DEFAULT current_timestamp(),
    KEY (`owner`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `rmstore_ipn`;
CREATE TABLE IF NOT EXISTS `rmstore_ipn`
(
    `id`             int(11)                                 NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid`         int(11)                                 NOT NULL DEFAULT 0,
    `recipient`      int(11)                                 NOT NULL DEFAULT 0,
    `transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `payer_email`    varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `pack_id`        int(11)                                 NOT NULL DEFAULT 0,
    `pack_cost`      decimal(4, 2)                           NOT NULL DEFAULT 0.00,
    `time_purchased` timestamp                               NOT NULL DEFAULT current_timestamp(),
    `paid_amount`    decimal(11, 2)                          NOT NULL DEFAULT 0.00,
    `discount`       int(5)                                  NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `rmstore_packs`;
CREATE TABLE IF NOT EXISTS `rmstore_packs`
(
    `id`          int(11)                                 NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`        varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `cost`        decimal(4, 2)                           NOT NULL DEFAULT 0.00,
    `days`        int(11)                                 NOT NULL DEFAULT 0,
    `money`       bigint(25)                              NOT NULL DEFAULT 0,
    `points`      bigint(25)                              NOT NULL DEFAULT 0,
    `prostitutes` int(11)                                 NOT NULL DEFAULT 0,
    `items`       text COLLATE utf8mb4_unicode_ci         NOT NULL,
    `enabled`     tinyint(4)                              NOT NULL DEFAULT 1
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `rmstore_packs_errors`;
CREATE TABLE IF NOT EXISTS `rmstore_packs_errors`
(
    `id`        int(11)                                               NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `subject`   varchar(191) COLLATE utf8mb4_unicode_ci               NOT NULL DEFAULT '',
    `message`   text COLLATE utf8mb4_unicode_ci                       NOT NULL,
    `status`    enum ('pending','handled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
    `time_sent` timestamp                                             NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `serverconfig`;
CREATE TABLE IF NOT EXISTS `serverconfig`
(
    `ID`               int(11)     NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `radio`            varchar(5)  NOT NULL DEFAULT '',
    `serverdown`       text        NULL,
    `messagefromadmin` text        NULL,
    `register_lock`    int(1)      NOT NULL DEFAULT 0,
    `gamename`         varchar(25) NOT NULL DEFAULT '',
    `link`             varchar(75) NOT NULL DEFAULT ''
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
INSERT INTO `serverconfig` (`ID`)
VALUES (1);

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions`
(
    `session_id`     varchar(32) NOT NULL DEFAULT '',
    `hash`           varchar(32) NOT NULL DEFAULT '',
    `session_data`   blob        NOT NULL,
    `session_expire` int(11)     NOT NULL DEFAULT 0,
    `userid`         int(11)     NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings`
(
    `settings`   int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `conf_name`  varchar(191) NOT NULL,
    `conf_value` varchar(191) NOT NULL

) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;
INSERT INTO `settings` (`settings`, `conf_name`, `conf_value`)
VALUES (1, 'registration', 'open'),
       (2, 'bus_travel_cost', '5000'),
       (3, 'google_analytics', ''),
       (4, 'banner_ads_enabled', 'off');

DROP TABLE IF EXISTS `shares`;
CREATE TABLE IF NOT EXISTS `shares`
(
    `id`        int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `companyid` int(11) NOT NULL DEFAULT 0,
    `userid`    int(11) NOT NULL DEFAULT 0,
    `amount`    int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `shout_box`;
CREATE TABLE IF NOT EXISTS `shout_box`
(
    `id`         int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `user`       varchar(60)  NOT NULL,
    `message`    varchar(100) NOT NULL,
    `date_time`  timestamp    NOT NULL DEFAULT current_timestamp(),
    `ip_address` varchar(40)  NOT NULL

) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

DROP TABLE IF EXISTS `site_bans`;
CREATE TABLE IF NOT EXISTS `site_bans`
(
    `id`       int(11)                                 NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid`   int(11)                                 NOT NULL,
    `reason`   varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
    `banner`   int(11)                                 NOT NULL,
    `bannedon` timestamp                               NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `spylog`;
CREATE TABLE IF NOT EXISTS `spylog`
(
    `id`       int(10) NOT NULL DEFAULT 0,
    `spyid`    int(10) NOT NULL DEFAULT 0,
    `strength` int(10) NOT NULL DEFAULT 0,
    `defense`  int(10) NOT NULL DEFAULT 0,
    `speed`    int(10) NOT NULL DEFAULT 0,
    `bank`     int(30) NOT NULL DEFAULT 0,
    `points`   int(20) NOT NULL DEFAULT 0,
    `age`      int(20) NOT NULL DEFAULT 0,
    KEY (`id`),
    KEY (`spyid`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `staffapps`;
CREATE TABLE IF NOT EXISTS `staffapps`
(
    `ID`        int(11)     NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid`    int(11)     NOT NULL,
    `timeon`    int(20)     NOT NULL,
    `pastexp`   int(11)     NOT NULL,
    `better`    varchar(75) NOT NULL DEFAULT '',
    `staffrole` varchar(75) NOT NULL DEFAULT ''
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `staff_logs`;
CREATE TABLE IF NOT EXISTS `staff_logs`
(
    `id`        int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `player`    int(11) NOT NULL DEFAULT 0,
    `text`      text    NOT NULL,
    `timestamp` int(11) NOT NULL DEFAULT 0,
    `extra`     int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `stocks`;
CREATE TABLE IF NOT EXISTS `stocks`
(
    `id`           int(10)     NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `company_name` varchar(75) NOT NULL,
    `cost`         int(10)     NOT NULL

) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `ticketreplies`;
CREATE TABLE IF NOT EXISTS `ticketreplies`
(
    `id`                 int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid`             int(11)   NOT NULL DEFAULT 0,
    `ticketid`           int(11)   NOT NULL DEFAULT 0,
    `body`               text      NOT NULL,
    `time_added`         timestamp NOT NULL DEFAULT current_timestamp(),
    `time_last_response` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE IF NOT EXISTS `tickets`
(
    `id`                 int(11)                                   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid`             int(11)                                   NOT NULL DEFAULT 0,
    `subject`            varchar(191)                              NOT NULL DEFAULT '',
    `body`               text                                      NOT NULL,
    `status`             enum ('open','pending','closed','locked') NOT NULL DEFAULT 'open',
    `time_added`         timestamp                                 NOT NULL DEFAULT current_timestamp(),
    `time_last_response` timestamp                                 NULL,
    KEY (`userid`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `theatre_videos`;
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

DROP TABLE IF EXISTS `theatre_video_votes`;
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

DROP TABLE IF EXISTS `todo`;
CREATE TABLE IF NOT EXISTS `todo`
(
    `id`         int(11)     NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `content`    text        NOT NULL,
    `status`     smallint(8) NOT NULL DEFAULT 0,
    `time_added` timestamp   NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `uni`;
CREATE TABLE IF NOT EXISTS `uni`
(
    `id`       int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `playerid` int(11) NOT NULL DEFAULT 0,
    `courseid` int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `updates`;
CREATE TABLE IF NOT EXISTS `updates`
(
    `id`       int(11)     NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`     varchar(75) NOT NULL DEFAULT '',
    `lastdone` int(11)     NOT NULL DEFAULT 0,
    UNIQUE KEY `uq_updates_name` (`name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
INSERT INTO `updates` (`name`, `lastdone`)
VALUES ('1min', UNIX_TIMESTAMP()),
       ('5min', UNIX_TIMESTAMP()),
       ('1hour', UNIX_TIMESTAMP()),
       ('1day', UNIX_TIMESTAMP());

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users`
(
    `id`              int(11)                        NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `username`        varchar(191)                   NOT NULL DEFAULT '',
    `loginame`        varchar(191)                   NOT NULL DEFAULT '',
    `password`        varchar(191)                   NOT NULL DEFAULT '',
    `admin`           int(11)                        NOT NULL DEFAULT 0,
    `ban`             tinyint(4)                     NOT NULL DEFAULT 0,
    `lastactive`      timestamp                      NOT NULL DEFAULT current_timestamp(),
    `gang`            int(11)                        NOT NULL DEFAULT 0,
    `grank`           int(11)                        NOT NULL DEFAULT 0,
    `eqweapon`        int(11)                        NOT NULL DEFAULT 0,
    `eqarmor`         int(11)                        NOT NULL DEFAULT 0,
    `eqshoes`         int(11)                        NOT NULL DEFAULT 0,
    `drugused`        int(11)                        NOT NULL DEFAULT 0,
    `drugtime`        int(11)                        NOT NULL DEFAULT 0,
    `strength`        bigint(25)                     NOT NULL DEFAULT 10,
    `speed`           bigint(25)                     NOT NULL DEFAULT 10,
    `defense`         bigint(25)                     NOT NULL DEFAULT 10,
    `ip`              varchar(191)                   NOT NULL DEFAULT '0.0.0.0',
    `marijuana`       int(11)                        NOT NULL DEFAULT 0,
    `potseeds`        int(11)                        NOT NULL DEFAULT 0,
    `experience`      bigint(25)                     NOT NULL DEFAULT 0,
    `level`           int(11)                        NOT NULL DEFAULT 1,
    `money`           bigint(25)                     NOT NULL DEFAULT 1000,
    `bank`            bigint(25)                     NOT NULL DEFAULT 0,
    `banklog`         bigint(25)                     NOT NULL DEFAULT 0,
    `bankupgrade`     bigint(25)                     NOT NULL DEFAULT 0,
    `upgradetimes`    int(11)                        NOT NULL DEFAULT 0,
    `whichbank`       int(11)                        NOT NULL DEFAULT 0,
    `workexp`         int(11)                        NOT NULL DEFAULT 0,
    `hp`              int(11)                        NOT NULL DEFAULT 50,
    `energy`          int(11)                        NOT NULL DEFAULT 10,
    `nerve`           int(11)                        NOT NULL DEFAULT 5,
    `battlewon`       int(11)                        NOT NULL DEFAULT 0,
    `battlelost`      int(11)                        NOT NULL DEFAULT 0,
    `battlemoney`     int(11)                        NOT NULL DEFAULT 0,
    `crimesucceeded`  int(11)                        NOT NULL DEFAULT 0,
    `crimefailed`     int(11)                        NOT NULL DEFAULT 0,
    `crimemoney`      int(11)                        NOT NULL DEFAULT 0,
    `busts`           int(11)                        NOT NULL DEFAULT 0,
    `caught`          int(11)                        NOT NULL DEFAULT 0,
    `signuptime`      timestamp                      NOT NULL DEFAULT current_timestamp(),
    `points`          int(11)                        NOT NULL DEFAULT 0,
    `rmdays`          int(11)                        NOT NULL DEFAULT 0,
    `house`           int(11)                        NOT NULL DEFAULT 0,
    `awake`           int(11)                        NOT NULL DEFAULT 100,
    `email`           varchar(191)                   NOT NULL DEFAULT '',
    `quote`           varchar(75)                    NOT NULL DEFAULT 'No-Quote',
    `avatar`          varchar(191)                   NOT NULL DEFAULT 'images/noimage.png',
    `city`            int(11)                        NOT NULL DEFAULT 1,
    `jail`            int(11)                        NOT NULL DEFAULT 0,
    `job`             int(11)                        NOT NULL DEFAULT 0,
    `hospital`        int(11)                        NOT NULL DEFAULT 0,
    `searchdowntown`  int(11)                        NOT NULL DEFAULT 100,
    `gender`          enum ('Male','Female','Other') NOT NULL DEFAULT 'Male',
    `posts`           int(11)                        NOT NULL DEFAULT 0,
    `signature`       text                           NOT NULL DEFAULT '',
    `notepad`         text                           NOT NULL DEFAULT '',
    `voted1`          int(11)                        NOT NULL DEFAULT 0,
    `voted2`          int(11)                        NOT NULL DEFAULT 0,
    `voted3`          int(11)                        NOT NULL DEFAULT 0,
    `voted4`          int(11)                        NOT NULL DEFAULT 0,
    `tag`             varchar(191)                   NOT NULL DEFAULT '',
    `polled1`         int(11)                        NOT NULL DEFAULT 0,
    `threadtime`      int(11)                        NOT NULL DEFAULT 0,
    `viewedupdate`    int(11)                        NOT NULL DEFAULT 0,
    `gangmail`        int(11)                        NOT NULL DEFAULT 0,
    `refcount`        int(11)                        NOT NULL DEFAULT 0,
    `boxes_opened`    int(11)                        NOT NULL DEFAULT 20,
    `lastchase`       int(11)                        NOT NULL DEFAULT 0,
    `userBANKDAYS`    int(11)                        NOT NULL DEFAULT 0,
    `mail_ban`        int(11)                        NOT NULL DEFAULT 0,
    `chat_ban`        int(11)                        NOT NULL DEFAULT 0,
    `banned`          int(11)                        NOT NULL DEFAULT 0,
    `hwho`            int(11)                        NOT NULL DEFAULT 0,
    `hwhen`           varchar(191)                   NOT NULL DEFAULT '',
    `hhow`            varchar(191)                   NOT NULL DEFAULT '',
    `gangleader`      int(11)                        NOT NULL DEFAULT 0,
    `activate`        int(11)                        NOT NULL DEFAULT 0,
    `news`            int(11)                        NOT NULL DEFAULT 0,
    `total`           int(11)                        NOT NULL DEFAULT 30,
    `posttime`        int(11)                        NOT NULL DEFAULT 0,
    `reported`        int(11)                        NOT NULL DEFAULT 0,
    `referrals`       int(11)                        NOT NULL DEFAULT 0,
    `signupip`        varchar(191)                   NOT NULL DEFAULT '0.0.0.0',
    `gangcrimes`      int(11)                        NOT NULL DEFAULT 0,
    `codescorrect`    int(11)                                 DEFAULT 0,
    `notes`           varchar(75)                    NOT NULL DEFAULT '',
    `class`           varchar(25)                    NOT NULL DEFAULT 'Mastermind',
    `nodoze`          int(11)                        NOT NULL DEFAULT 0,
    `genericsteroids` int(11)                        NOT NULL DEFAULT 0,
    `cocaine`         int(11)                        NOT NULL DEFAULT 0,
    `hookers`         int(11)                        NOT NULL DEFAULT 0,
    `slapping`        int(11)                        NOT NULL DEFAULT 0,
    `slapped`         int(11)                        NOT NULL DEFAULT 0,
    `barpoints`       int(11)                        NOT NULL DEFAULT 0,
    `gamble_daily`    int(11)                        NOT NULL DEFAULT 0
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = latin1;

DROP TABLE IF EXISTS `tickets_responses`;
CREATE TABLE `tickets_responses`
(
    id         INT(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    userid     INT(11)   NULL,
    body       TEXT      NULL,
    ticket_id  INT(11)   NOT NULL,
    time_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX (ticket_id),
    FOREIGN KEY (userid) REFERENCES users (id),
    FOREIGN KEY (ticket_id) REFERENCES tickets (id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS ganglog;
CREATE TABLE ganglog
(
    id         INT       NOT NULL PRIMARY KEY AUTO_INCREMENT,
    gangid     INT       NOT NULL,
    attacker   INT       NOT NULL,
    defender   INT       NOT NULL,
    winner     INT       NOT NULL,
    time_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gangid) REFERENCES gangs (id),
    FOREIGN KEY (attacker) REFERENCES users (id),
    FOREIGN KEY (defender) REFERENCES users (id),
    FOREIGN KEY (winner) REFERENCES users (id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `users_blocked`;
CREATE TABLE IF NOT EXISTS `users_blocked`
(
    `id`         int(11)   NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid`     int(11)   NOT NULL DEFAULT 0,
    `blocked_id` int(11)   NOT NULL DEFAULT 0,
    `comment`    text      NOT NULL,
    `time_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

DROP TABLE IF EXISTS `npcs`;
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

INSERT INTO `npcs` (`name`, `description`, `image`, `strength`, `defense`, `speed`, `hp`, `max_hp`, `level`, `money`, `city`, `enabled`, `can_mug`, `can_attack`, `hp_regen_time`) VALUES
('Vagrant',         'Desperate homeless person scraping by on the streets ... easy prey!',                     'images/npc/vagrant.png',          8,  5,  6,  40,  40,  1,  150,  1, 1, 0, 0, 1800),
('Street Thug',     'Low-level criminal who hangs around street corners looking for easy targets.',                       'images/npc/street-thug.png',      15, 12, 14, 80,  80,  3,  400,  1, 1, 1, 0, 2400),
('Drug Dealer',     'Mid-level pusher who guards his stash and cash with ruthless efficiency.',                           'images/npc/drug-dealer.png',      22, 18, 20, 120, 120, 5,  900,  1, 1, 1, 0, 3000),
('Crime Boss',      'Seasoned crime lord who clawed his way up through violence and cunning.',              'images/npc/crime-boss.png',       35, 30, 28, 200, 200, 8,  2000, 1, 1, 1, 1, 3600),
('Gang Lieutenant', 'Loyal enforcer of the cities most feared gang, dangerous and trigger-happy.',              'images/npc/gang-lieutenant.png',  50, 42, 45, 300, 300, 12, 4000, 1, 1, 1, 1, 4800),
('The Enforcer',    'Legendary street warrior, only the bravest or dumbest dare to challenge.',    'images/npc/enforcer.png',         70, 60, 65, 500, 500, 15, 8000, 1, 1, 1, 1, 7200);

DROP TABLE IF EXISTS `users_votes`;
CREATE TABLE IF NOT EXISTS `users_votes`
(
    `id`     int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid` int(11) NOT NULL DEFAULT 0,
    `site`   int(11) NOT NULL DEFAULT 0,
    KEY (`userid`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `vlog`;
CREATE TABLE IF NOT EXISTS `vlog`
(
    `id`        int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `gangid`    int(11) NOT NULL DEFAULT 0,
    `timestamp` int(11) NOT NULL DEFAULT 0,
    `text`      text    NOT NULL,
    `userid`    int(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `voting_sites`;
CREATE TABLE IF NOT EXISTS `voting_sites`
(
    `id`                   int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `title`                varchar(191) NOT NULL DEFAULT 'No title',
    `url`                  varchar(191) NOT NULL DEFAULT '',
    `reward_cash`          bigint(25)   NOT NULL DEFAULT 0,
    `reward_points`        bigint(25)   NOT NULL DEFAULT 0,
    `reward_items`         varchar(191) NOT NULL DEFAULT '0',
    `reward_rmdays`        int(11)      NOT NULL DEFAULT 0,
    `req_account_days_min` int(11)      NOT NULL DEFAULT 0,
    `req_account_days_max` int(11)      NOT NULL DEFAULT 0,
    `req_rmdays`           int(11)      NOT NULL DEFAULT 0,
    `days_between_vote`    int(11)      NOT NULL DEFAULT 1,
    `enabled`              tinyint(4)   NOT NULL DEFAULT 1,
    `date_added`           timestamp    NOT NULL DEFAULT current_timestamp(),
    KEY (`enabled`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
CREATE TABLE IF NOT EXISTS `luckyboxes`
(
    `id`          int(11)     NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `boxnumber`   varchar(20) NOT NULL,
    `fightername` varchar(20) NOT NULL DEFAULT '',
    `playerid`    int(11)     NOT NULL DEFAULT 0,
    UNIQUE KEY `boxnumber` (`boxnumber`),
    KEY `playerid` (`playerid`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

INSERT IGNORE INTO `luckyboxes` (`boxnumber`, `fightername`, `playerid`) VALUES
(1,  'Iron Fist',    0), (2,  'The Viper',   0), (3,  'Mad Dog',     0), (4,  'Knuckles',    0), (5,  'Razor',       0),
(6,  'The Crusher',  0), (7,  'Shadow',       0), (8,  'Brawler',     0), (9,  'The Beast',   0), (10, 'Stone Cold',  0),
(11, 'Rampage',      0), (12, 'The Hammer',   0), (13, 'Wildcat',     0), (14, 'Devastator',  0), (15, 'The Butcher', 0),
(16, 'Bone Breaker', 0), (17, 'Predator',     0), (18, 'The Reaper',  0), (19, 'Bruiser',     0), (20, 'Thunderbolt', 0);

CREATE TABLE IF NOT EXISTS `cagewinners`
(
    `id`         int(11)     NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userid`     int(11)     NOT NULL DEFAULT 0,
    `monkeyname` varchar(20) NOT NULL DEFAULT '',
    KEY `userid` (`userid`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

COMMIT;
