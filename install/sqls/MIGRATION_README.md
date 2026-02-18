# Forum Board Migration

## Purpose
This migration updates the forum board structure to move the "Announcements" board from the staff-only section to the public section, making it visible to all users at the top of the forum list.

## Who needs this migration?
- **Existing installations**: If you installed gRPG before this change, you should run this migration to update your database.
- **New installations**: This change is already included in the default installation, so you don't need to run this migration.

## How to apply the migration

### Method 1: Using phpMyAdmin or similar tool
1. Log into your database management tool (phpMyAdmin, Adminer, etc.)
2. Select your gRPG database
3. Go to the SQL tab
4. Copy the contents of `migrate_announcements_board.sql`
5. Paste and execute the SQL

### Method 2: Using MySQL command line
```bash
mysql -u your_username -p your_database_name < install/sqls/migrate_announcements_board.sql
```

### Method 3: Manual update via Admin Control Panel
1. Log in as an administrator
2. Go to the Staff Control Panel (`plugins/control.php`)
3. Navigate to Forum Boards management
4. Edit the "Announcements" board
5. Change the board type from "Staff" to "Public"
6. Save changes

## What this migration does
- Changes the `fb_auth` field of the "Announcements" board from 'staff' to 'public'
- This makes the Announcements board visible to all users, not just staff members
- The board will now appear in the main forum section instead of the "Staff Boards" section

## Note on board ordering
Board ordering is based on the `fb_id` (auto-increment) field. New installations will have Announcements as the first board. For existing installations, if you want to change the order, you can:
1. Manually update the `fb_id` values in the database (advanced users only)
2. Delete and recreate boards in your desired order via the admin panel
3. Modify the SQL query in `forum.php` to add custom ordering (e.g., `ORDER BY fb_name` or custom logic)
