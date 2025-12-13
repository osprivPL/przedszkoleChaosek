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
if ($user->typ[2] != 1 || !$_SESSION['logged']) {
    header('Location: ./../index.php');
    die();
}

$conteiner = 1;
$connection = mysqli_connect("localhost", "root", "", "przedszkole");

// --- OBSŁUGA JADŁOSPISU ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['meals'])) {
    $stmt = $connection->prepare("INSERT INTO jadlospis (kiedy, typ, opis) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE opis = VALUES(opis)");

    foreach ($_POST['meals'] as $date => $types) {
        foreach ($types as $type => $desc) {
            $desc = trim($desc);
            $stmt->bind_param("sis", $date, $type, $desc);
            $stmt->execute();
        }
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

// --- NOWA OBSŁUGA PLANU LEKCJI ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_plan_matrix'])) {
    // 1. Wyczyść tabelę plan_lekcji (najprostsza metoda przy pełnym nadpisywaniu macierzy)
    // Uwaga: Jeśli chcesz zachować ID, musiałbyś robić UPDATE, ale przy macierzy prościej jest TRUNCATE lub DELETE ALL
    $connection->query("TRUNCATE TABLE plan_lekcji");

    // Przygotuj zapytanie INSERT
    $stmt = $connection->prepare("INSERT INTO plan_lekcji (grupaID, day_of_week, godzinaLekcyjna, lekcjaID) VALUES (?, ?, ?, ?)");

    // 2. Iteruj przez przesłane dane: plan[grupa][dzien][godzina] = lekcjaID
    if (isset($_POST['plan']) && is_array($_POST['plan'])) {
        foreach ($_POST['plan'] as $gID => $days) {
            foreach ($days as $dayNum => $hours) {
                foreach ($hours as $hourID => $lekcjaID) {
                    if (!empty($lekcjaID)) {
                        $stmt->bind_param("iiii", $gID, $dayNum, $hourID, $lekcjaID);
                        $stmt->execute();
                    }
                }
            }
        }
    }

    $_SESSION['powodzenie'] = "Plan lekcji został zaktualizowany!";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

// --- DANE DO JADŁOSPISU ---
$sqlMenu = "SELECT * FROM jadlospis WHERE YEARWEEK(kiedy, 1) = YEARWEEK(CURDATE(), 1)";
$resultMenu = $connection->query($sqlMenu)->fetch_all(MYSQLI_ASSOC);

$menu = [];
foreach ($resultMenu as $row) {
    $menu[$row['kiedy']][$row['typ']] = $row['opis'];
}

$startWeek = new DateTime('monday this week');
$weekDates = [];
for ($i = 0; $i < 5; $i++) {
    $weekDates[] = $startWeek->format('Y-m-d');
    $startWeek->modify('+1 day');
}

$dniTygodniaPlan = [1 => 'Poniedziałek', 2 => 'Wtorek', 3 => 'Środa', 4 => 'Czwartek', 5 => 'Piątek'];
$sqlGodziny = "SELECT * FROM godzinylekcyjne ORDER BY start_time ASC";
$resGodziny = $connection->query($sqlGodziny);
$godzinyList = [];
while ($row = $resGodziny->fetch_assoc()) {
    $godzinyList[] = $row;
}

$sqlLekcjeDict = "SELECT id, nazwa FROM lekcje ORDER BY nazwa";
$resLekcjeDict = $connection->query($sqlLekcjeDict);
$lekcjeDict = [];
while ($row = $resLekcjeDict->fetch_assoc()) {
    $lekcjeDict[$row['id']] = $row['nazwa'];
}

$sqlCurrentPlan = "SELECT * FROM plan_lekcji";
$resCurrentPlan = $connection->query($sqlCurrentPlan);
$matrixPlan = [];
while ($row = $resCurrentPlan->fetch_assoc()) {
    $matrixPlan[$row['grupaID']][$row['day_of_week']][$row['godzinaLekcyjna']] = $row['lekcjaID'];
}

$teachers = [];
$sqlTeachers = "SELECT uzytkownicy.id, uzytkownicy.imie, uzytkownicy.nazwisko FROM uzytkownicy INNER JOIN uprawnienia ON uzytkownicy.typ = uprawnienia.id WHERE uprawnienia.nauczyciel = 1 OR uprawnienia.dyrektor = 1 ORDER BY uzytkownicy.nazwisko, uzytkownicy.imie;";
$resultTeachers = $connection->query($sqlTeachers)->fetch_all();
for ($i = 0; $i < count($resultTeachers); $i++) {
    $teachers[] = [
            'id' => $resultTeachers[$i][0],
            'full_name' => $resultTeachers[$i][1] . ' ' . $resultTeachers[$i][2]
    ];
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
    <link rel="stylesheet" href="./../styles/principle.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Momo+Trust+Display&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="./../assets/logo_tornado.svg">

    <title>Przedszkole Chaosek - Panel dyrektora</title>
    <script src="./../scripts/js/panels.js"></script>
    <script src="./../scripts/js/showUserPanel.js"></script>
    <script src="./../scripts/js/principle.js"></script>
    <script src="./../scripts/js/editGroups.js"></script>

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
    <div class="header-ui">
        <div onclick="showSomething(2)" class="user">
            <div class='userLabel'><?php echo $user->imie . ' ' . $user->nazwisko; ?><br>Dyrektor(ka)</div>
            <img src="../assets/user.svg" alt="user icon">
            <div class="user_pop_up" id="somethingBeingShown2">
                <?php
                if ($user->typ[1] == 1) {
                    echo '<a href="./teacher.php">Panel Nauczyciela</a>';
                }
                if ($user->typ[0] == 1) {
                    echo '<a href="./parents.php">Panel Rodzica</a>';
                }

                ?>
                <a href="inbox.php">Poczta</a>
                <a href="../scripts/php/logout.php">Wyloguj Się</a>
            </div>
        </div>
    </div>
</header>

<div class="layout">
    <nav id="somethingBeingShown1">
        <div class="nav_child" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/rekrutacja.png" alt="">
            <span>Rekrutacja</span>
        </div>
        <div class="nav_child" onclick="showChildren(2)">
            <img src="./../assets/artykuly.png" alt="">
            <div>
                <span>Artykuły</span>
                <span class="nav_arrow">▽</span>
            </div>
        </div>
        <div class="nav_child nav_child_child nav_child_article" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/dodaj_artykul.png" alt="">
            <span>Dodaj artykuł</span>
        </div>
        <div class="nav_child nav_child_child nav_child_article" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/zarzadaj_artykul.png" alt="">
            <span>Zarządzaj artykułami</span>
        </div>
        <div class="nav_child" onclick="showChildren(4)">
            <img src="./../assets/group.png" alt="">
            <span>Grupy</span>
            <span class="nav_arrow">▽</span>
        </div>
        <div class="nav_child nav_child_child nav_child_group" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/little-kid.png" alt="">
            <span>Grupa 1</span>
        </div>
        <div class="nav_child nav_child_child nav_child_group" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/little-kid.png" alt="">
            <span>Grupa 2</span>
        </div>
        <div class="nav_child nav_child_child nav_child_group" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/little-kid.png" alt="">
            <span>Grupa 3</span>
        </div>
        <div class="nav_child nav_child_child nav_child_group" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/little-kid.png" alt="">
            <span>Grupa 4</span>
        </div>
        <div class="nav_child" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/restaurant.png" alt="">
            <span>Jadłospis</span>
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
            <span class='logo-font-small'>Witaj w panelu dyrekcji</span>
            <?php
            if (isset($_SESSION['powodzenie'])) {
                echo "<div class='success-message'>" . $_SESSION['powodzenie'] . "</div>";
                unset($_SESSION['powodzenie']);
            }
            ?>
        </div>

        <div class="main-panel bigContainers main-panel-rekrutacja">
            <div class="styling-panel">
                <div class="formContainer">
                    <div id='listOfApplications'>
                        <hr>
                        <h1 class="logo-font-small"><span>Rekrutacja</span></h1>
                        <?php
                        $sql = "SELECT * FROM oczekujace ORDER BY ID";
                        $result = $connection->query($sql)->fetch_all();
                        for ($i = 0; $i < count($result); $i++) {
                            echo "<div id='Wniosek#" . $result[$i][0] . "' class='wniosek'>";
                            echo "<span class='wniosek-number'>Wniosek #" . $result[$i][0] . "</span> ";
                            echo "<div class='name'>";
                            echo "<span>" . $result[$i][5] . " " . $result[$i][6] . "</span>";
                            echo "</div>";
                            echo "<div class='buttons'>";
                            echo "<select class='submitButton' id='wniosek" . $result[$i][0] . "select'>";
                            echo "<option value=1>Grupa 1 </option>";
                            echo "<option value=2>Grupa 2 </option>";
                            echo "<option value=3>Grupa 3 </option>";
                            echo "<option value=4>Grupa 4 </option>";
                            echo "</select>";

                            echo "<div><button class='more-info' onclick='rozpatrzWniosek(" . json_encode($result[$i], 1) . ")'>🔍︎​</button></div>";
                            echo "<div><button class='accept' onclick='przyjmijWniosek(" . $result[$i][0] . ")'>✔</button></div>";
                            echo "<div><button class='deny' onclick='odrzucWniosek(" . $result[$i][0] . ")'>✖</button></div>";
                            echo "</div>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                    <div class='application' id='Application' style='display: none'>
                        <hr>
                        <h1 class="logo-font-small"><span id='wniosekNumber'>Wniosek #0</span></h1>
                        <div onclick='rozpatrzWniosek("")' class='go-back logo-font-small'>↩</div>
                        <div class='buttonContainer'>
                            <button class='applicationButton activeButton' onclick='changeInfo(1)'>Dane Dziecka</button>
                            <button class='applicationButton' onclick='changeInfo(2)'>Dane Rodzica</button>
                        </div>
                        <div class='infoDziecko info'>
                            <div>Imie Dziecka: <span class='information' id='imie'></span></div>
                            <div>Nazwisko Dziecka: <span class='information' id='nazwisko'></span></div>
                            <div>Data Urodzenia: <span id='dataur'></span></div>
                            <div>Pesel: <span class='information' id='pesel'></span></div>
                            <div>Adres Zamieszkania: <span class='information' id='adres'></span></div>
                        </div>
                        <div class='infoRodzic info' style='display: none'>
                            <div>Imie Rodzica: <span class='information' id='imieR'></span></div>
                            <div>Nazwisko Rodzica: <span class='information' id='nazwiskoR'></span></div>
                            <div>Email Rodzica: <span class='information' id='email'></span></div>
                            <div>Numer Telefonu: <span class='information' id='nrtel'></span></div>
                            <div>Adres Zamieszkania: <span class='information' id='adres'></span></div>
                        </div>
                        <script>
                            function changeInfo(n) {
                                let infoContainers = document.getElementsByClassName("info");
                                let buttons = document.getElementsByClassName("applicationButton");
                                for (let i = 0; i < 2; i++) {
                                    if (i === n - 1) {
                                        infoContainers[i].style.display = "flex";
                                        buttons[i].classList.add('activeButton');
                                        continue;
                                    }
                                    infoContainers[i].style.display = "none";
                                    buttons[i].classList.remove('activeButton');
                                }
                            }
                        </script>
                    </div>
                </div>
            </div>
            <script>
                function dateFromPesel(pesel) {
                    let rok = pesel.substring(0, 2);
                    let miesiac = parseInt(pesel.substring(2, 4), 10);
                    let dzien = pesel.substring(4, 6);
                    let stulecie = '';
                    if (miesiac >= 1 && miesiac <= 12) {
                        stulecie = '19';
                    } else if (miesiac >= 21 && miesiac <= 32) {
                        stulecie = '20';
                        miesiac -= 20;
                    }
                    let pelnyRok = stulecie + rok;
                    miesiac = miesiac.toString().padStart(2, '0');
                    return `${pelnyRok}-${miesiac}-${dzien}`;
                }

                function rozpatrzWniosek(rekord) {
                    if (rekord === "") {
                        document.getElementById('Application').style.display = "none";
                        document.getElementById('listOfApplications').style.display = "block";
                    } else {
                        document.getElementById('wniosekNumber').innerHTML = "Wniosek #" + rekord[0];
                        document.getElementById('Application').style.display = "block";
                        document.getElementById('listOfApplications').style.display = "none";
                        rekordy = [rekord[5], rekord[6], rekord[7], rekord[8], rekord[1], rekord[2], rekord[4], rekord[3], rekord[8]];
                        informations = document.getElementsByClassName('information');
                        for (i = 0; i < informations.length; i++) {
                            informations[i].innerHTML = rekordy[i];
                        }
                        document.getElementById('dataur').innerHTML = dateFromPesel(rekord[7]);
                    }
                }

                function odrzucWniosek(idRekordu) {
                    fetch('./../scripts/php/deleteApplication.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({id: idRekordu})
                    })
                        .then(response => response.text())
                        .then(data => {
                            if (data.trim() === 'OK') {
                                const element = document.getElementById('Wniosek#' + idRekordu);
                                if (element) {
                                    element.style.transition = "opacity 0.5s";
                                    element.style.opacity = "0";
                                    setTimeout(() => element.remove(), 500);
                                }
                            } else {
                                alert('Wystąpił błąd podczas zapisu.');
                            }
                        })
                        .catch(error => console.error('Błąd sieci:', error));
                }

                function przyjmijWniosek(idRekordu) {
                    fetch('./../scripts/php/confirmChild.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({
                            id: idRekordu,
                            group: document.getElementById("wniosek" + idRekordu + "select").value
                        })
                    })
                        .then(response => response.text())
                        .then(data => {
                            if (data.trim() === 'OK') {
                                const element = document.getElementById('Wniosek#' + idRekordu);
                                if (element) {
                                    element.style.transition = "opacity 0.5s";
                                    element.style.opacity = "0";
                                    setTimeout(() => element.remove(), 500);
                                }
                            } else {
                                alert('Wystąpił błąd podczas zapisu.');
                            }
                        })
                        .catch(error => console.error('Błąd sieci:', error));
                }
            </script>
        </div>

        <div class="main-panel bigContainers main-panel-add main-panel-add-article">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class="logo-font-small">Dodaj artykuł</h1>
                    <form method="post" action="../scripts/php/addArticle.php" enctype="multipart/form-data"
                          id="addArticleForm">
                        <div class="article">
                            <div class="article_header">
                                <input type="text" id="articleTitle" name="articleTitle">
                                <input type="date" id="articleData" name="articleData">
                            </div>
                            <textarea id="articleContent" name="articleContent" rows="10" cols="50"></textarea>
                        </div>
                        <div class="details">
                            <div class='choose-image'>
                                <label for="articleImg" class='file-button submitButton'>Wybierz Zdjęcie</label>
                                <input type="file" id="articleImg" name="articleImg" accept="image/*"
                                       style="display: none">
                                <span id='fileName'>Nie wybrano</span>
                                <script>
                                    document.getElementById('articleImg').addEventListener('change', function (e) {
                                        const fileName = e.target.files[0]?.name || '';
                                        document.getElementById('fileName').textContent = fileName ? fileName : 'Nie wybrano';
                                    });
                                </script>
                            </div>
                            <div><input type="submit" value="Dodaj artykuł" class='submitButton'></div>
                            <span></span>
                        </div>
                    </form>
                    <script>
                        document.getElementById('addArticleForm').addEventListener('submit', (e) => {
                            e.preventDefault();
                            let error = false;
                            let form = e.target;
                            let title = document.getElementById('articleTitle');
                            let articleData = document.getElementById('articleData');
                            const val = articleData.value;
                            let articleContent = document.getElementById('articleContent');
                            let fileName = document.getElementById('fileName');

                            if (title.value.length === 0) {
                                title.classList.add('error');
                                error = true;
                            } else {
                                title.classList.remove('error');
                            }
                            if (!val) {
                                articleData.classList.add('error');
                                error = true;
                            } else {
                                articleData.classList.remove('error');
                            }

                            const parts = val.split('-').map(Number);
                            if (parts.length !== 3 || parts.some(isNaN)) {
                                articleData.classList.add('error');
                                error = true;
                            } else {
                                const d1 = new Date(parts[0], parts[1] - 1, parts[2]);
                                const today = new Date();
                                today.setHours(0, 0, 0, 0);
                                if (d1 > today) {
                                    articleData.classList.add('error');
                                    error = true;
                                } else {
                                    articleData.classList.remove('error');
                                }
                            }

                            if (articleContent.value.length === 0) {
                                articleContent.classList.add('error');
                                error = true;
                            } else {
                                articleContent.classList.remove('error');
                            }
                            if (fileName.innerHTML === 'Nie wybrano') {
                                document.getElementById('articleImg').classList.add('error');
                                error = true;
                            } else {
                                document.getElementById('articleImg').classList.remove('error');
                            }

                            if (!error) form.submit();
                        });
                    </script>
                </div>
            </div>
        </div>

        <div class="main-panel bigContainers main-panel-articles" id="main-panel-articles">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class='logo-font-small'><span>Zarządzanie artykułami</span></h1>
                    <?php
                    $sql = "SELECT naglowek, tresc, data, img, id FROM artykuly ORDER BY data DESC";
                    $result = $connection->query($sql)->fetch_all();
                    /*for ($i = 0; $i < count($result); $i++) {
                        echo "<div class='articleDetails' style='animation-delay:". 0.2 * $i ."s' id='article" . $result[$i][4] . "'><div class='header-info'>";
                        echo "<h2 id='articleHeader".$result[$i][4]."'>" . $result[$i][0] . "</h2>";
                        echo "<p class='date' id='articleDate".$result[$i][4]."'>" . $result[$i][2] . "</p></div><div class='header-info'>";
                        echo "<p class='content' id='articleContent".$result[$i][4]."'>" . $result[$i][1] . "</p>";
                        if (!empty($result[$i][3])) {
                            echo "<img id='articleImg".$result[$i][4]."' src='" . './.' . $result[$i][3] . "' alt='Article Image' style='max-width:200px;'></div>";
                        }
                        echo "<button class='edit_article submitButton' id='btnEditArticle" . $result[$i][4] . "' onclick='edytujArtykul(" . $result[$i][4] . ")'>Edytuj artykuł</button>";
                        echo "<button class='delete_article submitButton' onclick='ukryjArtykul(" . $result[$i][4] . ")'>Usuń artykuł</button>";
                        echo "</div>";
                    }*/
                    for ($i = 0; $i < count($result); $i++) {
                        echo "<div class='newsCards' id='article" . $result[$i][4] . "' style='animation-delay: " . $i * 0.2 . "s'>
                                    <div class='header'>
                                        <span class='title' id='articleHeader" . $result[$i][4] . "'>" . $result[$i][0] . "</span>
                                        <span class='date' id='articleDate" . $result[$i][4] . "'>" . $result[$i][2] . "</span>
                                    </div>
                                    <div><div class='header-info'><span class='content' id='articleContent" . $result[$i][4] . "'>" . $result[$i][1] . "</span>";
                        if (!empty($result[$i][3])) {
                            echo "<img id='articleImg" . $result[$i][4] . "' src='" . './.' . $result[$i][3] . "' alt='Article Image' style='max-width:200px;'></div>";
                        }
                        echo "
                                    <div style='display: flex; justify-content: space-between'>
                                    <div style='display: flex; align-items: flex-end'>
                                    <button class='edit_article submitButton' id='btnEditArticle" . $result[$i][4] . "' onclick='edytujArtykul(" . $result[$i][4] . ")'>Edytuj artykuł</button>
                                    <button class='delete_article submitButton' onclick='ukryjArtykul(" . $result[$i][4] . ")'>Usuń artykuł</button></div>
                                    </div>
                                    </div>
                                    <div></div>
                                </div>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <?php for ($g = 1; $g <= 4; $g++): ?>
            <div class="main-panel bigContainers main-panel-groups" id="group<?php echo $g ?>Management">
                <div class="styling-panel">
                    <div class='formContainer'>
                        <hr>
                        <h1 class='logo-font-small'><span>Grupa <?php echo $g; ?></span></h1>
                        <img src="./../assets/edit.png" style="width: 200px" onclick="editGroup(<?php echo $g; ?>)">
                        <div class='groupInfo'>
                            <?php
                            $sql = "SELECT grupy.nazwa, uzytkownicy.imie, uzytkownicy.nazwisko FROM grupy JOIN uzytkownicy ON grupy.Wychowawca = uzytkownicy.ID WHERE grupy.id = $g";
                            $result = $connection->query($sql)->fetch_assoc();
                            echo "<div id='divGroupName" . $g . "'>Nazwa grupy: " . $result['nazwa'] . "</div>";
                            echo "<div id='divGroupSupervisor" . $g . "'>Wychowawca: " . $result['imie'] . " " . $result['nazwisko'] . "</div>";
                            echo "<form action='./../scripts/php/updateGroup.php' method='POST' id='frmUpdateGroup" . $g . "' style='display: none'>";
                                echo "<input type='hidden' name='groupID' value='" . $g . "'>";
                                echo "<label for='inputGroupName" . $g . "' id='labelGroupName" . $g . "'>Nazwa grupy: </label>";
                                echo "<input type='text' id='inputGroupName" . $g . "' name='inputGroupName".$g."'>";
                                echo "<label for='inputGroupSupervisor" . $g . "'  id='labelGroupSupervisor" . $g . "'>Wychowawca: </label>";
                                echo "<select id='inputGroupSupervisor" . $g . "' name='inputGroupSupervisor".$g."'>";
                                for ($i = 0; $i < count($teachers); $i++) {
                                    echo "<option value='" . $teachers[$i]['id'] . "'>" . $teachers[$i]['full_name'] . "</option>";
                                }
                                echo "</select>";
                                echo "<button>Zapisz zmiany</button>";
                            echo "</form>";


                            ?>
                        </div>
                        <div class="groupMembers">
                            <h2>Lista dzieci w grupie:</h2>
                            <div class="table">
                                <div class="table-header">
                                    <div>Imię</div>
                                    <div>Nazwisko</div>
                                    <div>PESEL</div>
                                    <div>Adres</div>
                                    <div>Rodzic</div>
                                    <div></div>
                                </div>
                                <?php
                                $sql = "SELECT dzieci.imie, dzieci.nazwisko, dzieci.pesel, dzieci.adres, uzytkownicy.imie, uzytkownicy.nazwisko, dzieci.ID, dzieci.grupa, dzieci.opinia FROM dzieci JOIN uzytkownicy ON dzieci.IDRodzica = uzytkownicy.ID WHERE dzieci.grupa = $g ORDER BY dzieci.nazwisko;";
                                $result = $connection->query($sql)->fetch_all();
                                for ($i = 0; $i < count($result); $i++) {
                                    $arr = implode(";", $result[$i]);
                                    echo "<div class='grid-row' id='dziecko" . $result[$i][6] . "'>";
                                    echo "<div class='grid-cell'>" . $result[$i][0] . "</div>";
                                    echo "<div class='grid-cell'>" . $result[$i][1] . "</div>";
                                    echo "<div class='grid-cell'>" . $result[$i][2] . "</div>";
                                    echo "<div class='grid-cell'>" . $result[$i][3] . "</div>";
                                    echo "<div class='grid-cell'>" . $result[$i][4] . " " . $result[$i][5] . "</div>";
                                    echo "<div class='grid-cell'><button class='delete_child' onclick='usunDziecko(\"" . $result[$i][6] . "\")'>Usuń dziecko</button><button class='edit_child' onclick='edytujDziecko(\"" . $arr . "\", " . $g . ")'>Edytuj informacje" . $g . "</button></div>";
                                    echo "</div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endfor; ?>

        <div class="main-panel bigContainers main-panel-food">
            <div class="styling-panel jadlospisManagement">
                <div class="formContainer">
                    <hr>
                    <h1 class="logo-font-small"><span>Jadlospis</span></h1>

                    <form method="POST" class='form' id="jadlospisFRM">
                        <div class="border-box">
                            <div class="corner-cell" style='font-size: 24px'>
                                <div class="corner-line"></div>
                                <span class="corner-text-top">Dzień</span>
                                <span class="corner-text-bottom">Danie</span>
                            </div>
                            <?php
                            foreach ($weekDates as $date) {
                                echo '<span class="table_cell label">';
                                echo weekDayFromDate($date) . '<br>';
                                echo '</span>';
                            }
                            ?>

                            <span class="table_cell label">II Śniadanie</span>
                            <?php
                            foreach ($weekDates as $date) {
                                $opis = $menu[$date][0] ?? '';
                                echo '<span class="table_cell">';
                                echo '<textarea class="meal-textarea" name="meals[' . $date . '][0]" placeholder="+ Dodaj">' . htmlspecialchars($opis) . '</textarea>';
                                echo '</span>';
                            }
                            ?>

                            <span class="table_cell label">Obiad</span>
                            <?php
                            foreach ($weekDates as $date) {
                                $opis = $menu[$date][1] ?? '';
                                echo '<span class="table_cell">';
                                echo '<textarea class="meal-textarea" name="meals[' . $date . '][1]" placeholder="+ Dodaj">' . htmlspecialchars($opis) . '</textarea>';
                                echo '</span>';
                            }
                            ?>

                            <span class="table_cell label">Podwieczorek</span>
                            <?php
                            foreach ($weekDates as $date) {
                                $opis = $menu[$date][2] ?? '';
                                echo '<span class="table_cell">';
                                echo '<textarea class="meal-textarea" name="meals[' . $date . '][2]" placeholder="+ Dodaj">' . htmlspecialchars($opis) . '</textarea>';
                                echo '</span>';
                            }
                            ?>
                        </div>
                        <div class='button-container'>
                            <button type="submit" class="submitButton">Zapisz tydzień</button>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                document.getElementById('jadlospisFRM').addEventListener('submit', (e) => {
                    e.preventDefault();
                    let form = e.target;
                    let meals = document.getElementsByClassName('meal-textarea');
                    let error = false;
                    for (let i = 0; i < meals.length; i++) {
                        let elem = meals[i];
                        if (elem.value.length === 0) {
                            elem.classList.add('error');
                            error = true;
                        } else {
                            elem.classList.remove('error');
                        }
                    }
                    if (error) return;
                    form.submit();
                });
            </script>
        </div>

        <div class="main-panel bigContainers main-panel-add main-panel-add-komunikaty" id="addAnnoucement">
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
                                    <option value="1">Grupa 1</option>
                                    <option value="2">Grupa 2</option>
                                    <option value="3">Grupa 3</option>
                                    <option value="4">Grupa 4</option>
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

        <div class="main-panel bigContainers main-panel-komunikaty" id="annoucementManager">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class="logo-font-small"><span>Zarządzaj komunikatami</span></h1>
                    <?php
                    $sql = "SELECT tytul, tresc, data, przynaleznosc, id FROM komunikaty ORDER BY data DESC";
                    $result = $connection->query($sql)->fetch_all();
                    for ($i = 0; $i < count($result); $i++) {
                        echo "<div class='newsCards' id='komunikat" . $result[$i][4] . "' style='animation-delay: " . $i * 0.2 . "s'>
                                    <div class='header'>
                                        <span class='title' id='annoucementHeader" . $result[$i][4] . "'>" . $result[$i][0] . "</span>
                                        <span class='date' id='annoucementDate" . $result[$i][4] . "'>" . $result[$i][2] . "
                                        <br><span class='labelVisibleFor2' style='display:none'>";
                        if ($result[$i][3] == 0) {
                            echo "Wszyscy";
                        } else {
                            echo "Grupa " . $result[$i][3];
                        }
                        echo "</span></span>
                                    </div>
                                    <div><span class='content' id='annoucementContent" . $result[$i][4] . "'>" . $result[$i][1] . "</span>
                                    <div style='display: flex; justify-content: space-between; margin-top: 15px'>
                                    <div style='display: flex; align-items: flex-end'>
                                    <button class='delete_article submitButton' onclick='edytujKomunikat(" . $result[$i][4] . ")'>Edytuj komunikat</button>
                                    <button class='delete_article submitButton' onclick='ukryjKomunikat(" . $result[$i][4] . ")'>Usuń komunikat</button></div>
                                    <span class='labelVisibleFor1' style='color: lightgray;' id='annoucementVisibility" . $result[$i][4] . "'>";
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

                    <form method="POST" class="form" id="planLekcjiFRM">
                        <input type="hidden" name="update_plan_matrix" value="1">

                        <?php for ($g = 1; $g <= 4; $g++): ?>
                            <div id="group-plan-container-<?php echo $g; ?>" class="group-plan-container"
                                 style="<?php echo ($g === 1) ? 'display: block;' : 'display: none;'; ?>">

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
                                                $selectedLessonID = $matrixPlan[$g][$dayNum][$godzina['id']] ?? 0;
                                                ?>

                                                <select class="lesson-select"
                                                        name="plan[<?php echo $g; ?>][<?php echo $dayNum; ?>][<?php echo $godzina['id']; ?>]">
                                                    <option value=""></option>
                                                    <?php foreach ($lekcjeDict as $lID => $lName): ?>
                                                        <option value="<?php echo $lID; ?>" <?php echo ($selectedLessonID == $lID) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($lName); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        <?php endforeach; ?>

                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                        <div class="button-container">
                            <div class='group-switcher'>
                                <?php for ($g = 1; $g <= 4; $g++): ?>
                                    <button type="button"
                                            id="btn-group-<?php echo $g; ?>"
                                            class="plan-group-btn submitButton <?php echo ($g === 1) ? 'active' : ''; ?>"
                                            onclick="showGroupPlan(<?php echo $g; ?>)"
                                            style="min-width: 100px;">
                                        Grupa <?php echo $g; ?>
                                    </button>
                                <?php endfor; ?>
                            </div>
                            <button type="submit" class="submitButton" style="font-size: 1.6em;">Zapisz plan</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="main-panel bigContainers main-panel-add main-panel-add-article" id="editArticle">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class="logo-font-small"><span>Edytuj artykuł</span></h1>
                    <form method="post" action="../scripts/php/editArticle.php" enctype="multipart/form-data"
                          id="editArticleForm">
                        <input type="hidden" name="articleId" id="articleIdHiddenInput">
                        <div class="article">
                            <div class="article_header">
                                <input type="text" id="editArticleHeader" name="editArticleHeader" required>
                                <input type="date" id="editArticleData" name="editArticleData">
                            </div>
                            <textarea id="editArticleContent" name="editArticleContent" rows="10" cols="50"
                                      required></textarea><br><br>
                        </div>
                        <div class="details">
                            <div class='choose-image'>
                                <label for="editArticleImg" class='file-button submitButton'>Wybierz Zdjęcie</label>
                                <input type="file" id="editArticleImg" name="editArticleImg" accept="image/*"
                                       style="display: none">
                                <span id='editFileName'>Nie wybrano</span>
                                <script>
                                    document.getElementById('editArticleImg').addEventListener('change', function (e) {
                                        const fileName = e.target.files[0]?.name || '';
                                        document.getElementById('editFileName').textContent = fileName ? fileName : 'Nie wybrano';
                                    });
                                </script>
                            </div>
                            <div><input id="btnAddArticle" type="submit" value="Zapisz" class='submitButton'></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('editArticleForm').addEventListener('submit', (e) => {
                e.preventDefault();
                let error = false;
                let form = e.target;
                let title = document.getElementById('editArticleHeader');
                let articleData = document.getElementById('editArticleData');
                const val = articleData.value;
                let articleContent = document.getElementById('editArticleContent');
                let fileName = document.getElementById('editFileName');

                if (title.value.length === 0) {
                    title.classList.add('error');
                    error = true;
                } else {
                    title.classList.remove('error');
                }
                if (!val) {
                    articleData.classList.add('error');
                    error = true;
                } else {
                    articleData.classList.remove('error');
                }

                const parts = val.split('-').map(Number);
                if (parts.length !== 3 || parts.some(isNaN)) {
                    articleData.classList.add('error');
                    error = true;
                } else {
                    const d1 = new Date(parts[0], parts[1] - 1, parts[2]);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    if (d1 > today) {
                        articleData.classList.add('error');
                        error = true;
                    } else {
                        articleData.classList.remove('error');
                    }
                }

                if (articleContent.value.length === 0) {
                    articleContent.classList.add('error');
                    error = true;
                } else {
                    articleContent.classList.remove('error');
                }
                if (fileName.innerHTML === 'Nie wybrano') {
                    fileName.classList.add('error');
                    error = true;
                } else {
                    fileName.classList.remove('error');
                }
                if (!error) form.submit();
            });
        </script>

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
                        <textarea id="editKomunikatContent" name="editKomunikatContent" rows="10"
                                  cols="50"></textarea><br><br>
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
        <div class="main-panel bigContainers main-panel-edit-child" id="editChild">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class="logo-font-small"><span>Edytuj dziecko</span></h1>
                    <form method="post" action="./../scripts/php/editChild.php">
                        <input type="hidden" id="editChildId" name="editChildId">
                        <label for="editChildName">Imię:</label>
                        <input type="text" name="editChildName" id="editChildName" required>
                        <label for="editChildSurname">Nazwisko:</label>
                        <input type="text" name="editChildSurname" id="editChildSurname" required>
                        <label for="editChildPesel">PESEL:</label>
                        <input type="text" name="editChildPesel" id="editChildPesel" required>
                        <label for="editChildAddress">Adres zamieszkania:</label>
                        <input type="text" name="editChildAddress" id="editChildAddress" required>
                        <label for="editChildGrupa">Grupa:</label>
                        <select id="editChildGrupa" name="editChildGrupa" class="submitButton">
                            <option value="1">Grupa 1</option>
                            <option value="2">Grupa 2</option>
                            <option value="3">Grupa 3</option>
                            <option value="4">Grupa 4</option>
                        </select>
                        <textarea id="editChildOpinion" name="editChildOpinion"></textarea>
                        <button id="saveChanges" onclick="editChildren()">Zapisz zmiany</button>
                    </form>

                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>