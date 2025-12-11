-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2025 at 01:29 PM
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
  `IDRodzica` int(11) NOT NULL,
  `opinia` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `dzieci`
--

INSERT INTO `dzieci` (`ID`, `imie`, `nazwisko`, `pesel`, `adres`, `grupa`, `img`, `IDRodzica`, `opinia`) VALUES
(1, 'Jonaszek', 'Kruk', '20271912141', 'Łódź, ul. Sienkiewicza 6, m. 7', 2, 'Jonaszek_Kruk.png', 1, 'chujoza'),
(2, 'Aldona', 'Kruk', '20271912145', 'Łódź, ul. Sienkiewicza 6, m. 7', 1, 'Aldona_Kruk.png', 1, 'Dziecko systematycznie pracuje na zajęciach, sumiennie wywiązuje się z obowiązków i dąży do poprawy wyników.'),
(3, 'Zosia', 'Kowalska', '21231501234', 'Łódź, ul. Kwiatowa 5/10', 1, 'brak', 4, 'Uczeń prezentuje wysoką kulturę osobistą, szanuje kolegów i nauczycieli oraz dba o dobrą atmosferę w klasie.'),
(4, 'Jan', 'Kowalski', '18251209876', 'Łódź, ul. Kwiatowa 5/10', 4, 'brak', 4, 'Dziecko aktywnie uczestniczy w lekcjach, zadaje pytania i chętnie dzieli się swoimi spostrzeżeniami'),
(5, 'Krzyś', 'Nowak', '20252005432', 'Łódź, ul. Słoneczna 12', 2, 'brak', 5, 'Uczeń potrafi pracować zarówno samodzielnie, jak i w grupie, przejmuje odpowiedzialność za powierzone zadania.'),
(6, 'Ala', 'Wiśniewska', '19301011223', 'Łódź, ul. Lipowa 3', 3, 'brak', 6, 'Dziecko rozwija swoje mocne strony, wykazuje ciekawość świata i chętnie podejmuje nowe wyzwania edukacyjne.'),
(7, 'Olek', 'Wiśniewski', '21310533441', 'Łódź, ul. Lipowa 3', 1, 'brak', 6, 'Uczeń stosuje się do zasad panujących w klasie, reaguje na uwagi i stara się korygować swoje zachowanie.'),
(8, 'Filip', 'Wiśniewski', '18222855667', 'Łódź, ul. Lipowa 3', 4, 'brak', 6, 'Dziecko jest empatyczne, wspiera rówieśników i potrafi rozwiązywać drobne konflikty w spokojny sposób.'),
(9, 'Michał', 'Zieliński', '19260199887', 'Łódź, ul. Długa 50/4', 3, 'brak', 7, 'Uczeń dobrze organizuje swoją pracę, zazwyczaj przygotowuje się do zajęć i przynosi potrzebne materiały.'),
(18, 'Brajan', 'Symilak', '22230711738', 'Wojska Polskiego 19/10', 1, 'brak', 21, 'Dziecko robi zauważalne postępy, a jego wysiłek i systematyczność pozytywnie wpływają na osiągane wyniki.');

-- --------------------------------------------------------

--
-- Table structure for table `godzinylekcyjne`
--

