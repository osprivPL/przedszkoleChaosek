<?php
// do mailow, zajebane z kina, ale ogolnie to dzialalo wiec teraz tez powinno jak nie to sie kysam
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once './vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = '';
    $mail->Password = '';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
//    $mail->SMTPDebug = 2; // lub 3
//    $mail->Debugoutput = 'html';
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->setFrom('', 'LOGO KINA');
    $mail->addAddress($_POST['email'], "Kino \"LOGO\"");
    $mail->isHTML(false);
    $mail->Subject = 'REZERWACJA W KINIE \"LOGO KINA\"';
    $mail->Body = "Zarezerwowane przez ciebie miejsca: " . $_SESSION['selectedSeats'];
    $mail->addReplyTo('', 'Kino LOGO');
    $mail->Sender = '';
    $mail->MessageID = "<" . md5(uniqid()) . ">";

    $mail->send();
}
catch (Exception $e) {
    echo "Nie można wysłać wiadomości e-mail. Błąd: {$mail->ErrorInfo}";
}
?>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>POWODZENIE!</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<header>
    <div class="logo">LOGO KINA</div>
</header>

<main class="container" style="height: 85%">
    <div class="">
        <h1>REZERWACJA ZAKOŃCZONA!</h1>
        <h3>Na mailu znajdują się szczegóły rezerwacji!</h3>




    </div>
</main>

<footer></footer>
</body>
</html>
