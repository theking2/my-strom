-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Gegenereerd op: 25 jul 2019 om 00:35
-- Serverversie: 5.5.57-MariaDB
-- PHP-versie: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myStrom`
--
CREATE DATABASE IF NOT EXISTS `myStrom` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `myStrom`;

-- --------------------------------------------------------

--
-- Stand-in structuur voor view `hourMeasurement`
-- (Zie onder voor de actuele view)
--
CREATE TABLE `hourMeasurement` (
`dt` datetime
,`SwitchName` varchar(32)
,`State` decimal(7,4)
,`Power` double(18,1)
,`Temp` double(18,1)
,`maxTemp` double(18,1)
,`minTemp` double(18,1)
);


-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Measurement`
--

CREATE TABLE `Measurement` (
  `MeasurementID` int(11) NOT NULL,
  `TimeStamp` int(11) NOT NULL,
  `IPAddr` int(10) UNSIGNED NOT NULL,
  `SwitchName` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `State` tinyint(4) NOT NULL,
  `Power` float NOT NULL,
  `Temperature` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `Measurement`
--

INSERT INTO `Measurement` (`MeasurementID`, `TimeStamp`, `IPAddr`, `SwitchName`, `State`, `Power`, `Temperature`) VALUES
(106683, 1552210994, 3232263946, 'Slaapkamer', 0, 0, 14.9375);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Room`
--

CREATE TABLE `Room` (
  `RoomID` int(11) NOT NULL,
  `RoomName` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `Room`
--

INSERT INTO `Room` (`RoomID`, `RoomName`) VALUES
(1, 'Woonkamer'),
(2, 'Study'),
(3, 'Kitchen'),
(4, 'Flur');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Switch`
--

CREATE TABLE `Switch` (
  `SwitchID` int(11) UNSIGNED NOT NULL,
  `SwitchName` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `IPAddr` int(11) UNSIGNED NOT NULL,
  `RoomID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `Switch`
--

INSERT INTO `Switch` (`SwitchID`, `SwitchName`, `IPAddr`, `RoomID`) VALUES
(1, 'Schemerlamp', 3232263944, 1),
(2, 'Mediacenter', 3232263945, 1),
(3, 'Koffiemachien', 3232263943, 3),
(4, 'Study', 3232263946, 4);

-- --------------------------------------------------------

--
-- Stand-in structuur voor view `vwSwitch`
-- (Zie onder voor de actuele view)
--
CREATE TABLE `vwSwitch` (
`SwitchID` int(11) unsigned
,`SwitchName` varchar(32)
,`IPAddres` varchar(87)
,`RoomID` int(11)
);

-- --------------------------------------------------------

--
-- Structuur voor de view `hourMeasurement`
--
DROP TABLE IF EXISTS `hourMeasurement`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `hourMeasurement`  AS  select from_unixtime((3600 * round((`Measurement`.`TimeStamp` / 3600),0))) AS `dt`,`Measurement`.`SwitchName` AS `SwitchName`,avg(`Measurement`.`State`) AS `State`,round(avg(`Measurement`.`Power`),1) AS `Power`,round(avg(`Measurement`.`Temperature`),1) AS `Temp`,round(max(`Measurement`.`Temperature`),1) AS `maxTemp`,round(min(`Measurement`.`Temperature`),1) AS `minTemp` from `Measurement` group by from_unixtime((3600 * round((`Measurement`.`TimeStamp` / 3600),0))),`Measurement`.`SwitchName` order by from_unixtime((3600 * round((`Measurement`.`TimeStamp` / 3600),0))) desc ;

-- --------------------------------------------------------

--
-- Structuur voor de view `vwSwitch`
--
DROP TABLE IF EXISTS `vwSwitch`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vwSwitch`  AS  select `Switch`.`SwitchID` AS `SwitchID`,`Switch`.`SwitchName` AS `SwitchName`,concat_ws('.',((`Switch`.`IPAddr` >> 24) & 0xff),((`Switch`.`IPAddr` >> 16) & 0xff),((`Switch`.`IPAddr` >> 8) & 0xff),(`Switch`.`IPAddr` & 0xff)) AS `IPAddres`,`Switch`.`RoomID` AS `RoomID` from `Switch` ;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `Measurement`
--
ALTER TABLE `Measurement`
  ADD PRIMARY KEY (`MeasurementID`);

--
-- Indexen voor tabel `Room`
--
ALTER TABLE `Room`
  ADD PRIMARY KEY (`RoomID`);

--
-- Indexen voor tabel `Switch`
--
ALTER TABLE `Switch`
  ADD PRIMARY KEY (`SwitchID`),
  ADD UNIQUE KEY `IPAddr` (`IPAddr`),
  ADD UNIQUE KEY `SwitchName` (`SwitchName`),
  ADD UNIQUE KEY `SwitchID` (`SwitchID`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `Measurement`
--
ALTER TABLE `Measurement`
  MODIFY `MeasurementID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106684;

--
-- AUTO_INCREMENT voor een tabel `Room`
--
ALTER TABLE `Room`
  MODIFY `RoomID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `Switch`
--
ALTER TABLE `Switch`
  MODIFY `SwitchID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
