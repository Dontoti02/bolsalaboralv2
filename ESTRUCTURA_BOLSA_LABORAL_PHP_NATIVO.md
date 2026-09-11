# 📘 ARQUITECTURA Y GUÍA DE REPLICACIÓN: BOLSA LABORAL (PHP NATIVO + MYSQL)
> **Objetivo:** Replicar íntegramente la plataforma de Bolsa Laboral v2 en **PHP Nativo (Vanilla PHP 8.x)** y **MySQL/MariaDB**, sin depender de Composer, frameworks (Laravel/Symfony) ni librerías externas de terceros.

---

## 📑 TABLA DE CONTENIDOS
1. [Visión General del Sistema y Actores](#1-visión-general-del-sistema-y-actores)
2. [Estructura de Base de Datos MySQL (DDL y Semillas)](#2-estructura-de-base-de-datos-mysql-ddl-y-semillas)
3. [Estructura de Directorios Recomendada (Patrón MVC Nativo)](#3-estructura-de-directorios-recomendada-patrón-mvc-nativo)
4. [Mapeo de Rutas, Módulos y Control de Acceso (RBAC)](#4-mapeo-de-rutas-módulos-y-control-de-acceso-rbac)
5. [Lógica de Negocio y Flujos Principales](#5-lógica-de-negocio-y-flujos-principales)
6. [Componentes del Núcleo (Core) en PHP Nativo](#6-componentes-del-núcleo-core-en-php-nativo)
   - 6.1 Conexión PDO Singleton (`Database.php`)
   - 6.2 Manejo de Sesiones y Autenticación (`Auth.php`)
   - 6.3 Protección CSRF Nativa (`Csrf.php`)
   - 6.4 Enrutador Front-Controller Simple (`Router.php`)
   - 6.5 Subida Segura de Archivos / CVs / Imágenes (`Uploader.php`)
   - 6.6 Exportación de Datos a Excel sin Dependencias (`Exporter.php`)
7. [Consideraciones Críticas de Seguridad y Producción](#7-consideraciones-críticas-de-seguridad-y-producción)

---

## 1. VISIÓN GENERAL DEL SISTEMA Y ACTORES

El sistema es una **Bolsa Laboral Universitaria / Institucional** que conecta estudiantes y egresados con empresas empleadoras, supervisado por un equipo de administradores y docentes.

### Actores del Sistema (`rol_id`):
| Rol ID | Clave (`key`) | Nombre | Acceso Principal | Responsabilidades / Funciones |
| :---: | :--- | :--- | :--- | :--- |
| **1** | `rol_admin` | **ADMINISTRADOR** | `/admin/dashboard` | Control total del sistema: gestión de usuarios, aprobación de empresas, supervisión de ofertas y postulaciones, métricas estadísticas, mantenedores globales, configuración de logotipo y textos, exportación de reportes a Excel. |
| **2** | `rol_teacher` | **DOCENTE** | `/` (Landing / Perfil) | Acceso de consulta a ofertas laborales, actualización de su perfil profesional y contraseña. |
| **3** | `rol_student` | **ESTUDIANTE / EGRESADO** | `/` (Landing) | Búsqueda y filtrado de ofertas, postulación con CVs (versionados en PDF), favoritos (ofertas guardadas), seguimiento de estado de postulaciones y retroalimentación recibida, edición de perfil profesional (habilidades, formación, experiencia). |
| **4** | `rol_company` | **EMPRESA** | `/company/dashboard` | Registro de empresa (sujeto a verificación por admin), publicación y gestión de ofertas laborales, filtrado y revisión de candidatos, descarga segura de CVs, aprobación/rechazo de postulantes con retroalimentación (feedback). |

---

## 2. ESTRUCTURA DE BASE DE DATOS MYSQL (DDL Y SEMILLAS)

Ejecuta este script SQL completo en MySQL/MariaDB (codificación recomendada: `utf8mb4_unicode_ci`):

```sql
CREATE DATABASE IF NOT EXISTS `bolsa_laboral_nativa` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bolsa_laboral_nativa`;

-- 1. Tabla de Roles
CREATE TABLE IF NOT EXISTS `rol` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `key` VARCHAR(255) NULL,
  `level` INT NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Programas de Estudio / Carreras
CREATE TABLE IF NOT EXISTS `study_programs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Personas (Datos Personales de Alumnos, Docentes y Admins)
CREATE TABLE IF NOT EXISTS `person` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_type` VARCHAR(50) NOT NULL DEFAULT 'DNI',
  `document_number` VARCHAR(20) NOT NULL,
  `names` VARCHAR(255) NOT NULL,
  `career` VARCHAR(255) NULL,
  `study_program_id` BIGINT UNSIGNED NULL,
  `phone` VARCHAR(20) NOT NULL DEFAULT '',
  `email` VARCHAR(255) NOT NULL,
  `sex` VARCHAR(10) NULL,
  `birth_date` DATE NULL,
  `native_language` VARCHAR(100) NULL,
  `about_me` TEXT NULL,
  `skills` LONGTEXT NULL,        -- Almacenado como JSON: ["PHP", "MySQL", "JavaScript"]
  `hobbies` LONGTEXT NULL,       -- Almacenado como JSON: ["Lectura", "Fútbol"]
  `education` LONGTEXT NULL,     -- Almacenado como JSON con historial educativo
  `experience` LONGTEXT NULL,    -- Almacenado como JSON con experiencia laboral
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_person_doc` (`document_number`),
  KEY `fk_person_study_program` (`study_program_id`),
  CONSTRAINT `fk_person_study_program` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Empresas Empleadoras
CREATE TABLE IF NOT EXISTS `job_opportunity_company` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `ruc` VARCHAR(11) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) NOT NULL DEFAULT '',
  `mailbox` VARCHAR(255) NOT NULL DEFAULT '',
  `is_verified` TINYINT(1) NOT NULL DEFAULT 0,
  `description` TEXT NULL,
  `website` VARCHAR(255) NULL,
  `address` VARCHAR(255) NULL,
  `logo` VARCHAR(255) NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_company_ruc` (`ruc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Usuarios del Sistema (Cuentas de Acceso)
CREATE TABLE IF NOT EXISTS `user` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` BIGINT UNSIGNED NULL,
  `person_id` BIGINT UNSIGNED NULL,
  `rol_id` BIGINT UNSIGNED NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100) NULL,
  `reset_password_token` VARCHAR(255) NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `last_login` DATETIME NULL,
  `avatar` VARCHAR(255) NULL,
  `attempts` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `last_attempt` DATETIME NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_email` (`email`),
  KEY `fk_user_rol` (`rol_id`),
  KEY `fk_user_company` (`company_id`),
  KEY `fk_user_person` (`person_id`),
  CONSTRAINT `fk_user_rol` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_user_company` FOREIGN KEY (`company_id`) REFERENCES `job_opportunity_company` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Tablas de Mantenedores / Catálogos de Ofertas
CREATE TABLE IF NOT EXISTS `job_opportunity_modalities` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_opportunity_work_schedules` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_opportunity_contract_types` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_opportunity_offer_category` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255) NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_opportunity_offer_state` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `key` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255) NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Ofertas Laborales
CREATE TABLE IF NOT EXISTS `job_opportunity_offer` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `requirements` TEXT NOT NULL,
  `benefits` TEXT NULL,
  `salary` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `salary_currency` VARCHAR(20) NOT NULL DEFAULT 'SOLES',
  `attachments` VARCHAR(255) NULL,
  `address` VARCHAR(255) NOT NULL,
  `department` VARCHAR(100) NOT NULL,
  `province` VARCHAR(100) NOT NULL,
  `country` VARCHAR(100) DEFAULT 'Perú',
  `publication_date` DATETIME NOT NULL,
  `deadline` DATETIME NULL,
  `company_id` BIGINT UNSIGNED NOT NULL,
  `modality_id` BIGINT UNSIGNED NOT NULL,
  `state_id` BIGINT UNSIGNED NOT NULL DEFAULT 2,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `work_schedule_id` BIGINT UNSIGNED NOT NULL,
  `contract_type_id` BIGINT UNSIGNED NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `fk_offer_company` (`company_id`),
  KEY `fk_offer_modality` (`modality_id`),
  KEY `fk_offer_state` (`state_id`),
  KEY `fk_offer_category` (`category_id`),
  KEY `fk_offer_schedule` (`work_schedule_id`),
  KEY `fk_offer_contract` (`contract_type_id`),
  CONSTRAINT `fk_offer_company` FOREIGN KEY (`company_id`) REFERENCES `job_opportunity_company` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_offer_modality` FOREIGN KEY (`modality_id`) REFERENCES `job_opportunity_modalities` (`id`),
  CONSTRAINT `fk_offer_state` FOREIGN KEY (`state_id`) REFERENCES `job_opportunity_offer_state` (`id`),
  CONSTRAINT `fk_offer_category` FOREIGN KEY (`category_id`) REFERENCES `job_opportunity_offer_category` (`id`),
  CONSTRAINT `fk_offer_schedule` FOREIGN KEY (`work_schedule_id`) REFERENCES `job_opportunity_work_schedules` (`id`),
  CONSTRAINT `fk_offer_contract` FOREIGN KEY (`contract_type_id`) REFERENCES `job_opportunity_contract_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Historial de Estados de Oferta
CREATE TABLE IF NOT EXISTS `job_opportunity_offer_state_detail` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `offer_id` BIGINT UNSIGNED NOT NULL,
  `state_id` BIGINT UNSIGNED NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `fk_detail_offer` (`offer_id`),
  KEY `fk_detail_state` (`state_id`),
  CONSTRAINT `fk_detail_offer` FOREIGN KEY (`offer_id`) REFERENCES `job_opportunity_offer` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_detail_state` FOREIGN KEY (`state_id`) REFERENCES `job_opportunity_offer_state` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Currículums de Estudiantes (PDFs con versionamiento)
CREATE TABLE IF NOT EXISTS `job_opportunity_user_cv` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `version` INT NOT NULL DEFAULT 1,
  `url` VARCHAR(255) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cv_user` (`user_id`),
  CONSTRAINT `fk_cv_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Postulaciones a Ofertas
CREATE TABLE IF NOT EXISTS `job_opportunity_applications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `offer_id` BIGINT UNSIGNED NOT NULL,
  `fullname` VARCHAR(255) NOT NULL,
  `program_study` VARCHAR(255) NULL,
  `message` TEXT NULL,
  `status` ENUM('postulated', 'under_review', 'accepted', 'rejected') NOT NULL DEFAULT 'postulated',
  `cv` VARCHAR(255) NULL,
  `feedback` TEXT NULL,
  `feedback_date` DATETIME NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `fk_app_user` (`user_id`),
  KEY `fk_app_offer` (`offer_id`),
  CONSTRAINT `fk_app_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_app_offer` FOREIGN KEY (`offer_id`) REFERENCES `job_opportunity_offer` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Ofertas Guardadas / Favoritos del Estudiante
CREATE TABLE IF NOT EXISTS `saved_offers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `offer_id` BIGINT UNSIGNED NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_saved_offer` (`user_id`, `offer_id`),
  KEY `fk_saved_user` (`user_id`),
  KEY `fk_saved_offer` (`offer_id`),
  CONSTRAINT `fk_saved_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_saved_offer` FOREIGN KEY (`offer_id`) REFERENCES `job_opportunity_offer` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Notificaciones del Sistema
CREATE TABLE IF NOT EXISTS `user_notifications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `link` VARCHAR(255) NULL,
  `read_at` DATETIME NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_notif_user` (`user_id`),
  CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Configuraciones Generales del Sistema
CREATE TABLE IF NOT EXISTS `system_configuration` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(100) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `type` VARCHAR(50) NOT NULL DEFAULT 'text',
  `value` LONGTEXT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_config_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Tokens de Recuperación de Contraseña
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- DATOS SEMILLA OBLIGATORIOS (LOOKUPS Y CATALOGOS)
-- ============================================================================

-- Roles
INSERT INTO `rol` (`id`, `name`, `key`, `level`) VALUES
(1, 'ADMINISTRADOR', 'rol_admin', 1),
(2, 'DOCENTE', 'rol_teacher', 1),
(3, 'ESTUDIANTE', 'rol_student', 1),
(4, 'EMPRESA', 'rol_company', 1);

-- Estados de Ofertas
INSERT INTO `job_opportunity_offer_state` (`id`, `name`, `key`, `description`) VALUES
(1, 'Borrador', 'draft', 'Oferta guardada pero no publicada'),
(2, 'Vigente', 'active', 'Oferta publicada y abierta a postulaciones'),
(3, 'Finalizada', 'finished', 'Proceso de selección cerrado'),
(4, 'Suspendida', 'suspended', 'Oferta en pausa temporal'),
(5, 'Cancelada', 'canceled', 'Oferta anulada definitivamente');

-- Modalidades de Trabajo
INSERT INTO `job_opportunity_modalities` (`id`, `name`) VALUES
(1, 'Remoto'),
(2, 'Presencial'),
(3, 'Híbrido');

-- Jornadas Laborales
INSERT INTO `job_opportunity_work_schedules` (`id`, `name`) VALUES
(1, 'Jornada Completa'),
(2, 'Becas / Prácticas'),
(3, 'Jornada Parcial'),
(4, 'Por Horas');

-- Tipos de Contrato
INSERT INTO `job_opportunity_contract_types` (`id`, `name`) VALUES
(1, 'Contrato a plazo indeterminado'),
(2, 'Contrato a plazo fijo'),
(3, 'Contrato por temporada'),
(4, 'Largo plazo');

-- Categorías Laborales Base
INSERT INTO `job_opportunity_offer_category` (`id`, `name`) VALUES
(1, 'Informática / Tecnología'),
(2, 'Marketing'),
(3, 'Administrativo'),
(4, 'Ingeniería'),
(5, 'Contabilidad y Finanzas');

-- Programas de Estudio Ejemplo
INSERT INTO `study_programs` (`id`, `name`, `is_active`) VALUES
(1, 'Desarrollo de Sistemas de Información', 1),
(2, 'Administración de Redes y Comunicaciones', 1),
(3, 'Contabilidad', 1),
(4, 'Gestión Administrativa', 1);

-- Configuraciones Iniciales del Sistema
INSERT INTO `system_configuration` (`key`, `name`, `type`, `value`) VALUES
('system_title', 'Título del Sistema', 'text', 'Bolsa Laboral Institucional'),
('system_subtitle', 'Subtítulo', 'text', 'Conectando talento con oportunidades laborales'),
('system_email', 'Correo de Contacto', 'text', 'bolsalaboral@instituto.edu.pe'),
('system_phone', 'Teléfono de Contacto', 'text', '(01) 234-5678'),
('system_logo', 'Logotipo del Sistema', 'image', '/uploads/settings/default_logo.png'),
('terms_conditions', 'Términos y Condiciones', 'textarea', 'Términos y políticas de uso de la bolsa de trabajo.');

-- Usuario Administrador por Defecto (Clave: admin123)
-- Hash generado con password_hash('admin123', PASSWORD_BCRYPT)
INSERT INTO `person` (`id`, `document_type`, `document_number`, `names`, `email`, `phone`) VALUES
(1, 'DNI', '00000001', 'Administrador Principal', 'admin@bolsalaboral.pe', '999999999');

INSERT INTO `user` (`id`, `person_id`, `rol_id`, `email`, `password`, `is_active`) VALUES
(1, 1, 1, 'admin@bolsalaboral.pe', '$2y$10$WqBvh5hQyq17/aM0qLq1O.0b0o8zM9V5bC87x3kS8zRzK8gZ5m6jG', 1);
```

---

## 3. ESTRUCTURA DE DIRECTORIOS RECOMENDADA (PATRÓN MVC NATIVO)

Para organizar el proyecto de forma limpia, escalable y sin dependencias, se utiliza el estándar **MVC con Front Controller**:

```text
bolsa-laboral-nativa/
│
├── config/
│   ├── database.php             # Credenciales de conexión PDO
│   └── app.php                  # Constantes del sistema (URL_BASE, timezone, etc.)
│
├── core/                        # Utilidades y componentes del núcleo
│   ├── Database.php             # Singleton de conexión PDO
│   ├── Auth.php                 # Manejo de login, sesiones y verificación de roles
│   ├── Csrf.php                 # Generación y validación de tokens anti-CSRF
│   ├── Router.php               # Despachador de peticiones y rutas
│   ├── Uploader.php             # Subida y sanitización de archivos (CVs, Avatars, Logos)
│   ├── Exporter.php             # Generación de Excel / CSV nativo
│   ├── Mailer.php               # Envío de correos mediante mail() nativo o socket SMTP
│   └── Helpers.php              # Funciones auxiliares (sanitize(), json(), redirect())
│
├── controllers/                 # Controladores con la lógica de negocio
│   ├── AuthController.php       # Login, registro empresa, forgot & reset password
│   ├── LandingController.php    # Landing pública, búsqueda AJAX de ofertas
│   ├── StudentController.php    # Dashboard estudiante, perfil, CVs, postulaciones, guardados
│   ├── CompanyController.php    # Dashboard empresa, gestión de ofertas, candidatos, CV download
│   ├── AdminController.php      # Dashboard admin, usuarios, empresas, métricas, settings
│   └── NotificationController.php # Listado y marcado de notificaciones
│
├── models/                      # Modelos con consultas preparadas (PDO)
│   ├── User.php
│   ├── Person.php
│   ├── Company.php
│   ├── Offer.php
│   ├── Application.php
│   ├── SavedOffer.php
│   ├── Notification.php
│   └── SystemConfig.php
│
├── views/                       # Vistas HTML con PHP incrustado
│   ├── layouts/
│   │   ├── header.php           # Meta tags, estilos CSS, enlaces a fuentes
│   │   ├── navbar.php           # Barra superior y badge de notificaciones
│   │   ├── sidebar.php          # Menú lateral dinámico según rol_id
│   │   └── footer.php           # Scripts JS y modales comunes
│   ├── auth/
│   │   ├── login.php
│   │   ├── register-company.php
│   │   ├── forgot-password.php
│   │   └── reset-password.php
│   ├── landing/
│   │   └── index.php            # Buscador principal, filtros AJAX, ofertas activas
│   ├── student/
│   │   ├── profile.php          # Edición de perfil, habilidades, experiencia
│   │   ├── applications.php     # Mis postulaciones y estados
│   │   └── saved-offers.php     # Ofertas favoritas
│   ├── company/
│   │   ├── dashboard.php        # Métricas, ofertas propias y candidatos
│   │   └── profile.php          # Datos fiscales y logo
│   └── admin/
│       ├── dashboard.php        # Estadísticas, gráficos y actividad
│       ├── users.php            # CRUD de usuarios (DNI, roles, estado)
│       ├── companies.php        # Verificación y listado de empresas
│       ├── offers.php           # Supervisión de ofertas y mantenedores
│       ├── applications.php     # Listado general de postulaciones
│       └── settings.php         # Configuración del sistema
│
├── public/                      # Raíz accesible desde el servidor web (DocumentRoot)
│   ├── index.php                # Punto de entrada único (Front Controller)
│   ├── .htaccess                # Reescritura de URLs para Apache / XAMPP
│   ├── assets/
│   │   ├── css/                 # Estilos CSS (Tailwind minificado o CSS puro)
│   │   ├── js/                  # Scripts JavaScript (AJAX vanilla)
│   │   └── img/                 # Iconos y recursos estáticos
│   └── uploads/                 # Archivos subidos por usuarios (con permisos 0755)
│       ├── avatars/             # Fotos de perfil
│       ├── logos/               # Logos de empresas
│       ├── cvs/                 # Currículums vitae en PDF
│       └── settings/            # Logotipo del sitio
```

---

## 4. MAPEO DE RUTAS, MÓDULOS Y CONTROL DE ACCESO (RBAC)

En PHP nativo, todas las peticiones ingresan por `public/index.php?route=...` (o mediante URL amigable con `.htaccess`):

| Método | Ruta Virtual | Rol Permitido | Controlador @ Método | Descripción |
| :---: | :--- | :---: | :--- | :--- |
| **GET** | `/login` | Público | `AuthController@showLogin` | Muestra formulario de acceso unificado. |
| **POST** | `/login` | Público | `AuthController@login` | Valida credenciales (Email, DNI o RUC) y crea sesión. |
| **POST** | `/logout` | Autenticado | `AuthController@logout` | Destruye la sesión de forma segura y redirige. |
| **POST** | `/register/company` | Público | `AuthController@registerCompany` | Registro público de nueva empresa. |
| **POST** | `/forgot-password` | Público | `AuthController@forgotPassword` | Genera token de recuperación de contraseña. |
| **GET** | `/reset-password` | Público | `AuthController@showResetForm` | Formulario de cambio de contraseña con token. |
| **POST** | `/reset-password` | Público | `AuthController@resetPassword` | Actualiza la contraseña si el token es válido. |
| **GET** | `/` | 2, 3 (o Admin/Empresa) | `LandingController@index` | Portal principal de búsqueda de ofertas. |
| **GET** | `/api/offers/search` | Autenticado | `LandingController@searchOffers` | API JSON para búsqueda paginada y filtros. |
| **GET** | `/api/notifications` | Autenticado | `NotificationController@index` | Retorna las notificaciones no leídas en JSON. |
| **POST** | `/api/notifications/read` | Autenticado | `NotificationController@markRead`| Marca notificación como leída. |
| **POST** | `/student/profile` | 2, 3 | `StudentController@updateProfile` | Guarda datos personales, habilidades y experiencia. |
| **POST** | `/student/avatar` | 2, 3 | `StudentController@updateAvatar` | Sube y actualiza foto de perfil del usuario. |
| **POST** | `/student/password` | 2, 3 | `StudentController@changePassword` | Cambio de contraseña propia. |
| **POST** | `/student/cv/upload` | 3 | `StudentController@uploadCv` | Sube nuevo CV en PDF e incrementa versión. |
| **POST** | `/student/cv/delete` | 3 | `StudentController@deleteCv` | Soft-delete del CV especificado. |
| **GET** | `/student/cv/download` | 3 | `StudentController@downloadCv` | Descarga de CV propio. |
| **POST** | `/student/apply` | 3 | `StudentController@apply` | Envía postulación a una oferta con CV seleccionado. |
| **POST** | `/student/save-offer` | 3 | `StudentController@toggleSaveOffer` | Agrega o quita una oferta de favoritos. |
| **GET** | `/student/saved-offers` | 3 | `StudentController@savedOffers` | Vista de ofertas guardadas. |
| **GET** | `/student/applications` | 3 | `StudentController@myApplications` | Vista de postulaciones realizadas y feedback. |
| **GET** | `/company/dashboard` | 4 | `CompanyController@dashboard` | Panel con estadísticas, ofertas y postulantes. |
| **POST** | `/company/profile` | 4 | `CompanyController@updateProfile` | Actualiza datos de la empresa y sube logo. |
| **GET** | `/company/offers` | 4 | `CompanyController@listOffers` | Retorna en JSON las ofertas creadas por la empresa. |
| **POST** | `/company/offers/store` | 4 | `CompanyController@storeOffer` | Crea nueva oferta laboral con estado activo. |
| **POST** | `/company/offers/update` | 4 | `CompanyController@updateOffer` | Modifica datos de oferta existente. |
| **POST** | `/company/offers/toggle` | 4 | `CompanyController@toggleState` | Alterna oferta entre Vigente (2) y Finalizada (3). |
| **POST** | `/company/offers/delete` | 4 | `CompanyController@destroyOffer` | Eliminación lógica de oferta. |
| **POST** | `/company/applications/status` | 4 | `CompanyController@updateAppStatus` | Acepta o rechaza postulante y envía feedback. |
| **GET** | `/applications/cv/download` | 1, 4 | `CompanyController@downloadAppCv` | Descarga protegida del CV del candidato. |
| **GET** | `/admin/dashboard` | 1 | `AdminController@dashboard` | Estadísticas globales, top empresas y gráficos. |
| **GET** | `/admin/users/filter` | 1 | `AdminController@filterUsers` | Paginación y búsqueda AJAX de usuarios. |
| **POST** | `/admin/users/store` | 1 | `AdminController@storeUser` | Crea usuario (Admin, Docente, Alumno o Empresa). |
| **POST** | `/admin/users/update` | 1 | `AdminController@updateUser` | Actualiza usuario existente. |
| **POST** | `/admin/users/toggle` | 1 | `AdminController@toggleUserStatus` | Habilita o inhabilita acceso de un usuario. |
| **POST** | `/admin/users/bulk-delete` | 1 | `AdminController@bulkDeleteUsers` | Eliminación masiva de usuarios seleccionados. |
| **POST** | `/admin/study-programs/assign` | 1 | `AdminController@assignProgram` | Asigna carrera profesional a un estudiante. |
| **POST** | `/admin/companies/toggle-verify` | 1 | `AdminController@toggleVerifyCompany` | Aprueba o desaprueba una empresa para publicar. |
| **GET** | `/admin/export/excel` | 1 | `AdminController@exportExcel` | Descarga reporte completo de postulaciones en Excel. |
| **POST** | `/admin/settings` | 1 | `AdminController@saveSettings` | Guarda textos, contactos y logotipo institucional. |

---

## 5. LÓGICA DE NEGOCIO Y FLUJOS PRINCIPALES

### 5.1 Sistema de Autenticación Multicredencial
El formulario de login recibe un campo de entrada único llamado `login` o `identifier`:
1. Si tiene formato de email (`filter_var($login, FILTER_VALIDATE_EMAIL)`), busca en `user.email`.
2. Si tiene exactamente **8 dígitos numéricos** (`/^\d{8}$/`), asume que es un **DNI** y busca en `person` un registro donde `document_number = $login`, cruzando con `user.rol_id IN (2, 3)` (Estudiante o Docente).
3. Si tiene exactamente **11 dígitos numéricos** (`/^\d{11}$/`), asume que es un **RUC** y busca en `job_opportunity_company` donde `ruc = $login`, cruzando con `user.rol_id = 4` (Empresa).
4. **Verificación de Contraseña:**
   - Se valida contra `password_verify($password, $user['password'])`.
   - **Fallback para empresas recién creadas:** Si la verificación falla pero es rol empresa, se comprueba si `$password === $company['ruc']`.
5. **Alerta de Contraseña por Defecto:**
   - Si un estudiante o docente tiene como contraseña su propio número de DNI (`password_verify($person['document_number'], $user['password'])`), el sistema activa la variable de sesión `$_SESSION['show_password_warning'] = true` para mostrar un banner instándole a cambiarla inmediatamente.

### 5.2 Flujo de Registro de Empresas
1. La empresa se registra públicamente en `/registro-empresa` ingresando RUC, Razón Social, Correo y Contraseña.
2. Se valida que el RUC tenga 11 dígitos y sea único en `job_opportunity_company`.
3. Se valida que el correo sea único en `user`.
4. Se crea el registro en `job_opportunity_company` con `is_verified = 0` (pendiente de validación).
5. Se crea el usuario en `user` con `rol_id = 4`.
6. La empresa puede acceder a su panel, pero **no puede publicar ofertas ni activar ofertas** hasta que el Administrador valide la empresa desde el panel `/admin/companies` mediante `toggle-verify`.

### 5.3 Flujo de Postulación a Ofertas
1. El estudiante ubica una oferta laboral activa en la Landing `/`.
2. Presiona "Postular". El modal solicita seleccionar uno de sus CVs cargados previamente (de `job_opportunity_user_cv`) y un mensaje opcional.
3. Se verifica:
   - Que el usuario tenga completados sus datos en `person`.
   - Que el CV pertenezca al usuario autenticado.
   - Que no exista un registro previo en `job_opportunity_applications` con el mismo `user_id` y `offer_id` (evita doble postulación).
4. Se crea el registro en `job_opportunity_applications` con `status = 'postulated'`.
5. Se crea una notificación en `user_notifications` para el usuario de la empresa y para los administradores.
6. Opcional: Se despacha correo electrónico al email de contacto de la empresa.

### 5.4 Flujo de Gestión de Postulantes por la Empresa
1. La empresa entra a su panel y visualiza los candidatos por cada oferta.
2. Descarga el CV mediante `/applications/cv/download?id=...`:
   - El sistema valida en backend que el usuario autenticado sea la empresa dueña de la oferta o un Administrador. **Bajo ninguna circunstancia se expone la ruta física directa del archivo en el navegador.**
3. La empresa evalúa el perfil y actualiza el estado a `accepted` o `rejected`, agregando un texto de observaciones (`feedback`).
4. Al guardar:
   - Se actualiza `status`, `feedback` y `feedback_date = NOW()`.
   - Se genera una notificación interna en `user_notifications` para el estudiante indicándole el veredicto.

---

## 6. COMPONENTES DEL NÚCLEO (CORE) EN PHP NATIVO

A continuación se presentan las clases esenciales listas para copiar y utilizar sin ninguna dependencia externa:

### 6.1 Conexión PDO Singleton (`core/Database.php`)
```php
<?php
// core/Database.php

class Database {
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../config/database.php';
            
            $dsn = sprintf(
                "mysql:host=%s;port=%s;dbname=%s;charset=%s",
                $config['host'],
                $config['port'] ?? '3306',
                $config['database'],
                $config['charset'] ?? 'utf8mb4'
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $config['username'], $config['password'], $options);
            } catch (PDOException $e) {
                error_log("Error de conexión BD: " . $e->getMessage());
                die(json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']));
            }
        }
        return self::$instance;
    }
}
```

Archivo de configuración asociado (`config/database.php`):
```php
<?php
// config/database.php
return [
    'host'     => '127.0.0.1',
    'port'     => '3306',
    'database' => 'bolsa_laboral_nativa',
    'username' => 'root',
    'password' => '',
    'charset'  => 'utf8mb4'
];
```

---

### 6.2 Manejo de Sesiones y Autenticación (`core/Auth.php`)
```php
<?php
// core/Auth.php
require_once __DIR__ . '/Database.php';

class Auth {
    public static function initSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_samesite', 'Lax');
            session_start();
        }
    }

    public static function check(): bool {
        self::initSession();
        return !empty($_SESSION['user_id']) && !empty($_SESSION['rol_id']);
    }

    public static function id(): ?int {
        self::initSession();
        return $_SESSION['user_id'] ?? null;
    }

    public static function role(): ?int {
        self::initSession();
        return isset($_SESSION['rol_id']) ? (int) $_SESSION['rol_id'] : null;
    }

    public static function user(): ?array {
        if (!self::check()) return null;
        $db = Database::getConnection();
        
        $stmt = $db->prepare("
            SELECT u.*, p.names as person_name, p.document_number, p.career, c.name as company_name, c.is_verified as company_verified
            FROM user u
            LEFT JOIN person p ON u.person_id = p.id
            LEFT JOIN job_opportunity_company c ON u.company_id = c.id
            WHERE u.id = ? AND u.deleted_at IS NULL
            LIMIT 1
        ");
        $stmt->execute([self::id()]);
        return $stmt->fetch() ?: null;
    }

    public static function login(array $user): void {
        self::initSession();
        session_regenerate_id(true); // Previene fijación de sesión
        
        $_SESSION['user_id']    = (int) $user['id'];
        $_SESSION['rol_id']     = (int) $user['rol_id'];
        $_SESSION['email']      = $user['email'];
        $_SESSION['person_id']  = $user['person_id'] ?? null;
        $_SESSION['company_id'] = $user['company_id'] ?? null;

        // Actualizar último login
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE user SET last_login = NOW(), attempts = 0 WHERE id = ?");
        $stmt->execute([$user['id']]);
    }

    public static function logout(): void {
        self::initSession();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    public static function requireRole(array $allowedRoles): void {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }

        if (!in_array(self::role(), $allowedRoles, true)) {
            http_response_code(403);
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Acceso denegado. Permisos insuficientes.']);
            } else {
                echo "<h1>403 Acceso Denegado</h1><p>No tienes los permisos requeridos para acceder a esta sección.</p>";
            }
            exit;
        }
    }
}
```

---

### 6.3 Protección CSRF Nativa (`core/Csrf.php`)
```php
<?php
// core/Csrf.php
require_once __DIR__ . '/Auth.php';

class Csrf {
    public static function token(): string {
        Auth::initSession();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function field(): string {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(self::token()) . '">';
    }

    public static function validate(?string $token = null): bool {
        Auth::initSession();
        if ($token === null) {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        }
        return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function verifyOrAbort(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
            if (!self::validate()) {
                http_response_code(419);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Token de seguridad CSRF inválido o expirado.']);
                exit;
            }
        }
    }
}
```

---

### 6.4 Enrutador Front-Controller Simple (`core/Router.php`)
```php
<?php
// core/Router.php

class Router {
    private array $routes = [];

    public function get(string $path, callable|array $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable|array $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remover base path si está en subcarpeta (ej. /bolsalaboralv2)
        $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        if (!empty($baseDir) && str_starts_with($uri, $baseDir)) {
            $uri = substr($uri, strlen($baseDir));
        }
        $uri = '/' . trim($uri, '/');

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];
            if (is_callable($handler)) {
                call_user_func($handler);
            } elseif (is_array($handler)) {
                [$controllerClass, $action] = $handler;
                require_once __DIR__ . '/../controllers/' . $controllerClass . '.php';
                $controller = new $controllerClass();
                $controller->$action();
            }
            return;
        }

        // Manejo de 404 Not Found
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => "Ruta no encontrada: {$method} {$uri}"]);
    }
}
```

Archivo de configuración `.htaccess` para Apache (`public/.htaccess`):
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```

