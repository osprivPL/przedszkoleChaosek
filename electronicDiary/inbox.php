<!DOCTYPE html>
<?php
error_reporting(E_ERROR | E_PARSE);
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

    <link rel="stylesheet" href="./../styles/style.css">
    <link rel="stylesheet" href="./../styles/panels.css">
    <link rel="stylesheet" href="./../styles/inbox.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Momo+Trust+Display&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="./../assets/logo_tornado.svg">

    <title>Przedszkole Chaosek - Wiadomości</title>
    <script src="./../scripts/js/showLogin.js"></script>
    <script src="./../scripts/js/inbox.js"></script>
    <script src="./../scripts/js/showUserPanel.js"></script>
</head>
<body>
<div id="dark_bg"></div>
<div class="information" id="informationPopUp">
    <div class='header'>
        <span class='circle'>i</span>
        <span>Informacja</span>
    </div>
    <div class='span-container' id="warningText">
        Coś tam Coś fdasfdsafsdfdasfdsa
    </div>
    <div class='button-container'>
        <button id="btnInformationAccept" class='submitButton okay' onclick="hideInformation()">OK</button>
    </div>
</div>
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
    <div class="header-ui">
        <div onclick="showSomething(2)" class="user">
            <div class='userLabel'><?php echo $user->imie . ' ' . $user->nazwisko; ?><br>
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
    <nav id='somethingBeingShown1'>
        <div class="nav_child" onclick="showContainerInbox(0)">
            <img src="./../assets/mailbox.png" alt="">
            <span>Odebrane</span>
        </div>
        <div class="nav_child " onclick="showContainerInbox(1)">
            <img src="./../assets/send.png" alt="">
            <span>Wysłane</span>
        </div>
        <div class="nav_child " onclick="showContainerInbox(2)">
            <img src="./../assets/recycle-bin.png" alt="">
            <span>Usunięte</span>
        </div>
        <div class="nav_child " onclick="showContainerInbox(3)">
            <img src="./../assets/drafts.png" alt="">
            <span class="toLong">Kopie robocze</span>
        </div>
        <div class="nav_exeption">
            <button onclick="showContainerInbox(4)">Nowa wiadomość</button>
        </div>
    </nav>
    <script>
        const nav = document.getElementById('somethingBeingShown1');
        nav.addEventListener('mouseleave', () => {
            nav.classList.remove('visible');
        });
    </script>
    <!-- ============================= -->
    <!-- MAIN -->
    <!-- ============================= -->
    <main id="main">
        <table id="receivedContainer" class="messagesContainer bigContainers">
            <tr class="messageCard headerCard">
                <td><label class="checkboxLabel">
                        <input type="checkbox" id="selectAllCheckboxReceived"
                               onchange="toggleAll(this, 'receivedContainer')">
                    </label></td>
                <td><span class="messageTitle">Tytuł</span></td>
                <td><span class="messageSender">Nadawca</span></td>
                <td><span class="messageDate">Data wysłania</span></td>
            </tr>
            <?php
            $connection = new mysqli("localhost", "root", "", "przedszkole");
            $result = $connection->query("SELECT id, tytul, tresc, dataWyslania, nadawcaID, odbiorcaID, usunieteNadawca FROM wiadomosci WHERE odbiorcaID = " . $user->id . " AND usunieteOdbiorca = 0 AND robocze = 0;")->fetch_all();
            for ($i = 0; $i < count($result); $i++) {
                $message = $result[$i];
                $sender = $connection->query("SELECT imie, nazwisko FROM uzytkownicy WHERE id = " . $message[4] . ";")->fetch_all();
                $tempAr = array();
                $tempAr[] = $message[0]; //id
                $tempAr[] = $message[1]; //tytul
                $tempAr[] = $message[2]; //tresc
                $tempAr[] = $message[3]; //dataWyslana
                $tempAr[] = $sender[0][0]; //imie-nadawca/odborca
                $tempAr[] = $sender[0][1]; //nazwsko-nadawca/odbiorca
                $tempAr[] = $user->imie; //imie-user
                $tempAr[] = $user->nazwisko; //nazwisko-user
                $tempAr[] = $message[4]; //nadawcaID
                $tempAr[] = $message[6]; //usunieteNadawca
                $tempAr[] = $user->id; //user id
                $tempAr[] = $message[5]; // odbiorcaID
                echo '<tr class="messageCard message" onclick=\'OpenMessage(' . json_encode($tempAr) . ', 0)\'>';
                echo '<td><label class="checkboxLabel"><input type="checkbox" class="messageCheckbox receivedCheckbox" name="message' . $message[0] . '"></label></td>';
                echo '<td><span class="messageTitle">' . $message[1] . '</span></td>';
                echo '<td><span class="messageSender">' . $sender[0][0] . ' ' . $sender[0][1] . '</span></td>';
                echo '<td><span class="messageDate">' . $message[3] . '</span></td>';
                echo '</tr>';
            }
            ?>
        </table>
        <table id="sentContainer" class="messagesContainer bigContainers">
            <tr class="messageCard headerCard">
                <td><label class="checkboxLabel">
                        <input type="checkbox" id="selectAllCheckboxSent" onchange="toggleAll(this, 'sentContainer')">
                    </label></td>
                <td><span class="messageTitle">Tytuł</span></td>
                <td><span class="messageSender">Odbiorca</span></td>
                <td><span class="messageDate">Data wysłania</span></td>
            </tr>
            <?php
            $connection = new mysqli("localhost", "root", "", "przedszkole");
            $result = $connection->query("SELECT id, tytul, tresc, dataWyslania, nadawcaID , odbiorcaID, usunieteNadawca FROM wiadomosci WHERE nadawcaID = " . $user->id . " AND usunieteNadawca = 0 AND robocze = 0 ORDER BY dataWyslania DESC;")->fetch_all();
            for ($i = 0; $i < count($result); $i++) {
                $message = $result[$i];
                $sender = $connection->query("SELECT imie, nazwisko FROM uzytkownicy WHERE id = " . $message[5] . ";")->fetch_all();

                $tempAr = array();
                $tempAr[] = $message[0]; //id
                $tempAr[] = $message[1]; //tytul
                $tempAr[] = $message[2]; //tresc
                $tempAr[] = $message[3]; //dataWyslana
                $tempAr[] = $user->imie; //imie-user
                $tempAr[] = $user->nazwisko; //nazwisko-user
                $tempAr[] = $sender[0][0]; //imie-nadawca/odborca
                $tempAr[] = $sender[0][1]; //nazwsko-nadawca/odbiorca
                $tempAr[] = $message[4]; //nadawcaID
                $tempAr[] = $message[6]; //usunieteNadawca
                $tempAr[] = $user->id; //user id
                $tempAr[] = $message[5]; // odbiorcaID
                echo '<tr class="messageCard message" onclick=\'OpenMessage(' . json_encode($tempAr) . ', 0)\'>';
                echo '<td><label class="checkboxLabel"><input type="checkbox" class="messageCheckbox sentCheckbox" name="message' . $message[0] . '"></label></td>';
                echo '<td><span class="messageTitle">' . $message[1] . '</span></td>';
                echo '<td><span class="messageSender">' . $sender[0][0] . ' ' . $sender[0][1] . '</span></td>';
                echo '<td><span class="messageDate">' . $message[3] . '</span></td>';
                echo '</tr>';
            }
            ?>
        </table>
        <table id="deletedContainer" class="messagesContainer bigContainers">
            <tr class="messageCard headerCard">
                <td><label class="checkboxLabel">
                        <input type="checkbox" id="selectAllCheckboxDeleted"
                               onchange="toggleAll(this, 'deletedContainer')">
                    </label></td>
                <td><span class="messageTitle">Tytuł</span></td>
                <td><span class="messageSender">Nadawca/Odbiorca</span></td>
                <td><span class="messageDate">Data wysłania</span></td>
            </tr>
            <?php
            $connection = new mysqli("localhost", "root", "", "przedszkole");
            $result = $connection->query("SELECT id, tytul, tresc, dataWyslania, nadawcaID, odbiorcaID, usunieteNadawca FROM wiadomosci WHERE (usunieteOdbiorca = 1 AND odbiorcaID = " . $user->id . ") OR (nadawcaID = " . $user->id . " AND usunieteNadawca = 1);")->fetch_all();
            for ($i = 0; $i < count($result); $i++) {
                $message = $result[$i];
                $sender = $connection->query("SELECT imie, nazwisko FROM uzytkownicy WHERE id = " . $message[5] . ";")->fetch_all();
                $sender2 = $connection->query("SELECT imie, nazwisko FROM uzytkownicy WHERE id = " . $message[4] . ";")->fetch_all();
                $tempAr = array();
                $tempAr[] = $message[0]; //id
                $tempAr[] = $message[1]; //tytul
                $tempAr[] = $message[2]; //tresc
                $tempAr[] = $message[3]; //dataWyslana
                $tempAr[] = $sender[0][0]; //imie-nadawca/odborca
                $tempAr[] = $sender[0][1]; //nazwsko-nadawca/odbiorca
                $tempAr[] = $user->imie; //imie-user
                $tempAr[] = $user->nazwisko; //nazwisko-user
                $tempAr[] = $message[4]; //nadawcaID
                $tempAr[] = $message[6]; //usunieteNadawca
                $tempAr[] = $user->id; //user id
                $tempAr[] = $message[5]; // odbiorcaID
                $tempAr[] = $sender2[0][0];
                $tempAr[] = $sender2[0][1];
                echo '<tr class="messageCard message" onclick=\'OpenMessage(' . json_encode($tempAr) . ', 1)\'>';
                echo '<td><label class="checkboxLabel"><input type="checkbox" class="messageCheckbox deletedCheckbox" name="message' . $message[0] . '"></label></td>';
                echo '<td><span class="messageTitle">' . $message[1] . '</span></td>';
                echo '<td><span class="messageSender">' . $sender[0][0] . ' ' . $sender[0][1] . '</span></td>';
                echo '<td><span class="messageDate">' . $message[3] . '</span></td>';
                echo '</tr>';
            }
            ?>
        </table>
        <table id="draftsContainer" class="messagesContainer bigContainers">
            <tr class="messageCard headerCard">
                <td><label class="checkboxLabel">
                        <input type="checkbox" id="selectAllCheckboxDrafts"
                               onchange="toggleAll(this, 'draftsContainer')">
                    </label></td>
                <td><span class="messageTitle">Tytuł</span></td>
                <td><span class="messageSender">Nadawca</span></td>
                <td><span class="messageDate">Data wysłania</span></td>
            </tr>
            <?php
            $connection = new mysqli("localhost", "root", "", "przedszkole");
            $result = $connection->query("SELECT id, tytul, tresc, dataWyslania, nadawcaID, odbiorcaID, usunieteNadawca  FROM wiadomosci WHERE robocze = 1 AND usunieteNadawca = 0;")->fetch_all();
            for ($i = 0; $i < count($result); $i++) {
                $message = $result[$i];
                $sender = $connection->query("SELECT imie, nazwisko FROM uzytkownicy WHERE id = " . $message[5] . ";")->fetch_all();
                $tempAr = array();
                $tempAr[] = $message[0]; //id
                $tempAr[] = $message[1]; //tytul
                $tempAr[] = $message[2]; //tresc
                $tempAr[] = $message[3]; //dataWyslana
                $tempAr[] = $user->imie; //imie-user
                $tempAr[] = $user->nazwisko; //nazwisko-user
                $tempAr[] = $sender[0][0]; //imie-nadawca/odborca
                $tempAr[] = $sender[0][1]; //nazwsko-nadawca/odbiorca
                $tempAr[] = $message[4]; //nadawcaID
                $tempAr[] = $message[6]; //usunieteNadawca
                $tempAr[] = $user->id; //user id
                $tempAr[] = $message[5]; // odbiorcaID
                echo '<tr class="messageCard message" onclick=\'OpenMessage(' . json_encode($tempAr) . ', 2)\'>';
                echo '<td><label class="checkboxLabel"><input type="checkbox" class="messageCheckbox draftsCheckbox" name="message' . $message[0] . '"></label></td>';
                echo '<td><span class="messageTitle">' . $message[1] . '</span></td>';
                echo '<td><span class="messageSender">' . $sender[0][0] . ' ' . $sender[0][1] . '</span></td>';
                echo '<td><span class="messageDate">' . $message[3] . '</span></td>';
                echo '</tr>';
            }
            ?>
        </table>
    </main>
    <div class='deleting'>
        <span>Wybrano: <span id='howMuchCheckboxes'>0</span></span>
        <span></span>
