<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../../models/User.php';
use models\User;

require_once("./../../vendor/phpmailer/phpmailer/src/PHPMailer.php");
require_once("./../../vendor/phpmailer/phpmailer/src/SMTP.php");
require_once("./../../vendor/phpmailer/phpmailer/src/Exception.php");
require_once './../../vendor/autoload.php';


function sendTempPasswordTeacher($tempPass){
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'm.ozdzynski@zsp10.elodz.edu.pl';
        $mail->Password = 'Mic1mic!';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $logoPath = __DIR__ . '/../../assets/logo_tornado.png';
        if(file_exists($logoPath)) {
            $mail->addEmbeddedImage($logoPath, 'logoPrzedszkola', 'logo_tornado.png', 'base64', 'image/svg+xml');
        }

        $mail->CharSet = 'UTF-8';
        $mail->setFrom('m.ozdzynski@zsp10.elodz.edu.pl', 'Przedszkole Chaosek');
        $mail->addAddress('snapmic@gmail.com');
        $mail->addReplyTo('m.ozdzynski@zsp10.elodz.edu.pl', 'Przedszkole Chaosek');

        $mail->isHTML(true);
        $mail->Subject = 'Hasło do konta';
        $mail->Body = '<h1>Hasło do konta dla nowego nauczyciela</h1>
        <p>Szanowna Panie, Szanowna Pani<br>
        Gratulujemy przyjęcia do zespołu Przedszkola Chaosek!
        Poniżej przesyłamy jednorazowe hasło do zalogowania do panelu nauczyciela.<br>
        <strong>Hasło: ' . $tempPass . '</strong><br><br>
        Pozdrawiamy, Przedszkole Chaosek</p> 
        <img src="cid:logoPrzedszkola" alt="Logo Przedszkola" style="width:120px;"><br>';

        $mail->send();
    } catch (Exception $e) {
        error_log("Błąd wysyłania hasła: " . $mail->ErrorInfo);
    }
}

function sendTempPassword($tempPass, $imie, $nazwisko){
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'm.ozdzynski@zsp10.elodz.edu.pl';
        $mail->Password = 'Mic1mic!';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $logoPath = __DIR__ . '/../../assets/logo_tornado.png';
        if(file_exists($logoPath)) {
            $mail->addEmbeddedImage($logoPath, 'logoPrzedszkola', 'logo_tornado.png', 'base64', 'image/svg+xml');
        }

        $mail->CharSet = 'UTF-8';
        $mail->setFrom('m.ozdzynski@zsp10.elodz.edu.pl', 'Przedszkole Chaosek');
        $mail->addAddress('snapmic@gmail.com');
        $mail->addReplyTo('m.ozdzynski@zsp10.elodz.edu.pl', 'Przedszkole Chaosek');

        $mail->isHTML(true);
        $mail->Subject = 'Wniosek zatwierdzony';
        $mail->Body = '<h1>Wniosek o przyjęcie dziecka zatwierdzony pomyślnie!</h1>
        <p>Szanowni Państwo,<br>
        Wniosek o przyjęcie dziecka ('.$imie.' '.$nazwisko.') został rozpatrzony pozytywnie. Poniżej przesyłamy jednorazowe hasło do zalogowania do panelu rodzica.<br>
        <strong>Hasło: ' . $tempPass . '</strong><br><br>
        Do logowania należy użyć maila podanego przez państwa przy rejestracji.
        Prosimy o dostarczenie do sekretariatu przedszkola zdjęcie dziecka, aby umieścić je w elektronicznym dzienniku.<br><br>
        Pozdrawiamy, Przedszkole Chaosek</p> 
        <img src="cid:logoPrzedszkola" alt="Logo Przedszkola" style="width:120px;"><br>';

        $mail->send();
    } catch (Exception $e) {
        error_log("Błąd wysyłania hasła: " . $mail->ErrorInfo);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['frmParentImie'])) {
    $_SESSION['sql'] = "INSERT INTO oczekujace(imieRodzica, nazwiskoRodzica, numerTelefonu, email, imieDziecka, nazwiskoDziecka, pesel, adres) VALUES ('" .
        $_POST['frmParentImie'] . "', '" .
        $_POST['frmParentNazwisko'] . "', '" .
        $_POST['frmParentTelefon'] . "', '" .
        $_POST['frmParentEmail'] . "', '" .
        $_POST['frmChildImie'] . "', '" .
        $_POST['frmChildNazwisko'] . "', '" .
        $_POST['frmChildPesel'] . "', '" .
        $_POST['frmChildAdres'] . "')";

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

        $logoPath = __DIR__ . '/../../assets/logo_tornado.png';
        if(file_exists($logoPath)) {
            $mail->addEmbeddedImage($logoPath, 'logoPrzedszkola', 'logo_tornado.png', 'base64', 'image/svg+xml');
        }

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
        die();
    } catch (Exception $e) {
        $_SESSION['error'] = 2;
        header('Location: ./../../rekrutacja.php');
        die();
    }
}