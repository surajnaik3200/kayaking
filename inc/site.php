<?php

/**
 * Site configuration (data-driven).
 *
 * Use this file to keep content in one place and make the site more dynamic.
 *
 * This file provides a hardcoded fallback, but will load experiences from the
 * database when the connection succeeds.
 */

$site = [
    'siteName' => 'PX Kayaking',

    // Primary navigation links used in the header
    'nav' => [
        ['key' => 'home', 'label' => 'Home', 'href' => 'index.php'],
        ['key' => 'about', 'label' => 'About', 'href' => 'about.php'],
        ['key' => 'experience', 'label' => 'Experiences', 'href' => 'experience.php'],
        ['key' => 'contact', 'label' => 'Book Now', 'href' => 'contact.php', 'class' => 'cta'],
    ],

    // Default experiences used when the database isn't available
    'experiences' => [
        [
            'id' => 1,
            'title' => 'Sunrise Creek Drift',
            'tag' => '90 mins',
            'description' => 'Soft light over the Canaguinim creek, quiet waters, and bird calls — perfect for first-timers and photo lovers.',
        ],
        [
            'id' => 2,
            'title' => 'Calm Backwater Trail',
            'tag' => '1.5 hours',
            'description' => 'Glide across Canaguinim’s shaded backwaters and learn how this estuary thrives away from the open sea.',
        ],
        [
            'id' => 3,
            'title' => 'Twilight Glow Paddle',
            'tag' => '75 mins',
            'description' => 'Chase West Coast sunsets and pastel skies into dusk, ending with chai by the village jetty.',
        ],
        [
            'id' => 4,
            'title' => 'Sea Kayaking (weather dependent)',
            'tag' => '2 hours',
            'description' => 'Sea sessions when the coast is calm — we confirm the launch by phone or WhatsApp before you head out.',
        ],
    ],

    'contact' => [
        'office' => [
            'phone' => '9822277190',
            'altPhone' => '9822156672',
            'location' => 'Canaguinim beach (little beach), Canaguinim, South Goa 403703',
            'hours' => '9:00 AM to 6:00 PM – Daily',
        ],
    ],
];

// Try to load the latest experiences from the database if possible.
try {
    require_once __DIR__ . '/../db.php';
    if (!empty($conn)) {
        $dbExperiences = [];
        $result = mysqli_query($conn, "SELECT id, title, tag, description FROM experiences ORDER BY id ASC");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $dbExperiences[] = [
                    'id' => (int) $row['id'],
                    'title' => $row['title'],
                    'tag' => $row['tag'],
                    'description' => $row['description'],
                ];
            }
        }

        if (!empty($dbExperiences)) {
            $site['experiences'] = $dbExperiences;
        }
    }
} catch (Throwable $e) {
    // Ignore. We keep the fallback experience list.
}

return $site;
