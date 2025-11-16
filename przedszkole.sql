-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2025 at 10:58 PM
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
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `artykuly`
--

INSERT INTO `artykuly` (`ID`, `naglowek`, `tresc`, `img`) VALUES
(1, 'Jesienna wycieczka do parku', 'W październiku nasze przedszkolaki wybrały się na kolorową wycieczkę do parku, gdzie obserwowały zmieniającą się przyrodę. Dzieci zbierały liście i bawiły się na świeżym powietrzu.', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb'),
(2, 'Dzień Pluszowego Misia', 'W naszym przedszkolu obchodziliśmy Dzień Pluszowego Misia. Dzieci przyniosły swoje ulubione maskotki, uczestniczyły w zabawach i konkursach.', 'https://images.unsplash.com/photo-1519125323398-675f0ddb6308'),
(3, 'Warsztaty kulinarne – robimy sałatkę owocową', 'Przedszkolaki własnoręcznie przygotowały zdrową i pyszną sałatkę owocową, ucząc się rozpoznawać różne owoce oraz dbając o higienę.', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836'),
(4, 'Teatrzyk kukiełkowy', 'Nauczyciele przygotowali przedstawienie kukiełkowe, które bardzo spodobało się dzieciom i zainspirowało je do własnej twórczości.', 'https://images.unsplash.com/photo-1464983953574-0892a716854b'),
(5, 'Bal karnawałowy', 'Przedszkolny bal karnawałowy dał dzieciom okazję do przebrania się w ulubione postacie, tańców oraz wspólnej zabawy.', 'https://images.unsplash.com/photo-1542727305-141b74791343');

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
  `grupa` varchar(20) DEFAULT NULL,
  `img` varchar(50) NOT NULL,
  `IDRodzica` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `dzieci`
--

INSERT INTO `dzieci` (`ID`, `imie`, `nazwisko`, `pesel`, `adres`, `grupa`, `img`, `IDRodzica`) VALUES
(1, 'Jonaszek', 'Kruk', '21241201290', 'Łódź, ul. Sienkiewicza 6, m. 7', 'I - jeżyki', 'Jonaszek_Kruk.png', 1),
(2, 'Aldona', 'Kruk', '20271912145', 'Łódź, ul. Sienkiewicza 6, m. 7', 'II - słoniki', 'Aldona_Kruk.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `oczekujace`
--

CREATE TABLE `oczekujace` (
  `imieRodzica` varchar(50) NOT NULL,
  `nazwiskoRodzica` varchar(50) NOT NULL,
  `numerTelefonu` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `imieDziecka` varchar(50) NOT NULL,
  `nazwiskoDziecka` varchar(50) NOT NULL,
  `pesel` varchar(50) NOT NULL,
  `adres` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

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
  `login` varchar(16) NOT NULL,
  `haslo` varchar(257) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`ID`, `imie`, `nazwisko`, `typ`, `numerTelefonu`, `login`, `haslo`) VALUES
(1, 'Jan', 'Kruk', 0, '123456789', 'jKruk@gmail.com', '$2y$10$V5DNoqC33NA5fe9CJ/QTMu7SSHWuKcPZfgl6GIaPtlA4hwGrwQWfq'),
(2, 'Stanisław', 'Odrowski', 1, '', '', ''),
(3, 'Jeremiasz', 'Michorczyk', 2, '', '', '');

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
  ADD KEY `ID Rodzica` (`IDRodzica`);

--
-- Indexes for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artykuly`
--
ALTER TABLE `artykuly`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dzieci`
--
ALTER TABLE `dzieci`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dzieci`
--
ALTER TABLE `dzieci`
  ADD CONSTRAINT `dzieci_ibfk_1` FOREIGN KEY (`IDRodzica`) REFERENCES `uzytkownicy` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
