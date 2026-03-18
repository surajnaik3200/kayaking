<?php
$page = 'experience';
$site = require __DIR__ . '/inc/site.php';
$experiences = $site['experiences'];
require __DIR__ . '/inc/header.php';
?>

    <section class="section">
        <h2>Pick your paddle</h2>
        <div class="cards">
            <?php foreach ($experiences as $experience): ?>
                <div class="card reveal">
                    <div class="tag"><?= htmlspecialchars($experience['tag']) ?></div>
                    <h3><?= htmlspecialchars($experience['title']) ?></h3>
                    <p><?= htmlspecialchars($experience['description']) ?></p>
                    <?php if (stripos($experience['title'] ?? '', 'sea') !== false): ?>
                        <a class="cta" href="contact.php#seacall">Call to check the sea</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section">
        <h2>What to expect</h2>
        <div class="cards">
            <div class="card reveal">
                <h3>Inclusive gear</h3>
                <p>Kayak, paddle, PFD, dry bag, headlamps for low light trips, and water refills included.</p>
            </div>
            <div class="card reveal">
                <h3>Pre-trip briefing</h3>
                <p>10-minute dockside intro covering paddling basics, signals, and local etiquette.</p>
            </div>
            <div class="card reveal">
                <h3>Photo-friendly</h3>
                <p>Guides help with safe photo stops and share the best lookout points on the Canaguinim creek.</p>
            </div>
        </div>
    </section>

<?php require __DIR__ . '/inc/footer.php';
?>







