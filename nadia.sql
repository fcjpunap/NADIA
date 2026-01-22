-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 22, 2026 at 04:34 PM
-- Server version: 10.11.14-MariaDB-0+deb12u2
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nadia`
--

-- --------------------------------------------------------

--
-- Table structure for table `areas_investigacion`
--

CREATE TABLE `areas_investigacion` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `areas_investigacion`
--

INSERT INTO `areas_investigacion` (`id`, `nombre`) VALUES
(1, 'Ciencias Sociales'),
(3, 'Ciencias Naturales');

-- --------------------------------------------------------

--
-- Table structure for table `configuraciones`
--

CREATE TABLE `configuraciones` (
  `id` int(10) UNSIGNED NOT NULL,
  `clave` varchar(50) NOT NULL,
  `valor` text NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `configuraciones`
--

INSERT INTO `configuraciones` (`id`, `clave`, `valor`, `descripcion`) VALUES
(1, 'nombre_institucion', 'Universidad Nacional del Altiplano de Puno', 'Nombre oficial'),
(2, 'plazo_revision', '15', 'Días hábiles jurado');

-- --------------------------------------------------------

--
-- Table structure for table `configuracion_plazos`
--

CREATE TABLE `configuracion_plazos` (
  `id` int(11) NOT NULL,
  `etapa_id` int(11) NOT NULL,
  `etapa_nombre` varchar(50) DEFAULT NULL,
  `estado_trigger` varchar(50) DEFAULT NULL,
  `dias_plazo` int(11) DEFAULT 15,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `configuracion_plazos`
--

INSERT INTO `configuracion_plazos` (`id`, `etapa_id`, `etapa_nombre`, `estado_trigger`, `dias_plazo`, `descripcion`) VALUES
(1, 1, 'Proyecto', 'Iniciado', 5, 'Sorteo de Jurados (Coord)'),
(2, 1, 'Proyecto', 'En Revisión', 10, 'Dictamen de Proyecto (Jurado)'),
(3, 1, 'Proyecto', 'Observado', 10, 'Subsanación de Observaciones (Tesista)'),
(4, 2, 'Borrador', 'En Revisión', 15, 'Dictamen de Borrador (Jurado)'),
(5, 2, 'Borrador', 'Observado', 15, 'Subsanación Borrador (Tesista)');

-- --------------------------------------------------------

--
-- Table structure for table `config_email`
--

CREATE TABLE `config_email` (
  `id` int(11) NOT NULL DEFAULT 1,
  `smtp_active` tinyint(1) DEFAULT 0,
  `smtp_server` varchar(100) DEFAULT NULL,
  `smtp_port` int(11) DEFAULT NULL,
  `smtp_secure` varchar(10) DEFAULT 'ssl',
  `smtp_user` varchar(100) DEFAULT NULL,
  `smtp_pass` varchar(100) DEFAULT NULL,
  `sender_email` varchar(100) DEFAULT NULL,
  `sender_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `config_email`
--

INSERT INTO `config_email` (`id`, `smtp_active`, `smtp_server`, `smtp_port`, `smtp_secure`, `smtp_user`, `smtp_pass`, `sender_email`, `sender_name`) VALUES
(1, 0, '', 25, 'ssl', '', '', 'usegundaefcjp@unap.edu.pe', 'Sistema NADIA');

-- --------------------------------------------------------

--
-- Table structure for table `config_plazos`
--

