-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-12-2017 a las 21:48:44
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
-- Base de datos: `sistemaindustrial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `balance`
--

CREATE TABLE `balance` (
  `year` int(10) UNSIGNED NOT NULL,
  `codigo` varchar(45) NOT NULL,
  `cuenta` varchar(45) NOT NULL,
  `N1` varchar(45) NOT NULL,
  `N2` varchar(45) NOT NULL,
  `N3` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `balanza`
--

CREATE TABLE `balanza` (
  `idcuenta` varchar(12) NOT NULL,
  `totaldebe` double NOT NULL,
  `totalhaber` double NOT NULL,
  `saldodeudor` double NOT NULL,
  `saldoacreedor` double NOT NULL,
  `year` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo`
--

CREATE TABLE `catalogo` (
  `codigo` varchar(12) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `tipocuenta` varchar(15) NOT NULL,
  `saldo` varchar(15) NOT NULL,
  `nivel` int(10) UNSIGNED NOT NULL,
  `R` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `catalogo`
--

INSERT INTO `catalogo` (`codigo`, `nombre`, `tipocuenta`, `saldo`, `nivel`, `R`) VALUES
('1', 'ACTIVO', 'Activo', 'Deudor', 1, ' '),
('11', 'ACTIVO CORRIENTE', 'Activo', 'Deudor', 2, ' '),
('1101', 'Caja', 'Activo', 'Deudor', 3, ' '),
('110101', 'Caja General', 'Activo', 'Deudor', 4, ' '),
('110102', 'Caja Chica', 'Activo', 'Deudor', 4, ' '),
('1102', 'Bancos', 'Activo', 'Deudor', 3, ' '),
('110201', 'Cuenta Corriente', 'Activo', 'Deudor', 4, ' '),
('110202', 'Cuenta de Ahorro', 'Activo', 'Deudor', 4, ' '),
('110203', 'Depósitos a Plazo', 'Activo', 'Deudor', 4, ' '),
('1103', 'Cuentas y Documentos por Cobrar', 'Activo', 'Deudor', 3, ' '),
('110301', 'Clientes', 'Activo', 'Deudor', 4, ' '),
('1104', 'Estimación para Cuentas Incobrables', 'Activo', 'Deudor', 3, ' '),
('1105', 'Deudores Diversos', 'Activo', 'Deudor', 3, ' '),
('110501', 'Préstamos a Funcionarios y Empleados', 'Activo', 'Deudor', 4, ' '),
('1106', 'IVA Crédito Fiscal', 'Activo', 'Deudor', 3, ' '),
('1107', 'Inventarios', 'Activo', 'Deudor', 3, ' '),
('110701', 'Inventario de Materia Prima', 'Activo', 'Deudor', 4, ' '),
('110702', 'Inventario de Productos en Proceso', 'Activo', 'Deudor', 4, ' '),
('11070201', 'Inventario de Productos en Proceso', 'Activo', 'Deudor', 5, ' '),
('11070202', 'Materia Prima Directa', 'Activo', 'Deudor', 5, ' '),
('11070203', 'Mano de Obra Directa', 'Activo', 'Deudor', 5, ' '),
('11070204', 'Costos Indirectos de Fabricación', 'Activo', 'Deudor', 5, ' '),
('1107020401', 'Materia Prima Indirecta', 'Activo', 'Deudor', 6, ' '),
('1107020402', 'Mano de Obra Indirecta', 'Activo', 'Deudor', 6, ' '),
('1107020403', 'otros cargos indirectos', 'Activo', 'Deudor', 6, ' '),
('110703', 'Inventario de Artículos Terminados', 'Activo', 'Deudor', 4, ' '),
('110704', 'Otros Inventarios', 'Activo', 'Deudor', 4, ' '),
('1108', 'Gastos Pagados por Anticipado', 'Activo', 'Deudor', 3, ' '),
('110801', 'Seguros', 'Activo', 'Deudor', 4, ' '),
('110802', 'Alquileres', 'Activo', 'Deudor', 4, ' '),
('110803', 'Papeleria y Utiles', 'Activo', 'Deudor', 4, ' '),
('12', 'ACTIVOS NO CORRIENTES', 'Activo', 'Deudor', 2, ' '),
('1201', 'Terrenos', 'Activo', 'Deudor', 3, ' '),
('1202', 'Edificios', 'Activo', 'Deudor', 3, ' '),
('1203', 'Instalaciones', 'Activo', 'Deudor', 3, ' '),
('1204', 'Mobiliario y Equipo de Oficina', 'Activo', 'Deudor', 3, ' '),
('1205', 'Equipo de Transporte', 'Activo', 'Deudor', 3, ' '),
('1206', 'Maquinaria y Equipo de Instalación', 'Activo', 'Deudor', 3, ' '),
('1207', 'Depreciación Acumulada', 'Activo', 'Acreedor', 3, 'R'),
('120701', 'Edificios', 'Activo', 'Acreedor', 4, 'R'),
('120702', 'Instalaciones', 'Activo', 'Acreedor', 4, 'R'),
('120703', 'Mobiliario y Equipo de Oficina', 'Activo', 'Acreedor', 4, 'R'),
('120704', 'Equipo de Transporte', 'Activo', 'Acreedor', 4, 'R'),
('120705', 'Maquinaria y Equipo de Planta', 'Activo', 'Acreedor', 4, 'R'),
('1208', 'Revaluaciones', 'Activo', 'Deudor', 3, ' '),
('120801', 'Revalúo de Terrenos', 'Activo', 'Deudor', 4, ' '),
('120802', 'Revalúo de Edificios', 'Activo', 'Deudor', 4, ' '),
('120803', 'Relvalúo de Maquinaria y Equipo', 'Activo', 'Deudor', 4, ' '),
('1209', 'Cuentas por Cobrar a Largo Plazo', 'Activo', 'Deudor', 3, ' '),
('1210', 'Inversiones Permanentes', 'Activo', 'Deudor', 3, ' '),
('1211', 'Intangibles', 'Activo', 'Deudor', 3, ' '),
('121101', 'Patentes', 'Activo', 'Deudor', 4, ' '),
('121102', 'Marcas de Fábrica', 'Activo', 'Deudor', 4, ' '),
('121103', 'Franquicias', 'Activo', 'Deudor', 4, ' '),
('121104', 'Software', 'Activo', 'Deudor', 4, ' '),
('121105', 'Otros Activos Intangibles', 'Activo', 'Deudor', 4, ' '),
('1212', 'Amortizaciones', 'Activo', 'Deudor', 3, ' '),
('2', 'PASIVO', 'Pasivo', 'Acreedor', 1, ' '),
('21', 'PASIVO CORRIENTE', 'Pasivo', 'Acreedor', 2, ' '),
('2101', 'Cuentas y Documentos por Pagar', 'Pasivo', 'Acreedor', 3, ' '),
('210101', 'Proveedores', 'Pasivo', 'Acreedor', 4, ' '),
('210102', 'Provisiones', 'Pasivo', 'Acreedor', 4, ' '),
('2102', 'Sobregiros Bancarios', 'Pasivo', 'Acreedor', 3, ' '),
('2103', 'Préstamos Bancarios a Corto Plazo', 'Pasivo', 'Acreedor', 3, ' '),
('2104', 'Retenciones Legales', 'Pasivo', 'Acreedor', 3, ' '),
('210401', 'ISSS', 'Pasivo', 'Acreedor', 4, ' '),
('210402', 'Impuesto sobre la Renta', 'Pasivo', 'Acreedor', 4, ' '),
('210403', 'Procuraduría General de la República', 'Pasivo', 'Acreedor', 4, ' '),
('210404', 'Unidad de Pensiones del ISSS', 'Pasivo', 'Acreedor', 4, ' '),
('210405', 'AFP Confía', 'Pasivo', 'Acreedor', 4, ' '),
('210406', 'AFP Crecer', 'Pasivo', 'Acreedor', 4, ' '),
('210407', 'Retención de Renta', 'Pasivo', 'Acreedor', 4, ' '),
('2105', 'Retenciones Personales', 'Pasivo', 'Acreedor', 3, ' '),
('210501', 'Bancos', 'Pasivo', 'Acreedor', 4, ' '),
('210502', 'Entidades Comerciales', 'Pasivo', 'Acreedor', 4, ' '),
('210503', 'Entidades de Seguros', 'Pasivo', 'Acreedor', 4, ' '),
('210504', 'Otras Entidades del Sistema', 'Pasivo', 'Acreedor', 4, ' '),
('210505', 'Otras Retenciones Personales', 'Pasivo', 'Acreedor', 4, ' '),
('2106', 'IVA Débito Fiscal', 'Pasivo', 'Acreedor', 3, ' '),
('2107', 'Intereses por Pagar', 'Pasivo', 'Acreedor', 3, ' '),
('2108', 'Impuestos por Pagar', 'Pasivo', 'Acreedor', 3, ' '),
('210801', 'Impuesto sobre la Renta', 'Pasivo', 'Acreedor', 4, ' '),
('210802', 'IVA', 'Pasivo', 'Acreedor', 4, ' '),
('210803', 'Impuestos Municipales', 'Pasivo', 'Acreedor', 4, ' '),
('2109', 'Acreedores Varios', 'Pasivo', 'Acreedor', 3, ' '),
('22', 'PASIVO NO CORRIENTE', 'Pasivo', 'Acreedor', 2, ' '),
('2201', 'Cuentas y Documentos por Pagar a Largo Plazo', 'Pasivo', 'Acreedor', 3, ' '),
('2202', 'Préstamos Bancarios a Largo Plazo', 'Pasivo', 'Acreedor', 3, ' '),
('2203', 'Ingresos Cobrados por Anticipado', 'Pasivo', 'Acreedor', 3, ' '),
('2204', 'Provision para Obligaciones', 'Pasivo', 'Acreedor', 3, ' '),
('220401', 'Reserva Laboral', 'Pasivo', 'Acreedor', 4, ' '),
('3', 'CAPITAL', 'Capital', 'Acreedor', 1, ' '),
('31', 'CAPITAL CONTABLE', 'Capital', 'Acreedor', 2, ' '),
('3101', 'Capital Social', 'Capital', 'Acreedor', 3, ' '),
('3102', 'Reserva Legal', 'Capital', 'Acreedor', 3, ' '),
('3103', 'Superavit por Revaluación', 'Capital', 'Acreedor', 3, ' '),
('3104', 'Utilidad por Distribuir', 'Capital', 'Acreedor', 3, ' '),
('3105', 'Pérdida por Aplicar', 'Capital', 'Acreedor', 3, ' '),
('4', 'CUENTAS DE RESULTADO DEUDORAS', 'Egresos', 'Deudor', 1, ' '),
('41', 'COSTOS', 'Egresos', 'Deudor', 2, ' '),
('4101', 'Costo de venta', 'Egresos', 'Deudor', 3, ' '),
('42', 'GASTOS OPERATIVOS', 'Egresos', 'Deudor', 2, ' '),
('4201', 'Gastos de Administración', 'Egresos', 'Deudor', 3, ' '),
('420101', 'Sueldos y Salarios', 'Egresos', 'Deudor', 4, ' '),
('420102', 'Comisiones', 'Egresos', 'Deudor', 4, ' '),
('420103', 'Vacaciones', 'Egresos', 'Deudor', 4, ' '),
('420104', 'Bonificaciones', 'Egresos', 'Deudor', 4, ' '),
('420105', 'Aguinaldos', 'Egresos', 'Deudor', 4, ' '),
('420106', 'Horas Extras', 'Egresos', 'Deudor', 4, ' '),
('420107', 'Viáticos', 'Egresos', 'Deudor', 4, ' '),
('420108', 'Indemnizaciones', 'Egresos', 'Deudor', 4, ' '),
('420109', 'Atención a Empleados', 'Egresos', 'Deudor', 4, ' '),
('420110', 'ISSSSALUD', 'Egresos', 'Deudor', 4, ' '),
('420111', 'ISSSPENSION', 'Egresos', 'Deudor', 4, ' '),
('420112', 'AFP', 'Egresos', 'Deudor', 4, ' '),
('420113', 'INSAFORP', 'Egresos', 'Deudor', 4, ' '),
('420114', 'Honorarios', 'Egresos', 'Deudor', 4, ' '),
('420115', 'Seguros', 'Egresos', 'Deudor', 4, ' '),
('420116', 'Transportes', 'Egresos', 'Deudor', 4, ' '),
('420117', 'Agua', 'Egresos', 'Deudor', 4, ' '),
('420118', 'Comunicaciones', 'Egresos', 'Deudor', 4, ' '),
('420119', 'Energia Electrica', 'Egresos', 'Deudor', 4, ' '),
('420120', 'Estimacion para Cuentas Incobrables', 'Egresos', 'Deudor', 4, ' '),
('420121', 'Papeleria y Utiles', 'Egresos', 'Deudor', 4, ' '),
('420122', 'Depreciación', 'Egresos', 'Deudor', 4, ' '),
('420123', 'Mantenimiento y Reparacion de Mobiliario y Eq', 'Egresos', 'Deudor', 4, ' '),
('420124', 'Mantenimiento y Reparacion de Edificios', 'Egresos', 'Deudor', 4, ' '),
('420125', 'Mantenimiento y Reparaciones de Equipo', 'Egresos', 'Deudor', 4, ' '),
('420126', 'Publicidad', 'Egresos', 'Deudor', 4, ' '),
('420127', 'Empaques', 'Egresos', 'Deudor', 4, ' '),
('420128', 'Atenciones a Clientes', 'Egresos', 'Deudor', 4, ' '),
('420129', 'Multas', 'Egresos', 'Deudor', 4, ' '),
('420130', 'Combustibles y Lubricantes', 'Egresos', 'Deudor', 4, ' '),
('420131', 'Impuestos Municipales', 'Egresos', 'Deudor', 4, ' '),
('420132', 'Inscripciones', 'Egresos', 'Deudor', 4, ' '),
('420133', 'Limpieza', 'Egresos', 'Deudor', 4, ' '),
('420134', 'Alquileres', 'Egresos', 'Deudor', 4, ' '),
('420135', 'Matriculas de Comercio', 'Egresos', 'Deudor', 4, ' '),
('420136', 'Donaciones y Contribuciones', 'Egresos', 'Deudor', 4, ' '),
('420137', 'Vigilancia', 'Egresos', 'Deudor', 4, ' '),
('420138', 'Uniformes', 'Egresos', 'Deudor', 4, ' '),
('420139', 'Amortizaciones', 'Egresos', 'Deudor', 4, ' '),
('420140', 'Ornato', 'Egresos', 'Deudor', 4, ' '),
('420141', 'Fovial', 'Egresos', 'Deudor', 4, ' '),
('420142', 'Otros Gastos de Administracion', 'Egresos', 'Deudor', 4, ' '),
('4202', 'Gastos de Venta', 'Egresos', 'Deudor', 3, ' '),
('4203', 'Gatos Financieros', 'Egresos', 'Deudor', 3, ' '),
('5', 'CUENTAS DE RESULTADO ACREEDORAS', 'Ingreso', 'Acreedor', 1, ' '),
('51', 'INGRESOS', 'Ingreso', 'Acreedor', 2, ' '),
('5101', 'Ventas', 'Ingreso', 'Acreedor', 3, ' '),
('5102', 'Otros Ingresos', 'Ingreso', 'Acreedor', 3, ' '),
('6', 'CUENTAS LIQUIDADORAS', 'Liquidadoras', 'Deudor', 1, ' '),
('61', 'CUENTAS DE CIERRE', 'Liquidadoras', 'Deudor', 2, ' '),
('6101', 'Pérdidas y Ganancias', 'Liquidadoras', 'Deudor', 3, ' '),
('7', 'CUENTAS DE ORDEN', 'Orden', 'Deudor', 1, ' '),
('71', 'Cuentas de Orden Deudoras', 'Orden', 'Deudor', 2, ' '),
('72', 'Cuentas de Orden Acreedoras', 'Orden', 'Acreedor', 2, ' ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costoproduccion`
--

CREATE TABLE `costoproduccion` (
  `year` int(10) UNSIGNED NOT NULL,
  `IIMP` double NOT NULL,
  `CMPR` double NOT NULL,
  `MP` double NOT NULL,
  `IFMP` double NOT NULL,
  `TMPU` double NOT NULL,
  `CMPI` double NOT NULL,
  `CMPU` double NOT NULL,
  `MOD` double NOT NULL,
  `CP` double NOT NULL,
  `CIF` double NOT NULL,
  `CPP` double NOT NULL,
  `IIPP` double NOT NULL,
  `PPD` double NOT NULL,
  `IFPP` double NOT NULL,
  `CPT` double NOT NULL,
  `IIAT` double NOT NULL,
  `ATD` double NOT NULL,
  `IFAT` double NOT NULL,
  `CAV` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadoresultado`
--

CREATE TABLE `estadoresultado` (
  `year` int(10) UNSIGNED NOT NULL,
  `VEN` double NOT NULL,
  `COVENT` double NOT NULL,
  `UTBR` double NOT NULL,
  `GO` double NOT NULL,
  `UO` double NOT NULL,
  `OI` double NOT NULL,
  `UAIR` double NOT NULL,
  `RL` double NOT NULL,
  `UAI` double NOT NULL,
  `ISR` double NOT NULL,
  `UE` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventarios`
--

CREATE TABLE `inventarios` (
  `year` int(10) UNSIGNED NOT NULL,
  `IIMP` double NOT NULL,
  `IIPP` double NOT NULL,
  `IIAT` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `librodiario`
--

CREATE TABLE `librodiario` (
  `idpartida` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `idcuenta` varchar(15) NOT NULL,
  `debe` double NOT NULL,
  `haber` double NOT NULL,
  `year` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mayorizacion`
--

CREATE TABLE `mayorizacion` (
  `idcuenta` varchar(12) NOT NULL,
  `idpartida` int(10) UNSIGNED NOT NULL,
  `debe` double NOT NULL,
  `haber` double NOT NULL,
  `saldo` double NOT NULL,
  `year` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida`
--

CREATE TABLE `partida` (
  `idpartida` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `concepto` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `year` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `year`
--

CREATE TABLE `year` (
  `year` int(10) UNSIGNED NOT NULL,
  `estado` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `year`
--

INSERT INTO `year` (`year`, `estado`) VALUES
(2017, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `balanza`
--
ALTER TABLE `balanza`
  ADD KEY `FK_balanza_1` (`year`),
  ADD KEY `FK_balanza_2` (`idcuenta`);

--
-- Indices de la tabla `catalogo`
--
ALTER TABLE `catalogo`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `costoproduccion`
--
ALTER TABLE `costoproduccion`
  ADD PRIMARY KEY (`year`);

--
-- Indices de la tabla `estadoresultado`
--
ALTER TABLE `estadoresultado`
  ADD PRIMARY KEY (`year`);

--
-- Indices de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD PRIMARY KEY (`year`);

--
-- Indices de la tabla `librodiario`
--
ALTER TABLE `librodiario`
  ADD KEY `FK_librodiario_1` (`idcuenta`),
  ADD KEY `FK_librodiario_2` (`year`);

--
-- Indices de la tabla `mayorizacion`
--
ALTER TABLE `mayorizacion`
  ADD KEY `FK_mayorizacion_1` (`idcuenta`),
  ADD KEY `FK_mayorizacion_2` (`year`);

--
-- Indices de la tabla `partida`
--
ALTER TABLE `partida`
  ADD KEY `FK_partida_1` (`year`);

--
-- Indices de la tabla `year`
--
ALTER TABLE `year`
  ADD PRIMARY KEY (`year`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `costoproduccion`
--
ALTER TABLE `costoproduccion`
  MODIFY `year` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `estadoresultado`
--
ALTER TABLE `estadoresultado`
  MODIFY `year` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  MODIFY `year` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `year`
--
ALTER TABLE `year`
  MODIFY `year` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2018;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `balanza`
--
ALTER TABLE `balanza`
  ADD CONSTRAINT `FK_balanza_1` FOREIGN KEY (`year`) REFERENCES `year` (`year`),
  ADD CONSTRAINT `FK_balanza_2` FOREIGN KEY (`idcuenta`) REFERENCES `catalogo` (`codigo`);

--
-- Filtros para la tabla `costoproduccion`
--
ALTER TABLE `costoproduccion`
  ADD CONSTRAINT `FK_costoproduccion_1` FOREIGN KEY (`year`) REFERENCES `year` (`year`);

--
-- Filtros para la tabla `estadoresultado`
--
ALTER TABLE `estadoresultado`
  ADD CONSTRAINT `FK_estadoresultado_1` FOREIGN KEY (`year`) REFERENCES `year` (`year`);

--
-- Filtros para la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD CONSTRAINT `FK_inventarios_1` FOREIGN KEY (`year`) REFERENCES `year` (`year`);

--
-- Filtros para la tabla `librodiario`
--
ALTER TABLE `librodiario`
  ADD CONSTRAINT `FK_librodiario_1` FOREIGN KEY (`idcuenta`) REFERENCES `catalogo` (`codigo`),
  ADD CONSTRAINT `FK_librodiario_2` FOREIGN KEY (`year`) REFERENCES `year` (`year`);

--
-- Filtros para la tabla `mayorizacion`
--
ALTER TABLE `mayorizacion`
  ADD CONSTRAINT `FK_mayorizacion_1` FOREIGN KEY (`idcuenta`) REFERENCES `catalogo` (`codigo`),
  ADD CONSTRAINT `FK_mayorizacion_2` FOREIGN KEY (`year`) REFERENCES `year` (`year`);

--
-- Filtros para la tabla `partida`
--
ALTER TABLE `partida`
  ADD CONSTRAINT `FK_partida_1` FOREIGN KEY (`year`) REFERENCES `year` (`year`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
