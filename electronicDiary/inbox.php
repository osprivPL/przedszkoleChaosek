<!DOCTYPE html>
<?php
require_once "./../scripts/php/printArr.php";
require_once __DIR__ . '/../models/User.php';

use models\User;

session_start();

$user = new User();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}


require_once "./../scripts/php/printArr.php";
if (!isset($_SESSION['logged'])) {
    $_SESSION['logged'] = false;
}
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = -1;
}
if (!$_SESSION['logged']) {
    header("Location: ./../index.php");
    die();
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
    <link rel="stylesheet" href="./../styles/style.css">
    <link rel="stylesheet" href="./../styles/panels.css">
<!--    <link rel="stylesheet" href="./../styles/parents.css">-->
    <link rel="stylesheet" href="./../styles/inbox.css">

    <!-- czcionka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <!-- ikonka -->
    <link rel="icon" type="image/x-icon" href="./../assets/logo_tornado.svg">

    <title>Przedszkole Chaosek - <?php
        if ($user->typ == 0) {
            echo "Panel Rodzica";
        } else {
            echo "Panel administratora";
        }
        ?></title>
</head>
<body>
<header>
    <a href="../index.php" id="logo">
        <img src="./../assets/logo_tornado.svg" alt="logo">
        <span>Przedszkole Chaosek</span>
    </a>
    <!--Tymon zrobił by to lepiej-->
    <div class="header-ui">
        <a href="./inbox.php"><img id="mail" src="./../assets/mail.png" alt="mail"></a>
        <img id="login" src="./../assets/user.svg" alt="login"
                <?php
                if ($_SESSION['logged']) {
                    echo 'onclick="userPanelOn()"';
                } else {
                    echo 'onclick="loginOn()"';
                }
                ?>>
    </div>
</header>

<div class="layout">
    <!-- ============================= -->
    <!-- NAVIGATION -->
    <!-- ============================= -->
    <nav>
        <!-- ZROBIC IKONKI DO TEGO, CZYT. ZMIENIC -->
        <div class="nav_child">
            <img src="./../assets/main_page.png" alt="">
            <span>Odebrane</span>
        </div>
        <div class="nav_child nav_child_dzieci">
            <img src="./../assets/playing.png" alt="">
            <span>Wysłane</span>
        </div>
        <div class="nav_child nav_child_szkola">
            <img src="./../assets/school.png" alt="">
            <span>Usunięte</span>
        </div>
        <div class="nav_child ">
            <img src="./../assets/restaurant.png" alt="">
            <span>Kopie robocze</span>
        </div>
    </nav>

    <!-- ============================= -->
    <!-- MAIN -->
    <!-- ============================= -->
    <main id="main">
        <!--Ma otwierać "nakładke" do pisania wiadomości-->
        <button id="btnWrite">Napisz wiadomość</button>
        <div id="messagesContainer" class="messagesContainer">

        </div>
        <script src="./../scripts/js/showLogin.js"></script>

    </main>
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
            echo "./parents.php";
        } else {
            echo "./admin.html";
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
        <form action="./../scripts/php/logout.php" method="post">
            <button type="submit">Wyloguj</button>
        </form>
    </div>
</div>
<script src="./../scripts/js/childrens.js"></script>
</body>
</html>