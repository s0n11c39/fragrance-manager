-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: lcree.lima-db.de:3306
-- Erstellungszeit: 16. Mai 2025 um 02:08
-- Server-Version: 8.0.39-30
-- PHP-Version: 7.2.34

--
-- Datenbank: `db_435211_1`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `defects`
--

DROP TABLE IF EXISTS `defects`;
CREATE TABLE `defects` (
  `id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `duft` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `problem` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `access_token` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `defect_messages`
--

DROP TABLE IF EXISTS `defect_messages`;
CREATE TABLE `defect_messages` (
  `id` int NOT NULL,
  `defect_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE `notes` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `scents`
--

DROP TABLE IF EXISTS `scents`;
CREATE TABLE `scents` (
  `id` int NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `inspired_by` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gender` enum('Herren','Damen','Unisex') COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `direction` text COLLATE utf8mb4_general_ci,
  `use_pyramid` tinyint(1) NOT NULL DEFAULT '1',
  `qr_code` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `scent_attributes`
--

DROP TABLE IF EXISTS `scent_attributes`;
CREATE TABLE `scent_attributes` (
  `id` int NOT NULL,
  `scent_id` int DEFAULT NULL,
  `category` enum('Dufttyp','Stil','Jahreszeit','Anlass') COLLATE utf8mb4_general_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `value` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `scent_notes`
--

DROP TABLE IF EXISTS `scent_notes`;
CREATE TABLE `scent_notes` (
  `id` int NOT NULL,
  `scent_id` int DEFAULT NULL,
  `type` enum('Kopf','Herz','Basis','All') COLLATE utf8mb4_general_ci NOT NULL,
  `note_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('admin','editor','user') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `defects`
--
ALTER TABLE `defects`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `defect_messages`
--
ALTER TABLE `defect_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `defect_id` (`defect_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indizes für die Tabelle `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `scents`
--
ALTER TABLE `scents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indizes für die Tabelle `scent_attributes`
--
ALTER TABLE `scent_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scent_id` (`scent_id`);

--
-- Indizes für die Tabelle `scent_notes`
--
ALTER TABLE `scent_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scent_id` (`scent_id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `defects`
--
ALTER TABLE `defects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `defect_messages`
--
ALTER TABLE `defect_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `scents`
--
ALTER TABLE `scents`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `scent_attributes`
--
ALTER TABLE `scent_attributes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `scent_notes`
--
ALTER TABLE `scent_notes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `defect_messages`
--
ALTER TABLE `defect_messages`
  ADD CONSTRAINT `defect_messages_ibfk_1` FOREIGN KEY (`defect_id`) REFERENCES `defects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `defect_messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `scent_attributes`
--
ALTER TABLE `scent_attributes`
  ADD CONSTRAINT `scent_attributes_ibfk_1` FOREIGN KEY (`scent_id`) REFERENCES `scents` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `scent_notes`
--
ALTER TABLE `scent_notes`
  ADD CONSTRAINT `scent_notes_ibfk_1` FOREIGN KEY (`scent_id`) REFERENCES `scents` (`id`) ON DELETE CASCADE;
