<!DOCTYPE html>
<?php
require_once __DIR__ . '/models/User.php';

use models\User;
session_start();
require_once "./scripts/php/printArr.php";
$user = new User();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
if (!isset($_SESSION['logged'])) {
    $_SESSION['logged'] = false;
} else {
    $connection = mysqli_connect("localhost", "root", "", "przedszkole");
    $_SESSION['registered'] = true;
    if ($connection) {
        printArr($_POST);
        $childName = htmlentities($_POST['frmChildImie'], ENT_QUOTES, 'UTF-8');
        $childSurname = htmlentities($_POST['frmChildNazwisko'], ENT_QUOTES, 'UTF-8');
        $childPesel = htmlentities($_POST['frmChildPesel'], ENT_QUOTES, 'UTF-8');
        $childAdres = htmlentities($_POST['frmChildAdres'], ENT_QUOTES, 'UTF-8');
        $parentName = $user->imie;
        $parentSurname = $user->nazwisko;
        $parentNumer = $user->telefon;
        $parentEmail = $user->email;
        $_SESSION['info'] = 'japidi';
        if ($connection->query(sprintf("SELECT * FROM oczekujace WHERE imieRodzica = '%s' AND nazwiskoRodzica = '%s' AND numerTelefonu = '%s' AND email='%s' AND imieDziecka = '%s' AND nazwiskoDziecka = '%s' AND pesel = '%s' AND adres = '%s'",
                        mysqli_real_escape_string($connection, $parentName),
                        mysqli_real_escape_string($connection, $parentSurname),
                        mysqli_real_escape_string($connection, $parentNumer),
                        mysqli_real_escape_string($connection, $parentEmail),
                        mysqli_real_escape_string($connection, $childName),
                        mysqli_real_escape_string($connection, $childSurname),
                        mysqli_real_escape_string($connection, $childPesel),
                        mysqli_real_escape_string($connection, $childAdres)))->num_rows > 0) {
            $_SESSION['error'] = 5;
            $_SESSION['info'] = 'jest w bazie';
        } else {
            $sql = sprintf("INSERT INTO oczekujace(imieRodzica, nazwiskoRodzica, numerTelefonu, email, imieDziecka, nazwiskoDziecka, pesel, adres) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                    mysqli_real_escape_string($connection, $parentName),
                    mysqli_real_escape_string($connection, $parentSurname),
                    mysqli_real_escape_string($connection, $parentNumer),
                    mysqli_real_escape_string($connection, $parentEmail),
                    mysqli_real_escape_string($connection, $childName),
                    mysqli_real_escape_string($connection, $childSurname),
                    mysqli_real_escape_string($connection, $childPesel),
                    mysqli_real_escape_string($connection, $childAdres)
            );
            if ($connection->query($sql)) {
                $_SESSION['error'] = 4;
                $_SESSION['info'] = 'jest g';
                $_SESSION['registered'] = true;
            }
        }
    }
}
if (!isset($_SESSION['registered'])) {
//    $_SESSION['registered'] = false;
    echo 'nie g';
//    header('Location: ./index.php');
//    die();
}

if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = -1;
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
    <link rel="stylesheet" href="old/style.css">
    <link rel="stylesheet" href="./styles/mailCode.css">

    <!-- czcionka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Momo+Trust+Display&display=swap" rel="stylesheet">

    <!-- ikonka -->
    <link rel="icon" type="image/x-icon" href="./assets/logo_tornado.svg">

    <title>Przedszkole Chaosek</title>
</head>
<body id='body'>
<!--przyciemnione tło jak odpalasz logowanie-->
<div id="dark_bg"></div>
<!-- ============================= -->
<!-- HEADER -->
<!-- ============================= -->
<header>
    <a href="old/index.php" id="logo">
        <img src="assets/logo_tornado.svg" alt="logo">
        <span>Przedszkole Chaosek</span>
    </a>
    <!--zrobilem troche lepiej -->
    <div id="login"
            <?php
            if ($_SESSION['logged']) {
                echo 'onclick="userPanelOn()"';
            } else {
                echo 'onclick="loginOn()"';
            }
            ?>></div>
