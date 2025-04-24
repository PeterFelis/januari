-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 24 apr 2025 om 18:22
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
  `naam` varchar(100) NOT NULL,
  `straat` varchar(100) NOT NULL,
  `nummer` varchar(20) NOT NULL,
  `postcode` varchar(20) NOT NULL,
  `plaats` varchar(100) NOT NULL,
  `extra_veld` text,
  `algemeen_telefoonnummer` varchar(20) DEFAULT NULL,
  `algemene_email` varchar(150) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `factuur_email` varchar(150) DEFAULT NULL,
  `factuur_extra_info` text,
  `factuur_straat` varchar(100) DEFAULT NULL,
  `factuur_nummer` varchar(20) DEFAULT NULL,
  `factuur_postcode` varchar(20) DEFAULT NULL,
  `factuur_plaats` varchar(100) DEFAULT NULL,
  `aflever_straat` varchar(100) DEFAULT NULL,
  `aflever_nummer` varchar(20) DEFAULT NULL,
  `aflever_postcode` varchar(20) DEFAULT NULL,
  `aflever_plaats` varchar(100) DEFAULT NULL,
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
(35, 7, 8, '2025-03-04 21:30:33', 'bestelling', 616.20, 1),
(36, 7, 8, '2025-03-05 19:36:20', 'bestelling', 970.78, 1);

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
  `hoofd_product` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `products`
--

