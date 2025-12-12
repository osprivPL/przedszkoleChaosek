<!DOCTYPE html>
<?php
echo "<script src='./scripts/js/showLogin.js'></script>";
header("Cache-Control: no-cache");

require_once __DIR__ . '/models/User.php';

use models\User;

session_start();


//session_destroy();
require_once "./scripts/php/printArr.php";
$user = new User();
if (!isset($_SESSION['logged'])) {
    $_SESSION['logged'] = false;
} else if ($_SESSION['logged']) {
    $user = $_SESSION['user'];
}
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = -1;
} else if ($_SESSION['error'] == 0 || $_SESSION['error'] == 1 || $_SESSION['error'] == 2) {
    echo '<script>
    setTimeout(function() {
        loginOn();
    }, 300);

    loginOn();
//       document.addEventListener("DOMContentLoaded", function() {
            
//        });
//    </script>';
}
?>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Przedszkole Chaosek</title>
    <link rel="icon" type="image/x-icon" href="./assets/logo_tornado.svg">

    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="./styles/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Momo+Trust+Display&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">
    <script src='./scripts/js/showUserPanel.js'></script>
    <script src='./scripts/js/passwordReveal.js'></script>
</head>
<body id="body"> <!--- <333333 -->
<div id="dark_bg"></div>
<div class="wrapper" id="loginWrapper" onclick="loginOff()">
    <div onclick="loginOff()" class="offButton" id="siur"><p>X</p></div>
    <form class="panel" action="./scripts/php/login.php" method="post" id="loginPanel"
          onclick="event.stopPropagation()"> <!-- STOP PROPAGANDA -->
        <div class='logo_dziennik'>
            <div>
                <div class="square"></div>
                <img src="./assets/logo_tornado.svg" alt="logo">
                <span class='logo-font-smaller'>Dzienniczek Chaosu</span>
            </div>
        </div>
        <h3 class='logo-font-smaller'>Logowanie</h3>
        <div class="Login">
            <div class='inputGroup'>
                <label for="tbxEmail">Email</label><br>
                <input type="email" name="tbxEmail" id="tbxEmail"><br>
            </div>
            <div class='inputGroup'>
                <label for="tbxHaslo">Hasło</label><br>
                <input type="password" name="tbxHaslo" id="tbxHaslo"><br>
            </div>
        </div>
        <button id="btnLogin" class="submitButton">Zaloguj</button>
        <span id="loginError" name="loginError" class="errorSpan">
            <?php
            if ($_SESSION['error'] == 1) {
                echo "Email nie istnieje w bazie danych";
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
    <script>
        document.getElementById('loginPanel').addEventListener('submit', (e) => {
            e.preventDefault();
            let form = e.target;
            let email = document.getElementById('tbxEmail');
            let password = document.getElementById('tbxHaslo');
            let error = false;
            const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email.value.length === 0 || !pattern.test(email.value)) {
                email.classList.add('error');
                error = true;
            } else {
                email.classList.remove('error');
            }
            if (password.value.length === 0) {
                password.classList.add('error');
                error = true;
            } else {
                password.classList.remove('error');
            }
            if (error) {
                return;
            }

            form.submit();
        });
    </script>
</div>

