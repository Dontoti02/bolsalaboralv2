-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-08-2026 a las 22:00:51
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
('laravel-cache-lopez sac|127.0.0.1', 'i:1;', 1787779765),
('laravel-cache-lopez sac|127.0.0.1:timer', 'i:1787779765;', 1787779765);

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

--
-- Volcado de datos para la tabla `job_opportunity_applications`
--

INSERT INTO `job_opportunity_applications` (`id`, `fullname`, `program_study`, `message`, `status`, `cv`, `feedback`, `feedback_date`, `offer_id`, `user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(9, 'Alex López Salinas', 'Producción Agropecuaria', '', 'under_review', '/uploads/cvs/cv_1637_1783461866.pdf', NULL, '2026-08-20 19:56:11', 26, 1637, '2026-07-15 15:12:05', '2026-08-20 19:56:11', NULL);

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

--
-- Volcado de datos para la tabla `job_opportunity_company`
--

INSERT INTO `job_opportunity_company` (`id`, `name`, `ruc`, `email`, `phone`, `mailbox`, `is_verified`, `description`, `website`, `address`, `logo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(601, 'LOPEZ SAC', '12345678901', 'anonlazarus0@gmail.com', '984938378', 'anonlazarus0@gmail.com', 1, NULL, NULL, 'Urb Los Jardines', '/uploads/logos/1787254310_17604242.png', '2026-07-15 14:57:06', '2026-08-31 16:36:47', NULL);

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
-- Estructura de tabla para la tabla `job_opportunity_modalities`
--

CREATE TABLE `job_opportunity_modalities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `job_opportunity_modalities`
--

INSERT INTO `job_opportunity_modalities` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
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
  `modality_id` bigint(20) UNSIGNED NOT NULL,
  `state_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `work_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `contract_type_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `job_opportunity_offer`
--

INSERT INTO `job_opportunity_offer` (`id`, `title`, `slug`, `description`, `requirements`, `publication_date`, `deadline`, `benefits`, `salary`, `salary_currency`, `attachments`, `address`, `department`, `province`, `country`, `company_id`, `modality_id`, `state_id`, `category_id`, `work_schedule_id`, `contract_type_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(26, 'Full-stack JAVA', 'full-stack-java', '5 años', '5 años', '2026-07-15 00:00:00', NULL, 'todo de acorde al merca', 2500.00, 'SOLES', NULL, 'Urb Los Jardines', 'Lima', 'Huaral', 'Perú', 601, 2, 2, 4, 1, 2, '2026-07-15 15:03:05', '2026-07-15 15:03:05', NULL),
(27, 'gnnnvbnvbnbn', 'gnnnvbnvbnbn', 'vbbvbnvbnvbnvn', 'vnvbnvbnn', '2026-08-20 00:00:00', NULL, 'nvbnvbbv', 3500.00, 'SOLES', NULL, 'Urb Los Jardines', 'Loreto', 'Mariscal Ramón Castilla', 'Perú', 601, 2, 2, 3, 3, 2, '2026-08-20 19:55:38', '2026-08-21 21:44:06', '2026-08-21 21:44:06');

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

--
-- Volcado de datos para la tabla `job_opportunity_offer_state_detail`
--

INSERT INTO `job_opportunity_offer_state_detail` (`id`, `offer_id`, `state_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(36, 26, 2, '2026-07-15 15:03:05', '2026-07-15 15:03:05', NULL),
(37, 27, 2, '2026-08-20 19:55:38', '2026-08-20 19:55:38', NULL);

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

--
-- Volcado de datos para la tabla `job_opportunity_user_cv`
--

INSERT INTO `job_opportunity_user_cv` (`id`, `version`, `url`, `user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, '1', '/uploads/cvs/cv_1637_1783461866.pdf', 1637, '2026-07-07 22:04:26', '2026-07-07 22:04:26', NULL),
(6, '1', '/uploads/cvs/cv_1647_1786540861.pdf', 1647, '2026-08-12 13:21:01', '2026-08-12 13:21:01', NULL),
(7, '2', '/uploads/cvs/cv_1647_1786541681.pdf', 1647, '2026-08-12 13:34:41', '2026-08-12 13:34:41', NULL);

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
(20, '2026_06_29_113000_create_user_notifications_table', 3),
(21, '2026_07_03_120000_add_profile_fields_to_person_table', 4),
(22, '2026_07_08_151641_add_career_to_person_table', 5),
(23, '2026_07_15_000001_create_study_programs_table', 6),
(24, '2026_07_15_000002_add_study_program_id_to_person_table', 7),
(25, '2026_07_15_145010_rename_location_to_modality', 8);

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
('anonlazarus0@gmail.com', '$2y$12$pWWNU6S5LWE9J.wZAZ/BaeinLGyvtukRmfpuu5RxfjLrD1zwnYODy', '2026-07-15 22:16:18'),
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
  `career` varchar(255) DEFAULT NULL,
  `study_program_id` bigint(20) UNSIGNED DEFAULT NULL,
  `phone` varchar(9) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `native_language` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `about_me` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `hobbies` text DEFAULT NULL,
  `education` text DEFAULT NULL,
  `experience` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `person`
--

INSERT INTO `person` (`id`, `document_type`, `document_number`, `names`, `career`, `study_program_id`, `phone`, `email`, `sex`, `birth_date`, `native_language`, `created_at`, `updated_at`, `deleted_at`, `about_me`, `skills`, `hobbies`, `education`, `experience`) VALUES
(1, 'DNI', '10000001', 'Admin Test 1', NULL, NULL, '999999999', 'admin1@test.com', NULL, NULL, NULL, '2026-06-26 16:59:58', '2026-06-26 16:59:58', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'DNI', '10000002', 'Admin Test 2', NULL, NULL, '999999999', 'admin2@test.com', NULL, NULL, NULL, '2026-06-26 16:59:58', '2026-06-26 16:59:58', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'DNI', '10000003', 'Admin Test 3', NULL, NULL, '999999999', 'admin3@test.com', NULL, NULL, NULL, '2026-06-26 16:59:58', '2026-06-26 16:59:58', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'DNI', '20000001', 'Docente Test 1', NULL, NULL, '988888888', 'docente1@test.com', NULL, NULL, NULL, '2026-06-26 16:59:59', '2026-06-26 16:59:59', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'DNI', '20000002', 'Docente Test 2', NULL, NULL, '988888888', 'docente2@test.com', NULL, NULL, NULL, '2026-06-26 16:59:59', '2026-06-26 16:59:59', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'DNI', '20000003', 'Docente Test 3', NULL, NULL, '988888888', 'docente3@test.com', NULL, NULL, NULL, '2026-06-26 16:59:59', '2026-06-26 16:59:59', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'DNI', '300000001', 'Estudiante Test 1', NULL, NULL, '977777777', 'estudiante1@test.com', NULL, NULL, NULL, '2026-06-26 16:59:59', '2026-06-26 16:59:59', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'DNI', '300000002', 'Estudiante Test 2', NULL, NULL, '977777777', 'estudiante2@test.com', NULL, NULL, NULL, '2026-06-26 17:00:00', '2026-06-26 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'DNI', '300000003', 'Estudiante Test 3', NULL, NULL, '977777777', 'estudiante3@test.com', NULL, NULL, NULL, '2026-06-26 17:00:00', '2026-06-26 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'DNI', '300000004', 'Estudiante Test 4', NULL, NULL, '977777777', 'estudiante4@test.com', NULL, NULL, NULL, '2026-06-26 17:00:00', '2026-06-26 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'DNI', '300000005', 'Estudiante Test 5', NULL, NULL, '977777777', 'estudiante5@test.com', NULL, NULL, NULL, '2026-06-26 17:00:00', '2026-06-26 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'DNI', '300000006', 'Estudiante Test 6', NULL, NULL, '977777777', 'estudiante6@test.com', NULL, NULL, NULL, '2026-06-26 17:00:00', '2026-06-26 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'DNI', '300000007', 'Estudiante Test 7', NULL, NULL, '977777777', 'estudiante7@test.com', NULL, NULL, NULL, '2026-06-26 17:00:01', '2026-06-26 17:00:01', NULL, NULL, NULL, NULL, NULL, NULL),
(14, 'DNI', '300000008', 'Estudiante Test 8', NULL, NULL, '977777777', 'estudiante8@test.com', NULL, NULL, NULL, '2026-06-26 17:00:01', '2026-06-26 17:00:01', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'DNI', '300000009', 'Estudiante Test 9', NULL, NULL, '977777777', 'estudiante9@test.com', NULL, NULL, NULL, '2026-06-26 17:00:01', '2026-06-26 17:00:01', NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'DNI', '300000010', 'Estudiante Test 10', NULL, NULL, '977777777', 'estudiante10@test.com', NULL, NULL, NULL, '2026-06-26 17:00:01', '2026-06-26 17:00:01', NULL, NULL, NULL, NULL, NULL, NULL),
(17, 'DNI', '300000011', 'Estudiante Test 11', NULL, NULL, '977777777', 'estudiante11@test.com', NULL, NULL, NULL, '2026-06-26 17:00:02', '2026-06-26 17:00:02', NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'DNI', '300000012', 'Estudiante Test 12', NULL, NULL, '977777777', 'estudiante12@test.com', NULL, NULL, NULL, '2026-06-26 17:00:02', '2026-06-26 17:00:02', NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'DNI', '300000013', 'Estudiante Test 13', NULL, NULL, '977777777', 'estudiante13@test.com', NULL, NULL, NULL, '2026-06-26 17:00:02', '2026-06-26 17:00:02', NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'DNI', '300000014', 'Estudiante Test 14', NULL, NULL, '977777777', 'estudiante14@test.com', NULL, NULL, NULL, '2026-06-26 17:00:02', '2026-06-26 17:00:02', NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'DNI', '300000015', 'Estudiante Test 15', NULL, NULL, '977777777', 'estudiante15@test.com', NULL, NULL, NULL, '2026-06-26 17:00:02', '2026-06-26 17:00:02', NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'DNI', '77966489', 'Alex Lopez', NULL, NULL, '94378829', 'springrandalf@gmail.com', NULL, NULL, NULL, '2026-06-26 17:07:58', '2026-06-26 17:07:58', NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'DNI', '70014523', 'Ana Lucía Pérez Ramos', NULL, NULL, '987654321', 'ana.perez@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:05', '2026-06-26 17:15:05', NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'DNI', '70014524', 'Carlos Eduardo Rojas Díaz', NULL, NULL, '987654322', 'carlos.rojas@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:06', '2026-06-26 17:15:06', NULL, NULL, NULL, NULL, NULL, NULL),
(25, 'DNI', '70014525', 'María Fernanda Torres Vega', NULL, NULL, '987654323', 'maria.torres@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:06', '2026-06-26 17:15:06', NULL, NULL, NULL, NULL, NULL, NULL),
(26, 'DNI', '70014526', 'Luis Alberto Quispe Flores', NULL, NULL, '987654324', 'luis.quispe@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:06', '2026-06-26 17:15:06', NULL, NULL, NULL, NULL, NULL, NULL),
(27, 'DNI', '70014527', 'Rosa Elena Huamán Salazar', NULL, NULL, '987654325', 'rosa.huaman@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:06', '2026-06-26 17:15:06', NULL, NULL, NULL, NULL, NULL, NULL),
(28, 'DNI', '70014528', 'Juan Diego Mendoza Castro', NULL, 1, '987654326', 'juan.mendoza@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:06', '2026-07-15 14:38:36', NULL, NULL, NULL, NULL, NULL, NULL),
(29, 'DNI', '70014529', 'Valeria Sofía Chávez Núñez', NULL, 1, '987654327', 'valeria.chavez@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:07', '2026-07-15 14:38:36', NULL, NULL, NULL, NULL, NULL, NULL),
(30, 'DNI', '70014530', 'Pedro Miguel García León', NULL, 1, '987654328', 'pedro.garcia@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:07', '2026-07-15 14:38:36', NULL, NULL, NULL, NULL, NULL, NULL),
(31, 'DNI', '70014531', 'Camila Alejandra Soto Ríos', NULL, 1, '987654329', 'camila.soto@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:07', '2026-07-15 14:38:36', NULL, NULL, NULL, NULL, NULL, NULL),
(32, 'DNI', '70014532', 'Diego Armando Paredes López', NULL, 1, '987654330', 'diego.paredes@demo.edu.pe', NULL, NULL, NULL, '2026-06-26 17:15:07', '2026-07-15 14:38:36', NULL, NULL, NULL, NULL, NULL, NULL),
(403, 'DNI', '70000002', 'Juan Campos Gutiérrez', NULL, NULL, '910000002', 'estudiante.juan.campos.gutierrez.2@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:21', '2026-06-27 16:26:21', NULL, NULL, NULL, NULL, NULL, NULL),
(404, 'DNI', '70000003', 'Brenda Cáceres Arias', NULL, NULL, '910000003', 'docente.brenda.caceres.arias.3@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:22', '2026-06-27 16:26:22', NULL, NULL, NULL, NULL, NULL, NULL),
(405, 'DNI', '70000007', 'Andrea Espinoza León', NULL, NULL, '910000007', 'estudiante.andrea.espinoza.leon.7@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:22', '2026-06-27 16:26:22', NULL, NULL, NULL, NULL, NULL, NULL),
(406, 'DNI', '70000008', 'Milagros Aguilar Herrera', NULL, NULL, '910000008', 'estudiante.milagros.aguilar.herrera.8@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:23', '2026-06-27 16:26:23', NULL, NULL, NULL, NULL, NULL, NULL),
(407, 'DNI', '70000010', 'Fernando Bravo Ramírez', NULL, NULL, '910000010', 'docente.fernando.bravo.ramirez.10@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:23', '2026-06-27 16:26:23', NULL, NULL, NULL, NULL, NULL, NULL),
(408, 'DNI', '70000012', 'José Arias Cruz', NULL, NULL, '910000012', 'docente.jose.arias.cruz.12@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:23', '2026-06-27 16:26:23', NULL, NULL, NULL, NULL, NULL, NULL),
(409, 'DNI', '70000013', 'Camila Castillo García', NULL, NULL, '910000013', 'docente.camila.castillo.garcia.13@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:24', '2026-06-27 16:26:24', NULL, NULL, NULL, NULL, NULL, NULL),
(410, 'DNI', '70000015', 'Karla Arias Torres', NULL, NULL, '910000015', 'docente.karla.arias.torres.15@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:24', '2026-06-27 16:26:24', NULL, NULL, NULL, NULL, NULL, NULL),
(411, 'DNI', '70000016', 'Yessica Ortega Ortega', NULL, NULL, '910000016', 'docente.yessica.ortega.ortega.16@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:24', '2026-06-27 16:26:24', NULL, NULL, NULL, NULL, NULL, NULL),
(412, 'DNI', '70000018', 'Alex Vásquez Salazar', NULL, NULL, '910000018', 'docente.alex.vasquez.salazar.18@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:25', '2026-06-27 16:26:25', NULL, NULL, NULL, NULL, NULL, NULL),
(413, 'DNI', '70000020', 'Diana Sánchez Cruz', NULL, NULL, '910000020', 'docente.diana.sanchez.cruz.20@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:25', '2026-06-27 16:26:25', NULL, NULL, NULL, NULL, NULL, NULL),
(414, 'DNI', '70000021', 'Manuel Bravo Vásquez', NULL, NULL, '910000021', 'estudiante.manuel.bravo.vasquez.21@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:25', '2026-06-27 16:26:25', NULL, NULL, NULL, NULL, NULL, NULL),
(415, 'DNI', '70000022', 'Alonso Rojas León', NULL, NULL, '910000022', 'estudiante.alonso.rojas.leon.22@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:26', '2026-06-27 16:26:26', NULL, NULL, NULL, NULL, NULL, NULL),
(416, 'DNI', '70000023', 'Gabriela Reyes Ortega', NULL, NULL, '910000023', 'estudiante.gabriela.reyes.ortega.23@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:26', '2026-06-27 16:26:26', NULL, NULL, NULL, NULL, NULL, NULL),
(417, 'DNI', '70000024', 'Diana Gutiérrez Ortega', NULL, NULL, '910000024', 'docente.diana.gutierrez.ortega.24@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:26', '2026-06-27 16:26:26', NULL, NULL, NULL, NULL, NULL, NULL),
(418, 'DNI', '70000025', 'Vanessa Mendoza Vargas', NULL, NULL, '910000025', 'docente.vanessa.mendoza.vargas.25@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:26', '2026-06-27 16:26:26', NULL, NULL, NULL, NULL, NULL, NULL),
(419, 'DNI', '70000026', 'Wilmer Salazar Rojas', NULL, NULL, '910000026', 'estudiante.wilmer.salazar.rojas.26@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:26', '2026-06-27 16:26:26', NULL, NULL, NULL, NULL, NULL, NULL),
(420, 'DNI', '70000027', 'Camila Pérez Silva', NULL, NULL, '910000027', 'estudiante.camila.perez.silva.27@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:27', '2026-06-27 16:26:27', NULL, NULL, NULL, NULL, NULL, NULL),
(421, 'DNI', '70000029', 'Silvia Vásquez Aguilar', NULL, NULL, '910000029', 'estudiante.silvia.vasquez.aguilar.29@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:27', '2026-06-27 16:26:27', NULL, NULL, NULL, NULL, NULL, NULL),
(422, 'DNI', '70000030', 'Brenda García Aguilar', NULL, NULL, '910000030', 'docente.brenda.garcia.aguilar.30@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:27', '2026-06-27 16:26:27', NULL, NULL, NULL, NULL, NULL, NULL),
(423, 'DNI', '70000032', 'Milagros Campos Mendoza', NULL, NULL, '910000032', 'docente.milagros.campos.mendoza.32@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:28', '2026-06-27 16:26:28', NULL, NULL, NULL, NULL, NULL, NULL),
(424, 'DNI', '70000033', 'Ruth Peña García', NULL, NULL, '910000033', 'docente.ruth.pena.garcia.33@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:28', '2026-06-27 16:26:28', NULL, NULL, NULL, NULL, NULL, NULL),
(425, 'DNI', '70000034', 'Rosa Campos Navarro', NULL, NULL, '910000034', 'estudiante.rosa.campos.navarro.34@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:28', '2026-06-27 16:26:28', NULL, NULL, NULL, NULL, NULL, NULL),
(426, 'DNI', '70000035', 'Sofía Espinoza Cáceres', NULL, NULL, '910000035', 'docente.sofia.espinoza.caceres.35@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:28', '2026-06-27 16:26:28', NULL, NULL, NULL, NULL, NULL, NULL),
(427, 'DNI', '70000036', 'Gabriela Calderón Rojas', NULL, NULL, '910000036', 'docente.gabriela.calderon.rojas.36@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:29', '2026-06-27 16:26:29', NULL, NULL, NULL, NULL, NULL, NULL),
(428, 'DNI', '70000038', 'Sofía Campos Peña', NULL, NULL, '910000038', 'estudiante.sofia.campos.pena.38@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:29', '2026-06-27 16:26:29', NULL, NULL, NULL, NULL, NULL, NULL),
(429, 'DNI', '70000039', 'Vanessa Vega Ortega', NULL, NULL, '910000039', 'estudiante.vanessa.vega.ortega.39@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:29', '2026-06-27 16:26:29', NULL, NULL, NULL, NULL, NULL, NULL),
(430, 'DNI', '70000040', 'Pablo Herrera Herrera', NULL, NULL, '910000040', 'docente.pablo.herrera.herrera.40@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:29', '2026-06-27 16:26:29', NULL, NULL, NULL, NULL, NULL, NULL),
(431, 'DNI', '70000042', 'Pablo García Pérez', NULL, NULL, '910000042', 'docente.pablo.garcia.perez.42@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:30', '2026-06-27 16:26:30', NULL, NULL, NULL, NULL, NULL, NULL),
(432, 'DNI', '70000044', 'Paola Arias Peña', NULL, NULL, '910000044', 'docente.paola.arias.pena.44@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:30', '2026-06-27 16:26:30', NULL, NULL, NULL, NULL, NULL, NULL),
(433, 'DNI', '70000045', 'Joel Vargas Valdez', NULL, NULL, '910000045', 'estudiante.joel.vargas.valdez.45@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:31', '2026-06-27 16:26:31', NULL, NULL, NULL, NULL, NULL, NULL),
(434, 'DNI', '70000047', 'Estefany Silva Silva', NULL, NULL, '910000047', 'estudiante.estefany.silva.silva.47@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:31', '2026-06-27 16:26:31', NULL, NULL, NULL, NULL, NULL, NULL),
(435, 'DNI', '70000049', 'Renato Navarro Flores', NULL, NULL, '910000049', 'docente.renato.navarro.flores.49@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:31', '2026-06-27 16:26:31', NULL, NULL, NULL, NULL, NULL, NULL),
(436, 'DNI', '70000050', 'Brenda Flores Mejía', NULL, NULL, '910000050', 'docente.brenda.flores.mejia.50@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:32', '2026-06-27 16:26:32', NULL, NULL, NULL, NULL, NULL, NULL),
(437, 'DNI', '70000052', 'Luis Díaz Bravo', NULL, NULL, '910000052', 'docente.luis.diaz.bravo.52@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:32', '2026-06-27 16:26:32', NULL, NULL, NULL, NULL, NULL, NULL),
(438, 'DNI', '70000054', 'Miguel Rojas Navarro', NULL, NULL, '910000054', 'docente.miguel.rojas.navarro.54@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:32', '2026-06-27 16:26:32', NULL, NULL, NULL, NULL, NULL, NULL),
(439, 'DNI', '70000059', 'María Reyes Rojas', NULL, NULL, '910000059', 'docente.maria.reyes.rojas.59@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:34', '2026-06-27 16:26:34', NULL, NULL, NULL, NULL, NULL, NULL),
(440, 'DNI', '70000060', 'Alex Aguilar Arias', NULL, NULL, '910000060', 'docente.alex.aguilar.arias.60@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:34', '2026-06-27 16:26:34', NULL, NULL, NULL, NULL, NULL, NULL),
(441, 'DNI', '70000061', 'Ruth Gutiérrez Pérez', NULL, NULL, '910000061', 'docente.ruth.gutierrez.perez.61@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:34', '2026-06-27 16:26:34', NULL, NULL, NULL, NULL, NULL, NULL),
(442, 'DNI', '70000062', 'Carlos Herrera Ortega', NULL, NULL, '910000062', 'estudiante.carlos.herrera.ortega.62@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:34', '2026-06-27 16:26:34', NULL, NULL, NULL, NULL, NULL, NULL),
(443, 'DNI', '70000064', 'Cristian Paredes Ramírez', NULL, NULL, '910000064', 'docente.cristian.paredes.ramirez.64@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:35', '2026-06-27 16:26:35', NULL, NULL, NULL, NULL, NULL, NULL),
(444, 'DNI', '70000065', 'Fernando Pérez Díaz', NULL, NULL, '910000065', 'estudiante.fernando.perez.diaz.65@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:35', '2026-06-27 16:26:35', NULL, NULL, NULL, NULL, NULL, NULL),
(445, 'DNI', '70000066', 'Valeria Cáceres Torres', NULL, NULL, '910000066', 'estudiante.valeria.caceres.torres.66@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:35', '2026-06-27 16:26:35', NULL, NULL, NULL, NULL, NULL, NULL),
(446, 'DNI', '70000067', 'Diego Valdez Chávez', NULL, NULL, '910000067', 'docente.diego.valdez.chavez.67@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:35', '2026-06-27 16:26:35', NULL, NULL, NULL, NULL, NULL, NULL),
(447, 'DNI', '70000068', 'Alonso Quispe Torres', NULL, NULL, '910000068', 'estudiante.alonso.quispe.torres.68@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:35', '2026-06-27 16:26:35', NULL, NULL, NULL, NULL, NULL, NULL),
(448, 'DNI', '70000069', 'Gabriela Sánchez Silva', NULL, NULL, '910000069', 'docente.gabriela.sanchez.silva.69@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:36', '2026-06-27 16:26:36', NULL, NULL, NULL, NULL, NULL, NULL),
(449, 'DNI', '70000070', 'Rosa Quispe Reyes', NULL, NULL, '910000070', 'estudiante.rosa.quispe.reyes.70@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:36', '2026-06-27 16:26:36', NULL, NULL, NULL, NULL, NULL, NULL),
(450, 'DNI', '70000072', 'María Aguilar Aguilar', NULL, NULL, '910000072', 'docente.maria.aguilar.aguilar.72@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:36', '2026-06-27 16:26:36', NULL, NULL, NULL, NULL, NULL, NULL),
(451, 'DNI', '70000073', 'Daniela Huamán Carrillo', NULL, NULL, '910000073', 'docente.daniela.huaman.carrillo.73@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:37', '2026-06-27 16:26:37', NULL, NULL, NULL, NULL, NULL, NULL),
(452, 'DNI', '70000075', 'Patricia Carrillo Arias', NULL, NULL, '910000075', 'docente.patricia.carrillo.arias.75@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:37', '2026-06-27 16:26:37', NULL, NULL, NULL, NULL, NULL, NULL),
(453, 'DNI', '70000079', 'Lorena Paredes Campos', NULL, NULL, '910000079', 'docente.lorena.paredes.campos.79@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:38', '2026-06-27 16:26:38', NULL, NULL, NULL, NULL, NULL, NULL),
(454, 'DNI', '70000081', 'Pedro García Vargas', NULL, NULL, '910000081', 'estudiante.pedro.garcia.vargas.81@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:38', '2026-06-27 16:26:38', NULL, NULL, NULL, NULL, NULL, NULL),
(455, 'DNI', '70000082', 'Wilmer Aguilar Ortega', NULL, NULL, '910000082', 'estudiante.wilmer.aguilar.ortega.82@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:39', '2026-06-27 16:26:39', NULL, NULL, NULL, NULL, NULL, NULL),
(456, 'DNI', '70000083', 'Sofía Bravo Castillo', NULL, NULL, '910000083', 'estudiante.sofia.bravo.castillo.83@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:39', '2026-06-27 16:26:39', NULL, NULL, NULL, NULL, NULL, NULL),
(457, 'DNI', '70000084', 'Manuel Rojas Mejía', NULL, NULL, '910000084', 'docente.manuel.rojas.mejia.84@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:39', '2026-06-27 16:26:39', NULL, NULL, NULL, NULL, NULL, NULL),
(458, 'DNI', '70000086', 'Elena Herrera Huamán', NULL, NULL, '910000086', 'estudiante.elena.herrera.huaman.86@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:39', '2026-06-27 16:26:39', NULL, NULL, NULL, NULL, NULL, NULL),
(459, 'DNI', '70000087', 'Joel Peña Salazar', NULL, NULL, '910000087', 'estudiante.joel.pena.salazar.87@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:40', '2026-06-27 16:26:40', NULL, NULL, NULL, NULL, NULL, NULL),
(460, 'DNI', '70000088', 'Bryan Navarro Pérez', NULL, NULL, '910000088', 'estudiante.bryan.navarro.perez.88@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:40', '2026-06-27 16:26:40', NULL, NULL, NULL, NULL, NULL, NULL),
(461, 'DNI', '70000089', 'Alex Chávez Calderón', NULL, NULL, '910000089', 'docente.alex.chavez.calderon.89@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:40', '2026-06-27 16:26:40', NULL, NULL, NULL, NULL, NULL, NULL),
(462, 'DNI', '70000092', 'Lorena León Rojas', NULL, NULL, '910000092', 'estudiante.lorena.leon.rojas.92@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:41', '2026-06-27 16:26:41', NULL, NULL, NULL, NULL, NULL, NULL),
(463, 'DNI', '70000093', 'Lucía Campos León', NULL, NULL, '910000093', 'estudiante.lucia.campos.leon.93@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:41', '2026-06-27 16:26:41', NULL, NULL, NULL, NULL, NULL, NULL),
(464, 'DNI', '70000094', 'Hugo Mendoza Navarro', NULL, NULL, '910000094', 'estudiante.hugo.mendoza.navarro.94@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:41', '2026-06-27 16:26:41', NULL, NULL, NULL, NULL, NULL, NULL),
(465, 'DNI', '70000096', 'Lucía Espinoza Medina', NULL, NULL, '910000096', 'estudiante.lucia.espinoza.medina.96@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:42', '2026-06-27 16:26:42', NULL, NULL, NULL, NULL, NULL, NULL),
(466, 'DNI', '70000098', 'Ruth Díaz Espinoza', NULL, NULL, '910000098', 'docente.ruth.diaz.espinoza.98@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:42', '2026-06-27 16:26:42', NULL, NULL, NULL, NULL, NULL, NULL),
(467, 'DNI', '70000099', 'Wilmer Mendoza Salazar', NULL, NULL, '910000099', 'estudiante.wilmer.mendoza.salazar.99@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:42', '2026-06-27 16:26:42', NULL, NULL, NULL, NULL, NULL, NULL),
(468, 'DNI', '70000101', 'Valeria Chávez Paredes', NULL, NULL, '910000101', 'docente.valeria.chavez.paredes.101@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:43', '2026-06-27 16:26:43', NULL, NULL, NULL, NULL, NULL, NULL),
(469, 'DNI', '70000102', 'José Condori Medina', NULL, NULL, '910000102', 'docente.jose.condori.medina.102@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:43', '2026-06-27 16:26:43', NULL, NULL, NULL, NULL, NULL, NULL),
(470, 'DNI', '70000103', 'Carmen Herrera Ortega', NULL, NULL, '910000103', 'docente.carmen.herrera.ortega.103@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:43', '2026-06-27 16:26:43', NULL, NULL, NULL, NULL, NULL, NULL),
(471, 'DNI', '70000105', 'Diana Gutiérrez Valdez', NULL, NULL, '910000105', 'docente.diana.gutierrez.valdez.105@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:43', '2026-06-27 16:26:43', NULL, NULL, NULL, NULL, NULL, NULL),
(472, 'DNI', '70000106', 'Camila Espinoza Bravo', NULL, NULL, '910000106', 'docente.camila.espinoza.bravo.106@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:44', '2026-06-27 16:26:44', NULL, NULL, NULL, NULL, NULL, NULL),
(473, 'DNI', '70000107', 'Carlos Quispe Mejía', NULL, NULL, '910000107', 'docente.carlos.quispe.mejia.107@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:44', '2026-06-27 16:26:44', NULL, NULL, NULL, NULL, NULL, NULL),
(474, 'DNI', '70000109', 'Juan Mendoza Reyes', NULL, NULL, '910000109', 'estudiante.juan.mendoza.reyes.109@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:44', '2026-06-27 16:26:44', NULL, NULL, NULL, NULL, NULL, NULL),
(475, 'DNI', '70000110', 'Raúl Campos Cáceres', NULL, NULL, '910000110', 'estudiante.raul.campos.caceres.110@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:45', '2026-06-27 16:26:45', NULL, NULL, NULL, NULL, NULL, NULL),
(476, 'DNI', '70000111', 'Pedro Espinoza Morales', NULL, NULL, '910000111', 'estudiante.pedro.espinoza.morales.111@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:45', '2026-06-27 16:26:45', NULL, NULL, NULL, NULL, NULL, NULL),
(477, 'DNI', '70000112', 'Patricia Quispe Torres', NULL, NULL, '910000112', 'estudiante.patricia.quispe.torres.112@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:45', '2026-06-27 16:26:45', NULL, NULL, NULL, NULL, NULL, NULL),
(478, 'DNI', '70000113', 'Luis Bravo Condori', NULL, NULL, '910000113', 'estudiante.luis.bravo.condori.113@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:45', '2026-06-27 16:26:45', NULL, NULL, NULL, NULL, NULL, NULL),
(479, 'DNI', '70000115', 'Gabriela Castillo Paredes', NULL, NULL, '910000115', 'docente.gabriela.castillo.paredes.115@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:46', '2026-06-27 16:26:46', NULL, NULL, NULL, NULL, NULL, NULL),
(480, 'DNI', '70000116', 'Rosa Paredes Mendoza', NULL, NULL, '910000116', 'estudiante.rosa.paredes.mendoza.116@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:46', '2026-06-27 16:26:46', NULL, NULL, NULL, NULL, NULL, NULL),
(481, 'DNI', '70000117', 'Ana Mejía Bravo', NULL, NULL, '910000117', 'docente.ana.mejia.bravo.117@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:46', '2026-06-27 16:26:46', NULL, NULL, NULL, NULL, NULL, NULL),
(482, 'DNI', '70000118', 'Ruth Flores Arias', NULL, NULL, '910000118', 'estudiante.ruth.flores.arias.118@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:46', '2026-06-27 16:26:46', NULL, NULL, NULL, NULL, NULL, NULL),
(483, 'DNI', '70000119', 'Andrés Vargas Vargas', NULL, NULL, '910000119', 'docente.andres.vargas.vargas.119@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:46', '2026-06-27 16:26:46', NULL, NULL, NULL, NULL, NULL, NULL),
(484, 'DNI', '70000120', 'Gustavo Herrera Campos', NULL, NULL, '910000120', 'docente.gustavo.herrera.campos.120@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:47', '2026-06-27 16:26:47', NULL, NULL, NULL, NULL, NULL, NULL),
(485, 'DNI', '70000121', 'Paola Torres Huamán', NULL, NULL, '910000121', 'estudiante.paola.torres.huaman.121@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:47', '2026-06-27 16:26:47', NULL, NULL, NULL, NULL, NULL, NULL),
(486, 'DNI', '70000122', 'Manuel Condori Calderón', NULL, NULL, '910000122', 'estudiante.manuel.condori.calderon.122@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:47', '2026-06-27 16:26:47', NULL, NULL, NULL, NULL, NULL, NULL),
(487, 'DNI', '70000123', 'Ricardo Espinoza Mejía', NULL, NULL, '910000123', 'estudiante.ricardo.espinoza.mejia.123@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:47', '2026-06-27 16:26:47', NULL, NULL, NULL, NULL, NULL, NULL),
(488, 'DNI', '70000124', 'Karla Ramírez Torres', NULL, NULL, '910000124', 'docente.karla.ramirez.torres.124@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:48', '2026-06-27 16:26:48', NULL, NULL, NULL, NULL, NULL, NULL),
(489, 'DNI', '70000126', 'Joel Arias Arias', NULL, NULL, '910000126', 'estudiante.joel.arias.arias.126@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:48', '2026-06-27 16:26:48', NULL, NULL, NULL, NULL, NULL, NULL),
(490, 'DNI', '70000128', 'Jorge Carrillo Ramírez', NULL, NULL, '910000128', 'estudiante.jorge.carrillo.ramirez.128@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:48', '2026-06-27 16:26:48', NULL, NULL, NULL, NULL, NULL, NULL),
(491, 'DNI', '70000130', 'Daniela Campos Bravo', NULL, NULL, '910000130', 'estudiante.daniela.campos.bravo.130@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:49', '2026-06-27 16:26:49', NULL, NULL, NULL, NULL, NULL, NULL),
(492, 'DNI', '70000131', 'José Valdez Quispe', NULL, NULL, '910000131', 'estudiante.jose.valdez.quispe.131@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:49', '2026-06-27 16:26:49', NULL, NULL, NULL, NULL, NULL, NULL),
(493, 'DNI', '70000132', 'Paola Valdez Cruz', NULL, NULL, '910000132', 'docente.paola.valdez.cruz.132@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:49', '2026-06-27 16:26:49', NULL, NULL, NULL, NULL, NULL, NULL),
(494, 'DNI', '70000135', 'Erick Espinoza Chávez', NULL, NULL, '910000135', 'docente.erick.espinoza.chavez.135@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:50', '2026-06-27 16:26:50', NULL, NULL, NULL, NULL, NULL, NULL),
(495, 'DNI', '70000136', 'Patricia Morales Díaz', NULL, NULL, '910000136', 'estudiante.patricia.morales.diaz.136@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:50', '2026-06-27 16:26:50', NULL, NULL, NULL, NULL, NULL, NULL),
(496, 'DNI', '70000137', 'Diana Cruz Navarro', NULL, NULL, '910000137', 'estudiante.diana.cruz.navarro.137@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:50', '2026-06-27 16:26:50', NULL, NULL, NULL, NULL, NULL, NULL),
(497, 'DNI', '70000138', 'Ricardo Valdez Castillo', NULL, NULL, '910000138', 'docente.ricardo.valdez.castillo.138@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:51', '2026-06-27 16:26:51', NULL, NULL, NULL, NULL, NULL, NULL),
(498, 'DNI', '70000139', 'Yessica Cáceres Medina', NULL, NULL, '910000139', 'docente.yessica.caceres.medina.139@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:51', '2026-06-27 16:26:51', NULL, NULL, NULL, NULL, NULL, NULL),
(499, 'DNI', '70000140', 'Yessica Gutiérrez Mejía', NULL, NULL, '910000140', 'estudiante.yessica.gutierrez.mejia.140@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:51', '2026-06-27 16:26:51', NULL, NULL, NULL, NULL, NULL, NULL),
(500, 'DNI', '70000141', 'José Valdez Castillo', NULL, NULL, '910000141', 'estudiante.jose.valdez.castillo.141@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:51', '2026-06-27 16:26:51', NULL, NULL, NULL, NULL, NULL, NULL),
(501, 'DNI', '70000142', 'Carlos Reyes Peña', NULL, NULL, '910000142', 'estudiante.carlos.reyes.pena.142@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:51', '2026-06-27 16:26:51', NULL, NULL, NULL, NULL, NULL, NULL),
(502, 'DNI', '70000144', 'Alex Ramírez Valdez', NULL, NULL, '910000144', 'docente.alex.ramirez.valdez.144@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:52', '2026-06-27 16:26:52', NULL, NULL, NULL, NULL, NULL, NULL),
(503, 'DNI', '70000146', 'Natalia León Cruz', NULL, NULL, '910000146', 'estudiante.natalia.leon.cruz.146@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:52', '2026-06-27 16:26:52', NULL, NULL, NULL, NULL, NULL, NULL),
(504, 'DNI', '70000147', 'Gustavo Calderón Cruz', NULL, NULL, '910000147', 'docente.gustavo.calderon.cruz.147@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:52', '2026-06-27 16:26:52', NULL, NULL, NULL, NULL, NULL, NULL),
(505, 'DNI', '70000148', 'Carmen Paredes Bravo', NULL, NULL, '910000148', 'docente.carmen.paredes.bravo.148@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:53', '2026-06-27 16:26:53', NULL, NULL, NULL, NULL, NULL, NULL),
(506, 'DNI', '70000151', 'Miguel Arias Medina', NULL, NULL, '910000151', 'estudiante.miguel.arias.medina.151@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:53', '2026-06-27 16:26:53', NULL, NULL, NULL, NULL, NULL, NULL),
(507, 'DNI', '70000152', 'Sofía Gutiérrez Herrera', NULL, NULL, '910000152', 'docente.sofia.gutierrez.herrera.152@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:54', '2026-06-27 16:26:54', NULL, NULL, NULL, NULL, NULL, NULL),
(508, 'DNI', '70000153', 'Estefany Rojas Campos', NULL, NULL, '910000153', 'docente.estefany.rojas.campos.153@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:54', '2026-06-27 16:26:54', NULL, NULL, NULL, NULL, NULL, NULL),
(509, 'DNI', '70000154', 'Hugo Mendoza Morales', NULL, NULL, '910000154', 'estudiante.hugo.mendoza.morales.154@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:54', '2026-06-27 16:26:54', NULL, NULL, NULL, NULL, NULL, NULL),
(510, 'DNI', '70000155', 'Marco Quispe Cáceres', NULL, NULL, '910000155', 'estudiante.marco.quispe.caceres.155@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:54', '2026-06-27 16:26:54', NULL, NULL, NULL, NULL, NULL, NULL),
(511, 'DNI', '70000156', 'César Sánchez Calderón', NULL, NULL, '910000156', 'docente.cesar.sanchez.calderon.156@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:54', '2026-06-27 16:26:54', NULL, NULL, NULL, NULL, NULL, NULL),
(512, 'DNI', '70000157', 'Brenda León Cáceres', NULL, NULL, '910000157', 'estudiante.brenda.leon.caceres.157@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:55', '2026-06-27 16:26:55', NULL, NULL, NULL, NULL, NULL, NULL),
(513, 'DNI', '70000159', 'Carmen Condori Espinoza', NULL, NULL, '910000159', 'docente.carmen.condori.espinoza.159@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:55', '2026-06-27 16:26:55', NULL, NULL, NULL, NULL, NULL, NULL),
(514, 'DNI', '70000160', 'Brenda Reyes Navarro', NULL, NULL, '910000160', 'estudiante.brenda.reyes.navarro.160@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:55', '2026-06-27 16:26:55', NULL, NULL, NULL, NULL, NULL, NULL),
(515, 'DNI', '70000161', 'Diego Arias León', NULL, NULL, '910000161', 'docente.diego.arias.leon.161@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:55', '2026-06-27 16:26:55', NULL, NULL, NULL, NULL, NULL, NULL),
(516, 'DNI', '70000162', 'Ruth Campos Quispe', NULL, NULL, '910000162', 'estudiante.ruth.campos.quispe.162@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:56', '2026-06-27 16:26:56', NULL, NULL, NULL, NULL, NULL, NULL),
(517, 'DNI', '70000164', 'Lorena Castillo Chávez', NULL, NULL, '910000164', 'docente.lorena.castillo.chavez.164@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:56', '2026-06-27 16:26:56', NULL, NULL, NULL, NULL, NULL, NULL),
(518, 'DNI', '70000166', 'Elena Salazar Campos', NULL, NULL, '910000166', 'estudiante.elena.salazar.campos.166@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:57', '2026-06-27 16:26:57', NULL, NULL, NULL, NULL, NULL, NULL),
(519, 'DNI', '70000167', 'Alex Peña Morales', NULL, NULL, '910000167', 'docente.alex.pena.morales.167@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:57', '2026-06-27 16:26:57', NULL, NULL, NULL, NULL, NULL, NULL),
(520, 'DNI', '70000168', 'Lucía Mejía Mejía', NULL, NULL, '910000168', 'docente.lucia.mejia.mejia.168@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:57', '2026-06-27 16:26:57', NULL, NULL, NULL, NULL, NULL, NULL),
(521, 'DNI', '70000169', 'Pablo Valdez Bravo', NULL, NULL, '910000169', 'docente.pablo.valdez.bravo.169@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:57', '2026-06-27 16:26:57', NULL, NULL, NULL, NULL, NULL, NULL),
(522, 'DNI', '70000172', 'Pedro Calderón Torres', NULL, NULL, '910000172', 'estudiante.pedro.calderon.torres.172@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:58', '2026-06-27 16:26:58', NULL, NULL, NULL, NULL, NULL, NULL),
(523, 'DNI', '70000173', 'Óscar Rojas Medina', NULL, NULL, '910000173', 'estudiante.oscar.rojas.medina.173@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:58', '2026-06-27 16:26:58', NULL, NULL, NULL, NULL, NULL, NULL),
(524, 'DNI', '70000176', 'Daniela Espinoza Valdez', NULL, NULL, '910000176', 'estudiante.daniela.espinoza.valdez.176@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:59', '2026-06-27 16:26:59', NULL, NULL, NULL, NULL, NULL, NULL),
(525, 'DNI', '70000177', 'Gabriela Peña Mejía', NULL, NULL, '910000177', 'estudiante.gabriela.pena.mejia.177@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:59', '2026-06-27 16:26:59', NULL, NULL, NULL, NULL, NULL, NULL),
(526, 'DNI', '70000178', 'Estefany Herrera Pérez', NULL, NULL, '910000178', 'estudiante.estefany.herrera.perez.178@demo.com', NULL, NULL, NULL, '2026-06-27 16:26:59', '2026-06-27 16:26:59', NULL, NULL, NULL, NULL, NULL, NULL),
(527, 'DNI', '70000180', 'Sofía Espinoza Gutiérrez', NULL, NULL, '910000180', 'estudiante.sofia.espinoza.gutierrez.180@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:00', '2026-06-27 16:27:00', NULL, NULL, NULL, NULL, NULL, NULL),
(528, 'DNI', '70000181', 'Joel Castillo Morales', NULL, NULL, '910000181', 'estudiante.joel.castillo.morales.181@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:00', '2026-06-27 16:27:00', NULL, NULL, NULL, NULL, NULL, NULL),
(529, 'DNI', '70000182', 'Joel Valdez Huamán', NULL, NULL, '910000182', 'docente.joel.valdez.huaman.182@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:00', '2026-06-27 16:27:00', NULL, NULL, NULL, NULL, NULL, NULL),
(530, 'DNI', '70000183', 'Tatiana Campos Espinoza', NULL, NULL, '910000183', 'estudiante.tatiana.campos.espinoza.183@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:00', '2026-06-27 16:27:00', NULL, NULL, NULL, NULL, NULL, NULL),
(531, 'DNI', '70000185', 'Carlos Espinoza Peña', NULL, NULL, '910000185', 'docente.carlos.espinoza.pena.185@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:01', '2026-06-27 16:27:01', NULL, NULL, NULL, NULL, NULL, NULL),
(532, 'DNI', '70000187', 'Valeria Quispe Navarro', NULL, NULL, '910000187', 'estudiante.valeria.quispe.navarro.187@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:01', '2026-06-27 16:27:01', NULL, NULL, NULL, NULL, NULL, NULL),
(533, 'DNI', '70000190', 'Sofía Quispe Bravo', NULL, NULL, '910000190', 'docente.sofia.quispe.bravo.190@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:02', '2026-06-27 16:27:02', NULL, NULL, NULL, NULL, NULL, NULL),
(534, 'DNI', '70000192', 'Manuel Salazar Huamán', NULL, NULL, '910000192', 'docente.manuel.salazar.huaman.192@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:02', '2026-06-27 16:27:02', NULL, NULL, NULL, NULL, NULL, NULL),
(535, 'DNI', '70000193', 'Raúl Arias Vega', NULL, NULL, '910000193', 'estudiante.raul.arias.vega.193@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:02', '2026-06-27 16:27:02', NULL, NULL, NULL, NULL, NULL, NULL),
(536, 'DNI', '70000194', 'Raúl Campos Condori', NULL, NULL, '910000194', 'docente.raul.campos.condori.194@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:03', '2026-06-27 16:27:03', NULL, NULL, NULL, NULL, NULL, NULL),
(537, 'DNI', '70000195', 'Andrea Medina León', NULL, NULL, '910000195', 'docente.andrea.medina.leon.195@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:03', '2026-06-27 16:27:03', NULL, NULL, NULL, NULL, NULL, NULL),
(538, 'DNI', '70000196', 'Marco Campos Mendoza', NULL, NULL, '910000196', 'estudiante.marco.campos.mendoza.196@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:03', '2026-06-27 16:27:03', NULL, NULL, NULL, NULL, NULL, NULL),
(539, 'DNI', '70000197', 'Pablo Espinoza Quispe', NULL, NULL, '910000197', 'estudiante.pablo.espinoza.quispe.197@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:03', '2026-06-27 16:27:03', NULL, NULL, NULL, NULL, NULL, NULL),
(540, 'DNI', '70000198', 'Yessica Mejía Chávez', NULL, NULL, '910000198', 'estudiante.yessica.mejia.chavez.198@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:03', '2026-06-27 16:27:03', NULL, NULL, NULL, NULL, NULL, NULL),
(541, 'DNI', '70000201', 'Elena Condori Castillo', NULL, NULL, '910000201', 'docente.elena.condori.castillo.201@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:04', '2026-06-27 16:27:04', NULL, NULL, NULL, NULL, NULL, NULL),
(542, 'DNI', '70000202', 'Karla Sánchez Espinoza', NULL, NULL, '910000202', 'estudiante.karla.sanchez.espinoza.202@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:04', '2026-06-27 16:27:04', NULL, NULL, NULL, NULL, NULL, NULL),
(543, 'DNI', '70000203', 'Raúl Medina León', NULL, NULL, '910000203', 'docente.raul.medina.leon.203@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:05', '2026-06-27 16:27:05', NULL, NULL, NULL, NULL, NULL, NULL),
(544, 'DNI', '70000204', 'Gustavo Silva Vega', NULL, NULL, '910000204', 'estudiante.gustavo.silva.vega.204@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:05', '2026-06-27 16:27:05', NULL, NULL, NULL, NULL, NULL, NULL),
(545, 'DNI', '70000205', 'Karla Ramírez Carrillo', NULL, NULL, '910000205', 'docente.karla.ramirez.carrillo.205@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:05', '2026-06-27 16:27:05', NULL, NULL, NULL, NULL, NULL, NULL),
(546, 'DNI', '70000206', 'Mónica Paredes Ramírez', NULL, NULL, '910000206', 'docente.monica.paredes.ramirez.206@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:05', '2026-06-27 16:27:05', NULL, NULL, NULL, NULL, NULL, NULL),
(547, 'DNI', '70000207', 'Patricia Espinoza Huamán', NULL, NULL, '910000207', 'docente.patricia.espinoza.huaman.207@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:05', '2026-06-27 16:27:05', NULL, NULL, NULL, NULL, NULL, NULL),
(548, 'DNI', '70000210', 'Elena Torres Castillo', NULL, NULL, '910000210', 'docente.elena.torres.castillo.210@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:06', '2026-06-27 16:27:06', NULL, NULL, NULL, NULL, NULL, NULL),
(549, 'DNI', '70000211', 'Erick Mejía Medina', NULL, NULL, '910000211', 'estudiante.erick.mejia.medina.211@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:06', '2026-06-27 16:27:06', NULL, NULL, NULL, NULL, NULL, NULL),
(550, 'DNI', '70000212', 'Renato Chávez Castillo', NULL, NULL, '910000212', 'estudiante.renato.chavez.castillo.212@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:06', '2026-06-27 16:27:06', NULL, NULL, NULL, NULL, NULL, NULL),
(551, 'DNI', '70000213', 'Alex Vargas Silva', NULL, NULL, '910000213', 'estudiante.alex.vargas.silva.213@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:07', '2026-06-27 16:27:07', NULL, NULL, NULL, NULL, NULL, NULL),
(552, 'DNI', '70000215', 'Diana Vásquez Chávez', NULL, NULL, '910000215', 'estudiante.diana.vasquez.chavez.215@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:07', '2026-06-27 16:27:07', NULL, NULL, NULL, NULL, NULL, NULL),
(553, 'DNI', '70000217', 'Pablo Silva Condori', NULL, NULL, '910000217', 'estudiante.pablo.silva.condori.217@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:08', '2026-06-27 16:27:08', NULL, NULL, NULL, NULL, NULL, NULL),
(554, 'DNI', '70000218', 'Daniela Arias Gutiérrez', NULL, NULL, '910000218', 'estudiante.daniela.arias.gutierrez.218@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:08', '2026-06-27 16:27:08', NULL, NULL, NULL, NULL, NULL, NULL),
(555, 'DNI', '70000219', 'Víctor León García', NULL, NULL, '910000219', 'docente.victor.leon.garcia.219@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:08', '2026-06-27 16:27:08', NULL, NULL, NULL, NULL, NULL, NULL),
(556, 'DNI', '70000220', 'Brenda Aguilar Gutiérrez', NULL, NULL, '910000220', 'docente.brenda.aguilar.gutierrez.220@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:08', '2026-06-27 16:27:08', NULL, NULL, NULL, NULL, NULL, NULL),
(557, 'DNI', '70000221', 'Sofía Chávez Quispe', NULL, NULL, '910000221', 'estudiante.sofia.chavez.quispe.221@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:08', '2026-06-27 16:27:08', NULL, NULL, NULL, NULL, NULL, NULL),
(558, 'DNI', '70000223', 'Yessica Vega Medina', NULL, NULL, '910000223', 'docente.yessica.vega.medina.223@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:09', '2026-06-27 16:27:09', NULL, NULL, NULL, NULL, NULL, NULL),
(559, 'DNI', '70000224', 'Claudia Díaz Peña', NULL, NULL, '910000224', 'estudiante.claudia.diaz.pena.224@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:09', '2026-06-27 16:27:09', NULL, NULL, NULL, NULL, NULL, NULL),
(560, 'DNI', '70000225', 'Tatiana Reyes Arias', NULL, NULL, '910000225', 'estudiante.tatiana.reyes.arias.225@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:09', '2026-06-27 16:27:09', NULL, NULL, NULL, NULL, NULL, NULL),
(561, 'DNI', '70000227', 'Sofía Gutiérrez León', NULL, NULL, '910000227', 'estudiante.sofia.gutierrez.leon.227@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:10', '2026-06-27 16:27:10', NULL, NULL, NULL, NULL, NULL, NULL),
(562, 'DNI', '70000229', 'Raúl Cáceres Salazar', NULL, NULL, '910000229', 'estudiante.raul.caceres.salazar.229@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:10', '2026-06-27 16:27:10', NULL, NULL, NULL, NULL, NULL, NULL),
(563, 'DNI', '70000230', 'Yessica Quispe Reyes', NULL, NULL, '910000230', 'estudiante.yessica.quispe.reyes.230@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:10', '2026-06-27 16:27:10', NULL, NULL, NULL, NULL, NULL, NULL),
(564, 'DNI', '70000232', 'Diana Torres León', NULL, NULL, '910000232', 'docente.diana.torres.leon.232@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:11', '2026-06-27 16:27:11', NULL, NULL, NULL, NULL, NULL, NULL),
(565, 'DNI', '70000234', 'Mónica Huamán Mendoza', NULL, NULL, '910000234', 'docente.monica.huaman.mendoza.234@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:11', '2026-06-27 16:27:11', NULL, NULL, NULL, NULL, NULL, NULL),
(566, 'DNI', '70000236', 'Karla Herrera Ortega', NULL, NULL, '910000236', 'docente.karla.herrera.ortega.236@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:12', '2026-06-27 16:27:12', NULL, NULL, NULL, NULL, NULL, NULL),
(567, 'DNI', '70000239', 'Wilmer Peña Navarro', NULL, NULL, '910000239', 'docente.wilmer.pena.navarro.239@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:12', '2026-06-27 16:27:12', NULL, NULL, NULL, NULL, NULL, NULL),
(568, 'DNI', '70000240', 'Andrés Chávez Mendoza', NULL, NULL, '910000240', 'docente.andres.chavez.mendoza.240@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:12', '2026-06-27 16:27:12', NULL, NULL, NULL, NULL, NULL, NULL),
(569, 'DNI', '70000242', 'César Aguilar Mejía', NULL, NULL, '910000242', 'estudiante.cesar.aguilar.mejia.242@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:13', '2026-06-27 16:27:13', NULL, NULL, NULL, NULL, NULL, NULL),
(570, 'DNI', '70000243', 'Wilmer Navarro Morales', NULL, NULL, '910000243', 'estudiante.wilmer.navarro.morales.243@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:13', '2026-06-27 16:27:13', NULL, NULL, NULL, NULL, NULL, NULL),
(571, 'DNI', '70000246', 'Sofía Campos Castillo', NULL, NULL, '910000246', 'estudiante.sofia.campos.castillo.246@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:14', '2026-06-27 16:27:14', NULL, NULL, NULL, NULL, NULL, NULL),
(572, 'DNI', '70000247', 'Jorge Rojas Peña', NULL, NULL, '910000247', 'docente.jorge.rojas.pena.247@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:14', '2026-06-27 16:27:14', NULL, NULL, NULL, NULL, NULL, NULL),
(573, 'DNI', '70000248', 'Wilmer Herrera Reyes', NULL, NULL, '910000248', 'estudiante.wilmer.herrera.reyes.248@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:14', '2026-06-27 16:27:14', NULL, NULL, NULL, NULL, NULL, NULL),
(574, 'DNI', '70000250', 'Ruth Silva Vega', NULL, NULL, '910000250', 'docente.ruth.silva.vega.250@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:15', '2026-06-27 16:27:15', NULL, NULL, NULL, NULL, NULL, NULL),
(575, 'DNI', '70000251', 'Andrea Carrillo Quispe', NULL, NULL, '910000251', 'docente.andrea.carrillo.quispe.251@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:15', '2026-06-27 16:27:15', NULL, NULL, NULL, NULL, NULL, NULL),
(576, 'DNI', '70000252', 'Patricia Vega Campos', NULL, NULL, '910000252', 'estudiante.patricia.vega.campos.252@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:15', '2026-06-27 16:27:15', NULL, NULL, NULL, NULL, NULL, NULL),
(577, 'DNI', '70000254', 'Juan Flores Pérez', NULL, NULL, '910000254', 'estudiante.juan.flores.perez.254@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:15', '2026-06-27 16:27:15', NULL, NULL, NULL, NULL, NULL, NULL),
(578, 'DNI', '70000255', 'Jorge Cáceres Navarro', NULL, NULL, '910000255', 'estudiante.jorge.caceres.navarro.255@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:16', '2026-06-27 16:27:16', NULL, NULL, NULL, NULL, NULL, NULL),
(579, 'DNI', '70000256', 'César Salazar Ortega', NULL, NULL, '910000256', 'estudiante.cesar.salazar.ortega.256@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:16', '2026-06-27 16:27:16', NULL, NULL, NULL, NULL, NULL, NULL),
(580, 'DNI', '70000259', 'Renato Vega Chávez', NULL, NULL, '910000259', 'docente.renato.vega.chavez.259@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:17', '2026-06-27 16:27:17', NULL, NULL, NULL, NULL, NULL, NULL),
(581, 'DNI', '70000260', 'Daniela Bravo Campos', NULL, NULL, '910000260', 'estudiante.daniela.bravo.campos.260@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:17', '2026-06-27 16:27:17', NULL, NULL, NULL, NULL, NULL, NULL),
(582, 'DNI', '70000261', 'Mónica Arias Bravo', NULL, NULL, '910000261', 'estudiante.monica.arias.bravo.261@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:17', '2026-06-27 16:27:17', NULL, NULL, NULL, NULL, NULL, NULL),
(583, 'DNI', '70000262', 'Rosa Sánchez Chávez', NULL, NULL, '910000262', 'docente.rosa.sanchez.chavez.262@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:17', '2026-06-27 16:27:17', NULL, NULL, NULL, NULL, NULL, NULL),
(584, 'DNI', '70000265', 'Andrés Díaz Campos', NULL, NULL, '910000265', 'estudiante.andres.diaz.campos.265@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:18', '2026-06-27 16:27:18', NULL, NULL, NULL, NULL, NULL, NULL),
(585, 'DNI', '70000266', 'Pedro Carrillo Campos', NULL, NULL, '910000266', 'docente.pedro.carrillo.campos.266@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:18', '2026-06-27 16:27:18', NULL, NULL, NULL, NULL, NULL, NULL),
(586, 'DNI', '70000267', 'Andrés Reyes Carrillo', NULL, NULL, '910000267', 'docente.andres.reyes.carrillo.267@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:18', '2026-06-27 16:27:18', NULL, NULL, NULL, NULL, NULL, NULL),
(587, 'DNI', '70000269', 'Tatiana Calderón Reyes', NULL, NULL, '910000269', 'docente.tatiana.calderon.reyes.269@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:19', '2026-06-27 16:27:19', NULL, NULL, NULL, NULL, NULL, NULL),
(588, 'DNI', '70000274', 'Lorena Herrera Sánchez', NULL, NULL, '910000274', 'estudiante.lorena.herrera.sanchez.274@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:20', '2026-06-27 16:27:20', NULL, NULL, NULL, NULL, NULL, NULL),
(589, 'DNI', '70000277', 'José Pérez Peña', NULL, NULL, '910000277', 'docente.jose.perez.pena.277@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:20', '2026-06-27 16:27:20', NULL, NULL, NULL, NULL, NULL, NULL),
(590, 'DNI', '70000278', 'Fiorella Campos Navarro', NULL, NULL, '910000278', 'estudiante.fiorella.campos.navarro.278@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:21', '2026-06-27 16:27:21', NULL, NULL, NULL, NULL, NULL, NULL),
(591, 'DNI', '70000280', 'Jorge Vargas Reyes', NULL, NULL, '910000280', 'docente.jorge.vargas.reyes.280@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:21', '2026-06-27 16:27:21', NULL, NULL, NULL, NULL, NULL, NULL),
(592, 'DNI', '70000281', 'Bryan Castillo Chávez', NULL, NULL, '910000281', 'estudiante.bryan.castillo.chavez.281@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:21', '2026-06-27 16:27:21', NULL, NULL, NULL, NULL, NULL, NULL),
(593, 'DNI', '70000284', 'Luis Paredes Flores', NULL, NULL, '910000284', 'docente.luis.paredes.flores.284@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:22', '2026-06-27 16:27:22', NULL, NULL, NULL, NULL, NULL, NULL),
(594, 'DNI', '70000285', 'Alex Espinoza Torres', NULL, NULL, '910000285', 'estudiante.alex.espinoza.torres.285@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:22', '2026-06-27 16:27:22', NULL, NULL, NULL, NULL, NULL, NULL),
(595, 'DNI', '70000286', 'Carlos León Flores', NULL, NULL, '910000286', 'estudiante.carlos.leon.flores.286@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:22', '2026-06-27 16:27:22', NULL, NULL, NULL, NULL, NULL, NULL),
(596, 'DNI', '70000288', 'Alex Mendoza León', NULL, NULL, '910000288', 'docente.alex.mendoza.leon.288@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:23', '2026-06-27 16:27:23', NULL, NULL, NULL, NULL, NULL, NULL),
(597, 'DNI', '70000290', 'Alonso Flores Bravo', NULL, NULL, '910000290', 'docente.alonso.flores.bravo.290@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:23', '2026-06-27 16:27:23', NULL, NULL, NULL, NULL, NULL, NULL),
(598, 'DNI', '70000291', 'Mónica Morales Carrillo', NULL, NULL, '910000291', 'estudiante.monica.morales.carrillo.291@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:23', '2026-06-27 16:27:23', NULL, NULL, NULL, NULL, NULL, NULL),
(599, 'DNI', '70000294', 'César García Silva', NULL, NULL, '910000294', 'docente.cesar.garcia.silva.294@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:24', '2026-06-27 16:27:24', NULL, NULL, NULL, NULL, NULL, NULL),
(600, 'DNI', '70000295', 'Gustavo Ortega Calderón', NULL, NULL, '910000295', 'docente.gustavo.ortega.calderon.295@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:24', '2026-06-27 16:27:24', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `person` (`id`, `document_type`, `document_number`, `names`, `career`, `study_program_id`, `phone`, `email`, `sex`, `birth_date`, `native_language`, `created_at`, `updated_at`, `deleted_at`, `about_me`, `skills`, `hobbies`, `education`, `experience`) VALUES
(601, 'DNI', '70000296', 'Hugo Valdez Pérez', NULL, NULL, '910000296', 'docente.hugo.valdez.perez.296@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:25', '2026-06-27 16:27:25', NULL, NULL, NULL, NULL, NULL, NULL),
(602, 'DNI', '70000297', 'Estefany León Peña', NULL, NULL, '910000297', 'docente.estefany.leon.pena.297@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:25', '2026-06-27 16:27:25', NULL, NULL, NULL, NULL, NULL, NULL),
(603, 'DNI', '70000298', 'Manuel Morales Morales', NULL, NULL, '910000298', 'estudiante.manuel.morales.morales.298@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:25', '2026-06-27 16:27:25', NULL, NULL, NULL, NULL, NULL, NULL),
(604, 'DNI', '70000299', 'Silvia Silva Reyes', NULL, NULL, '910000299', 'estudiante.silvia.silva.reyes.299@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:25', '2026-06-27 16:27:25', NULL, NULL, NULL, NULL, NULL, NULL),
(605, 'DNI', '70000300', 'Alonso Condori Cruz', NULL, NULL, '910000300', 'docente.alonso.condori.cruz.300@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:25', '2026-06-27 16:27:25', NULL, NULL, NULL, NULL, NULL, NULL),
(606, 'DNI', '70000301', 'César Vega Aguilar', NULL, NULL, '910000301', 'estudiante.cesar.vega.aguilar.301@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:26', '2026-06-27 16:27:26', NULL, NULL, NULL, NULL, NULL, NULL),
(607, 'DNI', '70000302', 'Eduardo Pérez Medina', NULL, NULL, '910000302', 'estudiante.eduardo.perez.medina.302@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:26', '2026-06-27 16:27:26', NULL, NULL, NULL, NULL, NULL, NULL),
(608, 'DNI', '70000303', 'Alex Ortega Valdez', NULL, NULL, '910000303', 'docente.alex.ortega.valdez.303@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:26', '2026-06-27 16:27:26', NULL, NULL, NULL, NULL, NULL, NULL),
(609, 'DNI', '70000305', 'Pedro Campos García', NULL, NULL, '910000305', 'estudiante.pedro.campos.garcia.305@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:26', '2026-06-27 16:27:26', NULL, NULL, NULL, NULL, NULL, NULL),
(610, 'DNI', '70000307', 'José Arias Navarro', NULL, NULL, '910000307', 'estudiante.jose.arias.navarro.307@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:27', '2026-06-27 16:27:27', NULL, NULL, NULL, NULL, NULL, NULL),
(611, 'DNI', '70000309', 'Raúl Carrillo León', NULL, NULL, '910000309', 'estudiante.raul.carrillo.leon.309@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:27', '2026-06-27 16:27:27', NULL, NULL, NULL, NULL, NULL, NULL),
(612, 'DNI', '70000310', 'Hugo Cruz Ortega', NULL, NULL, '910000310', 'estudiante.hugo.cruz.ortega.310@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:28', '2026-06-27 16:27:28', NULL, NULL, NULL, NULL, NULL, NULL),
(613, 'DNI', '70000311', 'Gabriela Díaz Calderón', NULL, NULL, '910000311', 'docente.gabriela.diaz.calderon.311@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:28', '2026-06-27 16:27:28', NULL, NULL, NULL, NULL, NULL, NULL),
(614, 'DNI', '70000312', 'Valeria Bravo Espinoza', NULL, NULL, '910000312', 'estudiante.valeria.bravo.espinoza.312@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:28', '2026-06-27 16:27:28', NULL, NULL, NULL, NULL, NULL, NULL),
(615, 'DNI', '70000314', 'Joel Reyes Castillo', NULL, NULL, '910000314', 'docente.joel.reyes.castillo.314@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:28', '2026-06-27 16:27:28', NULL, NULL, NULL, NULL, NULL, NULL),
(616, 'DNI', '70000315', 'Claudia Rojas Castillo', NULL, NULL, '910000315', 'docente.claudia.rojas.castillo.315@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:29', '2026-06-27 16:27:29', NULL, NULL, NULL, NULL, NULL, NULL),
(617, 'DNI', '70000318', 'Gustavo Ortega Torres', NULL, NULL, '910000318', 'estudiante.gustavo.ortega.torres.318@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:29', '2026-06-27 16:27:29', NULL, NULL, NULL, NULL, NULL, NULL),
(618, 'DNI', '70000320', 'Carmen Valdez Reyes', NULL, NULL, '910000320', 'estudiante.carmen.valdez.reyes.320@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:30', '2026-06-27 16:27:30', NULL, NULL, NULL, NULL, NULL, NULL),
(619, 'DNI', '70000321', 'Milagros Arias Condori', NULL, NULL, '910000321', 'estudiante.milagros.arias.condori.321@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:30', '2026-06-27 16:27:30', NULL, NULL, NULL, NULL, NULL, NULL),
(620, 'DNI', '70000323', 'Lorena Espinoza Cruz', NULL, NULL, '910000323', 'docente.lorena.espinoza.cruz.323@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:30', '2026-06-27 16:27:30', NULL, NULL, NULL, NULL, NULL, NULL),
(621, 'DNI', '70000325', 'Valeria Sánchez Cruz', NULL, NULL, '910000325', 'estudiante.valeria.sanchez.cruz.325@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:31', '2026-06-27 16:27:31', NULL, NULL, NULL, NULL, NULL, NULL),
(622, 'DNI', '70000326', 'José Chávez Ramírez', NULL, NULL, '910000326', 'estudiante.jose.chavez.ramirez.326@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:31', '2026-06-27 16:27:31', NULL, NULL, NULL, NULL, NULL, NULL),
(623, 'DNI', '70000330', 'Andrea Carrillo Ortega', NULL, NULL, '910000330', 'docente.andrea.carrillo.ortega.330@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:32', '2026-06-27 16:27:32', NULL, NULL, NULL, NULL, NULL, NULL),
(624, 'DNI', '70000331', 'Hugo Torres Carrillo', NULL, NULL, '910000331', 'estudiante.hugo.torres.carrillo.331@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:32', '2026-06-27 16:27:32', NULL, NULL, NULL, NULL, NULL, NULL),
(625, 'DNI', '70000332', 'Raúl Espinoza Ramírez', NULL, NULL, '910000332', 'estudiante.raul.espinoza.ramirez.332@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:32', '2026-06-27 16:27:32', NULL, NULL, NULL, NULL, NULL, NULL),
(626, 'DNI', '70000333', 'Renato Quispe León', NULL, NULL, '910000333', 'docente.renato.quispe.leon.333@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:32', '2026-06-27 16:27:32', NULL, NULL, NULL, NULL, NULL, NULL),
(627, 'DNI', '70000335', 'Raúl Calderón Mejía', NULL, NULL, '910000335', 'docente.raul.calderon.mejia.335@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:33', '2026-06-27 16:27:33', NULL, NULL, NULL, NULL, NULL, NULL),
(628, 'DNI', '70000336', 'Ricardo Flores Ortega', NULL, NULL, '910000336', 'docente.ricardo.flores.ortega.336@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:33', '2026-06-27 16:27:33', NULL, NULL, NULL, NULL, NULL, NULL),
(629, 'DNI', '70000337', 'Carmen Flores Navarro', NULL, NULL, '910000337', 'docente.carmen.flores.navarro.337@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:33', '2026-06-27 16:27:33', NULL, NULL, NULL, NULL, NULL, NULL),
(630, 'DNI', '70000338', 'Patricia Paredes Reyes', NULL, NULL, '910000338', 'docente.patricia.paredes.reyes.338@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:34', '2026-06-27 16:27:34', NULL, NULL, NULL, NULL, NULL, NULL),
(631, 'DNI', '70000340', 'Alex García Chávez', NULL, NULL, '910000340', 'estudiante.alex.garcia.chavez.340@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:34', '2026-06-27 16:27:34', NULL, NULL, NULL, NULL, NULL, NULL),
(632, 'DNI', '70000341', 'Sofía Mejía Aguilar', NULL, NULL, '910000341', 'estudiante.sofia.mejia.aguilar.341@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:34', '2026-06-27 16:27:34', NULL, NULL, NULL, NULL, NULL, NULL),
(633, 'DNI', '70000344', 'Marco Peña García', NULL, NULL, '910000344', 'estudiante.marco.pena.garcia.344@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:35', '2026-06-27 16:27:35', NULL, NULL, NULL, NULL, NULL, NULL),
(634, 'DNI', '70000346', 'Noelia Paredes García', NULL, NULL, '910000346', 'estudiante.noelia.paredes.garcia.346@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:35', '2026-06-27 16:27:35', NULL, NULL, NULL, NULL, NULL, NULL),
(635, 'DNI', '70000347', 'César Valdez Navarro', NULL, NULL, '910000347', 'docente.cesar.valdez.navarro.347@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:36', '2026-06-27 16:27:36', NULL, NULL, NULL, NULL, NULL, NULL),
(636, 'DNI', '70000348', 'Gustavo Díaz Sánchez', NULL, NULL, '910000348', 'estudiante.gustavo.diaz.sanchez.348@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:36', '2026-06-27 16:27:36', NULL, NULL, NULL, NULL, NULL, NULL),
(637, 'DNI', '70000349', 'Marco Reyes León', NULL, NULL, '910000349', 'estudiante.marco.reyes.leon.349@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:36', '2026-06-27 16:27:36', NULL, NULL, NULL, NULL, NULL, NULL),
(638, 'DNI', '70000350', 'Joel García Huamán', NULL, NULL, '910000350', 'estudiante.joel.garcia.huaman.350@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:36', '2026-06-27 16:27:36', NULL, NULL, NULL, NULL, NULL, NULL),
(639, 'DNI', '70000351', 'Lucía Mejía Vega', NULL, NULL, '910000351', 'docente.lucia.mejia.vega.351@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:36', '2026-06-27 16:27:36', NULL, NULL, NULL, NULL, NULL, NULL),
(640, 'DNI', '70000352', 'Eduardo Chávez Pérez', NULL, NULL, '910000352', 'estudiante.eduardo.chavez.perez.352@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:37', '2026-06-27 16:27:37', NULL, NULL, NULL, NULL, NULL, NULL),
(641, 'DNI', '70000353', 'Lorena Pérez Mendoza', NULL, NULL, '910000353', 'estudiante.lorena.perez.mendoza.353@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:37', '2026-06-27 16:27:37', NULL, NULL, NULL, NULL, NULL, NULL),
(642, 'DNI', '70000357', 'Eduardo Chávez García', NULL, NULL, '910000357', 'estudiante.eduardo.chavez.garcia.357@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:38', '2026-06-27 16:27:38', NULL, NULL, NULL, NULL, NULL, NULL),
(643, 'DNI', '70000358', 'César Valdez Vega', NULL, NULL, '910000358', 'estudiante.cesar.valdez.vega.358@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:38', '2026-06-27 16:27:38', NULL, NULL, NULL, NULL, NULL, NULL),
(644, 'DNI', '70000362', 'Lucía Díaz Torres', NULL, NULL, '910000362', 'estudiante.lucia.diaz.torres.362@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:39', '2026-06-27 16:27:39', NULL, NULL, NULL, NULL, NULL, NULL),
(645, 'DNI', '70000364', 'María Rojas Cruz', NULL, NULL, '910000364', 'estudiante.maria.rojas.cruz.364@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:39', '2026-06-27 16:27:39', NULL, NULL, NULL, NULL, NULL, NULL),
(646, 'DNI', '70000365', 'Lucía Vega Carrillo', NULL, NULL, '910000365', 'docente.lucia.vega.carrillo.365@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:39', '2026-06-27 16:27:39', NULL, NULL, NULL, NULL, NULL, NULL),
(647, 'DNI', '70000367', 'Karla Salazar Vega', NULL, NULL, '910000367', 'docente.karla.salazar.vega.367@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:40', '2026-06-27 16:27:40', NULL, NULL, NULL, NULL, NULL, NULL),
(648, 'DNI', '70000369', 'Pablo Bravo Carrillo', NULL, NULL, '910000369', 'docente.pablo.bravo.carrillo.369@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:40', '2026-06-27 16:27:40', NULL, NULL, NULL, NULL, NULL, NULL),
(649, 'DNI', '70000372', 'José Calderón Cruz', NULL, NULL, '910000372', 'docente.jose.calderon.cruz.372@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:41', '2026-06-27 16:27:41', NULL, NULL, NULL, NULL, NULL, NULL),
(650, 'DNI', '70000373', 'Erick Navarro Huamán', NULL, NULL, '910000373', 'docente.erick.navarro.huaman.373@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:41', '2026-06-27 16:27:41', NULL, NULL, NULL, NULL, NULL, NULL),
(651, 'DNI', '70000375', 'Jorge Torres Flores', NULL, NULL, '910000375', 'estudiante.jorge.torres.flores.375@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:42', '2026-06-27 16:27:42', NULL, NULL, NULL, NULL, NULL, NULL),
(652, 'DNI', '70000377', 'Juan Vega Silva', NULL, NULL, '910000377', 'docente.juan.vega.silva.377@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:42', '2026-06-27 16:27:42', NULL, NULL, NULL, NULL, NULL, NULL),
(653, 'DNI', '70000378', 'Vanessa Vásquez Peña', NULL, NULL, '910000378', 'docente.vanessa.vasquez.pena.378@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:42', '2026-06-27 16:27:42', NULL, NULL, NULL, NULL, NULL, NULL),
(654, 'DNI', '70000380', 'Hugo Silva Flores', NULL, NULL, '910000380', 'estudiante.hugo.silva.flores.380@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:43', '2026-06-27 16:27:43', NULL, NULL, NULL, NULL, NULL, NULL),
(655, 'DNI', '70000381', 'Luis Medina Arias', NULL, NULL, '910000381', 'estudiante.luis.medina.arias.381@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:43', '2026-06-27 16:27:43', NULL, NULL, NULL, NULL, NULL, NULL),
(656, 'DNI', '70000382', 'María Aguilar Cruz', NULL, NULL, '910000382', 'estudiante.maria.aguilar.cruz.382@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:43', '2026-06-27 16:27:43', NULL, NULL, NULL, NULL, NULL, NULL),
(657, 'DNI', '70000383', 'Carlos Carrillo Castillo', NULL, NULL, '910000383', 'docente.carlos.carrillo.castillo.383@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:43', '2026-06-27 16:27:43', NULL, NULL, NULL, NULL, NULL, NULL),
(658, 'DNI', '70000385', 'Juan Díaz Ortega', NULL, NULL, '910000385', 'docente.juan.diaz.ortega.385@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:44', '2026-06-27 16:27:44', NULL, NULL, NULL, NULL, NULL, NULL),
(659, 'DNI', '70000386', 'Juan Peña Castillo', NULL, NULL, '910000386', 'estudiante.juan.pena.castillo.386@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:44', '2026-06-27 16:27:44', NULL, NULL, NULL, NULL, NULL, NULL),
(660, 'DNI', '70000388', 'Andrea Aguilar Vásquez', NULL, NULL, '910000388', 'docente.andrea.aguilar.vasquez.388@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:44', '2026-06-27 16:27:44', NULL, NULL, NULL, NULL, NULL, NULL),
(661, 'DNI', '70000389', 'Víctor Vásquez Pérez', NULL, NULL, '910000389', 'docente.victor.vasquez.perez.389@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:45', '2026-06-27 16:27:45', NULL, NULL, NULL, NULL, NULL, NULL),
(662, 'DNI', '70000390', 'Fiorella Quispe Castillo', NULL, NULL, '910000390', 'docente.fiorella.quispe.castillo.390@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:45', '2026-06-27 16:27:45', NULL, NULL, NULL, NULL, NULL, NULL),
(663, 'DNI', '70000391', 'Eduardo Calderón Medina', NULL, NULL, '910000391', 'docente.eduardo.calderon.medina.391@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:45', '2026-06-27 16:27:45', NULL, NULL, NULL, NULL, NULL, NULL),
(664, 'DNI', '70000393', 'Mónica Quispe Chávez', NULL, NULL, '910000393', 'docente.monica.quispe.chavez.393@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:45', '2026-06-27 16:27:45', NULL, NULL, NULL, NULL, NULL, NULL),
(665, 'DNI', '70000394', 'Camila Rojas Gutiérrez', NULL, NULL, '910000394', 'estudiante.camila.rojas.gutierrez.394@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:46', '2026-06-27 16:27:46', NULL, NULL, NULL, NULL, NULL, NULL),
(666, 'DNI', '70000395', 'Renato Salazar Flores', NULL, NULL, '910000395', 'docente.renato.salazar.flores.395@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:46', '2026-06-27 16:27:46', NULL, NULL, NULL, NULL, NULL, NULL),
(667, 'DNI', '70000396', 'Tatiana León Torres', NULL, NULL, '910000396', 'docente.tatiana.leon.torres.396@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:46', '2026-06-27 16:27:46', NULL, NULL, NULL, NULL, NULL, NULL),
(668, 'DNI', '70000398', 'Ricardo Vega Herrera', NULL, NULL, '910000398', 'estudiante.ricardo.vega.herrera.398@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:46', '2026-06-27 16:27:46', NULL, NULL, NULL, NULL, NULL, NULL),
(669, 'DNI', '70000400', 'Bryan Sánchez Vega', NULL, NULL, '910000400', 'estudiante.bryan.sanchez.vega.400@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:47', '2026-06-27 16:27:47', NULL, NULL, NULL, NULL, NULL, NULL),
(670, 'DNI', '70000402', 'Carmen Navarro Bravo', NULL, NULL, '910000402', 'docente.carmen.navarro.bravo.402@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:47', '2026-06-27 16:27:47', NULL, NULL, NULL, NULL, NULL, NULL),
(671, 'DNI', '70000404', 'Sofía León Medina', NULL, NULL, '910000404', 'docente.sofia.leon.medina.404@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:48', '2026-06-27 16:27:48', NULL, NULL, NULL, NULL, NULL, NULL),
(672, 'DNI', '70000405', 'Raúl Vásquez Castillo', NULL, NULL, '910000405', 'estudiante.raul.vasquez.castillo.405@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:48', '2026-06-27 16:27:48', NULL, NULL, NULL, NULL, NULL, NULL),
(673, 'DNI', '70000407', 'Lucía Campos Bravo', NULL, NULL, '910000407', 'estudiante.lucia.campos.bravo.407@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:48', '2026-06-27 16:27:48', NULL, NULL, NULL, NULL, NULL, NULL),
(674, 'DNI', '70000408', 'Valeria Quispe Valdez', NULL, NULL, '910000408', 'docente.valeria.quispe.valdez.408@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:49', '2026-06-27 16:27:49', NULL, NULL, NULL, NULL, NULL, NULL),
(675, 'DNI', '70000410', 'Claudia Espinoza Flores', NULL, NULL, '910000410', 'estudiante.claudia.espinoza.flores.410@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:49', '2026-06-27 16:27:49', NULL, NULL, NULL, NULL, NULL, NULL),
(676, 'DNI', '70000411', 'Jorge Mendoza Castillo', NULL, NULL, '910000411', 'docente.jorge.mendoza.castillo.411@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:49', '2026-06-27 16:27:49', NULL, NULL, NULL, NULL, NULL, NULL),
(677, 'DNI', '70000412', 'Erick Rojas Bravo', NULL, NULL, '910000412', 'docente.erick.rojas.bravo.412@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:49', '2026-06-27 16:27:49', NULL, NULL, NULL, NULL, NULL, NULL),
(678, 'DNI', '70000416', 'Camila Pérez Torres', NULL, NULL, '910000416', 'estudiante.camila.perez.torres.416@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:50', '2026-06-27 16:27:50', NULL, NULL, NULL, NULL, NULL, NULL),
(679, 'DNI', '70000417', 'Raúl León Castillo', NULL, NULL, '910000417', 'docente.raul.leon.castillo.417@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:51', '2026-06-27 16:27:51', NULL, NULL, NULL, NULL, NULL, NULL),
(680, 'DNI', '70000419', 'Gustavo Carrillo Díaz', NULL, NULL, '910000419', 'estudiante.gustavo.carrillo.diaz.419@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:51', '2026-06-27 16:27:51', NULL, NULL, NULL, NULL, NULL, NULL),
(681, 'DNI', '70000420', 'Elena Quispe Arias', NULL, NULL, '910000420', 'docente.elena.quispe.arias.420@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:51', '2026-06-27 16:27:51', NULL, NULL, NULL, NULL, NULL, NULL),
(682, 'DNI', '70000423', 'Eduardo Vega Díaz', NULL, NULL, '910000423', 'estudiante.eduardo.vega.diaz.423@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:52', '2026-06-27 16:27:52', NULL, NULL, NULL, NULL, NULL, NULL),
(683, 'DNI', '70000424', 'Lucía Ramírez Espinoza', NULL, NULL, '910000424', 'estudiante.lucia.ramirez.espinoza.424@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:52', '2026-06-27 16:27:52', NULL, NULL, NULL, NULL, NULL, NULL),
(684, 'DNI', '70000425', 'Carlos Silva Carrillo', NULL, NULL, '910000425', 'docente.carlos.silva.carrillo.425@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:52', '2026-06-27 16:27:52', NULL, NULL, NULL, NULL, NULL, NULL),
(685, 'DNI', '70000430', 'Juan Carrillo Peña', NULL, NULL, '910000430', 'docente.juan.carrillo.pena.430@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:53', '2026-06-27 16:27:53', NULL, NULL, NULL, NULL, NULL, NULL),
(686, 'DNI', '70000431', 'Pablo Torres Paredes', NULL, NULL, '910000431', 'docente.pablo.torres.paredes.431@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:54', '2026-06-27 16:27:54', NULL, NULL, NULL, NULL, NULL, NULL),
(687, 'DNI', '70000432', 'Marco Medina Ortega', NULL, NULL, '910000432', 'docente.marco.medina.ortega.432@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:54', '2026-06-27 16:27:54', NULL, NULL, NULL, NULL, NULL, NULL),
(688, 'DNI', '70000433', 'Jorge Calderón Torres', NULL, NULL, '910000433', 'docente.jorge.calderon.torres.433@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:54', '2026-06-27 16:27:54', NULL, NULL, NULL, NULL, NULL, NULL),
(689, 'DNI', '70000434', 'Rosa Quispe Peña', NULL, NULL, '910000434', 'estudiante.rosa.quispe.pena.434@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:54', '2026-06-27 16:27:54', NULL, NULL, NULL, NULL, NULL, NULL),
(690, 'DNI', '70000436', 'Juan Silva Ramírez', NULL, NULL, '910000436', 'docente.juan.silva.ramirez.436@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:55', '2026-06-27 16:27:55', NULL, NULL, NULL, NULL, NULL, NULL),
(691, 'DNI', '70000437', 'Marco Navarro Arias', NULL, NULL, '910000437', 'estudiante.marco.navarro.arias.437@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:55', '2026-06-27 16:27:55', NULL, NULL, NULL, NULL, NULL, NULL),
(692, 'DNI', '70000438', 'Carlos Vásquez Mejía', NULL, NULL, '910000438', 'estudiante.carlos.vasquez.mejia.438@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:55', '2026-06-27 16:27:55', NULL, NULL, NULL, NULL, NULL, NULL),
(693, 'DNI', '70000439', 'Óscar Torres Torres', NULL, NULL, '910000439', 'estudiante.oscar.torres.torres.439@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:55', '2026-06-27 16:27:55', NULL, NULL, NULL, NULL, NULL, NULL),
(694, 'DNI', '70000440', 'Renato Silva Ortega', NULL, NULL, '910000440', 'estudiante.renato.silva.ortega.440@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:56', '2026-06-27 16:27:56', NULL, NULL, NULL, NULL, NULL, NULL),
(695, 'DNI', '70000441', 'Daniela Flores Herrera', NULL, NULL, '910000441', 'docente.daniela.flores.herrera.441@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:56', '2026-06-27 16:27:56', NULL, NULL, NULL, NULL, NULL, NULL),
(696, 'DNI', '70000442', 'Miguel Mendoza Mejía', NULL, NULL, '910000442', 'estudiante.miguel.mendoza.mejia.442@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:56', '2026-06-27 16:27:56', NULL, NULL, NULL, NULL, NULL, NULL),
(697, 'DNI', '70000443', 'Noelia Vásquez Flores', NULL, NULL, '910000443', 'estudiante.noelia.vasquez.flores.443@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:56', '2026-06-27 16:27:56', NULL, NULL, NULL, NULL, NULL, NULL),
(698, 'DNI', '70000444', 'José Medina Carrillo', NULL, NULL, '910000444', 'docente.jose.medina.carrillo.444@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:56', '2026-06-27 16:27:56', NULL, NULL, NULL, NULL, NULL, NULL),
(699, 'DNI', '70000445', 'Patricia Vega Gutiérrez', NULL, NULL, '910000445', 'estudiante.patricia.vega.gutierrez.445@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:57', '2026-06-27 16:27:57', NULL, NULL, NULL, NULL, NULL, NULL),
(700, 'DNI', '70000449', 'Joel Herrera Carrillo', NULL, NULL, '910000449', 'estudiante.joel.herrera.carrillo.449@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:57', '2026-06-27 16:27:57', NULL, NULL, NULL, NULL, NULL, NULL),
(701, 'DNI', '70000450', 'Gabriela Gutiérrez Carrillo', NULL, NULL, '910000450', 'estudiante.gabriela.gutierrez.carrillo.450@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:58', '2026-06-27 16:27:58', NULL, NULL, NULL, NULL, NULL, NULL),
(702, 'DNI', '70000451', 'Wilmer Morales Salazar', NULL, NULL, '910000451', 'estudiante.wilmer.morales.salazar.451@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:58', '2026-06-27 16:27:58', NULL, NULL, NULL, NULL, NULL, NULL),
(703, 'DNI', '70000452', 'Marco Navarro Mejía', NULL, NULL, '910000452', 'estudiante.marco.navarro.mejia.452@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:58', '2026-06-27 16:27:58', NULL, NULL, NULL, NULL, NULL, NULL),
(704, 'DNI', '70000453', 'Diana Mendoza Rojas', NULL, NULL, '910000453', 'docente.diana.mendoza.rojas.453@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:58', '2026-06-27 16:27:58', NULL, NULL, NULL, NULL, NULL, NULL),
(705, 'DNI', '70000454', 'Pablo Valdez Cruz', NULL, NULL, '910000454', 'estudiante.pablo.valdez.cruz.454@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:59', '2026-06-27 16:27:59', NULL, NULL, NULL, NULL, NULL, NULL),
(706, 'DNI', '70000455', 'Carlos Reyes Calderón', NULL, NULL, '910000455', 'docente.carlos.reyes.calderon.455@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:59', '2026-06-27 16:27:59', NULL, NULL, NULL, NULL, NULL, NULL),
(707, 'DNI', '70000456', 'Ricardo Arias Vargas', NULL, NULL, '910000456', 'docente.ricardo.arias.vargas.456@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:59', '2026-06-27 16:27:59', NULL, NULL, NULL, NULL, NULL, NULL),
(708, 'DNI', '70000458', 'Eduardo Salazar Arias', NULL, NULL, '910000458', 'docente.eduardo.salazar.arias.458@demo.com', NULL, NULL, NULL, '2026-06-27 16:27:59', '2026-06-27 16:27:59', NULL, NULL, NULL, NULL, NULL, NULL),
(709, 'DNI', '70000459', 'Gabriela Peña Flores', NULL, NULL, '910000459', 'docente.gabriela.pena.flores.459@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:00', '2026-06-27 16:28:00', NULL, NULL, NULL, NULL, NULL, NULL),
(710, 'DNI', '70000460', 'Joel Rojas Cáceres', NULL, NULL, '910000460', 'docente.joel.rojas.caceres.460@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:00', '2026-06-27 16:28:00', NULL, NULL, NULL, NULL, NULL, NULL),
(711, 'DNI', '70000461', 'Marco León Aguilar', NULL, NULL, '910000461', 'estudiante.marco.leon.aguilar.461@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:00', '2026-06-27 16:28:00', NULL, NULL, NULL, NULL, NULL, NULL),
(712, 'DNI', '70000463', 'Manuel Paredes García', NULL, NULL, '910000463', 'estudiante.manuel.paredes.garcia.463@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:00', '2026-06-27 16:28:00', NULL, NULL, NULL, NULL, NULL, NULL),
(713, 'DNI', '70000464', 'Joel Cáceres Díaz', NULL, NULL, '910000464', 'docente.joel.caceres.diaz.464@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:01', '2026-06-27 16:28:01', NULL, NULL, NULL, NULL, NULL, NULL),
(714, 'DNI', '70000465', 'Lucía Morales Arias', NULL, NULL, '910000465', 'docente.lucia.morales.arias.465@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:01', '2026-06-27 16:28:01', NULL, NULL, NULL, NULL, NULL, NULL),
(715, 'DNI', '70000467', 'Valeria Cáceres Cruz', NULL, NULL, '910000467', 'docente.valeria.caceres.cruz.467@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:01', '2026-06-27 16:28:01', NULL, NULL, NULL, NULL, NULL, NULL),
(716, 'DNI', '70000469', 'Hugo Castillo Reyes', NULL, NULL, '910000469', 'docente.hugo.castillo.reyes.469@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:02', '2026-06-27 16:28:02', NULL, NULL, NULL, NULL, NULL, NULL),
(717, 'DNI', '70000470', 'Pablo Paredes Cruz', NULL, NULL, '910000470', 'docente.pablo.paredes.cruz.470@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:02', '2026-06-27 16:28:02', NULL, NULL, NULL, NULL, NULL, NULL),
(718, 'DNI', '70000472', 'Ana Ortega Arias', NULL, NULL, '910000472', 'docente.ana.ortega.arias.472@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:02', '2026-06-27 16:28:02', NULL, NULL, NULL, NULL, NULL, NULL),
(719, 'DNI', '70000473', 'Alonso Flores Torres', NULL, NULL, '910000473', 'docente.alonso.flores.torres.473@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:03', '2026-06-27 16:28:03', NULL, NULL, NULL, NULL, NULL, NULL),
(720, 'DNI', '70000475', 'Andrés Díaz Silva', NULL, NULL, '910000475', 'docente.andres.diaz.silva.475@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:03', '2026-06-27 16:28:03', NULL, NULL, NULL, NULL, NULL, NULL),
(721, 'DNI', '70000476', 'Gabriela Salazar Flores', NULL, NULL, '910000476', 'docente.gabriela.salazar.flores.476@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:03', '2026-06-27 16:28:03', NULL, NULL, NULL, NULL, NULL, NULL),
(722, 'DNI', '70000477', 'Renato Cruz Navarro', NULL, NULL, '910000477', 'docente.renato.cruz.navarro.477@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:03', '2026-06-27 16:28:03', NULL, NULL, NULL, NULL, NULL, NULL),
(723, 'DNI', '70000479', 'Ana Peña Calderón', NULL, NULL, '910000479', 'docente.ana.pena.calderon.479@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:04', '2026-06-27 16:28:04', NULL, NULL, NULL, NULL, NULL, NULL),
(724, 'DNI', '70000482', 'Ana Torres Herrera', NULL, NULL, '910000482', 'docente.ana.torres.herrera.482@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:05', '2026-06-27 16:28:05', NULL, NULL, NULL, NULL, NULL, NULL),
(725, 'DNI', '70000484', 'Valeria Torres Torres', NULL, NULL, '910000484', 'docente.valeria.torres.torres.484@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:05', '2026-06-27 16:28:05', NULL, NULL, NULL, NULL, NULL, NULL),
(726, 'DNI', '70000486', 'Fiorella Navarro Condori', NULL, NULL, '910000486', 'estudiante.fiorella.navarro.condori.486@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:05', '2026-06-27 16:28:05', NULL, NULL, NULL, NULL, NULL, NULL),
(727, 'DNI', '70000487', 'Bryan Huamán Flores', NULL, NULL, '910000487', 'estudiante.bryan.huaman.flores.487@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:06', '2026-06-27 16:28:06', NULL, NULL, NULL, NULL, NULL, NULL),
(728, 'DNI', '70000488', 'Ricardo Campos Valdez', NULL, NULL, '910000488', 'estudiante.ricardo.campos.valdez.488@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:06', '2026-06-27 16:28:06', NULL, NULL, NULL, NULL, NULL, NULL),
(729, 'DNI', '70000489', 'Tatiana Vásquez Valdez', NULL, NULL, '910000489', 'docente.tatiana.vasquez.valdez.489@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:06', '2026-06-27 16:28:06', NULL, NULL, NULL, NULL, NULL, NULL),
(730, 'DNI', '70000490', 'Víctor García Medina', NULL, NULL, '910000490', 'estudiante.victor.garcia.medina.490@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:06', '2026-06-27 16:28:06', NULL, NULL, NULL, NULL, NULL, NULL),
(731, 'DNI', '70000492', 'Erick Torres Sánchez', NULL, NULL, '910000492', 'estudiante.erick.torres.sanchez.492@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:07', '2026-06-27 16:28:07', NULL, NULL, NULL, NULL, NULL, NULL),
(732, 'DNI', '70000494', 'Noelia Rojas Vargas', NULL, NULL, '910000494', 'docente.noelia.rojas.vargas.494@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:07', '2026-06-27 16:28:07', NULL, NULL, NULL, NULL, NULL, NULL),
(733, 'DNI', '70000496', 'Juan Valdez Mendoza', NULL, NULL, '910000496', 'docente.juan.valdez.mendoza.496@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:08', '2026-06-27 16:28:08', NULL, NULL, NULL, NULL, NULL, NULL),
(734, 'DNI', '70000497', 'Joel Flores Castillo', NULL, NULL, '910000497', 'docente.joel.flores.castillo.497@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:08', '2026-06-27 16:28:08', NULL, NULL, NULL, NULL, NULL, NULL),
(735, 'DNI', '70000499', 'Silvia Cáceres Rojas', NULL, NULL, '910000499', 'estudiante.silvia.caceres.rojas.499@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:08', '2026-06-27 16:28:08', NULL, NULL, NULL, NULL, NULL, NULL),
(736, 'DNI', '70000500', 'Gabriela Gutiérrez Arias', NULL, NULL, '910000500', 'estudiante.gabriela.gutierrez.arias.500@demo.com', NULL, NULL, NULL, '2026-06-27 16:28:08', '2026-06-27 16:28:08', NULL, NULL, NULL, NULL, NULL, NULL),
(737, 'DNI', '77966489', 'Alex Lopez', NULL, NULL, '94378829', 'springrandalf@gmail.com', NULL, NULL, NULL, '2026-06-27 20:52:44', '2026-06-27 20:52:44', NULL, NULL, NULL, NULL, NULL, NULL),
(738, 'DNI', '00000000', 'Administrador Sistema', NULL, NULL, '999999999', 'springrandalf@gmail.com', NULL, NULL, NULL, '2026-06-29 15:49:53', '2026-06-29 15:49:53', NULL, NULL, NULL, NULL, NULL, NULL),
(739, 'DNI', '99999999', 'Administrador Andheuris', NULL, NULL, '999999999', 'admin@andheuris.com', NULL, NULL, NULL, '2026-07-01 10:36:13', '2026-07-01 10:36:13', NULL, NULL, NULL, NULL, NULL, NULL),
(740, 'DNI', '12345678', 'Alex Lopez', NULL, NULL, '94378829', 'anonlazarus0@gmail.com', NULL, NULL, NULL, '2026-07-03 17:11:16', '2026-07-03 17:31:44', NULL, 'ssd', '[]', '[]', '[{\"institution\":\"dcsd\",\"degree\":\"sdds\",\"year_start\":\"dscsd\",\"year_end\":\"dscsd\"}]', '[{\"company\":\"dsd\",\"role\":\"sds\",\"year_start\":\"sdsd\",\"year_end\":\"dssd\",\"description\":\"sdsd\"}]'),
(741, 'DNI', '12345678', 'Alex López Salinas', 'Ing. Sistemas', 1, '94378829', 'springrandalf@gmail.com', 'M', '2002-05-15', 'Español', '2026-07-07 20:10:19', '2026-07-15 14:38:36', NULL, 'Me gusta trabar en equipo', '[]', '[]', '[]', '[]'),
(742, 'DNI', '12345678', 'Ana vera', NULL, NULL, '94378009', 'vera26@gmail.com', NULL, NULL, NULL, '2026-07-07 21:22:27', '2026-07-07 21:22:27', NULL, NULL, NULL, NULL, NULL, NULL),
(743, 'DNI', '63432068', 'LLERLINSON BAUTISTA LIMA', NULL, NULL, '992521774', 'bllerlinson@outlook.es', NULL, NULL, NULL, '2026-07-15 16:21:46', '2026-07-15 16:21:46', NULL, NULL, NULL, NULL, NULL, NULL),
(744, 'DNI', '63127958', 'YELSTIN BORIS CAHUAZA CRUZ', NULL, NULL, '978830153', 'cahuazacruzy@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:47', '2026-07-15 16:21:47', NULL, NULL, NULL, NULL, NULL, NULL),
(745, 'DNI', '63363408', 'YERI FILOMENA CARRANZA MERA', NULL, NULL, '999646506', 'carranzamerayevifilomena@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:47', '2026-07-15 16:21:47', NULL, NULL, NULL, NULL, NULL, NULL),
(746, 'DNI', '49070161', 'AMADEO CAUPER GARCIA', NULL, NULL, '975374838', 'carupergarciaamadeo161@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:47', '2026-07-15 16:21:47', NULL, NULL, NULL, NULL, NULL, NULL),
(747, 'DNI', '74567991', 'JULIO DELMER CHAVEZ FACHIN', NULL, NULL, '984848182', 'juliodelmerchavezfachin612@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:47', '2026-07-15 16:21:47', NULL, NULL, NULL, NULL, NULL, NULL),
(748, 'DNI', '73788702', 'LAYNES EDWIN CHAVEZ RIOS', NULL, NULL, '961541877', 'laynesedwinc@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:48', '2026-07-15 16:21:48', NULL, NULL, NULL, NULL, NULL, NULL),
(749, 'DNI', '63406209', 'WESNER FIDEL CRUZ AMARINGO', NULL, NULL, '961541877', 'wesnerfidelamaringo@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:48', '2026-07-15 16:21:48', NULL, NULL, NULL, NULL, NULL, NULL),
(750, 'DNI', '61836039', 'JULIA ESTRELLA GOMEZ GOMEZ', NULL, NULL, '942592487', 'barbarangomezalexeliel@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:48', '2026-07-15 16:21:48', NULL, NULL, NULL, NULL, NULL, NULL),
(751, 'DNI', '61836008', 'ERMILIO HOYOS MORI', NULL, NULL, '983621324', 'ermiliohoyos416@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:48', '2026-07-15 16:21:48', NULL, NULL, NULL, NULL, NULL, NULL),
(752, 'DNI', '47852038', 'ROGER INDALICIO PANDURO', NULL, NULL, '950583515', 'indalicioroger2023@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:48', '2026-07-15 16:21:48', NULL, NULL, NULL, NULL, NULL, NULL),
(753, 'DNI', '77234580', 'JASMITH INUMA CAUPER', NULL, NULL, '948981795', 'jasmith580@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:49', '2026-07-15 16:21:49', NULL, NULL, NULL, NULL, NULL, NULL),
(754, 'DNI', '73788932', 'JHOSER ROLIN LEONCIO MUÑOZ', NULL, NULL, '978372201', 'jhoserrolin932@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:49', '2026-07-15 16:21:49', NULL, NULL, NULL, NULL, NULL, NULL),
(755, 'DNI', '74240719', 'EFRAIN LOMAS MALDONADO', NULL, NULL, '954929898', 'efrainlomasmaldonado4@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:49', '2026-07-15 16:21:49', NULL, NULL, NULL, NULL, NULL, NULL),
(756, 'DNI', '62293644', 'KEN MICHEL MALDONADO RODRIGUEZ', NULL, NULL, '961541877', 'kenmichelmaldonado@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:49', '2026-07-15 16:21:49', NULL, NULL, NULL, NULL, NULL, NULL),
(757, 'DNI', '63090889', 'GLENDY MAYDAD MANUNGO LOZANO', NULL, NULL, '969459272', 'glendymanungo20@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:50', '2026-07-15 16:21:50', NULL, NULL, NULL, NULL, NULL, NULL),
(758, 'DNI', '60109812', 'EVIN ADY NUNTA BARBARAN', NULL, NULL, '961655649', 'evinadynuntabarbaran@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:50', '2026-07-15 16:21:50', NULL, NULL, NULL, NULL, NULL, NULL),
(759, 'DNI', '73788729', 'BETTY MARIBEL NUNTA INGA', NULL, NULL, '920795933', 'bettynunta59@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:50', '2026-07-15 16:21:50', NULL, NULL, NULL, NULL, NULL, NULL),
(760, 'DNI', '73788653', 'GLENDY MIRELLA NUNTA ROJAS', NULL, NULL, '938999507', 'minuntarojas@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:50', '2026-07-15 16:21:50', NULL, NULL, NULL, NULL, NULL, NULL),
(761, 'DNI', '60110680', 'DEIVIS ELIOENAI NUNTA YUI', NULL, NULL, '966447642', 'deivisnuntayui@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:50', '2026-07-15 16:21:50', NULL, NULL, NULL, NULL, NULL, NULL),
(762, 'DNI', '76444277', 'JULIAN OCHAVANO CAHUAZA', NULL, NULL, '961541877', 'ochavanocahuaza4277@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:51', '2026-07-15 16:21:51', NULL, NULL, NULL, NULL, NULL, NULL),
(763, 'DNI', '76687326', 'ALER LAADAN PACAYA CLEMENTE', NULL, NULL, '982693558', 'alerpacaya5@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:51', '2026-07-15 16:21:51', NULL, NULL, NULL, NULL, NULL, NULL),
(764, 'DNI', '60479906', 'NOEMI VANESSA PALOMINO CARDENAS', NULL, NULL, '984186014', 'noemivanessapalominocardenas5@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:51', '2026-07-15 16:21:51', NULL, NULL, NULL, NULL, NULL, NULL),
(765, 'DNI', '73788840', 'RONALDO PINEDO BARBARAN', NULL, NULL, '961541877', 'ronaldopinedo8840@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:51', '2026-07-15 16:21:51', NULL, NULL, NULL, NULL, NULL, NULL),
(766, 'DNI', '76931355', 'WENDER PIZANGO MALDONADO', NULL, NULL, '954929898', 'wenderpizango10@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:51', '2026-07-15 16:21:51', NULL, NULL, NULL, NULL, NULL, NULL),
(767, 'DNI', '76931296', 'ALEX ADRIAN PUGA DIAZ', NULL, NULL, '961655649', 'alexadrianpuga2025@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:52', '2026-07-15 16:21:52', NULL, NULL, NULL, NULL, NULL, NULL),
(768, 'DNI', '76931536', 'ROLY RAMIREZ SANANCINO', NULL, NULL, '968902418', 'ramirezsanancinoroly@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:52', '2026-07-15 16:21:52', NULL, NULL, NULL, NULL, NULL, NULL),
(769, 'DNI', '61835928', 'JORGE LUIS RENGIFO LOPEZ', NULL, NULL, '958701000', 'jorgeluisrengifolopez9@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:52', '2026-07-15 16:21:52', NULL, NULL, NULL, NULL, NULL, NULL),
(770, 'DNI', '61603988', 'JACKES XANDER RIOS CHAVEZ', NULL, NULL, '950471750', 'jackesxanderrioschavez@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:52', '2026-07-15 16:21:52', NULL, NULL, NULL, NULL, NULL, NULL),
(771, 'DNI', '63406292', 'LELIA FRAELY RIOS INDALICIO', NULL, NULL, '962693962', 'riosindalicioleliafraely@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:53', '2026-07-15 16:21:53', NULL, NULL, NULL, NULL, NULL, NULL),
(772, 'DNI', '73832727', 'FIORY JHULITZA RIOS JACINTO', NULL, NULL, '958141968', 'riosjacintofioryjhulitza@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:53', '2026-07-15 16:21:53', NULL, NULL, NULL, NULL, NULL, NULL),
(773, 'DNI', '46730832', 'NEYRA MARLENE RIOS MUÑOZ', NULL, NULL, '961541877', 'rneyramarlene@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:53', '2026-07-15 16:21:53', NULL, NULL, NULL, NULL, NULL, NULL),
(774, 'DNI', '63273626', 'JERSON RODRIGUEZ CRUZ', NULL, NULL, '920991835', 'jersonrodriguezcruz15@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:53', '2026-07-15 16:21:53', NULL, NULL, NULL, NULL, NULL, NULL),
(775, 'DNI', '60109840', 'GLENI LUCILA RODRIGUEZ FAUSTINO', NULL, NULL, '961655649', 'rodriguezgleni07@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:53', '2026-07-15 16:21:53', NULL, NULL, NULL, NULL, NULL, NULL),
(776, 'DNI', '76692238', 'AMMI MARIZA SAAVEDRA MOZOMBITE', NULL, NULL, '938360183', 'ammisaavedra@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:54', '2026-07-15 16:21:54', NULL, NULL, NULL, NULL, NULL, NULL),
(777, 'DNI', '44093188', 'CHARLES FRANK SAAVEDRA TAMANI', NULL, NULL, '956972774', 'saavedratamanicharlesfrank@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:54', '2026-07-15 16:21:54', NULL, NULL, NULL, NULL, NULL, NULL),
(778, 'DNI', '63275395', 'NOLBERTO SANANCINO ANICETO', NULL, NULL, '979412614', 'nolbertosanancinoaniceto@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:54', '2026-07-15 16:21:54', NULL, NULL, NULL, NULL, NULL, NULL),
(779, 'DNI', '73788680', 'MAX JHORDY SANCHEZ FAUSTINO', NULL, NULL, '959029273', 'maxjhordy680@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:54', '2026-07-15 16:21:54', NULL, NULL, NULL, NULL, NULL, NULL),
(780, 'DNI', '77469156', 'FRANK JUNIOR SANCHEZ INGA', NULL, NULL, '961655649', 'sanchezingafrankjunior@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:54', '2026-07-15 16:21:54', NULL, NULL, NULL, NULL, NULL, NULL),
(781, 'DNI', '61609114', 'ALBER SANCHEZ MORI', NULL, NULL, '982009650', 'sanchezmorialber@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:55', '2026-07-15 16:21:55', NULL, NULL, NULL, NULL, NULL, NULL),
(782, 'DNI', '48351034', 'ELIO MANASES SANTOS ESCOBAR', NULL, NULL, '961655649', 'santoselio184@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:55', '2026-07-15 16:21:55', NULL, NULL, NULL, NULL, NULL, NULL),
(783, 'DNI', '62109024', 'YARITZA TANGOA GARCIA', NULL, NULL, '944640782', 'tangoagarciayaritza@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:55', '2026-07-15 16:21:55', NULL, NULL, NULL, NULL, NULL, NULL),
(784, 'DNI', '77416324', 'NYDIA ENITH URQUIA LOPEZ', NULL, NULL, '942611920', 'urquialopezn@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:55', '2026-07-15 16:21:55', NULL, NULL, NULL, NULL, NULL, NULL),
(785, 'DNI', '63432092', 'NEHEMIAS URQUIA SANCHEZ', NULL, NULL, '948141623', 'urquiasancheznehemias51@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:56', '2026-07-15 16:21:56', NULL, NULL, NULL, NULL, NULL, NULL),
(786, 'DNI', '77469023', 'JACKS GABRIEL URQUIA VALLES', NULL, NULL, '932358047', 'jacksgabrielurquiavalles17@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:56', '2026-07-15 16:21:56', NULL, NULL, NULL, NULL, NULL, NULL),
(787, 'DNI', '73788755', 'ANGELA MARIA VALERA MALDONADO', NULL, NULL, '948283086', 'angelavaleramaldonado@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:56', '2026-07-15 16:21:56', NULL, NULL, NULL, NULL, NULL, NULL),
(788, 'DNI', '60340995', 'RILQUE VALLES SANCHEZ', NULL, NULL, '961655649', 'rilquevallessanchez@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:56', '2026-07-15 16:21:56', NULL, NULL, NULL, NULL, NULL, NULL),
(789, 'DNI', '74568467', 'RUSBER VALLES SANCHEZ', NULL, NULL, '961655649', 'rusbeevallles@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:56', '2026-07-15 16:21:56', NULL, NULL, NULL, NULL, NULL, NULL),
(790, 'DNI', '62936362', 'YALESKA JOVITA VASQUEZ TAMANI', NULL, NULL, '938118827', 'vasqueztamaniyaleskajovita@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:57', '2026-07-15 16:21:57', NULL, NULL, NULL, NULL, NULL, NULL),
(791, 'DNI', '76923701', 'JUAN ANTONIO VASQUEZ ZUMAETA', NULL, NULL, '918025821', 'antonyvasquezzumaeta81@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:57', '2026-07-15 16:21:57', NULL, NULL, NULL, NULL, NULL, NULL),
(792, 'DNI', '60110298', 'AMNER HITLER VENANCINO HUANUIRI', NULL, NULL, '939267020', 'venancinohuanuiriamner@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:57', '2026-07-15 16:21:57', NULL, NULL, NULL, NULL, NULL, NULL),
(793, 'DNI', '73788965', 'GRISELDO ZUMAETA AMPUERO', NULL, NULL, '951979095', 'griseldoampuero2025@outlook.com', NULL, NULL, NULL, '2026-07-15 16:21:57', '2026-07-15 16:21:57', NULL, NULL, NULL, NULL, NULL, NULL),
(794, 'DNI', '73788964', 'JATNIEL ZUMAETA AMPUERO', NULL, NULL, '950491750', 'jatnielzumaeta0510@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:57', '2026-07-15 16:21:57', NULL, NULL, NULL, NULL, NULL, NULL),
(795, 'DNI', '73788979', 'MELODY ESTEFANE ZUMAETA AMPUERO', NULL, NULL, '985095544', 'melodyestefane12@gmail.com', NULL, NULL, NULL, '2026-07-15 16:21:58', '2026-07-15 16:21:58', NULL, NULL, NULL, NULL, NULL, NULL);

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
(1634, 1, 1634),
(1637, 3, 1637),
(1638, 2, 1638),
(1642, 2, 1642),
(1643, 2, 1643),
(1644, 2, 1644),
(1645, 2, 1645),
(1646, 2, 1646),
(1647, 3, 1647),
(1648, 3, 1648),
(1649, 3, 1649),
(1650, 3, 1650),
(1651, 3, 1651),
(1652, 4, 1652);

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
-- Estructura de tabla para la tabla `study_programs`
--

CREATE TABLE `study_programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `study_programs`
--

INSERT INTO `study_programs` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Producción Agropecuaria', 1, '2026-07-15 19:20:24', '2026-07-15 19:20:24'),
(2, 'Construcción Civil', 1, '2026-08-22 02:44:57', '2026-08-22 02:44:57');

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
(1, 'application_name', 'Nombre de la institución', 'string', 'IESTP Sangarará', '2025-06-03 09:58:28', '2026-08-19 17:25:43', NULL),
(2, 'support_emails', 'Correos de soporte', 'array', NULL, '2025-06-03 09:58:28', '2025-06-03 09:58:28', NULL),
(3, 'logo', 'Logo', 'string', '/uploads/logo_1787160343.png', '2025-06-03 09:58:28', '2026-08-19 17:25:43', NULL),
(4, 'favicon', 'Favicon', 'string', '/uploads/favicon_1787160343.png', '2025-06-03 09:58:28', '2026-08-19 17:25:43', NULL),
(5, 'banner', 'Banner', 'string', '/uploads/banner_1787160343.jpg', '2025-06-03 09:58:28', '2026-08-19 17:25:43', NULL),
(6, 'maximum_file_size_to_upload', 'Tamaño máximo de archivos a subir (MB)', 'number', '10', '2025-06-03 09:58:28', '2026-08-19 17:25:43', NULL),
(7, 'extensions_allowed_to_upload', 'Extensiones permitidas para subir archivos', 'array', '[{\"extension\":\"pdf\",\"permitted\":true},{\"extension\":\"doc\",\"permitted\":true},{\"extension\":\"docx\",\"permitted\":true},{\"extension\":\"xls\",\"permitted\":true},{\"extension\":\"xlsx\",\"permitted\":true},{\"extension\":\"ppt\",\"permitted\":true},{\"extension\":\"pptx\",\"permitted\":true},{\"extension\":\"zip\",\"permitted\":true},{\"extension\":\"rar\",\"permitted\":true},{\"extension\":\"jpg\",\"permitted\":true},{\"extension\":\"jpeg\",\"permitted\":true},{\"extension\":\"png\",\"permitted\":true},{\"extension\":\"gif\",\"permitted\":true},{\"extension\":\"mp3\",\"permitted\":true},{\"extension\":\"mp4\",\"permitted\":true},{\"extension\":\"avi\",\"permitted\":true},{\"extension\":\"mkv\",\"permitted\":true}]', '2025-06-03 09:58:28', '2026-08-19 17:25:43', NULL),
(8, 'primary_color', 'Color Primario', 'string', '#dc2626', '2025-06-03 09:58:28', '2026-08-19 17:25:43', NULL),
(9, 'primary_container_color', 'Color principal suavizado', 'color', '#f5c2c2', '2026-06-27 21:14:41', '2026-08-19 17:25:43', NULL),
(10, 'secondary_color', 'Color secundario', 'color', '#475569', '2026-06-27 21:14:41', '2026-08-19 17:25:43', NULL),
(11, 'secondary_container_color', 'Color secundario suavizado', 'color', '#cbcfd5', '2026-06-27 21:14:41', '2026-08-19 17:25:43', NULL),
(12, 'accent_color', 'Color de acento', 'color', '#f59e0b', '2026-06-27 21:14:41', '2026-08-19 17:25:43', NULL),
(13, 'theme_mode', 'Modo visual', 'select', 'light', '2026-06-27 21:14:41', '2026-08-19 17:25:43', NULL),
(14, 'interface_density', 'Densidad de interfaz', 'select', 'compact', '2026-06-27 21:14:41', '2026-08-19 17:25:43', NULL),
(15, 'sidebar_style', 'Estilo del sidebar', 'select', 'expanded', '2026-06-27 21:14:41', '2026-08-19 17:25:43', NULL);

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
(1634, NULL, 739, 1, 'admin@andheuris.com', '$2y$12$lMOsBpJai4fLSMuGfOlXCeDUqiLTI0vlR6Tn06h7cBRF2rUyuxBie', NULL, NULL, 1, NULL, 'profile-photos/uWI70rQvGmwarJhPUX3eCOwXclJtILiBcjp1wLDl.png', 0, NULL, '2026-07-01 10:36:13', '2026-07-08 22:26:14', NULL),
(1637, NULL, 741, 3, 'springrandalf@gmail.com', '$2y$12$ZnMPaMzdwg4yd.HN7BLWZe27dGo17S2ws37EHPCJrBWHkMCGf/6rG', NULL, NULL, 1, NULL, '/uploads/avatars/avatar_1637_1783456078.png', 0, NULL, '2026-07-07 20:10:19', '2026-07-07 20:27:58', NULL),
(1638, NULL, 742, 2, 'vera26@gmail.com', '$2y$12$Xy/qKX0JyLuZX9VDLI29zuE90CWEGg5YAWtUdQpPokFqphxd4r4y2', NULL, NULL, 1, NULL, NULL, 0, NULL, '2026-07-07 21:22:27', '2026-07-07 21:22:27', NULL),
(1642, NULL, 23, 2, 'ana.perez@demo.edu.pe', '$2y$12$gU2TtB72Wh8qANpXkMk/WejVJuYlBKm0mfdfF7bpX.x8dn1h.FHYa', NULL, NULL, 1, NULL, NULL, 0, NULL, '2026-07-15 14:27:55', '2026-07-15 14:27:55', NULL),
(1643, NULL, 24, 2, 'carlos.rojas@demo.edu.pe', '$2y$12$eIh6bbgbkc9yLwYEy7Z8AO7kfIiv4QOA4OvY5Oa8U.doA5pVoxT96', NULL, NULL, 1, NULL, NULL, 0, NULL, '2026-07-15 14:27:55', '2026-07-15 14:27:55', NULL),
(1644, NULL, 25, 2, 'maria.torres@demo.edu.pe', '$2y$12$e4AncnFUlWlO.90G5ZllzOoyZc3UE9RClHRtmD6RF2MgfqwLeaSYu', NULL, NULL, 1, NULL, NULL, 0, NULL, '2026-07-15 14:27:55', '2026-07-15 14:27:55', NULL),
(1645, NULL, 26, 2, 'luis.quispe@demo.edu.pe', '$2y$12$DKJjpVVFp9ZQF/jeE787fO6kCDScPJ3xnjbaveXJRWxH9WOBYBSl2', NULL, NULL, 1, NULL, NULL, 0, NULL, '2026-07-15 14:27:55', '2026-07-15 14:27:55', NULL),
(1646, NULL, 27, 2, 'rosa.huaman@demo.edu.pe', '$2y$12$UlsVKwTF674l9cq7LA1i/ujFDi6BFfgTuCiJk8mCinFyDQewIn4VK', NULL, NULL, 1, NULL, NULL, 0, NULL, '2026-07-15 14:27:55', '2026-07-15 14:27:55', NULL),
(1647, NULL, 28, 3, 'juan.mendoza@demo.edu.pe', '$2y$12$PLDk1pZweWp0y6N85e7PveZYh76dmffVpFEfVucOp6xxrriKzOEwK', NULL, NULL, 1, NULL, NULL, 0, NULL, '2026-07-15 14:27:55', '2026-07-15 14:27:55', NULL),
(1648, NULL, 29, 3, 'valeria.chavez@demo.edu.pe', '$2y$12$pvwT4yDrHwwA6p.XPHKeHOzCDIboq5wmHOFcvgDow7z1Pq/ami9pa', NULL, NULL, 1, NULL, NULL, 0, NULL, '2026-07-15 14:27:55', '2026-07-15 14:27:55', NULL),
(1649, NULL, 30, 3, 'pedro.garcia@demo.edu.pe', '$2y$12$fZC69CSgzdO0YWX15x2mg.9Mm/xbIOFa8SVv25tupx7w7dH6MXHQ2', NULL, NULL, 1, NULL, NULL, 0, NULL, '2026-07-15 14:27:55', '2026-07-15 14:27:55', NULL),
(1650, NULL, 31, 3, 'camila.soto@demo.edu.pe', '$2y$12$q6gLHxgD0.dU9LxoH2/GG.CWBRsiJ3CnZlHESs7DhJwkLGabyPf6.', NULL, NULL, 1, NULL, NULL, 0, NULL, '2026-07-15 14:27:55', '2026-07-15 14:27:55', NULL),
(1651, NULL, 32, 3, 'diego.paredes@demo.edu.pe', '$2y$12$EPDHi1u81TUEcv52QkCktOTbz9qVQV00olBSB4M52UOV3q1X.eUUy', NULL, NULL, 1, NULL, NULL, 0, NULL, '2026-07-15 14:27:55', '2026-07-15 14:27:55', NULL),
(1652, 601, NULL, 4, 'anonlazarus0@gmail.com', '$2y$12$nOr0jiInN3WgIPASFYbaJ.SMSYGpff8yRmGd3SpKIOkkyYZgd./gG', NULL, NULL, 1, NULL, NULL, 0, NULL, '2026-07-15 14:57:06', '2026-07-15 14:57:06', NULL),
(1706, NULL, NULL, 1, 'teststrength@temp.local', '$2y$12$LGu0or6Q6w0RugIDYw4MP.Ds4edST.efVKL4Gz3N9pRFPYVc/OHe6', NULL, NULL, 1, NULL, NULL, 0, NULL, '2026-08-07 21:19:53', '2026-08-07 21:19:53', NULL);

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
(5, 1634, 'Nueva oferta laboral', 'La empresa alicorp sac ha publicado la oferta: sdfsdf', '/admin/dashboard?tab=offers', '2026-07-08 22:45:59', '2026-07-03 22:37:35', '2026-07-08 22:45:59'),
(8, 1634, 'Nueva postulación', 'El estudiante Alex Lopez ha postulado a la oferta: sdfsdf', '/admin/dashboard?tab=applications', '2026-07-08 22:45:59', '2026-07-03 22:41:01', '2026-07-08 22:45:59'),
(9, 1634, 'Nueva oferta laboral', 'La empresa Innova schools ha publicado la oferta: sssfs', '/admin/dashboard?tab=offers', '2026-07-08 22:45:59', '2026-07-08 03:01:28', '2026-07-08 22:45:59'),
(11, 1634, 'Nueva postulación', 'El estudiante Alex López Salinas ha postulado a la oferta: sssfs', '/admin/dashboard?tab=applications', '2026-07-08 22:45:59', '2026-07-08 19:39:51', '2026-07-08 22:45:59'),
(12, 1634, 'Nueva oferta laboral', 'La empresa Innova schools ha publicado la oferta: dsfsdf', '/admin/dashboard?tab=offers', '2026-07-08 22:45:59', '2026-07-08 19:43:05', '2026-07-08 22:45:59'),
(14, 1634, 'Nueva postulación', 'El estudiante Alex López Salinas ha postulado a la oferta: dsfsdf', '/admin/dashboard?tab=applications', '2026-07-08 22:45:59', '2026-07-08 19:46:14', '2026-07-08 22:45:59'),
(15, 1637, 'Estado de postulación actualizado', 'Tu postulación ha sido aceptada por la empresa.', '/student/dashboard?tab=applications', '2026-07-09 03:18:33', '2026-07-08 20:22:07', '2026-07-09 03:18:33'),
(16, 1634, 'Nueva oferta laboral', 'La empresa Innova schools ha publicado la oferta: adasds', '/admin/dashboard?tab=offers', '2026-07-11 19:06:19', '2026-07-09 02:58:14', '2026-07-11 19:06:19'),
(18, 1634, 'Nueva postulación', 'El estudiante Alex López Salinas ha postulado a la oferta: adasds', '/admin/dashboard?tab=applications', '2026-07-11 19:06:19', '2026-07-09 21:22:57', '2026-07-11 19:06:19'),
(19, 1634, 'Nueva oferta laboral', 'La empresa LOPEZ SAC ha publicado la oferta: Full-stack JAVA', '/admin/dashboard?tab=offers', NULL, '2026-07-15 18:46:20', '2026-07-15 18:46:20'),
(21, 1634, 'Nueva postulación', 'El estudiante Alex López Salinas ha postulado a la oferta: Full-stack JAVA', '/admin/dashboard?tab=applications', NULL, '2026-07-15 18:46:51', '2026-07-15 18:46:51'),
(22, 1634, 'Nueva oferta laboral', 'La empresa LOPEZ SAC ha publicado la oferta: Full-stack JAVA', '/admin/dashboard?tab=offers', NULL, '2026-07-15 20:03:05', '2026-07-15 20:03:05'),
(23, 1652, 'Nueva postulación', 'El estudiante Alex López Salinas ha postulado a tu oferta: Full-stack JAVA', '/company/dashboard?tab=applicants', NULL, '2026-07-15 20:12:05', '2026-07-15 20:12:05'),
(24, 1634, 'Nueva postulación', 'El estudiante Alex López Salinas ha postulado a la oferta: Full-stack JAVA', '/admin/dashboard?tab=applications', NULL, '2026-07-15 20:12:05', '2026-07-15 20:12:05'),
(25, 1637, 'Estado de postulación actualizado', 'Tu postulación ha sido en revisión por el administrador.', '/student/dashboard?tab=applications', NULL, '2026-08-21 00:56:11', '2026-08-21 00:56:11');

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
-- Indices de la tabla `job_opportunity_modalities`
--
ALTER TABLE `job_opportunity_modalities`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `job_opportunity_offer`
--
ALTER TABLE `job_opportunity_offer`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `job_opportunity_offer_slug_unique` (`slug`) USING BTREE,
  ADD KEY `job_opportunity_offer_company_id_foreign` (`company_id`) USING BTREE,
  ADD KEY `job_opportunity_offer_location_id_foreign` (`modality_id`) USING BTREE,
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
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `person_study_program_id_foreign` (`study_program_id`);

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
-- Indices de la tabla `study_programs`
--
ALTER TABLE `study_programs`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `job_opportunity_company`
--
ALTER TABLE `job_opportunity_company`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=602;

--
-- AUTO_INCREMENT de la tabla `job_opportunity_contract_types`
--
ALTER TABLE `job_opportunity_contract_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `job_opportunity_modalities`
--
ALTER TABLE `job_opportunity_modalities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `job_opportunity_offer`
--
ALTER TABLE `job_opportunity_offer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `job_opportunity_user_cv`
--
ALTER TABLE `job_opportunity_user_cv`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `option`
--
ALTER TABLE `option`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `person`
--
ALTER TABLE `person`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=796;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1706;

--
-- AUTO_INCREMENT de la tabla `study_programs`
--
ALTER TABLE `study_programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `system_configuration`
--
ALTER TABLE `system_configuration`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1707;

--
-- AUTO_INCREMENT de la tabla `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
  ADD CONSTRAINT `job_opportunity_offer_modality_id_foreign` FOREIGN KEY (`modality_id`) REFERENCES `job_opportunity_modalities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
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
-- Filtros para la tabla `person`
--
ALTER TABLE `person`
  ADD CONSTRAINT `person_study_program_id_foreign` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE SET NULL;

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