INSERT INTO `products` (`id`, `categorie`, `subcategorie`, `TypeNummer`, `omschrijving`, `prijsstaffel`, `aantal_per_doos`, `aangemaakt_op`, `USP`, `sticker_text`, `leverbaar`, `hoofd_product`) VALUES
(1, 'Hoofdtelefoons', 'Degelijk', 'HP-136 S', '<p><strong>Onze klassieke en betrouwbare HP-136</strong></p><p><br></p><p>Ontdek de vernieuwde HP-136, nu uitgerust met een afneembaar snoer, een handige bewaartas en een naamsticker, zodat altijd duidelijk is welke hoofdtelefoon van wie is.</p><p><br></p><p><strong>Comfort en design:</strong></p><p>De hoofdtelefoon heeft een metalen hoofdband bekleed met stof en verstelbare, zacht beklede oorschelpen. Het maximale volume is begrensd op 85 dB, wat veilig gebruik garandeert. De hoofdtelefoon zit zeer comfortabel en is geschikt voor urenlang probleemloos gebruik.</p><p><br></p><p><strong>Verpakking en accessoires:</strong></p><p>Per stuk verpakt in een zakje, of per 32 stuks in een overdoos.</p><p>Enkelzijdig afneembaar en vervangbaar, met een lengte van 1,2 meter.</p><p><br></p><p><strong>Type: </strong>Halfopen</p><p><br></p>', '32 7,86\n64 7,64\n128 7,24\n256 6.95', 32, '2024-12-27 15:59:45', '<p>Afneembaar snoer</p><p>Verstelbare oorschelpen</p><p>Nylon verstevigd snoer</p><p>Inclusief naamsticker</p>', '<p>Afneembaar 1,2 meter snoer,</p><p>Met naam sticker en bewaartas</p><p>Zachte hoofdband</p><p>Verstelbare zachte oorschelpen</p><p>max volume 85dB </p>', 'ja', ''),
(2, 'Hoofdtelefoons', 'Comfort', 'HP-305', '<h3>Comfortabele hoofdtelefoon voor een scherpe prijs</h3><h3><br></h3><p><strong>Eenzijdig snoer</strong> van 2 meter – raakt minder snel in de knoop en wordt niet snel in de mond genomen.</p><p><strong>3,5 mm rechte stekker</strong> – past altijd.</p><p><strong>Zachte oorschelpen en verstelbare hoofdband</strong> – voor een prettige pasvorm.</p><p><strong>Opklapbare oorschelpen</strong> – handig bij het opbergen.</p><p><br></p><p>Optioneel verkrijgbaar met een <strong>gerecyclede denim bewaartas</strong>, waarop een naam geschreven kan worden. Voorkomt dat het snoer in de knoop raakt.</p><p><br></p><p><strong>Verpakking:</strong></p><p>Per stuk in een zakje</p><p>Per 50 in een doos</p><p>Overdoos van 100 stuks</p><p><br></p><p><strong><span class=\"ql-cursor\">﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿</span></strong>Zonder blisterverpakking – compact en eenvoudig op te slaan.</p>', '50 2,29\n100 2,09\n200 2,03', 50, '2024-12-27 16:01:05', '<p>Uitstekende</p><p>prijs-kwaliteitsverhouding</p><p>Lang, enkelzijdig snoer</p><p>Verstelbare hoofdband</p><p>past altijd comfortabel</p><p>Zachte, opklapbare oorschelpen</p>', '<p><strong>Comfort hoofdtelefoon</strong></p><p>2 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', ''),
(3, 'Hoofdtelefoons', 'Nekband', 'HP-122', '<p><strong>Nekband Koptelefoon</strong></p><p><br></p><p><u>Specificaties:</u></p><p>Snoer:&nbsp;Robuust nylon, beschermd tegen beschadiging.</p><p>Elk hygiënisch verpakt in een nette opbergtas.</p><p>Minimalistische opslag zonder overbodig afval: Geen blister of extra doos.</p><p><br></p><p>Aantal: 32 stuks per doos.</p><p>Certificeringen:&nbsp;De koptelefoon is CE-gecertificeerd en voldoet aan zowel de RoHS als de Reach standaard.</p><p>Snoerlengte:&nbsp;1,8 meter, voorzien van een standaard 3,5 mm rechte stereo stekker.</p><p><br></p><p><u>Bijzonder geschikt voor</u>:</p><p>Scholen. De nekband koptelefoon is uitermate geschikt voor jongere kinderen dankzij zijn comfortabele pasvorm en duurzaamheid.</p><p><br></p><p>Uitstekende hoofdtelefoon voor school gebruik.</p><p><br></p>', '32 4,31\n64 4,05\n128 3,92', 32, '2025-01-06 13:26:28', '<p>Nekband uitvoering</p><p>Nylon verstevigd snoer</p><p>1,8 meter snoer</p><p>In bewaartas</p>', '<p>Nekband koptelefoon</p><p>1,8 meter snoer</p><p>degelijk</p>', 'ja', NULL),
(4, 'Hoofdtelefoons', 'Comfort', 'HP-316', '<p>Ultrasound Hoofdtelefoon (zo genoemd door onze duitse partner)</p><p>1,8 meter enkelzijdig snoer met rechte stekker</p><p><br></p><p>Met glans de beste geluidskwaliteit in deze klasse.</p><p><br></p><p>Draaibare zachter oorschelpen</p><p>Verstelbare hoofdband.</p><p><br></p><p>Per 50 in een doos</p><p>Per 100 in overdoos</p><p><br></p>', '50 3,39\n100 3,22\n200 3,02', 50, '2025-02-03 14:32:21', '<p>Ultrasound Hoofdtelefoon</p><p>Beste geluidskwaliteit</p><p>in deze klasse</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', ''),
(5, 'Hoofdtelefoons', 'Budget', 'HP-2706', '<p>Budget hoofdtelefoon</p><p><br></p><p>De meest basis hoofdtelefoon die we verkopen.</p><p>Met twee meter snoer een verstelbare hoofdband en automatisch stellende oorschelpen.</p><p><br></p><p>Het snoer is voorzien van een kleine, rechte stereo stekker.</p><p>Deze hoofdtelefoon heeft geen microfoon</p><p><br></p><p>Oorschelpen voorzien van een zachte comfortabele spons.</p><p><br></p><p>Per stuk verpakt in eenzakje, per 125 in een doos en per 250 in een overdoos</p><p><br></p><p>uitstekend geschikt voor scholen, ziekenhuizen, musea, rondvaarten, bibliotheken en revalidatiecentra.</p><p><br></p><p><br></p><p><br></p>', '125 1,15\n250 1,10\n500 1,00', 125, '2025-02-03 14:32:21', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p><p>zachte oorschelpen</p>', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', 'ja', ''),
(6, 'Hoofdtelefoons', 'Budget', 'HP-2710', '<p>Betaalbare kleurrijke koptelefoon.</p><p><br></p><p><br></p><p>Uitermate geschikt voor:</p><p><br></p><p>Scholen en bibliotheken: een budgetvriendelijke, hygiënische oplossing voor leerlingen. Geniet van een rijke luisterervaring in musea of verbeter het onderwijs met onze koptelefoons.</p><p><br></p><p>Ziekenhuizen: waar deze hoofdtelefoons naadloos integreren met patiënten entertainment systeem. Biedt extra comfort en bewegingsvrijheid dankzij de 1,8 meter lange kabel.</p><p><br></p><p>Revalidatiecentra: waar patiënten vaak bedlegerig zijn, biedt de lange kabel van de HP-2710 minder belasting op de televisie of computer aansluiting en meer bewegingsvrijheid.</p><p><br></p><p>Elke doos bevat 125 hoofdtelefoons in een gelijke verdeling van vier vrolijke kleuren.</p><p><br></p><p>De HP-2710 wordt alleen per doos verkocht, wat kostenefficiënt is.</p><p>De hoofdtelefoons zitten per stuk in een zakje verpakt, dus geen overbodig afval en nodeloos kostenverhogend blister.</p><p><br></p><p>Specs:</p><p>Verstelbare oorschelpen</p><p>Verstelbare kunststof hoofdband</p><p>Hygienisch per stuk in een zakje verpakt.</p><p>Weinig opslag en geen overbodig afval. (Geen blister of doosje).</p><p>125 stuks per doos.</p><p><br></p><p>Per overdoos: 31 rode, 31 groene, 31 blauwe en 32 zwarte.</p><p><br></p><p>De hoofdtelefoon is CE gekeurd en voldoet aan de RoHS en Reach standaard.</p><p>1,8 meter snoer met standaard 3,5 mm rechte stereo stekker.</p><p><br></p>', '125 1,35\n250 1,25 \n500 1,15', 125, '2025-02-03 14:40:37', '<p>Budget koptelefoon</p><p>4 vrolijke kleuren</p><p>Geen bliser - geen afval</p>', '<p>Budget hoofdtelefoon</p><p>4 verschillende vrolijke kleuren</p><p>rood - groen - blauw en zwart</p>', 'ja', ''),
(7, 'Oortjes', 'In doosje', 'HP-32', '<p><strong>Comfortabel en veilig in gebruik</strong></p><p><br></p><p>Dit oortje wordt geleverd in een stevige bewaardoos en heeft een volumebegrenzing op 84 dB, zodat het gehoor beschermd blijft. </p><p>Dankzij de drie maten eartips zit het altijd perfect.</p><p><br></p><p><strong>Slim verpakt &amp; handig gelabeld</strong></p><p>Per stuk verpakt in een zakje en geleverd per 50 in een overdoos (geen blister). </p><p>Elke set bevat een beschrijfbare naamsticker, zodat altijd duidelijk is van wie het oortje is – ideaal voor scholen, bedrijven of gedeeld gebruik.</p><p><br></p><p>Verkrijgbaar in verschillende kleuren.</p><p>Prijs per doos van 50 stuks, inclusief naamstickers.</p>', '50 1,65\n100 1,52\n200 1,39', 50, '2025-02-03 14:40:37', '<p>Oortje in opbergdoosje</p><p>1 meter snoer</p><p>Met 3 maten eartips</p><p>Met beschrijfbare naamsticker</p>', '<p>Oortje in opbergdoosje</p><p>1 meter snoer</p><p>Met 3 maten eartips</p><p><strong>Met beschrijfbare naamsticker</strong></p>', 'ja', ''),
(15, 'Oortjes', 'Hoesje', 'i-900 Z', '<p><br></p>', '1', 192, '2025-02-10 12:29:10', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', NULL),
(24, 'Hoofdtelefoons', 'Budget', 'HP-2707', '<p>Budget hoofdtelefoon</p><p>3 meter snoer</p><p><br></p><p><br></p>', '1', 125, '2025-02-26 13:57:21', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', 'nee', ''),
(28, 'Hoofdtelefoons', 'Degelijk', 'HP-88', '<p><strong>Onze klassieke en betrouwbare HP-136</strong></p><p><br></p><p>Ontdek de vernieuwde HP-136, nu uitgerust met een afneembaar snoer, een handige bewaartas en een naamsticker, zodat altijd duidelijk is welke hoofdtelefoon van wie is.</p><p><br></p><p><strong>Comfort en design:</strong></p><p>De hoofdtelefoon heeft een metalen hoofdband bekleed met stof en verstelbare, zacht beklede oorschelpen. Het maximale volume is begrensd op 85 dB, wat veilig gebruik garandeert. De hoofdtelefoon zit zeer comfortabel en is geschikt voor urenlang probleemloos gebruik.</p><p><br></p><p><strong>Verpakking en accessoires:</strong></p><p>Per stuk verpakt in een zakje, of per 32 stuks in een overdoos.</p><p>Enkelzijdig afneembaar en vervangbaar, met een lengte van 1,2 meter.</p><p><br></p><p><strong>Type: </strong>Halfopen</p><p><br></p>', '32 7,86\n64 7,64\n128 7,24\n256 6.95', 32, '2025-02-26 14:19:52', '<p>Afneembaar snoer</p><p>Verstelbare oorschelpen</p><p>Nylon verstevigd snoer</p><p>Inclusief naamsticker</p>', '<p>Afneembaar 1,2 meter snoer,</p><p>Met naam sticker en bewaartas</p><p>Zachte hoofdband</p><p>Verstelbare zachte oorschelpen</p><p>max volume 85dB </p>', 'ja', ''),
(29, 'Hoofdtelefoons', 'Budget', 'HP-2705', '<p>Budget hoofdtelefoon</p><p>3 meter snoer</p><p><br></p><p><br></p>', '1', 125, '2025-02-26 14:25:11', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', 'nee', ''),
(30, 'Hoofdtelefoons', 'Comfort', 'HP-112', '<p><strong>Comfortabele hoofdtelefoon voor relaxed luisteren</strong></p><p><br></p><p>1 meter snoer met haakse stekker</p><p>Metalen hoofdbeugel</p><p>Verstelbare hoogte, dus goed aan te passen</p><p><br></p><p>Grote halfopen oorschelpen zorgen voor goed geluid maar geen volledige onttrekking aan de omgeving</p><p><br></p><p>Per 32 stuks in een doos</p>', '32 4,31\n64 4,05\n128 3,92', 50, '2025-02-26 14:25:55', '<p>Metalen hoofdband</p><p>Grote halfopen oorschelpen</p><p>1 meter snoer</p>', '<p>Metalen hoofdband</p><p>Grote halfopen oorschelpen</p><p>1 meter snoer</p><p><br></p>', 'ja', ''),
(31, 'Hoofdtelefoons', 'Degelijk', 'HP-136', '<p><strong>Onze klassieke en betrouwbare HP-136</strong></p><p><br></p><p>Met vast 1,2 meter snoer</p><p><br></p><p><strong>Comfort en design:</strong></p><p>De hoofdtelefoon heeft een metalen hoofdband bekleed met stof en verstelbare, zacht beklede oorschelpen. Het maximale volume is begrensd op 85 dB, wat veilig gebruik garandeert. De hoofdtelefoon zit zeer comfortabel en is geschikt voor urenlang probleemloos gebruik.</p><p><br></p><p><strong>Verpakking en accessoires:</strong></p><p>Per stuk verpakt in een zakje, of per 32 stuks in een overdoos.</p><p>Enkelzijdig afneembaar en vervangbaar, met een lengte van 1,2 meter.</p><p><br></p><p><strong>Type: </strong>Halfopen</p><p><br></p>', '32 7,86\n64 7,64\n128 7,24\n256 6.95', 32, '2025-02-26 14:26:24', '<p>Vast snoer</p><p>Verstelbare oorschelpen</p><p>Nylon verstevigd snoer</p><p>Inclusief naamsticker</p>', '<p>1,2 meter snoer</p><p>Zachte hoofdband</p><p>Verstelbare zachte oorschelpen</p><p>max volume 85dB </p>', 'ja', ''),
(32, 'Oortjes', 'Comfort', 'i-900', '<p><br></p>', '1', 192, '2025-02-26 14:27:12', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', NULL),
(34, 'Oortjes', 'Comfort', 'i-40', '<p><br></p>', '1', 192, '2025-02-26 14:28:23', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', NULL),
(35, 'Oortjes', 'Hoesje', 'i-40 Z', '<p><br></p>', '1', 192, '2025-02-26 14:28:49', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', NULL),
(36, 'Oortjes', 'Budget', 'HP-30', '<p>Budget witte oortjes per stuk verpakt in een zakje.</p><p><br></p><p>1 meter snoer, rechte stereo stekker.</p><p>Deze oortjes zijn niet voorzien van een microfoon</p><p><br></p><p>Maximale volumebegrenzing om oorschade te voorkomen</p><p><br></p><p>Oor leverbaar met bewaar pouch</p><p><br></p>', '50 1,87\n100 1,73\n200 1,62', 50, '2025-02-26 14:29:47', '<p>Budget oortje</p><p>1 meter snoer</p><p>Witte uitvoering</p><p>In hersluitbaar zakje</p>', '<p>Witte oortjes</p><p>Rechte stekker</p><p>kleur: wit</p>', 'ja', ''),
(37, 'Oortjes', 'Hoesje', 'HP-30 Z', '<p><br></p>', '1', 192, '2025-02-26 14:30:33', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', ''),
(39, 'USB Oortjes', 'Budget', 'Ear-USB 3', '<p>anda</p>', '1', 192, '2025-02-26 14:34:32', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', NULL),
(40, 'USB Oortjes', 'Comfort', 'Ear-USB 1', '<p>techancy</p>', '1', 192, '2025-02-26 14:35:17', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', ''),
(41, 'USB Oortjes', 'Budget', 'Ear-USB 3-KOPIE', '<p>anda</p>', '1', 192, '2025-02-26 14:37:31', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', NULL),
(42, 'Splitters', 'budget', 'HoofdSplit zwart', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '1', 50, '2025-02-26 14:38:18', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', ''),
(44, 'Splitters', 'budget', 'HoofdSplit wit', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '1', 50, '2025-02-26 14:39:43', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', ''),
(45, 'Adapters', 'budget', 'USB A > C', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '1', 50, '2025-02-26 14:40:04', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', NULL),
(46, 'Adapters', 'budget', 'USB C > A', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '1', 50, '2025-02-26 14:40:40', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', NULL),
(48, 'Hoofdtelefoons', 'Comfort met zakje', 'HP-305Z', '<p>De HP-305 met gerecyclede en beschrijfbare opbergtas.</p><p><br></p><p>Verder helemaal gelijk aan de HP-305.</p><p><br></p>', '50 3,29\n100 3,09\n200 3,03', 50, '2025-02-27 12:32:34', '<p>Uitstekende prijs-kwaliteitSverhouding</p><p>Lang, enkelzijdig snoer</p><p>Verstelbare hoofdband – past altijd comfortabel</p><p>Zachte, opklapbare oorschelpen</p>', '<p><strong>Comfort hoofdtelefoon</strong></p><p>2 meter enkel zijdig snoer</p><p><u>Met bewaartas</u></p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', 'HP-305'),
(49, 'Oortjes', 'Budget met hoesje', 'HP-30 Pouch', '<p>Dit is de HP-30 met een siliconen (haast onverwoestbare) zwarte bewaar-pouch.</p><p><br></p>', '50 1,87\n100 1,73\n200 1,62', 50, '2025-02-28 12:50:24', '<p>Budget oortje</p><p>met (haast) onverwoestbare bewaar pouch</p>', '<p>Witte oortjes</p><p>Rechte stekker</p><p>kleur: wit</p>', 'ja', 'HP-30'),
(50, 'Hoofdtelefoons', 'Comfort met zakje', 'HP-316Z', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '150 3,89\n100 3,72\n200 3,52', 50, '2025-03-05 16:07:04', '<p>Met gerycycled bewaarzakje</p><p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', 'HP-316'),
(51, 'Batterijen', 'AA', 'Bat AA', '<p><br></p>', '', 48, '2025-03-06 11:58:34', '<p>Alkaline batterij</p><p>AA</p>', '<p>Alkaline batterij</p><p>AA</p>', 'ja', ''),
(53, 'Batterijen', 'AAA', 'Bat AAA', '<p><br></p>', '', 48, '2025-03-06 11:59:21', '<p>Alkaline batterij</p><p>AAA</p>', '<p>Alkaline batterij</p><p>AAA</p>', 'ja', ''),
(54, 'Soundcard', 'USB', 'Sound USB', '<p><br></p>', '', 5, '2025-03-06 12:00:19', '<p>USB soundcard</p><p>Te gebruiken bij verstopte koptelefoon aansluiting</p><p>plug en play</p><p>Apple/chromebook/windows</p>', '<p>USB soundcard</p><p>Te gebruiken bij verstopte koptelefoon aansluiting</p><p>plug en play</p><p>Apple/chromebook/windows</p>', 'ja', ''),
(57, 'Hoofdtelefoons', 'Budget met zakje', 'HP-2710Z', '<p>Onze HP-2710 maar nut met een gerycyled stoffen bewaarzakje.</p><p><br></p><p>Zakje is afsluitbaar en beschrijfbaar en zorgt ervoor dat de hoofdtelefoon netjes opgeruimd kan worden.</p>', '125 1,95\n250 1,85 \n500 1,75', 125, '2025-03-06 15:14:55', '<p>Budget koptelefoon</p><p>4 vrolijke kleuren</p><p>Geen bliser - geen afval</p><p>met opruimzakje</p>', '<p>Budget hoofdtelefoon</p><p>4 verschillende vrolijke kleuren</p><p>rood - groen - blauw en zwart</p><p>met opruimzakje</p>', 'ja', 'HP-2710'),
(58, 'Hoofdtelefoons', 'Budget met zakje', 'HP-2706Z', '<p>Onze HP-2706 maar nut met een gerycyled stoffen bewaarzakje.</p><p><br></p><p>Zakje is afsluitbaar en beschrijfbaar en zorgt ervoor dat de hoofdtelefoon netjes opgeruimd kan worden.</p><p><br></p>', '125 1,95\n250 1,80\n500 1,70', 125, '2025-03-06 15:21:13', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p><p>Met bewaarzakjes</p>', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', 'ja', 'HP-2706'),
(59, 'Hoofdtelefoons', 'Comfort met zakje', 'HP-112Z', '<p>De HP-112 met gerecyclede en beschrijfbare opbergtas.</p><p>Verder helemaal gelijk aan de HP-112.</p>', '32 4,91\n64 4,65\n128 4,52', 50, '2025-03-07 15:07:47', '<p>Met beschijfbare bewaar tast</p><p>Metalen hoofdband</p><p>Grote halfopen oorschelpen</p><p>1 meter snoer</p>', '<p>Metalen hoofdband</p><p>Grote halfopen oorschelpen</p><p>1 meter snoer</p><p><br></p>', 'ja', 'HP-112'),
(60, 'Hoofdtelefoons', 'Bluetooth', 'BT-7', '<p><strong>BT hoofdtelefoon</strong></p>', '32 7,86\n64 7,64\n128 7,24\n256 6.95', 5, '2025-03-13 14:04:53', '<p>Vast snoer</p><p>Verstelbare oorschelpen</p><p>Nylon verstevigd snoer</p><p>Inclusief naamsticker</p>', '<p>Witte Bluetooth Koptelefoon</p><p>Opklapbaar</p><p>Degelijk</p>', 'ja', ''),
(61, 'Hoofdtelefoons', 'Kids', 'Ki 100 wit', '<h3>Speciale kinderhoofdtelefoon</h3><p>Met volume begrenzing</p><p>En voorzien van leuke cartoon bedrukking</p><p><br></p><p>De volume begrenzing houdt het maximale volume altijd op een veilig niveau voor kinderen, maximaal 85 db.</p><p><br></p><p>Comfortabel te dragen hoofdtelefoon met zachte oorschelpen en verstelbare hoofdband.</p><p><br></p><p>Enkelzijdig 1,2 meter snoer met een kleine rechte stereo stekker.</p><p><br></p><p>Geschikt voor telefoons, chromebooks, ipads (met hoofdtelefoon aansluiting) </p><p><br></p><p>Dit is de witte uitvoering, ook leverbaar in blauw.</p><p><br></p><p><br></p><p><br></p><p><br></p>', '8 6,98\n16 6,42\n24 5,90', 8, '2025-03-21 14:04:18', '<p>Kids hoofdtelefoon</p><p>Maximale volume begrenzing</p><p>Lang comfortabel te dragen</p><p>Leverbaar in wit en blauw</p>', '<p><strong>Kids hoofdtelefoon</strong></p><p>Witte uitvoering</p><p>Enkelzijdig snoer</p><p>Verstelbare hoofdband</p>', 'ja', ''),
(62, 'Hoofdtelefoons', 'Kids', 'Ki 100 blauw', '<h3>Speciale kinderhoofdtelefoon</h3><p>Met volume begrenzing </p><p>En voorzien van leuke cartoon bedrukking</p><p><br></p><p>De volume begrenzing houdt het maximale volume altijd op een veilig niveau voor kinderen, maximaal 85 db.</p><p><br></p><p>Comfortabel te dragen hoofdtelefoon met zachte oorschelpen en verstelbare hoofdband.</p><p><br></p><p>Enkelzijdig 1,2 meter snoer met een kleine rechte stereo stekker.</p><p><br></p><p>Geschikt voor telefoons, chromebooks, ipads (met hoofdtelefoon aansluiting) </p><p><br></p><p>Dit is de Blauwe uitvoering, ook leverbaar in wit.</p><p><br></p><p><br></p><p><br></p><p><br></p>', '8 6,98\n16 6,42\n24 5,90', 8, '2025-03-21 14:12:58', '<p>Kids hoofdtelefoon</p><p>Maximale volume begrenzing</p><p>Lang comfortabel te dragen</p><p>Leverbaar in wit en blauw</p>', '<p><strong>Kids hoofdtelefoon</strong></p><p>Witte uitvoering</p><p>Enkelzijdig snoer</p><p>Verstelbare hoofdband</p>', 'ja', ''),
(63, 'Hoofdtelefoons', 'Degelijk', 'HP-92', '<p>Half open degelijke hoofdtelefoon</p><p><br></p><p>Inklapbaar en dat is makkelijk bij het opruimen</p><p>Zelf stellende oorschelpen met zachte kussens en de hoofdband is in hoogte verstelbaar.</p><p><br></p><p>1,2 meter enkelzijdig met nylon verstevigd snoer met kleine 3,5 mm stereo stekker.</p><p><br></p><p>Impedantie: 32 ohm</p><p>frequentiebereik: 20-20.000 Hz</p><p>Gevoeligheid: 105db/mw</p><p><br></p><p>Deze hoofdtelefoon heeft geen microfoon.</p><p><br></p><p>Verpakking: per 8 in een overdoos</p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p>', '12 8,65 \n24 8,26\n36 7,99', 12, '2025-03-21 14:39:00', '<p>Nylon verstevigd snoer</p><p>Inklapbaar</p><p>Zelfstellend</p>', '<p>Inklapbaar</p><p>1,2 meter verstevigde kabel</p>', 'ja', ''),
(64, 'Hoofdtelefoons', 'Budget', 'HP-2706-KOPIE', '<p>Budget hoofdtelefoon</p><p><br></p><p>De meest basis hoofdtelefoon die we verkopen.</p><p>Met twee meter snoer een verstelbare hoofdband en automatisch stellende oorschelpen.</p><p><br></p><p>Het snoer is voorzien van een kleine, rechte stereo stekker.</p><p>Deze hoofdtelefoon heeft geen microfoon</p><p><br></p><p>Oorschelpen voorzien van een zachte comfortabele spons.</p><p><br></p><p>Per stuk verpakt in eenzakje, per 125 in een doos en per 250 in een overdoos</p><p><br></p><p>uitstekend geschikt voor scholen, ziekenhuizen, musea, rondvaarten, bibliotheken en revalidatiecentra.</p><p><br></p><p><br></p><p><br></p>', '125 1,15\n250 1,10\n500 1,00', 125, '2025-04-09 14:34:36', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p><p>zachte oorschelpen</p>', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', 'ja', ''),
(65, 'Hoofdtelefoons', 'Stilte Koptelefoon', 'Stil 100', '<p>🎧 Stilte koptelefoon – Rust in het koppie, focus in de klas!</p><p> </p><p>Voor kinderen die snel zijn afgeleid, is een beetje stilte soms precies wat ze nodig hebben. De stilte koptelefoon helpt leerlingen zich beter te concentreren in een drukke klas, zonder zich helemaal af te sluiten. Minder prikkels, meer rust.</p><p>Waarom leerkrachten (en kinderen) fan zijn:</p><p>🔇 Dempt storend geluid en harde muziek tot wel 29dB</p><p>🧠 Ideaal bij overprikkeling of concentratieproblemen</p><p>🎉 Ook superhandig bij feestjes, muziekles of drukke momenten</p><p>👦👧 Voor kinderen vanaf 5 jaar</p><p>💺 Comfortabele zachte hoofdband, voelt fijn en licht</p><p>📏 Makkelijk verstelbaar – one size fits all</p><p>🎒 Opvouwbaar, dus makkelijk mee te nemen</p><p>Tip uit de klas: Zet een paar stilte koptelefoons klaar in een mandje. Kinderen kunnen ze zelf pakken als het even te druk wordt in hun hoofd. Werkt verrassend goed – en geeft rust voor de hele klas. 🌈</p>', '1 17,90\n5 16,20\n10 15,70', 5, '2025-04-09 14:38:11', '<p>Verhoogt concentratie</p><p>Opklapbaar</p><p>29 db demping</p>', '<p>Verhoogt concentratie</p><p>Opklapbaar</p><p>29 db demping</p><p><br></p>', 'ja', '');

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
  `voornaam` varchar(100) NOT NULL,
  `achternaam` varchar(100) NOT NULL,
  `geslacht` enum('M','V','X') NOT NULL,
  `email` varchar(150) NOT NULL,
  `wachtwoord` varchar(255) NOT NULL,
  `rol` enum('klant','admin') NOT NULL DEFAULT 'klant',
  `google_id` varchar(255) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `klant_id` int DEFAULT NULL,
  `aangemaakt_op` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `email_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `confirmation_token` varchar(64) DEFAULT NULL,
  `password_reset_token` varchar(32) DEFAULT NULL,
  `password_reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `voornaam`, `achternaam`, `geslacht`, `email`, `wachtwoord`, `rol`, `google_id`, `avatar_url`, `klant_id`, `aangemaakt_op`, `email_confirmed`, `confirmation_token`, `password_reset_token`, `password_reset_expires`) VALUES
(3, 'Peter', 'Felis', 'M', 'peter@felis.nl', '$2y$10$4u59LfnT4hChV/.HJNQjq./.iW1shzGwQ.Wj/rJ16e2sJtTWLPGXi', 'admin', NULL, NULL, 2, '2025-02-15 14:54:53', 1, NULL, NULL, NULL),
(8, 'Peter', 'Felis', 'M', 'verkoop@fetum.nl', '$2y$10$Fg4A0zdKiEyntOlxGHS2HuJef1BWGvEOBbGLYHpWXZm42GoI80UU.', 'klant', NULL, NULL, 7, '2025-02-28 13:42:24', 1, NULL, NULL, NULL);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT voor een tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
