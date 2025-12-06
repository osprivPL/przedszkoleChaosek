<!DOCTYPE html>
<?php
require_once __DIR__ . '/models/User.php';

use models\User;

session_start();
//session_destroy();
require_once "./scripts/php/printArr.php";
if (!isset($_SESSION['logged'])) {
    $_SESSION['logged'] = false;
}
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = -1;
}
$user = new User();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
//echo $_SESSION['sql'];
$_SESSION['sql'] = $_SESSION['sql'];
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
    <link rel="stylesheet" href="./styles/style.css">
    <!-- czcionka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Momo+Trust+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Momo+Trust+Display&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">

    <!-- ikonka -->
    <link rel="icon" type="image/x-icon" href="./assets/logo_tornado.svg">

    <title>Przedszkole Chaosek</title>
</head>
<body id='body'>
<header>
    <div class="square_container">
        <div class="square"></div>
    </div>
    <a href="index.php" class="logo logo-font">
        <img src="assets/logo_tornado.svg" alt="logo">
        <span>Przedszkole Chaosek</span>
    </a>
</header>
<main>
    <?php
    //printArr($_SESSION);
    ?>
    <div id="container" class='container containerProcess'>
        <hr>
        <h1 class='logo-font'>Rekrutacja</h1>
        <div class='codeContainer'>
            <span>Podaj kod wysłany na Twój email</span>
            <form action="./scripts/php/verifyCode.php" method="post" id="codeForm">
                <input type="text" name="tbxCode" id="tbxCode" maxlength="6" required>
                <button type="submit" id="btnVerifyCode" class='submitButton'>Zweryfikuj kod</button>
            </form>
            <?php
            if ($_SESSION['error'] == 3) {
                echo "<span class='error'>Nieprawidłowy kod</span>";
                unset($_SESSION['error']);
            }
            ?>
            <span class="error" id="codeError"></span>
        </div>
    </div>
</main>

<script src="./scripts/js/showLogin.js"></script>
<script src="./scripts/js/indexFormValidator.js"></script>

</body>
</html>