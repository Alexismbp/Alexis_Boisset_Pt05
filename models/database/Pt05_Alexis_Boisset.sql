-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 08-01-2025 a las 20:19:50
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `Pt05_Alexis_Boisset`
--
CREATE DATABASE IF NOT EXISTS `Pt05_Alexis_Boisset` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `Pt05_Alexis_Boisset`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `match_id` (`match_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `articles`
--

INSERT INTO `articles` (`id`, `match_id`, `user_id`, `title`, `content`, `created_at`) VALUES
(1, 93, 5, 'O James', 'Que habilidad asd', '2024-12-03 03:20:26'),
(2, 91, 5, 'Ansu Fati', 'Butanero', '2024-12-03 03:20:53'),
(4, 95, 2, 'SAD', 'ASD', '2024-12-03 04:31:40'),
(6, 98, 5, 'Empate', 'Derbi de pueblo', '2024-12-04 19:07:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equips`
--

CREATE TABLE IF NOT EXISTS `equips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `lliga_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`),
  KEY `fk_lliga` (`lliga_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equips`
--

INSERT INTO `equips` (`id`, `nom`, `lliga_id`) VALUES
(1, 'FC Barcelona', 1),
(2, 'Real Madrid', 1),
(3, 'Sevilla FC', 1),
(4, 'Real Betis', 1),
(5, 'Atlético de Madrid', 1),
(6, 'Valencia CF', 1),
(7, 'Villarreal CF', 1),
(8, 'Celta de Vigo', 1),
(9, 'Real Sociedad', 1),
(10, 'Athletic Club', 1),
(11, 'Getafe CF', 1),
(12, 'Espanyol', 1),
(13, 'Deportivo Alavés', 1),
(14, 'Rayo Vallecano', 1),
(15, 'Cádiz', 1),
(16, 'RCD Mallorca', 1),
(17, 'Girona FC', 1),
(18, 'CA Osasuna', 1),
(19, 'Granada CF', 1),
(20, 'UD Las Palmas', 1),
(21, 'Manchester City', 2),
(22, 'Manchester United', 2),
(23, 'Liverpool', 2),
(24, 'Chelsea', 2),
(25, 'Arsenal', 2),
(26, 'Tottenham', 2),
(27, 'Leicester City', 2),
(28, 'Everton', 2),
(29, 'Newcastle United', 2),
(30, 'West Ham United', 2),
(31, 'Crystal Palace', 2),
(32, 'Brighton', 2),
(33, 'Aston Villa', 2),
(34, 'Wolverhampton', 2),
(35, 'Burnley', 2),
(36, 'Fulham', 2),
(37, 'Southampton', 2),
(38, 'Leeds', 2),
(39, 'Brentford', 2),
(40, 'Sheffield United', 2),
(41, 'Paris Saint-Germain', 3),
(42, 'Olympique de Marseille', 3),
(43, 'Olympique Lyonnais', 3),
(44, 'Lille OSC', 3),
(45, 'AS Monaco', 3),
(46, 'OGC Nice', 3),
(47, 'Bordeaux', 3),
(48, 'Saint-Etienne', 3),
(49, 'Stade Rennais', 3),
(50, 'FC Nantes', 3),
(51, 'Montpellier HSC', 3),
(52, 'RC Strasbourg', 3),
(53, 'RC Lens', 3),
(54, 'Stade de Reims', 3),
(55, 'Stade Brestois 29', 3),
(56, 'Angers SCO', 3),
(57, 'Toulouse FC', 3),
(58, 'FC Lorient', 3),
(59, 'FC Metz', 3),
(60, 'Clermont Foot', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lligues`
--

CREATE TABLE IF NOT EXISTS `lligues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lligues`
--

INSERT INTO `lligues` (`id`, `nom`) VALUES
(1, 'LaLiga'),
(3, 'Ligue 1'),
(2, 'Premier League');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partits`
--

CREATE TABLE IF NOT EXISTS `partits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `equip_local_id` int(11) NOT NULL,
  `equip_visitant_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `gols_local` tinyint(4) DEFAULT NULL,
  `gols_visitant` tinyint(4) DEFAULT NULL,
  `jugat` tinyint(1) DEFAULT 0,
  `liga_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `equip_local_id` (`equip_local_id`),
  KEY `equip_visitant_id` (`equip_visitant_id`),
  KEY `fk_liga` (`liga_id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partits`
--

INSERT INTO `partits` (`id`, `equip_local_id`, `equip_visitant_id`, `data`, `gols_local`, `gols_visitant`, `jugat`, `liga_id`) VALUES
(31, 1, 2, '2024-11-01', 2, 1, 1, 1),
(32, 3, 4, '2024-11-02', NULL, NULL, 0, 1),
(33, 5, 6, '2024-11-03', 1, 1, 1, 1),
(34, 7, 8, '2024-11-04', NULL, NULL, 0, 1),
(35, 9, 10, '2024-11-05', 3, 0, 1, 1),
(36, 11, 12, '2024-11-06', NULL, NULL, 0, 1),
(37, 13, 14, '2024-11-07', 1, 0, 1, 1),
(38, 15, 16, '2024-11-08', NULL, NULL, 0, 1),
(39, 17, 18, '2024-11-09', 2, 2, 1, 1),
(40, 19, 20, '2024-11-10', NULL, NULL, 0, 1),
(41, 1, 3, '2024-11-11', 2, 0, 1, 1),
(42, 2, 4, '2024-11-12', NULL, NULL, 0, 1),
(43, 5, 7, '2024-11-13', 3, 2, 1, 1),
(44, 6, 8, '2024-11-14', NULL, NULL, 0, 1),
(45, 9, 11, '2024-11-15', 1, 1, 1, 1),
(46, 12, 14, '2024-11-16', NULL, NULL, 0, 1),
(47, 13, 15, '2024-11-17', 1, 0, 1, 1),
(48, 16, 18, '2024-11-18', NULL, NULL, 0, 1),
(49, 17, 19, '2024-11-19', 2, 0, 1, 1),
(50, 10, 20, '2024-11-20', NULL, NULL, 0, 1),
(51, 21, 22, '2024-11-01', 1, 2, 1, 2),
(52, 23, 24, '2024-11-02', NULL, NULL, 0, 2),
(53, 25, 26, '2024-11-03', 0, 0, 1, 2),
(54, 27, 28, '2024-11-04', NULL, NULL, 0, 2),
(55, 29, 30, '2024-11-05', 3, 1, 1, 2),
(56, 31, 32, '2024-11-06', NULL, NULL, 0, 2),
(57, 33, 34, '2024-11-07', 2, 1, 1, 2),
(58, 35, 36, '2024-11-08', NULL, NULL, 0, 2),
(59, 37, 38, '2024-11-09', 1, 3, 1, 2),
(60, 39, 40, '2024-11-10', NULL, NULL, 0, 2),
(61, 21, 23, '2024-11-11', 2, 2, 1, 2),
(62, 22, 24, '2024-11-12', NULL, NULL, 0, 2),
(63, 25, 27, '2024-11-13', 1, 0, 1, 2),
(64, 26, 28, '2024-11-14', NULL, NULL, 0, 2),
(65, 29, 31, '2024-11-15', 3, 2, 1, 2),
(66, 32, 34, '2024-11-16', NULL, NULL, 0, 2),
(67, 33, 35, '2024-11-17', 1, 1, 1, 2),
(68, 36, 38, '2024-11-18', NULL, NULL, 0, 2),
(69, 37, 39, '2024-11-19', 2, 0, 1, 2),
(70, 40, 40, '2024-11-20', NULL, NULL, 0, 2),
(71, 41, 42, '2024-11-01', 2, 2, 1, 3),
(72, 43, 44, '2024-11-02', NULL, NULL, 0, 3),
(74, 47, 48, '2024-11-04', NULL, NULL, 0, 3),
(75, 49, 50, '2024-11-05', 3, 0, 1, 3),
(76, 51, 52, '2024-11-06', NULL, NULL, 0, 3),
(77, 53, 54, '2024-11-07', 1, 0, 1, 3),
(78, 55, 56, '2024-11-08', NULL, NULL, 0, 3),
(79, 57, 58, '2024-11-09', 2, 3, 1, 3),
(80, 59, 60, '2024-11-10', NULL, NULL, 0, 3),
(81, 41, 43, '2024-11-11', 3, 1, 1, 3),
(82, 42, 44, '2024-11-12', NULL, NULL, 0, 3),
(83, 45, 47, '2024-11-13', 2, 1, 1, 3),
(85, 49, 51, '2024-11-15', 1, 1, 1, 3),
(86, 52, 54, '2024-11-16', NULL, NULL, 0, 3),
(87, 53, 55, '2024-11-17', 1, 0, 1, 3),
(88, 56, 58, '2024-11-18', NULL, NULL, 0, 3),
(89, 57, 59, '2024-11-19', 2, 2, 1, 3),
(90, 60, 60, '2024-11-20', NULL, NULL, 0, 3),
(91, 46, 45, '2024-10-25', 1, 2, 1, 3),
(93, 46, 41, '2024-11-15', 2, 3, 1, 3),
(95, 17, 5, '2024-12-20', 2, 0, 1, 1),
(97, 2, 5, '2024-12-11', 2, 1, 1, 1),
(98, 1, 17, '2024-12-11', 2, 2, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shared_articles`
--

CREATE TABLE IF NOT EXISTS `shared_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(64) NOT NULL,
  `article_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `show_title` tinyint(1) DEFAULT 0,
  `show_content` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `article_id` (`article_id`),
  KEY `match_id` (`match_id`),
  KEY `idx_token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `shared_articles`
--

INSERT INTO `shared_articles` (`id`, `token`, `article_id`, `match_id`, `show_title`, `show_content`, `created_at`) VALUES
(1, 'ef9824422842ab95be192a1fee939da9', 2, 91, 1, 0, '2024-12-16 18:59:55'),
(2, '966fc91fe592ac7b5111cf339a323434', 2, 91, 1, 0, '2024-12-16 18:59:55'),
(3, 'fc4d5ae123fee2acadf8d9b0defa1503', 2, 91, 1, 1, '2024-12-16 18:59:56'),
(4, 'e725376c0660a7b59887f97ca15bf457', 2, 91, 1, 1, '2024-12-16 18:59:57'),
(5, 'de58cd786ce7fd53028601733e868741', 2, 91, 1, 0, '2024-12-16 19:00:01'),
(6, 'fa782d302becd359179a68359e82cd2d', 2, 91, 1, 0, '2024-12-16 19:00:54'),
(7, '15a4351856dcba13bd4598e2dd169999', 2, 91, 1, 0, '2024-12-16 19:00:55'),
(8, 'ada59a670f3c052fdb140e96884a0605', 2, 91, 1, 0, '2024-12-16 19:05:27'),
(9, '10fd67b204ba998096717ffd539be06f', 2, 91, 1, 0, '2024-12-16 19:05:27'),
(10, '5267590361d9186cc2f5dbd649269788', 2, 91, 1, 0, '2024-12-16 19:05:27'),
(11, '34d3d0f98e9738ab8a5bb7ee6735b536', 2, 91, 0, 1, '2024-12-16 19:07:41'),
(12, '1c7e559d3ccce01d242ec714c61165c7', 2, 91, 1, 1, '2024-12-16 19:16:06'),
(13, '2130ed3df97ff888fc4429e5cab3ced8', 2, 91, 1, 0, '2024-12-16 19:16:09'),
(14, 'a9b1c18f4430dd6d464d12e21beb2eb7', 2, 91, 1, 0, '2024-12-16 19:16:13'),
(15, '8b11225ac751a366d82ffb5be853e8c9', 2, 91, 1, 0, '2024-12-16 19:16:13'),
(16, 'eaa3fd60245454e9a619a1a75196dbc1', 2, 91, 1, 0, '2024-12-16 19:16:14'),
(17, '1565ebc6ab4b228b23a4e4c6beb6de4c', 2, 91, 1, 0, '2024-12-16 19:16:14'),
(18, 'fadc863ac95ae9dbb0c4245eba7c7a20', 2, 91, 1, 0, '2024-12-16 19:16:15'),
(19, 'db7ca7ce5c312394335b05dd5d03ef61', 2, 91, 1, 0, '2024-12-16 19:16:15'),
(20, 'a79cb89d213414ec58adf0fd37df924f', 2, 91, 1, 0, '2024-12-16 19:16:15'),
(21, '1cbac60600c0c19988ce94c6a15d53f2', 2, 91, 1, 0, '2024-12-16 19:16:16'),
(22, '7f20148ddd34ea4d5572debabee54975', 2, 91, 1, 0, '2024-12-16 19:16:16'),
(23, '8ad0d23cc4e3fe2480ff8fe85769e1fa', 2, 91, 0, 0, '2024-12-16 19:18:32'),
(24, 'b0947d7653dc67c7358517973fff9bad', 2, 91, 1, 0, '2024-12-18 18:27:06'),
(25, '25713f3d0570b5b75c5753aa50774a1e', 2, 91, 1, 1, '2024-12-18 18:50:56'),
(26, 'f9d418dd08e09c1b54d64715290901f4', 2, 91, 1, 1, '2024-12-18 18:50:56'),
(27, 'ebc5404e91037b4a9d1763249c4b5cfc', 2, 91, 1, 1, '2024-12-18 18:50:56'),
(28, '839c8284ba1a39d89324e096756c3d2d', 2, 91, 1, 1, '2024-12-18 18:50:56'),
(29, '23a06a7deb99b73ed105c40e564e3a16', 2, 91, 1, 1, '2024-12-18 18:50:56'),
(30, 'abb3861093dfd1084d2d4f6f61191399', 2, 91, 1, 1, '2024-12-18 18:50:56'),
(31, '2ecbf07a0b04526ba9d59a8d5c07fb36', 2, 91, 1, 1, '2024-12-18 18:50:56'),
(32, 'ba55a34b92d7682b4c28b6c455ccdf85', 2, 91, 1, 1, '2024-12-18 18:50:56'),
(33, '9224c9176d7696e07ca977d1c7803fff', 2, 91, 1, 1, '2024-12-18 18:50:57'),
(34, 'eabe62e96b78dad9d3d456fcc8870270', 2, 91, 1, 1, '2024-12-18 18:50:57'),
(35, 'cba70240d87bbffd86fcd25de6ee496f', 2, 91, 1, 0, '2024-12-18 19:12:33'),
(36, '1d1d6be66086bb623f2fdba0a00040aa', 4, 95, 0, 0, '2025-01-08 18:21:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuaris`
--

CREATE TABLE IF NOT EXISTS `usuaris` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_usuari` varchar(50) NOT NULL,
  `correu_electronic` varchar(100) NOT NULL,
  `contrasenya` varchar(255) DEFAULT NULL,
  `equip_favorit` varchar(100) NOT NULL,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `remember_token_expires` datetime DEFAULT NULL,
  `is_oauth_user` tinyint(1) DEFAULT 0,
  `oauth_provider` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom_usuari` (`nom_usuari`),
  UNIQUE KEY `correu_electronic` (`correu_electronic`),
  UNIQUE KEY `reset_token_hash` (`reset_token_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuaris`
--

INSERT INTO `usuaris` (`id`, `nom_usuari`, `correu_electronic`, `contrasenya`, `equip_favorit`, `reset_token_hash`, `reset_token_expires_at`, `remember_token`, `remember_token_expires`, `is_oauth_user`, `oauth_provider`, `avatar`) VALUES
(1, 'admin', 'admin@alexisboisset.cat', '$2y$10$tgJfJdYsf5kTq5psWW3EK.JrwbupzOeI7AfAE3KLSGBRURlp0EIAq', '', '4195ed43ff789e09910989a4f4b32ab3f2362f54f24608833fb4122c728b67e1', '2024-12-08 21:53:02', NULL, NULL, 0, '', NULL),
(2, 'Xavi', 'xavi@gmail.com', '$2y$10$CyjHCsfj9nNgrvf4BvUaIO9.mgEb4wrn3u7uWqQYZl43CfsO1Ueyi', 'Girona FC', NULL, NULL, NULL, NULL, 0, NULL, NULL),
(3, 'Josep', 'jpedrerol@gmail.com', '$2y$10$UzJrOph0LT2CCR8.w2qBVOKnk1gArl8UonbTGn3UYtLykVuDcY.z.', 'Crystal Palace', NULL, NULL, NULL, NULL, 0, NULL, NULL),
(4, 'Alexismbp', 'alexismarcbp@gmail.com', NULL, 'pendiente', 'e7e03731333e2722a74689d023e376f5d3d9b25dcf35b8cb1fae71a9775c4222', '2024-12-08 21:30:37', NULL, NULL, 1, 'github', NULL),
(5, 'SaPAlexis', 'a.boisset@sapalomera.cat', '$2y$10$locI6NR1S0Zt7oDvaV4Gf.wverj1wSU9FeJ3hr8ndvPf2O1CEq3LC', 'OGC Nice', '100e05311bbbfb18f288f830907720cb5a280b3883cbcc81bbe4141cc9636730', '2025-01-03 13:29:00', NULL, NULL, 0, '', NULL),
(7, 'Alexis', 'alexis@gmail.com', '$2y$10$5jOplzI9.lewF548D4UBwe.4Q/9QKm5EvfMdZX1V9e.K5U/ydH3pe', 'OGC Nice', NULL, NULL, NULL, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Nueva tabla para guardar la API Key de cada usuario
CREATE TABLE IF NOT EXISTS `api_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `api_key` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_key` (`api_key`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_user_api` FOREIGN KEY (`user_id`) REFERENCES `usuaris` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `partits` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `usuaris` (`id`);

--
-- Filtros para la tabla `equips`
--
ALTER TABLE `equips`
  ADD CONSTRAINT `fk_lliga` FOREIGN KEY (`lliga_id`) REFERENCES `lligues` (`id`);

--
-- Filtros para la tabla `partits`
--
ALTER TABLE `partits`
  ADD CONSTRAINT `fk_liga` FOREIGN KEY (`liga_id`) REFERENCES `lligues` (`id`),
  ADD CONSTRAINT `partits_ibfk_1` FOREIGN KEY (`equip_local_id`) REFERENCES `equips` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `partits_ibfk_2` FOREIGN KEY (`equip_visitant_id`) REFERENCES `equips` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `shared_articles`
--
ALTER TABLE `shared_articles`
  ADD CONSTRAINT `shared_articles_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shared_articles_ibfk_2` FOREIGN KEY (`match_id`) REFERENCES `partits` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
