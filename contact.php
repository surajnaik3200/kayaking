<?php
$page = 'contact';
$site = require __DIR__ . '/inc/site.php';
$experiences = $site['experiences'];
$contactInfo = $site['contact']['office'];
require __DIR__ . '/inc/header.php';
?>

    <section class="section">
        <h2>Book your paddle</h2>
        <p id="seacall">Sea kayaking runs only when the sea is calm. Call to check conditions first: <a href="tel:<?= htmlspecialchars($contactInfo['phone']) ?>"><?= htmlspecialchars($contactInfo['phone']) ?></a> / <a href="tel:<?= htmlspecialchars($contactInfo['altPhone']) ?>"><?= htmlspecialchars($contactInfo['altPhone']) ?></a>. We'll confirm your slot once the sea is favourable.</p>
        <p>Tell us when you'd like to paddle and we’ll confirm with a quick call or WhatsApp.</p>
        <div class="contact-grid">
            <form action="send.php" method="POST" class="reveal">
                <input type="text" name="name" placeholder="Full name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="tel" name="phone" placeholder="Phone / WhatsApp" required>
                <input type="date" name="date" required>
                <div class="time-range">
                    <input type="time" name="time_start" aria-label="Start time" required>
                    <span class="time-separator">to</span>
                    <input type="time" name="time_end" aria-label="End time" required>
                </div>
                <label for="experience_id">Which experience?</label>
                <select id="experience_id" name="experience_id">
                    <option value="">Choose an experience (optional)</option>
                    <?php foreach ($experiences as $experience): ?>
                        <option value="<?= htmlspecialchars($experience['id']) ?>"><?= htmlspecialchars($experience['title']) ?></option>
                    <?php endforeach; ?>
                </select>
                <textarea name="message" placeholder="Group size, preferences, anything we should know"></textarea>
                <button type="submit" class="btn">Submit booking request</button>
            </form>
            <div class="card reveal">
                <div class="tag">Canaguinim</div>
                <h3>Launch point</h3>
                <p><?= htmlspecialchars($contactInfo['location']) ?> (search “PX Kayaking” on Maps).</p>
                <h3>How to reach</h3>
                <p>Park by the beach for free, our team meets you near the road.</p>
                <h3>Operating hours</h3>
                <p><?= htmlspecialchars($contactInfo['hours']) ?></p>
            </div>
        </div>
    </section>

<?php require __DIR__ . '/inc/footer.php';
?>



