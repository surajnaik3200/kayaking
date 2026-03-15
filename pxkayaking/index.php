
<?php $page = "home"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PX Kayaking | South Goa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="nav">
        <div class="nav-inner">
            <div class="brand">PX Kayaking</div>
            <div class="nav-links">
                <a class="<?php echo $page === 'home' ? 'active' : ''; ?>" href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="experience.php">Experiences</a>
                <a href="contact.php" class="cta">Book Now</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-card">
            <div class="hero-badge">Canaguinim Backwaters</div>
            <h1>Kayak calm backwaters under coconut trees in South Goa.</h1>
            <p>Guided routes near Nakeri bridge through shaded creeks, mirror-flat water, and golden-hour horizons. Small groups, safety-first gear, and hosts who know every bend of Canaguinim's backwaters.</p>
            <ul class="hero-list">
                <li>- Certified local guides</li>
                <li>- Beginner-friendly & family-safe</li>
                <li>- Eco-aware routes, zero-plastic policy</li>
            </ul>
            <a class="cta" href="contact.php">Book your paddle</a>
        </div>
        <div class="hero-visual">
            <div class="hero-slider">
                <div class="slide slide-1"></div>
                <div class="slide slide-2"></div>
                <div class="slide slide-3"></div>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Why paddle with us</h2>
        <div class="cards">
            <div class="card">
                <div class="tag">Safety first</div>
                <h3>Top-tier equipment</h3>
                <p>Premium sit-on-top kayaks, buoyancy aids in multiple sizes, and pre-trip briefings tailored to your comfort.</p>
            </div>
            <div class="card">
                <div class="tag">Local love</div>
                <h3>Goan-born crew</h3>
                <p>Guides who grew up paddling Canaguinim share stories, wildlife spots, and the best sunset angles.</p>
            </div>
            <div class="card">
                <div class="tag">Flexible</div>
                <h3>Custom slots</h3>
                <p>Early bird, golden hour, or moonlit paddles - pick a time that fits your trip. Group and private tours available.</p>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Popular experiences</h2>
        <div class="cards">
            <div class="card">
                <h3>Sunrise Creek Drift</h3>
                <p>Soft light over the Canaguinim creek, quiet waters, and bird calls - perfect for first-timers and photo lovers.</p>
            </div>
            <div class="card">
                <h3>Calm Backwater Trail</h3>
                <p>Glide across Canaguinim's shaded backwaters and learn how this estuary thrives away from the open sea.</p>
            </div>
            <div class="card">
                <h3>Twilight Glow Paddle</h3>
                <p>Chase West Coast sunsets and pastel skies into dusk, ending with chai by the village jetty.</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        (c) <?php echo date('Y'); ?> PX Kayaking -- Canaguinim, South Goa
    </footer>
</body>
</html>
