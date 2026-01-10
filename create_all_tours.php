<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/classes/Model.php';
require_once 'includes/classes/Tour.php';
require_once 'includes/classes/Destination.php';

$tour = new Tour();
$destModel = new Destination();

// Tours to create from ichhedanaexpeditions.com
$toursData = [
    [
        'destination_name' => 'Sundarban National Park',
        'title' => 'Sundarban National Park - 4 Nights - 5 Days',
        'slug' => 'sundarban-national-park-4-nights-5-days',
        'duration_nights' => 4,
        'duration_days' => 5,
        'start_date' => '2026-01-08',
        'end_date' => '2026-01-12',
        'price' => 24999.00,
        'short_description' => 'Explore the world\'s largest mangrove forest and home to the Royal Bengal Tiger in the UNESCO World Heritage Site of Sundarbans.',
        'full_description' => 'The Sundarbans, a UNESCO World Heritage Site, is the world\'s largest mangrove forest and home to the majestic Royal Bengal Tiger. This 5-day expedition takes you deep into this unique ecosystem where land meets sea. Navigate through narrow creeks, spot crocodiles basking on mudbanks, witness diverse bird species, and if lucky, catch a glimpse of the elusive Bengal tiger. Experience village life, learn about honey collectors, and understand the delicate balance of this tidal ecosystem.',
        'itinerary' => '**Day 1: Kolkata to Sundarbans**
Drive from Kolkata to Sundarbans (3-4 hours). Check into resort. Evening orientation and nature walk around resort area. Dinner and overnight.

**Day 2: Full Day Jungle Safari**
Early morning boat safari through narrow creeks and rivers. Visit Sajnekhali Watchtower and Museum. Wildlife spotting including crocodiles, deer, birds. Packed lunch during safari. Return evening. Overnight stay.

**Day 3: Sudhanyakhali & Dobanki**
Visit Sudhanyakhali watchtower for tiger spotting. Explore Dobanki with its canopy walk. Bird watching session. Evening cultural program. Overnight stay.

**Day 4: Village Tour & Safari**
Morning visit to local village, interact with honey collectors. Boat safari to different zones. Wildlife photography. Evening at leisure. Overnight stay.

**Day 5: Departure**
Early morning bird watching. After breakfast, departure to Kolkata.',
        'difficulty_level' => 'easy',
        'photography_highlights' => 'Royal Bengal Tigers, saltwater crocodiles, spotted deer, wild boars, numerous bird species including kingfishers and herons, mangrove forests, village life'
    ],
    [
        'destination_name' => 'Sundarban National Park',
        'title' => 'Sundarban National Park - 2 Nights - 3 Days - February',
        'slug' => 'sundarban-national-park-2-nights-3-days-february',
        'duration_nights' => 2,
        'duration_days' => 3,
        'start_date' => '2026-02-15',
        'end_date' => '2026-02-17',
        'price' => 16999.00,
        'short_description' => 'Quick weekend getaway to Sundarbans mangrove forest with boat safaris and wildlife spotting.',
        'full_description' => 'Perfect weekend escape to the mysterious Sundarbans. Experience the thrill of navigating through dense mangrove forests, spotting wildlife from watchtowers, and cruising along tidal rivers in search of the Royal Bengal Tiger.',
        'itinerary' => '**Day 1: Kolkata to Sundarbans**
Morning departure from Kolkata. Reach resort by afternoon. Evening boat cruise. Overnight stay.

**Day 2: Full Day Safari**
Early morning safari to Sajnekhali and Sudhanyakhali watchtowers. Wildlife spotting throughout the day. Evening return. Overnight stay.

**Day 3: Departure**
Morning bird watching session. After breakfast, return to Kolkata.',
        'difficulty_level' => 'easy',
        'photography_highlights' => 'Tigers, crocodiles, mangrove ecosystem, birds, sunrise/sunset over creeks'
    ],
    [
        'destination_name' => 'Manas National Park',
        'title' => 'Manas National Park 3 Nights-4 Days',
        'slug' => 'manas-national-park-3-nights-4-days',
        'duration_nights' => 3,
        'duration_days' => 4,
        'start_date' => '2026-03-14',
        'end_date' => '2026-03-17',
        'price' => 28999.00,
        'short_description' => 'Discover UNESCO World Heritage Manas National Park with its incredible biodiversity including tigers, elephants, and rare golden langurs.',
        'full_description' => 'Manas National Park, a UNESCO World Heritage Site in Assam, is known for its spectacular biodiversity. Home to tigers, Indian rhinoceros, elephants, wild water buffalo, and the rare golden langur, this pristine wilderness offers unforgettable wildlife encounters. The park is set against the backdrop of the Bhutan hills with the Manas River flowing through it.',
        'itinerary' => '**Day 1: Arrival at Manas**
Arrive at Barpeta Road station. Transfer to Manas (1.5 hours). Check in to jungle resort. Evening nature walk. Overnight stay.

**Day 2: Full Day Jeep Safari**
Morning and afternoon jeep safaris in different zones. Spot tigers, rhinos, elephants, golden langurs. Packed lunch. Evening at resort. Overnight stay.

**Day 3: Elephant Safari & River Rafting**
Early morning elephant safari for close wildlife encounters. After breakfast, river rafting on Manas River (optional). Afternoon jeep safari. Overnight stay.

**Day 4: Departure**
Morning bird watching. After breakfast, transfer to Barpeta Road for onward journey.',
        'difficulty_level' => 'moderate',
        'photography_highlights' => 'Tigers, Indian rhinos, elephants, golden langurs, wild buffalo, pygmy hogs, hispid hares, hornbills, Manas River, Bhutan foothills'
    ],
    [
        'destination_name' => 'Jim Corbett National Park',
        'title' => 'Jim Corbett National Park - 3 Nights 4 Days',
        'slug' => 'jim-corbett-national-park-3-nights-4-days',
        'duration_nights' => 3,
        'duration_days' => 4,
        'start_date' => '2026-04-03',
        'end_date' => '2026-04-06',
        'price' => 26999.00,
        'short_description' => 'Visit India\'s oldest national park, home to the majestic Bengal tiger and diverse wildlife in the Himalayan foothills.',
        'full_description' => 'Jim Corbett National Park, established in 1936, is India\'s oldest national park and a haven for wildlife enthusiasts. Located in Uttarakhand\'s Himalayan foothills, this park is famous for its healthy tiger population. The diverse landscape includes hills, riverine belts, marshy depressions, grasslands, and forests creating perfect habitat for tigers, elephants, leopards, and over 600 bird species.',
        'itinerary' => '**Day 1: Arrival at Corbett**
Arrive at Ramnagar. Check in to resort near park. Evening visit to Corbett Museum. Overnight stay.

**Day 2: Dhikala Zone Safari**
Full day excursion to Dhikala zone (most famous for tiger sightings). Jeep safari through diverse landscapes. Packed lunch. Return evening. Overnight stay.

**Day 3: Bijrani Zone & Garjiya Temple**
Morning safari in Bijrani zone. Return for lunch. Afternoon visit to Garjiya Devi Temple on Kosi River. Evening at leisure. Overnight stay.

**Day 4: Departure**
Optional early morning safari (additional cost). After breakfast, departure.',
        'difficulty_level' => 'easy',
        'photography_highlights' => 'Bengal tigers, Asian elephants, leopards, sloth bears, deer species, 600+ birds including hornbills, Ramganga River, sal forests'
    ],
    [
        'destination_name' => 'Singalila National Park',
        'title' => 'Singalila National Park - 5 Nights - 6 Days',
        'slug' => 'singalila-national-park-5-nights-6-days',
        'duration_nights' => 5,
        'duration_days' => 6,
        'start_date' => '2026-04-12',
        'end_date' => '2026-04-17',
        'price' => 35999.00,
        'short_description' => 'Trek along the Indo-Nepal border with stunning Himalayan views including Mt. Everest, Kanchenjunga, and rare red pandas.',
        'full_description' => 'Singalila National Park trek offers one of the most spectacular mountain views in the world. Walk along the Singalila Ridge on the Indo-Nepal border with panoramic views of four of the world\'s five highest peaks: Mt. Everest, Kanchenjunga, Lhotse, and Makalu. The park is home to the endangered red panda, Himalayan black bear, and diverse alpine flora including rhododendrons. This high-altitude trek combines natural beauty with wildlife adventure.',
        'itinerary' => '**Day 1: NJP/Bagdogra to Manebhanjan**
Pick up from NJP/Bagdogra. Drive to Manebhanjan (3 hours). Acclimatization. Overnight at guesthouse.

**Day 2: Manebhanjan to Tonglu (10,130 ft)**
Trek begins through forests and villages. First Himalayan views. 4-5 hours trek. Overnight at lodge.

**Day 3: Tonglu to Sandakphu (11,930 ft)**
Trek through rhododendron forests. Reach Sandakphu - highest point of trek. Stunning 360° mountain views. 5-6 hours. Overnight at trekker\'s hut.

**Day 4: Sandakphu to Phalut (11,810 ft)**
Trek along Indo-Nepal border. Views of Mt. Everest, Kanchenjunga. Wildlife spotting opportunities. 6 hours. Overnight at trekker\'s hut.

**Day 5: Phalut to Rimbik**
Descend through forests. Pass through Gorkhey village. Possible red panda sighting. 6-7 hours. Overnight at guesthouse.

**Day 6: Rimbik to NJP/Bagdogra**
Drive back to NJP/Bagdogra. Tour ends.',
        'difficulty_level' => 'challenging',
        'photography_highlights' => 'Mt. Everest, Kanchenjunga, Lhotse, Makalu, red pandas, Himalayan black bears, blood pheasants, rhododendron forests, sunrise over Himalayas, Indo-Nepal border ridge'
    ],
    [
        'destination_name' => 'Bhitarkanika National Park',
        'title' => 'Bhitarkanika National Park - 2 Nights - 3 Days',
        'slug' => 'bhitarkanika-national-park-2-nights-3-days',
        'duration_nights' => 2,
        'duration_days' => 3,
        'start_date' => '2026-04-19',
        'end_date' => '2026-04-21',
        'price' => 18999.00,
        'short_description' => 'Explore Odisha\'s mangrove paradise known for giant saltwater crocodiles and diverse birdlife.',
        'full_description' => 'Bhitarkanika National Park in Odisha is India\'s second-largest mangrove ecosystem after Sundarbans. Famous for its giant saltwater crocodiles (some over 20 feet long), this pristine sanctuary is a paradise for nature lovers. The park hosts thousands of migratory birds, rare olive ridley sea turtles nest nearby, and the waterways offer serene boat safaris through dense mangroves.',
        'itinerary' => '**Day 1: Arrival at Bhitarkanika**
Reach Chandbali/Bhadrak. Transfer to Bhitarkanika. Check in to forest rest house. Evening boat ride. Overnight stay.

**Day 2: Full Day Safari**
Early morning boat safari to Dangamal and Bagagahana. Spot giant crocodiles, birds, and explore mangrove creeks. Visit crocodile breeding center. Overnight stay.

**Day 3: Departure**
Morning bird watching. After breakfast, departure.',
        'difficulty_level' => 'easy',
        'photography_highlights' => 'Giant saltwater crocodiles, white crocodiles, king cobras, water monitor lizards, migratory birds, mangrove ecosystem, olive ridley turtles (seasonal)'
    ],
    [
        'destination_name' => 'Eaglenest Wildlife Sanctuary',
        'title' => 'Eaglenest Wildlife Sanctuary - 8 Nights - 9 Days',
        'slug' => 'eaglenest-wildlife-sanctuary-8-nights-9-days',
        'duration_nights' => 8,
        'duration_days' => 9,
        'start_date' => '2026-04-26',
        'end_date' => '2026-05-04',
        'price' => 72999.00,
        'short_description' => 'Ultimate birding paradise in Arunachal Pradesh with over 500 bird species including rare Bugun liocichla.',
        'full_description' => 'Eaglenest Wildlife Sanctuary in Arunachal Pradesh is a birder\'s paradise and one of the world\'s top birding destinations. With over 500 bird species recorded, including many rare and endemic species like the Bugun liocichla (discovered in 2006), this sanctuary offers unparalleled opportunities for serious birdwatchers and nature photographers. The varying altitudes from 500m to 3250m create diverse habitats ranging from subtropical forests to temperate zones.',
        'itinerary' => '**Day 1: Guwahati to Tezpur**
Pick up from Guwahati. Drive to Tezpur (4 hours). Overnight at hotel.

**Day 2: Tezpur to Eaglenest**
Drive to Eaglenest (5-6 hours). Check in to forest rest house/camp. Evening acclimatization. Overnight stay.

**Day 3-4: Eaglenest Lower Elevations**
Early morning birding at lower elevations (500-1500m). Target species include Ward\'s trogon, beautiful nuthatch, rufous-necked hornbill. Full day birding excursions. Overnight at same location.

**Day 5-6: Eaglenest Mid Elevations**
Move to mid-elevation zone. Birding sessions for species like fire-tailed myzornis, red-tailed laughingthrush, yellow-rumped honeyguide. Overnight at camps.

**Day 7-8: Eaglenest Upper Elevations & Sela Pass**
Excursion to higher altitudes. Target temperate forest species. Visit Sela Pass (13,700 ft) for high-altitude species. Overnight at Eaglenest.

**Day 9: Eaglenest to Guwahati**
Early morning final birding session. Long drive back to Guwahati. Tour ends.',
        'difficulty_level' => 'challenging',
        'photography_highlights' => 'Over 500 bird species, Bugun liocichla, Ward\'s trogon, Beautiful nuthatch, hornbills, pheasants, laughingthrushes, pristine forests, mountain landscapes, Sela Pass'
    ],
    [
        'destination_name' => 'Bandhavgarh National Park',
        'title' => 'Bandhavgarh National Forest - 3 Nights - 4 Days',
        'slug' => 'bandhavgarh-national-forest-3-nights-4-days',
        'duration_nights' => 3,
        'duration_days' => 4,
        'start_date' => '2026-05-10',
        'end_date' => '2026-05-13',
        'price' => 31999.00,
        'short_description' => 'Experience one of India\'s highest tiger density parks with excellent sighting opportunities and ancient fort ruins.',
        'full_description' => 'Bandhavgarh National Park in Madhya Pradesh boasts one of the highest tiger densities in India, making it one of the best places to spot wild tigers. The park is set against the backdrop of ancient fort ruins and scattered with numerous caves featuring ancient inscriptions. Besides tigers, the park is home to leopards, sloth bears, various deer species, and over 250 bird species. The varied topography of hills, valleys, and meadows creates perfect tiger habitat.',
        'itinerary' => '**Day 1: Arrival at Bandhavgarh**
Arrive at Umaria/Jabalpur. Transfer to Bandhavgarh. Check in to wildlife resort. Evening at leisure. Overnight stay.

**Day 2: Morning & Afternoon Safaris**
Early morning jeep safari in Tala zone (best for tigers). Return for breakfast. Afternoon safari in Magadhi/Khitauli zone. High chances of tiger sightings. Overnight stay.

**Day 3: Full Day Safari & Fort Visit**
Morning safari. After lunch, visit Bandhavgarh Fort ruins (subject to permissions). Evening safari. Overnight stay.

**Day 4: Morning Safari & Departure**
Final morning safari. After breakfast and checkout, transfer to Umaria/Jabalpur.',
        'difficulty_level' => 'easy',
        'photography_highlights' => 'Royal Bengal Tigers (highest density), leopards, sloth bears, deer species, ancient fort ruins, caves with inscriptions, diverse birdlife, sal forests'
    ]
];

$successCount = 0;
$errorCount = 0;

echo "Creating " . count($toursData) . " tours...\n\n";

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
- All safaris/activities as per itinerary
- Experienced naturalist guide
- All entry permits and fees
- Transportation during tour
- All taxes and service charges',
        'excluded_services' => '- Transportation to/from starting point
- Personal expenses and beverages
- Camera and video fees
- Travel insurance
- Tips for guides and drivers
- Any meals during travel days
- Additional activities not mentioned
- Emergency medical expenses',
        'accommodation_details' => 'Comfortable lodges/resorts with modern amenities, attached bathrooms with hot water, and authentic local cuisine.',
        'photography_highlights' => $data['photography_highlights'],
        'status' => 'published',
        'featured' => 1,
        'display_order' => $index + 3,
        'meta_title' => $data['title'] . ' - Wildlife Photography Tour',
        'meta_description' => substr($data['short_description'], 0, 155),
        'meta_keywords' => strtolower($data['destination_name']) . ', wildlife tour, photography, nature'
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
echo "\nAll tours are now available on your website!\n";
?>
