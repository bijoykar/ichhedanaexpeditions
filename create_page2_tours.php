<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/classes/Model.php';
require_once 'includes/classes/Tour.php';
require_once 'includes/classes/Destination.php';

$tour = new Tour();
$destModel = new Destination();

// Tours from page 2 and 3
$toursData = [
    [
        'destination_name' => 'Ranthambore National Park',
        'title' => 'Ranthambore National Park - 3 Nights - 4 Days',
        'slug' => 'ranthambore-national-park-3-nights-4-days',
        'duration_nights' => 3,
        'duration_days' => 4,
        'start_date' => '2026-06-14',
        'end_date' => '2026-06-17',
        'price' => 29999.00,
        'short_description' => 'Explore the historic Ranthambore with its famous tigers, ancient fort ruins, and diverse wildlife in Rajasthan\'s premier tiger reserve.',
        'full_description' => 'Ranthambore National Park in Rajasthan is one of India\'s most famous tiger reserves, known for its daytime tiger sightings. The park is unique with its ancient fort ruins, lakes, and historic temples scattered throughout. The dramatic landscape of rocky terrain, dry deciduous forests, and water bodies creates perfect tiger habitat. Besides tigers, spot leopards, sloth bears, marsh crocodiles, and numerous bird species.',
        'itinerary' => '**Day 1: Arrival at Ranthambore**
Arrive at Sawai Madhopur. Transfer to resort near park. Evening at leisure. Visit to Ranthambore Fort (optional). Overnight stay.

**Day 2: Morning & Afternoon Safaris**
Early morning jeep safari in one of the zones. Return for breakfast. Afternoon safari in different zone. High chances of tiger sightings. Overnight stay.

**Day 3: Full Day Safari**
Morning safari followed by visit to Surwal Lake for birdwatching. Afternoon safari. Evening at resort. Overnight stay.

**Day 4: Morning Safari & Departure**
Final morning safari. After breakfast, departure to Sawai Madhopur station/Jaipur.',
        'difficulty_level' => 'easy',
        'photography_highlights' => 'Royal Bengal Tigers in daylight, ancient Ranthambore Fort, Padam Talao lake, leopards, sloth bears, marsh muggers, sambar deer, peacocks, diverse birds'
    ],
    [
        'destination_name' => 'Dehing Patkai Wildlife Sanctuary',
        'title' => 'Dehing Patkai Wildlife Sanctuary & Maguri Beel (4N5D)',
        'slug' => 'dehing-patkai-wildlife-sanctuary-maguri-beel-4n5d',
        'duration_nights' => 4,
        'duration_days' => 5,
        'start_date' => '2026-10-24',
        'end_date' => '2026-10-28',
        'price' => 36999.00,
        'short_description' => 'Discover Assam\'s rainforest jewel with rare hoolock gibbons, white-winged wood ducks, and pristine wilderness.',
        'full_description' => 'Dehing Patkai Wildlife Sanctuary in Assam, often called the "Amazon of the East," is India\'s largest stretch of lowland rainforest. This pristine wilderness is home to seven primate species including the endangered hoolock gibbon. Maguri Beel wetland nearby is famous for the critically endangered white-winged wood duck. The combination offers exceptional wildlife and birding opportunities in one of India\'s most biodiverse regions.',
        'itinerary' => '**Day 1: Arrival at Dibrugarh**
Arrive at Dibrugarh. Transfer to Dehing Patkai area. Check in to eco-lodge. Evening orientation. Overnight stay.

**Day 2: Dehing Patkai Exploration**
Early morning trek to listen to hoolock gibbon calls. Nature walk through rainforest. Afternoon visit to canopy walkway. Bird watching session. Overnight stay.

**Day 3: Maguri Beel Excursion**
Full day excursion to Maguri Beel wetland. Boat safari for white-winged wood duck. Extensive bird watching. Packed lunch. Return evening. Overnight stay.

**Day 4: Rainforest Trek**
Full day rainforest trek with naturalist. Wildlife tracking, butterfly watching, learning about flora. Photography sessions. Overnight stay.

**Day 5: Departure**
Morning bird watching walk. After breakfast, transfer to Dibrugarh for onward journey.',
        'difficulty_level' => 'moderate',
        'photography_highlights' => 'Hoolock gibbons, white-winged wood ducks, capped langurs, slow loris, hornbills, rainforest canopy, Maguri Beel wetland, butterflies, orchids'
    ],
    [
        'destination_name' => 'Desert National Park',
        'title' => 'Desert National Park (3Nights/4Days)',
        'slug' => 'desert-national-park-3nights-4days',
        'duration_nights' => 3,
        'duration_days' => 4,
        'start_date' => '2026-11-13',
        'end_date' => '2026-11-16',
        'price' => 27999.00,
        'short_description' => 'Experience the unique Thar Desert ecosystem with Great Indian Bustards, desert foxes, and golden sand dunes near Jaisalmer.',
        'full_description' => 'Desert National Park near Jaisalmer showcases the unique ecosystem of the Thar Desert. This vast expanse of rolling sand dunes, rocky terrain, and salt lake depressions is home to the critically endangered Great Indian Bustard. The park supports surprising biodiversity adapted to harsh desert conditions including desert foxes, blackbucks, chinkaras, and numerous resident and migratory birds.',
        'itinerary' => '**Day 1: Arrival at Jaisalmer**
Arrive at Jaisalmer. Transfer to desert resort. Visit Jaisalmer Fort. Evening cultural program. Overnight stay.

**Day 2: Desert National Park Safari**
Early morning safari in Desert National Park. Spot Great Indian Bustard, desert fox, chinkara. Visit fossils park. Afternoon at leisure. Evening sunset at Sam Sand Dunes. Overnight stay.

**Day 3: Full Day Exploration**
Morning safari to different zone. Visit Amar Sagar and Gadsisar Lake for bird watching. Afternoon visit to local villages. Camel ride (optional). Overnight stay.

**Day 4: Departure**
Morning leisure or shopping in Jaisalmer. After breakfast, departure.',
        'difficulty_level' => 'easy',
        'photography_highlights' => 'Great Indian Bustards, desert foxes, blackbucks, chinkaras, sand dunes, desert landscapes, migratory birds, Jaisalmer Fort, sunrise/sunset over dunes'
    ],
    [
        'destination_name' => 'Pilibhit Tiger Reserve',
        'title' => 'Pilibhit Tiger Reserve 3 Nights - 4 Days',
        'slug' => 'pilibhit-tiger-reserve-3-nights-4-days',
        'duration_nights' => 3,
        'duration_days' => 4,
        'start_date' => '2026-11-29',
        'end_date' => '2026-12-02',
        'price' => 28999.00,
        'short_description' => 'Explore Uttar Pradesh\'s newest tiger reserve in the Terai region with dense sal forests and thriving wildlife.',
        'full_description' => 'Pilibhit Tiger Reserve in Uttar Pradesh is part of the Terai Arc Landscape. This relatively new reserve has gained attention for its healthy tiger population and successful conservation efforts. The dense sal and teak forests interspersed with tall grasslands create ideal habitat for tigers, leopards, swamp deer, and numerous other species. The park offers an off-beat wildlife experience away from tourist crowds.',
        'itinerary' => '**Day 1: Arrival at Pilibhit**
Arrive at Pilibhit (from Delhi/Lucknow). Transfer to forest lodge. Evening nature walk. Overnight stay.

**Day 2: Morning & Evening Safaris**
Early morning jeep safari in core zone. Return for breakfast. Rest during afternoon. Evening safari. Overnight stay.

**Day 3: Full Day Safari**
Full day safari with packed lunch. Explore different zones. Wildlife photography and tracking. Return evening. Overnight stay.

**Day 4: Morning Safari & Departure**
Final morning safari. After breakfast, departure to Pilibhit/Bareilly for onward journey.',
        'difficulty_level' => 'easy',
        'photography_highlights' => 'Bengal tigers, leopards, swamp deer, hog deer, elephants, fishing cats, bengal floricans, sal forests, grasslands'
    ],
    [
        'destination_name' => 'Tadoba-Andhari Tiger Reserve',
        'title' => 'Tadoba-Andhari Tiger Reserve',
        'slug' => 'tadoba-andhari-tiger-reserve',
        'duration_nights' => 3,
        'duration_days' => 4,
        'start_date' => '2026-12-06',
        'end_date' => '2026-12-09',
        'price' => 32999.00,
        'short_description' => 'Maharashtra\'s premier tiger reserve famous for frequent tiger sightings, including the legendary tigress Maya.',
        'full_description' => 'Tadoba-Andhari Tiger Reserve in Maharashtra is one of India\'s premier tiger reserves, known for its high tiger density and frequent sightings. The park gained fame through the legendary tigress Maya and her cubs. The varied landscape of teak forests, bamboo groves, and meadows around Tadoba Lake creates excellent wildlife habitat. The reserve is also home to leopards, sloth bears, wild dogs, and diverse birdlife.',
        'itinerary' => '**Day 1: Arrival at Tadoba**
Arrive at Chandrapur/Nagpur. Transfer to Tadoba. Check in to jungle resort. Evening at leisure. Overnight stay.

**Day 2: Morning & Afternoon Safaris**
Early morning jeep safari in Moharli zone. Return for breakfast. Afternoon safari in Tadoba zone. High tiger sighting chances. Overnight stay.

**Day 3: Full Day Safari Experience**
Morning safari in Kolara zone. Lunch at resort. Afternoon safari in different gate. Watch for Maya\'s lineage tigers. Overnight stay.

**Day 4: Morning Safari & Departure**
Final morning safari. After breakfast, departure to Chandrapur/Nagpur.',
        'difficulty_level' => 'easy',
        'photography_highlights' => 'Royal Bengal Tigers (Maya lineage), leopards, sloth bears, wild dogs, gaur, Tadoba Lake, honey buzzards, diverse birds, teak forests'
    ],
    [
        'destination_name' => 'Pench National Park',
        'title' => 'Pench National Park',
        'slug' => 'pench-national-park',
        'duration_nights' => 3,
        'duration_days' => 4,
        'start_date' => '2026-12-10',
        'end_date' => '2026-12-13',
        'price' => 30999.00,
        'short_description' => 'Visit the land that inspired Kipling\'s Jungle Book, home to tigers, leopards, and diverse wildlife across Madhya Pradesh-Maharashtra border.',
        'full_description' => 'Pench National Park, straddling Madhya Pradesh and Maharashtra, is the inspiration behind Rudyard Kipling\'s "The Jungle Book." Named after the Pench River flowing through it, the park offers excellent tiger sightings and diverse wildlife. The landscape of teak and bamboo forests with open meadows creates classic tiger country. Pench is known for its successful conservation and comfortable wildlife viewing experiences.',
        'itinerary' => '**Day 1: Arrival at Pench**
Arrive at Nagpur. Transfer to Pench (3 hours). Check in to resort. Evening nature walk. Overnight stay.

**Day 2: Pench Safaris**
Morning jeep safari in Turia or Karmajhiri gate. Return for breakfast. Afternoon safari in different zone. Mowgli land exploration. Overnight stay.

**Day 3: Full Day Wildlife Experience**
Morning safari. Mid-day visit to tribal village or Alikatta viewpoint. Afternoon safari. Evening documentary screening. Overnight stay.

**Day 4: Departure Safari**
Early morning final safari. After breakfast, departure to Nagpur.',
        'difficulty_level' => 'easy',
        'photography_highlights' => 'Bengal tigers, leopards, wild dogs, sloth bears, gaur, chital, nilgai, Pench River, Jungle Book landscapes, hornbills, eagles'
    ],
    [
        'destination_name' => 'Rann of Kutch',
        'title' => 'Rann of Kutch',
        'slug' => 'rann-of-kutch',
        'duration_nights' => 3,
        'duration_days' => 4,
        'start_date' => '2026-12-14',
        'end_date' => '2026-12-17',
        'price' => 33999.00,
        'short_description' => 'Experience the surreal white salt desert, vibrant Rann Utsav, wild ass sanctuary, and rich cultural heritage of Kutch.',
        'full_description' => 'The Great Rann of Kutch in Gujarat is a surreal landscape of white salt desert stretching endlessly. Visit during the Rann Utsav to experience vibrant cultural performances, handicraft bazaars, and the magical sight of moonlit white desert. The adjacent Wild Ass Sanctuary is home to the endangered Indian wild ass. Explore local villages known for traditional crafts, textiles, and warm hospitality.',
        'itinerary' => '**Day 1: Arrival at Bhuj - Rann**
Arrive at Bhuj. Visit Aina Mahal and Prag Mahal. Transfer to Rann area. Check in to resort/tent. Evening sunset at White Rann. Overnight stay.

**Day 2: Wild Ass Sanctuary & Villages**
Morning safari in Wild Ass Sanctuary. Visit Nirona village for handicrafts (Rogan art, copper bells). Afternoon visit to Kala Dungar (Black Hill). Evening cultural program at Rann Utsav. Overnight stay.

**Day 3: Full Day Exploration**
Visit Dhordo village, handicraft markets. Explore salt pans, bird watching. Afternoon visit to Khavda pottery village. Evening moonlight safari on White Rann. Overnight stay.

**Day 4: Bhuj & Departure**
Morning return to Bhuj. Visit Kutch Museum. Shopping for handicrafts. Departure.',
        'difficulty_level' => 'easy',
        'photography_highlights' => 'White salt desert, Indian wild ass, flamingos, full moon over Rann, cultural performances, traditional handicrafts, village life, sunset/sunrise landscapes'
    ],
    [
        'destination_name' => 'Sundarban National Park',
        'title' => 'Sundarban National Park - 2 Nights - 3 Days',
        'slug' => 'sundarban-national-park-2-nights-3-days-december',
        'duration_nights' => 2,
        'duration_days' => 3,
        'start_date' => '2026-12-20',
        'end_date' => '2026-12-22',
        'price' => 17999.00,
        'short_description' => 'Quick December getaway to Sundarbans for Royal Bengal Tiger safari in the mangrove wilderness.',
        'full_description' => 'A perfect short winter trip to experience the mystical Sundarbans. December offers pleasant weather for exploring the mangrove forests and spotting wildlife including the elusive Royal Bengal Tiger. Cruise through narrow creeks, visit watchtowers, and enjoy the unique tidal ecosystem.',
        'itinerary' => '**Day 1: Kolkata to Sundarbans**
Morning drive from Kolkata. Reach by afternoon. Evening boat cruise through creeks. Overnight stay.

**Day 2: Full Day Safari**
Early morning safari to Sajnekhali and Sudhanyakhali. Full day wildlife exploration. Watchtower visits. Overnight stay.

**Day 3: Return to Kolkata**
Morning bird watching. After breakfast, return journey to Kolkata.',
        'difficulty_level' => 'easy',
        'photography_highlights' => 'Royal Bengal Tigers, saltwater crocodiles, spotted deer, kingfishers, mangrove ecosystem, winter migratory birds'
    ],
    [
        'destination_name' => 'North Sikkim',
        'title' => 'North Sikkim - 5 Nights - 6 Days',
        'slug' => 'north-sikkim-5-nights-6-days',
        'duration_nights' => 5,
        'duration_days' => 6,
        'start_date' => '2026-12-25',
        'end_date' => '2026-12-30',
        'price' => 44999.00,
        'short_description' => 'Journey to the pristine North Sikkim with Gurudongmar Lake, Yumthang Valley, and stunning Himalayan landscapes.',
        'full_description' => 'North Sikkim is a remote paradise of pristine landscapes, sacred lakes, and snow-capped peaks. Visit the holy Gurudongmar Lake (17,800 ft), one of the highest lakes in the world. Explore Yumthang Valley with its hot springs and rhododendron forests. Experience the rich culture of Lachen and Lachung villages, witness spectacular mountain views, and visit ancient monasteries in this least explored region of Sikkim.',
        'itinerary' => '**Day 1: NJP/Bagdogra to Gangtok**
Pick up from NJP/Bagdogra. Drive to Gangtok (4-5 hours). Evening MG Road exploration. Overnight in Gangtok.

**Day 2: Gangtok to Lachen**
Drive to Lachen village (6 hours) through Chungthang. Visit Seven Sisters Waterfalls. Acclimatization. Overnight at homestay.

**Day 3: Gurudongmar Lake - Lachung**
Early morning excursion to Gurudongmar Lake (17,800 ft). One of world\'s highest lakes. Return to Lachen. Drive to Lachung. Overnight at homestay.

**Day 4: Yumthang Valley - Gangtok**
Morning visit to Yumthang Valley (Valley of Flowers). Hot springs. Zero Point (optional, subject to weather). Return to Gangtok. Overnight in Gangtok.

**Day 5: Gangtok Sightseeing**
Visit Tsomgo Lake, Baba Mandir, Nathula Pass (subject to permit and weather). Evening at leisure. Overnight in Gangtok.

**Day 6: Gangtok to NJP/Bagdogra**
After breakfast, drive to NJP/Bagdogra. Tour ends.',
        'difficulty_level' => 'challenging',
        'photography_highlights' => 'Gurudongmar Lake, Yumthang Valley, snow-capped Himalayas, Teesta River, waterfalls, monasteries, traditional villages, alpine flowers, Nathula Pass, Tsomgo Lake'
    ]
];