---

### 6.5 Subida Segura de Archivos (`core/Uploader.php`)
```php
<?php
// core/Uploader.php

class Uploader {
    /**
     * Sube un archivo validando tipo MIME real, extensión y peso máximo.
     */
    public static function upload(
        array $file,
        string $subfolder,
        array $allowedMimes,
        array $allowedExtensions,
        int $maxSizeBytes = 5242880 // 5 MB
    ): array {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Error al transferir el archivo al servidor.'];
        }

        if ($file['size'] > $maxSizeBytes) {
            $maxMb = round($maxSizeBytes / 1048576, 1);
            return ['success' => false, 'message' => "El archivo supera el tamaño máximo permitido de {$maxMb}MB."];
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, $allowedMimes, true)) {
            return ['success' => false, 'message' => 'Tipo de archivo no permitido.'];
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions, true)) {
            return ['success' => false, 'message' => 'Extensión de archivo inválida.'];
        }

        // Generar nombre seguro e irrepetible
        $cleanName = bin2hex(random_bytes(16)) . '_' . time() . '.' . $extension;
        $targetDir = __DIR__ . '/../public/uploads/' . trim($subfolder, '/');

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetPath = $targetDir . '/' . $cleanName;
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => false, 'message' => 'No se pudo guardar el archivo físico en el disco.'];
        }

        return [
            'success'   => true,
            'filename'  => $cleanName,
            'url'       => '/uploads/' . trim($subfolder, '/') . '/' . $cleanName,
            'full_path' => $targetPath
        ];
    }
}
```

