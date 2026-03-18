<?php
/**
 * PX Kayaking — PHPMailer helper
 *
 * Requires PHPMailer installed via Composer:
 *   composer require phpmailer/phpmailer
 *
 * Or manually download from:
 *   https://github.com/PHPMailer/PHPMailer/releases
 *   and place the src/ folder at vendor/phpmailer/phpmailer/src/
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';

// ── Gmail SMTP credentials ────────────────────────────────────
define('MAIL_FROM',     'pxkayaking@gmail.com');
define('MAIL_FROM_NAME','PX Kayaking');
define('MAIL_TO',       'pxkayaking@gmail.com');   // booking notifications go here
define('SMTP_HOST',     'smtp.gmail.com');
define('SMTP_PORT',     587);
define('SMTP_USER',     'pxkayaking@gmail.com');
define('SMTP_PASS',     'xslg ovqt cayd hmov');     // Gmail App Password

/**
 * Create a pre-configured PHPMailer instance.
 */
function pxMailer(): PHPMailer {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = SMTP_PORT;
    $mail->CharSet    = 'UTF-8';
    $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
    return $mail;
}

/**
 * Send booking notification to the owner.
 */
function sendOwnerNotification(array $data): bool {
    try {
        $mail = pxMailer();
        $mail->addAddress(MAIL_TO, MAIL_FROM_NAME);
        $mail->addReplyTo($data['email'], $data['name']);
        $mail->Subject = '🛶 New Booking Request — ' . $data['name'];
        $mail->isHTML(true);
        $mail->Body    = ownerEmailHtml($data);
        $mail->AltBody = ownerEmailText($data);
        return $mail->send();
    } catch (Exception $e) {
        error_log('PX Kayaking owner email failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Send booking confirmation to the customer.
 */
function sendCustomerConfirmation(array $data): bool {
    try {
        $mail = pxMailer();
        $mail->addAddress($data['email'], $data['name']);
        $mail->Subject = '✅ Booking received — PX Kayaking';
        $mail->isHTML(true);
        $mail->Body    = customerEmailHtml($data);
        $mail->AltBody = customerEmailText($data);
        return $mail->send();
    } catch (Exception $e) {
        error_log('PX Kayaking customer email failed: ' . $e->getMessage());
        return false;
    }
}

// ── Email templates ───────────────────────────────────────────

function ownerEmailHtml(array $d): string {
    $exp  = htmlspecialchars($d['experience'] ?? 'Not specified');
    $name = htmlspecialchars($d['name']);
    $email= htmlspecialchars($d['email']);
    $phone= htmlspecialchars($d['phone']);
    $date = htmlspecialchars($d['date'] ? date('D, d M Y', strtotime($d['date'])) : '—');
    $time = htmlspecialchars($d['time_start'] . ' – ' . $d['time_end']);
    $msg  = nl2br(htmlspecialchars($d['message'] ?? ''));

    return <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#0b1a2e;font-family:'Segoe UI',Helvetica,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#0b1a2e;padding:40px 20px;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="background:#0f2033;border-radius:16px;border:1px solid rgba(255,255,255,0.08);overflow:hidden;max-width:600px;width:100%;">

        <!-- Header -->
        <tr>
          <td style="background:linear-gradient(135deg,#d4a84b,#f0c96e);padding:28px 32px;">
            <p style="margin:0;font-size:13px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0a1508;opacity:0.7;">PX Kayaking</p>
            <h1 style="margin:6px 0 0;font-size:22px;font-weight:800;color:#0a1508;">🛶 New Booking Request</h1>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:32px;">
            <p style="margin:0 0 24px;font-size:15px;color:#8faabf;">A new booking request has been submitted. Details below:</p>

            <!-- Detail rows -->
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.06);">
                  <span style="font-size:11px;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;color:#6b8da8;">Name</span><br>
                  <span style="font-size:16px;font-weight:600;color:#e8eff8;">{$name}</span>
                </td>
              </tr>
              <tr>
                <td style="padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.06);">
                  <span style="font-size:11px;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;color:#6b8da8;">Email</span><br>
                  <a href="mailto:{$email}" style="font-size:15px;color:#34c7a0;text-decoration:none;">{$email}</a>
                </td>
              </tr>
              <tr>
                <td style="padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.06);">
                  <span style="font-size:11px;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;color:#6b8da8;">Phone / WhatsApp</span><br>
                  <a href="tel:{$phone}" style="font-size:15px;color:#34c7a0;text-decoration:none;">{$phone}</a>
                </td>
              </tr>
              <tr>
                <td style="padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.06);">
                  <span style="font-size:11px;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;color:#6b8da8;">Trip Date</span><br>
                  <span style="font-size:15px;font-weight:600;color:#d4a84b;">{$date}</span>
                </td>
              </tr>
              <tr>
                <td style="padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.06);">
                  <span style="font-size:11px;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;color:#6b8da8;">Time Slot</span><br>
                  <span style="font-size:15px;color:#e8eff8;">{$time}</span>
                </td>
              </tr>
              <tr>
                <td style="padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.06);">
                  <span style="font-size:11px;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;color:#6b8da8;">Experience</span><br>
                  <span style="font-size:15px;color:#e8eff8;">{$exp}</span>
                </td>
              </tr>
              <tr>
                <td style="padding:10px 0;">
                  <span style="font-size:11px;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;color:#6b8da8;">Message</span><br>
                  <span style="font-size:14px;color:#8faabf;line-height:1.6;">{$msg}</span>
                </td>
              </tr>
            </table>

            <!-- CTA -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:28px;">
              <tr>
                <td align="center">
                  <a href="mailto:{$email}" style="display:inline-block;padding:13px 28px;background:linear-gradient(135deg,#d4a84b,#f0c96e);color:#0a1508;border-radius:10px;font-weight:700;font-size:14px;text-decoration:none;">Reply to {$name}</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="padding:18px 32px;border-top:1px solid rgba(255,255,255,0.06);">
            <p style="margin:0;font-size:12px;color:#4a6070;text-align:center;">PX Kayaking · Canaguinim, South Goa · pxkayaking@gmail.com</p>
          </td>
        </tr>

      </table>
    </td></tr>
  </table>
</body>
</html>
HTML;
}

function ownerEmailText(array $d): string {
    return "New Booking Request — PX Kayaking\n\n"
         . "Name: {$d['name']}\n"
         . "Email: {$d['email']}\n"
         . "Phone: {$d['phone']}\n"
         . "Date: {$d['date']}\n"
         . "Time: {$d['time_start']} – {$d['time_end']}\n"
         . "Experience: " . ($d['experience'] ?? 'N/A') . "\n"
         . "Message: " . ($d['message'] ?? '') . "\n";
}

function customerEmailHtml(array $d): string {
    $name = htmlspecialchars($d['name']);
    $date = htmlspecialchars($d['date'] ? date('D, d M Y', strtotime($d['date'])) : '—');
    $time = htmlspecialchars($d['time_start'] . ' – ' . $d['time_end']);
    $exp  = htmlspecialchars($d['experience'] ?? 'Not specified');
    $msg  = nl2br(htmlspecialchars($d['message'] ?? ''));

    return <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#0b1a2e;font-family:'Segoe UI',Helvetica,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#0b1a2e;padding:40px 20px;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="background:#0f2033;border-radius:16px;border:1px solid rgba(255,255,255,0.08);overflow:hidden;max-width:600px;width:100%;">

        <!-- Header -->
        <tr>
          <td style="background:linear-gradient(135deg,#0d2037,#132840);padding:36px 32px;text-align:center;border-bottom:1px solid rgba(255,255,255,0.08);">
            <p style="margin:0 0 16px;font-size:13px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#34c7a0;">PX Kayaking · Canaguinim, Goa</p>
            <h1 style="margin:0;font-size:26px;font-weight:800;color:#e8eff8;line-height:1.2;">Your booking is confirmed! 🛶</h1>
            <p style="margin:12px 0 0;font-size:15px;color:#8faabf;">Thanks {$name}, we've received your request and will confirm shortly.</p>
          </td>
        </tr>

        <!-- Summary -->
        <tr>
          <td style="padding:32px;">
            <h2 style="margin:0 0 20px;font-size:14px;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;color:#6b8da8;">Your booking summary</h2>

            <table width="100%" cellpadding="0" cellspacing="0" style="background:rgba(255,255,255,0.04);border-radius:12px;overflow:hidden;">
              <tr>
                <td style="padding:14px 18px;border-bottom:1px solid rgba(255,255,255,0.06);">
                  <span style="font-size:12px;color:#6b8da8;">📅 Date</span><br>
                  <span style="font-size:15px;font-weight:600;color:#d4a84b;">{$date}</span>
                </td>
              </tr>
              <tr>
                <td style="padding:14px 18px;border-bottom:1px solid rgba(255,255,255,0.06);">
                  <span style="font-size:12px;color:#6b8da8;">⏰ Time</span><br>
                  <span style="font-size:15px;color:#e8eff8;">{$time}</span>
                </td>
              </tr>
              <tr>
                <td style="padding:14px 18px;border-bottom:1px solid rgba(255,255,255,0.06);">
                  <span style="font-size:12px;color:#6b8da8;">🚣 Experience</span><br>
                  <span style="font-size:15px;color:#e8eff8;">{$exp}</span>
                </td>
              </tr>
              <tr>
                <td style="padding:14px 18px;">
                  <span style="font-size:12px;color:#6b8da8;">📍 Location</span><br>
                  <span style="font-size:15px;color:#e8eff8;">Canaguinim beach (little beach), South Goa 403703</span>
                </td>
              </tr>
            </table>

            <!-- What's next -->
            <h2 style="margin:28px 0 14px;font-size:14px;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;color:#6b8da8;">What happens next</h2>
            <p style="margin:0 0 10px;font-size:14px;color:#8faabf;line-height:1.7;">📞 We'll call or WhatsApp you within a few hours to confirm your slot and answer any questions.</p>
            <p style="margin:0 0 10px;font-size:14px;color:#8faabf;line-height:1.7;">🌊 For sea kayaking, we'll check conditions before confirming — safety first!</p>
            <p style="margin:0;font-size:14px;color:#8faabf;line-height:1.7;">🎒 All gear is included — just bring yourself, sunscreen, and a sense of adventure.</p>

            <!-- Contact -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:28px;background:rgba(52,199,160,0.08);border:1px solid rgba(52,199,160,0.2);border-radius:12px;">
              <tr>
                <td style="padding:18px 20px;">
                  <p style="margin:0 0 6px;font-size:12px;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;color:#34c7a0;">Need to reach us?</p>
                  <p style="margin:0;font-size:14px;color:#8faabf;">📞 <a href="tel:9822277190" style="color:#34c7a0;text-decoration:none;">9822277190</a> &nbsp;|&nbsp; <a href="tel:9822156672" style="color:#34c7a0;text-decoration:none;">9822156672</a></p>
                  <p style="margin:4px 0 0;font-size:14px;color:#8faabf;">✉️ <a href="mailto:pxkayaking@gmail.com" style="color:#34c7a0;text-decoration:none;">pxkayaking@gmail.com</a></p>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="padding:18px 32px;border-top:1px solid rgba(255,255,255,0.06);text-align:center;">
            <p style="margin:0;font-size:12px;color:#4a6070;">PX Kayaking · Canaguinim, South Goa · Operating hours: 9 AM – 6 PM daily</p>
          </td>
        </tr>

      </table>
    </td></tr>
  </table>
</body>
</html>
HTML;
}

function customerEmailText(array $d): string {
    return "Hi {$d['name']}, your booking request has been received!\n\n"
         . "Summary:\n"
         . "Date: {$d['date']}\n"
         . "Time: {$d['time_start']} – {$d['time_end']}\n"
         . "Experience: " . ($d['experience'] ?? 'N/A') . "\n\n"
         . "We'll call or WhatsApp you shortly to confirm your slot.\n\n"
         . "Questions? Call us: 9822277190 / 9822156672\n"
         . "PX Kayaking · Canaguinim, South Goa\n";
}
