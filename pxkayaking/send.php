

<?php
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
    try {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=pxkayaking;charset=utf8mb4',
            'px_user',
            'CHANGE_ME_PASSWORD',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );

        $stmt = $pdo->prepare("""
            INSERT INTO inquiries (name, email, phone, trip_date, time_start, time_end, message, experience_id)
            VALUES (:name, :email, :phone, :trip_date, :time_start, :time_end, :message, :experience_id)
        """);
        $stmt->execute([
            ":name" => $name,
            ":email" => $email,
            ":phone" => $phone,
            ":trip_date" => $date,
            ":time_start" => $timeStart,
            ":time_end" => $timeEnd,
            ":message" => $message,
            ":experience_id" => $experienceId,
        ]);

        $to = "bookings@pxkayaking.com"; // replace with your mailbox
        $subject = "New booking inquiry - PX Kayaking";
        $body = "Name: $name\nEmail: $email\nPhone: $phone\nDate: $date\nTime: $timeStart to $timeEnd\nMessage: $message";
        $headers = "From: no-reply@pxkayaking.com" . "\r\n" . "Reply-To: " . $email;
        @mail($to, $subject, $body, $headers);

        $success = true;
    } catch (Throwable $e) {
        $errors[] = "Could not save your request right now. Please call +91 9822277190 / 9822156672.";
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
