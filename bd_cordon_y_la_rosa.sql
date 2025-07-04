-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-12-2023 a las 22:49:08
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
-- Base de datos: `bd_cordon_y_la_rosa`
--

-- Desactivar la verificación de claves foráneas
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `boleta`;
DROP TABLE IF EXISTS `factura`;
DROP TABLE IF EXISTS `facturacompra`;
DROP TABLE IF EXISTS `detallecompra`;
DROP TABLE IF EXISTS `detalleventa`;
DROP TABLE IF EXISTS `compra`;
DROP TABLE IF EXISTS `venta`;
DROP TABLE IF EXISTS `producto`;
DROP TABLE IF EXISTS `marca`;
DROP TABLE IF EXISTS `categoria`;
DROP TABLE IF EXISTS `cliente`;
DROP TABLE IF EXISTS `proveedor`;
DROP TABLE IF EXISTS `usuario`;
DROP TABLE IF EXISTS `empresa`;
DROP TABLE IF EXISTS `persona`;
DROP TABLE IF EXISTS `distrito`;
DROP TABLE IF EXISTS `provincia`;
DROP TABLE IF EXISTS `departamento`;
DROP TABLE IF EXISTS `metodopago`;
DROP TABLE IF EXISTS `tienda`;
DROP TABLE IF EXISTS `tipoacceso`;
DROP TABLE IF EXISTS `producto_subcategoria`;
DROP TABLE IF EXISTS `subcategoria`;
DROP TABLE IF EXISTS `subcategoria_tipo`;

-- Reactivar la verificación de claves foráneas
SET FOREIGN_KEY_CHECKS=1;

--
-- Estructura de tabla para la tabla `boleta`
--

CREATE TABLE `boleta` (
  `id_boleta` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `fecha_emision` datetime NOT NULL DEFAULT current_timestamp(),
  `n_boleta` varchar(50) NOT NULL,
  `serie` varchar(50) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nom_categoria` varchar(50) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nom_categoria`, `descripcion`, `estado`) VALUES
(6, ' Bebidas y Refrescos', 'Ofrece una variedad de bebidas, desde agua embotellada y jugos hasta refrescos y bebidas energéticas.', 0x31),
(7, 'Snacks y Golosinas', 'Contiene una variedad de snacks, chocolates, galletas y productos para satisfacer antojos rápidos. ', 0x31),
(8, 'Productos de Panadería', 'Incluye pan fresco, pasteles, galletas y otros productos de panadería. ', 0x30),
(9, 'Frutas y verduras', 'Productos frescos como frutas y verduras', 0x30),
(10, 'Productos Lacteos', 'Ofrece leche, queso, yogurt y mantequila. ', 0x31),
(11, 'Artículos de Cuidado Personal', 'Ofrece productos de higiene personal, como champús, jabones, cremas y productos de cuidado facial.', 0x30),
(12, ' Artículos de Limpieza', 'Incluye oroductos de limpieza especializados como detergente, lavajias, Lejias, Guantes, Limpia vidrios, Escoba, Recojedor y trapeador.. ', 0x31),
(13, 'Artículos de Papelería', 'Una variedad de productos de papelería, desde bolígrafos y cuadernos hasta suministros de oficina básicos', 0x31),
(14, 'Electrónicos y Accesorios', 'Ofrecemos pequeños electrodomésticos, cargadores, baterías y otros', 0x31),
(15, 'Productos para Mascotas', 'Alimentos, juguetes, accesorios y productos para el cuidado de mascotas,', 0x31);

--

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `fecha_registro` date DEFAULT (curdate()),
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `id_persona`, `id_empresa`, `fecha_registro`, `estado`) VALUES
(1, 2, NULL, '2023-11-24', 0x31),
(2, 5, NULL, '2023-11-24', 0x31),
(3, 6, NULL, '2023-11-24', 0x30),
(4, 7, NULL, '2023-11-24', 0x31),
(5, 8, NULL, '2023-11-24', 0x31),
(6, 9, NULL, '2023-11-24', 0x31),
(7, 10, NULL, '2023-12-01', 0x31);

