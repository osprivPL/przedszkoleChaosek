<!DOCTYPE html>
<?php
require_once "./../scripts/php/printArr.php";
require_once "./../scripts/php/weekDayFromDate.php";

require_once __DIR__ . '/../models/User.php';

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
if ($user->typ[1] != 1 || !$_SESSION['logged']) {
    header('Location: ./../index.php');
    die();
}

$conteiner = 1;
$groups = [];

$connection = mysqli_connect("localhost", "root", "", "przedszkole");


$dniTygodniaPlan = [
        1 => 'Poniedziałek',
        2 => 'Wtorek',
        3 => 'Środa',
        4 => 'Czwartek',
        5 => 'Piątek'
];

$godzinyList = [];
$sqlG = "SELECT id, start_time, end_time FROM godzinylekcyjne ORDER BY start_time ASC";
$resG = $connection->query($sqlG);
if ($resG) {
    while ($row = $resG->fetch_assoc()) {
        $godzinyList[] = $row;
    }
}

$lekcjeDict = [];
$sqlL = "SELECT id, nazwa FROM lekcje";
$resL = $connection->query($sqlL);
if ($resL) {
    while ($row = $resL->fetch_assoc()) {
        $lekcjeDict[$row['id']] = $row['nazwa'];
    }
}
if (empty($lekcjeDict)) {
    $lekcjeDict = [
            1 => 'Matematyka', 2 => 'J. Polski', 3 => 'Angielski',
            4 => 'WF', 5 => 'Plastyka', 6 => 'Muzyka'
    ];
}

