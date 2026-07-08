# 🎯 Bolsa Laboral v2 — Manual de Instalación y Configuración

Sistema de bolsa de empleo para instituciones educativas. Permite a **estudiantes/egresados** postular a ofertas y a **empresas** publicar vacantes y gestionar postulantes.

---

## 📋 Requisitos Previos

| Requisito | Versión Mínima | Notas |
|-----------|----------------|-------|
| **PHP** | 8.2+ | Extensiones: `pdo_mysql`, `mbstring`, `xml`, `curl`, `zip`, `gd`, `bcmath`, `intl` |
| **Composer** | 2.x | Gestor de dependencias PHP |
| **Node.js** | 18+ | Incluye `npm` |
| **MySQL / MariaDB** | 8.0 / 10.6+ | Base de datos relacional |
| **Git** | 2.x | Control de versiones |

> **Opcional (Producción):** Nginx/Apache + SSL, Redis (colas/caché), Supervisor (workers).

---

## 🚀 Instalación Rápida (Desarrollo Local)

### 1. Clonar el repositorio
```bash
git clone https://github.com/Dontoti02/bolsalaboralv2.git
cd bolsalaboralv2
```

### 2. Instalar dependencias PHP
```bash
composer install --no-interaction --prefer-dist --optimize-autoloader
```

### 3. Instalar dependencias Frontend
```bash
npm install
```

### 4. Configurar entorno
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configurar Base de Datos (`.env`)
Edita `.env` con tus credenciales MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bolsalaboral
DB_USERNAME=root
DB_PASSWORD=tu_password
```

> **Nota:** Crea la base de datos antes:
> ```sql
> CREATE DATABASE bolsalaboral CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
> ```

### 6. Migraciones y Seeders
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 7. Compilar Assets Frontend
```bash
# Desarrollo (con hot-reload)
npm run dev

# Producción
npm run build
```

### 8. Servidor de Desarrollo
```bash
php artisan serve
```
🌐 Abre: **http://127.0.0.1:8000**

---

## ⚙️ Configuración Detallada

### Variables de Entorno Críticas (`.env`)

```env
# Aplicación
APP_NAME="Bolsa Laboral IESTP Purús"
APP_ENV=local                 # production en prod
APP_DEBUG=true                # false en prod
APP_URL=http://localhost:8000 # tu dominio en prod

# Base de Datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bolsalaboral
DB_USERNAME=root
DB_PASSWORD=

# Correo (Gmail SMTP ejemplo)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_app_password   # Contraseña de aplicación, NO tu password real
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tudominio.com"
MAIL_FROM_NAME="${APP_NAME}"

# Colas y Caché (Producción: usa Redis)
QUEUE_CONNECTION=database       # redis en prod
CACHE_STORE=database            # redis en prod
SESSION_DRIVER=database

# Archivos subidos
FILESYSTEM_DISK=local           # s3 en prod
```

### Configuración de Correo (Gmail)
1. Activa **Verificación en 2 pasos** en tu cuenta Google
2. Genera **Contraseña de Aplicación**: *Cuenta → Seguridad → Contraseñas de aplicaciones*
3. Usa esa contraseña en `MAIL_PASSWORD`

---

## 🗄️ Estructura de Base de Datos (Tablas Principales)

| Tabla | Descripción |
|-------|-------------|
| `users` | Usuarios del sistema (estudiantes, empresas, admins, docentes) |
| `person` | Datos personales extendidos (estudiantes/egresados) |
| `company` | Empresas registradas |
| `job_opportunity_offer` | Ofertas laborales publicadas |
| `job_opportunity_applications` | Postulaciones de estudiantes a ofertas |
| `job_opportunity_user_cv` | CVs subidos por estudiantes |
| `user_notifications` | Notificaciones in-app (campanita) |
| `categories`, `locations`, `work_schedules`, `contract_types` | Catálogos maestros |

> Ver migraciones en `database/migrations/

---

## 👥 Roles y Permisos

| Rol (`rol_id`) | Descripción | Accesos Principales |
|----------------|-------------|---------------------|
| **1** | **Admin** | Panel completo: usuarios, empresas, ofertas, reportes |
| **2** | **Docente** | Ver ofertas, compartir con estudiantes |
| **3** | **Estudiante/Egresado** | Buscar ofertas, postular, CVs, postulaciones, notificaciones |
| **4** | **Empresa** | Publicar ofertas, ver postulantes, cambiar estados, notificaciones |

> La asignación de rol se hace en registro o desde panel Admin.

---

## 📦 Comandos Útiles

```bash
# Limpiar cachés
php artisan optimize:clear

# Solo cachés de configuración
php artisan config:clear && php artisan route:clear && php artisan view:clear

# Ejecutar tests
php artisan test

# Formatear código (Pint)
./vendor/bin/pint

# Colas (procesar notificaciones, emails)
php artisan queue:work --tries=3 --timeout=60

# Logs en tiempo real
php artisan pail

# Enlace storage público (para avatars/logos/CVs)
php artisan storage:link
```