---

### 6.6 Exportación a Excel sin Librerías (`core/Exporter.php`)
Sin utilizar PhpSpreadsheet ni dependencias pesadas, se puede generar un archivo Excel legítimo utilizando el formato **CSV con codificación UTF-8 con BOM** que Microsoft Excel abre perfectamente con todos los acentos y caracteres especiales:

```php
<?php
// core/Exporter.php

class Exporter {
    /**
     * Exporta datos a formato CSV compatible con Microsoft Excel (con BOM UTF-8 y separador por comas/punto y coma).
     */
    public static function toCsv(string $filename, array $headers, array $rows): void {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // BOM UTF-8 para que Excel abra acentos y caracteres especiales sin deformarlos
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Escribir encabezados
        fputcsv($output, $headers, ';');

        // Escribir filas
        foreach ($rows as $row) {
            fputcsv($output, $row, ';');
        }

        fclose($output);
        exit;
    }
}
```

Ejemplo de uso en `AdminController@exportExcel`:
```php
public function exportExcel(): void {
    Auth::requireRole([1]); // Solo Administrador

    $db = Database::getConnection();
    $stmt = $db->query("
        SELECT 
            app.id,
            app.fullname,
            app.program_study,
            app.status,
            app.created_at,
            o.title as offer_title,
            c.name as company_name,
            u.email as student_email
        FROM job_opportunity_applications app
        JOIN job_opportunity_offer o ON app.offer_id = o.id
        JOIN job_opportunity_company c ON o.company_id = c.id
        JOIN user u ON app.user_id = u.id
        WHERE app.deleted_at IS NULL
        ORDER BY app.created_at DESC
    ");
    $data = $stmt->fetchAll();

    $headers = ['ID', 'Postulante', 'Carrera / Programa', 'Estado', 'Fecha', 'Oferta Laboral', 'Empresa', 'Correo Electrónico'];
    $rows = [];

    foreach ($data as $item) {
        $rows[] = [
            $item['id'],
            $item['fullname'],
            $item['program_study'],
            $item['status'],
            $item['created_at'],
            $item['offer_title'],
            $item['company_name'],
            $item['student_email'],
        ];
    }

    Exporter::toCsv('Reporte_Postulaciones_' . date('Y-m-d'), $headers, $rows);
}
```

