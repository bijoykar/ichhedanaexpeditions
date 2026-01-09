# Ichhedana Expeditions Website - Installation Guide

## System Requirements

### Server Requirements
- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher (or MariaDB 10.2+)
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP Extensions**:
  - PDO
  - PDO_MySQL
  - GD (for image processing)
  - mbstring
  - fileinfo
  - json

### Recommended Server Configuration
- PHP Memory Limit: 256MB or higher
- Max Upload File Size: 20MB
- Max Execution Time: 60 seconds
- Allow URL fopen: On

## Installation Steps

### 1. Download and Extract Files

Extract all files to your web server's document root or a subdirectory:
```
/var/www/html/ichhedanaexpeditions/
```
or
```
C:\xampp\htdocs\ichhedanaexpeditions\
```

### 2. Create Database

Open phpMyAdmin or MySQL command line and create a new database:

```sql
CREATE DATABASE ichhedana_expeditions CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Import Database Schema

Import the database schema file:

```bash
mysql -u root -p ichhedana_expeditions < database/schema.sql
```

Or through phpMyAdmin:
1. Select the `ichhedana_expeditions` database
2. Go to "Import" tab
3. Choose `database/schema.sql`
4. Click "Go"

### 4. Configure Database Connection

Edit `config/config.php` and update the database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'ichhedana_expeditions');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 5. Update Site URL

In `config/config.php`, update the SITE_URL:

```php
define('SITE_URL', 'http://localhost/ichhedanaexpeditions');
// or
define('SITE_URL', 'https://yourdomain.com');
```

### 6. Set Directory Permissions

Set proper permissions for upload directories:

**On Linux/Unix:**
```bash
chmod 755 uploads/
chmod 755 uploads/tours/
chmod 755 uploads/destinations/
chmod 755 uploads/blog/
chmod 755 uploads/gallery/
chmod 755 uploads/reviews/
```

**On Windows:** Ensure the web server user has write permissions to these folders.

### 7. Create Upload Directories

Create the following directories if they don't exist:

```
uploads/
  ├── tours/
  ├── destinations/
  ├── blog/
  ├── gallery/
  └── reviews/
```

### 8. Configure .htaccess (Apache)

If using Apache, ensure mod_rewrite is enabled and create/verify `.htaccess`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /ichhedanaexpeditions/
    
    # Remove .php extension
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME}\.php -f
    RewriteRule ^(.*)$ $1.php [L]
    
    # Protect config files
    <FilesMatch "^(config|database)\.php$">
        Order allow,deny
        Deny from all
    </FilesMatch>
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>
```

### 9. Test Installation

1. Visit: `http://localhost/ichhedanaexpeditions/` (or your domain)
2. You should see the homepage

### 10. Access Admin Panel

1. Visit: `http://localhost/ichhedanaexpeditions/admin/login.php`
2. Default credentials:
   - **Username**: `admin`
   - **Password**: `admin123`
3. **IMPORTANT**: Change the default password immediately!

## Post-Installation Configuration

### Change Admin Password

1. Login to admin panel
2. Go to "Profile" or "Admin Users"
3. Change the default password to a strong one

### Configure Site Settings

1. Go to Admin Panel → Settings
2. Update:
   - Site name and tagline
   - Contact information
   - Social media URLs
   - Email settings (for contact forms)

### Upload Initial Content

1. **Destinations**: Add your tour destinations
2. **Tours**: Create tour packages
3. **Gallery**: Upload photography samples
4. **Blog Posts**: Add content for SEO
5. **Reviews**: Add customer testimonials

### Email Configuration (Optional)

For contact form emails, configure SMTP in `config/config.php`:

```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('SMTP_ENCRYPTION', 'tls');
```

**For Gmail:**
1. Enable 2-factor authentication
2. Generate an App Password
3. Use the App Password in config

## Troubleshooting

### Database Connection Error
- Verify database credentials in `config/config.php`
- Check if MySQL service is running
- Ensure database user has proper privileges

### File Upload Issues
- Check directory permissions (755 or 777)
- Verify `upload_max_filesize` in php.ini
- Check `post_max_size` in php.ini

### Images Not Displaying
- Verify upload directory paths
- Check file permissions
- Ensure GD extension is enabled

### 404 Errors
- Check .htaccess configuration
- Verify mod_rewrite is enabled (Apache)
- Update SITE_URL in config.php

### Session Issues
- Check session directory permissions
- Verify session.save_path in php.ini

## Security Recommendations

### Production Environment

1. **Disable Error Display**:
```php
error_reporting(0);
ini_set('display_errors', 0);
```

2. **Enable HTTPS**:
   - Install SSL certificate
   - Update SITE_URL to https://

3. **Database Security**:
   - Use strong database passwords
   - Restrict database user privileges
   - Don't use 'root' user

4. **File Permissions**:
   - Set files to 644
   - Set directories to 755
   - Never use 777 in production

5. **Backup**:
   - Setup regular database backups
   - Backup upload directories
   - Keep backup off-server

6. **Update Regularly**:
   - Keep PHP updated
   - Update dependencies
   - Monitor security advisories

## Server Configuration Examples

### Apache Virtual Host

```apache
<VirtualHost *:80>
    ServerName ichhedanaexpeditions.com
    ServerAlias www.ichhedanaexpeditions.com
    DocumentRoot /var/www/ichhedanaexpeditions
    
    <Directory /var/www/ichhedanaexpeditions>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/ichhedana-error.log
    CustomLog ${APACHE_LOG_DIR}/ichhedana-access.log combined
</VirtualHost>
```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name ichhedanaexpeditions.com www.ichhedanaexpeditions.com;
    root /var/www/ichhedanaexpeditions;
    index index.php index.html;
    
    location / {
        try_files $uri $uri/ $uri.php?$query_string;
    }
    
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
    
    location ~ /\. {
        deny all;
    }
}
```

## Support

For issues or questions:
- Email: ichhedanaexpeditions@gmail.com
- Phone: 9007820752

## License

© 2026 Ichhedana Expeditions. All rights reserved.
