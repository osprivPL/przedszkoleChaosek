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

    <title>Przedszkole Chaosek - Wiadomości</title>
    <script src="./../scripts/js/showLogin.js"></script>
    <script src="./../scripts/js/inbox.js"></script>
    <script src="./../scripts/js/showUserPanel.js"></script>
</head>
<body>
<header>
    <div class="square_container">
        <div class="square"></div>
    </div>
    <div class="options" onclick='showSomething(1)'>
        <span>☰</span>
    </div>
    <a href="../index.php" id="logo" class='logo'>
        <img src="./../assets/logo_tornado.svg" alt="logo">
        <span class='logo-font-small'>Przedszkole Chaosek</span>
    </a>
    <!--Tymon zrobił by to lepiej-->
    <div class="header-ui">
        <a href="./parents.php"><img id="mail" src="./../assets/main_page2.png" alt="główna"></a>
        <div onclick="showSomething(2)" class="user">
            <div><?php echo $user->imie . ' ' . $user->nazwisko; ?><br>
                <?php
                if ($user->typ[0] == 1) {
                    $typ = "Rodzic/";
                }
                if ($user->typ[1] == 1) {
                    $typ = "Nauczyciel/";
                }
                if ($user->typ[2] == 1) {
                    $typ = "Dyrekcja/";
                }
                $typ = substr($typ, 0, strlen($typ) - 1);
                echo $typ;
                ?>
            </div>
            <img src="../assets/user.svg" alt="user icon">
            <div class="user_pop_up" id="somethingBeingShown2">
                <?php
                if ($user->typ[0] == 1) {
                    echo '<a href="./parents.php">Panel Rodzica</a>';
                }
                if ($user->typ[1] == 1) {
                    echo '<a href="./teacher.php">Panel Nauczyciela</a>';
                }
                if ($user->typ[2] == 1) {
                    echo '<a href="./principle.php">Panel Dyrekcji</a>';
                }
                $typ = substr($typ, 0, strlen($typ) - 1);
                ?>
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
    <nav id='somethingBeingShown1'>
        <!-- ZROBIC IKONKI DO TEGO, CZYT. ZMIENIC -->
        <div class="nav_child" onclick="showContainer(0)">
            <img src="./../assets/mailbox.png" alt="">
            <span>Odebrane</span>
        </div>
        <div class="nav_child " onclick="showContainer(1)">
            <img src="./../assets/send.png" alt="">
            <span>Wysłane</span>
        </div>
        <div class="nav_child " onclick="showContainer(2)">
            <img src="./../assets/recycle-bin.png" alt="">
            <span>Usunięte</span>
        </div>
        <div class="nav_child " onclick="showContainer(3)">
            <img src="./../assets/drafts.png" alt="">
            <span class="toLong">Kopie robocze</span>
        </div>
        <div class="nav_exeption">
            <button onclick="showContainer(4)">Nowa wiadomość</button>
        </div>
    </nav>

    <!-- ============================= -->
    <!-- MAIN -->
    <!-- ============================= -->
    <main id="main">
        <!--Ma otwierać "nakładke" do pisania wiadomości-->

        <!--=======================================================-->
        <!-- ODEBRANE        -->
        <!--=======================================================-->
        <table id="receivedContainer" class="messagesContainer bigContainers">
            <tr class="messageCard headerCard">
                <td><label class="checkboxLabel"><input type="checkbox" id="selectAllCheckbox1"
                                                        onclick="selectAllCheckboxes(1)"></label></td>
                <td><span class="messageTitle">Tytuł</span></td>
                <td><span class="messageSender">Nadawca</span></td>
                <td><span class="messageDate">Data wysłania</span></td>
                <td></td>
            </tr>
            <?php
            $connection = new mysqli("localhost", "root", "", "przedszkole");
            $result = $connection->query("SELECT id, tytul, tresc, dataWyslania, nadawcaID FROM wiadomosci WHERE odbiorcaID = " . $user->id . " AND Usunięte = 0 AND robocze = 0;")->fetch_all();
            for ($i = 0; $i < count($result); $i++) {
                $message = $result[$i];
                $sender = $connection->query("SELECT imie, nazwisko FROM uzytkownicy WHERE id = " . $message[4] . ";")->fetch_all();
                echo '<tr class="messageCard" onclick="OpenMessage('. $message[0] . ', 0)">';
                echo '<td><label class="checkboxLabel"><input type="checkbox" class="messageCheckbox" name="message' . $message[0] . '"></label></td>';
                echo '<td><span class="messageTitle">' . $message[1] . '</span></td>';
                echo '<td><span class="messageSender">' . $sender[0][0] . ' ' . $sender[0][1] . '</span></td>';
                echo '<td><span class="messageDate">' . $message[3] . '</span></td>';
                echo '<td><span><img src="./../assets/trash.png" class="mail_icons"></span></td>';
                echo '</tr>';
            }
            ?>
            <script>setIleWiadomosci(<?php echo count($result);?>);</script>
        </table>
        <!--=======================================================-->
        <!-- WYSŁANE        -->
        <!--=======================================================-->
        <table id="sentContainer" class="messagesContainer bigContainers">
            <tr class="messageCard headerCard">
                <td><label class="checkboxLabel"><input type="checkbox" id="selectAllCheckbox1"
                                                        onclick="selectAllCheckboxes(1)"></label></td>
                <td><span class="messageTitle">Tytuł</span></td>
                <td><span class="messageSender">Odbiorca</span></td>
                <td><span class="messageDate">Data wysłania</span></td>
                <td></td>
            </tr>
            <?php
            $connection = new mysqli("localhost", "root", "", "przedszkole");
            $result = $connection->query("SELECT id, tytul, tresc, dataWyslania, odbiorcaID FROM wiadomosci WHERE nadawcaID = " . $user->id . " AND Usunięte = 0 AND robocze = 0;")->fetch_all();
            for ($i = 0; $i < count($result); $i++) {
                $message = $result[$i];
                $sender = $connection->query("SELECT imie, nazwisko FROM uzytkownicy WHERE id = " . $message[4] . ";")->fetch_all();
                echo '<tr class="messageCard" onclick="OpenMessage('. $message[0] . ', 0)">';
                echo '<td><label class="checkboxLabel"><input type="checkbox" class="messageCheckbox" name="message' . $message[0] . '"></label></td>';
                echo '<td><span class="messageTitle">' . $message[1] . '</span></td>';
                echo '<td><span class="messageSender">' . $sender[0][0] . ' ' . $sender[0][1] . '</span></td>';
                echo '<td><span class="messageDate">' . $message[3] . '</span></td>';
                echo '<td><span><img src="./../assets/trash.png"></span></td>';
                echo '</tr>';
            }
            ?>
            <script>setIleWiadomosci(<?php echo count($result);?>);</script>
        </
        >
        <!--=======================================================-->
        <!-- USUNIĘTE        -->
        <!--=======================================================-->
        <table id="deletedContainer" class="messagesContainer bigContainers">
            <tr class="messageCard headerCard">
                <td><label class="checkboxLabel"><input type="checkbox" id="selectAllCheckbox1"
                                                        onclick="selectAllCheckboxes(1)"></label></td>
                <td><span class="messageTitle">Tytuł</span></td>
                <td><span class="messageSender">Nadawca</span></td>
                <td><span class="messageDate">Data wysłania</span></td>
                <td></td>
            </tr>
            <?php
            $connection = new mysqli("localhost", "root", "", "przedszkole");
            $result = $connection->query("SELECT id, tytul, tresc, dataWyslania, nadawcaID FROM wiadomosci WHERE Usunięte = 1;")->fetch_all();
            for ($i = 0; $i < count($result); $i++) {
                $message = $result[$i];
                $sender = $connection->query("SELECT imie, nazwisko FROM uzytkownicy WHERE id = " . $message[4] . ";")->fetch_all();
                echo '<tr class="messageCard" onclick="OpenMessage('. $message[0] . ', 2)">';
                echo '<td><label class="checkboxLabel"><input type="checkbox" class="messageCheckbox" name="message' . $message[0] . '"></label></td>';
                echo '<td><span class="messageTitle">' . $message[1] . '</span></td>';
                echo '<td><span class="messageSender">' . $sender[0][0] . ' ' . $sender[0][1] . '</span></td>';
                echo '<td><span class="messageDate">' . $message[3] . '</span></td>';
                echo '<td><span><img src="./../assets/trash.png"></span></td>';
                echo '</tr>';
            }
            ?>
            <script>setIleWiadomosci(<?php echo count($result);?>);</script>
        </table>
        <!--=======================================================-->
        <!-- KOPIE ROBOCZE        -->
        <!--=======================================================-->
        <table id="draftsContainer" class="messagesContainer bigContainers">
            <tr class="messageCard headerCard">
                <td><label class="checkboxLabel"><input type="checkbox" id="selectAllCheckbox1"
                                                        onclick="selectAllCheckboxes(1)"></label></td>
                <td><span class="messageTitle">Tytuł</span></td>
                <td><span class="messageSender">Nadawca</span></td>
                <td><span class="messageDate">Data wysłania</span></td>
                <td></td>
            </tr>
            <?php
            $connection = new mysqli("localhost", "root", "", "przedszkole");
            $result = $connection->query("SELECT id, tytul, tresc, dataWyslania, nadawcaID FROM wiadomosci WHERE robocze = 1;")->fetch_all();
            for ($i = 0; $i < count($result); $i++) {
                $message = $result[$i];
                $sender = $connection->query("SELECT imie, nazwisko FROM uzytkownicy WHERE id = " . $message[4] . ";")->fetch_all();
                echo '<tr class="messageCard" onclick="OpenMessage('. $message[0] . ', 1)">';
                echo '<td><label class="checkboxLabel"><input type="checkbox" class="messageCheckbox" name="message' . $message[0] . '"></label></td>';
                echo '<td><span class="messageTitle">' . $message[1] . '</span></td>';
                echo '<td><span class="messageSender">' . $sender[0][0] . ' ' . $sender[0][1] . '</span></td>';
                echo '<td><span class="messageDate">' . $message[3] . '</span></td>';
                echo '<td><span><img src="./../assets/trash.png"></span></td>';
                echo '</tr>';
            }
            ?>
        </table>
    </main>
