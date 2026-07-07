-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-07-2026 a las 16:02:13
-- Versión del servidor: 11.8.2-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_blaboral`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-carlos.valenzuela@talentum.edu.pe|127.0.0.1', 'i:1;', 1782828498),
('laravel-cache-carlos.valenzuela@talentum.edu.pe|127.0.0.1:timer', 'i:1782828498;', 1782828498),
('laravel-cache-cnectando selva|127.0.0.1', 'i:1;', 1782593572),
('laravel-cache-cnectando selva|127.0.0.1:timer', 'i:1782593572;', 1782593572),
('laravel-cache-innova schools|127.0.0.1', 'i:1;', 1782746022),
('laravel-cache-innova schools|127.0.0.1:timer', 'i:1782746022;', 1782746022),
('laravel-cache-jperez@instituto.edu.pe|127.0.0.1', 'i:1;', 1782828539),
('laravel-cache-jperez@instituto.edu.pe|127.0.0.1:timer', 'i:1782828539;', 1782828539);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `file`
--

CREATE TABLE `file` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fileable_type` varchar(255) NOT NULL,
  `fileable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`metadata`)),
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_opportunity_applications`
--

CREATE TABLE `job_opportunity_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `program_study` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'postulated',
  `cv` varchar(255) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `feedback_date` datetime DEFAULT NULL,
  `offer_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_opportunity_company`
--

CREATE TABLE `job_opportunity_company` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ruc` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `mailbox` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_opportunity_contract_types`
--

CREATE TABLE `job_opportunity_contract_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `job_opportunity_contract_types`
--

