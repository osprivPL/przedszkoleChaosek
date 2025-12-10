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
    <link rel="stylesheet" href="./../styles/parents.css">

    <!-- czcionka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Momo+Trust+Display&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">

    <!-- ikonka -->
    <link rel="icon" type="image/x-icon" href="./../assets/logo_tornado.svg">

    <title>Przedszkole Chaosek - Panel rodzica</title>
    <script src="./../scripts/js/panels.js"></script>
    <script src="./../scripts/js/childrens.js"></script>
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
        <a href="./inbox.php"><img id="mail" src="./../assets/mail.png" alt="mail"></a>
        <div onclick="showSomething(2)" class="user">
            <div><?php echo $user->imie . ' ' . $user->nazwisko; ?><br>Rodzic</div>
            <img src="../assets/user.svg" alt="user icon">
            <div class="user_pop_up" id="somethingBeingShown2">
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
    <nav id="somethingBeingShown1">

        <div class="nav_child" id="nav_child_dzieci" onclick="showChildren(4)">
            <img src="./../assets/playing.png" alt="">
            <span>Grupy</span>
            <span class="nav_arrow">▽</span>
        </div>

        <?php
        $sql = "SELECT nazwa FROM grupy WHERE Wychowawca = " . $user->id . ";";
        $resultGroups = $connection->query($sql)->fetch_all();
        for ($i = 0; $i < count($resultGroups); $i++) {
            echo '<div class="nav_child nav_child_child nav_child_group" onclick="showContainer(' . $conteiner . ')">
                        <img src="./../assets/group.png" alt="">
                        <span>' . $resultGroups[$i][0] . '</span>
                    </div>';
            $conteiner++;
        }
        ?>

        <div class="nav_child" id="nav_child_szkola" onclick="showChildren(1)">
            <img src="./../assets/school.png" alt="">
            <span>o Szkole</span>
            <span class="nav_arrow">▽</span>
        </div>

        <div class="nav_child nav_child_child nav_child_oSzkole" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/teacher.png" alt="">
            <span>Nauczyciele</span>
        </div>

        <div class="nav_child " onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/restaurant.png" alt="">
            <span>Stołówka</span>
        </div>
        <div class="nav_child" onclick="showContainer(<?php echo $conteiner; ?>)">
            <img src="./../assets/speaker.png" alt="">
            <span>Komunikaty</span>
        </div>
    </nav>

    <!-- ============================= -->
    <!-- MAIN -->
    <!-- ============================= -->
    <main id="main">
        <?php
        for ($i = 0; $i < count($resultGroups); $i++) {
            echo '<div class="main-panel bigContainers main-panel-groups" id="main-groups">';
            echo '<div class="styling-panel">';
            echo "<div class='formContainer'>";
            echo 'Grupa'. $resultGroups[$i][0];
            echo '</div>';
            echo '</div>';
            echo '</div>';
            }
        ?>
        <!-- ============================= -->
        <!-- NAUCZYCIELE -->
        <!-- ============================= -->
        <div class="main-panel bigContainers main-panel-teachers" id="main-teachers">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class='logo-font-small'>Nauczyciele</h1>
                    <?php
                    $sql = "SELECT imie, nazwisko, login ,typ, opinia, zdjecie FROM uzytkownicy WHERE typ = 1 OR typ = 2 ORDER BY typ DESC";
                    $result = $connection->query($sql)->fetch_all();
                    //                    print_r($result);
                    for ($i = 0; $i < count($result); $i++) {
                        echo '<fieldset class="teacherCards" style="animation-delay: ' . $i * 0.2 . 's">';
                        if ($result[$i][3] == 1) {
                            $typ = "Nauczyciel";
                        } else if ($result[$i][3] == 2) {
                            $typ = "Dyrektor";
                        }
                        echo '<legend>' . $typ . '</legend>';
                        echo '<div class="teacherInfo">';
                        echo '<h3>' . $result[$i][0] . " " . $result[$i][1] . '<span class="email">' . $result[$i][2] . '</span></h3>';
                        echo '<p class="opinia"> ' . $result[$i][4] . "</p>";
                        echo '</div>';
                        echo '<div>';
                        echo "<div class='imgContainer' style='background-image: url(./../assets/staff/" . $result[$i][5] . ")'></div>";
                        echo '</div>';
                        $typ = "";


                        echo '</fieldset>';
                    }

                    ?>
                </div>
            </div>
        </div>
        <!-- ============================= -->
        <!-- CAFETERIA -->
        <!-- ============================= -->
        <div class="main-panel bigContainers main-panel-food" id="main-cafeteria">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class='logo-font-small'>Jadłospis</h1>
                    <div>
                        <div class="cafeteria-table">
                            <div class="corner-cell" style='font-size: 24px'>
                                <div class="corner-line"></div>
                                <span class="corner-text-top">Dzień</span>
                                <span class="corner-text-bottom">Danie</span>
                            </div>
                            <?php
                            $sql = "SELECT DISTINCT kiedy FROM jadlospis WHERE YEARWEEK(kiedy, 1) = YEARWEEK(CURDATE(), 1)ORDER BY kiedy;";
                            $result = $connection->query($sql)->fetch_all();
                            for ($i = 0; $i < count($result); $i++) {
                                echo "<span class='table_cell label'>" . weekDayFromDate($result[$i][0]) . "</span>";
                            }
                            ?>
                            <span class="table_cell label">II Śniadanie</span>
                            <?php
                            $sql = "SELECT opis FROM jadlospis WHERE YEARWEEK(kiedy, 1) = YEARWEEK(CURDATE(), 1) AND typ = 0 ORDER BY kiedy;";
                            $result = $connection->query($sql)->fetch_all();
                            for ($i = 0; $i < count($result); $i++) {
                                echo "<span class='table_cell'>" . $result[$i][0] . "</span>";
                            }
                            ?>
                            <span class="table_cell label">Obiad</span>
                            <?php
                            $sql = "SELECT opis FROM jadlospis WHERE YEARWEEK(kiedy, 1) = YEARWEEK(CURDATE(), 1) AND typ = 1 ORDER BY kiedy;";
                            $result = $connection->query($sql)->fetch_all();
                            for ($i = 0; $i < count($result); $i++) {
                                echo "<span class='table_cell'>" . $result[$i][0] . "</span>";
                            }
                            ?>

                            <span class="table_cell label">Podwieczorek</span>
                            <?php
                            $sql = "SELECT opis FROM jadlospis WHERE YEARWEEK(kiedy, 1) = YEARWEEK(CURDATE(), 1) AND typ = 2 ORDER BY kiedy;";
                            $result = $connection->query($sql)->fetch_all();
                            for ($i = 0; $i < count($result); $i++) {
                                echo "<span class='table_cell'>" . $result[$i][0] . "</span>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================= -->
        <!-- KOMUNIKATY -->
        <!-- ============================= -->
        <div class="main-panel bigContainers main-panel-news" id="main-news">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class='logo-font-small'>Komunikaty</h1>
                    <!-- TO SA NARAZIE DLA CALRGO PRZEDSZKOLA, TRZEBA ZROBIC TO ROZWIJANE DLA OGOLNYCH KOMUUNIKATOW I KONKRETNYCH GRUP-->
                    <?php
                    $sql = "SELECT tytul, tresc, data FROM komunikaty WHERE przynaleznosc = 0 ORDER BY data DESC;";
                    $result = $connection->query($sql)->fetch_all();
                    for ($i = 0; $i < count($result); $i++) {
                        echo "<div class='newsCards' style='animation-delay: " . $i * 0.2 . "s'>
                                    <div class='header'>
                                        <span class='title'>" . $result[$i][0] . "</span>
                                        <span class='date'>" . $result[$i][2] . "</span>
                                    </div>
                                    <div><span class='content'>" . $result[$i][1] . "</span></div>
                                </div>";
                    }
                    ?>
                </div>
            </div>
        </div>


        <!-- ============================= -->
        <!-- DZIECI -->
        <!-- ============================= -->
        <!--<aside>
        <ul id="listaDzieci">
            <?php
        $connection = mysqli_connect("localhost", "root", "", "przedszkole");
        $json = array();
        if (!$connection) {
            echo "Brak połączenia z bazą danych";
        } else {
            if ($result = $connection->query(sprintf("SELECT imie, nazwisko, pesel, adres, grupa FROM dzieci WHERE IDrodzica='%s'", mysqli_real_escape_string($connection, $user->id)))) {
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


</body>
</html>