<!DOCTYPE html>
<?php
session_start();
//session_destroy();
require_once "./../scripts/php/printArr.php";
if (!isset($_SESSION['logged'])) {
    $_SESSION['logged'] = false;
}
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = -1;
}
if (!isset($_SESSION['typ'])) {
    header('Location: ./../../index.php');
    die();
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
    <link rel="stylesheet" href="./../styles/parents.css">

    <!-- czcionka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <!-- ikonka -->
    <link rel="icon" type="image/x-icon" href="./../assets/logo_tornado.svg">

    <title>Przedszkole Chaosek - <?php
        if (isset($_SESSION['typ']) && $_SESSION['typ'] == 0) {
            echo "Panel Rodzica";
        } else {
            echo "Panel administratora";
        }
        ?></title>
</head>
<body>
<header>
    <a href="./../index.php" id="logo">
        <img src="./../assets/logo_tornado.svg" alt="logo">
        <span>Przedszkole Chaosek</span>
    </a>
    <!--Tymon zrobił by to lepiej-->
    <div class="header-ui">
    <img id="mail" src="./../assets/mail.png" alt="mail">
    <img id="login" src="./../assets/.png" alt="login"
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
    <div class="nav_child">
        <img src="./../assets/main_page.png" alt="">
        <span>Panel główny</span>
    </div>
    <div class="nav_child nav_child_dzieci">
        <img src="./../assets/playing.png" alt="">
        <span>Dziecko</span>
    </div>
    <div class="nav_child nav_child_szkola">
        <img src="./../assets/school.png" alt="">
        <span>o Szkole</span>
    </div>
    <div class="nav_child ">
        <img src="./../assets/restaurant.png" alt="">
        <span>Stołówka</span>
    </div>
    <div class="nav_child">
        <img src="./../assets/speaker.png" alt="">
        <span>Ogłoszenia</span>
    </div>
</nav>

<!-- ============================= -->
<!-- MAIN -->
<!-- ============================= -->
<main id="main">










    <!--<aside>
        <ul id="listaDzieci">
            <?php
            $connection = mysqli_connect("localhost", "root", "", "przedszkole");
            $json = array();
            if (!$connection) {
                echo "Brak połączenia z bazą danych";
            } else {
                if ($result = $connection->query(sprintf("SELECT imie, nazwisko, pesel, adres, grupa FROM dzieci WHERE IDrodzica='%s'", mysqli_real_escape_string($connection, $_SESSION['id'])))) {
                    $result = $result->fetch_all();
                    foreach ($result as $row) {
                        $json[] = $row;
                    }
                }
            }
            $connection->close();
            ?>

        </ul>
    </aside>
    <?php printArr($_SESSION); ?>

    <script src="./../scripts/js/showLogin.js"></script>-->

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
<script>
    let php = <?php echo json_encode($json); ?>;
    showOnAside(php);
    // console.log(php);
</script>
</body>
</html>