<?php
function dateFromPesel($pesel) {
    $rok = substr($pesel, 0, 2);
    $miesiac = substr($pesel, 2, 2);
    $dzien = substr($pesel, 4, 2);

    $stulecie = '';

    if ($miesiac >= 1 && $miesiac <= 12) {
        $stulecie = 19;
    } elseif ($miesiac >= 21 && $miesiac <= 32) {
        $stulecie = 20;
        $miesiac -= 20;
    }

    $pelnyRok = $stulecie . $rok;

    $miesiac = str_pad($miesiac, 2, "0", STR_PAD_LEFT);

    return "$pelnyRok-$miesiac-$dzien";
}
