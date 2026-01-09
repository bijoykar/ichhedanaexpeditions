# Ichhedana Expeditions Website - Developer Documentation

## Project Overview

A comprehensive PHP/MySQL website for wildlife photography tours with admin panel for content management.

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+ / MariaDB 10.2+
- **Frontend**: HTML5, CSS3, JavaScript
- **Libraries**: jQuery 3.7.0
- **Icons**: Font Awesome 6.4.0
- **Fonts**: Google Fonts (Poppins, Playfair Display)

## Project Structure

```
ichhedanaexpeditions/
├── admin/                      # Admin panel
│   ├── includes/              # Admin includes
│   │   ├── admin-header.php
│   │   └── admin-footer.php
│   ├── index.php              # Admin dashboard
│   ├── login.php              # Admin login
│   ├── logout.php             # Logout handler
│   ├── tours.php              # Tour management
│   ├── destinations.php       # Destination management
│   ├── blog-posts.php         # Blog management
│   ├── gallery.php            # Gallery management
│   ├── reviews.php            # Review management
│   ├── messages.php           # Contact messages
│   └── settings.php           # Site settings
├── assets/                    # Static assets
│   ├── css/
│   │   ├── style.css         # Main stylesheet
│   │   ├── responsive.css    # Responsive styles
│   │   └── admin.css         # Admin panel styles
│   ├── js/
│   │   ├── main.js           # Main JavaScript
│   │   └── admin.js          # Admin JavaScript
│   └── images/               # Static images
├── config/                    # Configuration
│   ├── config.php            # Main configuration
│   └── database.php          # Database connection
├── database/                  # Database files
│   └── schema.sql            # Database schema
├── includes/                  # Shared includes
│   ├── classes/              # PHP classes
│   │   ├── Model.php         # Base model
│   │   ├── Tour.php          # Tour model
│   │   ├── Destination.php   # Destination model
│   │   ├── BlogPost.php      # Blog post model
│   │   ├── Gallery.php       # Gallery model
│   │   └── Review.php        # Review model
│   ├── header.php            # Frontend header
│   ├── footer.php            # Frontend footer
│   └── functions.php         # Utility functions
├── uploads/                   # Upload directory
│   ├── tours/
│   ├── destinations/
│   ├── blog/
│   ├── gallery/
│   └── reviews/
├── index.php                  # Homepage
├── tours.php                  # Tours listing
├── tour-details.php           # Tour details page
├── destinations.php           # Destinations listing
├── destination-details.php    # Destination details
├── blogs.php                  # Blog listing
├── blog-details.php           # Blog post details
├── gallery.php                # Gallery page
├── reviews.php                # Reviews page
├── about.php                  # About page
├── contact.php                # Contact page
├── custom-tours.php           # Custom tours page
├── privacy-policy.php         # Privacy policy
├── sitemap.php                # HTML sitemap
├── README.md                  # Project readme
└── INSTALLATION.md            # Installation guide
```

## Database Schema

### Core Tables

1. **admin_users** - Admin user accounts
2. **destinations** - Tour destinations
3. **tours** - Photography tour packages
4. **blog_posts** - Blog articles
5. **gallery** - Photo gallery
6. **reviews** - Customer testimonials
7. **contact_messages** - Contact form submissions
8. **site_settings** - Site configuration
9. **newsletter_subscribers** - Newsletter emails
10. **tour_bookings** - Tour booking requests

## PHP Classes

### Model.php (Base Model)
Base class with CRUD operations:
- `find($id)` - Find by ID
- `findBy($column, $value)` - Find by column
- `all()` - Get all records
- `where($conditions)` - Get with conditions
- `insert($data)` - Insert record
- `update($id, $data)` - Update record
- `delete($id)` - Delete record
- `paginate()` - Paginated results

### Tour.php
Tour-specific methods:
- `getPublished()` - Get published tours
- `getFeatured()` - Get featured tours
- `getUpcoming()` - Get upcoming tours
- `getBySlug($slug)` - Get by slug
- `getByDestination($id)` - Get by destination
- `search($keyword)` - Search tours

### Destination.php
Destination methods:
- `getPublished()` - Published destinations
- `getFeatured()` - Featured destinations
- `getBySlug($slug)` - Get by slug
- `getByCountry($country)` - By country
- `getWithTourCount()` - With tour count

### BlogPost.php
Blog methods:
- `getPublished()` - Published posts
- `getFeatured()` - Featured posts
- `getBySlug($slug)` - Get by slug
- `getByCategory($category)` - By category
- `incrementViews($id)` - Increment views
- `search($keyword)` - Search posts

### Gallery.php
Gallery methods:
- `getPublished()` - Published images
- `getFeatured()` - Featured images
- `getByCategory($category)` - By category
- `getByDestination($id)` - By destination
- `getByTour($id)` - By tour

### Review.php
Review methods:
- `getApproved()` - Approved reviews
- `getFeatured()` - Featured reviews
- `getByTour($id)` - By tour
- `getAverageRating()` - Average rating

## Utility Functions

### functions.php

**Security & Validation**:
- `sanitize($data)` - Sanitize input
- `generateSlug($string)` - Create URL slug
- `generateCsrfToken()` - CSRF token
- `verifyCsrfToken($token)` - Verify CSRF

