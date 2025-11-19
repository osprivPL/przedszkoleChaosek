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
    <link rel="stylesheet" href="../old/style.css">
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
    <!--stasiek zrob to lepiej-->
    <img id="login" src="./../assets/user.png" alt="login"
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
    <a href="./../index.php">
        <span>O nas</span>
    </a>
    <a href="./../index.php">
        <span>Aktualności</span>
    </a>
    <a href="./../index.php">
        <span>Jak dojechać?</span>
    </a>
    <a href="./../index.php">
        <span>REKRUTACJA</span>
    </a>
    <a href="./../index.php">
        <span>Kontakt</span>
    </a>
</nav>
<!-- ============================= -->
<!-- MAIN -->
<!-- ============================= -->
<aside>
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

<main id="main">
    <?php printArr($_SESSION); ?>

    <script src="./../scripts/js/showLogin.js"></script>
</main>
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