<?php $page = 'about'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | PX Kayaking</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="nav">
        <div class="nav-inner">
            <div class="brand">PX Kayaking</div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a class="<?php echo $page === 'about' ? 'active' : ''; ?>" href="about.php">About</a>
                <a href="experience.php">Experiences</a>
                <a href="contact.php" class="cta">Book Now</a>
            </div>
        </div>
    </nav>

    <section class="section">
        <h2>Our story</h2>
        <p>PX Kayaking is rooted in the Canaguinim backwaters near Nakeri bridge. We grew up fishing and paddling these calm creeks and now guide guests through them—plus sea sessions when the west coast is gentle—with a focus on safety, ecology, and warm Goan hospitality.</p>
        <p>Every paddle is capped at small group sizes, with gear checked daily. We carry first-aid, throw lines, and radio contact on every trip.</p>
    </section>

    <section class="section">
        <h2>Your hosts</h2>
        <div class="cards">
            <div class="card">
                <div class="tag">Lead guide</div>
                <h3>Local PX crew</h3>
                <p>Goa-born paddlers who know every tide change, bird call, and safe channel near Canaguinim.</p>
            </div>
            <div class="card">
                <div class="tag">Safety</div>
                <h3>On-water support</h3>
                <p>Lifeguard-certified team ensures every guest is briefed, fitted, and confident before launch.</p>
            </div>
            <div class="card">
                <div class="tag">Logistics</div>
                <h3>Dockside helpers</h3>
                <p>Shuttle and shore crew keep your gear ready, and provide a safety briefing.</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        See you on the water — PX Kayaking, Canaguinim
    </footer>
</body>
</html>