</header>
<!-- ============================= -->
<!-- NAVIGATION -->
<!-- ============================= -->
<nav>
    <a href="old/index.php">
        <span>O nas</span>
    </a>
    <a href="old/index.php">
        <span>Aktualności</span>
    </a>
    <a href="old/index.php">
        <span>Jak dojechać?</span>
    </a>
    <a href="./rekrutacja.php">
        <span>REKRUTACJA</span>
    </a>
    <a href="old/index.php">
        <span>Kontakt</span>
    </a>
</nav>
<!-- ============================= -->
<!-- MAIN -->
<!-- ============================= -->
<main>
    <?php
    printArr($_SESSION);
    ?>
    <div id="container">
        <?php
        if ($_SESSION['error'] == 4) {
            echo '<h1>WNIOSEK ZŁOŻONY POMYŚLNIE!</h1>
            <p>Dziękujemy za złożenie wniosku o przyjęcie dziecka do naszego przedszkola. Wkrótce otrzymają Państwo
            wiadomość e-mail z informacją o wyniku rekrutacji.</p>';
        }
//        elseif(){
//            if ($_SESSION['error'] == 5){
//
//            }
//        }
        else {
            echo '<h1>WYSTĄPIŁ BŁĄD PODCZAS SKŁADANIA WNIOSKU!</h1>
            <p>Przepraszamy, ale podczas składania wniosku o przyjęcie dziecka do naszego przedszkola wystąpił błąd.
            Prosimy spróbować ponownie później. Jeśli problem będzie się powtarzał, prosimy o kontakt z administracją
            przedszkola.</p>';
        }
        ?>

    </div>
</main>

<!-- ============================= -->
<!-- LOGIN PANEL -->
<!-- ============================= -->
<div class="wrapper" id="loginWrapper">
    <form class="panel" action="./scripts/php/login.php" method="post" id="loginPanel">
        <h1 class='logo_dziennik'>
            <div>
                <img src="assets/logo_tornado.svg" alt="logo">
            </div>
            <button onclick="loginOff()" class="offButton">X</button>
        </h1>
        <h3>Log in</h3>
        <div class="Login">
            <div>
                <label for="tbxEmail">Email</label>
                <input type="email" name="tbxEmail" id="tbxEmail"><br>
                <span class="error" id="emailError"></span>
            </div>
            <div>
                <label for="tbxHaslo">Hasło</label>
                <input type="text" name="tbxHaslo" id="tbxHaslo"><br>
                <span class="error" id="passwordError"></span>
            </div>
        </div>
        <button id="btnLogin">Zaloguj</button>
        <span id="loginError" name="loginError" class="error">
        <?php
        if ($_SESSION['error'] == 1) {
            echo "Nie znaleziono użytkownika o podanym emailu";
            unset($_SESSION['error']);
        } else if ($_SESSION['error'] == 0) {
            echo "Błąd serwera, spróbuj ponownie później";
            unset($_SESSION['error']);
        } else if ($_SESSION['error'] == 2) {
            echo "Nieprawidłowe hasło";
            unset($_SESSION['error']);
        }
        ?>
    </span>
    </form>

</div>

<div class="wrapper" id="userWrapper">
    <div id="userPanel" class="panel">
        <button onclick="userPanelOff()" class="offButton">X</button>
        <p>Imię: <?php echo $user->imie ?></p>
        <p>Nazwisko: <?php echo $user->nazwisko ?></p>
        <p>Typ konta:
            <?php
            if ($user->typ == 0) {
                echo "Rodzic";
            } else if ($user->typ == 1) {
                echo "Nauczyciel(ka)";
            } else {
                echo "Dyrekcja";
            }
            ?>
        </p>
        <a href="
        <?php
        if ($user->typ == 0) {
            echo "./panels/parents.php";
        } else {
            echo "./panels/admin.html";
        }
        ?>
        ">
            <button>
                <?php
                if ($user->typ == 0) {
                    echo "Panel Rodzica";
                } else {
                    echo "Panel Pracownika";
                }
                ?>
            </button>
        </a>
        <form action="./scripts/php/logout.php" method="post">
            <button type="submit">Wyloguj</button>
        </form>
    </div>
</div>


<script src="./scripts/js/showLogin.js"></script>
<script src="./scripts/js/indexFormValidator.js"></script>

</body>
</html>
