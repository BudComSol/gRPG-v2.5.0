-- Migration: Allow NULL userid in tickets_responses for system messages
-- Existing installations should run this to fix the foreign key constraint
-- violation that occurs when admins respond to tickets.

-- Convert legacy sentinel value 0 to NULL before altering the column
UPDATE `tickets_responses` SET `userid` = NULL WHERE `userid` = 0;

ALTER TABLE `tickets_responses`
    MODIFY COLUMN `userid` INT(11) NULL;
