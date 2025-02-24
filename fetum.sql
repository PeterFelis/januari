-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 24 feb 2025 om 14:57
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
(2, 'Fetum Company', 'Hoofdstraat', '1', '1234AB', 'Amsterdam', NULL, NULL, NULL, 'http://fetum.nl', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-15 14:54:53', 'Nederland');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `contactpersoon_id` int NOT NULL,
  `datum` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('offerte','besteld') DEFAULT 'offerte',
  `totaal_prijs` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `leverbaar` enum('ja','nee') NOT NULL DEFAULT 'ja'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `products`
--

INSERT INTO `products` (`id`, `categorie`, `subcategorie`, `TypeNummer`, `omschrijving`, `prijsstaffel`, `aantal_per_doos`, `aangemaakt_op`, `USP`, `sticker_text`, `leverbaar`) VALUES
(1, 'Hoofdtelefoons', 'Degelijk', 'HP-136 S', '<p><strong>Onze klassieke en betrouwbare HP-136</strong></p><p><br></p><p>Ontdek de vernieuwde HP-136, nu uitgerust met een afneembaar snoer, een handige bewaartas en een naamsticker, zodat altijd duidelijk is welke hoofdtelefoon van wie is.</p><p><br></p><p><strong>Comfort en design:</strong></p><p>De hoofdtelefoon heeft een metalen hoofdband bekleed met stof en verstelbare, zacht beklede oorschelpen. Het maximale volume is begrensd op 85 dB, wat veilig gebruik garandeert. De hoofdtelefoon zit zeer comfortabel en is geschikt voor urenlang probleemloos gebruik.</p><p><br></p><p><strong>Verpakking en accessoires:</strong></p><p>Per stuk verpakt in een zakje, of per 32 stuks in een overdoos.</p><p>Enkelzijdig afneembaar en vervangbaar, met een lengte van 1,2 meter.</p><p><br></p><p><strong>Type: </strong>Halfopen</p><p><br></p>', '32 7,86\n64 7,64\n128 7,24\n256 6.95', 32, '2024-12-27 15:59:45', '<p>Afneembaar snoer</p><p>Verstelbare oorschelpen</p><p>Nylon verstevigd snoer</p><p>Inclusief naamsticker</p>', '<p>Afneembaar 1,2 meter snoer,</p><p>Met naam sticker en bewaartas</p><p>Zachte hoofdband</p><p>Verstelbare zachte oorschelpen</p><p>max volume 85dB </p>', 'ja'),
(2, 'Hoofdtelefoons', 'Comfort', 'HP-305', '<h3>Comfortabele hoofdtelefoon voor een scherpe prijs</h3><p><br></p><p>✔ <strong>Eenzijdig snoer</strong> van 2 meter – raakt minder snel in de knoop en wordt niet snel in de mond genomen.</p><p>✔ <strong>3,5 mm rechte stekker</strong> – past altijd.</p><p>✔ <strong>Zachte oorschelpen en verstelbare hoofdband</strong> – voor een prettige pasvorm.</p><p>✔ <strong>Opklapbare oorschelpen</strong> – handig bij het opbergen.</p><p><br></p><p>Optioneel verkrijgbaar met een <strong>gerecyclede denim bewaartas</strong>, waarop een naam geschreven kan worden. Voorkomt dat het snoer in de knoop raakt.</p><p><br></p><p><strong>Verpakking:</strong></p><p><strong>🛍 Per stuk in een zakje</strong></p><p><strong>📦 Per 50 in een doos</strong></p><p><strong>📦 Overdoos van 100 stuks</strong></p><p><br></p><p>Zonder blisterverpakking – compact en eenvoudig op te slaan.</p><p><br></p>', '50 2,29\n100 2,09\n200 2,03', 50, '2024-12-27 16:01:05', '➡ Uitstekende prijs-kwaliteitverhouding\n➡ Lang, enkelzijdig snoer\n➡ Verstelbare hoofdband – past altijd comfortabel\n➡ Zachte, opklapbare oorschelpen', '<p><strong>Comfort hoofdtelefoon</strong></p><p>2 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja'),
(3, 'Hoofdtelefoons', 'Nekband', 'HP-122', '<p>Nekband Koptelefoon</p><ul><li>Snoer:&nbsp;Robuust nylon, beschermd tegen beschadiging.</li></ul><p>Specificaties:</p><ul><li>Elk hygiënisch verpakt in een nette opbergtas.</li><li>Minimalistische opslag zonder overbodig afval: Geen blister of extra doos.</li><li>Aantal: 32 stuks per doos.</li><li>Certificeringen:&nbsp;De koptelefoon is CE-gecertificeerd en voldoet aan zowel de RoHS als de Reach standaard.</li><li>Snoerlengte:&nbsp;1,8 meter, voorzien van een standaard 3,5 mm rechte stereo stekker.</li></ul><p>Bijzonder geschikt voor:</p><ul><li>Scholen. De nekband koptelefoon is uitermate geschikt voor jongere kinderen dankzij zijn comfortabele pasvorm en duurzaamheid.</li><li>Uitstekende hoofdtelefoon voor school gebruik.</li></ul><p><br></p>', '32 4,31\n64 4,05\n128 3,92', 32, '2025-01-06 13:26:28', '<p>nekband uitvoering</p><p>met nylon verstevigd snoer</p>', '<p>Nekband koptelefoon</p><p>1,8 meter snoer</p><p>degelijk</p>', 'ja'),
(4, 'Hoofdtelefoons', 'Comfort', 'HP-316 ultrasound', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '1', 50, '2025-02-03 14:32:21', 'Ultrasound Hoofdtelefoon\n1,8 meter enkelzijdig snoer\nDraaibare oorschelpen', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>', 'ja'),
(5, 'Hoofdtelefoons', 'Budget', 'HP-2706', '<p>Budget hoofdtelefoon</p><p><br></p>', '1', 125, '2025-02-03 14:32:21', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>', 'ja'),
(6, 'Hoofdtelefoons', 'Budget', 'HP-2710', '<p>Betaalbare kleurrijke koptelefoon.</p><p><br></p><p><br></p><p>Uitermate geschikt voor:</p><p><br></p><p>Scholen en bibliotheken: een budgetvriendelijke, hygiënische oplossing voor leerlingen. Geniet van een rijke luisterervaring in musea of verbeter het onderwijs met onze koptelefoons.</p><p><br></p><p>Ziekenhuizen: waar deze hoofdtelefoons naadloos integreren met patiënten entertainment systeem. Biedt extra comfort en bewegingsvrijheid dankzij de 1,8 meter lange kabel.</p><p><br></p><p>Revalidatiecentra: waar patiënten vaak bedlegerig zijn, biedt de lange kabel van de HP-2710 minder belasting op de televisie of computer aansluiting en meer bewegingsvrijheid.</p><p><br></p><p>Elke doos bevat 125 hoofdtelefoons in een gelijke verdeling van vier vrolijke kleuren.</p><p><br></p><p>De HP-2710 wordt alleen per doos verkocht, wat kostenefficiënt is.</p><p>De hoofdtelefoons zitten per stuk in een zakje verpakt, dus geen overbodig afval en nodeloos kostenverhogend blister.</p><p><br></p><p>Specs:</p><p>Verstelbare oorschelpen</p><p>Verstelbare kunststof hoofdband</p><p>Hygienisch per stuk in een zakje verpakt.</p><p>Weinig opslag en geen overbodig afval. (Geen blister of doosje).</p><p>125 stuks per doos.</p><p><br></p><p>Per overdoos: 31 rode, 31 groene, 31 blauwe en 32 zwarte.</p><p><br></p><p>De hoofdtelefoon is CE gekeurd en voldoet aan de RoHS en Reach standaard.</p><p>1,8 meter snoer met standaard 3,5 mm rechte stereo stekker.</p><p><br></p>', '24 2,29\n125 1,35\n250 1,25 ', 125, '2025-02-03 14:40:37', '<p>Budget koptelefoon</p><p>4 vrolijke kleuren</p><p>Geen bliser - geen afval</p>', '<p>Budget hoofdtelefoon</p><p>4 verschillende vrolijke kleuren</p><p>rood - wit - blauw en zwart</p>', 'ja'),
(7, 'Oortjes', 'In doosje', 'HP-32', '<p><strong>Comfortabel en veilig in gebruik</strong></p><p>Dit oortje wordt geleverd in een stevige bewaardoos en heeft een volumebegrenzing op 84 dB, zodat het gehoor beschermd blijft. Dankzij de drie maten eartips zit het altijd perfect.</p><p><br></p><p><strong>Slim verpakt &amp; handig gelabeld</strong></p><p>Per stuk verpakt in een zakje en geleverd per 50 in een overdoos (geen blister). Elke set bevat een beschrijfbare naamsticker, zodat altijd duidelijk is van wie het oortje is – ideaal voor scholen, bedrijven of gedeeld gebruik.</p><p><br></p><p>Kies je kleur</p><p>Verkrijgbaar in verschillende kleuren.</p><p><br></p><p>Prijs per doos van 50 stuks, inclusief naamstickers.</p>', '50 1,65\n100 1,52\n200 1,39', 50, '2025-02-03 14:40:37', 'Oortje in opbergdoosje\n1 meter snoer\nMet 3 maten eartips\nMet beschrijfbare naamsticker', '<p>Oortje in opbergdoosje</p><p>1 meter snoer</p><p>Met 3 maten eartips</p><p><strong>Met beschrijfbare naamsticker</strong></p>', 'ja'),
(14, 'Hoofdtelefoons', 'Degelijk', 'HP-136 S-KOPIE', '<p><strong>Onze klassieke en betrouwbare HP-136</strong></p><p><br></p><p>Ontdek de vernieuwde HP-136, nu uitgerust met een afneembaar snoer, een handige bewaartas en een naamsticker, zodat altijd duidelijk is welke hoofdtelefoon van wie is.</p><p><br></p><p><strong>Comfort en design:</strong></p><p>De hoofdtelefoon heeft een metalen hoofdband bekleed met stof en verstelbare, zacht beklede oorschelpen. Het maximale volume is begrensd op 85 dB, wat veilig gebruik garandeert. De hoofdtelefoon zit zeer comfortabel en is geschikt voor urenlang probleemloos gebruik.</p><p><br></p><p><strong>Verpakking en accessoires:</strong></p><p>Per stuk verpakt in een zakje, of per 32 stuks in een overdoos.</p><p>Enkelzijdig afneembaar en vervangbaar, met een lengte van 1,2 meter.</p><p><br></p><p><strong>Type: </strong>Halfopen</p><p><br></p>', '32 7,86\n64 7,64\n128 7,24\n256 6.95', 32, '2025-02-10 08:41:04', '<p>Versterkt afneembaar snoer</p><p>Zachte oorschelpen</p><p>Verstelbaar</p><p>In bewaartas</p><p>Met naam sticker</p>', '<p>Afneembaar 1,2 meter snoer,</p><p>Met naam sticker en bewaartas</p><p>Zachte hoofdband</p><p>Verstelbare zachte oorschelpen</p><p>max volume 85dB </p>', 'ja'),
(15, 'Oortjes', 'Microfoon en hoesje', 'i-900 N', '<p><br></p>', '1', 192, '2025-02-10 12:29:10', '<p>Budget oortje</p><p>Verstevigd snoer</p><p>met bewaartasje</p>', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>', 'ja');

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
(3, 'Peter', 'Felis', 'M', 'peter@felis.nl', '$2y$10$dvrmztV4V5tIs4Mryn8lAOMbr6xeu56PVDHE0pYwCqlWI.3BVFPlG', 'admin', NULL, NULL, 2, '2025-02-15 14:54:53', 1, NULL, NULL, NULL);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT voor een tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
