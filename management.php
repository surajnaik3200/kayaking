<?php
session_start();
require_once 'db.php';

// Handle login
if (!isset($_SESSION['logged_in_user'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $error = 'Please enter username and password.';
        } else {
            $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? LIMIT 1");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if ($result) {
                    $user = mysqli_fetch_assoc($result);
                    if ($user && $user['password'] === $password) {
                        $_SESSION['logged_in_user'] = $username;
                        header('Location: management.php');
                        exit;
                    }
                }
            }
            $error = 'Invalid username or password.';
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Management Login | PX Kayaking</title>
        <link rel="stylesheet" href="style.css">
        <style>
            .login-box { max-width: 340px; margin: 80px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 12px #aaa; padding: 32px; text-align: center; }
            .login-box h2 { color: #0b1a2e; margin-bottom: 18px; }
            .login-box input { margin-bottom: 14px; }
            .login-box .btn { width: 100%; }
            .login-box .error { color: #e74c3c; margin-bottom: 12px; }
        </style>
    </head>
    <body>
        <div class="login-box">
            <h2>Management Login</h2>
            <?php if (isset($error)) echo '<div class="error">' . $error . '</div>'; ?>
            <form method="post">
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button class="btn" type="submit">Login</button>
            </form>
            <a href="index.php" style="display:block;margin:18px auto 0 auto;width:100%;padding:10px 0;background:#10b981;color:#fff;border-radius:8px;font-weight:700;text-decoration:none;font-size:1.1em;">Home</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// If logged in, show bookings
    $bookings = [];
    $result = mysqli_query($conn, "SELECT * FROM inquiries ORDER BY created_at DESC");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $bookings[] = $row;
        }
    } else {
        $error = 'Could not load bookings from database: ' . mysqli_error($conn);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Management | PX Kayaking</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .management-table { max-width: 1100px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px #aaa; padding: 24px; }
        .management-table h2 { color: #0b1a2e; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #f5f5f5; color: #0b1a2e; }
        tr:last-child td { border-bottom: none; }
        .logout-btn { float: right; background: #e74c3c; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="management-table">
        <form method="post" action="logout.php" style="display:inline;">
            <button class="logout-btn" type="submit">Logout</button>
        </form>
        <h2>All Bookings</h2>
        <?php if (isset($error)) echo '<div class="error">' . $error . '</div>'; ?>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Date</th>
                <th>Time</th>
                <th>Message</th>
                <th>Experience</th>
                <th>Created At</th>
            </tr>
            <?php foreach ($bookings as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['trip_date'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['time_start'] ?? '') ?> - <?= htmlspecialchars($row['time_end'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['message'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['experience_id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['created_at'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
