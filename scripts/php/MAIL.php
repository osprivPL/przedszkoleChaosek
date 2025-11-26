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
    $mail->Username = 'przedszkole.chaosek@outlook.com';
    $mail->Password = 'Chaosek123';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->addEmbeddedImage('./../../assets/logo_tornado.svg', 'logoCID', 'logo_tornado.svg', 'base64', 'image/svg+xml');
    $mail->CharSet = 'UTF-8';
    $mail->setFrom('m.ozdzynski@zsp10.elodz.edu.pl', 'Przedszkole Chaosek');
    $mail->addAddress('snapmic@gmail.com');
    $mail->addReplyTo('m.ozdzynski@zsp10.elodz.edu.pl', 'Przedszkole Chaosek');

    $mail->isHTML(true);
    $mail->Subject = 'Potwierdzenie zapisu dziecka';
    $mail->Body = '<h1>Potwierdzenie zapisu dziecka do Przedszkola Chaosek</h1>
    <p>Szanowni Państwo,<br>
    aby dokończyć zapisywanie dziecka, Wprowadźcie poniższy kod, na stronie przedszkola:<br>
    <strong>' . $kod . '</strong>
    <br><br></p> <img src="cid:logoPrzedszkola" alt="Logo Przedszkola" style="width:120px;">';
    $mail->send();
    header('Location: ./../../mailCode.php');
} catch (Exception $e) {
    $_SESSION['error'] = 2;
    header('Location: ./../../rekrutacja.php');
    die();
}
