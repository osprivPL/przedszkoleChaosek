<?php
use PHPMailer\PHPMailer\PHPMailer;
require_once __DIR__ . '/../../models/User.php';
use models\User;
require("./../../vendor/phpmailer/phpmailer/src/PHPMailer.php");
require("./../../vendor/phpmailer/phpmailer/src/SMTP.php");
require("./../../vendor/phpmailer/phpmailer/src/Exception.php");
require_once './../../vendor/autoload.php';

session_start();



$mail = new PHPMailer(true);

try {
    $kod = rand(100000, 999999);
    $_SESSION['kod'] = $kod;
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->SMTPDebug = 2; // 0 = off (dla produkcji), 2 = client/server messages
    $mail->Username = 'przedszkole.chaosek@outlook.com';
    $mail->Password = 'jhiqpsoknntikewa';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->addEmbeddedImage('./../../assets/logo_tornado.svg', 'logoCID', 'logo_tornado.svg', 'base64', 'image/svg+xml');
    $mail->CharSet = 'UTF-8';
    $mail->setFrom('przedszkole.chaosek@outlook.com', 'Przedszkole Chaosek');
    $mail->addAddress('snapmic@gmail.com');
    $mail->addReplyTo('przedszkole.chaosek@outlook.com', 'Przedszkole Chaosek');
    echo 'dziala1';

    $mail->isHTML(true);
    $mail->Subject = 'Potwierdzenie zapisu dziecka';
    $mail->Body = '<h1>Potwierdzenie zapisu dziecka do Przedszkola Chaosek</h1>
    <p>Szanowni Państwo,<br>
    aby dokończyć zapisywanie dziecka, Wprowadźcie poniższy kod, na stronie przedszkola:<br>
    <strong>' . $kod . '</strong>
    <br><br></p> <img src="cid:logoPrzedszkola" alt="Logo Przedszkola" style="width:120px;">';
    echo 'dziala2';
    $mail->send();
    echo 'wyslano';
    header('Location: ./../../mailCode.php');
} catch (Exception $e) {
    $_SESSION['error'] = 2;
    echo "Błąd wysyłania wiadomości: {$mail->ErrorInfo}";
    echo "<br>Treść wyjątku: " . $e->getMessage();
//    header('Location: ./../../rekrutacja.php');
    die();
}
