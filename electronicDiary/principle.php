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
if ($user->typ != 2 || !$_SESSION['logged']) {
    header('Location: ./../index.php');
    die();
}

$conteiner = 1;

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
    <link rel="stylesheet" href="./../styles/principle.css">

    <!-- czcionka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <!-- ikonka -->
    <link rel="icon" type="image/x-icon" href="./../assets/logo_tornado.svg">

    <title>Przedszkole Chaosek - Panel dyrektora</title>
    <script src="./../scripts/js/panels.js"></script>
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
    <!-- ============================= -->
    <!-- NAVIGATION -->
    <!-- ============================= -->
    <nav id="nav">
        <div class="nav_child" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>REKRUTACJA</span>
        </div>
        <div class="nav_child" onclick="showChildren(2)">
            <img src="./../assets/main_page.png" alt="">
            <span>Artykuły</span>
            <span class="nav_arrow">▽</span>
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
        <div class="nav_child" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Zarządzanie grupami</span>
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
            <span>Dodaj artykuł</span>
        </div>
        <div class="nav_child nav_child_child nav_child_annoucement" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Zarządzaj artykułami</span>
        </div>


        <div class="nav_child" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/main_page.png" alt="">
            <span>Plan lekcji</span>
        </div>
    </nav>

    <!-- ============================= -->
    <!-- MAIN -->
    <!-- ============================= -->
    <main id="main">
        <div class="main-cell main-cell-news bigContainers">
            <h1 class='logo-font-small witaj'>
                <span>Witaj w panelu dyrekcji</span>
            </h1>
        </div>
        <div class="main-panel bigContainers">
            <div class="styling-panel">
                <h1><span>rekrutacja</span></h1>
            </div>
        </div>
        <div class="main-panel bigContainers">
            <div class="styling-panel">
                <h1 class="logo-font-small"><span>Dodaj artykul</span></h1>
                <form method="post" action="./../scripts/php/img.php" enctype="multipart/form-data">
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
                        echo "<div class='articleDetails' id='article".$result[$i][4]."'>";
                        echo "<h2>" . $result[$i][0] . "</h2>";
                        echo "<p>" . $result[$i][1] . "</p>";
                        echo "<p>Data: " . $result[$i][2] . "</p>";
                        if (!empty($result[$i][3])) {
                            echo "<img src='" . './.'. $result[$i][3] . "' alt='Article Image' style='max-width:200px;'><br>";
                        }
                        echo "<button class='delete_article' onclick='ukryjArtykul(".$result[$i][4].")'>Usuń artykuł</button>";
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
                <h1><span>grupy</span></h1>
            </div>
        </div>
        <div class="main-panel bigContainers">
            <div class="styling-panel">
                <h1><span>jadlospis</span></h1>
            </div>
        </div>
        <div class="main-panel bigContainers">
            <div class="styling-panel">
                <h1 class="logo-font-small"><span>Dodaj komunikat</span></h1>

            </div>
        </div>

        <div class="main-panel bigContainers">
            <div class="styling-panel annoucementManagement" >
                <h1 class="logo-font-small"><span>Zarządzaj komunikatami</span></h1>
                <?php
                    $sql = "SELECT tytul, tresc, data, przynaleznosc, id FROM komunikaty ORDER BY data DESC";
                    $result = $connection->query($sql)->fetch_all();
                    for ($i = 0; $i < count($result); $i++) {
                        echo "<div class='articleDetails' id='komunikat".$result[$i][4]."'>";
                        echo "<h2>" . $result[$i][0] . "</h2>";
                        echo "<p>" . $result[$i][1] . "</p>";
                        echo "<p>Data: " . $result[$i][2] . "</p>";
//                        echo "<p>Przynależność: " . $result[$i][3] . "</p>";
                        echo "<p>";
                            echo "Wiedoczność: ";
                            if ($result[$i][3] == 0) {
                                echo "Wszyscy";
                            }
                            else if ($result[$i][3] == 1){
                                echo "Grupa 1";
                            }
                            else if ($result[$i][3] == 2){
                                echo "Grupa 2";
                            }
                            else if ($result[$i][3] == 3){
                                echo "Grupa 3";
                            }
                            else if ($result[$i][3] == 4){
                                echo "Grupa 4";
                            }
                        echo "</p>";
                        echo "<button class='delete_article' onclick='ukryjKomunikat(".$result[$i][4].")'>Usuń komunikat</button>";
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
            <div class="styling-panel">
                <h1 class="logo-font-small"><span>plan lekcji</span></h1>
            </div>

        </div>
    </main>
</div>
</body>
</html>