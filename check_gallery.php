<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/classes/Model.php';
require_once 'includes/classes/Gallery.php';

$gallery = new Gallery();
$images = $gallery->getPublished(50);

echo "Total images in gallery: " . count($images) . "\n\n";
echo "Recent images:\n";
echo "==========================================\n";

foreach ($images as $index => $img) {
    echo ($index + 1) . ". ID: " . $img['id'] . "\n";
    echo "   Title: " . $img['title'] . "\n";
    echo "   Category: " . $img['category'] . "\n";
    echo "   Image: " . $img['image_path'] . "\n";
    echo "   Status: " . $img['status'] . "\n";
    echo "   Created: " . $img['created_at'] . "\n";
    echo "------------------------------------------\n";
}

echo "\nAll images are ready to display in gallery.php!\n";
?>
