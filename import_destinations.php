<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$db = Database::getInstance()->getConnection();

// List of destinations from ichhedanaexpeditions.com/destinations
$destinations = [
    [
        'name' => 'Maharashtra',
        'slug' => 'maharashtra',
        'region' => 'Maharashtra',
        'country' => 'India',
        'description' => 'Explore the diverse wildlife and rich biodiversity of Maharashtra, home to several national parks and wildlife sanctuaries.',
        'status' => 'published'
    ],
    [
        'name' => 'Assam',
        'slug' => 'assam',
        'region' => 'Assam',
        'country' => 'India',
        'description' => 'Discover the one-horned rhinoceros and diverse wildlife of Assam, featuring Kaziranga and Manas National Parks.',
        'status' => 'published'
    ],
    [
        'name' => 'Bhitarkanika National Park',
        'slug' => 'bhitarkanika-national-park',
        'region' => 'Orissa',
        'country' => 'India',
        'description' => 'Famous for its mangrove forests, estuarine crocodiles, and diverse bird species in the coastal wetlands of Odisha.',
        'status' => 'published'
    ],
    [
        'name' => 'Paro',
        'slug' => 'paro',
        'region' => 'Paro',
        'country' => 'Bhutan',
        'description' => 'Experience the stunning landscapes and cultural heritage of Paro, Bhutan, with opportunities for wildlife and nature photography.',
        'status' => 'published'
    ],
    [
        'name' => 'Jaisalmer',
        'slug' => 'jaisalmer',
        'region' => 'Rajasthan',
        'country' => 'India',
        'description' => 'The Golden City of Rajasthan, featuring desert landscapes, unique wildlife, and stunning sand dunes of the Thar Desert.',
        'status' => 'published'
    ],
    [
        'name' => 'Bikaner',
        'slug' => 'bikaner',
        'region' => 'Rajasthan',
        'country' => 'India',
        'description' => 'Explore the desert wildlife and rich culture of Bikaner, including the famous Desert National Park.',
        'status' => 'published'
    ],
    [
        'name' => 'Sikkim',
        'slug' => 'sikkim',
        'region' => 'Sikkim',
        'country' => 'India',
        'description' => 'Discover the pristine beauty and rich biodiversity of Sikkim, featuring red pandas, snow leopards, and stunning Himalayan landscapes.',
        'status' => 'published'
    ],
    [
        'name' => 'Kutch',
        'slug' => 'kutch',
        'region' => 'Gujarat',
        'country' => 'India',
        'description' => 'Experience the unique ecosystem of the Rann of Kutch, home to the Asiatic Wild Ass and diverse birdlife.',
        'status' => 'published'
    ],
    [
        'name' => 'Meghalaya',
        'slug' => 'meghalaya',
        'region' => 'Meghalaya',
        'country' => 'India',
        'description' => 'The abode of clouds, featuring rich biodiversity, living root bridges, and stunning waterfalls in Northeast India.',
        'status' => 'published'
    ],
    [
        'name' => 'Kalimpong',
        'slug' => 'kalimpong',
        'region' => 'West Bengal',
        'country' => 'India',
        'description' => 'A scenic hill station offering panoramic views of the Himalayas and opportunities for bird watching and nature photography.',
        'status' => 'published'
    ],
    [
        'name' => 'Madhya Pradesh',
        'slug' => 'madhya-pradesh',
        'region' => 'Madhya Pradesh',
        'country' => 'India',
        'description' => 'The Tiger State of India, home to famous national parks like Bandhavgarh, Kanha, Pench, and Satpura.',
        'status' => 'published'
    ],
    [
        'name' => 'Uttarakhand',
        'slug' => 'uttarakhand',
        'region' => 'Uttarakhand',
        'country' => 'India',
        'description' => 'Gateway to the Himalayas, featuring Jim Corbett National Park and diverse wildlife in the foothills.',
        'status' => 'published'
    ],
    [
        'name' => 'West Bengal',
        'slug' => 'west-bengal',
        'region' => 'West Bengal',
        'country' => 'India',
        'description' => 'Home to the Sundarbans mangrove forests, Royal Bengal Tigers, and diverse ecosystems from mountains to coast.',
        'status' => 'published'
    ],
    [
        'name' => 'Arunachal Pradesh',
        'slug' => 'arunachal-pradesh',
        'region' => 'Arunachal Pradesh',
        'country' => 'India',
        'description' => 'The Land of Rising Sun, featuring pristine forests, diverse wildlife including the red panda and clouded leopard.',
        'status' => 'published'
    ],
    [
        'name' => 'Uttar Pradesh',
        'slug' => 'uttar-pradesh',
        'region' => 'Uttar Pradesh',
        'country' => 'India',
        'description' => 'Home to several important wildlife sanctuaries and the Dudhwa National Park, known for swamp deer and tigers.',
        'status' => 'published'
    ]
];

try {
    $stmt = $db->prepare("INSERT INTO destinations (name, slug, region, country, description, status, created_at, updated_at) 
                          VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
    
    $count = 0;
    foreach ($destinations as $dest) {
        // Check if destination already exists
        $checkStmt = $db->prepare("SELECT id FROM destinations WHERE slug = ?");
        $checkStmt->execute([$dest['slug']]);
        
        if ($checkStmt->rowCount() == 0) {
            $stmt->execute([
                $dest['name'],
                $dest['slug'],
                $dest['region'],
                $dest['country'],
                $dest['description'],
                $dest['status']
            ]);
            $count++;
            echo "Added: {$dest['name']}<br>";
        } else {
            echo "Skipped (already exists): {$dest['name']}<br>";
        }
    }
    
    echo "<br><strong>Total destinations added: $count</strong>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
