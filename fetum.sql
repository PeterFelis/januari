-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 02 jun 2025 om 18:06
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
(2, 'Fetum Company', 'Hoofdstraat', '1', '1234AB', 'Amsterdam', '', '', '', 'http://fetum.nl', '', '', '', '', '', '', '', '', '', '', '2025-02-15 14:54:53', 'Nederland'),
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
  `hoofd_product` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `products`
--

INSERT INTO `products` (`id`, `categorie`, `subcategorie`, `TypeNummer`, `omschrijving`, `prijsstaffel`, `aantal_per_doos`, `aangemaakt_op`, `USP`, `sticker_text`, `leverbaar`, `hoofd_product`) VALUES
(1, 'Hoofdtelefoons', 'Degelijk', 'HP-136 S', '<p><strong>Onze klassieke en betrouwbare HP-136</strong></p><p><br></p><p>Ontdek de vernieuwde HP-136, nu uitgerust met een afneembaar snoer, een handige bewaartas en een naamsticker, zodat altijd duidelijk is welke hoofdtelefoon van wie is.</p><p><br></p><p><strong>Comfort en design:</strong></p><p>De hoofdtelefoon heeft een metalen hoofdband bekleed met stof en verstelbare, zacht beklede oorschelpen. Het maximale volume is begrensd op 85 dB, wat veilig gebruik garandeert. De hoofdtelefoon zit zeer comfortabel en is geschikt voor urenlang probleemloos gebruik.</p><p><br></p><p><strong>Verpakking en accessoires:</strong></p><p>Per stuk verpakt in een zakje, of per 32 stuks in een overdoos.</p><p>Enkelzijdig afneembaar en vervangbaar, met een lengte van 1,2 meter.</p><p><br></p><p><strong>Type: </strong>Halfopen</p><p><br></p>', '32 7,86\n64 7,64\n128 7,24\n256 6.95', 32, '2024-12-27 15:59:45', '<p>Afneembaar snoer</p><p>Verstelbare oorschelpen</p><p>Nylon verstevigd snoer</p><p>Inclusief naamsticker</p>', '<p>Afneembaar 1,2 meter snoer,</p><p>Met naam sticker en bewaartas</p><p>Zachte hoofdband</p><p>Verstelbare zachte oorschelpen</p><p>max volume 85dB </p>', 'ja', NULL),
(2, 'Hoofdtelefoons', 'Comfort', 'HP-305', '<h3>Comfortabele hoofdtelefoon voor een scherpe prijs</h3><h3><br></h3><p><strong>Eenzijdig snoer</strong> van 2 meter – raakt minder snel in de knoop en wordt niet snel in de mond genomen.</p><p><strong>3,5 mm rechte stekker</strong> – past altijd.</p><p><strong>Zachte oorschelpen en verstelbare hoofdband</strong> – voor een prettige pasvorm.</p><p><strong>Opklapbare oorschelpen</strong> – handig bij het opbergen.</p><p><br></p><p>Optioneel verkrijgbaar met een <strong>gerecyclede denim bewaartas</strong>, waarop een naam geschreven kan worden. Voorkomt dat het snoer in de knoop raakt.</p><p><br></p><p><strong>Verpakking:</strong></p><p>Per stuk in een zakje</p><p>Per 50 in een doos</p><p>Overdoos van 100 stuks</p><p><br></p><p><strong><span class=\"ql-cursor\">﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿</span></strong>Zonder blisterverpakking – compact en eenvoudig op te slaan.</p>', '50 2,29\n100 2,09\n200 2,03', 50, '2024-12-27 16:01:05', '<p>Uitstekende prijs-kwaliteitSverhouding</p><p>Lang, enkelzijdig snoer</p><p>Verstelbare hoofdband – past altijd comfortabel</p><p>Zachte, opklapbare oorschelpen</p>', '<p><strong>Comfort hoofdtelefoon</strong></p><p>2 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', ''),
(3, 'Hoofdtelefoons', 'Nekband', 'HP-122', '<p><strong>Nekband Koptelefoon</strong></p><p><br></p><p><u>Specificaties:</u></p><p>Snoer:&nbsp;Robuust nylon, beschermd tegen beschadiging.</p><p>Elk hygiënisch verpakt in een nette opbergtas.</p><p>Minimalistische opslag zonder overbodig afval: Geen blister of extra doos.</p><p><br></p><p>Aantal: 32 stuks per doos.</p><p>Certificeringen:&nbsp;De koptelefoon is CE-gecertificeerd en voldoet aan zowel de RoHS als de Reach standaard.</p><p>Snoerlengte:&nbsp;1,8 meter, voorzien van een standaard 3,5 mm rechte stereo stekker.</p><p><br></p><p><u>Bijzonder geschikt voor</u>:</p><p>Scholen. De nekband koptelefoon is uitermate geschikt voor jongere kinderen dankzij zijn comfortabele pasvorm en duurzaamheid.</p><p><br></p><p>Uitstekende hoofdtelefoon voor school gebruik.</p><p><br></p>', '32 4,31\n64 4,05\n128 3,92', 32, '2025-01-06 13:26:28', '<p>Nekband uitvoering</p><p>Nylon verstevigd snoer</p><p>1,8 meter snoer</p><p>In bewaartas</p>', '<p>Nekband koptelefoon</p><p>1,8 meter snoer</p><p>degelijk</p>', 'ja', ''),
(4, 'Hoofdtelefoons', 'Comfort', 'HP-316', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '50 3,39\n100 3,22\n200 3,02', 50, '2025-02-03 14:32:21', '<p>Ultrasound Hoofdtelefoon</p><p>Beste geluidskwaliteit in deze klasse</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', ''),
(5, 'Hoofdtelefoons', 'Budget', 'HP-2706', '<p><strong>HP-2706 Budget Koptelefoon</strong></p><p>Een voordelige oplossing wanneer een betrouwbare koptelefoon nodig is. Voorzien van een kunststof hoofdband, zelfstellende oorschelpen en een snoer van 1,8 meter.</p><p> De compacte rechte stekker past vaak ook door beschermhoezen.</p><p><strong>Toepassingen:</strong></p><p><br></p><ul><li>In de klas, met PC, tablet of Chromebook</li><li>Aan het patiëntenbed, bijvoorbeeld in ziekenhuizen of revalidatieklinieken</li></ul><p><br></p><p>Hygiënisch per stuk verpakt in een zakje, zonder overbodige blisterverpakking en met minimaal afval.</p><p> Alle hoofdtelefoons zijn CE-, ROHS- en REACH-gecertificeerd, getest door TÜV Duitsland.</p><p><br></p><p><strong>Verpakking:</strong></p><ul><li>125 stuks per doos</li><li>250 stuks per overdoos</li><li>7.000 stuks per pallet</li></ul><p><br></p><p>Grote aantallen nodig voor eenmalig of continu gebruik? Wij maken graag een passend voorstel.</p><p><br></p><p><strong>Beschikbare varianten:</strong></p><p><br></p><ul><li><strong>HP-2710</strong>: Zelfde model in vier vrolijke kleuren</li><li><strong>HP-2705</strong>: Uitvoering met metalen hoofdband en volumeregeling</li><li><strong>HP-2707</strong>: Uitvoering met 3 meter snoer</li></ul><p><br></p><p><br></p><p><br></p>', '125 1,20\n250 1,10', 125, '2025-02-03 14:32:21', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', 'ja', ''),
(6, 'Hoofdtelefoons', 'Budget', 'HP-2710', '<p>Betaalbare kleurrijke koptelefoon.</p><p><br></p><p><br></p><p>Uitermate geschikt voor:</p><p><br></p><p>Scholen en bibliotheken: een budgetvriendelijke, hygiënische oplossing voor leerlingen. Geniet van een rijke luisterervaring in musea of verbeter het onderwijs met onze koptelefoons.</p><p><br></p><p>Ziekenhuizen: waar deze hoofdtelefoons naadloos integreren met patiënten entertainment systeem. Biedt extra comfort en bewegingsvrijheid dankzij de 1,8 meter lange kabel.</p><p><br></p><p>Revalidatiecentra: waar patiënten vaak bedlegerig zijn, biedt de lange kabel van de HP-2710 minder belasting op de televisie of computer aansluiting en meer bewegingsvrijheid.</p><p><br></p><p>Elke doos bevat 125 hoofdtelefoons in een gelijke verdeling van vier vrolijke kleuren.</p><p><br></p><p>De HP-2710 wordt alleen per doos verkocht, wat kostenefficiënt is.</p><p>De hoofdtelefoons zitten per stuk in een zakje verpakt, dus geen overbodig afval en nodeloos kostenverhogend blister.</p><p><br></p><p>Specs:</p><p>Verstelbare oorschelpen</p><p>Verstelbare kunststof hoofdband</p><p>Hygienisch per stuk in een zakje verpakt.</p><p>Weinig opslag en geen overbodig afval. (Geen blister of doosje).</p><p>125 stuks per doos.</p><p><br></p><p>Per overdoos: 31 rode, 31 groene, 31 blauwe en 32 zwarte.</p><p><br></p><p>De hoofdtelefoon is CE gekeurd en voldoet aan de RoHS en Reach standaard.</p><p>1,8 meter snoer met standaard 3,5 mm rechte stereo stekker.</p><p><br></p>', '24 2,29\n125 1,35\n250 1,25 ', 125, '2025-02-03 14:40:37', '<p>Budget koptelefoon</p><p>4 vrolijke kleuren</p><p>Geen bliser - geen afval</p>', '<p>Budget hoofdtelefoon</p><p>4 verschillende vrolijke kleuren</p><p>rood - wit - blauw en zwart</p>', 'ja', ''),
(7, 'Oortjes', 'Budget', 'HP-32', '<h3><strong>Comfort en veiligheid gecombineerd</strong></h3><p><br></p><p>Deze oortjes zijn ontworpen met het oog op comfort én gehoorbescherming.</p><p><br></p><p>Ze beschikken over een ingebouwde volumebegrenzing op 84 dB, wat het gehoor beschermt tijdens langdurig gebruik.</p><p><br></p><p>Dankzij de meegeleverde <strong>drie maten eartips</strong> sluit het oortje altijd goed aan – voor elke gebruiker de juiste pasvorm.</p><p><br></p><p>Elk setje wordt geleverd in een <strong>stevig kunststof bewaardoosje</strong>, ideaal voor bescherming en langdurig gebruik.</p><h3><br></h3><h3><strong>Slim verpakt, direct klaar voor gebruik</strong></h3><p><br></p><p>✔️ <em>Individueel verpakt</em> in stevige zakjes met doosje (geen blisterverpakking)</p><p>✔️ <em>Efficiënt geleverd</em> – 50 stuks per overdoos</p><p>✔️ <em>Herkenbaar</em> – elke set bevat een beschrijfbare naamsticker</p><p><br></p><p><strong>Verkrijgbaar in meerdere kleuren.</strong></p><p><strong>Verkoop per doos van 50 stuks – inclusief doosjes en naamstickers.</strong></p>', '50 1,65\n100 1,52\n200 1,39', 50, '2025-02-03 14:40:37', '<p>Oortje in opbergdoosje</p><p>1 meter snoer</p><p>Met 3 maten eartips</p><p>Met beschrijfbare naamsticker</p>', '<p>Oortje in opbergdoosje</p><p>1 meter snoer</p><p>Met 3 maten eartips</p><p><strong>Met beschrijfbare naamsticker</strong></p>', 'ja', ''),
(15, 'Oortjes', 'Comfort', 'i-900N', '<p>i-900 oortje met beschrijfbare bewaartas.</p>', '48 2,21\n92 2,09\n192 1,95', 48, '2025-02-10 12:29:10', '<p>Comfort oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja', 'i-900'),
(24, 'Hoofdtelefoons', 'Budget', 'HP-2707', '<p>Budget hoofdtelefoon</p><p>3 meter snoer</p><p><br></p><p><br></p>', '1', 125, '2025-02-26 13:57:21', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', 'nee', ''),
(28, 'Hoofdtelefoons', 'Degelijk', 'HP-88', '<p><strong>Professionele hoofdtelefoon – Comfort en betrouwbaarheid in één</strong></p><p><br></p><p><strong>✔️ <em>Stijlvol en degelijk</em></strong> – Verkrijgbaar in zwart of wit, met een luxe afwerking.</p><p>✔️ <strong><em>Optimale pasvorm</em></strong> – Verstelbare hoofdband en comfortabele, met zachte stof beklede oorschelpen.</p><p>✔️ <strong><em>Compact op te bergen</em></strong> – Inklapbare oorschelpen, ideaal voor transport en opslag.</p><p>✔️ <strong><em>Duurzaam in gebruik</em></strong> – Enkelzijdig verstevigd snoer voorkomt knikken en kabelbreuk.</p><p>✔️ <strong><em>Veilig en gecertificeerd</em></strong> – CE-gekeurd en voldoet aan RoHS- en REACH-richtlijnen.</p><p>✔️ <strong><em>Praktische kabellengte</em></strong> – 1,2 meter snoer met standaard 3,5 mm rechte stereostekker</p><p><br></p><p>Verpakking per 32 stuks</p><p><br></p>', '32 7,86\n64 7,64\n128 7,24\n256 6.95', 32, '2025-02-26 14:19:52', '<p>Stijlvol en degelijk</p><p>Optimale pasvorm</p><p>Compact op te bergen</p><p>Veilig en gecertificeerd</p>', '<p>Stijlvol en degelijk</p><p>Optimale pasvorm</p><p>Compact op te bergen</p><p>Veilig en gecertificeerd&nbsp;</p>', 'nee', ''),
(29, 'Hoofdtelefoons', 'Budget', 'HP-2705', '<p>Budget hoofdtelefoon</p><p>3 meter snoer</p><p><br></p><p><br></p>', '125 1,20\n250 1,10', 125, '2025-02-26 14:25:11', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', 'nee', ''),
(30, 'Hoofdtelefoons', 'Comfort', 'HP-112', '<p><strong>De betrouwbare keuze voor dagelijks gebruik.</strong></p><p><br></p><p>Waar je hem ook gebruikt: deze hoofdtelefoon biedt comfort, kwaliteit en zekerheid.</p><p><br></p><p>✔️ <strong>Verstelbare metalen hoofdband</strong> – Past altijd perfect, voor optimaal draagcomfort.</p><p>✔️ <strong>Royale oorschelpen </strong>– Bedekken de oren, maar sluiten niet volledig af.</p><p>✔️ <strong>Hygiënisch verpakt</strong> – Per stuk verpakt op een stevig karton. </p><p>✔️ <strong>Comfortabel &amp; robuust</strong> – Stevige hoofdtelefoon die ook bij langdurig gebruik prettig blijft zitten.</p><p>✔️ <strong>Veilig en gecertificeerd</strong> – CE-gekeurd en voldoet aan de RoHS- en REACH-richtlijnen.</p><p>✔️ <strong>Handig formaat snoer</strong> – 1 meter kabel met standaard 3,5 mm rechte stereostekker.</p><p><br></p><p>📦 Verpakking per 32 stuks</p><p><br></p>', '32 4,31\n64 4,05\n128 3,92', 32, '2025-02-26 14:25:55', '<p>Metalen hoofdband</p><p>Royale oorschelpen</p><p>Kleine stekker</p>', '<p>Metalen hoofdband</p><p>Royale oorschelpen</p><p>Kleine stekker</p>', 'ja', ''),
(31, 'Hoofdtelefoons', 'Degelijk', 'HP-92', '<p><strong>Halfopen Comfort Hoofdtelefoon</strong></p><p><br></p><p>Comfort, kwaliteit en gebruiksgemak in één</p><p><br></p><p>Deze halfopen hoofdtelefoon biedt een aangename luisterervaring met een ontwerp dat comfort en praktisch gebruik combineert. Ideaal voor bijvoorbeeld onderwijs en bibliotheken.</p><p><br></p><p>🎧 Heldere geluidsweergave</p><p> Geniet van een natuurlijke klank met voldoende omgevingsbewustzijn dankzij het halfopen ontwerp en de goede speakers. Perfect voor langdurig gebruik zonder afgesloten gevoel.</p><p><br></p><p>✔️ Zelfstellende oorschelpen</p><p> Sluiten vanzelf netjes aan voor optimaal draagcomfort, zonder gedoe met verstellen.</p><p><br></p><p>✔️ Zachte hoofdband</p><p> Bekleed met zacht stof – comfortabel, zelfs bij langer gebruik.</p><p><br></p><p>✔️ Verstevigd snoer van 1,2 meter</p><p> Nylon omhulsel voorkomt knikken en breuken. Ideaal voor dagelijks intensief gebruik.</p><p><br></p><p>✔️ Compact op te bergen</p><p> Opklapbaar ontwerp – handig in het  klaslokaal.</p><p><br></p><p>✔️ Slimme aansluiting</p><p> De compacte 3,5 mm stekker past zelfs door dikkere tablet- en telefoonhoezen.</p><p><br></p><p>📦 Verpakking per stuk in doosje</p><p>🧳 Overdoos met 12 stuks – makkelijk in beheer en distributie</p><p><br></p><p>Een betrouwbare allround hoofdtelefoon met een prettig draagcomfort en robuuste afwerking – precies wat je nodig hebt voor dagelijks gebruik in professionele omgevingen.</p>', '12 8,65\n24 8,26\n36 7,99', 12, '2025-02-26 14:26:24', '<p>Halfopen ontwerp met helder geluid</p><p>Verstevigd nylon snoer (1,2 m)</p><p>Compact & inklapbaar</p><p>Zelfstellende oorschelpen & zachte hoofdband</p>', '<p>Halfopen ontwerp met helder geluid&nbsp;</p><p>Verstevigd nylon snoer (1,2 m)&nbsp;</p><p>Compact &amp; inklapbaar</p><p>Zelfstellende oorschelpen &amp; zachte hoofdband</p>', 'ja', ''),
(32, 'Oortjes', 'Comfort', 'i-900', '<h3><strong>In-ear oortje met microfoon – afsluitend, stevig en veelzijdig</strong></h3><p><br></p><p>Dit witte oortje is ontworpen voor optimaal draagcomfort en geluidsisolatie.</p><p><br></p><p>De in-ear pasvorm sluit de gehoorgang goed af, wat zorgt voor een prettige luisterervaring, ook in rumoerige omgevingen.</p><p><br></p><p>Dankzij de <strong>drie meegeleverde maten eartips</strong> is er voor elke gebruiker een perfecte </p><p>pasvorm.</p><p><br></p><p>Het nylon verstevigde snoer van <strong>1,1 meter</strong> is flexibel en duurzaam.</p><p><br></p><p>In het snoer zit een <strong>microfoon met pauze-/playknop</strong> verwerkt – ideaal voor bellen, videolessen of het bedienen van audio.</p><p><br></p><p>De <strong>3,5 mm stereostekker</strong> met <strong>TRRS-aansluiting (3 ringen)</strong> maakt dit oortje geschikt voor tablets, laptops, pc’s en Chromebooks met gecombineerde audio/mic-poort.</p><p><br></p><p>Per 12 stuks in overdoos.</p>', '48 1,91\n92 1,79\n192 1,65', 48, '2025-02-26 14:27:12', '<p>Comfort oortje</p><p>Helemaal wit</p><p>Verstevigd snoer</p><p>ook leverbaar in zwart</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><br></p>', 'ja', ''),
(34, 'Oortjes', 'Comfort', 'i-40', '<p><strong>Comfortabel oortje met microfoon – licht, stevig en veelzijdig</strong></p><p><strong><span class=\"ql-cursor\">﻿﻿﻿﻿</span></strong></p><p>✔️ <em>Perfecte pasvorm</em> – Het ronde ontwerp sluit goed aan in het oor en blijft comfortabel, zelfs bij langdurig gebruik.</p><p> ✔️ <em>Lichtgewicht en duurzaam</em> – Ideaal voor dagelijks gebruik op school of kantoor.</p><p> ✔️ <em>Heldere communicatie</em> – Ingebouwde microfoon in het snoer, geschikt voor spraaktoepassingen en online lessen.</p><p> ✔️ <em>Universeel te gebruiken</em> – Compatibel met tablets, pc’s en Chromebooks (3,5 mm stekker met 3 ringen – TRRS).</p><p> ✔️ <em>Stevig en flexibel</em> – 1 meter lang verstevigd snoer dat soepel meebeweegt.</p><p> ✔️ <em>Duurzame verpakking</em> – Per stuk geleverd in een milieuvriendelijk kartonnen doosje.</p>', '50 2,69\n100 2,59\n200 2,38', 50, '2025-02-26 14:28:23', '<p>Comfort oortje</p><p>Verstevigd snoer</p><p>Met microfoon</p><p>Uitstekende pasvorm</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p>Uitstekende pasvorm</p><p><br></p>', 'ja', ''),
(35, 'Oortjes', 'Comfort', 'i-40N', '<p><strong>Comfortabel oortje met microfoon – licht, stevig en veelzijdig</strong></p><p>✔️ <em>Perfecte pasvorm</em> – Het ronde ontwerp sluit goed aan in het oor en blijft comfortabel, zelfs bij langdurig gebruik.</p><p> ✔️ <em>Lichtgewicht en duurzaam</em> – Ideaal voor dagelijks gebruik op school of kantoor.</p><p> ✔️ <em>Heldere communicatie</em> – Ingebouwde microfoon in het snoer, geschikt voor spraaktoepassingen en online lessen.</p><p> ✔️ <em>Extra bedieningsgemak</em> – Met microfoon, pauze/start-stopfunctie in het snoer.</p><p> ✔️ <em>Universeel te gebruiken</em> – Compatibel met tablets, pc’s en Chromebooks (3,5 mm stekker met 3 ringen – TRRS).</p><p> ✔️ <em>Stevig en flexibel</em> – 1 meter lang verstevigd snoer dat soepel meebeweegt.</p><p> ✔️ <em>Netjes verpakt</em> – In een degelijk bewaardoosje met transparant deksel.</p><p> ✔️ <em>Persoonlijk te labelen</em> – Inclusief beschrijfbare naamsticker (te plakken op de achterkant).</p><p> ✔️ <em>Duurzame verpakking</em> – Per stuk geleverd in een milieuvriendelijk kartonnen doosje.</p>', '50 4,49\n100 4,09\n200 3,79', 50, '2025-02-26 14:28:49', '<p>Comfort oortje</p><p>In doosje</p><p>Met naamsticker</p><p>Uitstekende pasvorm</p>', '<p>Comfort oortje</p><p>In doosje</p><p>met naamsticker</p><p>uitstekende pasvorm</p>', 'ja', ''),
(36, 'Oortjes', 'Budget', 'HP-30', '<p><strong>Budget witte oortjes</strong></p><p><br></p><ul><li>Per stuk hygiënisch verpakt in een herbruikbaar zakje.</li><li>1 meter snoer met rechte stereostekker.</li><li>Zonder microfoon.</li><li>Volumebegrensd: voorkomt gehoorschade.</li><li>Optioneel leverbaar met bewaar-pouch.</li></ul><p><br></p>', '50 1,87\n100 1,73\n200 1,62', 50, '2025-02-26 14:29:47', '<p>Budget oortje</p><p>1 meter snoer</p><p>Witte uitvoering</p><p>In hersluitbaar zakje</p>', '<p>Witte oortjes</p><p>Rechte stekker</p><p>kleur: wit</p>', 'ja', ''),
(39, 'Oortjes', 'USB', 'Ear-USB 3', '<p><strong>Moderne USB-C oortjes in stevig opbergdoosje</strong></p><p><br></p><p>Op zoek naar betrouwbare oortjes met USB-C aansluiting, netjes verpakt en direct klaar voor gebruik? Deze set combineert duurzaamheid, gebruiksgemak en hygiëne in één slim product.</p><p><br></p><p>✔️ <strong>USB-C aansluiting – de moderne standaard</strong></p><p> Compatibel met Chromebooks, laptops, tablets en andere apparaten met USB-C. Geen gedoe meer met adapters of verouderde 3,5 mm poorten.</p><p><br></p><p>✔️ <strong>Altijd goede aansluiting</strong></p><p> De USB-C stekker is robuuster en gaat langer mee dan een klassieke 3,5 mm plug. Stabiele connectie, direct geluid.</p><p><br></p><p>✔️ <strong>Slimme verpakking</strong></p><p> Ieder setje zit in een stevig opbergdoosje, leverbaar in zwart of wit. De oortjes zijn altijd wit – fris en neutraal.</p><p><br></p><p>✔️ <strong>Persoonlijk en hygiënisch</strong></p><p> Inclusief naamsticker om zelf te beschrijven: ideaal voor gebruik op school.</p><p><br></p><p>✔️ <strong>Comfort voor iedereen</strong></p><p> Wordt geleverd met 3 maten siliconen eartips. Zo zit het altijd goed.</p><p><br></p><p>📦 Verpakt per 25 stuks</p><p> 📏 1 meter snoer</p><p> 🎧 Stevig opbergdoosje meegeleverd</p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p>', '25 4,38\n50 4,03\n100 3,58\n200 3,01', 25, '2025-02-26 14:34:32', '<p>USB-C aansluiting</p><p>In bewaardoosje</p><p>3 eartips</p><p>Met naamsticker</p>', '<p>ISB-C oortje</p><p>In bewaar doosje</p><p>3 maten ear-tips</p><p>Met naam sticker</p>', 'ja', ''),
(42, 'Accessoires', 'Splitters', 'Splitter zwart', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '1', 50, '2025-02-26 14:38:18', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'nee', ''),
(44, 'Accessoires', 'Splitters', 'Splitter wit', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '1', 50, '2025-02-26 14:39:43', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'nee', ''),
(45, 'Adapters', 'budget', 'USB A > C', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '1', 50, '2025-02-26 14:40:04', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', NULL),
(46, 'Adapters', 'budget', 'USB C > A', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '1', 50, '2025-02-26 14:40:40', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', NULL),
(48, 'Hoofdtelefoons', 'Comfort met zakje', 'HP-305Z', '<p>De HP-305 met gerycycled en beschrijfbare opbergtas</p><p><br></p>', '50 3,29\n100 3,09\n200 3,03', 50, '2025-02-27 12:32:34', '<p>Uitstekende prijs-kwaliteitSverhouding</p><p>Lang, enkelzijdig snoer</p><p>Verstelbare hoofdband – past altijd comfortabel</p><p>Zachte, opklapbare oorschelpen</p>', '<p><strong>Comfort hoofdtelefoon</strong></p><p>2 meter enkel zijdig snoer</p><p><u>Met bewaartas</u></p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', 'HP-305'),
(49, 'Oortjes', 'Budget', 'HP-30 Pouch', '<p>Dit is de HP-30 met een siliconen (onverwoestbare) zwarte bewaar pouch</p><p><br></p>', '50 2,87\n100 2,73\n200 2,62', 50, '2025-02-28 12:50:24', '<p>Budget oortje</p><p>met onverwoestbare bewaar pouch</p>', '<p>Witte oortjes</p><p>Rechte stekker</p><p>kleur: wit</p>', 'ja', 'HP-30'),
(50, 'Hoofdtelefoons', 'Comfort met zakje', 'HP-316Z', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '150 3,89\n100 3,72\n200 3,52', 50, '2025-03-05 16:07:04', '<p>Met gerycycled bewaarzakje</p><p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja', ''),
(51, 'Hoofdtelefoons', 'Kinder', 'Ki 100 blauw', '<p><strong>Kinderhoofdtelefoon met vrolijke look</strong></p><p><strong>Veilig geluid voor jonge oren</strong></p><p><br></p><p>Deze blauwe hoofdtelefoon met een vrolijke print is speciaal ontworpen voor kinderen. Een veilige, duurzame en comfortabele oplossing voor gebruik op school, in de bieb of onderweg.</p><p><br></p><p>🎧 <strong>Kindvriendelijk volume</strong></p><p> De ingebouwde volumebegrenzing tot 85 dB beschermt jonge oren tegen gehoorschade – ook bij langdurig gebruik.</p><p><br></p><p>🔌 <strong>Stevige aansluiting</strong></p><p> De hoofdtelefoon heeft een compact vormgegeven 3,5 mm rechte stekker die ook past door dikke hoezen. Met 1 meter snoer is hij ideaal voor gebruik in klaslokalen en leeromgevingen.</p><p><br></p><p>🧵 <strong>Nylon verstevigd snoer</strong></p><p> Het enkelzijdige snoer is extra duurzaam en bestand tegen trekken en draaien – perfect voor kinderhanden.</p><p><br></p><p>🎨 <strong>Vrolijk ontwerp</strong></p><p> De frisse blauwe kleur en speelse afbeelding maken deze hoofdtelefoon leuk om te dragen – én makkelijk herkenbaar.</p><p><br></p><p>🔒 <strong>Veilig &amp; gecertificeerd</strong></p><p> CE-gekeurd en voldoet aan de RoHS- en REACH-richtlijnen.</p><p><br></p><p>📦 <strong>Verpakking per 12 in een overdoos</strong> </p>', '8 6,98\n16 6,42\n24 5,90', 8, '2025-05-10 13:04:49', '<p>85 dB volumebegrenzing</p><p>Nylon verstevigd 1 meter snoerg</p><p>Speels design</p><p>Compacte aansluiting</p>', '<p>85 dB volumebegrenzing</p><p>Nylon verstevigd 1 meter snoer</p><p>Speels design&nbsp;</p><p>Compacte aansluiting</p>', 'ja', ''),
(52, 'Hoofdtelefoons', 'Kinder', 'Ki 100 wit', '<p><strong>Kinderhoofdtelefoon met vrolijke look</strong></p><p><strong>Veilig geluid voor jonge oren</strong></p><p><br></p><p>Deze blauwe hoofdtelefoon met een vrolijke print is speciaal ontworpen voor kinderen. Een veilige, duurzame en comfortabele oplossing voor gebruik op school, in de bieb of onderweg.</p><p><br></p><p>🎧 <strong>Kindvriendelijk volume</strong></p><p> De ingebouwde volumebegrenzing tot 85 dB beschermt jonge oren tegen gehoorschade – ook bij langdurig gebruik.</p><p><br></p><p>🔌 <strong>Stevige aansluiting</strong></p><p> De hoofdtelefoon heeft een compact vormgegeven 3,5 mm rechte stekker die ook past door dikke hoezen. Met 1 meter snoer is hij ideaal voor gebruik in klaslokalen en leeromgevingen.</p><p><br></p><p>🧵 <strong>Nylon verstevigd snoer</strong></p><p> Het enkelzijdige snoer is extra duurzaam en bestand tegen trekken en draaien – perfect voor kinderhanden.</p><p><br></p><p>🎨 <strong>Vrolijk ontwerp</strong></p><p> De frisse blauwe kleur en speelse afbeelding maken deze hoofdtelefoon leuk om te dragen – én makkelijk herkenbaar.</p><p><br></p><p>🔒 <strong>Veilig &amp; gecertificeerd</strong></p><p> CE-gekeurd en voldoet aan de RoHS- en REACH-richtlijnen.</p><p><br></p><p>📦 <strong>Verpakking per 12 in een overdoos</strong> </p>', '8 6,98\n16 6,42\n24 5,90', 8, '2025-05-10 13:08:05', '<p>85 dB volumebegrenzing</p><p>Nylon verstevigd 1 meter snoer</p><p>Speels design</p><p>Compacte aansluiting</p>', '<p>85 dB volumebegrenzing</p><p>Nylon verstevigd 1 meter snoer</p><p>Speels design&nbsp;</p><p>Compacte aansluiting</p>', 'ja', ''),
(53, 'Hoofdtelefoons', 'Comfort met zakje', 'HP-112Z', '<p>Dit is de HP-112 met een </p><p><strong>beschrijfbare opberg tas</strong></p><p><br></p><p>Per 32 stuks in een doos</p><p><br></p><p><br></p>', '32 4,81\n64 4,55\n128 3,42', 32, '2025-05-14 11:48:29', '<p>Metalen hoofdband</p><p>Royale oorschelpen</p><p>Kleine stekker</p><p>Met opbergtas</p>', '<p>Metalen hoofdband</p><p>rest nog maken</p>', 'ja', 'HP-112'),
(54, 'Hoofdtelefoons', 'Budget met zakje', 'HP-2706Z', '<p><strong>Dit is de HP-2706 Budget Koptelefoon</strong></p><p>Met een beschrijfbare opberg tas</p><p><br></p><p><br></p>', '125 1,20\n250 1,10', 125, '2025-05-15 14:38:08', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p><p>Inclusief opberg tas</p>', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p><p>Met opberg tas</p>', 'ja', 'HP-2706'),
(55, 'Hoofdtelefoons', 'Budget met zakje', 'HP-2710Z', '<p><strong>Dit is de HP-2710 Budget Koptelefoon</strong></p><p>Met een beschrijfbare opberg tas</p>', '24 2,29\n125 1,35\n250 1,25 ', 125, '2025-05-15 15:26:43', '<p>Budget koptelefoon</p><p>4 vrolijke kleuren</p><p>Geen bliser - geen afval</p>', '<p>Budget hoofdtelefoon</p><p>4 verschillende vrolijke kleuren</p><p>rood - wit - blauw en zwart</p>', 'ja', 'HP-2710'),
(56, 'Accessoires', 'Geluids adapter', 'Sound USB', '<p>Ideale oplossing als de 3,5mm aansluiting van de computer, laptop, tablet of pc niet meer te gebruiken is.</p><p><br></p><p>Volledige USB sound kaart met microfoon ingang en hoofdtelefoon aansluiting.</p><p>Werkt gelijk op pc\'s, tablets en chromebooks.</p><p><br></p><p>Insteken, hoofdtelefoon aansluiten en gelijk luisteren. Geen software installeren, gewoon eenvoudig gebruiken</p><p><br></p><p>Verpakt per 5 stuks</p>', '5 8,10\n10 7,46', 5, '2025-05-15 15:32:10', '<p>USB A aansluiting</p><p>Geen software nodig</p><p>Microfoon en hoofdtelefoon aansluiting</p>', '<p><strong>USB sound card</strong></p><p><strong>USB A aansluiting</strong></p><p><strong>Hoofdtelefoon en microfoon aansluiting</strong></p>', 'ja', ''),
(57, 'Stilte koptelefoon', 'stt', 'Stil 100', '<p>🎧 Stilte koptelefoon – Rust in het koppie, focus in de klas!</p><p> </p><p>Voor kinderen die snel zijn afgeleid, is een beetje stilte soms precies wat ze nodig hebben. De stilte koptelefoon helpt leerlingen zich beter te concentreren in een drukke klas, zonder zich helemaal af te sluiten. Minder prikkels, meer rust.</p><p>Waarom leerkrachten (en kinderen) fan zijn:</p><p>🔇 Dempt storend geluid en harde muziek tot wel 29dB</p><p>🧠 Ideaal bij overprikkeling of concentratieproblemen</p><p>🎉 Ook superhandig bij feestjes, muziekles of drukke momenten</p><p>👦👧 Voor kinderen vanaf 5 jaar</p><p>💺 Comfortabele zachte hoofdband, voelt fijn en licht</p><p>📏 Makkelijk verstelbaar – one size fits all</p><p>🎒 Opvouwbaar, dus makkelijk mee te nemen</p><p>Tip uit de klas: Zet een paar stilte koptelefoons klaar in een mandje. Kinderen kunnen ze zelf pakken als het even te druk wordt in hun hoofd. Werkt verrassend goed – en geeft rust voor de hele klas. 🌈</p>', '1 17,90\n5 16,20\n10 15,70', 5, '2025-05-15 15:50:23', '<p>Stilte koptelefoon</p><p>Demping 29 dB</p><p>Eenvoudig schoon te maken</p>', '<p>Stilte koptelefoon</p><p>Demping 29 db</p><p>Eenvoudig schoon te maken</p><p><br></p>', 'ja', ''),
(58, 'Muizen', 'Bedraad', 'Muis 55 zwart', '<h3><strong>Compacte USB-muis – geschikt voor kleinere handen</strong></h3><h3><br></h3><p>Deze degelijke, compacte muis is ideaal voor dagelijks gebruik op school.</p><p><br></p><p>Dankzij het compacte formaat ligt hij comfortabel in (kleinere) handen – perfect voor kinderen of gebruikers met een smallere grip.</p><p><br></p><p>✔️ Verkrijgbaar in zwart of wit</p><p> ✔️ Stevige behuizing met scrollwiel</p><p> ✔️ <strong>USB-A aansluiting</strong>, direct klaar voor gebruik – geen installatie nodig</p><p><br></p><p> ✔️ <strong>Zelf installerend</strong> op Windows, macOS en Chromebooks</p><p> ✔️ Per stuk verpakt in een doosje, <strong>geleverd per 8 in een overdoos</strong></p>', '1 100\n2 200', 8, '2025-05-21 12:23:02', '<p>Compacte USB muis</p><p>Zwarte uitvoering</p><p>Responsive packaging</p>', '<p>Compacte USB muis</p><p>Zwarte uitvoering</p><p>Responsive packaging</p><p><br></p>', 'ja', ''),
(59, 'Hoofdtelefoons', 'Bluetooth', 'BT-9', '<p><strong>Degelijke Bluetooth Hoofdtelefoon</strong></p><p><strong> </strong></p><p>Een robuuste Bluetooth hoofdtelefoon, ontworpen voor regelmatig gebruik  </p><p>Comfortabel, veelzijdig en eenvoudig in onderhoud.</p><p><br></p><p>✔️ Bluetooth 5.0 technologie</p><p> Stabiele verbinding en compatibel met oudere Bluetooth-versies. Werkt met laptops, tablets en telefoons.</p><p><br></p><p>✔️ Altijd inzetbaar – ook bedraad</p><p> Batterij leeg? Sluit eenvoudig de meegeleverde 3,5 mm AUX-kabel aan en gebruik de hoofdtelefoon direct verder.</p><p><br></p><p>✔️ Comfortabel én hygiënisch</p><p> Degelijke afwerking met gladde oppervlakken die eenvoudig schoon te maken zijn. De oorschelpen sluiten goed aan, maar laten nog wat omgevingsgeluid door.</p><p><br></p><p>✔️ Handig in gebruik</p><p> Met ingebouwde microfoon voor spraakgebruik en inklapbaar ontwerp voor eenvoudig opbergen.</p><p><br></p><p>🔋 Oplaadtijd: ± 2 uur</p><p> 🔊 Gebruikstijd: minimaal 3 uur draadloos</p><p> </p><p>🔌 Inclusief micro USB oplaadkabel en AUX-kabel</p><p><br></p><p>Een betrouwbare keuze voor wie draadloos wil werken, zonder concessies te doen aan flexibiliteit en duurzaamheid.</p>', '6 17,06\n12 15,22\n24 13,04', 6, '2025-05-22 09:47:31', '<p>Bluetooth Hoofdtelefoon</p><p>Opklapbaar</p><p>Met microfoon</p><p>Aux kabel meegeleverd</p>', '<p>Bluetooth Hoofdtelefoon</p><p>Opklapbaar</p><p>Met microfoon</p><p>Aux kabel meegeleverd</p>', 'ja', ''),
(60, 'Hoofdtelefoons', 'Bluetooth', 'BT-7', '<p><strong>Witte Bluetooth Hoofdtelefoon</strong></p><p>Stijlvol draadloos geluid met praktische extra’s</p><p><br></p><p>Deze stijlvolle witte Bluetooth hoofdtelefoon combineert helder geluid met comfort en functionaliteit. Een uitstekende keuze voor scholen, kantoren en projectmatig gebruik.</p><p><br></p><p>🎧 Strak design in wit</p><p> Ziet er modern uit en valt op in de juiste manier – schoon, licht en professioneel.</p><p><br></p><p>📶 Betrouwbare verbinding via Bluetooth 5.0</p><p> Verbindt soepel met alle gangbare apparaten en is achterwaarts compatibel met oudere versies.</p><p><br></p><p>🔋 Draadloos óf met kabel</p><p> Geen stroom meer? Geen probleem. Gebruik de meegeleverde 3,5 mm AUX-kabel om direct verder te luisteren, ook zonder batterij.</p><p><br></p><p>🧼 Makkelijk schoon te houden</p><p> De gladde materialen maken schoonmaken simpel en hygiënisch – ideaal bij gedeeld gebruik.</p><p><br></p><p>🎙️ Handsfree klaar</p><p> Met ingebouwde microfoon voor videogesprekken, telefonie of spraakopnames.</p><p><br></p><p>🧳 Opvouwbaar ontwerp</p><p> Klap \'m in en berg \'m compact op. Klaar voor de volgende sessie.</p><p><br></p><p>⚡ Laadtijd: circa 2 uur</p><p> 🔊 Luistertijd: minimaal 3 uur draadloos gebruik</p><p><br></p><p> 🔌 Inclusief micro USB oplaadkabel en AUX-kabel</p><p><br></p><p>Praktisch, duurzaam en met een frisse uitstraling – deze witte hoofdtelefoon is klaar voor dagelijks gebruik, waar je ook werkt of leert.</p>', '5 22,00\n10 20,00\n20 18,00', 5, '2025-05-22 10:15:51', '<p>witte Bluetooth Hoofdtelefoon</p><p>Opklapbaar</p><p>Met microfoon</p><p>Degelijk</p>', '<p>witte Bluetooth Hoofdtelefoon</p><p>Opklapbaar</p><p>Met microfoon</p><p>Degelijk</p>', 'ja', ''),
(61, 'Muizen', 'Bedraad', 'Muis 55 wit', '<p><br></p>', '1 100\n2 200', 8, '2025-06-02 15:19:44', '<p>usp</p>', '<p>Compacte USB muis</p><p>Zwarte uitvoering</p><p>Responsive packaging</p><p><br></p>', 'ja', 'Muis 55 zwart'),
(62, 'Muizen', 'Bluetooth', 'Muis 255 BT', '<p><br></p>', '', 10, '2025-06-02 15:20:00', '', '<p>Compacte USB muis</p><p>Zwarte uitvoering</p><p>Responsive packaging</p><p><br></p>', 'ja', '');

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
(3, 'Peter', 'Felis', 'M', 'peter@felis.nl', '$2y$10$lXZTo/ZscHyU3Is7DDLEz.dDMh1dCkBGNZuoZRFBne4zF.WV1ozyG', 'admin', NULL, NULL, 2, '2025-02-15 14:54:53', 1, NULL, NULL, NULL),
(8, 'Peter', 'Felis', 'M', 'verkoop@fetum.nl', '$2y$10$syRuJwfGvZR1C/42LfYpVuMvupUfR0zqhCcoTLvnKHcsPzG0BnKGa', 'klant', NULL, NULL, 7, '2025-02-28 13:42:24', 1, NULL, NULL, NULL);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT voor een tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
