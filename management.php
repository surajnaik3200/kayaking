<?php
session_start();
require_once 'db.php';

$dbErrorMessage = null;
if (!$conn) {
    $dbErrorMessage = 'Database unavailable: ' . ($dbError ?? 'unknown error');
}

// ── Login handler ────────────────────────────────────────────
if (!isset($_SESSION['logged_in_user'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $loginError = 'Please enter your username and password.';
        } elseif (!$conn) {
            $loginError = 'Database unavailable — cannot authenticate right now.';
        } else {
            $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? LIMIT 1");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $user   = $result ? mysqli_fetch_assoc($result) : null;
                if ($user) {
                    $stored = $user['password'];
                    $ok = password_verify($password, $stored);
                    if (!$ok && $password === $stored) {
                        $ok = true;
                        $newHash = password_hash($password, PASSWORD_DEFAULT);
                        mysqli_query($conn, "UPDATE users SET password = '" . mysqli_real_escape_string($conn, $newHash) . "' WHERE id = " . intval($user['id']));
                    }
                    if ($ok) {
                        $_SESSION['logged_in_user'] = $username;
                        header('Location: management.php');
                        exit;
                    }
                }
            }
            $loginError = 'Incorrect username or password.';
        }
    }
    // ── Login page ───────────────────────────────────────────
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PX Kayaking</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="hero2/logo/logo.png" type="image/png">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; }
        .login-wrap {
            width: 100%;
            max-width: 400px;
            padding: 24px;
        }
        .login-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 44px 40px 36px;
            box-shadow: 0 24px 80px rgba(0,0,0,0.5);
            text-align: center;
        }
        .login-logo {
            width: 100px;
            margin: 0 auto 20px;
            opacity: 0.9;
        }
        .login-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 6px;
        }
        .login-sub {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 28px;
        }
        .login-fields { display:flex; flex-direction:column; gap:12px; text-align:left; }
        .login-fields input { font-size: 15px; }
        .login-btn {
            width: 100%;
            margin-top: 6px;
            padding: 13px;
            font-size: 15px;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: #0a1508;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 18px var(--accent-glow);
            transition: transform 0.15s, box-shadow 0.15s;
            font-family: var(--font-body);
        }
        .login-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 28px var(--accent-glow); }
        .login-error {
            background: rgba(248,113,113,0.1);
            border: 1px solid rgba(248,113,113,0.25);
            border-radius: 10px;
            color: #f87171;
            font-size: 13px;
            padding: 10px 14px;
            margin-bottom: 14px;
            text-align: left;
        }
        .login-back {
            display: inline-block;
            margin-top: 20px;
            font-size: 13px;
            color: var(--muted);
            transition: color 0.2s;
        }
        .login-back:hover { color: var(--text); }
    </style>
</head>
<body>
    <div class="login-wrap">
        <div class="login-card">
            <img class="login-logo" src="hero2/logo/logo.png" alt="PX Kayaking">
            <div class="login-title">Management Portal</div>
            <div class="login-sub">PX Kayaking — Staff access only</div>
            <?php if (isset($loginError)): ?>
                <div class="login-error"><?= htmlspecialchars($loginError) ?></div>
            <?php endif; ?>
            <form method="post" class="login-fields">
                <input type="text" name="username" placeholder="Username" required autofocus>
                <input type="password" name="password" placeholder="Password" required>
                <button class="login-btn" type="submit">Sign in →</button>
            </form>
            <a class="login-back" href="index.php">← Back to website</a>
        </div>
    </div>
</body>
</html>
    <?php
    exit;
}

// ── Dashboard ────────────────────────────────────────────────
$bookings = [];
$dashError = null;

if ($conn) {
    $result = mysqli_query($conn, "SELECT * FROM inquiries ORDER BY created_at DESC");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $bookings[] = $row;
        }
    } else {
        $dashError = 'Could not load bookings: ' . mysqli_error($conn);
    }
} else {
    $dashError = $dbErrorMessage;
}

$site = require __DIR__ . '/inc/site.php';
$experienceMap = [];
foreach ($site['experiences'] as $exp) {
    $experienceMap[$exp['id']] = $exp['title'];
}