CREATE TABLE `godzinylekcyjne` (
  `id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `godzinylekcyjne`
--

INSERT INTO `godzinylekcyjne` (`id`, `start_time`, `end_time`) VALUES
(1, '08:00:00', '09:00:00'),
(2, '09:00:00', '10:00:00'),
(3, '10:00:00', '11:00:00'),
(4, '11:00:00', '12:00:00'),
(5, '12:00:00', '14:00:00'),
(6, '14:00:00', '17:00:00');

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
  `przynaleznosc` int(11) NOT NULL COMMENT '0 - ogolne, 1 - gr1, 2 - gr2, 3-gr3, 4-gr4',
  `autor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `komunikaty`
--

INSERT INTO `komunikaty` (`ID`, `tytul`, `tresc`, `data`, `przynaleznosc`, `autor`) VALUES
(29, 'Opłaty za żywienie', 'Drodzy Rodzice, przypominamy o konieczności uiszczenia opłaty za żywienie do 10-go dnia miesiąca.', '2025-11-02', 0, 3),
(30, 'Ważne: Ospa wietrzna', 'Uwaga! W przedszkolu panuje ospa wietrzna. Prosimy o obserwację dzieci.', '2025-11-28', 0, 3),
(31, 'Piknik Rodzinny', 'Zapraszamy serdecznie na Zimowy Kiermasz, który odbędzie się w ogrodzie przedszkolnym w sobotę o 11:00.', '2025-12-07', 0, 3),
(32, 'Jesienna pogoda', 'W związku z deszczową pogodą prosimy, aby każde dziecko miało w szafce kalosze i pelerynę.', '2025-10-15', 0, 3),
(33, 'Zdrowie dzieci', 'Przypominamy: prosimy nie przyprowadzać do przedszkola dzieci przeziębionych i z gorączką.', '2025-11-30', 0, 3),
(34, 'Przerwa techniczna', 'W najbliższy piątek placówka będzie nieczynna z powodu prac technicznych w sieci wodociągowej.', '2025-12-01', 0, 3),
(35, 'Podpisanie rzeczy', 'Grupa 1: Prosimy o podpisanie wszystkich smoczków i przytulanek przyniesionych do leżakowania.', '2025-09-05', 1, 2),
(36, 'Artykuły higieniczne', 'Do rodziców Grupy 1: Kończą się zapasy chusteczek nawilżanych, prosimy o dostarczenie nowych paczek.', '2025-11-25', 1, 3),
(37, 'Wyjście do parku', 'Grupa 2: Jutro idziemy na dłuższy spacer do parku, prosimy o wygodne obuwie.', '2025-10-10', 2, 3),
(38, 'Materiały plastyczne', 'Rodzice Grupy 2: Zbieramy rolki po ręcznikach papierowych i kartony na zajęcia plastyczne.', '2025-11-18', 2, 3),
(39, 'Mikołajki', 'Grupa 3: Prosimy, aby w dniu 6 grudnia dzieci przyszły ubrane na czerwono lub w czapkach Mikołaja.', '2025-11-29', 3, 3),
(40, 'Zajęcia z rytmiki', 'Dla Grupy 3: W czwartek odbędą się zajęcia z rytmiki, prosimy o strój gimnastyczny w worku.', '2025-11-27', 3, 3),
(44, 'mamdosc', 'backendowiec tego dziennika ma dosc.', '2025-12-02', 0, 3);

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
(29, 'Ja', 'Nie', '903241678', 'tajny@email.com', 'Maciek', 'to samo', '11111111111', 'Łódź, Harcerska 6/7'),
(30, 'Jeremiasz', 'Michorczyk', '666777888', 'jeremi@yahoo.com', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `plan_lekcji`
--

CREATE TABLE `plan_lekcji` (
  `id` int(11) NOT NULL,
  `grupaID` int(11) NOT NULL,
  `lekcjaID` int(11) NOT NULL,
  `day_of_week` tinyint(4) NOT NULL,
  `godzinaLekcyjna` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `plan_lekcji`
--

INSERT INTO `plan_lekcji` (`id`, `grupaID`, `lekcjaID`, `day_of_week`, `godzinaLekcyjna`) VALUES
(105, 1, 1, 1, 1),
(106, 1, 2, 1, 2),
(107, 1, 3, 1, 3),
(108, 1, 6, 1, 4),
(109, 1, 7, 1, 5),
(110, 1, 1, 1, 6),
(111, 1, 1, 2, 1),
(112, 1, 4, 2, 2),
(113, 1, 5, 2, 3),
(114, 1, 1, 2, 4),
(115, 1, 7, 2, 5),
(116, 1, 1, 2, 6),
(117, 1, 1, 3, 1),
(118, 1, 3, 3, 2),
(119, 1, 2, 3, 3),
(120, 1, 4, 3, 4),
(121, 1, 7, 3, 5),
(122, 1, 1, 3, 6),
(123, 1, 1, 4, 1),
(124, 1, 5, 4, 2),
(125, 1, 6, 4, 3),
(126, 1, 3, 4, 4),
(127, 1, 7, 4, 5),
(128, 1, 1, 4, 6),
(129, 1, 1, 5, 1),
(130, 1, 4, 5, 2),
(131, 1, 1, 5, 3),
(132, 1, 2, 5, 4),
(133, 1, 7, 5, 5),
(134, 1, 6, 5, 6),
(135, 2, 1, 1, 1),
(136, 2, 3, 1, 2),
(137, 2, 4, 1, 3),
(138, 2, 5, 1, 4),
(139, 2, 7, 1, 5),
(140, 2, 1, 1, 6),
(141, 2, 1, 2, 1),
(142, 2, 6, 2, 2),
(143, 2, 2, 2, 3),
(144, 2, 3, 2, 4),
(145, 2, 7, 2, 5),
(146, 2, 1, 2, 6),
(147, 2, 1, 3, 1),
(148, 2, 5, 3, 2),
(149, 2, 1, 3, 3),
(150, 2, 6, 3, 4),
(151, 2, 7, 3, 5),
(152, 2, 1, 3, 6),
(153, 2, 1, 4, 1),
(154, 2, 4, 4, 2),
(155, 2, 3, 4, 3),
(156, 2, 2, 4, 4),
(157, 2, 7, 4, 5),
(158, 2, 1, 4, 6),
(159, 2, 1, 5, 1),
(160, 2, 2, 5, 2),
(161, 2, 5, 5, 3),
(162, 2, 1, 5, 4),
(163, 2, 7, 5, 5),
(164, 2, 6, 5, 6),
(165, 3, 1, 1, 1),
(166, 3, 5, 1, 2),
(167, 3, 6, 1, 3),
(168, 3, 2, 1, 4),
(169, 3, 7, 1, 5),
(170, 3, 1, 1, 6),
(171, 3, 1, 2, 1),
(172, 3, 3, 2, 2),
(173, 3, 4, 2, 3),
(174, 3, 5, 2, 4),
(175, 3, 7, 2, 5),
(176, 3, 1, 2, 6),
(177, 3, 1, 3, 1),
(178, 3, 6, 3, 2),
(179, 3, 1, 3, 3),
(180, 3, 3, 3, 4),
(181, 3, 7, 3, 5),
(182, 3, 1, 3, 6),
(183, 3, 1, 4, 1),
(184, 3, 2, 4, 2),
(185, 3, 5, 4, 3),
(186, 3, 4, 4, 4),
(187, 3, 7, 4, 5),
(188, 3, 1, 4, 6),
(189, 3, 1, 5, 1),
(190, 3, 3, 5, 2),
(191, 3, 2, 5, 3),
(192, 3, 6, 5, 4),
(193, 3, 7, 5, 5),
(194, 3, 6, 5, 6),
(195, 4, 1, 1, 1),
(196, 4, 6, 1, 2),
(197, 4, 5, 1, 3),
(198, 4, 3, 1, 4),
(199, 4, 7, 1, 5),
(200, 4, 1, 1, 6),
(201, 4, 1, 2, 1),
(202, 4, 2, 2, 2),
(203, 4, 1, 2, 3),
(204, 4, 4, 2, 4),
(205, 4, 7, 2, 5),
(206, 4, 1, 2, 6),
(207, 4, 1, 3, 1),
(208, 4, 4, 3, 2),
(209, 4, 3, 3, 3),
(210, 4, 5, 3, 4),
(211, 4, 7, 3, 5),
(212, 4, 1, 3, 6),
(213, 4, 1, 4, 1),
(214, 4, 6, 4, 2),
(215, 4, 4, 4, 3),
(216, 4, 2, 4, 4),
(217, 4, 7, 4, 5),
(218, 4, 1, 4, 6),
(219, 4, 1, 5, 1),
(220, 4, 5, 5, 2),
(221, 4, 6, 5, 3),
(222, 4, 3, 5, 4),
(223, 4, 7, 5, 5),
(224, 4, 6, 5, 6);

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
-- Table structure for table `uprawnienia`
--

CREATE TABLE `uprawnienia` (
  `ID` int(11) NOT NULL,
  `rodzic` int(11) NOT NULL,
  `nauczyciel` int(11) NOT NULL,
  `dyrektor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uprawnienia`
--

INSERT INTO `uprawnienia` (`ID`, `rodzic`, `nauczyciel`, `dyrektor`) VALUES
(1, 1, 0, 0),
(2, 0, 1, 0),
(3, 0, 1, 1),
(4, 1, 0, 0),
(5, 1, 0, 0),
(6, 1, 0, 0),
(7, 1, 0, 0),
(8, 1, 0, 0),
(9, 1, 0, 0),
(10, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `ID` int(11) NOT NULL,
  `imie` varchar(50) NOT NULL,
  `nazwisko` varchar(50) NOT NULL,
  `typ` int(100) NOT NULL COMMENT '0 - rodzic, 1- nauczyciel, 2-dyrekcja',
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
(1, 'Jan', 'Kruk', 1, '123456789', 'jKruk@gmail.com', '$2y$10$V5DNoqC33NA5fe9CJ/QTMu7SSHWuKcPZfgl6GIaPtlA4hwGrwQWfq', 0, NULL, NULL),
(2, 'Stanisław', 'Odrowski', 2, '999999999', 'stasiu@outlook.com', '$2y$10$GklSuzP8xNagCDpk4IPUaOI2Aahwb9rFtCZoPiOOwv9u7wk0me8B6', 0, 'Bardzo fajny nauczyciel, ma świetne podejście do dzieci i potrafi stworzyć na lekcjach miłą atmosferę. Tłumaczy w sposób zrozumiały i zawsze stara się, żeby każdy wszystko dobrze zrozumiał. Widać, że lubi swoją pracę i zależy mu na uczniach.\n', 'stanislawOdrowski.jpg'),
(3, 'Jeremiasz', 'Michorczyk', 3, '666777888', 'jeremi@yahoo.com', '$2y$10$ZFmNZui9uCZRAkrpCsYTdOzpAM2BiRn1gHEnaC5M45ItwDdC.yclu', 0, 'Nauczyciel z pasją, potrafi zainteresować tematem i widać, że zależy mu na uczniach. Zawsze cierpliwie wszystko tłumaczy i tworzy przyjazną atmosferę na lekcjach.\n', 'jeremiaszMichorczyk.jpg'),
(4, 'Anna', 'Kowalska', 4, '501234567', 'anna.kowalska@poczta.pl', 'haslo123', 0, NULL, NULL),
(5, 'Piotr', 'Nowak', 5, '602345678', 'piotr.nowak@gmail.com', 'tajnehaslo', 0, NULL, NULL),
(6, 'Magdalena', 'Wiśniewska', 6, '793456789', 'magda.wisniewska@onet.pl', 'magda2024', 0, NULL, NULL),
(7, 'Tomasz', 'Zieliński', 7, '511000111', 'tomek.zielinski@wp.pl', 'qwertyuiop', 0, NULL, NULL),
(8, 'Katarzyna', 'Wójcik', 8, '698765432', 'kasia.wojcik@poczta.fm', 'rodzic1', 0, NULL, NULL),
(15, 'Jakub', 'Juźwicki', 9, '666777678', 'juzwik@zhp.pl', '$2y$10$ziAoEp1XqJOOM/xrsN0G7O9W20qikbc7dmQG6fHFzQ9D6SkxBQ7la', 0, NULL, NULL),
(21, 'Marcin', 'Symilak', 10, '191019101', 'mSymilak@pilka.pl', '$2y$10$xnKq49lr1AYi.5ws807NuuzJ2YSBdr8av9xXRDtYLG8PsZSKn.d9q', 0, NULL, NULL);

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
-- Indexes for table `godzinylekcyjne`
--
ALTER TABLE `godzinylekcyjne`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`ID`),
  ADD KEY `autor` (`autor`);

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
  ADD KEY `lekcjaID` (`lekcjaID`),
  ADD KEY `godzinaLekcyjna` (`godzinaLekcyjna`);

--
-- Indexes for table `pracedomowe`
--
ALTER TABLE `pracedomowe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grupa` (`grupa`);

--
-- Indexes for table `uprawnienia`
--
ALTER TABLE `uprawnienia`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `numerTelefonu` (`numerTelefonu`),
  ADD KEY `typ` (`typ`);

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
-- AUTO_INCREMENT for table `godzinylekcyjne`
--
ALTER TABLE `godzinylekcyjne`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `oczekujace`
--
ALTER TABLE `oczekujace`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `plan_lekcji`
--
ALTER TABLE `plan_lekcji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=225;

--
-- AUTO_INCREMENT for table `pracedomowe`
--
ALTER TABLE `pracedomowe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `uprawnienia`
--
ALTER TABLE `uprawnienia`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- Constraints for table `komunikaty`
--
ALTER TABLE `komunikaty`
  ADD CONSTRAINT `komunikaty_ibfk_1` FOREIGN KEY (`autor`) REFERENCES `uzytkownicy` (`ID`);

--
-- Constraints for table `plan_lekcji`
--
ALTER TABLE `plan_lekcji`
  ADD CONSTRAINT `plan_lekcji_ibfk_1` FOREIGN KEY (`grupaID`) REFERENCES `grupy` (`id`),
  ADD CONSTRAINT `plan_lekcji_ibfk_2` FOREIGN KEY (`lekcjaID`) REFERENCES `lekcje` (`id`),
  ADD CONSTRAINT `plan_lekcji_ibfk_3` FOREIGN KEY (`godzinaLekcyjna`) REFERENCES `godzinylekcyjne` (`id`);

--
-- Constraints for table `pracedomowe`
--
ALTER TABLE `pracedomowe`
  ADD CONSTRAINT `pracedomowe_ibfk_1` FOREIGN KEY (`grupa`) REFERENCES `grupy` (`id`);

--
-- Constraints for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD CONSTRAINT `uzytkownicy_ibfk_1` FOREIGN KEY (`typ`) REFERENCES `uprawnienia` (`ID`);

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
