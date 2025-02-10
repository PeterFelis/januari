-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 10, 2025 at 08:44 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

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
-- Table structure for table `crm_notes`
--

CREATE TABLE `crm_notes` (
  `id` int NOT NULL,
  `klant_id` int NOT NULL,
  `datum` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `opmerking` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `klanten`
--

CREATE TABLE `klanten` (
  `id` int NOT NULL,
  `naam` varchar(100) NOT NULL,
  `adres` text,
  `contact_email` varchar(150) DEFAULT NULL,
  `telefoonnummer` varchar(20) DEFAULT NULL,
  `aangemaakt_op` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `klanten`
--

INSERT INTO `klanten` (`id`, `naam`, `adres`, `contact_email`, `telefoonnummer`, `aangemaakt_op`) VALUES
(1, 'Fetum', 'Grote Waard 36', 'verkoop@fetum.nl', '0656653', '2025-01-06 11:55:06'),
(2, 'Dinges', 'straat', 'dinges@dinges.nl', '0123', '2025-02-05 14:51:07');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
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
-- Table structure for table `order_items`
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
-- Table structure for table `products`
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
  `sticker_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `categorie`, `subcategorie`, `TypeNummer`, `omschrijving`, `prijsstaffel`, `aantal_per_doos`, `aangemaakt_op`, `USP`, `sticker_text`) VALUES
(1, 'Hoofdtelefoons', 'Degelijk', 'HP-136 S', '<p><strong>Onze klassieke en betrouwbare HP-136</strong></p><p><br></p><p>Ontdek de vernieuwde HP-136, nu uitgerust met een afneembaar snoer, een handige bewaartas en een naamsticker, zodat altijd duidelijk is welke hoofdtelefoon van wie is.</p><p><br></p><p><strong>Comfort en design:</strong></p><p>De hoofdtelefoon heeft een metalen hoofdband bekleed met stof en verstelbare, zacht beklede oorschelpen. Het maximale volume is begrensd op 85 dB, wat veilig gebruik garandeert. De hoofdtelefoon zit zeer comfortabel en is geschikt voor urenlang probleemloos gebruik.</p><p><br></p><p><strong>Verpakking en accessoires:</strong></p><p>Per stuk verpakt in een zakje, of per 32 stuks in een overdoos.</p><p>Enkelzijdig afneembaar en vervangbaar, met een lengte van 1,2 meter.</p><p><br></p><p><strong>Type: </strong>Halfopen</p><p><br></p>', '32 7,86\n64 7,64\n128 7,24\n256 6.95', 32, '2024-12-27 15:59:45', 'Versterkt afneembaar snoer\nZachte oorschelpen\nVerstelbaar\nIn bewaartas\nMet naam sticker\n', '<p>Afneembaar 1,2 meter snoer,</p><p>Met naam sticker en bewaartas</p><p>Zachte hoofdband</p><p>Verstelbare zachte oorschelpen</p><p>max volume 85dB </p>'),
(2, 'Hoofdtelefoons', 'Comfort', 'HP-305', '<h3>Comfortabele hoofdtelefoon voor een scherpe prijs</h3><p><br></p><p>✔ <strong>Eenzijdig snoer</strong> van 2 meter – raakt minder snel in de knoop en wordt niet snel in de mond genomen.</p><p>✔ <strong>3,5 mm rechte stekker</strong> – past altijd.</p><p>✔ <strong>Zachte oorschelpen en verstelbare hoofdband</strong> – voor een prettige pasvorm.</p><p>✔ <strong>Opklapbare oorschelpen</strong> – handig bij het opbergen.</p><p><br></p><p>Optioneel verkrijgbaar met een <strong>gerecyclede denim bewaartas</strong>, waarop een naam geschreven kan worden. Voorkomt dat het snoer in de knoop raakt.</p><p><br></p><p><strong>Verpakking:</strong></p><p><strong>🛍 Per stuk in een zakje</strong></p><p><strong>📦 Per 50 in een doos</strong></p><p><strong>📦 Overdoos van 100 stuks</strong></p><p><br></p><p>Zonder blisterverpakking – compact en eenvoudig op te slaan.</p><p><br></p>', '50 2,29\n100 2,09\n200 2,03', 50, '2024-12-27 16:01:05', '➡ Uitstekende prijs-kwaliteitverhouding\n➡ Lang, enkelzijdig snoer\n➡ Verstelbare hoofdband – past altijd comfortabel\n➡ Zachte, opklapbare oorschelpen', '<p><strong>Comfort hoofdtelefoon</strong></p><p>2 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>'),
(3, 'Hoofdtelefoons', 'Nekband', 'HP-122', '<p><br></p>', '0 2,00\n1 4,00', 32, '2025-01-06 13:26:28', '', '<p><br></p>'),
(4, 'Hoofdtelefoons', 'Comfort', 'HP-316 ultrasound', '<p>Ultrasound Hoofdtelefoon</p><p>1,8 meter enkelzijdig snoer</p><p>Draaibare oorschelpen</p>', '1', 50, '2025-02-03 14:32:21', 'Ultrasound Hoofdtelefoon\n1,8 meter enkelzijdig snoer\nDraaibare oorschelpen', '<p><strong>Ultrasound Hoofdtelefoon</strong></p><p>1,8 meter snoer</p><p>Enkelzijdig snoer</p><p>Draaibare oorschelpen</p><p>Verstelbare hoofdband</p>'),
(5, 'Hoofdtelefoons', 'Budget', 'HP-2706', '<p>Budget hoofdtelefoon</p><p><br></p>', '1', 125, '2025-02-03 14:32:21', 'Budget hoofdtelefoon\n2 meter snoer, stereo stekker\nGeen blister verpakking', '<p>Budget hoofdtelefoon</p><p>2 meter snoer, stereo stekker</p><p>Geen blister verpakking</p>'),
(6, 'Hoofdtelefoons', 'Budget', 'HP-2710', '<p>kleurtjes		</p>', '1', 125, '2025-02-03 14:40:37', 'budget', '<p>Budget hoofdtelefoon</p><p>4 verschillende vrolijke kleuren</p><p>rood - wit - blauw en zwart</p>'),
(7, 'Oortjes', 'Microfoon en hoesje', 'i-900 N', '<p><br></p>', '1', 125, '2025-02-03 14:40:37', 'budget', '<p>Witte oortjes</p><p>Verstevigd snoer</p><p>Met microfoon</p><p><strong>Met bewaartasje</strong></p>'),
(14, 'Hoofdtelefoons', 'Degelijk', 'HP-136 S-KOPIE', '<p><strong>Onze klassieke en betrouwbare HP-136</strong></p><p><br></p><p>Ontdek de vernieuwde HP-136, nu uitgerust met een afneembaar snoer, een handige bewaartas en een naamsticker, zodat altijd duidelijk is welke hoofdtelefoon van wie is.</p><p><br></p><p><strong>Comfort en design:</strong></p><p>De hoofdtelefoon heeft een metalen hoofdband bekleed met stof en verstelbare, zacht beklede oorschelpen. Het maximale volume is begrensd op 85 dB, wat veilig gebruik garandeert. De hoofdtelefoon zit zeer comfortabel en is geschikt voor urenlang probleemloos gebruik.</p><p><br></p><p><strong>Verpakking en accessoires:</strong></p><p>Per stuk verpakt in een zakje, of per 32 stuks in een overdoos.</p><p>Enkelzijdig afneembaar en vervangbaar, met een lengte van 1,2 meter.</p><p><br></p><p><strong>Type: </strong>Halfopen</p><p><br></p>', '32 7,86\n64 7,64\n128 7,24\n256 6.95', 32, '2025-02-10 08:41:04', 'Versterkt afneembaar snoer\nZachte oorschelpen\nVerstelbaar\nIn bewaartas\nMet naam sticker\n', '<p>Afneembaar 1,2 meter snoer,</p><p>Met naam sticker en bewaartas</p><p>Zachte hoofdband</p><p>Verstelbare zachte oorschelpen</p><p>max volume 85dB </p>');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `key_name` varchar(50) NOT NULL,
  `value_text` text NOT NULL,
  `aangemaakt_op` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `naam` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `wachtwoord` varchar(255) NOT NULL,
  `rol` enum('klant','admin') DEFAULT 'klant',
  `google_id` varchar(255) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `klant_id` int DEFAULT NULL,
  `aangemaakt_op` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `naam`, `email`, `wachtwoord`, `rol`, `google_id`, `avatar_url`, `klant_id`, `aangemaakt_op`) VALUES
(1, 'Peter', 'Peter@felis.nl', '$2y$10$z36pvzx9NTjzJ3kXV5pSpOuAUE2mwmEoczgg9ycNlFqEX3P4EVTI2', 'admin', NULL, NULL, 1, '2025-01-06 11:55:06'),
(2, 'Titus', 'Titus@titus.nl', '$2y$10$XwRMxqbCcwxCBMtYIDhQaOMOw2WPMyExsRO9GcoA31gPrRZfnM9HK', 'klant', NULL, NULL, 2, '2025-02-05 14:51:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `crm_notes`
--
ALTER TABLE `crm_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klant_id` (`klant_id`);

--
-- Indexes for table `klanten`
--
ALTER TABLE `klanten`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klant_id` (`klant_id`),
  ADD KEY `contactpersoon_id` (`contactpersoon_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `google_id` (`google_id`),
  ADD KEY `klant_id` (`klant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `crm_notes`
--
ALTER TABLE `crm_notes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `klanten`
--
ALTER TABLE `klanten`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `crm_notes`
--
ALTER TABLE `crm_notes`
  ADD CONSTRAINT `crm_notes_ibfk_1` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`contactpersoon_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`klant_id`) REFERENCES `klanten` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