**File Handling**:
- `uploadImage($file, $dir)` - Upload image
- `resizeImage($source, $dest, $w, $h)` - Resize image
- `deleteFile($path)` - Delete file

**Authentication**:
- `isLoggedIn()` - Check login status
- `getLoggedInUser()` - Get user data
- `requireLogin()` - Require authentication

**Helpers**:
- `formatDate($date)` - Format date
- `truncateText($text, $length)` - Truncate text
- `redirect($url)` - Redirect
- `setFlashMessage($type, $msg)` - Set flash message
- `getFlashMessage()` - Get flash message
- `sendEmail($to, $subject, $msg)` - Send email
- `getPaginationHTML()` - Pagination HTML
- `getStarRatingHTML($rating)` - Star rating

## Frontend Pages

### Homepage (index.php)
Sections:
- Hero slider
- Featured tours
- Top destinations
- Gallery preview
- Recent blog posts
- Customer reviews
- Call-to-action

### Tours (tours.php)
- Grid layout of all tours
- Filtering options
- Pagination
- Tour cards with details

### Tour Details (tour-details.php)
- Full tour information
- Itinerary
- Pricing
- Included/excluded services
- Photography highlights
- Booking form
- Related tours

### Destinations (destinations.php)
- Grid of destinations
- Country-wise filtering
- Tour count per destination

### Destination Details (destination-details.php)
- Destination information
- Best time to visit
- Wildlife information
- Available tours
- Photo gallery

### Gallery (gallery.php)
- Masonry grid layout
- Category filtering
- Lightbox view
- Image details

### Blog (blogs.php)
- Blog post listing
- Category filtering
- Search functionality
- Pagination

### Blog Details (blog-details.php)
- Full blog content
- Author information
- Related posts
- Social sharing

### Reviews (reviews.php)
- Customer testimonials
- Star ratings
- Tour-wise filtering

### Contact (contact.php)
- Contact form
- Contact information
- Social media links
- Google Maps integration (optional)

## Admin Panel

### Dashboard (admin/index.php)
- Statistics cards
- Recent activity
- Quick actions
- Charts (optional)

### Content Management
- **Tours**: Add/Edit/Delete tours
- **Destinations**: Manage destinations
- **Blog Posts**: Create/edit articles
- **Gallery**: Upload/organize images
- **Reviews**: Approve/manage reviews

### User Management
- View contact messages
- Manage bookings
- Newsletter subscribers
- Admin users

### Settings
- Site configuration
- SEO settings
- Social media URLs
- Email configuration

## Security Features

1. **Input Sanitization**: All user inputs sanitized
2. **CSRF Protection**: Token validation
3. **SQL Injection Prevention**: PDO prepared statements
4. **XSS Protection**: HTML escaping
5. **Session Security**: Secure session handling
6. **Password Hashing**: BCrypt hashing
7. **File Upload Validation**: Type and size checks

## Responsive Design

- **Desktop**: Full layout (1200px+)
- **Tablet**: Optimized (768px-1024px)
- **Mobile**: Mobile-first (< 768px)
- **Touch-friendly**: Large tap targets
- **Performance**: Optimized images

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## API Endpoints (AJAX)

- `/api/contact.php` - Contact form submission
- `/api/booking.php` - Tour booking
- `/api/newsletter.php` - Newsletter subscription
- `/api/review.php` - Submit review

## SEO Features

- Meta tags (title, description, keywords)
- Open Graph tags
- Schema.org markup
- XML sitemap
- Robots.txt
- Canonical URLs
- SEO-friendly URLs

## Performance Optimization

- Minified CSS/JS (production)
- Image optimization
- Lazy loading
- Browser caching
- GZIP compression
- CDN for libraries

## Development Workflow

### Local Development

1. Setup XAMPP/WAMP/MAMP
2. Import database
3. Configure config.php
4. Access via localhost

### Making Changes

1. Edit PHP files for backend logic
2. Modify CSS in assets/css/
3. Update JavaScript in assets/js/
4. Test thoroughly

### Deployment

1. Upload files via FTP/SFTP
2. Import database
3. Update config.php
4. Set permissions
5. Test on production

## Customization

### Colors
Edit CSS variables in `assets/css/style.css`:
```css
:root {
    --primary-color: #2c5f2d;
    --secondary-color: #8b4513;
    --accent-color: #ffa500;
}
```

### Adding New Pages

1. Create PHP file in root
2. Include header.php
3. Add content
4. Include footer.php
5. Add to navigation

### Adding New Features

1. Create model class if needed
2. Add database tables
3. Create admin management page
4. Add frontend display page
5. Update navigation

## Best Practices

1. Always sanitize user input
2. Use prepared statements for queries
3. Validate file uploads
4. Handle errors gracefully
5. Log important actions
6. Comment complex code
7. Follow naming conventions
8. Test on multiple devices
9. Backup regularly
10. Keep dependencies updated

## Maintenance

### Regular Tasks
- Backup database weekly
- Update content regularly
- Monitor error logs
- Check broken links
- Update tour dates
- Respond to messages
- Moderate reviews
- Optimize images

### Updates
- Keep PHP updated
- Update libraries
- Security patches
- Feature enhancements

## Support & Contact

- **Email**: ichhedanaexpeditions@gmail.com
- **Phone**: 9007820752
- **Location**: Kolkata, India

## License

© 2026 Ichhedana Expeditions. All rights reserved.
