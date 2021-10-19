-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-10-2021 a las 01:30:51
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
  `estado` int(11) NOT NULL COMMENT '1=Abierto,0=Cerrado',
  `inventarioi` float DEFAULT NULL,
  `inventariof` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `anio`
--

INSERT INTO `anio` (`idanio`, `estado`, `inventarioi`, `inventariof`) VALUES
(2021, 0, 1000, 500),
(2022, 1, 2222, -1),
(2023, 0, 0, 0);

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
(6, '1', 'Activo', 'ACTIVO', 'DEUDOR', '', 1),
(7, '110101', 'Caja', 'ACTIVO', 'DEUDOR', '', 4),
(8, '11010101', 'Caja General', 'ACTIVO', 'DEUDOR', '', 5),
(11, '1101', 'EFECTIVO Y EQUIVALENTES', 'ACTIVO', 'DEUDOR', '', 3),
(12, '11010102', 'Fondo de Caja chica', 'ACTIVO', 'DEUDOR', '', 5),
(13, '110102', 'Bancos', 'ACTIVO', 'DEUDOR', '', 4),
(14, '11010201', 'Cuentas corrientes', 'ACTIVO', 'DEUDOR', '', 5),
(15, '11010202', 'Cuentas de Deposito de Ahorro', 'ACTIVO', 'DEUDOR', '', 5),
(16, '11010203', 'Cuentas de Deposito a Plazo (Hasta 90 dí', 'ACTIVO', 'DEUDOR', '', 5),
(17, '110104', 'Inversiones Temporales (Hasta 90 días)', 'ACTIVO', 'DEUDOR', '', 4),
(18, '1102', 'Estimación por Fluctuaciones de Efectivo', 'ACTIVO', 'DEUDOR', '', 3),
(19, '1103', 'Cuentas por Cobrar Comerciales', 'ACTIVO', 'DEUDOR', '', 3),
(20, '110301', 'Clientes Locales', 'ACTIVO', 'DEUDOR', '', 4),
(21, '110302', 'Clientes del exterior', 'ACTIVO', 'DEUDOR', '', 4),
(22, '110303', 'Anticipo de Proveedores', 'ACTIVO', 'DEUDOR', '', 4),
(23, '110304', 'Prestamos y Anticipos al Personal', 'ACTIVO', 'DEUDOR', '', 4),
(24, '110305', 'Deudores Diversos', 'ACTIVO', 'DEUDOR', '', 4),
(25, '110306', 'Otras Cuentas por Cobrar', 'ACTIVO', 'DEUDOR', ' ', 4),
(26, '1104', 'Documentos por cobrar', 'ACTIVO', 'DEUDOR', ' ', 3),
(27, '110401', 'Pagares', 'ACTIVO', 'DEUDOR', ' ', 4),
(28, '110402', 'Letras de Cambio', 'ACTIVO', 'DEUDOR', ' ', 4),
(29, '110403', 'Cartas de Crédito', 'ACTIVO', 'DEUDOR', ' ', 4),
(30, '110404', 'Contratos a Corto Plazo', 'ACTIVO', 'DEUDOR', ' ', 4),
(31, '1105', 'Estimacion para cuentas Incobrables', 'ACTIVO', 'DEUDOR', ' ', 3),
(32, '1106', 'Accionistas', 'ACTIVO', 'DEUDOR', ' ', 3),
(33, '110601', 'Acciones Suscritas no Pagadas', 'ACTIVO', 'DEUDOR', ' ', 4),
(34, '1107', 'Inventarios/Almacen', 'ACTIVO', 'DEUDOR', ' ', 3),
(35, '110701', 'Inventario de Mercadería', 'ACTIVO', 'DEUDOR', ' ', 4),
(36, '110702', 'Mercadería en Transito', 'ACTIVO', 'DEUDOR', ' ', 4),
(37, '1108', 'Estimación por Obsol., Perdida y/o Deter', 'ACTIVO', 'DEUDOR', ' ', 3),
(38, '1109', 'IVA Crédito Fiscal', 'ACTIVO', 'DEUDOR', ' ', 3),
(39, '110901', 'IVA Crédito Fiscal por Compras', 'ACTIVO', 'DEUDOR', ' ', 4),
(40, '110902', 'IVA Crédito Fiscal por Importaciones', 'ACTIVO', 'DEUDOR', ' ', 4),
(41, '110903', 'IVA Crédito Fiscal Remanente', 'ACTIVO', 'DEUDOR', ' ', 4),
(42, '1110', 'IVA Anticipo a Cuenta', 'ACTIVO', 'DEUDOR', ' ', 3),
(43, '1111', 'IVA Percibido', 'ACTIVO', 'DEUDOR', ' ', 3),
(44, '1112', 'IVA Retenido', 'ACTIVO', 'DEUDOR', ' ', 3),
(45, '1113', 'Pagos Anticipados', 'ACTIVO', 'DEUDOR', ' ', 3),
(46, '111301', 'Seguros', 'ACTIVO', 'DEUDOR', ' ', 4),
(47, '11130101', 'Seguros de Vida Personal', 'ACTIVO', 'DEUDOR', ' ', 5),
(48, '11130102', 'Seguros de Bienes', 'ACTIVO', 'DEUDOR', ' ', 5),
(49, '111302', 'Alquileres', 'ACTIVO', 'DEUDOR', ' ', 4),
(50, '111303', 'Publicidad y Propaganda', 'ACTIVO', 'DEUDOR', ' ', 4),
(51, '111304', 'Pago a Cuenta Impuesto sobre la Renta', 'ACTIVO', 'DEUDOR', ' ', 4),
(52, '111305', 'Impuestos Municipales', 'ACTIVO', 'DEUDOR', ' ', 4),
(53, '111306', 'Contrato de Mantenimiento', 'ACTIVO', 'DEUDOR', ' ', 4),
(54, '111307', 'Intereses', 'ACTIVO', 'DEUDOR', ' ', 4),
(55, '111308', 'Papeleria y Utiles', 'ACTIVO', 'DEUDOR', ' ', 4),
(56, '111309', 'Gastos de Organizacion', 'ACTIVO', 'DEUDOR', ' ', 4),
(57, '111310', 'Otros Pagos Anticipados', 'ACTIVO', 'DEUDOR', ' ', 4),
(58, '1114', 'Partes Relacionadas', 'ACTIVO', 'DEUDOR', ' ', 3),
(59, '111401', 'Directores, Ejecutivos y Empleados', 'ACTIVO', 'DEUDOR', ' ', 4),
(60, '111402', 'Compañias Afiliadas', 'ACTIVO', 'DEUDOR', ' ', 4),
(61, '111403', 'Compañias Asociadas', 'ACTIVO', 'DEUDOR', ' ', 4),
(62, '111404', 'Compañías Subsidiarias', 'ACTIVO', 'DEUDOR', ' ', 4),
(63, '12', 'Activo No Corriente', 'ACTIVO', 'DEUDOR', ' ', 2),
(64, '1201', 'Propiedad, Planta y Equipo', 'ACTIVO', 'DEUDOR', ' ', 3),
(65, '120101', 'Terrenos', 'ACTIVO', 'DEUDOR', ' ', 3),
(66, '120102', 'Edificios', 'ACTIVO', 'DEUDOR', ' ', 3),
(67, '120103', 'Instalaciones', 'ACTIVO', 'DEUDOR', ' ', 3),
(68, '120104', 'Mobiliario y Equipo de Oficina', 'ACTIVO', 'DEUDOR', ' ', 3),
(69, '120105', 'Equipo de Transporte', 'ACTIVO', 'DEUDOR', ' ', 3),
(70, '120106', 'Exhibidores', 'ACTIVO', 'DEUDOR', ' ', 3),
(71, '120107', 'Otros Bienes', 'ACTIVO', 'DEUDOR', ' ', 3),
(72, '1202', 'Revaluaciones Propiedad, Planta y Equipo', 'ACTIVO', 'DEUDOR', ' ', 3),
(73, '120201', 'Revaluacion de Terrenos', 'ACTIVO', 'DEUDOR', ' ', 4),
(74, '120202', 'Revaluacion de Edificios', 'ACTIVO', 'DEUDOR', ' ', 4),
(75, '120203', 'Revaluacion de Instalaciones', 'ACTIVO', 'DEUDOR', ' ', 4),
(76, '120204', 'Revaluacion de Mobiliario y Equipo de Of', 'ACTIVO', 'DEUDOR', ' ', 4),
(77, '120205', 'Revaluacion de Equipo de Transporte', 'ACTIVO', 'DEUDOR', ' ', 4),
(78, '120206', 'Revaluacion de Exhibidores', 'ACTIVO', 'DEUDOR', ' ', 4),
(79, '120207', 'Revaluacion de Otros Bienes', 'ACTIVO', 'DEUDOR', ' ', 4),
(80, '1203', 'Contrucciones en Proceso', 'ACTIVO', 'DEUDOR', ' ', 3),
(81, '1204', 'Depreciacion Acumulada Propiedad, Planta', 'ACTIVO', 'DEUDOR', ' ', 3),
(82, '120401', 'Depreciacion de Terrenos', 'ACTIVO', 'DEUDOR', ' ', 4),
(83, '120402', 'Depreciacion de Edificios', 'ACTIVO', 'DEUDOR', ' ', 4),
(84, '120403', 'Depreciacion de Instalaciones', 'ACTIVO', 'DEUDOR', ' ', 4),
(85, '120404', 'Depreciacion de Mobiliario y Equipo de O', 'ACTIVO', 'DEUDOR', ' ', 4),
(86, '120405', 'Depreciacion de Equipo de Transporte', 'ACTIVO', 'DEUDOR', ' ', 4),
(87, '120406', 'Depreciacion de Exhibidores', 'ACTIVO', 'DEUDOR', ' ', 4),
(88, '120407', 'Depreciacion de Otros Bienes', 'ACTIVO', 'DEUDOR', ' ', 4),
(89, '1205', 'Depreciacion Acumulada Revaluos', 'ACTIVO', 'DEUDOR', ' ', 3),
(90, '120501', 'Depreciacion Revaluacion de Terrenos', 'ACTIVO', 'DEUDOR', ' ', 4),
(91, '120502', 'Depreciacion Revaluacion de Edificios', 'ACTIVO', 'DEUDOR', ' ', 4),
(92, '120503', 'Depreciacion Revaluacion de Instalacione', 'ACTIVO', 'DEUDOR', ' ', 4),
(93, '120504', 'Depreciacion Revaluacion de Mobiliario y', 'ACTIVO', 'DEUDOR', ' ', 4),
(94, '120505', 'Depreciacion Revaluacion de Equipo de Tr', 'ACTIVO', 'DEUDOR', ' ', 4),
(95, '120506', 'Depreciacion Revaluacion de Exhibidores', 'ACTIVO', 'DEUDOR', ' ', 4),
(96, '120507', 'Depreciacion Revaluacion de Otros Bienes', 'ACTIVO', 'DEUDOR', ' ', 4),
(97, '1206', 'Inversiones Permanentes', 'ACTIVO', 'DEUDOR', ' ', 3),
(98, '120601', 'Inversiones en subsidiarias', 'ACTIVO', 'DEUDOR', ' ', 4),
(99, '120602', 'Inversiones en Asociadas', 'ACTIVO', 'DEUDOR', ' ', 4),
(100, '120603', 'Inversiones en Negocios Conjuntos', 'ACTIVO', 'DEUDOR', ' ', 4),
(101, '1207', 'Estimacion por Fluctuaciones de Inversio', 'ACTIVO', 'DEUDOR', ' ', 3),
(102, '1208', 'Activos Intangibles', 'ACTIVO', 'DEUDOR', ' ', 3),
(103, '120801', 'Derechos de Llave', 'ACTIVO', 'DEUDOR', ' ', 4),
(104, '120802', 'Patentes y Marcas', 'ACTIVO', 'DEUDOR', ' ', 4),
(105, '120803', 'Franquicias', 'ACTIVO', 'DEUDOR', ' ', 4),
(106, '120804', 'Licencias y Concesiones', 'ACTIVO', 'DEUDOR', ' ', 4),
(107, '120805', 'Programas y Sistemas', 'ACTIVO', 'DEUDOR', ' ', 4),
(108, '120806', 'Otros intangibles', 'ACTIVO', 'DEUDOR', ' ', 4),
(109, '1209', 'Amortizacion de Intangibles', 'ACTIVO', 'DEUDOR', ' ', 3),
(110, '120901', 'Amortizacion Derechos de Llave', 'ACTIVO', 'DEUDOR', ' ', 4),
(111, '120902', 'Amortizacion Patentes y Marcas', 'ACTIVO', 'DEUDOR', ' ', 4),
(112, '120903', 'Amortizacion Franquicias', 'ACTIVO', 'DEUDOR', ' ', 4),
(113, '120904', 'Amortizacion Licencias y Concesiones', 'ACTIVO', 'DEUDOR', ' ', 4),
(114, '120905', 'Amortizacion Programas y Sistemas', 'ACTIVO', 'DEUDOR', ' ', 4),
(115, '120906', 'Amortizacion Otros intangibles', 'ACTIVO', 'DEUDOR', ' ', 4),
(116, '1210', 'Cuentas y documentos por cobrar a largo ', 'ACTIVO', 'DEUDOR', ' ', 3),
(117, '1211', 'Estimacion para Cuentas Incobrables a La', 'ACTIVO', 'DEUDOR', ' ', 3),
(118, '1212', 'Partes Relacionadas a Largo Plazo', 'ACTIVO', 'DEUDOR', ' ', 3),
(119, '121201', 'Directores, Ejecutivos y Empleados a Lar', 'ACTIVO', 'DEUDOR', ' ', 4),
(120, '121202', 'Compañias Afiliadas a Largo Plazo', 'ACTIVO', 'DEUDOR', ' ', 4),
(121, '121203', 'Compañias Asociadas a Largo Plazo', 'ACTIVO', 'DEUDOR', ' ', 4),
(122, '121204', 'Compañías Subsidiarias a Largo Plazo', 'ACTIVO', 'DEUDOR', ' ', 4),
(123, '2', 'PASIVO', 'PASIVO', 'ACREEDOR', ' ', 1),
(124, '21', 'PASIVO CORRIENTE', 'PASIVO', 'ACREEDOR', ' ', 2),
(125, '2101', 'CUENTAS POR PAGAR COMERCIALES', 'PASIVO', 'ACREEDOR', ' ', 3),
(126, '210101', 'Proveedores Locales', 'PASIVO', 'ACREEDOR', ' ', 4),
(127, '210102', 'Proveedores Extranjeros', 'PASIVO', 'ACREEDOR', ' ', 4),
(128, '2102', 'DOCUMENTOS POR PAGAR COMERCIALES', 'PASIVO', 'ACREEDOR', ' ', 3),
(129, '210201', 'Pagares', 'PASIVO', 'ACREEDOR', ' ', 4),
(130, '210202', 'Letras de Cambio', 'PASIVO', 'ACREEDOR', ' ', 4),
(131, '210203', 'Cartas de Crédito', 'PASIVO', 'ACREEDOR', ' ', 4),
(132, '210204', 'Contratos a Corto Plazo', 'PASIVO', 'ACREEDOR', ' ', 4),
(133, '2103', 'PRESTAMOS Y SOBREGIROS BANCARIOS', 'PASIVO', 'ACREEDOR', ' ', 3),
(134, '210301', 'Sobregiros Bancarios', 'PASIVO', 'ACREEDOR', ' ', 4),
(135, '210302', 'Prestamos a Corto Plazo', 'PASIVO', 'ACREEDOR', ' ', 4),
(136, '210303', 'Porción Circulante de Prestamos a Largo ', 'PASIVO', 'ACREEDOR', ' ', 4),
(137, '2104', 'REMUNERACIONES Y PRESTACIONES POR PAGAR ', 'PASIVO', 'ACREEDOR', ' ', 3),
(138, '210401', 'Sueldos y Salarios', 'PASIVO', 'ACREEDOR', ' ', 4),
(139, '210402', 'Comisiones', 'PASIVO', 'ACREEDOR', ' ', 4),
(140, '210403', 'Bonificaciones', 'PASIVO', 'ACREEDOR', ' ', 4),
(141, '210404', 'Vacaciones', 'PASIVO', 'ACREEDOR', ' ', 4),
(142, '210405', 'Aguinaldos', 'PASIVO', 'ACREEDOR', ' ', 4),
(143, '2105', 'RETENCIONES Y DESCUENTOS', 'PASIVO', 'ACREEDOR', ' ', 3),
(144, '210501', 'Cotizaciones Laborales ISSS Salud ', 'PASIVO', 'ACREEDOR', ' ', 4),
(145, '210502', 'Cotizaciones Laborales Fondo de Pensione', 'PASIVO', 'ACREEDOR', ' ', 4),
(146, '21050201', 'AFP Confía', 'PASIVO', 'ACREEDOR', ' ', 5),
(147, '21050202', 'AFP Crecer', 'PASIVO', 'ACREEDOR', ' ', 5),
(148, '210503', 'Retención Impuesto Sobre la Renta', 'PASIVO', 'ACREEDOR', ' ', 4),
(149, '210504', 'Ordenes de Descuentos Bancos y Otras Ins', 'PASIVO', 'ACREEDOR', ' ', 4),
(150, '210505', 'Procuraduría General de la República', 'PASIVO', 'ACREEDOR', ' ', 4),
(151, '2106', 'ACEEDORES VARIOS', 'PASIVO', 'ACREEDOR', ' ', 3),
(152, '210601', 'Acreedores Locales', 'PASIVO', 'ACREEDOR', ' ', 4),
(153, '210602', 'Acreedores Extranjeros', 'PASIVO', 'ACREEDOR', ' ', 4),
(154, '2107', 'PROVISIONES', 'PASIVO', 'ACREEDOR', ' ', 3),
(155, '210701', 'Cuota Patronal ISSS Salud', 'PASIVO', 'ACREEDOR', ' ', 4),
(156, '210702', 'Cuota Patronal AFP Fondo de Pensiones', 'PASIVO', 'ACREEDOR', ' ', 4),
(157, '21070201', 'AFP Confía', 'PASIVO', 'ACREEDOR', ' ', 5),
(158, '21070202', 'AFP Crecer', 'PASIVO', 'ACREEDOR', ' ', 5),
(159, '210703', 'Servicio de Agua', 'PASIVO', 'ACREEDOR', ' ', 4),
(160, '210704', 'Servicio de Energía Eléctrica', 'PASIVO', 'ACREEDOR', ' ', 4),
(161, '210705', 'Servicio de Teléfono + Cable + Internet', 'PASIVO', 'ACREEDOR', ' ', 4),
(162, '210706', 'Honorarios por Pagar', 'PASIVO', 'ACREEDOR', ' ', 4),
(163, '210707', 'Intereses por Pagar', 'PASIVO', 'ACREEDOR', ' ', 4),
(164, '2108', 'IVA DEBITO FISCAL', 'PASIVO', 'ACREEDOR', ' ', 3),
(165, '210801', 'IVA por Venta a Consumidor Final', 'PASIVO', 'ACREEDOR', ' ', 4),
(166, '210802', 'IVA por Venta a Contribuyentes', 'PASIVO', 'ACREEDOR', ' ', 4),
(167, '2109', 'IVA Retenido a Terceros', 'PASIVO', 'ACREEDOR', ' ', 3),
(168, '2110', 'IVA Percibido', 'PASIVO', 'ACREEDOR', ' ', 3),
(169, '2111', 'IMPUESTOS POR PAGAR', 'PASIVO', 'ACREEDOR', ' ', 3),
(170, '211101', 'Impuestos Sobre la Renta', 'PASIVO', 'ACREEDOR', ' ', 4),
(171, '211102', 'IVA por Pagar', 'PASIVO', 'ACREEDOR', ' ', 4),
(172, '211103', 'Impuestos Municipales', 'PASIVO', 'ACREEDOR', ' ', 4),
(173, '2112', 'INTERESES POR PAGAR', 'PASIVO', 'ACREEDOR', ' ', 3),
(174, '211201', 'Intereses Financieros por Pagar', 'PASIVO', 'ACREEDOR', ' ', 4),
(175, '211202', 'Intereses a Proveedores por Pagar', 'PASIVO', 'ACREEDOR', ' ', 4),
(176, '2113', 'DIVIDENDOS POR PAGAR', 'PASIVO', 'ACREEDOR', ' ', 3),
(177, '211301', 'Dividendos por Pagar a Accionistas', 'PASIVO', 'ACREEDOR', ' ', 4),
(178, '2114', 'CUENTAS POR PAGAR A PARTES RELACIONADAS', 'PASIVO', 'ACREEDOR', ' ', 3),
(179, '211401', 'Directores, Ejecutivos y Empleados', 'PASIVO', 'ACREEDOR', ' ', 4),
(180, '211402', 'Compañías Afiliadas', 'PASIVO', 'ACREEDOR', ' ', 4),
(181, '211403', 'Compañías Asociadas', 'PASIVO', 'ACREEDOR', ' ', 4),
(182, '211404', 'Compañías Subsidiarias', 'PASIVO', 'ACREEDOR', ' ', 4),
(183, '2115', 'COBROS ANTICIPADOS', 'PASIVO', 'ACREEDOR', ' ', 3),
(184, '211501', 'Abono de Clientes Anticipado', 'PASIVO', 'ACREEDOR', ' ', 4),
(185, '211599', 'Otros cobros anticipados', 'PASIVO', 'ACREEDOR', ' ', 4),
(186, '22', 'PASIVO NO CORRIENTE', 'PASIVO', 'ACREEDOR', ' ', 2),
(187, '2201', 'CUENTAS Y DOCUMENTOS POR PAGAR A LARGO P', 'PASIVO', 'ACREEDOR', ' ', 3),
(188, '220101', 'Cuentas por Pagar a Largo Plazo', 'PASIVO', 'ACREEDOR', ' ', 4),
(189, '220102', 'Documentos por Pagar a Largo Plazo', 'PASIVO', 'ACREEDOR', ' ', 4),
(190, '2202', 'PRESTAMOS BANCARIOS A LARGO PLAZO', 'PASIVO', 'ACREEDOR', ' ', 3),
(191, '2203', 'PROVISION PARA OBLIGACIONES LABORALES', 'PASIVO', 'ACREEDOR', ' ', 3),
(192, '220301', 'Indemnizaciones', 'PASIVO', 'ACREEDOR', ' ', 4),
(193, '220302', 'Pasivo Laboral por Antigüedad', 'PASIVO', 'ACREEDOR', ' ', 4),
(194, '3', 'PATRIMONIO DE LOS ACCIONISTAS', 'CAPITAL', 'ACREEDOR', ' ', 1),
(195, '31', 'CAPITAL CONTABLE', 'CAPITAL', 'ACREEDOR', ' ', 2),
(196, '3101', 'CAPITAL SOCIAL', 'CAPITAL', 'ACREEDOR', ' ', 3),
(197, '310101', 'Capital Social Suscrito', 'CAPITAL', 'ACREEDOR', ' ', 4),
(198, '31010101', 'Capital Social Pagado', 'CAPITAL', 'ACREEDOR', ' ', 5),
(199, '31010102', 'Capital Social No Pagado', 'CAPITAL', 'ACREEDOR', ' ', 5),
(200, '3102', 'UTILIDADES RESTRINGIDAS', 'CAPITAL', 'ACREEDOR', ' ', 3),
(201, '310201', 'Reserva Legal', 'CAPITAL', 'ACREEDOR', ' ', 4),
(202, '310202', 'Otras Reservas', 'CAPITAL', 'ACREEDOR', ' ', 4),
(203, '3103', 'UTILIDADES POR DISTRIBUIR', 'CAPITAL', 'ACREEDOR', ' ', 3),
(204, '310301', 'Utilidades de Ejercicios Anteriores', 'CAPITAL', 'ACREEDOR', ' ', 4),
(205, '310302', 'Utilidades del Ejercicio', 'CAPITAL', 'ACREEDOR', ' ', 4),
(206, '3104', 'PERDIDAS POR APLICAR', 'CAPITAL', 'ACREEDOR', ' ', 3),
(207, '310401', 'Perdidas de Ejercicios Anteriores', 'CAPITAL', 'ACREEDOR', ' ', 4),
(208, '310402', 'Perdidas del Ejercicio', 'CAPITAL', 'ACREEDOR', ' ', 4),
(209, '3105', 'SUPERAVIT POR REVALUACIONES', 'CAPITAL', 'ACREEDOR', ' ', 3),
(210, '310501', 'Revaluación de Terrenos', 'CAPITAL', 'ACREEDOR', ' ', 4),
(211, '310502', 'Revaluación de Edificios', 'CAPITAL', 'ACREEDOR', ' ', 4),
(212, '310503', 'Revaluación de Instalaciones', 'CAPITAL', 'ACREEDOR', ' ', 4),
(213, '310504', 'Revaluación de Mobiliario y Equipo de Of', 'CAPITAL', 'ACREEDOR', ' ', 4),
(214, '310505', 'Revaluación de Equipo de Transporte', 'CAPITAL', 'ACREEDOR', ' ', 4),
(215, '310506', 'Revaluación de Exhibidores', 'CAPITAL', 'ACREEDOR', ' ', 4),
(216, '310507', 'Revaluación de Otros Activos', 'CAPITAL', 'ACREEDOR', ' ', 4),
(217, '4', 'CUENTAS DE RESULTADO DEUDORAS', 'CRD', 'DEUDOR', ' ', 1),
(218, '41', 'COSTOS DE OPERACIÓN', 'CRD', 'DEUDOR', ' ', 2),
(219, '4101', 'COMPRAS', 'CRD', 'DEUDOR', ' ', 3),
(220, '410101', 'Compras Locales', 'CRD', 'DEUDOR', ' ', 4),
(221, '410102', 'Compras del Exterior', 'CRD', 'DEUDOR', ' ', 4),
(222, '4102', 'GASTOS SOBRE COMPRAS', 'CRD', 'DEUDOR', ' ', 3),
(223, '410201', 'Transporte por Compras', 'CRD', 'DEUDOR', ' ', 4),
(224, '4103', 'REBAJAS Y DEVOLUCIONES SOBRE VENTAS', 'CRD', 'DEUDOR', ' ', 3),
(225, '410301', 'Rebajas sobre Ventas', 'CRD', 'DEUDOR', ' ', 4),
(226, '410302', 'Devoluciones sobre Ventas', 'CRD', 'DEUDOR', ' ', 4),
(227, '4104', 'COSTO DE VENTAS', 'CRD', 'DEUDOR', ' ', 3),
(228, '410401', 'Costo de Ventas de Mercadería Vendida', 'CRD', 'DEUDOR', ' ', 4),
(229, '42', 'GASTOS DE OPERACIÓN', 'CRD', 'DEUDOR', ' ', 2),
(230, '4201', 'GASTOS DE ADMINISTRACION', 'CRD', 'DEUDOR', ' ', 3),
(231, '420101', 'Sueldos y Salarios', 'CRD', 'DEUDOR', ' ', 4),
(232, '420102', 'Horas Extras', 'CRD', 'DEUDOR', ' ', 4),
(233, '420103', 'Comisiones', 'CRD', 'DEUDOR', ' ', 4),
(234, '420104', 'Vacaciones', 'CRD', 'DEUDOR', ' ', 4),
(235, '420105', 'Aguinaldos', 'CRD', 'DEUDOR', ' ', 4),
(236, '420106', 'Indemnizaciones', 'CRD', 'DEUDOR', ' ', 4),
(237, '420107', 'Bonificaciones', 'CRD', 'DEUDOR', ' ', 4),
(238, '420108', 'Seguridad Social ISSS', 'CRD', 'DEUDOR', ' ', 4),
(239, '420109', 'AFP Crecer', 'CRD', 'DEUDOR', ' ', 4),
(240, '420110', 'AFP Confía', 'CRD', 'DEUDOR', ' ', 4),
(241, '420111', 'Viáticos', 'CRD', 'DEUDOR', ' ', 4),
(242, '420112', 'Gastos de Viaje', 'CRD', 'DEUDOR', ' ', 4),
(243, '420113', 'Servicios de Telefonia', 'CRD', 'DEUDOR', ' ', 4),
(244, '420114', 'Internet y Cable', 'CRD', 'DEUDOR', ' ', 4),
(245, '420115', 'Agua Potable', 'CRD', 'DEUDOR', ' ', 4),
(246, '420116', 'Agua Envasada', 'CRD', 'DEUDOR', ' ', 4),
(247, '420117', 'Energía Eléctrica', 'CRD', 'DEUDOR', ' ', 4),
(248, '420118', 'Arrendamiento', 'CRD', 'DEUDOR', ' ', 4),
(249, '420119', 'Servicios Profesionales', 'CRD', 'DEUDOR', ' ', 4),
(250, '420120', 'Asesoria Tecnica', 'CRD', 'DEUDOR', ' ', 4),
(251, '420121', 'Multas', 'CRD', 'DEUDOR', ' ', 4),
(252, '420122', 'Atenciones al Personal', 'CRD', 'DEUDOR', ' ', 4),
(253, '420123', 'Servicios de Courier', 'CRD', 'DEUDOR', ' ', 4),
(254, '420124', 'Impuestos Municipales', 'CRD', 'DEUDOR', ' ', 4),
(255, '420125', 'Hospedaje y Alojamiento', 'CRD', 'DEUDOR', ' ', 4),
(256, '420126', 'Combustible y Lubricantes', 'CRD', 'DEUDOR', ' ', 4),
(257, '420127', 'Mantenimiento y Repuestos de Vehiculos', 'CRD', 'DEUDOR', ' ', 4),
(258, '420128', 'Seguros de Vehiculos', 'CRD', 'DEUDOR', ' ', 4),
(259, '420129', 'Seguros de Personas', 'CRD', 'DEUDOR', ' ', 4),
(260, '420130', 'Equipo y Accesorios de Oficina', 'CRD', 'DEUDOR', ' ', 4),
(261, '420131', 'Cuotas y Suscripciones', 'CRD', 'DEUDOR', ' ', 4),
(262, '420132', 'Donaciones y contribuciones', 'CRD', 'DEUDOR', ' ', 4),
(263, '420133', 'INSAFORP', 'CRD', 'DEUDOR', ' ', 4),
(264, '420134', 'Papeleria y Utiles', 'CRD', 'DEUDOR', ' ', 4),
(265, '420135', 'Mantenimiento de Equipo de Oficina', 'CRD', 'DEUDOR', ' ', 4),
(266, '420136', 'Material de Limpieza', 'CRD', 'DEUDOR', ' ', 4),
(267, '420137', 'Depreciacion de Edificio', 'CRD', 'DEUDOR', ' ', 4),
(268, '420138', 'Depreciacion de Vehiculos', 'CRD', 'DEUDOR', ' ', 4),
(269, '420139', 'Depreciacion de Mobiliario y Equipo de O', 'CRD', 'DEUDOR', ' ', 4),
(270, '420140', 'Viajes al exterior', 'CRD', 'DEUDOR', ' ', 4),
(271, '420141', 'Convenciones y seminarios', 'CRD', 'DEUDOR', ' ', 4),
(272, '420142', 'Fiesta Navideña', 'CRD', 'DEUDOR', ' ', 4),
(273, '420143', 'Capacitaciones', 'CRD', 'DEUDOR', ' ', 4),
(274, '420144', 'Matricula de Comercio', 'CRD', 'DEUDOR', ' ', 4),
(275, '4202', 'GASTOS DE VENTA', 'CRD', 'DEUDOR', ' ', 3),
(276, '420201', 'Sueldos y Salarios', 'CRD', 'DEUDOR', ' ', 4),
(277, '420202', 'Horas Extras', 'CRD', 'DEUDOR', ' ', 4),
(278, '420203', 'Comisiones', 'CRD', 'DEUDOR', ' ', 4),
(279, '420204', 'Vacaciones', 'CRD', 'DEUDOR', ' ', 4),
(280, '420205', 'Aguinaldos', 'CRD', 'DEUDOR', ' ', 4),
(281, '420206', 'Indemnizaciones', 'CRD', 'DEUDOR', ' ', 4),
(282, '420207', 'Bonificaciones', 'CRD', 'DEUDOR', ' ', 4),
(283, '420208', 'Seguridad Social ISSS', 'CRD', 'DEUDOR', ' ', 4),
(284, '420209', 'AFP Crecer', 'CRD', 'DEUDOR', ' ', 4),
(285, '420210', 'AFP Confía', 'CRD', 'DEUDOR', ' ', 4),
(286, '420211', 'Viáticos', 'CRD', 'DEUDOR', ' ', 4),
(287, '420212', 'Gastos de Viaje', 'CRD', 'DEUDOR', ' ', 4),
(288, '420213', 'Servicios de Telefonia', 'CRD', 'DEUDOR', ' ', 4),
(289, '420214', 'Internet y Cable', 'CRD', 'DEUDOR', ' ', 4),
(290, '420215', 'Agua Potable', 'CRD', 'DEUDOR', ' ', 4),
(291, '420216', 'Agua Envasada', 'CRD', 'DEUDOR', ' ', 4),
(292, '420217', 'Energía Eléctrica', 'CRD', 'DEUDOR', ' ', 4),
(293, '420218', 'Arrendamiento', 'CRD', 'DEUDOR', ' ', 4),
(294, '420219', 'Tramites Aduanales', 'CRD', 'DEUDOR', ' ', 4),
(295, '420220', 'Comunicaciones', 'CRD', 'DEUDOR', ' ', 4),
(296, '420221', 'Multas', 'CRD', 'DEUDOR', ' ', 4),
(297, '420222', 'Atenciones al Personal', 'CRD', 'DEUDOR', ' ', 4),
(298, '420223', 'Servicios de Courier', 'CRD', 'DEUDOR', ' ', 4),
(299, '420224', 'Impuestos Municipales', 'CRD', 'DEUDOR', ' ', 4),
(300, '420225', 'Hospedaje y Alojamiento', 'CRD', 'DEUDOR', ' ', 4),
(301, '420226', 'Combustible y Lubricantes', 'CRD', 'DEUDOR', ' ', 4),
(302, '420227', 'Mantenimiento y Repuestos de Vehiculos', 'CRD', 'DEUDOR', ' ', 4),
(303, '420228', 'Seguros de Vehiculos', 'CRD', 'DEUDOR', ' ', 4),
(304, '420229', 'Seguros de Personas', 'CRD', 'DEUDOR', ' ', 4),
(305, '420230', 'Seguro de Mercaderia', 'CRD', 'DEUDOR', ' ', 4),
(306, '420231', 'Alquiler de Vehiculos', 'CRD', 'DEUDOR', ' ', 4),
(307, '420232', 'Alquiler de Equipo', 'CRD', 'DEUDOR', ' ', 4),
(308, '420233', 'INSAFORP', 'CRD', 'DEUDOR', ' ', 4),
(309, '420234', 'Papeleria y Utiles', 'CRD', 'DEUDOR', ' ', 4),
(310, '420235', 'Publicidad y Propaganda', 'CRD', 'DEUDOR', ' ', 4),
(311, '420236', 'Material de Limpieza', 'CRD', 'DEUDOR', ' ', 4),
(312, '420237', 'Depreciacion de Edificio', 'CRD', 'DEUDOR', ' ', 4),
(313, '420238', 'Depreciacion de Vehiculos', 'CRD', 'DEUDOR', ' ', 4),
(314, '420239', 'Depreciacion de Mobiliario y Equipo', 'CRD', 'DEUDOR', ' ', 4),
(315, '420240', 'Depreciacion de Exhibidores', 'CRD', 'DEUDOR', ' ', 4),
(316, '420241', 'Reserva para Cuentas Incobrables', 'CRD', 'DEUDOR', ' ', 4),
(317, '420242', 'Seguridad Privada', 'CRD', 'DEUDOR', ' ', 4),
(318, '420243', 'Capacitaciones', 'CRD', 'DEUDOR', ' ', 4),
(319, '43', 'GASTOS NO OPERACIONALES', 'CRD', 'DEUDOR', ' ', 2),
(320, '4301', 'GASTOS FINANCIEROS', 'CRD', 'DEUDOR', ' ', 3),
(321, '430101', 'Intereses Bancarios', 'CRD', 'DEUDOR', ' ', 4),
(322, '430102', 'Comisiones Bancarias', 'CRD', 'DEUDOR', ' ', 4),
(323, '430103', 'Intereses por Compras al Credito', 'CRD', 'DEUDOR', ' ', 4),
(324, '430104', 'Diferencial Cambiario', 'CRD', 'DEUDOR', ' ', 4),
(325, '4302', 'OTROS GASTOS NO OPERACIONALES', 'CRD', 'DEUDOR', ' ', 3),
(326, '430201', 'Perdida en Venta o Retiro de Activo Fijo', 'CRD', 'DEUDOR', ' ', 4),
(327, '430202', 'Gastos por Deterioro de Activos', 'CRD', 'DEUDOR', ' ', 4),
(328, '430203', 'Gastos por Deterioro de Inversiones', 'CRD', 'DEUDOR', ' ', 4),
(329, '430204', 'Gastos por Siniestros', 'CRD', 'DEUDOR', ' ', 4),
(330, '430205', 'Gastos no reconocidos de Ejercicios Ante', 'CRD', 'DEUDOR', ' ', 4),
(331, '4303', 'GASTOS POR OPERACIONES EN DISCONTINUACIO', 'CRD', 'DEUDOR', ' ', 3),
(332, '4304', 'GASTOS POR IMPUESTO SOBRE LA RENTA', 'CRD', 'DEUDOR', ' ', 3),
(333, '5', 'CUENTAS DE RESULTADO ACREEDORAS', 'CRA', 'ACREEDOR', ' ', 1),
(334, '51', 'INGRESOS DE OPERACIÓN', 'CRA', 'ACREEDOR', ' ', 2),
(335, '5101', 'VENTAS', 'CRA', 'ACREEDOR', ' ', 3),
(336, '510101', 'Ventas al Contado', 'CRA', 'ACREEDOR', ' ', 4),
(337, '510102', 'Ventas al Credito', 'CRA', 'ACREEDOR', ' ', 4),
(338, '5102', 'REBAJAS Y DEVOLUCIONES SOBRE COMPRAS', 'CRA', 'ACREEDOR', ' ', 3),
(339, '510201', 'Rebajas sobre Compras', 'CRA', 'ACREEDOR', ' ', 4),
(340, '510202', 'Devoluciones sobre Compras', 'CRA', 'ACREEDOR', ' ', 4),
(341, '52', 'INGRESOS NO OPERACIONALES', 'CRA', 'ACREEDOR', ' ', 2),
(342, '5201', 'INGRESOS FINANCIEROS', 'CRA', 'ACREEDOR', ' ', 3),
(343, '520101', 'Intereses Generados por Documentos Desco', 'CRA', 'ACREEDOR', ' ', 4),
(344, '520102', 'Intereses Bancarios', 'CRA', 'ACREEDOR', ' ', 4),
(345, '520103', 'Comisiones', 'CRA', 'ACREEDOR', ' ', 4),
(346, '520104', 'Diferencial Cambiario', 'CRA', 'ACREEDOR', ' ', 4),
(347, '5202', 'DIVIDENDOS GANADOS', 'CRA', 'ACREEDOR', ' ', 3),
(348, '5203', 'OTROS INGRESOS NO OPERACIONALES', 'CRA', 'ACREEDOR', ' ', 3),
(349, '520301', 'Ganancia en Venta de Activo Fijo', 'CRA', 'ACREEDOR', ' ', 4),
(350, '520302', 'Indemnizaciones por Siniestros', 'CRA', 'ACREEDOR', ' ', 4),
(351, '520303', 'Ingresos no Reconocidos de Ejercicios An', 'CRA', 'ACREEDOR', ' ', 4),
(352, '5204', 'INGRESOS POR OPERACIONES EN DISCONTINUAC', 'CRA', 'ACREEDOR', ' ', 3),
(353, '6', 'CUENTAS LIQUIDADORAS', 'CL', 'DEUDOR', ' ', 1),
(354, '61', 'CUENTAS DE CIERRE', 'CL', 'DEUDOR', ' ', 2),
(355, '6101', 'PERDIDAS Y GANANCIAS', 'CL', 'DEUDOR', ' ', 3),
(356, '610101', 'Perdidas y Ganancias', 'CL', 'DEUDOR', ' ', 4);

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
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `nombre`, `pass`, `mail`, `telefono`, `fecha`, `usuario`) VALUES
(1, 'admin', 'admin', 'admin@hotmail.com', '12345678', '2021-10-18 16:14:24', 'admin'),
(12, 'werqwer', '12345', 'qwerqwer', '89797979', '2021-10-18 20:27:12', 'admin1');

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
  MODIFY `idcatalogo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=357;

--
-- AUTO_INCREMENT de la tabla `ldiario`
--
ALTER TABLE `ldiario`
  MODIFY `idldiario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
