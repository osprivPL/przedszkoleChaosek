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
if ($user->typ != 2 || !$_SESSION['logged']) {
    header('Location: ./../index.php');
    die();
}

$conteiner = 1;
$connection = mysqli_connect("localhost", "root", "", "przedszkole");

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
    <div class="header-ui">
        <a href="./inbox.php"><img id="mail" src="./../assets/mail.png" alt="mail"></a>
        <div onclick="userPanel(1)" class="user">
            <div><?php echo $user->imie . ' ' . $user->nazwisko; ?><br>Dyrektor(ka)</div>
            <img src="../assets/user.svg" alt="user icon">
            <div class="user_pop_up" id="user_pop_up1">
                <a href="../index.php">Strona Główna</a>
                <a href="../scripts/php/logout.php">Wyloguj Się</a>
            </div>
        </div>
    </div>
</header>

<div class="layout">
    <nav id="nav">
        <div class="nav_child" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Rekrutacja</span>
        </div>
        <div class="nav_child" onclick="showChildren(2)">
            <img src="./../assets/main_page.png" alt="">
            <div>
                <span>Artykuły</span>
                <span class="nav_arrow">▽</span>
            </div>
        </div>
        <div class="nav_child nav_child_child nav_child_article" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Dodaj artykuł</span>
        </div>
        <div class="nav_child nav_child_child nav_child_article" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Zarządzaj artykułami</span>
        </div>
        <div class="nav_child" onclick="showChildren(4)">
            <img src="./../assets/main_page.png" alt="">
            <span>Grupy</span>
            <span class="nav_arrow">▽</span>
        </div>
        <div class="nav_child nav_child_child nav_child_group" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Grupa 1</span>
        </div>
        <div class="nav_child nav_child_child nav_child_group" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Grupa 2</span>
        </div>
        <div class="nav_child nav_child_child nav_child_group" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Grupa 3</span>
        </div>
        <div class="nav_child nav_child_child nav_child_group" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Grupa 4</span>
        </div>
        <div class="nav_child" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Jadłospis</span>
        </div>
        <div class="nav_child" onclick="showChildren(3)">
            <img src="./../assets/main_page.png" alt="">
            <span>Komunikaty</span>
            <span class="nav_arrow">▽</span>
        </div>
        <div class="nav_child nav_child_child nav_child_annoucement" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Dodaj komunikat</span>
        </div>
        <div class="nav_child nav_child_child nav_child_annoucement" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Zarządzaj komunikatami</span>
        </div>
        <div class="nav_child" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Plan lekcji</span>
        </div>
    </nav>

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
                                
                                /*echo "<span>Rodzic: " . $result[$i][1] . " " . $result[$i][2] . "</span><br>";
                                echo "<span>PESEL: " . $result[$i][7] . "</span><br>";
                                echo "<span>Numer telefonu rodzica: " . $result[$i][3] . "</span>";
                                echo "<span>Email rodzica: " . $result[$i][4] . "</span><br>";
                                echo "<span>Adres zamieszkania: " . $result[$i][8] . "</span><br>";*/
                                echo "<div class='buttons'>";
                                    echo "<select class='submitButton' id='wniosek".$result[$i][0]."select'>";
                                        echo "<option value=1>Grupa 1 </option>";
                                        echo "<option value=2>Grupa 2 </option>";
                                        echo "<option value=3>Grupa 3 </option>";
                                        echo "<option value=4>Grupa 4 </option>";
                                    echo "</select>";
                                    
                                    echo "<div><button class='more-info' onclick='rozpatrzWniosek(".json_encode($result[$i], 1).")'>🔍︎​</button></div>";
                                    echo "<div><button class='accept' onclick='przyjmijWniosek(".$result[$i][0].")'>✔</button></div>";
                                    echo "<div><button class='deny' onclick='odrzucWniosek(".$result[$i][0].")'>✖</button></div>";
                                echo "</div>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                    <div id='Application' style='display: none'>
                        <hr>
                        <h1 class="logo-font-small" id='wniosekNumber'>Wniosek #0</h1>
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
                            function changeInfo(n){
                                let infoContainers = document.getElementsByClassName("info");
                                let buttons = document.getElementsByClassName("applicationButton");
                                for (let i = 0; i < 2; i++){
                                    if (i===n-1){
                                        infoContainers[i].style.display="flex";
                                        buttons[i].classList.add('activeButton');
                                        continue;
                                    }
                                    infoContainers[i].style.display="none";
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
                function rozpatrzWniosek(rekord){
                    if(rekord === ""){
                        document.getElementById('Application').style.display = "none";
                        document.getElementById('listOfApplications').style.display = "block";
                    }else{
                        document.getElementById('wniosekNumber').innerHTML = "Wniosek #" + rekord[0];
                        document.getElementById('Application').style.display = "block";
                        document.getElementById('listOfApplications').style.display = "none";
                        rekordy = [rekord[5], rekord[6], rekord[7], rekord[8], rekord[1], rekord[2], rekord[4], rekord[3], rekord[8]];
                        informations = document.getElementsByClassName('information');
                        for(i = 0; i < informations.length; i++){
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
                                console.error('Błąd serwera:', data);
                                alert('Wystąpił błąd podczas zapisu.');
                            }
                        })
                        .catch(error => {
                            console.error('Błąd sieci:', error);
                        });
                }
            </script>
            <script>
                function przyjmijWniosek(idRekordu) {
                    // wyciemnianie
                    fetch('./../scripts/php/confirmChild.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({id: idRekordu, group: document.getElementById("wniosek"+idRekordu+"select").value})
                    })
                        .then(response => response.text())
                        .then(data => {
                            if (data.trim() === 'OK') {
                                const element = document.getElementById('Wniosek#' + idRekordu);
                                if (element) {
                                    element.style.transition = "opacity 0.5s";
                                    element.style.opacity = "0";
                                    // odciemnianie + kasowanie "wnisoku"

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
        <div class="main-panel bigContainers main-panel-add main-panel-add-article">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class="logo-font-small"><span>Dodaj artykuł</span></h1>
                    <form method="post" action="../scripts/php/addArticle.php" enctype="multipart/form-data">
                        <div class="article">
                            <div class="article_header">
                                <input type="text" id="articleTitle" name="articleTitle" required>
                                <input type="date" id="articleData" name="articleData">
                            </div>
                            <textarea id="articleContent" name="articleContent" rows="10" cols="50" required></textarea><br><br>
                        </div>
                        <div class="details">
                            <div class='choose-image'>
                                <label for="articleImg" class='file-button submitButton'>Wybierz Zdjęcie</label>
                                <input type="file" id="articleImg" name="articleImg" accept="image/*" style="display: none">
                                <span id='fileName'>Nie wybrano</span>
                                <script>
                                    document.getElementById('articleImg').addEventListener('change', function(e){
                                        const fileName = e.target.files[0]?.name || '';
                                        document.getElementById('fileName').textContent = fileName ? fileName : 'Nie wybrano';
                                    });
                                </script>
                            </div>
                            <div><input type="submit" value="Dodaj artykuł" class='submitButton'></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="main-panel bigContainers main-panel-articles">
            <div class="styling-panel articlesManagement">
                <h1><span>Zarzadządzanie artykułami</span></h1>
                <?php
                $sql = "SELECT naglowek, tresc, data, img, id FROM artykuly ORDER BY data DESC";
                $result = $connection->query($sql)->fetch_all();
                for ($i = 0; $i < count($result); $i++) {
                    echo "<div class='articleDetails' id='article" . $result[$i][4] . "'>";
                    echo "<h2>" . $result[$i][0] . "</h2>";
                    echo "<p>" . $result[$i][1] . "</p>";
                    echo "<p>Data: " . $result[$i][2] . "</p>";
                    if (!empty($result[$i][3])) {
                        echo "<img src='" . './.' . $result[$i][3] . "' alt='Article Image' style='max-width:200px;'><br>";
                    }
                    echo "<button class='delete_article' onclick='ukryjArtykul(" . $result[$i][4] . ")'>Usuń artykuł</button>";
                    echo "<hr></div>";
                }
                ?>
                <script>
                    function ukryjArtykul(idRekordu) {
                        fetch('./../scripts/php/deleteArticle.php', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify({id: idRekordu})
                        })
                            .then(response => response.text())
                            .then(data => {
                                if (data.trim() === 'OK') {
                                    const element = document.getElementById('article' + idRekordu);
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
        </div>

        <div class="main-panel bigContainers main-panel-groups">
            <div class="styling-panel">
                <h1><span>Grupa 1</span></h1>
                <div class="groupInfo">
                    <h2>Informacje o grupie:</h2>
                    <?php
                    $sql = "SELECT grupy.nazwa, uzytkownicy.imie, uzytkownicy.nazwisko FROM grupy JOIN uzytkownicy ON grupy.Wychowawca = uzytkownicy.ID WHERE grupy.id = 1";
                    $result = $connection->query($sql)->fetch_assoc();
                    echo "<p>Nazwa grupy: " . $result['nazwa'] . "</p>";
                    echo "<p>Wychowawca: " . $result['imie'] . " " . $result['nazwisko'] . "</p>";
                    ?>
                </div>
                <div class="groupMembers">
                    <h2>Lista dzieci w grupie:</h2>
                    <table>
                        <tr>
                            <th>Imię</th>
                            <th>Nazwisko</th>
                            <th>PESEL</th>
                            <th>Adres</th>
                            <th>Rodzic</th>
                            <th></th>
                        </tr>
                        <?php
                        $sql = "SELECT dzieci.imie, dzieci.nazwisko, dzieci.pesel, dzieci.adres, uzytkownicy.imie, uzytkownicy.nazwisko, dzieci.ID FROM dzieci JOIN uzytkownicy ON dzieci.IDRodzica = uzytkownicy.ID WHERE dzieci.grupa = 1 ORDER BY dzieci.nazwisko;";
                        $result = $connection->query($sql)->fetch_all();
                        for ($i = 0; $i < count($result); $i++) {
                            echo "<tr id='dziecko" . $result[$i][6] . "'>";
                            echo "<td>" . $result[$i][0] . "</td>";
                            echo "<td>" . $result[$i][1] . "</td>";
                            echo "<td>" . $result[$i][2] . "</td>";
                            echo "<td>" . $result[$i][3] . "</td>";
                            echo "<td>" . $result[$i][4] . " " . $result[$i][5] . "</td>";
                            echo "<td><button class='delete_child' onclick='usunDziecko(\"" . $result[$i][6] . "\")'>Usuń dziecko</button></td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="main-panel bigContainers main-panel-groups">
            <div class="styling-panel">
                <h1><span>Grupa 2</span></h1>
                <div class="groupInfo">
                    <h2>Informacje o grupie:</h2>
                    <?php
                    $sql = "SELECT grupy.nazwa, uzytkownicy.imie, uzytkownicy.nazwisko FROM grupy JOIN uzytkownicy ON grupy.Wychowawca = uzytkownicy.ID WHERE grupy.id = 2";
                    $result = $connection->query($sql)->fetch_assoc();
                    echo "<p>Nazwa grupy: " . $result['nazwa'] . "</p>";
                    echo "<p>Wychowawca: " . $result['imie'] . " " . $result['nazwisko'] . "</p>";
                    ?>
                </div>
                <div class="groupMembers">
                    <h2>Lista dzieci w grupie:</h2>
                    <table>
                        <tr>
                            <th>Imię</th>
                            <th>Nazwisko</th>
                            <th>PESEL</th>
                            <th>Adres</th>
                            <th>Rodzic</th>
                            <th></th>
                        </tr>
                        <?php
                        $sql = "SELECT dzieci.imie, dzieci.nazwisko, dzieci.pesel, dzieci.adres, uzytkownicy.imie, uzytkownicy.nazwisko, dzieci.ID FROM dzieci JOIN uzytkownicy ON dzieci.IDRodzica = uzytkownicy.ID WHERE dzieci.grupa = 2 ORDER BY dzieci.nazwisko;";
                        $result = $connection->query($sql)->fetch_all();
                        for ($i = 0; $i < count($result); $i++) {
                            echo "<tr id='dziecko" . $result[$i][6] . "'>";
                            echo "<td>" . $result[$i][0] . "</td>";
                            echo "<td>" . $result[$i][1] . "</td>";
                            echo "<td>" . $result[$i][2] . "</td>";
                            echo "<td>" . $result[$i][3] . "</td>";
                            echo "<td>" . $result[$i][4] . " " . $result[$i][5] . "</td>";
                            echo "<td><button class='delete_child' onclick='usunDziecko(\"" . $result[$i][6] . "\")'>Usuń dziecko</button></td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="main-panel bigContainers main-panel-groups">
            <div class="styling-panel">
                <h1><span>Grupa 3</span></h1>

                <div class="groupInfo">
                    <h2>Informacje o grupie:</h2>
                    <?php
                    $sql = "SELECT grupy.nazwa, uzytkownicy.imie, uzytkownicy.nazwisko FROM grupy JOIN uzytkownicy ON grupy.Wychowawca = uzytkownicy.ID WHERE grupy.id = 3";
                    $result = $connection->query($sql)->fetch_assoc();
                    echo "<p>Nazwa grupy: " . $result['nazwa'] . "</p>";
                    echo "<p>Wychowawca: " . $result['imie'] . " " . $result['nazwisko'] . "</p>";
                    ?>
                </div>
                <div class="groupMembers">
                    <h2>Lista dzieci w grupie:</h2>
                    <table>
                        <tr>
                            <th>Imię</th>
                            <th>Nazwisko</th>
                            <th>PESEL</th>
                            <th>Adres</th>
                            <th>Rodzic</th>
                            <th></th>
                        </tr>
                        <?php
                        $sql = "SELECT dzieci.imie, dzieci.nazwisko, dzieci.pesel, dzieci.adres, uzytkownicy.imie, uzytkownicy.nazwisko, dzieci.ID FROM dzieci JOIN uzytkownicy ON dzieci.IDRodzica = uzytkownicy.ID WHERE dzieci.grupa = 3 ORDER BY dzieci.nazwisko;";
                        $result = $connection->query($sql)->fetch_all();
                        for ($i = 0; $i < count($result); $i++) {
                            echo "<tr id='dziecko" . $result[$i][6] . "'>";
                            echo "<td>" . $result[$i][0] . "</td>";
                            echo "<td>" . $result[$i][1] . "</td>";
                            echo "<td>" . $result[$i][2] . "</td>";
                            echo "<td>" . $result[$i][3] . "</td>";
                            echo "<td>" . $result[$i][4] . " " . $result[$i][5] . "</td>";
                            echo "<td><button class='delete_child' onclick='usunDziecko(\"" . $result[$i][6] . "\")'>Usuń dziecko</button></td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="main-panel bigContainers main-panel-groups">
            <div class="styling-panel">
                <h1>Grupa 4</h1>
                <div class="groupInfo">
                    <h2>Informacje o grupie:</h2>
                    <?php
                    $sql = "SELECT grupy.nazwa, uzytkownicy.imie, uzytkownicy.nazwisko FROM grupy JOIN uzytkownicy ON grupy.Wychowawca = uzytkownicy.ID WHERE grupy.id = 4";
                    $result = $connection->query($sql)->fetch_assoc();
                    echo "<p>Nazwa grupy: " . $result['nazwa'] . "</p>";
                    echo "<p>Wychowawca: " . $result['imie'] . " " . $result['nazwisko'] . "</p>";
                    ?>
                </div>
                <div class="groupMembers">
                    <h2>Lista dzieci w grupie:</h2>
                    <table>
                        <tr>
                            <th>Imię</th>
                            <th>Nazwisko</th>
                            <th>PESEL</th>
                            <th>Adres</th>
                            <th>Rodzic</th>
                            <th></th>
                        </tr>
                        <?php
                        $sql = "SELECT dzieci.imie, dzieci.nazwisko, dzieci.pesel, dzieci.adres, uzytkownicy.imie, uzytkownicy.nazwisko, dzieci.ID FROM dzieci JOIN uzytkownicy ON dzieci.IDRodzica = uzytkownicy.ID WHERE dzieci.grupa = 4 ORDER BY dzieci.nazwisko;";
                        $result = $connection->query($sql)->fetch_all();
                        for ($i = 0; $i < count($result); $i++) {
                            echo "<tr id='dziecko" . $result[$i][6] . "'>";
                            echo "<td>" . $result[$i][0] . "</td>";
                            echo "<td>" . $result[$i][1] . "</td>";
                            echo "<td>" . $result[$i][2] . "</td>";
                            echo "<td>" . $result[$i][3] . "</td>";
                            echo "<td>" . $result[$i][4] . " " . $result[$i][5] . "</td>";
                            echo "<td><button class='delete_child' onclick='usunDziecko(\"" . $result[$i][6] . "\")'>Usuń dziecko</button></td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>

            </div>
        </div>
        <script>
            function usunDziecko(idRekordu) {
                fetch('./../scripts/php/deleteChild.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id: idRekordu})
                })
                    .then(response => response.text())
                    .then(data => {
                        // Sprawdzamy, czy PHP zwróciło dokładnie "OK"
                        if (data.trim() === 'OK') {
                            const element = document.getElementById('dziecko' + idRekordu);
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

        <div class="main-panel bigContainers main-panel-food">
            <div class="styling-panel jadlospisManagement">
                <div class="formContainer">
                    <hr>
                    <h1 class="logo-font-small"><span>Jadlospis</span></h1>

                    <form method="POST" class='form'>
                        <div class="border-box">
                            <span class="table_cell label"><span>Data<br>----------<br>Posiłek</span></span>
                            <?php
                            foreach ($weekDates as $date) {
                                echo '<span class="table_cell label">';
                                echo weekDayFromDate($date) . '<br>';
    //                            echo '<span>' . date('d.m', strtotime($date)) . '</span>';
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
                        <div class='button-container'><button type="submit" class="submitButton">Zapisz tydzień</button></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="main-panel bigContainers main-panel-add main-panel-add-komunikaty">
            <div class="styling-panel">
                <div class="formContainer">
                    <hr>
                    <h1 class="logo-font-small"><span>Dodaj komunikat</span></h1>
                    <form method="post" action="./../scripts/php/addAnoucement.php">
                        <div class="article_header">
                            <input type="text" id="komunikatHeader"name="komunikatHeader">
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
                </div>
            </div>
        </div>

        <div class="main-panel bigContainers main-panel-komunikaty">
            <div class="styling-panel annoucementManagement">
                <h1 class="logo-font-small"><span>Zarządzaj komunikatami</span></h1>
                <?php
                $sql = "SELECT tytul, tresc, data, przynaleznosc, id FROM komunikaty ORDER BY data DESC";
                $result = $connection->query($sql)->fetch_all();
                for ($i = 0; $i < count($result); $i++) {
                    echo "<div class='articleDetails' id='komunikat" . $result[$i][4] . "'>";
                    echo "<h2>" . $result[$i][0] . "</h2>";
                    echo "<p>" . $result[$i][1] . "</p>";
                    echo "<p>Data: " . $result[$i][2] . "</p>";
                    echo "<p>";
                    echo "Wiedoczność: ";
                    if ($result[$i][3] == 0) {
                        echo "Wszyscy";
                    } else if ($result[$i][3] == 1) {
                        echo "Grupa 1";
                    } else if ($result[$i][3] == 2) {
                        echo "Grupa 2";
                    } else if ($result[$i][3] == 3) {
                        echo "Grupa 3";
                    } else if ($result[$i][3] == 4) {
                        echo "Grupa 4";
                    }
                    echo "</p>";
                    echo "<button class='delete_article' onclick='ukryjKomunikat(" . $result[$i][4] . ")'>Usuń komunikat</button>";
                    echo "<hr></div>";
                }
                ?>
                <script>
                    function ukryjKomunikat(idRekordu) {
                        fetch('./../scripts/php/deleteAnnoucement.php', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify({id: idRekordu})
                        })
                            .then(response => response.text())
                            .then(data => {
                                if (data.trim() === 'OK') {
                                    const element = document.getElementById('komunikat' + idRekordu);
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
        </div>
        <div class="main-panel bigContainers main-panel-plan">
            <div class="styling-panel planlekcjiManagement">
                <h1 class="logo-font-small"><span>plan lekcji</span></h1>
                <?php
                $availableLessons = [];
                $sqlLekcje = "SELECT id, nazwa FROM lekcje ORDER BY nazwa";
                $resultLekcje = $connection->query($sqlLekcje);
                while ($row = $resultLekcje->fetch_assoc()) {
                    $availableLessons[$row['id']] = $row['nazwa'];
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_plan_action'])) {
                    if (isset($_POST['schedule'])) {
                        $stmtUpdate = $connection->prepare("UPDATE plan_lekcji SET lekcjaID=?, start_time=?, end_time=? WHERE id=?");
                        $stmtDelete = $connection->prepare("DELETE FROM plan_lekcji WHERE id=?");

                        foreach ($_POST['schedule'] as $id => $data) {
                            if (isset($data['delete']) && $data['delete'] == 1) {
                                $stmtDelete->bind_param("i", $id);
                                $stmtDelete->execute();
                            } else {
                                $stmtUpdate->bind_param("issi", $data['lekcjaID'], $data['start_time'], $data['end_time'], $id);
                                $stmtUpdate->execute();
                            }
                        }
                    }

                    if (isset($_POST['new_schedule'])) {
                        $stmtInsert = $connection->prepare("INSERT INTO plan_lekcji (grupaID, day_of_week, lekcjaID, start_time, end_time) VALUES (?, ?, ?, ?, ?)");

                        foreach ($_POST['new_schedule'] as $gID => $days) {
                            foreach ($days as $dID => $lessonData) {
                                if (!empty($lessonData['lekcjaID']) && !empty($lessonData['start'])) {
                                    $endVal = !empty($lessonData['end']) ? $lessonData['end'] : date('H:i', strtotime($lessonData['start']) + 2700); // domyślnie +45min
                                    $stmtInsert->bind_param("iiiss", $gID, $dID, $lessonData['lekcjaID'], $lessonData['start'], $endVal);
                                    $stmtInsert->execute();
                                }
                            }
                        }
                    }
                    echo "<script>window.location.href = window.location.href;</script>";
                    exit;
                }

                $sqlPlan = "SELECT * FROM plan_lekcji ORDER BY start_time ASC";
                $resultPlan = $connection->query($sqlPlan)->fetch_all(MYSQLI_ASSOC);

                $planData = [];
                for ($g = 1; $g <= 4; $g++) {
                    for ($d = 1; $d <= 5; $d++) {
                        $planData[$g][$d] = [];
                    }
                }
                foreach ($resultPlan as $row) {
                    $planData[$row['grupaID']][$row['day_of_week']][] = $row;
                }

                $weekDays = [1 => 'Poniedziałek', 2 => 'Wtorek', 3 => 'Środa', 4 => 'Czwartek', 5 => 'Piątek'];
                ?>
                <div class="tabs-header">
                    <button class="tab-btn active" onclick="openGroupTab(event, 'tab_g1')">Grupa 1</button>
                    <button class="tab-btn" onclick="openGroupTab(event, 'tab_g2')">Grupa 2</button>
                    <button class="tab-btn" onclick="openGroupTab(event, 'tab_g3')">Grupa 3</button>
                    <button class="tab-btn" onclick="openGroupTab(event, 'tab_g4')">Grupa 4</button>
                </div>

                <form method="POST">
                    <input type="hidden" name="save_plan_action" value="1">

                    <?php for ($g = 1; $g <= 4; $g++): ?>
                        <div id="tab_g<?php echo $g; ?>" class="tab-content"
                             style="display: <?php echo ($g == 1) ? 'block' : 'none'; ?>;">

                            <div class="schedule-grid">
                                <?php foreach ($weekDays as $dayNum => $dayName): ?>
                                    <div class="day-column">
                                        <div class="day-header"><?php echo $dayName; ?></div>
                                        <div class="day-body">

                                            <?php if (!empty($planData[$g][$dayNum])): ?>
                                                <?php foreach ($planData[$g][$dayNum] as $lesson): ?>
                                                    <div class="lesson-card">
                                                        <div class="l-row">
                                                            <input type="time"
                                                                   name="schedule[<?php echo $lesson['id']; ?>][start_time]"
                                                                   value="<?php echo substr($lesson['start_time'], 0, 5); ?>"
                                                                   title="Start">
                                                            <span>-</span>
                                                            <input type="time"
                                                                   name="schedule[<?php echo $lesson['id']; ?>][end_time]"
                                                                   value="<?php echo substr($lesson['end_time'], 0, 5); ?>"
                                                                   title="Koniec">
                                                        </div>
                                                        <div class="l-row">
                                                            <select name="schedule[<?php echo $lesson['id']; ?>][lekcjaID]">
                                                                <?php foreach ($availableLessons as $id => $name): ?>
                                                                    <option value="<?php echo $id; ?>" <?php if ($id == $lesson['lekcjaID']) echo 'selected'; ?>>
                                                                        <?php echo $name; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="l-row delete-check">
                                                            <label>
                                                                <input type="checkbox"
                                                                       name="schedule[<?php echo $lesson['id']; ?>][delete]"
                                                                       value="1">
                                                                Usuń
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                            <div class="lesson-card new-lesson">
                                                <div class="l-title">+ Dodaj lekcję:</div>
                                                <div class="l-row">
                                                    <input type="time"
                                                           name="new_schedule[<?php echo $g; ?>][<?php echo $dayNum; ?>][start]">
                                                    <span>-</span>
                                                    <input type="time"
                                                           name="new_schedule[<?php echo $g; ?>][<?php echo $dayNum; ?>][end]">
                                                </div>
                                                <div class="l-row">
                                                    <select name="new_schedule[<?php echo $g; ?>][<?php echo $dayNum; ?>][lekcjaID]">
                                                        <option value="" selected disabled>- Przedmiot -</option>
                                                        <?php foreach ($availableLessons as $id => $name): ?>
                                                            <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endfor; ?>

                    <div class="save-bar">
                        <button type="submit" class="btn-save-big">Zapisz zmiany w planie</button>
                    </div>
                </form>
            </div>

        </div>
    </main>
</div>
</body>
</html>