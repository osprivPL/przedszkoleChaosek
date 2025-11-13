<!DOCTYPE html>
<?php
session_start();
//session_destroy();
require_once "./scripts/php/printArr.php";
if (!isset($_SESSION['logged'])) {
    $_SESSION['logged'] = false;
}
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = -1;
} else if ($_SESSION['error'] != -1) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var loginEl = document.getElementById("login");
            if (loginEl) loginEl.click();
        });
    </script>';
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
    <link rel="stylesheet" href="./styles/style.css">
<!--    <link rel="stylesheet" href="./styles/index.css">-->

    <!-- czcionka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <!-- ikonka -->
    <link rel="icon" type="image/x-icon" href="./assets/logo_tornado.svg">

    <title>Przedszkole Chaosek</title>
</head>
<body>
<!-- ============================= -->
<!-- HEADER -->
<!-- ============================= -->
<header>
    <a href="./index.php" id="logo">
        <img src="assets/logo_tornado.svg" alt="logo">
        <span>Przedszkole Chaosek</span>
    </a>
    <!--stasiek zrob to lepiej-->
    <img id="login" src="./assets/person.png" alt="login"
            <?php
            if ($_SESSION['logged']) {
                echo 'onclick="userPanelOn()"';
            } else {
                echo 'onclick="loginOn()"';
            }
            ?>>
</header>
<!-- ============================= -->
<!-- NAVIGATION -->
<!-- ============================= -->
<nav>
    <a href="./index.php">
        <span>O nas</span>
    </a>
    <a href="./index.php">
        <span>Aktualności</span>
    </a>
    <a href="./index.php">
        <span>Jak dojechać?</span>
    </a>
    <a href="./index.php">
        <span>REKRUTACJA</span>
    </a>
    <a href="./index.php">
        <span>Kontakt</span>
    </a>
</nav>
<!-- ============================= -->
<!-- MAIN -->
<!-- ============================= -->
<main>
    <?php printArr($_SESSION); ?>
</main>

<!-- logowanie-->

<!-- jakas rejestracja -->
<!--<div class="SignUp">-->
<!--    <h3>Sign up</h3>-->
<!--    <div class="SignInLogIn">-->
<!--        <label for="tbxImie_singUp">Imie</label>-->
<!--        <input type="text" name="tbxImie_singUp" id="tbxImie_singUp">-->
<!---->
<!--        <label for="tbxNazw_singUp">Nazwisko</label>-->
<!--        <input type="text" name="tbxNazw_singUp" id="tbxNazw_singUp">-->
<!---->
<!--        <label for="tbxNum_singUp">Numer telefonu</label>-->
<!--        <input type="number" name="tbxNum_singUp" id="tbxNum_singUp">-->
<!--        <label for="tbxPesel_singUp">Pesel</label>-->
<!--        <input type="text" name="tbxPesel_singUp" id="tbxPesel_singUp">-->
<!---->
<!--        <label for="tbxAdres_singUp">Miejsce zamieszkania</label>-->
<!--        <input type="text" name="tbxAdres_singUp" id="tbxAdres_singUp">-->
<!---->
<!--        <label for="tbxHaslo_singUp">Hasło</label>-->
<!--        <input type="text" name="tbxHaslo_singUp" id="tbxHaslo_singUp">-->
<!---->
<!--        <label for="tbxHasloRep_singUp">Powturzenie hasła</label>-->
<!--        <input type="text" name="tbxHasloRep_singUp" id="tbxHasloRep_singUp">-->
<!--    </div>-->
<!--</div>-->

<!-- ============================= -->
<!-- LOGIN PANEL -->
<!-- ============================= -->
<div class="wrapper" id="loginWrapper">
    <form class="panel" action="./scripts/php/login.php" method="post" id="loginPanel">
        <h3>Log in</h3>
        <div class="Login">
            <div>
                <label for="tbxEmail">Email</label>
                <input type="email" name="tbxEmail" id="tbxEmail"><br>
                <span class="error" id="emailError"></span>
            </div>
            <div>
                <label for="tbxHaslo">Hasło</label>
                <input type="text" name="tbxHaslo" id="tbxHaslo">
                <span class="error" id="passwordError"></span>
            </div>
            <button onclick="loginOff()" class="offButton">X</button>
        </div>
        <button id="btnLogin">Zaloguj</button>
        <span id="loginError" name="loginError" class="error">
        <?php
        if (isset($_SESSION['error'])) {

        }
        if ($_SESSION['error'] == 1) {
            echo "Nie znaleziono użytkownika o podanym emailu";
            unset($_SESSION['error']);
        } else if ($_SESSION['error'] == 0) {
            echo "Błąd serwera, spróbuj ponownie później";
            unset($_SESSION['error']);
        }
        ?>
    </span>
    </form>

</div>

<div class="wrapper" id="userWrapper">
    <div id="userPanel" class="panel">
        <button onclick="userPanelOff()" class="offButton">X</button>
        <p>Imię: <?php echo $_SESSION['imie'] ?></p>
        <p>Nazwisko: <?php echo $_SESSION['nazwisko'] ?></p>
        <p>Typ konta:
            <?php
            if ($_SESSION['typ'] == 0) {
                echo "Rodzic";
            } else if ($_SESSION['typ'] == 1) {
                echo "Nauczyciel(ka)";
            } else {
                echo "Dyrekcja";
            }
            ?>
        </p>
        <a href="
        <?php
        if ($_SESSION['typ'] == 0) {
            echo "./panels/parents.php";
        } else {
            echo "./panels/admin.html";
        }
        ?>
        ">
            <button>
                <?php
                    if ($_SESSION['typ'] == 0) {
                        echo "Panel Rodzica";
                    } else {
                        echo "Panel Pracownika";
                    }
                ?>
            </button>
        </a>
        <form action="./scripts/php/logout.php" method="post">
            <button type="submit">Wyloguj</button>
        </form>
    </div>
</div>


<script src="./scripts/js/showLogin.js"></script>
<script src="./scripts/js/indexFormValidator.js"></script>

</body>
</html>