<div class="wrapper" id="passwordWrapper" style='opacity: 1; pointer-events: auto'>
    <form class="panel passwordPanel" action="./scripts/php/changePassword.php" method="post" id="passwordChangePanel">
        <div class='logo_dziennik'>
            <div>
                <div class="square"></div>
                <img src="./assets/logo_tornado.svg" alt="logo">
                <span class='logo-font-smaller'>Przedszkole Chaosu</span>
            </div>
        </div>
        <h3 class='logo-font-smaller'>Zmiana hasła</h3>
        <div class="Login">
            <div class='inputGroup'>
                <label for="tbxFirstPassword">Hasło</label><br>
                <input type="password" name="tbxFirstPassword" id="tbxFirstPassword"><br>
            </div>
            <div class='inputGroup'>
                <label for="tbxSecondHaslo">Potwierdź hasło</label><br>
                <input type="password" name="tbxSecondHaslo" id="tbxSecondHaslo"><br>
            </div>
        </div>
        <ul class='listOfRequirements'>
            <li id='requirement1'>Hasło ma mieć conajmniej 8 znaków</li>
            <li id='requirement2'>Haslo ma miec conajmniej jedną wielką literę</li>
            <li id='requirement3'>Haslo ma miec conajmniej małą literę</li>
            <li id='requirement4'>Hasło ma mieć conajmniej jeden znak specjalny</li>
        </ul>
        <button id="btnChangePassword" class="submitButton">Zmień hasło</button>
        <span id="passwordChangeError" name="passwordChangeError" class="errorSpan">
            <?php
            if ($_SESSION['error'] == 1) {
                echo "Email nie istnieje w bazie danych";
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
    <script>
        /*document.getElementById('loginPanel').addEventListener('submit', (e) => {
            e.preventDefault();
            let form = e.target;
            let email = document.getElementById('tbxEmail');
            let password = document.getElementById('tbxHaslo');
            let error = false;
            const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email.value.length === 0 || !pattern.test(email.value)) {
                email.classList.add('error');
                error = true;
            } else {
                email.classList.remove('error');
            }
            if (password.value.length === 0) {
                password.classList.add('error');
                error = true;
            } else {
                password.classList.remove('error');
            }
            if (error) {
                return;
            }

            form.submit();
        });*/
    </script>
</div>

<!--============================-->
<!--Sticky nav-->
<!--============================-->
<div class="sticky-banner">
    <script>
        console.log(<?php echo print_r($user->typ); ?>);
    </script>
    <div class="square_container">
        <div class="square"></div>
    </div>
    <div class="options" onclick='showSomething(1)'>
        <span>☰ <span class='sectionsLabel'>Sekcje</span></span>
        <div class="options_pop_up" id='somethingBeingShown1'>
            <a href="#o_nas">O nas</a>
            <a href="#aktualnosci">Aktualności</a>
            <a href="#dojazd">Dojazd</a>
            <a href="./rekrutacja.php">Rekrutacja</a>
            <a href="#kontakt">Kontakt</a>
        </div>
    </div>
    <a class="logo" href="#header">
        <img src="./assets/logo_tornado.svg" alt="logo">
        <div class="text logo-font-small"><span class='labelPrzedszkole'>Przedszkole</span> Chaosek</div>
    </a>

    <div class="nav">
        <a href="#o_nas" class='sectionss'>O nas</a>
        <a href="#aktualnosci" class='sectionss'>Aktualności</a>
        <a href="#dojazd" class='sectionss'>Dojazd</a>
        <a href="./rekrutacja.php" class='sectionss'>Rekrutacja</a>
        <a href="#kontakt" class='sectionss'>Kontakt</a>
        <script>
            console.log(<?php echo print_r($user->typ); ?>);
        </script>
        <?php
        if ($_SESSION['logged']) {
            if ($user->typ[0] == 1) {
                $typ = "Rodzic";
            }
            if ($user->typ[1] == 1) {
                $typ = "Nauczyciel";
            }
            if ($user->typ[2] == 1) {
                $typ = "Dyrekcja";
            }
            echo '<div onclick="showSomething(2)" class="user">
                    <div class="userLabel">' . $user->imie . ' ' . $user->nazwisko . '<br>';
            if ($user->typ[0] == 1) {
                $typ = "Rodzic/";
            }
            if ($user->typ[1] == 1) {
                $typ = "Nauczyciel/";
            }
            if ($user->typ[2] == 1) {
                $typ = "Dyrekcja/";
            }
            $typ = substr($typ, 0, strlen($typ) - 1);
            echo $typ;
            $typ = "";
            echo '</div>
                    <img src="./assets/user.svg" alt="user icon">
                    <div class="user_pop_up" id="somethingBeingShown2">';
            if ($user->typ[0] == 1) {
                echo '<a href="./electronicDiary/parents.php">Panel Rodzica</a>';
            }
            if ($user->typ[1] == 1) {
                echo '<a href="./electronicDiary/teacher.php">Panel Nauczyciela</a>';
            }
            if ($user->typ[2] == 1) {
                echo '<a href="./electronicDiary/principle.php">Panel Dyrekcji</a>';
            }
            echo '<a href="./scripts/php/logout.php">Wyloguj Się</a>
                    </div>
                </div>';
        } else {
            echo '<a onclick="loginOn()" class="loginButton">Zaloguj się</a>';
        }
        ?>
    </div>
</div>

<!--============================-->
<!--Pierwszy, główny "slide"-->
<!--============================-->
<div class="header" id="header">
    <div class="square"></div>
    <div class="nav">
        <div class="options" onclick='showSomething(3)'>
            <span>☰<span class='labelSections'> Sekcje</span></span>
            <div class="options_pop_up" id='somethingBeingShown3'>
                <a href="#o_nas">O nas</a>
                <a href="#aktualnosci">Aktualności</a>
                <a href="#dojazd">Dojazd</a>
                <a href="./rekrutacja.php">Rekrutacja</a>
                <a href="#kontakt">Kontakt</a>

            </div>
        </div>
        <form action="./scripts/php/loginAsParent.php" method="post">
            <button type="submit" id="btnLoginAsParent">Zaloguj się jako Rodzic (demo)</button>
        </form>
        <form action="./scripts/php/loginAsTeacher.php" method="post">
            <button type="submit" id="btnLoginAsTeacher">Zaloguj się jako Nauczyciel (demo)</button>
        </form>
        <form action="./scripts/php/loginAsPrinciple.php" method="post">
            <button type="submit" id="btnLoginAsPrinciple">Zaloguj się jako Dyrektor (demo)</button>
        </form>
        <a href="#o_nas">O nas</a>
        <a href="#aktualnosci">Aktualności</a>
        <a href="#dojazd">Dojazd</a>
        <a href="./rekrutacja.php">Rekrutacja</a>
        <a href="#kontakt">Kontakt</a>
        <?php
        $typ = '';
        if ($_SESSION['logged']) {
            if ($user->typ[0] == 1) {
                $typ = "Rodzic";
            }
            if ($user->typ[1] == 1) {
                $typ = "Nauczyciel";
            }
            if ($user->typ[2] == 1) {
                $typ = "Dyrekcja";
            }
            echo '<div onclick="showSomething(4)" class="user">
                    <div class="labelUser">' . $user->imie . ' ' . $user->nazwisko . '<br>' . $typ . '</div>
                    <img src="./assets/user.svg" alt="user icon">
                    <div class="user_pop_up" id="somethingBeingShown4">';

            if ($user->typ[0] == 1) {
                echo '<a href="./electronicDiary/parents.php">Panel Rodzica</a>';
            }
            if ($user->typ[1] == 1) {
                echo '<a href="./electronicDiary/teacher.php">Panel Nauczyciela</a>';
            }
            if ($user->typ[2] == 1) {
                echo '<a href="./electronicDiary/principle.php">Panel Dyrekcji</a>';
            }
            echo '<a href="./scripts/php/logout.php">Wyloguj Się</a>
                    </div>
                </div>';
        } else {
            echo '<a onclick="loginOn()" class="loginButton">Zaloguj się</a>';
        }
        ?>
    </div>
    <div class="logo">
        <div class="logo_img_container">
            <img src="./assets/logo_tornado.svg">
        </div>
        <div class="text logo-font" style='text-wrap: wrap;'>Przedszkole Chaosek</div>
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
                        <div class="title logo-font-small">Wyjście do Parku <span>21.03.2025</span></div>
                        <div class="context">Dzieci bawiące się na dworze pokazują, że przedszkole stawia na aktywność i codzienny kontakt z naturą.</div>
                    </div>
                </div>
                <div class="slider_element">
                    <div class="slide_content">
                        <div class="title logo-font-small">Wielkanoc<span>20.04.2025</span></div>
                        <div class="context">Sesja zdjęciowa na wielkanoc tworzy ciepłą atmosferę i buduje wyjątkowe tradycje w naszej placówce.</div>
                    </div>
                </div>
                <div class="slider_element">
                    <div class="slide_content">
                        <div class="title logo-font-small">Dzień nauczyciela<span>14.10.2025</span></div>
                        <div class="context">Dzieci w świetnie wyposażonej sali uczą się i rozwijają w bezpiecznym, inspirującym otoczeniu.</div>
                    </div>
                </div>
                <div class="slider_element">
                    <div class="slide_content">
                        <div class="title logo-font-small">Halloween<span>31.10.2025</span></div>
                        <div class="context">Przebieranki na Halloween rozwijają kreatywność i sprawiają, że wspólna zabawa staje się prawdziwą przygodą.</div>
                    </div>
                </div>';
            } else {
                $sql = "SELECT naglowek, tresc, data, img FROM artykuly ORDER BY data DESC LIMIT 5";
                $result = $connection->query($sql)->fetch_all();

                for ($i = 0; $i < count($result); $i++) {
                    $bg = "background-image: url('" . $result[$i][3] . "')";
                    $style = 'style="' . $bg . '"';
                    echo '<div class="slider_element"' . $style . '>';
                    echo '<div class="slide_content">';
                    echo '<div class="title logo-font-small">' . $result[$i][0] . "<span>" . $result[$i][2] . "</span></div>";
                    echo '<div class="context">' . $result[$i][1] . "</div>";
                    echo '</div>';
                    echo '</div>';
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
            świata. Szukasz miejsca bez nudy, pełnego ciepła i inspiracji? Chaosek to świetny wybór - zapraszamy do
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
            Nasze przedszkole w Starych Skoszewach znajduje się w świetnie skomunikowanej lokalizacji. Łatwy dojazd z
            Łodzi i okolicznych miejscowości, bliskość przystanków autobusowych oraz wygodny parking sprawiają, że
            codzienne przywożenie i odbieranie dzieci jest szybkie i komfortowe.
        </div>
    </div>
    <div class="container_google_map">
        <div>
            <div class="border_part"></div>
            <div class="border_part"></div>
            <iframe class="google_map"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d616.1463937555037!2d19.635769074586868!3d51.850263974032025!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471bceee1b893ee7%3A0xdd4b854606d4e!2sStare%20Skoszewy%2018%2C%2092-701%20Stare%20Skoszewy!5e0!3m2!1sen!2spl!4v1763641031850!5m2!1sen!2spl"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>

<!--============================-->
<!--Slide Kontakt-->
<!--============================-->
<div class="slide kontakt" id="kontakt">
    <div class="sub_container">
        <div class="title logo-font-small">Firma</div>
        <a href="#header">Przedszkole Chaosek</a>
        <a>Data założenia 25.11.2025</a>
        <a href="https://pl.wikipedia.org/wiki/Sp%C3%B3%C5%82ka_z_ograniczon%C4%85_odpowiedzialno%C5%9Bci%C4%85"
           target="_blank">Spółka z ogarniczoną odpowiedzialnością</a>
    </div>
    <div class="sub_container">
        <div class="title logo-font-small">Kontakt</div>
        <a href="mailto:przedszkole.chaosek@outlook.com">Email<br>przedszkole.chaosek@gmail.com</a>
        <a href="Tel:+48535091970">Nr Tel<br>+48 535 091 970</a>
        <a href="#dojazd">Adres<br>Stare Skoszewy 44aa</a>
    </div>
    <div class="sub_container">
        <div class="title logo-font-small">Założyciele</div>
        <a href="https://www.instagram.com/michas.cpp/" target="_blank">Michał Ożdżyński</a>
        <a href="https://www.instagram.com/piotrek.peryt/" target="_blank">Piotr Peryt</a>
        <a href="https://www.instagram.com/odroww/" target="_blank">Stanisław Odrowski</a>
    </div>
</div>

<script src='./scripts/js/indexUtilities.js'></script>

</body>
</html>