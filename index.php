
<?php
$page = 'home';
$site = require __DIR__ . '/inc/site.php';
$experiences = $site['experiences'];
require __DIR__ . '/inc/header.php';
?>

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
                <?php
                // Dynamically load slider images from the hero2 folder.
                $heroDir = __DIR__ . '/hero2';
                $slides = [];
                if (is_dir($heroDir)) {
                    $files = scandir($heroDir);
                    foreach ($files as $file) {
                        if (preg_match('/\.(jpe?g|png|gif)$/i', $file)) {
                            $slides[] = 'hero2/' . $file;
                        }
                    }
                }

                // Ensure we always have at least one slide.
                if (empty($slides)) {
                    $slides = ['hero2/k1.png'];
                }

                foreach ($slides as $idx => $slide):
                    $isActive = $idx === 0;
                ?>
                    <img class="slide" src="<?= htmlspecialchars($slide) ?>" alt="Kayaking <?= $idx + 1 ?>" style="display:<?= $isActive ? 'block' : 'none' ?>; width:100%; border-radius:16px; position:absolute; top:0; left:0; transition:opacity 1s; opacity:<?= $isActive ? '1' : '0' ?>;">
                <?php endforeach; ?>
            </div>
            <script>
                const slides = document.querySelectorAll('.hero-slider .slide');
                let current = 0;
                setInterval(() => {
                    slides[current].style.opacity = 0;
                    slides[current].style.display = 'none';
                    current = (current + 1) % slides.length;
                    slides[current].style.display = 'block';
                    slides[current].style.opacity = 1;
                }, 3000);
            </script>
        </div>
    </section>

    <section class="section">
        <h2>Why paddle with us</h2>
        <div class="cards">
            <div class="card reveal">
                <div class="tag">Safety first</div>
                <h3>Top-tier equipment</h3>
                <p>Premium sit-on-top kayaks, buoyancy aids in multiple sizes, and pre-trip briefings tailored to your comfort.</p>
            </div>
            <div class="card reveal">
                <div class="tag">Local love</div>
                <h3>Goan-born crew</h3>
                <p>Guides who grew up paddling Canaguinim share stories, wildlife spots, and the best sunset angles.</p>
            </div>
            <div class="card reveal">
                <div class="tag">Flexible</div>
                <h3>Custom slots</h3>
                <p>Early bird, golden hour, or moonlit paddles - pick a time that fits your trip. Group and private tours available.</p>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Popular experiences</h2>
        <div class="cards">
            <?php foreach (array_slice($experiences, 0, 3) as $experience): ?>
                <div class="card reveal">
                    <div class="tag"><?= htmlspecialchars($experience['tag']) ?></div>
                    <h3><?= htmlspecialchars($experience['title']) ?></h3>
                    <p><?= htmlspecialchars($experience['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

<?php require __DIR__ . '/inc/reviews.php'; ?>

<?php
require __DIR__ . '/inc/footer.php';
?>
