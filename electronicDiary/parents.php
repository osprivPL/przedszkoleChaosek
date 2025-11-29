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
    <nav>
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

        <div class="nav_child nav_child_child nav_child_dziecko" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/little-kid.png" alt="">
            <span>Imie dziecka</span>
        </div>

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
        <div class="nav_child" onclick="showContainer(<?php echo $conteiner;
        $conteiner++; ?>)">
            <img src="./../assets/speaker.png" alt="">
            <span>Ogłoszenia</span>
        </div>
    </nav>

    <!-- ============================= -->
    <!-- MAIN -->
    <!-- ============================= -->
    <main id="main">

        <!-- Mój zamysł na działanie tego są takie że bedzie to działało jak panel rodzica jak się zalogujesz -->
        <!-- Gdy kliknie się na któreś z .nav-child to korespondujacy .main-panel się pokaże -->

        <div class="main-panel main-main" id="main-main">
            <div class="main-style-panel">
                <div class="main-main-cell test-plan">Plan lekcji</div>
                <div class="main-main-cell test-grades">W przedszkolu nie ma ocen</div>
                <div class="main-main-cell test-changes">Zmiany w planie</div>
                <div class="main-main-cell test-plan">Prace domowe</div>
                <div class="main-main-cell test-grades">Ogłoszenia</div>
                <div class="main-main-cell test-changes">Wychowawca</div>
                <div class="main-main-cell test-plan">7</div>
                <div class="main-main-cell test-grades">8</div>
                <div class="main-main-cell test-changes"></div>
            </div>
        </div>

        <div class="main-panel" id="main-child1">
            <div class="main-style-panel">
                <h2>dziecko</h2>
            </div>
        </div>
        <div class="main-panel" id="main-teachers">
            <div class="main-style-panel">
                <h2>nauczyciele</h2>
            </div>
        </div>
        <div class="main-panel" id="main-cafeteria">
            <div class="main-style-panel">
                <div class="cafeteria-table">

                        <?php
                        $sql = "SELECT DISTINCT kiedy FROM jadlospis WHERE YEARWEEK(kiedy, 1) = YEARWEEK(CURDATE(), 1)ORDER BY kiedy ASC;";
                        $result = $connection->query($sql)->fetch_all();
                        for ($i = 0; $i < count($result); $i++) {

                            echo "<span>".weekDayFromDate($result[$i][0])."</span>";
                        }
                        ?>

                </div>
            </div>
        </div>
        <div class="main-panel" id="main-news">
            <div class="main-style-panel">
                <h2>news</h2>
            </div>
        </div>

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

    <script src="./../scripts/js/childrens.js"></script>
    <script src="./../scripts/js/showUserPanel.js"></script>
    <script>
        let php = <?php echo json_encode($json); ?>;
        showOnAside(php);
        // console.log(php);

    </script>
</body>
</html>