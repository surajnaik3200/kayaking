<?php
/**
 * Footer template used across the site.
 * Secret: click the copyright year 5 times quickly to access management.
 */
$site = $site ?? (require __DIR__ . '/site.php');
?>

    <footer class="footer">
        <div class="footer-brand">
            <img class="footer-logo" src="hero2/logo/logo.png" alt="<?= htmlspecialchars($site['siteName']) ?> logo" width="120" height="30" aria-hidden="true">
            <div>
                <div class="footer-site-name"><?= htmlspecialchars($site['siteName']) ?></div>
                <div class="footer-copy">
                    &copy; <span id="secret-trigger" title=""><?= date('Y') ?></span>
                    <?= htmlspecialchars($site['siteName']) ?> — Canaguinim, South Goa
                </div>
            </div>
        </div>
        <button id="themeToggle" class="theme-toggle fixed-theme" type="button" aria-label="Toggle dark mode">
            <span class="theme-icon" aria-hidden="true">🌙</span>
        </button>
    </footer>

    <script>
        // ── Secret management access ──────────────────────────────
        // Click the year in the footer copyright 5 times within 3 seconds
        (function () {
            const trigger = document.getElementById('secret-trigger');
            if (!trigger) return;

            let clicks = 0;
            let timer  = null;

            trigger.style.cursor = 'default';

            trigger.addEventListener('click', () => {
                clicks++;
                clearTimeout(timer);

                if (clicks >= 5) {
                    clicks = 0;
                    window.location.href = 'management.php';
                    return;
                }

                // Reset after 3 seconds of inactivity
                timer = setTimeout(() => { clicks = 0; }, 3000);
            });
        })();
    </script>
</body>
</html>