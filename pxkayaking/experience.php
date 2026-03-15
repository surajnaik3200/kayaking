<?php $page = 'experience'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Experiences | PX Kayaking</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="nav">
        <div class="nav-inner">
            <div class="brand">PX Kayaking</div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a class="<?php echo $page === 'experience' ? 'active' : ''; ?>" href="experience.php">Experiences</a>
                <a href="contact.php" class="cta">Book Now</a>
            </div>
        </div>
    </nav>

    <section class="section">
        <h2>Pick your paddle</h2>
        <div class="cards">
            <div class="card">
                <div class="tag">90 mins</div>
                <h3>Sunrise Creek Drift</h3>
                <p>Launch at first light from Nakeri bridge, watch the Canaguinim estuary wake up, and glide back as the village stirs.</p>
            </div>
            <div class="card">
                <div class="tag">2 hours · sea permitting</div>
                <h3>Sea Kayaking</h3>
                <p>2-hour open-sea paddle when the sea is calm—west-coast sunset lines when conditions allow. We confirm every launch by phone.</p>
                <a class="cta" href="contact.php#seacall">Call to check the sea</a>
            </div>
            <div class="card">
                <div class="tag">75 mins</div>
                <h3>Twilight Glow</h3>
                <p>Pastel skies to starlight with a slow, steady loop and hot chai at the jetty.</p>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>What to expect</h2>
        <div class="cards">
            <div class="card">
                <h3>Inclusive gear</h3>
                <p>Kayak, paddle, PFD, dry bag, headlamps for low light trips, and water refills included.</p>
            </div>
            <div class="card">
                <h3>Pre-trip briefing</h3>
                <p>10-minute dockside intro covering paddling basics, signals, and local etiquette.</p>
            </div>
            <div class="card">
                <h3>Photo-friendly</h3>
                <p>Guides help with safe photo stops and share the best lookout points on the Canaguinim creek.</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        Ready to launch? <a href="contact.php" class="cta">Book your slot</a>
    </footer>
</body>
</html>







