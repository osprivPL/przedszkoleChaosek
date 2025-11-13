<!DOCTYPE html>
<?php
session_start();
//session_destroy();
require_once "./../scripts/php/printArr.php";
if (!isset($_SESSION['logged'])) {
    $_SESSION['logged'] = false;
}
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = -1;
}
if (!$_SESSION['logged']){
    header("Location: ./../index.php");
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
<!--    <link rel="stylesheet" href="./styles/index.css">-->

    <!-- czcionka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <!-- ikonka -->
    <link rel="icon" type="image/x-icon" href="./assets/logo_tornado.svg">

    <title>Przedszkole Chaosek - <?php

        ?></title>
</head>
<body>
<header>
    <a href="./index.php" id="logo">
         <img src="./../assets/logo_tornado.svg" alt="logo">
        <span>Przedszkole Chaosek</span>
    </a>
    <!--stasiek zrob to lepiej-->
    <img id="login" src="./../assets/person.png" alt="login"
            <?php
            if ($_SESSION['logged']) {
                echo 'onclick="userPanelOn()"';
            } else {
                echo 'onclick="loginOn()"';
            }
            ?>>
</header>
<!-- ============================= -->
<!-- NAVIGATION -->
<!-- ============================= -->
<nav>
    <a href="./../index.php">
        <span>O nas</span>
    </a>
    <a href="./../index.php">
        <span>Aktualności</span>
    </a>
    <a href="./../index.php">
        <span>Jak dojechać?</span>
    </a>
    <a href="./../index.php">
        <span>REKRUTACJA</span>
    </a>
    <a href="./../index.php">
        <span>Kontakt</span>
    </a>
</nav>
<!-- ============================= -->
<!-- MAIN -->
<!-- ============================= -->
<main>
    <?php printArr($_SESSION); ?>
</main>
</body>
</html>