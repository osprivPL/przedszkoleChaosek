-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2025 at 06:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `przedszkole`
--

-- --------------------------------------------------------

--
-- Table structure for table `artykuly`
--

CREATE TABLE `artykuly` (
  `ID` int(11) NOT NULL,
  `naglowek` varchar(100) NOT NULL,
  `tresc` text NOT NULL,
  `data` date NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `artykuly`
--

INSERT INTO `artykuly` (`ID`, `naglowek`, `tresc`, `data`, `img`) VALUES
(1, 'Jesienna wycieczka do parku', 'W październiku nasze przedszkolaki wybrały się na kolorową wycieczkę do parku, gdzie obserwowały zmieniającą się przyrodę. Dzieci zbierały liście i bawiły się na świeżym powietrzu.', '2025-11-12', './assets/articles/jesienna_wycieczka_do_parku_6935a8fb8bd92.png'),
(3, 'Warsztaty kulinarne – robimy sałatkę owocową', 'Przedszkolaki własnoręcznie przygotowały zdrową i pyszną sałatkę owocową, ucząc się rozpoznawać różne owoce oraz dbając o higienę.', '2025-10-10', './assets/articles/salatka.png'),
(4, 'Teatrzyk kukiełkowy', 'Nauczyciele przygotowali przedstawienie kukiełkowe, które bardzo spodobało się dzieciom i zainspirowało je do własnej twórczości.', '2025-10-27', './assets/articles/teatrzyk.png'),
(8, 'Chaotyczna Wielkanoc', 'Sesja zdjęciowa na wielkanoc tworzy ciepłą atmosferę i buduje wyjątkowe tradycje w naszej placówce.', '2025-04-20', './assets/articles/wielkanoc.png'),
(9, 'Halloween', 'Przebieranki na Halloween rozwijają kreatywność i sprawiają, że wspólna zabawa staje się prawdziwą przygodą.', '2025-10-31', './assets/articles/halloween.png');

-- --------------------------------------------------------

--
-- Table structure for table `dzieci`
--

CREATE TABLE `dzieci` (
  `ID` int(11) NOT NULL,
  `imie` varchar(50) NOT NULL,
  `nazwisko` varchar(50) NOT NULL,
  `pesel` varchar(11) NOT NULL,
  `adres` varchar(100) NOT NULL,
  `grupa` int(20) DEFAULT NULL,
  `img` varchar(50) NOT NULL,
  `IDRodzica` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `dzieci`
--

INSERT INTO `dzieci` (`ID`, `imie`, `nazwisko`, `pesel`, `adres`, `grupa`, `img`, `IDRodzica`) VALUES
(1, 'Jonaszek', 'Kruk', '21241201290', 'Łódź, ul. Sienkiewicza 6, m. 7', 1, 'Jonaszek_Kruk.png', 1),
(2, 'Aldona', 'Kruk', '20271912145', 'Łódź, ul. Sienkiewicza 6, m. 7', 2, 'Aldona_Kruk.png', 1),
(3, 'Zosia', 'Kowalska', '21231501234', 'Łódź, ul. Kwiatowa 5/10', 1, 'brak', 4),
(4, 'Jan', 'Kowalski', '18251209876', 'Łódź, ul. Kwiatowa 5/10', 4, 'brak', 4),
(5, 'Krzyś', 'Nowak', '20252005432', 'Łódź, ul. Słoneczna 12', 2, 'brak', 5),
(6, 'Ala', 'Wiśniewska', '19301011223', 'Łódź, ul. Lipowa 3', 3, 'brak', 6),
(7, 'Olek', 'Wiśniewski', '21310533441', 'Łódź, ul. Lipowa 3', 1, 'brak', 6),
(8, 'Filip', 'Wiśniewski', '18222855667', 'Łódź, ul. Lipowa 3', 4, 'brak', 6),
(9, 'Michał', 'Zieliński', '19260199887', 'Łódź, ul. Długa 50/4', 3, 'brak', 7),
(18, 'Brajan', 'Symilak', '22230711738', 'Wojska Polskiego 19/10', 1, 'brak', 21);

-- --------------------------------------------------------

--
-- Table structure for table `grupy`
--

CREATE TABLE `grupy` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(50) NOT NULL,
  `Wychowawca` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `grupy`
--

INSERT INTO `grupy` (`id`, `nazwa`, `Wychowawca`) VALUES
(1, 'Smerfy', 2),
(2, 'Reksie', 3),
(3, 'Muminki', 3),
(4, 'Flinstonowie', 3);

-- --------------------------------------------------------

--
-- Table structure for table `jadlospis`
--

CREATE TABLE `jadlospis` (
  `id` int(11) NOT NULL,
  `kiedy` date NOT NULL,
  `typ` tinyint(4) NOT NULL COMMENT '0 - II sniadanie, 1 - obiad, 2 - podwieczorek',
  `opis` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `jadlospis`
--

INSERT INTO `jadlospis` (`id`, `kiedy`, `typ`, `opis`) VALUES
(1, '2025-11-24', 0, 'Kanapki z szynką drobiową, pomidorem i sałatą, herbata z cytryną'),
(2, '2025-11-24', 1, 'Zupa pomidorowa z ryżem. Filet z kurczaka w sosie potrawkowym, ziemniaki, mizeria'),
(3, '2025-11-24', 2, 'Banan i wafelek kukurydziany'),
(4, '2025-11-25', 0, 'Płatki kukurydziane na mleku, bułka wrocławska z masłem'),
(5, '2025-11-25', 1, 'Zupa ogórkowa z ziemniakami. Kotlet mielony wieprzowy, kasza jęczmienna, buraczki zasmażane'),
(6, '2025-11-25', 2, 'Kisiel wiśniowy ze startym jabłkiem'),
(7, '2025-11-26', 0, 'Bułka grahamek z pastą jajeczną ze szczypiorkiem, kakao'),
(8, '2025-11-26', 1, 'Rosół z makaronem nitki. Potrawka z indyka z warzywami, ryż parboiled, kompot'),
(9, '2025-11-26', 2, 'Jabłko i herbatniki maślane'),
(10, '2025-11-27', 0, 'Parówki z szynki na ciepło, chleb razowy, ketchup, kawa inka'),
(11, '2025-11-27', 1, 'Krupnik na wywarze jarzynowym. Naleśniki z serem białym i polewą truskawkową'),
(12, '2025-11-27', 2, 'Jogurt naturalny z granolą i miodem'),
(13, '2025-11-28', 0, 'Chleb żytni z serem żółtym i ogórkiem kiszonym, herbata owocowa'),
(14, '2025-11-28', 1, 'Zupa jarzynowa z brukselką. Ryba miruna w panierce, ziemniaki puree, surówka z kiszonej kapusty'),
(15, '2025-11-28', 2, 'Ciasto drożdżowe z kruszonką (wypiek własny) i mleko'),
(16, '2025-12-01', 0, 'Tosty z serem i szynką, pomidor koktajlowy, herbata malinowa'),
(17, '2025-12-01', 1, 'Zupa krem z brokułów z grzankami. Gulasz wieprzowy, kopytka, ogórek kiszony'),
(18, '2025-12-01', 2, 'Mandarynka i chrupki kukurydziane'),
(19, '2025-12-02', 0, 'Owsianka na mleku z rodzynkami i cynamonem, ciepła herbata'),
(20, '2025-12-02', 1, 'Zupa pieczarkowa z makaronem łazanki. Pulpety w sosie koperkowym, ziemniaki, marchewka z groszkiem'),
(21, '2025-12-02', 2, 'Galaretka truskawkowa z bitą śmietaną'),
(22, '2025-12-03', 0, 'Kanapki z twarożkiem, rzodkiewką i szczypiorkiem, kakao'),
(23, '2025-12-03', 1, 'Kapuśniak ze słodkiej kapusty. Spaghetti bolognese z mięsem wieprzowym i serem żółtym'),
(24, '2025-12-03', 2, 'Gruszka i paluszki'),
(25, '2025-12-04', 0, 'Jajecznica na maśle, chleb graham, pomidor, herbata z cytryną'),
(26, '2025-12-04', 1, 'Zupa fasolowa z majerankiem. Pierogi leniwe z masełkiem i bułką tartą, surówka z jabłka i marchwi'),
(27, '2025-12-04', 2, 'Mus owocowy w tubce'),
(28, '2025-12-05', 0, 'Bułka maślana z dżemem truskawkowym, mleko'),
(29, '2025-12-05', 1, 'Barszcz czerwony zabielany z ziemniakami. Paluszki rybne z pieca, ryż z warzywami, surówka z selera'),
(30, '2025-12-05', 2, 'Muffinka czekoladowa i cząstka pomarańczy'),
(31, '2025-12-08', 0, 'Bułka kajzerka z polędwicą sopocką i papryką czerwoną, herbata'),
(32, '2025-12-08', 1, 'Zupa grochowa z grzankami. Bitki schabowe w sosie własnym, kasza gryczana, ogórek konserwowy'),
(33, '2025-12-08', 2, 'Serek homogenizowany waniliowy'),
(34, '2025-12-09', 0, 'parówki z serem'),
(35, '2025-12-09', 1, 'Zupa kalafiorowa z koperkiem. Udko z kurczaka pieczone, ziemniaki, surówka z kapusty pekińskiej'),
(36, '2025-12-09', 2, 'Budyń czekoladowy z sokiem malinowym'),
(37, '2025-12-10', 0, 'Kanapki z pastą z cieciorki (hummus) i ogórkiem świeżym, inka'),
(38, '2025-12-10', 1, 'Zupa pomidorowa z makaronem świderki. Risotto z warzywami i kurczakiem, sos jogurtowy'),
(39, '2025-12-10', 2, 'Kiść winogron bezpestkowych'),
(40, '2025-12-11', 0, 'Kabanosy drobiowe, chleb razowy, ketchup, herbata miętowa'),
(41, '2025-12-11', 1, 'Zupa neapolitańska z serem. Placki ziemniaczane ze śmietaną i cukrem'),
(42, '2025-12-11', 2, 'Smoothie bananowo-truskawkowe'),
(43, '2025-12-12', 0, 'Rogalik z miodem i masłem, mleko ciepłe'),
(44, '2025-12-12', 1, 'Zupa szczawiowa z jajkiem. Ryba dorsz pieczona w ziołach, ziemniaki, surówka z marchewki i jabłka'),
(45, '2025-12-12', 2, 'Ciastka owsiane z żurawiną');

-- --------------------------------------------------------

--
-- Table structure for table `komunikaty`
--

CREATE TABLE `komunikaty` (
  `ID` int(11) NOT NULL,
  `tytul` varchar(1000) NOT NULL,
  `tresc` varchar(1000) NOT NULL,
  `data` date NOT NULL,
  `przynaleznosc` int(11) NOT NULL COMMENT '0 - ogolne, 1 - gr1, 2 - gr2, 3-gr3, 4-gr4'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `komunikaty`
--

INSERT INTO `komunikaty` (`ID`, `tytul`, `tresc`, `data`, `przynaleznosc`) VALUES
(29, 'Opłaty za żywienie', 'Drodzy Rodzice, przypominamy o konieczności uiszczenia opłaty za żywienie do 10-go dnia miesiąca.', '2025-11-02', 0),
(30, 'Ważne: Ospa wietrzna', 'Uwaga! W przedszkolu panuje ospa wietrzna. Prosimy o obserwację dzieci.', '2025-11-28', 0),
(31, 'Piknik Rodzinny', 'Zapraszamy serdecznie na Zimowy Kiermasz, który odbędzie się w ogrodzie przedszkolnym w sobotę o 11:00.', '2025-12-07', 0),
(32, 'Jesienna pogoda', 'W związku z deszczową pogodą prosimy, aby każde dziecko miało w szafce kalosze i pelerynę.', '2025-10-15', 0),
(33, 'Zdrowie dzieci', 'Przypominamy: prosimy nie przyprowadzać do przedszkola dzieci przeziębionych i z gorączką.', '2025-11-30', 0),
(34, 'Przerwa techniczna', 'W najbliższy piątek placówka będzie nieczynna z powodu prac technicznych w sieci wodociągowej.', '2025-12-01', 0),
(35, 'Podpisanie rzeczy', 'Grupa 1: Prosimy o podpisanie wszystkich smoczków i przytulanek przyniesionych do leżakowania.', '2025-09-05', 1),
(36, 'Artykuły higieniczne', 'Do rodziców Grupy 1: Kończą się zapasy chusteczek nawilżanych, prosimy o dostarczenie nowych paczek.', '2025-11-25', 1),
(37, 'Wyjście do parku', 'Grupa 2: Jutro idziemy na dłuższy spacer do parku, prosimy o wygodne obuwie.', '2025-10-10', 2),
(38, 'Materiały plastyczne', 'Rodzice Grupy 2: Zbieramy rolki po ręcznikach papierowych i kartony na zajęcia plastyczne.', '2025-11-18', 2),
(39, 'Mikołajki', 'Grupa 3: Prosimy, aby w dniu 6 grudnia dzieci przyszły ubrane na czerwono lub w czapkach Mikołaja.', '2025-11-29', 3),
(40, 'Zajęcia z rytmiki', 'Dla Grupy 3: W czwartek odbędą się zajęcia z rytmiki, prosimy o strój gimnastyczny w worku.', '2025-11-27', 3),
(44, 'mamdosc', 'backendowiec tego dziennika ma dosc.', '2025-12-02', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lekcje`
--

CREATE TABLE `lekcje` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `lekcje`
--

INSERT INTO `lekcje` (`id`, `nazwa`) VALUES
(1, 'Czas wolny / Zabawa'),
(2, 'Czytanie bajek / Nauka słów'),
(3, 'Plastyka (Rysowanie/Lepienie)'),
(4, 'Rytmika i Muzyka'),
(5, 'Język Angielski (Zabawa)'),
(6, 'Gimnastyka / Plac zabaw'),
(7, 'Leżakowanie / Odpoczynek');

-- --------------------------------------------------------

--
-- Table structure for table `oczekujace`
--

CREATE TABLE `oczekujace` (
  `ID` int(11) NOT NULL,
  `imieRodzica` varchar(50) NOT NULL,
  `nazwiskoRodzica` varchar(50) NOT NULL,
  `numerTelefonu` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `imieDziecka` varchar(50) NOT NULL,
  `nazwiskoDziecka` varchar(50) NOT NULL,
  `pesel` varchar(50) NOT NULL,
  `adres` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `oczekujace`
--

INSERT INTO `oczekujace` (`ID`, `imieRodzica`, `nazwiskoRodzica`, `numerTelefonu`, `email`, `imieDziecka`, `nazwiskoDziecka`, `pesel`, `adres`) VALUES
(29, 'Ja', 'Nie', '903241678', 'tajny@email.com', 'Maciek', 'to samo', '11111111111', 'Łódź, Harcerska 6/7');

-- --------------------------------------------------------

--
-- Table structure for table `plan_lekcji`
--

CREATE TABLE `plan_lekcji` (
  `id` int(11) NOT NULL,
  `grupaID` int(11) NOT NULL,
  `lekcjaID` int(11) NOT NULL,
  `day_of_week` tinyint(4) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `plan_lekcji`
--

INSERT INTO `plan_lekcji` (`id`, `grupaID`, `lekcjaID`, `day_of_week`, `start_time`, `end_time`) VALUES
(1, 1, 1, 1, '08:00:00', '09:00:00'),
(2, 1, 2, 1, '09:00:00', '10:00:00'),
(3, 1, 3, 1, '10:00:00', '11:00:00'),
(4, 1, 6, 1, '11:00:00', '12:00:00'),
(5, 1, 7, 1, '12:00:00', '14:00:00'),
(6, 1, 1, 1, '14:00:00', '17:00:00'),
(7, 2, 1, 1, '08:00:00', '09:00:00'),
(8, 2, 4, 1, '09:00:00', '10:00:00'),
(9, 2, 2, 1, '10:00:00', '11:00:00'),
(10, 2, 6, 1, '11:00:00', '12:00:00'),
(11, 2, 7, 1, '12:00:00', '14:00:00'),
(12, 2, 1, 1, '14:00:00', '17:00:00'),
(13, 3, 1, 1, '08:00:00', '09:00:00'),
(14, 3, 5, 1, '09:00:00', '10:00:00'),
(15, 3, 3, 1, '10:00:00', '11:00:00'),
(16, 3, 6, 1, '11:00:00', '12:00:00'),
(17, 3, 1, 1, '12:00:00', '14:00:00'),
(18, 3, 1, 1, '14:00:00', '17:00:00'),
(19, 4, 1, 1, '08:00:00', '09:00:00'),
(20, 4, 2, 1, '09:00:00', '10:00:00'),
(21, 4, 5, 1, '10:00:00', '11:00:00'),
(22, 4, 3, 1, '11:00:00', '12:00:00'),
(23, 4, 1, 1, '12:00:00', '17:00:00'),
(24, 1, 1, 2, '08:00:00', '09:00:00'),
(25, 1, 4, 2, '09:00:00', '10:00:00'),
(26, 1, 1, 2, '10:00:00', '11:00:00'),
(27, 1, 6, 2, '11:00:00', '12:00:00'),
(28, 1, 7, 2, '12:00:00', '14:00:00'),
(29, 1, 1, 2, '14:00:00', '17:00:00'),
(30, 2, 1, 2, '08:00:00', '09:00:00'),
(31, 2, 3, 2, '09:00:00', '10:00:00'),
(32, 2, 1, 2, '10:00:00', '11:00:00'),
(33, 2, 6, 2, '11:00:00', '12:00:00'),
(34, 2, 7, 2, '12:00:00', '14:00:00'),
(35, 2, 1, 2, '14:00:00', '17:00:00'),
(36, 3, 1, 2, '08:00:00', '09:00:00'),
(37, 3, 6, 2, '09:00:00', '10:00:00'),
(38, 3, 2, 2, '10:00:00', '11:00:00'),
(39, 3, 4, 2, '11:00:00', '12:00:00'),
(40, 3, 1, 2, '12:00:00', '17:00:00'),
(41, 4, 1, 2, '08:00:00', '09:00:00'),
(42, 4, 2, 2, '09:00:00', '10:00:00'),
(43, 4, 6, 2, '10:00:00', '11:00:00'),
(44, 4, 4, 2, '11:00:00', '12:00:00'),
(45, 4, 1, 2, '12:00:00', '17:00:00'),
(46, 1, 1, 3, '08:00:00', '09:00:00'),
(47, 1, 3, 3, '09:00:00', '10:00:00'),
(48, 1, 6, 3, '10:00:00', '11:00:00'),
(49, 1, 1, 3, '11:00:00', '12:00:00'),
(50, 1, 7, 3, '12:00:00', '14:00:00'),
(51, 1, 1, 3, '14:00:00', '17:00:00'),
(52, 2, 1, 3, '08:00:00', '09:00:00'),
(53, 2, 5, 3, '09:00:00', '10:00:00'),
(54, 2, 3, 3, '10:00:00', '11:00:00'),
(55, 2, 6, 3, '11:00:00', '12:00:00'),
(56, 2, 7, 3, '12:00:00', '14:00:00'),
(57, 2, 1, 3, '14:00:00', '17:00:00'),
(58, 3, 1, 3, '08:00:00', '09:00:00'),
(59, 3, 3, 3, '09:00:00', '11:00:00'),
(60, 3, 6, 3, '11:00:00', '12:00:00'),
(61, 3, 1, 3, '12:00:00', '17:00:00'),
(62, 4, 1, 3, '08:00:00', '09:00:00'),
(63, 4, 5, 3, '09:00:00', '10:00:00'),
(64, 4, 3, 3, '10:00:00', '12:00:00'),
(65, 4, 1, 3, '12:00:00', '17:00:00'),
(66, 1, 1, 4, '08:00:00', '09:00:00'),
(67, 1, 6, 4, '09:00:00', '10:00:00'),
(68, 1, 4, 4, '10:00:00', '11:00:00'),
(69, 1, 1, 4, '11:00:00', '12:00:00'),
(70, 1, 7, 4, '12:00:00', '14:00:00'),
(71, 1, 1, 4, '14:00:00', '17:00:00'),
(72, 2, 1, 4, '08:00:00', '09:00:00'),
(73, 2, 2, 4, '09:00:00', '10:00:00'),
(74, 2, 4, 4, '10:00:00', '11:00:00'),
(75, 2, 6, 4, '11:00:00', '12:00:00'),
(76, 2, 7, 4, '12:00:00', '14:00:00'),
(77, 2, 1, 4, '14:00:00', '17:00:00'),
(78, 3, 1, 4, '08:00:00', '09:00:00'),
(79, 3, 4, 4, '09:00:00', '10:00:00'),
(80, 3, 2, 4, '10:00:00', '11:00:00'),
(81, 3, 6, 4, '11:00:00', '12:00:00'),
(82, 3, 1, 4, '12:00:00', '17:00:00'),
(83, 4, 1, 4, '08:00:00', '09:00:00'),
(84, 4, 6, 4, '09:00:00', '10:00:00'),
(85, 4, 2, 4, '10:00:00', '11:00:00'),
(86, 4, 1, 4, '11:00:00', '17:00:00'),
(87, 1, 1, 5, '08:00:00', '09:00:00'),
(88, 1, 1, 5, '09:00:00', '11:00:00'),
(89, 1, 6, 5, '11:00:00', '12:00:00'),
(90, 1, 7, 5, '12:00:00', '14:00:00'),
(91, 1, 1, 5, '14:00:00', '17:00:00'),
(92, 2, 1, 5, '08:00:00', '09:00:00'),
(93, 2, 6, 5, '09:00:00', '10:00:00'),
(94, 2, 1, 5, '10:00:00', '12:00:00'),
(95, 2, 7, 5, '12:00:00', '14:00:00'),
(96, 2, 1, 5, '14:00:00', '17:00:00'),
(97, 3, 1, 5, '08:00:00', '09:00:00'),
(98, 3, 5, 5, '09:00:00', '10:00:00'),
(99, 3, 6, 5, '10:00:00', '12:00:00'),
(100, 3, 1, 5, '12:00:00', '17:00:00'),
(101, 4, 1, 5, '08:00:00', '09:00:00'),
(102, 4, 6, 5, '09:00:00', '11:00:00'),
(103, 4, 5, 5, '11:00:00', '12:00:00'),
(104, 4, 1, 5, '12:00:00', '17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `pracedomowe`
--

CREATE TABLE `pracedomowe` (
  `id` int(11) NOT NULL,
  `tresc` varchar(1000) NOT NULL,
  `grupa` int(11) NOT NULL,
  `data` date NOT NULL,
  `zrobione` tinyint(4) NOT NULL COMMENT '0 - nie, 1 - tak'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `pracedomowe`
--

INSERT INTO `pracedomowe` (`id`, `tresc`, `grupa`, `data`, `zrobione`) VALUES
(1, 'Prosimy o przyniesienie jednej białej skarpetki (będziemy robić bałwanka)', 1, '2025-12-02', 1),
(2, 'Przeczytanie dziecku bajki o zimie w ramach akcji \"Czytamy razem\"', 1, '2025-12-05', 1),
(3, 'Narysowanie w domu portretu Świętego Mikołaja (na konkurs plastyczny)', 2, '2025-12-03', 1),
(4, 'Spacer z rodzicami: obserwacja czy widać już pierwsze oznaki zimy', 2, '2025-12-07', 0),
(5, 'Przyniesienie rolki po ręczniku papierowym na zajęcia techniczne', 2, '2025-12-09', 0),
(6, 'Nauka wierszyka dla Mikołaja (tekst wklejony do zeszytu kontaktowego)', 3, '2025-12-04', 1),
(7, 'Wspólne wykonanie z rodzicami jednej ozdoby choinkowej z papieru', 3, '2025-12-08', 0),
(8, 'Uzupełnienie karty pracy: Szlaczki i litera \"M\" (str. 15)', 4, '2025-12-02', 1),
(9, 'Zadanie matematyczne: policzenie ile bombek/ozdób wisi na domowej choince (lub stoi na stroiku)', 4, '2025-12-06', 0),
(10, 'Przyniesienie stroju gimnastycznego na próby do Jasełek', 4, '2025-12-10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `ID` int(11) NOT NULL,
  `imie` varchar(50) NOT NULL,
  `nazwisko` varchar(50) NOT NULL,
  `typ` int(11) NOT NULL COMMENT '0 - rodzic, 1- nauczyciel, 2-dyrekcja',
  `numerTelefonu` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `haslo` varchar(257) NOT NULL,
  `firstLogin` tinyint(4) NOT NULL COMMENT '0 - nie, 1 - tak',
  `opinia` varchar(5000) DEFAULT NULL,
  `zdjecie` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`ID`, `imie`, `nazwisko`, `typ`, `numerTelefonu`, `login`, `haslo`, `firstLogin`, `opinia`, `zdjecie`) VALUES
(1, 'Jan', 'Kruk', 0, '123456789', 'jKruk@gmail.com', '$2y$10$V5DNoqC33NA5fe9CJ/QTMu7SSHWuKcPZfgl6GIaPtlA4hwGrwQWfq', 0, NULL, NULL),
(2, 'Stanisław', 'Odrowski', 1, '999999999', 'stasiu@outlook.com', '$2y$10$GklSuzP8xNagCDpk4IPUaOI2Aahwb9rFtCZoPiOOwv9u7wk0me8B6', 0, 'Bardzo fajny nauczyciel, ma świetne podejście do dzieci i potrafi stworzyć na lekcjach miłą atmosferę. Tłumaczy w sposób zrozumiały i zawsze stara się, żeby każdy wszystko dobrze zrozumiał. Widać, że lubi swoją pracę i zależy mu na uczniach.\n', 'stanislawOdrowski.jpg'),
(3, 'Jeremiasz', 'Michorczyk', 2, '666777888', 'jeremi@yahoo.com', '$2y$10$ZFmNZui9uCZRAkrpCsYTdOzpAM2BiRn1gHEnaC5M45ItwDdC.yclu', 0, 'Nauczyciel z pasją, potrafi zainteresować tematem i widać, że zależy mu na uczniach. Zawsze cierpliwie wszystko tłumaczy i tworzy przyjazną atmosferę na lekcjach.\n', 'jeremiaszMichorczyk.jpg'),
(4, 'Anna', 'Kowalska', 0, '501234567', 'anna.kowalska@poczta.pl', 'haslo123', 0, NULL, NULL),
(5, 'Piotr', 'Nowak', 0, '602345678', 'piotr.nowak@gmail.com', 'tajnehaslo', 0, NULL, NULL),
(6, 'Magdalena', 'Wiśniewska', 0, '793456789', 'magda.wisniewska@onet.pl', 'magda2024', 0, NULL, NULL),
(7, 'Tomasz', 'Zieliński', 0, '511000111', 'tomek.zielinski@wp.pl', 'qwertyuiop', 0, NULL, NULL),
(8, 'Katarzyna', 'Wójcik', 0, '698765432', 'kasia.wojcik@poczta.fm', 'rodzic1', 0, NULL, NULL),
(15, 'Jakub', 'Juźwicki', 0, '666777678', 'juzwik@zhp.pl', '$2y$10$ziAoEp1XqJOOM/xrsN0G7O9W20qikbc7dmQG6fHFzQ9D6SkxBQ7la', 0, NULL, NULL),
(21, 'Marcin', 'Symilak', 0, '191019101', 'mSymilak@pilka.pl', '$2y$10$xnKq49lr1AYi.5ws807NuuzJ2YSBdr8av9xXRDtYLG8PsZSKn.d9q', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wiadomosci`
--

CREATE TABLE `wiadomosci` (
  `id` int(11) NOT NULL,
  `tytul` varchar(256) NOT NULL,
  `tresc` varchar(10000) NOT NULL,
  `dataWyslania` date NOT NULL,
  `nadawcaID` int(11) NOT NULL,
  `odbiorcaID` int(11) NOT NULL,
  `odczytane` int(11) NOT NULL COMMENT '0 - nie, 1 - tak',
  `Usunięte` int(11) NOT NULL COMMENT '0 - nie, 1 - tak'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `wiadomosci`
--

INSERT INTO `wiadomosci` (`id`, `tytul`, `tresc`, `dataWyslania`, `nadawcaID`, `odbiorcaID`, `odczytane`, `Usunięte`) VALUES
(1, 'Pana syn to chuj', 'Pana syn to chuj', '0000-00-00', 2, 1, 0, 0),
(3, 'pana tez', 'pana tez', '2025-11-27', 1, 2, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artykuly`
--
ALTER TABLE `artykuly`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `dzieci`
--
ALTER TABLE `dzieci`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID Rodzica` (`IDRodzica`),
  ADD KEY `grupa` (`grupa`);

--
-- Indexes for table `grupy`
--
ALTER TABLE `grupy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Wychowawca` (`Wychowawca`);

--
-- Indexes for table `jadlospis`
--
ALTER TABLE `jadlospis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unikalny_posilek` (`kiedy`,`typ`);

--
-- Indexes for table `komunikaty`
--
ALTER TABLE `komunikaty`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `lekcje`
--
ALTER TABLE `lekcje`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oczekujace`
--
ALTER TABLE `oczekujace`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `plan_lekcji`
--
ALTER TABLE `plan_lekcji`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grupaID` (`grupaID`),
  ADD KEY `lekcjaID` (`lekcjaID`);

--
-- Indexes for table `pracedomowe`
--
ALTER TABLE `pracedomowe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grupa` (`grupa`);

--
-- Indexes for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `numerTelefonu` (`numerTelefonu`);

--
-- Indexes for table `wiadomosci`
--
ALTER TABLE `wiadomosci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `odbiorcaID` (`odbiorcaID`),
  ADD KEY `nadawcaID` (`nadawcaID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artykuly`
--
ALTER TABLE `artykuly`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `dzieci`
--
ALTER TABLE `dzieci`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `grupy`
--
ALTER TABLE `grupy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jadlospis`
--
ALTER TABLE `jadlospis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `komunikaty`
--
ALTER TABLE `komunikaty`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `oczekujace`
--
ALTER TABLE `oczekujace`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `plan_lekcji`
--
ALTER TABLE `plan_lekcji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `pracedomowe`
--
ALTER TABLE `pracedomowe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `wiadomosci`
--
ALTER TABLE `wiadomosci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dzieci`
--
ALTER TABLE `dzieci`
  ADD CONSTRAINT `dzieci_ibfk_1` FOREIGN KEY (`IDRodzica`) REFERENCES `uzytkownicy` (`ID`),
  ADD CONSTRAINT `dzieci_ibfk_2` FOREIGN KEY (`grupa`) REFERENCES `grupy` (`id`);

--
-- Constraints for table `grupy`
--
ALTER TABLE `grupy`
  ADD CONSTRAINT `grupy_ibfk_1` FOREIGN KEY (`Wychowawca`) REFERENCES `uzytkownicy` (`ID`);

--
-- Constraints for table `plan_lekcji`
--
ALTER TABLE `plan_lekcji`
  ADD CONSTRAINT `plan_lekcji_ibfk_1` FOREIGN KEY (`grupaID`) REFERENCES `grupy` (`id`),
  ADD CONSTRAINT `plan_lekcji_ibfk_2` FOREIGN KEY (`lekcjaID`) REFERENCES `lekcje` (`id`);

--
-- Constraints for table `pracedomowe`
--
ALTER TABLE `pracedomowe`
  ADD CONSTRAINT `pracedomowe_ibfk_1` FOREIGN KEY (`grupa`) REFERENCES `grupy` (`id`);

--
-- Constraints for table `wiadomosci`
--
ALTER TABLE `wiadomosci`
  ADD CONSTRAINT `wiadomosci_ibfk_1` FOREIGN KEY (`odbiorcaID`) REFERENCES `uzytkownicy` (`ID`),
  ADD CONSTRAINT `wiadomosci_ibfk_2` FOREIGN KEY (`nadawcaID`) REFERENCES `uzytkownicy` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
