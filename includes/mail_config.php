<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

if (!function_exists('sendMail')) {
    function sendMail($toEmail, $subject, $message) {
        return sendReminderEmail($toEmail, $subject, $message);
    }
}

// KEEP your original function — do NOT remove it!
if (!function_exists('sendReminderEmail')) {
    function sendReminderEmail($toEmail, $subject, $message) {
        $mail = new PHPMailer(true);
        try {
            // Disable debug output in production
            $mail->SMTPDebug = 0;

            // SMTP Setup
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'Kamandesalome710@gmail.com';  
            $mail->Password   = 'yzju bypj mnth rpjw'; // secure app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Sender & Receiver
            $mail->setFrom('Kamandesalome710@gmail.com', 'Weight Management System');
            $mail->addAddress($toEmail);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;
            $mail->AltBody = strip_tags($message);

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Mail error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
?>
