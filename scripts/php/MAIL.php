<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../../models/User.php';
use models\User;
require("./../../vendor/phpmailer/phpmailer/src/PHPMailer.php");
require("./../../vendor/phpmailer/phpmailer/src/SMTP.php");
require("./../../vendor/phpmailer/phpmailer/src/Exception.php");
require_once './../../vendor/autoload.php';

session_start();


$_SESSION['sql'] = "INSERT INTO oczekujace(imieRodzica, nazwiskoRodzica, numerTelefonu, email, imieDziecka, nazwiskoDziecka, pesel, adres) VALUES ('" .
    $_POST['frmParentImie'] . "', '" .
    $_POST['frmParentNazwisko'] . "', '" .
    $_POST['frmParentTelefon'] . "', '" .
    $_POST['frmParentEmail'] . "', '" .
    $_POST['frmChildImie'] . "', '" .
    $_POST['frmChildNazwisko'] . "', '" .
    $_POST['frmChildPesel'] . "', '" .
    $_POST['frmChildAdres'] . "')";
function sendTempPassword($tempPass, $imie, $nazwisko){
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'm.ozdzynski@zsp10.elodz.edu.pl';
    $mail->Password = 'Mic1mic!';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->addEmbeddedImage('./../../assets/logo_tornado.png', 'logoCID', 'logo_tornado.png', 'base64', 'image/svg+xml');
    $mail->CharSet = 'UTF-8';
    $mail->setFrom('m.ozdzynski@zsp10.elodz.edu.pl', 'Przedszkole Chaosek');
    $mail->addAddress('snapmic@gmail.com');
    $mail->addReplyTo('m.ozdzynski@zsp10.elodz.edu.pl', 'Przedszkole Chaosek');

    $mail->isHTML(true);
    $mail->Subject = 'Wniosek zatwierdzony';
    $mail->Body = '<h1>Wniosek o przyjęcie dziecka zatwierdzony pomyślnie!</h1>
    <p>Szanowni Państwo,<br>
    Wniosek o przyjęcie dziecka('.$imie.$nazwisko.') został rozpatrzony pozytywnie. Poniżej przesyłamy jednorazowe hasło do zalogowania do panelu rodzica.<br>
    <strong>Hasło: ' . $tempPass . '</strong><br><br>
    Do logowania należy użyć maila podanego przez państwa przy rejestracji.
    Prosimy o dostarczenie do sekretariatu przedszkola zdjęcie dziecka, aby umieścić je w elektronicznym dzienniku.<br><br>
    Pozdrawiamy, Przedszkole Chaosek</p> <img src="cid:logoPrzedszkola" alt="Logo Przedszkola" style="width:120px;"><br>
    <br><br></p> <img src="cid:logoCID" alt="Logo Przedszkola" style="width:120px;">
    <p>Przedszkole Chaosek</p>';
    $mail->send();
}

try {
    $mail = new PHPMailer(true);
    $kod = rand(100000, 999999);
    $_SESSION['kod'] = $kod;
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'm.ozdzynski@zsp10.elodz.edu.pl';
    $mail->Password = 'Mic1mic!';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->addEmbeddedImage('./../../assets/logo_tornado.png', 'logoPrzedszkola', 'logo_tornado.png', 'base64', 'image/svg+xml');
    $mail->CharSet = 'UTF-8';
    $mail->setFrom('m.ozdzynski@zsp10.elodz.edu.pl', 'Przedszkole Chaosek');
    $mail->addAddress('snapmic@gmail.com');
    $mail->addReplyTo('m.ozdzynski@zsp10.elodz.edu.pl', 'Przedszkole Chaosek');

    $mail->isHTML(true);
    $mail->Subject = 'Potwierdzenie zapisu dziecka';
    $mail->Body = '<h1>Potwierdzenie zapisu dziecka do Przedszkola Chaosek</h1>
    <p>Szanowni Państwo,<br>
    Aby dokończyć zapisywanie dziecka, Wprowadźcie poniższy kod, na stronie przedszkola:<br>
    <strong>' . $kod . '</strong>
    <br><br></p> <img src="cid:logoPrzedszkola" alt="Logo Przedszkola" style="width:120px;"><br>
    <p>Przedszkole Chaosek</p>';
    $mail->send();
    header('Location: ./../../mailCode.php');
} catch (Exception $e) {
    $_SESSION['error'] = 2;
    header('Location: ./../../rekrutacja.php');
    die();
}