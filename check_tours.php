<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/classes/Model.php';
require_once 'includes/classes/Tour.php';

$tour = new Tour();
$tours = $tour->all();

echo "Current tours in database: " . count($tours) . "\n\n";
foreach($tours as $t) {
    echo $t['id'] . ". " . $t['title'] . " (Slug: " . $t['slug'] . ")\n";
}
?>
