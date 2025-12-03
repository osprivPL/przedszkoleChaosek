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
        <div class="nav_child" onclick="showChildren(5)">
            <img src="./../assets/main_page.png" alt="">
            <div>
                <span>REKRUTACJA</span>
                <span class="nav_arrow">▽</span>
            </div>
        </div>
        <div class="nav_child nav_child_child nav_child_rekrutacja" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Wyświetl kandydatów</span>
        </div>
        <div class="nav_child nav_child_child nav_child_rekrutacja" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Przypisz zatwierdzonych kandydatów</span>
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
            <span>Zarządzanie grupami</span>
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
            <span>Zarządzanie komunikatami</span>
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
        <div class="main-cell main-cell-news bigContainers">
            <h1 class='logo-font-small witaj'>
                <span>Witaj w panelu dyrekcji</span>
                <?php
                if (isset($_SESSION['powodzenie'])) {
                    echo "<div class='success-message'>" . $_SESSION['powodzenie'] . "</div>";
                    unset($_SESSION['powodzenie']);
                }
                ?>
            </h1>
        </div>
        <div class="main-panel bigContainers">
            <div class="styling-panel">
                <h1><span>Wyśwwietl kandydatów</span></h1>
            </div>
        </div>
        <div class="main-panel bigContainers">
            <div class="styling-panel">
                <h1><span>Przypisz zatwierdzonych kandydatów</span></h1>
            </div>
        </div>

        <div class="main-panel bigContainers">
            <div class="styling-panel">
                <h1 class="logo-font-small"><span>Dodaj artykul</span></h1>
                <form method="post" action="../scripts/php/addArticle.php" enctype="multipart/form-data">
                    <label for="articleTitle">Tytuł artykułu:</label><br>
                    <input type="text" id="articleTitle" name="articleTitle" required><br><br>
                    <label for="articleContent">Treść artykułu:</label><br>
                    <textarea id="articleContent" name="articleContent" rows="10" cols="50" required></textarea><br><br>
                    <label for="articleData">Data</label><input type="date" id="articleData" name="articleData"><br><br>
                    <label for="articleImg">Zdjęcie</label>
                    <input type="file" id="articleImg" name="articleImg" accept="image/*"><br><br>

                    <input type="submit" value="Dodaj artykuł">
                </form>
            </div>
        </div>
        <div class="main-panel bigContainers">
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

        <div class="main-panel bigContainers">
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
                            echo "<tr id='dziecko".$result[$i][6]."'>";
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
        <div class="main-panel bigContainers">
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
                            echo "<tr id='dziecko".$result[$i][6]."'>";
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
        <div class="main-panel bigContainers">
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
                            echo "<tr id='dziecko".$result[$i][6]."'>";
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
        <div class="main-panel bigContainers">
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
                            echo "<tr id='dziecko".$result[$i][6]."'>";
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

        <div class="main-panel bigContainers">
            <div class="styling-panel jadlospisManagement">
                <h1 class="logo-font-small"><span>jadlospis</span></h1>

                <form method="POST">

                    <div class="cafeteria-table">
                        <span class="nzw">Data</span>
                        <?php
                        foreach ($weekDates as $date) {
                            echo '<span class="table_cell">';
                            echo weekDayFromDate($date) . '<br>';
//                            echo '<span>' . date('d.m', strtotime($date)) . '</span>';
                            echo '</span>';
                        }
                        ?>
                    </div>

                    <div class="cafeteria-table">
                        <span class="nzw">II Śniadanie</span>
                        <?php
                        foreach ($weekDates as $date) {
                            $opis = $menu[$date][0] ?? '';
                            echo '<span class="table_cell">';
                            echo '<textarea class="meal-textarea" name="meals[' . $date . '][0]" placeholder="+ Dodaj">' . htmlspecialchars($opis) . '</textarea>';
                            echo '</span>';
                        }
                        ?>
                    </div>

                    <div class="cafeteria-table">
                        <span class="nzw">Obiad</span>
                        <?php
                        foreach ($weekDates as $date) {
                            $opis = $menu[$date][1] ?? '';
                            echo '<span class="table_cell">';
                            echo '<textarea class="meal-textarea" name="meals[' . $date . '][1]" placeholder="+ Dodaj">' . htmlspecialchars($opis) . '</textarea>';
                            echo '</span>';
                        }
                        ?>
                    </div>

                    <div class="cafeteria-table">
                        <span class="nzw">Podwieczorek</span>
                        <?php
                        foreach ($weekDates as $date) {
                            $opis = $menu[$date][2] ?? '';
                            echo '<span class="table_cell">';
                            echo '<textarea class="meal-textarea" name="meals[' . $date . '][2]" placeholder="+ Dodaj">' . htmlspecialchars($opis) . '</textarea>';
                            echo '</span>';
                        }
                        ?>
                    </div>

                    <button type="submit" class="btn-save-week">Zapisz tydzień</button>
                </form>
            </div>
        </div>
        <div class="main-panel bigContainers">
            <div class="styling-panel">
                <h1 class="logo-font-small"><span>Dodaj komunikat</span></h1>
                <form method="post" action="./../scripts/php/addAnoucement.php">
                    <label for="komunikatHeader">Nagłówek</label> <input type="text" id="komunikatHeader"
                                                                         name="komunikatHeader"><br><br>
                    <label for="komunikatContent">Treść</label><br>
                    <textarea id="komunikatContent" name="komunikatContent" rows="10" cols="50"></textarea><br><br>
                    <label for="komunikatGrupa">Wybierz widoczność</label>
                    <select id="komunikatGrupa" name="komunikatGrupa">
                        <option value="0">Wszyscy</option>
                        <option value="1">Grupa 1</option>
                        <option value="2">Grupa 2</option>
                        <option value="3">Grupa 3</option>
                        <option value="4">Grupa 4</option>
                    </select><br><br>
                    <input type="submit" value="Dodaj komunikat">
                </form>
            </div>
        </div>

        <div class="main-panel bigContainers">
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
        <div class="main-panel bigContainers">
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