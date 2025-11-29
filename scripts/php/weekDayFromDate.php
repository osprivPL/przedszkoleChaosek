<?php
function weekDayFromDate($date)
{
    $dniTygodnia = [
        1 => 'Poniedziałek',
        2 => 'Wtorek',
        3 => 'Środa',
        4 => 'Czwartek',
        5 => 'Piątek',
        6 => 'Sobota',
        7 => 'Niedziela'
    ];


    $czas = strtotime($date);
    $numerDnia = date('N', $czas);

    return $dniTygodnia[$numerDnia]. " (" . date('d.m.y', $czas) . ")";
}