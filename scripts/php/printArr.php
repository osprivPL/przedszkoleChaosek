<?php
    function printArr(&$arr): void {

        foreach ($arr as $key => $value) {
            try {
                if (is_array($value)) {
                    echo $key . " => <br>";
                    printArr($value);
                    continue;
                }
                else if (is_object($value)) {
                 continue;
                }
                else if (gettype($value) == "boolean") {
                    echo $key . " => " . ($value ? "true" : "false") . "<br>";
                } else {
                    echo $key . " => " . $value . "<br>";
                }
            }
            catch(Exception $e) {
                continue;
            }
        }
}

    function printUser(&$user): void {
        echo "Imię: " . $user->getImie() . "<br>";
        echo "Nazwisko: " . $user->getNazwisko() . "<br>";
        echo "Typ: " . $user->getTyp() . "<br>";
        echo "Numer telefonu: " . $user->getNumerTelefonu() . "<br>";
        echo "Login: " . $user->getLogin() . "<br>";
    }