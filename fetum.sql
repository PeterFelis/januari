-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Gegenereerd op: 12 mei 2025 om 13:58
-- Serverversie: 8.0.30
-- PHP-versie: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fetum`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `crm_notes`
--

CREATE TABLE `crm_notes` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `datum` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `opmerking` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klanten`
--

CREATE TABLE `klanten` (
  `id` int NOT NULL,
  `naam` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `straat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nummer` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `postcode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `plaats` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `extra_veld` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `algemeen_telefoonnummer` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `algemene_email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `factuur_email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `factuur_extra_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `factuur_straat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `factuur_nummer` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `factuur_postcode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `factuur_plaats` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `aflever_straat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `aflever_nummer` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `aflever_postcode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `aflever_plaats` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `aangemaakt_op` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `land` varchar(50) NOT NULL DEFAULT 'Nederland'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `klanten`
--

INSERT INTO `klanten` (`id`, `naam`, `straat`, `nummer`, `postcode`, `plaats`, `extra_veld`, `algemeen_telefoonnummer`, `algemene_email`, `website`, `factuur_email`, `factuur_extra_info`, `factuur_straat`, `factuur_nummer`, `factuur_postcode`, `factuur_plaats`, `aflever_straat`, `aflever_nummer`, `aflever_postcode`, `aflever_plaats`, `aangemaakt_op`, `land`) VALUES
(2, 'Fetum Company', 'Hoofdstraat', '1', '1234AB', 'Amsterdam', NULL, NULL, NULL, 'http://fetum.nl', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-15 14:54:53', 'Nederland'),
(7, 'Jansschool', 'Couperuslaan', '56', '1422BE', 'Uithoorn', 'om de hoek bezorgen', '0125', 'verkoop@fetum.nl', 'http://www.fetum.nl', '', '', '', '', '', '', '', '', '', '', '2025-02-28 13:42:24', 'Nederland');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `contactpersoon_id` int NOT NULL,
  `datum` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('offerte','bestelling') DEFAULT NULL,
  `totaal_prijs` decimal(10,2) DEFAULT '0.00',
  `afgehandeld` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `orders`
--

INSERT INTO `orders` (`id`, `klant_id`, `contactpersoon_id`, `datum`, `status`, `totaal_prijs`, `afgehandeld`) VALUES
(35, 7, 8, '2025-03-04 21:30:33', 'bestelling', 616.20, 0),
(36, 7, 8, '2025-03-05 19:36:20', 'bestelling', 970.78, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `aantal` int NOT NULL,
  `prijs_per_stuk` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `aantal`, `prijs_per_stuk`) VALUES
(34, 35, 3, 128, 3.92),
(35, 36, 3, 96, 4.05),
(36, 36, 2, 200, 2.03);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `categorie` varchar(100) DEFAULT NULL,
  `subcategorie` varchar(100) DEFAULT NULL,
  `TypeNummer` varchar(100) NOT NULL,
  `omschrijving` text,
  `prijsstaffel` text NOT NULL,
  `aantal_per_doos` int NOT NULL,
  `aangemaakt_op` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `USP` text,
  `sticker_text` text,
  `leverbaar` enum('ja','nee') NOT NULL DEFAULT 'ja',
  `hoofd_product` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `products`
--

INSERT INTO `products` (`id`, `categorie`, `subcategorie`, `TypeNummer`, `omschrijving`, `prijsstaffel`, `aantal_per_doos`, `aangemaakt_op`, `USP`, `sticker_text`, `leverbaar`, `hoofd_product`) VALUES
(1, 'Hoofdtelefoons', 'Degelijk', 'HP-136 S', '<p><strong>Onze klassieke en betrouwbare HP-136</strong></p><p><br></p><p>Ontdek de vernieuwde HP-136, nu uitgerust met een afneembaar snoer, een handige bewaartas en een naamsticker, zodat altijd duidelijk is welke hoofdtelefoon van wie is.</p><p><br></p><p><strong>Comfort en design:</strong></p><p>De hoofdtelefoon heeft een metalen hoofdband bekleed met stof en verstelbare, zacht beklede oorschelpen. Het maximale volume is begrensd op 85 dB, wat veilig gebruik garandeert. De hoofdtelefoon zit zeer comfortabel en is geschikt voor urenlang probleemloos gebruik.</p><p><br></p><p><strong>Verpakking en accessoires:</strong></p><p>Per stuk verpakt in een zakje, of per 32 stuks in een overdoos.</p><p>Enkelzijdig afneembaar en vervangbaar, met een lengte van 1,2 meter.</p><p><br></p><p><strong>Type: </strong>Halfopen</p><p><br></p>', '32 7,86\n64 7,64\n128 7,24\n256 6.95', 32, '2024-12-27 15:59:45', '<p>Afneembaar snoer</p><p>Verstelbare oorschelpen</p><p>Nylon verstevigd snoer</p><p>Inclusief naamsticker</p>', '<p>Afneembaar 1,2 meter snoer,</p><p>Met naam sticker en bewaartas</p><p>Zachte hoofdband</p><p>Verstelbare zachte oorschelpen</p><p>max volume 85dB </p>', 'ja', NULL),
(2, 'Hoofdtelefoons', 'Comfort', 'HP-305', '<h3>Comfortabele hoofdtelefoon voor een scherpe prijs</h3><h3><br></h3><p><strong>Eenzijdig snoer</strong> van 2 meter – raakt minder snel in de knoop en wordt niet snel in de mond genomen.</p><p><strong>3,5 mm rechte stekker</strong> – past altijd.</p><p><strong>Zachte oorschelpen en verstelbare hoofdband</strong> – voor een prettige pasvorm.</p><p><strong>Opklapbare oorschelpen</strong> – handig bij het opbergen.</p><p><br></p><p>Optioneel verkrijgbaar met een <strong>gerecyclede denim bewaartas</strong>, waarop een naam geschreven kan worden. Voorkomt dat het snoer in de knoop raakt.</p><p><br></p><p><strong>Verpakking:</strong></p><p>Per stuk in een zakje</p><p>Per 50 in een doos</p><p>Overdoos van 100 stuks</p><p><br></p><p><strong><span class=\"ql-cursor\">﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿</span></strong>Zonder blisterverpakking – compact en eenvoudig op te slaan.</p>', '50 2,29\n100 2,09\n200 2,03', 50, '2024-12-27 16:01:05', '<p>Uitstekende prijs-kwaliteitSverhouding</p><p>Lang, enkelzijdig snoer</p><p>Verstelbare hoofdband – past altijd comfortabel</p><p>Zachte, opklapbare oorschelpen</p>', '<p><strong>Comfort hoofdtelefoon</strong></p><p>2 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', ''),
(3, 'Hoofdtelefoons', 'Nekband', 'HP-122', '<p><strong>Nekband Koptelefoon</strong></p><p><br></p><p><u>Specificaties:</u></p><p>Snoer:&nbsp;Robuust nylon, beschermd tegen beschadiging.</p><p>Elk hygiënisch verpakt in een nette opbergtas.</p><p>Minimalistische opslag zonder overbodig afval: Geen blister of extra doos.</p><p><br></p><p>Aantal: 32 stuks per doos.</p><p>Certificeringen:&nbsp;De koptelefoon is CE-gecertificeerd en voldoet aan zowel de RoHS als de Reach standaard.</p><p>Snoerlengte:&nbsp;1,8 meter, voorzien van een standaard 3,5 mm rechte stereo stekker.</p><p><br></p><p><u>Bijzonder geschikt voor</u>:</p><p>Scholen. De nekband koptelefoon is uitermate geschikt voor jongere kinderen dankzij zijn comfortabele pasvorm en duurzaamheid.</p><p><br></p><p>Uitstekende hoofdtelefoon voor school gebruik.</p><p><br></p>', '32 4,31\n64 4,05\n128 3,92', 32, '2025-01-06 13:26:28', '<p>Nekband uitvoering</p><p>Nylon verstevigd snoer</p><p>1,8 meter snoer</p><p>In bewaartas</p>', '<p>Nekband koptelefoon</p><p>1,8 meter snoer</p><p>degelijk</p>', 'ja', ''),
(4, 'Hoofdtelefoons', 'Comfort', 'HP-316', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '50 3,39\n100 3,22\n200 3,02', 50, '2025-02-03 14:32:21', '<p>Ultrasound Hoofdtelefoon</p><p>Beste geluidskwaliteit in deze klasse</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', ''),
(5, 'Hoofdtelefoons', 'Budget', 'HP-2706', '<p>Budget hoofdtelefoon</p><p><br></p><p><br></p>', '1', 125, '2025-02-03 14:32:21', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', 'ja', ''),
(6, 'Hoofdtelefoons', 'Budget', 'HP-2710', '<p>Betaalbare kleurrijke koptelefoon.</p><p><br></p><p><br></p><p>Uitermate geschikt voor:</p><p><br></p><p>Scholen en bibliotheken: een budgetvriendelijke, hygiënische oplossing voor leerlingen. Geniet van een rijke luisterervaring in musea of verbeter het onderwijs met onze koptelefoons.</p><p><br></p><p>Ziekenhuizen: waar deze hoofdtelefoons naadloos integreren met patiënten entertainment systeem. Biedt extra comfort en bewegingsvrijheid dankzij de 1,8 meter lange kabel.</p><p><br></p><p>Revalidatiecentra: waar patiënten vaak bedlegerig zijn, biedt de lange kabel van de HP-2710 minder belasting op de televisie of computer aansluiting en meer bewegingsvrijheid.</p><p><br></p><p>Elke doos bevat 125 hoofdtelefoons in een gelijke verdeling van vier vrolijke kleuren.</p><p><br></p><p>De HP-2710 wordt alleen per doos verkocht, wat kostenefficiënt is.</p><p>De hoofdtelefoons zitten per stuk in een zakje verpakt, dus geen overbodig afval en nodeloos kostenverhogend blister.</p><p><br></p><p>Specs:</p><p>Verstelbare oorschelpen</p><p>Verstelbare kunststof hoofdband</p><p>Hygienisch per stuk in een zakje verpakt.</p><p>Weinig opslag en geen overbodig afval. (Geen blister of doosje).</p><p>125 stuks per doos.</p><p><br></p><p>Per overdoos: 31 rode, 31 groene, 31 blauwe en 32 zwarte.</p><p><br></p><p>De hoofdtelefoon is CE gekeurd en voldoet aan de RoHS en Reach standaard.</p><p>1,8 meter snoer met standaard 3,5 mm rechte stereo stekker.</p><p><br></p>', '24 2,29\n125 1,35\n250 1,25 ', 125, '2025-02-03 14:40:37', '<p>Budget koptelefoon</p><p>4 vrolijke kleuren</p><p>Geen bliser - geen afval</p>', '<p>Budget hoofdtelefoon</p><p>4 verschillende vrolijke kleuren</p><p>rood - wit - blauw en zwart</p>', 'ja', ''),
(7, 'Oortjes', 'In doosje', 'HP-32', '<p><strong>Comfortabel en veilig in gebruik</strong></p><p><br></p><p>Dit oortje wordt geleverd in een stevige bewaardoos en heeft een volumebegrenzing op 84 dB, zodat het gehoor beschermd blijft. </p><p>Dankzij de drie maten eartips zit het altijd perfect.</p><p><br></p><p><strong>Slim verpakt &amp; handig gelabeld</strong></p><p>Per stuk verpakt in een zakje en geleverd per 50 in een overdoos (geen blister). </p><p>Elke set bevat een beschrijfbare naamsticker, zodat altijd duidelijk is van wie het oortje is – ideaal voor scholen, bedrijven of gedeeld gebruik.</p><p><br></p><p>Verkrijgbaar in verschillende kleuren.</p><p>Prijs per doos van 50 stuks, inclusief naamstickers.</p>', '50 1,65\n100 1,52\n200 1,39', 50, '2025-02-03 14:40:37', '<p>Oortje in opbergdoosje</p><p>1 meter snoer</p><p>Met 3 maten eartips</p><p>Met beschrijfbare naamsticker</p>', '<p>Oortje in opbergdoosje</p><p>1 meter snoer</p><p>Met 3 maten eartips</p><p><strong>Met beschrijfbare naamsticker</strong></p>', 'ja', ''),
(15, 'Oortjes', 'Hoesje', 'i-900 Z', '<p><br></p>', '1', 192, '2025-02-10 12:29:10', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', NULL),
(24, 'Hoofdtelefoons', 'Budget', 'HP-2707', '<p>Budget hoofdtelefoon</p><p>3 meter snoer</p><p><br></p><p><br></p>', '1', 125, '2025-02-26 13:57:21', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', 'ja', ''),
(28, 'Hoofdtelefoons', 'Degelijk', 'HP-88', '<p><strong>Onze klassieke en betrouwbare HP-136</strong></p><p><br></p><p>Ontdek de vernieuwde HP-136, nu uitgerust met een afneembaar snoer, een handige bewaartas en een naamsticker, zodat altijd duidelijk is welke hoofdtelefoon van wie is.</p><p><br></p><p><strong>Comfort en design:</strong></p><p>De hoofdtelefoon heeft een metalen hoofdband bekleed met stof en verstelbare, zacht beklede oorschelpen. Het maximale volume is begrensd op 85 dB, wat veilig gebruik garandeert. De hoofdtelefoon zit zeer comfortabel en is geschikt voor urenlang probleemloos gebruik.</p><p><br></p><p><strong>Verpakking en accessoires:</strong></p><p>Per stuk verpakt in een zakje, of per 32 stuks in een overdoos.</p><p>Enkelzijdig afneembaar en vervangbaar, met een lengte van 1,2 meter.</p><p><br></p><p><strong>Type: </strong>Halfopen</p><p><br></p>', '32 7,86\n64 7,64\n128 7,24\n256 6.95', 32, '2025-02-26 14:19:52', '<p>Afneembaar snoer</p><p>Verstelbare oorschelpen</p><p>Nylon verstevigd snoer</p><p>Inclusief naamsticker</p>', '<p>Afneembaar 1,2 meter snoer,</p><p>Met naam sticker en bewaartas</p><p>Zachte hoofdband</p><p>Verstelbare zachte oorschelpen</p><p>max volume 85dB </p>', 'ja', NULL),
(29, 'Hoofdtelefoons', 'Budget', 'HP-2705', '<p>Budget hoofdtelefoon</p><p>3 meter snoer</p><p><br></p><p><br></p>', '1', 125, '2025-02-26 14:25:11', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', 'ja', ''),
(30, 'Hoofdtelefoons', 'Comfort', 'HP-112', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '1', 50, '2025-02-26 14:25:55', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', NULL),
(31, 'Hoofdtelefoons', 'Degelijk', 'HP-136 N', '<p><strong>Onze klassieke en betrouwbare HP-136</strong></p><p><br></p><p>Ontdek de vernieuwde HP-136, nu uitgerust met een afneembaar snoer, een handige bewaartas en een naamsticker, zodat altijd duidelijk is welke hoofdtelefoon van wie is.</p><p><br></p><p><strong>Comfort en design:</strong></p><p>De hoofdtelefoon heeft een metalen hoofdband bekleed met stof en verstelbare, zacht beklede oorschelpen. Het maximale volume is begrensd op 85 dB, wat veilig gebruik garandeert. De hoofdtelefoon zit zeer comfortabel en is geschikt voor urenlang probleemloos gebruik.</p><p><br></p><p><strong>Verpakking en accessoires:</strong></p><p>Per stuk verpakt in een zakje, of per 32 stuks in een overdoos.</p><p>Enkelzijdig afneembaar en vervangbaar, met een lengte van 1,2 meter.</p><p><br></p><p><strong>Type: </strong>Halfopen</p><p><br></p>', '32 7,86\n64 7,64\n128 7,24\n256 6.95', 32, '2025-02-26 14:26:24', '<p>Afneembaar snoer</p><p>Verstelbare oorschelpen</p><p>Nylon verstevigd snoer</p><p>Inclusief naamsticker</p>', '<p>Afneembaar 1,2 meter snoer,</p><p>Met naam sticker en bewaartas</p><p>Zachte hoofdband</p><p>Verstelbare zachte oorschelpen</p><p>max volume 85dB </p>', 'ja', NULL),
(32, 'Oortjes', 'Comfort', 'i-900', '<p><br></p>', '1', 192, '2025-02-26 14:27:12', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', NULL),
(34, 'Oortjes', 'Comfort', 'i-40', '<p><br></p>', '1', 192, '2025-02-26 14:28:23', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', NULL),
(35, 'Oortjes', 'Hoesje', 'i-40 Z', '<p><br></p>', '1', 192, '2025-02-26 14:28:49', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', NULL),
(36, 'Oortjes', 'Budget', 'HP-30', '<p>Budget witte oortjes per stuk verpakt in een zakje.</p><p><br></p><p>1 meter snoer, rechte stereo stekker.</p><p>Deze oortjes zijn niet voorzien van een microfoon</p><p><br></p><p>Maximale volumebegrenzing om oorschade te voorkomen</p><p><br></p><p>Oor leverbaar met bewaar pouch</p><p><br></p>', '50 1,87\n100 1,73\n200 1,62', 50, '2025-02-26 14:29:47', '<p>Budget oortje</p><p>1 meter snoer</p><p>Witte uitvoering</p><p>In hersluitbaar zakje</p>', '<p>Witte oortjes</p><p>Rechte stekker</p><p>kleur: wit</p>', 'ja', ''),
(37, 'Oortjes', 'Hoesje', 'HP-30 Z', '<p><br></p>', '1', 192, '2025-02-26 14:30:33', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', ''),
(39, 'USB Oortjes', 'Budget', 'Ear-USB 3', '<p>anda</p>', '1', 192, '2025-02-26 14:34:32', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', NULL),
(40, 'USB Oortjes', 'Comfort', 'Ear-USB 1', '<p>techancy</p>', '1', 192, '2025-02-26 14:35:17', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', NULL),
(41, 'USB Oortjes', 'Budget', 'Ear-USB 3-KOPIE', '<p>anda</p>', '1', 192, '2025-02-26 14:37:31', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', NULL),
(42, 'Splitters', 'budget', 'Splitter zwart', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '1', 50, '2025-02-26 14:38:18', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', NULL),
(44, 'Splitters', 'budget', 'Splitter wit', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '1', 50, '2025-02-26 14:39:43', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', NULL),
(45, 'Adapters', 'budget', 'USB A > C', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '1', 50, '2025-02-26 14:40:04', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', NULL),
(46, 'Adapters', 'budget', 'USB C > A', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '1', 50, '2025-02-26 14:40:40', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', NULL),
(48, 'Hoofdtelefoons', 'Comfort met zakje', 'HP-305Z', '<p>De HP-305 met gerycycled en beschrijfbare opbergtas</p><p><br></p>', '50 3,29\n100 3,09\n200 3,03', 50, '2025-02-27 12:32:34', '<p>Uitstekende prijs-kwaliteitSverhouding</p><p>Lang, enkelzijdig snoer</p><p>Verstelbare hoofdband – past altijd comfortabel</p><p>Zachte, opklapbare oorschelpen</p>', '<p><strong>Comfort hoofdtelefoon</strong></p><p>2 meter enkel zijdig snoer</p><p><u>Met bewaartas</u></p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', 'HP-305'),
(49, 'Oortjes', 'Budget met hoesje', 'HP-30 Pouch', '<p>Dit is de HP-30 met een syleconen (onverwoestbare) zwarte bewaar pouch</p><p><br></p>', '1', 50, '2025-02-28 12:50:24', '<p>Budget oortje</p><p>met onverwoestbare bewaar pouch</p>', '<p>Witte oortjes</p><p>Rechte stekker</p><p>kleur: wit</p>', 'ja', 'HP-30'),
(50, 'Hoofdtelefoons', 'Comfort met zakje', 'HP-316Z', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '150 3,89\n100 3,72\n200 3,52', 50, '2025-03-05 16:07:04', '<p>Met gerycycled bewaarzakje</p><p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', ''),
(51, 'Hoofdtelefoons', 'Kinder', 'Ki 100 blauw', '<p>Blauwe hoofdtelefoon met een vrolijke afbeelding.</p><p> </p><p>De hoofdtelefoon heeft 1 meter enkelzijdig snoer en een compacte rechte plug.</p><p>Het snoer is nylon verstevigd en gaat lang mee.</p><p><br></p><p>Voorzien van 85dB volumebegrenzing om ook bij langdurig gebruik oren niet te beschadigen.</p><p><br></p><p>De hoofdtelefoon is CE-gekeurd en voldoet aan de RoHS- en REACH standaard</p><p>1,8 meter snoer met een standaard 3,5 mm rechte stereostekker</p><p><br></p><p>Per 12 in een overdoos</p>', '1', 12, '2025-05-10 13:04:49', '<p>Kinder hoofdtelefoon</p><p>Blauwe uitvoering</p><p>Nylon verstevigd snoer</p><p>Max volume begrenzing</p>', '<p>Kinder hoofdtelefoon</p><p>Blauwe uitvoering</p><p>Nylon verstevigd snoer</p>', 'ja', ''),
(52, 'Hoofdtelefoons', 'Kinder', 'Ki 100 wit', '<p>Witte hoofdtelefoon met een vrolijke afbeelding.</p><p> </p><p>De hoofdtelefoon heeft 1 meter enkelzijdig snoer en een compacte rechte plug.</p><p>Het snoer is nylon verstevigd en gaat lang mee.</p><p><br></p><p>Voorzien van 85dB volumebegrenzing om ook bij langdurig gebruik oren niet te beschadigen.</p><p><br></p><p>De hoofdtelefoon is CE-gekeurd en voldoet aan de RoHS- en REACH standaard</p><p>1,8 meter snoer met een standaard 3,5 mm rechte stereostekker</p><p><br></p><p>Per 12 in een overdoos</p>', '1', 12, '2025-05-10 13:08:05', '<p>Kinder hoofdtelefoon</p><p>Witte uitvoering</p><p>Nylon verstevigd snoer</p><p>Max volume begrenzing</p>', '<p>Kinder hoofdtelefoon</p><p>Blauwe uitvoering</p><p>Nylon verstevigd snoer</p>', 'ja', '');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `key_name` varchar(50) NOT NULL,
  `value_text` text NOT NULL,
  `aangemaakt_op` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `TypeNummer` varchar(50) NOT NULL,
  `productType` varchar(50) NOT NULL,
  `aantal` int NOT NULL,
  `prijs_per_stuk` decimal(10,2) NOT NULL,
  `totaal_prijs` decimal(10,2) NOT NULL,
  `prijsstaffel` text NOT NULL,
  `aantal_per_doos` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `voornaam` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `achternaam` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `geslacht` enum('M','V','X') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `wachtwoord` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rol` enum('klant','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'klant',
  `google_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `avatar_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `klant_id` int DEFAULT NULL,
  `aangemaakt_op` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `email_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `confirmation_token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password_reset_token` varchar(32) DEFAULT NULL,
  `password_reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `voornaam`, `achternaam`, `geslacht`, `email`, `wachtwoord`, `rol`, `google_id`, `avatar_url`, `klant_id`, `aangemaakt_op`, `email_confirmed`, `confirmation_token`, `password_reset_token`, `password_reset_expires`) VALUES
(3, 'Peter', 'Felis', 'M', 'peter@felis.nl', '$2y$10$4u59LfnT4hChV/.HJNQjq./.iW1shzGwQ.Wj/rJ16e2sJtTWLPGXi', 'admin', NULL, NULL, 2, '2025-02-15 14:54:53', 1, NULL, NULL, NULL),
(8, 'Peter', 'Felis', 'M', 'verkoop@fetum.nl', '$2y$10$Uvb112KetqKJ8KHqzhab/.Q.qQgaOBgwpcnPYLfR1G/AaXQZgejKy', 'klant', NULL, NULL, 7, '2025-02-28 13:42:24', 1, NULL, NULL, NULL);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `crm_notes`
--
ALTER TABLE `crm_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klant_id` (`klant_id`);

--
-- Indexen voor tabel `klanten`
--
ALTER TABLE `klanten`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klant_id` (`klant_id`),
  ADD KEY `contactpersoon_id` (`contactpersoon_id`);

--
-- Indexen voor tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexen voor tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `klant_id` (`klant_id`,`TypeNummer`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_google_id` (`google_id`),
  ADD KEY `idx_klant_id` (`klant_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `crm_notes`
--
ALTER TABLE `crm_notes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `klanten`
--
ALTER TABLE `klanten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT voor een tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT voor een tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT voor een tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `crm_notes`
--
ALTER TABLE `crm_notes`
  ADD CONSTRAINT `crm_notes_ibfk_1` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`);

--
-- Beperkingen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`contactpersoon_id`) REFERENCES `users` (`id`);

--
-- Beperkingen voor tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Beperkingen voor tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_klanten` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
