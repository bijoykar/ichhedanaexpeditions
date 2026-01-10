<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/classes/Model.php';
require_once 'includes/classes/Review.php';

$review = new Review();

// First, delete all existing reviews
try {
    $db = Database::getInstance()->getConnection();
    $db->exec("DELETE FROM reviews");
    echo "✓ Cleared all existing reviews\n\n";
} catch (Exception $e) {
    echo "Error clearing reviews: " . $e->getMessage() . "\n";
    exit;
}

// Reviews data from ichhedanaexpeditions.com
$reviewsData = [
    [
        'name' => 'Anirban Sanyal',
        'rating' => 5,
        'comment' => 'The last Tour completed with Ichhedana was to North Sikkim in the Last January. The tour came out with bouquet of unforgettable sweet experiences besides some amazing company and colorful shots..all the credits and thanks duly acknowledged to Surajit babu..',
        'tour_name' => 'North Sikkim'
    ],
    [
        'name' => 'Subhendu Barman',
        'rating' => 5,
        'comment' => 'When an Wildlife photographer become a tour operator you will definitely get something extraordinary and exciting. Definitely you will enjoy and get the most',
        'tour_name' => null
    ],
    [
        'name' => 'Akshay Rao',
        'rating' => 5,
        'comment' => 'This was my first trip to the Sunderban. Thank you Surajit for this wonderful trip. You managed everything really nicely and were very considerate to our needs. Our guide and other staff were excellent too. I will revisit Sunderban soon through you. Thanks again',
        'tour_name' => 'Sundarban'
    ],
    [
        'name' => 'S. Sirish Kumar',
        'rating' => 5,
        'comment' => 'For me this is my first trip to the Sunderban. A wonderful experience with like minded birders. Superb spotting by Surajit. Friendly and polite boatmen and staff, who know the area very well. Delicious food was the icing on the cake!! Thank you Surajit and Team!!!',
        'tour_name' => 'Sundarban'
    ],
    [
        'name' => 'Amlan Chatterjee',
        'rating' => 5,
        'comment' => 'Few of our colleagues planned a short trip to Sunderban with IchheDana tourism during Holi. The trip was planned beautifully and it was a successful trip with excellent sightings along with Royal Bengal Tiger. Completely satisfied with the services of Ichhe Dana Tourism and definitely looking forward to future tours with them',
        'tour_name' => 'Sundarban'
    ],
    [
        'name' => 'Goutam Mitra',
        'rating' => 5,
        'comment' => 'I had three tours with Ichhe Dana Tourism "Wings of Desire", Sunderban and later to Mangalajodi and recently to Bhitarkanika National Park, from 22nd to 25th March\'2018! It was really awesome to travel with them. All the arrangements, Food, Safaris, Guide knowledge and services were really fantastic!!! Surajit Sarkar took care of all the minutest details during the tours. I will be going with them again as and when opportunity comes!',
        'tour_name' => 'Bhitarkanika'
    ],
    [
        'name' => 'Writam Porel',
        'rating' => 4,
        'comment' => 'Pack your bag and follow Mr. Surajit you will not be disappointed, rather be sure to have a great time....',
        'tour_name' => null
    ],
    [
        'name' => 'Chiranjib Dutta',
        'rating' => 5,
        'comment' => 'We had a wonderful trip to Sundarban Tiger Reserve in August, 2018. Everything about the trip was planned perfectly, and executed almost to perfection, would recommend Icche Dana to interested people.',
        'tour_name' => 'Sundarban'
    ],
    [
        'name' => 'Sudipto Banerjee',
        'rating' => 4,
        'comment' => 'Superb experience with Surajit, great planning and execution. wish you all the very best guys....look fwd to more in the days to come',
        'tour_name' => null
    ],
    [
        'name' => 'Chandra Mouli Roy Chowdhury',
        'rating' => 5,
        'comment' => 'I meet Surajit through Arindam and I went on a trip to sundarban with him. What I noticed is his care n uttermost sense of responsibilty towards his guest. He is very friendly and I never felt him as an acquitance rather a close friend. We have the best of the time in sunderban and spotted some rare species...I strongly recommmed you to avail his services and see yourself....',
        'tour_name' => 'Sundarban'
    ],
    [
        'name' => 'Abanti Goswami',
        'rating' => 4,
        'comment' => 'Great experience with Ichhe Dana on a trip to Sundarban, looking forward for more',
        'tour_name' => 'Sundarban'
    ],
    [
        'name' => 'Smita Goyal Gupta',
        'rating' => 4,
        'comment' => 'we went to Sundarbans from 18-20th January 2019 with Icche Dana. Surajit took care for every detail to make awesome experience.',
        'tour_name' => 'Sundarban'
    ],
    [
        'name' => 'Shounak Lahiri',
        'rating' => 5,
        'comment' => 'Ichhe Dana tourism is hugely different from other famous wildlife operators. Surajit, who is a professional photographer himself, runs the show here. Before getting into professional details what must be said at first is Surajit is a great guy to be with and be friends with. Professionally he is astute with his work, leaves no stone unturned to take care of the guest and strives to ensure an elusive thing in this field, \'Customer Delight\'. Being a professional photographer he also is able to help with shooting techniques etc....especially for learners like me. Thanks to Ichhe Dana for a great trip. I recommend it highly to other enthusiasts.',
        'tour_name' => null
    ],
    [
        'name' => 'Gargi Gupta',
        'rating' => 5,
        'comment' => 'I have recently done a prolonged desired trip to TATR. It was an amazing trip. When i started the trip I was unknown to everyone in the team. But the team organiser Surajit, who is an amazing person, a patient guide, never let me feel that, rather he managed the trip so well that i would like to tag along with Ichhe Dana once more. Ofcorse along with Surajit I got a chance to interact with other people as well who are good photographers. Hope to learn from them.',
        'tour_name' => 'Tadoba'
    ],
    [
        'name' => 'Adhirup Ghosh',
        'rating' => 4,
        'comment' => 'IchheDana is a fantastic experience arranged by Surajit Sarkar and his team. The arrangements during our recent Sundarban trip including food and logistics were 1st grade. The provided wildlife naturalists were thorough professionals with great experience in spotting mammals and birds in the wild. Surajit Sarkar, makes sure no one remains uncomfortable with his great communications skill. We spotted 85 species of birds, mammals and reptiles during our short stay in Sundarbans. The trips with IchheDana is equally pocket friendly as well. I strongly recommend all of you to experience you wildlife trips with Ichhe Dana. you will not be disappointed...',
        'tour_name' => 'Sundarban'
    ],
    [
        'name' => 'Neel Ray',
        'rating' => 5,
        'comment' => 'We had a fantastic trip to Sundarbans with "IchheDana Tourism". Initially I was bit sceptical, since we were travelling with our 4yrs old kid. But the trip turned out to be one of my best ones, thanks to Surajit for his super awesome hospitality and super friendly nature. We absolutely had no issues with our little one and thoroughly enjoyed every bit of it. The food definitely requires a big shout out.Thank you very much Surajit for this wonderful trip....Very soon will meet again...Cheers...',
        'tour_name' => 'Sundarban'
    ],
    [
        'name' => 'Sohini Pyne',
        'rating' => 5,
        'comment' => 'Went to Sunderbans with Icche Dana Tourism for three days and had the most relaxing time. Learnt so much about birdwatching and had a fun time recognising so many different species! The tour organiser was also very friendly and took great care of us at every moment! His enthusiasm and passion for this profession could be clearly seen in the way he conducted the tour and showed us around!',
        'tour_name' => 'Sundarban'
    ],
    [
        'name' => 'Nabarun Majumdar',
        'rating' => 4,
        'comment' => 'A great guide and friend with excellent hospitality towards guests.',
        'tour_name' => null
    ],
    [
        'name' => 'Prakash Roy',
        'rating' => 5,
        'comment' => 'I went on a birding trip to Darjeeling with IchheDana "Wings of Desire" in Feb 2023, and I must say it was an incredible experience! The team was very knowledgeable and passionate about birding as well as other wildlife watching, and they made sure we had a successful trip. We were able to see some rare birds that I never thought I would see in my lifetime, and it was truly a magical experience. Apart from the birding, we had lots of fun exploring the beautiful landscapes of Darjeeling. The accommodations provided by the IchheDana group were outstanding, and we were able to relax and unwind after a long day of birding. Overall, I highly recommend the IchheDana group for anyone looking to go on a birding trip in Darjeeling. They are professional, knowledgeable, and passionate about what they do, and they will make sure you have an unforgettable experience. Thank you, Ichhedana and Surajit Sarkar, for an amazing trip!',
        'tour_name' => 'Darjeeling Birding'
    ],
    [
        'name' => 'Somnath Goswami',
        'rating' => 5,
        'comment' => 'A very efficient and experienced outfit, led by Surjit Sarkar who is honest, hardworking and very well-behaved. They always use the services of the best of properties and personnel. I whole-heartedly recommend them, at all times and every time.',
        'tour_name' => null
    ]
];