CREATE TABLE `config_plazos` (
  `clave` varchar(50) NOT NULL,
  `valor` int(11) NOT NULL,
  `unidad` enum('dias','meses','aÃ±os') DEFAULT 'dias',
  `descripcion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `config_plazos`
--

INSERT INTO `config_plazos` (`clave`, `valor`, `unidad`, `descripcion`) VALUES
('plazo_ejecucion_tesis', 24, 'meses', 'Tiempo mÃ¡ximo para desarrollar la tesis'),
('plazo_fase_borrador', 30, 'dias', NULL),
('plazo_fase_dictamen', 10, 'dias', 'Plazo para dictamen de Borrador de Tesis'),
('plazo_fase_dictamen_proyecto', 10, 'dias', 'Plazo para dictamen de Proyecto de Tesis'),
('plazo_fase_ejecucion', 24, 'meses', 'Plazo para ejecuciÃ³n de tesis (Tras aprobaciÃ³n)'),
('plazo_fase_revision', 10, 'dias', 'Plazo para revisiÃ³n de jurados (Proyecto)'),
('plazo_fase_sorteo', 5, 'dias', 'Plazo para sortear jurados tras registro'),
('plazo_jurado_revision', 10, 'dias', 'Tiempo para que el jurado emita dictamen');

-- --------------------------------------------------------

--
-- Table structure for table `constancias_emitidas`
--

CREATE TABLE `constancias_emitidas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `cvd` varchar(32) DEFAULT NULL,
  `fecha_emision` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `constancias_emitidas`
--

INSERT INTO `constancias_emitidas` (`id`, `id_usuario`, `cvd`, `fecha_emision`) VALUES
(1, 4, 'FA872BF88087FB68', '2026-01-11 15:53:14'),
(2, -4, '7690891B194C547A', '2026-01-11 19:03:19'),
(3, -4, '37DC2381AB6AEBF6', '2026-01-11 19:11:32'),
(4, -4, 'E59DBF0A2B2DECA8', '2026-01-11 19:46:34'),
(5, 5, '79A72647BDF73515', '2026-01-11 21:16:46'),
(6, 4, '69176F8ADE13E9F9', '2026-01-13 03:48:12'),
(7, 4, '68165E5C7EE0EF58', '2026-01-13 04:12:25'),
(8, -2, 'BB9411C17ED9B43D', '2026-01-13 04:13:02'),
(9, 4, 'DED5C2EFE597D37D', '2026-01-13 04:13:52'),
(10, -3, '0E6D8047CA2DDC23', '2026-01-13 05:24:23'),
(11, 3, 'D3D3E5211293DE61', '2026-01-13 05:29:21'),
(12, -9, 'F4374CA71363C4CE', '2026-01-14 19:38:33'),
(13, -9, '9D2A3E3F84F49072', '2026-01-18 17:40:10'),
(14, -9, 'BD56D6832CA48894', '2026-01-18 18:25:20'),
(15, -10, '4C439B589C57CE5E', '2026-01-19 02:53:57'),
(16, -10, '35A74CD6102EC465', '2026-01-19 05:46:10'),
(17, -10, '3055FEF152AB2B2E', '2026-01-19 18:32:54'),
(30, -11, 'C30BA7BCB6726855', '2026-01-20 02:55:52'),
(34, -11, '488576FBE35327FE', '2026-01-20 04:22:42'),
(42, -11, 'D24FDC4DA31F4B91', '2026-01-20 06:29:14'),
(47, -12, '1A6AC28428A68B75', '2026-01-20 19:48:58'),
(53, -12, '732B2851AB692B77', '2026-01-21 02:27:11'),
(56, -12, 'BAC12DF0555DC32A', '2026-01-21 03:11:42'),
(57, -13, '95064CFE0D9B9EBD', '2026-01-21 19:53:09'),
(58, -14, '028F581314ACA276', '2026-01-22 02:32:15'),
(59, -14, 'BD541FCB860096D0', '2026-01-22 02:54:40'),
(61, -14, '7CCB2AAC75C5B963', '2026-01-22 03:10:59'),
(71, -15, '80CBC6FEF27A188E', '2026-01-22 04:22:46'),
(72, -15, 'A06508646ECEE29F', '2026-01-22 04:30:51'),
(73, -15, 'E0243D57CF8FC9EA', '2026-01-22 04:36:40');

-- --------------------------------------------------------

--
-- Table structure for table `dictamenes`
--

CREATE TABLE `dictamenes` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_proyecto` int(10) UNSIGNED NOT NULL,
  `id_jurado` int(10) UNSIGNED NOT NULL,
  `resultado` varchar(100) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_emision` timestamp NULL DEFAULT current_timestamp(),
  `etapa` varchar(50) DEFAULT 'Proyecto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dictamenes`
--

INSERT INTO `dictamenes` (`id`, `id_proyecto`, `id_jurado`, `resultado`, `observaciones`, `fecha_emision`, `etapa`) VALUES
(1, 2, 5, 'Aprobado', 'Dictamen final', '2025-11-18 22:40:44', 'Proyecto'),
(2, 2, 3, 'Observado', 'Dictamen Final', '2025-11-19 06:39:30', 'Proyecto'),
(3, 2, 4, 'Aprobado', 'Dictamen Final', '2025-11-19 06:44:53', 'Proyecto'),
(5, 2, 6, 'Aprobado', 'Dictamen Final', '2025-11-19 21:19:33', 'Proyecto'),
(6, 3, 10, 'Aprobado', 'Dictamen Final', '2025-11-19 22:00:24', 'Proyecto'),
(7, 3, 20, 'Aprobado', 'Dictamen Final', '2025-11-19 22:02:41', 'Proyecto'),
(8, 3, 12, 'Aprobado', 'Dictamen Final', '2025-11-19 22:02:59', 'Proyecto'),
(9, 3, 13, 'Aprobado', 'Dictamen Final', '2025-11-19 22:03:20', 'Proyecto'),
(10, 3, 20, 'Aprobado', 'Dictamen de Borrador', '2025-11-20 03:54:08', 'Borrador'),
(11, 3, 13, 'Observado', 'Dictamen de Borrador', '2025-11-20 03:55:33', 'Borrador'),
(12, 3, 12, 'Observado', 'Dictamen de Borrador', '2025-11-20 03:56:22', 'Borrador'),
(13, 3, 10, 'Aprobado', 'Dictamen de Borrador', '2025-11-20 04:01:51', 'Borrador'),
(14, 1, 3, 'Observado', NULL, '2025-11-22 05:15:31', 'Proyecto'),
(15, 1, 4, 'Aprobado', NULL, '2025-11-27 02:30:22', 'Proyecto'),
(16, 5, 3, 'Observado', NULL, '2025-11-27 04:12:56', 'Proyecto'),
(17, 5, 4, 'Aprobado', NULL, '2025-11-28 06:25:07', 'Proyecto'),
(18, 5, 28, 'Aprobado', NULL, '2025-11-28 06:29:56', 'Proyecto'),
(19, 4, 5, 'Aprobado', NULL, '2025-11-28 19:37:24', 'Proyecto'),
(20, 4, 6, 'Aprobado', NULL, '2025-11-28 19:40:58', 'Proyecto'),
(21, 4, 24, 'Observado', NULL, '2025-11-28 19:53:57', 'Proyecto'),
(22, 4, 24, 'Aprobado', NULL, '2025-11-28 20:49:37', 'Proyecto'),
(23, 4, 5, 'Aprobado', NULL, '2025-11-28 20:56:04', 'Proyecto'),
(24, 4, 6, 'Aprobado', NULL, '2025-11-28 20:59:00', 'Proyecto'),
(25, 4, 4, 'Aprobado', NULL, '2025-11-28 22:18:01', 'Proyecto'),
(26, 4, 6, 'Observado', NULL, '2025-11-29 04:19:26', 'Borrador'),
(27, 4, 5, 'Observado', NULL, '2025-11-29 04:29:05', 'Borrador'),
(28, 4, 24, 'Aprobado', NULL, '2025-11-29 04:30:46', 'Borrador'),
(29, 4, 5, 'Aprobado', NULL, '2025-11-29 05:09:20', 'Borrador'),
(30, 4, 24, 'Aprobado', NULL, '2025-11-29 05:23:47', 'Borrador'),
(31, 4, 6, 'Aprobado', NULL, '2025-11-29 06:33:20', 'Sustentacion'),
(32, 4, 5, 'Aprobado', NULL, '2025-11-29 06:35:59', 'Sustentacion'),
(33, 4, 4, 'Aprobado con distinción', NULL, '2025-11-30 04:35:25', 'Sustentacion'),
(34, 4, 24, 'Aprobado con distinción', NULL, '2025-11-30 04:37:33', 'Sustentacion'),
(35, 9, 28, 'Aprobado', NULL, '2026-01-13 08:06:45', 'Proyecto'),
(36, 9, 5, 'Aprobado', NULL, '2026-01-13 08:08:17', 'Proyecto'),
(37, 9, 3, 'Aprobado', NULL, '2026-01-13 08:09:12', 'Proyecto'),
(38, 8, 4, 'Aprobado', NULL, '2026-01-13 08:10:02', 'Proyecto'),
(39, 9, 28, 'Aprobado', NULL, '2026-01-18 16:27:39', 'Borrador'),
(40, 9, 5, 'Aprobado', NULL, '2026-01-18 17:33:12', 'Borrador'),
(41, 9, 28, 'Aprobado', NULL, '2026-01-18 18:21:12', 'Sustentacion'),
(42, 9, 5, 'Aprobado', NULL, '2026-01-18 18:24:00', 'Sustentacion'),
(43, 9, 3, 'Aprobado', NULL, '2026-01-18 18:25:14', 'Sustentacion'),
(44, 10, 4, 'Aprobado', NULL, '2026-01-19 02:00:12', 'Proyecto'),
(45, 10, 5, 'Aprobado', NULL, '2026-01-19 02:53:48', 'Proyecto'),
(46, 10, 28, 'Aprobado', NULL, '2026-01-19 03:09:21', 'Proyecto'),
(47, 10, 3, 'Aprobado', NULL, '2026-01-19 03:20:27', 'Proyecto'),
(48, 10, 28, 'Aprobado', NULL, '2026-01-19 05:43:58', 'Borrador'),
(49, 10, 3, 'Aprobado', NULL, '2026-01-19 05:44:37', 'Borrador'),
(50, 10, 5, 'Aprobado', NULL, '2026-01-19 05:45:07', 'Borrador'),
(51, 10, 4, 'Aprobado', NULL, '2026-01-19 05:45:47', 'Borrador'),
(52, 10, 3, 'Aprobado', NULL, '2026-01-19 18:17:41', 'Sustentacion'),
(53, 10, 4, 'Aprobado', NULL, '2026-01-19 18:27:06', 'Sustentacion'),
(54, 10, 5, 'Aprobado', NULL, '2026-01-19 18:30:52', 'Sustentacion'),
(55, 10, 28, 'Aprobado', NULL, '2026-01-19 18:32:31', 'Sustentacion'),
(56, 11, 4, 'Aprobado', NULL, '2026-01-20 02:47:44', 'Proyecto'),
(57, 11, 28, 'Aprobado', NULL, '2026-01-20 02:48:14', 'Proyecto'),
(58, 11, 3, 'Aprobado', NULL, '2026-01-20 02:54:28', 'Proyecto'),
(59, 11, 5, 'Aprobado', NULL, '2026-01-20 02:55:09', 'Proyecto'),
(60, 11, 4, 'Aprobado', NULL, '2026-01-20 04:19:40', 'Borrador'),
(61, 11, 28, 'Aprobado', NULL, '2026-01-20 04:20:06', 'Borrador'),
(62, 11, 3, 'Aprobado', NULL, '2026-01-20 04:21:10', 'Borrador'),
(63, 11, 5, 'Aprobado', NULL, '2026-01-20 04:21:55', 'Borrador'),
(64, 11, 4, 'Aprobado', NULL, '2026-01-20 06:26:37', 'Sustentacion'),
(65, 11, 28, 'Aprobado', NULL, '2026-01-20 06:28:27', 'Sustentacion'),
(66, 11, 3, 'Aprobado', NULL, '2026-01-20 06:29:10', 'Sustentacion'),
(67, 11, 5, 'Aprobado', NULL, '2026-01-20 06:30:53', 'Sustentacion'),
(68, 12, 5, 'Aprobado', NULL, '2026-01-20 19:45:19', 'Proyecto'),
(69, 12, 3, 'Aprobado', NULL, '2026-01-20 19:46:10', 'Proyecto'),
(70, 12, 4, 'Aprobado', NULL, '2026-01-20 19:46:42', 'Proyecto'),
(71, 8, 28, 'Aprobado', NULL, '2026-01-20 19:47:59', 'Proyecto'),
(72, 12, 28, 'Aprobado', NULL, '2026-01-20 19:50:09', 'Proyecto'),
(73, 12, 4, 'Aprobado', NULL, '2026-01-21 02:25:09', 'Borrador'),
(74, 12, 5, 'Aprobado', NULL, '2026-01-21 02:26:05', 'Borrador'),
(75, 12, 3, 'Aprobado', NULL, '2026-01-21 02:26:24', 'Borrador'),
(76, 12, 28, 'Aprobado', NULL, '2026-01-21 02:26:52', 'Borrador'),
(77, 12, 4, 'Aprobado', NULL, '2026-01-21 02:35:41', 'Sustentacion'),
(78, 12, 5, 'Aprobado', NULL, '2026-01-21 02:44:22', 'Sustentacion'),
(79, 12, 3, 'Aprobado', NULL, '2026-01-21 02:45:16', 'Sustentacion'),
(80, 12, 28, 'Aprobado', NULL, '2026-01-21 03:11:03', 'Sustentacion'),
(81, 13, 4, 'Aprobado', NULL, '2026-01-21 19:36:47', 'Proyecto'),
(82, 13, 5, 'Aprobado', NULL, '2026-01-21 19:49:13', 'Proyecto'),
(83, 13, 3, 'Aprobado', NULL, '2026-01-21 19:49:33', 'Proyecto'),
(84, 13, 28, 'Aprobado', NULL, '2026-01-21 19:50:07', 'Proyecto'),
(85, 14, 4, 'Aprobado', NULL, '2026-01-22 02:30:22', 'Proyecto'),
(86, 14, 28, 'Aprobado', NULL, '2026-01-22 02:30:55', 'Proyecto'),
(87, 14, 3, 'Aprobado', NULL, '2026-01-22 02:31:26', 'Proyecto'),
(88, 14, 5, 'Aprobado', NULL, '2026-01-22 02:32:05', 'Proyecto'),
(89, 14, 5, 'Aprobado', NULL, '2026-01-22 02:51:49', 'Borrador'),
(90, 14, 3, 'Aprobado', NULL, '2026-01-22 02:52:28', 'Borrador'),
(91, 14, 28, 'Aprobado', NULL, '2026-01-22 02:53:42', 'Borrador'),
(92, 14, 4, 'Aprobado', NULL, '2026-01-22 02:54:31', 'Borrador'),
(93, 14, 4, 'Aprobado con distinción', NULL, '2026-01-22 03:07:34', 'Sustentacion'),
(94, 14, 28, 'Aprobado con distinción', NULL, '2026-01-22 03:08:40', 'Sustentacion'),
(95, 14, 3, 'Aprobado con distinción', NULL, '2026-01-22 03:10:52', 'Sustentacion'),
(96, 14, 5, 'Aprobado con distinción', NULL, '2026-01-22 03:16:24', 'Sustentacion'),
(97, 15, 25, 'Aprobado', NULL, '2026-01-22 04:05:57', 'Proyecto'),
(98, 15, 5, 'Aprobado', NULL, '2026-01-22 04:06:33', 'Proyecto'),
(99, 15, 24, 'Aprobado', NULL, '2026-01-22 04:07:15', 'Proyecto'),
(100, 15, 4, 'Aprobado', NULL, '2026-01-22 04:08:43', 'Proyecto'),
(101, 15, 24, 'Aprobado', NULL, '2026-01-22 04:28:11', 'Borrador'),
(102, 15, 4, 'Aprobado', NULL, '2026-01-22 04:28:56', 'Borrador'),
(103, 15, 5, 'Aprobado', NULL, '2026-01-22 04:30:01', 'Borrador'),
(104, 15, 25, 'Aprobado', NULL, '2026-01-22 04:30:39', 'Borrador'),
(105, 15, 25, 'Aprobado', NULL, '2026-01-22 04:33:29', 'Sustentacion'),
(106, 15, 4, 'Aprobado', NULL, '2026-01-22 04:34:48', 'Sustentacion'),
(107, 15, 5, 'Aprobado', NULL, '2026-01-22 04:35:32', 'Sustentacion'),
(108, 15, 24, 'Aprobado', NULL, '2026-01-22 04:36:23', 'Sustentacion');

-- --------------------------------------------------------

--
-- Table structure for table `docente_sublineas`
--

CREATE TABLE `docente_sublineas` (
  `id_docente` int(10) UNSIGNED NOT NULL,
  `id_sublinea` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `docente_sublineas`
--

INSERT INTO `docente_sublineas` (`id_docente`, `id_sublinea`) VALUES
(3, 1),
(4, 1),
(4, 2),
(4, 3),
(5, 1),
(5, 2),
(5, 3),
(6, 3),
(6, 6),
(10, 7),
(12, 7),
(13, 7),
(16, 7),
(22, 7),
(24, 2),
(24, 3),
(24, 4),
(24, 6),
(25, 3),
(25, 4),
(25, 6),
(26, 3),
(26, 4),
(28, 1);

-- --------------------------------------------------------

--
-- Table structure for table `documentos`
--

CREATE TABLE `documentos` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_proyecto` int(10) UNSIGNED NOT NULL,
  `id_usuario_sube` int(10) UNSIGNED NOT NULL,
  `tipo_documento` varchar(50) NOT NULL,
  `nombre_archivo_original` varchar(255) DEFAULT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `mime_type` varchar(100) DEFAULT 'application/pdf',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documentos`
--

INSERT INTO `documentos` (`id`, `id_proyecto`, `id_usuario_sube`, `tipo_documento`, `nombre_archivo_original`, `ruta_archivo`, `mime_type`, `created_at`) VALUES
(1, 2, 7, 'Proyecto Inicial', 'PROYECTO DE TESIS FLOR .pdf', 'uploads/tesis/1763449780_f83d142a3cb5a83d.pdf', 'application/pdf', '2025-11-18 07:09:40'),
(2, 3, 11, 'Proyecto (PDF)', 'BORRADOR TESIS - Rolando Cruz - FINAL.pdf', 'uploads/tesis/1763585478.pdf', 'application/pdf', '2025-11-19 20:51:18'),
(3, 3, 11, 'Requisitos de Grado (PDF)', 'Firma turnitin.pdf', 'uploads/tesis/1763590962_v2.pdf', 'application/pdf', '2025-11-19 22:22:42'),
(4, 3, 11, 'Borrador Tesis (PDF)', 'BORRADOR TESIS - Rolando Cruz - FINAL.pdf', 'uploads/tesis/1763591291_v2.pdf', 'application/pdf', '2025-11-19 22:28:11'),
(5, 3, 11, 'Borrador Tesis (Word)', 'BORRADOR TESIS - Rolando Cruz - FINAL (2).docx', 'uploads/tesis/1763591304_v2.docx', 'application/msword', '2025-11-19 22:28:24'),
(6, 3, 11, 'Corrección Borrador', 'Borrador corregido.pdf', 'uploads/tesis/1763691760_v2.pdf', 'application/pdf', '2025-11-21 02:22:40'),
(7, 2, 7, 'Corrección Proyecto', 'Vilma Proyecto corregido.pdf', 'uploads/tesis/1763786573_v2.pdf', 'application/pdf', '2025-11-22 04:42:53'),
(8, 1, 2, 'Corrección Proyecto', 'Pedro Proyecto.pdf', 'uploads/tesis/1763786806_v2.pdf', 'application/pdf', '2025-11-22 04:46:46'),
(9, 4, 23, 'Proyecto (PDF)', 'Pedro Planas Proyecto.pdf', 'uploads/tesis/1763790779.pdf', 'application/pdf', '2025-11-22 05:52:59'),
(10, 4, 23, 'Proyecto (Word)', 'Pedro Planas Proyecto.docx', 'uploads/tesis/1763790779w.docx', 'application/msword', '2025-11-22 05:52:59'),
(11, 5, 27, 'Proyecto (PDF)', 'PROYECTO DE Sin Miedo Carlos.pdf', 'uploads/tesis/1764192319.pdf', 'application/pdf', '2025-11-26 21:25:19'),
(12, 5, 27, 'Proyecto (Word)', 'PROYECTO DE Sin Miedo Carlos.docx', 'uploads/tesis/1764192319w.docx', 'application/msword', '2025-11-26 21:25:19'),
(13, 5, 27, 'Corrección Proyecto (PDF)', 'PROYECTO corregido DE Sin Miedo Carlos.pdf', 'uploads/tesis/1764301954.pdf', 'application/pdf', '2025-11-28 03:52:34'),
(14, 5, 27, 'Corrección Proyecto (Word)', 'PROYECTO corregido DE Sin Miedo Carlos.docx', 'uploads/tesis/1764301954w.docx', 'application/msword', '2025-11-28 03:52:34'),
(15, 4, 23, 'Corrección Proyecto (PDF)', 'PROYECTO corregido de Pedro Planas.pdf', 'uploads/tesis/1764360424.pdf', 'application/pdf', '2025-11-28 20:07:04'),
(16, 4, 23, 'Corrección Proyecto (Word)', 'PROYECTO corregido de Pedro Planas.docx', 'uploads/tesis/1764360424w.docx', 'application/msword', '2025-11-28 20:07:04'),
(17, 4, 23, 'Requisitos de Grado (PDF)', 'Requisitos de Pedro Planas.pdf', 'uploads/tesis/1764369804.pdf', 'application/pdf', '2025-11-28 22:43:24'),
(18, 4, 23, 'Requisitos de Grado (Word)', 'Requisitos de Pedro Planas.docx', 'uploads/tesis/1764369804w.docx', 'application/msword', '2025-11-28 22:43:24'),
(19, 4, 23, 'Borrador Tesis (PDF)', 'Borrador de tesis de Pedro Planas.pdf', 'uploads/tesis/1764370074.pdf', 'application/pdf', '2025-11-28 22:47:54'),
(20, 4, 23, 'Borrador Tesis (Word)', 'Borrador de tesis de Pedro Planas.docx', 'uploads/tesis/1764370074w.docx', 'application/msword', '2025-11-28 22:47:54'),
(21, 4, 23, 'Corrección Borrador (PDF)', 'Borrador corregido de tesis de Pedro Planas.pdf', 'uploads/tesis/1764390869.pdf', 'application/pdf', '2025-11-29 04:34:29'),
(22, 4, 23, 'Corrección Borrador (Word)', 'Borrador corregido de tesis de Pedro Planas.docx', 'uploads/tesis/1764390869w.docx', 'application/msword', '2025-11-29 04:34:29'),
(23, 4, 23, 'Requisitos Sustentación', 'Requisitos para sustentar - Pedro Planas.pdf', 'uploads/tesis/1764396416_req.pdf', 'application/pdf', '2025-11-29 06:06:56'),
(24, 6, 29, 'Proyecto (PDF)', 'Proyecto de Pedro Palotes.pdf', 'uploads/tesis/1767405539.pdf', 'application/pdf', '2026-01-03 01:58:59'),
(25, 6, 29, 'Proyecto (Word)', 'Proyecto de Pedro Palotes.docx', 'uploads/tesis/1767405539w.docx', 'application/msword', '2026-01-03 01:58:59'),
(26, 7, 30, 'Proyecto (PDF)', 'Proyecto de Pedro Segundo.pdf', 'uploads/tesis/1767583462.pdf', 'application/pdf', '2026-01-05 03:24:22'),
(27, 7, 30, 'Proyecto (Word)', 'Proyecto de Pedro Segundo.docx', 'uploads/tesis/1767583462w.docx', 'application/msword', '2026-01-05 03:24:22'),
(29, 8, 33, 'Proyecto (Word)', 'Proyecto de Pedro quinto.docx', 'uploads/tesis/1767590030w.docx', 'application/msword', '2026-01-05 05:13:50'),
(30, 9, 34, 'Proyecto (PDF)', 'Proyecto de Pedro Sexto ajsutado.pdf', 'uploads/tesis/1768286142_9de901fd.pdf', 'application/pdf', '2026-01-13 06:35:42'),
(31, 9, 34, 'Proyecto (Word)', 'Proyecto de Pedro Sexto ajsutado.docx', 'uploads/tesis/1768286161_b5d26b0f.docx', 'application/msword', '2026-01-13 06:36:01'),
(32, 8, 1, 'Proyecto (PDF)', '1767590030.pdf', 'uploads/tesis/1768143931_6963bc3b4908e.pdf', 'application/pdf', '2026-01-11 15:05:31'),
(33, 9, 34, 'Requisitos de Grado (PDF)', 'Requisitos-Grado.pdf', 'uploads/tesis/requisitos_proyecto9.pdf', 'application/pdf', '2026-01-14 21:18:31'),
(34, 9, 1, 'Requisitos de Grado (PDF)', 'Requisitos del Titulo del proyecto de Sexto Primero Pedro y Septimo Segundo Pedro.pdf', 'uploads/tesis/1768429067_fa16ba8a.pdf', 'application/pdf', '2026-01-14 22:17:47'),
(35, 9, 34, 'Borrador Tesis (PDF)', 'BORRADOR DE TESIS del Titulo del proyecto de Sexto Primero Pedro y Septimo Segundo Pedro.pdf', 'uploads/tesis/1768429407.pdf', 'application/pdf', '2026-01-14 22:23:27'),
(36, 9, 34, 'Borrador Tesis (Word)', 'BORRADOR DE TESIS del Titulo del proyecto de Sexto Primero Pedro y Septimo Segundo Pedro.docx', 'uploads/tesis/1768429407w.docx', 'application/msword', '2026-01-14 22:23:27'),
(37, 9, 34, 'Corrección Borrador (PDF)', 'CORRECCION DEL BORRADOR DE TESIS del Titulo del proyecto de Sexto Primero Pedro y Septimo Segundo Pedro.pdf', 'uploads/tesis/1768701555.pdf', 'application/pdf', '2026-01-18 01:59:15'),
(38, 9, 34, 'Corrección Borrador (Word)', 'CORRECCION DEL BORRADOR DE TESIS del Titulo del proyecto de Sexto Primero Pedro y Septimo Segundo Pedro.docx', 'uploads/tesis/1768701555w.docx', 'application/msword', '2026-01-18 01:59:15'),
(39, 10, 37, 'Proyecto (PDF)', 'Proyecto de Pedro Octavo.pdf', 'uploads/tesis/1768770737_d7898ee3.pdf', 'application/pdf', '2026-01-18 21:12:17'),
(40, 10, 37, 'Proyecto (Word)', 'Proyecto de Pedro Octavo.docx', 'uploads/tesis/1768770762_0d9e5f0f.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '2026-01-18 21:12:42'),
(41, 10, 37, 'Corrección Proyecto (PDF)', 'Proyecto corregido de Pedro Octavo.pdf', 'uploads/tesis/1768786049.pdf', 'application/pdf', '2026-01-19 01:27:29'),
(42, 10, 37, 'Corrección Proyecto (Word)', 'Proyecto corregido de Pedro Octavo.docx', 'uploads/tesis/1768786049w.docx', 'application/msword', '2026-01-19 01:27:29'),
(43, 10, 37, 'Requisitos de Grado (PDF)', 'Requisitos de Pedro Octavo.pdf', 'uploads/tesis/1768796438.pdf', 'application/pdf', '2026-01-19 04:20:38'),
(44, 10, 37, 'Requisitos de Grado (Word)', 'Requisitos de Pedro Octavo.docx', 'uploads/tesis/1768796438w.docx', 'application/msword', '2026-01-19 04:20:38'),
(45, 10, 37, 'Borrador Tesis (PDF)', 'Borrador de Tesis de Pedro Octavo.pdf', 'uploads/tesis/1768798525.pdf', 'application/pdf', '2026-01-19 04:55:25'),
(46, 10, 37, 'Borrador Tesis (Word)', 'Borrador de Tesis de Pedro Octavo.docx', 'uploads/tesis/1768798525w.docx', 'application/msword', '2026-01-19 04:55:25'),
(47, 10, 37, 'Corrección Borrador (PDF)', 'Correcion del Borrador de Tesis de Pedro Octavo.pdf', 'uploads/tesis/1768801101.pdf', 'application/pdf', '2026-01-19 05:38:21'),
(48, 10, 37, 'Corrección Borrador (Word)', 'Correcion del Borrador de Tesis de Pedro Octavo.docx', 'uploads/tesis/1768801101w.docx', 'application/msword', '2026-01-19 05:38:21'),
(49, 10, 37, 'Requisitos de Sustentacion (PDF)', 'Requisitos de sustentación de Pedro Octavo.pdf', 'uploads/tesis/1768801686.pdf', 'application/pdf', '2026-01-19 05:48:06'),
(50, 10, 37, 'Requisitos de Sustentacion (Word)', 'Requisitos de sustentación de Pedro Octavo.docx', 'uploads/tesis/1768801686w.docx', 'application/msword', '2026-01-19 05:48:06'),
(51, 11, 41, 'Proyecto (PDF)', 'Proyecto investigación jurídica de Pedro Doce.pdf', 'uploads/tesis/1768855936.pdf', 'application/pdf', '2026-01-19 20:52:16'),
(52, 11, 41, 'Proyecto (Word)', 'Proyecto investigación jurídica de Pedro Doce.docx', 'uploads/tesis/w.docx', 'application/msword', '2026-01-19 20:52:16'),
(53, 11, 41, 'Corrección Proyecto (PDF)', 'Correcion del Proyecto investigación jurídica de Pedro Doce.pdf', 'uploads/tesis/1768875745.pdf', 'application/pdf', '2026-01-20 02:22:25'),
(54, 11, 41, 'Corrección Proyecto (Word)', 'Correcion del Proyecto investigación jurídica de Pedro Doce.docx', 'uploads/tesis/1768875745w.docx', 'application/msword', '2026-01-20 02:22:25'),
(55, 11, 41, 'Requisitos de Grado (PDF)', 'Requisitos del Proyecto investigación jurídica de Pedro Doce.pdf', 'uploads/tesis/1768878153.pdf', 'application/pdf', '2026-01-20 03:02:33'),
(56, 11, 41, 'Requisitos de Grado (Word)', 'Requisitos del Proyecto investigación jurídica de Pedro Doce.docx', 'uploads/tesis/1768878153w.docx', 'application/msword', '2026-01-20 03:02:33'),
(57, 11, 41, 'Borrador Tesis (PDF)', 'Borrador de tesis jurídica de Pedro Doce.pdf', 'uploads/tesis/1768878345.pdf', 'application/pdf', '2026-01-20 03:05:45'),
(58, 11, 41, 'Borrador Tesis (Word)', 'Borrador de tesis jurídica de Pedro Doce.docx', 'uploads/tesis/1768878345w.docx', 'application/msword', '2026-01-20 03:05:45'),
(59, 11, 41, 'Corrección Borrador (PDF)', 'correccion del Borrador de tesis jurídica de Pedro Doce.pdf', 'uploads/tesis/1768879203.pdf', 'application/pdf', '2026-01-20 03:20:03'),
(60, 11, 41, 'Corrección Borrador (Word)', 'correccion del Borrador de tesis jurídica de Pedro Doce.docx', 'uploads/tesis/1768879203w.docx', 'application/msword', '2026-01-20 03:20:03'),
(63, 11, 41, 'Requisitos de Sustentacion (PDF)', 'Requisistos para sustentar Borrador de tesis jurídica de Pedro Doce.pdf', 'uploads/tesis/1768883723.pdf', 'application/pdf', '2026-01-20 04:35:23'),
(64, 11, 41, 'Requisitos de Sustentacion (Word)', 'Requisistos para sustentar Borrador de tesis jurídica de Pedro Doce.docx', 'uploads/tesis/1768883723w.docx', 'application/msword', '2026-01-20 04:35:23'),
(65, 12, 42, 'Proyecto (PDF)', 'Proyecto Pedro Trece y Once.pdf', 'uploads/tesis/1768937132_bb7f4f37.pdf', 'application/pdf', '2026-01-20 19:25:32'),
(66, 12, 42, 'Proyecto (Word)', 'Proyecto Pedro Trece y Once.docx', 'uploads/tesis/1768937146_741263bd.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '2026-01-20 19:25:46'),
(67, 12, 42, 'Corrección Proyecto (PDF)', 'Proyecto coregido Pedro Trece y Once.pdf', 'uploads/tesis/1768938144.pdf', 'application/pdf', '2026-01-20 19:42:24'),
(68, 12, 42, 'Corrección Proyecto (Word)', 'Proyecto coregido Pedro Trece y Once.docx', 'uploads/tesis/1768938144w.docx', 'application/msword', '2026-01-20 19:42:24'),
(69, 12, 42, 'Requisitos de Grado (PDF)', 'Requisitos para subir borrador de tesis Pedro Trece y Once.pdf', 'uploads/tesis/1768939289.pdf', 'application/pdf', '2026-01-20 20:01:29'),
(70, 12, 42, 'Requisitos de Grado (Word)', 'Requisitos para subir borrador de tesis Pedro Trece y Once.docx', 'uploads/tesis/1768939289w.docx', 'application/msword', '2026-01-20 20:01:29'),
(71, 12, 42, 'Borrador Tesis (PDF)', 'Borrador de tesis Pedro Trece y Once.pdf', 'uploads/tesis/1768940233.pdf', 'application/pdf', '2026-01-20 20:17:13'),
(72, 12, 42, 'Borrador Tesis (Word)', 'Borrador de tesis Pedro Trece y Once.docx', 'uploads/tesis/1768940233w.docx', 'application/msword', '2026-01-20 20:17:13'),
(73, 12, 42, 'Corrección Borrador (PDF)', 'Correcion Borrador de tesis Pedro Trece y Once.pdf', 'uploads/tesis/1768956447.pdf', 'application/pdf', '2026-01-21 00:47:27'),
(74, 12, 42, 'Corrección Borrador (Word)', 'Correcion Borrador de tesis Pedro Trece y Once.docx', 'uploads/tesis/1768956447w.docx', 'application/msword', '2026-01-21 00:47:27'),
(75, 12, 42, 'Requisitos de Sustentacion (PDF)', 'Requisitos para sustentar de tesis Pedro Trece y Once.pdf', 'uploads/tesis/1768962603.pdf', 'application/pdf', '2026-01-21 02:30:03'),
(76, 12, 42, 'Requisitos de Sustentacion (Word)', 'Requisitos para sustentar de tesis Pedro Trece y Once.docx', 'uploads/tesis/1768962603w.docx', 'application/msword', '2026-01-21 02:30:03'),
(77, 13, 43, 'Proyecto (PDF)', 'Proyecto de Pedro Catorce.pdf', 'uploads/tesis/1769021764.pdf', 'application/pdf', '2026-01-21 18:56:04'),
(78, 13, 43, 'Proyecto (Word)', 'Proyecto de Pedro Catorce.docx', 'uploads/tesis/1769021764w.docx', 'application/msword', '2026-01-21 18:56:04'),
(79, 13, 43, 'Corrección Proyecto (PDF)', 'Proyecto corregido de Pedro Catorce.pdf', 'uploads/tesis/1769023613.pdf', 'application/pdf', '2026-01-21 19:26:53'),
(80, 13, 43, 'Corrección Proyecto (Word)', 'Proyecto corregido de Pedro Catorce.docx', 'uploads/tesis/1769023613w.docx', 'application/msword', '2026-01-21 19:26:53'),
(81, 13, 43, 'Requisitos de Grado (PDF)', 'Requisitos de grado Proyecto corregido de Pedro Catorce.pdf', 'uploads/tesis/1769025109.pdf', 'application/pdf', '2026-01-21 19:51:49'),
(82, 13, 43, 'Requisitos de Grado (Word)', 'Requisitos de grado Proyecto corregido de Pedro Catorce.docx', 'uploads/tesis/1769025109w.docx', 'application/msword', '2026-01-21 19:51:49'),
(83, 14, 44, 'Proyecto (PDF)', 'Proyecto de Pedro 15.pdf', 'uploads/tesis/1769045822.pdf', 'application/pdf', '2026-01-22 01:37:02'),
(84, 14, 44, 'Proyecto (Word)', 'Proyecto de Pedro 15.docx', 'uploads/tesis/1769045822w.docx', 'application/msword', '2026-01-22 01:37:02'),
(85, 14, 44, 'Corrección Proyecto (PDF)', 'Correcion Proyecto de Pedro 15.pdf', 'uploads/tesis/1769048388.pdf', 'application/pdf', '2026-01-22 02:19:48'),
(86, 14, 44, 'Corrección Proyecto (Word)', 'Correcion Proyecto de Pedro 15.docx', 'uploads/tesis/1769048388w.docx', 'application/msword', '2026-01-22 02:19:48'),
(87, 14, 44, 'Requisitos de Grado (PDF)', 'Requisitos Correcion Proyecto de Pedro 15.pdf', 'uploads/tesis/1769049277.pdf', 'application/pdf', '2026-01-22 02:34:37'),
(88, 14, 44, 'Requisitos de Grado (Word)', 'Requisitos Correcion Proyecto de Pedro 15.docx', 'uploads/tesis/1769049277w.docx', 'application/msword', '2026-01-22 02:34:37'),
(89, 14, 44, 'Borrador Tesis (PDF)', 'Borrador de tesis de Pedro 15.pdf', 'uploads/tesis/1769049525.pdf', 'application/pdf', '2026-01-22 02:38:45'),
(90, 14, 44, 'Borrador Tesis (Word)', 'Borrador de tesis de Pedro 15.docx', 'uploads/tesis/1769049525w.docx', 'application/msword', '2026-01-22 02:38:45'),
(91, 14, 44, 'Corrección Borrador (PDF)', 'Correccionews al Borrador de tesis de Pedro 15.pdf', 'uploads/tesis/1769050152.pdf', 'application/pdf', '2026-01-22 02:49:12'),
(92, 14, 44, 'Corrección Borrador (Word)', 'Correccionews al Borrador de tesis de Pedro 15.docx', 'uploads/tesis/1769050152w.docx', 'application/msword', '2026-01-22 02:49:12'),
(93, 14, 44, 'Requisitos de Sustentacion (PDF)', 'Requisitos para la sustentación de tesis de Pedro 15.pdf', 'uploads/tesis/1769050676.pdf', 'application/pdf', '2026-01-22 02:57:56'),
(94, 14, 44, 'Requisitos de Sustentacion (Word)', 'Requisitos para la sustentación de tesis de Pedro 15.docx', 'uploads/tesis/1769050676w.docx', 'application/msword', '2026-01-22 02:57:56'),
(95, 15, 45, 'Proyecto (PDF)', 'Proyecto de Pedro 15.pdf', 'uploads/tesis/1769053749.pdf', 'application/pdf', '2026-01-22 03:49:09'),
(96, 15, 45, 'Proyecto (Word)', 'Proyecto de Pedro 15.docx', 'uploads/tesis/1769053749w.docx', 'application/msword', '2026-01-22 03:49:09'),
(97, 15, 45, 'Corrección Proyecto (PDF)', 'Correcion Proyecto de Pedro 15.pdf', 'uploads/tesis/1769054726.pdf', 'application/pdf', '2026-01-22 04:05:26'),
(98, 15, 45, 'Corrección Proyecto (Word)', 'Correcion Proyecto de Pedro 15.docx', 'uploads/tesis/1769054726w.docx', 'application/msword', '2026-01-22 04:05:26'),
(99, 15, 45, 'Requisitos de Grado (PDF)', 'Requisitos Correcion Proyecto de Pedro 15.pdf', 'uploads/tesis/1769054959.pdf', 'application/pdf', '2026-01-22 04:09:19'),
(100, 15, 45, 'Requisitos de Grado (Word)', 'Requisitos Correcion Proyecto de Pedro 15.docx', 'uploads/tesis/1769054959w.docx', 'application/msword', '2026-01-22 04:09:19'),
(101, 15, 45, 'Requisitos de Grado (PDF)', 'Requisitos Correcion Proyecto de Pedro 15.pdf', 'uploads/tesis/1769054960.pdf', 'application/pdf', '2026-01-22 04:09:20'),
(102, 15, 45, 'Requisitos de Grado (Word)', 'Requisitos Correcion Proyecto de Pedro 15.docx', 'uploads/tesis/1769054960w.docx', 'application/msword', '2026-01-22 04:09:20'),
(103, 15, 45, 'Borrador Tesis (PDF)', 'Borrador de tesis de Pedro 15.pdf', 'uploads/tesis/1769055330.pdf', 'application/pdf', '2026-01-22 04:15:30'),
(104, 15, 45, 'Borrador Tesis (Word)', 'Borrador de tesis de Pedro 15.docx', 'uploads/tesis/1769055330w.docx', 'application/msword', '2026-01-22 04:15:30'),
(105, 15, 45, 'Corrección Borrador (PDF)', 'Correccionews al Borrador de tesis de Pedro 15.pdf', 'uploads/tesis/1769055633.pdf', 'application/pdf', '2026-01-22 04:20:33'),
(106, 15, 45, 'Corrección Borrador (Word)', 'Correccionews al Borrador de tesis de Pedro 15.docx', 'uploads/tesis/1769055633w.docx', 'application/msword', '2026-01-22 04:20:33'),
(107, 15, 45, 'Corrección Borrador (PDF)', 'Correccionews al Borrador de tesis de Pedro 15.pdf', 'uploads/tesis/1769056055.pdf', 'application/pdf', '2026-01-22 04:27:35'),
(108, 15, 45, 'Corrección Borrador (Word)', 'Correccionews al Borrador de tesis de Pedro 15.docx', 'uploads/tesis/1769056055w.docx', 'application/msword', '2026-01-22 04:27:35'),
(109, 15, 45, 'Requisitos de Sustentacion (PDF)', 'Requisitos para la sustentación de tesis de Pedro 15.pdf', 'uploads/tesis/1769056283.pdf', 'application/pdf', '2026-01-22 04:31:23'),
(110, 15, 45, 'Requisitos de Sustentacion (Word)', 'Requisitos para la sustentación de tesis de Pedro 15.docx', 'uploads/tesis/1769056283w.docx', 'application/msword', '2026-01-22 04:31:23');

-- --------------------------------------------------------

--
-- Table structure for table `facultades`
--

CREATE TABLE `facultades` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `siglas` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facultades`
--

INSERT INTO `facultades` (`id`, `nombre`, `siglas`) VALUES
(1, 'Facultad de Ciencias Jurídicas y Polí­ticas', 'FCJP'),
(2, 'Facultad de Ciencias de la Educación ', 'FCEDUC');

-- --------------------------------------------------------

--
-- Table structure for table `historial_movimientos`
--

CREATE TABLE `historial_movimientos` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_proyecto` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED DEFAULT NULL,
  `accion` varchar(255) DEFAULT NULL,
  `detalle` text DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `historial_movimientos`
--

INSERT INTO `historial_movimientos` (`id`, `id_proyecto`, `id_usuario`, `accion`, `detalle`, `fecha`) VALUES
(1, 2, 3, 'DICTAMEN', 'Voto: Observado', '2025-11-19 06:39:30'),
(2, 2, 4, 'DICTAMEN', 'Voto: Aprobado', '2025-11-19 06:44:53'),
(3, 2, 6, 'DICTAMEN', 'Voto: Observado', '2025-11-19 21:12:55'),
(4, 2, 6, 'DICTAMEN', 'Voto: Aprobado', '2025-11-19 21:19:33'),
(5, 3, 10, 'DICTAMEN', 'Voto: Aprobado', '2025-11-19 22:00:24'),
(6, 3, 14, 'DICTAMEN', 'Voto: Aprobado', '2025-11-19 22:02:41'),
(7, 3, 12, 'DICTAMEN', 'Voto: Aprobado', '2025-11-19 22:02:59'),
(8, 3, 13, 'DICTAMEN', 'Voto: Aprobado', '2025-11-19 22:03:20'),
(9, 3, 1, 'EDICIÓN_ADMIN', 'Modificación manual de estado/asesor', '2025-11-19 22:43:21'),
(10, 3, 14, 'DICTAMEN', 'Voto (Borrador): Aprobado', '2025-11-20 03:54:08'),
(11, 3, 13, 'DICTAMEN', 'Voto (Borrador): Observado', '2025-11-20 03:55:33'),
(12, 3, 12, 'DICTAMEN', 'Voto (Borrador): Observado', '2025-11-20 03:56:22'),
(13, 3, 10, 'DICTAMEN', 'Voto (Borrador): Aprobado', '2025-11-20 04:01:51'),
(14, 1, 2, 'REGISTRO', 'Registro inicial del proyecto (Histórico)', '2025-11-18 07:01:44'),
(15, 3, 11, 'SUBIDA', 'Archivo: Corrección Borrador', '2025-11-21 02:22:40'),
(16, 2, 7, 'SUBIDA', 'Archivo: Corrección Proyecto', '2025-11-22 04:42:53'),
(17, 1, 2, 'SUBIDA', 'Archivo: Corrección Proyecto', '2025-11-22 04:46:46'),
(18, 1, 3, 'DICTAMEN', 'Voto Proyecto: Observado', '2025-11-22 05:15:31'),
(19, 4, 23, 'REGISTRO', 'Creación de expediente', '2025-11-22 05:52:59'),
(20, 5, 27, 'REGISTRO', 'Creación de expediente', '2025-11-26 21:25:19'),
(21, 1, 4, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2025-11-27 02:30:22'),
(22, 5, 3, 'DICTAMEN', 'Voto Proyecto: Observado', '2025-11-27 04:12:56'),
(23, 5, 27, 'SUBIDA', 'Archivos de Corrección', '2025-11-28 03:52:34'),
(24, 5, 4, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2025-11-28 06:25:07'),
(25, 5, 28, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2025-11-28 06:29:56'),
(26, 4, 5, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2025-11-28 19:37:24'),
(27, 4, 6, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2025-11-28 19:40:58'),
(28, 4, 24, 'DICTAMEN', 'Voto Proyecto: Observado', '2025-11-28 19:53:57'),
(29, 4, 23, 'SUBIDA', 'Archivos de Corrección', '2025-11-28 20:07:04'),
(30, 4, 24, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2025-11-28 20:49:37'),
(31, 4, 5, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2025-11-28 20:56:04'),
(32, 4, 6, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2025-11-28 20:59:00'),
(33, 4, 4, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2025-11-28 22:18:01'),
(34, 4, 23, 'SUBIDA', 'Archivos de Corrección', '2025-11-28 22:43:24'),
(35, 4, 23, 'SUBIDA', 'Archivos de Corrección', '2025-11-28 22:47:54'),
(36, 4, 6, 'DICTAMEN', 'Voto Borrador: Observado', '2025-11-29 04:19:26'),
(37, 4, 5, 'DICTAMEN', 'Voto Borrador: Observado', '2025-11-29 04:29:05'),
(38, 4, 24, 'DICTAMEN', 'Voto Borrador: Aprobado', '2025-11-29 04:30:46'),
(39, 4, 23, 'SUBIDA', 'Archivos de Corrección', '2025-11-29 04:34:29'),
(40, 4, 5, 'DICTAMEN', 'Voto Borrador: Aprobado', '2025-11-29 05:09:20'),
(41, 4, 23, 'SUBIDA', 'Archivos de Corrección', '2025-11-29 05:22:23'),
(42, 4, 24, 'DICTAMEN', 'Voto Borrador: Aprobado', '2025-11-29 05:23:47'),
(43, 4, 23, 'REQUISITOS', 'Subida de requisitos para sustentación', '2025-11-29 06:06:56'),
(44, 4, 6, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2025-11-29 06:33:20'),
(45, 4, 5, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2025-11-29 06:35:59'),
(46, 4, 4, 'DICTAMEN', 'Voto Sustentacion: Aprobado con distinción', '2025-11-30 04:35:25'),
(47, 4, 24, 'DICTAMEN', 'Voto Sustentacion: Aprobado con distinción', '2025-11-30 04:37:33'),
(48, 6, 29, 'REGISTRO', 'Creación de expediente', '2026-01-03 01:58:59'),
(49, 7, 30, 'REGISTRO', 'Creación de expediente', '2026-01-05 03:24:22'),
(50, 8, 33, 'REGISTRO', 'Creación de expediente', '2026-01-05 05:13:50'),
(51, 9, 34, 'REGISTRO', 'Creación de expediente', '2026-01-07 16:37:15'),
(52, 8, 1, 'SUBIDA_ARCHIVO', 'Tipo: Proyecto (PDF). Archivo: 1767590030.pdf. Motivo: Pruebas de desarrollo.', '2026-01-11 15:05:31'),
(53, 9, 8, 'REEMPLAZO', '[Proyecto (PDF)] Reemplazado por Administrador. Motivo: Subsanación de corrección de formato.', '2026-01-13 06:35:42'),
(54, 9, 8, 'REEMPLAZO', '[Proyecto (Word)] Reemplazado por Administrador. Motivo: Subsanación de corrección de formato.', '2026-01-13 06:36:01'),
(55, 9, 28, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-13 08:06:45'),
(56, 9, 5, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-13 08:08:17'),
(57, 9, 3, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-13 08:09:12'),
(58, 8, 4, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-13 08:10:02'),
(59, 9, 34, 'SUBIDA', 'Archivos de Corrección', '2026-01-14 21:18:31'),
(60, 9, 1, 'CARGA', '[Requisitos para sustentar (PDF)] Cargado por Administrador. Motivo: Prueba', '2026-01-14 22:17:47'),
(61, 9, 34, 'SUBIDA', 'Borrador de Tesis', '2026-01-14 22:23:27'),
(62, 9, 34, 'SUBIDA', 'Corrección de Borrador', '2026-01-18 01:59:15'),
(63, 9, 28, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-18 16:27:39'),
(64, 9, 5, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-18 17:33:12'),
(65, 9, 28, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-18 18:21:12'),
(66, 9, 5, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-18 18:24:00'),
(67, 9, 3, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-18 18:25:14'),
(68, 10, 37, 'REGISTRO', 'Creación de expediente', '2026-01-18 20:19:46'),
(69, 10, 1, 'REEMPLAZO', '[Proyecto (PDF)] Reemplazado por Administrador. Motivo: Subsanación.', '2026-01-18 21:12:17'),
(70, 10, 1, 'REEMPLAZO', '[Proyecto (Word)] Reemplazado por Administrador. Motivo: Subsanación.', '2026-01-18 21:12:42'),
(71, 10, 37, 'SUBIDA', 'Corrección de Proyecto', '2026-01-19 01:27:29'),
(72, 10, 4, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-19 02:00:12'),
(73, 10, 5, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-19 02:53:48'),
(74, 10, 28, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-19 03:09:21'),
(75, 10, 3, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-19 03:20:27'),
(78, 10, 37, 'SUBIDA', 'Requisitos de Grado', '2026-01-19 04:20:38'),
(79, 10, 1, 'AUTORIZACIÓN', 'Se autorizó la subida del borrador de tesis', '2026-01-19 04:32:31'),
(80, 10, 37, 'SUBIDA', 'Borrador de Tesis', '2026-01-19 04:55:25'),
(81, 10, 37, 'SUBIDA', 'Corrección de Borrador', '2026-01-19 05:38:21'),
(82, 10, 28, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-19 05:43:58'),
(83, 10, 3, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-19 05:44:37'),
(84, 10, 5, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-19 05:45:07'),
(85, 10, 4, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-19 05:45:47'),
(86, 10, 37, 'SUBIDA', 'Requisitos de Sustentacion', '2026-01-19 05:48:06'),
(87, 10, 3, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-19 18:17:41'),
(88, 10, 4, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-19 18:27:06'),
(89, 10, 5, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-19 18:30:52'),
(90, 10, 28, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-19 18:32:31'),
(91, 11, 41, 'REGISTRO', 'Creación de expediente', '2026-01-19 20:52:16'),
(92, 11, 41, 'SUBIDA', 'Corrección de Proyecto', '2026-01-20 02:22:25'),
(93, 11, 4, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-20 02:47:44'),
(94, 11, 28, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-20 02:48:14'),
(95, 11, 3, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-20 02:54:28'),
(96, 11, 5, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-20 02:55:09'),
(97, 11, 41, 'SUBIDA', 'Requisitos de Grado', '2026-01-20 03:02:33'),
(98, 11, 1, 'AUTORIZACIÓN', 'Se autorizó la subida del borrador de tesis', '2026-01-20 03:04:33'),
(99, 11, 41, 'SUBIDA', 'Borrador de Tesis', '2026-01-20 03:05:45'),
(100, 11, 41, 'SUBIDA', 'Corrección de Borrador', '2026-01-20 03:20:03'),
(101, 11, 4, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-20 04:19:40'),
(102, 11, 28, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-20 04:20:06'),
(103, 11, 3, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-20 04:21:10'),
(104, 11, 5, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-20 04:21:55'),
(106, 11, 41, 'SUBIDA', 'Requisitos de Sustentacion', '2026-01-20 04:35:23'),
(107, 11, 4, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-20 06:26:37'),
(108, 11, 28, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-20 06:28:27'),
(109, 11, 3, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-20 06:29:10'),
(110, 11, 5, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-20 06:30:53'),
(111, 12, 42, 'REGISTRO', 'Creación de expediente', '2026-01-20 07:15:45'),
(112, 12, 8, 'REEMPLAZO', '[Proyecto (PDF)] Reemplazado por Administrador. Motivo: Subsana con formato.', '2026-01-20 19:25:32'),
(113, 12, 8, 'REEMPLAZO', '[Proyecto (Word)] Reemplazado por Administrador. Motivo: Subsana con formato.', '2026-01-20 19:25:46'),
(114, 12, 42, 'SUBIDA', 'Corrección de Proyecto', '2026-01-20 19:42:25'),
(115, 12, 5, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-20 19:45:19'),
(116, 12, 3, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-20 19:46:10'),
(117, 12, 4, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-20 19:46:42'),
(118, 8, 28, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-20 19:47:59'),
(119, 12, 28, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-20 19:50:09'),
(120, 12, 42, 'SUBIDA', 'Requisitos de Grado', '2026-01-20 20:01:29'),
(121, 12, 8, 'AUTORIZACIÓN', 'Se autorizó la subida del borrador de tesis', '2026-01-20 20:02:34'),
(122, 12, 42, 'SUBIDA', 'Borrador de Tesis', '2026-01-20 20:17:13'),
(123, 12, 42, 'SUBIDA', 'Corrección de Borrador', '2026-01-21 00:47:27'),
(124, 12, 4, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-21 02:25:09'),
(125, 12, 5, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-21 02:26:05'),
(126, 12, 3, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-21 02:26:24'),
(127, 12, 28, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-21 02:26:52'),
(128, 12, 42, 'SUBIDA', 'Requisitos de Sustentacion', '2026-01-21 02:30:03'),
(129, 12, 4, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-21 02:35:41'),
(130, 12, 5, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-21 02:44:22'),
(131, 12, 3, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-21 02:45:16'),
(132, 12, 28, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-21 03:11:03'),
(133, 13, 43, 'REGISTRO', 'Creación de expediente', '2026-01-21 18:56:04'),
(134, 13, 43, 'SUBIDA', 'Corrección de Proyecto', '2026-01-21 19:26:53'),
(135, 13, 4, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-21 19:36:47'),
(136, 13, 5, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-21 19:49:13'),
(137, 13, 3, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-21 19:49:33'),
(138, 13, 28, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-21 19:50:07'),
(139, 13, 43, 'SUBIDA', 'Requisitos de Grado', '2026-01-21 19:51:49'),
(140, 13, 8, 'AUTORIZACIÓN', 'Se autorizó la subida del borrador de tesis', '2026-01-21 19:53:25'),
(141, 14, 44, 'REGISTRO', 'Creación de expediente', '2026-01-22 01:37:02'),
(142, 14, 44, 'SUBIDA', 'Corrección de Proyecto', '2026-01-22 02:19:48'),
(143, 14, 4, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-22 02:30:22'),
(144, 14, 28, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-22 02:30:55'),
(145, 14, 3, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-22 02:31:26'),
(146, 14, 5, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-22 02:32:05'),
(147, 14, 44, 'SUBIDA', 'Requisitos de Grado', '2026-01-22 02:34:37'),
(148, 14, 8, 'AUTORIZACIÓN', 'Se autorizó la subida del borrador de tesis', '2026-01-22 02:36:34'),
(149, 14, 44, 'SUBIDA', 'Borrador de Tesis', '2026-01-22 02:38:45'),
(150, 14, 44, 'SUBIDA', 'Corrección de Borrador', '2026-01-22 02:49:12'),
(151, 14, 5, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-22 02:51:49'),
(152, 14, 3, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-22 02:52:28'),
(153, 14, 28, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-22 02:53:42'),
(154, 14, 4, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-22 02:54:31'),
(155, 14, 44, 'SUBIDA', 'Requisitos de Sustentacion', '2026-01-22 02:57:56'),
(156, 14, 4, 'DICTAMEN', 'Voto Sustentacion: Aprobado con distinción', '2026-01-22 03:07:34'),
(157, 14, 28, 'DICTAMEN', 'Voto Sustentacion: Aprobado con distinción', '2026-01-22 03:08:40'),
(158, 14, 3, 'DICTAMEN', 'Voto Sustentacion: Aprobado con distinción', '2026-01-22 03:10:52'),
(159, 14, 5, 'DICTAMEN', 'Voto Sustentacion: Aprobado con distinción', '2026-01-22 03:16:24'),
(160, 15, 45, 'REGISTRO', 'Creación de expediente', '2026-01-22 03:49:09'),
(161, 15, 45, 'SUBIDA', 'Corrección de Proyecto', '2026-01-22 04:05:26'),
(162, 15, 25, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-22 04:05:57'),
(163, 15, 5, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-22 04:06:33'),
(164, 15, 24, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-22 04:07:15'),
(165, 15, 4, 'DICTAMEN', 'Voto Proyecto: Aprobado', '2026-01-22 04:08:43'),
(166, 15, 45, 'SUBIDA', 'Requisitos de Grado', '2026-01-22 04:09:19'),
(167, 15, 45, 'SUBIDA', 'Requisitos de Grado', '2026-01-22 04:09:20'),
(168, 15, 8, 'AUTORIZACIÓN', 'Se autorizó la subida del borrador de tesis', '2026-01-22 04:10:24'),
(169, 15, 45, 'SUBIDA', 'Borrador de Tesis', '2026-01-22 04:15:30'),
(170, 15, 45, 'SUBIDA', 'Corrección de Borrador', '2026-01-22 04:20:33'),
(171, 15, 45, 'SUBIDA', 'Corrección de Borrador', '2026-01-22 04:27:35'),
(172, 15, 24, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-22 04:28:11'),
(173, 15, 4, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-22 04:28:56'),
(174, 15, 5, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-22 04:30:01'),
(175, 15, 25, 'DICTAMEN', 'Voto Borrador: Aprobado', '2026-01-22 04:30:39'),
(176, 15, 45, 'SUBIDA', 'Requisitos de Sustentacion', '2026-01-22 04:31:23'),
(177, 15, 25, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-22 04:33:29'),
(178, 15, 4, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-22 04:34:48'),
(179, 15, 5, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-22 04:35:32'),
(180, 15, 24, 'DICTAMEN', 'Voto Sustentacion: Aprobado', '2026-01-22 04:36:23');

-- --------------------------------------------------------

--
-- Table structure for table `jurado_asignaciones`
--

CREATE TABLE `jurado_asignaciones` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_proyecto` int(10) UNSIGNED NOT NULL,
  `id_jurado` int(10) UNSIGNED NOT NULL,
  `rol_jurado` varchar(50) NOT NULL,
  `fecha_asignacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jurado_asignaciones`
--

INSERT INTO `jurado_asignaciones` (`id`, `id_proyecto`, `id_jurado`, `rol_jurado`, `fecha_asignacion`) VALUES
(7, 2, 5, 'Presidente', '2025-11-18 07:56:07'),
(8, 2, 3, 'Primer miembro', '2025-11-18 07:56:07'),
(9, 2, 6, 'Segundo miembro', '2025-11-18 07:56:07'),
(10, 3, 22, 'Presidente', '2025-11-19 21:34:37'),
(11, 3, 12, 'Primer miembro', '2025-11-19 21:34:37'),
(12, 3, 13, 'Segundo miembro', '2025-11-19 21:34:37'),
(13, 1, 4, 'Presidente', '2025-11-22 04:51:29'),
(14, 1, 5, 'Primer miembro', '2025-11-22 04:51:29'),
(15, 1, 3, 'Segundo miembro', '2025-11-22 04:51:29'),
(16, 4, 6, 'Presidente', '2025-11-22 06:54:23'),
(17, 4, 5, 'Primer miembro', '2025-11-22 06:54:23'),
(18, 4, 24, 'Segundo miembro', '2025-11-22 06:54:23'),
(19, 5, 3, 'Presidente', '2025-11-27 01:49:55'),
(20, 5, 4, 'Primer miembro', '2025-11-27 01:49:55'),
(21, 5, 28, 'Segundo miembro', '2025-11-27 01:49:55'),
(22, 8, 4, 'Presidente', '2026-01-05 05:16:29'),
(23, 8, 28, 'Primer miembro', '2026-01-05 05:16:29'),
(24, 8, 5, 'Segundo miembro', '2026-01-05 05:16:29'),
(31, 9, 28, 'Presidente', '2026-01-13 08:03:21'),
(32, 9, 5, 'Primer miembro', '2026-01-13 08:03:21'),
(33, 9, 3, 'Segundo miembro', '2026-01-13 08:03:21'),
(34, 10, 4, 'Presidente', '2026-01-18 21:14:10'),
(35, 10, 28, 'Primer miembro', '2026-01-18 21:14:10'),
(36, 10, 5, 'Segundo miembro', '2026-01-18 21:14:10'),
(37, 11, 4, 'Presidente', '2026-01-19 21:06:42'),
(38, 11, 28, 'Primer miembro', '2026-01-19 21:06:42'),
(39, 11, 3, 'Segundo miembro', '2026-01-19 21:06:42'),
(40, 12, 4, 'Presidente', '2026-01-20 19:26:49'),
(41, 12, 5, 'Primer miembro', '2026-01-20 19:26:49'),
(42, 12, 3, 'Segundo miembro', '2026-01-20 19:26:49'),
(43, 13, 4, 'Presidente', '2026-01-21 19:13:10'),
(44, 13, 5, 'Primer miembro', '2026-01-21 19:13:10'),
(45, 13, 3, 'Segundo miembro', '2026-01-21 19:13:10'),
(46, 14, 4, 'Presidente', '2026-01-22 01:57:44'),
(47, 14, 28, 'Primer miembro', '2026-01-22 01:57:44'),
(48, 14, 3, 'Segundo miembro', '2026-01-22 01:57:44'),
(49, 15, 4, 'Presidente', '2026-01-22 03:58:54'),
(50, 15, 24, 'Primer miembro', '2026-01-22 03:58:54'),
(51, 15, 5, 'Segundo miembro', '2026-01-22 03:58:54');

-- --------------------------------------------------------

--
-- Table structure for table `lineas_investigacion_v2`
--

CREATE TABLE `lineas_investigacion_v2` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_area` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lineas_investigacion_v2`
--

INSERT INTO `lineas_investigacion_v2` (`id`, `id_area`, `nombre`) VALUES
(1, 1, 'Derecho'),
(3, 1, 'Perspectivas teóricas de la educación');

-- --------------------------------------------------------

--
-- Table structure for table `logs_sistema`
--

CREATE TABLE `logs_sistema` (
  `id` int(11) NOT NULL,
  `accion` varchar(50) DEFAULT NULL,
  `detalle` text DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_remitente` int(10) UNSIGNED NOT NULL,
  `id_proyecto_relacionado` int(10) UNSIGNED DEFAULT NULL,
  `asunto` varchar(255) NOT NULL,
  `cuerpo` longtext NOT NULL,
  `fecha_envio` timestamp NULL DEFAULT current_timestamp(),
  `tipo_mensaje` enum('Normal','Notificacion') DEFAULT 'Normal',
  `estado` enum('Enviado','Borrador') DEFAULT 'Enviado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mensajes`
--

INSERT INTO `mensajes` (`id`, `id_remitente`, `id_proyecto_relacionado`, `asunto`, `cuerpo`, `fecha_envio`, `tipo_mensaje`, `estado`) VALUES
(1, 1, NULL, 'Prueba de mensaje', 'Este es el primer mensaje con un archivo adjunto.', '2026-01-05 19:56:22', 'Normal', 'Enviado'),
(2, 1, NULL, 'Prueba 2', 'Mensaje 2 con un archivo adjunto.', '2026-01-05 20:26:53', 'Normal', 'Enviado'),
(3, 1, NULL, 'Prueba 3', 'Contenido del mensaje 3 con adjunto.', '2026-01-05 20:52:20', 'Normal', 'Enviado'),
(4, 1, NULL, 'Prueba 8', 'Mensaje de prueba 8', '2026-01-07 06:26:00', 'Normal', 'Enviado'),
(5, 1, NULL, 'Prueba 9', 'Mensaje', '2026-01-07 06:28:12', 'Normal', 'Enviado'),
(6, 1, NULL, 'Prueba 10', 'Mensaje de prueba 10', '2026-01-07 06:36:49', 'Normal', 'Enviado'),
(7, 1, NULL, 'Prueba 11', 'Mensaje con smtp externo.', '2026-01-07 06:49:46', 'Normal', 'Enviado'),
(8, 1, NULL, 'Prueba 12', 'Smtp interno', '2026-01-07 06:51:25', 'Normal', 'Enviado'),
(9, 34, NULL, 'Prueba', 'Mensaje de prueba', '2026-01-11 05:16:51', 'Normal', 'Enviado'),
(10, 8, NULL, 'Prueba 13', 'Mensaje', '2026-01-11 06:04:26', 'Normal', 'Enviado'),
(11, 34, NULL, 'Prueba 2', 'Mensaje', '2026-01-11 06:06:59', 'Normal', 'Enviado'),
(12, 8, NULL, 'Corregir formato', 'Estimado Pedro, falta aplicar el formato de proyecto de tesis. Me envia por este medio el archivo correcto para reemplazar.', '2026-01-13 06:30:24', 'Normal', 'Enviado'),
(13, 34, NULL, 'Subsano observación', 'Adjunto archivos de proyectos adecuados al formato.', '2026-01-13 06:32:54', 'Normal', 'Enviado'),
(14, 8, NULL, 'Observaciones', 'Falta las numeración de lineas, cuando se corrija se procederá al sorteo de jurados.', '2026-01-18 20:32:33', 'Normal', 'Enviado'),
(15, 37, NULL, 'Subsano observación', 'Remito archivos corregidos. Solicito sorteo de jurados.', '2026-01-18 20:38:13', 'Normal', 'Enviado'),
(16, 8, NULL, 'Observación preliminar al proyecto', 'Prueba, corregir el proyecto, aplicar formato.', '2026-01-19 20:59:15', 'Normal', 'Enviado'),
(17, 41, NULL, 'Subsano observación preliminar', 'Remito dos archivos Word y PDF.', '2026-01-19 21:04:40', 'Normal', 'Enviado'),
(18, 8, NULL, 'Re: Subsano observación preliminar', 'Ok. Procedí al sorteo de jurados.', '2026-01-19 23:12:21', 'Normal', 'Enviado'),
(19, 1, NULL, 'Prueba', 'Este es el contenido del mensaje.', '2026-01-20 00:02:12', 'Normal', 'Enviado'),
(20, 8, NULL, 'Observación del formato', 'Aplicar formato al proyecto.', '2026-01-20 19:17:28', 'Normal', 'Enviado'),
(21, 42, NULL, 'Re: Observación del formato', 'Remito proyecto con el formato.', '2026-01-20 19:22:10', 'Normal', 'Enviado'),
(22, 8, NULL, 'Aplicar formato', 'Aplicar formato al proyecto', '2026-01-21 19:05:19', 'Normal', 'Enviado'),
(23, 43, NULL, 'Re: Aplicar formato', 'Remito proyecto según formato.', '2026-01-21 19:10:07', 'Normal', 'Enviado'),
(24, 8, NULL, 'Re: Aplicar formato', 'Ok. Se procedió con el sorteo de jurados.', '2026-01-21 19:19:18', 'Normal', 'Enviado'),
(25, 8, NULL, 'Aplicar formato.', 'Mensaje de prueba', '2026-01-22 01:40:29', 'Normal', 'Enviado'),
(26, 44, NULL, 'Re: Aplicar formato.', 'Remito archivos corregidos.', '2026-01-22 01:56:38', 'Normal', 'Enviado'),
(27, 8, NULL, 'Corregir formato', 'Mensaje de prueba', '2026-01-22 03:57:13', 'Normal', 'Enviado'),
(28, 45, NULL, 'Re: Corregir formato', 'Listo con archivos adjuntos.', '2026-01-22 04:00:19', 'Normal', 'Enviado');

-- --------------------------------------------------------

--
-- Table structure for table `mensaje_adjuntos`
--

CREATE TABLE `mensaje_adjuntos` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_mensaje` int(10) UNSIGNED NOT NULL,
  `nombre_original` varchar(255) NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `mime_type` varchar(100) DEFAULT 'application/octet-stream'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mensaje_adjuntos`
--

INSERT INTO `mensaje_adjuntos` (`id`, `id_mensaje`, `nombre_original`, `ruta_archivo`, `mime_type`) VALUES
(1, 1, 'prueba.pdf', 'uploads/mensajes/1767642982_prueba.pdf', 'application/octet-stream'),
(2, 2, 'prueba2.pdf', 'uploads/mensajes_secure/a484f7d0d4b089b96c89f26a81454c8b366664200c70a90f273fcf209c5e9f38', 'application/pdf'),
(3, 3, 'prueba3.pdf', 'uploads/mensajes_secure/bbd583c5cb8f7a869c550f8d668c2446d962360dd3f03345cb674a5fff1b2a5e', 'application/pdf'),
(4, 6, 'respuestaprueba.pdf', 'uploads/mensajes_secure/af9ce2cb0f4ceccd9ef861f9768c1eb7598f671a0f2601d1b61ed71d6a4b7240', 'application/pdf'),
(5, 6, 'prueba4.pdf', 'uploads/mensajes_secure/e7d2810c4fc4788590a1274b6c1c6e6b40a09553e6c61e215ef6958ef4997e58', 'application/pdf'),
(6, 9, 'respuestaprueba.pdf', 'uploads/mensajes_secure/6212b31772be8ac1a9215fade629019fa9c45f3c19f2376172770c7d702c76f9', 'application/pdf'),
(7, 9, 'prueba4.pdf', 'uploads/mensajes_secure/36920780c94b84891733ed3faeae8e18fc9a2fe9610cba803a055efe9fdd2b04', 'application/pdf'),
(8, 10, 'prueba4.pdf', 'uploads/mensajes_secure/b2a818a0a2fb0692902f971f91a0506666218839c9f65cf2df2286caad0077b7', 'application/pdf'),
(9, 11, 'prueba4.pdf', 'uploads/mensajes_secure/7a1daad3532c4fbda4a5bedf6a7ea618059c5e3e3d52ff48dcc1618b59956662', 'application/pdf'),
(10, 13, 'Proyecto de Pedro Sexto ajsutado.pdf', 'uploads/mensajes_secure/7657204401aaef9d1cd80fa142e3620d2a2311e973172f147b1512710b575281', 'application/pdf'),
(11, 13, 'Proyecto de Pedro Sexto ajsutado.docx', 'uploads/mensajes_secure/d2ded0ad1921033242c277a8223991f236f7f9fa1fa69578be45bf5af3946274', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
(12, 15, 'Proyecto de Pedro Octavo.docx', 'uploads/mensajes_secure/82e54777cd8e9764f0be3c61ddfe793b790ad9a55656ebe4bfa2678c4826232a', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
(13, 15, 'Proyecto de Pedro Octavo.pdf', 'uploads/mensajes_secure/d6ab20d59c1048282406daee335fbd5618d1de3b53ab79c592994902faef46dd', 'application/pdf'),
(14, 16, 'adjuntoprueba.pdf', 'uploads/mensajes_secure/6713ff2db66fc19f078136b91c4f8342d30b9df0126ddd1900e79976f24fcb1b', 'application/pdf'),
(15, 17, 'Proyecto investigación jurídica de Pedro Doce.docx', 'uploads/mensajes_secure/4582ee62eece5095ffdb2d0ecc79a3f45af48b1e7e42fa926a307e51353420de', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
(16, 17, 'Proyecto investigación jurídica de Pedro Doce.pdf', 'uploads/mensajes_secure/7913da6de97bcbc87b6228fe5f5b9b1683b6f5d67e4a4029bda7a6d2d4494987', 'application/pdf'),
(17, 18, 'adjuntoprueba.pdf', 'uploads/mensajes_secure/34852ad78b3b1867918ce2debd4a303540a1d4e2b8d4e15495c3a220081ebe9d', 'application/pdf'),
(18, 19, 'adjuntoprueba.pdf', 'uploads/mensajes_secure/40ea3fe08c3dba2ef1b7c3707b508f14236813279828520c12b993b53c55de72', 'application/pdf'),
(19, 21, 'Proyecto Pedro Trece y Once.pdf', 'uploads/mensajes_secure/100d004c898e12fd31430f6fb884f1c8e95c5872d13a3821974ce80d90bf1feb', 'application/pdf'),
(20, 21, 'Proyecto Pedro Trece y Once.docx', 'uploads/mensajes_secure/b2d29046a8cbfba08cc83acb436c2fe446e6def8b076c11e56fa30c2172503a5', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
(21, 23, 'Proyecto de Pedro Catorce.docx', 'uploads/mensajes_secure/e3713f8522639803413c5e37c732780d81e402318e0beb4112c8bf44ff608782', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
(22, 23, 'Proyecto de Pedro Catorce.pdf', 'uploads/mensajes_secure/4c4e3ae05dec64d1049907eacf89240c271fd255d622a4b82e525bf8c81fa0ae', 'application/pdf'),
(23, 26, 'Proyecto de Pedro 15.docx', 'uploads/mensajes_secure/657f5e01eb92bd5be6bc4460a93a44e4b55267cd103ec1e2f5ed3b9192588980', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
(24, 26, 'Proyecto de Pedro 15.pdf', 'uploads/mensajes_secure/cfed436a417179f61ed1c453200b3de8217ad3cebc5064934c31d5f40127b970', 'application/pdf'),
(25, 28, 'Proyecto de Pedro 15.pdf', 'uploads/mensajes_secure/c0f32cbd36f6638d7200f136060a84330e68f00bc7406ebe02077e0d01f38eaa', 'application/pdf'),
(26, 28, 'Proyecto de Pedro 15.docx', 'uploads/mensajes_secure/af9d0a39f3a134cb2a0cae1410fd0e73fb7cae43ccaf0e98799809d9660a85cc', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

-- --------------------------------------------------------

--
-- Table structure for table `mensaje_destinatarios`
--

CREATE TABLE `mensaje_destinatarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_mensaje` int(10) UNSIGNED NOT NULL,
  `id_destinatario` int(10) UNSIGNED NOT NULL,
  `leido` tinyint(1) DEFAULT 0,
  `fecha_lectura` timestamp NULL DEFAULT NULL,
  `carpeta` enum('Entrada','Papelera','Eliminado') DEFAULT 'Entrada',
  `borrado_papelera_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mensaje_destinatarios`
--

INSERT INTO `mensaje_destinatarios` (`id`, `id_mensaje`, `id_destinatario`, `leido`, `fecha_lectura`, `carpeta`, `borrado_papelera_at`) VALUES
(1, 1, 3, 1, '2026-01-05 20:30:18', 'Entrada', NULL),
(2, 2, 3, 1, '2026-01-05 20:27:58', 'Entrada', NULL),
(3, 3, 3, 1, '2026-01-07 06:27:20', 'Entrada', NULL),
(4, 4, 3, 1, '2026-01-07 06:27:28', 'Entrada', NULL),
(5, 5, 3, 1, '2026-01-07 06:37:09', 'Entrada', NULL),
(6, 6, 3, 1, '2026-01-07 06:37:12', 'Entrada', NULL),
(7, 7, 3, 1, '2026-01-07 06:50:08', 'Entrada', NULL),
(8, 8, 3, 1, '2026-01-07 06:51:36', 'Entrada', NULL),
(9, 9, 8, 1, '2026-01-11 05:17:58', 'Entrada', NULL),
(10, 10, 3, 1, '2026-01-11 06:05:29', 'Entrada', NULL),
(11, 11, 8, 1, '2026-01-11 06:07:11', 'Entrada', NULL),
(12, 12, 34, 1, '2026-01-13 06:31:08', 'Entrada', NULL),
(13, 13, 8, 1, '2026-01-13 06:34:08', 'Entrada', NULL),
(14, 14, 37, 1, '2026-01-18 20:35:07', 'Entrada', NULL),
(15, 14, 38, 0, NULL, 'Entrada', NULL),
(16, 14, 3, 1, '2026-01-20 00:35:57', 'Entrada', NULL),
(17, 15, 8, 1, '2026-01-18 20:38:37', 'Entrada', NULL),
(18, 16, 41, 1, '2026-01-19 21:00:17', 'Entrada', NULL),
(19, 16, 3, 1, '2026-01-20 00:36:14', 'Entrada', NULL),
(20, 17, 8, 1, '2026-01-19 21:05:07', 'Entrada', NULL),
(21, 18, 41, 1, '2026-01-19 23:15:30', 'Entrada', NULL),
(22, 19, 3, 1, '2026-01-20 00:36:24', 'Entrada', NULL),
(23, 20, 42, 1, '2026-01-20 19:20:53', 'Entrada', NULL),
(24, 20, 3, 0, NULL, 'Entrada', NULL),
(25, 21, 8, 1, '2026-01-20 19:24:12', 'Entrada', NULL),
(26, 22, 43, 1, '2026-01-21 19:09:03', 'Entrada', NULL),
(27, 22, 28, 0, NULL, 'Entrada', NULL),
(28, 23, 8, 1, '2026-01-21 19:10:17', 'Entrada', NULL),
(29, 24, 43, 1, '2026-01-21 19:19:23', 'Entrada', NULL),
(30, 25, 44, 1, '2026-01-22 01:55:35', 'Entrada', NULL),
(31, 25, 5, 0, NULL, 'Entrada', NULL),
(32, 26, 8, 1, '2026-01-22 01:56:48', 'Entrada', NULL),
(33, 27, 45, 1, '2026-01-22 03:59:49', 'Entrada', NULL),
(34, 27, 25, 0, NULL, 'Entrada', NULL),
(35, 28, 8, 1, '2026-01-22 04:00:49', 'Entrada', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `observaciones`
--

CREATE TABLE `observaciones` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_proyecto` int(10) UNSIGNED NOT NULL,
  `id_jurado` int(10) UNSIGNED NOT NULL,
  `rol_autor` varchar(50) DEFAULT 'Jurado',
  `pagina` int(10) UNSIGNED DEFAULT 1,
  `tipo_observacion` varchar(50) DEFAULT 'Borrador',
  `observacion_texto` text DEFAULT NULL,
  `fecha_observacion` timestamp NULL DEFAULT current_timestamp(),
  `es_antiguo` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `observaciones`
--

INSERT INTO `observaciones` (`id`, `id_proyecto`, `id_jurado`, `rol_autor`, `pagina`, `tipo_observacion`, `observacion_texto`, `fecha_observacion`, `es_antiguo`) VALUES
(1, 2, 4, 'Asesor', 1, 'Proyecto', 'Falta ingresar el titulo completo', '2025-11-18 07:53:07', 0),
(2, 2, 5, 'Jurado', 8, 'Proyecto', 'Mejorar el resumen.', '2025-11-18 07:57:20', 0),
(3, 2, 5, 'Jurado', 1, 'Proyecto', 'Ingresar el titulo completo.', '2025-11-18 22:40:34', 0),
(4, 2, 3, 'Jurado', 2, 'Proyecto', 'Falta la firma del asesor.', '2025-11-19 06:38:51', 0),
(5, 2, 3, 'Jurado', 9, 'Proyecto', 'Keyword en orden alfabetico.', '2025-11-19 06:39:22', 0),
(6, 2, 6, 'Jurado', 1, 'Proyecto', 'titulo incompleto', '2025-11-19 21:17:43', 0),
(7, 3, 17, 'Jurado', 4, 'Borrador', 'el escaneado mas nítido', '2025-11-19 21:53:05', 1),
(8, 3, 10, 'Jurado', 4, 'Borrador', 'mejorar el escaneado', '2025-11-19 21:55:04', 1),
(9, 3, 17, 'Jurado', 4, 'Borrador', 'Mejora la calidad del escaneado. Está recortado de un costado.', '2025-11-20 03:53:07', 0),
(10, 3, 13, 'Jurado', 4, 'Borrador', 'Opaco.', '2025-11-20 03:54:59', 0),
(11, 1, 3, 'Jurado', 1, 'Borrador', 'No cumple con la estructura.', '2025-11-22 04:56:10', 0),
(12, 1, 3, 'Jurado', 2, 'Borrador', 'falta contenido, solo dice 2.', '2025-11-22 05:11:52', 0),
(13, 1, 5, 'Jurado', 1, 'Borrador', 'Falta título correcto', '2025-11-23 06:04:50', 0),
(14, 4, 24, 'Jurado', 1, 'Borrador', 'Falta aplicar el formato del borrador.', '2025-11-24 03:14:33', 1),
(15, 5, 3, 'Jurado', 1, 'Proyecto', 'Sin correcciones.', '2025-11-27 02:18:31', 0),
(16, 5, 3, 'Jurado', 2, 'Proyecto', 'Sin correcciones.', '2025-11-27 02:18:43', 0),
(17, 1, 4, 'Jurado', 1, 'Proyecto', 'Sin correcciones.', '2025-11-27 02:30:20', 0),
(18, 5, 4, 'Jurado', 1, 'Proyecto', 'Mejorar titulo.', '2025-11-27 02:30:49', 0),
(19, 5, 28, 'Jurado', 1, 'Proyecto', 'Sin observaciones.', '2025-11-27 02:42:43', 0),
(20, 1, 5, 'Jurado', 1, 'Proyecto', 'Sin observaciones.', '2025-11-27 02:48:41', 0),
(21, 5, 5, 'Jurado', 1, 'Proyecto', 'Sin observaciones.', '2025-11-28 04:19:33', 0),
(23, 4, 5, 'Jurado', 1, 'Proyecto', 'Sin observaciones.', '2025-11-28 19:13:35', 0),
(24, 4, 6, 'Jurado', 1, 'Proyecto', 'Sin observaciones.', '2025-11-28 19:38:27', 0),
(25, 4, 24, 'Jurado', 1, 'Proyecto', 'Sin observaciones.', '2025-11-28 20:48:29', 0),
(26, 4, 5, 'Jurado', 1, 'Proyecto', 'Sin observaciones.', '2025-11-28 20:55:10', 0),
(27, 4, 6, 'Jurado', 1, 'Proyecto', 'Sin observaciones.', '2025-11-28 20:58:35', 0),
(28, 4, 4, 'Jurado', 1, 'Proyecto', 'Sin observaciones.', '2025-11-28 22:17:39', 0),
(29, 4, 6, 'Jurado', 1, 'Borrador', 'Sin observaciones.', '2025-11-28 22:54:59', 0),
(30, 4, 5, 'Jurado', 1, 'Borrador', 'Falta cumplir con la estructura.', '2025-11-29 03:26:39', 0),
(31, 4, 6, 'Jurado', 1, 'Borrador', 'Mejorar.', '2025-11-29 04:19:00', 0),
(32, 4, 5, 'Jurado', 1, 'Borrador', 'Falta resumen.', '2025-11-29 04:28:53', 0),
(33, 4, 5, 'Jurado', 1, 'Borrador', 'Nombre en mayusculas.', '2025-11-29 04:59:51', 0),
(34, 4, 5, 'Jurado', 1, 'Borrador', 'No hay otras observaciones relevantes.', '2025-11-29 05:08:11', 0),
(35, 4, 24, 'Jurado', 1, 'Borrador', 'Sin correciones.', '2025-11-29 05:23:21', 0),
(36, 4, 6, 'Jurado', 1, 'Borrador', 'Mejorar la redacción (en la sustentación)', '2025-11-29 06:26:33', 0),
(37, 4, 5, 'Jurado', 1, 'Borrador', 'Sin observaciones en la sustentación.', '2025-11-29 06:35:37', 0),
(38, 4, 4, 'Jurado', 2, 'Borrador', 'Sin observaciones a la sustentación.', '2025-11-30 04:34:53', 0),
(39, 4, 24, 'Jurado', 2, 'Borrador', 'Sin observaciones en la sustentación.', '2025-11-30 04:35:56', 0),
(40, 9, 28, 'Jurado', 1, 'Proyecto', 'Falta mas contenido según formato.', '2026-01-13 08:05:16', 0),
(41, 9, 5, 'Jurado', 1, 'Proyecto', 'Sin correciones.', '2026-01-13 08:07:55', 0),
(42, 9, 3, 'Jurado', 2, 'Proyecto', 'Sin observaciones en la segunda página.', '2026-01-13 08:09:05', 0),
(43, 8, 4, 'Jurado', 1, 'Proyecto', 'Sin comentarios.', '2026-01-13 08:09:54', 0),
(44, 9, 28, 'Jurado', 1, 'Borrador', 'Mi observación.', '2026-01-14 23:06:18', 1),
(45, 9, 5, 'Jurado', 1, 'Borrador', 'Segunda observación', '2026-01-14 23:09:05', 1),
(46, 9, 3, 'Jurado', 1, 'Borrador', 'Tercera observación.', '2026-01-14 23:13:05', 1),
(47, 9, 4, 'Jurado', 1, 'Borrador', 'Sin correcciones 2025.', '2026-01-15 00:07:56', 1),
(48, 9, 4, 'Jurado', 1, 'Borrador', 'Sin observaciones 2026', '2026-01-15 00:42:48', 1),
(49, 9, 3, 'Jurado', 1, 'Borrador', 'Sin obs.', '2026-01-15 01:06:07', 1),
(51, 9, 28, 'Jurado', 1, 'Borrador', 'Falta numeración de líneas.', '2026-01-18 02:01:26', 0),
(52, 9, 28, 'Jurado', 1, 'Borrador', 'Observación del jurado Carlos.', '2026-01-18 03:44:22', 0),
(54, 9, 5, 'Jurado', 1, 'Borrador', 'Sin correcciones de Francisco Tipula.', '2026-01-18 04:34:16', 0),
(55, 10, 4, 'Jurado', 1, 'Proyecto', 'Es mi primera observación de J. Pineda.', '2026-01-18 21:32:06', 1),
(56, 10, 5, 'Jurado', 1, 'Proyecto', 'Es mi primera observación de F. Tipula.', '2026-01-18 22:35:28', 1),
(57, 10, 28, 'Jurado', 1, 'Proyecto', 'Es mi primera observación de C. Ramirez.', '2026-01-18 22:42:15', 1),
(58, 10, 3, 'Jurado', 1, 'Proyecto', 'Es mi primera observación de M. Espinoza.', '2026-01-18 22:48:03', 1),
(59, 10, 4, 'Jurado', 1, 'Proyecto', 'Sin correcciones.', '2026-01-19 01:46:34', 0),
(60, 8, 5, 'Jurado', 1, 'Proyecto', 'Sin observaciones.', '2026-01-19 02:14:48', 0),
(61, 10, 5, 'Jurado', 1, 'Proyecto', 'Sin correciones.', '2026-01-19 02:15:19', 0),
(62, 10, 5, 'Jurado', 1, 'Proyecto', 'Sin correcciones 2.', '2026-01-19 02:26:48', 0),
(63, 10, 28, 'Jurado', 1, 'Proyecto', 'Sin correciones. Carlos.', '2026-01-19 02:34:56', 0),
(64, 10, 28, 'Jurado', 1, 'Proyecto', 'Sin correciones 2. Carlos.\n', '2026-01-19 02:48:59', 0),
(65, 10, 3, 'Jurado', 1, 'Proyecto', 'Sin correcciones. Michael.', '2026-01-19 02:50:02', 0),
(66, 10, 4, 'Jurado', 1, 'Borrador', 'Mi primera observación al borrador. J. Pineda.', '2026-01-19 05:01:28', 1),
(67, 10, 5, 'Jurado', 1, 'Borrador', 'Mi primera observación al borrador. F. Tipula.', '2026-01-19 05:14:30', 1),
(68, 10, 28, 'Jurado', 1, 'Borrador', 'Mi primera observación al borrador. C. Ramirez.', '2026-01-19 05:17:32', 1),
(69, 10, 3, 'Jurado', 2, 'Borrador', 'Mi primera observación al borrador. M. Espinoza.', '2026-01-19 05:18:51', 1),
(70, 10, 3, 'Jurado', 1, 'Borrador', 'Sin correcciones Michael.', '2026-01-19 05:39:14', 0),
(71, 10, 4, 'Jurado', 1, 'Borrador', 'Sin correcciones José.', '2026-01-19 05:40:43', 0),
(72, 10, 5, 'Jurado', 1, 'Borrador', 'Sin correcciones Francisco.', '2026-01-19 05:42:15', 0),
(73, 10, 28, 'Jurado', 1, 'Borrador', 'Sin correcciones Carlos.', '2026-01-19 05:43:12', 0),
(74, 10, 4, 'Jurado', 1, 'Borrador', 'Sin correciones.', '2026-01-19 16:31:19', 0),
(75, 10, 4, 'Jurado', 1, 'Sustentacion', 'Sin correcciones J. Pineda en sustentación de tesis.', '2026-01-19 17:08:00', 0),
(76, 10, 5, 'Jurado', 1, 'Sustentacion', 'Sin correcciones F. Tipula en sustentación de tesis.', '2026-01-19 17:38:40', 0),
(77, 10, 28, 'Jurado', 1, 'Sustentacion', 'Sin correcciones C. Ramirez en sustentación de tesis.', '2026-01-19 18:09:16', 0),
(78, 10, 3, 'Jurado', 1, 'Sustentacion', 'Sin correcciones M. Espinoza en sustentación de tesis.', '2026-01-19 18:17:22', 0),
(79, 11, 4, 'Jurado', 1, 'Proyecto', 'Falta contenido.', '2026-01-20 00:28:02', 1),
(80, 11, 28, 'Jurado', 1, 'Proyecto', 'Sin correcciones al proyecto.', '2026-01-20 00:32:58', 1),
(81, 11, 3, 'Jurado', 1, 'Proyecto', 'Sin correcciones al proyecto. Michael.', '2026-01-20 00:34:44', 1),
(82, 11, 5, 'Jurado', 1, 'Proyecto', 'Sin correcciones al proyecto. Francisco.', '2026-01-20 00:37:27', 1),
(83, 11, 28, 'Jurado', 1, 'Proyecto', 'Sin correcciones al proyecto. Carlos.', '2026-01-20 02:32:59', 0),
(84, 11, 3, 'Jurado', 1, 'Proyecto', 'Sin correcciones al proyecto. Michael.', '2026-01-20 02:36:42', 0),
(85, 11, 4, 'Jurado', 1, 'Proyecto', 'Sin correcciones al proyecto. José.', '2026-01-20 02:38:01', 0),
(86, 11, 5, 'Jurado', 1, 'Proyecto', 'Sin correcciones al proyecto. Francisco.', '2026-01-20 02:40:02', 0),
(87, 11, 4, 'Jurado', 1, 'Borrador', 'Aplicar formato. José.', '2026-01-20 03:08:21', 1),
(88, 11, 28, 'Jurado', 1, 'Borrador', 'Sin observaciones. Carlos.', '2026-01-20 03:09:43', 1),
(89, 11, 3, 'Jurado', 1, 'Borrador', 'Sin observaciones. Michael.', '2026-01-20 03:11:05', 1),
(90, 11, 5, 'Jurado', 1, 'Borrador', 'Sin observaciones. F. Tipula.', '2026-01-20 03:13:08', 1),
(91, 11, 4, 'Jurado', 1, 'Borrador', 'Sin correcciones al borrador. J. Pineda.', '2026-01-20 03:57:54', 0),
(92, 11, 28, 'Jurado', 1, 'Borrador', 'Sin correcciones al borrador. Carlos.', '2026-01-20 04:06:50', 0),
(93, 11, 3, 'Jurado', 1, 'Borrador', 'Sin correcciones al borrador. Michael.', '2026-01-20 04:07:48', 0),
(94, 11, 5, 'Jurado', 1, 'Borrador', 'Sin correcciones al borrador. Francisco.', '2026-01-20 04:12:42', 0),
(95, 11, 4, 'Jurado', 1, 'Sustentacion', 'Sin observaciones a la sustentación. Pineda.', '2026-01-20 06:25:52', 0),
(96, 11, 28, 'Jurado', 1, 'Sustentacion', 'Sin observaciones a la sustentación. Ramirez.', '2026-01-20 06:28:15', 0),
(97, 11, 3, 'Jurado', 1, 'Sustentacion', 'Sin observaciones a la sustentación. Espinoza.', '2026-01-20 06:28:56', 0),
(98, 11, 5, 'Jurado', 1, 'Sustentacion', 'Sin observaciones a la sustentación. Tipula.', '2026-01-20 06:30:45', 0),
(99, 12, 4, 'Jurado', 1, 'Proyecto', 'Primera observación al proyecto. Pineda.', '2026-01-20 19:29:40', 1),
(100, 12, 5, 'Jurado', 1, 'Proyecto', 'Primera observación al proyecto. F. Tipula.', '2026-01-20 19:31:19', 1),
(101, 12, 3, 'Jurado', 1, 'Proyecto', 'Primera observación al proyecto. Espinoza.', '2026-01-20 19:32:14', 1),
(102, 12, 28, 'Jurado', 1, 'Proyecto', 'Primera observación al proyecto. Carlos (Asesor).', '2026-01-20 19:32:57', 1),
(103, 12, 4, 'Jurado', 1, 'Proyecto', 'Sin correciones. Pineda.', '2026-01-20 19:43:16', 0),
(104, 12, 5, 'Jurado', 1, 'Proyecto', 'Sin correcciones al proyecto corregido. Tipula.', '2026-01-20 19:44:15', 0),
(105, 12, 3, 'Jurado', 1, 'Proyecto', 'Sin correcciones al proyecto corregido. Espinoza.', '2026-01-20 19:45:50', 0),
(106, 8, 28, 'Jurado', 1, 'Proyecto', 'Sin correcciones al proyecto corregido. Carlos.', '2026-01-20 19:47:47', 0),
(107, 12, 28, 'Jurado', 1, 'Proyecto', 'Sin observación al proyecto. Carlos (Asesor).', '2026-01-20 19:49:46', 0),
(108, 12, 4, 'Jurado', 1, 'Borrador', 'Mi primera observación al borrador de tesis. Pineda.', '2026-01-20 23:53:00', 1),
(109, 12, 5, 'Jurado', 1, 'Borrador', 'Mi primera observación al borrador de tesis. Tipula.', '2026-01-20 23:59:24', 1),
(110, 12, 3, 'Jurado', 1, 'Borrador', 'Mi primera observación al borrador de tesis. Espinoza.', '2026-01-21 00:00:34', 1),
(111, 12, 28, 'Jurado', 1, 'Borrador', 'Sin observación al borrador de tesis. Ramirez.', '2026-01-21 00:04:58', 1),
(112, 12, 28, 'Jurado', 1, 'Borrador', 'Sin observación a la corrección del borrador de tesis. Ramirez.', '2026-01-21 01:16:47', 0),
(113, 12, 4, 'Jurado', 1, 'Borrador', 'Sin observación a la corrección del borrador de tesis. Pineda.', '2026-01-21 01:24:32', 0),
(114, 12, 5, 'Jurado', 1, 'Borrador', 'Sin observación a la corrección del borrador de tesis. Tipula.', '2026-01-21 01:25:38', 0),
(115, 12, 3, 'Jurado', 1, 'Borrador', 'Sin observación a la corrección del borrador de tesis. Espinoza.', '2026-01-21 01:26:12', 0),
(116, 12, 4, 'Jurado', 1, 'Sustentacion', 'Sin correcciones a la sustentación. Pineda.', '2026-01-21 02:34:48', 0),
(117, 12, 5, 'Jurado', 1, 'Sustentacion', 'Sin correcciones a la sustentación. Tipula.', '2026-01-21 02:37:48', 0),
(118, 12, 3, 'Jurado', 1, 'Sustentacion', 'Sin correcciones a la sustentación. Espinoza.', '2026-01-21 02:45:06', 0),
(119, 12, 28, 'Jurado', 1, 'Sustentacion', 'Sin correcciones a la sustentación. Ramirez.', '2026-01-21 02:47:12', 0),
(120, 13, 4, 'Jurado', 1, 'Proyecto', 'Primera corrección al proyecto. Pineda.', '2026-01-21 19:21:01', 1),
(121, 13, 5, 'Jurado', 1, 'Proyecto', 'Primera corrección al proyecto. Tipula.', '2026-01-21 19:23:10', 1),
(122, 13, 3, 'Jurado', 1, 'Proyecto', 'Primera corrección al proyecto. Espinoza.', '2026-01-21 19:23:43', 1),
(123, 13, 28, 'Jurado', 1, 'Proyecto', 'Primera corrección al proyecto. Ramirez.', '2026-01-21 19:24:40', 1),
(124, 13, 28, 'Jurado', 1, 'Proyecto', 'Sin corrección al proyecto corregido. Ramirez.', '2026-01-21 19:28:13', 0),
(125, 13, 3, 'Jurado', 1, 'Proyecto', 'Sin corrección al proyecto corregido. Espinoza.', '2026-01-21 19:33:00', 0),
(126, 13, 5, 'Jurado', 1, 'Proyecto', 'Sin corrección al proyecto corregido. Tipula.', '2026-01-21 19:34:05', 0),
(127, 13, 4, 'Jurado', 1, 'Proyecto', 'Sin corrección al proyecto corregido. Pineda.', '2026-01-21 19:36:07', 0),
(128, 14, 4, 'Jurado', 1, 'Proyecto', 'Primera observación al proyecto. Pineda.', '2026-01-22 02:06:49', 1),
(129, 14, 28, 'Jurado', 1, 'Proyecto', 'Primera observación al proyecto. Ramirez.', '2026-01-22 02:08:37', 1),
(130, 14, 3, 'Jurado', 1, 'Proyecto', 'Primera observación al proyecto. Espinoza.', '2026-01-22 02:11:25', 1),
(131, 14, 5, 'Jurado', 1, 'Proyecto', 'Primera observación al proyecto. Tipula.', '2026-01-22 02:14:10', 1),
(132, 14, 4, 'Jurado', 1, 'Proyecto', 'Sin observación a la corrección al proyecto. Pineda.', '2026-01-22 02:21:13', 0),
(133, 14, 3, 'Jurado', 1, 'Proyecto', 'Sin observación a la corrección al proyecto. Espinoza.', '2026-01-22 02:24:37', 0),
(134, 14, 28, 'Jurado', 1, 'Proyecto', 'Sin observación a la corrección al proyecto. Ramirez.', '2026-01-22 02:26:38', 0),
(135, 14, 5, 'Jurado', 1, 'Proyecto', 'Sin observación a la corrección al proyecto. Tipula.', '2026-01-22 02:28:08', 0),
(136, 14, 4, 'Jurado', 1, 'Borrador', 'Primera observación al borrador de tesis. Pineda.', '2026-01-22 02:43:15', 1),
(137, 14, 28, 'Jurado', 1, 'Borrador', 'Primera observación al borrador de tesis. Ramirez.', '2026-01-22 02:44:22', 1),
(138, 14, 3, 'Jurado', 1, 'Borrador', 'Primera observación al borrador de tesis. Espinoza.', '2026-01-22 02:44:55', 1),
(139, 14, 5, 'Jurado', 1, 'Borrador', 'Primera observación al borrador de tesis. Tipula.', '2026-01-22 02:45:56', 1),
(140, 14, 5, 'Jurado', 1, 'Borrador', 'Sin observación a las correcciones del borrador de tesis. Tipula.', '2026-01-22 02:51:33', 0),
(141, 14, 3, 'Jurado', 1, 'Borrador', 'Sin observación a las correcciones del borrador de tesis. Espinoza.', '2026-01-22 02:52:18', 0),
(142, 14, 28, 'Jurado', 1, 'Borrador', 'Sin observación a las correcciones del borrador de tesis. Ramirez.', '2026-01-22 02:53:29', 0),
(143, 14, 4, 'Jurado', 1, 'Borrador', 'Sin observación a las correcciones del borrador de tesis. Pineda.', '2026-01-22 02:54:21', 0),
(144, 14, 4, 'Jurado', 1, 'Sustentacion', 'Sin correcciones a la sustentación. Pineda.', '2026-01-22 03:07:21', 0),
(145, 14, 28, 'Jurado', 1, 'Sustentacion', 'Sin correcciones a la sustentación. Ramirez.', '2026-01-22 03:08:22', 0),
(146, 14, 3, 'Jurado', 1, 'Sustentacion', 'Sin correcciones a la sustentación. Espinoza.', '2026-01-22 03:10:40', 0),
(147, 14, 5, 'Jurado', 1, 'Sustentacion', 'Sin correcciones a la sustentación. Tipula.', '2026-01-22 03:16:12', 0),
(148, 15, 4, 'Jurado', 1, 'Proyecto', 'Primera observación.', '2026-01-22 04:02:24', 1),
(149, 15, 24, 'Jurado', 1, 'Proyecto', 'Primera observación.', '2026-01-22 04:03:23', 1),
(150, 15, 5, 'Jurado', 1, 'Proyecto', 'Primera observación.', '2026-01-22 04:03:55', 1),
(151, 15, 25, 'Jurado', 1, 'Proyecto', 'Primera observación.', '2026-01-22 04:04:30', 1),
(152, 15, 25, 'Jurado', 1, 'Proyecto', 'Sin correcciones.', '2026-01-22 04:05:47', 0),
(153, 15, 5, 'Jurado', 1, 'Proyecto', 'Sin correcciones.\n', '2026-01-22 04:06:27', 0),
(154, 15, 24, 'Jurado', 1, 'Proyecto', 'Sin correcciones.\n ', '2026-01-22 04:07:06', 0),
(155, 15, 4, 'Jurado', 1, 'Proyecto', 'Sin correcciones.', '2026-01-22 04:08:34', 0),
(156, 15, 4, 'Jurado', 1, 'Borrador', 'Primera observación.', '2026-01-22 04:17:33', 1),
(157, 15, 24, 'Jurado', 1, 'Borrador', 'Primera observación.\n', '2026-01-22 04:18:38', 1),
(158, 15, 5, 'Jurado', 1, 'Borrador', 'Primera observación.\n', '2026-01-22 04:19:12', 1),
(159, 15, 25, 'Jurado', 1, 'Borrador', 'Primera observación.\n', '2026-01-22 04:20:05', 1),
(160, 15, 24, 'Jurado', 1, 'Borrador', 'Sin correcciones.', '2026-01-22 04:27:58', 0),
(161, 15, 4, 'Jurado', 1, 'Borrador', 'Sin correcciones.\n', '2026-01-22 04:28:39', 0),
(162, 15, 5, 'Jurado', 1, 'Borrador', 'Sin correcciones.\n', '2026-01-22 04:29:50', 0),
(163, 15, 25, 'Jurado', 1, 'Borrador', 'Sin correcciones.\n', '2026-01-22 04:30:30', 0),
(164, 15, 25, 'Jurado', 1, 'Sustentacion', 'Sin correcciones.', '2026-01-22 04:33:15', 0),
(165, 15, 4, 'Jurado', 1, 'Sustentacion', 'Sin correcciones.\n', '2026-01-22 04:34:23', 0),
(166, 15, 5, 'Jurado', 1, 'Sustentacion', 'Sin correcciones.\n', '2026-01-22 04:35:19', 0),
(167, 15, 24, 'Jurado', 1, 'Sustentacion', 'Sin correcciones.\n', '2026-01-22 04:36:08', 0);

-- --------------------------------------------------------

--
-- Table structure for table `observaciones_finalizadas`
--

CREATE TABLE `observaciones_finalizadas` (
  `id` int(11) NOT NULL,
  `id_proyecto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `etapa` varchar(50) NOT NULL,
  `fecha_finalizacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `observaciones_finalizadas`
--

INSERT INTO `observaciones_finalizadas` (`id`, `id_proyecto`, `id_usuario`, `etapa`, `fecha_finalizacion`) VALUES
(1, 9, 4, 'Borrador', '2026-01-15 01:04:25'),
(2, 9, 3, 'Borrador', '2026-01-15 01:06:24'),
(3, 9, 28, 'Borrador', '2026-01-18 04:28:57'),
(4, 9, 5, 'Borrador', '2026-01-18 04:34:20'),
(12, 8, 5, 'Proyecto', '2026-01-19 02:14:55'),
(16, 10, 28, 'Proyecto', '2026-01-19 02:49:03'),
(17, 10, 3, 'Proyecto', '2026-01-19 02:50:32'),
(18, 10, 5, 'Proyecto', '2026-01-19 02:51:54'),
(23, 10, 3, 'Borrador', '2026-01-19 05:39:20'),
(24, 10, 4, 'Borrador', '2026-01-19 05:40:47'),
(25, 10, 5, 'Borrador', '2026-01-19 05:42:18'),
(26, 10, 28, 'Borrador', '2026-01-19 05:43:16'),
(27, 10, 4, 'Sustentacion', '2026-01-19 17:08:24'),
(28, 10, 5, 'Sustentacion', '2026-01-19 17:38:45'),
(29, 10, 28, 'Sustentacion', '2026-01-19 18:09:33'),
(30, 10, 3, 'Sustentacion', '2026-01-19 18:17:27'),
(31, 1, 5, 'Proyecto', '2026-01-19 18:31:11'),
(36, 11, 28, 'Proyecto', '2026-01-20 02:33:04'),
(37, 11, 3, 'Proyecto', '2026-01-20 02:36:46'),
(38, 11, 4, 'Proyecto', '2026-01-20 02:38:05'),
(39, 11, 5, 'Proyecto', '2026-01-20 02:42:14'),
(44, 11, 4, 'Borrador', '2026-01-20 03:58:00'),
(45, 11, 28, 'Borrador', '2026-01-20 04:06:54'),
(46, 11, 3, 'Borrador', '2026-01-20 04:07:52'),
(47, 11, 5, 'Borrador', '2026-01-20 04:12:45'),
(48, 11, 4, 'Sustentacion', '2026-01-20 06:25:56'),
(49, 11, 28, 'Sustentacion', '2026-01-20 06:28:20'),
(50, 11, 3, 'Sustentacion', '2026-01-20 06:29:00'),
(51, 11, 5, 'Sustentacion', '2026-01-20 06:30:48'),
(56, 12, 4, 'Proyecto', '2026-01-20 19:43:28'),
(57, 12, 5, 'Proyecto', '2026-01-20 19:44:24'),
(58, 12, 3, 'Proyecto', '2026-01-20 19:45:55'),
(59, 8, 28, 'Proyecto', '2026-01-20 19:47:51'),
(60, 12, 28, 'Proyecto', '2026-01-20 19:50:00'),
(65, 12, 28, 'Borrador', '2026-01-21 01:18:44'),
(66, 12, 4, 'Borrador', '2026-01-21 01:24:36'),
(67, 12, 5, 'Borrador', '2026-01-21 01:25:41'),
(68, 12, 3, 'Borrador', '2026-01-21 01:26:35'),
(69, 12, 4, 'Sustentacion', '2026-01-21 02:34:52'),
(70, 12, 5, 'Sustentacion', '2026-01-21 02:37:50'),
(71, 12, 3, 'Sustentacion', '2026-01-21 02:45:09'),
(72, 12, 28, 'Sustentacion', '2026-01-21 02:47:19'),
(77, 13, 28, 'Proyecto', '2026-01-21 19:28:29'),
(78, 13, 3, 'Proyecto', '2026-01-21 19:33:03'),
(79, 13, 5, 'Proyecto', '2026-01-21 19:34:08'),
(80, 13, 4, 'Proyecto', '2026-01-21 19:36:11'),
(85, 14, 4, 'Proyecto', '2026-01-22 02:21:17'),
(86, 14, 3, 'Proyecto', '2026-01-22 02:24:42'),
(87, 14, 28, 'Proyecto', '2026-01-22 02:26:44'),
(88, 14, 5, 'Proyecto', '2026-01-22 02:28:13'),
(93, 14, 5, 'Borrador', '2026-01-22 02:51:39'),
(94, 14, 3, 'Borrador', '2026-01-22 02:52:22'),
(95, 14, 28, 'Borrador', '2026-01-22 02:53:32'),
(96, 14, 4, 'Borrador', '2026-01-22 02:54:24'),
(97, 14, 4, 'Sustentacion', '2026-01-22 03:07:26'),
(98, 14, 28, 'Sustentacion', '2026-01-22 03:08:25'),
(99, 14, 3, 'Sustentacion', '2026-01-22 03:10:44'),
(100, 14, 5, 'Sustentacion', '2026-01-22 03:16:15'),
(105, 15, 25, 'Proyecto', '2026-01-22 04:05:50'),
(106, 15, 5, 'Proyecto', '2026-01-22 04:06:29'),
(107, 15, 24, 'Proyecto', '2026-01-22 04:07:10'),
(108, 15, 4, 'Proyecto', '2026-01-22 04:08:38'),
(117, 15, 24, 'Borrador', '2026-01-22 04:28:01'),
(118, 15, 4, 'Borrador', '2026-01-22 04:28:43'),
(119, 15, 5, 'Borrador', '2026-01-22 04:29:52'),
(120, 15, 25, 'Borrador', '2026-01-22 04:30:33'),
(121, 15, 25, 'Sustentacion', '2026-01-22 04:33:18'),
(122, 15, 4, 'Sustentacion', '2026-01-22 04:34:40'),
(123, 15, 5, 'Sustentacion', '2026-01-22 04:35:23'),
(124, 15, 24, 'Sustentacion', '2026-01-22 04:36:17');

-- --------------------------------------------------------

--
-- Table structure for table `plantillas`
--

CREATE TABLE `plantillas` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `asunto` varchar(200) DEFAULT NULL,
  `contenido` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plantillas`
--

INSERT INTO `plantillas` (`id`, `tipo`, `nombre`, `asunto`, `contenido`) VALUES
(1, 'Acta Proyecto', 'Acta Aprobación Proyecto', 'Acta aprobación de proyecto', '<div style=\"margin-left:0; margin-right:0\"><!-- CABECERA -->\r\n<p style=\"margin-left:0; margin-right:0; text-align:center\">[programa]</p>\r\n\r\n<p style=\"margin-left:0; margin-right:0; text-align:center\"><span style=\"font-size:11px\"><strong>COORDINACIÓN DE INVESTIGACIÓN DE LA UNIDAD DE SEGUNDA ESPECIALIDAD</strong></span></p>\r\n\r\n<h2 style=\"margin-left:0; margin-right:0; text-align:center\"><span style=\"font-size:12px\"><strong>ACTA DE REUNIÓN DE DICTAMEN DE PROYECTO DE INVESTIGACIÓN</strong></span></h2>\r\n\r\n<p style=\"margin-left:0; margin-right:0; text-align:justify\"><span style=\"font-size:10px; text-transform:uppercase\"><strong>SEÑOR DIRECTOR DE LA COORDINACIÓN DE INVESTIGACIÓN DE LA UNIDAD DE SEGUNDA ESPECIALIDAD DE LA [facultad] DE LA UNIVERSIDAD NACIONAL DEL ALTIPLANO DE PUNO</strong></span></p>\r\n<!-- CUERPO -->\r\n\r\n<p style=\"margin-left:0; margin-right:0; text-align:justify\"><span style=\"font-size:10px\">En mérito a la revisión, evaluación y dictamen del proyecto de tesis, titulado \"<strong>[titulo]</strong>\" presentado por el/la interesado(a) <strong>[tesista]</strong>, <strong>[cotesista]</strong> a PIUSE, el jurado revisor lo declara: <strong>[resultado]</strong>. El/la interesado(a) se encuentra expedito para la EJECUCIÓN DEL PROYECTO.</span></p>\r\n\r\n<p style=\"margin-left:0; margin-right:0; text-align:right\"><span style=\"font-size:10px\">Puno, [fecha].</span></p>\r\n<!-- TABLA DE FIRMAS ULTRA COMPACTA -->\r\n\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse:collapse; margin-top:2px; width:100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"text-align:center; vertical-align:bottom; width:50%\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[presidente]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Presidente de Jurado</em></span></p>\r\n			</td>\r\n			<td style=\"text-align:center; vertical-align:bottom; width:50%\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[primer_miembro]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Primer miembro de Jurado</em></span></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan=\"2\" style=\"text-align:center; vertical-align:bottom\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[segundo_miembro]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Segundo miembro de Jurado</em></span></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center; vertical-align:bottom\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[asesor]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Director / Asesor</em></span></p>\r\n			</td>\r\n			<td style=\"text-align:center; vertical-align:bottom\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[tesista]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Tesista principal</em></span></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan=\"2\" style=\"text-align:center; vertical-align:bottom\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[cotesista]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Cotesista</em></span></p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<!-- PROVEÍDO FINAL -->\r\n\r\n<div>\r\n<p style=\"margin-left:0; margin-right:0; text-align:center\"><span style=\"font-size:10px\"><strong>PROVEÍDO DE LA COORDINACIÓN DE INVESTIGACIÓN</strong></span></p>\r\n\r\n<p style=\"margin-left:0; margin-right:0; text-align:justify\"><span style=\"font-size:10px\">Declarado el proyecto como <strong>[resultado]</strong>, esta Coordinación autoriza su ejecución. Puno, [fecha].</span></p>\r\n\r\n<div style=\"text-align:center\">\r\n<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">.……………………………………………</span></p>\r\n\r\n<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[coordinador]</strong></span></p>\r\n\r\n<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">Coordinador de Investigación</span></p>\r\n</div>\r\n</div>\r\n</div>\r\n'),
(2, 'Acta Borrador', 'Acta Aprobación Borrador', '', '<div style=\"margin-left:0; margin-right:0\"><!-- CABECERA -->\r\n<p style=\"margin-left:0; margin-right:0; text-align:center\">[programa]</p>\r\n\r\n<p style=\"margin-left:0; margin-right:0; text-align:center\"><span style=\"font-size:11px\"><strong>COORDINACIÓN DE INVESTIGACIÓN DE LA UNIDAD DE SEGUNDA ESPECIALIDAD</strong></span></p>\r\n\r\n<h2 style=\"margin-left:0; margin-right:0; text-align:center\"><span style=\"font-size:12px\"><strong>ACTA DE REUNIÓN DE DICTAMEN DE BORRADOR DE TESIS</strong></span></h2>\r\n\r\n<p style=\"margin-left:0; margin-right:0; text-align:justify\"><span style=\"font-size:10px; text-transform:uppercase\"><strong>SEÑOR DIRECTOR DE LA COORDINACIÓN DE INVESTIGACIÓN DE LA UNIDAD DE SEGUNDA ESPECIALIDAD DE LA [facultad] DE LA UNIVERSIDAD NACIONAL DEL ALTIPLANO DE PUNO</strong></span></p>\r\n<!-- CUERPO -->\r\n\r\n<p style=\"margin-left:0; margin-right:0; text-align:justify\"><span style=\"font-size:10px\">En mérito a la revisión, evaluación y dictamen del borrador de tesis, titulado \"<strong>[titulo]</strong>\" presentado por el/la interesado(a) <strong>[tesista]</strong>, <strong>[cotesista]</strong> a PIUSE, el jurado revisor lo declara: <strong>[resultado]</strong>. El/la interesado(a) se encuentra expedito para la SUSTENTACIÓN DEL BORRADOR DE TESIS en fecha: [fecha_sustentacion], hora: [hora_sustentacion] y lugar: [lugar_sustentacion].</span></p>\r\n\r\n<p style=\"margin-left:0; margin-right:0; text-align:right\"><span style=\"font-size:10px\">Puno, [fecha].</span></p>\r\n<!-- TABLA DE FIRMAS ULTRA COMPACTA -->\r\n\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse:collapse; margin-top:2px; width:100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"text-align:center; vertical-align:bottom; width:50%\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[presidente]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Presidente de Jurado</em></span></p>\r\n			</td>\r\n			<td style=\"text-align:center; vertical-align:bottom; width:50%\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[primer_miembro]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Primer miembro de Jurado</em></span></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan=\"2\" style=\"text-align:center; vertical-align:bottom\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[segundo_miembro]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Segundo miembro de Jurado</em></span></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center; vertical-align:bottom\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[asesor]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Director / Asesor</em></span></p>\r\n			</td>\r\n			<td style=\"text-align:center; vertical-align:bottom\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[tesista]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Tesista principal</em></span></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan=\"2\" style=\"text-align:center; vertical-align:bottom\">\r\n			<p><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p><span style=\"font-size:10px\"><strong>[cotesista]</strong></span></p>\r\n\r\n			<p><span style=\"font-size:10px\"><em>Cotesista</em></span></p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<!-- PROVEÍDO FINAL -->\r\n\r\n<div>\r\n<p style=\"margin-left:0; margin-right:0; text-align:center\"><span style=\"font-size:10px\"><strong>PROVEÍDO DE LA COORDINACIÓN DE INVESTIGACIÓN</strong></span></p>\r\n\r\n<p style=\"margin-left:0; margin-right:0; text-align:justify\"><span style=\"font-size:10px\">Declarado el borrador como <strong>[resultado]</strong>, esta Coordinación autoriza la sustentación. Puno, [fecha].</span></p>\r\n\r\n<div style=\"text-align:center\">\r\n<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">.……………………………………………</span></p>\r\n\r\n<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[coordinador]</strong></span></p>\r\n\r\n<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">Coordinador de Investigación</span></p>\r\n</div>\r\n</div>\r\n</div>\r\n'),
(4, 'Email Proyecto', 'Notificación Proyecto', 'Nuevo Proyecto Asignado', 'Estimado [jurado], se le ha asignado el proyecto [titulo].'),
(5, 'Email Dictamen', 'Notificación Dictamen', 'Dictamen Emitido', 'El proyecto [titulo] ha recibido un dictamen: [resultado].'),
(28, 'Acta Sustentacion', 'Acta de Sustentación', 'Acta de sustentación', '<div style=\"margin-left:0; margin-right:0\"><!-- CABECERA -->\r\n<p style=\"margin-left:0; margin-right:0; text-align:center\">[programa]</p>\r\n\r\n<p style=\"margin-left:0; margin-right:0; text-align:center\"><span style=\"font-size:11px\"><strong>COORDINACIÓN DE INVESTIGACIÓN DE LA UNIDAD DE SEGUNDA ESPECIALIDAD</strong></span></p>\r\n\r\n<h2 style=\"margin-left:0; margin-right:0; text-align:center\"><span style=\"font-size:12px\"><strong>ACTA DE SUSTENTACIÓN</strong></span></h2>\r\n\r\n<p style=\"margin-left:0; margin-right:0; text-align:justify\"><span style=\"font-size:10px; text-transform:uppercase\"><strong>SEÑOR DIRECTOR DE LA COORDINACIÓN DE INVESTIGACIÓN DE LA UNIDAD DE SEGUNDA ESPECIALIDAD DE LA [facultad] DE LA UNIVERSIDAD NACIONAL DEL ALTIPLANO DE PUNO</strong></span></p>\r\n<!-- CUERPO -->\r\n\r\n<p style=\"margin-left:0; margin-right:0; text-align:justify\"><span style=\"font-size:10px\">En mérito a la defensa de la tesis, titulado \"<strong>[titulo]</strong>\" presentado por el/la interesado(a) <strong>[tesista]</strong>, <strong>[cotesista]</strong> programado para el [fecha_sustentacion] y realizado en el [lugar_sustentacion] a horas  [hora_sustentacion], el jurado revisor lo declara: <strong>[resultado]</strong>. El/la interesado(a) se encuentra expedito para la obtención del título o grado correspondiente.</span></p>\r\n\r\n<p style=\"margin-left:0; margin-right:0; text-align:right\"><span style=\"font-size:10px\">Puno, [fecha].</span></p>\r\n<!-- TABLA DE FIRMAS ULTRA COMPACTA -->\r\n\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse:collapse; margin-top:2px; width:100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"text-align:center; vertical-align:bottom; width:50%\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[presidente]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Presidente de Jurado</em></span></p>\r\n			</td>\r\n			<td style=\"text-align:center; vertical-align:bottom; width:50%\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[primer_miembro]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Primer miembro de Jurado</em></span></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan=\"2\" style=\"text-align:center; vertical-align:bottom\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[segundo_miembro]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Segundo miembro de Jurado</em></span></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"text-align:center; vertical-align:bottom\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[asesor]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Director / Asesor</em></span></p>\r\n			</td>\r\n			<td style=\"text-align:center; vertical-align:bottom\">\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[tesista]</strong></span></p>\r\n\r\n			<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><em>Tesista principal</em></span></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan=\"2\" style=\"text-align:center; vertical-align:bottom\">\r\n			<p><span style=\"font-size:10px\">____________________________</span></p>\r\n\r\n			<p><span style=\"font-size:10px\"><strong>[cotesista]</strong></span></p>\r\n\r\n			<p><span style=\"font-size:10px\"><em>Cotesista</em></span></p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<!-- PROVEÍDO FINAL -->\r\n\r\n<div>\r\n<p style=\"margin-left:0; margin-right:0; text-align:center\"><span style=\"font-size:10px\"><strong>PROVEÍDO DE LA COORDINACIÓN DE INVESTIGACIÓN</strong></span></p>\r\n\r\n<p style=\"margin-left:0; margin-right:0; text-align:justify\"><span style=\"font-size:10px\">Declarado la defensa de la tesis como <strong>[resultado]</strong>, esta Coordinación autoriza el tramite de obtención de título o grado que corresponda. Puno, [fecha].</span></p>\r\n\r\n<div style=\"text-align:center\">\r\n<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">.……………………………………………</span></p>\r\n\r\n<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\"><strong>[coordinador]</strong></span></p>\r\n\r\n<p style=\"margin-left:0; margin-right:0\"><span style=\"font-size:10px\">Coordinador de Investigación</span></p>\r\n</div>\r\n</div>\r\n</div>\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `programas`
--

CREATE TABLE `programas` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_facultad` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `nivel` enum('Pregrado','Posgrado','Segunda Especialidad') DEFAULT 'Pregrado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programas`
--

INSERT INTO `programas` (`id`, `id_facultad`, `nombre`, `nivel`) VALUES
(1, 1, 'Derecho', 'Pregrado'),
(2, 1, 'Maestrí­a en Derecho Constitucional', 'Posgrado'),
(3, 1, 'Función jurisdiccional y procesal', 'Segunda Especialidad'),
(4, 2, 'USE - CIENCIAS SOCIALES', 'Segunda Especialidad');

-- --------------------------------------------------------

--
-- Table structure for table `proyectos`
--

CREATE TABLE `proyectos` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  `titulo` text DEFAULT NULL,
  `resumen` text DEFAULT NULL,
  `id_linea_investigacion` int(10) UNSIGNED DEFAULT NULL,
  `id_tesista` int(10) UNSIGNED DEFAULT NULL,
  `id_cotesista` int(10) UNSIGNED DEFAULT NULL,
  `id_tesista_2` int(10) UNSIGNED DEFAULT NULL,
  `id_asesor` int(10) UNSIGNED DEFAULT NULL,
  `id_coasesor` int(10) UNSIGNED DEFAULT NULL,
  `estado` varchar(50) DEFAULT 'Iniciado',
  `id_etapa_actual` int(10) UNSIGNED DEFAULT 1,
  `autorizado_borrador` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `facultad` varchar(150) DEFAULT 'Facultad de Ciencias JurÃ­dicas y PolÃ­ticas',
  `id_facultad` int(10) UNSIGNED DEFAULT NULL,
  `programa` varchar(150) DEFAULT 'Derecho',
  `id_programa` int(10) UNSIGNED DEFAULT NULL,
  `nivel_academico` enum('Pregrado','Posgrado','Segunda Especialidad') DEFAULT 'Pregrado',
  `requiere_correccion` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_sustentacion` date DEFAULT NULL,
  `hora_sustentacion` time DEFAULT NULL,
  `lugar_sustentacion` varchar(255) DEFAULT NULL,
  `url_sustentacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proyectos`
--

INSERT INTO `proyectos` (`id`, `uuid`, `titulo`, `resumen`, `id_linea_investigacion`, `id_tesista`, `id_cotesista`, `id_tesista_2`, `id_asesor`, `id_coasesor`, `estado`, `id_etapa_actual`, `autorizado_borrador`, `created_at`, `facultad`, `id_facultad`, `programa`, `id_programa`, `nivel_academico`, `requiere_correccion`, `updated_at`, `fecha_sustentacion`, `hora_sustentacion`, `lugar_sustentacion`, `url_sustentacion`) VALUES
(1, '6c05cc2d2e0e21c3', 'La prisión preventiva 2024', NULL, 1, 2, NULL, NULL, 4, NULL, 'Observado', 1, 0, '2025-11-18 07:01:44', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 'USE - CIENCIAS SOCIALES', 1, 'Pregrado', 0, '2025-11-22 05:15:31', NULL, NULL, NULL, NULL),
(2, 'f83d142a3cb5a83d', 'Prisión preventiva 2024', 'Estes es el resumen de mi proyecto', 1, 7, NULL, NULL, 4, NULL, 'Aprobado', 1, 0, '2025-11-18 07:09:40', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 'Derecho', 1, 'Pregrado', 0, '2025-12-01 04:14:04', NULL, NULL, NULL, NULL),
(3, '0a3c25ac75a73a3c', 'Nivel de comprensión lectora en el área comunicación en los estudiantes de cuarto ciclo avanzado CEBA C.L.E 2025', 'En el Perú, el bajo nivel de comprensión lectora se ha consolidado como una de las principales barreras para el desarrollo educativo y social de adolescentes y adultos. La carencia de un hábito sistemático de lectura no solo limita la capacidad de extraer, interpretar y evaluar información de textos, sino que repercute directamente en el pensamiento crítico, la claridad de la expresión oral y escrita, y en la autonomía para el aprendizaje permanente. El estudio tiene como objetivo principal Determinar el nivel de Comprensión lectora en el área de comunicación en los estudiantes de cuarto ciclo avanzado de educación básica alternativa “coronel Ladislao Espinar” 2024. Con un diseño no experimental, enfoque cuantitativo y de tipo descriptivo, cuya muestra estará conformada por todos los estudiantes del cuarto ciclo avanzado de la institución. Donde se aplicará una prueba escrita adaptada para evaluar los tres niveles de compresión lectora. Se espera que estos hallazgos evidenciarán brechas significativas en habilidades lectoras complejas, las cuales estarán asociadas a factores como el limitado acceso a materiales, la escasa práctica de estrategias metacognitivas y el contexto socioeconómico rural. La investigación aportará datos diagnósticos para diseñar intervenciones pedagógicas focalizadas en la educación de adultos, alineadas con las políticas de alfabetización del MINEDU.\r\n', 7, 11, NULL, NULL, 10, NULL, 'Sustentado', 3, 0, '2025-11-19 20:51:18', 'Facultad de Ciencias de la Educación ', 2, 'USE - CIENCIAS SOCIALES', 4, 'Segunda Especialidad', 0, '2025-11-24 04:50:18', '2025-11-24', '10:30:00', 'Virtual', 'https://meet.google.com/spy-qtov-nwq'),
(4, '59111c2b0f64881a', 'Derecho a leer y el Derecho', 'Estes es el resumen del proyecto.', 3, 23, NULL, NULL, 4, NULL, 'Sustentado', 3, 0, '2025-11-22 05:52:59', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 'Derecho', 1, 'Pregrado', 0, '2025-11-29 06:35:59', '2025-11-29', '09:30:00', 'Auditorio FCJP', 'https://meet.google.com/spy-qtov-nwq'),
(5, '697d6d814e2045ad', 'El Derecho sin Derecho', 'Este el resumen del proyecto.', 1, 27, NULL, NULL, 5, NULL, 'Observado', 1, 0, '2025-11-26 21:25:19', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 'Derecho', 1, 'Pregrado', 0, '2025-12-01 04:40:18', NULL, NULL, NULL, NULL),
(6, '13d091e0629f0da5', 'Proyecto de prueba de Pedro Palotes', 'El Proyecto solo es una demostración de uso de NADIA.', 1, 29, NULL, NULL, 4, NULL, 'Iniciado', 1, 0, '2026-01-03 01:58:59', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 'Derecho', 1, 'Pregrado', 0, '2026-01-05 03:25:39', NULL, NULL, NULL, NULL),
(7, 'e9af62f26686e2ff', 'Proyecto de prueba de Pedrito Segundo', 'Este es el resumen del proyecto de Pedrito Segundo.', 1, 30, NULL, NULL, 4, NULL, 'Iniciado', 1, 0, '2026-01-05 03:24:22', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 'Derecho', 1, 'Pregrado', 0, '2026-01-05 03:39:21', NULL, NULL, NULL, NULL),
(8, '7a99a61c46b2215e', 'Proyecto de prueba de Pedro Quinto', 'Es el resumen de proyecto de Pedro Quinto.', 1, 33, NULL, NULL, 4, NULL, 'En Revisión', 1, 0, '2026-01-05 05:13:50', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 'Derecho', 1, 'Pregrado', 0, '2026-01-05 05:16:29', NULL, NULL, NULL, NULL),
(9, '434513e6a1d3c70f', 'Titulo del proyecto de Sexto Primero Pedro y Septimo Segundo Pedro', 'Es el resumen del proyecto.', 1, 34, NULL, 35, 4, NULL, 'Sustentado', 3, 0, '2026-01-07 16:37:15', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 'Derecho', 1, 'Pregrado', 0, '2026-01-18 18:25:14', '2026-01-19', '16:00:00', 'Taller de simulación de audiencias', 'https://meet.google.com/spy-qtov-nwq'),
(10, 'a0e8e340fe89b566', 'Proyecto de Pedro Octavo y Noveno y  pedro8@miunap.edu.pe ', 'Es el contenido del resumen del proyecto de Pedro Octavo y Noveno', 1, 37, NULL, 38, 3, NULL, 'Sustentado', 3, 1, '2026-01-18 20:19:46', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 'Derecho', 1, 'Pregrado', 0, '2026-01-19 18:30:52', '2026-01-19', '12:00:00', 'Auditorio FCJP', 'https://meet.google.com/spy-qtov-nwq'),
(11, '7f6a8111384c2a84', 'Proyecto investigación jurídica de Pedro Doce', 'Esto es el resumen del proyecto de Pedro Doce.', 1, 41, NULL, NULL, 5, NULL, 'Sustentado', 3, 1, '2026-01-19 20:52:16', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 'Derecho', 1, 'Pregrado', 0, '2026-01-20 06:29:10', '2026-01-20', '08:00:00', 'Virtual', 'https://meet.google.com/hta-jipd-tjd'),
(12, '79e986b84b68f0ec', 'Proyecto investigación jurídica de Pedro  Trece y Pedro Diez', 'Es el resumen del proyecto de Pedro Trece y Diez', 1, 42, NULL, 39, 28, NULL, 'Sustentado', 3, 1, '2026-01-20 07:15:45', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 'Derecho', 1, 'Pregrado', 0, '2026-01-21 02:45:16', '2026-01-21', '08:00:00', 'Virtual', 'https://meet.google.com/spy-qtov-nwq'),
(13, 'cc3e0992171052d8', 'Proyecto de Pedro Catorce', 'Es el resumen del Proyecto de Pedro Catorce', 1, 43, NULL, NULL, 28, NULL, 'En Revisión', 1, 1, '2026-01-21 18:56:04', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 'Derecho', 1, 'Pregrado', 0, '2026-01-21 19:53:25', NULL, NULL, NULL, NULL),
(14, 'f9929a1b2d93e2a1', 'Proyecto de Pedro 15', 'Estes el resumen del proyecto.', 1, 44, NULL, NULL, 5, NULL, 'Sustentado', 3, 1, '2026-01-22 01:37:02', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 'Derecho', 1, 'Pregrado', 0, '2026-01-22 03:10:52', '2026-01-22', '09:30:00', 'Virtual', 'https://meet.google.com/spy-qtov-nwq'),
(15, 'f03c3cf94f9ad550', 'Proyecto de Pedro 16', 'Es el resumen.', 3, 45, NULL, NULL, 25, NULL, 'Sustentado', 3, 1, '2026-01-22 03:49:09', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 'Derecho', 1, 'Pregrado', 0, '2026-01-22 04:35:32', '2026-01-22', '08:30:00', 'Virtual', 'https://meet.google.com/spy-qtov-nwq');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `nombre_rol`) VALUES
(3, 'Administrador'),
(4, 'Coordinador de Investigación'),
(2, 'Docente'),
(1, 'Tesista');

-- --------------------------------------------------------

--
-- Table structure for table `sistema_acceso`
--

CREATE TABLE `sistema_acceso` (
  `id` int(11) NOT NULL DEFAULT 1,
  `activo` tinyint(1) DEFAULT 0,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `mensaje_cierre` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sistema_acceso`
--

INSERT INTO `sistema_acceso` (`id`, `activo`, `fecha_inicio`, `fecha_fin`, `mensaje_cierre`, `created_at`, `updated_at`) VALUES
(1, 0, '2026-01-07 02:19:00', '2026-01-08 02:19:00', 'El sistema se encuentra cerrado temporalmente por periodo de vacaciones. Contacte a la administración para más información.', '2026-01-07 07:06:16', '2026-01-07 07:26:56');

-- --------------------------------------------------------

--
-- Table structure for table `sublineas_investigacion`
--

CREATE TABLE `sublineas_investigacion` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_linea` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sublineas_investigacion`
--

INSERT INTO `sublineas_investigacion` (`id`, `id_linea`, `nombre`) VALUES
(1, 1, 'Derecho Penal'),
(2, 1, 'Derecho Civil'),
(3, 1, 'Derecho Constitucional'),
(4, 1, 'Derecho Administrativo'),
(5, 1, 'Derecho Laboral'),
(6, 1, 'Teoría del Derecho'),
(7, 3, 'Calidad Educativa.');

-- --------------------------------------------------------

--
-- Table structure for table `sustentaciones`
--

CREATE TABLE `sustentaciones` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_proyecto` int(10) UNSIGNED NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `lugar_enlace` varchar(255) NOT NULL,
  `estado` enum('Programada','Realizada','Cancelada') DEFAULT 'Programada',
  `nota_final` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombres` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `grado_academico` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `id_rol_principal` int(10) UNSIGNED DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `facultad_asignada` varchar(150) DEFAULT NULL,
  `id_facultad` int(10) UNSIGNED DEFAULT NULL,
  `id_programa` int(10) UNSIGNED DEFAULT NULL,
  `nivel_academico` enum('Pregrado','Posgrado','Segunda Especialidad') DEFAULT 'Pregrado',
  `condicion_laboral` enum('Ordinario','Contratado','Externo') DEFAULT 'Ordinario',
  `categoria_docente` enum('Principal','Asociado','Auxiliar','Contratado') DEFAULT 'Auxiliar',
  `antiguedad_anios` int(11) DEFAULT 0,
  `id_area_investigacion` int(10) UNSIGNED DEFAULT NULL,
  `id_linea_investigacion` int(10) UNSIGNED DEFAULT NULL,
  `recovery_code` varchar(10) DEFAULT NULL,
  `recovery_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `grado_academico`, `email`, `dni`, `codigo`, `telefono`, `password_hash`, `id_rol_principal`, `activo`, `created_at`, `facultad_asignada`, `id_facultad`, `id_programa`, `nivel_academico`, `condicion_laboral`, `categoria_docente`, `antiguedad_anios`, `id_area_investigacion`, `id_linea_investigacion`, `recovery_code`, `recovery_expires`) VALUES
(1, 'Admin', '', NULL, 'admin@miunap.pe', '', NULL, NULL, '$2y$12$Y2svXY/4zIgVJ.vS6t2AK.EVbtj18yPrN/NPRqrkkoJip6WMigE/G', 3, 1, '2025-11-18 05:45:23', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, NULL, 'Pregrado', 'Ordinario', 'Auxiliar', 0, NULL, NULL, NULL, NULL),
(2, 'Pedro', 'Picapiedra', 'Estudiante', 'pedrito@picapiedra.com', '', NULL, '', '$2y$12$Y2svXY/4zIgVJ.vS6t2AK.EVbtj18yPrN/NPRqrkkoJip6WMigE/G', 1, 1, '2025-11-18 06:36:09', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, NULL, 'Pregrado', 'Externo', 'Auxiliar', 0, 1, 1, NULL, NULL),
(3, 'Michael', 'Espinoza Coila', 'Doctor', 'mespinoza@unap.edu.pe', '', NULL, '', '$2y$12$Y2svXY/4zIgVJ.vS6t2AK.EVbtj18yPrN/NPRqrkkoJip6WMigE/G', 2, 1, '2025-11-18 06:36:52', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, NULL, 'Pregrado', 'Ordinario', 'Auxiliar', 7, 1, 1, '396967', '2026-01-05 23:22:23'),
(4, 'Jose', 'Pineda', 'Doctor', 'jpineda@miunap.pe', '', NULL, '', '$2y$12$Y2svXY/4zIgVJ.vS6t2AK.EVbtj18yPrN/NPRqrkkoJip6WMigE/G', 2, 1, '2025-11-18 06:58:51', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, NULL, 'Pregrado', 'Ordinario', 'Principal', 25, 1, 1, NULL, NULL),
(5, 'Francisco', 'Tipula', 'Maestro', 'ftipula@miunap.pe', '', NULL, '', '$2y$12$Y2svXY/4zIgVJ.vS6t2AK.EVbtj18yPrN/NPRqrkkoJip6WMigE/G', 2, 1, '2025-11-18 06:59:16', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, NULL, 'Pregrado', 'Ordinario', 'Auxiliar', 22, 1, 1, NULL, NULL),
(6, 'Galimberty', 'Ponce', 'Maestro', 'gponce@miunap.pe', '', NULL, '', '$2y$12$Y2svXY/4zIgVJ.vS6t2AK.EVbtj18yPrN/NPRqrkkoJip6WMigE/G', 2, 1, '2025-11-18 06:59:44', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Contratado', 'Auxiliar', 6, 1, 1, NULL, NULL),
(7, 'Vilma', 'Picapiedra', NULL, 'vilma@miunap.pe', NULL, NULL, NULL, '$2y$12$Y2svXY/4zIgVJ.vS6t2AK.EVbtj18yPrN/NPRqrkkoJip6WMigE/G', 1, 1, '2025-11-18 07:03:41', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, NULL, 'Pregrado', 'Ordinario', 'Auxiliar', 0, NULL, NULL, NULL, NULL),
(8, 'Boris', 'Espezua', 'Doctor', 'bgespezua@miunap.pe', '', NULL, '', '$2y$12$Y2svXY/4zIgVJ.vS6t2AK.EVbtj18yPrN/NPRqrkkoJip6WMigE/G', 4, 1, '2025-11-18 23:10:12', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Auxiliar', 0, 1, 1, NULL, NULL),
(9, 'Juanito', 'Mendoza', NULL, 'juanito@miunap.pe', '', '', '', '$2y$12$Y2svXY/4zIgVJ.vS6t2AK.EVbtj18yPrN/NPRqrkkoJip6WMigE/G', 1, 1, '2025-11-19 02:21:24', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, NULL, 'Pregrado', 'Ordinario', 'Auxiliar', 0, 1, 1, NULL, NULL),
(10, 'YOLANDA', 'LUJA', 'Doctor', 'yolanda@miunap.pe', '', NULL, NULL, '$2y$12$Y2svXY/4zIgVJ.vS6t2AK.EVbtj18yPrN/NPRqrkkoJip6WMigE/G', 2, 1, '2025-11-19 20:37:24', 'Facultad de Ciencias de la Educación ', 2, NULL, 'Pregrado', 'Ordinario', 'Asociado', 20, NULL, NULL, NULL, NULL),
(11, 'Marleni', 'Cutipa', 'Estudiante', 'marle@gmail.com', '', '', '', '$2y$12$Y2svXY/4zIgVJ.vS6t2AK.EVbtj18yPrN/NPRqrkkoJip6WMigE/G', 1, 1, '2025-11-19 20:42:15', 'Facultad de Ciencias de la Educación ', 2, NULL, 'Pregrado', 'Externo', 'Auxiliar', 0, NULL, NULL, NULL, NULL),
(12, 'yeni', 'pacho', 'Magister', 'yeni@miunap.pe', '', NULL, NULL, '$2y$12$Y2svXY/4zIgVJ.vS6t2AK.EVbtj18yPrN/NPRqrkkoJip6WMigE/G', 2, 1, '2025-11-19 20:43:22', 'Facultad de Ciencias de la Educación ', 2, NULL, 'Pregrado', 'Ordinario', 'Asociado', 18, NULL, NULL, NULL, NULL),
(13, 'marisol', 'yana', 'Doctor', 'marisol@unap.edu.pe', '', NULL, NULL, '$2y$12$Y2svXY/4zIgVJ.vS6t2AK.EVbtj18yPrN/NPRqrkkoJip6WMigE/G', 2, 1, '2025-11-19 20:44:09', 'FACULTAD DE CIENCIAS DE LA EDUCACIÓN ', 2, NULL, 'Pregrado', 'Ordinario', 'Asociado', 15, NULL, NULL, NULL, NULL),
(16, 'paul', 'quispe', 'Doctor', 'paul@unap.edu.pe', '', NULL, NULL, '$2y$12$Y2svXY/4zIgVJ.vS6t2AK.EVbtj18yPrN/NPRqrkkoJip6WMigE/G', 2, 1, '2025-11-19 20:46:40', '2', 2, NULL, 'Pregrado', 'Ordinario', 'Asociado', 10, NULL, NULL, NULL, NULL),
(22, 'Nery', 'Mamani', 'Dr.', 'nery@miunap.pe', '', NULL, NULL, '$2y$12$1FD84O0UkDiozA/9nm1FceKx61JGNqVGqsDdOAHjs8zM9ocskG7GW', 2, 1, '2025-11-21 22:41:35', 'Facultad de Ciencias de la Educación ', 2, 4, 'Pregrado', 'Ordinario', 'Asociado', 20, 1, 3, NULL, NULL),
(23, 'Pedro', 'Planas Quispe', NULL, 'pedro@planas.com', '', NULL, NULL, '$2y$10$awimbjKFtFzm9BIh9xrKOe3Qd9gWlzHYQtK0VW0SF7j8zG1LCwHAq', 1, 1, '2025-11-22 05:25:33', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, NULL, 'Pregrado', 'Ordinario', 'Auxiliar', 0, NULL, NULL, NULL, NULL),
(24, 'Juan', 'Casazola', 'Dr.', 'jcasazola@miunap.pe', '', NULL, '', '$2y$10$AZ1qF.uGSxoWV6FNvDE6LOzqtMmBftO.KdWMoq09mz8Yf2dd.8tiu', 2, 1, '2025-11-22 05:36:51', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Principal', 15, 1, 1, NULL, NULL),
(25, 'Rosario', 'Canal', 'Dra.', 'rcanal@miunap.pe', '', NULL, '', '$2y$10$2PjBxLYMArxIi76U7abtouKaPoHxI4e/zjxEvtCZZU.px6YsrwT6y', 2, 1, '2025-11-22 05:46:53', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Asociado', 10, 1, 1, NULL, NULL),
(26, 'Irene', 'Huanca', 'Dra.', 'ihuanca@miunap.edu.pe', '', NULL, '', '$2y$10$OInwkkuBZuRnNU5D3thE3ubRw/v0wm73s0RLDeVlpa/nf1CK6KmpK', 2, 1, '2025-11-22 05:48:53', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Auxiliar', 15, 1, 1, NULL, NULL),
(27, 'Carlos', 'Sin Miedo', NULL, 'carlos@sinmiedo.pe', '', NULL, '951777777', '$2y$10$DWtqWllLdc15sWZITO00BeFNjTdhXv7HALsr3sC3uSba6KwFqG0JW', 1, 1, '2025-11-26 20:42:12', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, NULL, 'Pregrado', 'Ordinario', 'Auxiliar', 0, NULL, NULL, NULL, NULL),
(28, 'Carlos', 'Ramirez', 'Mtro.', 'cramirez@miunap.pe', '', NULL, '', '$2y$10$l3kAZGypyzFGMRTsS1wSxeLj7hYLHdLPILZ9TXptACpOZLSws3LwS', 2, 1, '2025-11-26 21:21:20', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, NULL, 'Pregrado', 'Ordinario', 'Asociado', 15, 1, 1, NULL, NULL),
(29, 'Pedro', 'De los palotes', NULL, 'pedrop@unap.pe', '', NULL, '', '$2y$10$5om1JUS9fxcjRpvtTCanWe3N//vQmW8S/3lrqQocfKAOKKxcQY9MW', 1, 1, '2026-01-03 01:53:58', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, NULL, 'Pregrado', 'Ordinario', 'Auxiliar', 0, NULL, NULL, NULL, NULL),
(30, 'Pedro', 'Segundo', NULL, 'pedro2@unap.pe', '', NULL, '', '$2y$10$6hcJef2Y3dseunQScRPvs.Vktsg/HLb792j3mFmyktOWDUzTBcn4O', 1, 1, '2026-01-05 03:07:56', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, NULL, 'Pregrado', 'Ordinario', 'Auxiliar', 0, NULL, NULL, NULL, NULL),
(31, 'Pedro', 'Tercero Quispe', NULL, 'pedro3@unap.pe', '', NULL, '', '$2y$10$8XQ8fb9DpTJiAqLC.8LhWev2rRgnx4B48p2ozijhEBxUloungw4Cq', 1, 1, '2026-01-05 03:42:37', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, NULL, 'Pregrado', 'Ordinario', 'Auxiliar', 0, NULL, NULL, NULL, NULL),
(32, 'Pedro', 'Cuarto', NULL, 'pedro4@unap.pe', '', '', NULL, '$2y$10$1pzF488nrKtfoHIzkSdFO.pJSaR0Jk5D3LTtdMXsIDE2fznenPnnO', 1, 1, '2026-01-05 04:00:24', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Auxiliar', 0, NULL, NULL, NULL, NULL),
(33, 'Pedro', 'Quinto', NULL, 'pedro5@unap.pe', '', '', '', '$2y$10$R1S5vln5YB0QRWv1ocFXoOCEtINCRpQHBUEkQwE.Bh2DQe.DOYlh6', 1, 1, '2026-01-05 05:07:21', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Auxiliar', 0, 1, 1, NULL, NULL),
(34, 'Pedro', 'Sexto Primero', NULL, 'pedro6@miunap.pe', '', '', NULL, '$2y$10$M3GmHwQXQGdRdw6X6fGYZOW60GHys.ahgMpequGG/v7c16nRLr4YS', 1, 1, '2026-01-07 15:43:09', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Auxiliar', 0, 1, 1, NULL, NULL),
(35, 'Pedro', 'Septimo Segundo', NULL, 'pedro7@miunap.pe', '', '', NULL, '$2y$10$i.dgan/CIzhA0SG.XBkz4upMNahA.TspBFxXrMHeNrf.vF1hkOU/6', 1, 1, '2026-01-07 15:44:01', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Auxiliar', 0, 1, 1, NULL, NULL),
(36, 'Yolanda', 'Lujano', NULL, 'ylujano@miunap.pe', '', '2007916', NULL, '$2y$10$O7AAN7TsqVP/pv6RTwnsB.WivCkxW25eyGFGzOVKxyR5QfPOMPuNW', 4, 1, '2026-01-12 04:11:32', 'Facultad de Ciencias de la Educación ', 2, 4, 'Pregrado', 'Ordinario', 'Auxiliar', 0, 1, 3, NULL, NULL),
(37, 'Pedro', 'Octavo', NULL, 'pedro8@miunap.edu.pe', '', '', '', '$2y$10$Vlj0lgYeLNUHGtnhuXPXOewOYvUSwHXzAZSX49qUeA/0Lvgh7L8WW', 1, 1, '2026-01-18 19:17:14', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Auxiliar', 0, 1, 1, NULL, NULL),
(38, 'Pedro', 'Noveno', NULL, 'pedro9@unap.edu.pe', '', '', NULL, '$2y$10$jUJN12Q.cmOkvedRk/VgD.purrRJdACQGOf9LaTYZ1QwKHqxRzjU6', 1, 1, '2026-01-18 20:15:44', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Auxiliar', 0, 1, 1, NULL, NULL),
(39, 'Pedro', 'Diez 10', 'Estudiante', 'pedro10@miunap.pe', '', '', '', '$2y$10$WqKfLQJQTejwbv8N9inBSuf7HNGhRgLCHJ1xJ9DxhxHUEhatPOt9u', 1, 1, '2026-01-19 19:24:14', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Auxiliar', 0, 1, 1, NULL, NULL),
(40, 'Pedro', 'Once', NULL, 'pedro11@miunap.pe', '', '', NULL, '$2y$10$9pOQi1C7bVQlcI3KsylDl.qogPMnDYjUt1RXwyhZuZEeoTdjG1hzm', 1, 1, '2026-01-19 20:17:03', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Auxiliar', 0, 1, 1, NULL, NULL),
(41, 'Pedro', 'Doce', NULL, 'pedro12@miunap.pe', '', '', NULL, '$2y$10$BdimwH4ZvUa3m2ZzK202vuL7/N6dzVtSVRwlwLbxFu0banzxc6.aG', 1, 1, '2026-01-19 20:42:58', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Auxiliar', 0, 1, 1, NULL, NULL),
(42, 'Pedro', 'Trece', NULL, 'pedro13@miunap.pe', '', '', NULL, '$2y$10$Cny4wpvfzEFqTWPw0luExOzvXFbsP0sl1aq7MgBeR5bCTGIIwpyo6', 1, 1, '2026-01-20 07:07:09', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Auxiliar', 0, 1, 1, NULL, NULL),
(43, 'Pedro', 'Catorce', NULL, 'pedro14@miunap.pe', '', '', NULL, '$2y$10$LUKKgAWOFGrdkRF6qdkpN.y5VefCizU.1n5C.6vtdKuI29sMoYK9O', 1, 1, '2026-01-21 17:59:23', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Auxiliar', 0, 1, 1, NULL, NULL),
(44, 'Pedro', 'Quince', NULL, 'pedro15@miunap.pe', '', '', NULL, '$2y$10$MR0b8/oPkUB6tukOszhbBuAY3GJ9PGXiRbJHhvV6oEZXrwcWalQni', 1, 1, '2026-01-21 20:13:41', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Auxiliar', 0, 1, 1, NULL, NULL),
(45, 'Pedro', 'Dieciséis', NULL, 'pedro16@miunap.pe', '', '', NULL, '$2y$10$OvzPJp.SKV1k66cVDkgB2ue8qLnprJVlcOCZSdeIO2gwbNHO46BNq', 1, 1, '2026-01-22 03:45:13', 'Facultad de Ciencias Jurídicas y Polí­ticas', 1, 1, 'Pregrado', 'Ordinario', 'Auxiliar', 0, 1, 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `areas_investigacion`
--
ALTER TABLE `areas_investigacion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `configuraciones`
--
ALTER TABLE `configuraciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indexes for table `configuracion_plazos`
--
ALTER TABLE `configuracion_plazos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config_email`
--
ALTER TABLE `config_email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config_plazos`
--
ALTER TABLE `config_plazos`
  ADD PRIMARY KEY (`clave`);

--
-- Indexes for table `constancias_emitidas`
--
ALTER TABLE `constancias_emitidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cvd` (`cvd`);

--
-- Indexes for table `dictamenes`
--
ALTER TABLE `dictamenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_proyecto` (`id_proyecto`);

--
-- Indexes for table `docente_sublineas`
--
ALTER TABLE `docente_sublineas`
  ADD PRIMARY KEY (`id_docente`,`id_sublinea`),
  ADD KEY `id_sublinea` (`id_sublinea`);

--
-- Indexes for table `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facultades`
--
ALTER TABLE `facultades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `historial_movimientos`
--
ALTER TABLE `historial_movimientos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jurado_asignaciones`
--
ALTER TABLE `jurado_asignaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_jurado` (`id_proyecto`,`id_jurado`);

--
-- Indexes for table `lineas_investigacion_v2`
--
ALTER TABLE `lineas_investigacion_v2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_area` (`id_area`);

--
-- Indexes for table `logs_sistema`
--
ALTER TABLE `logs_sistema`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mensaje_adjuntos`
--
ALTER TABLE `mensaje_adjuntos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mensaje` (`id_mensaje`);

--
-- Indexes for table `mensaje_destinatarios`
--
ALTER TABLE `mensaje_destinatarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mensaje` (`id_mensaje`);

--
-- Indexes for table `observaciones`
--
ALTER TABLE `observaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_proyecto` (`id_proyecto`),
  ADD KEY `id_jurado` (`id_jurado`);

--
-- Indexes for table `observaciones_finalizadas`
--
ALTER TABLE `observaciones_finalizadas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_finalizacion` (`id_proyecto`,`id_usuario`,`etapa`);

--
-- Indexes for table `plantillas`
--
ALTER TABLE `plantillas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programas`
--
ALTER TABLE `programas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_facultad` (`id_facultad`);

--
-- Indexes for table `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indexes for table `sistema_acceso`
--
ALTER TABLE `sistema_acceso`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sublineas_investigacion`
--
ALTER TABLE `sublineas_investigacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_linea` (`id_linea`);

--
-- Indexes for table `sustentaciones`
--
ALTER TABLE `sustentaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_facultad` (`id_facultad`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `areas_investigacion`
--
ALTER TABLE `areas_investigacion`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `configuraciones`
--
ALTER TABLE `configuraciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `configuracion_plazos`
--
ALTER TABLE `configuracion_plazos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `constancias_emitidas`
--
ALTER TABLE `constancias_emitidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `dictamenes`
--
ALTER TABLE `dictamenes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `facultades`
--
ALTER TABLE `facultades`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `historial_movimientos`
--
ALTER TABLE `historial_movimientos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT for table `jurado_asignaciones`
--
ALTER TABLE `jurado_asignaciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `lineas_investigacion_v2`
--
ALTER TABLE `lineas_investigacion_v2`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `logs_sistema`
--
ALTER TABLE `logs_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `mensaje_adjuntos`
--
ALTER TABLE `mensaje_adjuntos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `mensaje_destinatarios`
--
ALTER TABLE `mensaje_destinatarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `observaciones`
--
ALTER TABLE `observaciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `observaciones_finalizadas`
--
ALTER TABLE `observaciones_finalizadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `plantillas`
--
ALTER TABLE `plantillas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `programas`
--
ALTER TABLE `programas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sublineas_investigacion`
--
ALTER TABLE `sublineas_investigacion`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sustentaciones`
--
ALTER TABLE `sustentaciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dictamenes`
--
ALTER TABLE `dictamenes`
  ADD CONSTRAINT `dictamenes_ibfk_1` FOREIGN KEY (`id_proyecto`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `docente_sublineas`
--
ALTER TABLE `docente_sublineas`
  ADD CONSTRAINT `docente_sublineas_ibfk_1` FOREIGN KEY (`id_docente`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `docente_sublineas_ibfk_2` FOREIGN KEY (`id_sublinea`) REFERENCES `sublineas_investigacion` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lineas_investigacion_v2`
--
ALTER TABLE `lineas_investigacion_v2`
  ADD CONSTRAINT `lineas_investigacion_v2_ibfk_1` FOREIGN KEY (`id_area`) REFERENCES `areas_investigacion` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mensaje_adjuntos`
--
ALTER TABLE `mensaje_adjuntos`
  ADD CONSTRAINT `mensaje_adjuntos_ibfk_1` FOREIGN KEY (`id_mensaje`) REFERENCES `mensajes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mensaje_destinatarios`
--
ALTER TABLE `mensaje_destinatarios`
  ADD CONSTRAINT `mensaje_destinatarios_ibfk_1` FOREIGN KEY (`id_mensaje`) REFERENCES `mensajes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programas`
--
ALTER TABLE `programas`
  ADD CONSTRAINT `programas_ibfk_1` FOREIGN KEY (`id_facultad`) REFERENCES `facultades` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sublineas_investigacion`
--
ALTER TABLE `sublineas_investigacion`
  ADD CONSTRAINT `sublineas_investigacion_ibfk_1` FOREIGN KEY (`id_linea`) REFERENCES `lineas_investigacion_v2` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
