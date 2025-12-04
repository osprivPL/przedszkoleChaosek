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
echo $_SESSION['sql'];
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
    <a href="index.php" id="logo">
        <img src="assets/logo_tornado.svg" alt="logo">
        <span>Przedszkole Chaosek</span>
    </a>
    <!--zrobilem troche lepiej -->
</header>
<!-- ============================= -->
<!-- MAIN -->
<!-- ============================= -->
<main>
    <?php
    printArr($_SESSION);
    ?>
    <div id="container">
        <h1>Podaj kod wysłany na Twój email</h1>
        <form action="./scripts/php/verifyCode.php" method="post" id="codeForm">
            <label for="tbxCode">Kod:</label>
            <input type="text" name="tbxCode" id="tbxCode" maxlength="6" required>
            <span class="error" id="codeError"></span><br>
            <button type="submit" id="btnVerifyCode">Zweryfikuj kod</button>
        </form>
        <?php
        if ($_SESSION['error'] == 3) {
            echo "<span class='error'>Nieprawidłowy kod</span>";
            unset($_SESSION['error']);
        }
        ?>
    </div>
</main>

<script src="./scripts/js/showLogin.js"></script>
<script src="./scripts/js/indexFormValidator.js"></script>

</body>
</html>