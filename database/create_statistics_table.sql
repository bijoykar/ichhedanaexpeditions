-- Statistics Table
USE ichhedana_expeditions;

CREATE TABLE IF NOT EXISTS site_statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stat_key VARCHAR(50) UNIQUE NOT NULL,
    stat_value VARCHAR(100) NOT NULL,
    stat_label VARCHAR(100) NOT NULL,
    icon_class VARCHAR(50) NOT NULL,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default statistics
INSERT INTO site_statistics (stat_key, stat_value, stat_label, icon_class, display_order) VALUES
('tours_conducted', '150', 'Tours Conducted', 'fas fa-camera', 1),
('happy_clients', '2500', 'Happy Clients', 'fas fa-users', 2),
('destinations', '25', 'Destinations', 'fas fa-map-marker-alt', 3),
('average_rating', '4.9', 'Average Rating', 'fas fa-star', 4)
ON DUPLICATE KEY UPDATE
    stat_value = VALUES(stat_value),
    stat_label = VALUES(stat_label),
    icon_class = VALUES(icon_class),
    display_order = VALUES(display_order);
