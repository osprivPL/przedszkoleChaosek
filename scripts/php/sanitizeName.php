<?php
function sanitizeFileName($text) {
    $pl_chars = [
        'ą'=>'a', 'ć'=>'c', 'ę'=>'e', 'ł'=>'l', 'ń'=>'n', 'ó'=>'o', 'ś'=>'s', 'ź'=>'z', 'ż'=>'z',
        'Ą'=>'A', 'Ć'=>'C', 'Ę'=>'E', 'Ł'=>'L', 'Ń'=>'N', 'Ó'=>'O', 'Ś'=>'S', 'Ź'=>'Z', 'Ż'=>'Z'
    ];

    $text = strtr($text, $pl_chars);
    $text = preg_replace('/[^a-zA-Z0-9]/', '_', $text);
    $text = preg_replace('/_+/', '_', $text);
    $text = strtolower($text);
    $text = trim($text, '_');

    return $text;
}
