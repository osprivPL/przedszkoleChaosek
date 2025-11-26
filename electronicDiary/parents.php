<!DOCTYPE html>
<?php
require_once "./../scripts/php/printArr.php";
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
if ($user->typ != 0 || !$_SESSION['logged']) {
    header('Location: ./../index.php');
    die();
}
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
</head>
<body>
<header>
    <a href="../index.php" id="logo">
        <img src="./../assets/logo_tornado.svg" alt="logo">
        <span>Przedszkole Chaosek</span>
    </a>
    <!--Tymon zrobił by to lepiej-->
    <div class="header-ui">
        <a href="./inbox.php"><img id="mail" src="./../assets/mail.png" alt="mail"></a>
        <img id="login" src="./../assets/user.svg" alt="login"
             onclick="userPanelOn()">
    </div>
</header>

<div class="layout">
    <!-- ============================= -->
    <!-- NAVIGATION -->
    <!-- ============================= -->
    <nav>
        <div class="nav_child">
            <img src="./../assets/main_page.png" alt="">
            <span>Panel główny</span>
        </div>
        <div class="nav_child nav_child_dzieci">
            <img src="./../assets/playing.png" alt="">
            <span>Dziecko</span>
        </div>
        <div class="nav_child nav_child_szkola">
            <img src="./../assets/school.png" alt="">
            <span>o Szkole</span>
        </div>
        <div class="nav_child ">
            <img src="./../assets/restaurant.png" alt="">
            <span>Stołówka</span>
        </div>
        <div class="nav_child">
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

        <div class="main-panel main-main">
            <div class="main-style-panel">
            <div class="main-panel-cell test-plan">Plan lekcji</div>
            <div class="main-panel-cell test-grades">W przedszkolu nie ma ocen</div>
            <div class="main-panel-cell test-changes">Zmiany w planie</div>
            <div class="main-panel-cell test-plan">Prace domowe</div>
            <div class="main-panel-cell test-grades">Ogłoszenia</div>
            <div class="main-panel-cell test-changes">Wychowawca</div>
            <div class="main-panel-cell test-plan">7</div>
            <div class="main-panel-cell test-grades">8</div>
            <div class="main-panel-cell test-changes">9</div>
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
</div>
<div class="wrapper" id="userWrapper">
    <div id="userPanel" class="panel">
        <button onclick="userPanelOff()" class="offButton">X</button>
        <p>Imię: <?php echo $user->imie ?></p>
        <p>Nazwisko: <?php echo $user->nazwisko ?></p>
        <p>Typ konta: Rodzic
        </p>
        <a href="./parents.php">
            <button>
                Panel rodzica
            </button>
        </a>
        <form action="./../scripts/php/logout.php" method="post">
            <button type="submit">Wyloguj</button>
        </form>
    </div>
</div>
<script src="./../scripts/js/childrens.js"></script>
<script>
    let php = <?php echo json_encode($json); ?>;
    showOnAside(php);
    // console.log(php);
</script>
</body>
</html>