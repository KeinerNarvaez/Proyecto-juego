-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-12-2024 a las 21:57:00
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

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
  `userID` int(11) NOT NULL,
  `bestScore` int(11) DEFAULT NULL,
  `timesCompleted` int(11) NOT NULL,
  `timesDefeated` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hostroom`
--

CREATE TABLE `hostroom` (
  `hostRoomID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `levelPositionID` int(11) DEFAULT NULL,
  `spellLevelID` int(11) DEFAULT NULL,
  `gameTime` time DEFAULT NULL,
  `roomCode` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `only`
--

CREATE TABLE `only` (
  `onlyID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `levelPositionID` int(11) DEFAULT NULL,
  `spellLevelID` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `privatemath`
--

CREATE TABLE `privatemath` (
  `privateMatchID` int(11) NOT NULL,
  `hostRoomID` int(11) DEFAULT NULL,
  `playerID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
  ADD KEY `user_userID_history_FK` (`userID`);

--
-- Indices de la tabla `hostroom`
--
ALTER TABLE `hostroom`
  ADD PRIMARY KEY (`hostRoomID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `hostroom_modo1` (`levelPositionID`),
  ADD KEY `hostroom_modo2` (`spellLevelID`);

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
  ADD KEY `playerID` (`playerID`);

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
  MODIFY `accountActivationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `avatar`
--
ALTER TABLE `avatar`
  MODIFY `avartarID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `history`
--
ALTER TABLE `history`
  MODIFY `historyID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `levelposition`
--
ALTER TABLE `levelposition`
  MODIFY `levelPositionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `login`
--
ALTER TABLE `login`
  MODIFY `loginID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `only`
--
ALTER TABLE `only`
  MODIFY `onlyID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `player`
--
ALTER TABLE `player`
  MODIFY `playerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recoverpassword`
--
ALTER TABLE `recoverpassword`
  MODIFY `recoverPasswordID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `spelllevel`
--
ALTER TABLE `spelllevel`
  MODIFY `spellLevelID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `user_userID_history_FK` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Filtros para la tabla `hostroom`
--
ALTER TABLE `hostroom`
  ADD CONSTRAINT `hostroom_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `hostroom_modo1` FOREIGN KEY (`levelPositionID`) REFERENCES `levelposition` (`levelPositionID`),
  ADD CONSTRAINT `hostroom_modo2` FOREIGN KEY (`spellLevelID`) REFERENCES `spelllevel` (`spellLevelID`);

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
  ADD CONSTRAINT `only_modo1` FOREIGN KEY (`levelPositionID`) REFERENCES `levelposition` (`levelPositionID`) ON DELETE CASCADE,
  ADD CONSTRAINT `only_modo2` FOREIGN KEY (`spellLevelID`) REFERENCES `spelllevel` (`spellLevelID`) ON DELETE CASCADE;

--
-- Filtros para la tabla `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `user_userID_player` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `privatemath`
--
ALTER TABLE `privatemath`
  ADD CONSTRAINT `privatemath_ibfk_1` FOREIGN KEY (`hostRoomID`) REFERENCES `hostroom` (`hostRoomID`),
  ADD CONSTRAINT `privatemath_ibfk_2` FOREIGN KEY (`playerID`) REFERENCES `player` (`playerID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
