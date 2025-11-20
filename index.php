<!DOCTYPE html>
<?php
header("Cache-Control: no-cache");
session_start();
//session_destroy();
require_once "./scripts/php/printArr.php";
if (!isset($_SESSION['logged'])) {
    $_SESSION['logged'] = false;
}
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = -1;
} else if ($_SESSION['error'] != -1) {
   echo '<script>
       document.addEventListener("DOMContentLoaded", function() {
            var loginEl = document.getElementById("login");
            if (loginEl) loginEl.click();
        });
    </script>';
}
?>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST</title>

    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="./styles/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Momo+Trust+Display&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">
</head>
<body id="body"> <!--- <333333 -->
    <div id="dark_bg"></div>
    <div class="wrapper" id="loginWrapper">
           <form class="panel" action="./scripts/php/login.php" method="post" id="loginPanel">
            <div onclick="loginOff()" class="offButton"><p>X</p></div>
            <h1 class='logo_dziennik'>
                <div>
                    <img src="./assets/logo_tornado.svg" alt="logo">
                                   <span>Dzienniczek Chaosu</span>
                </div>

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

<!--============================-->
<!--Sticky nav-->
<!--============================-->
<div class="sticky-banner">
    <div class="square"></div>
    <a class="logo" href="#header">
        <img src="./assets/logo_tornado.svg">
        <div class="text logo-font">Przedszkole Chaosek</div>
    </a>
    <div class="nav">
        <!--            --><?php //printArr($_SESSION); ?>
        <form action="./scripts/php/loginAsParent.php" method="post">
            <button type="submit" id="btnLoginAsParent">Zaloguj się jako Rodzic (demo)</button>
        </form>
        <a href="#o_nas">O nas</a>
        <a href="#aktualnosci">Aktualności</a>
        <a href="#dojazd">Dojazd</a>
        <a href="#rejestracja">Rejestracja</a>
        <a href="#kontakt">Kontakt</a>
        <a href="#phpOutputs">php</a>
        <img src="./assets/user.svg"></img>
    </div>
</div>

<!--============================-->
<!--Pierwszy, główny "slide"-->
<!--============================-->
<div class="header" id="header">
    <div class="square"></div>
    <div class="nav">
        <a href="#o_nas">O nas</a>
        <a href="#aktualnosci">Aktualności</a>
        <a href="#dojazd">Dojazd</a>
        <a href="#rejestracja">Rejestracja</a>
        <a href="#kontakt">Kontakt</a>
        <a href="#phpOutputs">php</a>
        <img onclick="loginOn()" src="./assets/user.svg"></img>
    </div>
    <div class="logo">
        <div class="logo_img_container">
            <img src="./assets/logo_tornado.svg" class='no_drag' draggable="false">
        </div>
        <div class="text logo-font">Przedszkole<br>Chaosek</div>
    </div>
</div>

<!--============================-->
<!--Slide o Nas-->
<!--============================-->
<div class="slide o_nas" id="o_nas">
    <div class="text_container">
        <h1 class="logo-font">
            <div class="hide_brush"></div>
            <span>O nas</span></h1>
        <div class="text">
            W Chaosku tworzymy przyjazne środowisko, w którym dzieci mogą swobodnie poznawać świat przez zabawę. Nasza
            wykwalifikowana kadra łączy opiekę z metodami wspierającymi rozwój emocjonalny, społeczny i poznawczy.
            Kładziemy nacisk na kreatywność, samodzielność i współpracę — codzienne zajęcia są pełne eksperymentów,
            ruchu i zajęć artystycznych. Bezpieczeństwo i otwartość na potrzeby rodziny są dla nas priorytetem.
        </div>
    </div>
    <img src="./assets/onasimage1.png">
</div>