// Stats
$total       = count($bookings);
$todayStr    = date('Y-m-d');
$today       = count(array_filter($bookings, fn($b) => ($b['trip_date'] ?? '') === $todayStr));
$thisMonth   = count(array_filter($bookings, fn($b) => str_starts_with($b['trip_date'] ?? '', date('Y-m'))));
$upcoming    = count(array_filter($bookings, fn($b) => ($b['trip_date'] ?? '') >= $todayStr));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | PX Kayaking</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="hero2/logo/logo.png" type="image/png">
    <style>
        /* ── Dashboard layout ── */
        body { background: var(--bg); min-height: 100vh; }

        .dash-nav {
            background: rgba(7,17,30,0.92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        [data-theme='light'] .dash-nav { background: rgba(242,237,230,0.92); }

        .dash-nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 15px;
            color: var(--text);
        }

        .dash-nav-brand img { width: 72px; height: auto; }

        .dash-nav-badge {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            padding: 3px 9px;
            background: var(--teal-dim);
            border: 1px solid rgba(52,199,160,0.25);
            color: var(--teal);
            border-radius: 100px;
        }

        .dash-nav-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .dash-user {
            font-size: 13px;
            color: var(--text-dim);
            font-weight: 500;
        }

        .dash-logout {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: rgba(248,113,113,0.1);
            border: 1px solid rgba(248,113,113,0.2);
            border-radius: 8px;
            color: #f87171;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
        }
        .dash-logout:hover { background: rgba(248,113,113,0.2); }

        .dash-body {
            max-width: 1200px;
            margin: 0 auto;
            padding: 36px 28px 60px;
        }

        .dash-heading {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 6px;
        }

        .dash-sub {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 32px;
        }

        /* Stats row */
        .dash-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 36px;
        }

        @media (max-width: 800px) { .dash-stats { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 480px) { .dash-stats { grid-template-columns: 1fr; } }

        .stat-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 22px 24px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--accent), var(--accent-light));
            opacity: 0.6;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 10px;
        }

        .stat-value {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 38px;
            font-weight: 900;
            color: var(--accent);
            line-height: 1;
        }

        .stat-desc {
            font-size: 12px;
            color: var(--text-dim);
            margin-top: 6px;
        }

        /* Table panel */
        .dash-table-wrap {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 40px var(--shadow);
        }

        .dash-table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 12px;
        }

        .dash-table-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
        }

        .dash-table-count {
            font-size: 13px;
            color: var(--muted);
            margin-top: 2px;
        }

        .dash-search {
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.04);
            color: var(--text);
            font-size: 13px;
            font-family: inherit;
            width: 220px;
            transition: border-color 0.2s;
        }
        .dash-search:focus { outline: none; border-color: var(--accent); }
        .dash-search::placeholder { color: var(--muted); }

        .dash-table-scroll { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        thead th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: var(--text-dim);
            background: rgba(255,255,255,0.03);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        tbody td {
            padding: 13px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            color: var(--text);
            vertical-align: top;
        }

        [data-theme='light'] tbody td { border-bottom-color: rgba(0,0,0,0.05); }

        tbody tr:last-child td { border-bottom: none; }

        tbody tr:hover td { background: rgba(255,255,255,0.025); }

        .td-name { font-weight: 600; }

        .td-email a, .td-phone a {
            color: var(--teal);
            text-decoration: none;
            transition: opacity 0.15s;
        }
        .td-email a:hover, .td-phone a:hover { opacity: 0.75; }

        .td-date {
            white-space: nowrap;
            font-family: 'DM Mono', monospace;
            font-size: 12.5px;
            color: var(--text-dim);
        }

        .td-time {
            white-space: nowrap;
            font-family: 'DM Mono', monospace;
            font-size: 12px;
            color: var(--text-dim);
        }

        .td-exp .exp-badge {
            display: inline-block;
            padding: 3px 10px;
            background: var(--teal-dim);
            border: 1px solid rgba(52,199,160,0.2);
            border-radius: 100px;
            color: var(--teal);
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .td-msg {
            max-width: 220px;
            color: var(--text-dim);
            font-size: 13px;
            line-height: 1.5;
        }

        .td-created {
            white-space: nowrap;
            font-size: 12px;
            color: var(--muted);
        }

        /* Date badge — upcoming vs past */
        .date-upcoming { color: #34c7a0; }
        .date-past     { color: var(--muted); }
        .date-today    { color: var(--accent); font-weight: 700; }

        /* Empty state */
        .dash-empty {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-dim);
        }
        .dash-empty-icon { font-size: 40px; margin-bottom: 14px; opacity: 0.5; }
        .dash-empty h3 { font-size: 18px; color: var(--text); margin-bottom: 6px; }
        .dash-empty p  { font-size: 14px; color: var(--muted); }

        /* Error */
        .dash-error {
            background: rgba(248,113,113,0.08);
            border: 1px solid rgba(248,113,113,0.2);
            border-radius: 12px;
            padding: 16px 20px;
            color: #f87171;
            font-size: 14px;
            margin-bottom: 24px;
        }

        /* Search highlight */
        mark { background: rgba(212,168,75,0.25); color: var(--accent); border-radius: 2px; }
    </style>
</head>
<body>

<!-- Nav -->
<nav class="dash-nav">
    <div class="dash-nav-brand">
        <img src="hero2/logo/logo.png" alt="PX Kayaking">
        <span class="dash-nav-badge">Dashboard</span>
    </div>
    <div class="dash-nav-right">
        <span class="dash-user">👤 <?= htmlspecialchars($_SESSION['logged_in_user']) ?></span>
        <a class="dash-logout" href="logout.php">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Logout
        </a>
    </div>
</nav>

<div class="dash-body">

    <div class="dash-heading">Booking Overview</div>
    <div class="dash-sub"><?= date('l, d F Y') ?> — All incoming booking requests</div>

    <?php if ($dashError): ?>
        <div class="dash-error">⚠ <?= htmlspecialchars($dashError) ?></div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="dash-stats">
        <div class="stat-card">
            <div class="stat-label">Total Bookings</div>
            <div class="stat-value"><?= $total ?></div>
            <div class="stat-desc">All time</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Upcoming</div>
            <div class="stat-value"><?= $upcoming ?></div>
            <div class="stat-desc">From today onwards</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">This Month</div>
            <div class="stat-value"><?= $thisMonth ?></div>
            <div class="stat-desc"><?= date('F Y') ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Today</div>
            <div class="stat-value"><?= $today ?></div>
            <div class="stat-desc"><?= date('d M') ?></div>
        </div>
    </div>

    <!-- Table -->
    <div class="dash-table-wrap">
        <div class="dash-table-header">
            <div>
                <div class="dash-table-title">All Booking Requests</div>
                <div class="dash-table-count"><?= $total ?> total</div>
            </div>
            <input class="dash-search" type="search" id="searchInput" placeholder="Search name, email, phone…">
        </div>

        <div class="dash-table-scroll">
            <?php if (empty($bookings) && !$dashError): ?>
                <div class="dash-empty">
                    <div class="dash-empty-icon">🛶</div>
                    <h3>No bookings yet</h3>
                    <p>When someone submits the contact form, it'll appear here.</p>
                </div>
            <?php else: ?>
            <table id="bookingsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Trip Date</th>
                        <th>Time Slot</th>
                        <th>Experience</th>
                        <th>Message</th>
                        <th>Received</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($bookings as $i => $row):
                    $tripDate  = $row['trip_date'] ?? '';
                    $dateClass = $tripDate === $todayStr ? 'date-today' : ($tripDate >= $todayStr ? 'date-upcoming' : 'date-past');
                    $expTitle  = $experienceMap[$row['experience_id']] ?? ($row['experience_id'] ? 'Experience #' . $row['experience_id'] : '—');
                    $created   = $row['created_at'] ? date('d M Y, H:i', strtotime($row['created_at'])) : '—';
                    $tripFmt   = $tripDate ? date('d M Y', strtotime($tripDate)) : '—';
                ?>
                <tr>
                    <td style="color:var(--muted);font-size:12px;"><?= $total - $i ?></td>
                    <td class="td-name"><?= htmlspecialchars($row['name'] ?? '—') ?></td>
                    <td>
                        <div class="td-email"><a href="mailto:<?= htmlspecialchars($row['email'] ?? '') ?>"><?= htmlspecialchars($row['email'] ?? '—') ?></a></div>
                        <div class="td-phone" style="margin-top:3px;"><a href="tel:<?= htmlspecialchars($row['phone'] ?? '') ?>"><?= htmlspecialchars($row['phone'] ?? '') ?></a></div>
                    </td>
                    <td class="td-date <?= $dateClass ?>"><?= $tripFmt ?><?= $tripDate === $todayStr ? ' <span style="font-size:10px;background:rgba(212,168,75,0.15);padding:1px 6px;border-radius:4px;">TODAY</span>' : '' ?></td>
                    <td class="td-time"><?= htmlspecialchars($row['time_start'] ?? '—') ?> → <?= htmlspecialchars($row['time_end'] ?? '') ?></td>
                    <td class="td-exp"><span class="exp-badge"><?= htmlspecialchars($expTitle) ?></span></td>
                    <td class="td-msg"><?= htmlspecialchars($row['message'] ?? '—') ?></td>
                    <td class="td-created"><?= $created ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
// Live search
const searchInput = document.getElementById('searchInput');
const rows = document.querySelectorAll('#bookingsTable tbody tr');

searchInput?.addEventListener('input', () => {
    const q = searchInput.value.toLowerCase().trim();
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = (!q || text.includes(q)) ? '' : 'none';
    });
});
</script>

</body>
</html>