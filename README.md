# Ichhedana Expeditions Website

## Overview
A complete PHP/MySQL website for Ichhedana Expeditions - a wildlife photography tour operator based in Kolkata, India. The website serves as a comprehensive booking and information platform for photography enthusiasts seeking guided expeditions to national parks and wildlife destinations.

## 🌟 Key Features

### Frontend Features
- **Responsive Design**: Fully responsive for desktop, tablet, and mobile devices
- **Modern UI/UX**: Clean, professional design with smooth animations
- **Photography Tours**: Showcase of 2025 tour packages with detailed information
- **Destination Gallery**: Interactive destination cards with rich information
- **Photo Gallery**: Beautiful masonry grid with lightbox view
- **Blog Section**: SEO-optimized blog for content marketing
- **Customer Reviews**: Star ratings and testimonials
- **Contact System**: Modal contact form with AJAX submission
- **Social Integration**: Links to Facebook and Instagram profiles

### Admin Panel Features
- **Secure Authentication**: Login system with password hashing
- **Dashboard**: Statistics overview and recent activity
- **Content Management**:
  - Tours: Add/Edit/Delete tour packages
  - Destinations: Manage destination information
  - Blog Posts: Create and publish articles
  - Gallery: Upload and organize images
  - Reviews: Approve and manage customer testimonials
- **Customer Management**:
  - View contact messages
  - Manage tour bookings
  - Newsletter subscribers
- **Settings**: Site configuration and user management
- **Image Management**: Upload and resize images automatically

## 📋 Website Sections

