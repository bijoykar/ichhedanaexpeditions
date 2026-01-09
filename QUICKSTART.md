# Ichhedana Expeditions - Quick Start Guide

## ⚡ Quick Installation (5 Minutes)

### Step 1: Setup Environment
Ensure you have:
- ✅ PHP 7.4+ installed
- ✅ MySQL 5.7+ installed
- ✅ Apache/Nginx web server running
- ✅ phpMyAdmin (optional, for easy database management)

### Step 2: Extract Files
Extract the project to your web server directory:
- **Windows (XAMPP)**: `C:\xampp\htdocs\ichhedanaexpeditions\`
- **Linux**: `/var/www/html/ichhedanaexpeditions/`
- **Mac (MAMP)**: `/Applications/MAMP/htdocs/ichhedanaexpeditions/`

### Step 3: Create Database
Open phpMyAdmin or MySQL command line:
```sql
CREATE DATABASE ichhedana_expeditions CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 4: Import Database
**Option A - phpMyAdmin:**
1. Select `ichhedana_expeditions` database
2. Click "Import" tab
3. Choose `database/schema.sql`
4. Click "Go"

**Option B - Command Line:**
```bash
mysql -u root -p ichhedana_expeditions < database/schema.sql
```

### Step 5: Configure Database
Edit `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'ichhedana_expeditions');
define('DB_USER', 'root');          // Your MySQL username
define('DB_PASS', '');              // Your MySQL password
define('SITE_URL', 'http://localhost/ichhedanaexpeditions');
```

### Step 6: Set Permissions (Linux/Mac only)
```bash
chmod 755 uploads/
chmod 755 uploads/tours/
chmod 755 uploads/destinations/
chmod 755 uploads/blog/
chmod 755 uploads/gallery/
chmod 755 uploads/reviews/
```

### Step 7: Access Website
**Frontend**: http://localhost/ichhedanaexpeditions/
**Admin Panel**: http://localhost/ichhedanaexpeditions/admin/

**Default Admin Login:**
- Username: `admin`
- Password: `admin123`

🔒 **IMPORTANT**: Change the admin password immediately after login!

---

## 🎯 First Steps After Installation

### 1. Change Admin Password
1. Login to admin panel
2. Go to Profile or Admin Users
3. Update password to something secure

### 2. Configure Site Settings
Go to Admin → Settings and update:
- Site name and tagline
- Contact information (phone, email, address)
- Social media URLs
- Email SMTP settings (for contact forms)

### 3. Add Your First Content

#### Add a Destination
1. Go to Admin → Destinations
2. Click "Add New Destination"
3. Fill in:
   - Name (e.g., "Sundarban National Park")
   - Region (e.g., "West Bengal")
   - Country (e.g., "India")
   - Description
   - Upload featured image
4. Click "Save"

#### Add a Tour
1. Go to Admin → Tours
2. Click "Add New Tour"
3. Fill in:
   - Title (e.g., "Sundarban Photography Tour")
   - Select destination
   - Start and end dates
   - Duration (nights/days)
   - Price
   - Description and itinerary
   - Upload images
4. Set status to "Published"
5. Click "Save"

#### Upload Gallery Images
1. Go to Admin → Gallery
2. Click "Upload Images"
3. Select multiple images
4. Add titles and descriptions
5. Categorize images
6. Click "Upload"

#### Create a Blog Post
1. Go to Admin → Blog Posts
2. Click "Add New Post"
3. Fill in:
   - Title
   - Category (e.g., "Photography Tips")
   - Content
   - Upload featured image
4. Set status to "Published"
5. Click "Publish"

#### Add Customer Reviews
1. Go to Admin → Reviews
2. Click "Add Review"
3. Fill in customer details
4. Select tour (optional)
5. Rating (1-5 stars)
6. Review text
7. Approve and save

### 4. Test Contact Form
1. Visit the contact page
2. Fill in the form
3. Submit
4. Check Admin → Messages to see the submission

---

## 📱 Mobile Testing

Test your website on mobile:
1. On same network, find your computer's IP address
2. Access from mobile: `http://YOUR-IP/ichhedanaexpeditions/`
3. Test navigation, forms, and gallery

---

## 🎨 Quick Customization

### Change Colors
Edit `assets/css/style.css`:
```css
:root {
    --primary-color: #2c5f2d;      /* Main green color */
    --secondary-color: #8b4513;    /* Brown accent */
    --accent-color: #ffa500;       /* Orange highlights */
}
```

### Update Logo
Replace logo in:
- `assets/images/logo.png`
- Update in `includes/header.php`

### Change Homepage Hero Image
Replace or add images in:
- `assets/images/hero/hero1.jpg`

---

## 🚨 Common Issues & Quick Fixes

### "Database Connection Error"
✅ Check `config/config.php` credentials
✅ Verify MySQL is running
✅ Test database connection in phpMyAdmin

### "Images Not Uploading"
✅ Check folder permissions (755 or 777)
✅ Verify PHP upload settings in php.ini:
```ini
upload_max_filesize = 20M
post_max_size = 25M
```

### "Page Not Found" (404)
✅ Check if mod_rewrite is enabled (Apache)
✅ Verify .htaccess file exists
✅ Update SITE_URL in config.php

### "Blank Page" or Errors
✅ Enable error display in `config/config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```
✅ Check PHP error logs

### "Admin Login Not Working"
✅ Clear browser cache and cookies
✅ Try different browser
✅ Verify database was imported correctly
✅ Check admin_users table has default user

---

## 📚 Next Steps

1. **Read Full Documentation**:
   - [INSTALLATION.md](INSTALLATION.md) - Complete installation guide
   - [DEVELOPER_GUIDE.md](DEVELOPER_GUIDE.md) - Developer documentation
   - [SITEMAP_URLS.md](SITEMAP_URLS.md) - URL structure

2. **Customize Design**:
   - Modify CSS in `assets/css/style.css`
   - Update colors and fonts
   - Add your branding

3. **Add More Content**:
   - Upload more tours
   - Add blog posts for SEO
   - Build photo gallery
   - Collect customer reviews

4. **Configure Email**:
   - Setup SMTP in config.php
   - Test contact form emails
   - Configure newsletter

5. **Optimize for Production**:
   - Disable error display
   - Enable HTTPS
   - Setup backups
   - Optimize images
   - Configure caching

---

## 🆘 Need Help?

### Support Channels
- **Email**: ichhedanaexpeditions@gmail.com
- **Phone**: 9007820752
- **Check Documentation**: All .md files in project root

### Useful Commands

**Backup Database:**
```bash
mysqldump -u root -p ichhedana_expeditions > backup.sql
```

**Restore Database:**
```bash
mysql -u root -p ichhedana_expeditions < backup.sql
```

**Check PHP Version:**
```bash
php -v
```

**Check Apache Status:**
```bash
# Linux
sudo service apache2 status

# Windows (in Services)
# Look for "Apache" service
```

---

## ✅ Success Checklist

After setup, verify:
- [ ] Homepage loads correctly
- [ ] Admin panel accessible
- [ ] Can login with admin credentials
- [ ] Can create a tour
- [ ] Can upload images
- [ ] Contact form works
- [ ] Mobile responsive
- [ ] All pages accessible
- [ ] Images display correctly
- [ ] Navigation works

---

**Installation Time**: ~5 minutes
**First Content**: ~15 minutes
**Full Setup**: ~30 minutes

Ready to launch! 🚀

**Last Updated**: January 8, 2026
