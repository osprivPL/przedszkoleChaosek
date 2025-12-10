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

// --- FUNKCJE POMOCNICZE PHP ---

// Funkcja wyciągająca datę z PESEL
function dateFromPesel($pesel) {
    $rok = substr($pesel, 0, 2);
    $miesiac = (int)substr($pesel, 2, 2);
    $dzien = substr($pesel, 4, 2);
    $stulecie = '19';

    if ($miesiac >= 21 && $miesiac <= 32) {
        $stulecie = '20';
        $miesiac -= 20;
    }
    $pelnyRok = $stulecie . $rok;
    $miesiacStr = str_pad($miesiac, 2, '0', STR_PAD_LEFT);
    return "$pelnyRok-$miesiacStr-$dzien";
}

// Pobieramy dzieci raz na początku, aby użyć ich w nav i w main
$sqlChildren = "SELECT imie, nazwisko, pesel, adres, grupa, img, opinia FROM dzieci WHERE IDRodzica = " . $user->id . ";";
$resultChildren = $connection->query($sqlChildren)->fetch_all(MYSQLI_ASSOC);

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

    <script>
        function openChildPanel(pesel) {
            // Ukryj wszystkie główne panele
            const allPanels = document.querySelectorAll('.main-panel');
            allPanels.forEach(panel => {
                panel.style.display = 'none';
            });

            // Pokaż panel konkretnego dziecka
            const targetPanel = document.getElementById('panel-' + pesel);
            if(targetPanel) {
                targetPanel.style.display = 'flex';
                // Pokaż wewnętrzny kontener (zachowanie ze starego JS)
                const innerStyling = targetPanel.querySelector('.styling-panel');
                if(innerStyling) innerStyling.style.display = 'flex';
            }
        }
    </script>
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
        <div onclick="showSomething(2)" class="user">
            <div class='userLabel'><?php echo $user->imie . ' ' . $user->nazwisko; ?><br>Rodzic</div>
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

        <?php foreach ($resultChildren as $child): ?>
            <div class="nav_child nav_child_child nav_child_dziecko"
                 onclick="openChildPanel('<?php echo $child['pesel']; ?>')">
                <img src="./../assets/little-kid.png" alt="Dziecko">
                <span><?php echo $child['imie'] . " " . $child['nazwisko']; ?></span>
            </div>
        <?php endforeach; ?>
        <div class="nav_child" id="nav_child_szkola" onclick="showChildren(1)">
            <img src="./../assets/school.png" alt="">
            <span>o Szkole</span>
            <span class="nav_arrow">▽</span>
        </div>

        <div class="nav_child nav_child_child nav_child_oSzkole" onclick="showContainer(<?php echo $conteiner; $conteiner++; ?>)">
            <img src="./../assets/teacher.png" alt="">
            <span>Nauczyciele</span>
        </div>

        <div class="nav_child " onclick="showContainer(<?php echo $conteiner; $conteiner++; ?>)">
            <img src="./../assets/restaurant.png" alt="">
            <span>Stołówka</span>
        </div>
        <div class="nav_child" onclick="showContainer(<?php echo $conteiner; ?>)">
            <img src="./../assets/speaker.png" alt="">
            <span>Komunikaty</span>
        </div>
    </nav>

    <main id="main">
        <div class="main-panel bigContainers" id="main-main">
            <div class="styling-panel styling-panel-js">

                <div class="main-cell main-cell-plan">
                    <h1 class='logo-font-small'>
                        <span>Plan lekcji</span>
                    </h1>
                    <div class="buttonContainer">
                        <?php
                        $dzienTygodnia = date('N');

                        // Używamy pobranych wcześniej grup z tablicy $resultChildren, żeby nie pytać bazy drugi raz o to samo
                        // Ale musimy wyciągnąć unikalne grupy
                        $uniqueGroups = array_unique(array_column($resultChildren, 'grupa'));
                        $groups = array_values($uniqueGroups); // Reindex

                        for ($i = 0; $i < count($groups); $i++) {
                            echo '<button class="plan-lekcji-buttons '; if($i==0){echo "activeButton";} echo '" onclick="showPlan(' . $groups[$i] . ')">Grupa ' . $groups[$i] . '</button> ';
                        }
                        ?>
                    </div>
                    <?php
                    // generowanie planow
                    for ($i = 0; $i < count($groups); $i++) {
                        echo '<div id="plan' . $groups[$i] . '" class="plan-container ';
                        if ($i == 0) { echo "shownPlan"; }
                        echo '">';

                        $sql = "SELECT lekcje.nazwa, godzinylekcyjne.start_time, godzinylekcyjne.end_time FROM plan_lekcji JOIN lekcje ON plan_lekcji.lekcjaID = lekcje.id JOIN godzinylekcyjne ON plan_lekcji.godzinaLekcyjna = godzinylekcyjne.id WHERE plan_lekcji.grupaID = " . $groups[$i] . " AND plan_lekcji.day_of_week = " . $dzienTygodnia . " ORDER BY godzinylekcyjne.start_time;";

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

                <div class="main-cell main-cell-homework">
                    <h1 class='logo-font-small'><span>Prace domowe</span></h1>
                    <?php
                    // Tutaj też możemy użyć $groups zamiast kolejnego zapytania
                    if (count($groups) > 0) {
                        $condition = "WHERE (";
                        for ($i = 0; $i < count($groups); $i++) {
                            if ($i == count($groups) - 1) {
                                $condition .= 'grupa = ' . $groups[$i] . ') AND zrobione = 0';
                            } else {
                                $condition .= 'grupa = ' . $groups[$i] . ' OR ';
                            }
                        }
                        $sql = "SELECT tresc, grupa, data, id FROM pracedomowe " . $condition . " ORDER BY data;";
                        $result = $connection->query($sql)->fetch_all();

                        for ($i = 0; $i < count($result); $i++) {
                            echo '<div id="homework' . $result[$i][3] . '" class="homework-item" onclick="ukryjZadanie(' . $result[$i][3] . ')">';
                            echo '<h3>' . $result[$i][0] . "</h3><div><span>" . $result[$i][2] . "</span><br>";
                            echo '<span>Grupa ' . $result[$i][1] . "</span></div>";
                            echo '<div class="homework-pop-up"><span>Oznacz jako zrobione</span></div>';
                            echo '</div>';
                        }
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
                    echo "<div class='news-item'>
                            <div class='news-item-header'><span>" . $result[0][0] . "</span><span class='news-date'>" . $result[0][2] . "</span></div>
                            <div class='news-content'>" . $result[0][1] . "</div>
                        </div>";
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

                    if(count($result) > 1){
                        echo "<div class='buttonContainer'>";
                        for ($i = 1; $i < count($result)+1; $i++) {
                            echo "<button class='wychowawca-buttons ";if($i==1){echo "activeButton";} echo "' onclick='showWychowawca(".$i.")'>Grupa ".$i."</button>";
                        }
                        echo "</div>";
                    }

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

                    //                echo $condition;
                    $sqlSupervisor = "SELECT imie, nazwisko, opinia, zdjecie FROM uzytkownicy " . $condition;
                    $result = $connection->query($sqlSupervisor)->fetch_all();
                    for ($i = 0; $i < count($result); $i++) {
                        echo "<div class='wychowawca-container' style='display:"; if($i==0){echo "flex";}else{echo "none";} echo "'>";
                            echo "<div>";
                                echo "<div class='wychowawca-name'>"
                                        . $result[$i][0] .' ' . $result[$i][1];
                                echo "</div>";
                                echo "<div class='wychowawca-about'><div></div>";
                                    echo $result[$i][2];
                                echo "</div>";
                            echo "</div>";
                            echo "<div class='wychowawca-img-container' style='background-image: url(./../assets/staff/".$result[$i][3].")'></div>";
                        echo "</div>";
                    }
                    ?>
                </div>

                <!-- CELL JADŁOSPIS -->
                <div class="main-cell main-cell-jadlospis">
                    <h1 class='logo-font-small'>
                        <span>Jadłospis na dziś</span>
                    </h1>
                    <div>
                        <?php
                        $sql = "SELECT opis FROM jadlospis WHERE kiedy = CURDATE() ORDER BY typ;";
                        $result = $connection->query($sql)->fetch_all();
                        echo "<div class='meals-container'>";
                        if (count($result) == 0) {
                            echo "Dzień wolny od przedszkola";
                        } else {
                            echo "<div><span>II śniadanie:</span><span> " . substr($result[0][0],0,50) . "</span></div>";
                            echo "<div><span>Obiad:</span><span> " . substr($result[1][0],0,50) . "</span></div>";
                            echo "<div><span>Podwieczorek:</span><span> " . substr($result[2][0],0,50) . "</span></div>";
                        }
                        echo "</div>";
                        //                            printArr($result);
                        ?>
                    </div>
                </div>

            </div>
        </div>
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
                        echo '<fieldset class="teacherCards" style="animation-delay: '.$i*0.2 .'s">';
                        if ($result[$i][3] == 1) {
                            $typ = "Nauczyciel";
                        } else if ($result[$i][3] == 2) {
                            $typ = "Dyrektor";
                        }
                        echo '<legend>' . $typ . '</legend>';
                        echo '<div class="teacherInfo">';
                        echo '<h3>' . $result[$i][0] . " " . $result[$i][1] . '<span class="email">' .$result[$i][2] . '</span></h3>';
                        echo '<p class="opinia"> ' . $result[$i][4] . "</p>";
                        echo '</div>';
                        echo '<div>';
                        echo "<div class='imgContainer' style='background-image: url(./../assets/staff/".$result[$i][5].")'></div>";
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
                    $sql = "SELECT tytul, tresc, data,przynaleznosc FROM komunikaty WHERE przynaleznosc = 0 ORDER BY data DESC;";
                    $result = $connection->query($sql)->fetch_all();
                    for ($i = 0; $i < count($result); $i++) {
                        echo "<div class='newsCards' style='animation-delay: ".$i*0.2 ."s'>
                                    <div class='header'>
                                        <span class='title'>" . $result[$i][0] . "</span>
                                        <span class='date'>" . $result[$i][2] . "</span>
                                    </div>
                                    <div><span class='content'>" . $result[$i][1] . "</span>
                                    <span class='labelVisibleFor1' style='float: right; color: lightgray;'><br>";
                                        if ($result[$i][3] == 0) {
                                            echo "Wszyscy";
                                        } else {
                                            echo "Grupa " . $result[$i][3];
                                        }
                                    echo "</span></div>
                                    <div></div>
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