$matrixPlan = [];
$sqlP = "SELECT grupaID AS id_grupy, day_of_week AS dzien_tygodnia, godzinaLekcyjna AS id_godziny, lekcjaID AS id_przedmiotu FROM plan_lekcji";
$resP = $connection->query($sqlP);
if ($resP) {
    while ($row = $resP->fetch_assoc()) {
        $matrixPlan[$row['id_grupy']][$row['dzien_tygodnia']][$row['id_godziny']] = $row['id_przedmiotu'];
    }
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
    <link rel="stylesheet" href="./../styles/teacher.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Momo+Trust+Display&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="./../assets/logo_tornado.svg">

    <title>Przedszkole Chaosek - Panel nauczyciela</title>
    <script src="./../scripts/js/panels.js"></script>
    <script src="./../scripts/js/childrens.js"></script>
    <script src="./../scripts/js/showUserPanel.js"></script>

    <script>
        function showGroupPlan(groupNum) {
            // Ukryj wszystkie kontenery planu
            const containers = document.getElementsByClassName('group-plan-container');
            for (let i = 0; i < containers.length; i++) {
                containers[i].style.display = 'none';
            }
            // Pokaż wybrany
            const selected = document.getElementById('group-plan-container-' + groupNum);
            if (selected) selected.style.display = 'block';

            // Aktualizuj klasy przycisków
            const buttons = document.getElementsByClassName('plan-group-btn');
            for (let i = 0; i < buttons.length; i++) {
                buttons[i].classList.remove('active');
            }
            const activeBtn = document.getElementById('btn-group-' + groupNum);
            if (activeBtn) activeBtn.classList.add('active');
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
    <div class="header-ui">
        <div onclick="showSomething(2)" class="user">
            <div class='userLabel'><?php echo $user->imie . ' ' . $user->nazwisko; ?><br>Nauczyciel(ka)</div>
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
        <div class="nav_child" id="nav_child_dzieci" onclick="showChildren(4)">
            <img src="./../assets/playing.png" alt="">
            <span>Grupy</span>
            <span class="nav_arrow">▽</span>
        </div>

        <?php
        // Tutaj pobierane są grupy nauczyciela
        $sql = "SELECT nazwa, id FROM grupy WHERE Wychowawca = " . $user->id . ";";
        $resultGroups = $connection->query($sql)->fetch_all();

        // Zbieramy ID grup do tablicy $groups, żeby użyć ich później w Planie Lekcji
        for ($i = 0; $i < count($resultGroups); $i++) {
            echo '<div class="nav_child nav_child_child nav_child_group" onclick="showContainer(' . $conteiner . ')">
                        <img src="./../assets/group.png" alt="">
                        <span>Grupa ' . $resultGroups[$i][1] . ' - ' . $resultGroups[$i][0] . '</span>
                    </div>';
            $groups[] = $resultGroups[$i][1];
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
        <div class="nav_child" onclick="showChildren(3)">
            <img src="./../assets/speaker.png" alt="">
            <span>Komunikaty</span>
            <span class="nav_arrow">▽</span>
        </div>
        <div class="nav_child nav_child_child nav_child_annoucement" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/speaker_add.png" alt="">
            <span>Dodaj komunikat</span>
        </div>
        <div class="nav_child nav_child_child nav_child_annoucement" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/speaker_gear.png" alt="">
            <span>Wyświetl komunikaty</span>
        </div>
        <div class="nav_child nav_child_child nav_child_annoucement" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/speaker_gear.png" alt="">
            <span>Zarządzaj komunikatami</span>
        </div>
        <div class="nav_child" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/lesson_plan.png" alt="">
            <span>Plan lekcji</span>
        </div>
    </nav>
    <script>
        const nav = document.getElementById('somethingBeingShown1');
        nav.addEventListener('mouseleave', () => {
            nav.classList.remove('visible');
        });
    </script>
    <main id="main">
        <div class="main-panel bigContainers main-panel-witaj">
            <span class='logo-font-small'>Witaj w panelu nauczyciela</span>
            <?php
            if (isset($_SESSION['powodzenie'])) {
                echo "<div class='success-message'>" . $_SESSION['powodzenie'] . "</div>";
                unset($_SESSION['powodzenie']);
            }
            ?>
            <?php // print_r($_SESSION); ?>
        </div>
        <?php
        for ($i = 0; $i < count($resultGroups); $i++) {
            echo '<div class="main-panel bigContainers main-panel-groups" id="main-groups">';
            echo '<div class="styling-panel">';
            echo "<div class='formContainer'>";
            echo '<hr>';
            echo '<h1 class="logo-font-small"><span>Grupa ' . $resultGroups[$i][1] . "</span></h1>";
            echo '<div class="groupInfo">';
            $sql = "SELECT grupy.nazwa, uzytkownicy.imie, uzytkownicy.nazwisko FROM grupy JOIN uzytkownicy ON grupy.Wychowawca = uzytkownicy.ID WHERE grupy.id =".$resultGroups[$i][1].";";
            $result = $connection->query($sql)->fetch_assoc();
            echo "<div>Nazwa grupy: " . $result['nazwa'] . "</div>";
            echo "<div>Wychowawca: " . $result['imie'] . " " . $result['nazwisko'] . "</div>";
            echo '</div>';
            echo '<div class="groupMembers">';
            echo '<h2>Lista dzieci w grupie:</h2>';
            echo '<div class="table" >';
            echo '<div class="table-header">
                                    <div>Imię</div>
                                    <div>Nazwisko</div>
                                    <div>PESEL</div>
                                    <div>Adres</div>
                                    <div>Rodzic</div>
                                </div>';
            $sql = "SELECT dzieci.imie, dzieci.nazwisko, dzieci.pesel, dzieci.adres, uzytkownicy.imie, uzytkownicy.nazwisko, dzieci.ID, dzieci.grupa, dzieci.opinia FROM dzieci JOIN uzytkownicy ON dzieci.IDRodzica = uzytkownicy.ID WHERE dzieci.grupa =". $resultGroups[$i][1]." ORDER BY dzieci.nazwisko;";
            $result = $connection->query($sql)->fetch_all();
            for ($j = 0; $j < count($result); $j++) {
                $arr = implode(";", $result[$j]);
                echo "<div class='grid-row' id='dziecko" . $result[$j][6] . "'>";
                echo "<div class='grid-cell'>" . $result[$j][0] . "</div>";
                echo "<div class='grid-cell'>" . $result[$j][1] . "</div>";
                echo "<div class='grid-cell'>" . $result[$j][2] . "</div>";
                echo "<div class='grid-cell'>" . $result[$j][3] . "</div>";
                echo "<div class='grid-cell'>" . $result[$j][4] . " " . $result[$j][5] . "</div>";
                echo "</div>";
            }

            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>

        <div class="main-panel bigContainers main-panel-teachers" id="main-teachers">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class='logo-font-small'>Nauczyciele</h1>
                    <?php
                    $sql = "SELECT imie, nazwisko, login ,typ, opinia, zdjecie, nauczyciel, dyrektor FROM uzytkownicy INNER JOIN uprawnienia ON typ = uprawnienia.ID WHERE nauczyciel = 1 OR dyrektor = 1 ORDER BY typ DESC";
                    $result = $connection->query($sql)->fetch_all();
                    //                    print_r($result);
                    for ($i = 0; $i < count($result); $i++) {
                        echo '<fieldset class="teacherCards" style="animation-delay: '.$i*0.2 .'s">';
                        if ($result[$i][6] == 1) {
                            $typ = "Nauczyciel";
                        } if ($result[$i][7] == 1) {
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

        <div class="main-panel bigContainers main-panel-add-komunikaty" id="addAnnoucement">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class="logo-font-small"><span>Dodaj komunikat</span></h1>
                    <form method="post" action="../scripts/php/addAnnoucement.php" id="frmAddKomunikat">
                        <div class="article_header">
                            <input type="text" id="komunikatHeader" name="komunikatHeader">
                        </div>
                        <textarea id="komunikatContent" name="komunikatContent" rows="10" cols="50"></textarea><br><br>
                        <div class="details">
                            <div class="choose-visibility">
                                <label for="komunikatGrupa">Wybierz widoczność</label>
                                <select id="komunikatGrupa" name="komunikatGrupa" class='submitButton'>
                                    <option value="0">Wszyscy</option>
                                    <?php
                                    for ($i = 0; $i < count($groups); $i++) {
                                        echo '<option value="' . $groups[$i] . '">Grupa ' . $groups[$i] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div><input type="submit" value="Dodaj komunikat" class='submitButton'></div>
                        </div>
                    </form>
                    <script>
                        document.getElementById('frmAddKomunikat').addEventListener('submit', (e) => {
                            e.preventDefault();
                            let form = e.target;
                            let error = false;
                            let header = document.getElementById('komunikatHeader');
                            let content = document.getElementById('komunikatContent');
                            if (header.value.length === 0) {
                                header.classList.add('error');
                                error = true;
                            } else {
                                header.classList.remove('error');
                            }
                            if (content.value.length === 0) {
                                content.classList.add('error');
                                error = true;
                            } else {
                                content.classList.remove('error');
                            }
                            if (error) return;
                            form.submit();
                        });
                    </script>
                </div>
            </div>
        </div>


        <div class="main-panel bigContainers main-panel-komunikaty" id="main-news">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class='logo-font-small'>Komunikaty</h1>
                    <?php
                    $condition = "WHERE przynaleznosc = 0 ";
                    for ($i = 0; $i < count($groups); $i++) {
                        $condition .= "OR przynaleznosc = " . $groups[$i] . " ";
                    }
                    $sql = "SELECT tytul, tresc, data, przynaleznosc FROM komunikaty " . $condition . "ORDER BY data DESC;";
                    $result = $connection->query($sql)->fetch_all();
                    for ($i = 0; $i < count($result); $i++) {
                        echo "<div class='newsCards' style='animation-delay: " . $i * 0.2 . "s'>
                                    <div class='header'>
                                        <span class='title'>" . $result[$i][0] . "</span>
                                        <span class='date'>" . $result[$i][2] . "</span>
                                    </div>
                                    <div><span class='content'>" . $result[$i][1] . "</span></div>
                                    <div><span class='date' style='float:right'>";
                        if ($result[$i][3] == 0) {
                            echo "Wszyscy";
                        } else {
                            echo "Grupa " . $result[$i][3];
                        }
                        echo "</span></div>
                                </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="main-panel bigContainers main-panel-komunikaty" id="annoucementManager">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class="logo-font-small"><span>Zarządzaj komunikatami</span></h1>
                    <?php
                    $sql = "SELECT tytul, tresc, data, przynaleznosc, id FROM komunikaty WHERE autor = " . $user->id . " ORDER BY data DESC";
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
                                    <div style='display: flex; justify-content: space-between'>
                                    <div style='display: flex; align-items: flex-end'>
                                    <button class='delete_article submitButton' onclick='edytujKomunikat(" . $result[$i][4] . ")'>Edytuj komunikat</button>
                                    <button class='delete_article submitButton' onclick='ukryjKomunikat(" . $result[$i][4] . ")'>Usuń komunikat</button></div>
                                    <span class='labelVisibleFor1' style='color: lightgray;'><br>";
                                        if ($result[$i][3] == 0) {
                                            echo "Wszyscy";
                                        } else {
                                            echo "Grupa " . $result[$i][3];
                                        }
                                    echo "</span>
                                    </div>
                                    </div>
                                    <div></div>
                                </div>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="main-panel bigContainers main-panel-plan">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class="logo-font-small">
                        <span>Plan lekcji</span>
                    </h1>

                    <div id="planLekcjiContainer">

                        <?php
                        if (empty($groups)) {
                            echo "<div style='text-align:center; padding: 20px;'>Brak przypisanych grup.</div>";
                        }

                        foreach ($groups as $index => $groupID):
                            ?>
                            <div id="group-plan-container-<?php echo $groupID; ?>"
                                 class="group-plan-container"
                                 style="<?php echo ($index === 0) ? 'display: block;' : 'display: none;'; ?>">

                                <div class="plan-grid-container">

                                    <div class="corner-cell">
                                        <div class="corner-line"></div>
                                        <span class="corner-text-top">Dzień</span>
                                        <span class="corner-text-bottom">Godz.</span>
                                    </div>

                                    <?php foreach ($dniTygodniaPlan as $dayNum => $dayName): ?>
                                        <div class="plan-header">
                                            <?php echo $dayName; ?>
                                        </div>
                                    <?php endforeach; ?>

                                    <?php foreach ($godzinyList as $godzina): ?>

                                        <div class="time-cell">
                                            <span><?php echo substr($godzina['start_time'], 0, 5); ?></span>
                                            <span style="font-size:0.8em; opacity:0.7;">-</span>
                                            <span><?php echo substr($godzina['end_time'], 0, 5); ?></span>
                                        </div>

                                        <?php foreach ($dniTygodniaPlan as $dayNum => $dayName): ?>
                                            <div class="plan-cell">
                                                <?php
                                                $selectedLessonID = $matrixPlan[$groupID][$dayNum][$godzina['id']] ?? 0;
                                                $lessonName = $lekcjeDict[$selectedLessonID] ?? '';

                                                if ($lessonName !== '') {
                                                    echo '<span class="lesson-name">' . htmlspecialchars($lessonName) . '</span>';
                                                } else {
                                                    echo '<span class="lesson-empty" style="color: #ccc;">-</span>';
                                                }
                                                ?>
                                            </div>
                                        <?php endforeach; ?>

                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="button-container">
                            <div class='group-switcher'>
                                <?php
                                foreach ($groups as $index => $groupID):
                                    ?>
                                    <button type="button"
                                            id="btn-group-<?php echo $groupID; ?>"
                                            class="plan-group-btn submitButton <?php echo ($index === 0) ? 'active' : ''; ?>"
                                            onclick="showGroupPlan(<?php echo $groupID; ?>)"
                                            style="min-width: 100px;">
                                        Grupa <?php echo $groupID; ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-panel bigContainers main-panel-add main-panel-add-komunikaty" id="editAnnoucements">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class="logo-font-small"><span>Edytuj komunikat</span></h1>
                    <form method="post" action="./../scripts/php/editAnnoucement.php" id="frmEditKomunikat">
                        <input type="hidden" name="editKomunikatIdHiddenInput" id="editKomunikatIdHiddenInput">
                        <div class="article_header">
                            <input type="text" id="editKomunikatHeader" name="editKomunikatHeader">
                        </div>
                        <textarea id="editKomunikatContent" name="editKomunikatContent" rows="10" cols="50"></textarea><br><br>
                        <div class="details">
                            <div class="choose-visibility">
                                <label for="editKomunikatGrupa">Wybierz widoczność</label>
                                <select id="editKomunikatGrupa" name="editKomunikatGrupa" class='submitButton'>
                                    <option value="0">Wszyscy</option>
                                    <option value="1">Grupa 1</option>
                                    <option value="2">Grupa 2</option>
                                    <option value="3">Grupa 3</option>
                                    <option value="4">Grupa 4</option>
                                </select>
                            </div>
                            <div><input type="submit" value="Zapisz" class='submitButton'></div>
                        </div>
                    </form>
                    <script>
                        document.getElementById('frmEditKomunikat').addEventListener('submit', (e) => {
                            e.preventDefault();
                            let form = e.target;
                            let error = false;
                            let header = document.getElementById('editKomunikatHeader');
                            let content = document.getElementById('editKomunikatContent');
                            if (header.value.length === 0) {
                                header.classList.add('error');
                                error = true;
                            } else {
                                header.classList.remove('error');
                            }
                            if (content.value.length === 0) {
                                content.classList.add('error');
                                error = true;
                            } else {
                                content.classList.remove('error');
                            }
                            if (error) return;
                            form.submit();
                        });
                    </script>
                </div>
            </div>
        </div>
    </main>
</body>
</html>