-- Ichhedana Expeditions Database Schema
-- Database: ichhedana_expeditions

CREATE DATABASE IF NOT EXISTS ichhedana_expeditions CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ichhedana_expeditions;

-- Admin Users Table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'editor') DEFAULT 'editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    status ENUM('active', 'inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Destinations Table
CREATE TABLE IF NOT EXISTS destinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(250) UNIQUE NOT NULL,
    region VARCHAR(100) NOT NULL,
    country VARCHAR(100) DEFAULT 'India',
    description TEXT,
    climate_info TEXT,
    best_time_to_visit VARCHAR(255),
    wildlife_info TEXT,
    featured_image VARCHAR(255),
    gallery_images TEXT,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords VARCHAR(255),
    status ENUM('published', 'draft') DEFAULT 'draft',
    featured BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tours Table
CREATE TABLE IF NOT EXISTS tours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(300) UNIQUE NOT NULL,
    destination_id INT,
    short_description TEXT,
    full_description TEXT,
    itinerary TEXT,
    duration_days INT NOT NULL,
    duration_nights INT NOT NULL,
    start_date DATE,
    end_date DATE,
    price DECIMAL(10, 2),
    max_participants INT DEFAULT 10,
    difficulty_level ENUM('easy', 'moderate', 'challenging') DEFAULT 'moderate',
    included_services TEXT,
    excluded_services TEXT,
    accommodation_details TEXT,
    photography_highlights TEXT,
    featured_image VARCHAR(255),
    gallery_images TEXT,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords VARCHAR(255),
    status ENUM('published', 'draft', 'archived') DEFAULT 'draft',
    featured BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (destination_id) REFERENCES destinations(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blog Posts Table
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(300) UNIQUE NOT NULL,
    author_id INT NOT NULL,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    featured_image VARCHAR(255),
    category VARCHAR(100),
    tags VARCHAR(255),
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords VARCHAR(255),
    status ENUM('published', 'draft') DEFAULT 'draft',
    featured BOOLEAN DEFAULT FALSE,
    views INT DEFAULT 0,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES admin_users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Gallery Table
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    hero_title VARCHAR(255) NULL,
    hero_subtitle VARCHAR(500) NULL,
    hero_button_text VARCHAR(100) NULL,
    hero_button_link VARCHAR(500) NULL,
    image_path VARCHAR(255) NOT NULL,
    thumbnail_path VARCHAR(255),
    category VARCHAR(100),
    destination_id INT NULL,
    tour_id INT NULL,
    photographer VARCHAR(100),
    camera_settings VARCHAR(255),
    location VARCHAR(255),
    taken_date DATE,
    display_order INT DEFAULT 0,
    featured BOOLEAN DEFAULT FALSE,
    status ENUM('published', 'draft') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (destination_id) REFERENCES destinations(id) ON DELETE SET NULL,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reviews Table
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100),
    customer_photo VARCHAR(255),
    tour_id INT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT NOT NULL,
    review_date DATE,
    status ENUM('approved', 'pending', 'rejected') DEFAULT 'pending',
    featured BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contact Messages Table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255),
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Site Settings Table
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'textarea', 'number', 'boolean', 'json') DEFAULT 'text',
    setting_group VARCHAR(50) DEFAULT 'general',
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Newsletter Subscribers Table
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(100),
    status ENUM('active', 'unsubscribed') DEFAULT 'active',
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tour Bookings Table
CREATE TABLE IF NOT EXISTS tour_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    number_of_participants INT NOT NULL,
    special_requests TEXT,
    booking_status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2),
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Default Admin User (password: admin123 - should be changed after first login)
INSERT INTO admin_users (username, email, password, full_name, role) 
VALUES ('admin', 'admin@ichhedanaexpeditions.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- Insert Default Site Settings
INSERT INTO site_settings (setting_key, setting_value, setting_type, setting_group, description) VALUES
('site_name', 'Ichhedana Expeditions', 'text', 'general', 'Website name'),
('site_tagline', 'Wildlife Photography Tours & Expeditions', 'text', 'general', 'Website tagline'),
('contact_phone', '9007820752', 'text', 'contact', '24/7 Support phone number'),
('contact_email', 'ichhedanaexpeditions@gmail.com', 'text', 'contact', 'Contact email'),
('contact_address', 'Kolkata, India', 'text', 'contact', 'Office location'),
('facebook_url', 'https://www.facebook.com/profile.php?id=100063782000455', 'text', 'social', 'Facebook profile URL'),
('facebook_group_url', 'https://www.facebook.com/groups/2010223942443396/', 'text', 'social', 'Facebook group URL'),
('instagram_url', 'https://www.instagram.com/ichhedanaexpeditions/', 'text', 'social', 'Instagram profile URL'),
('site_description', 'Ichhedana Expeditions offers professional wildlife photography tours to national parks across India and Bhutan. Led by experienced wildlife photographers.', 'textarea', 'seo', 'Site meta description'),
('site_keywords', 'wildlife photography, photography tours, wildlife expeditions, nature photography, India tours, Bhutan tours', 'text', 'seo', 'Site meta keywords');

-- Create Indexes for Better Performance
CREATE INDEX idx_destinations_slug ON destinations(slug);
CREATE INDEX idx_destinations_status ON destinations(status);
CREATE INDEX idx_tours_slug ON tours(slug);
CREATE INDEX idx_tours_status ON tours(status);
CREATE INDEX idx_tours_dates ON tours(start_date, end_date);
CREATE INDEX idx_blog_slug ON blog_posts(slug);
CREATE INDEX idx_blog_status ON blog_posts(status);
CREATE INDEX idx_gallery_category ON gallery(category);
CREATE INDEX idx_reviews_status ON reviews(status);
CREATE INDEX idx_contact_status ON contact_messages(status);
