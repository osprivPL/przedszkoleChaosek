<!DOCTYPE html>
<?php
require_once __DIR__ . '/models/User.php';

use models\User;
session_start();
require_once "./scripts/php/printArr.php";
$user = new User();
$connection = mysqli_connect("localhost", "root", "", "przedszkole");
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

if ($_SESSION['registered']) {
    echo "g";
}
else{
    echo "nie g";
}

if (!isset($_SESSION['logged']) || !$_SESSION['logged']) {
    $_SESSION['logged'] = false;
}
else {

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
    <link rel="stylesheet" href="./styles/rekrutacja.css">

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
<!-- ============================= -->
<!-- HEADER -->
<!-- ============================= -->
<header>
    <a href="old/index.php" id="logo">
        <img src="assets/logo_tornado.svg" alt="logo">
        <span>Przedszkole Chaosek</span>
    </a>
</header>
<!-- ============================= -->
<!-- MAIN -->
<!-- ============================= -->
<main>
    <?php
    printArr($_SESSION);
    ?>
    <div id="container">
        <?php
        if ($_SESSION['error'] == 4 || $_SESSION['registered']) {
            echo '<h1>WNIOSEK ZŁOŻONY POMYŚLNIE!</h1>
            <p>Dziękujemy za złożenie wniosku o przyjęcie dziecka do naszego przedszkola. Wkrótce otrzymają Państwo
            wiadomość e-mail z informacją o wyniku rekrutacji.</p>';
            $connection->query($_SESSION['sql']);
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
<script src="./scripts/js/showLogin.js"></script>
<script src="./scripts/js/indexFormValidator.js"></script>

</body>
</html>
