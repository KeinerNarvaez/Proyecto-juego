-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-11-2024 a las 13:55:10
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mwm_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accountactivation`
--

CREATE TABLE `accountactivation` (
  `accountActivationID` int(11) NOT NULL,
  `activation` int(11) NOT NULL,
  `activationCode` varchar(6) NOT NULL,
  `expires` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avatar`
--

CREATE TABLE `avatar` (
  `avartarID` int(11) NOT NULL,
  `avatar` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `history`
--

CREATE TABLE `history` (
  `historyID` int(11) NOT NULL,
  `onlyID` int(11) DEFAULT NULL,
  `bestScore` int(11) DEFAULT NULL,
  `timesCompleted` int(11) DEFAULT NULL,
  `timesDefeated` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hostroom`
--

CREATE TABLE `hostroom` (
  `hostRoomID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `roomCode` varchar(6) DEFAULT NULL,
  `levelPosition` int(11) DEFAULT NULL,
  `spelllevel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `hostroom`
--

INSERT INTO `hostroom` (`hostRoomID`, `userID`, `roomCode`, `levelPosition`, `spelllevel`) VALUES
(5, 1, 'KFC123', 0, 1),
(6, 2, 'KFC123', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `levelposition`
--

CREATE TABLE `levelposition` (
  `levelPositionID` int(11) NOT NULL,
  `level1` int(11) NOT NULL,
  `level2` int(11) NOT NULL,
  `level3` int(11) NOT NULL,
  `level4` int(11) NOT NULL,
  `level5` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `levelposition`
--

INSERT INTO `levelposition` (`levelPositionID`, `level1`, `level2`, `level3`, `level4`, `level5`) VALUES
(1, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login`
--

CREATE TABLE `login` (
  `loginID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `loginDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `online` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `login`
--

INSERT INTO `login` (`loginID`, `userID`, `password`, `email`, `loginDate`, `online`) VALUES
(4, 1, '$2y$10$PL/tzVC339rjpeqa.DDei.fX8J9/i/cPRBzOJQFG62Xz01nQfuJc.', 'ckeinercano@gmail.com', '2024-11-21 12:53:52', 0),
(5, 2, '$2y$10$fSK9ffnDQrRvh2fTzAz94.VDjhbrME0ZQhhItTEKkeFm9PkYlrZNm', 'cristoferlozano233@gmail.com', '2024-11-21 14:20:22', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `only`
--

CREATE TABLE `only` (
  `onlyID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `levelPositionID` int(11) NOT NULL,
  `spellLevelID` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `player`
--

CREATE TABLE `player` (
  `playerID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `roomCode` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `player`
--

INSERT INTO `player` (`playerID`, `userID`, `roomCode`) VALUES
(1, 1, 'KFC123'),
(3, 2, 'KFC123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `privatemath`
--

CREATE TABLE `privatemath` (
  `privateMatchID` int(11) NOT NULL,
  `hostRoomID` int(11) DEFAULT NULL,
  `playerID` int(11) DEFAULT NULL,
  `levelPositionID` int(11) DEFAULT NULL,
  `spelllevelID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `privatemath`
--

INSERT INTO `privatemath` (`privateMatchID`, `hostRoomID`, `playerID`, `levelPositionID`, `spelllevelID`) VALUES
(5, 5, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recoverpassword`
--

CREATE TABLE `recoverpassword` (
  `recoverPasswordID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `codeEstatus` int(11) NOT NULL,
  `applicationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `spelllevel`
--

CREATE TABLE `spelllevel` (
  `spellLevelID` int(11) NOT NULL,
  `level1` int(11) NOT NULL,
  `level2` int(11) NOT NULL,
  `level3` int(11) NOT NULL,
  `level4` int(11) NOT NULL,
  `level5` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `spelllevel`
--

INSERT INTO `spelllevel` (`spellLevelID`, `level1`, `level2`, `level3`, `level4`, `level5`) VALUES
(1, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `avatarID` int(11) NOT NULL,
  `accountActivationID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `gamerTag` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`userID`, `avatarID`, `accountActivationID`, `name`, `lastName`, `gamerTag`) VALUES
(1, 0, 4, 'KEINER', 'lozano', 'KANOA'),
(2, 0, 5, 'cristofer', 'Lozano', 'krizto');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accountactivation`
--
ALTER TABLE `accountactivation`
  ADD PRIMARY KEY (`accountActivationID`);

--
-- Indices de la tabla `avatar`
--
ALTER TABLE `avatar`
  ADD PRIMARY KEY (`avartarID`);

--
-- Indices de la tabla `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`historyID`),
  ADD KEY `onlyID` (`onlyID`);

--
-- Indices de la tabla `hostroom`
--
ALTER TABLE `hostroom`
  ADD PRIMARY KEY (`hostRoomID`),
  ADD KEY `userID` (`userID`);

--
-- Indices de la tabla `levelposition`
--
ALTER TABLE `levelposition`
  ADD PRIMARY KEY (`levelPositionID`);

--
-- Indices de la tabla `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`loginID`),
  ADD KEY `user_userID_login` (`userID`);

--
-- Indices de la tabla `only`
--
ALTER TABLE `only`
  ADD PRIMARY KEY (`onlyID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `only_modo1` (`levelPositionID`),
  ADD KEY `only_modo2` (`spellLevelID`);

--
-- Indices de la tabla `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`playerID`),
  ADD KEY `user_userID_player` (`userID`);

--
-- Indices de la tabla `privatemath`
--
ALTER TABLE `privatemath`
  ADD PRIMARY KEY (`privateMatchID`),
  ADD KEY `hostRoomID` (`hostRoomID`),
  ADD KEY `playerID` (`playerID`),
  ADD KEY `private_Mode1` (`levelPositionID`),
  ADD KEY `private_Mode2` (`spelllevelID`);

--
-- Indices de la tabla `recoverpassword`
--
ALTER TABLE `recoverpassword`
  ADD PRIMARY KEY (`recoverPasswordID`),
  ADD KEY `user_userID_recoverPassword` (`userID`);

--
-- Indices de la tabla `spelllevel`
--
ALTER TABLE `spelllevel`
  ADD PRIMARY KEY (`spellLevelID`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD KEY `avatar_avatarID_user` (`avatarID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accountactivation`
--
ALTER TABLE `accountactivation`
  MODIFY `accountActivationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `avatar`
--
ALTER TABLE `avatar`
  MODIFY `avartarID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `hostroom`
--
ALTER TABLE `hostroom`
  MODIFY `hostRoomID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `levelposition`
--
ALTER TABLE `levelposition`
  MODIFY `levelPositionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `login`
--
ALTER TABLE `login`
  MODIFY `loginID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `player`
--
ALTER TABLE `player`
  MODIFY `playerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `privatemath`
--
ALTER TABLE `privatemath`
  MODIFY `privateMatchID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `recoverpassword`
--
ALTER TABLE `recoverpassword`
  MODIFY `recoverPasswordID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `spelllevel`
--
ALTER TABLE `spelllevel`
  MODIFY `spellLevelID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_ibfk_1` FOREIGN KEY (`onlyID`) REFERENCES `only` (`onlyID`);

--
-- Filtros para la tabla `hostroom`
--
ALTER TABLE `hostroom`
  ADD CONSTRAINT `hostroom_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Filtros para la tabla `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `user_userID_login` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `only`
--
ALTER TABLE `only`
  ADD CONSTRAINT `only_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `only_mode1` FOREIGN KEY (`levelPositionID`) REFERENCES `levelposition` (`levelPositionID`),
  ADD CONSTRAINT `only_mode2` FOREIGN KEY (`spellLevelID`) REFERENCES `spelllevel` (`spellLevelID`);

--
-- Filtros para la tabla `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `user_userID_player` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `privatemath`
--
ALTER TABLE `privatemath`
  ADD CONSTRAINT `admin_sala` FOREIGN KEY (`hostRoomID`) REFERENCES `hostroom` (`hostRoomID`),
  ADD CONSTRAINT `private_Mode1` FOREIGN KEY (`levelPositionID`) REFERENCES `levelposition` (`levelPositionID`),
  ADD CONSTRAINT `private_Mode2` FOREIGN KEY (`spelllevelID`) REFERENCES `spelllevel` (`spellLevelID`),
  ADD CONSTRAINT `privatemath_ibfk_2` FOREIGN KEY (`playerID`) REFERENCES `player` (`playerID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
