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

    $mail->CharSet = 'UTF-8';
    $mail->setFrom('m.ozdzynski@zsp10.elodz.edu.pl', 'Przedszkole Chaosek');
    $mail->addAddress('snapmic@gmail.com');
    $mail->addReplyTo('m.ozdzynski@zsp10.elodz.edu.pl', 'Przedszkole Chaosek');

    $mail->isHTML(true);
    $mail->Subject = 'Temat wiadomości';
    $mail->Body = '<h2>Potwierdzenie zapisu dziecka do Przedszkola Chaosek</h2> <p>Szanowni Państwo,<br> Twoje dziecko zostało pomyślnie zapisane do naszego przedszkola!</p> <p><b>Kod potwierdzający:</b> <span style="font-size:1.4em; color:green;">' . $kod . '</span></p> <p>Dziękujemy za zaufanie!</p> <img src="cid:logoPrzedszkola" alt="Logo Przedszkola" style="width:120px;">';
    $mail->send();
    header('Location: ./../../mailCode.php');
}
catch (Exception $e) {
    $_SESSION['error'] = 2;
    header('Location: ./../../rekrutacja.php');
    die();
}