Based on the original website analysis (https://ichhedanaexpeditions.com/):

Based on the original website analysis (https://ichhedanaexpeditions.com/):

### 1. **Tour Management System**
- Photography Tours Catalog (2025)
- Individual tour detail pages with booking information
- Tour dates, durations, and pricing
- Featured tours showcase
- Upcoming tours section

### 2. **Destination Directory**
- Top Destinations Showcase
- Individual destination detail pages
- Climate and best visiting time information
- Wildlife and photography opportunities
- Available tour packages per destination

### 3. **Gallery**
- Photo gallery showcasing previous expeditions
- Category-based filtering
- Lightbox view with image details
- Visual portfolio of wildlife and nature photography

### 4. **Blog Section**
- Recent blog posts display
- Category-wise organization
- SEO-optimized content
- Content marketing and expedition stories

### 5. **Customer Reviews & Testimonials**
- Star ratings (★★★★★)
- Detailed testimonials
- Tour-specific reviews
- Featured reviews on homepage

### 6. **Contact & Communication**
- Contact Form with AJAX submission
- 24/7 phone support: 9007820752
- Email: ichhedanaexpeditions@gmail.com
- Location: Kolkata, India
- Success confirmation messages

### 7. **Social Media Integration**
- Facebook profile: https://www.facebook.com/profile.php?id=100063782000455
- Facebook group: https://www.facebook.com/groups/2010223942443396/
- Instagram: https://www.instagram.com/ichhedanaexpeditions/

### 8. **Navigation & User Experience**
- Sticky header with smooth scrolling
- Mobile-responsive navigation
- Breadcrumb navigation
- Search functionality
- SEO-friendly URLs

### 9. **Legal & Compliance**
- Privacy Policy page
- Sitemap (HTML and XML)
- Cookie consent (optional)
- Terms and conditions

### 10. **Additional Features**
- Newsletter subscription
- Tour booking system
- Custom tour inquiries
- Scroll to top button
- Image lazy loading
- Social sharing

## 💻 Technology Stack

- **Backend**: PHP 7.4+ with OOP principles
- **Database**: MySQL 5.7+ / MariaDB 10.2+
- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **Libraries**: 
  - jQuery 3.7.0 for DOM manipulation
  - Font Awesome 6.4.0 for icons
  - Google Fonts (Poppins, Playfair Display)
- **Architecture**: MVC-inspired structure with Models and Controllers
- **Security**: PDO prepared statements, CSRF protection, input sanitization

## 📁 Project Structure

```
ichhedanaexpeditions/
├── admin/                     # Admin panel
├── assets/                    # CSS, JS, images
├── config/                    # Configuration files
├── database/                  # Database schema
├── includes/                  # PHP classes and utilities
├── uploads/                   # User-uploaded files
├── index.php                  # Homepage
├── tours.php                  # Tours listing
├── destinations.php           # Destinations listing
├── gallery.php                # Photo gallery
├── blogs.php                  # Blog listing
├── reviews.php                # Customer reviews
├── contact.php                # Contact page
├── README.md                  # This file
├── INSTALLATION.md            # Installation guide
└── DEVELOPER_GUIDE.md         # Developer documentation
```

## 🚀 Quick Start

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (optional)

### Installation

1. **Clone or download the project**
   ```bash
   cd /var/www/html/
   # or extract ZIP file
   ```

2. **Create database**
   ```sql
   CREATE DATABASE ichhedana_expeditions CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. **Import database schema**
   ```bash
   mysql -u root -p ichhedana_expeditions < database/schema.sql
   ```

4. **Configure settings**
   Edit `config/config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'ichhedana_expeditions');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('SITE_URL', 'http://localhost/ichhedanaexpeditions');
   ```

5. **Set permissions**
   ```bash
   chmod 755 uploads/
   chmod 755 uploads/*
   ```

6. **Access the website**
   - Frontend: `http://localhost/ichhedanaexpeditions/`
   - Admin: `http://localhost/ichhedanaexpeditions/admin/`
   - Default login: `admin` / `admin123`

For detailed installation instructions, see [INSTALLATION.md](INSTALLATION.md)

## 🔐 Admin Panel

### Default Credentials
- **Username**: admin
- **Password**: admin123
- **⚠️ IMPORTANT**: Change password immediately after first login!

### Admin Features
- Dashboard with statistics
- Tour management (CRUD operations)
- Destination management
- Blog post creation and editing
- Gallery image upload with auto-resize
- Review moderation
- Contact message management
- Booking management
- Site settings configuration
- User management

## 📱 Responsive Design

The website is fully responsive and tested on:
- **Desktop**: 1920px, 1600px, 1366px, 1280px
- **Laptop**: 1024px
- **Tablet**: 768px (iPad, Android tablets)
- **Mobile**: 480px, 375px, 320px (iPhone, Android phones)

## 🎨 Design Features

- Modern, clean interface
- Wildlife/nature color scheme (greens, browns, oranges)
- Professional typography
- Smooth animations and transitions
- Touch-friendly buttons and navigation
- High-quality image placeholders
- Accessible design (WCAG compliant)

## 🔒 Security Features

1. **Input Validation**: All user inputs sanitized
2. **SQL Injection Prevention**: PDO prepared statements
3. **XSS Protection**: HTML entity encoding
4. **CSRF Protection**: Token validation
5. **Password Security**: BCrypt hashing
6. **Session Security**: Secure session handling
7. **File Upload Security**: Type and size validation
8. **Error Handling**: Proper error logging

## 📊 Database Schema

Key tables:
- `admin_users` - Admin accounts
- `tours` - Tour packages
- `destinations` - Destination information
- `blog_posts` - Blog articles
- `gallery` - Photo gallery
- `reviews` - Customer testimonials
- `contact_messages` - Contact form submissions
- `tour_bookings` - Booking requests
- `site_settings` - Configuration

See [DEVELOPER_GUIDE.md](DEVELOPER_GUIDE.md) for complete schema documentation.

## 🛠️ Customization

### Change Colors
Edit `assets/css/style.css`:
```css
:root {
    --primary-color: #2c5f2d;      /* Change primary color */
    --secondary-color: #8b4513;    /* Change secondary color */
    --accent-color: #ffa500;       /* Change accent color */
}
```

### Add New Pages
1. Create PHP file in root directory
2. Include `includes/header.php`
3. Add your content
4. Include `includes/footer.php`
5. Update navigation in header

### Modify Email Settings
Edit `config/config.php` for SMTP configuration

## 📈 SEO Features

- SEO-friendly URLs (slugs)
- Meta tags (title, description, keywords)
- Open Graph tags for social sharing
- XML sitemap generation
- Robots.txt
- Schema.org markup
- Fast page load times
- Mobile-first indexing ready

## 🌐 Browser Support

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ✅ Mobile browsers (iOS, Android)

## 📝 Documentation

- [INSTALLATION.md](INSTALLATION.md) - Detailed installation guide
- [DEVELOPER_GUIDE.md](DEVELOPER_GUIDE.md) - Developer documentation
- [SITEMAP_URLS.md](SITEMAP_URLS.md) - Complete URL structure

## 🐛 Troubleshooting

### Common Issues

**Database Connection Error**
- Check credentials in `config/config.php`
- Verify MySQL service is running

**Images Not Uploading**
- Check directory permissions (755)
- Verify PHP upload settings

**404 Errors**
- Enable mod_rewrite (Apache)
- Check .htaccess configuration

See [INSTALLATION.md](INSTALLATION.md) for more troubleshooting tips.

## 🔄 Updates & Maintenance

- Regular database backups recommended
- Keep PHP and MySQL updated
- Update tour dates and content regularly
- Monitor contact form submissions
- Moderate reviews promptly
- Update blog regularly for SEO

## 📞 Support & Contact

- **Phone**: 9007820752 (24/7 support)
- **Email**: ichhedanaexpeditions@gmail.com
- **Location**: Kolkata, India
- **Website**: https://ichhedanaexpeditions.com/

## 📄 License

© 2026 Ichhedana Expeditions. All rights reserved.

---

## 🎯 Business Model
- **Service Type**: Wildlife photography tours and expeditions
- **Target Audience**: Photography enthusiasts, wildlife photographers, nature lovers
- **Geographic Focus**: India (National Parks) and neighboring countries (Bhutan)
- **Unique Selling Proposition**: Tours led by wildlife photographer with expert knowledge

## Technical Features
- Contact form with success messaging
- Dynamic tour listings with dates
- Image gallery system
- Blog content management
- Customer review system
- Social media integration
- Mobile-responsive design

## Support Services
- 24/7 phone support
- Email inquiry system
- Quick response promise for contact form submissions

---

**Last Updated**: January 8, 2026
**Version**: 1.0.0
**Website**: https://ichhedanaexpeditions.com/
