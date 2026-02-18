-- Migration to move Announcements board from staff to public section
-- This migration updates existing installations to match the new default configuration
-- Run this SQL script on your database if you have an existing installation

-- Update the Announcements board to be public instead of staff
UPDATE `forum_boards` 
SET `fb_auth` = 'public' 
WHERE `fb_name` = 'Announcements' AND `fb_auth` = 'staff';

-- Note: Board ordering is based on fb_id (auto-increment)
-- If you want Announcements to appear at the top, you would need to manually
-- reorder the fb_id values or use ORDER BY in queries
