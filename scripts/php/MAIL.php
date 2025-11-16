<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;

require("./../../vendor/phpmailer/phpmailer/src/PHPMailer.php");
require("./../../vendor/phpmailer/phpmailer/src/SMTP.php");
require("./../../vendor/phpmailer/phpmailer/src/Exception.php");
require_once './../../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $kod = rand(100000, 999999);
    $_SESSION['kod'] = $kod;
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'm.ozdzynski@zsp10.elodz.edu.pl';
    $mail->Password = 'Mic1mic!';
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
    aby dokończyć zapisywanie dziecka, Wprowadźcie poniższy kod, na stronie przedszkola<br> </p> <img src="cid:logoPrzedszkola" alt="Logo Przedszkola" style="width:120px;">';
    $mail->send();
    header('Location: ./../../mailCode.php');
} catch (Exception $e) {
    $_SESSION['error'] = 2;
    header('Location: ./../../rekrutacja.php');
    die();
}
