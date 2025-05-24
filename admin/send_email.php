<?php
require_once __DIR__ . '/../libs/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../libs/phpmailer/src/SMTP.php';
require_once __DIR__ . '/../libs/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$configPath = dirname(__DIR__) . '/config/website_settings.json';

/**
 * sendCustomMail – Versendet eine HTML-E-Mail mit optionaler Plaintext-Version.
 *
 * @param string $to        Empfängeradresse
 * @param string $subject   Betreff der E-Mail
 * @param string $body      HTML-Inhalt der E-Mail
 * @param string $altBody   (optional) Textversion – für Vorschau/Plaintext
 * @return bool             true bei Erfolg, false bei Fehler
 */
function sendCustomMail($to, $subject, $body, $altBody = '')
{
    global $configPath;

    if (!file_exists($configPath)) return false;
    $settings = json_decode(file_get_contents($configPath), true);

    $mail = new PHPMailer(true);

    try {
        $mail->CharSet  = 'UTF-8';              // Sonderzeichen wie „ü“, „ß“ etc.
        $mail->Encoding = 'quoted-printable';   // iOS-Vorschau und Kompatibilität

        $mail->isSMTP();
        $mail->Host       = $settings['smtp_host'] ?? '';
        $mail->SMTPAuth   = true;
        $mail->Username   = $settings['smtp_user'] ?? '';
        $mail->Password   = $settings['smtp_pass'] ?? '';
        $mail->SMTPSecure = $settings['smtp_secure'] ?: 'tls';
        $mail->Port       = $settings['smtp_port'] ?? 587;

        $mail->setFrom($settings['email_from'], $settings['email_from_name'] ?? '');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Vorschau ermöglichen durch klaren, einfachen Text
        $alt = $altBody ?: strip_tags(
            preg_replace('/<br\s*\/?>/i', "\n", $body)
        );
        $alt = html_entity_decode($alt, ENT_QUOTES | ENT_HTML5, 'UTF-8'); // „ü“ statt „&uuml;“
        $alt = preg_replace('/\s+/', ' ', $alt); // Entfernt überflüssige Zeilenumbrüche
        $mail->AltBody = trim($alt);

        return $mail->send();
    } catch (Exception $e) {
        error_log("Mail error: " . $mail->ErrorInfo);
        return false;
    }
}
