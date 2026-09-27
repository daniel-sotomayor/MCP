-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-09-2026 a las 19:23:40
-- Versión del servidor: 12.1.2-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hospital_bienes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `id_area` int(11) NOT NULL,
  `nombre_area` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `edificio` varchar(50) DEFAULT NULL,
  `piso` tinyint(4) DEFAULT NULL COMMENT '-1=Sótano, 0=Planta Baja, 1..N=Pisos',
  `responsable_id` int(11) DEFAULT NULL,
  `area_padre_id` int(11) DEFAULT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`id_area`, `nombre_area`, `descripcion`, `edificio`, `piso`, `responsable_id`, `area_padre_id`, `activa`, `created_at`, `updated_at`) VALUES
(1, 'Mantenimiento Tecnico', 'Taller de mantenimiento', 'Principal', -1, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(2, 'Lavanderia', 'Servicio de lavanderia', 'Principal', -1, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(3, 'Electromedicina', 'Dept. electromedicina', 'Principal', -1, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(4, 'Servicios Generales', 'Servicios generales', 'Principal', -1, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(5, 'Telecomunicaciones', 'Centro telecomunicaciones', 'Principal', -1, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(6, 'Sala de Parto', 'Sala de partos', 'Principal', 1, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(7, 'Quirófano', 'Salas de operaciones', 'Principal', 1, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(8, 'Banco de Sangre', 'Banco de sangre', 'Principal', 1, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(9, 'Anestesia', 'Dept. anestesia', 'Principal', 1, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(10, 'Terapia Adulto', 'Unidad terapia adulto', 'Principal', 1, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(11, 'Nutrición', 'Dept. nutrición', 'Principal', 1, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(12, 'Gerencia de Bienes Nacionales', 'Dept. control bienes', 'Principal', 1, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(13, 'Administracion', 'Area administrativa', 'Principal', 1, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(14, 'Materno-Fetal', 'Unidad materno-fetal', 'Principal', 2, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(15, 'Consultas de Eco', 'Ecografia', 'Principal', 2, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(16, 'Hospitalizacion P2', 'Hosp. piso 2', 'Principal', 2, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(17, 'Hospitalizacion P3', 'Hosp. piso 3', 'Principal', 3, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(18, 'Hospitalizacion P5', 'Hosp. piso 5', 'Principal', 5, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(19, 'Residencias Medicas', 'Residencias medicas', 'Principal', 9, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(20, 'Milicianos', 'Sede milicianos', 'Anexo', -1, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(21, 'Odontologia', 'Servicio odontologia', 'Anexo', 0, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(22, 'Prenatal', 'Consultas prenatales', 'Anexo', 0, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(23, 'Adolescentes', 'Servicio adolescentes', 'Anexo', 1, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(24, 'Fertilidad', 'Unidad fertilidad', 'Anexo', 3, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(25, 'Psiquiatria', 'Servicio psiquiatria', 'Anexo', 3, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(26, 'Medicina Interna', 'Medicina interna', 'Anexo', 3, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(27, 'Cuidados Intensivos Neonatal', 'UCIN', 'Anexo', 2, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(28, 'Urgencias', 'Servicio urgencias 24h', 'Principal', 0, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(29, 'Farmacia', 'Farmacia central', 'Principal', 0, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(30, 'Radiologia', 'Radiologia e imagenologia', 'Principal', 0, NULL, NULL, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id_auditoria` int(11) NOT NULL,
  `tabla_afectada` varchar(50) NOT NULL,
  `registro_id` int(11) DEFAULT NULL,
  `accion` varchar(20) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `datos_anteriores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_anteriores`)),
  `datos_nuevos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_nuevos`)),
  `fecha_operacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id_auditoria`, `tabla_afectada`, `registro_id`, `accion`, `usuario_id`, `ip_address`, `user_agent`, `datos_anteriores`, `datos_nuevos`, `fecha_operacion`) VALUES
(155, 'movimientos', 13, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:18:45'),
(156, 'usuarios', 1, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:19:29'),
(157, 'movimientos', 14, 'INSERT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:22:10'),
(158, 'movimientos', 12, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:23:32'),
(159, 'usuarios', 5, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:26:22'),
(160, 'movimientos', 11, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:37:40'),
(161, 'usuarios', 1, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:40:59'),
(162, 'usuarios', 2, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:41:12'),
(163, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:55:06'),
(164, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:55:08'),
(165, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:55:08'),
(166, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:55:08'),
(167, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:55:08'),
(168, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:55:08'),
(169, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:55:09'),
(170, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:55:09'),
(171, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:55:09'),
(172, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:55:09'),
(173, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:55:20'),
(174, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:55:44'),
(175, 'usuarios', 1, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 11:56:41'),
(176, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:03:19'),
(177, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:06:06'),
(178, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:06:15'),
(179, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:06:21'),
(180, 'usuarios', 6, 'INSERT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:07:15'),
(181, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:07:21'),
(182, 'usuarios', 6, 'login', 6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:07:39'),
(183, 'usuarios', 6, 'UPDATE', 6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:08:18'),
(184, 'usuarios', 6, 'logout', 6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:08:23'),
(185, 'usuarios', 5, 'login', 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:12:40'),
(186, 'usuarios', 5, 'UPDATE', 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:13:16'),
(187, 'configuracion', 0, 'UPDATE', 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:13:42'),
(188, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:23:49'),
(189, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:23:59'),
(190, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:23:59'),
(191, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:24:00'),
(192, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:24:00'),
(193, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:24:12'),
(194, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:25:03'),
(195, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:25:21'),
(196, 'usuarios', 6, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:26:44'),
(197, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:26:48'),
(198, 'usuarios', 6, 'login', 6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:28:37'),
(199, 'usuarios', 6, 'logout', 6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:29:06'),
(200, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:33:16'),
(201, 'usuarios', 6, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:33:38'),
(202, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:33:41'),
(203, 'usuarios', 6, 'login', 6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, NULL, '2026-08-08 12:33:49'),
(204, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 15:01:18'),
(205, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 15:20:24'),
(206, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 15:22:15'),
(207, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 15:22:47'),
(208, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 15:22:47'),
(209, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 15:22:48'),
(210, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 15:54:54'),
(211, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 16:35:22'),
(212, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 16:44:30'),
(213, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 16:55:17'),
(214, 'usuarios', 6, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 16:55:44'),
(215, 'usuarios', 6, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 16:56:05'),
(216, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 16:56:09'),
(217, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:06:18'),
(218, 'configuracion', 0, 'UPDATE', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:12:27'),
(219, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:12:50'),
(220, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:12:51'),
(221, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:12:51'),
(222, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:12:51'),
(223, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:13:17'),
(224, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:18:55'),
(225, 'usuarios', 1, 'logout_tab_closed', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:19:00'),
(226, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:19:25'),
(227, 'usuarios', 1, 'logout_tab_closed', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:19:28'),
(228, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:19:47'),
(229, 'usuarios', 1, 'logout_tab_closed', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:19:51'),
(230, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:20:27'),
(231, 'usuarios', 1, 'logout_tab_closed', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:20:30'),
(232, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:22:58'),
(233, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:23:17'),
(234, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:23:17'),
(235, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:23:17'),
(236, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:23:17'),
(237, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:23:17'),
(238, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:23:17'),
(239, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:23:18'),
(240, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:23:23'),
(241, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:23:24'),
(242, 'configuracion', 0, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:23:24'),
(243, 'movimientos', 15, 'INSERT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:24:43'),
(244, 'usuarios', 1, 'logout_tab_closed', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:24:43'),
(245, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:34:36'),
(246, 'movimientos', 16, 'INSERT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:35:38'),
(247, 'usuarios', 1, 'logout_tab_closed', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:37:07'),
(248, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:46:16'),
(249, 'usuarios', 1, 'logout_tab_closed', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:54:02'),
(250, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:54:12'),
(251, 'usuarios', 1, 'logout_tab_closed', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:58:33'),
(252, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:58:44'),
(253, 'bienes', 1, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 17:59:33'),
(254, 'verificaciones', 9, 'VERIFY', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, '{\"bien_id\":1}', '2026-08-09 17:59:40'),
(255, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-09 18:07:33'),
(256, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-10 20:50:43'),
(257, 'movimientos', 17, 'INSERT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-10 20:56:31'),
(258, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-10 21:05:50'),
(259, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-18 15:55:01'),
(260, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, NULL, '2026-08-18 15:55:06'),
(261, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, NULL, '2026-09-27 16:41:49'),
(262, 'bienes', 81, 'INSERT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, NULL, '2026-09-27 16:50:07'),
(263, 'verificaciones', 10, 'VERIFY', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '{\"bien_id\":32}', '2026-09-27 16:53:20'),
(264, 'movimientos', 19, 'INSERT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, NULL, '2026-09-27 16:54:59'),
(265, 'verificaciones', 11, 'VERIFY', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '{\"bien_id\":6}', '2026-09-27 17:00:31'),
(266, 'verificaciones', 12, 'VERIFY', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, '{\"bien_id\":6}', '2026-09-27 17:00:39'),
(267, 'usuarios', 1, 'logout_tab_closed', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, NULL, '2026-09-27 17:03:04'),
(268, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, NULL, '2026-09-27 17:03:13'),
(269, 'usuarios', 1, 'logout_tab_closed', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, NULL, '2026-09-27 17:03:25'),
(270, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, NULL, '2026-09-27 17:03:59'),
(271, 'usuarios', 1, 'logout_tab_closed', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, NULL, '2026-09-27 17:07:38'),
(272, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, NULL, '2026-09-27 17:07:59'),
(273, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, NULL, '2026-09-27 17:09:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `backups_log`
--

CREATE TABLE `backups_log` (
  `id_backup` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('success','error') NOT NULL DEFAULT 'success',
  `error_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bienes`
--

CREATE TABLE `bienes` (
  `id_bien` int(11) NOT NULL,
  `codigo_sudebip` varchar(50) NOT NULL,
  `codigo_interno` varchar(50) DEFAULT NULL,
  `codigo_ministerio` varchar(50) DEFAULT NULL,
  `nro_bien_ministerio` varchar(30) DEFAULT NULL,
  `es_sn` tinyint(1) NOT NULL DEFAULT 0,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `serial` varchar(100) DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `anio_fabricacion` smallint(6) DEFAULT NULL,
  `cantidad` smallint(6) NOT NULL DEFAULT 1,
  `condicion_inicial` enum('Nuevo','Bueno','Regular','Malo') DEFAULT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1,
  `id_tipo` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  `responsable_id` int(11) DEFAULT NULL,
  `responsable_nombre` varchar(255) DEFAULT NULL,
  `responsable_cedula` varchar(20) DEFAULT NULL,
  `responsable_foto_path` varchar(500) DEFAULT NULL,
  `cin_edificio` varchar(50) DEFAULT NULL,
  `cin_piso` varchar(10) DEFAULT NULL,
  `cin_departamento` varchar(100) DEFAULT NULL,
  `cin_oficina` varchar(100) DEFAULT NULL,
  `cin_posicion` varchar(20) DEFAULT NULL,
  `numero_factura` varchar(50) DEFAULT NULL,
  `fecha_adquisicion` date DEFAULT NULL,
  `valor_inicial` decimal(15,2) DEFAULT NULL,
  `valor_residual` decimal(15,2) NOT NULL DEFAULT 0.00,
  `vida_util_anos` tinyint(4) NOT NULL DEFAULT 10,
  `observaciones` text DEFAULT NULL,
  `imagen_path` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `bienes`
--

INSERT INTO `bienes` (`id_bien`, `codigo_sudebip`, `codigo_interno`, `codigo_ministerio`, `nro_bien_ministerio`, `es_sn`, `nombre`, `descripcion`, `serial`, `marca`, `modelo`, `color`, `anio_fabricacion`, `cantidad`, `condicion_inicial`, `id_estado`, `id_tipo`, `id_area`, `responsable_id`, `responsable_nombre`, `responsable_cedula`, `responsable_foto_path`, `cin_edificio`, `cin_piso`, `cin_departamento`, `cin_oficina`, `cin_posicion`, `numero_factura`, `fecha_adquisicion`, `valor_inicial`, `valor_residual`, `vida_util_anos`, `observaciones`, `imagen_path`, `created_at`, `updated_at`) VALUES
(1, 'BN-2024-000001', '00-1-ANE-9-0001', NULL, 'MN-001', 0, 'Escritorio Ejecutivo', 'Escritorio con cajones', 'ESC-001', 'Ofimax', 'EX-400', 'Marron', 2022, 1, 'Bueno', 1, 2, 22, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Anexo', '0', 'Prenatal', 'Direccion', 'P-01', 'FAC-001', '2024-01-15', 350.00, 35.00, 10, 'adwdsdawdadwd', NULL, '2024-01-15 14:00:00', '2026-08-09 17:59:33'),
(2, 'BN-2024-000002', '00-2-ANE-9-0002', NULL, 'MN-002', 0, 'Silla Ergonomica', 'Giratoria ergonomica', 'SIL-002', 'Eurotech', 'ZUO', 'Negro', 2023, 1, 'Nuevo', 1, 1, 6, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Principal', '1', 'Sala de Parto', 'Gerencia', 'P-02', 'FAC-002', '2024-02-10', 280.00, 28.00, 10, NULL, NULL, '2024-02-10 14:00:00', '2026-08-10 20:56:31'),
(3, 'BN-2024-000003', '00-2-ANE-9-0003', NULL, 'MN-003', 0, 'Computadora de Escritorio', 'PC i5 8GB 256GB SSD', 'PC-003', 'HP', 'Pro 280 G6', 'Negro', 2023, 1, 'Nuevo', 1, 14, 12, NULL, 'Ana Luz Garcia', '21345678', NULL, 'Principal', '1', 'Gerencia BN', 'Control', 'P-03', 'FAC-003', '2024-01-20', 890.00, 89.00, 5, NULL, NULL, '2024-01-20 14:00:00', '2024-01-20 14:00:00'),
(4, 'BN-2024-000004', '00-2-ANE-9-0004', NULL, 'MN-004', 0, 'Monitor 24 pulgadas', 'LED Full HD 24\"', 'MON-004', 'Samsung', 'LF24T350', 'Negro', 2023, 1, 'Nuevo', 1, 14, 12, NULL, 'Ana Luz Garcia', '21345678', NULL, 'Principal', '1', 'Gerencia BN', 'Control', 'P-04', 'FAC-004', '2024-01-20', 195.00, 19.50, 5, NULL, NULL, '2024-01-20 14:00:00', '2024-01-20 14:00:00'),
(5, 'BN-2024-000005', '00-2-ANE-9-0005', NULL, 'MN-005', 0, 'Impresora Laser', 'Laser monocromatica', 'IMP-005', 'Brother', 'HL-L2350DW', 'Gris', 2023, 1, 'Nuevo', 1, 14, 13, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Principal', '1', 'Administracion', 'Archivo', 'P-05', 'FAC-005', '2024-03-01', 220.00, 22.00, 5, NULL, NULL, '2024-03-01 14:00:00', '2024-03-01 14:00:00'),
(6, 'BN-2024-000006', '00-1-ANE-9-0006', NULL, 'MN-006', 0, 'Archivador Metalico', '4 cajones metalico', 'ARC-006', 'Vidaland', 'A4-400', 'Gris', 2022, 1, 'Bueno', 1, 1, 13, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '1', 'Administracion', 'Archivo', 'P-06', 'FAC-006', '2024-01-10', 180.00, 18.00, 10, NULL, NULL, '2024-01-10 14:00:00', '2024-01-10 14:00:00'),
(7, 'BN-2024-000007', '00-2-ANE-9-0007', NULL, 'MN-007', 0, 'Aire Acondicionado', 'Split 12000 BTU', 'AAC-007', 'Daikin', 'FTKF35TV', 'Blanco', 2023, 1, 'Nuevo', 1, 3, 13, NULL, 'Luis Fernando Sanchez', '17654321', NULL, 'Principal', '1', 'Administracion', 'Gerencia', 'P-07', 'FAC-007', '2024-02-15', 650.00, 65.00, 10, NULL, NULL, '2024-02-15 14:00:00', '2024-02-15 14:00:00'),
(8, 'BN-2024-000008', '00-2-ANE-9-0008', NULL, 'MN-008', 0, 'Proyector Multimedia', 'LED 3500 lumenes', 'PRY-008', 'Epson', 'EB-W52', 'Blanco', 2023, 1, 'Nuevo', 1, 10, 10, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Principal', '1', 'Terapia Adulto', 'Sala Reunion', 'P-08', 'FAC-008', '2024-03-10', 520.00, 52.00, 10, NULL, NULL, '2024-03-10 14:00:00', '2024-03-10 14:00:00'),
(9, 'BN-2024-000009', '00-1-ANE-9-0009', NULL, 'MN-009', 0, 'Mesa de Reuniones', '8 personas', 'MR-009', 'OFIMBLE', 'MR-8P', 'Marron', 2022, 1, 'Bueno', 1, 1, 13, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Principal', '1', 'Administracion', 'Sala Juntas', 'P-09', 'FAC-009', '2024-01-05', 450.00, 45.00, 10, NULL, NULL, '2024-01-05 14:00:00', '2024-01-05 14:00:00'),
(10, 'BN-2024-000010', '00-2-ANE-5-0010', NULL, 'MN-010', 0, 'Telefono VoIP', 'Telefono IP escritorio', 'TEL-010', 'Cisco', 'SPA303', 'Negro', 2023, 1, 'Nuevo', 1, 6, 5, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '-1', 'Telecomunicaciones', 'Central', 'P-10', 'FAC-010', '2024-02-20', 150.00, 15.00, 8, NULL, NULL, '2024-02-20 14:00:00', '2024-02-20 14:00:00'),
(11, 'BN-2024-000011', '00-1-ANE-6-0001', NULL, 'MN-001', 0, 'Camilla Hospitalaria', 'Con ruedas y barandas', 'CAM-011', 'Hill-Rom', 'Centera', 'Blanco', 2022, 1, 'Bueno', 1, 7, 6, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '1', 'Sala de Parto', 'Box 1', 'P-11', 'FAC-011', '2024-01-15', 1800.00, 180.00, 10, NULL, NULL, '2024-01-15 14:00:00', '2024-01-15 14:00:00'),
(12, 'BN-2024-000012', '00-2-ANE-7-0002', NULL, 'MN-002', 0, 'Monitor Signos Vitales', 'Multiparametrico ECG/SpO2', 'MSV-012', 'Nihon Kohden', 'BSM-6301K', 'Gris', 2023, 1, 'Nuevo', 1, 7, 7, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Principal', '1', 'Quirófano', 'Q1', 'P-12', 'FAC-012', '2024-02-01', 3500.00, 350.00, 10, NULL, NULL, '2024-02-01 14:00:00', '2024-02-01 14:00:00'),
(13, 'BN-2024-000013', '00-2-ANE-9-0003', NULL, 'MN-003', 0, 'Desfibrilador', 'Automatico externo', 'DEF-013', 'Philips', 'HeartStart FRx', 'Gris', 2023, 1, 'Nuevo', 1, 7, 28, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Principal', '0', 'Urgencias', 'Sala R', 'P-13', 'FAC-013', '2024-03-05', 4200.00, 420.00, 10, NULL, NULL, '2024-03-05 14:00:00', '2024-03-05 14:00:00'),
(14, 'BN-2024-000014', '00-1-ANE-9-0004', NULL, 'MN-004', 0, 'Equipo de Anestesia', 'De cabecera', 'EQA-014', 'Dräger', 'Fabius GS', 'Blanco', 2022, 1, 'Bueno', 1, 8, 9, NULL, 'Luis Fernando Sanchez', '17654321', NULL, 'Principal', '1', 'Anestesia', 'Q2', 'P-14', 'FAC-014', '2024-01-25', 12000.00, 1200.00, 10, NULL, NULL, '2024-01-25 14:00:00', '2024-01-25 14:00:00'),
(15, 'BN-2024-000015', '00-2-ANE-9-0005', NULL, 'MN-005', 0, 'Incubadora Neonatal', 'Cuidados intensivos neonatal', 'INC-015', 'Draeger', 'BABybreze', 'Blanco', 2023, 1, 'Nuevo', 1, 8, 27, NULL, 'Ana Luz Garcia', '21345678', NULL, 'Anexo', '2', 'Cuidados Intensivos Neonatal', 'UCIN', 'P-15', 'FAC-015', '2024-04-01', 8500.00, 850.00, 10, NULL, NULL, '2024-04-01 14:00:00', '2024-04-01 14:00:00'),
(16, 'BN-2024-000016', '00-1-ANE-7-0006', NULL, 'MN-006', 0, 'Mesa Quirurgica', 'Hidraulica multifuncional', 'MQ-016', 'Trumpf', 'TruSystem 7500', 'Gris', 2022, 1, 'Bueno', 1, 8, 7, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '1', 'Quirófano', 'Q1', 'P-16', 'FAC-016', '2024-01-10', 8900.00, 890.00, 10, NULL, NULL, '2024-01-10 14:00:00', '2024-01-10 14:00:00'),
(17, 'BN-2024-000017', '00-2-ANE-7-0007', NULL, 'MN-007', 0, 'Lampara Quirurgica LED', 'Techo LED quirófano', 'LQ-017', 'Stryker', 'Solaris 7000', 'Blanco', 2023, 1, 'Nuevo', 1, 8, 7, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Principal', '1', 'Quirófano', 'Q1', 'P-17', 'FAC-017', '2024-02-20', 5600.00, 560.00, 10, NULL, NULL, '2024-02-20 14:00:00', '2024-02-20 14:00:00'),
(18, 'BN-2024-000018', '00-2-ANE-9-0008', NULL, 'MN-008', 0, 'Bomba de Infusion', 'Volumetrica', 'BI-018', 'B.Braun', 'Infusomat S', 'Blanco', 2023, 1, 'Nuevo', 1, 8, 10, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Principal', '1', 'Terapia Adulto', 'Box T', 'P-18', 'FAC-018', '2024-03-15', 1200.00, 120.00, 10, NULL, NULL, '2024-03-15 14:00:00', '2024-03-15 14:00:00'),
(19, 'BN-2024-000019', '00-1-ANE-6-0009', NULL, 'MN-009', 0, 'Oximetro de Pulso', 'Dedo', 'OXI-019', 'Nonin', '3230', 'Azul', 2022, 1, 'Bueno', 1, 7, 6, NULL, 'Ana Luz Garcia', '21345678', NULL, 'Principal', '1', 'Sala de Parto', 'Box 2', 'P-19', 'FAC-019', '2024-01-05', 350.00, 35.00, 10, NULL, NULL, '2024-01-05 14:00:00', '2024-01-05 14:00:00'),
(20, 'BN-2024-000020', '00-2-ANE-9-0010', NULL, 'MN-010', 0, 'Equipo Rayos X', 'Portatil digital', 'RX-020', 'Carestream', 'DRX-Revolution', 'Gris', 2023, 1, 'Nuevo', 1, 10, 21, NULL, 'Luis Fernando Sanchez', '17654321', NULL, 'Anexo', '0', 'Odontologia', 'Sala RX', 'P-20', 'FAC-020', '2024-02-01', 15000.00, 1500.00, 10, NULL, NULL, '2024-02-01 14:00:00', '2026-08-08 11:23:32'),
(21, 'BN-2024-000021', '00-2-ANE-5-0001', NULL, 'MN-001', 0, 'Servidor Rack', 'Servidor de datos', 'SRV-021', 'Dell', 'PowerEdge T350', 'Negro', 2023, 1, 'Nuevo', 1, 14, 5, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '-1', 'Telecomunicaciones', 'Server', 'P-21', 'FAC-021', '2024-01-30', 3200.00, 320.00, 5, NULL, NULL, '2024-01-30 14:00:00', '2024-01-30 14:00:00'),
(22, 'BN-2024-000022', '00-2-ANE-5-0002', NULL, 'MN-002', 0, 'Switch Red 48 Puertos', 'Gestionable Gigabit', 'SW-022', 'Cisco', 'CBS250-48T', 'Negro', 2023, 1, 'Nuevo', 1, 14, 5, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '-1', 'Telecomunicaciones', 'Rack', 'P-22', 'FAC-022', '2024-01-30', 890.00, 89.00, 5, NULL, NULL, '2024-01-30 14:00:00', '2024-01-30 14:00:00'),
(23, 'BN-2024-000023', '00-2-ANE-5-0003', NULL, 'MN-003', 0, 'UPS 3000VA', 'No-break 3000VA', 'UPS-023', 'APC', 'SRT3000', 'Negro', 2023, 1, 'Nuevo', 1, 4, 5, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Principal', '-1', 'Telecomunicaciones', 'Rack', 'P-23', 'FAC-023', '2024-02-10', 1800.00, 180.00, 5, NULL, NULL, '2024-02-10 14:00:00', '2024-02-10 14:00:00'),
(24, 'BN-2024-000024', '00-2-ANE-5-0004', NULL, 'MN-004', 0, 'Router Inalambrico', 'WiFi 6 empresarial', 'RUT-024', 'Ubiquiti', 'UniFi U6', 'Blanco', 2023, 1, 'Nuevo', 1, 6, 5, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Principal', '-1', 'Telecomunicaciones', 'Techo', 'P-24', 'FAC-024', '2024-03-01', 320.00, 32.00, 8, NULL, NULL, '2024-03-01 14:00:00', '2024-03-01 14:00:00'),
(25, 'BN-2024-000025', '00-2-ANE-9-0005', NULL, 'MN-005', 0, 'Notebook Ejecutiva', 'i7 16GB 512GB SSD', 'NB-025', 'Lenovo', 'ThinkPad T14', 'Negro', 2023, 1, 'Nuevo', 5, 14, 13, NULL, 'Luis Fernando Sanchez', '17654321', NULL, 'Principal', '1', 'Administracion', 'Gerencia', 'P-25', 'FAC-025', '2024-02-15', 1450.00, 145.00, 5, NULL, NULL, '2024-02-15 14:00:00', '2026-08-08 11:18:45'),
(26, 'BN-2024-000026', '00-1-ANE-1-0001', NULL, 'MN-001', 0, 'Aspiradora Industrial', 'Agua y polvo industrial', 'ASP-026', 'Kärcher', 'NT 30/1', 'Amarillo', 2022, 1, 'Bueno', 1, 3, 1, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '-1', 'Mantenimiento', 'Taller', 'P-26', 'FAC-026', '2024-01-10', 850.00, 85.00, 10, NULL, NULL, '2024-01-10 14:00:00', '2024-01-10 14:00:00'),
(27, 'BN-2024-000027', '00-2-ANE-1-0002', NULL, 'MN-002', 0, 'Hidrolavadora', 'Presion limpieza', 'HID-027', 'Kärcher', 'K5', 'Amarillo', 2023, 1, 'Nuevo', 1, 3, 1, NULL, 'Ana Luz Garcia', '21345678', NULL, 'Principal', '-1', 'Mantenimiento', 'Taller', 'P-27', 'FAC-027', '2024-02-01', 620.00, 62.00, 10, NULL, NULL, '2024-02-01 14:00:00', '2024-02-01 14:00:00'),
(28, 'BN-2024-000028', '00-1-ANE-2-0003', NULL, 'MN-003', 0, 'Plancha Industrial Vapor', 'Industrial lavanderia', 'PLA-028', 'Speed Queen', 'SP18', 'Gris', 2022, 1, 'Bueno', 1, 3, 2, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Principal', '-1', 'Lavanderia', 'Planchado', 'P-28', 'FAC-028', '2024-01-20', 2800.00, 280.00, 10, NULL, NULL, '2024-01-20 14:00:00', '2024-01-20 14:00:00'),
(29, 'BN-2024-000029', '00-2-ANE-2-0004', NULL, 'MN-004', 0, 'Lavadora Industrial', 'Automatica 20kg', 'LAV-029', 'Miele', 'PW 6123', 'Gris', 2023, 1, 'Nuevo', 1, 3, 2, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Principal', '-1', 'Lavanderia', 'Lavado', 'P-29', 'FAC-029', '2024-03-01', 4500.00, 450.00, 10, NULL, NULL, '2024-03-01 14:00:00', '2024-03-01 14:00:00'),
(30, 'BN-2024-000030', '00-2-ANE-2-0005', NULL, 'MN-005', 0, 'Secadora Industrial', 'Tambor industrial', 'SEC-030', 'Miele', 'PT 8303', 'Gris', 2023, 1, 'Nuevo', 1, 3, 2, NULL, 'Luis Fernando Sanchez', '17654321', NULL, 'Principal', '-1', 'Lavanderia', 'Secado', 'P-30', 'FAC-030', '2024-03-01', 3800.00, 380.00, 10, NULL, NULL, '2024-03-01 14:00:00', '2024-03-01 14:00:00'),
(31, 'BN-2024-000031', '00-2-ANE-9-0001', NULL, 'MN-001', 0, 'Silla Odontologica', 'Dental instrumentos basicos', 'SOD-031', 'A dec', 'Allegro II', 'Azul', 2023, 1, 'Nuevo', 1, 9, 21, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Anexo', '0', 'Odontologia', 'Consultorio', 'P-31', 'FAC-031', '2024-02-01', 5200.00, 520.00, 10, NULL, NULL, '2024-02-01 14:00:00', '2024-02-01 14:00:00'),
(32, 'BN-2024-000032', '00-1-ANE-9-0002', NULL, 'MN-002', 0, 'Rayos X Dental', 'Periapical dental', 'RXD-032', 'Dürr', 'Dentalix', 'Blanco', 2022, 1, 'Bueno', 5, 9, 21, NULL, 'Ana Luz Garcia', '21345678', NULL, 'Anexo', '0', 'Odontologia', 'Consultorio', 'P-32', 'FAC-032', '2024-01-15', 3200.00, 320.00, 10, NULL, NULL, '2024-01-15 14:00:00', '2026-09-27 16:54:59'),
(33, 'BN-2024-000033', '00-2-ANE-9-0003', NULL, 'MN-003', 0, 'Compresor Sin Aceite', 'Dental sin aceite', 'CDA-033', 'Tecnogaz', 'T201', 'Blanco', 2023, 1, 'Nuevo', 1, 9, 21, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Anexo', '0', 'Odontologia', 'Consultorio', 'P-33', 'FAC-033', '2024-02-10', 1800.00, 180.00, 10, NULL, NULL, '2024-02-10 14:00:00', '2024-02-10 14:00:00'),
(34, 'BN-2024-000034', '00-2-ANE-9-0004', NULL, 'MN-004', 0, 'Autoclave Dental', 'Esterilizacion instrumentos', 'ACL-034', 'Cox', 'Prestige', 'Gris', 2023, 1, 'Nuevo', 1, 9, 21, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Anexo', '0', 'Odontologia', 'Esterilizacion', 'P-34', 'FAC-034', '2024-03-01', 2100.00, 210.00, 10, NULL, NULL, '2024-03-01 14:00:00', '2024-03-01 14:00:00'),
(35, 'BN-2024-000035', '00-2-ANE-9-0005', NULL, 'MN-005', 0, 'Lampara Operatoria Dental', 'LED operatoria dental', 'LOD-035', 'LED Medical', 'D-LED', 'Blanco', 2023, 1, 'Nuevo', 1, 9, 21, NULL, 'Luis Fernando Sanchez', '17654321', NULL, 'Anexo', '0', 'Odontologia', 'Consultorio', 'P-35', 'FAC-035', '2024-02-15', 450.00, 45.00, 10, NULL, NULL, '2024-02-15 14:00:00', '2024-02-15 14:00:00'),
(36, 'BN-2024-000036', '00-2-ANE-9-0006', NULL, 'MN-006', 0, 'Ecografo Portatil', 'Sonda convexa', 'ECO-036', 'GE', 'Venue Go', 'Gris', 2023, 1, 'Nuevo', 1, 10, 15, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '2', 'Consultas de Eco', 'Sala Eco', 'P-36', 'FAC-036', '2024-02-20', 8500.00, 850.00, 10, NULL, NULL, '2024-02-20 14:00:00', '2024-02-20 14:00:00'),
(37, 'BN-2024-000037', '00-1-ANE-9-0007', NULL, 'MN-007', 0, 'Ecografo de Piso', 'Con impresora termica', 'ECOP-037', 'Philips', 'Affiniti 50', 'Gris', 2022, 1, 'Bueno', 1, 10, 14, NULL, 'Ana Luz Garcia', '21345678', NULL, 'Principal', '2', 'Materno-Fetal', 'Sala MF', 'P-37', 'FAC-037', '2024-01-10', 18000.00, 1800.00, 10, NULL, NULL, '2024-01-10 14:00:00', '2024-01-10 14:00:00'),
(38, 'BN-2024-000038', '00-2-ANE-9-0011', NULL, 'MN-011', 0, 'Doppler Fetal', 'Portatil', 'DOP-038', 'Huntleigh', 'DigiMax', 'Azul', 2023, 1, 'Nuevo', 1, 8, 22, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Anexo', '0', 'Prenatal', 'Consulta', 'P-38', 'FAC-038', '2024-03-10', 380.00, 38.00, 10, NULL, NULL, '2024-03-10 14:00:00', '2024-03-10 14:00:00'),
(39, 'BN-2024-000039', '00-1-ANE-6-0012', NULL, 'MN-012', 0, 'Tocodinametro', 'Monitor contracciones', 'TOD-039', 'Corometrics', '259', 'Gris', 2022, 1, 'Bueno', 1, 8, 6, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Principal', '1', 'Sala de Parto', 'Box 1', 'P-39', 'FAC-039', '2024-01-20', 2200.00, 220.00, 10, NULL, NULL, '2024-01-20 14:00:00', '2024-01-20 14:00:00'),
(40, 'BN-2024-000040', '00-2-ANE-9-0008', NULL, 'MN-008', 0, 'Rayos X Panoramico', 'Dental panoramico', 'RXP-040', 'Planmeca', 'ProMax', 'Blanco', 2023, 1, 'Nuevo', 1, 10, 30, NULL, 'Luis Fernando Sanchez', '17654321', NULL, 'Principal', '0', 'Radiologia', 'Sala RX', 'P-40', 'FAC-040', '2024-03-15', 22000.00, 2200.00, 10, NULL, NULL, '2024-03-15 14:00:00', '2024-03-15 14:00:00'),
(41, 'BN-2024-000041', '00-2-ANE-9-0001', NULL, 'MN-001', 0, 'Refrigerador Farmaceutico', '400L medicamentos', 'REF-041', 'Westinghouse', 'WRB400', 'Blanco', 2023, 1, 'Nuevo', 1, 2, 29, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '0', 'Farmacia', 'Almacen', 'P-41', 'FAC-041', '2024-02-01', 850.00, 85.00, 10, NULL, NULL, '2024-02-01 14:00:00', '2024-02-01 14:00:00'),
(42, 'BN-2024-000042', '00-2-ANE-9-0002', NULL, 'MN-002', 0, 'Refrigerador Vacunas', 'Monitor temperatura', 'REV-042', 'Gram', 'Biomedical', 'Blanco', 2023, 1, 'Nuevo', 1, 2, 29, NULL, 'Ana Luz Garcia', '21345678', NULL, 'Principal', '0', 'Farmacia', 'Vaccunas', 'P-42', 'FAC-042', '2024-02-10', 1200.00, 120.00, 10, NULL, NULL, '2024-02-10 14:00:00', '2024-02-10 14:00:00'),
(43, 'BN-2024-000043', '00-2-ANE-9-0003', NULL, 'MN-003', 0, 'Nevera Portatil', 'Transporte medicamentos', 'NEV-043', 'Igloo', 'BMX 65', 'Azul', 2023, 1, 'Nuevo', 1, 2, 11, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Principal', '1', 'Nutricion', 'Almacen', 'P-43', 'FAC-043', '2024-03-01', 180.00, 18.00, 10, NULL, NULL, '2024-03-01 14:00:00', '2024-03-01 14:00:00'),
(44, 'BN-2024-000044', '00-1-ANE-7-0004', NULL, 'MN-004', 0, 'Mesa Instrumentos', 'Inoxidable quirurgica', 'MEI-044', 'Mikron', 'MI-2000', 'Gris', 2022, 1, 'Bueno', 1, 3, 7, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Principal', '1', 'Quirófano', 'Q1', 'P-44', 'FAC-044', '2024-01-15', 650.00, 65.00, 10, NULL, NULL, '2024-01-15 14:00:00', '2024-01-15 14:00:00'),
(45, 'BN-2024-000045', '00-2-ANE-9-0005', NULL, 'MN-005', 0, 'Carro Curaciones', 'Multi-uso con cajones', 'CC-045', 'Bender', 'Moduline', 'Gris', 2023, 1, 'Nuevo', 1, 3, 28, NULL, 'Luis Fernando Sanchez', '17654321', NULL, 'Principal', '0', 'Urgencias', 'Sala Curac', 'P-45', 'FAC-045', '2024-03-10', 420.00, 42.00, 10, NULL, NULL, '2024-03-10 14:00:00', '2024-03-10 14:00:00'),
(46, 'BN-2023-000056', '00-1-ANE-9-0013', NULL, 'MN-013', 0, 'Impresora Matriz', 'Antigua impresora', 'IPM-056', 'Epson', 'LQ-350', 'Gris', 2018, 1, 'Regular', 2, 14, 13, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '1', 'Administracion', 'Archivo', 'P-56', 'FAC-056', '2023-06-01', 650.00, 65.00, 5, NULL, NULL, '2023-06-01 14:00:00', '2023-06-01 14:00:00'),
(47, 'BN-2023-000057', '00-1-ANE-9-0019', NULL, 'MN-019', 0, 'Oximetro de Piso', 'Pantalla TFT', 'OXIP-057', 'Masimo', 'Rad-97', 'Gris', 2020, 1, 'Bueno', 2, 7, 27, NULL, 'Ana Luz Garcia', '21345678', NULL, 'Anexo', '2', 'Cuidados Intensivos Neonatal', 'UCIN', 'P-57', 'FAC-057', '2023-03-15', 1800.00, 180.00, 10, NULL, NULL, '2023-03-15 14:00:00', '2023-03-15 14:00:00'),
(48, 'BN-2022-000058', '00-1-ANE-5-0007', NULL, 'MN-007', 0, 'Regulador 2000VA', 'Antiguo regulador', 'REG-058', 'Sencamer', 'RVE-2000', 'Gris', 2015, 1, 'Malo', 3, 4, 5, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Principal', '-1', 'Telecomunicaciones', 'Bodega', 'P-58', 'FAC-058', '2022-08-01', 280.00, 28.00, 5, NULL, NULL, '2022-08-01 14:00:00', '2022-08-01 14:00:00'),
(49, 'BN-2023-000061', '00-1-ANE-9-0021', NULL, 'MN-021', 0, 'Bomba Infusion 2', 'Falla electronica', 'BI2-061', 'B.Braun', 'Accufuser', 'Blanco', 2021, 1, 'Bueno', 2, 8, 10, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '1', 'Terapia Adulto', 'Bodega', 'P-61', 'FAC-061', '2023-01-10', 980.00, 98.00, 10, NULL, NULL, '2023-01-10 14:00:00', '2023-01-10 14:00:00'),
(50, 'BN-2022-000062', '00-1-ANE-4-0002', NULL, 'MN-002', 0, 'Motocicleta Oficial', 'Mensajeria institucional', 'MOT-062', 'Honda', 'Wave 110', 'Azul', 2020, 1, 'Bueno', 3, 5, 4, NULL, 'Ana Luz Garcia', '21345678', NULL, 'Principal', '-1', 'Servicios Gral', 'Garage', 'P-62', 'FAC-062', '2022-03-01', 1200.00, 120.00, 15, NULL, NULL, '2022-03-01 14:00:00', '2022-03-01 14:00:00'),
(51, 'BN-2023-000063', '00-1-ANE-9-0022', NULL, 'MN-022', 0, 'Silla de Ruedas', 'Estandar plegable', 'SRU-063', 'Drive', 'Silver', 'Azul', 2022, 1, 'Bueno', 3, 8, 28, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Principal', '0', 'Urgencias', 'Bodega', 'P-63', 'FAC-063', '2023-02-01', 350.00, 35.00, 10, NULL, NULL, '2023-02-01 14:00:00', '2023-02-01 14:00:00'),
(52, 'BN-2021-000059', '00-1-ANE-9-0008', NULL, 'MN-008', 0, 'Cama Hospitalaria Manual', 'Con ruedas', 'CHM-059', 'Stryker', 'InTouch', 'Gris', 2017, 1, 'Malo', 5, 8, 17, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Principal', '3', 'Hospitalizacion P3', 'Hab 302', 'P-59', 'FAC-059', '2021-05-01', 2200.00, 220.00, 10, NULL, NULL, '2021-05-01 14:00:00', '2021-05-01 14:00:00'),
(53, 'BN-2021-000060', '00-1-ANE-9-0014', NULL, 'MN-014', 0, 'Computadora Portatil', 'Laptop antigua', 'LPT-060', 'HP', 'Pavilion', 'Plata', 2016, 1, 'Regular', 2, 14, 13, NULL, 'Luis Fernando Sanchez', '17654321', NULL, 'Principal', '1', 'Administracion', 'Archivo', 'P-60', 'FAC-060', '2021-09-15', 780.00, 78.00, 5, NULL, NULL, '2021-09-15 14:00:00', '2021-09-15 14:00:00'),
(54, 'BN-2020-000064', '00-1-ANE-9-0008', NULL, 'MN-008', 0, 'Extintor ABC 10lbs', 'Quimico seco ABC', 'EXT-064', 'Amerex', 'B402', 'Rojo', 2019, 1, 'Nuevo', 5, 3, 28, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Principal', '0', 'Urgencias', 'Pasillo', 'P-64', 'FAC-064', '2020-01-15', 85.00, 8.50, 5, NULL, NULL, '2020-01-15 14:00:00', '2020-01-15 14:00:00'),
(55, 'BN-2020-000065', '00-1-ANE-1-0023', NULL, 'MN-023', 0, 'Manguera Incendio', '30 metros', 'MI-065', 'Tirosol', '30m-65mm', 'Rojo', 2018, 1, 'Regular', 5, 3, 1, NULL, 'Luis Fernando Sanchez', '17654321', NULL, 'Principal', '-1', 'Mantenimiento', 'Bodega', 'P-65', 'FAC-065', '2020-06-01', 220.00, 22.00, 5, NULL, NULL, '2020-06-01 14:00:00', '2020-06-01 14:00:00'),
(56, 'BN-2024-000046', '00-1-ANE-5-0001', NULL, 'MN-001', 0, 'Regulador 5000VA', 'Automatico', 'REG-046', 'Sencamer', 'RVE-5000', 'Gris', 2022, 1, 'Bueno', 1, 4, 5, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '-1', 'Telecomunicaciones', 'Rack', 'P-46', 'FAC-046', '2024-01-10', 380.00, 38.00, 5, NULL, NULL, '2024-01-10 14:00:00', '2024-01-10 14:00:00'),
(57, 'BN-2024-000047', '00-2-ANE-9-0011', NULL, 'MN-011', 0, 'Pizarra Digital', 'Interactiva 65\"', 'PD-047', 'ViewSonic', 'TD2455', 'Negro', 2023, 1, 'Nuevo', 1, 11, 10, NULL, 'Ana Luz Garcia', '21345678', NULL, 'Principal', '1', 'Terapia Adulto', 'Sala Prof', 'P-47', 'FAC-047', '2024-02-20', 1200.00, 120.00, 10, NULL, NULL, '2024-02-20 14:00:00', '2024-02-20 14:00:00'),
(58, 'BN-2024-000048', '00-2-ANE-4-0001', NULL, 'MN-001', 0, 'Camioneta 4x4', 'Transporte institucional', 'CAM-048', 'Toyota', 'Hilux', 'Blanco', 2023, 1, 'Nuevo', 1, 5, 4, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Principal', '-1', 'Servicios Gral', 'Garage', 'P-48', 'FAC-048', '2024-01-05', 35000.00, 3500.00, 15, NULL, NULL, '2024-01-05 14:00:00', '2024-01-05 14:00:00'),
(59, 'BN-2024-000049', '00-2-ANE-9-0014', NULL, 'MN-014', 0, 'Mamografo Digital', 'Mamografia digital', 'MAM-049', 'Hologic', 'Selenia', 'Gris', 2023, 1, 'Nuevo', 1, 10, 14, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Principal', '2', 'Materno-Fetal', 'Sala Mamog', 'P-49', 'FAC-049', '2024-03-01', 45000.00, 4500.00, 10, NULL, NULL, '2024-03-01 14:00:00', '2024-03-01 14:00:00'),
(60, 'BN-2024-000050', '00-1-ANE-9-0015', NULL, 'MN-015', 0, 'Colposcopio', 'Digital con camara', 'COL-050', 'Leisegang', 'Euroloupe', 'Gris', 2022, 1, 'Bueno', 1, 10, 22, NULL, 'Luis Fernando Sanchez', '17654321', NULL, 'Anexo', '0', 'Prenatal', 'Consulta', 'P-50', 'FAC-050', '2024-01-20', 3200.00, 320.00, 10, NULL, NULL, '2024-01-20 14:00:00', '2024-01-20 14:00:00'),
(61, 'BN-2024-000051', '00-1-ANE-9-0012', NULL, 'MN-012', 0, 'Silla Espera 3 plazas', 'Banca tapizada', 'SE-051', 'OFIMBLE', 'SE-3P', 'Gris', 2022, 1, 'Bueno', 1, 1, 28, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '0', 'Urgencias', 'Sala Espera', 'P-51', 'FAC-051', '2024-01-15', 280.00, 28.00, 10, NULL, NULL, '2024-01-15 14:00:00', '2024-01-15 14:00:00'),
(62, 'BN-2024-000052', '00-2-ANE-9-0016', NULL, 'MN-016', 0, 'Equipo ECG 12 Deriv.', 'Digital 12 deriv.', 'ECG-052', 'GE', 'MAC 2000', 'Gris', 2023, 1, 'Nuevo', 1, 7, 10, NULL, 'Ana Luz Garcia', '21345678', NULL, 'Principal', '1', 'Terapia Adulto', 'Box T', 'P-52', 'FAC-052', '2024-02-10', 2800.00, 280.00, 10, NULL, NULL, '2024-02-10 14:00:00', '2024-02-10 14:00:00'),
(63, 'BN-2024-000053', '00-2-ANE-9-0017', NULL, 'MN-017', 0, 'Nebulizador', 'Ultrasónico portatil', 'NEB-053', 'Omron', 'NE-C801', 'Blanco', 2023, 1, 'Nuevo', 1, 8, 17, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Principal', '3', 'Hospitalizacion P3', 'Hab 301', 'P-53', 'FAC-053', '2024-03-05', 180.00, 18.00, 10, NULL, NULL, '2024-03-05 14:00:00', '2024-03-05 14:00:00'),
(64, 'BN-2024-000054', '00-1-ANE-9-0018', NULL, 'MN-018', 0, 'Camilla Exploracion', 'Con rollo papel', 'CE-054', 'Nagar', 'SP-100', 'Gris', 2022, 1, 'Bueno', 1, 1, 19, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Principal', '9', 'Residencias Medicas', 'Consulta', 'P-54', 'FAC-054', '2024-01-25', 450.00, 45.00, 10, NULL, NULL, '2024-01-25 14:00:00', '2024-01-25 14:00:00'),
(65, 'BN-2024-000055', '00-2-ANE-9-0006', NULL, 'MN-006', 0, 'Mesa de Ayuda', 'Coordinacion', 'MAO-055', 'OFIMBLE', 'MO-2000', 'Gris', 2023, 1, 'Nuevo', 1, 1, 28, NULL, 'Luis Fernando Sanchez', '17654321', NULL, 'Principal', '0', 'Urgencias', 'Mesa Op', 'P-55', 'FAC-055', '2024-02-20', 680.00, 68.00, 10, NULL, NULL, '2024-02-20 14:00:00', '2024-02-20 14:00:00'),
(66, 'BN-2024-000066', '00-2-ANE-7-0024', NULL, 'MN-024', 0, 'Equipo Laparoscopia', 'Torre HD', 'LAP-066', 'Stryker', '1688 AIM', 'Gris', 2023, 1, 'Nuevo', 1, 8, 7, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '1', 'Quirófano', 'Q2', 'P-66', 'FAC-066', '2024-04-01', 28000.00, 2800.00, 10, NULL, NULL, '2024-04-01 14:00:00', '2024-04-01 14:00:00'),
(67, 'BN-2024-000067', '00-2-ANE-6-0025', NULL, 'MN-025', 0, 'Mesa de Parto', 'Electrica con frenos', 'MPT-067', 'Berger', 'Primus', 'Gris', 2023, 1, 'Nuevo', 1, 8, 6, NULL, 'Ana Luz Garcia', '21345678', NULL, 'Principal', '1', 'Sala de Parto', 'Box 3', 'P-67', 'FAC-067', '2024-03-15', 6800.00, 680.00, 10, NULL, NULL, '2024-03-15 14:00:00', '2024-03-15 14:00:00'),
(68, 'BN-2024-000068', '00-2-ANE-9-0026', NULL, 'MN-026', 0, 'Incubadora Prematuro', 'Control humedad', 'INC2-068', 'Draeger', 'BAByplus', 'Blanco', 2023, 1, 'Nuevo', 1, 8, 27, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Anexo', '2', 'Cuidados Intensivos Neonatal', 'UCIN', 'P-68', 'FAC-068', '2024-04-01', 9200.00, 920.00, 10, NULL, NULL, '2024-04-01 14:00:00', '2024-04-01 14:00:00'),
(69, 'BN-2024-000069', '00-2-ANE-9-0008', NULL, 'MN-008', 0, 'Impresora 3D Medica', 'Modelos anatomicos', 'I3D-069', 'Ultimaker', 'S5', 'Negro', 2023, 1, 'Nuevo', 1, 14, 10, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Principal', '1', 'Terapia Adulto', 'Laboratorio', 'P-69', 'FAC-069', '2024-05-01', 3500.00, 350.00, 5, NULL, NULL, '2024-05-01 14:00:00', '2024-05-01 14:00:00'),
(70, 'BN-2024-000070', '00-2-ANE-7-0027', NULL, 'MN-027', 0, 'Autoclave Grande', 'Mesa quirurgica', 'ACL2-070', 'Getinge', 'HS66', 'Gris', 2023, 1, 'Nuevo', 1, 8, 7, NULL, 'Luis Fernando Sanchez', '17654321', NULL, 'Principal', '1', 'Quirófano', 'Esteril', 'P-70', 'FAC-070', '2024-02-15', 5800.00, 580.00, 10, NULL, NULL, '2024-02-15 14:00:00', '2024-02-15 14:00:00'),
(71, 'BN-2024-000071', '00-2-ANE-9-0015', NULL, 'MN-015', 0, 'Escaner Documentos', 'Alta velocidad', 'ESC2-071', 'Fujitsu', 'ScanSnap iX1600', 'Blanco', 2023, 1, 'Nuevo', 1, 14, 13, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '1', 'Administracion', 'Archivo', 'P-71', 'FAC-071', '2024-03-01', 420.00, 42.00, 5, NULL, NULL, '2024-03-01 14:00:00', '2024-03-01 14:00:00'),
(72, 'BN-2024-000072', '00-2-ANE-9-0028', NULL, 'MN-028', 0, 'Pulsoximetro Neonatal', 'Sonda dedo', 'PXN-072', 'Masimo', 'LNOP', 'Azul', 2023, 1, 'Nuevo', 1, 8, 27, NULL, 'Ana Luz Garcia', '21345678', NULL, 'Anexo', '2', 'Cuidados Intensivos Neonatal', 'UCIN', 'P-72', 'FAC-072', '2024-04-10', 320.00, 32.00, 10, NULL, NULL, '2024-04-10 14:00:00', '2024-04-10 14:00:00'),
(73, 'BN-2024-000073', '00-2-ANE-9-0029', NULL, 'MN-029', 0, 'Equipo Ultrasonido', 'Portatil 3 sondas', 'US-073', 'GE', 'Venue Ace', 'Gris', 2023, 1, 'Nuevo', 1, 10, 14, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Principal', '2', 'Materno-Fetal', 'Sala US', 'P-73', 'FAC-073', '2024-03-20', 12500.00, 1250.00, 10, NULL, NULL, '2024-03-20 14:00:00', '2024-03-20 14:00:00'),
(74, 'BN-2024-000074', '00-2-ANE-7-0009', NULL, 'MN-009', 0, 'Bomba de Calor', 'Climatizacion quirófano', 'BCL-074', 'Carrier', '42CE', 'Blanco', 2023, 1, 'Nuevo', 1, 3, 7, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Principal', '1', 'Quirófano', 'Techos', 'P-74', 'FAC-074', '2024-02-01', 8500.00, 850.00, 15, NULL, NULL, '2024-02-01 14:00:00', '2024-02-01 14:00:00'),
(75, 'BN-2024-000075', '00-2-ANE-7-0030', NULL, 'MN-030', 0, 'Unidad de Succion', 'Quirurgica portatil', 'USU-075', 'Medela', 'Vacuum', 'Azul', 2023, 1, 'Nuevo', 1, 8, 7, NULL, 'Luis Fernando Sanchez', '17654321', NULL, 'Principal', '1', 'Quirófano', 'Q1', 'P-75', 'FAC-075', '2024-03-10', 680.00, 68.00, 10, NULL, NULL, '2024-03-10 14:00:00', '2024-03-10 14:00:00'),
(76, 'BN-2024-000076', '00-2-ANE-9-0016', NULL, 'MN-016', 0, 'Silla Oficina', 'Ejecutiva reposabrazos', 'SO-076', 'OFIMBLE', 'SE-PRO', 'Negro', 2024, 1, 'Nuevo', 1, 1, 13, NULL, 'Pedro Jose Fernandez', '19876543', NULL, 'Principal', '1', 'Administracion', 'Gerencia', 'P-76', 'FAC-076', '2024-05-01', 320.00, 32.00, 10, NULL, NULL, '2024-05-01 14:00:00', '2024-05-01 14:00:00'),
(77, 'BN-2024-000077', '00-2-ANE-9-0031', NULL, 'MN-031', 0, 'Tableta Clinica', 'Gestion clinica', 'TAB-077', 'Apple', 'iPad 10th', 'Plata', 2024, 1, 'Nuevo', 1, 14, 28, NULL, 'Ana Luz Garcia', '21345678', NULL, 'Principal', '0', 'Urgencias', 'Estacion', 'P-77', 'FAC-077', '2024-04-15', 580.00, 58.00, 5, NULL, NULL, '2024-04-15 14:00:00', '2024-04-15 14:00:00'),
(78, 'BN-2024-000078', '00-2-ANE-9-0032', NULL, 'MN-032', 0, 'Desfibrilador Pediatrico', 'Pads pediatricos', 'DEF2-078', 'Philips', 'HeartStart FRx', 'Gris', 2024, 1, 'Nuevo', 1, 7, 28, NULL, 'Maria Elena Rodriguez', '20123456', NULL, 'Principal', '0', 'Urgencias', 'Sala R2', 'P-78', 'FAC-078', '2024-05-01', 4800.00, 480.00, 10, NULL, NULL, '2024-05-01 14:00:00', '2024-05-01 14:00:00'),
(79, 'BN-2024-000079', '00-2-ANE-6-0033', NULL, 'MN-033', 0, 'Monitor Fetal', 'Frecuencia cardiaca fetal', 'MFT-079', 'GE', 'Corometrics 259cx', 'Gris', 2024, 1, 'Nuevo', 1, 8, 6, NULL, 'Carlos Alberto Martinez', '18765432', NULL, 'Principal', '1', 'Sala de Parto', 'Box 1', 'P-079', 'FAC-079', '2024-05-15', 3200.00, 320.00, 10, NULL, NULL, '2024-05-15 14:00:00', '2024-05-15 14:00:00'),
(80, 'BN-2024-000080', '00-2-ANE-7-0034', NULL, 'MN-034', 0, 'Equipo Electrocirugia', 'Pinzas y pedales', 'ECQ-080', 'Erbe', 'VIO 300', 'Gris', 2024, 1, 'Nuevo', 1, 8, 7, NULL, 'Luis Fernando Sanchez', '17654321', NULL, 'Principal', '1', 'Quirófano', 'Q1', 'P-080', 'FAC-080', '2024-06-01', 15000.00, 1500.00, 10, NULL, NULL, '2024-06-01 14:00:00', '2024-06-01 14:00:00'),
(81, 'BN-2026-000001', '06-2-PRI--1-0001', '', '645654SDFT', 0, 'Cama', 'Cama blanca reclinable con patas blancas y colores azules en los bordes', '124TYSAW', '', '', '', NULL, 1, 'Nuevo', 1, 9, 3, NULL, NULL, NULL, NULL, 'Principal', '-1', 'Electromedicina', 'sala 3B', '9', '', '2026-09-27', 0.00, 0.00, 10, 'Bien completo en Orden ', NULL, '2026-09-27 16:50:07', '2026-09-27 16:50:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id_configuracion` int(11) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `valor` text DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo_dato` varchar(20) NOT NULL DEFAULT 'string',
  `editable` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id_configuracion`, `clave`, `valor`, `descripcion`, `tipo_dato`, `editable`, `created_at`, `updated_at`) VALUES
(8, 'mail_from_email', 'danielsotomayor475@gmail.com', 'Correo electrónico remitente (notificaciones, códigos de recuperación)', 'string', 1, '2026-08-08 11:44:19', '2026-08-09 17:12:50'),
(9, 'mail_from_name', 'Sistema Bienes Nacionales', 'Nombre que aparece como remitente del correo', 'string', 1, '2026-08-08 11:44:19', '2026-08-08 11:44:19'),
(10, 'mail_host', 'smtp.gmail.com', 'Servidor SMTP (ej: smtp.gmail.com)', 'string', 1, '2026-08-08 11:44:19', '2026-08-08 11:44:19'),
(11, 'mail_port', '587', 'Puerto del servidor SMTP', 'integer', 1, '2026-08-08 11:44:19', '2026-08-08 11:44:19'),
(12, 'mail_username', 'danielsotomayor475@gmail.com', 'Usuario del correo SMTP (correo completo)', 'string', 1, '2026-08-08 11:44:19', '2026-08-08 12:25:21'),
(13, 'mail_password', 'qtlr dzxy wpnt oaql', 'Contraseña del correo SMTP (o contraseña de aplicación de Google)', 'string', 1, '2026-08-08 11:44:19', '2026-08-08 12:23:59'),
(14, 'mail_encryption', 'tls', 'Cifrado SMTP: tls o ssl', 'string', 1, '2026-08-08 11:44:19', '2026-08-08 11:44:19'),
(15, 'app_name', 'Sistema de Bienes Nacionales', 'Nombre del sistema', 'string', 1, '2026-08-08 11:44:19', '2026-08-08 11:44:19'),
(16, 'app_url', '', 'URL pública del sistema (ej: https://mcp.gob.ve/sbn)', 'string', 1, '2026-08-08 11:44:19', '2026-08-08 11:44:19'),
(17, 'session_timeout', '10', 'Tiempo de inactividad antes de cerrar sesión (minutos)', 'integer', 1, '2026-08-08 11:44:19', '2026-08-08 11:44:19'),
(18, 'login_max_attempts', '10', 'Máximo de intentos fallidos de login antes de bloqueo', 'integer', 1, '2026-08-08 11:44:19', '2026-08-09 17:23:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id_estado` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `color` varchar(7) DEFAULT NULL COMMENT 'Color HEX para la UI',
  `es_baja` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id_estado`, `nombre`, `descripcion`, `color`, `es_baja`, `created_at`) VALUES
(1, 'Operativo', 'Bien en funcionamiento normal', '#28a745', 0, '2026-05-26 17:40:34'),
(2, 'Inoperativo', 'Bien que no funciona pero es recuperable', '#ffc107', 0, '2026-05-26 17:40:34'),
(3, 'En Resguardo', 'Bien resguardado temporalmente', '#17a2b8', 0, '2026-05-26 17:40:34'),
(4, 'Chatarra', 'Bien sin posibilidad de recuperación', '#dc3545', 1, '2026-05-26 17:40:34'),
(5, 'Desincorporado', 'Bien dado de baja oficialmente', '#6c757d', 1, '2026-05-26 17:40:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimientos`
--

CREATE TABLE `mantenimientos` (
  `id_mantenimiento` int(11) NOT NULL,
  `bien_id` int(11) DEFAULT NULL,
  `tipo_servicio` enum('preventivo','correctivo','predictivo') NOT NULL,
  `fecha_programada` date DEFAULT NULL,
  `fecha_ejecutada` date DEFAULT NULL,
  `proveedor` varchar(255) DEFAULT NULL,
  `tecnico` varchar(255) DEFAULT NULL,
  `costo` decimal(15,2) DEFAULT NULL,
  `diagnostico` text DEFAULT NULL,
  `trabajo_realizado` text DEFAULT NULL,
  `proxima_fecha_programada` date DEFAULT NULL,
  `garantia_meses` tinyint(4) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `documento_reporte` varchar(500) DEFAULT NULL,
  `realizado_por_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `id_movimiento` int(11) NOT NULL,
  `bien_id` int(11) NOT NULL,
  `tipo_movimiento` enum('incorporacion','traslado','desincorporacion','asignacion') NOT NULL,
  `area_origen_id` int(11) DEFAULT NULL,
  `area_destino_id` int(11) DEFAULT NULL,
  `usuario_solicita_id` int(11) DEFAULT NULL,
  `usuario_aprueba_id` int(11) DEFAULT NULL,
  `usuario_recibe_id` int(11) DEFAULT NULL COMMENT 'Usuario que recibe el bien en el area destino',
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_aprobacion` timestamp NULL DEFAULT NULL,
  `fecha_ejecucion` timestamp NULL DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `contenido_acta` longtext DEFAULT NULL,
  `responsable_datos` text DEFAULT NULL,
  `documento_soporte` varchar(500) DEFAULT NULL,
  `estado` enum('pendiente','aprobado','rechazado','cancelado') NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`id_movimiento`, `bien_id`, `tipo_movimiento`, `area_origen_id`, `area_destino_id`, `usuario_solicita_id`, `usuario_aprueba_id`, `usuario_recibe_id`, `fecha_solicitud`, `fecha_aprobacion`, `fecha_ejecucion`, `motivo`, `observaciones`, `contenido_acta`, `responsable_datos`, `documento_soporte`, `estado`, `created_at`, `updated_at`) VALUES
(1, 3, 'incorporacion', NULL, 12, 4, 1, NULL, '2024-01-15 13:00:00', '2024-01-15 14:00:00', NULL, 'Incorporacion PC Gerencia BN', NULL, '<p>Acta Incorporación <strong>BN-2024-000003</strong> (PC) → Gerencia BN. Estado: Nuevo.</p>', NULL, NULL, 'aprobado', '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(2, 11, 'traslado', 6, 7, 4, 1, NULL, '2024-03-01 13:00:00', '2024-03-01 14:00:00', NULL, 'Traslado camilla Sala Parto → Quirófano', NULL, '<p>Acta Traslado <strong>BN-2024-000011</strong> (Camilla). Sala Parto → Quirófano.</p>', NULL, NULL, 'aprobado', '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(3, 1, 'traslado', 13, 13, 4, 2, NULL, '2024-04-01 13:00:00', '2024-04-01 14:00:00', NULL, 'Reubicacion escritorio admin', NULL, '<p>Acta Reubicación <strong>BN-2024-000001</strong> (Escritorio) dentro de Administración.</p>', NULL, NULL, 'aprobado', '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(4, 56, 'desincorporacion', 13, NULL, 3, 1, NULL, '2024-05-10 13:00:00', '2024-05-10 14:00:00', NULL, 'Impresora falla irreparable', NULL, '<p>Acta Desincorporación <strong>BN-2023-000056</strong> (Impresora Matriz). Falla alimentador.</p>', NULL, NULL, 'aprobado', '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(5, 59, 'desincorporacion', 17, NULL, 3, 1, NULL, '2024-06-01 13:00:00', '2024-06-01 14:00:00', NULL, 'Cama oxidada → chatarra', NULL, '<p>Acta Desincorporación <strong>BN-2021-000059</strong> (Cama). Estructura oxidada.</p>', NULL, NULL, 'aprobado', '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(6, 36, 'incorporacion', NULL, 15, 4, 1, NULL, '2024-02-20 13:00:00', '2024-02-20 14:00:00', NULL, 'Ecografo portatil Consultas Eco', NULL, '<p>Acta Incorporación <strong>BN-2024-000036</strong> (Ecógrafo) → Consultas de Eco.</p>', NULL, NULL, 'aprobado', '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(7, 15, 'incorporacion', NULL, 27, 4, 1, NULL, '2024-04-01 13:00:00', '2024-04-01 14:00:00', NULL, 'Incubadora neonatal UCIN', NULL, '<p>Acta Incorporación <strong>BN-2024-000015</strong> (Incubadora) → UCIN.</p>', NULL, NULL, 'aprobado', '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(8, 61, 'traslado', 10, 10, 4, 2, NULL, '2024-05-15 13:00:00', '2024-05-15 14:00:00', NULL, 'Bomba falla → bodega revision', NULL, '<p>Acta Traslado <strong>BN-2023-000061</strong> (Bomba) → bodega.</p>', NULL, NULL, 'aprobado', '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(9, 49, 'incorporacion', NULL, 14, 4, 1, NULL, '2024-03-01 13:00:00', '2024-03-01 14:00:00', NULL, 'Mamografo digital Materno-Fetal', NULL, '<p>Acta Incorporación <strong>BN-2024-000049</strong> (Mamógrafo) → Materno-Fetal.</p>', NULL, NULL, 'aprobado', '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(10, 67, 'incorporacion', NULL, 6, 4, 1, NULL, '2024-03-15 13:00:00', '2024-03-15 14:00:00', NULL, 'Mesa de parto Sala Parto', NULL, '<p>Acta Incorporación <strong>BN-2024-000067</strong> (Mesa Parto) → Sala Parto Box 3.</p>', NULL, NULL, 'aprobado', '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(11, 8, 'incorporacion', NULL, 8, 4, 1, NULL, '2026-08-01 13:00:00', '2026-08-08 11:37:40', NULL, 'Equipo de ultrasonido para Banco de Sangre', NULL, '<p>Acta Incorporación <strong>BN-2026-000008</strong> (Ultrasonido) → Banco de Sangre. Equipo nuevo.</p>', NULL, NULL, 'aprobado', '2026-08-08 17:18:18', '2026-08-08 11:37:40'),
(12, 20, 'traslado', 20, 21, 4, 1, NULL, '2026-08-03 13:00:00', '2026-08-08 11:23:32', NULL, 'Traslado silla de ruedas a Odontología', NULL, '<p>Acta Traslado <strong>BN-2026-000020</strong> (Silla Ruedas). Milicianos → Odontología.</p>', NULL, NULL, 'aprobado', '2026-08-08 17:18:18', '2026-08-08 11:23:32'),
(13, 25, 'desincorporacion', 25, NULL, 3, 1, NULL, '2026-08-05 13:00:00', '2026-08-08 11:18:45', NULL, 'Monitor con falla electrónica', NULL, '<p>Acta Desincorporación <strong>BN-2026-000025</strong> (Monitor). Falla placa electrónica.</p>', NULL, NULL, 'aprobado', '2026-08-08 17:18:18', '2026-08-08 11:18:45'),
(14, 1, 'traslado', 13, 13, 1, NULL, NULL, '2026-08-08 11:22:10', NULL, NULL, 'sdadadsw', NULL, '<div style=\"font-family: Arial, Helvetica, sans-serif; max-width: 850px; margin: auto; color: #1e293b; background: #fff;\">\r\n\r\n    <!-- ENCABEZADO CON ONDA AZUL/ROJA -->\r\n    <div style=\"position: relative; width: 100%; height: 120px; overflow: hidden;\">\r\n        <img src=\"/MCP/apps/SBN_MCP/public/img/encabezado_at.png\" style=\"width: 100%; height: 120px; object-fit: cover; display: block;\" alt=\"Encabezado\">\r\n    </div>\r\n\r\n    <!-- TÍTULO DEL ACTA -->\r\n    <div style=\"display: flex; justify-content: space-between; align-items: center; padding: 15px 25px; margin-bottom: 5px;\">\r\n        <div style=\"display: flex; align-items: center; gap: 10px;\">\r\n            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\r\n            <circle cx=\"12\" cy=\"12\" r=\"11\" fill=\"#1e40af\"></circle>\r\n            <path d=\"M11 7h2v4h4v2h-4v4h-2v-4H7v-2h4V7z\" fill=\"#fff\"></path>\r\n        </svg>\r\n            <h2 style=\"margin: 0; font-size: 14pt; color: #1e40af; font-weight: 800; letter-spacing: 0.02em;\">ACTA DE TRASLADO</h2>\r\n        </div>\r\n        <div style=\"border: 1px solid #93c5fd; border-radius: 20px; padding: 6px 18px; background: #eff6ff;\">\r\n            <span style=\"font-size: 9pt; font-weight: 700; color: #1e40af;\">Nº de Acta:</span>\r\n            <span style=\"font-size: 9pt; color: #334155; margin-left: 5px;\">20260808-0001</span>\r\n        </div>\r\n    </div>\r\n\r\n    <!-- SECCIÓN 1: INFORMACIÓN GENERAL -->\r\n    <div style=\"margin: 0 25px 15px 25px;\">\r\n        <div style=\"background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;\">\r\n            1. INFORMACIÓN GENERAL\r\n        </div>\r\n        <div style=\"border: 2px solid #3b82f6; border-radius: 0 10px 10px 10px; padding: 15px 20px; background: #f8fafc;\">\r\n            <table width=\"100%\" style=\"border-collapse: collapse;\">\r\n                <tbody><tr>\r\n                    <td style=\"padding: 6px 0; font-size: 10pt; width: 50%;\">\r\n                        <strong>Fecha:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px; min-width: 120px; display: inline-block;\">08/08/2026</span>\r\n                    </td>\r\n                    <td style=\"padding: 6px 0; font-size: 10pt; width: 50%;\">\r\n                        <strong>Hora:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px; min-width: 80px; display: inline-block;\">07:22</span>\r\n                    </td>\r\n                </tr>\r\n                <tr>\r\n                    <td colspan=\"2\" style=\"padding: 6px 0; font-size: 10pt;\">\r\n                        <strong>Área / Servicio:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px;\">Administracion — Principal</span>\r\n                    </td>\r\n                </tr>\r\n                <tr>\r\n                    <td colspan=\"2\" style=\"padding: 6px 0; font-size: 10pt;\">\r\n                        <strong>Responsable del Registro:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px;\">Maria Elena Rodriguez Perez</span>\r\n                    </td>\r\n                </tr>\r\n                \r\n            </tbody></table>\r\n        </div>\r\n    </div>\r\n\r\n    <!-- SECCIÓN 2: DATOS DEL BIEN -->\r\n    <div style=\"margin: 0 25px 15px 25px;\">\r\n        <div style=\"background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;\">\r\n            2. DATOS DEL BIEN TRASLADADO\r\n        </div>\r\n        <table width=\"100%\" style=\"border-collapse: collapse; border: 1px solid #bfdbfe; font-size: 10pt;\">\r\n            <tbody><tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; width: 35%; background: #eff6ff; border: 1px solid #bfdbfe;\">Código / Nº de Bien:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">00-1-ANE-9-0001</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Nombre / Descripción:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">Escritorio Ejecutivo</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Marca / Modelo:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">Ofimax / EX-400</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Serie:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">ESC-001</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Cantidad:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">1</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Estado:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">\r\n                    <label style=\"margin-right: 15px; cursor: default;\">\r\n                        <input type=\"radio\" name=\"estado_bien\" value=\"nuevo\" disabled=\"\"> Nuevo\r\n                    </label>\r\n                    <label style=\"margin-right: 15px; cursor: default;\">\r\n                        <input type=\"radio\" name=\"estado_bien\" value=\"usado\" checked=\"\" disabled=\"\"> Usado\r\n                    </label>\r\n                    <label style=\"cursor: default;\">\r\n                        <input type=\"radio\" name=\"estado_bien\" value=\"reacondicionado\" disabled=\"\"> Reacondicionado\r\n                    </label>\r\n                </td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Fecha de Incorporación:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">08/08/2026</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Proveedor (si aplica):</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">&nbsp;</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe; vertical-align: top;\">Observaciones:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe; min-height: 60px; vertical-align: top;\">sdadadsw</td>\r\n            </tr>\r\n            \r\n            <tr>\r\n                <td style=\"padding:8px 12px; font-weight:700; font-size:10pt; width:35%; background:#eff6ff;\">Área de Origen:</td>\r\n                <td style=\"padding:8px 12px; font-size:10pt; border:1px solid #bfdbfe;\">Administracion — Principal</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding:8px 12px; font-weight:700; font-size:10pt; background:#eff6ff;\">Área de Destino:</td>\r\n                <td style=\"padding:8px 12px; font-size:10pt; border:1px solid #bfdbfe;\">Administracion — Principal</td>\r\n            </tr>\r\n        </tbody></table>\r\n    </div>\r\n\r\n    <!-- SECCIÓN 3: RESPONSABLES -->\r\n    <div style=\"margin: 20px 25px 15px 25px;\">\r\n        <div style=\"background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;\">\r\n            3. RESPONSABLES\r\n        </div>\r\n        <table width=\"100%\" style=\"border-collapse: collapse; border: 1px solid #bfdbfe; font-size: 10pt; text-align: center;\">\r\n            <thead>\r\n                <tr style=\"background: #eff6ff;\">\r\n                    <th style=\"padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 33%;\">Responsable del Área</th>\r\n                    <th style=\"padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 34%;\">Encargado de Inventario</th>\r\n                    <th style=\"padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 33%;\">Autorizado por</th>\r\n                </tr>\r\n            </thead>\r\n            <tbody>\r\n                <tr>\r\n                    <td style=\"padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;\">\r\n                        <div style=\"font-size: 9pt; color: #64748b; margin-bottom: 5px;\">Firma: ______________________</div>\r\n                        <div style=\"font-size: 8pt; color: #94a3b8;\">Nombre: ____________________</div>\r\n                    </td>\r\n                    <td style=\"padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;\">\r\n                        <div style=\"font-size: 9pt; color: #64748b; margin-bottom: 5px;\">Firma: ______________________</div>\r\n                        <div style=\"font-size: 8pt; color: #94a3b8;\">Nombre: ____________________</div>\r\n                    </td>\r\n                    <td style=\"padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;\">\r\n                        <div style=\"font-size: 9pt; color: #64748b; margin-bottom: 5px;\">Firma: ______________________</div>\r\n                        <div style=\"font-size: 8pt; color: #94a3b8;\">Nombre: ____________________</div>\r\n                    </td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n\r\n    <!-- PIE DE PÁGINA -->\r\n    <div style=\"margin: 25px 25px 10px 25px; text-align: center; font-size: 8pt; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px;\">\r\n        Documento generado electrónicamente por el Sistema de Gestión de Bienes Nacionales (SBN-MCP) — Maternidad Concepción Palacios\r\n    </div>\r\n\r\n</div>', NULL, NULL, 'pendiente', '2026-08-08 11:22:10', '2026-08-08 11:22:10'),
(15, 1, 'traslado', 13, 9, 1, 1, NULL, '2026-08-09 17:24:43', '2026-08-09 17:24:43', NULL, 'asdwda', NULL, '<div style=\"font-family: Arial, Helvetica, sans-serif; max-width: 850px; margin: auto; color: #1e293b; background: #fff;\">\r\n\r\n    <!-- ENCABEZADO CON ONDA AZUL/ROJA -->\r\n    <div style=\"position: relative; width: 100%; height: 120px; overflow: hidden;\">\r\n        <img src=\"/MCP/apps/SBN_MCP/public/img/encabezado_at.png\" style=\"width: 100%; height: 120px; object-fit: cover; display: block;\" alt=\"Encabezado\">\r\n    </div>\r\n\r\n    <!-- TÍTULO DEL ACTA -->\r\n    <div style=\"display: flex; justify-content: space-between; align-items: center; padding: 15px 25px; margin-bottom: 5px;\">\r\n        <div style=\"display: flex; align-items: center; gap: 10px;\">\r\n            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\r\n            <circle cx=\"12\" cy=\"12\" r=\"11\" fill=\"#1e40af\"></circle>\r\n            <path d=\"M11 7h2v4h4v2h-4v4h-2v-4H7v-2h4V7z\" fill=\"#fff\"></path>\r\n        </svg>\r\n            <h2 style=\"margin: 0; font-size: 14pt; color: #1e40af; font-weight: 800; letter-spacing: 0.02em;\">ACTA DE TRASLADO</h2>\r\n        </div>\r\n        <div style=\"border: 1px solid #93c5fd; border-radius: 20px; padding: 6px 18px; background: #eff6ff;\">\r\n            <span style=\"font-size: 9pt; font-weight: 700; color: #1e40af;\">Nº de Acta:</span>\r\n            <span style=\"font-size: 9pt; color: #334155; margin-left: 5px;\">20260809-0001</span>\r\n        </div>\r\n    </div>\r\n\r\n    <!-- SECCIÓN 1: INFORMACIÓN GENERAL -->\r\n    <div style=\"margin: 0 25px 15px 25px;\">\r\n        <div style=\"background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;\">\r\n            1. INFORMACIÓN GENERAL\r\n        </div>\r\n        <div style=\"border: 2px solid #3b82f6; border-radius: 0 10px 10px 10px; padding: 15px 20px; background: #f8fafc;\">\r\n            <table width=\"100%\" style=\"border-collapse: collapse;\">\r\n                <tbody><tr>\r\n                    <td style=\"padding: 6px 0; font-size: 10pt; width: 50%;\">\r\n                        <strong>Fecha:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px; min-width: 120px; display: inline-block;\">09/08/2026</span>\r\n                    </td>\r\n                    <td style=\"padding: 6px 0; font-size: 10pt; width: 50%;\">\r\n                        <strong>Hora:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px; min-width: 80px; display: inline-block;\">13:24</span>\r\n                    </td>\r\n                </tr>\r\n                <tr>\r\n                    <td colspan=\"2\" style=\"padding: 6px 0; font-size: 10pt;\">\r\n                        <strong>Área / Servicio:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px;\">Anestesia — Principal</span>\r\n                    </td>\r\n                </tr>\r\n                <tr>\r\n                    <td colspan=\"2\" style=\"padding: 6px 0; font-size: 10pt;\">\r\n                        <strong>Responsable del Registro:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px;\">Maria Elena Rodriguez Perez</span>\r\n                    </td>\r\n                </tr>\r\n                \r\n            </tbody></table>\r\n        </div>\r\n    </div>\r\n\r\n    <!-- SECCIÓN 2: DATOS DEL BIEN -->\r\n    <div style=\"margin: 0 25px 15px 25px;\">\r\n        <div style=\"background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;\">\r\n            2. DATOS DEL BIEN TRASLADADO\r\n        </div>\r\n        <table width=\"100%\" style=\"border-collapse: collapse; border: 1px solid #bfdbfe; font-size: 10pt;\">\r\n            <tbody><tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; width: 35%; background: #eff6ff; border: 1px solid #bfdbfe;\">Código / Nº de Bien:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">00-1-ANE-9-0001</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Nombre / Descripción:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">Escritorio Ejecutivo</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Marca / Modelo:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">Ofimax / EX-400</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Serie:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">ESC-001</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Cantidad:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">1</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Estado:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">\r\n                    <label style=\"margin-right: 15px; cursor: default;\">\r\n                        <input type=\"radio\" name=\"estado_bien\" value=\"nuevo\" disabled=\"\"> Nuevo\r\n                    </label>\r\n                    <label style=\"margin-right: 15px; cursor: default;\">\r\n                        <input type=\"radio\" name=\"estado_bien\" value=\"usado\" checked=\"\" disabled=\"\"> Usado\r\n                    </label>\r\n                    <label style=\"cursor: default;\">\r\n                        <input type=\"radio\" name=\"estado_bien\" value=\"reacondicionado\" disabled=\"\"> Reacondicionado\r\n                    </label>\r\n                </td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Fecha de Incorporación:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">09/08/2026</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Proveedor (si aplica):</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">&nbsp;</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe; vertical-align: top;\">Observaciones:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe; min-height: 60px; vertical-align: top;\">asdwda</td>\r\n            </tr>\r\n            \r\n            <tr>\r\n                <td style=\"padding:8px 12px; font-weight:700; font-size:10pt; width:35%; background:#eff6ff;\">Área de Origen:</td>\r\n                <td style=\"padding:8px 12px; font-size:10pt; border:1px solid #bfdbfe;\">Administracion — Principal</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding:8px 12px; font-weight:700; font-size:10pt; background:#eff6ff;\">Área de Destino:</td>\r\n                <td style=\"padding:8px 12px; font-size:10pt; border:1px solid #bfdbfe;\">Anestesia — Principal</td>\r\n            </tr>\r\n        </tbody></table>\r\n    </div>\r\n\r\n    <!-- SECCIÓN 3: RESPONSABLES -->\r\n    <div style=\"margin: 20px 25px 15px 25px;\">\r\n        <div style=\"background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;\">\r\n            3. RESPONSABLES\r\n        </div>\r\n        <table width=\"100%\" style=\"border-collapse: collapse; border: 1px solid #bfdbfe; font-size: 10pt; text-align: center;\">\r\n            <thead>\r\n                <tr style=\"background: #eff6ff;\">\r\n                    <th style=\"padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 33%;\">Responsable del Área</th>\r\n                    <th style=\"padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 34%;\">Encargado de Inventario</th>\r\n                    <th style=\"padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 33%;\">Autorizado por</th>\r\n                </tr>\r\n            </thead>\r\n            <tbody>\r\n                <tr>\r\n                    <td style=\"padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;\">\r\n                        <div style=\"font-size: 9pt; color: #64748b; margin-bottom: 5px;\">Firma: ______________________</div>\r\n                        <div style=\"font-size: 8pt; color: #94a3b8;\">Nombre: ____________________</div>\r\n                    </td>\r\n                    <td style=\"padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;\">\r\n                        <div style=\"font-size: 9pt; color: #64748b; margin-bottom: 5px;\">Firma: ______________________</div>\r\n                        <div style=\"font-size: 8pt; color: #94a3b8;\">Nombre: ____________________</div>\r\n                    </td>\r\n                    <td style=\"padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;\">\r\n                        <div style=\"font-size: 9pt; color: #64748b; margin-bottom: 5px;\">Firma: ______________________</div>\r\n                        <div style=\"font-size: 8pt; color: #94a3b8;\">Nombre: ____________________</div>\r\n                    </td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n\r\n    <!-- PIE DE PÁGINA -->\r\n    <div style=\"margin: 25px 25px 10px 25px; text-align: center; font-size: 8pt; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px;\">\r\n        Documento generado electrónicamente por el Sistema de Gestión de Bienes Nacionales (SBN-MCP) — Maternidad Concepción Palacios\r\n    </div>\r\n\r\n</div>', NULL, NULL, 'aprobado', '2026-08-09 17:24:43', '2026-08-09 17:24:43'),
(16, 1, 'traslado', 9, 22, 1, 1, NULL, '2026-08-09 17:35:38', '2026-08-09 17:35:38', NULL, 'asdawsad', NULL, '<div style=\"font-family: Arial, Helvetica, sans-serif; max-width: 850px; margin: auto; color: #1e293b; background: #fff;\">\r\n\r\n    <!-- ENCABEZADO CON ONDA AZUL/ROJA -->\r\n    <div style=\"position: relative; width: 100%; height: 120px; overflow: hidden;\">\r\n        <img src=\"/MCP/apps/SBN_MCP/public/img/encabezado_at.png\" style=\"width: 100%; height: 120px; object-fit: cover; display: block;\" alt=\"Encabezado\">\r\n    </div>\r\n\r\n    <!-- TÍTULO DEL ACTA -->\r\n    <div style=\"display: flex; justify-content: space-between; align-items: center; padding: 15px 25px; margin-bottom: 5px;\">\r\n        <div style=\"display: flex; align-items: center; gap: 10px;\">\r\n            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\r\n            <circle cx=\"12\" cy=\"12\" r=\"11\" fill=\"#1e40af\"></circle>\r\n            <path d=\"M11 7h2v4h4v2h-4v4h-2v-4H7v-2h4V7z\" fill=\"#fff\"></path>\r\n        </svg>\r\n            <h2 style=\"margin: 0; font-size: 14pt; color: #1e40af; font-weight: 800; letter-spacing: 0.02em;\">ACTA DE TRASLADO</h2>\r\n        </div>\r\n        <div style=\"border: 1px solid #93c5fd; border-radius: 20px; padding: 6px 18px; background: #eff6ff;\">\r\n            <span style=\"font-size: 9pt; font-weight: 700; color: #1e40af;\">Nº de Acta:</span>\r\n            <span style=\"font-size: 9pt; color: #334155; margin-left: 5px;\">20260809-0001</span>\r\n        </div>\r\n    </div>\r\n\r\n    <!-- SECCIÓN 1: INFORMACIÓN GENERAL -->\r\n    <div style=\"margin: 0 25px 15px 25px;\">\r\n        <div style=\"background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;\">\r\n            1. INFORMACIÓN GENERAL\r\n        </div>\r\n        <div style=\"border: 2px solid #3b82f6; border-radius: 0 10px 10px 10px; padding: 15px 20px; background: #f8fafc;\">\r\n            <table width=\"100%\" style=\"border-collapse: collapse;\">\r\n                <tbody><tr>\r\n                    <td style=\"padding: 6px 0; font-size: 10pt; width: 50%;\">\r\n                        <strong>Fecha:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px; min-width: 120px; display: inline-block;\">09/08/2026</span>\r\n                    </td>\r\n                    <td style=\"padding: 6px 0; font-size: 10pt; width: 50%;\">\r\n                        <strong>Hora:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px; min-width: 80px; display: inline-block;\">13:35</span>\r\n                    </td>\r\n                </tr>\r\n                <tr>\r\n                    <td colspan=\"2\" style=\"padding: 6px 0; font-size: 10pt;\">\r\n                        <strong>Área / Servicio:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px;\">Prenatal — Anexo</span>\r\n                    </td>\r\n                </tr>\r\n                <tr>\r\n                    <td colspan=\"2\" style=\"padding: 6px 0; font-size: 10pt;\">\r\n                        <strong>Responsable del Registro:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px;\">Maria Elena Rodriguez Perez</span>\r\n                    </td>\r\n                </tr>\r\n                \r\n            </tbody></table>\r\n        </div>\r\n    </div>\r\n\r\n    <!-- SECCIÓN 2: DATOS DEL BIEN -->\r\n    <div style=\"margin: 0 25px 15px 25px;\">\r\n        <div style=\"background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;\">\r\n            2. DATOS DEL BIEN TRASLADADO\r\n        </div>\r\n        <table width=\"100%\" style=\"border-collapse: collapse; border: 1px solid #bfdbfe; font-size: 10pt;\">\r\n            <tbody><tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; width: 35%; background: #eff6ff; border: 1px solid #bfdbfe;\">Código / Nº de Bien:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">00-1-ANE-9-0001</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Nombre / Descripción:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">Escritorio Ejecutivo</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Marca / Modelo:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">Ofimax / EX-400</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Serie:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">ESC-001</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Cantidad:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">1</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Estado:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">\r\n                    <label style=\"margin-right: 15px; cursor: default;\">\r\n                        <input type=\"radio\" name=\"estado_bien\" value=\"nuevo\" disabled=\"\"> Nuevo\r\n                    </label>\r\n                    <label style=\"margin-right: 15px; cursor: default;\">\r\n                        <input type=\"radio\" name=\"estado_bien\" value=\"usado\" checked=\"\" disabled=\"\"> Usado\r\n                    </label>\r\n                    <label style=\"cursor: default;\">\r\n                        <input type=\"radio\" name=\"estado_bien\" value=\"reacondicionado\" disabled=\"\"> Reacondicionado\r\n                    </label>\r\n                </td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Fecha de Incorporación:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">09/08/2026</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Proveedor (si aplica):</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">&nbsp;</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe; vertical-align: top;\">Observaciones:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe; min-height: 60px; vertical-align: top;\">asdawsad</td>\r\n            </tr>\r\n            \r\n            <tr>\r\n                <td style=\"padding:8px 12px; font-weight:700; font-size:10pt; width:35%; background:#eff6ff;\">Área de Origen:</td>\r\n                <td style=\"padding:8px 12px; font-size:10pt; border:1px solid #bfdbfe;\">Anestesia — Principal</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding:8px 12px; font-weight:700; font-size:10pt; background:#eff6ff;\">Área de Destino:</td>\r\n                <td style=\"padding:8px 12px; font-size:10pt; border:1px solid #bfdbfe;\">Prenatal — Anexo</td>\r\n            </tr>\r\n        </tbody></table>\r\n    </div>\r\n\r\n    <!-- SECCIÓN 3: RESPONSABLES -->\r\n    <div style=\"margin: 20px 25px 15px 25px;\">\r\n        <div style=\"background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;\">\r\n            3. RESPONSABLES\r\n        </div>\r\n        <table width=\"100%\" style=\"border-collapse: collapse; border: 1px solid #bfdbfe; font-size: 10pt; text-align: center;\">\r\n            <thead>\r\n                <tr style=\"background: #eff6ff;\">\r\n                    <th style=\"padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 33%;\">Responsable del Área</th>\r\n                    <th style=\"padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 34%;\">Encargado de Inventario</th>\r\n                    <th style=\"padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 33%;\">Autorizado por</th>\r\n                </tr>\r\n            </thead>\r\n            <tbody>\r\n                <tr>\r\n                    <td style=\"padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;\">\r\n                        <div style=\"font-size: 9pt; color: #64748b; margin-bottom: 5px;\">Firma: ______________________</div>\r\n                        <div style=\"font-size: 8pt; color: #94a3b8;\">Nombre: ____________________</div>\r\n                    </td>\r\n                    <td style=\"padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;\">\r\n                        <div style=\"font-size: 9pt; color: #64748b; margin-bottom: 5px;\">Firma: ______________________</div>\r\n                        <div style=\"font-size: 8pt; color: #94a3b8;\">Nombre: ____________________</div>\r\n                    </td>\r\n                    <td style=\"padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;\">\r\n                        <div style=\"font-size: 9pt; color: #64748b; margin-bottom: 5px;\">Firma: ______________________</div>\r\n                        <div style=\"font-size: 8pt; color: #94a3b8;\">Nombre: ____________________</div>\r\n                    </td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n\r\n    <!-- PIE DE PÁGINA -->\r\n    <div style=\"margin: 25px 25px 10px 25px; text-align: center; font-size: 8pt; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px;\">\r\n        Documento generado electrónicamente por el Sistema de Gestión de Bienes Nacionales (SBN-MCP) — Maternidad Concepción Palacios\r\n    </div>\r\n\r\n</div>', NULL, NULL, 'aprobado', '2026-08-09 17:35:38', '2026-08-09 17:35:38'),
(17, 2, 'traslado', 13, 6, 1, 1, NULL, '2026-08-10 20:56:31', '2026-08-10 20:56:31', NULL, 'kasmoidjawdadiawsjoad', NULL, '<div style=\"font-family: Arial, Helvetica, sans-serif; max-width: 850px; margin: auto; color: #1e293b; background: #fff;\">\r\n\r\n    <!-- ENCABEZADO CON ONDA AZUL/ROJA -->\r\n    <div style=\"position: relative; width: 100%; height: 120px; overflow: hidden;\">\r\n        <img src=\"/MCP/apps/SBN_MCP/public/img/encabezado_at.png\" style=\"width: 100%; height: 120px; object-fit: cover; display: block;\" alt=\"Encabezado\">\r\n    </div>\r\n\r\n    <!-- TÍTULO DEL ACTA -->\r\n    <div style=\"display: flex; justify-content: space-between; align-items: center; padding: 15px 25px; margin-bottom: 5px;\">\r\n        <div style=\"display: flex; align-items: center; gap: 10px;\">\r\n            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\r\n            <circle cx=\"12\" cy=\"12\" r=\"11\" fill=\"#1e40af\"></circle>\r\n            <path d=\"M11 7h2v4h4v2h-4v4h-2v-4H7v-2h4V7z\" fill=\"#fff\"></path>\r\n        </svg>\r\n            <h2 style=\"margin: 0; font-size: 14pt; color: #1e40af; font-weight: 800; letter-spacing: 0.02em;\">ACTA DE TRASLADO</h2>\r\n        </div>\r\n        <div style=\"border: 1px solid #93c5fd; border-radius: 20px; padding: 6px 18px; background: #eff6ff;\">\r\n            <span style=\"font-size: 9pt; font-weight: 700; color: #1e40af;\">Nº de Acta:</span>\r\n            <span style=\"font-size: 9pt; color: #334155; margin-left: 5px;\">20260810-0002</span>\r\n        </div>\r\n    </div>\r\n\r\n    <!-- SECCIÓN 1: INFORMACIÓN GENERAL -->\r\n    <div style=\"margin: 0 25px 15px 25px;\">\r\n        <div style=\"background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;\">\r\n            1. INFORMACIÓN GENERAL\r\n        </div>\r\n        <div style=\"border: 2px solid #3b82f6; border-radius: 0 10px 10px 10px; padding: 15px 20px; background: #f8fafc;\">\r\n            <table width=\"100%\" style=\"border-collapse: collapse;\">\r\n                <tbody><tr>\r\n                    <td style=\"padding: 6px 0; font-size: 10pt; width: 50%;\">\r\n                        <strong>Fecha:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px; min-width: 120px; display: inline-block;\">10/08/2026</span>\r\n                    </td>\r\n                    <td style=\"padding: 6px 0; font-size: 10pt; width: 50%;\">\r\n                        <strong>Hora:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px; min-width: 80px; display: inline-block;\">16:56</span>\r\n                    </td>\r\n                </tr>\r\n                <tr>\r\n                    <td colspan=\"2\" style=\"padding: 6px 0; font-size: 10pt;\">\r\n                        <strong>Área / Servicio:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px;\">Sala de Parto — Principal</span>\r\n                    </td>\r\n                </tr>\r\n                <tr>\r\n                    <td colspan=\"2\" style=\"padding: 6px 0; font-size: 10pt;\">\r\n                        <strong>Responsable del Registro:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px;\">Maria Elena Rodriguez Perez</span>\r\n                    </td>\r\n                </tr>\r\n                \r\n            </tbody></table>\r\n        </div>\r\n    </div>\r\n\r\n    <!-- SECCIÓN 2: DATOS DEL BIEN -->\r\n    <div style=\"margin: 0 25px 15px 25px;\">\r\n        <div style=\"background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;\">\r\n            2. DATOS DEL BIEN TRASLADADO\r\n        </div>\r\n        <table width=\"100%\" style=\"border-collapse: collapse; border: 1px solid #bfdbfe; font-size: 10pt;\">\r\n            <tbody><tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; width: 35%; background: #eff6ff; border: 1px solid #bfdbfe;\">Código / Nº de Bien:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">00-2-ANE-9-0002</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Nombre / Descripción:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">Silla Ergonomica</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Marca / Modelo:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">Eurotech / ZUO</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Serie:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">SIL-002</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Cantidad:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">1</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Estado:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">\r\n                    <label style=\"margin-right: 15px; cursor: default;\">\r\n                        <input type=\"radio\" name=\"estado_bien\" value=\"nuevo\" disabled=\"\"> Nuevo\r\n                    </label>\r\n                    <label style=\"margin-right: 15px; cursor: default;\">\r\n                        <input type=\"radio\" name=\"estado_bien\" value=\"usado\" checked=\"\" disabled=\"\"> Usado\r\n                    </label>\r\n                    <label style=\"cursor: default;\">\r\n                        <input type=\"radio\" name=\"estado_bien\" value=\"reacondicionado\" disabled=\"\"> Reacondicionado\r\n                    </label>\r\n                </td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Fecha de Incorporación:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">10/08/2026</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Proveedor (si aplica):</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">&nbsp;</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe; vertical-align: top;\">Observaciones:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe; min-height: 60px; vertical-align: top;\">kasmoidjawdadiawsjoad</td>\r\n            </tr>\r\n            \r\n            <tr>\r\n                <td style=\"padding:8px 12px; font-weight:700; font-size:10pt; width:35%; background:#eff6ff;\">Área de Origen:</td>\r\n                <td style=\"padding:8px 12px; font-size:10pt; border:1px solid #bfdbfe;\">Administracion — Principal</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding:8px 12px; font-weight:700; font-size:10pt; background:#eff6ff;\">Área de Destino:</td>\r\n                <td style=\"padding:8px 12px; font-size:10pt; border:1px solid #bfdbfe;\">Sala de Parto — Principal</td>\r\n            </tr>\r\n        </tbody></table>\r\n    </div>\r\n\r\n    <!-- SECCIÓN 3: RESPONSABLES -->\r\n    <div style=\"margin: 20px 25px 15px 25px;\">\r\n        <div style=\"background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;\">\r\n            3. RESPONSABLES\r\n        </div>\r\n        <table width=\"100%\" style=\"border-collapse: collapse; border: 1px solid #bfdbfe; font-size: 10pt; text-align: center;\">\r\n            <thead>\r\n                <tr style=\"background: #eff6ff;\">\r\n                    <th style=\"padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 33%;\">Responsable del Área</th>\r\n                    <th style=\"padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 34%;\">Encargado de Inventario</th>\r\n                    <th style=\"padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 33%;\">Autorizado por</th>\r\n                </tr>\r\n            </thead>\r\n            <tbody>\r\n                <tr>\r\n                    <td style=\"padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;\">\r\n                        <div style=\"font-size: 9pt; color: #64748b; margin-bottom: 5px;\">Firma: ______________________</div>\r\n                        <div style=\"font-size: 8pt; color: #94a3b8;\">Nombre: ____________________</div>\r\n                    </td>\r\n                    <td style=\"padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;\">\r\n                        <div style=\"font-size: 9pt; color: #64748b; margin-bottom: 5px;\">Firma: ______________________</div>\r\n                        <div style=\"font-size: 8pt; color: #94a3b8;\">Nombre: ____________________</div>\r\n                    </td>\r\n                    <td style=\"padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;\">\r\n                        <div style=\"font-size: 9pt; color: #64748b; margin-bottom: 5px;\">Firma: ______________________</div>\r\n                        <div style=\"font-size: 8pt; color: #94a3b8;\">Nombre: ____________________</div>\r\n                    </td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n\r\n    <!-- PIE DE PÁGINA -->\r\n    <div style=\"margin: 25px 25px 10px 25px; text-align: center; font-size: 8pt; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px;\">\r\n        Documento generado electrónicamente por el Sistema de Gestión de Bienes Nacionales (SBN-MCP) — Maternidad Concepción Palacios\r\n    </div>\r\n\r\n</div>', NULL, NULL, 'aprobado', '2026-08-10 20:56:31', '2026-08-10 20:56:31'),
(18, 81, 'incorporacion', NULL, 3, 1, NULL, NULL, '2026-09-27 16:50:07', NULL, NULL, 'Incorporación inicial del bien', NULL, NULL, NULL, NULL, 'pendiente', '2026-09-27 16:50:07', '2026-09-27 16:50:07');
INSERT INTO `movimientos` (`id_movimiento`, `bien_id`, `tipo_movimiento`, `area_origen_id`, `area_destino_id`, `usuario_solicita_id`, `usuario_aprueba_id`, `usuario_recibe_id`, `fecha_solicitud`, `fecha_aprobacion`, `fecha_ejecucion`, `motivo`, `observaciones`, `contenido_acta`, `responsable_datos`, `documento_soporte`, `estado`, `created_at`, `updated_at`) VALUES
(19, 32, 'desincorporacion', 21, NULL, 1, 1, NULL, '2026-09-27 16:54:59', '2026-09-27 16:54:59', NULL, 'Se desincorpora por fallos de uso', NULL, '<div style=\"font-family: Arial, Helvetica, sans-serif; max-width: 850px; margin: auto; color: #1e293b; background: #fff;\">\r\n\r\n    <!-- ENCABEZADO CON ONDA AZUL/ROJA -->\r\n    <div style=\"position: relative; width: 100%; height: 120px; overflow: hidden;\">\r\n        <img src=\"/MCP/apps/SBN_MCP/public/img/encabezado_at.png\" style=\"width: 100%; height: 120px; object-fit: cover; display: block;\" alt=\"Encabezado\">\r\n    </div>\r\n\r\n    <!-- TÍTULO DEL ACTA -->\r\n    <div style=\"display: flex; justify-content: space-between; align-items: center; padding: 15px 25px; margin-bottom: 5px;\">\r\n        <div style=\"display: flex; align-items: center; gap: 10px;\">\r\n            <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\r\n            <circle cx=\"12\" cy=\"12\" r=\"11\" fill=\"#1e40af\"></circle>\r\n            <path d=\"M11 7h2v4h4v2h-4v4h-2v-4H7v-2h4V7z\" fill=\"#fff\"></path>\r\n        </svg>\r\n            <h2 style=\"margin: 0; font-size: 14pt; color: #1e40af; font-weight: 800; letter-spacing: 0.02em;\">ACTA DE DESINCORPORACIÓN</h2>\r\n        </div>\r\n        <div style=\"border: 1px solid #93c5fd; border-radius: 20px; padding: 6px 18px; background: #eff6ff;\">\r\n            <span style=\"font-size: 9pt; font-weight: 700; color: #1e40af;\">Nº de Acta:</span>\r\n            <span style=\"font-size: 9pt; color: #334155; margin-left: 5px;\">20260927-0032</span>\r\n        </div>\r\n    </div>\r\n\r\n    <!-- SECCIÓN 1: INFORMACIÓN GENERAL -->\r\n    <div style=\"margin: 0 25px 15px 25px;\">\r\n        <div style=\"background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;\">\r\n            1. INFORMACIÓN GENERAL\r\n        </div>\r\n        <div style=\"border: 2px solid #3b82f6; border-radius: 0 10px 10px 10px; padding: 15px 20px; background: #f8fafc;\">\r\n            <table width=\"100%\" style=\"border-collapse: collapse;\">\r\n                <tbody><tr>\r\n                    <td style=\"padding: 6px 0; font-size: 10pt; width: 50%;\">\r\n                        <strong>Fecha:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px; min-width: 120px; display: inline-block;\">27/09/2026</span>\r\n                    </td>\r\n                    <td style=\"padding: 6px 0; font-size: 10pt; width: 50%;\">\r\n                        <strong>Hora:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px; min-width: 80px; display: inline-block;\">12:54</span>\r\n                    </td>\r\n                </tr>\r\n                <tr>\r\n                    <td colspan=\"2\" style=\"padding: 6px 0; font-size: 10pt;\">\r\n                        <strong>Área / Servicio:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px;\">Odontologia — Anexo</span>\r\n                    </td>\r\n                </tr>\r\n                <tr>\r\n                    <td colspan=\"2\" style=\"padding: 6px 0; font-size: 10pt;\">\r\n                        <strong>Responsable del Registro:</strong> <span style=\"border-bottom: 1px solid #94a3b8; padding-bottom: 1px;\">Maria Elena Rodriguez Perez</span>\r\n                    </td>\r\n                </tr>\r\n                \r\n            </tbody></table>\r\n        </div>\r\n    </div>\r\n\r\n    <!-- SECCIÓN 2: DATOS DEL BIEN -->\r\n    <div style=\"margin: 0 25px 15px 25px;\">\r\n        <div style=\"background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;\">\r\n            2. DATOS DEL BIEN DESINCORPORADO\r\n        </div>\r\n        <table width=\"100%\" style=\"border-collapse: collapse; border: 1px solid #bfdbfe; font-size: 10pt;\">\r\n            <tbody><tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; width: 35%; background: #eff6ff; border: 1px solid #bfdbfe;\">Código / Nº de Bien:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">00-1-ANE-9-0002</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Nombre / Descripción:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">Rayos X Dental</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Marca / Modelo:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">Dürr / Dentalix</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Serie:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">RXD-032</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Cantidad:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">1</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Estado:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">\r\n                    <label style=\"margin-right: 15px; cursor: default;\">\r\n                        <input type=\"radio\" name=\"estado_bien\" value=\"nuevo\" disabled=\"\"> Nuevo\r\n                    </label>\r\n                    <label style=\"margin-right: 15px; cursor: default;\">\r\n                        <input type=\"radio\" name=\"estado_bien\" value=\"usado\" checked=\"\" disabled=\"\"> Usado\r\n                    </label>\r\n                    <label style=\"cursor: default;\">\r\n                        <input type=\"radio\" name=\"estado_bien\" value=\"reacondicionado\" disabled=\"\"> Reacondicionado\r\n                    </label>\r\n                </td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Fecha de Incorporación:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">27/09/2026</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;\">Proveedor (si aplica):</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe;\">&nbsp;</td>\r\n            </tr>\r\n            <tr>\r\n                <td style=\"padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe; vertical-align: top;\">Observaciones:</td>\r\n                <td style=\"padding: 8px 12px; border: 1px solid #bfdbfe; min-height: 60px; vertical-align: top;\">Se desincorpora por fallos de uso</td>\r\n            </tr>\r\n            \r\n        </tbody></table>\r\n    </div>\r\n\r\n    <!-- SECCIÓN 3: RESPONSABLES -->\r\n    <div style=\"margin: 20px 25px 15px 25px;\">\r\n        <div style=\"background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;\">\r\n            3. RESPONSABLES\r\n        </div>\r\n        <table width=\"100%\" style=\"border-collapse: collapse; border: 1px solid #bfdbfe; font-size: 10pt; text-align: center;\">\r\n            <thead>\r\n                <tr style=\"background: #eff6ff;\">\r\n                    <th style=\"padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 33%;\">Responsable del Área</th>\r\n                    <th style=\"padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 34%;\">Encargado de Inventario</th>\r\n                    <th style=\"padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 33%;\">Autorizado por</th>\r\n                </tr>\r\n            </thead>\r\n            <tbody>\r\n                <tr>\r\n                    <td style=\"padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;\">\r\n                        <div style=\"font-size: 9pt; color: #64748b; margin-bottom: 5px;\">Firma: ______________________</div>\r\n                        <div style=\"font-size: 8pt; color: #94a3b8;\">Nombre: ____________________</div>\r\n                    </td>\r\n                    <td style=\"padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;\">\r\n                        <div style=\"font-size: 9pt; color: #64748b; margin-bottom: 5px;\">Firma: ______________________</div>\r\n                        <div style=\"font-size: 8pt; color: #94a3b8;\">Nombre: ____________________</div>\r\n                    </td>\r\n                    <td style=\"padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;\">\r\n                        <div style=\"font-size: 9pt; color: #64748b; margin-bottom: 5px;\">Firma: ______________________</div>\r\n                        <div style=\"font-size: 8pt; color: #94a3b8;\">Nombre: ____________________</div>\r\n                    </td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n\r\n    <!-- PIE DE PÁGINA -->\r\n    <div style=\"margin: 25px 25px 10px 25px; text-align: center; font-size: 8pt; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px;\">\r\n        Documento generado electrónicamente por el Sistema de Gestión de Bienes Nacionales (SBN-MCP) — Maternidad Concepción Palacios\r\n    </div>\r\n\r\n</div>', NULL, NULL, 'aprobado', '2026-09-27 16:54:59', '2026-09-27 16:54:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificacion` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('info','success','warning','error') NOT NULL DEFAULT 'info',
  `titulo` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `link` varchar(500) DEFAULT NULL,
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `read_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id_notificacion`, `usuario_id`, `tipo`, `titulo`, `mensaje`, `link`, `leida`, `created_at`, `read_at`) VALUES
(1, 1, 'info', 'Bien incorporado', 'PC incorporada a Gerencia BN', NULL, 1, '2026-08-08 07:18:18', '2026-08-09 11:57:50'),
(2, 1, 'success', 'Traslado aprobado', 'Camilla a Quirófano aprobada', NULL, 1, '2026-08-08 07:18:18', NULL),
(3, 2, 'warning', 'Desincorp. pendiente', 'Pendiente impresora BN-2023', NULL, 0, '2026-08-08 07:18:18', NULL),
(4, 3, 'info', 'Mantenimiento prog.', 'Ecógrafo mañana', NULL, 0, '2026-08-08 07:18:18', NULL),
(5, 1, 'error', 'Bien en chatarra', 'Cama BN-2021 clasificada', NULL, 1, '2026-08-08 07:18:18', '2026-08-09 11:57:50'),
(6, 2, 'warning', 'Nuevo Bien Registrado — Asignar Nro. de Bien', 'admin registró el bien \"Cama\" (Código: 06-2-PRI--1-0001). Debe asignarle un Número de Bien.', '/bienes/81', 0, '2026-09-27 12:50:07', NULL),
(7, 1, 'warning', 'Nuevo Bien Registrado — Asignar Nro. de Bien', 'admin registró el bien \"Cama\" (Código: 06-2-PRI--1-0001). Debe asignarle un Número de Bien.', '/bienes/81', 1, '2026-09-27 12:50:07', '2026-09-27 13:00:44'),
(8, 5, 'warning', 'Nuevo Bien Registrado — Asignar Nro. de Bien', 'admin registró el bien \"Cama\" (Código: 06-2-PRI--1-0001). Debe asignarle un Número de Bien.', '/bienes/81', 0, '2026-09-27 12:50:07', NULL),
(9, 2, 'warning', 'Nuevo Bien — Asignar Nro. de Bien', 'admin registró \"Cama\" (Código: 06-2-PRI--1-0001). Asigne Número de Bien.', '/bienes/81', 0, '2026-09-27 12:50:07', NULL),
(10, 1, 'warning', 'Nuevo Bien — Asignar Nro. de Bien', 'admin registró \"Cama\" (Código: 06-2-PRI--1-0001). Asigne Número de Bien.', '/bienes/81', 1, '2026-09-27 12:50:07', '2026-09-27 13:00:44'),
(11, 5, 'warning', 'Nuevo Bien — Asignar Nro. de Bien', 'admin registró \"Cama\" (Código: 06-2-PRI--1-0001). Asigne Número de Bien.', '/bienes/81', 0, '2026-09-27 12:50:07', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id_reset` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `codigo` varchar(6) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `intentos_dia` int(11) NOT NULL DEFAULT 1,
  `fecha_intento` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `password_resets`
--

INSERT INTO `password_resets` (`id_reset`, `usuario_id`, `codigo`, `email`, `created_at`, `expires_at`, `used_at`, `ip_address`, `user_agent`, `intentos_dia`, `fecha_intento`) VALUES
(1, 6, '6e2942', 'danielsotomayor475@gmail.com', '2026-08-08 08:08:31', '2026-08-08 08:38:31', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-08'),
(2, 6, '64b47d', 'dtsr32361670@gmail.com', '2026-08-08 08:27:31', '2026-08-08 08:57:31', '2026-08-08 08:28:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 1, '2026-08-08'),
(3, 6, '0012ca', 'dtsr32361670@gmail.com', '2026-08-09 12:57:14', '2026-08-09 13:27:14', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-09'),
(4, 6, '25d45c', 'dtsr32361670@gmail.com', '2026-08-09 13:13:33', '2026-08-09 13:43:33', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-09'),
(5, 6, '2f0714', 'dtsr32361670@gmail.com', '2026-08-09 13:15:13', '2026-08-09 13:45:13', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 1, '2026-08-09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `permisos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`permisos`)),
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`, `descripcion`, `permisos`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'administrador', 'Administrador del sistema — acceso total y gestión de usuarios', '[\"usuarios_crear\",\"usuarios_editar\",\"usuarios_eliminar\",\"usuarios_ver\",\"bienes_crear\",\"bienes_editar\",\"bienes_eliminar\",\"bienes_ver\",\"movimientos_crear\",\"movimientos_ver\",\"movimientos_aprobar\",\"actas_generar\",\"reportes_generar\",\"configuracion\",\"auditoria_ver\"]', 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(2, 'gerencia_bn', 'Gerente de Bienes Nacionales — asigna Nro. de Bien, registra, incorpora y genera actas', '[\"bienes_crear\",\"bienes_editar\",\"bienes_ver\",\"movimientos_crear\",\"movimientos_ver\",\"movimientos_aprobar\",\"actas_generar\",\"reportes_generar\",\"auditoria_ver\"]', 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(3, 'controlador_inventario', 'Controlador de Inventario — lleva el control global y puede corregir el inventario', '[\"bienes_editar\",\"bienes_ver\",\"movimientos_ver\",\"reportes_generar\",\"auditoria_ver\"]', 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(4, 'registrador', 'Registrador — solo carga información, no puede editar', '[\"bienes_crear\",\"bienes_ver\",\"movimientos_ver\"]', 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(5, 'validador_inventario', 'Validador de Inventario - Realiza verificaciones semestrales', '{}', 1, '2026-08-08 11:40:28', '2026-08-08 11:40:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_bien`
--

CREATE TABLE `tipos_bien` (
  `id_tipo` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `vida_util_anos` tinyint(4) NOT NULL DEFAULT 10,
  `categoria` varchar(50) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipos_bien`
--

INSERT INTO `tipos_bien` (`id_tipo`, `codigo`, `nombre`, `descripcion`, `vida_util_anos`, `categoria`, `activo`, `created_at`) VALUES
(1, '01', 'Equipos de Oficina', 'Mobiliario y equipos de oficina en general', 10, 'Oficina', 1, '2026-05-26 17:40:34'),
(2, '02', 'Del Alojamiento', 'Bienes del alojamiento (unidad)', 10, 'Alojamiento', 1, '2026-05-26 17:40:34'),
(3, '03', 'Material de Construcción y Taller', 'Material de construcción, equipos de taller, etc.', 15, 'Construcción', 1, '2026-05-26 17:40:34'),
(4, '03-1', 'Reguladores de Voltaje', 'Todos los reguladores de voltaje', 5, 'Construcción', 1, '2026-05-26 17:40:34'),
(5, '04', 'Vehículos', 'Vehículos de la institución', 15, 'Vehículos', 1, '2026-05-26 17:40:34'),
(6, '05', 'Telecomunicaciones', 'Teléfonos, radios y equipos de comunicación', 8, 'Telecomunicaciones', 1, '2026-05-26 17:40:34'),
(7, '06', 'Bienes Hospitalarios', 'Bienes hospitalarios en general', 10, 'Hospitalario', 1, '2026-05-26 17:40:34'),
(8, '06-1', 'Equipos Quirúrgicos y Hospitalarios', 'Equipos quirúrgicos y hospitalarios específicos', 10, 'Hospitalario', 1, '2026-05-26 17:40:34'),
(9, '06-2', 'Equipo Odontológico', 'Equipo odontológico únicamente', 10, 'Hospitalario', 1, '2026-05-26 17:40:34'),
(10, '07', 'Equipos Científicos, Enseñanza y Religiosos', 'Equipos científicos, de enseñanza y religiosos', 10, 'Científico', 1, '2026-05-26 17:40:34'),
(11, '07-2', 'Equipos de Enseñanza', 'Pizarras, pupitres y equipos de enseñanza', 10, 'Científico', 1, '2026-05-26 17:40:34'),
(12, '07-3', 'Capilla', 'Todo lo que abarca la Capilla', 20, 'Religioso', 1, '2026-05-26 17:40:34'),
(13, '08', 'Cultural y Artístico', 'Cuadros y bienes culturales o artísticos', 20, 'Cultural', 1, '2026-05-26 17:40:34'),
(14, '13', 'Equipos de Procesamiento de Datos', 'Todo el equipamiento tecnológico e informático', 5, 'Tecnología', 1, '2026-05-26 17:40:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `cedula` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `primer_nombre` varchar(100) NOT NULL,
  `segundo_nombre` varchar(100) NOT NULL DEFAULT '',
  `primer_apellido` varchar(100) NOT NULL,
  `segundo_apellido` varchar(100) NOT NULL DEFAULT '',
  `nombre_completo` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `intentos_fallidos` int(11) NOT NULL DEFAULT 0,
  `bloqueado_hasta` timestamp NULL DEFAULT NULL,
  `primer_login` tinyint(1) NOT NULL DEFAULT 1,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_rol`, `cedula`, `username`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `nombre_completo`, `email`, `password_hash`, `telefono`, `cargo`, `ultimo_acceso`, `intentos_fallidos`, `bloqueado_hasta`, `primer_login`, `activo`, `created_at`, `updated_at`) VALUES
(1, 1, '20123456', 'admin', 'Maria', 'Elena', 'Rodriguez', 'Perez', 'Maria Elena Rodriguez Perez', 'admin@mcp.gob.ve', '$2y$12$kQAPnfOj4gLf.hewnc4RruxFyJv3Q/eHuNtcUrZPcAqqzqAW1i7Ra', '0414-1234567', 'Gerente de Bienes Nacionales', '2026-09-27 17:07:59', 0, NULL, 0, 1, '2026-08-08 17:18:18', '2026-09-27 17:07:59'),
(2, 2, '18765432', 'gerencia', 'Carlos', 'Alberto', 'Martinez', 'Lopez', 'Carlos Alberto Martinez Lopez', 'gerencia@mcp.gob.ve', '$2y$12$LJ3m4kVz5K8Qz9v2X6y3OeYqHgFpDnMbWcRtYuIoPlKjHgFdSaS4m', '0412-9876543', 'Subgerente Administrativo', NULL, 0, NULL, 0, 1, '2026-08-08 17:18:18', '2026-08-08 12:03:53'),
(3, 3, '21345678', 'inventario', 'Ana', 'Luz', 'Garcia', 'Torres', 'Ana Luz Garcia Torres', 'inventario@mcp.gob.ve', '$2y$12$LJ3m4kVz5K8Qz9v2X6y3OeYqHgFpDnMbWcRtYuIoPlKjHgFdSaS4m', '0416-5551234', 'Controlador de Inventario', NULL, 0, NULL, 0, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(4, 4, '19876543', 'registrador', 'Pedro', 'Jose', 'Fernandez', 'Ramos', 'Pedro Jose Fernandez Ramos', 'registrador@mcp.gob.ve', '$2y$12$LJ3m4kVz5K8Qz9v2X6y3OeYqHgFpDnMbWcRtYuIoPlKjHgFdSaS4m', '0424-7778899', 'Registrador de Bienes', NULL, 0, NULL, 0, 1, '2026-08-08 17:18:18', '2026-08-08 17:18:18'),
(5, 1, '17654321', 'admin2', 'Luis', 'Fernando', 'Sanchez', 'Mendez', 'Luis Fernando Sanchez Mendez', 'admin2@mcp.gob.ve', '$2y$12$L7ywTNaj/3JHryQz6PszXuwkKCXziJPm/W/nLzgXKl1oKXmOZMeJ2', '0414-3334455', 'Jefe de Area BN', '2026-08-08 12:12:40', 0, NULL, 0, 1, '2026-08-08 17:18:18', '2026-08-08 12:13:16'),
(6, 5, '32361670', 'dsotomayor', 'Daniel', 'Tomas', 'Sotomayor', 'Romero', 'Daniel Tomas Sotomayor Romero', 'dtsr32361670@gmail.com', '$2y$12$CTtdZ8zN9HWr8Puqhx6kKO1e.uQf//MkeZpEeh8.fF13.O/9yrzQS', NULL, 'Jefe de Area BN', '2026-08-08 12:33:49', 2, NULL, 1, 1, '2026-08-08 12:07:15', '2026-08-18 16:29:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `verificaciones`
--

CREATE TABLE `verificaciones` (
  `id_verificacion` int(11) NOT NULL,
  `bien_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('semestral','anual','extraordinaria') NOT NULL DEFAULT 'semestral',
  `observaciones` text DEFAULT NULL,
  `fecha_verificacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `verificaciones`
--

INSERT INTO `verificaciones` (`id_verificacion`, `bien_id`, `usuario_id`, `tipo`, `observaciones`, `fecha_verificacion`) VALUES
(1, 3, 3, 'semestral', 'PC OK', '2024-06-30 04:00:00'),
(2, 11, 3, 'semestral', 'Camilla barandas OK', '2024-06-30 04:00:00'),
(3, 12, 3, 'semestral', 'Monitor calibrado', '2024-12-30 04:00:00'),
(4, 14, 3, 'semestral', 'Anestesia OK post-rep', '2024-12-30 04:00:00'),
(5, 21, 3, 'semestral', 'Servidor actualizado', '2024-12-30 04:00:00'),
(6, 36, 3, 'semestral', 'Ecografo calibrado', '2024-12-30 04:00:00'),
(7, 7, 3, 'semestral', 'AC filtros OK', '2024-12-30 04:00:00'),
(8, 49, 3, 'semestral', 'Mamografo OK post-rep', '2025-01-30 04:00:00'),
(9, 1, 1, 'semestral', '', '2026-08-09 17:59:40'),
(10, 32, 1, 'semestral', '', '2026-09-27 16:53:20'),
(11, 6, 1, 'semestral', '', '2026-09-27 17:00:31'),
(12, 6, 1, 'semestral', '', '2026-09-27 17:00:39');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_intentos_recuperacion`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_intentos_recuperacion` (
`usuario_id` int(11)
,`fecha_intento` date
,`total_intentos` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `v_intentos_recuperacion`
--
DROP TABLE IF EXISTS `v_intentos_recuperacion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_intentos_recuperacion`  AS SELECT `password_resets`.`usuario_id` AS `usuario_id`, `password_resets`.`fecha_intento` AS `fecha_intento`, count(0) AS `total_intentos` FROM `password_resets` WHERE `password_resets`.`fecha_intento` = curdate() GROUP BY `password_resets`.`usuario_id`, `password_resets`.`fecha_intento` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id_area`),
  ADD KEY `responsable_id` (`responsable_id`),
  ADD KEY `area_padre_id` (`area_padre_id`),
  ADD KEY `idx_areas_edificio` (`edificio`);

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id_auditoria`),
  ADD KEY `idx_auditoria_tabla` (`tabla_afectada`),
  ADD KEY `idx_auditoria_usuario` (`usuario_id`),
  ADD KEY `idx_auditoria_fecha` (`fecha_operacion`);

--
-- Indices de la tabla `backups_log`
--
ALTER TABLE `backups_log`
  ADD PRIMARY KEY (`id_backup`),
  ADD KEY `idx_backups_log_created` (`created_at`),
  ADD KEY `idx_backups_log_status` (`status`);

--
-- Indices de la tabla `bienes`
--
ALTER TABLE `bienes`
  ADD PRIMARY KEY (`id_bien`),
  ADD UNIQUE KEY `codigo_sudebip` (`codigo_sudebip`),
  ADD KEY `responsable_id` (`responsable_id`),
  ADD KEY `idx_bienes_codigo_sudebip` (`codigo_sudebip`),
  ADD KEY `idx_bienes_codigo_interno` (`codigo_interno`),
  ADD KEY `idx_bienes_nro_ministerio` (`nro_bien_ministerio`),
  ADD KEY `idx_bienes_estado` (`id_estado`),
  ADD KEY `idx_bienes_area` (`id_area`),
  ADD KEY `idx_bienes_tipo` (`id_tipo`),
  ADD KEY `idx_bienes_nombre` (`nombre`),
  ADD KEY `idx_bienes_updated_at` (`updated_at`),
  ADD KEY `idx_bienes_responsable_cedula` (`responsable_cedula`),
  ADD KEY `idx_bienes_responsable_foto` (`responsable_foto_path`),
  ADD KEY `idx_bienes_estado_area` (`id_estado`,`id_area`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id_configuracion`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD PRIMARY KEY (`id_mantenimiento`),
  ADD KEY `realizado_por_id` (`realizado_por_id`),
  ADD KEY `idx_mantenimientos_bien` (`bien_id`),
  ADD KEY `idx_mantenimientos_fecha_ejecutada` (`fecha_ejecutada`),
  ADD KEY `idx_mantenimientos_fecha_programada` (`fecha_programada`),
  ADD KEY `idx_mantenimientos_tipo` (`tipo_servicio`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `idx_movimientos_usuario_recibe` (`usuario_recibe_id`),
  ADD KEY `area_origen_id` (`area_origen_id`),
  ADD KEY `area_destino_id` (`area_destino_id`),
  ADD KEY `usuario_solicita_id` (`usuario_solicita_id`),
  ADD KEY `usuario_aprueba_id` (`usuario_aprueba_id`),
  ADD KEY `idx_movimientos_bien` (`bien_id`),
  ADD KEY `idx_movimientos_tipo` (`tipo_movimiento`),
  ADD KEY `idx_movimientos_estado` (`estado`),
  ADD KEY `idx_movimientos_fecha` (`fecha_solicitud`),
  ADD KEY `idx_movimientos_estado_fecha` (`estado`,`fecha_solicitud`),
  ADD KEY `idx_movimientos_tipo_fecha` (`tipo_movimiento`,`fecha_solicitud`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `idx_notificaciones_usuario` (`usuario_id`),
  ADD KEY `idx_notificaciones_leida` (`leida`),
  ADD KEY `idx_notificaciones_created` (`created_at`),
  ADD KEY `idx_notificaciones_usuario_leida` (`usuario_id`,`leida`),
  ADD KEY `idx_notificaciones_sse` (`usuario_id`,`leida`,`id_notificacion`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id_reset`),
  ADD KEY `idx_password_resets_codigo` (`codigo`),
  ADD KEY `idx_password_resets_expires` (`expires_at`),
  ADD KEY `idx_password_resets_usuario_fecha` (`usuario_id`,`fecha_intento`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `tipos_bien`
--
ALTER TABLE `tipos_bien`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_usuarios_username` (`username`),
  ADD KEY `idx_usuarios_cedula` (`cedula`),
  ADD KEY `idx_usuarios_email` (`email`),
  ADD KEY `idx_usuarios_rol` (`id_rol`);

--
-- Indices de la tabla `verificaciones`
--
ALTER TABLE `verificaciones`
  ADD PRIMARY KEY (`id_verificacion`),
  ADD KEY `idx_verificaciones_bien` (`bien_id`),
  ADD KEY `idx_verificaciones_usuario` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=274;

--
-- AUTO_INCREMENT de la tabla `backups_log`
--
ALTER TABLE `backups_log`
  MODIFY `id_backup` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bienes`
--
ALTER TABLE `bienes`
  MODIFY `id_bien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id_configuracion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  MODIFY `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id_reset` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipos_bien`
--
ALTER TABLE `tipos_bien`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `verificaciones`
--
ALTER TABLE `verificaciones`
  MODIFY `id_verificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `areas`
--
ALTER TABLE `areas`
  ADD CONSTRAINT `areas_ibfk_1` FOREIGN KEY (`responsable_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL,
  ADD CONSTRAINT `areas_ibfk_2` FOREIGN KEY (`area_padre_id`) REFERENCES `areas` (`id_area`) ON DELETE SET NULL;

--
-- Filtros para la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `bienes`
--
ALTER TABLE `bienes`
  ADD CONSTRAINT `bienes_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`),
  ADD CONSTRAINT `bienes_ibfk_2` FOREIGN KEY (`id_tipo`) REFERENCES `tipos_bien` (`id_tipo`) ON DELETE SET NULL,
  ADD CONSTRAINT `bienes_ibfk_3` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id_area`) ON DELETE SET NULL,
  ADD CONSTRAINT `bienes_ibfk_4` FOREIGN KEY (`responsable_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD CONSTRAINT `mantenimientos_ibfk_1` FOREIGN KEY (`bien_id`) REFERENCES `bienes` (`id_bien`) ON DELETE CASCADE,
  ADD CONSTRAINT `mantenimientos_ibfk_2` FOREIGN KEY (`realizado_por_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`bien_id`) REFERENCES `bienes` (`id_bien`) ON DELETE CASCADE,
  ADD CONSTRAINT `movimientos_ibfk_2` FOREIGN KEY (`area_origen_id`) REFERENCES `areas` (`id_area`) ON DELETE SET NULL,
  ADD CONSTRAINT `movimientos_ibfk_3` FOREIGN KEY (`area_destino_id`) REFERENCES `areas` (`id_area`) ON DELETE SET NULL,
  ADD CONSTRAINT `movimientos_ibfk_4` FOREIGN KEY (`usuario_solicita_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL,
  ADD CONSTRAINT `movimientos_ibfk_5` FOREIGN KEY (`usuario_aprueba_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `verificaciones`
--
ALTER TABLE `verificaciones`
  ADD CONSTRAINT `verificaciones_ibfk_1` FOREIGN KEY (`bien_id`) REFERENCES `bienes` (`id_bien`) ON DELETE CASCADE,
  ADD CONSTRAINT `verificaciones_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