<!--        <button onclick='removeEverything()' class="submitButton">Usuń wszystkie wiadomości</button>-->
    </div>
</div>

<div class='warning' id="warningPopUp">
    <div class='header'>
        <span class='circle'>!</span>
        <span>Ostrzeżenie</span>
    </div>
    <div class='span-container'>
        <span class='warning-content'>Czy chcesz zapisać wiadomość jako kopia robocza? </span><br>
    </div>
    <div class='button-container'>
        <button class='submitButton no' onclick="DontSaveDraft(); ">Nie</button>
        <button id="btnWarningAcceptRekrutacja" onclick="SaveAsDraft()" class='submitButton yes'>Tak</button>
    </div>
</div>

<div class='warning' id="deleteFewMessagesPopUp">
    <div class='header'>
        <span class='circle'>!</span>
        <span>Ostrzeżenie</span>
    </div>
    <div class='span-container'>
        <span class='warning-content' id="warningContent">Czy napewno chcesz usunąć zaznaczone wiadomości? </span><br>
    </div>
    <div class='button-container'>
        <button class='submitButton no' onclick="hidePopUp()">Nie</button>
        <button id="btnWarningAcceptDeleting" class='submitButton yes' onclick="deleteFew()">Tak</button>
    </div>
</div>
<script>
    function hidePopUp() {
        document.getElementById('deleteFewMessagesPopUp').style.display = 'none';
        document.getElementById('dark_bg').style.display = 'none';
    }
