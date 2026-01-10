<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/classes/Model.php';
require_once 'includes/classes/Tour.php';
require_once 'includes/classes/Destination.php';

// Get or create Satpura destination
$destModel = new Destination();
$satpura = $destModel->findBy('name', 'Satpura Tiger Reserve');

if (!$satpura) {
    $destId = $destModel->insert([
        'name' => 'Satpura Tiger Reserve',
        'slug' => 'satpura-tiger-reserve',
        'description' => 'One of India\'s most unique tiger reserves offering walking safaris and boat rides in pristine wilderness',
        'featured_image' => 'satpura.jpg',
        'status' => 'published'
    ]);
} else {
    $destId = $satpura['id'];
}

// Satpura Tiger Reserve 3N/4D Tour Data
$tourData = [
    'title' => 'Satpura Tiger Reserve - 3 Nights - 4 Days',
    'slug' => 'satpura-tiger-reserve-3-nights-4-days',
    'destination_id' => $destId,
    'short_description' => 'Experience the unique wilderness of Satpura Tiger Reserve with walking safaris, boat rides, and jeep safaris in one of India\'s most pristine tiger habitats.',
    'full_description' => 'Satpura Tiger Reserve in Madhya Pradesh offers a truly unique wildlife experience unlike any other tiger reserve in India. This 4-day expedition takes you through diverse landscapes including dense forests, deep gorges, and tranquil water bodies. What makes Satpura special is the opportunity to explore on foot with trained guides, enjoy boat safaris on the Denwa River, and experience night safaris. The reserve is home to tigers, leopards, sloth bears, Indian bison, wild dogs, and over 300 species of birds. With its rugged terrain and relatively fewer tourists, Satpura provides an intimate and authentic jungle experience.',
    'itinerary' => '**Day 1: Arrival at Satpura**
Arrive at Pipariya railway station or Bhopal airport (transfer can be arranged). Drive to Satpura Tiger Reserve (approximately 1.5-2 hours from Pipariya). Check in to resort/lodge. Evening orientation about the reserve and safari protocols. Dinner and overnight stay.

**Day 2: Morning Jeep Safari & Afternoon Boat Safari**
Early morning jeep safari in Satpura National Park core zone (4 hours). Return to lodge for breakfast and rest. After lunch, enjoy boat safari on Denwa River watching wildlife along the banks including marsh muggers, birds, and animals coming to drink. Evening at leisure or nature walk around resort. Dinner and overnight stay.

**Day 3: Walking Safari & Evening Jeep Safari**
After breakfast, embark on guided walking safari (3-4 hours) - a unique experience exclusive to Satpura. Walk through the forest with armed guards and trained naturalists, tracking animals, learning about flora and fauna. Return for lunch and afternoon rest. Evening jeep safari in buffer zone exploring different habitats. Return for dinner and campfire. Overnight stay.

**Day 4: Morning Safari & Departure**
Early morning safari (jeep or walking based on preference). Return to lodge for breakfast. Check out and departure to Pipariya/Bhopal with memories of pristine wilderness.',
    
    'duration_days' => 4,
    'duration_nights' => 3,
    'start_date' => '2026-02-20',
    'end_date' => '2026-02-23',
    'price' => 32999.00,
    'max_participants' => 12,
    'difficulty_level' => 'moderate',
    
    'included_services' => '- Accommodation in forest lodge/resort (twin sharing)
- All meals (breakfast, lunch, dinner)
- 3 jeep safaris in core/buffer zones
- 1 boat safari on Denwa River
- 1 walking safari with trained guides
- All entry permits and forest fees
- Experienced naturalist guide
- All taxes and service charges
- Mineral water during safaris',
    
    'excluded_services' => '- Transportation to/from Pipariya or Bhopal
- Personal expenses and beverages
- Camera and video fees at park
- Travel insurance
- Tips for guides, drivers and staff
- Any meals during travel
- Additional safaris beyond mentioned
- Any activities not specified in itinerary
- Emergency medical expenses',
    
    'accommodation_details' => 'Stay in comfortable eco-friendly forest lodges or resorts near the park with modern amenities. Rooms with attached bathrooms, hot water, and basic comforts. Most lodges offer beautiful views of the forest and serve delicious home-style meals.',
    
    'photography_highlights' => '- Tigers and leopards in natural habitat
- Sloth bears foraging in forest
- Indian gaur (bison) in meadows
- Wild dogs (dholes) hunting
- Marsh muggers on riverbanks
- Over 300 bird species including Malabar pied hornbill
- Scenic landscapes with gorges and plateaus
- Dense sal and teak forests
- Denwa River and waterbodies
- Walking safari unique perspective shots',
    
    'featured_image' => 'satpura-tiger.jpg',
    'status' => 'published',
    'featured' => 1,
    'display_order' => 2,
    'meta_title' => 'Satpura Tiger Reserve Tour 3N/4D - Walking Safari & Boat Safari',
    'meta_description' => 'Explore Satpura Tiger Reserve with unique walking safaris, boat rides, and jeep safaris. 4-day wildlife tour package including accommodation, meals, and all safaris.',
    'meta_keywords' => 'satpura tiger reserve, walking safari, madhya pradesh wildlife, tiger safari, boat safari, satpura national park'
];

$tour = new Tour();
$tourId = $tour->insert($tourData);

if ($tourId) {
    echo "✓ Successfully created Satpura Tiger Reserve tour!\n\n";
    echo "Tour Details:\n";
    echo "===========================================\n";
    echo "ID: $tourId\n";
    echo "Title: {$tourData['title']}\n";
    echo "Duration: {$tourData['duration_nights']} Nights / {$tourData['duration_days']} Days\n";
    echo "Price: ₹" . number_format($tourData['price'], 2) . "\n";
    echo "Start Date: {$tourData['start_date']}\n";
    echo "End Date: {$tourData['end_date']}\n";
    echo "Difficulty: {$tourData['difficulty_level']}\n";
    echo "Status: {$tourData['status']}\n";
    echo "Featured: " . ($tourData['featured'] ? 'Yes' : 'No') . "\n";
    echo "===========================================\n\n";
    echo "Unique Features:\n";
    echo "- Walking Safari (exclusive to Satpura)\n";
    echo "- Boat Safari on Denwa River\n";
    echo "- 3 Jeep Safaris\n";
    echo "- Less crowded than other reserves\n";
    echo "===========================================\n\n";
    echo "View tour at: " . SITE_URL . "/tour-details.php?slug=satpura-tiger-reserve-3-nights-4-days\n";
} else {
    echo "✗ Failed to create tour\n";
}
?>
