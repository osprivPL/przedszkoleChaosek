<!DOCTYPE html>
<?php
require_once "./../scripts/php/printArr.php";
require_once __DIR__ . '/../models/User.php';
require_once "./../scripts/php/weekDayFromDate.php";

use models\User;
session_start();
$user = new User();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    header('Location: ./../index.php');
    die();
}

//session_destroy();

if (!isset($_SESSION['logged'])) {
    $_SESSION['logged'] = false;
}
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = -1;
}
if ($user->typ != 1 || !$_SESSION['logged']) {
    header('Location: ./../index.php');
    die();
}

$conteiner = 0;

$connection = mysqli_connect("localhost", "root", "", "przedszkole");

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
    <link rel="stylesheet" href="./../styles/teacher.css">

    <!-- czcionka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <!-- ikonka -->
    <link rel="icon" type="image/x-icon" href="./../assets/logo_tornado.svg">

    <title>Przedszkole Chaosek - Panel nauczyciela</title>
    <script src="./../scripts/js/panels.js"></script>
    <script src="./../scripts/js/teacher.js"></script>
</head>
<body>
<header>
    <div class="square_container">
        <div class="square"></div>
    </div>
    <a href="../index.php" id="logo" class='logo'>
        <img src="./../assets/logo_tornado.svg" alt="logo">
        <span class='logo-font-small'>Przedszkole Chaosek</span>
    </a>
    <!--Tymon zrobił by to lepiej-->
    <div class="header-ui">
        <a href="./inbox.php"><img id="mail" src="./../assets/mail.png" alt="mail"></a>
        <div onclick="userPanel(1)" class="user">
            <div><?php echo $user->imie . ' ' . $user->nazwisko; ?><br>Nauczyciel(ka)</div>
            <img src="../assets/user.svg" alt="user icon">
            <div class="user_pop_up" id="user_pop_up1">
                <a href="../index.php">Strona Główna</a>
                <a href="../scripts/php/logout.php">Wyloguj Się</a>
            </div>
        </div>
    </div>
</header>

<div class="layout">
    <!-- ============================= -->
    <!-- NAVIGATION -->
    <!-- ============================= -->
    <nav id="nav">
        <div class="nav_child" id="nav_child_dzieci" onclick="showGroups(0)">
            <img src="./../assets/playing.png" alt="">
            <span>Grupy</span>
            <span class="nav_arrow">▽</span>
        </div>
        <script>
            <?php
            $json1 = array();
            $json2 = array();
            $sql = "SELECT id, nazwa FROM grupy WHERE Wychowawca = ".$user->id.";";
            $result = $connection->query($sql)->fetch_all();
            foreach ($result as $row) {
                $json1[] = $row;
            }
//            $sql = "SELECT imie, nazwisko, pesel, adres, IDRodzica FROM dzieci WHERE grupa = "
            ?>
            let php = <?php echo json_encode($json1); ?>;
            console.log(php);
            createGroups(<?php echo json_encode($json1).', '.json_encode($json2); ?>);
            <?php
            $conteiner = $conteiner + count($json1)-1;
            ?>
        </script>
    </nav>

    <!-- ============================= -->
    <!-- MAIN -->
    <!-- ============================= -->
    <main id="main">
        <?php

            for ($i = 0; $i < count($json1); $i++) {
                echo print_r($json1[$i]).'<br>';
            }
        ?>
    </main>
</div>
</body>
</html>