</script>

<div id="writeContainer" class="bigContainers">

    <form action="./../scripts/php/addMessage.php" method="post" class="mailLayout" id="NewMailForm">
        <div class="offButton" onclick="PopUpDraft()">
            <p>X</p>
        </div>
        <!--        logo font mail to ma być taki sam font tylko jakaś bardziej poważna wersja-->
        <hr>
        <h1 class='logo-font'>Nowa wiadomość</h1>
        <div class="content inputGroup">
            <input id="newMesTytul" class='title' type="text" name="tytle" placeholder="Tytuł">
        </div>
        <div class="content inputGroup">
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

        <div class="content inputTextarea inputGroup">
            <textarea id="newMesTresc" name="tresc" placeholder="Treść"></textarea>
        </div>
        <div class="content inputButtons">
            <button type="submit" name="action" value="draft" class="submitButton" id="buttonDraftId">Zapisz Kopie
                roboczą
            </button>
            <button type="submit" name="action" value="sent" class="submitButton">Wyślij</button>
        </div>
    </form>
</div>

<div id="OpenedMessage">
    <div class="mailLayout formContainer">
        <div class="offButton" onclick="CloseMessage()">
            <p>X</p>
        </div>

        <div class="OnetimeUse">
            <hr>
            <h1 class="logo-font">Wiadomość</h1>
            <h2 id="MessageData" style='position: absolute;z-index:2;top: 20px; right: 80px;'>Data</h2>
        </div>
        <form action="./../scripts/php/editAndSaveDraftMessage.php" method="post" id="secretFormForEdit">
            <div class="content inputGroup">
                <input type="text" id="MessageTytle" disabled placeholder="temat">
            </div>
            <div class="content inputGroup" id="MessageDoDiv">
                <span id="MessageDoSpan">Do: <span id="MessageDo"></span></span>
                <?php
                echo '<select class="submitButton" name="odbiorca" id="MessageDoSelect">';
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
            <div class="content inputGroup">
                <textarea id="MessageTextarea" disabled>tresc</textarea>
            </div>
            <div class="content inputButtons" id="FormButtons"></div>

        </form>
    </div>
</div>

</body>
</html>