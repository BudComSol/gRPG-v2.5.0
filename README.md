# gRPG v2
PHP, MySQL, HTML, CSS.
Text-based online RPG

### How do I get set up? ###

* Upload the files to your public_html (or htdocs/www)
* Create a MySQL database and user
* Go to the URL of wherever you just uploaded them - go through the installer and fill in the details
* Login

**Note:** All dependencies are included locally - no need for Composer or other package managers!

### Error Logging ###

The application includes a centralized error logging system to help track and diagnose issues:

* **Log Location:** All errors are logged to `logs/error.log`
* **Security:** The logs directory is protected by `.htaccess` to prevent web access
* **Log Rotation:** Logs are automatically rotated when they reach 10MB (configurable in `inc/dbcon.php`)
* **Usage:** Use the logging functions in your code:
  * `log_error($message, $level, $context)` - Log general errors
  * `log_database_error($message, $context)` - Log database-related errors
  * `log_security_issue($message, $context)` - Log security issues
  * `log_warning($message, $context)` - Log warnings
  * `log_info($message, $context)` - Log informational messages

Each log entry includes timestamp, severity level, IP address, user ID, request URI, and optional context data.

### I found a bug! ###
If you're able to repair it, please do and submit the fix back. If not, please notify us

### I want to contribute to the code ###
Awesome! Simply fork the repo, make your changes/additions, and submit a pull request!