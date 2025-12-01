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
if ($user->typ != 0 || !$_SESSION['logged']) {
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
    <a href="../index.php" id="logo" class='logo'>
        <img src="./../assets/logo_tornado.svg" alt="logo">
        <span class='logo-font-small'>Przedszkole Chaosek</span>
    </a>
    <!--Tymon zrobił by to lepiej-->
    <div class="header-ui">
        <a href="./inbox.php"><img id="mail" src="./../assets/mail.png" alt="mail"></a>
        <div onclick="userPanel(1)" class="user">
            <div><?php echo $user->imie . ' ' . $user->nazwisko; ?><br>Rodzic</div>
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
        <div class="nav_child" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Panel główny</span>
        </div>

        <div class="nav_child" id="nav_child_dzieci" onclick="showChildren(0)">
            <img src="./../assets/playing.png" alt="">
            <span>Dziecko</span>
            <span class="nav_arrow">▽</span>
        </div>

        <script>
            <?php
            $json = array();
            $sql = "SELECT imie, nazwisko, pesel, adres, grupa, img FROM dzieci WHERE IDRodzica = " . $user->id . ";";
            $result = $connection->query($sql)->fetch_all();
            foreach ($result as $row) {
                $json[] = $row;
            }
            ?>
            let php = <?php echo json_encode($json); ?>;

            showDzieci(php);
            <?php
            //            przez to po usunięciu main-child1 nie działało NIC
            //            $conteiner = $conteiner + count($json) - 1;
            ?>
        </script>

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
        <!-- ============================= -->
        <!-- GŁÓWNA -->
        <!-- ============================= -->
        <div class="main-panel bigContainers" id="main-main">
            <div class="styling-panel styling-panel-js">

                <!-- CELL PLAN LEKCJI -->
                <div class="main-cell main-cell-plan">
                    <h1 class='logo-font-small'>
                        <span>Plan lekcji</span>
                    </h1>
                    <?php
                    //                            // Pobieramy numer dnia tygodnia (1 = Poniedziałek, ..., 5 = Piątek, 6,7 = Weekend)
                    $dzienTygodnia = date('N');
                    //
                    //                            // Zapytanie SQL
                    //                            $sqlPlan = "SELECT * FROM plan_lekcji  WHERE day_of_week = $dzienTygodnia AND grupaID = 1 ORDER BY start_time";
                    //                            $result = $connection->query($sql)->fetch_all();
                    //                            print_r($result);
                    // buttony do zmiany aktuwnego planu
                    $sql = "SELECT DISTINCT grupa FROM dzieci WHERE IDRodzica = " . $user->id . ";";
                    $result = $connection->query($sql)->fetch_all();
                    $groups = array();
                    for ($i = 0; $i < count($result); $i++) {
                        echo '<button onclick="showPlan(' . $result[$i][0] . ')">Gr. ' . $result[$i][0] . '</button> ';
                        $groups[] = $result[$i][0];
                    }


                    // generowanie planow
                    for ($i = 0; $i < count($groups); $i++) {
                        echo '<div id="plan"' . $groups[$i] . ' class="plan-container"';
                        if ($i != 0) {
                            echo ' style="display:none;"';
                        }
                        echo '>';
                        $sql = "SELECT lekcje.nazwa, plan_lekcji.start_time, plan_lekcji.end_time FROM plan_lekcji JOIN lekcje ON plan_lekcji.lekcjaID = lekcje.id WHERE grupaID = " . $groups[$i] . " AND day_of_week = " . $dzienTygodnia . " ORDER BY start_time;";
                        $result = $connection->query($sql)->fetch_all();
                        for ($j = 0; $j < count($result); $j++) {
                            echo '<div class="lesson-item">
                                    <span class="lesson-time">' . substr($result[$j][1], 0, 5) . ' - ' . substr($result[$j][2], 0, 5) . '</span>
                                    <span class="lesson-name">' . $result[$j][0] . '</span>
                                    </div>';
                        }
                        echo '</div>';
                    }
                    ?>

                </div>

                <!-- CELL PRACA DOMOWA -->
                <div class="main-cell main-cell-homework">
                    <h1 class='logo-font-small'><span>Prace domowe</span></h1>
                    <?php
                    $sqlGroups = "SELECT DISTINCT grupa FROM dzieci WHERE IDRodzica = " . $user->id . ";";
                    $result = $connection->query($sqlGroups)->fetch_all();
                    $output = [];
                    $condition = "WHERE (";
                    for ($i = 0; $i < count($result); $i++) {
                        $output[$i] = "Gr. " . $result[$i][0] . ": ";
                        if ($i == count($result) - 1) {
                            $condition = strval($condition . 'grupa = ' . $result[$i][0] . ') AND zrobione = 0');
                            break;
                        }
                        $condition = strval($condition . 'grupa = ' . $result[$i][0] . ' OR ');
                    }
                    $sql = "SELECT tresc, grupa, data, id FROM pracedomowe " . $condition . " ORDER BY data;";
                    $result = $connection->query($sql)->fetch_all();
                    //                        printArr($result);
                    for ($i = 0; $i < count($result); $i++) {
                        echo '<div id="homework' . $result[$i][3] . '">';
                        echo '<h3>' . $result[$i][0] . "</h3><span>" . $result[$i][2] . "</span>";
                        echo '<p>Grupa ' . $result[$i][1] . "</p>";
                        echo '<button onclick="ukryjZadanie(' . $result[$i][3] . ')">Oznacz jako ukończone</button>';
                        echo '</div>';
                    }
                    ?>
                    <script>
                        function ukryjZadanie(idRekordu) {
                            fetch('./../scripts/php/markHomeworkAsDone.php', {
                                method: 'POST',
                                headers: {'Content-Type': 'application/json'},
                                body: JSON.stringify({id: idRekordu})
                            })
                                .then(response => response.text())
                                .then(data => {
                                    // Sprawdzamy, czy PHP zwróciło dokładnie "OK"
                                    if (data.trim() === 'OK') {
                                        const element = document.getElementById('homework' + idRekordu);
                                        if (element) {
                                            element.style.transition = "opacity 0.5s";
                                            element.style.opacity = "0";

                                            setTimeout(() => element.remove(), 500);
                                        }
                                    } else {
                                        console.error('Błąd serwera:', data);
                                        alert('Wystąpił błąd podczas zapisu.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Błąd sieci:', error);
                                });
                        }
                    </script>
                </div>
                <!-- CELL OSTATNI KOMUNIKAT -->
                <div class="main-cell main-cell-news">
                    <h1 class='logo-font-small'>
                        <span>Ostatni Komunikat</span>
                    </h1>
                    <?php
                    $sql = "SELECT tytul, tresc, data FROM komunikaty WHERE przynaleznosc = 0 ORDER BY data DESC LIMIT 1;";
                    $result = $connection->query($sql)->fetch_all();
                    echo "<h3>" . $result[0][0] . "</h3>
                                    <span class='news-date'>" . $result[0][2] . "</span>
                                    <p>" . $result[0][1] . "</p>";
                    ?>

                </div>
                <!-- CELL WYCHOWAWCY -->
                <div class="main-cell main-cell-wychowawcy">
                    <?php
                    $sqlGroups = "SELECT DISTINCT grupa FROM dzieci WHERE IDRodzica = " . $user->id . ";";
                    $result = $connection->query($sqlGroups)->fetch_all();
                    $output = [];
                    $condition = "WHERE ";
                    for ($i = 0; $i < count($result); $i++) {
                        $output[$i] = "Gr. " . $result[$i][0] . ": ";
                        if ($i == count($result) - 1) {
                            $condition = strval($condition . 'id = ' . $result[$i][0] . ';');
                            break;
                        }
                        $condition = strval($condition . 'id = ' . $result[$i][0] . ' OR ');
                    }
                    echo "<h1 class='logo-font-small'><span>";
                    if (count($result) > 1) {
                        echo 'Wychowawcy';
                    } else {
                        echo 'Wychowawca';
                    }
                    echo "</span></h1>";

                    $sqlWychowawcy = "SELECT Wychowawca FROM grupy " . $condition;
                    $result = $connection->query($sqlWychowawcy)->fetch_all();

                    $condition = "WHERE ";
                    for ($i = 0; $i < count($result); $i++) {
                        if ($i == count($result) - 1) {
                            $condition = strval($condition . 'id = ' . $result[$i][0] . ';');
                            break;
                        }
                        $condition = strval($condition . 'id = ' . $result[$i][0] . ' OR ');
                    }

                    //                        echo $condition;
                    $sqlSupervisor = "SELECT imie, nazwisko FROM uzytkownicy " . $condition;
                    $result = $connection->query($sqlSupervisor)->fetch_all();
                    for ($i = 0; $i < count($result); $i++) {
                        $output[$i] .= $result[$i][0] . " " . $result[$i][1];
                    }
                    for ($i = 0; $i < count($result); $i++) {
                        echo "<p>" . $output[$i] . "</p>";
                    }
                    ?>
                </div>

                <!-- CELL DZIECI -->
                <div class="main-cell main-cell-dzieci">
                    <h1 class='logo-font-small'>
                        <span>Dzieci</span>
                    </h1><br>
                    <?php
                    printArr($json);
                    ?></div>
                <!-- CELL JADŁOSPIS -->
                <div class="main-cell main-cell-jadlospis">
                    <h1 class='logo-font-small'>
                        <span>Jadłospis na dziś</span>
                    </h1>
                    <div>
                        <?php
                        $sql = "SELECT opis FROM jadlospis WHERE kiedy = CURDATE() ORDER BY typ;";
                        $result = $connection->query($sql)->fetch_all();
                        if (count($result) == 0) {
                            echo "Dzień wolny od przedszkola";
                        } else {
                            echo "II śniadanie: " . $result[0][0] . "<br>";
                            echo "Obiad: " . $result[1][0] . "<br>";
                            echo "Podwieczorek: " . $result[2][0] . "<br>";
                        }
                        //                            printArr($result);
                        ?>
                    </div>
                </div>

            </div>
        </div>
        <!-- ============================= -->
        <!-- NAUCZYCIELE -->
        <!-- ============================= -->
        <div class="main-panel bigContainers" id="main-teachers">
            <div class="styling-panel">
                <?php
                $sql = "SELECT imie, nazwisko,login,typ FROM uzytkownicy WHERE typ = 1 OR typ = 2";
                $result = $connection->query($sql)->fetch_all();
                //                    print_r($result);
                for ($i = 0; $i < count($result); $i++) {
                    echo '<div>';
                    echo '<h3>' . $result[$i][0] . " " . $result[$i][1] . '</h3>';
                    echo '<p>Email: ' . $result[$i][2] . "</p>";
                    $typ = "";

                    if ($result[$i][3] == 1) {
                        $typ = "Nauczyciel";
                    } else if ($result[$i][3] == 2) {
                        $typ = "Dyrektor";
                    }
                    echo '<p>' . $typ . '</p>';

                    echo '</div>';
                }

                ?>

            </div>
        </div>
        <!-- ============================= -->
        <!-- CAFETERIA -->
        <!-- ============================= -->
        <div class="main-panel bigContainers" id="main-cafeteria">
            <div class="styling-panel">
                <div class="cafeteria-table">
                    <span class="nzw">Nazwa</span>
                    <?php
                    $sql = "SELECT DISTINCT kiedy FROM jadlospis WHERE YEARWEEK(kiedy, 1) = YEARWEEK(CURDATE(), 1)ORDER BY kiedy;";
                    $result = $connection->query($sql)->fetch_all();
                    for ($i = 0; $i < count($result); $i++) {
                        echo "<span class='table_cell'>" . weekDayFromDate($result[$i][0]) . "</span>";
                    }
                    ?>
                </div>
                <div class="cafeteria-table">
                    <span class="nzw">II Śniadanie</span>
                    <?php
                    $sql = "SELECT opis FROM jadlospis WHERE YEARWEEK(kiedy, 1) = YEARWEEK(CURDATE(), 1) AND typ = 0 ORDER BY kiedy;";
                    $result = $connection->query($sql)->fetch_all();
                    for ($i = 0; $i < count($result); $i++) {
                        echo "<span class='table_cell'>" . $result[$i][0] . "</span>";
                    }
                    ?>
                </div>
                <div class="cafeteria-table">
                    <span class="nzw">Obiad</span>
                    <?php
                    $sql = "SELECT opis FROM jadlospis WHERE YEARWEEK(kiedy, 1) = YEARWEEK(CURDATE(), 1) AND typ = 1 ORDER BY kiedy;";
                    $result = $connection->query($sql)->fetch_all();
                    for ($i = 0; $i < count($result); $i++) {
                        echo "<span class='table_cell'>" . $result[$i][0] . "</span>";
                    }
                    ?>
                </div>
                <div class="cafeteria-table">
                    <span class="nzw">Podwieczorek</span>
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

        <!-- ============================= -->
        <!-- KOMUNIKATY -->
        <!-- ============================= -->
        <div class="main-panel bigContainers" id="main-news">
            <div class="styling-panel">
                <!-- TO SA NARAZIE DLA CALRGO PRZEDSZKOLA, TRZEBA ZROBIC TO ROZWIJANE DLA OGOLNYCH KOMUUNIKATOW I KONKRETNYCH GRUP-->
                <?php
                $sql = "SELECT tytul, tresc, data FROM komunikaty WHERE przynaleznosc = 0 ORDER BY data DESC;";
                $result = $connection->query($sql)->fetch_all();
                for ($i = 0; $i < count($result); $i++) {
                    echo "<div class='news-item'>
                                <h3>" . $result[$i][0] . "</h3>
                                <span class='news-date'>" . $result[$i][2] . "</span>
                                <p>" . $result[$i][1] . "</p>
                              </div>";
                }
                ?>
            </div>
        </div>


        <!-- ============================= -->
        <!-- DZIECI? -->
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
        <!--<script src="./../scripts/js/mainMainLayout.js"></script>-->
    </main>


</body>
</html>