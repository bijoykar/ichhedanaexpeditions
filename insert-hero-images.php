<?php
/**
 * Script to download and insert hero slider images into the database
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Get database connection
$database = new Database();
$db = $database->getConnection();

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Insert Hero Images</title>";
echo "<style>body{font-family:Arial,sans-serif;max-width:800px;margin:50px auto;padding:20px;background:#f5f5f5;}";
echo ".success{background:#d4edda;color:#155724;padding:15px;border-radius:5px;margin:10px 0;}";
echo ".error{background:#f8d7da;color:#721c24;padding:15px;border-radius:5px;margin:10px 0;}";
echo ".info{background:#d1ecf1;color:#0c5460;padding:15px;border-radius:5px;margin:10px 0;}";
echo "ul{line-height:2;}a{color:#007bff;text-decoration:none;}a:hover{text-decoration:underline;}</style></head><body>";

echo "<h1>Hero Slider Images Setup</h1>";

// Create uploads/gallery directory if it doesn't exist
$uploadDir = __DIR__ . '/uploads/gallery';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
    echo "<div class='info'>Created directory: uploads/gallery/</div>";
}

// Images to download and insert
$images = [
    [
        'filename' => '794686787.jpg',
        'url' => 'https://ichhedanaexpeditions.com/usercontent/794686787.jpg',
        'title' => 'Wildlife Photography Expedition',
        'category' => 'Wildlife',
        'location' => 'Nepal',
        'order' => 1
    ],
    [
        'filename' => '623395350.png',
        'url' => 'https://ichhedanaexpeditions.com/usercontent/623395350.png',
        'title' => 'Mountain Wilderness Adventure',
        'category' => 'Landscape',
        'location' => 'Himalayas',
        'order' => 2
    ]
];

echo "<h2>Step 1: Downloading Images</h2>";

foreach ($images as $image) {
    $filePath = $uploadDir . '/' . $image['filename'];
    
    // Download image if it doesn't exist
    if (!file_exists($filePath)) {
        echo "<p>Downloading {$image['filename']}...</p>";
        
        $imageContent = @file_get_contents($image['url']);
        
        if ($imageContent !== false) {
            file_put_contents($filePath, $imageContent);
            echo "<div class='success'>✓ Downloaded: {$image['filename']}</div>";
        } else {
            echo "<div class='error'>✗ Failed to download: {$image['filename']}</div>";
            echo "<div class='info'>Please manually copy this file from:<br><strong>{$image['url']}</strong><br>to:<br><strong>$filePath</strong></div>";
        }
    } else {
        echo "<div class='info'>✓ File already exists: {$image['filename']}</div>";
    }
}

echo "<h2>Step 2: Inserting into Database</h2>";

try {
    // Check if images already exist in database
    $stmt = $db->prepare("SELECT id, title, image_path FROM gallery WHERE image_path IN (?, ?)");
    $stmt->execute(['794686787.jpg', '623395350.png']);
    $existing = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($existing)) {
        echo "<div class='info'><strong>These images already exist in the database:</strong><ul>";
        foreach ($existing as $img) {
            echo "<li>ID: {$img['id']} - {$img['title']} ({$img['image_path']})</li>";
        }
        echo "</ul></div>";
        echo "<div class='info'>To update them, you can delete and re-insert, or use the admin panel to edit them.</div>";
    } else {
        // Insert images into gallery table
        $stmt = $db->prepare("INSERT INTO gallery (title, image_path, category, location, featured, status, display_order, created_at) 
                             VALUES (?, ?, ?, ?, 1, 'published', ?, NOW())");
        
        foreach ($images as $image) {
            $stmt->execute([
                $image['title'],
                $image['filename'],
                $image['category'],
                $image['location'],
                $image['order']
            ]);
        }
        
        echo "<div class='success'><h3>✓ Success!</h3>";
        echo "<p>Hero slider images have been added to the database:</p><ul>";
        foreach ($images as $image) {
            echo "<li>{$image['filename']} - {$image['title']}</li>";
        }
        echo "</ul></div>";
    }
    
    // Show current featured images
    echo "<h2>Current Featured Gallery Images</h2>";
    $stmt = $db->query("SELECT id, title, image_path, category, location FROM gallery WHERE featured = 1 AND status = 'published' ORDER BY display_order ASC");
    $featured = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($featured)) {
        echo "<div class='info'><ul>";
        foreach ($featured as $img) {
            echo "<li><strong>{$img['title']}</strong> - {$img['image_path']} ({$img['category']})</li>";
        }
        echo "</ul></div>";
    } else {
        echo "<div class='error'>No featured images found in the database.</div>";
    }
    
} catch (PDOException $e) {
    echo "<div class='error'><strong>Database Error:</strong><br>" . $e->getMessage() . "</div>";
}

echo "<hr><p><a href='index.php' style='margin-right:20px;'>→ View Homepage</a> <a href='admin/index.php'>→ Go to Admin Panel</a></p>";
echo "</body></html>";
?>
