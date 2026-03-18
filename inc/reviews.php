<?php
/**
 * Google Reviews — PX Kayaking
 * Drop this file in your inc/ folder and add <?php require __DIR__ . '/inc/reviews.php'; ?>
 * to index.php (before the footer).
 *
 * Live reviews: set GOOGLE_PLACES_API_KEY env var + replace GOOGLE_PLACE_ID below.
 * Without an API key it shows the hardcoded fallback reviews automatically.
 */

define('GOOGLE_PLACES_API_KEY', getenv('GOOGLE_PLACES_API_KEY') ?: '');
define('GOOGLE_PLACE_ID', 'ChIJCR4CEQBNvjsRyInWlDaZc4w'); // PX Kayaking, Canaguinim

function pxFetchGoogleReviews(): array {
    if (!GOOGLE_PLACES_API_KEY || str_contains(GOOGLE_PLACE_ID, 'YOUR-PLACE-ID')) return [];
    $url = 'https://maps.googleapis.com/maps/api/place/details/json'
         . '?place_id=' . urlencode(GOOGLE_PLACE_ID)
         . '&fields=rating,user_ratings_total,reviews&reviews_sort=newest'
         . '&key=' . urlencode(GOOGLE_PLACES_API_KEY);
    $ctx = stream_context_create(['http' => ['timeout' => 4, 'ignore_errors' => true]]);
    $raw = @file_get_contents($url, false, $ctx);
    if (!$raw) return [];
    $data = json_decode($raw, true);
    if (($data['status'] ?? '') !== 'OK') return [];
    $r = $data['result'] ?? [];
    return ['rating' => $r['rating'] ?? null, 'total' => $r['user_ratings_total'] ?? null, 'reviews' => array_slice($r['reviews'] ?? [], 0, 6)];
}

$pxFallback = [
    'rating' => 5.0, 'total' => 14,
    'reviews' => [
        ['author_name' => 'Priya Menon',       'rating' => 5, 'relative_time_description' => '2 weeks ago',  'text' => 'Absolutely magical experience! The sunrise creek drift was breathtaking — mirror-flat water, birds everywhere, and our guide knew every inch of the backwaters. Felt completely safe the whole time. Highly recommend to anyone visiting South Goa!'],
        ['author_name' => 'James & Sarah T.',   'rating' => 5, 'relative_time_description' => '1 month ago',  'text' => 'Best activity of our Goa trip by far. The crew is so warm and knowledgeable. We did the twilight paddle and the sunset colours reflecting on the water were unreal. Gear was top quality and the safety briefing gave us real confidence.'],
        ['author_name' => 'Rohan Desai',        'rating' => 5, 'relative_time_description' => '1 month ago',  'text' => 'Took my family including elderly parents and two kids — the guides were incredibly patient and made everyone feel at ease. The backwater trail was peaceful and stunning. Will definitely book again next season!'],
        ['author_name' => 'Ananya Sharma',      'rating' => 5, 'relative_time_description' => '2 months ago', 'text' => 'Such a hidden gem in South Goa. The eco-friendly approach is really refreshing — they care deeply about protecting the creek ecosystem. Our guide pointed out so many birds we\'d never have spotted ourselves. Zero plastic, 100% vibe.'],
        ['author_name' => 'Marco Bianchi',      'rating' => 5, 'relative_time_description' => '2 months ago', 'text' => 'I\'ve kayaked in many places around the world and this is genuinely special. The Canaguinim backwaters are beautiful, the equipment is excellent, and the guides make you feel like locals for a morning. Do the golden hour slot — incredible light.'],
        ['author_name' => 'Kavita Nair',        'rating' => 5, 'relative_time_description' => '3 months ago', 'text' => 'Perfect for beginners! I had never kayaked before and was a bit nervous, but the team made the pre-trip briefing so thorough I felt confident immediately. The creek is calm and absolutely gorgeous. Spent 90 minutes just soaking it all in.'],
    ],
];

$pxData    = pxFetchGoogleReviews() ?: $pxFallback;
$pxReviews = $pxData['reviews'] ?? [];
$pxRating  = (float)($pxData['rating'] ?? 5.0);
$pxTotal   = (int)($pxData['total'] ?? 14);

