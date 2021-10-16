-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-12-2017 a las 22:37:26
-- Versión del servidor: 10.1.25-MariaDB
-- Versión de PHP: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistemacontable`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anio`
--

CREATE TABLE `anio` (
  `idanio` int(11) NOT NULL,
  `estado` int(11) NOT NULL COMMENT '1=Abierto,0=Cerrado'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo`
--

CREATE TABLE `catalogo` (
  `idcatalogo` int(11) NOT NULL,
  `codigocuenta` varchar(10) NOT NULL,
  `nombrecuenta` varchar(40) NOT NULL,
  `tipocuenta` varchar(20) NOT NULL,
  `saldo` varchar(20) NOT NULL,
  `r` varchar(1) NOT NULL,
  `nivel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `catalogo`
--

INSERT INTO `catalogo` (`idcatalogo`, `codigocuenta`, `nombrecuenta`, `tipocuenta`, `saldo`, `r`, `nivel`) VALUES
(2, '11', 'Activo Corriente', 'ACTIVO', 'DEUDOR', '', 2),
(4, '2', 'Pasivo', 'PASIVO', 'ACREEDOR', '', 1),
(5, '21', 'Pasivo Corriente', 'PASIVO', 'ACREEDOR', '', 2),
(6, '1', 'Activo', 'ACTIVO', 'DEUDOR', '', 1),
(7, '111', 'Caja', 'ACTIVO', 'DEUDOR', '', 3),
(8, '11101', 'Caja General', 'ACTIVO', 'DEUDOR', '', 4),
(9, '11102', 'Cajas Chicas', 'ACTIVO', 'DEUDOR', '', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ldiario`
--

CREATE TABLE `ldiario` (
  `idldiario` int(11) NOT NULL,
  `idpartida` int(11) NOT NULL,
  `idcatalogo` int(15) NOT NULL,
  `debe` double NOT NULL,
  `haber` double NOT NULL,
  `idanio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida`
--

CREATE TABLE `partida` (
  `idpartida` int(11) NOT NULL,
  `concepto` varchar(150) NOT NULL,
  `fecha` date NOT NULL,
  `idanio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `nombre`, `pass`, `mail`, `telefono`, `fecha`, `usuario`) VALUES
(1, 'fernando', 'jessica', 'fer@hotmail.com', '23339087', '2017-12-06 15:42:47', 'ferJess'),
(2, 'chele', 'chele123', 'chele@gmail.com', '2388399', '0000-00-00 00:00:00', 'chelito96'),
(3, 'fernan', 'hkjhkj', 'khkh', '786876', '2017-12-06 06:00:00', 'hkjhk'),
(4, 'jjjjjj', '6767', 'jjjjjkjjkjkjk', '6775', '2017-12-06 16:28:14', 'jkhjhghj'),
(5, 'jose rafael', '5656', 'jkdshkjh', '78787', '2017-12-06 16:29:53', 'hkjgjjg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `anio`
--
ALTER TABLE `anio`
  ADD PRIMARY KEY (`idanio`);

--
-- Indices de la tabla `catalogo`
--
ALTER TABLE `catalogo`
  ADD PRIMARY KEY (`idcatalogo`);

--
-- Indices de la tabla `ldiario`
--
ALTER TABLE `ldiario`
  ADD PRIMARY KEY (`idldiario`),
  ADD KEY `fk_anio` (`idanio`),
  ADD KEY `fk_idcuenta` (`idcatalogo`);

--
-- Indices de la tabla `partida`
--
ALTER TABLE `partida`
  ADD KEY `fk_anio` (`idanio`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `catalogo`
--
ALTER TABLE `catalogo`
  MODIFY `idcatalogo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `ldiario`
--
ALTER TABLE `ldiario`
  MODIFY `idldiario` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ldiario`
--
ALTER TABLE `ldiario`
  ADD CONSTRAINT `ldiario_ibfk_1` FOREIGN KEY (`idanio`) REFERENCES `anio` (`idanio`),
  ADD CONSTRAINT `ldiario_ibfk_2` FOREIGN KEY (`idcatalogo`) REFERENCES `catalogo` (`idcatalogo`);

--
-- Filtros para la tabla `partida`
--
ALTER TABLE `partida`
  ADD CONSTRAINT `partida_ibfk_1` FOREIGN KEY (`idanio`) REFERENCES `anio` (`idanio`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
