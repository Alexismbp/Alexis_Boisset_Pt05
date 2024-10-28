-- Alexis Boisset --
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 25-10-2024 a las 22:17:58
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

DROP DATABASE IF EXISTS `Pt05_Alexis_Boisset`;

CREATE DATABASE IF NOT EXISTS `Pt05_Alexis_Boisset`;
USE `Pt05_Alexis_Boisset`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
--
-- Base de datos: `Pt05_Alexis_Boisset`
--

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `equips`
--

CREATE TABLE `equips` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `lliga_id` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Volcado de datos para la tabla `equips`
--

INSERT INTO `equips` (`id`, `nom`, `lliga_id`)
VALUES (1, 'FC Barcelona', 1),
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

CREATE TABLE `lligues` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Volcado de datos para la tabla `lligues`
--

INSERT INTO `lligues` (`id`, `nom`)
VALUES (1, 'LaLiga'),
  (3, 'Ligue 1'),
  (2, 'Premier League');
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `partits`
--

CREATE TABLE `partits` (
  `id` int(11) NOT NULL,
  `equip_local_id` int(11) NOT NULL,
  `equip_visitant_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `gols_local` tinyint(4) DEFAULT NULL,
  `gols_visitant` tinyint(4) DEFAULT NULL,
  `jugat` tinyint(1) DEFAULT 0,
  `liga_id` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Volcado de datos para la tabla `partits`
--

INSERT INTO `partits` (
    `id`,
    `equip_local_id`,
    `equip_visitant_id`,
    `data`,
    `gols_local`,
    `gols_visitant`,
    `jugat`,
    `liga_id`
  )
VALUES (31, 1, 2, '2024-11-01', 2, 1, 1, 1),
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
  (93, 46, 41, '2024-11-01', NULL, NULL, 0, 3);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `prediccions`
--

CREATE TABLE `prediccions` (
  `id` int(11) NOT NULL,
  `partit_id` int(11) NOT NULL,
  `usuari_id` int(11) NOT NULL,
  `gols_local` tinyint(4) DEFAULT NULL,
  `gols_visitant` tinyint(4) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `usuaris`
--

CREATE TABLE `usuaris` (
  `id` int(11) NOT NULL,
  `nom_usuari` varchar(50) NOT NULL,
  `correu_electronic` varchar(100) NOT NULL,
  `contrasenya` varchar(255) NOT NULL,
  `equip_favorit` varchar(100) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Volcado de datos para la tabla `usuaris`
--

INSERT INTO `usuaris` (
    `id`,
    `nom_usuari`,
    `correu_electronic`,
    `contrasenya`,
    `equip_favorit`
  )
VALUES (
    1,
    'Alexis',
    'alexis@gmail.com',
    '$2y$10$5jOplzI9.lewF548D4UBwe.4Q/9QKm5EvfMdZX1V9e.K5U/ydH3pe',
    'OGC Nice'
  ),
  (
    2,
    'Xavi',
    'xavi@gmail.com',
    '$2y$10$CyjHCsfj9nNgrvf4BvUaIO9.mgEb4wrn3u7uWqQYZl43CfsO1Ueyi',
    'Girona FC'
  ),
  (
    3,
    'Josep',
    'jpedrerol@gmail.com',
    '$2y$10$UzJrOph0LT2CCR8.w2qBVOKnk1gArl8UonbTGn3UYtLykVuDcY.z.',
    'Crystal Palace'
  );
--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `equips`
--
ALTER TABLE `equips`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`),
  ADD KEY `fk_lliga` (`lliga_id`);
--
-- Indices de la tabla `lligues`
--
ALTER TABLE `lligues`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);
--
-- Indices de la tabla `partits`
--
ALTER TABLE `partits`
ADD PRIMARY KEY (`id`),
  ADD KEY `equip_local_id` (`equip_local_id`),
  ADD KEY `equip_visitant_id` (`equip_visitant_id`),
  ADD KEY `fk_liga` (`liga_id`);
--
-- Indices de la tabla `prediccions`
--
ALTER TABLE `prediccions`
ADD PRIMARY KEY (`id`),
  ADD KEY `partit_id` (`partit_id`),
  ADD KEY `usuari_id` (`usuari_id`);
--
-- Indices de la tabla `usuaris`
--
ALTER TABLE `usuaris`
ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom_usuari` (`nom_usuari`),
  ADD UNIQUE KEY `correu_electronic` (`correu_electronic`);
--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `equips`
--
ALTER TABLE `equips`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 61;
--
-- AUTO_INCREMENT de la tabla `lligues`
--
ALTER TABLE `lligues`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 4;
--
-- AUTO_INCREMENT de la tabla `partits`
--
ALTER TABLE `partits`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 94;
--
-- AUTO_INCREMENT de la tabla `prediccions`
--
ALTER TABLE `prediccions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `usuaris`
--
ALTER TABLE `usuaris`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 4;
--
-- Restricciones para tablas volcadas
--

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
-- Filtros para la tabla `prediccions`
--
ALTER TABLE `prediccions`
ADD CONSTRAINT `prediccions_ibfk_1` FOREIGN KEY (`partit_id`) REFERENCES `partits` (`id`),
  ADD CONSTRAINT `prediccions_ibfk_2` FOREIGN KEY (`usuari_id`) REFERENCES `usuaris` (`id`);
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;