--

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id_compra` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_metodo_pago` int(11) NOT NULL,
  `fecha_compra` datetime NOT NULL,
  `monto_total` decimal(6,2) NOT NULL,
  `dscto` decimal(6,2) NOT NULL,
  `dscto_total` decimal(6,2) NOT NULL,
  `subtotal` decimal(6,2) NOT NULL,
  `total_pagar` decimal(6,2) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `id_departamento` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Estructura de tabla para la tabla `detallecompra`
--

CREATE TABLE `detallecompra` (
  `id_detalle_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_total` decimal(6,2) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Estructura de tabla para la tabla `detalleventa`
--

CREATE TABLE `detalleventa` (
  `id_detalle_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_total` decimal(6,2) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Estructura de tabla para la tabla `distrito`
--

CREATE TABLE `distrito` (
  `id_distrito` int(11) NOT NULL,
  `id_provincia` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id_empresa` int(11) NOT NULL,
  `id_distrito` int(11) DEFAULT NULL,
  `n_ruc` varchar(11) NOT NULL,
  `razon_social` varchar(250) NOT NULL,
  `n_telefono` varchar(12) DEFAULT 'sin telefono',
  `email` varchar(50) DEFAULT 'sin email',
  `direccion` varchar(50) DEFAULT 'sin direccion',
  `fecha_registro` date DEFAULT (curdate()),
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id_empresa`, `id_distrito`, `n_ruc`, `razon_social`, `n_telefono`, `email`, `direccion`, `fecha_registro`, `estado`) VALUES
(3, NULL, '20447750859', 'PRODUCTOS Y SERVICIOS INDUSTRIALES ALPAMAYO SAC', '923253210', 'alpamayo@gmail.com.pe', 'MZA. H LOTE. 2 URB. AZIRUNI II ETAPA PUNO PUNO PUN', '2023-11-24', 0x31),
(5, NULL, '20447612543', 'DISTRIBUIDORA DTODO SOCIEDAD ANONIMA CERRADA', '920250211', 'dtodo@gmail.com.pe', 'JR. CAHUIDE NRO. 735 BAR. MANCO CAPAC PUNO SAN ROM', '2023-11-24', 0x31),
(6, NULL, '20101420591', 'ARIES COMERCIAL S.A.C.', '929292921', 'aries@gmail.com', 'AV. ELMER FAUCETT NRO. 1814 URB. SAN JOSE CALLAO C', '2023-11-27', 0x31),
(7, NULL, '20100617332', 'RINTI S A', '926906105', 'rinti@gmail.com', 'AV. NICOLAS AYLLON-C.CENTRAL KM. 17.5 FND. PARIACH', '2023-11-27', 0x31),
(8, NULL, '20606838477', 'BLANQUI CUIDADO PERSONAL Y DEL HOGAR E.I.R.L.', '924606234', 'blanqui@gmail.com', 'AV. ELMER FAUCETT NRO. 169 INT. 309 URB. MARANGA E', '2023-11-27', 0x31),
(9, NULL, '20333541778', 'LIBRERIA PAPELERIA E IMPRESIONES SUR PACIFICO S.R.LTDA', '958859620', 'surpacifico@gmail.com', 'AV. EMILIO CAVENECIA NRO. 235 LIMA LIMA SAN ISIDRO', '2023-11-27', 0x31),
(10, NULL, '20555533439', 'COMERCIAL ARTICULOS DE LIMPIEZA YAK S.A.C.', '912821356', 'yack@gmail.com', 'JR. JOSE ANTONIO ENCINAS NRO. 370 COO. LAS FLORES ', '2023-11-27', 0x31),
(11, NULL, '20602267165', 'CUIDADO PERSONAL AURORAS E.I.R.L.', '922652132', 'auroras@gmail.com', 'CAL. 1 MZA. B LOTE. 13 URB. LAS PALMERAS DE VILLA ', '2023-11-27', 0x31),
(12, NULL, '20516117649', 'DIGASEL S.A.C.', '913845622', 'digasel@gmail.com', 'CAL. SAN MARTIN NRO. 665 URB. ORBEA LIMA LIMA MAGD', '2023-11-27', 0x31),
(13, NULL, '20100190797', 'LECHE GLORIA SOCIEDAD ANONIMA - GLORIA S.A.', '935623456', 'gloria@gmail.com', 'AV. REPUBLICA DE PANAMA NRO. 2461 URB. SANTA CATAL', '2023-11-27', 0x31),
(14, NULL, '20297182456', 'SNACKS AMERICA LATINA S.R.L.', '956123399', 'america@gmail.com', 'AV. BOLOGNESI NRO. 550 LIMA LIMA SANTA ANITA', '2023-11-27', 0x31),
(15, NULL, '20526893507', 'DISTRIBUIDORA DE GOLOSINAS Y ALIMENTOS SOCIEDAD ANONIMA CERRADA', '994567345', 'digo@gmail.com', '---- TAMBO OSCCOLLOPAMPA MZA. A LOTE. 1 CUSCO CUSC', '2023-11-27', 0x31),
(16, NULL, '20604854084', '"AGUA DEL CIELO E.I.R.L."', '968367062', 'cielo@gmail.com', 'PQ. 06 08 NRO. SN C.H. TALARA PIURA TALARA PARIÑAS', '2023-11-27', 0x31),
(17, NULL, '20263322496', 'NESTLE PERU S A', '926896446', 'nestle@gmail.com', 'CAL. LUIS GALVANI NRO. 493 URB. LOTIZACION INDUSTR', '2023-11-27', 0x31);

--

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id_factura` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `fecha_emision` datetime NOT NULL DEFAULT current_timestamp(),
  `n_factura` varchar(50) NOT NULL,
  `serie` varchar(50) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Estructura de tabla para la tabla `facturacompra`
--

CREATE TABLE `facturacompra` (
  `id_factura_compra` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `fecha_emision` datetime NOT NULL DEFAULT current_timestamp(),
  `n_factura` varchar(50) NOT NULL,
  `serie` varchar(50) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `id_marca` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `nom_marca` varchar(50) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Estructura de tabla para la tabla `metodopago`
--

CREATE TABLE `metodopago` (
  `id_metodo_pago` int(11) NOT NULL,
  `nom_metodo_pago` varchar(50) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_persona` int(11) NOT NULL,
  `id_distrito` int(11) DEFAULT NULL,
  `tipo_doc` varchar(3) NOT NULL DEFAULT 'DNI',
  `n_doc` varchar(20) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `a_paterno` varchar(50) NOT NULL,
  `a_materno` varchar(50) NOT NULL,
  `f_nacimiento` date DEFAULT NULL,
  `genero` char(1) DEFAULT NULL CHECK (`genero` in ('M','F')),
  `direccion` varchar(50) DEFAULT 'sin direccion',
  `n_telefono` varchar(12) DEFAULT 'sin telefono',
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_persona`, `id_distrito`, `tipo_doc`, `n_doc`, `nombres`, `a_paterno`, `a_materno`, `f_nacimiento`, `genero`, `direccion`, `n_telefono`, `estado`) VALUES
(2, NULL, 'DNI', '72559620', 'ERIK JHONATAN', 'MARCA', 'LOAYZA', NULL, NULL, 'AV. PRIMAVERA 123', '923253218', 0x31),
(4, NULL, 'DNI', '72559620', 'ERIK JHONATAN', 'MARCA', 'LOAYZA', NULL, NULL, 'sin direccion', 'sin telefono', 0x31),
(5, NULL, 'DNI', '72559610', 'MASHELY', 'PICKMAN', 'GUTIERREZ', NULL, NULL, 'sin direccion', 'sin telefono', 0x31),
(6, NULL, 'DNI', '70559620', 'EVELYN YADIRA', 'SULCA', 'ROCA', NULL, NULL, 'sin direccion', 'sin telefono', 0x31),
(7, NULL, 'DNI', '27427864', 'JOSE PEDRO', 'CASTILLO', 'TERRONES', NULL, NULL, 'sin direccion', 'sin telefono', 0x31),
(8, NULL, 'DNI', '49046204', 'GIANLUCA', 'LAPADULA', 'VARGAS', NULL, NULL, 'sin direccion', 'sin telefono', 0x31),
(9, NULL, 'DNI', '00212241', 'ARTURO', 'MERINO', 'DE LAMA', NULL, NULL, 'sin direccion', 'sin telefono', 0x31),
(10, NULL, 'DNI', '49086261', 'OLIVER', 'SONNE', 'CHRISTENSEN', NULL, NULL, 'sin direccion', 'sin telefono', 0x31),
(11, NULL, 'DNI', '61685209', 'MARCO ANTONIO', 'CHUMBES', 'ESPINOZA', NULL, NULL, 'AV. LIBERTAD 123', '929292929', 0x31);

--

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_marca` int(11) DEFAULT NULL,
  `nom_producto` varchar(50) NOT NULL,
  `imagen` varchar(250) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `precio` decimal(6,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `stock_minimo` int(11) NOT NULL DEFAULT 5,
  `fecha_vencimiento` date DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `id_categoria`, `id_proveedor`, `id_marca`, `nom_producto`, `imagen`, `descripcion`, `precio`, `stock`, `stock_minimo`, `fecha_vencimiento`, `fecha_registro`, `estado`) VALUES
(13, 6, 9, NULL, 'Inka Kola', 'images/productos/13.jpg', 'Gaseosa Inca Kola 600 ml', 3.00, 30, 5, '2025-07-31', '2023-11-27 17:02:53', 0x31),
(14, 6, 9, NULL, 'Coca cola', 'images/productos/14.jpg', 'Gaseosa Coca Cola 600 ml', 3.00, 30, 5, '2025-08-15', '2023-11-27 17:10:35', 0x31),
(15, 6, 9, NULL, 'Fanta', 'images/productos/15.jpg', 'Gaseosa de 500 ml', 2.00, 30, 5, '2025-07-20', '2023-11-27 17:13:52', 0x31),
(16, 6, 9, NULL, 'Agua Cielo', 'images/productos/16.jpg', 'Botella de 1LT', 3.00, 30, 5, '2026-01-10', '2023-11-27 17:21:03', 0x31),
(17, 6, 9, NULL, 'Frugos Valle', 'images/productos/17.jpg', 'Durazno caja 1.5 LT', 6.50, 30, 5, '2025-09-05', '2023-11-27 17:35:04', 0x31),
(18, 6, 9, NULL, 'Pulp', 'images/productos/18.jpg', 'Jugo de durazno 1LT', 4.00, 30, 5, '2025-07-12', '2023-11-27 17:38:41', 0x31),
(19, 6, 9, NULL, 'Cifrut', 'images/productos/19.jpg', 'Refresco sabor naranja 3LT', 6.50, 30, 5, '2025-10-25', '2023-11-27 17:41:56', 0x31),
(20, 7, 11, NULL, 'Lays', 'images/productos/20.jpg', 'Papitas Lays', 2.00, 30, 5, '2025-07-08', '2023-11-27 17:47:20', 0x31),
(21, 7, 11, NULL, 'Doritos', 'images/productos/21.jpg', 'Tortilla de maiz 45g', 2.00, 30, 5, '2025-08-01', '2023-11-27 17:51:38', 0x31),
(22, 7, 11, NULL, 'Cuates', 'images/productos/22.jpg', 'Cuates picante sabor maiz 69g', 1.00, 30, 5, '2025-07-28', '2023-11-27 17:53:32', 0x31),
(23, 7, 11, NULL, 'Chisitos', 'images/productos/23.jpg', 'Sabor a queso 190g', 1.50, 30, 5, '2025-09-15', '2023-11-27 17:56:39', 0x31),
(24, 7, 14, NULL, 'Sublime', 'images/productos/24.jpg', 'Sabor a chocolate 30g', 2.00, 30, 5, '2025-11-11', '2023-11-27 18:03:50', 0x31),
(25, 7, 2, NULL, 'Gomitas', 'images/productos/25.jpg', 'sabores surtidos 90G', 3.00, 30, 5, '2025-12-01', '2023-11-30 18:08:31', 0x31),
(26, 7, 1, NULL, 'Trululu', 'images/productos/26.jpg', 'Masmelos unicornio 250g', 7.00, 30, 5, '2025-10-01', '2023-11-30 18:13:48', 0x31),
(27, 7, 1, NULL, 'OREO', 'images/productos/27.jpg', 'Galletas sabor chocolate 108g', 2.50, 30, 5, '2025-09-22', '2023-11-30 18:18:47', 0x31),
(28, 10, 10, NULL, 'Gloria', 'images/productos/28.jpg', 'Leche azul 395g', 4.20, 30, 5, '2025-07-18', '2023-11-30 18:26:13', 0x31),
(29, 10, 10, NULL, 'Gloria leche evaporada', 'images/productos/29.jpg', 'Leche 400 g', 4.60, 30, 5, '2025-07-19', '2023-11-30 18:30:43', 0x31),
(30, 10, 10, NULL, 'Gloria sin lactosa', 'images/productos/30.jpg', 'Leche 400g', 5.00, 30, 5, '2025-07-25', '2023-11-30 18:32:44', 0x31),
(31, 10, 10, NULL, 'Mantequilla', 'images/productos/31.jpg', 'Mantequilla Gloria en pote de 390g', 16.00, 30, 5, '2025-08-30', '2023-11-30 18:37:03', 0x31),
(32, 10, 10, NULL, 'Yogurt Gloria', 'images/productos/32.jpg', 'Botella de 1L', 6.00, 30, 5, '2025-07-10', '2023-11-30 21:01:23', 0x31),
(33, 10, 10, NULL, 'Yogurt', 'images/productos/33.jpg', 'Sin lactosa sabor fresa 1L', 7.20, 30, 5, '2025-07-15', '2023-11-30 21:04:28', 0x31),
(34, 10, 10, NULL, 'Queso Mozzarella', 'images/productos/34.jpg', 'Bonle 250g', 12.90, 30, 5, '2025-07-22', '2023-11-30 21:04:28', 0x31),
(35, 12, 7, NULL, 'Lavavajilla ', 'images/productos/35.jpg', 'Ayudin lima limon liquido botella 1.2 L', 23.00, 30, 5, '2026-11-30', '2023-11-30 21:24:42', 0x31),
(36, 12, 7, NULL, 'Clorox', 'images/productos/36.jpg', 'Lejia para ropa blanca 324 ml', 1.50, 30, 5, '2026-11-30', '2023-11-30 21:28:47', 0x31),
(37, 12, 7, NULL, 'Scotch brite', 'images/productos/37.jpg', 'Guantes para cocina', 10.90, 30, 5, '2028-01-01', '2023-11-30 21:32:42', 0x31),
(38, 12, 7, NULL, 'Poet', 'images/productos/38.jpg', 'Limpiatodo aroma a flores 1L', 4.80, 30, 5, '2026-11-30', '2023-11-30 21:35:46', 0x31),
(39, 12, 7, NULL, 'Bolivar ', 'images/productos/39.jpg', 'Detergente 330g', 6.60, 30, 5, '2026-11-30', '2023-11-30 21:37:51', 0x31),
(40, 10, 7, NULL, 'Jabon Liquido', 'images/productos/40.jpg', 'Antibacterial frutos rojos 380 ml', 7.50, 30, 5, '2026-11-30', '2023-11-30 21:41:47', 0x31),
(41, 15, 4, NULL, 'Pate', 'images/productos/41.jpg', 'Ricocat sabor higado y pollo 330g', 5.50, 30, 5, '2025-11-20', '2023-12-01 14:28:47', 0x31),
(42, 15, 4, NULL, 'Ricocat', 'images/productos/42.jpg', 'Comida para gato 3k', 39.50, 30, 5, '2026-02-10', '2023-12-01 14:31:00', 0x31);

--

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `fecha_registro` date NOT NULL DEFAULT (curdate()),
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `id_empresa`, `fecha_registro`, `estado`) VALUES
(1, 3, '2023-11-24', 0x31),
(2, 5, '2023-11-24', 0x31),
(3, 6, '2023-11-27', 0x30),
(4, 7, '2023-11-27', 0x31),
(5, 8, '2023-11-27', 0x31),
(6, 9, '2023-11-27', 0x31),
(7, 10, '2023-11-27', 0x31),
(8, 11, '2023-11-27', 0x31),
(9, 12, '2023-11-27', 0x31),
(10, 13, '2023-11-27', 0x31),
(11, 14, '2023-11-27', 0x31),
(12, 15, '2023-11-27', 0x31),
(13, 16, '2023-11-27', 0x31),
(14, 17, '2023-11-27', 0x31);

--

--
-- Estructura de tabla para la tabla `provincia`
--

CREATE TABLE `provincia` (
  `id_provincia` int(11) NOT NULL,
  `id_departamento` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Estructura de tabla para la tabla `tienda`
--

CREATE TABLE `tienda` (
  `id_tienda` int(11) NOT NULL,
  `nom_tienda` varchar(100) NOT NULL,
  `razon_social` varchar(250) NOT NULL,
  `direccion` varchar(250) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tienda`
--

INSERT INTO `tienda` (`id_tienda`, `nom_tienda`, `razon_social`, `direccion`, `estado`) VALUES
(2, 'EL CORDON Y LA ROSA MARKET', 'EL CORDON Y LA ROSA MARKET EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA', 'AV. SAN MARTIN NRO. 1040 URB. SAN ISIDRO ICA ICA ICA', 0x31);

--

--
-- Estructura de tabla para la tabla `tipoacceso`
--

CREATE TABLE `tipoacceso` (
  `id_tipo_acceso` int(11) NOT NULL,
  `nom_tipo_acceso` varchar(50) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipoacceso`
--

INSERT INTO `tipoacceso` (`id_tipo_acceso`, `nom_tipo_acceso`, `estado`) VALUES
(1, 'Administrador', 0x31),
(2, 'Vendedor', 0x31),
(3, 'Almacenero', 0x31);

--

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `id_tipo_acceso` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `usuario` varchar(250) NOT NULL,
  `imagen` varchar(250) NOT NULL,
  `contrasena` varchar(250) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `id_tipo_acceso`, `id_persona`, `usuario`, `imagen`, `contrasena`, `estado`) VALUES
(1, 1, 2, 'erik-jhonatan', 'images/usuarios/2.jpg', '$2y$10$Ifl3Ks7oYF095zIUXz8xnuKyni13Sr6/OZneu7vg8dHB.T3eqhSx2', 0x31),
(3, 2, 11, 'marco.a', 'images/usuarios/11.jpg', '$2y$10$32oi/0mG52jRbfzVfJ66POt.Ini5R0Y.5MBpyAtGVskSg4AyRVKNO', 0x31);

--

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id_venta` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_metodo_pago` int(11) NOT NULL,
  `fecha_venta` datetime NOT NULL,
  `monto_total` decimal(6,2) NOT NULL,
  `dscto` decimal(6,2) NOT NULL,
  `dscto_total` decimal(6,2) NOT NULL,
  `subtotal` decimal(6,2) NOT NULL,
  `total_pagar` decimal(6,2) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `boleta`
--
ALTER TABLE `boleta`
  ADD PRIMARY KEY (`id_boleta`),
  ADD KEY `id_venta` (`id_venta`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `id_persona` (`id_persona`),
  ADD KEY `id_empresa` (`id_empresa`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_metodo_pago` (`id_metodo_pago`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`id_departamento`);

--
-- Indices de la tabla `detallecompra`
--
ALTER TABLE `detallecompra`
  ADD PRIMARY KEY (`id_detalle_compra`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_compra` (`id_compra`);

--
-- Indices de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD PRIMARY KEY (`id_detalle_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `distrito`
--
ALTER TABLE `distrito`
  ADD PRIMARY KEY (`id_distrito`),
  ADD KEY `id_provincia` (`id_provincia`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id_empresa`),
  ADD KEY `id_distrito` (`id_distrito`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `id_venta` (`id_venta`);

--
-- Indices de la tabla `facturacompra`
--
ALTER TABLE `facturacompra`
  ADD PRIMARY KEY (`id_factura_compra`),
  ADD KEY `id_compra` (`id_compra`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id_marca`),
  ADD KEY `id_empresa` (`id_empresa`);

--
-- Indices de la tabla `metodopago`
--
ALTER TABLE `metodopago`
  ADD PRIMARY KEY (`id_metodo_pago`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_persona`),
  ADD KEY `id_distrito` (`id_distrito`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_marca` (`id_marca`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`),
  ADD KEY `id_empresa` (`id_empresa`);

--
-- Indices de la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD PRIMARY KEY (`id_provincia`),
  ADD KEY `id_departamento` (`id_departamento`);

--
-- Indices de la tabla `tienda`
--
ALTER TABLE `tienda`
  ADD PRIMARY KEY (`id_tienda`);

--
-- Indices de la tabla `tipoacceso`
--
ALTER TABLE `tipoacceso`
  ADD PRIMARY KEY (`id_tipo_acceso`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `id_tipo_acceso` (`id_tipo_acceso`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_metodo_pago` (`id_metodo_pago`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `boleta`
--
ALTER TABLE `boleta`
  MODIFY `id_boleta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detallecompra`
--
ALTER TABLE `detallecompra`
  MODIFY `id_detalle_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  MODIFY `id_detalle_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `distrito`
--
ALTER TABLE `distrito`
  MODIFY `id_distrito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `facturacompra`
--
ALTER TABLE `facturacompra`
  MODIFY `id_factura_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id_marca` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `metodopago`
--
ALTER TABLE `metodopago`
  MODIFY `id_metodo_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `provincia`
--
ALTER TABLE `provincia`
  MODIFY `id_provincia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tienda`
--
ALTER TABLE `tienda`
  MODIFY `id_tienda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipoacceso`
--
ALTER TABLE `tipoacceso`
  MODIFY `id_tipo_acceso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `boleta`
--
ALTER TABLE `boleta`
  ADD CONSTRAINT `boleta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id_venta`);

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`),
  ADD CONSTRAINT `cliente_ibfk_2` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`);

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  ADD CONSTRAINT `compra_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `compra_ibfk_3` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodopago` (`id_metodo_pago`);

--
-- Filtros para la tabla `detallecompra`
--
ALTER TABLE `detallecompra`
  ADD CONSTRAINT `detallecompra_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`),
  ADD CONSTRAINT `detallecompra_ibfk_2` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id_compra`);

--
-- Filtros para la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD CONSTRAINT `detalleventa_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `distrito`
--
ALTER TABLE `distrito`
  ADD CONSTRAINT `distrito_ibfk_1` FOREIGN KEY (`id_provincia`) REFERENCES `provincia` (`id_provincia`);

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `empresa_ibfk_1` FOREIGN KEY (`id_distrito`) REFERENCES `distrito` (`id_distrito`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id_venta`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_ibfk_3` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodopago` (`id_metodo_pago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_subcategoria`
--
ALTER TABLE `producto_subcategoria`
  ADD CONSTRAINT `producto_subcategoria_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_subcategoria_ibfk_2` FOREIGN KEY (`id_subcategoria`) REFERENCES `subcategoria` (`id_subcategoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `subcategoria`
--
ALTER TABLE `subcategoria`
  ADD CONSTRAINT `subcategoria_ibfk_1` FOREIGN KEY (`id_subcategoria_tipo`) REFERENCES `subcategoria_tipo` (`id_subcategoria_tipo`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
-- --------------------------------------------------------
--
-- SUBCATEGORIAS
--
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subcategoria_tipo`
--

CREATE TABLE `subcategoria_tipo` (
  `id_subcategoria_tipo` int(11) NOT NULL,
  `nombre_tipo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subcategoria_tipo`
--

INSERT INTO `subcategoria_tipo` (`id_subcategoria_tipo`, `nombre_tipo`) VALUES
(1, 'Tamaño'),
(2, 'Lote'),
(3, 'Tipo de Envase');

--
-- Estructura de tabla para la tabla `subcategoria`
--

CREATE TABLE `subcategoria` (
  `id_subcategoria` int(11) NOT NULL,
  `id_subcategoria_tipo` int(11) NOT NULL,
  `valor` varchar(255) NOT NULL,
  `estado` binary(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subcategoria`
--

INSERT INTO `subcategoria` (`id_subcategoria`, `id_subcategoria_tipo`, `valor`, `estado`) VALUES
(1, 1, '600 ml', 1),
(2, 1, '1 Litro', 1),
(3, 1, '1.5 Litros', 1),
(4, 1, '3 Litros', 1),
(5, 3, 'Botella de Plástico', 1),
(6, 3, 'Lata', 1),
(7, 3, 'Caja', 1),
(8, 3, 'Bolsa', 1),
(9, 2, 'LOTE-A01-2024', 1),
(10, 2, 'LOTE-B02-2024', 1),
(11, 1, '45g', 1),
(12, 1, '190g', 1),
(13, 1, '30g', 1),
(14, 1, '250g', 1),
(15, 1, '395g', 1),
(16, 1, '400g', 1),
(17, 1, '330g', 1),
(18, 1, '3kg', 1);

--
-- Estructura de tabla para la tabla `producto_subcategoria`
--

CREATE TABLE `producto_subcategoria` (
  `id_producto` int(11) NOT NULL,
  `id_subcategoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto_subcategoria`
--

INSERT INTO `producto_subcategoria` (`id_producto`, `id_subcategoria`) VALUES
(13, 1), -- Inka Kola, 600 ml
(13, 5), -- Inka Kola, Botella de Plástico
(13, 9), -- Inka Kola, Lote
(14, 1), -- Coca cola, 600 ml
(14, 5), -- Coca cola, Botella de Plástico
(14, 9), -- Coca cola, Lote
(16, 2), -- Agua Cielo, 1 Litro
(16, 5), -- Agua Cielo, Botella de Plástico
(17, 3), -- Frugos Valle, 1.5 Litros
(17, 7), -- Frugos Valle, Caja
(21, 11), -- Doritos, 45g
(21, 8), -- Doritos, Bolsa
(28, 6), -- Gloria, Lata
(28, 15), -- Gloria, 395g
(42, 18), -- Ricocat, 3kg
(42, 8);  -- Ricocat, Bolsa

--
-- Indices de la tabla `subcategoria_tipo`
--
ALTER TABLE `subcategoria_tipo`
  ADD PRIMARY KEY (`id_subcategoria_tipo`);

--
-- Indices de la tabla `subcategoria`
--
ALTER TABLE `subcategoria`
  ADD PRIMARY KEY (`id_subcategoria`),
  ADD KEY `id_subcategoria_tipo` (`id_subcategoria_tipo`);

--
-- Indices de la tabla `producto_subcategoria`
--
ALTER TABLE `producto_subcategoria`
  ADD PRIMARY KEY (`id_producto`,`id_subcategoria`),
  ADD KEY `id_subcategoria` (`id_subcategoria`);

--
-- AUTO_INCREMENT de la tabla `subcategoria_tipo`
--
ALTER TABLE `subcategoria_tipo`
  MODIFY `id_subcategoria_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `subcategoria`
--
ALTER TABLE `subcategoria`
  MODIFY `id_subcategoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- Filtros para la tabla `subcategoria`
--
ALTER TABLE `subcategoria`
  ADD CONSTRAINT `subcategoria_ibfk_1` FOREIGN KEY (`id_subcategoria_tipo`) REFERENCES `subcategoria_tipo` (`id_subcategoria_tipo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_subcategoria`
--
ALTER TABLE `producto_subcategoria`
  ADD CONSTRAINT `producto_subcategoria_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_subcategoria_ibfk_2` FOREIGN KEY (`id_subcategoria`) REFERENCES `subcategoria` (`id_subcategoria`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;