---

## 🌐 Despliegue en Producción (Checklist)

- [ ] `APP_ENV=production` y `APP_DEBUG=false`
- [ ] `APP_URL=https://tudominio.com`
- [ ] Generar `APP_KEY` única: `php artisan key:generate --force`
- [ ] Base de datos MySQL con usuario dedicado (no root)
- [ ] Configurar **Redis** para `QUEUE_CONNECTION`, `CACHE_STORE`, `SESSION_DRIVER`
- [ ] Configurar **Supervisor** para workers de cola:
  ```ini
  [program:laravel-worker]
  process_name=%(program_name)s_%(process_num)02d
  command=php /ruta/proyecto/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
  autostart=true
  autorestart=true
  user=www-data
  numprocs=2
  redirect_stderr=true
  stdout_logfile=/ruta/proyecto/storage/logs/worker.log
  ```
- [ ] **Nginx/Apache** apuntando a `/public` con SSL (Let's Encrypt)
- [ ] `npm run build` (assets minificados)
- [ ] `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- [ ] `php artisan storage:link`
- [ ] Backups automáticos de BD y `storage/app`

---

## 🖥️ Instalación en VPS Hosting (Producción paso a paso)

### Paso 1 — Acceder al VPS
```bash
ssh root@tu_ip_del_vps
```

### Paso 2 — Actualizar sistema y dependencias
```bash
# Ubuntu/Debian
apt update && apt upgrade -y
apt install -y curl wget git unzip software-properties-common

# CentOS/RHEL
dnf update -y
dnf install -y curl wget git unzip
```

### Paso 3 — Instalar PHP 8.2
```bash
# Ubuntu/Debian
apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-mbstring php8.2-xml \
  php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl \
  php8.2-mysql php8.2-redis php8.2-dom php8.2-tokenizer php8.2-fileinfo

# Verificar
php -v
```

### Paso 4 — Instalar MySQL/MariaDB
```bash
# Ubuntu/Debian
apt install -y mysql-server
mysql_secure_installation

# Crear base de datos y usuario
mysql -u root -p
```
```sql
CREATE DATABASE bolsalaboral CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'bolsa_user'@'localhost' IDENTIFIED BY 'tu_password_seguro';
GRANT ALL PRIVILEGES ON bolsalaboral.* TO 'bolsa_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Paso 5 — Instalar Node.js + NPM
```bash
# Node.js 20 LTS
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# Verificar
node -v && npm -v
```

### Paso 6 — Instalar Composer
```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
composer --version
```

### Paso 7 — Instalar Redis (opcional pero recomendado)
```bash
apt install -y redis-server
systemctl enable redis-server
systemctl start redis-server
```

### Paso 8 — Clonar el proyecto
```bash
cd /var/www
git clone https://github.com/Dontoti02/bolsalaboralv2.git
cd bolsalaboralv2
```

### Paso 9 — Configurar entorno
```bash
cp .env.example .env
php artisan key:generate
```

Edita `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bolsalaboral
DB_USERNAME=bolsa_user
DB_PASSWORD=tu_password_seguro

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_app_password
MAIL_ENCRYPTION=tls

QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis
```

### Paso 10 — Instalar dependencias y compilar
```bash
composer install --no-dev --optimize-autoloader --no-interaction
npm install
npm run build
```

### Paso 11 — Migraciones y permisos
```bash
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permisos
chown -R www-data:www-data /var/www/bolsalaboralv2
find /var/www/bolsalaboralv2 -type d -exec chmod 755 {} \;
find /var/www/bolsalaboralv2 -type f -exec chmod 644 {} \;
chmod -R 775 /var/www/bolsalaboralv2/storage
chmod -R 775 /var/www/bolsalaboralv2/bootstrap/cache
```

### Paso 12 — Configurar Nginx
```bash
apt install -y nginx
```

Crea el archivo `/etc/nginx/sites-available/bolsalaboral`:
```nginx
server {
    listen 80;
    server_name tudominio.com www.tudominio.com;
    root /var/www/bolsalaboralv2/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache de assets estáticos
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff2|woff|ttf|svg)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    # Proteger archivos sensibles
    location ~ /\.(env|ht) {
        deny all;
    }
}
```

```bash
ln -s /etc/nginx/sites-available/bolsalaboral /etc/nginx/sites-enabled/
rm /etc/nginx/sites-enabled/default
nginx -t
systemctl reload nginx
```

### Paso 13 — SSL con Let's Encrypt (gratis)
```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d tudominio.com -d www.tudominio.com
# Auto-renovación:
systemctl enable certbot.timer
```

### Paso 14 — Configurar Supervisor (Colas/Workers)
```bash
apt install -y supervisor
```

Crea `/etc/supervisor/conf.d/laravel-worker.conf`:
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/bolsalaboralv2/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/bolsalaboralv2/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
supervisorctl reread
supervisorctl update
supervisorctl start "laravel-worker:*"
```

### Paso 15 — Configurar Cron (Tareas programadas)
```bash
crontab -e
```
Agrega:
```cron
* * * * * cd /var/www/bolsalaboralv2 && php artisan schedule:run >> /dev/null 2>&1
```

### Paso 16 — Verificar funcionamiento
```bash
# Test Nginx
curl -I https://tudominio.com

# Test artisan
cd /var/www/bolsalaboralv2
php artisan about

# Ver logs si hay errores
tail -f storage/logs/laravel.log
```

### Resumen rápido comandos VPS
```bash
# Una vez todo configurado, para actualizar:
cd /var/www/bolsalaboralv2
git pull origin main
composer install --no-dev --optimize-autoloader
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🔐 Seguridad

| Medida | Estado |
|--------|--------|
| CSRF Protection | ✅ Laravel nativo |
| XSS Protection | ✅ Blade `{{ }}` escapa salida |
| SQL Injection | ✅ Eloquent/Query Builder |
| Rate Limiting | ✅ Throttle en rutas API |
| Password Hashing | ✅ Bcrypt (configurable rounds) |
| File Upload Validation | ✅ Tipos MIME, tamaño, extensión |
| `.env` fuera de webroot | ✅ Por defecto en Laravel |

---

## 🐛 Solución de Problemas Comunes

| Error | Solución |
|-------|----------|
| `The environment file is invalid!` | Revisa `.env`: sin espacios extra, comillas en valores con espacios, `MAIL_HOST=smtp.gmail.com` (sin texto extra) |
| `Class "Redis" not found` | Instala `php-redis` o usa `database` en `.env` para colas/caché |
| `Permission denied storage/` | `chmod -R 775 storage bootstrap/cache && chown -R www-data:www-data storage bootstrap/cache` |
| `Vite manifest not found` | Ejecuta `npm run build` |
| `Specified key was too long` | En `AppServiceProvider`: `Schema::defaultStringLength(191);` |
| Emails no llegan | Revisa `MAIL_FROM_ADDRESS`, usa contraseña de aplicación Gmail, revisa `storage/logs/laravel.log` |

---

## 📁 Estructura de Carpetas Clave

```
app/
├── Http/Controllers/
│   ├── LandingController.php      # Landing público + búsqueda ofertas
│   ├── StudentController.php      # Perfil, CVs, postulaciones estudiante
│   ├── CompanyDashboardController.php # Panel empresa: ofertas, postulantes
│   ├── NotificationController.php # API notificaciones (campanita)
│   └── AuthController.php         # Login, registro, roles
├── Models/
│   ├── User.php
│   ├── Person.php
│   ├── Company.php
│   ├── JobOpportunityOffer.php
│   ├── JobOpportunityApplication.php
│   └── UserNotification.php
resources/views/
├── landing.blade.php              # Landing + búsqueda + modales
├── student/dashboard.blade.php    # Panel estudiante
├── company/dashboard.blade.php    # Panel empresa
└── partials/
    ├── sidebar.blade.php
    └── notifications-dropdown.blade.php
database/migrations/               # Todas las migraciones
routes/web.php                     # Rutas web (auth, dashboards, API notificaciones)
```

---

## 🔗 Rutas Principales

| Ruta | Método | Descripción | Middleware |
|------|--------|-------------|------------|
| `/` | GET | Landing público (ofertas, búsqueda) | - |
| `/login` | GET/POST | Inicio de sesión | guest |
| `/register` | GET/POST | Registro con selección de rol | guest |
| `/student/dashboard` | GET | Panel estudiante (redirige a `/`) | auth, role:3 |
| `/company/dashboard` | GET | Panel empresa | auth, role:4 |
| `/admin/dashboard` | GET | Panel admin | auth, role:1 |
| `/buscar-ofertas` | GET | API búsqueda ofertas (AJAX) | - |
| `/notifications` | GET | API notificaciones usuario | auth |
| `/notifications/read-all` | POST | Marcar todas como leídas | auth |
| `/student/apply/{offer}` | POST | Postular a oferta | auth, role:3 |
| `/company/offers/{id}/toggle-state` | POST | Activar/Finalizar oferta | auth, role:4 |
| `/company/applications/{id}/status` | POST | Cambiar estado postulante | auth, role:4 |

---

## 📝 Licencia

Proyecto privado para **IESTP Purús**. Uso interno institucional.

---

## 👨‍💻 Créditos

Desarrollado con **Laravel 12**, **Tailwind CSS 4**, **Vite**, **MySQL**.

**Última actualización:** Julio 2025