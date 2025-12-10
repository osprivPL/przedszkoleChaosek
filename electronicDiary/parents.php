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

function dateFromPesel($pesel)
{
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

// Pobieramy dzieci
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

    <link rel="stylesheet" href="./../styles/style.css">
    <link rel="stylesheet" href="./../styles/panels.css">
    <link rel="stylesheet" href="./../styles/parents.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Momo+Trust+Display&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="./../assets/logo_tornado.svg">

    <title>Przedszkole Chaosek - Panel rodzica</title>
    <script src="./../scripts/js/panels.js"></script>
    <script src="./../scripts/js/showUserPanel.js"></script>

    <script>
        function openChildPanel(pesel) {
            // Ukryj wszystkie główne panele (te z klasą main-panel)
            const allPanels = document.querySelectorAll('.main-panel');
            allPanels.forEach(panel => {
                panel.style.display = 'none';
            });

            // Pokaż panel konkretnego dziecka
            const targetPanel = document.getElementById('panel-' + pesel);
            if (targetPanel) {
                targetPanel.style.display = 'flex';

                // Upewnij się, że wewnętrzny styling-panel też jest widoczny (dla pewności)
                const innerStyling = targetPanel.querySelector('.styling-panel');
                if (innerStyling) innerStyling.style.display = 'flex';
            } else {
                console.error("Nie znaleziono panelu o ID: panel-" + pesel);
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
        <span class='logo-font-small'><span class='labelPrzedszkole'>Przedszkole</span> Chaosek</span>
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
    <script>
        const nav = document.getElementById('somethingBeingShown1');
        nav.addEventListener('mouseleave', () => {
        nav.classList.remove('visible');
        });
    </script>
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

                        $uniqueGroups = array_unique(array_column($resultChildren, 'grupa'));
                        $groups = array_values($uniqueGroups);

                        for ($i = 0; $i < count($groups); $i++) {
                            echo '<button class="plan-lekcji-buttons ';
                            if ($i == 0) {
                                echo "activeButton";
                            }
                            echo '" onclick="showPlan(' . $groups[$i] . ')">Grupa ' . $groups[$i] . '</button> ';
                        }
                        ?>
                    </div>
                    <?php
                    // generowanie planow
                    for ($i = 0; $i < count($groups); $i++) {
                        echo '<div id="plan' . $groups[$i] . '" class="plan-container ';
                        if ($i == 0) {
                            echo "shownPlan";
                        }
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

                <div class="main-cell main-cell-news">
                    <h1 class='logo-font-small'>
                        <span>Ostatni Komunikat</span>
                    </h1>
                    <?php
                    $sql = "SELECT tytul, tresc, data FROM komunikaty WHERE przynaleznosc = 0 ORDER BY data DESC LIMIT 1;";
                    $result = $connection->query($sql)->fetch_all();
                    if (isset($result[0])) {
                        echo "<div class='news-item'>
                                <div class='news-item-header'><span>" . $result[0][0] . "</span><span class='news-date'>" . $result[0][2] . "</span></div>
                                <div class='news-content'>" . $result[0][1] . "</div>
                            </div>";
                    }
                    ?>
                </div>

                <div class="main-cell main-cell-wychowawcy">
                    <?php
                    // Budowanie zapytania dla wychowawców na podstawie grup dziecka
                    $condition = "WHERE ";
                    if (count($groups) > 0) {
                        for ($i = 0; $i < count($groups); $i++) {
                            if ($i == count($groups) - 1) {
                                $condition .= 'id = ' . $groups[$i] . ';';
                            } else {
                                $condition .= 'id = ' . $groups[$i] . ' OR ';
                            }
                        }

                        echo "<h1 class='logo-font-small'><span>";
                        if (count($groups) > 1) {
                            echo 'Wychowawcy';
                        } else {
                            echo 'Wychowawca';
                        }
                        echo "</span></h1>";

                        if (count($groups) > 1) {
                            echo "<div class='buttonContainer'>";
                            for ($i = 0; $i < count($groups); $i++) {
                                echo "<button class='wychowawca-buttons ";
                                if ($i == 0) {
                                    echo "activeButton";
                                }
                                echo "' onclick='showWychowawca(" . ($i + 1) . ")'>Grupa " . $groups[$i] . "</button>";
                            }
                            echo "</div>";
                        }

                        $sqlWychowawcy = "SELECT Wychowawca FROM grupy " . $condition;
                        $resultWych = $connection->query($sqlWychowawcy)->fetch_all();

                        // Pobieranie danych wychowawców
                        $conditionUser = "WHERE ";
                        if (count($resultWych) > 0) {
                            for ($i = 0; $i < count($resultWych); $i++) {
                                if ($i == count($resultWych) - 1) {
                                    $conditionUser .= 'id = ' . $resultWych[$i][0] . ';';
                                } else {
                                    $conditionUser .= 'id = ' . $resultWych[$i][0] . ' OR ';
                                }
                            }

                            $sqlSupervisor = "SELECT imie, nazwisko, opinia, zdjecie FROM uzytkownicy " . $conditionUser;
                            $resultSup = $connection->query($sqlSupervisor)->fetch_all();

                            for ($i = 0; $i < count($resultSup); $i++) {
                                echo "<div class='wychowawca-container' style='display:";
                                if ($i == 0) {
                                    echo "flex";
                                } else {
                                    echo "none";
                                }
                                echo "'>";
                                echo "<div>";
                                echo "<div class='wychowawca-name'>" . $resultSup[$i][0] . ' ' . $resultSup[$i][1] . "</div>";
                                echo "<div class='wychowawca-about'><div></div>" . $resultSup[$i][2] . "</div>";
                                echo "</div>";
                                echo "<div class='wychowawca-img-container' style='background-image: url(./../assets/staff/" . $resultSup[$i][3] . ")'></div>";
                                echo "</div>";
                            }
                        }
                    }
                    ?>
                </div>

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
                            echo "<div><span>II śniadanie:</span><span> " . substr($result[0][0], 0, 50) . "</span></div>";
                            echo "<div><span>Obiad:</span><span> " . substr($result[1][0], 0, 50) . "</span></div>";
                            echo "<div><span>Podwieczorek:</span><span> " . substr($result[2][0], 0, 50) . "</span></div>";
                        }
                        echo "</div>";
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
                        echo "<div class='imgContainer imgContainer2' style='background-image: url(./../assets/staff/".$result[$i][5].");display: none'></div>";
                        echo '<p class="email emailPlacedBelow" style="display: none">'.$result[$i][2].'</p>';
                        echo '<p class="opinia"> ' . $result[$i][4] . "</p>";
                        echo '</div>';
                        echo '<div>';
                        echo "<div class='imgContainer imgContainer1' style='background-image: url(./../assets/staff/".$result[$i][5].")'></div>";
                        echo '</div>';
                        $typ = "";



                        echo '</fieldset>';
                    }

                    ?>
                </div>
            </div>
        </div>


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
                                        <span class='date'>" . $result[$i][2] . "
                                        <br><span class='labelVisibleFor2' style='display:none'>";
                                        if ($result[$i][3] == 0) {
                                            echo "Wszyscy";
                                        } else {
                                            echo "Grupa " . $result[$i][3];
                                        }
                                    echo "</span></span>
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

        <?php foreach ($resultChildren as $child): ?>
            <div id="panel-<?php echo $child['pesel']; ?>" class="main-panel main-child bigContainers"
                 style="display: none;">
                <div id="<?php echo $child['pesel']; ?>" class="childCard styling-panel" style="display: flex;">
                    <div class="child-info">
                        <h3><?php echo $child['imie'] . " " . $child['nazwisko']; ?></h3>
                        <span>Data urodzenia: <?php echo dateFromPesel($child['pesel']); ?></span>
                        <span>Grupa: <?php echo $child['grupa']; ?></span>
                        <span class="info-opinia">
                            <h4>Opinia: <?php echo $child['opinia']; ?></h4>
                            <br>
                        </span>
                    </div>
                    <img src="./../assets/childrenImages/<?php echo $child['img']; ?>" alt="Zdjęcie dziecka">
                </div>
            </div>
        <?php endforeach; ?>

    </main>
</body>
</html>