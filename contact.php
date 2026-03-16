<?php $page = 'contact'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | PX Kayaking</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="nav">
        <div class="nav-inner">
            <div class="brand">PX Kayaking</div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="experience.php">Experiences</a>
                <a class="<?php echo $page === 'contact' ? 'active' : ''; ?> cta" href="contact.php">Book Now</a>
            </div>
        </div>
    </nav>

    <section class="section">
        <h2>Book your paddle</h2>
        <p id="seacall">Sea kayaking runs only when the sea is calm. Call to check conditions first: <a href="tel:9822277190">9822277190</a> / <a href="tel:9822156672">9822156672</a>. We'll confirm your slot once the sea is favourable.</p>
        <p>Tell us when you’d like to paddle and we’ll confirm with a quick call or WhatsApp.</p>
        <div class="contact-grid">
            <form action="send.php" method="POST">
                <input type="text" name="name" placeholder="Full name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="tel" name="phone" placeholder="Phone / WhatsApp" required>
                <input type="date" name="date" required>
                <div class="time-range">
                    <input type="time" name="time_start" aria-label="Start time" required>
                    <span class="time-separator">to</span>
                    <input type="time" name="time_end" aria-label="End time" required>
                </div>
                <textarea name="message" placeholder="Group size, preferences, anything we should know"></textarea>
                <button type="submit" class="btn">Submit booking request</button>
            </form>
            <div class="card">
                <div class="tag">Canaguinim</div>
                <h3>Launch point</h3>
                <p>Near Canaguinim beach(little beach), Canaguinim, South Goa 403703 (search “PX Kayaking” on Maps).</p>
                <h3>How to reach</h3>
                <p>Park by the beach for free , our team meets you near the road.</p>
                <h3>Operating hours</h3>
                <p>9:00 AM to 6:00 PM · Daily</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        We reply within one working hour · PX Kayaking, South Goa
    </footer>
</body>
</html>



