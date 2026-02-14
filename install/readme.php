<?php
declare(strict_types=1);

require_once __DIR__.'/header.php';
?>
<div class="header">
	<h1>gRPG v2 Installation Guide</h1>
	<h2>How to Install</h2>
</div>
<div class="content">
	<h2 class="content-subhead">Prerequisites</h2>
	<ul>
		<li><strong>PHP 7.4 or higher - works with 8.0+</strong> - Required for running the game</li>
		<li><strong>MySQL Database</strong> - For storing game data</li>
		<li><strong>Web Server</strong> - Apache, Nginx, or similar with PHP support</li>
		<li><strong>Write Permissions</strong> - The web server must be able to write to the <code>/.env</code> file</li>
	</ul>

	<h2 class="content-subhead">Installation Steps</h2>
	
	<h3>Step 1: Upload Files</h3>
	<p>
		Upload all files to your web server's public directory (usually <code>public_html</code>, <code>htdocs</code>, or <code>www</code>).
		You can use FTP, SFTP, or your hosting control panel's file manager.
	</p>

	<h3>Step 2: Create MySQL Database</h3>
	<p>
		Before running the installer, you need to create a MySQL database and user:
	</p>
	<ol>
		<li>Log in to your hosting control panel (cPanel, Plesk, etc.) or use phpMyAdmin</li>
		<li>Create a new MySQL database</li>
		<li>Create a MySQL user with a secure password</li>
		<li>Grant the user all privileges on the database</li>
		<li>Note down the database name, username, and password - you'll need these for the installer</li>
	</ol>

	<h3>Step 3: Set File Permissions</h3>
	<p>
		Ensure that the <code>/.env</code> file (or the root directory if <code>.env</code> doesn't exist yet) is writable by the web server.
		The installer will attempt to create and write to this file.
	</p>
	<pre>chmod 644 .env</pre>
	<p>Or if the file doesn't exist yet:</p>
	<pre>chmod 755 .</pre>

	<h3>Step 4: Access the Installer</h3>
	<p>
		Navigate to the game's URL in your web browser. You should see this installer interface.
		The installer will guide you through the remaining steps:
	</p>
	<ol>
		<li><strong>System Check</strong> - Verifies PHP version, SQL file existence, and game directory</li>
		<li><strong>Database Configuration</strong> - Enter your MySQL database details and timezone</li>
		<li><strong>Database Installation</strong> - Automatically imports the database schema</li>
		<li><strong>Admin Account Creation</strong> - Create your game administrator account</li>
		<li><strong>Composer (Optional)</strong> - If available, updates PHP dependencies</li>
		<li><strong>Cleanup</strong> - Option to remove the installation directory for security</li>
	</ol>

	<h3>Step 5: Complete Installation</h3>
	<p>
		Follow the on-screen instructions in the installer. Each step provides detailed information about what's required.
		After completion, you should remove the <code>/install</code> directory for security purposes.
	</p>

	<h2 class="content-subhead">Post-Installation</h2>
	<ul>
		<li><strong>Remove Install Directory</strong> - Delete or rename the <code>/install</code> folder to prevent unauthorized access</li>
		<li><strong>Login</strong> - Use the admin credentials you created during installation</li>
		<li><strong>Configure Game Settings</strong> - Customize your game through the admin panel</li>
		<li><strong>Backup</strong> - Regularly backup your database and <code>/.env</code> file</li>
	</ul>

	<h2 class="content-subhead">Troubleshooting</h2>
	<h3>Common Issues:</h3>
	<dl>
		<dt><strong>Cannot write to .env file</strong></dt>
		<dd>Make sure the file or directory has proper write permissions. You may need to manually create the file or adjust permissions using FTP or SSH.</dd>
		
		<dt><strong>Database connection failed</strong></dt>
		<dd>Verify your database credentials are correct. Check that your database user has the necessary privileges and that the database exists.</dd>
		
		<dt><strong>PHP version too old</strong></dt>
		<dd>This game requires PHP 7.4 or higher. Contact your hosting provider to upgrade PHP or consider switching to a host that supports newer PHP versions.</dd>
		
		<dt><strong>SQL file not found</strong></dt>
		<dd>Ensure all files were uploaded correctly, particularly the <code>/install/sqls/grpg-pdo.sql</code> file.</dd>
	</dl>

	<h2 class="content-subhead">Need Help?</h2>
	<p>
		If you encounter any issues during installation:
	</p>
	<ul>
		<li>Check the <a href="https://github.com/BudComSol/GRPG-v2" target="_blank">GitHub repository</a> for documentation and known issues</li>
		<li>Contact support at <a href="mailto:support@thegrpg.com">support@thegrpg.com</a></li>
		<li>Submit an issue on GitHub if you've found a bug</li>
	</ul>

	<h2 class="content-subhead">Ready to Begin?</h2>
	<p>
		Once you've completed the prerequisites, click on <strong>"Install"</strong> in the menu to start the installation process.
	</p>
</div>
