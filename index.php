<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Michał Ożdżyński Stanisław Odrowski Piotr Peryt">
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="./styles/index.css">

    <!-- czcionka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <title>Przedszkole Chaosek</title>
</head>
<body>
<header>
    <a href="./index.html" id="logo">
        <img src="assets/pochita.png" alt="logo">
        <span>Przedszkole Chaosek</span>
    </a>
    <!--stasiek zrob to lepiej-->
    <img id="login" src="./assets/person.png" alt="login" onclick="PanelOn()">
</header>
<nav>
    <a href="./index.html">
        <span>O nas</span>
    </a>
    <a href="./index.html">
        <span>Aktualności</span>
    </a>
    <a href="./index.html">
        <span>Jak dojechać?</span>
    </a>
    <a href="./index.html">
        <span>REKRUTACJA</span>
    </a>
    <a href="./index.html">
        <span>Kontakt</span>
    </a>
</nav>

<!-- logowanie-->

<!-- jakas rejestracja -->
<!--<div class="SignUp">-->
<!--    <h3>Sign up</h3>-->
<!--    <div class="SignInLogIn">-->
<!--        <label for="tbxImie_singUp">Imie</label>-->
<!--        <input type="text" name="tbxImie_singUp" id="tbxImie_singUp">-->
<!---->
<!--        <label for="tbxNazw_singUp">Nazwisko</label>-->
<!--        <input type="text" name="tbxNazw_singUp" id="tbxNazw_singUp">-->
<!---->
<!--        <label for="tbxNum_singUp">Numer telefonu</label>-->
<!--        <input type="number" name="tbxNum_singUp" id="tbxNum_singUp">-->
<!--        <label for="tbxPesel_singUp">Pesel</label>-->
<!--        <input type="text" name="tbxPesel_singUp" id="tbxPesel_singUp">-->
<!---->
<!--        <label for="tbxAdres_singUp">Miejsce zamieszkania</label>-->
<!--        <input type="text" name="tbxAdres_singUp" id="tbxAdres_singUp">-->
<!---->
<!--        <label for="tbxHaslo_singUp">Hasło</label>-->
<!--        <input type="text" name="tbxHaslo_singUp" id="tbxHaslo_singUp">-->
<!---->
<!--        <label for="tbxHasloRep_singUp">Powturzenie hasła</label>-->
<!--        <input type="text" name="tbxHasloRep_singUp" id="tbxHasloRep_singUp">-->
<!--    </div>-->
<!--</div>-->


<div class="panelWrapper" id="panelWrapper">
    <form class="panel" action="./scripts/php/login.php" method="post" id="loginPanel">
        <div class="LogIn">
            <h3>Log in</h3>
            <div class="Login">
                <div>
                    <label for="tbxEmail">Email</label>
                    <input type="email" name="tbxEmail" id="tbxEmail"><br>
                    <span class="error" id="emailError">Wprowadź poprawny email</span>
                </div>
                <div>
                    <label for="tbxHaslo">Hasło</label>
                    <input type="text" name="tbxHaslo" id="tbxHaslo">
                    <span class="error" id="passwordError">Wprowadź poprawne hasło</span>
                </div>
            </div>
        </div>
        <button id="btnLogin">Zaloguj</button>
    </form>
    <button onclick="PanelOff()" class="offButton">X</button>
</div>


<script src="./scripts/js/showLogin.js"></script>
<script src="./scripts/js/indexFormValidator.js"></script>

</body>
</html>