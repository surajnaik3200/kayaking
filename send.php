<?php
require_once 'db.php';
require_once __DIR__ . '/inc/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

$page = 'contact';
$site = require __DIR__ . '/inc/site.php';

// ── Collect form data ────────────────────────────────────────
$name         = trim($_POST['name']         ?? '');
$email        = trim($_POST['email']        ?? '');
$phone        = trim($_POST['phone']        ?? '');
$date         = trim($_POST['date']         ?? '');
$timeStart    = trim($_POST['time_start']   ?? '');
$timeEnd      = trim($_POST['time_end']     ?? '');
$message      = trim($_POST['message']      ?? '');
$experienceId = (isset($_POST['experience_id']) && $_POST['experience_id'] !== '')
                ? trim($_POST['experience_id']) : null;

// ── Validate ─────────────────────────────────────────────────
$errors = [];
if ($name === '' || $email === '' || $phone === '' || $date === '' || $timeStart === '' || $timeEnd === '') {
    $errors[] = 'Please fill in all required fields.';
}
if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if (!$errors && !$conn) {
    $errors[] = 'Unable to submit right now (database unavailable). Please call or WhatsApp us directly.';
}

// ── Resolve experience title ─────────────────────────────────
$experienceTitle = null;
if ($experienceId) {
    foreach ($site['experiences'] as $exp) {
        if ((string) $exp['id'] === (string) $experienceId) {
            $experienceTitle = $exp['title'];
            break;
        }
    }
}

$success = false;

// ── Save to database & send emails ───────────────────────────
if (!$errors) {
    $stmt = mysqli_prepare($conn,
        "INSERT INTO inquiries (name, email, phone, trip_date, time_start, time_end, message, experience_id)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, 'ssssssss',
        $name, $email, $phone, $date, $timeStart, $timeEnd, $message, $experienceId
    );

    if (mysqli_stmt_execute($stmt)) {
        $success = true;

        $mailData = [
            'name'       => $name,
            'email'      => $email,
            'phone'      => $phone,
            'date'       => $date,
            'time_start' => $timeStart,
            'time_end'   => $timeEnd,
            'message'    => $message,
            'experience' => $experienceTitle,
        ];

        sendOwnerNotification($mailData);
        sendCustomerConfirmation($mailData);

    } else {
        $errors[] = 'Database error: ' . mysqli_error($conn);
    }
}

require __DIR__ . '/inc/header.php';
?>

<main style="padding: 40px 16px;">

    <?php if ($success): ?>
    <div class="state-box success">
        <div style="font-size:40px;margin-bottom:16px;">🛶</div>
        <h2>You're all set!</h2>
        <p>Your booking request has been received and a confirmation has been sent to <strong style="color:var(--teal)"><?= htmlspecialchars($email) ?></strong>.<br>We'll call or WhatsApp you shortly to confirm your slot.</p>
        <a href="index.php" class="btn" style="margin-top:8px;">Back to Home</a>
    </div>
    <?php endif; ?>

    <?php if (!$success && $errors): ?>
    <div class="state-box error">
        <div style="font-size:36px;margin-bottom:14px;">⚠️</div>
        <h2>Something went wrong</h2>
        <ul style="margin:0 0 20px;padding-left:20px;color:var(--text-dim);text-align:left;font-size:14px;line-height:1.8;">
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
        <a href="contact.php" style="color:var(--teal);font-weight:600;font-size:14px;">← Go back and try again</a>
    </div>
    <?php endif; ?>

</main>

<?php require __DIR__ . '/inc/footer.php'; ?>