---

## 7. CONSIDERACIONES CRÍTICAS DE SEGURIDAD Y PRODUCCIÓN

Al migrar de un framework como Laravel a PHP nativo, se pierde la capa automática de seguridad por defecto. Por ello, debes implementar obligatoriamente las siguientes 6 reglas:

1. **Prepared Statements Obligatorios (Evitar SQL Injection):**
   - **NUNCA** concatenar variables en cadenas SQL (`"SELECT * FROM user WHERE id = " . $id`).
   - Usar siempre `$stmt = $db->prepare("SELECT * FROM user WHERE id = ?"); $stmt->execute([$id]);`.

2. **Sanitización de Salidas (Evitar XSS):**
   - En todas las vistas, imprimir variables de texto usando:
     ```php
     function e(?string $str): string {
         return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
     }
     ```
   - Uso en HTML: `<span><?= e($user['person_name']) ?></span>`.

3. **Descarga Segura de Archivos (Sin Exposición Directa de Rutas):**
   - Para descargar un CV, el cliente invoca `/applications/cv/download?id=X`.
   - El script valida que `Auth::id()` sea el dueño del CV o que la empresa sea propietaria de la oferta de la postulación.
   - Envía el archivo usando `readfile()` con headers `Content-Disposition: attachment; filename="CV.pdf"`.

