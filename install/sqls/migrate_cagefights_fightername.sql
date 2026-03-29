-- Migration: Add fightername column to luckyboxes table
-- Run this on existing installations that already have the base schema installed.

ALTER TABLE `luckyboxes`
    ADD COLUMN IF NOT EXISTS `fightername` varchar(20) NOT NULL DEFAULT '' AFTER `boxnumber`;

UPDATE `luckyboxes` SET `fightername` = CASE `boxnumber`
    WHEN '1'  THEN 'Iron Fist'
    WHEN '2'  THEN 'The Viper'
    WHEN '3'  THEN 'Mad Dog'
    WHEN '4'  THEN 'Knuckles'
    WHEN '5'  THEN 'Razor'
    WHEN '6'  THEN 'The Crusher'
    WHEN '7'  THEN 'Shadow'
    WHEN '8'  THEN 'Brawler'
    WHEN '9'  THEN 'The Beast'
    WHEN '10' THEN 'Stone Cold'
    WHEN '11' THEN 'Rampage'
    WHEN '12' THEN 'The Hammer'
    WHEN '13' THEN 'Wildcat'
    WHEN '14' THEN 'Devastator'
    WHEN '15' THEN 'The Butcher'
    WHEN '16' THEN 'Bone Breaker'
    WHEN '17' THEN 'Predator'
    WHEN '18' THEN 'The Reaper'
    WHEN '19' THEN 'Bruiser'
    WHEN '20' THEN 'Thunderbolt'
    ELSE `fightername`
END
WHERE `boxnumber` IN ('1','2','3','4','5','6','7','8','9','10',
                      '11','12','13','14','15','16','17','18','19','20');