</div>

<div id="writeContainer" class="bigContainers">
    <form action="./../scripts/php/addMessage.php" method="post">
        <div class="offButton" onclick="showContainer(0)">
            <p>X</p>
        </div>
        <h1 class="logo-font">Nowa wiadomość</h1>
        <!--        logo font mail to ma być taki sam font tylko jakaś bardziej poważna wersja-->
        <div class="content">
            <?php
            echo '<select class="newMesOdbiorcy submitButton" name="odbiorca">';
            if ($user->typ[0] == 1) {
                $sql_mes = "SELECT u.ID, u.imie, u.nazwisko, u.typ FROM uzytkownicy u 
                        INNER JOIN grupy g ON u.ID = g.Wychowawca 
                        INNER JOIN dzieci d ON d.grupa = g.id WHERE d.IDRodzica = " . $user->id . "
                        UNION 
                        SELECT u.ID, u.imie, u.nazwisko, u.typ FROM uzytkownicy u 
                        INNER JOIN uprawnienia a ON u.ID = a.ID
                        WHERE a.dyrektor = 1 
                        ORDER BY nazwisko;";
            }
            if ($user->typ[1] == 1) {
                $sql_mes = "SELECT u.ID, u.imie, u.nazwisko, u.typ FROM uzytkownicy u 
                        INNER JOIN dzieci d ON u.ID = d.IDRodzica 
                        INNER JOIN grupy g ON d.grupa = g.id 
                        WHERE g.Wychowawca = " . $user->id . "
                        UNION 
                        SELECT u.ID, u.imie, u.nazwisko, u.typ FROM uzytkownicy u 
                        INNER JOIN uprawnienia a ON u.ID = a.ID
                        WHERE a.dyrektor = 1
                        ORDER BY nazwisko;";
            }
            if ($user->typ[2] == 1) {
                $sql_mes = "SELECT ID, imie, nazwisko, typ FROM uzytkownicy
                            WHERE id != " . $user->id . " 
                            ORDER BY nazwisko;";
            }

            $result = $connection->query($sql_mes)->fetch_all();

            for ($i = 0; $i < count($result); $i++) {
                $sql_typ = "SELECT * FROM uprawnienia WHERE ID = " . $result[$i][3] . ";";
                $resultTyp = $connection->query($sql_typ)->fetch_all();
                if ((int)$resultTyp[0][3] == "1") {
                    $typ = "Dyrekcja";
                } elseif ((int)$resultTyp[0][2] == "1") {
                    $typ = "Nauczyciel";
                } else {
                    $typ = "Rodzic";
                }
                echo "<option class='submitButton' value='" . $result[$i][0] . "'>" . $result[$i][1] . " " . $result[$i][2] . " - " . $typ . "</option>";
            }
            echo "</select>";
            ?>
        </div>
        <div class="content">
            <input id="newMesTytul" type="text" name="tytle" placeholder="Tytuł">
        </div>
        <div class="content inputTextarea">
            <textarea id="newMesTresc" name="tresc" placeholder="Treść"></textarea>
        </div>
        <div class="content inputButtons">
            <button type="submit" onclick="showContainer(0)" name="action" value="draft" class="buttonDraft">Zapisz Kopie roboczą</button>
            <button type="submit" onclick="showContainer(0)" name="action" value="sent" class="buttonSent">Wyślij</button>

        </div>
    </form>
</div>

<div id="OpenedMessage">

</div>

</body>
</html>