<!--============================-->
<!--Slide Aktualności-->
<!--============================-->
<div class="slide aktualnosci" id="aktualnosci">
    <div class="slider">
        <div class="slides">
            <?php
            $connection = new mysqli("localhost", "root", "", "przedszkole");
            $connection->set_charset("utf8");
            if ($connection->connect_errno != 0) {
                echo '<div class="slider_element">
                    <div class="slide_content">
                        <div class="title">Wyjście do Parku <span>21.03.2025</span></div>
                        <div class="context">Dzieci bawiące się na dworze pokazują, że przedszkole stawia na aktywność i codzienny kontakt z naturą.</div>
                    </div>
                </div>
                <div class="slider_element">
                    <div class="slide_content">
                        <div class="title">Wielkanoc<span>20.04.2025</span></div>
                        <div class="context">Sesja zdjęciowa na wielkanoc tworzy ciepłą atmosferę i buduje wyjątkowe tradycje w naszej placówce.</div>
                    </div>
                </div>
                <div class="slider_element">
                    <div class="slide_content">
                        <div class="title">Dzień nauczyciela<span>14.10.2025</span></div>
                        <div class="context">Dzieci w świetnie wyposażonej sali uczą się i rozwijają w bezpiecznym, inspirującym otoczeniu.</div>
                    </div>
                </div>
                <div class="slider_element">
                    <div class="slide_content">
                        <div class="title">Halloween<span>31.10.2025</span></div>
                        <div class="context">Przebieranki na Halloween rozwijają kreatywność i sprawiają, że wspólna zabawa staje się prawdziwą przygodą.</div>
                    </div>
                </div>';
            }
            else {
                $sql = "SELECT naglowek, tresc, data, img FROM artykuly ORDER BY data DESC LIMIT 6";
                $result = $connection->query($sql)->fetch_all();

                for ($i = 0; $i < 6; $i++){
                    $bg = "background-image: url('".$result[$i][3]."')";
                    $style = 'style="'.$bg.'"';
                    echo'<div class="slider_element"'.$style.'>';
                        echo '<div class="slide_content">';
                            echo '<div class="title">'.$result[$i][0]."<span>".$result[$i][2]."</span></div>";
                            echo '<div class="context">'.$result[$i][1]."</div>";
                        echo '</div>';
                    echo'</div>';
//                    if ($i == 2){
//                        die();
//                    }

                }
            }

            ?>

        </div>
    </div>
    <div class="text_container">
        <h1 class="logo-font">
            <div class="hide_brush"></div>
            <span>Aktualności</span></h1>
        <div class="text">W Przedszkolu Chaosek każdy dzień to pełna radości i kreatywnej zabawy przygoda. Nasze sale
            tętnią energią, a dzieci biorą udział w różnorodnych zajęciach rozwijających wyobraźnię i ciekawość
            świata.Szukasz miejsca bez nudy, pełnego ciepła i inspiracji? Chaosek to świetny wybór - zapraszamy do
            zapisów!
        </div>
    </div>
</div>

<!--============================-->
<!--Slide Dojazd-->
<!--============================-->
<div class="slide dojazd" id="dojazd">
    <div class="text_container">
        <h1 class="slide_title logo-font">Dojazd</h1>
        <div class="text">
            Nasze przedszkole w Starych Skoszewach znajduje się w świetnie skomunikowanej lokalizacji. Łatwy dojazd z Łodzi i okolicznych miejscowości, bliskość przystanków autobusowych oraz wygodny parking sprawiają, że codzienne przywożenie i odbieranie dzieci jest szybkie i komfortowe.
        </div>
    </div>
    <div class="container_google_map">
        <div>
            <div class="border_part"></div>
            <div class="border_part"></div>
            <iframe class="google_map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d616.1463937555037!2d19.635769074586868!3d51.850263974032025!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471bceee1b893ee7%3A0xdd4b854606d4e!2sStare%20Skoszewy%2018%2C%2092-701%20Stare%20Skoszewy!5e0!3m2!1sen!2spl!4v1763641031850!5m2!1sen!2spl" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>

<!--============================-->
<!--Slide Kontakt-->
<!--============================-->
<div class="slide kontakt" id="kontakt">

</div>
<div id="phpOutputs">
    <?php
    printArr($_SESSION);
    printArr($result);
    ?>

</div>
    <script src='./scripts/js/indexUtilities.js'></script>
    <script src='./scripts/js/showLogin.js'></script>
</body>
</html>