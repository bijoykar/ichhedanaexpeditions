<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/classes/Model.php';
require_once __DIR__ . '/includes/classes/Tour.php';

$tourModel = new Tour();

// Meghalaya Tour
$meghalaya = [
    'title' => 'Meghalaya - 3 Nights - 4 Days',
    'slug' => 'meghalaya-3-nights-4-days',
    'short_description' => 'Experience the Scotland of the East with stunning landscapes, living root bridges, and cascading waterfalls in this immersive photography expedition.',
    'full_description' => 'Discover Meghalaya\'s enchanting beauty through your lens. This carefully curated 4-day photography tour takes you through misty hills, crystal-clear rivers, and the iconic living root bridges. Capture the essence of Cherrapunji, one of the wettest places on Earth, and witness the spectacular Nohkalikai Falls. Perfect for landscape and nature photography enthusiasts.',
    'itinerary' => "Day 1: Arrival in Guwahati - Drive to Shillong (3-4 hours). Check-in at hotel. Evening visit to Ward's Lake and Police Bazaar. Sunset photography at Shillong Peak.\n\nDay 2: Shillong to Cherrapunji. Visit Elephant Falls, Shillong View Point. Drive to Cherrapunji. Visit Nohkalikai Falls (India's tallest plunge waterfall), Seven Sisters Falls, and Mawsmai Cave. Photography session during golden hour.\n\nDay 3: Full day at Living Root Bridges. Trek to Double Decker Living Root Bridge (3000+ steps). Capture the unique bioengineering marvel. Visit nearby waterfalls and natural pools. Return trek and evening at leisure.\n\nDay 4: Morning visit to Dawki River (crystal clear waters). Photography at Umngot River. Drive back to Guwahati. Departure.",
    'duration_days' => 4,
    'duration_nights' => 3,
    'start_date' => '2026-03-15',
    'end_date' => '2026-03-18',
    'price' => 24999.00,
    'max_participants' => 12,
    'difficulty_level' => 'moderate',
    'included_services' => 'Accommodation (3 nights in comfortable hotels/resorts), All meals (breakfast, lunch, dinner), Transportation in private vehicle, Professional photography guide, All entry fees and permits, Mineral water during travel',
    'excluded_services' => 'Personal expenses, Camera equipment and accessories, Insurance, Any meals not mentioned, Tips and gratuities, Anything not mentioned in inclusions',
    'accommodation_details' => '3 nights accommodation in well-appointed hotels/resorts with modern amenities. Rooms with attached bathrooms, hot water, and WiFi. Properties selected for comfort and proximity to photography locations.',
    'photography_highlights' => 'Living Root Bridges (unique bioengineering), Nohkalikai Falls (India\'s tallest plunge waterfall), Seven Sisters Falls, Dawki River (crystal clear waters), Mawsmai Cave, Misty landscapes, Cascading waterfalls, Lush green valleys, Traditional Khasi villages',
    'status' => 'published',
    'featured' => 1,
    'display_order' => 1
];

// Neora Valley Tour
$neoraValley = [
    'title' => 'Neora Valley National Park - 4 Days - 5 Nights',
    'slug' => 'neora-valley-national-park-4-days-5-nights',
    'short_description' => 'Explore the pristine biodiversity hotspot of Neora Valley, home to red pandas, exotic birds, and untouched Eastern Himalayan forests.',
    'full_description' => 'Neora Valley National Park is one of the richest biological zones in Eastern India. This 5-day expedition offers unparalleled opportunities to photograph rare wildlife including red pandas, Himalayan black bears, clouded leopards, and over 265 bird species. Trek through dense virgin forests, capture misty mountain vistas, and experience the tranquility of the Eastern Himalayas.',
    'itinerary' => "Day 1: Arrival at Bagdogra/NJP. Drive to Lava (5-6 hours). Check-in at forest lodge. Evening birding session and acclimatization walk. Night stay at Lava.\n\nDay 2: Early morning birding at Lava forest. Visit Neora Valley viewpoint. Photography session capturing Himalayan landscapes. Afternoon trek to nearby waterfalls. Sunset photography. Night stay at Lava.\n\nDay 3: Full day wildlife safari in Neora Valley National Park. Morning and afternoon safari sessions with professional naturalist. Focus on red panda habitat areas, bird photography, and landscape shots. Packed lunch in the forest. Night stay at Lava.\n\nDay 4: Morning trek to Changey Falls. Photography session at the falls and surrounding areas. Visit Lava Monastery and local village. Cultural photography opportunities. Evening at leisure. Night stay at Lava.\n\nDay 5: Early morning final birding session. Breakfast and check-out. Drive to Bagdogra/NJP. Departure with memories of pristine wilderness.",
    'duration_days' => 5,
    'duration_nights' => 4,
    'start_date' => '2026-04-10',
    'end_date' => '2026-04-14',
    'price' => 29999.00,
    'max_participants' => 10,
    'difficulty_level' => 'moderate',
    'included_services' => 'Accommodation (4 nights in forest lodge/eco-resort), All meals (breakfast, lunch, dinner), Transportation in private vehicle, Professional wildlife photographer/naturalist guide, All national park entry fees and permits, Safari charges (2 safaris), Mineral water during travel',
    'excluded_services' => 'Personal expenses, Camera equipment and lenses, Travel insurance, Alcoholic beverages, Tips for guide and driver, Any expenses due to unforeseen circumstances, Anything not mentioned in inclusions',
    'accommodation_details' => '4 nights accommodation in comfortable forest lodge or eco-resort near Lava. Basic but clean rooms with attached bathrooms. Hot water availability. Proximity to forest for early morning wildlife photography.',
    'photography_highlights' => 'Red Panda (rare and endangered), Himalayan Black Bear, Clouded Leopard, 265+ bird species including Satyr Tragopan, Blood Pheasant, Rufous-necked Hornbill, Virgin Eastern Himalayan forests, Misty mountain landscapes, Changey Falls, Alpine meadows, Rhododendron forests, Traditional Lepcha culture',
    'status' => 'published',
    'featured' => 1,
    'display_order' => 2
];

try {
    // Check if tours already exist
    $existingMeghalaya = $tourModel->findBy('slug', 'meghalaya-3-nights-4-days');
    $existingNeora = $tourModel->findBy('slug', 'neora-valley-national-park-4-days-5-nights');
    
    if (!$existingMeghalaya) {
        $tourModel->insert($meghalaya);
        echo "✓ Meghalaya tour inserted successfully!\n";
    } else {
        echo "⚠ Meghalaya tour already exists.\n";
    }
    
    if (!$existingNeora) {
        $tourModel->insert($neoraValley);
        echo "✓ Neora Valley tour inserted successfully!\n";
    } else {
        echo "⚠ Neora Valley tour already exists.\n";
    }
    
    echo "\nTour data insertion completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
