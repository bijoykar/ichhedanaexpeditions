<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/classes/Model.php';
require_once 'includes/classes/Gallery.php';

// List of all image URLs from ichhedanaexpeditions.com
$imageUrls = [
    'https://ichhedanaexpeditions.com/usercontent/1973216607.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1727653297.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1793112231.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1813684925.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1422434558.jpg',
    'https://ichhedanaexpeditions.com/usercontent/383822695.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1175885500.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1638739269.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1739927394.jpg',
    'https://ichhedanaexpeditions.com/usercontent/973080013.jpg',
    'https://ichhedanaexpeditions.com/usercontent/697124994.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/2025943628.jpg',
    'https://ichhedanaexpeditions.com/usercontent/473568887.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1677260488.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/1652569018.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1945331695.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/929646502.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/607819960.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/1726611595.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/1326879878.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/1097863336.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/1464479069.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/1479506831.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1341695260.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/1092050025.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/1668116806.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/1066031495.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/1889437886.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/1229619903.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/1748091088.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/1027997093.jpeg',
    'https://ichhedanaexpeditions.com/usercontent/1726766046.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1620817569.jpg',
    'https://ichhedanaexpeditions.com/usercontent/751330622.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1163327341.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1267638933.jpg',
    'https://ichhedanaexpeditions.com/usercontent/2082051408.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1099098874.jpg',
    'https://ichhedanaexpeditions.com/usercontent/969695903.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1398733479.jpg',
    'https://ichhedanaexpeditions.com/usercontent/644388218.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1976844842.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1743838817.jpg',
    'https://ichhedanaexpeditions.com/usercontent/704960641.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1544826992.jpg',
    'https://ichhedanaexpeditions.com/usercontent/1421869917.jpg',
    'https://ichhedanaexpeditions.com/usercontent/831936976.jpg',
    'https://ichhedanaexpeditions.com/usercontent/2002166289.jpg',
];

// Categories to randomly assign
$categories = ['Mountains', 'Beaches', 'Adventure', 'Cultural', 'Wildlife', 'Trekking'];

// Create uploads directory if it doesn't exist
$uploadDir = __DIR__ . '/uploads/gallery/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

echo "Starting download of " . count($imageUrls) . " images...\n\n";

$gallery = new Gallery();
$successCount = 0;
$errorCount = 0;

foreach ($imageUrls as $index => $url) {
    try {
        echo ($index + 1) . ". Downloading: " . basename($url) . "... ";
        
        // Download image using cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        curl_setopt($ch, CURLOPT_REFERER, 'https://ichhedanaexpeditions.com/gallery/');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: image/webp,image/apng,image/*,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.9',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $imageContent = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($imageContent === false || $httpCode !== 200) {
            echo "FAILED (HTTP $httpCode" . ($curlError ? ", $curlError" : "") . ")\n";
            $errorCount++;
            continue;
        }
        
        // Get file extension
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        if (empty($extension)) {
            $extension = 'jpg';
        }
        
        // Generate unique filename
        $filename = 'gallery_' . time() . '_' . uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        // Save file
        if (file_put_contents($filepath, $imageContent)) {
            // Insert into database
            $category = $categories[array_rand($categories)];
            $data = [
                'title' => 'Travel Memory ' . ($index + 1),
                'description' => 'Beautiful moment captured during our expedition',
                'image_path' => 'uploads/gallery/' . $filename,
                'category' => $category,
                'display_order' => $index + 1,
                'status' => 'published'
            ];
            
            if ($gallery->insert($data)) {
                echo "SUCCESS (saved as $filename, category: $category)\n";
                $successCount++;
            } else {
                echo "FAILED (database error)\n";
                $errorCount++;
                @unlink($filepath); // Remove file if DB insert failed
            }
            
            // Small delay to avoid overwhelming the server
            usleep(100000); // 0.1 second
        } else {
            echo "FAILED (save error)\n";
            $errorCount++;
        }
    } catch (Exception $e) {
        echo "FAILED (exception: " . $e->getMessage() . ")\n";
        $errorCount++;
    }
}

echo "\n========================================\n";
echo "Download Complete!\n";
echo "Success: $successCount images\n";
echo "Failed: $errorCount images\n";
echo "========================================\n";
echo "\nImages saved to: $uploadDir\n";
echo "You can now view them at: " . SITE_URL . "/gallery.php\n";
?>