$successCount = 0;
$errorCount = 0;

echo "Creating " . count($toursData) . " tours from page 2 & 3...\n\n";

foreach ($toursData as $index => $data) {
    echo ($index + 1) . ". Creating: {$data['title']}... ";
    
    // Get or create destination
    $dest = $destModel->findBy('name', $data['destination_name']);
    if (!$dest) {
        $destId = $destModel->insert([
            'name' => $data['destination_name'],
            'slug' => strtolower(str_replace(' ', '-', $data['destination_name'])),
            'description' => $data['destination_name'],
            'status' => 'published'
        ]);
    } else {
        $destId = $dest['id'];
    }
    
    // Prepare tour data
    $tourData = [
        'title' => $data['title'],
        'slug' => $data['slug'],
        'destination_id' => $destId,
        'short_description' => $data['short_description'],
        'full_description' => $data['full_description'],
        'itinerary' => $data['itinerary'],
        'duration_days' => $data['duration_days'],
        'duration_nights' => $data['duration_nights'],
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date'],
        'price' => $data['price'],
        'max_participants' => 12,
        'difficulty_level' => $data['difficulty_level'],
        'included_services' => '- Accommodation (twin sharing)
- All meals (breakfast, lunch, dinner)
- All transfers and transportation
- All safaris/activities as per itinerary
- Experienced naturalist/guide
- All entry permits and fees
- All taxes and service charges',
        'excluded_services' => '- Transportation to/from starting point
- Personal expenses and beverages
- Camera and video fees
- Travel insurance
- Tips for guides and drivers
- Any meals during travel
- Additional activities not mentioned
- Emergency medical expenses',
        'accommodation_details' => 'Comfortable accommodation with modern amenities, attached bathrooms with hot water, and authentic local cuisine.',
        'photography_highlights' => $data['photography_highlights'],
        'status' => 'published',
        'featured' => 1,
        'display_order' => $index + 11,
        'meta_title' => $data['title'] . ' - Wildlife & Nature Tour',
        'meta_description' => substr($data['short_description'], 0, 155),
        'meta_keywords' => strtolower($data['destination_name']) . ', tour, wildlife, nature, photography'
    ];
    
    try {
        $tourId = $tour->insert($tourData);
        if ($tourId) {
            echo "SUCCESS (ID: $tourId)\n";
            $successCount++;
        } else {
            echo "FAILED\n";
            $errorCount++;
        }
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        $errorCount++;
    }
}

echo "\n========================================\n";
echo "Tour Creation Complete!\n";
echo "Success: $successCount tours\n";
echo "Failed: $errorCount tours\n";
echo "========================================\n";
echo "\nAll tours from page 2 & 3 are now available!\n";
?>
