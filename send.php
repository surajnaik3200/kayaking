    <a href="management.php" style="position:fixed;bottom:24px;right:24px;font-size:2.5rem;text-decoration:none;z-index:1000;box-shadow:0 2px 8px #aaa;background:#fff;border-radius:50%;padding:12px;">🛟</a>
<?php
require_once 'db.php';
$page = "contact";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: contact.php");
    exit;
}

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$date = trim($_POST["date"] ?? "");
$timeStart = trim($_POST["time_start"] ?? "");
$timeEnd = trim($_POST["time_end"] ?? "");
$message = trim($_POST["message"] ?? "");
$experienceId = null; // set if you add experience dropdown later
$errors = array();
if ($name === "" || $email === "" || $phone === "" || $date === "" || $timeStart === "" || $timeEnd === "") {
    $errors[] = "Please fill in all required fields.";
}
if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

$success = false;
// Save to DB and optionally email
if (!$errors) {
    $stmt = mysqli_prepare($conn, "INSERT INTO inquiries (name, email, phone, trip_date, time_start, time_end, message, experience_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssssss", $name, $email, $phone, $date, $timeStart, $timeEnd, $message, $experienceId);
    if (mysqli_stmt_execute($stmt)) {
        $to = "bookings@pxkayaking.com"; // replace with your mailbox
        $subject = "New booking inquiry - PX Kayaking";
        $body = "Name: $name\nEmail: $email\nPhone: $phone\nDate: $date\nTime: $timeStart to $timeEnd\nMessage: $message";
        $headers = "From: no-reply@pxkayaking.com" . "\r\n" . "Reply-To: " . $email;
        @mail($to, $subject, $body, $headers);
        $success = true;
    } else {
        $errors[] = "Database error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking status | PX Kayaking</title>
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
                <a class="cta" href="contact.php">Book Now</a>
        </div>
    </nav>

    <main>
        <?php if ($success): ?>
            <div style="margin: 40px auto; max-width: 400px; background: #fff; border: 1px solid #27ae60; border-radius: 8px; padding: 32px; text-align: center; box-shadow: 0 2px 8px #ccc;">
                <h2 style="color: #27ae60; margin-bottom: 16px; font-weight: bold;">Thank you!</h2>
                <p style="color: #222; font-size: 1.1em; margin-bottom: 24px;">Your booking request has been received.<br>We will respond to you shortly.</p>
                <a href="index.php" style="display: inline-block; margin-top: 0; padding: 12px 32px; background: #27ae60; color: #fff; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 1.1em; box-shadow: 0 2px 4px #aaa;">Back to Home</a>
            </div>
        <?php endif; ?>
    </main>
