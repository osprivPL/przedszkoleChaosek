<!DOCTYPE html>
<?php
error_reporting(E_ERROR | E_PARSE);
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

</head>
<body id='body'>
    <div class="loading" id='loading'>
        <img src='./assets/loading.gif' alt='Ładowanie'>
    </div>
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
    <div id='container' <?php if ($_SESSION['logged']) {
        echo "class='container containerLoggedIn'";
    } else {
        echo "class='container containerLoggedOut'";
    } ?>>
        <hr>
        <h1 class='logo-font'>Rekrutacja</h1>
        <form id="frmRekrutacja" class='form' method="post" action=
                <?php
                if ($_SESSION['logged'] && $user->typ[0] == 1 && $user->typ[1] == 0 && $user->typ[2] == 0) {
                    echo "rekrutacjaCompleted.php";
                } else {
                    echo "./scripts/php/MAIL.php";
                }
                ?>>
            <div class="subContainer">
                <div class="formsContainer">
                    <div class="
                        <?php
                    if ($_SESSION['logged']) {
                        echo "frmChildLogged";
                    } else {
                        echo "frmChild";
                    }
                    ?>
                        ">
                        <?php if ($_SESSION['logged']) {
                            echo "<div class='smallerFrmChild'>";
                        } ?>
                        <div class="inputGroup"><input type="text" id="frmChildImie" name="frmChildImie"
                                                       placeholder="Imię dziecka"></div>
                        <div class="inputGroup"><input type="text" id="frmChildNazwisko" name="frmChildNazwisko"
                                                       placeholder="Nazwisko dziecka"
                            ></div>
                        <?php if ($_SESSION['logged']) {
                            echo "</div><div class='smallerFrmChild'>";
                        } ?>
                        <div class="inputGroup"><input type="text" id="frmChildPesel" name="frmChildPesel"
                                                       placeholder="Pesel dziecka"></div>
                        <div class="inputGroup"><input type="text" id="frmChildAdres" name="frmChildAdres"
                                                       placeholder="Adres zamieszkania dziecka"
                            ></div>
                        <?php if ($_SESSION['logged']) {
                            echo "</div>";
                        } ?>
                    </div>
                    <div class="frmParent" <?php
                    if ($_SESSION['logged'] && in_array(1, $user->typ)) {
                        echo 'style="display:none;"';
                    }
                    ?>>
                        <div class="inputGroup">
                            <input type="text" id="frmParentImie" name="frmParentImie"
                                   placeholder="Imię rodzica/opiekuna"
                                    <?php
                                    if ($_SESSION['logged'] && in_array(1, $user->typ)) {
                                        echo 'value="' . $user->imie . '" readonly';
                                    }
                                    ?>></div>
                        <div class="inputGroup">
                            <input type="text" id="frmParentNazwisko" name="frmParentNazwisko"
                                   placeholder="Nazwisko rodzica/opiekuna"
                                    <?php
                                    if ($_SESSION['logged'] && in_array(1, $user->typ)) {
                                        echo 'value="' . $user->nazwisko . '" readonly';
                                    }
                                    ?>></div>
                        <div class="inputGroup">
                            <input type="text" id="frmParentTelefon" name="frmParentTelefon"
                                   placeholder="Numer telefonu rodzica/opiekuna"
                                    <?php
                                    if ($_SESSION['logged'] && in_array(1, $user->typ)) {
                                        echo 'value="' . $user->telefon . '" readonly';
                                    }
                                    ?>></div>
                        <div class="inputGroup">
                            <input type="email" id="frmParentEmail" name="frmParentEmail"
                                   placeholder="Email rodzica/opiekuna"
                                    <?php
                                    if ($_SESSION['logged'] && in_array(1, $user->typ)) {
                                        echo 'value="' . $user->email . '" readonly';
                                    } ?>
                            ></div>
                    </div>
                </div>
                <div class="inputGroup textBlock"><input type="text" id="frmInne" name="frmInne"
                                                         placeholder="Inne ważne informacje"></div>
            </div>
            <button id="btnRekrutacja" class="submitButton">Zapisz dziecko!</button>
            <?php
            if ($_SESSION['error'] == 2) {
                echo '<span id="registerError" class="error">Wystąpił błąd podczas wysyłania formularza. Spróbuj ponownie później.</span>';
                unset($_SESSION['error']);
            } ?>
            <span></span>
        </form>
        <script>
            document.getElementById('frmRekrutacja').addEventListener('submit', (e) => {
                e.preventDefault();
                const form = e.target;
                document.getElementById("loading").style.display = "block";
                let childImie = document.getElementById('frmChildImie');
                let childNazwisko = document.getElementById('frmChildNazwisko');
                let childPesel = document.getElementById('frmChildPesel');
                let childAdres = document.getElementById('frmChildAdres');
                let parentImie = document.getElementById('frmParentImie');
                let parentNazwisko = document.getElementById('frmParentNazwisko');
                let parentTelefon = document.getElementById('frmParentTelefon');
                let parentEmail = document.getElementById('frmParentEmail');
                const phoneRegex = /^[0-9]{9}$/;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                let error = false;
                if (childImie.value.length < 2) {
                    error = true;
                    childImie.classList.add('error');
                } else {
                    childImie.classList.remove('error');
                }
                if (childNazwisko.value.length < 2) {
                    error = true;
                    childNazwisko.classList.add('error');
                } else {
                    childNazwisko.classList.remove('error');
                }
                if (childPesel.value.length !== 11) {
                    error = true;
                    childPesel.classList.add('error');
                } else {
                    childPesel.classList.remove('error');
                }
                if (childAdres.value.length < 5) {
                    error = true;
                    childAdres.classList.add('error');
                } else {
                    childAdres.classList.remove('error');
                }
                if (parentImie.value.length < 2) {
                    error = true;
                    parentImie.classList.add('error');
                } else {
                    parentImie.classList.remove('error');
                }
                if (parentNazwisko.value.length < 2) {
                    error = true;
                    parentNazwisko.classList.add('error');
                } else {
                    parentNazwisko.classList.remove('error');
                }

                if (!phoneRegex.test(parentTelefon.value)) {
                    error = true;
                    parentTelefon.classList.add('error');
                } else {
                    parentTelefon.classList.remove('error');
                }
                if (!emailRegex.test(parentEmail.value)) {
                    error = true;
                    parentEmail.classList.add('error');
                } else {
                    parentEmail.classList.remove('error');
                }
                let weight = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
                let sum = 0;
                let controlNumber = parseInt(childPesel.value.substring(10, 11));


                if (childPesel.value.length !== 11 || isNaN(childPesel.value)) {
                    childPesel.classList.add('error')
                    error = true;
                }
                else{
                    childPesel.classList.remove('error')
                }

                for (let i = 0; i < weight.length; i++) {
                    sum += (parseInt(childPesel.value.substring(i, i + 1)) * weight[i]);
                }
                sum = sum % 10;
                if ((10 - sum) % 10 !== controlNumber) {
                    childPesel.classList.add('error')
                    error = true;
                }
                else{
                    childPesel.classList.remove('error')
                }
                const d = new Date();
                const wiek = d.getFullYear() - parseInt(dateFromPesel(childPesel.value).substring(0,4));
                if(wiek < 3 || wiek > 6){
                    childPesel.classList.add('error')
                    error = true;
                }else{
                    childPesel.classList.remove('error')
                }

                if (error){
                    document.getElementById("loading").style.display = "none";
                    return;
                }
                form.submit();
            });
        </script>
    </div>
</main>

<script src="scripts/js/registerValidator.js"></script>
<script src="scripts/js/childrens.js"></script>
</body>
</html>