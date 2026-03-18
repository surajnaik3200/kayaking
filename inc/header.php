<?php
/**
 * Header template.
 *
 * Requires:
 *   - $page: string key for active nav item (e.g. 'home', 'about', 'experience', 'contact')
 *   - optional $pageTitle: custom title (defaults to site name + page)
 */

$site = require __DIR__ . '/site.php';

// Ensure the page key is set for nav highlighting.
$page = $page ?? 'home';

$pageTitle = $pageTitle ?? ($site['siteName'] . ($page !== 'home' ? ' | ' . ucfirst($page) : ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="hero2/logo/logo.png" type="image/png">
</head>
<body class="page-bg">
    <nav class="nav">
        <div class="nav-inner">
            <a class="brand" href="index.php">
                <img class="brand-logo" src="hero2/logo/logo.png" alt="<?= htmlspecialchars($site['siteName']) ?> logo" width="120" height="30" aria-hidden="true">
                <span class="brand-text"><?= htmlspecialchars($site['siteName']) ?></span>
            </a>
            <div class="nav-actions">
                <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
                    <span class="nav-toggle-bar"></span>
                    <span class="nav-toggle-bar"></span>
                    <span class="nav-toggle-bar"></span>
                </button>
            </div>
            <div class="nav-links">
                <?php foreach ($site['nav'] as $navItem):
                    $active = ($navItem['key'] ?? '') === $page ? 'active' : '';
                    $classes = trim(($navItem['class'] ?? '') . ' ' . $active);
                ?>
                    <a class="<?= htmlspecialchars($classes) ?>" href="<?= htmlspecialchars($navItem['href']) ?>"><?= htmlspecialchars($navItem['label']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </nav>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navToggle = document.querySelector('.nav-toggle');
            const navLinks = document.querySelector('.nav-links');
            if (navToggle && navLinks) {
                navToggle.addEventListener('click', () => {
                    const expanded = navToggle.getAttribute('aria-expanded') === 'true';
                    navToggle.setAttribute('aria-expanded', String(!expanded));
                    navLinks.classList.toggle('nav-open');
                });
            }

            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = themeToggle?.querySelector('.theme-icon');
            const root = document.documentElement;

            const setTheme = (theme) => {
                root.dataset.theme = theme;
                localStorage.setItem('pxTheme', theme);
                if (themeIcon) themeIcon.textContent = theme === 'dark' ? '🌙' : '☀️';
            };

            const savedTheme = localStorage.getItem('pxTheme');
            const prefersDark = window.matchMedia?.('(prefers-color-scheme: dark)').matches;
            setTheme(savedTheme || (prefersDark ? 'dark' : 'light'));

            themeToggle?.addEventListener('click', () => {
                const next = root.dataset.theme === 'dark' ? 'light' : 'dark';
                setTheme(next);
            });

            // Scroll reveal (simple, lightweight)
            const revealElements = document.querySelectorAll('.reveal');
            if ('IntersectionObserver' in window && revealElements.length) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('revealed');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.15 });
                revealElements.forEach((el) => observer.observe(el));
            } else {
                revealElements.forEach((el) => el.classList.add('revealed'));
            }
        });
    </script>
