<!DOCTYPE html>
<?php
require_once __DIR__ . '/models/User.php';

use models\User;

session_start();
//session_destroy();
require_once "./scripts/php/printArr.php";
if (!isset($_SESSION['logged'])) {
    $_SESSION['logged'] = false;
}
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = -1;
}
$user = new User();
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
    <link rel="stylesheet" href="old/style.css">
    <link rel="stylesheet" href="./styles/mailCode.css">

    <!-- czcionka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Momo+Trust+Display&display=swap" rel="stylesheet">

    <!-- ikonka -->
    <link rel="icon" type="image/x-icon" href="./assets/logo_tornado.svg">

    <title>Przedszkole Chaosek</title>
</head>
<body id='body'>
<!--przyciemnione tło jak odpalasz logowanie-->
<div id="dark_bg"></div>
<!-- ============================= -->
<!-- HEADER -->
<!-- ============================= -->
<header>
    <a href="index.php" id="logo">
        <img src="assets/logo_tornado.svg" alt="logo">
        <span>Przedszkole Chaosek</span>
    </a>
    <!--zrobilem troche lepiej -->
    <div id="login"
            <?php
            if ($_SESSION['logged']) {
                echo 'onclick="userPanelOn()"';
            } else {
                echo 'onclick="loginOn()"';
            }
            ?>></div>
</header>
<!-- ============================= -->
<!-- NAVIGATION -->
<!-- ============================= -->
<nav>
    <a href="index.php">
        <span>O nas</span>
    </a>
    <a href="index.php">
        <span>Aktualności</span>
    </a>
    <a href="index.php">
        <span>Jak dojechać?</span>
    </a>
    <a href="./rekrutacja.php">
        <span>REKRUTACJA</span>
    </a>
    <a href="index.php">
        <span>Kontakt</span>
    </a>
</nav>
<!-- ============================= -->
<!-- MAIN -->
<!-- ============================= -->
<main>
    <?php
    printArr($_SESSION);
    ?>
    <div id="container">
        <h1>Podaj kod wysłany na Twój email</h1>
        <form action="./scripts/php/verifyCode.php" method="post" id="codeForm">
            <label for="tbxCode">Kod:</label>
            <input type="text" name="tbxCode" id="tbxCode" maxlength="6" required>
            <span class="error" id="codeError"></span><br>
            <button type="submit" id="btnVerifyCode">Zweryfikuj kod</button>
        </form>
        <?php
        if ($_SESSION['error'] == 3) {
            echo "<span class='error'>Nieprawidłowy kod</span>";
            unset($_SESSION['error']);
        }
        ?>
    </div>
</main>

<!-- ============================= -->
<!-- LOGIN PANEL -->
<!-- ============================= -->
<div class="wrapper" id="loginWrapper">
    <form class="panel" action="./scripts/php/login.php" method="post" id="loginPanel">
        <h1 class='logo_dziennik'>
            <div>
                <img src="assets/logo_tornado.svg" alt="logo">
            </div>
            <button onclick="loginOff()" class="offButton">X</button>
        </h1>
        <h3>Log in</h3>
        <div class="Login">
            <div>
                <label for="tbxEmail">Email</label>
                <input type="email" name="tbxEmail" id="tbxEmail"><br>
                <span class="error" id="emailError"></span>
            </div>
            <div>
                <label for="tbxHaslo">Hasło</label>
                <input type="text" name="tbxHaslo" id="tbxHaslo"><br>
                <span class="error" id="passwordError"></span>
            </div>
        </div>
        <button id="btnLogin">Zaloguj</button>
        <span id="loginError" name="loginError" class="error">
        <?php
        if ($_SESSION['error'] == 1) {
            echo "Nie znaleziono użytkownika o podanym emailu";
            unset($_SESSION['error']);
        } else if ($_SESSION['error'] == 0) {
            echo "Błąd serwera, spróbuj ponownie później";
            unset($_SESSION['error']);
        } else if ($_SESSION['error'] == 2) {
            echo "Nieprawidłowe hasło";
            unset($_SESSION['error']);
        }
        ?>
    </span>
    </form>

</div>

<div class="wrapper" id="userWrapper">
    <div id="userPanel" class="panel">
        <button onclick="userPanelOff()" class="offButton">X</button>
        <p>Imię: <?php echo $user->imie ?></p>
        <p>Nazwisko: <?php echo $user->nazwisko ?></p>
        <p>Typ konta:
            <?php
            if ($user->typ == 0) {
                echo "Rodzic";
            } else if ($user->typ == 1) {
                echo "Nauczyciel(ka)";
            } else {
                echo "Dyrekcja";
            }
            ?>
        </p>
        <a href="
        <?php
        if ($user->typ == 0) {
            echo "./panels/parents.php";
        } else {
            echo "./panels/admin.html";
        }
        ?>
        ">
            <button>
                <?php
                if ($user->typ == 0) {
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