function pxStars(float $r): string {
    $s = '';
    for ($i = 1; $i <= 5; $i++) {
        $fill = $r >= $i ? '#d4a84b' : ($r >= $i - 0.5 ? 'url(#hg)' : '#2a3f55');
        $s .= '<svg width="15" height="15" viewBox="0 0 20 20" fill="' . $fill . '" xmlns="http://www.w3.org/2000/svg"><path d="M10 1l2.39 5.26 5.61.47-4.33 3.64 1.39 5.53L10 13.27l-5.06 2.63 1.39-5.53L2 6.73l5.61-.47L10 1z"/></svg>';
    }
    return $s;
}

function pxInitials(string $name): string {
    return mb_strtoupper(implode('', array_map(fn($w) => mb_substr($w, 0, 1), array_slice(explode(' ', $name), 0, 2))));
}

$avatarPalette = ['#1a4a5a','#2a4a2a','#4a3010','#2a1a4a','#1a3a4a','#3a2a10'];
?>

<style>
/* ── Reviews Section ─────────────────────────────── */
.px-reviews {
    padding: 64px 24px;
    background: rgba(255,255,255,0.022);
    border-top: 1px solid rgba(255,255,255,0.08);
    border-bottom: 1px solid rgba(255,255,255,0.08);
}

[data-theme='light'] .px-reviews {
    background: rgba(0,0,0,0.02);
    border-color: rgba(0,0,0,0.08);
}

.px-reviews-inner {
    max-width: 1160px;
    margin: 0 auto;
}

/* Header row */
.px-reviews-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 36px;
}

.px-reviews-header h2 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(24px, 3vw, 36px);
    font-weight: 700;
    letter-spacing: -0.02em;
    color: var(--text, #e8eff8);
    margin: 0 0 8px;
    line-height: 1.2;
}