4. **Hash de Contraseñas:**
   - Usar `password_hash($password, PASSWORD_BCRYPT)` para crear el hash al guardar.
   - Usar `password_verify($password, $hash)` al autenticar.

5. **Rate Limiting Nativo para Login:**
   - Registrar la IP y los intentos fallidos en una tabla auxiliar o en las columnas `attempts` y `last_attempt` de la tabla `user`.
   - Si `attempts >= 5` y han pasado menos de 60 segundos desde `last_attempt`, rechazar la petición con código 429 ("Demasiados intentos").

6. **Encabezados HTTP de Seguridad:**
   En el inicio de `public/index.php`, enviar los siguientes headers:
   ```php
   header('X-Content-Type-Options: nosniff');
   header('X-Frame-Options: SAMEORIGIN');
   header('X-XSS-Protection: 1; mode=block');
   header('Referrer-Policy: strict-origin-when-cross-origin');
   ```

---

## 8. CONCLUSIÓN Y SIGUIENTES PASOS

Con la base de datos SQL proporcionada en la [Sección 2](#2-estructura-de-base-de-datos-mysql-ddl-y-semillas) y los componentes nativos de la [Sección 6](#6-componentes-del-núcleo-core-en-php-nativo), tienes todo lo necesario para montar el proyecto en cualquier servidor XAMPP, LAMP o hosting compartido sin instalar `composer install` ni configurar dependencias externas.
