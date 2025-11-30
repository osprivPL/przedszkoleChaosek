php
<?php
use PHPMailer\PHPMailer\PHPMailer;
use models\User;

require_once __DIR__ . '/../../models/User.php';
require './../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './../../vendor/phpmailer/phpmailer/src/SMTP.php';
require './../../vendor/phpmailer/phpmailer/src/Exception.php';
require_once './../../vendor/autoload.php';

session_start();

$mail = new PHPMailer(true);

try {
    $kod = rand(100000, 999999);
    $_SESSION['kod'] = $kod;

    // Gmail SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->SMTPDebug = 0; // 0 for production
    $mail->Username = 'przedszkole.chaosek@gmail.com';       // <-- use your Gmail address
    $mail->Password = 'your-app-password-here';             // <-- use a Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // From must match the authenticated Gmail account
    $mail->setFrom('przedszkole.chaosek@gmail.com', 'Przedszkole Chaosek');
    $mail->addAddress('snapmic@gmail.com');
    $mail->addReplyTo('przedszkole.chaosek@gmail.com', 'Przedszkole Chaosek');

    $mail->addEmbeddedImage('./../../assets/logo_tornado.svg', 'logoCID', 'logo_tornado.svg', 'base64', 'image/svg+xml');

    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);
    $mail->Subject = 'Potwierdzenie zapisu dziecka';
    $mail->Body = '<h1>Potwierdzenie zapisu dziecka do Przedszkola Chaosek</h1>
        <p>Szanowni Państwo,<br>
        aby dokończyć zapisywanie dziecka, Wprowadźcie poniższy kod, na stronie przedszkola:<br>
        <strong>' . $kod . '</strong>
        <br><br></p>
        <img src="cid:logoCID" alt="Logo Przedszkola" style="width:120px;">';

    $mail->send();
    header('Location: ./../../mailCode.php');
} catch (Exception $e) {
    $_SESSION['error'] = 2;
    echo "Błąd wysyłania wiadomości: {$mail->ErrorInfo}";
    echo "<br>Treść wyjątku: " . $e->getMessage();
    die();
}