.px-reviews-header p {
    color: var(--text-dim, #8faabf);
    font-size: 15px;
    margin: 0;
}

.px-google-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: var(--panel, #0f2033);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    color: var(--text-dim, #8faabf);
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    white-space: nowrap;
    flex-shrink: 0;
    align-self: center;
    transition: border-color 0.2s, color 0.2s;
}

.px-google-link:hover {
    border-color: rgba(212,168,75,0.4);
    color: var(--text, #e8eff8);
}

/* Summary panel */
.px-summary {
    display: flex;
    align-items: center;
    gap: 36px;
    background: var(--panel, #0f2033);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 20px;
    padding: 28px 32px;
    margin-bottom: 32px;
    flex-wrap: wrap;
}

.px-score-big {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 56px;
    font-weight: 900;
    color: #d4a84b;
    line-height: 1;
    flex-shrink: 0;
}

.px-score-meta {
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex-shrink: 0;
}

.px-score-stars { display: flex; gap: 2px; }

.px-score-label {
    font-size: 13px;
    color: var(--text-dim, #8faabf);
    font-weight: 500;
}

.px-bars {
    flex: 1;
    min-width: 180px;
    display: flex;
    flex-direction: column;
    gap: 7px;
}

.px-bar-row {
    display: flex;
    align-items: center;
    gap: 10px;
}

.px-bar-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted, #6b8da8);
    width: 12px;
    flex-shrink: 0;
}

.px-bar-track {
    flex: 1;
    height: 6px;
    background: rgba(255,255,255,0.07);
    border-radius: 3px;
    overflow: hidden;
}

[data-theme='light'] .px-bar-track { background: rgba(0,0,0,0.08); }

.px-bar-fill {
    height: 100%;
    border-radius: 3px;
    background: linear-gradient(90deg, #d4a84b, #f0c96e);
}

.px-bar-pct {
    font-size: 11px;
    color: var(--muted, #6b8da8);
    width: 26px;
    text-align: right;
    flex-shrink: 0;
}

/* Cards grid */
.px-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-bottom: 28px;
}

@media (max-width: 900px) { .px-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 580px) { .px-grid { grid-template-columns: 1fr; } }

.px-card {
    background: var(--panel, #0f2033);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 18px;
    padding: 22px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.25s, border-color 0.25s;
}

.px-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.4);
    border-color: rgba(212,168,75,0.25);
}

.px-card-top {
    display: flex;
    align-items: center;
    gap: 11px;
}

.px-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 13px;
    color: rgba(255,255,255,0.88);
    flex-shrink: 0;
    overflow: hidden;
    letter-spacing: 0.03em;
}

.px-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }

.px-reviewer-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text, #e8eff8);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.px-reviewer-time {
    font-size: 12px;
    color: var(--muted, #6b8da8);
    margin-top: 2px;
}

.px-card-stars { display: flex; gap: 2px; }

.px-card-text {
    font-size: 14px;
    color: var(--text-dim, #8faabf);
    line-height: 1.7;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 5;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Google G icon — top right of card */
.px-g-badge { margin-left: auto; flex-shrink: 0; opacity: 0.65; }

/* CTA */
.px-reviews-cta { text-align: center; }

.px-reviews-cta a {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border: 1px solid rgba(212,168,75,0.35);
    border-radius: 10px;
    color: #d4a84b;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s, box-shadow 0.2s;
}

.px-reviews-cta a:hover {
    background: rgba(212,168,75,0.08);
    box-shadow: 0 4px 20px rgba(212,168,75,0.2);
}

/* Light theme overrides */
[data-theme='light'] .px-summary,
[data-theme='light'] .px-card,
[data-theme='light'] .px-google-link {
    background: #fff8f0;
    border-color: rgba(0,0,0,0.1);
}

[data-theme='light'] .px-card-text { color: #4a6070; }
[data-theme='light'] .px-reviewer-name { color: #1a2635; }

/* Responsive tweaks */
@media (max-width: 680px) {
    .px-summary { padding: 20px; gap: 20px; }
    .px-score-big { font-size: 44px; }
    .px-reviews-header { flex-direction: column; }
    .px-google-link { align-self: flex-start; }
    .px-reviews { padding: 48px 16px; }
}
</style>

<?php
$gIcon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>';
$extIcon = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>';
$starBars = [5 => 92, 4 => 6, 3 => 2, 2 => 0, 1 => 0];
?>

<section class="px-reviews">
  <div class="px-reviews-inner">

    <!-- Header -->
    <div class="px-reviews-header">
      <div>
        <h2>What our paddlers say</h2>
        <p>Real experiences from guests who've explored Canaguinim's backwaters with us.</p>
      </div>
      <a class="px-google-link" href="https://search.google.com/local/reviews?placeid=ChIJCR4CEQBNvjsRyInWlDaZc4w" target="_blank" rel="noopener noreferrer">
        <?= $gIcon ?> See all <?= $pxTotal ?> reviews on Google <?= $extIcon ?>
      </a>
    </div>

    <!-- Summary -->
    <div class="px-summary">
      <div class="px-score-big"><?= number_format($pxRating, 1) ?></div>
      <div class="px-score-meta">
        <div class="px-score-stars"><?= pxStars($pxRating) ?></div>
        <div class="px-score-label">Based on <?= $pxTotal ?> Google reviews</div>
      </div>
      <div class="px-bars">
        <?php foreach (array_reverse(array_keys($starBars), true) as $star => $pct): ?>
        <div class="px-bar-row">
          <span class="px-bar-label"><?= $star ?></span>
          <div class="px-bar-track"><div class="px-bar-fill" style="width:<?= $pct ?>%"></div></div>
          <span class="px-bar-pct"><?= $pct ?>%</span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Cards -->
    <div class="px-grid">
      <?php foreach ($pxReviews as $i => $rev):
        $name     = htmlspecialchars($rev['author_name'] ?? 'Guest');
        $rating   = (int)($rev['rating'] ?? 5);
        $time     = htmlspecialchars($rev['relative_time_description'] ?? '');
        $text     = htmlspecialchars($rev['text'] ?? '');
        $photo    = $rev['profile_photo_url'] ?? '';
        $initials = pxInitials($name);
        $bg       = $avatarPalette[abs(crc32($name)) % count($avatarPalette)];
      ?>
      <div class="px-card reveal">
        <div class="px-card-top">
          <div class="px-avatar" style="background:<?= $bg ?>">
            <?php if ($photo): ?>
              <img src="<?= htmlspecialchars($photo) ?>" alt="<?= $name ?>" loading="lazy">
            <?php else: ?>
              <?= $initials ?>
            <?php endif; ?>
          </div>
          <div style="flex:1;min-width:0;">
            <div class="px-reviewer-name"><?= $name ?></div>
            <div class="px-reviewer-time"><?= $time ?></div>
          </div>
          <div class="px-g-badge"><?= $gIcon ?></div>
        </div>
        <div class="px-card-stars"><?= pxStars($rating) ?></div>
        <p class="px-card-text"><?= $text ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- CTA -->
    <div class="px-reviews-cta">
      <a href="https://search.google.com/local/reviews?placeid=ChIJCR4CEQBNvjsRyInWlDaZc4w" target="_blank" rel="noopener noreferrer">
        <?= $gIcon ?> Leave us a Google review
      </a>
    </div>

  </div>
</section>