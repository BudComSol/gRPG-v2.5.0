# Security Configuration Guide

This guide explains how to configure your web server to hide version information and improve security.

## Problem

By default, web servers and PHP disclose version information in HTTP headers:
- `Server: nginx/1.28.0` or `Server: Apache/2.4.x`
- `X-Powered-By: PHP/7.4.33`

This information can help attackers identify vulnerable versions and target specific exploits.

## Solutions

### For Apache with mod_php (recommended for most users)

The `.htaccess` file in the root directory already includes the necessary configuration:

```apache
# Hide server version information
Header always unset Server
Header always unset X-Powered-By

# Disable PHP version disclosure
php_flag expose_php off
```

No additional action is required if you're using Apache.

### For nginx

1. Copy the example configuration:
   ```bash
   cp nginx.conf.example /etc/nginx/sites-available/grpg
   ```

2. Edit the file to match your setup (server_name, root path, PHP socket)

3. Enable the site:
   ```bash
   ln -s /etc/nginx/sites-available/grpg /etc/nginx/sites-enabled/
   nginx -t
   systemctl reload nginx
   ```

The nginx configuration includes:
- `server_tokens off;` - Hides nginx version
- `fastcgi_hide_header X-Powered-By;` - Hides PHP version

### For PHP-FPM (all web servers)

Copy the PHP configuration file:
```bash
cp .user.ini.example .user.ini
```

The `.user.ini` file will be automatically loaded by PHP-FPM and includes:
- `expose_php = Off` - Prevents PHP from sending the X-Powered-By header

**Note:** Changes to `.user.ini` require PHP-FPM to be reloaded or may take up to 5 minutes to take effect (depending on `user_ini.cache_ttl` setting).

### Verification

After applying the changes, verify the headers are no longer exposed:

```bash
curl -I http://your-domain.com
```

You should NOT see:
- `Server:` header with version numbers
- `X-Powered-By:` header

## Additional Security Headers

The configuration also includes several security headers to protect against common attacks:

- `X-Frame-Options: SAMEORIGIN` - Prevents clickjacking
- `X-Content-Type-Options: nosniff` - Prevents MIME type sniffing
- `Strict-Transport-Security` - Enforces HTTPS
- `Content-Security-Policy` - Prevents XSS attacks

## Server-Level Configuration

For maximum security, you should also configure these settings at the server level:

### Apache (httpd.conf or apache2.conf)

```apache
ServerTokens Prod
ServerSignature Off
```

### nginx (nginx.conf)

```nginx
http {
    server_tokens off;
    more_clear_headers 'Server';
    more_clear_headers 'X-Powered-By';
}
```

Note: The `more_clear_headers` directive requires the `headers-more-nginx-module`.

### PHP (php.ini)

```ini
expose_php = Off
```

## Troubleshooting

**Problem:** Headers still showing after changes

**Solution:**
1. Clear browser cache
2. Restart web server: `systemctl restart nginx` or `systemctl restart apache2`
3. Restart PHP-FPM: `systemctl restart php-fpm` or `systemctl restart php7.4-fpm`
4. Check that mod_headers is enabled (Apache): `a2enmod headers`

**Problem:** 500 Internal Server Error after .htaccess changes

**Solution:**
1. Check that mod_headers is enabled: `a2enmod headers`
2. Verify Apache configuration: `apache2ctl configtest`
3. Check error logs: `/var/log/apache2/error.log`

## References

- [OWASP - Information Disclosure](https://owasp.org/www-community/vulnerabilities/Information_exposure_through_server_headers)
- [nginx security headers](https://nginx.org/en/docs/http/ngx_http_headers_module.html)
- [Apache mod_headers](https://httpd.apache.org/docs/2.4/mod/mod_headers.html)
- [PHP expose_php](https://www.php.net/manual/en/ini.core.php#ini.expose-php)
