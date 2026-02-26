# gRPG v2.5.0
Full Stack - PHP, MySQL, HTML, CSS.
Text-based online RPG-MMORPG-PBBG script.

### Credits ###

* We acknowlege copyright, the original author and everyone who has had a hand in this scripts development, there are many names and you know who you are, thank you all, we are grateful for your contributions and hope you have something more to add going forward.

### Why Persist With Legacy Code ###

* Because we can, and by the way, you get the same files here as the demo site demonstrates.
* Because it does not deserve to be thrown on the scrap heap and should be preserved for posterity.
* The codebase has been around for a long time with many people contributing, which is reflected in the different coding styles found about the place.
* This is an attempt to update this codebase with some uniformity without resorting to frameworks 'cause I like old stuff and frameworks not so much.

### How Do I Get Setup? ###

* Upload the files to your public_html (or htdocs/www)
* Create a MySQL database and user
* Go to the URL of wherever you just uploaded them - go through the installer and fill in the details
* Login

**Note:** All dependencies are now included locally - no need for Composer, Docker or other package managers, making it much simpler to install in shared hosting, particularly with the easy peasy installer!

### PayPal Implementation ###

* You add your details in the .env file found in the root directory.
* PAYPAL_ADDRESS="you@yourpaypal.com".
* PAYPAL_CLIENT_ID=""   # get from developer.paypal.com/dashboard/applications/.
* RMSTORE_CURRENCY="USD".
* RMSTORE_LOCALE="en_US".
* RMSTORE_DISCOUNT="0".
* RMSTORE_BOGOF="false".

**Note:** You will need to configure your IPN URL once only in your PayPal account here: PayPal account → Profile → Selling tools → Instant Payment Notifications → Edit → set URL to https://yourgame.com/ipn/notify.php.

### Game Administration ###

After installation, administrators can manage game content through the Staff Control Panel:

* **Access:** Admin accounts have access to the control panel at `plugins/control.php`
* **Manage Crimes:** Add, edit, or delete crimes with custom messages and difficulty levels
* **Manage Cities:** Configure cities, land prices, and level requirements
* **Manage Items:** Add custom items, weapons, and equipment
* **Manage Jobs:** Create jobs with various pay rates and requirements
* **Player Management:** Edit player stats, ban users, manage referrals
* **And More:** Cars, houses, forum categories, voting sites, and RM store options

The game comes with default content including 7 sample crimes ranging from Pickpocket to Bank Robbery. Administrators can customize all messages and add new content as needed.

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

### Security Configuration ###

The application includes security hardening configurations to protect against common vulnerabilities:

* **Server Version Hiding:** Configuration files are provided to hide server and PHP version information
* **Security Headers:** Multiple security headers are configured (CSP, HSTS, X-Frame-Options, etc.)
* **Sensitive File Protection:** `.env` files and logs are protected from web access

See [SECURITY-CONFIG.md](SECURITY-CONFIG.md) for detailed security configuration instructions for Apache, nginx, and PHP.

### I Found A Bug! ###

It's a work in progress, if you're able to repair it, please do and submit the fix back. If not, please notify us.

### I Want To Contribute To The Code ###

Awesome! Simply fork the repo, make your changes/additions, and submit a pull request!
