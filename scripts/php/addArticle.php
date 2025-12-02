<?php
session_start();

// Ustawienia katalogu
$uploadDir = "./../../assets/articles/";

// 1. Sprawdzenie czy przesłano plik
if (!isset($_FILES["articleImg"]) || $_FILES["articleImg"]["error"] != 0) {
    $_SESSION['blad'] = "<span style='color:red'>Nie wybrano zdjęcia lub wystąpił błąd.</span>";
    // header("Location: ./../../dashboard.php");
    die("Błąd przesyłania pliku.");
}

// 2. Pobranie rozszerzenia pliku
$originalName = basename($_FILES["articleImg"]["name"]);
$fileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

// Sprawdzenie dozwolonych rozszerzeń
$extensions = ['jpg', 'jpeg', 'png', 'webp'];
if (!in_array($fileType, $extensions)) {
    $_SESSION['blad'] = "<span style='color:red'>Niedozwolony format pliku.</span>";
    // header("Location: ./../../dashboard.php");
    die("Błąd formatu.");
}

// ==========================================================
// 3. TWORZENIE NAZWY PLIKU NA PODSTAWIE TYTUŁU (NAGŁÓWKA)
// ==========================================================

$rawTitle = $_POST['articleTitle'];

// Funkcja czyszcząca nazwę (usuwa polskie znaki, spacje, dziwne symbole)
function sanitizeFileName($text) {
    // Tablica tłumaczeń polskich znaków
    $pl_chars = [
        'ą'=>'a', 'ć'=>'c', 'ę'=>'e', 'ł'=>'l', 'ń'=>'n', 'ó'=>'o', 'ś'=>'s', 'ź'=>'z', 'ż'=>'z',
        'Ą'=>'A', 'Ć'=>'C', 'Ę'=>'E', 'Ł'=>'L', 'Ń'=>'N', 'Ó'=>'O', 'Ś'=>'S', 'Ź'=>'Z', 'Ż'=>'Z'
    ];

    // 1. Zamień polskie znaki
    $text = strtr($text, $pl_chars);
    // 2. Zamień wszystko co NIE jest literą lub cyfrą na podkreślnik
    $text = preg_replace('/[^a-zA-Z0-9]/', '_', $text);
    // 3. Usuń podwójne podkreślniki (np. po " - ")
    $text = preg_replace('/_+/', '_', $text);
    // 4. Zmień na małe litery
    $text = strtolower($text);
    // 5. Usuń podkreślniki z początku i końca
    $text = trim($text, '_');

    return $text;
}

// Stwórz bezpieczną nazwę ("Zakończenie roku!" -> "zakonczenie_roku")
$safeTitle = sanitizeFileName($rawTitle);

// Dodaj losową końcówkę, aby uniknąć nadpisania pliku, jeśli dodasz
// dwa artykuły o tym samym tytule (opcjonalne, ale zalecane)
// Jeśli nie chcesz losowości, usuń: . "_" . uniqid()
$newFileName = $safeTitle . "_" . uniqid() . "." . $fileType;

$targetFile = $uploadDir . $newFileName;

// ==========================================================

// 4. Przenoszenie pliku i zapis do bazy
if (move_uploaded_file($_FILES["articleImg"]["tmp_name"], $targetFile)) {

    $connection = new mysqli("localhost", "root", "", "przedszkole");

    if ($connection->connect_errno == 0 ) {
        $title = $connection->real_escape_string($_POST['articleTitle']);
        $content = $connection->real_escape_string($_POST['articleContent']);
        $date = $connection->real_escape_string($_POST['articleData']);
        $imgNameDB = $connection->real_escape_string($newFileName);

        // Zapytanie SQL
        $sql = "INSERT INTO artykuly (naglowek, tresc, data, img) VALUES ('$title', '$content', '$date', '".'./assets/articles/'.$imgNameDB."')";

        if($connection->query($sql)){
            $_SESSION['powodzenie'] = "Dodano artykuł!";
        } else {
            $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
        }

        $connection->close();
    } else {
        $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
    }

} else {
    $_SESSION['powodzenie'] = "Wystąpił błąd, spróbuj ponownie później.";
}
header("Location: ./../../electronicDiary/principle.php");