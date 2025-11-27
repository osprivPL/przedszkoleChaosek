<!DOCTYPE html>
<?php
require_once __DIR__ . '/models/User.php';

use models\User;

session_start();
//session_destroy();
$user = new User();

require_once "./scripts/php/printArr.php";
if (!isset($_SESSION['logged'])) {
    $_SESSION['logged'] = false;
}
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = -1;
}

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
?>

<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Michał Ożdżyński Stanisław Odrowski Piotr Peryt">

    <!-- style -->
    <link rel="stylesheet" href="./styles/rekrutacja.css">
    <link rel="stylesheet" href="./styles/style.css">

    <!-- czcionka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Momo+Trust+Display&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">

    <!-- ikonka -->
    <link rel="icon" type="image/x-icon" href="./assets/logo_tornado.svg">

    <title>Przedszkole Chaosek</title>
    <script src="scripts/js/registerValidator.js"></script>
</head>
<body id='body'>
<!-- ============================= -->
<!-- HEADER -->
<!-- ============================= -->
<header>
    <div class="square_container">
        <div class="square"></div>
    </div>
    <a href="index.php" class="logo logo-font">
        <img src="assets/logo_tornado.svg" alt="logo">
        <span>Przedszkole Chaosek</span>
    </a>
</header>
<!-- ============================= -->
<!-- MAIN -->
<!-- ============================= -->
<main>
    <!---<?php printArr($_SESSION); ?>-->
    <div id="container">
        <h1 class='logo-font'>Rekrutacja</h1>
        <form id="frmRekrutacja" method="post" action=
                <?php
                if ($_SESSION['logged'] && $user->typ == 0) {
                    echo "rekrutacjaCompleted.php";
                } else {
                    echo "./scripts/php/MAIL.php";
                }
                ?>>
            <div class="container">
                <div class="formsContainer">
                    <div class="
                        <?php 
                            if($_SESSION['logged'])
                                {echo "frmChildLogged";}
                            else{echo "frmChild";}
                            ?>
                        ">
                        <?php if($_SESSION['logged']){echo "<div class='smallerFrmChild'>";}?>
                        <div class="inputGroup"><input type="text" id="frmChildImie" name="frmChildImie" placeholder="Imię dziecka" required></div>
                        <div class="inputGroup"><input type="text" id="frmChildNazwisko" name="frmChildNazwisko" placeholder="Nazwisko dziecka"
                            required></div>
                            <?php if($_SESSION['logged']){echo "</div><div class='smallerFrmChild'>";}?>
                        <div class="inputGroup"><input type="text" id="frmChildPesel" name="frmChildPesel" placeholder="Pesel dziecka" required></div>
                        <div class="inputGroup"><input type="text" id="frmChildAdres" name="frmChildAdres" placeholder="Adres zamieszkania dziecka"
                            required></div>
                            <?php if($_SESSION['logged']){echo "</div>";}?>
                    </div>
                    <div class="frmParent" <?php
                    if ($_SESSION['logged'] && $user->typ == 0) {
                        echo 'style="display:none;"';
                    }
                    ?>>
                    <div class="inputGroup">
                        <input type="text" id="frmParentImie" name="frmParentImie" placeholder="Imię rodzica/opiekuna" required
                                <?php
                                if ($_SESSION['logged'] && $user->typ == 0) {
                                    echo 'value="' . $user->imie . '" readonly';
                                }
                                ?>></div>
                    <div class="inputGroup">
                        <input type="text" id="frmParentNazwisko" name="frmParentNazwisko"
                            placeholder="Nazwisko rodzica/opiekuna" required
                                <?php
                                if ($_SESSION['logged'] && $user->typ == 0) {
                                    echo 'value="' . $user->nazwisko . '" readonly';
                                }
                                ?>></div>
                    <div class="inputGroup">
                        <input type="text" id="frmParentTelefon" name="frmParentTelefon"
                            placeholder="Numer telefonu rodzica/opiekuna"
                            required
                                <?php
                                if ($_SESSION['logged'] && $user->typ == 0) {
                                    echo 'value="' . $user->telefon . '" readonly';
                                }
                                ?>></div>
                    <div class="inputGroup">
                        <input type="email" id="frmParentEmail" name="frmParentEmail" placeholder="Email rodzica/opiekuna"
                            required
                                <?php
                                if ($_SESSION['logged'] && $user->typ == 0) {
                                    echo 'value="' . $user->email . '" readonly';
                                } ?>
                        ></div>
                    </div>
                </div>
                <div class="inputGroup textBlock"><input type="text" id="frmInne" name="frmInne" placeholder="Inne ważne informacje"></div>
            </div>
            <button id="btnRekrutacja" class="submitButton">Zapisz dziecko!</button>
            <?php
            if ($_SESSION['error'] == 2) {
                echo '<span class="error">Wystąpił błąd podczas wysyłania formularza. Spróbuj ponownie później.</span>';
                unset($_SESSION['error']);
            } ?>
            <span id="registerError" class="error"></span>
        </form>
    </div>
</main>

<script src="./scripts/js/showLogin.js"></script>
<script src="./scripts/js/indexFormValidator.js"></script>

</body>
</html>