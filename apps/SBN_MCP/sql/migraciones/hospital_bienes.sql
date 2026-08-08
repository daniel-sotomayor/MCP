-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-06-2026 a las 18:57:39
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
(1, 'Mantenimiento Técnico', 'Taller de mantenimiento técnico', 'Principal', -1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(2, 'Lavandería', 'Servicio de lavandería', 'Principal', -1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(3, 'Sindicato', 'Sede del sindicato', 'Principal', -1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(4, 'Electromedicina', 'Departamento de electromedicina', 'Principal', -1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(5, 'Servicios Generales', 'Servicios generales del hospital', 'Principal', -1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(6, 'Telecomunicaciones', 'Centro de telecomunicaciones', 'Principal', -1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(7, 'Morgue', 'Servicio de morgue', 'Principal', -1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(8, 'Casa de Abrigo', 'Casa de abrigo institucional', 'Principal', -1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(9, 'Mantenimiento de Limpieza', 'Servicio de limpieza y mantenimiento', 'Principal', -1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(10, 'Centro de Capacitación', 'Centro de capacitación del personal', 'Principal', -1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(11, 'Depósito BN 1', 'Depósito de Bienes Nacionales N°1', 'Principal', -1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(12, 'Depósito BN 2', 'Depósito de Bienes Nacionales N°2', 'Principal', -1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(13, 'Depósito BN 3', 'Depósito de Bienes Nacionales N°3', 'Principal', -1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(14, 'Depósito BN 4', 'Depósito de Bienes Nacionales N°4', 'Principal', -1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(15, 'Sala de Parto', 'Sala de partos', 'Principal', 1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(16, 'Quirófano', 'Salas de operaciones', 'Principal', 1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(17, 'Banco de Sangre', 'Banco de sangre', 'Principal', 1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(18, 'Anestesia', 'Departamento de anestesia', 'Principal', 1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(19, 'Central de Suministros', 'Central de suministros médicos', 'Principal', 1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(20, 'Terapia Adulto', 'Unidad de terapia adulto', 'Principal', 1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(21, 'Nutrición', 'Departamento de nutrición', 'Principal', 1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(22, 'Gerencia de Bienes Nacionales', 'Departamento de control de bienes', 'Principal', 1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(23, 'Administración', 'Área administrativa central', 'Principal', 1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(24, 'Materno-Fetal', 'Unidad materno-fetal', 'Principal', 2, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(25, 'Consultas de Eco', 'Consultas de ecosonografía', 'Principal', 2, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(26, 'Hospitalización P2', 'Hospitalización piso 2', 'Principal', 2, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(27, 'Hospitalización P3', 'Hospitalización piso 3', 'Principal', 3, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(28, 'Hospitalización P4', 'Hospitalización piso 4', 'Principal', 4, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(29, 'Hospitalización P5', 'Hospitalización piso 5', 'Principal', 5, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(30, 'Hospitalización P6', 'Hospitalización piso 6', 'Principal', 6, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(31, 'Hospitalización P7', 'Hospitalización piso 7', 'Principal', 7, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(32, 'Hospitalización P8', 'Hospitalización piso 8', 'Principal', 8, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(33, 'Residencias Médicas', 'Residencias médicas', 'Principal', 9, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(34, 'Milicianos', 'Sede de milicianos', 'Anexo', -1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(35, 'Mamá Canguro', 'Programa mamá canguro', 'Anexo', 0, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(36, 'Odontología', 'Servicio de odontología', 'Anexo', 0, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(37, 'Central de Citas', 'Central de citas médicas', 'Anexo', 0, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(38, 'Prenatal', 'Consultas prenatales (mezzanina)', 'Anexo', 0, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(39, 'Adolescentes', 'Servicio de adolescentes', 'Anexo', 1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(40, 'Alto Riesgo Neurológico', 'Unidad de alto riesgo neurológico', 'Anexo', 1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(41, 'Ortopedia', 'Servicio de ortopedia', 'Anexo', 1, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(42, 'Ginecología', 'Servicio de ginecología', 'Anexo', 2, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(43, 'Ecosonografía', 'Servicio de ecosonografía', 'Anexo', 2, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(44, 'Planificación Familiar', 'Servicio de planificación familiar', 'Anexo', 2, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(45, 'Endocrinología', 'Servicio de endocrinología', 'Anexo', 2, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(46, 'Fertilidad', 'Servicio de fertilidad', 'Anexo', 3, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(47, 'Psiquiatría', 'Servicio de psiquiatría', 'Anexo', 3, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(48, 'Medicina Interna', 'Servicio de medicina interna', 'Anexo', 3, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(49, 'Infectología', 'Servicio de infectología', 'Anexo', 3, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(50, 'Recursos Humanos', 'Departamento de recursos humanos', 'Anexo', 4, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34'),
(51, 'Postgrado', 'Departamento de postgrado', 'Anexo', 4, NULL, NULL, 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34');

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
(1, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-26 17:40:48'),
(2, 'usuarios', 1, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-26 17:41:39'),
(3, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-26 17:47:21'),
(4, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-28 17:39:33'),
(5, 'bienes', 1, 'INSERT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-28 17:42:02'),
(6, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-28 17:55:04'),
(7, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-28 17:57:30'),
(8, 'bienes', 2, 'INSERT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-28 18:35:56'),
(9, 'movimientos', 3, 'INSERT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-28 18:53:27'),
(10, 'movimientos', 4, 'INSERT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-28 19:05:57'),
(11, 'usuarios', 2, 'INSERT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-29 20:38:47'),
(12, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-29 20:38:59'),
(13, 'usuarios', 2, 'login', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-29 20:39:06'),
(14, 'usuarios', 2, 'UPDATE', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-29 20:39:18'),
(15, 'usuarios', 2, 'logout', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-29 20:39:51'),
(16, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-29 20:39:53'),
(17, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-29 20:40:22'),
(18, 'usuarios', 2, 'login', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-29 20:40:27'),
(19, 'usuarios', 2, 'logout', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-29 20:45:08'),
(20, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-29 20:45:35'),
(21, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-29 20:49:50'),
(22, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-29 20:51:12'),
(23, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-05-29 20:58:14'),
(24, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 17:08:51'),
(25, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 17:15:24'),
(26, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 17:33:15'),
(27, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 17:35:19'),
(28, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 17:38:03'),
(29, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 17:40:32'),
(30, 'usuarios', 2, 'login', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 17:41:52'),
(31, 'usuarios', 2, 'logout', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 17:42:17'),
(32, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 17:42:25'),
(33, 'usuarios', 3, 'INSERT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 17:43:28'),
(34, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 17:43:42'),
(35, 'usuarios', 3, 'login', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 17:44:11'),
(36, 'usuarios', 3, 'UPDATE', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 17:45:00'),
(37, 'usuarios', 3, 'logout', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 17:45:39'),
(38, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 19:20:28'),
(39, 'usuarios', 3, 'UPDATE', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 19:22:05'),
(40, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 19:22:11'),
(41, 'usuarios', 3, 'login', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 19:22:34'),
(42, 'usuarios', 3, 'UPDATE', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 19:22:52'),
(43, 'usuarios', 3, 'logout', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 19:23:48'),
(44, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 20:05:19'),
(45, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 20:15:02'),
(46, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 20:15:12'),
(47, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 20:29:00'),
(48, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 20:30:48'),
(49, 'bienes', 3, 'INSERT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 20:35:51'),
(50, 'movimientos', 6, 'INSERT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 20:37:39'),
(51, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 20:41:56'),
(52, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 21:05:20'),
(53, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-02 21:17:54'),
(54, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-03 15:17:07'),
(55, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-03 15:21:34'),
(56, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-04 21:47:44'),
(57, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-04 21:48:58'),
(58, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-05 00:25:03'),
(59, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-05 00:28:21'),
(60, 'usuarios', 1, 'login', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-05 00:31:29'),
(61, 'usuarios', 1, 'logout', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, '2026-06-05 00:32:45');

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


CREATE TABLE `verificaciones` (
  `id_verificacion` int(11) NOT NULL AUTO_INCREMENT,
  `bien_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('semestral','anual','extraordinaria') NOT NULL DEFAULT 'semestral',
  `observaciones` text DEFAULT NULL,
  `fecha_verificacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_verificacion`),
  KEY `idx_verificaciones_bien` (`bien_id`),
  KEY `idx_verificaciones_usuario` (`usuario_id`),
  CONSTRAINT `verificaciones_ibfk_1` FOREIGN KEY (`bien_id`) REFERENCES `bienes` (`id_bien`) ON DELETE CASCADE,
  CONSTRAINT `verificaciones_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
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
(1, 'BN-2026-000001', '06-2-ANE-3-0001', 'dadw', 'DG45345', 0, 'dsa4354', 'dsfrfsdf43', 'dsfse3453', 'sd', 'sdfsadf', 'dsfs43', 1900, 1, 'Nuevo', 5, 9, 47, NULL, NULL, NULL, NULL, 'Anexo', '3', 'Psiquiatría', 'jkhy', '2', '', '2026-05-21', 0.00, 0.00, 10, 'ewre54', NULL, '2026-05-28 17:42:02', '2026-05-28 19:05:57'),
(2, 'BN-2026-000002', '03-ANE-3-0001', 'GFHDG', 'S/N', 1, 'dfgdsf', 'FGHDRH', 'FTH', 'FHT', 'FHT', '', NULL, 1, 'Nuevo', 5, 3, 48, NULL, 'GFHT', '32361670', NULL, 'Anexo', '3', 'Medicina Interna', 'jkhy', 'fgdr', '', '2026-05-21', 0.00, 0.00, 10, 'FJJY', NULL, '2026-05-28 18:35:56', '2026-05-28 18:53:27'),
(3, 'BN-2026-000003', '03-1-ANE--1-0001', 'rttdtrd34555', 'S/N', 1, 'hgjyghhkuh', 'iuoi', 'iuy656886fre', 'kuhui', 'lkjij', 'jh', NULL, 1, 'Nuevo', 3, 4, 47, NULL, NULL, NULL, NULL, 'Anexo', '3', 'Psiquiatría', 'mmml', '9', '', '2026-06-01', 0.00, 0.00, 10, 'jkji8frdtrdyfyr', NULL, '2026-06-02 20:35:51', '2026-06-02 20:37:39');

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
(1, 'app_nombre', 'Sistema de Gestión de Bienes Nacionales', 'Nombre de la aplicación', 'string', 1, '2026-05-26 17:40:35', '2026-05-26 17:40:35'),
(2, 'app_version', '1.1.0', 'Versión del sistema', 'string', 1, '2026-05-26 17:40:35', '2026-05-26 17:40:35'),
(3, 'institucion_nombre', 'Maternidad Concepción Palacios', 'Nombre de la institución', 'string', 1, '2026-05-26 17:40:35', '2026-05-26 17:40:35'),
(4, 'institucion_rif', 'G-20003090-0', 'RIF de la institución', 'string', 1, '2026-05-26 17:40:35', '2026-05-26 17:40:35'),
(5, 'frecuencia_inventario', 'Semestral', 'Frecuencia de inventario acordada', 'string', 1, '2026-05-26 17:40:35', '2026-05-26 17:40:35'),
(6, 'sesion_tiempo_minutos', '30', 'Tiempo de sesión en minutos', 'integer', 1, '2026-05-26 17:40:35', '2026-05-26 17:40:35'),
(7, 'intentos_login_max', '5', 'Máximo de intentos de login', 'integer', 1, '2026-05-26 17:40:35', '2026-05-26 17:40:35');

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
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_aprobacion` timestamp NULL DEFAULT NULL,
  `fecha_ejecucion` timestamp NULL DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `contenido_acta` longtext DEFAULT NULL,
  `documento_soporte` varchar(500) DEFAULT NULL,
  `estado` enum('pendiente','aprobado','rechazado','cancelado') NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`id_movimiento`, `bien_id`, `tipo_movimiento`, `area_origen_id`, `area_destino_id`, `usuario_solicita_id`, `usuario_aprueba_id`, `fecha_solicitud`, `fecha_aprobacion`, `fecha_ejecucion`, `motivo`, `observaciones`, `contenido_acta`, `documento_soporte`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 'incorporacion', NULL, 47, 1, NULL, '2026-05-28 17:42:02', '2026-05-28 17:42:02', NULL, 'Incorporación inicial del bien', NULL, NULL, NULL, 'aprobado', '2026-05-28 17:42:02', '2026-05-28 17:42:02'),
(2, 2, 'incorporacion', NULL, 48, 1, NULL, '2026-05-28 18:35:56', '2026-05-28 18:35:56', NULL, 'Incorporación inicial del bien', NULL, NULL, NULL, 'aprobado', '2026-05-28 18:35:56', '2026-05-28 18:35:56'),
(3, 2, 'desincorporacion', 48, NULL, 1, 1, '2026-05-28 18:53:27', '2026-05-28 18:53:27', NULL, 'SDAWD', NULL, '------------------------------------------------------------------\r\nACTA DE DESINCORPORACIÓN DE BIEN NACIONAL\r\n------------------------------------------------------------------\r\nFecha: 28/05/2026 | Hora: 14:53\r\nSede: Maternidad Concepción Palacios\r\n\r\nPor medio de la presente, se hace constar el movimiento del siguiente bien nacional:\r\n\r\nDATOS DEL BIEN:\r\n- Nombre: dfgdsf\r\n- Código Interno: 03-ANE-3-0001\r\n- Nro. Bien (Ministerio): S/N\r\n- Marca / Modelo: FHT / FHT\r\n- Serial: FTH\r\n\r\nDETALLES DEL MOVIMIENTO:\r\n- Tipo: Desincorporacion\r\n- Área Origen: Medicina Interna (Edif. Anexo)\r\n- Área Destino: No aplica\r\n\r\nMOTIVO DEL MOVIMIENTO:\r\nSDAWD\r\n\r\n------------------------------------------------------------------\r\nFIRMAS DE RESPONSABILIDAD:\r\n\r\nSolicitado por:\r\n__________________________\r\nAdministrador BN\r\nC.I: V-00000001\r\n\r\nRecibido por (Área Destino):\r\n__________________________\r\nNombre:\r\nC.I:\r\nFecha:\r\n\r\nSello de la Unidad / Departamento\r\n------------------------------------------------------------------', NULL, 'aprobado', '2026-05-28 18:53:27', '2026-05-28 18:53:27'),
(4, 1, 'desincorporacion', 47, NULL, 1, 1, '2026-05-28 19:05:57', '2026-05-28 19:05:57', NULL, 'ASDADW', NULL, 'REPÚBLICA BOLIVARIANA DE VENEZUELA\r\nMINISTERIO DEL PODER POPULAR PARA LA SALUD\r\nMATERNIDAD CONCEPCIÓN PALACIOS\r\nDIVISIÓN DE BIENES NACIONALES\r\n\r\nACTA DE DESINCORPORACIÓN DE BIEN NACIONAL\r\nNro: 20260528-1\r\n\r\nFECHA: 28/05/2026\r\nLUGAR: Caracas, Distrito Capital\r\n\r\nPor medio de la presente Acta, se hace constar formalmente el movimiento del Bien Nacional que se detalla a continuación, bajo la responsabilidad de las unidades involucradas:\r\n\r\n1. IDENTIFICACIÓN DEL BIEN:\r\n------------------------------------------------------------------\r\nNOMBRE: dsa4354\r\nCÓDIGO INTERNO: 06-2-ANE-3-0001\r\nNRO. BIEN (MINISTERIO): DG45345\r\nMARCA / MODELO: sd / sdfsadf\r\nSERIAL: dsfse3453\r\nESTADO ACTUAL: Nuevo\r\n\r\n2. DETALLES DEL MOVIMIENTO:\r\n------------------------------------------------------------------\r\nTIPO DE MOVIMIENTO: Desincorporacion\r\nÁREA DE ORIGEN: Psiquiatría (Edif. Anexo)\r\nÁREA DE DESTINO: No aplica\r\n\r\n3. MOTIVO / JUSTIFICACIÓN:\r\n------------------------------------------------------------------\r\nASDADW\r\n\r\n------------------------------------------------------------------\r\nFIRMAS DE RESPONSABILIDAD:\r\n\r\nENTREGA (SOLICITANTE):            RECIBE (ÁREA DESTINO):\r\n__________________________        __________________________\r\nFirma y Sello                     Firma y Sello\r\nNombre: Administrador BN        Nombre:\r\nC.I: V-00000001           C.I:\r\nCargo: Administrador del Sistema          Cargo:\r\n\r\nREVISADO POR (BIENES NACIONALES):\r\n__________________________\r\nFirma y Sello Autorizado\r\nGerencia de Bienes Nacionales MCP', NULL, 'aprobado', '2026-05-28 19:05:57', '2026-05-28 19:05:57'),
(5, 3, 'incorporacion', NULL, 34, 1, NULL, '2026-06-02 20:35:51', '2026-06-02 20:35:51', NULL, 'Incorporación inicial del bien', NULL, NULL, NULL, 'aprobado', '2026-06-02 20:35:51', '2026-06-02 20:35:51'),
(6, 3, 'traslado', 34, 47, 1, 1, '2026-06-02 20:37:39', '2026-06-02 20:37:39', NULL, 'kjyiug', NULL, '<div style=\'font-family: Arial, sans-serif; max-width: 800px; margin: auto; padding: 20px; color: #333;\'>\r\n    <table width=\'100%\' style=\'border-collapse: collapse; margin-bottom: 20px;\'>\r\n        <tr>\r\n            <td width=\'20%\' style=\'text-align: center;\'>\r\n                <img src=\'/SBN_MCP/public/img/logo-mcp.svg\' width=\'80\' height=\'80\' alt=\'Logo\'>\r\n            </td>\r\n            <td width=\'80%\' style=\'text-align: center; line-height: 1.2;\'>\r\n                <div style=\'font-size: 14pt; font-weight: bold;\'>REPÚBLICA BOLIVARIANA DE VENEZUELA</div>\r\n                <div style=\'font-size: 12pt; font-weight: bold;\'>MINISTERIO DEL PODER POPULAR PARA LA SALUD</div>\r\n                <div style=\'font-size: 12pt; font-weight: bold;\'>MATERNIDAD CONCEPCIÓN PALACIOS</div>\r\n                <div style=\'font-size: 11pt;\'>DIVISIÓN DE BIENES NACIONALES</div>\r\n            </td>\r\n        </tr>\r\n    </table>\r\n\r\n    <div style=\'text-align: center; background: #f1f5f9; padding: 10px; margin-bottom: 20px; border: 1px solid #cbd5e1;\'>\r\n        <h2 style=\'margin: 0; font-size: 16pt; color: #1e293b;\'>ACTA DE TRASLADO DE BIEN NACIONAL</h2>\r\n        <div style=\'font-size: 10pt; color: #64748b;\'>Nro de Control: 20260602-0003</div>\r\n    </div>\r\n\r\n    <table width=\'100%\' style=\'margin-bottom: 20px;\'>\r\n        <tr>\r\n            <td style=\'font-size: 11pt;\'><strong>FECHA:</strong> 02/06/2026</td>\r\n            <td style=\'text-align: right; font-size: 11pt;\'><strong>LUGAR:</strong> Caracas, Distrito Capital</td>\r\n        </tr>\r\n    </table>\r\n\r\n    <p style=\'font-size: 11pt; text-align: justify; line-height: 1.5;\'>\r\n        Por medio de la presente Acta, se hace constar formalmente el movimiento del Bien Nacional que se detalla a continuación, el cual ha sido debidamente identificado y verificado bajo la normativa vigente de control de inventarios de esta institución:\r\n    </p>\r\n\r\n    <h3 style=\'border-bottom: 2px solid #334155; padding-bottom: 5px; color: #334155; font-size: 12pt;\'>1. IDENTIFICACIÓN DEL BIEN</h3>\r\n    <table width=\'100%\' style=\'border-collapse: collapse; margin-bottom: 20px; font-size: 10.5pt;\'>\r\n        <tr>\r\n            <td width=\'35%\' style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'><strong>NOMBRE DEL BIEN:</strong></td>\r\n            <td width=\'65%\' style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'>hgjyghhkuh</td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'><strong>CÓDIGO INTERNO:</strong></td>\r\n            <td style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'>03-1-ANE--1-0001</td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'><strong>NRO. BIEN (MINISTERIO):</strong></td>\r\n            <td style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'>S/N</td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'><strong>MARCA / MODELO:</strong></td>\r\n            <td style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'>kuhui / lkjij</td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'><strong>SERIAL / COLOR:</strong></td>\r\n            <td style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'>iuy656886fre / jh</td>\r\n        </tr>\r\n    </table>\r\n\r\n    <h3 style=\'border-bottom: 2px solid #334155; padding-bottom: 5px; color: #334155; font-size: 12pt;\'>2. DETALLES DEL MOVIMIENTO</h3>\r\n    <table width=\'100%\' style=\'border-collapse: collapse; margin-bottom: 20px; font-size: 10.5pt;\'>\r\n        <tr>\r\n            <td width=\'35%\' style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'><strong>TIPO DE MOVIMIENTO:</strong></td>\r\n            <td width=\'65%\' style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'>Traslado</td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'><strong>ÁREA DE ORIGEN:</strong></td>\r\n            <td style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'>Milicianos (Edif. Anexo)</td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'><strong>ÁREA DE DESTINO:</strong></td>\r\n            <td style=\'padding: 5px; border-bottom: 1px solid #e2e8f0;\'>Psiquiatría (Edif. Anexo)</td>\r\n        </tr>\r\n    </table>\r\n\r\n    <h3 style=\'border-bottom: 2px solid #334155; padding-bottom: 5px; color: #334155; font-size: 12pt;\'>3. MOTIVO / JUSTIFICACIÓN TÉCNICA</h3>\r\n    <div style=\'padding: 15px; border: 1px dashed #cbd5e1; background: #f8fafc; font-size: 10.5pt; line-height: 1.4; min-height: 60px;\'>\r\n        kjyiug\r\n    </div>\r\n\r\n    <div style=\'margin-top: 50px;\'>\r\n        <table width=\'100%\' style=\'border-collapse: collapse;\'>\r\n            <tr>\r\n                <td width=\'45%\' style=\'text-align: center; vertical-align: bottom;\'>\r\n                    <div style=\'border-top: 1px solid #000; padding-top: 10px; font-size: 10pt;\'>\r\n                        <strong>ENTREGA (SOLICITANTE)</strong><br>\r\n                        Administrador BN<br>\r\n                        C.I: V-00000001<br>\r\n                        Cargo: Administrador del Sistema\r\n                    </div>\r\n                </td>\r\n                <td width=\'10%\'></td>\r\n                <td width=\'45%\' style=\'text-align: center; vertical-align: bottom;\'>\r\n                    <div style=\'border-top: 1px solid #000; padding-top: 10px; font-size: 10pt;\'>\r\n                        <strong>RECIBE (ÁREA DESTINO)</strong><br>\r\n                        Nombre: ____________________<br>\r\n                        C.I: _______________________<br>\r\n                        Cargo: _____________________\r\n                    </div>\r\n                </td>\r\n            </tr>\r\n            <tr>\r\n                <td colspan=\'3\' style=\'text-align: center; padding-top: 40px;\'>\r\n                    <div style=\'display: inline-block; width: 250px; border-top: 1px solid #000; padding-top: 10px; font-size: 10pt;\'>\r\n                        <strong>REVISADO POR</strong><br>\r\n                        DIVISIÓN DE BIENES NACIONALES<br>\r\n                        Maternidad Concepción Palacios\r\n                    </div>\r\n                </td>\r\n            </tr>\r\n        </table>\r\n    </div>\r\n\r\n    <div style=\'margin-top: 30px; text-align: center; font-size: 8pt; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 10px;\'>\r\n        Documento generado electrónicamente por el Sistema de Gestión de Bienes Nacionales (SBN-MCP)\r\n    </div>\r\n</div>', NULL, 'aprobado', '2026-06-02 20:37:39', '2026-06-02 20:37:39');

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
(4, 'registrador', 'Registrador — solo carga información, no puede editar', '[\"bienes_crear\",\"bienes_ver\",\"movimientos_ver\"]', 1, '2026-05-26 17:40:34', '2026-05-26 17:40:34');

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
(1, 1, 'V-00000001', 'admin', 'Administrador', '', 'BN', '', 'Administrador BN', 'admin@mcp.gob.ve', '$2y$12$vpXoDhQfKK9R7NDUn1jEauW25P/WWnpwg/ufz9ZfCwa6ihkH2vc7m', NULL, 'Administrador del Sistema', '2026-06-05 00:31:29', 0, NULL, 0, 1, '2026-05-26 17:40:34', '2026-06-05 00:31:29'),
(2, 3, '32361670', 'dsotomayor', 'Daniel', 'Tomas', 'Sotomayor', 'Romero', 'Daniel Tomas Sotomayor Romero', 'dtsr32361670@gmail.com', '$2y$12$G8NFEzTqe9shq5YjL1zA1OvBYPLyMjFih0QtS5cJbVIJCe.mRr.eu', NULL, 'Control de inventario', '2026-06-02 17:41:52', 0, NULL, 0, 1, '2026-05-29 20:38:47', '2026-06-02 17:41:52'),
(3, 4, '32361671', 'asotomayor', 'Addiel', '', 'Sotomayor', '', 'Addiel  Sotomayor', 'asotomayor@gmail.com', '$2y$12$XyF6NDPXBdOu5fvfofye3een2go0EXKmaoWPXXWDqyokFffr3ATv.', NULL, 'Recolector', '2026-06-02 19:22:34', 0, NULL, 0, 1, '2026-06-02 17:43:28', '2026-06-02 19:22:52');

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
  ADD KEY `idx_bienes_responsable_foto` (`responsable_foto_path`);

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
  ADD KEY `area_origen_id` (`area_origen_id`),
  ADD KEY `area_destino_id` (`area_destino_id`),
  ADD KEY `usuario_solicita_id` (`usuario_solicita_id`),
  ADD KEY `usuario_aprueba_id` (`usuario_aprueba_id`),
  ADD KEY `idx_movimientos_bien` (`bien_id`),
  ADD KEY `idx_movimientos_tipo` (`tipo_movimiento`),
  ADD KEY `idx_movimientos_estado` (`estado`),
  ADD KEY `idx_movimientos_fecha` (`fecha_solicitud`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `idx_notificaciones_usuario` (`usuario_id`),
  ADD KEY `idx_notificaciones_leida` (`leida`),
  ADD KEY `idx_notificaciones_created` (`created_at`),
  ADD KEY `idx_notificaciones_usuario_leida` (`usuario_id`,`leida`);

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
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `backups_log`
--
ALTER TABLE `backups_log`
  MODIFY `id_backup` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bienes`
--
ALTER TABLE `bienes`
  MODIFY `id_bien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id_configuracion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id_reset` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipos_bien`
--
ALTER TABLE `tipos_bien`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- Filtros para la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
