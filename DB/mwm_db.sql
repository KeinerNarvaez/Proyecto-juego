-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-10-2024 a las 07:42:57
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
  `expires` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `accountactivation`
--

INSERT INTO `accountactivation` (`accountActivationID`, `activation`, `activationCode`, `expires`) VALUES
(1, 1, '283637', '06:40:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login`
--

CREATE TABLE `login` (
  `loginID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `twoStepsVerificationID` int(11) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `loginDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `online` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `login`
--

INSERT INTO `login` (`loginID`, `userID`, `twoStepsVerificationID`, `password`, `email`, `loginDate`, `online`) VALUES
(1, 1, 0, '12345', 'cristoferlozano233@gmail.com', '2024-10-10 04:39:01', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recoverpassword`
--

CREATE TABLE `recoverpassword` (
  `recoverPasswordID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `codeEstatus` int(11) NOT NULL,
  `applicationDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `twostepsverification`
--

CREATE TABLE `twostepsverification` (
  `twoStepsVerificationID` int(11) NOT NULL,
  `verification` int(11) NOT NULL,
  `codeTwoSteps` int(11) NOT NULL,
  `expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
(1, 0, 1, 'Cristofer', 'Lozano', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accountactivation`
--
ALTER TABLE `accountactivation`
  ADD PRIMARY KEY (`accountActivationID`);

--
-- Indices de la tabla `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`loginID`),
  ADD KEY `user_userID_login` (`userID`);

--
-- Indices de la tabla `recoverpassword`
--
ALTER TABLE `recoverpassword`
  ADD PRIMARY KEY (`recoverPasswordID`);

--
-- Indices de la tabla `twostepsverification`
--
ALTER TABLE `twostepsverification`
  ADD PRIMARY KEY (`twoStepsVerificationID`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accountactivation`
--
ALTER TABLE `accountactivation`
  MODIFY `accountActivationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `login`
--
ALTER TABLE `login`
  MODIFY `loginID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `recoverpassword`
--
ALTER TABLE `recoverpassword`
  MODIFY `recoverPasswordID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `twostepsverification`
--
ALTER TABLE `twostepsverification`
  MODIFY `twoStepsVerificationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `user_userID_login` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
