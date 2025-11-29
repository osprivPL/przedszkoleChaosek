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
    <script src="./../scripts/js/showLogin.js"></script>
    <script src="./../scripts/js/inbox.js"></script>
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
        <a href="./parents.php"><img id="mail" src="./../assets/main_page2.png" alt="główna"></a>
        <div onclick="userPanel(1)" class="user">
                    <div><?php echo $user->imie.' '.$user->nazwisko;?><br>Rodzic</div>
                    <img src="../assets/user.svg" alt="user icon">
                    <div class="user_pop_up" id="user_pop_up1">
                        <a href="parents.php">Panel Rodzica</a>
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
    <nav>
        <!-- ZROBIC IKONKI DO TEGO, CZYT. ZMIENIC -->
        <div class="nav_child" onclick="showContainer(0)">
            <img src="./../assets/mailbox.png" alt="">
            <span>Odebrane</span>
        </div>
        <div class="nav_child "onclick=" showContainer(1)">
            <img src="./../assets/send.png" alt="">
            <span>Wysłane</span>
        </div>
        <div class="nav_child " onclick="showContainer(2)">
            <img src="./../assets/recycle-bin.png" alt="">
            <span>Usunięte</span>
        </div>
        <div class="nav_child "onclick="showContainer(3)">
            <img src="./../assets/drafts.png" alt="">
            <span>Kopie robocze</span>
        </div>
    </nav>

    <!-- ============================= -->
    <!-- MAIN -->
    <!-- ============================= -->
    <main id="main">
        <!--Ma otwierać "nakładke" do pisania wiadomości-->
        <button id="btnWrite" onclick="showContainer(4)">Napisz wiadomość</button>
        <table id="receivedContainer" class="messagesContainer">
            <tr class="messageCard headerCard">
                <td><input type="checkbox" id="selectAllCheckbox1" onclick="selectAllCheckboxes(1)"></td>
                <td><span class="messageTitle">Tytuł</span></td>
                <td><span class="messageSender">Nadawca</span></td>
                <td><span class="messageDate">Data wysłania</span></td>
                <td></td>
            </tr>
            <?php
            $connection = new mysqli("localhost", "root", "", "przedszkole");
            $result = $connection->query("SELECT id, tytul, tresc, dataWyslania, nadawcaID FROM wiadomosci WHERE odbiorcaID = " . $user->id . ";")->fetch_all();
            for ($i = 0; $i < count($result); $i++) {
                $message = $result[$i];
                $sender = $connection->query("SELECT imie, nazwisko FROM uzytkownicy WHERE id = " . $message[4] . ";")->fetch_all();
                echo '<tr class="messageCard">';
                echo '<td><input type="checkbox" class="messageCheckbox" name="message' . $message[0] . '"></td>';
                echo '<td><span class="messageTitle">' . $message[1] . '</span></td>';
                echo '<td><span class="messageSender">' . $sender[0][0] . ' ' . $sender[0][1] . '</span></td>';
                echo '<td><span class="messageDate">' . $message[3] . '</span></td>';
                echo '<td><span><img src="./../assets/trash.png"</span></td>';
                echo '</tr>';
            }
            ?>
            <script>setIleWiadomosci(<?php echo count($result);?>);</script>
        </>
        <table id="sentContainer" class="messagesContainer">
            <tr class="messageCard headerCard">
                <td><input type="checkbox" id="selectAllCheckbox1" onclick="selectAllCheckboxes(1)"></td>
                <td><span class="messageTitle">Tytuł</span></td>
                <td><span class="messageSender">Nadawca</span></td>
                <td><span class="messageDate">Data wysłania</span></td>
                <td></td>
            </tr>
            <?php
            $connection = new mysqli("localhost", "root", "", "przedszkole");
            $result = $connection->query("SELECT id, tytul, tresc, dataWyslania, nadawcaID FROM wiadomosci WHERE nadawcaID = " . $user->id . ";")->fetch_all();
            for ($i = 0; $i < count($result); $i++) {
                $message = $result[$i];
                $sender = $connection->query("SELECT imie, nazwisko FROM uzytkownicy WHERE id = " . $message[4] . ";")->fetch_all();
                echo '<tr class="messageCard">';
                echo '<td><input type="checkbox" class="messageCheckbox" name="message' . $message[0] . '"></td>';
                echo '<td><span class="messageTitle">' . $message[1] . '</span></td>';
                echo '<td><span class="messageSender">' . $sender[0][0] . ' ' . $sender[0][1] . '</span></td>';
                echo '<td><span class="messageDate">' . $message[3] . '</span></td>';
                echo '<td><span><img src="./../assets/trash.png"</span></td>';
                echo '</tr>';
            }
            ?>
            <script>setIleWiadomosci(<?php echo count($result);?>);</script>
        </>
        <div id="deletedContainer" class="messagesContainer">
        </div>
        <div id="draftsContainer" class="messagesContainer">

        </div>
        <div id="writeContainer" class="messagesContainer">

        </div>
    </main>
<span>nyga nyga nyga</span>
</div>
<script src="./../scripts/js/showUserPanel.js"></script>
</body>
</html>