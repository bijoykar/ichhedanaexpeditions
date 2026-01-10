<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/classes/Model.php';
require_once 'includes/classes/Tour.php';
require_once 'includes/classes/Destination.php';

// Get or create Bhutan destination
$destModel = new Destination();
$bhutan = $destModel->findBy('name', 'Bhutan');

if (!$bhutan) {
    $destId = $destModel->insert([
        'name' => 'Bhutan',
        'slug' => 'bhutan',
        'description' => 'The Land of the Thunder Dragon - Experience the mystical kingdom of Bhutan',
        'featured_image' => 'bhutan.jpg',
        'status' => 'published'
    ]);
} else {
    $destId = $bhutan['id'];
}

// Bhutan 9N/10D Tour Data
$tourData = [
    'title' => 'Bhutan - 9 Nights - 10 Days',
    'slug' => 'bhutan-9-nights-10-days',
    'destination_id' => $destId,
    'short_description' => 'Discover the magical kingdom of Bhutan with this comprehensive 10-day journey through pristine landscapes, ancient monasteries, and vibrant culture.',
    'full_description' => 'Embark on an unforgettable journey through the mystical kingdom of Bhutan. This carefully crafted 9 nights 10 days tour takes you through the most iconic destinations including Paro, Thimphu, Punakha, and the breathtaking Tiger\'s Nest Monastery. Experience the unique blend of ancient Buddhist culture and stunning Himalayan landscapes. Witness colorful festivals, interact with friendly locals, explore historic dzongs, and immerse yourself in the philosophy of Gross National Happiness.',
    'itinerary' => '**Day 1: Arrival in Paro - Transfer to Thimphu**
Arrive at Paro International Airport and enjoy scenic drive to Thimphu (2 hours). Visit Buddha Dordenma statue offering panoramic valley views. Overnight in Thimphu.

**Day 2: Thimphu Sightseeing**
Explore National Memorial Chorten, Tashichho Dzong, Folk Heritage Museum, and Centenary Farmers Market. Optional visit to Motithang Takin Preserve. Overnight in Thimphu.

**Day 3: Thimphu - Punakha**
Drive to Punakha via Dochula Pass (3,100m) with 108 chortens and stunning Himalayan views. Visit Punakha Dzong, one of Bhutan\'s most beautiful fortresses. Overnight in Punakha.

**Day 4: Punakha Valley Exploration**
Hike to Chimi Lhakhang (Temple of Fertility) through rice fields and villages. Visit Punakha Suspension Bridge. Evening at leisure. Overnight in Punakha.

**Day 5: Punakha - Phobjikha Valley**
Drive to beautiful Phobjikha Valley (Gangtey), winter home of rare black-necked cranes. Visit Gangtey Monastery. Nature walk through the valley. Overnight in Phobjikha.

**Day 6: Phobjikha - Paro**
Return journey to Paro valley. Visit Paro town and local market. Evening leisure time to explore. Overnight in Paro.

**Day 7: Paro Sightseeing**
Visit National Museum, Paro Rinpung Dzong, and Kyichu Lhakhang (one of the oldest temples). Stroll through Paro town. Overnight in Paro.

**Day 8: Tiger\'s Nest Monastery Hike**
Iconic hike to Taktsang Monastery (Tiger\'s Nest) perched on cliff at 3,120m. This sacred site is where Guru Rinpoche meditated. Afternoon visit to Drukgyel Dzong ruins. Overnight in Paro.

**Day 9: Paro - Haa Valley Excursion**
Day trip to remote and beautiful Haa Valley. Visit ancient temples and experience traditional Bhutanese rural life. Return to Paro. Overnight in Paro.

**Day 10: Departure**
Transfer to Paro International Airport for departure with unforgettable memories of the Thunder Dragon Kingdom.',
    
    'duration_days' => 10,
    'duration_nights' => 9,
    'start_date' => '2026-03-15',
    'end_date' => '2026-03-24',
    'price' => 89999.00,
    'max_participants' => 12,
    'difficulty_level' => 'moderate',
    
    'included_services' => '- Accommodation in 3-star hotels (twin sharing)
- All meals (breakfast, lunch, dinner)
- Licensed Bhutanese tour guide
- Private vehicle with driver
- All sightseeing and monument entrance fees
- Sustainable Development Fee (SDF) for entire stay
- Bhutan visa fee
- Inner line permits for restricted areas
- All government taxes and service charges
- Bottled water during tours',
    
    'excluded_services' => '- International flights to/from Paro
- Travel insurance
- Personal expenses (laundry, telephone, beverages)
- Tips for guide and driver
- Camera fees at monuments
- Any meals during travel days
- Any activities not mentioned in itinerary
- Emergency evacuation costs
- Optional activities or extensions',
    
    'accommodation_details' => 'Comfortable 3-star hotels and guesthouses throughout the tour. All rooms equipped with modern amenities, attached bathrooms with hot water, Wi-Fi where available. Traditional Bhutanese hospitality with authentic local cuisine.',
    
    'photography_highlights' => '- Tiger\'s Nest Monastery clinging to cliff
- Punakha Dzong at river confluence
- Dochula Pass 108 chortens with Himalayan backdrop
- Black-necked cranes in Phobjikha Valley (winter)
- Buddha Dordenma statue overlooking Thimphu
- Prayer flags and mountain landscapes
- Traditional architecture and colorful festivals
- Local people in traditional dress (Gho & Kira)',
    
    'featured_image' => 'bhutan-tigers-nest.jpg',
    'status' => 'published',
    'featured' => 1,
    'display_order' => 1,
    'meta_title' => 'Bhutan Tour 9 Nights 10 Days - Complete Kingdom Experience',
    'meta_description' => 'Experience the best of Bhutan in 10 days. Visit Tiger\'s Nest, Punakha Dzong, Phobjikha Valley, and Thimphu. All-inclusive package with SDF, visa, meals & guide.',
    'meta_keywords' => 'bhutan tour, bhutan package, tigers nest, punakha, thimphu, paro, bhutan travel, himalayan tour'
];

$tour = new Tour();
$tourId = $tour->insert($tourData);

if ($tourId) {
    echo "✓ Successfully created Bhutan tour!\n\n";
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
    echo "View tour at: " . SITE_URL . "/tour-details.php?slug=bhutan-9-nights-10-days\n";
} else {
    echo "✗ Failed to create tour\n";
}
?>
