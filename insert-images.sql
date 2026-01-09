-- Insert hero slider images into gallery table
-- Run this in phpMyAdmin or MySQL command line

INSERT INTO gallery (title, image_path, category, location, featured, status, display_order, created_at) VALUES
('Wildlife Photography Expedition', '794686787.jpg', 'Wildlife', 'Nepal', 1, 'published', 1, NOW()),
('Mountain Wilderness Adventure', '623395350.png', 'Landscape', 'Himalayas', 1, 'published', 2, NOW());

-- Check if images were inserted
SELECT * FROM gallery WHERE featured = 1;