INSERT INTO `job_opportunity_contract_types` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Contrato a plazo indeterminado', '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL),
(2, 'Contrato a plazo fijo', '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL),
(3, 'Contrato por temporada', '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL),
(4, 'Largo plazo', '2026-06-29 14:10:56', '2026-06-30 15:10:35', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_opportunity_location`
--

CREATE TABLE `job_opportunity_location` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `job_opportunity_location`
--

INSERT INTO `job_opportunity_location` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Remoto', '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL),
(2, 'Presencial', '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL),
(3, 'Híbrido', '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_opportunity_offer`
--

CREATE TABLE `job_opportunity_offer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `requirements` text NOT NULL,
  `publication_date` datetime NOT NULL,
  `deadline` datetime DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `salary` decimal(10,2) NOT NULL,
  `salary_currency` varchar(255) NOT NULL,
  `attachments` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `country` varchar(255) DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `state_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `work_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `contract_type_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_opportunity_offer_category`
--

CREATE TABLE `job_opportunity_offer_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `job_opportunity_offer_category`
--

INSERT INTO `job_opportunity_offer_category` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Informatica/Tecnologia', NULL, '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL),
(2, 'Marketing', NULL, '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL),
(3, 'Administrativo', NULL, '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL),
(4, 'Tecnologia', NULL, '2026-06-25 19:51:07', '2026-06-25 19:51:07', NULL),
(5, 'Secretaria', NULL, '2026-06-25 22:11:59', '2026-06-25 22:12:06', '2026-06-25 22:12:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_opportunity_offer_state`
--

CREATE TABLE `job_opportunity_offer_state` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `job_opportunity_offer_state`
--

INSERT INTO `job_opportunity_offer_state` (`id`, `name`, `key`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Borrador', 'draft', NULL, '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL),
(2, 'Vigente', 'active', NULL, '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL),
(3, 'Finalizada', 'finished', NULL, '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL),
(4, 'Suspendida', 'suspended', NULL, '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL),
(5, 'Cancelada', 'canceled', NULL, '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_opportunity_offer_state_detail`
--

CREATE TABLE `job_opportunity_offer_state_detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `offer_id` bigint(20) UNSIGNED NOT NULL,
  `state_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_opportunity_user_cv`
--

CREATE TABLE `job_opportunity_user_cv` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_opportunity_work_schedules`
--

CREATE TABLE `job_opportunity_work_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `job_opportunity_work_schedules`
--

INSERT INTO `job_opportunity_work_schedules` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Jornada Completa', '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL),
(2, 'Becas/Prácticas', '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL),
(3, 'Jornada Parcial', '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL),
(4, 'Por Horas', '2025-06-03 09:58:30', '2025-06-03 09:58:30', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `link`
--

CREATE TABLE `link` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `linkable_type` varchar(255) NOT NULL,
  `linkable_id` bigint(20) UNSIGNED NOT NULL,
  `url` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Principal', '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(2, 'Aula virtual', '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(3, 'Preferencias', '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(4, 'Configuración', '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(5, 'Acceso rápido', '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(6, 'Bolsa Laboral', '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_04_08_145119_create_job_opportunity_company_table', 1),
(2, '2024_04_08_145119_create_person_table', 1),
(3, '2024_04_08_145119_create_system_configuration_table', 1),
(4, '2024_04_08_145120_create_user_table', 1),
(5, '2024_04_08_231525_create_file_table', 1),
(6, '2024_04_08_231525_create_link_table', 1),
(7, '2024_05_15_104107_create_cache_table', 1),
(8, '2024_05_15_110412_create_jobs_table', 1),
(9, '2024_05_15_151348_create_failed_jobs_table', 1),
(10, '2025_05_14_114152_create_job_opportunity_location_table', 1),
(11, '2025_05_14_114620_create_job_opportunity_offer_state_table', 1),
(12, '2025_05_14_114758_create_job_opportunity_offer_category_table', 1),
(13, '2025_05_14_114918_create_job_opportunity_user_cv_table', 1),
(14, '2025_05_14_115118_create_job_opportunity_work_schedules_table', 1),
(15, '2025_05_14_115135_create_job_opportunity_contract_types_table', 1),
(16, '2025_05_14_115138_create_job_opportunity_offer_table', 1),
(17, '2025_05_14_115139_create_job_opportunity_offer_state_detail_table', 1),
(18, '2025_05_14_115140_create_job_opportunity_applications_table', 1),
(19, '0001_01_01_000000_create_users_table', 2),
(20, '2026_06_29_113000_create_user_notifications_table', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `option`
--

CREATE TABLE `option` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `option_id` bigint(20) UNSIGNED DEFAULT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_url` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `option`
--

INSERT INTO `option` (`id`, `option_id`, `menu_id`, `name`, `name_url`, `icon`, `is_visible`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 1, 'Inicio', 'Home', NULL, 1, '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(2, NULL, 3, 'Ajustes', 'Settings', NULL, 1, '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(3, NULL, 4, 'Usuarios', 'UsersList', NULL, 1, '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(4, NULL, 5, 'Mi Perfil', 'Profile', NULL, 1, '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(5, NULL, 6, 'Convocatorias', 'Offers', NULL, 1, '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(6, NULL, 6, 'Empresas', 'Companies', NULL, 1, '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(7, NULL, 6, 'Postulaciones', 'Applications', NULL, 1, '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(8, NULL, 6, 'Candidato', 'Candidate', NULL, 1, '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(9, NULL, 6, 'Mantenedores', 'JobMaintainers', NULL, 1, '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('springrandalf@gmail.com', '$2y$12$rr9Etbtr180KhIThCWp0ROxc.zsaTBBcrr8CivCxoueq/F7bqK8ey', '2026-06-29 20:22:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `person`
--

CREATE TABLE `person` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `document_number` varchar(255) NOT NULL,
  `names` varchar(255) NOT NULL,
  `phone` varchar(9) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `native_language` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `person`
--

INSERT INTO `person` (`id`, `document_type`, `document_number`, `names`, `phone`, `email`, `sex`, `birth_date`, `native_language`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'DNI', '10000001', 'Admin Test 1', '999999999', 'admin1@test.com', NULL, NULL, NULL, '2026-06-26 16:59:58', '2026-06-26 16:59:58', NULL),
(2, 'DNI', '10000002', 'Admin Test 2', '999999999', 'admin2@test.com', NULL, NULL, NULL, '2026-06-26 16:59:58', '2026-06-26 16:59:58', NULL),
(3, 'DNI', '10000003', 'Admin Test 3', '999999999', 'admin3@test.com', NULL, NULL, NULL, '2026-06-26 16:59:58', '2026-06-26 16:59:58', NULL),
(4, 'DNI', '20000001', 'Docente Test 1', '988888888', 'docente1@test.com', NULL, NULL, NULL, '2026-06-26 16:59:59', '2026-06-26 16:59:59', NULL),
(5, 'DNI', '20000002', 'Docente Test 2', '988888888', 'docente2@test.com', NULL, NULL, NULL, '2026-06-26 16:59:59', '2026-06-26 16:59:59', NULL),
(6, 'DNI', '20000003', 'Docente Test 3', '988888888', 'docente3@test.com', NULL, NULL, NULL, '2026-06-26 16:59:59', '2026-06-26 16:59:59', NULL),
(7, 'DNI', '300000001', 'Estudiante Test 1', '977777777', 'estudiante1@test.com', NULL, NULL, NULL, '2026-06-26 16:59:59', '2026-06-26 16:59:59', NULL),
(8, 'DNI', '300000002', 'Estudiante Test 2', '977777777', 'estudiante2@test.com', NULL, NULL, NULL, '2026-06-26 17:00:00', '2026-06-26 17:00:00', NULL),
(9, 'DNI', '300000003', 'Estudiante Test 3', '977777777', 'estudiante3@test.com', NULL, NULL, NULL, '2026-06-26 17:00:00', '2026-06-26 17:00:00', NULL),
(10, 'DNI', '300000004', 'Estudiante Test 4', '977777777', 'estudiante4@test.com', NULL, NULL, NULL, '2026-06-26 17:00:00', '2026-06-26 17:00:00', NULL),
(11, 'DNI', '300000005', 'Estudiante Test 5', '977777777', 'estudiante5@test.com', NULL, NULL, NULL, '2026-06-26 17:00:00', '2026-06-26 17:00:00', NULL),
(12, 'DNI', '300000006', 'Estudiante Test 6', '977777777', 'estudiante6@test.com', NULL, NULL, NULL, '2026-06-26 17:00:00', '2026-06-26 17:00:00', NULL),
(13, 'DNI', '300000007', 'Estudiante Test 7', '977777777', 'estudiante7@test.com', NULL, NULL, NULL, '2026-06-26 17:00:01', '2026-06-26 17:00:01', NULL),
(14, 'DNI', '300000008', 'Estudiante Test 8', '977777777', 'estudiante8@test.com', NULL, NULL, NULL, '2026-06-26 17:00:01', '2026-06-26 17:00:01', NULL),
(15, 'DNI', '300000009', 'Estudiante Test 9', '977777777', 'estudiante9@test.com', NULL, NULL, NULL, '2026-06-26 17:00:01', '2026-06-26 17:00:01', NULL),
(16, 'DNI', '300000010', 'Estudiante Test 10', '977777777', 'estudiante10@test.com', NULL, NULL, NULL, '2026-06-26 17:00:01', '2026-06-26 17:00:01', NULL),
(17, 'DNI', '300000011', 'Estudiante Test 11', '977777777', 'estudiante11@test.com', NULL, NULL, NULL, '2026-06-26 17:00:02', '2026-06-26 17:00:02', NULL),
(18, 'DNI', '300000012', 'Estudiante Test 12', '977777777', 'estudiante12@test.com', NULL, NULL, NULL, '2026-06-26 17:00:02', '2026-06-26 17:00:02', NULL),
(19, 'DNI', '300000013', 'Estudiante Test 13', '977777777', 'estudiante13@test.com', NULL, NULL, NULL, '2026-06-26 17:00:02', '2026-06-26 17:00:02', NULL),
(20, 'DNI', '300000014', 'Estudiante Test 14', '977777777', 'estudiante14@test.com', NULL, NULL, NULL, '2026-06-26 17:00:02', '2026-06-26 17:00:02', NULL),
(21, 'DNI', '300000015', 'Estudiante Test 15', '977777777', 'estudiante15@test.com', NULL, NULL, NULL, '2026-06-26 17:00:02', '2026-06-26 17:00:02', NULL),
(22, 'DNI', '77966489', 'Alex Lopez', '94378829', 'springrandalf@gmail.com', NULL, NULL, NULL, '2026-06-26 17:07:58', '2026-06-26 17:07:58', NULL),
(23, 'DNI', '70014523', 'Ana Lucía Pérez Ramos', '987654321', 'ana.perez@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:05', '2026-06-26 17:15:05', NULL),
(24, 'DNI', '70014524', 'Carlos Eduardo Rojas Díaz', '987654322', 'carlos.rojas@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:06', '2026-06-26 17:15:06', NULL),
(25, 'DNI', '70014525', 'María Fernanda Torres Vega', '987654323', 'maria.torres@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:06', '2026-06-26 17:15:06', NULL),
(26, 'DNI', '70014526', 'Luis Alberto Quispe Flores', '987654324', 'luis.quispe@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:06', '2026-06-26 17:15:06', NULL),
(27, 'DNI', '70014527', 'Rosa Elena Huamán Salazar', '987654325', 'rosa.huaman@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:06', '2026-06-26 17:15:06', NULL),
(28, 'DNI', '70014528', 'Juan Diego Mendoza Castro', '987654326', 'juan.mendoza@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:06', '2026-06-26 17:15:06', NULL),
(29, 'DNI', '70014529', 'Valeria Sofía Chávez Núñez', '987654327', 'valeria.chavez@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:07', '2026-06-26 17:15:07', NULL),
(30, 'DNI', '70014530', 'Pedro Miguel García León', '987654328', 'pedro.garcia@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:07', '2026-06-26 17:15:07', NULL),
(31, 'DNI', '70014531', 'Camila Alejandra Soto Ríos', '987654329', 'camila.soto@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:07', '2026-06-26 17:15:07', NULL),
(32, 'DNI', '70014532', 'Diego Armando Paredes López', '987654330', 'diego.paredes@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:07', '2026-06-26 17:15:07', NULL),
(403, 'DNI', '70000002', 'Juan Campos Gutiérrez', '910000002', 'estudiante.juan.campos.gutierrez.2@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:21', '2026-06-27 16:26:21', NULL),
(404, 'DNI', '70000003', 'Brenda Cáceres Arias', '910000003', 'docente.brenda.caceres.arias.3@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:22', '2026-06-27 16:26:22', NULL),
(405, 'DNI', '70000007', 'Andrea Espinoza León', '910000007', 'estudiante.andrea.espinoza.leon.7@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:22', '2026-06-27 16:26:22', NULL),
(406, 'DNI', '70000008', 'Milagros Aguilar Herrera', '910000008', 'estudiante.milagros.aguilar.herrera.8@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:23', '2026-06-27 16:26:23', NULL),
(407, 'DNI', '70000010', 'Fernando Bravo Ramírez', '910000010', 'docente.fernando.bravo.ramirez.10@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:23', '2026-06-27 16:26:23', NULL),
(408, 'DNI', '70000012', 'José Arias Cruz', '910000012', 'docente.jose.arias.cruz.12@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:23', '2026-06-27 16:26:23', NULL),
(409, 'DNI', '70000013', 'Camila Castillo García', '910000013', 'docente.camila.castillo.garcia.13@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:24', '2026-06-27 16:26:24', NULL),
(410, 'DNI', '70000015', 'Karla Arias Torres', '910000015', 'docente.karla.arias.torres.15@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:24', '2026-06-27 16:26:24', NULL),
(411, 'DNI', '70000016', 'Yessica Ortega Ortega', '910000016', 'docente.yessica.ortega.ortega.16@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:24', '2026-06-27 16:26:24', NULL),
(412, 'DNI', '70000018', 'Alex Vásquez Salazar', '910000018', 'docente.alex.vasquez.salazar.18@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:25', '2026-06-27 16:26:25', NULL),
(413, 'DNI', '70000020', 'Diana Sánchez Cruz', '910000020', 'docente.diana.sanchez.cruz.20@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:25', '2026-06-27 16:26:25', NULL),
(414, 'DNI', '70000021', 'Manuel Bravo Vásquez', '910000021', 'estudiante.manuel.bravo.vasquez.21@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:25', '2026-06-27 16:26:25', NULL),
(415, 'DNI', '70000022', 'Alonso Rojas León', '910000022', 'estudiante.alonso.rojas.leon.22@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:26', '2026-06-27 16:26:26', NULL),
(416, 'DNI', '70000023', 'Gabriela Reyes Ortega', '910000023', 'estudiante.gabriela.reyes.ortega.23@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:26', '2026-06-27 16:26:26', NULL),
(417, 'DNI', '70000024', 'Diana Gutiérrez Ortega', '910000024', 'docente.diana.gutierrez.ortega.24@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:26', '2026-06-27 16:26:26', NULL),
(418, 'DNI', '70000025', 'Vanessa Mendoza Vargas', '910000025', 'docente.vanessa.mendoza.vargas.25@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:26', '2026-06-27 16:26:26', NULL),
(419, 'DNI', '70000026', 'Wilmer Salazar Rojas', '910000026', 'estudiante.wilmer.salazar.rojas.26@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:26', '2026-06-27 16:26:26', NULL),
(420, 'DNI', '70000027', 'Camila Pérez Silva', '910000027', 'estudiante.camila.perez.silva.27@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:27', '2026-06-27 16:26:27', NULL),
(421, 'DNI', '70000029', 'Silvia Vásquez Aguilar', '910000029', 'estudiante.silvia.vasquez.aguilar.29@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:27', '2026-06-27 16:26:27', NULL),
(422, 'DNI', '70000030', 'Brenda García Aguilar', '910000030', 'docente.brenda.garcia.aguilar.30@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:27', '2026-06-27 16:26:27', NULL),
(423, 'DNI', '70000032', 'Milagros Campos Mendoza', '910000032', 'docente.milagros.campos.mendoza.32@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:28', '2026-06-27 16:26:28', NULL),
(424, 'DNI', '70000033', 'Ruth Peña García', '910000033', 'docente.ruth.pena.garcia.33@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:28', '2026-06-27 16:26:28', NULL),
(425, 'DNI', '70000034', 'Rosa Campos Navarro', '910000034', 'estudiante.rosa.campos.navarro.34@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:28', '2026-06-27 16:26:28', NULL),
(426, 'DNI', '70000035', 'Sofía Espinoza Cáceres', '910000035', 'docente.sofia.espinoza.caceres.35@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:28', '2026-06-27 16:26:28', NULL),
(427, 'DNI', '70000036', 'Gabriela Calderón Rojas', '910000036', 'docente.gabriela.calderon.rojas.36@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:29', '2026-06-27 16:26:29', NULL),
(428, 'DNI', '70000038', 'Sofía Campos Peña', '910000038', 'estudiante.sofia.campos.pena.38@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:29', '2026-06-27 16:26:29', NULL),
(429, 'DNI', '70000039', 'Vanessa Vega Ortega', '910000039', 'estudiante.vanessa.vega.ortega.39@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:29', '2026-06-27 16:26:29', NULL),
(430, 'DNI', '70000040', 'Pablo Herrera Herrera', '910000040', 'docente.pablo.herrera.herrera.40@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:29', '2026-06-27 16:26:29', NULL),
(431, 'DNI', '70000042', 'Pablo García Pérez', '910000042', 'docente.pablo.garcia.perez.42@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:30', '2026-06-27 16:26:30', NULL),
(432, 'DNI', '70000044', 'Paola Arias Peña', '910000044', 'docente.paola.arias.pena.44@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:30', '2026-06-27 16:26:30', NULL),
(433, 'DNI', '70000045', 'Joel Vargas Valdez', '910000045', 'estudiante.joel.vargas.valdez.45@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:31', '2026-06-27 16:26:31', NULL),
(434, 'DNI', '70000047', 'Estefany Silva Silva', '910000047', 'estudiante.estefany.silva.silva.47@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:31', '2026-06-27 16:26:31', NULL),
(435, 'DNI', '70000049', 'Renato Navarro Flores', '910000049', 'docente.renato.navarro.flores.49@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:31', '2026-06-27 16:26:31', NULL),
(436, 'DNI', '70000050', 'Brenda Flores Mejía', '910000050', 'docente.brenda.flores.mejia.50@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:32', '2026-06-27 16:26:32', NULL),
(437, 'DNI', '70000052', 'Luis Díaz Bravo', '910000052', 'docente.luis.diaz.bravo.52@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:32', '2026-06-27 16:26:32', NULL),
(438, 'DNI', '70000054', 'Miguel Rojas Navarro', '910000054', 'docente.miguel.rojas.navarro.54@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:32', '2026-06-27 16:26:32', NULL),
(439, 'DNI', '70000059', 'María Reyes Rojas', '910000059', 'docente.maria.reyes.rojas.59@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:34', '2026-06-27 16:26:34', NULL),
(440, 'DNI', '70000060', 'Alex Aguilar Arias', '910000060', 'docente.alex.aguilar.arias.60@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:34', '2026-06-27 16:26:34', NULL),
(441, 'DNI', '70000061', 'Ruth Gutiérrez Pérez', '910000061', 'docente.ruth.gutierrez.perez.61@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:34', '2026-06-27 16:26:34', NULL),
(442, 'DNI', '70000062', 'Carlos Herrera Ortega', '910000062', 'estudiante.carlos.herrera.ortega.62@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:34', '2026-06-27 16:26:34', NULL),
(443, 'DNI', '70000064', 'Cristian Paredes Ramírez', '910000064', 'docente.cristian.paredes.ramirez.64@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:35', '2026-06-27 16:26:35', NULL),
(444, 'DNI', '70000065', 'Fernando Pérez Díaz', '910000065', 'estudiante.fernando.perez.diaz.65@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:35', '2026-06-27 16:26:35', NULL),
(445, 'DNI', '70000066', 'Valeria Cáceres Torres', '910000066', 'estudiante.valeria.caceres.torres.66@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:35', '2026-06-27 16:26:35', NULL),
(446, 'DNI', '70000067', 'Diego Valdez Chávez', '910000067', 'docente.diego.valdez.chavez.67@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:35', '2026-06-27 16:26:35', NULL),
(447, 'DNI', '70000068', 'Alonso Quispe Torres', '910000068', 'estudiante.alonso.quispe.torres.68@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:35', '2026-06-27 16:26:35', NULL),
(448, 'DNI', '70000069', 'Gabriela Sánchez Silva', '910000069', 'docente.gabriela.sanchez.silva.69@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:36', '2026-06-27 16:26:36', NULL),
(449, 'DNI', '70000070', 'Rosa Quispe Reyes', '910000070', 'estudiante.rosa.quispe.reyes.70@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:36', '2026-06-27 16:26:36', NULL),
(450, 'DNI', '70000072', 'María Aguilar Aguilar', '910000072', 'docente.maria.aguilar.aguilar.72@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:36', '2026-06-27 16:26:36', NULL),
(451, 'DNI', '70000073', 'Daniela Huamán Carrillo', '910000073', 'docente.daniela.huaman.carrillo.73@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:37', '2026-06-27 16:26:37', NULL),
(452, 'DNI', '70000075', 'Patricia Carrillo Arias', '910000075', 'docente.patricia.carrillo.arias.75@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:37', '2026-06-27 16:26:37', NULL),
(453, 'DNI', '70000079', 'Lorena Paredes Campos', '910000079', 'docente.lorena.paredes.campos.79@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:38', '2026-06-27 16:26:38', NULL),
(454, 'DNI', '70000081', 'Pedro García Vargas', '910000081', 'estudiante.pedro.garcia.vargas.81@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:38', '2026-06-27 16:26:38', NULL),
(455, 'DNI', '70000082', 'Wilmer Aguilar Ortega', '910000082', 'estudiante.wilmer.aguilar.ortega.82@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:39', '2026-06-27 16:26:39', NULL),
(456, 'DNI', '70000083', 'Sofía Bravo Castillo', '910000083', 'estudiante.sofia.bravo.castillo.83@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:39', '2026-06-27 16:26:39', NULL),
(457, 'DNI', '70000084', 'Manuel Rojas Mejía', '910000084', 'docente.manuel.rojas.mejia.84@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:39', '2026-06-27 16:26:39', NULL),
(458, 'DNI', '70000086', 'Elena Herrera Huamán', '910000086', 'estudiante.elena.herrera.huaman.86@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:39', '2026-06-27 16:26:39', NULL),
(459, 'DNI', '70000087', 'Joel Peña Salazar', '910000087', 'estudiante.joel.pena.salazar.87@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:40', '2026-06-27 16:26:40', NULL),
(460, 'DNI', '70000088', 'Bryan Navarro Pérez', '910000088', 'estudiante.bryan.navarro.perez.88@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:40', '2026-06-27 16:26:40', NULL),
(461, 'DNI', '70000089', 'Alex Chávez Calderón', '910000089', 'docente.alex.chavez.calderon.89@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:40', '2026-06-27 16:26:40', NULL),
(462, 'DNI', '70000092', 'Lorena León Rojas', '910000092', 'estudiante.lorena.leon.rojas.92@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:41', '2026-06-27 16:26:41', NULL),
(463, 'DNI', '70000093', 'Lucía Campos León', '910000093', 'estudiante.lucia.campos.leon.93@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:41', '2026-06-27 16:26:41', NULL),
(464, 'DNI', '70000094', 'Hugo Mendoza Navarro', '910000094', 'estudiante.hugo.mendoza.navarro.94@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:41', '2026-06-27 16:26:41', NULL),
(465, 'DNI', '70000096', 'Lucía Espinoza Medina', '910000096', 'estudiante.lucia.espinoza.medina.96@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:42', '2026-06-27 16:26:42', NULL),
(466, 'DNI', '70000098', 'Ruth Díaz Espinoza', '910000098', 'docente.ruth.diaz.espinoza.98@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:42', '2026-06-27 16:26:42', NULL),
(467, 'DNI', '70000099', 'Wilmer Mendoza Salazar', '910000099', 'estudiante.wilmer.mendoza.salazar.99@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:42', '2026-06-27 16:26:42', NULL),
(468, 'DNI', '70000101', 'Valeria Chávez Paredes', '910000101', 'docente.valeria.chavez.paredes.101@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:43', '2026-06-27 16:26:43', NULL),
(469, 'DNI', '70000102', 'José Condori Medina', '910000102', 'docente.jose.condori.medina.102@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:43', '2026-06-27 16:26:43', NULL),
(470, 'DNI', '70000103', 'Carmen Herrera Ortega', '910000103', 'docente.carmen.herrera.ortega.103@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:43', '2026-06-27 16:26:43', NULL),
(471, 'DNI', '70000105', 'Diana Gutiérrez Valdez', '910000105', 'docente.diana.gutierrez.valdez.105@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:43', '2026-06-27 16:26:43', NULL),
(472, 'DNI', '70000106', 'Camila Espinoza Bravo', '910000106', 'docente.camila.espinoza.bravo.106@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:44', '2026-06-27 16:26:44', NULL),
(473, 'DNI', '70000107', 'Carlos Quispe Mejía', '910000107', 'docente.carlos.quispe.mejia.107@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:44', '2026-06-27 16:26:44', NULL),
(474, 'DNI', '70000109', 'Juan Mendoza Reyes', '910000109', 'estudiante.juan.mendoza.reyes.109@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:44', '2026-06-27 16:26:44', NULL),
(475, 'DNI', '70000110', 'Raúl Campos Cáceres', '910000110', 'estudiante.raul.campos.caceres.110@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:45', '2026-06-27 16:26:45', NULL),
(476, 'DNI', '70000111', 'Pedro Espinoza Morales', '910000111', 'estudiante.pedro.espinoza.morales.111@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:45', '2026-06-27 16:26:45', NULL),
(477, 'DNI', '70000112', 'Patricia Quispe Torres', '910000112', 'estudiante.patricia.quispe.torres.112@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:45', '2026-06-27 16:26:45', NULL),
(478, 'DNI', '70000113', 'Luis Bravo Condori', '910000113', 'estudiante.luis.bravo.condori.113@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:45', '2026-06-27 16:26:45', NULL),
(479, 'DNI', '70000115', 'Gabriela Castillo Paredes', '910000115', 'docente.gabriela.castillo.paredes.115@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:46', '2026-06-27 16:26:46', NULL),
(480, 'DNI', '70000116', 'Rosa Paredes Mendoza', '910000116', 'estudiante.rosa.paredes.mendoza.116@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:46', '2026-06-27 16:26:46', NULL),
(481, 'DNI', '70000117', 'Ana Mejía Bravo', '910000117', 'docente.ana.mejia.bravo.117@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:46', '2026-06-27 16:26:46', NULL),
(482, 'DNI', '70000118', 'Ruth Flores Arias', '910000118', 'estudiante.ruth.flores.arias.118@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:46', '2026-06-27 16:26:46', NULL),
(483, 'DNI', '70000119', 'Andrés Vargas Vargas', '910000119', 'docente.andres.vargas.vargas.119@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:46', '2026-06-27 16:26:46', NULL),
(484, 'DNI', '70000120', 'Gustavo Herrera Campos', '910000120', 'docente.gustavo.herrera.campos.120@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:47', '2026-06-27 16:26:47', NULL),
(485, 'DNI', '70000121', 'Paola Torres Huamán', '910000121', 'estudiante.paola.torres.huaman.121@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:47', '2026-06-27 16:26:47', NULL),
(486, 'DNI', '70000122', 'Manuel Condori Calderón', '910000122', 'estudiante.manuel.condori.calderon.122@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:47', '2026-06-27 16:26:47', NULL),
(487, 'DNI', '70000123', 'Ricardo Espinoza Mejía', '910000123', 'estudiante.ricardo.espinoza.mejia.123@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:47', '2026-06-27 16:26:47', NULL),
(488, 'DNI', '70000124', 'Karla Ramírez Torres', '910000124', 'docente.karla.ramirez.torres.124@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:48', '2026-06-27 16:26:48', NULL),
(489, 'DNI', '70000126', 'Joel Arias Arias', '910000126', 'estudiante.joel.arias.arias.126@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:48', '2026-06-27 16:26:48', NULL),
(490, 'DNI', '70000128', 'Jorge Carrillo Ramírez', '910000128', 'estudiante.jorge.carrillo.ramirez.128@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:48', '2026-06-27 16:26:48', NULL),
(491, 'DNI', '70000130', 'Daniela Campos Bravo', '910000130', 'estudiante.daniela.campos.bravo.130@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:49', '2026-06-27 16:26:49', NULL),
(492, 'DNI', '70000131', 'José Valdez Quispe', '910000131', 'estudiante.jose.valdez.quispe.131@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:49', '2026-06-27 16:26:49', NULL),
(493, 'DNI', '70000132', 'Paola Valdez Cruz', '910000132', 'docente.paola.valdez.cruz.132@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:49', '2026-06-27 16:26:49', NULL),
(494, 'DNI', '70000135', 'Erick Espinoza Chávez', '910000135', 'docente.erick.espinoza.chavez.135@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:50', '2026-06-27 16:26:50', NULL),
(495, 'DNI', '70000136', 'Patricia Morales Díaz', '910000136', 'estudiante.patricia.morales.diaz.136@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:50', '2026-06-27 16:26:50', NULL),
(496, 'DNI', '70000137', 'Diana Cruz Navarro', '910000137', 'estudiante.diana.cruz.navarro.137@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:50', '2026-06-27 16:26:50', NULL),
(497, 'DNI', '70000138', 'Ricardo Valdez Castillo', '910000138', 'docente.ricardo.valdez.castillo.138@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:51', '2026-06-27 16:26:51', NULL),
(498, 'DNI', '70000139', 'Yessica Cáceres Medina', '910000139', 'docente.yessica.caceres.medina.139@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:51', '2026-06-27 16:26:51', NULL),
(499, 'DNI', '70000140', 'Yessica Gutiérrez Mejía', '910000140', 'estudiante.yessica.gutierrez.mejia.140@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:51', '2026-06-27 16:26:51', NULL),
(500, 'DNI', '70000141', 'José Valdez Castillo', '910000141', 'estudiante.jose.valdez.castillo.141@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:51', '2026-06-27 16:26:51', NULL),
(501, 'DNI', '70000142', 'Carlos Reyes Peña', '910000142', 'estudiante.carlos.reyes.pena.142@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:51', '2026-06-27 16:26:51', NULL),
(502, 'DNI', '70000144', 'Alex Ramírez Valdez', '910000144', 'docente.alex.ramirez.valdez.144@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:52', '2026-06-27 16:26:52', NULL),
(503, 'DNI', '70000146', 'Natalia León Cruz', '910000146', 'estudiante.natalia.leon.cruz.146@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:52', '2026-06-27 16:26:52', NULL),
(504, 'DNI', '70000147', 'Gustavo Calderón Cruz', '910000147', 'docente.gustavo.calderon.cruz.147@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:52', '2026-06-27 16:26:52', NULL),
(505, 'DNI', '70000148', 'Carmen Paredes Bravo', '910000148', 'docente.carmen.paredes.bravo.148@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:53', '2026-06-27 16:26:53', NULL),
(506, 'DNI', '70000151', 'Miguel Arias Medina', '910000151', 'estudiante.miguel.arias.medina.151@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:53', '2026-06-27 16:26:53', NULL),
(507, 'DNI', '70000152', 'Sofía Gutiérrez Herrera', '910000152', 'docente.sofia.gutierrez.herrera.152@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:54', '2026-06-27 16:26:54', NULL),
(508, 'DNI', '70000153', 'Estefany Rojas Campos', '910000153', 'docente.estefany.rojas.campos.153@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:54', '2026-06-27 16:26:54', NULL),
(509, 'DNI', '70000154', 'Hugo Mendoza Morales', '910000154', 'estudiante.hugo.mendoza.morales.154@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:54', '2026-06-27 16:26:54', NULL),
(510, 'DNI', '70000155', 'Marco Quispe Cáceres', '910000155', 'estudiante.marco.quispe.caceres.155@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:54', '2026-06-27 16:26:54', NULL),
(511, 'DNI', '70000156', 'César Sánchez Calderón', '910000156', 'docente.cesar.sanchez.calderon.156@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:54', '2026-06-27 16:26:54', NULL),
(512, 'DNI', '70000157', 'Brenda León Cáceres', '910000157', 'estudiante.brenda.leon.caceres.157@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:55', '2026-06-27 16:26:55', NULL),
(513, 'DNI', '70000159', 'Carmen Condori Espinoza', '910000159', 'docente.carmen.condori.espinoza.159@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:55', '2026-06-27 16:26:55', NULL),
(514, 'DNI', '70000160', 'Brenda Reyes Navarro', '910000160', 'estudiante.brenda.reyes.navarro.160@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:55', '2026-06-27 16:26:55', NULL),
(515, 'DNI', '70000161', 'Diego Arias León', '910000161', 'docente.diego.arias.leon.161@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:55', '2026-06-27 16:26:55', NULL),
(516, 'DNI', '70000162', 'Ruth Campos Quispe', '910000162', 'estudiante.ruth.campos.quispe.162@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:56', '2026-06-27 16:26:56', NULL),
(517, 'DNI', '70000164', 'Lorena Castillo Chávez', '910000164', 'docente.lorena.castillo.chavez.164@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:56', '2026-06-27 16:26:56', NULL),
(518, 'DNI', '70000166', 'Elena Salazar Campos', '910000166', 'estudiante.elena.salazar.campos.166@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:57', '2026-06-27 16:26:57', NULL),
(519, 'DNI', '70000167', 'Alex Peña Morales', '910000167', 'docente.alex.pena.morales.167@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:57', '2026-06-27 16:26:57', NULL),
(520, 'DNI', '70000168', 'Lucía Mejía Mejía', '910000168', 'docente.lucia.mejia.mejia.168@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:57', '2026-06-27 16:26:57', NULL),
(521, 'DNI', '70000169', 'Pablo Valdez Bravo', '910000169', 'docente.pablo.valdez.bravo.169@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:57', '2026-06-27 16:26:57', NULL),
(522, 'DNI', '70000172', 'Pedro Calderón Torres', '910000172', 'estudiante.pedro.calderon.torres.172@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:58', '2026-06-27 16:26:58', NULL),
(523, 'DNI', '70000173', 'Óscar Rojas Medina', '910000173', 'estudiante.oscar.rojas.medina.173@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:58', '2026-06-27 16:26:58', NULL),
(524, 'DNI', '70000176', 'Daniela Espinoza Valdez', '910000176', 'estudiante.daniela.espinoza.valdez.176@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:59', '2026-06-27 16:26:59', NULL),
(525, 'DNI', '70000177', 'Gabriela Peña Mejía', '910000177', 'estudiante.gabriela.pena.mejia.177@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:59', '2026-06-27 16:26:59', NULL),
(526, 'DNI', '70000178', 'Estefany Herrera Pérez', '910000178', 'estudiante.estefany.herrera.perez.178@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:59', '2026-06-27 16:26:59', NULL),
(527, 'DNI', '70000180', 'Sofía Espinoza Gutiérrez', '910000180', 'estudiante.sofia.espinoza.gutierrez.180@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:00', '2026-06-27 16:27:00', NULL),
(528, 'DNI', '70000181', 'Joel Castillo Morales', '910000181', 'estudiante.joel.castillo.morales.181@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:00', '2026-06-27 16:27:00', NULL),
(529, 'DNI', '70000182', 'Joel Valdez Huamán', '910000182', 'docente.joel.valdez.huaman.182@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:00', '2026-06-27 16:27:00', NULL),
(530, 'DNI', '70000183', 'Tatiana Campos Espinoza', '910000183', 'estudiante.tatiana.campos.espinoza.183@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:00', '2026-06-27 16:27:00', NULL),
(531, 'DNI', '70000185', 'Carlos Espinoza Peña', '910000185', 'docente.carlos.espinoza.pena.185@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:01', '2026-06-27 16:27:01', NULL),
(532, 'DNI', '70000187', 'Valeria Quispe Navarro', '910000187', 'estudiante.valeria.quispe.navarro.187@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:01', '2026-06-27 16:27:01', NULL),
(533, 'DNI', '70000190', 'Sofía Quispe Bravo', '910000190', 'docente.sofia.quispe.bravo.190@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:02', '2026-06-27 16:27:02', NULL),
(534, 'DNI', '70000192', 'Manuel Salazar Huamán', '910000192', 'docente.manuel.salazar.huaman.192@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:02', '2026-06-27 16:27:02', NULL),
(535, 'DNI', '70000193', 'Raúl Arias Vega', '910000193', 'estudiante.raul.arias.vega.193@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:02', '2026-06-27 16:27:02', NULL),
(536, 'DNI', '70000194', 'Raúl Campos Condori', '910000194', 'docente.raul.campos.condori.194@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:03', '2026-06-27 16:27:03', NULL),
(537, 'DNI', '70000195', 'Andrea Medina León', '910000195', 'docente.andrea.medina.leon.195@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:03', '2026-06-27 16:27:03', NULL),
(538, 'DNI', '70000196', 'Marco Campos Mendoza', '910000196', 'estudiante.marco.campos.mendoza.196@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:03', '2026-06-27 16:27:03', NULL),
(539, 'DNI', '70000197', 'Pablo Espinoza Quispe', '910000197', 'estudiante.pablo.espinoza.quispe.197@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:03', '2026-06-27 16:27:03', NULL),
(540, 'DNI', '70000198', 'Yessica Mejía Chávez', '910000198', 'estudiante.yessica.mejia.chavez.198@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:03', '2026-06-27 16:27:03', NULL),
(541, 'DNI', '70000201', 'Elena Condori Castillo', '910000201', 'docente.elena.condori.castillo.201@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:04', '2026-06-27 16:27:04', NULL),
(542, 'DNI', '70000202', 'Karla Sánchez Espinoza', '910000202', 'estudiante.karla.sanchez.espinoza.202@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:04', '2026-06-27 16:27:04', NULL),
(543, 'DNI', '70000203', 'Raúl Medina León', '910000203', 'docente.raul.medina.leon.203@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:05', '2026-06-27 16:27:05', NULL),
(544, 'DNI', '70000204', 'Gustavo Silva Vega', '910000204', 'estudiante.gustavo.silva.vega.204@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:05', '2026-06-27 16:27:05', NULL),
(545, 'DNI', '70000205', 'Karla Ramírez Carrillo', '910000205', 'docente.karla.ramirez.carrillo.205@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:05', '2026-06-27 16:27:05', NULL),
(546, 'DNI', '70000206', 'Mónica Paredes Ramírez', '910000206', 'docente.monica.paredes.ramirez.206@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:05', '2026-06-27 16:27:05', NULL),
(547, 'DNI', '70000207', 'Patricia Espinoza Huamán', '910000207', 'docente.patricia.espinoza.huaman.207@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:05', '2026-06-27 16:27:05', NULL),
(548, 'DNI', '70000210', 'Elena Torres Castillo', '910000210', 'docente.elena.torres.castillo.210@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:06', '2026-06-27 16:27:06', NULL),
(549, 'DNI', '70000211', 'Erick Mejía Medina', '910000211', 'estudiante.erick.mejia.medina.211@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:06', '2026-06-27 16:27:06', NULL),
(550, 'DNI', '70000212', 'Renato Chávez Castillo', '910000212', 'estudiante.renato.chavez.castillo.212@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:06', '2026-06-27 16:27:06', NULL),
(551, 'DNI', '70000213', 'Alex Vargas Silva', '910000213', 'estudiante.alex.vargas.silva.213@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:07', '2026-06-27 16:27:07', NULL),
(552, 'DNI', '70000215', 'Diana Vásquez Chávez', '910000215', 'estudiante.diana.vasquez.chavez.215@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:07', '2026-06-27 16:27:07', NULL),
(553, 'DNI', '70000217', 'Pablo Silva Condori', '910000217', 'estudiante.pablo.silva.condori.217@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:08', '2026-06-27 16:27:08', NULL),
(554, 'DNI', '70000218', 'Daniela Arias Gutiérrez', '910000218', 'estudiante.daniela.arias.gutierrez.218@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:08', '2026-06-27 16:27:08', NULL),
(555, 'DNI', '70000219', 'Víctor León García', '910000219', 'docente.victor.leon.garcia.219@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:08', '2026-06-27 16:27:08', NULL),
(556, 'DNI', '70000220', 'Brenda Aguilar Gutiérrez', '910000220', 'docente.brenda.aguilar.gutierrez.220@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:08', '2026-06-27 16:27:08', NULL),
(557, 'DNI', '70000221', 'Sofía Chávez Quispe', '910000221', 'estudiante.sofia.chavez.quispe.221@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:08', '2026-06-27 16:27:08', NULL),
(558, 'DNI', '70000223', 'Yessica Vega Medina', '910000223', 'docente.yessica.vega.medina.223@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:09', '2026-06-27 16:27:09', NULL),
(559, 'DNI', '70000224', 'Claudia Díaz Peña', '910000224', 'estudiante.claudia.diaz.pena.224@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:09', '2026-06-27 16:27:09', NULL),
(560, 'DNI', '70000225', 'Tatiana Reyes Arias', '910000225', 'estudiante.tatiana.reyes.arias.225@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:09', '2026-06-27 16:27:09', NULL),
(561, 'DNI', '70000227', 'Sofía Gutiérrez León', '910000227', 'estudiante.sofia.gutierrez.leon.227@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:10', '2026-06-27 16:27:10', NULL),
(562, 'DNI', '70000229', 'Raúl Cáceres Salazar', '910000229', 'estudiante.raul.caceres.salazar.229@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:10', '2026-06-27 16:27:10', NULL),
(563, 'DNI', '70000230', 'Yessica Quispe Reyes', '910000230', 'estudiante.yessica.quispe.reyes.230@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:10', '2026-06-27 16:27:10', NULL),
(564, 'DNI', '70000232', 'Diana Torres León', '910000232', 'docente.diana.torres.leon.232@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:11', '2026-06-27 16:27:11', NULL),
(565, 'DNI', '70000234', 'Mónica Huamán Mendoza', '910000234', 'docente.monica.huaman.mendoza.234@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:11', '2026-06-27 16:27:11', NULL),
(566, 'DNI', '70000236', 'Karla Herrera Ortega', '910000236', 'docente.karla.herrera.ortega.236@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:12', '2026-06-27 16:27:12', NULL),
(567, 'DNI', '70000239', 'Wilmer Peña Navarro', '910000239', 'docente.wilmer.pena.navarro.239@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:12', '2026-06-27 16:27:12', NULL),
(568, 'DNI', '70000240', 'Andrés Chávez Mendoza', '910000240', 'docente.andres.chavez.mendoza.240@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:12', '2026-06-27 16:27:12', NULL),
(569, 'DNI', '70000242', 'César Aguilar Mejía', '910000242', 'estudiante.cesar.aguilar.mejia.242@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:13', '2026-06-27 16:27:13', NULL),
(570, 'DNI', '70000243', 'Wilmer Navarro Morales', '910000243', 'estudiante.wilmer.navarro.morales.243@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:13', '2026-06-27 16:27:13', NULL),
(571, 'DNI', '70000246', 'Sofía Campos Castillo', '910000246', 'estudiante.sofia.campos.castillo.246@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:14', '2026-06-27 16:27:14', NULL),
(572, 'DNI', '70000247', 'Jorge Rojas Peña', '910000247', 'docente.jorge.rojas.pena.247@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:14', '2026-06-27 16:27:14', NULL),
(573, 'DNI', '70000248', 'Wilmer Herrera Reyes', '910000248', 'estudiante.wilmer.herrera.reyes.248@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:14', '2026-06-27 16:27:14', NULL),
(574, 'DNI', '70000250', 'Ruth Silva Vega', '910000250', 'docente.ruth.silva.vega.250@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:15', '2026-06-27 16:27:15', NULL),
(575, 'DNI', '70000251', 'Andrea Carrillo Quispe', '910000251', 'docente.andrea.carrillo.quispe.251@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:15', '2026-06-27 16:27:15', NULL),
(576, 'DNI', '70000252', 'Patricia Vega Campos', '910000252', 'estudiante.patricia.vega.campos.252@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:15', '2026-06-27 16:27:15', NULL),
(577, 'DNI', '70000254', 'Juan Flores Pérez', '910000254', 'estudiante.juan.flores.perez.254@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:15', '2026-06-27 16:27:15', NULL),
(578, 'DNI', '70000255', 'Jorge Cáceres Navarro', '910000255', 'estudiante.jorge.caceres.navarro.255@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:16', '2026-06-27 16:27:16', NULL),
(579, 'DNI', '70000256', 'César Salazar Ortega', '910000256', 'estudiante.cesar.salazar.ortega.256@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:16', '2026-06-27 16:27:16', NULL),
(580, 'DNI', '70000259', 'Renato Vega Chávez', '910000259', 'docente.renato.vega.chavez.259@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:17', '2026-06-27 16:27:17', NULL),
(581, 'DNI', '70000260', 'Daniela Bravo Campos', '910000260', 'estudiante.daniela.bravo.campos.260@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:17', '2026-06-27 16:27:17', NULL),
(582, 'DNI', '70000261', 'Mónica Arias Bravo', '910000261', 'estudiante.monica.arias.bravo.261@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:17', '2026-06-27 16:27:17', NULL),
(583, 'DNI', '70000262', 'Rosa Sánchez Chávez', '910000262', 'docente.rosa.sanchez.chavez.262@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:17', '2026-06-27 16:27:17', NULL),
(584, 'DNI', '70000265', 'Andrés Díaz Campos', '910000265', 'estudiante.andres.diaz.campos.265@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:18', '2026-06-27 16:27:18', NULL),
(585, 'DNI', '70000266', 'Pedro Carrillo Campos', '910000266', 'docente.pedro.carrillo.campos.266@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:18', '2026-06-27 16:27:18', NULL),
(586, 'DNI', '70000267', 'Andrés Reyes Carrillo', '910000267', 'docente.andres.reyes.carrillo.267@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:18', '2026-06-27 16:27:18', NULL),
(587, 'DNI', '70000269', 'Tatiana Calderón Reyes', '910000269', 'docente.tatiana.calderon.reyes.269@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:19', '2026-06-27 16:27:19', NULL),
(588, 'DNI', '70000274', 'Lorena Herrera Sánchez', '910000274', 'estudiante.lorena.herrera.sanchez.274@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:20', '2026-06-27 16:27:20', NULL),
(589, 'DNI', '70000277', 'José Pérez Peña', '910000277', 'docente.jose.perez.pena.277@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:20', '2026-06-27 16:27:20', NULL),
(590, 'DNI', '70000278', 'Fiorella Campos Navarro', '910000278', 'estudiante.fiorella.campos.navarro.278@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:21', '2026-06-27 16:27:21', NULL),
(591, 'DNI', '70000280', 'Jorge Vargas Reyes', '910000280', 'docente.jorge.vargas.reyes.280@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:21', '2026-06-27 16:27:21', NULL),
(592, 'DNI', '70000281', 'Bryan Castillo Chávez', '910000281', 'estudiante.bryan.castillo.chavez.281@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:21', '2026-06-27 16:27:21', NULL),
(593, 'DNI', '70000284', 'Luis Paredes Flores', '910000284', 'docente.luis.paredes.flores.284@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:22', '2026-06-27 16:27:22', NULL),
(594, 'DNI', '70000285', 'Alex Espinoza Torres', '910000285', 'estudiante.alex.espinoza.torres.285@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:22', '2026-06-27 16:27:22', NULL),
(595, 'DNI', '70000286', 'Carlos León Flores', '910000286', 'estudiante.carlos.leon.flores.286@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:22', '2026-06-27 16:27:22', NULL),
(596, 'DNI', '70000288', 'Alex Mendoza León', '910000288', 'docente.alex.mendoza.leon.288@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:23', '2026-06-27 16:27:23', NULL),
(597, 'DNI', '70000290', 'Alonso Flores Bravo', '910000290', 'docente.alonso.flores.bravo.290@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:23', '2026-06-27 16:27:23', NULL),
(598, 'DNI', '70000291', 'Mónica Morales Carrillo', '910000291', 'estudiante.monica.morales.carrillo.291@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:23', '2026-06-27 16:27:23', NULL),
(599, 'DNI', '70000294', 'César García Silva', '910000294', 'docente.cesar.garcia.silva.294@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:24', '2026-06-27 16:27:24', NULL),
(600, 'DNI', '70000295', 'Gustavo Ortega Calderón', '910000295', 'docente.gustavo.ortega.calderon.295@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:24', '2026-06-27 16:27:24', NULL),
(601, 'DNI', '70000296', 'Hugo Valdez Pérez', '910000296', 'docente.hugo.valdez.perez.296@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:25', '2026-06-27 16:27:25', NULL),
(602, 'DNI', '70000297', 'Estefany León Peña', '910000297', 'docente.estefany.leon.pena.297@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:25', '2026-06-27 16:27:25', NULL),
(603, 'DNI', '70000298', 'Manuel Morales Morales', '910000298', 'estudiante.manuel.morales.morales.298@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:25', '2026-06-27 16:27:25', NULL),
(604, 'DNI', '70000299', 'Silvia Silva Reyes', '910000299', 'estudiante.silvia.silva.reyes.299@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:25', '2026-06-27 16:27:25', NULL),
(605, 'DNI', '70000300', 'Alonso Condori Cruz', '910000300', 'docente.alonso.condori.cruz.300@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:25', '2026-06-27 16:27:25', NULL),
(606, 'DNI', '70000301', 'César Vega Aguilar', '910000301', 'estudiante.cesar.vega.aguilar.301@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:26', '2026-06-27 16:27:26', NULL),
(607, 'DNI', '70000302', 'Eduardo Pérez Medina', '910000302', 'estudiante.eduardo.perez.medina.302@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:26', '2026-06-27 16:27:26', NULL),
(608, 'DNI', '70000303', 'Alex Ortega Valdez', '910000303', 'docente.alex.ortega.valdez.303@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:26', '2026-06-27 16:27:26', NULL),
(609, 'DNI', '70000305', 'Pedro Campos García', '910000305', 'estudiante.pedro.campos.garcia.305@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:26', '2026-06-27 16:27:26', NULL),
(610, 'DNI', '70000307', 'José Arias Navarro', '910000307', 'estudiante.jose.arias.navarro.307@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:27', '2026-06-27 16:27:27', NULL),
(611, 'DNI', '70000309', 'Raúl Carrillo León', '910000309', 'estudiante.raul.carrillo.leon.309@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:27', '2026-06-27 16:27:27', NULL),
(612, 'DNI', '70000310', 'Hugo Cruz Ortega', '910000310', 'estudiante.hugo.cruz.ortega.310@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:28', '2026-06-27 16:27:28', NULL),
(613, 'DNI', '70000311', 'Gabriela Díaz Calderón', '910000311', 'docente.gabriela.diaz.calderon.311@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:28', '2026-06-27 16:27:28', NULL),
(614, 'DNI', '70000312', 'Valeria Bravo Espinoza', '910000312', 'estudiante.valeria.bravo.espinoza.312@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:28', '2026-06-27 16:27:28', NULL),
(615, 'DNI', '70000314', 'Joel Reyes Castillo', '910000314', 'docente.joel.reyes.castillo.314@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:28', '2026-06-27 16:27:28', NULL),
(616, 'DNI', '70000315', 'Claudia Rojas Castillo', '910000315', 'docente.claudia.rojas.castillo.315@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:29', '2026-06-27 16:27:29', NULL),
(617, 'DNI', '70000318', 'Gustavo Ortega Torres', '910000318', 'estudiante.gustavo.ortega.torres.318@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:29', '2026-06-27 16:27:29', NULL),
(618, 'DNI', '70000320', 'Carmen Valdez Reyes', '910000320', 'estudiante.carmen.valdez.reyes.320@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:30', '2026-06-27 16:27:30', NULL),
(619, 'DNI', '70000321', 'Milagros Arias Condori', '910000321', 'estudiante.milagros.arias.condori.321@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:30', '2026-06-27 16:27:30', NULL),
(620, 'DNI', '70000323', 'Lorena Espinoza Cruz', '910000323', 'docente.lorena.espinoza.cruz.323@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:30', '2026-06-27 16:27:30', NULL),
(621, 'DNI', '70000325', 'Valeria Sánchez Cruz', '910000325', 'estudiante.valeria.sanchez.cruz.325@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:31', '2026-06-27 16:27:31', NULL),
(622, 'DNI', '70000326', 'José Chávez Ramírez', '910000326', 'estudiante.jose.chavez.ramirez.326@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:31', '2026-06-27 16:27:31', NULL),
(623, 'DNI', '70000330', 'Andrea Carrillo Ortega', '910000330', 'docente.andrea.carrillo.ortega.330@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:32', '2026-06-27 16:27:32', NULL),
(624, 'DNI', '70000331', 'Hugo Torres Carrillo', '910000331', 'estudiante.hugo.torres.carrillo.331@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:32', '2026-06-27 16:27:32', NULL),
(625, 'DNI', '70000332', 'Raúl Espinoza Ramírez', '910000332', 'estudiante.raul.espinoza.ramirez.332@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:32', '2026-06-27 16:27:32', NULL),
(626, 'DNI', '70000333', 'Renato Quispe León', '910000333', 'docente.renato.quispe.leon.333@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:32', '2026-06-27 16:27:32', NULL),
(627, 'DNI', '70000335', 'Raúl Calderón Mejía', '910000335', 'docente.raul.calderon.mejia.335@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:33', '2026-06-27 16:27:33', NULL),
(628, 'DNI', '70000336', 'Ricardo Flores Ortega', '910000336', 'docente.ricardo.flores.ortega.336@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:33', '2026-06-27 16:27:33', NULL),
(629, 'DNI', '70000337', 'Carmen Flores Navarro', '910000337', 'docente.carmen.flores.navarro.337@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:33', '2026-06-27 16:27:33', NULL),
(630, 'DNI', '70000338', 'Patricia Paredes Reyes', '910000338', 'docente.patricia.paredes.reyes.338@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:34', '2026-06-27 16:27:34', NULL),
(631, 'DNI', '70000340', 'Alex García Chávez', '910000340', 'estudiante.alex.garcia.chavez.340@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:34', '2026-06-27 16:27:34', NULL),
(632, 'DNI', '70000341', 'Sofía Mejía Aguilar', '910000341', 'estudiante.sofia.mejia.aguilar.341@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:34', '2026-06-27 16:27:34', NULL),
(633, 'DNI', '70000344', 'Marco Peña García', '910000344', 'estudiante.marco.pena.garcia.344@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:35', '2026-06-27 16:27:35', NULL),
(634, 'DNI', '70000346', 'Noelia Paredes García', '910000346', 'estudiante.noelia.paredes.garcia.346@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:35', '2026-06-27 16:27:35', NULL),
(635, 'DNI', '70000347', 'César Valdez Navarro', '910000347', 'docente.cesar.valdez.navarro.347@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:36', '2026-06-27 16:27:36', NULL),
(636, 'DNI', '70000348', 'Gustavo Díaz Sánchez', '910000348', 'estudiante.gustavo.diaz.sanchez.348@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:36', '2026-06-27 16:27:36', NULL),
(637, 'DNI', '70000349', 'Marco Reyes León', '910000349', 'estudiante.marco.reyes.leon.349@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:36', '2026-06-27 16:27:36', NULL),
(638, 'DNI', '70000350', 'Joel García Huamán', '910000350', 'estudiante.joel.garcia.huaman.350@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:36', '2026-06-27 16:27:36', NULL),
(639, 'DNI', '70000351', 'Lucía Mejía Vega', '910000351', 'docente.lucia.mejia.vega.351@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:36', '2026-06-27 16:27:36', NULL),
(640, 'DNI', '70000352', 'Eduardo Chávez Pérez', '910000352', 'estudiante.eduardo.chavez.perez.352@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:37', '2026-06-27 16:27:37', NULL),
(641, 'DNI', '70000353', 'Lorena Pérez Mendoza', '910000353', 'estudiante.lorena.perez.mendoza.353@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:37', '2026-06-27 16:27:37', NULL),
(642, 'DNI', '70000357', 'Eduardo Chávez García', '910000357', 'estudiante.eduardo.chavez.garcia.357@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:38', '2026-06-27 16:27:38', NULL),
(643, 'DNI', '70000358', 'César Valdez Vega', '910000358', 'estudiante.cesar.valdez.vega.358@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:38', '2026-06-27 16:27:38', NULL),
(644, 'DNI', '70000362', 'Lucía Díaz Torres', '910000362', 'estudiante.lucia.diaz.torres.362@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:39', '2026-06-27 16:27:39', NULL),
(645, 'DNI', '70000364', 'María Rojas Cruz', '910000364', 'estudiante.maria.rojas.cruz.364@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:39', '2026-06-27 16:27:39', NULL),
(646, 'DNI', '70000365', 'Lucía Vega Carrillo', '910000365', 'docente.lucia.vega.carrillo.365@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:39', '2026-06-27 16:27:39', NULL),
(647, 'DNI', '70000367', 'Karla Salazar Vega', '910000367', 'docente.karla.salazar.vega.367@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:40', '2026-06-27 16:27:40', NULL),
(648, 'DNI', '70000369', 'Pablo Bravo Carrillo', '910000369', 'docente.pablo.bravo.carrillo.369@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:40', '2026-06-27 16:27:40', NULL),
(649, 'DNI', '70000372', 'José Calderón Cruz', '910000372', 'docente.jose.calderon.cruz.372@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:41', '2026-06-27 16:27:41', NULL),
(650, 'DNI', '70000373', 'Erick Navarro Huamán', '910000373', 'docente.erick.navarro.huaman.373@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:41', '2026-06-27 16:27:41', NULL),
(651, 'DNI', '70000375', 'Jorge Torres Flores', '910000375', 'estudiante.jorge.torres.flores.375@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:42', '2026-06-27 16:27:42', NULL),
(652, 'DNI', '70000377', 'Juan Vega Silva', '910000377', 'docente.juan.vega.silva.377@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:42', '2026-06-27 16:27:42', NULL),
(653, 'DNI', '70000378', 'Vanessa Vásquez Peña', '910000378', 'docente.vanessa.vasquez.pena.378@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:42', '2026-06-27 16:27:42', NULL),
(654, 'DNI', '70000380', 'Hugo Silva Flores', '910000380', 'estudiante.hugo.silva.flores.380@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:43', '2026-06-27 16:27:43', NULL),
(655, 'DNI', '70000381', 'Luis Medina Arias', '910000381', 'estudiante.luis.medina.arias.381@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:43', '2026-06-27 16:27:43', NULL),
(656, 'DNI', '70000382', 'María Aguilar Cruz', '910000382', 'estudiante.maria.aguilar.cruz.382@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:43', '2026-06-27 16:27:43', NULL);
INSERT INTO `person` (`id`, `document_type`, `document_number`, `names`, `phone`, `email`, `sex`, `birth_date`, `native_language`, `created_at`, `updated_at`, `deleted_at`) VALUES
(657, 'DNI', '70000383', 'Carlos Carrillo Castillo', '910000383', 'docente.carlos.carrillo.castillo.383@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:43', '2026-06-27 16:27:43', NULL),
(658, 'DNI', '70000385', 'Juan Díaz Ortega', '910000385', 'docente.juan.diaz.ortega.385@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:44', '2026-06-27 16:27:44', NULL),
(659, 'DNI', '70000386', 'Juan Peña Castillo', '910000386', 'estudiante.juan.pena.castillo.386@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:44', '2026-06-27 16:27:44', NULL),
(660, 'DNI', '70000388', 'Andrea Aguilar Vásquez', '910000388', 'docente.andrea.aguilar.vasquez.388@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:44', '2026-06-27 16:27:44', NULL),
(661, 'DNI', '70000389', 'Víctor Vásquez Pérez', '910000389', 'docente.victor.vasquez.perez.389@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:45', '2026-06-27 16:27:45', NULL),
(662, 'DNI', '70000390', 'Fiorella Quispe Castillo', '910000390', 'docente.fiorella.quispe.castillo.390@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:45', '2026-06-27 16:27:45', NULL),
(663, 'DNI', '70000391', 'Eduardo Calderón Medina', '910000391', 'docente.eduardo.calderon.medina.391@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:45', '2026-06-27 16:27:45', NULL),
(664, 'DNI', '70000393', 'Mónica Quispe Chávez', '910000393', 'docente.monica.quispe.chavez.393@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:45', '2026-06-27 16:27:45', NULL),
(665, 'DNI', '70000394', 'Camila Rojas Gutiérrez', '910000394', 'estudiante.camila.rojas.gutierrez.394@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:46', '2026-06-27 16:27:46', NULL),
(666, 'DNI', '70000395', 'Renato Salazar Flores', '910000395', 'docente.renato.salazar.flores.395@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:46', '2026-06-27 16:27:46', NULL),
(667, 'DNI', '70000396', 'Tatiana León Torres', '910000396', 'docente.tatiana.leon.torres.396@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:46', '2026-06-27 16:27:46', NULL),
(668, 'DNI', '70000398', 'Ricardo Vega Herrera', '910000398', 'estudiante.ricardo.vega.herrera.398@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:46', '2026-06-27 16:27:46', NULL),
(669, 'DNI', '70000400', 'Bryan Sánchez Vega', '910000400', 'estudiante.bryan.sanchez.vega.400@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:47', '2026-06-27 16:27:47', NULL),
(670, 'DNI', '70000402', 'Carmen Navarro Bravo', '910000402', 'docente.carmen.navarro.bravo.402@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:47', '2026-06-27 16:27:47', NULL),
(671, 'DNI', '70000404', 'Sofía León Medina', '910000404', 'docente.sofia.leon.medina.404@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:48', '2026-06-27 16:27:48', NULL),
(672, 'DNI', '70000405', 'Raúl Vásquez Castillo', '910000405', 'estudiante.raul.vasquez.castillo.405@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:48', '2026-06-27 16:27:48', NULL),
(673, 'DNI', '70000407', 'Lucía Campos Bravo', '910000407', 'estudiante.lucia.campos.bravo.407@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:48', '2026-06-27 16:27:48', NULL),
(674, 'DNI', '70000408', 'Valeria Quispe Valdez', '910000408', 'docente.valeria.quispe.valdez.408@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:49', '2026-06-27 16:27:49', NULL),
(675, 'DNI', '70000410', 'Claudia Espinoza Flores', '910000410', 'estudiante.claudia.espinoza.flores.410@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:49', '2026-06-27 16:27:49', NULL),
(676, 'DNI', '70000411', 'Jorge Mendoza Castillo', '910000411', 'docente.jorge.mendoza.castillo.411@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:49', '2026-06-27 16:27:49', NULL),
(677, 'DNI', '70000412', 'Erick Rojas Bravo', '910000412', 'docente.erick.rojas.bravo.412@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:49', '2026-06-27 16:27:49', NULL),
(678, 'DNI', '70000416', 'Camila Pérez Torres', '910000416', 'estudiante.camila.perez.torres.416@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:50', '2026-06-27 16:27:50', NULL),
(679, 'DNI', '70000417', 'Raúl León Castillo', '910000417', 'docente.raul.leon.castillo.417@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:51', '2026-06-27 16:27:51', NULL),
(680, 'DNI', '70000419', 'Gustavo Carrillo Díaz', '910000419', 'estudiante.gustavo.carrillo.diaz.419@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:51', '2026-06-27 16:27:51', NULL),
(681, 'DNI', '70000420', 'Elena Quispe Arias', '910000420', 'docente.elena.quispe.arias.420@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:51', '2026-06-27 16:27:51', NULL),
(682, 'DNI', '70000423', 'Eduardo Vega Díaz', '910000423', 'estudiante.eduardo.vega.diaz.423@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:52', '2026-06-27 16:27:52', NULL),
(683, 'DNI', '70000424', 'Lucía Ramírez Espinoza', '910000424', 'estudiante.lucia.ramirez.espinoza.424@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:52', '2026-06-27 16:27:52', NULL),
(684, 'DNI', '70000425', 'Carlos Silva Carrillo', '910000425', 'docente.carlos.silva.carrillo.425@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:52', '2026-06-27 16:27:52', NULL),
(685, 'DNI', '70000430', 'Juan Carrillo Peña', '910000430', 'docente.juan.carrillo.pena.430@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:53', '2026-06-27 16:27:53', NULL),
(686, 'DNI', '70000431', 'Pablo Torres Paredes', '910000431', 'docente.pablo.torres.paredes.431@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:54', '2026-06-27 16:27:54', NULL),
(687, 'DNI', '70000432', 'Marco Medina Ortega', '910000432', 'docente.marco.medina.ortega.432@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:54', '2026-06-27 16:27:54', NULL),
(688, 'DNI', '70000433', 'Jorge Calderón Torres', '910000433', 'docente.jorge.calderon.torres.433@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:54', '2026-06-27 16:27:54', NULL),
(689, 'DNI', '70000434', 'Rosa Quispe Peña', '910000434', 'estudiante.rosa.quispe.pena.434@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:54', '2026-06-27 16:27:54', NULL),
(690, 'DNI', '70000436', 'Juan Silva Ramírez', '910000436', 'docente.juan.silva.ramirez.436@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:55', '2026-06-27 16:27:55', NULL),
(691, 'DNI', '70000437', 'Marco Navarro Arias', '910000437', 'estudiante.marco.navarro.arias.437@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:55', '2026-06-27 16:27:55', NULL),
(692, 'DNI', '70000438', 'Carlos Vásquez Mejía', '910000438', 'estudiante.carlos.vasquez.mejia.438@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:55', '2026-06-27 16:27:55', NULL),
(693, 'DNI', '70000439', 'Óscar Torres Torres', '910000439', 'estudiante.oscar.torres.torres.439@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:55', '2026-06-27 16:27:55', NULL),
(694, 'DNI', '70000440', 'Renato Silva Ortega', '910000440', 'estudiante.renato.silva.ortega.440@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:56', '2026-06-27 16:27:56', NULL),
(695, 'DNI', '70000441', 'Daniela Flores Herrera', '910000441', 'docente.daniela.flores.herrera.441@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:56', '2026-06-27 16:27:56', NULL),
(696, 'DNI', '70000442', 'Miguel Mendoza Mejía', '910000442', 'estudiante.miguel.mendoza.mejia.442@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:56', '2026-06-27 16:27:56', NULL),
(697, 'DNI', '70000443', 'Noelia Vásquez Flores', '910000443', 'estudiante.noelia.vasquez.flores.443@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:56', '2026-06-27 16:27:56', NULL),
(698, 'DNI', '70000444', 'José Medina Carrillo', '910000444', 'docente.jose.medina.carrillo.444@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:56', '2026-06-27 16:27:56', NULL),
(699, 'DNI', '70000445', 'Patricia Vega Gutiérrez', '910000445', 'estudiante.patricia.vega.gutierrez.445@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:57', '2026-06-27 16:27:57', NULL),
(700, 'DNI', '70000449', 'Joel Herrera Carrillo', '910000449', 'estudiante.joel.herrera.carrillo.449@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:57', '2026-06-27 16:27:57', NULL),
(701, 'DNI', '70000450', 'Gabriela Gutiérrez Carrillo', '910000450', 'estudiante.gabriela.gutierrez.carrillo.450@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:58', '2026-06-27 16:27:58', NULL),
(702, 'DNI', '70000451', 'Wilmer Morales Salazar', '910000451', 'estudiante.wilmer.morales.salazar.451@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:58', '2026-06-27 16:27:58', NULL),
(703, 'DNI', '70000452', 'Marco Navarro Mejía', '910000452', 'estudiante.marco.navarro.mejia.452@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:58', '2026-06-27 16:27:58', NULL),
(704, 'DNI', '70000453', 'Diana Mendoza Rojas', '910000453', 'docente.diana.mendoza.rojas.453@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:58', '2026-06-27 16:27:58', NULL),
(705, 'DNI', '70000454', 'Pablo Valdez Cruz', '910000454', 'estudiante.pablo.valdez.cruz.454@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:59', '2026-06-27 16:27:59', NULL),
(706, 'DNI', '70000455', 'Carlos Reyes Calderón', '910000455', 'docente.carlos.reyes.calderon.455@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:59', '2026-06-27 16:27:59', NULL),
(707, 'DNI', '70000456', 'Ricardo Arias Vargas', '910000456', 'docente.ricardo.arias.vargas.456@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:59', '2026-06-27 16:27:59', NULL),
(708, 'DNI', '70000458', 'Eduardo Salazar Arias', '910000458', 'docente.eduardo.salazar.arias.458@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:59', '2026-06-27 16:27:59', NULL),
(709, 'DNI', '70000459', 'Gabriela Peña Flores', '910000459', 'docente.gabriela.pena.flores.459@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:00', '2026-06-27 16:28:00', NULL),
(710, 'DNI', '70000460', 'Joel Rojas Cáceres', '910000460', 'docente.joel.rojas.caceres.460@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:00', '2026-06-27 16:28:00', NULL),
(711, 'DNI', '70000461', 'Marco León Aguilar', '910000461', 'estudiante.marco.leon.aguilar.461@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:00', '2026-06-27 16:28:00', NULL),
(712, 'DNI', '70000463', 'Manuel Paredes García', '910000463', 'estudiante.manuel.paredes.garcia.463@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:00', '2026-06-27 16:28:00', NULL),
(713, 'DNI', '70000464', 'Joel Cáceres Díaz', '910000464', 'docente.joel.caceres.diaz.464@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:01', '2026-06-27 16:28:01', NULL),
(714, 'DNI', '70000465', 'Lucía Morales Arias', '910000465', 'docente.lucia.morales.arias.465@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:01', '2026-06-27 16:28:01', NULL),
(715, 'DNI', '70000467', 'Valeria Cáceres Cruz', '910000467', 'docente.valeria.caceres.cruz.467@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:01', '2026-06-27 16:28:01', NULL),
(716, 'DNI', '70000469', 'Hugo Castillo Reyes', '910000469', 'docente.hugo.castillo.reyes.469@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:02', '2026-06-27 16:28:02', NULL),
(717, 'DNI', '70000470', 'Pablo Paredes Cruz', '910000470', 'docente.pablo.paredes.cruz.470@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:02', '2026-06-27 16:28:02', NULL),
(718, 'DNI', '70000472', 'Ana Ortega Arias', '910000472', 'docente.ana.ortega.arias.472@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:02', '2026-06-27 16:28:02', NULL),
(719, 'DNI', '70000473', 'Alonso Flores Torres', '910000473', 'docente.alonso.flores.torres.473@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:03', '2026-06-27 16:28:03', NULL),
(720, 'DNI', '70000475', 'Andrés Díaz Silva', '910000475', 'docente.andres.diaz.silva.475@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:03', '2026-06-27 16:28:03', NULL),
(721, 'DNI', '70000476', 'Gabriela Salazar Flores', '910000476', 'docente.gabriela.salazar.flores.476@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:03', '2026-06-27 16:28:03', NULL),
(722, 'DNI', '70000477', 'Renato Cruz Navarro', '910000477', 'docente.renato.cruz.navarro.477@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:03', '2026-06-27 16:28:03', NULL),
(723, 'DNI', '70000479', 'Ana Peña Calderón', '910000479', 'docente.ana.pena.calderon.479@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:04', '2026-06-27 16:28:04', NULL),
(724, 'DNI', '70000482', 'Ana Torres Herrera', '910000482', 'docente.ana.torres.herrera.482@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:05', '2026-06-27 16:28:05', NULL),
(725, 'DNI', '70000484', 'Valeria Torres Torres', '910000484', 'docente.valeria.torres.torres.484@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:05', '2026-06-27 16:28:05', NULL),
(726, 'DNI', '70000486', 'Fiorella Navarro Condori', '910000486', 'estudiante.fiorella.navarro.condori.486@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:05', '2026-06-27 16:28:05', NULL),
(727, 'DNI', '70000487', 'Bryan Huamán Flores', '910000487', 'estudiante.bryan.huaman.flores.487@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:06', '2026-06-27 16:28:06', NULL),
(728, 'DNI', '70000488', 'Ricardo Campos Valdez', '910000488', 'estudiante.ricardo.campos.valdez.488@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:06', '2026-06-27 16:28:06', NULL),
(729, 'DNI', '70000489', 'Tatiana Vásquez Valdez', '910000489', 'docente.tatiana.vasquez.valdez.489@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:06', '2026-06-27 16:28:06', NULL),
(730, 'DNI', '70000490', 'Víctor García Medina', '910000490', 'estudiante.victor.garcia.medina.490@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:06', '2026-06-27 16:28:06', NULL),
(731, 'DNI', '70000492', 'Erick Torres Sánchez', '910000492', 'estudiante.erick.torres.sanchez.492@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:07', '2026-06-27 16:28:07', NULL),
(732, 'DNI', '70000494', 'Noelia Rojas Vargas', '910000494', 'docente.noelia.rojas.vargas.494@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:07', '2026-06-27 16:28:07', NULL),
(733, 'DNI', '70000496', 'Juan Valdez Mendoza', '910000496', 'docente.juan.valdez.mendoza.496@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:08', '2026-06-27 16:28:08', NULL),
(734, 'DNI', '70000497', 'Joel Flores Castillo', '910000497', 'docente.joel.flores.castillo.497@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:08', '2026-06-27 16:28:08', NULL),
(735, 'DNI', '70000499', 'Silvia Cáceres Rojas', '910000499', 'estudiante.silvia.caceres.rojas.499@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:08', '2026-06-27 16:28:08', NULL),
(736, 'DNI', '70000500', 'Gabriela Gutiérrez Arias', '910000500', 'estudiante.gabriela.gutierrez.arias.500@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:08', '2026-06-27 16:28:08', NULL),
(737, 'DNI', '77966489', 'Alex Lopez', '94378829', 'springrandalf@gmail.com', NULL, NULL, NULL, '2026-06-27 20:52:44', '2026-06-27 20:52:44', NULL),
(738, 'DNI', '00000000', 'Administrador Sistema', '999999999', 'springrandalf@gmail.com', NULL, NULL, NULL, '2026-06-29 15:49:53', '2026-06-29 15:49:53', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `name`, `key`, `level`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ADMINISTRADOR', 'rol_admin', 1, '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(2, 'DOCENTE', 'rol_teacher', 1, '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(3, 'ESTUDIANTE', 'rol_student', 1, '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL),
(4, 'EMPRESA', 'rol_company', 1, '2025-06-03 09:58:29', '2025-06-03 09:58:29', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_option`
--

CREATE TABLE `rol_option` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rol_id` bigint(20) UNSIGNED NOT NULL,
  `option_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `rol_option`
--

INSERT INTO `rol_option` (`id`, `rol_id`, `option_id`) VALUES
(1, 2, 1),
(2, 2, 4),
(3, 3, 1),
(4, 3, 4),
(5, 1, 1),
(6, 1, 2),
(7, 1, 3),
(8, 1, 4),
(9, 1, 6),
(10, 1, 5),
(11, 1, 7),
(12, 4, 1),
(13, 4, 5),
(14, 4, 7),
(15, 1, 9),
(16, 3, 8),
(17, 2, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_user`
--

CREATE TABLE `rol_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rol_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `rol_user`
--

INSERT INTO `rol_user` (`id`, `rol_id`, `user_id`) VALUES
(1602, 1, 1602);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system_configuration`
--

CREATE TABLE `system_configuration` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `system_configuration`
--

INSERT INTO `system_configuration` (`id`, `key`, `name`, `type`, `value`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'application_name', 'Nombre de la institución', 'string', 'IESTP Purús', '2025-06-03 09:58:28', '2026-07-01 13:57:10', NULL),
(2, 'support_emails', 'Correos de soporte', 'array', NULL, '2025-06-03 09:58:28', '2025-06-03 09:58:28', NULL),
(3, 'logo', 'Logo', 'string', '/uploads/logo_1782914218.png', '2025-06-03 09:58:28', '2026-07-01 13:56:58', NULL),
(4, 'favicon', 'Favicon', 'string', '/uploads/favicon_1782914218.png', '2025-06-03 09:58:28', '2026-07-01 13:56:58', NULL),
(5, 'banner', 'Banner', 'string', '/uploads/banner_1782914218.png', '2025-06-03 09:58:28', '2026-07-01 13:56:58', NULL),
(6, 'maximum_file_size_to_upload', 'Tamaño máximo de archivos a subir (MB)', 'number', '10', '2025-06-03 09:58:28', '2026-07-01 13:57:10', NULL),
(7, 'extensions_allowed_to_upload', 'Extensiones permitidas para subir archivos', 'array', '[{\"extension\":\"pdf\",\"permitted\":true},{\"extension\":\"doc\",\"permitted\":true},{\"extension\":\"docx\",\"permitted\":true},{\"extension\":\"xls\",\"permitted\":true},{\"extension\":\"xlsx\",\"permitted\":true},{\"extension\":\"ppt\",\"permitted\":true},{\"extension\":\"pptx\",\"permitted\":true},{\"extension\":\"zip\",\"permitted\":true},{\"extension\":\"rar\",\"permitted\":true},{\"extension\":\"jpg\",\"permitted\":true},{\"extension\":\"jpeg\",\"permitted\":true},{\"extension\":\"png\",\"permitted\":true},{\"extension\":\"gif\",\"permitted\":true},{\"extension\":\"mp3\",\"permitted\":true},{\"extension\":\"mp4\",\"permitted\":true},{\"extension\":\"avi\",\"permitted\":true},{\"extension\":\"mkv\",\"permitted\":true}]', '2025-06-03 09:58:28', '2026-07-01 13:57:10', NULL),
(8, 'primary_color', 'Color Primario', 'string', '#137115', '2025-06-03 09:58:28', '2026-07-01 13:57:10', NULL),
(9, 'primary_container_color', 'Color principal suavizado', 'color', '#bdd7bd', '2026-06-27 21:14:41', '2026-07-01 13:57:10', NULL),
(10, 'secondary_color', 'Color secundario', 'color', '#12b533', '2026-06-27 21:14:41', '2026-07-01 13:57:10', NULL),
(11, 'secondary_container_color', 'Color secundario suavizado', 'color', '#bdeac6', '2026-06-27 21:14:41', '2026-07-01 13:57:10', NULL),
(12, 'accent_color', 'Color de acento', 'color', '#2b1b0d', '2026-06-27 21:14:41', '2026-07-01 13:57:10', NULL),
(13, 'theme_mode', 'Modo visual', 'select', 'light', '2026-06-27 21:14:41', '2026-07-01 13:57:10', NULL),
(14, 'interface_density', 'Densidad de interfaz', 'select', 'compact', '2026-06-27 21:14:41', '2026-07-01 13:57:10', NULL),
(15, 'sidebar_style', 'Estilo del sidebar', 'select', 'expanded', '2026-06-27 21:14:41', '2026-07-01 13:57:10', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `person_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rol_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `reset_password_token` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `last_attempt` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `company_id`, `person_id`, `rol_id`, `email`, `password`, `remember_token`, `reset_password_token`, `is_active`, `last_login`, `avatar`, `attempts`, `last_attempt`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1602, NULL, 738, 1, 'springrandalf@gmail.com', '$2y$12$hCwsjS4fqPbgt3ZlPwIoxet1GDQUfpqgr1xS.n3PysyX0aM5LT4ki', 'vAk1LFMEUZEB8yiEkDmHFwkcwqBHiPsj9bf00VVY9EF8hwPhtEPI2g1oXiRg', NULL, 1, NULL, 'profile-photos/UYjPwOJDvofayovbnWZPtm69eBIY0SBWjOM8AW9T.png', 0, NULL, '2026-06-29 15:49:53', '2026-06-30 15:21:58', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user_notifications`
--

INSERT INTO `user_notifications` (`id`, `user_id`, `title`, `message`, `link`, `read_at`, `created_at`, `updated_at`) VALUES
(2, 1602, 'Nueva postulación', 'El estudiante Juan Diego Mendoza Castro ha postulado a la oferta: Docente en informatica', '/admin/dashboard?tab=applications', NULL, '2026-06-30 20:30:38', '2026-06-30 20:30:38');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`) USING BTREE;

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`) USING BTREE;

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`) USING BTREE;

--
-- Indices de la tabla `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `file_fileable_type_fileable_id_index` (`fileable_type`,`fileable_id`) USING BTREE;

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `jobs_queue_index` (`queue`) USING BTREE;

--
-- Indices de la tabla `job_opportunity_applications`
--
ALTER TABLE `job_opportunity_applications`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `job_opportunity_applications_offer_id_foreign` (`offer_id`) USING BTREE,
  ADD KEY `job_opportunity_applications_user_id_foreign` (`user_id`) USING BTREE;

--
-- Indices de la tabla `job_opportunity_company`
--
ALTER TABLE `job_opportunity_company`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `job_opportunity_company_ruc_unique` (`ruc`) USING BTREE;

--
-- Indices de la tabla `job_opportunity_contract_types`
--
ALTER TABLE `job_opportunity_contract_types`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `job_opportunity_location`
--
ALTER TABLE `job_opportunity_location`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `job_opportunity_offer`
--
ALTER TABLE `job_opportunity_offer`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `job_opportunity_offer_slug_unique` (`slug`) USING BTREE,
  ADD KEY `job_opportunity_offer_company_id_foreign` (`company_id`) USING BTREE,
  ADD KEY `job_opportunity_offer_location_id_foreign` (`location_id`) USING BTREE,
  ADD KEY `job_opportunity_offer_state_id_foreign` (`state_id`) USING BTREE,
  ADD KEY `job_opportunity_offer_category_id_foreign` (`category_id`) USING BTREE,
  ADD KEY `job_opportunity_offer_work_schedule_id_foreign` (`work_schedule_id`) USING BTREE,
  ADD KEY `job_opportunity_offer_contract_type_id_foreign` (`contract_type_id`) USING BTREE;

--
-- Indices de la tabla `job_opportunity_offer_category`
--
ALTER TABLE `job_opportunity_offer_category`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `job_opportunity_offer_state`
--
ALTER TABLE `job_opportunity_offer_state`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `job_opportunity_offer_state_detail`
--
ALTER TABLE `job_opportunity_offer_state_detail`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `job_opportunity_offer_state_detail_offer_id_foreign` (`offer_id`) USING BTREE,
  ADD KEY `job_opportunity_offer_state_detail_state_id_foreign` (`state_id`) USING BTREE;

--
-- Indices de la tabla `job_opportunity_user_cv`
--
ALTER TABLE `job_opportunity_user_cv`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `job_opportunity_user_cv_user_id_foreign` (`user_id`) USING BTREE;

--
-- Indices de la tabla `job_opportunity_work_schedules`
--
ALTER TABLE `job_opportunity_work_schedules`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `link`
--
ALTER TABLE `link`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `link_linkable_type_linkable_id_index` (`linkable_type`,`linkable_id`) USING BTREE;

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `option`
--
ALTER TABLE `option`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `option_option_id_foreign` (`option_id`) USING BTREE,
  ADD KEY `option_menu_id_foreign` (`menu_id`) USING BTREE;

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `rol_option`
--
ALTER TABLE `rol_option`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `rol_option_rol_id_foreign` (`rol_id`) USING BTREE,
  ADD KEY `rol_option_option_id_foreign` (`option_id`) USING BTREE;

--
-- Indices de la tabla `rol_user`
--
ALTER TABLE `rol_user`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `rol_user_rol_id_foreign` (`rol_id`) USING BTREE,
  ADD KEY `rol_user_user_id_foreign` (`user_id`) USING BTREE;

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `system_configuration`
--
ALTER TABLE `system_configuration`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `system_configuration_key_unique` (`key`) USING BTREE,
  ADD UNIQUE KEY `system_configuration_name_unique` (`name`) USING BTREE;

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `user_email_unique` (`email`) USING BTREE,
  ADD KEY `user_company_id_foreign` (`company_id`) USING BTREE,
  ADD KEY `user_person_id_foreign` (`person_id`) USING BTREE,
  ADD KEY `user_rol_id_foreign` (`rol_id`) USING BTREE;

--
-- Indices de la tabla `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_notifications_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `file`
--
ALTER TABLE `file`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `job_opportunity_applications`
--
ALTER TABLE `job_opportunity_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `job_opportunity_company`
--
ALTER TABLE `job_opportunity_company`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=592;

--
-- AUTO_INCREMENT de la tabla `job_opportunity_contract_types`
--
ALTER TABLE `job_opportunity_contract_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `job_opportunity_location`
--
ALTER TABLE `job_opportunity_location`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `job_opportunity_offer`
--
ALTER TABLE `job_opportunity_offer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `job_opportunity_offer_category`
--
ALTER TABLE `job_opportunity_offer_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `job_opportunity_offer_state`
--
ALTER TABLE `job_opportunity_offer_state`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `job_opportunity_offer_state_detail`
--
ALTER TABLE `job_opportunity_offer_state_detail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `job_opportunity_user_cv`
--
ALTER TABLE `job_opportunity_user_cv`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `job_opportunity_work_schedules`
--
ALTER TABLE `job_opportunity_work_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `link`
--
ALTER TABLE `link`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `option`
--
ALTER TABLE `option`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `person`
--
ALTER TABLE `person`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=739;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `rol_option`
--
ALTER TABLE `rol_option`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `rol_user`
--
ALTER TABLE `rol_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1634;

--
-- AUTO_INCREMENT de la tabla `system_configuration`
--
ALTER TABLE `system_configuration`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1634;

--
-- AUTO_INCREMENT de la tabla `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `job_opportunity_applications`
--
ALTER TABLE `job_opportunity_applications`
  ADD CONSTRAINT `job_opportunity_applications_offer_id_foreign` FOREIGN KEY (`offer_id`) REFERENCES `job_opportunity_offer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_opportunity_applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `job_opportunity_offer`
--
ALTER TABLE `job_opportunity_offer`
  ADD CONSTRAINT `job_opportunity_offer_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `job_opportunity_offer_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_opportunity_offer_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `job_opportunity_company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_opportunity_offer_contract_type_id_foreign` FOREIGN KEY (`contract_type_id`) REFERENCES `job_opportunity_contract_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_opportunity_offer_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `job_opportunity_location` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_opportunity_offer_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `job_opportunity_offer_state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_opportunity_offer_work_schedule_id_foreign` FOREIGN KEY (`work_schedule_id`) REFERENCES `job_opportunity_work_schedules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `job_opportunity_offer_state_detail`
--
ALTER TABLE `job_opportunity_offer_state_detail`
  ADD CONSTRAINT `job_opportunity_offer_state_detail_offer_id_foreign` FOREIGN KEY (`offer_id`) REFERENCES `job_opportunity_offer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_opportunity_offer_state_detail_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `job_opportunity_offer_state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `job_opportunity_user_cv`
--
ALTER TABLE `job_opportunity_user_cv`
  ADD CONSTRAINT `job_opportunity_user_cv_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `option`
--
ALTER TABLE `option`
  ADD CONSTRAINT `option_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `option_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `option` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rol_option`
--
ALTER TABLE `rol_option`
  ADD CONSTRAINT `rol_option_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `option` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rol_option_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rol_user`
--
ALTER TABLE `rol_user`
  ADD CONSTRAINT `rol_user_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rol_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `job_opportunity_company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_person_id_foreign` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `user_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