$successCount = 0;
$errorCount = 0;

echo "Inserting " . count($reviewsData) . " reviews...\n\n";

foreach ($reviewsData as $index => $data) {
    echo ($index + 1) . ". Adding review from {$data['name']}... ";
    
    $reviewData = [
        'customer_name' => $data['name'],
        'customer_email' => strtolower(str_replace(' ', '.', $data['name'])) . '@customer.com',
        'rating' => $data['rating'],
        'review_text' => $data['comment'],
        'review_date' => date('Y-m-d'),
        'status' => 'approved',
        'featured' => ($data['rating'] == 5) ? 1 : 0
    ];
    
    try {
        $reviewId = $review->insert($reviewData);
        if ($reviewId) {
            echo "SUCCESS (ID: $reviewId, Rating: {$data['rating']} stars)\n";
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
echo "Review Import Complete!\n";
echo "Success: $successCount reviews\n";
echo "Failed: $errorCount reviews\n";
echo "========================================\n";
echo "\n5-star reviews: " . count(array_filter($reviewsData, function($r) { return $r['rating'] == 5; })) . "\n";
echo "4-star reviews: " . count(array_filter($reviewsData, function($r) { return $r['rating'] == 4; })) . "\n";
echo "\nAll reviews are now approved and ready to display!\n";
?>
