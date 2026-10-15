# Diseño: Landing Pública de Ofertas Laborales y Flujo de Postulación con Autenticación

## 1. Resumen Ejecutivo
Transformar la pantalla principal (`/`) en una landing page pública moderna, intuitiva, rápida y responsive donde cualquier visitante (estudiantes, egresados, empresas o público en general) pueda explorar y buscar todas las ofertas laborales activas sin necesidad de iniciar sesión.

Al intentar postular a cualquier vacante, si el usuario no ha iniciado sesión, se le solicitará autenticarse de forma amigable mediante un modal informativo, preservando la oferta seleccionada para retornar y abrir el proceso de postulación inmediatamente después del inicio de sesión exitoso.

Además, toda la identidad visual (logo, nombre institucional, favicon y colores primario, secundario, secundario suavizado y acento) reflejará fielmente la configuración establecida por el Administrador en la tabla `system_configuration`.

---

## 2. Arquitectura de Rutas y Controladores

### 2.1 Modificación de Rutas (`routes/web.php`)
- **Extraer del middleware `auth`:**
  - `Route::get('/', [LandingController::class, 'index'])->name('landing');`
  - `Route::get('/buscar-ofertas', [LandingController::class, 'searchOffers'])->name('landing.search');`
- **Mantener bajo protección `auth` + `role`:**
  - Rutas de subida de CV (`/student/cv/upload`).
  - Rutas de postulación efectiva (`/student/apply/{offer_id}`).
  - Rutas de ofertas guardadas (`/student/save-offer/{offer_id}`).
  - Rutas de perfil y contraseñas.

### 2.2 Controlador (`LandingController.php`)
- Ya maneja visitas de usuarios no autenticados (`$authUser = null`).
- Consultas optimizadas con Eloquent y caché ligero de ser necesario:
  - Total de ofertas activas y empresas verificadas para contadores.
  - Carga de metadatos de filtros (modalidades, jornadas, tipos de contrato, categorías y departamentos).
  - Soporte de parámetro `?offer={id}` y `?apply=1`: si el usuario llega con este parámetro y ya está autenticado, la interfaz abre directamente la oferta y el modal de postulación.

---

## 3. Identidad Visual y Configuración Dinámica (Admin)

### 3.1 Integración de Colores y Logotipo
Se utilizarán las variables CSS ya configuradas dinámicamente desde `system_configuration`:
- `--pri`: Color primario institucional (`$config['primary_color'] ?? '#002741'`).
- `--sec`: Color secundario (`$config['secondary_color'] ?? '#006b60'`).
- `--secc`: Color secundario claro/suavizado (`$config['secondary_container_color'] ?? '#7df7e4'`).
- `--acc`: Color de acento (`$config['accent_color'] ?? '#f97316'`).
- Logo institucional: `$config['logo']`. Si no existe, se muestra ícono moderno con el nombre de la institución.
- Favicon institucional: `$config['favicon']`.

---

## 4. Estructura y Componentes de la Interfaz (`landing.blade.php`)

### 4.1 Topbar y Navbar Inteligente
- **Para visitantes no autenticados:**
  - Enlace rápido a "¿Eres una empresa? Publica ofertas" (`#empresas` o `/login#empresa`).
  - Botón "Ingresar" que lleva al login general.
  - Botón destacado "Publicar empleo" para captación de empresas aliadas.
- **Para usuarios autenticados (Estudiantes / Egresados / Docentes):**
  - Campanita de notificaciones.
  - Menú de avatar y perfil con acceso a "Mis postulaciones", "Ofertas guardadas", "Cambiar contraseña" y "Cerrar sesión".

### 4.2 Sección Hero Principal (Impacto Visual y Búsqueda Inmediata)
- **Título & Subtítulo:** "Encuentra tu próxima oportunidad laboral" — Conectamos el talento de nuestra institución con empresas líderes.
- **Buscador interactivo centralizado:**
  - Campo 1: Cargo, palabra clave o empresa (con sugerencias automáticas desplegables).
  - Campo 2: Departamento / Ubicación.
  - Botón de búsqueda de alta visibilidad.
- **Píldoras de Categorías Populares:** Accesos directos tipo chips con un clic para filtrar ofertas instantáneamente.
- **Barra de Estadísticas / Confianza:**
  - Contador de ofertas laborales activas.
  - Contador de empresas empleadoras verificadas.
  - Badge de "Oportunidades 100% Verificadas".

### 4.3 Explorador de Ofertas (Layout Dual Estilo LinkedIn/Indeed)
- **Barra de Filtros Rápidos:**
  - Ordenar (Más recientes, Mayor salario, Menor salario).
  - Modalidad (Presencial, Remoto, Híbrido).
  - Jornada laboral (Completa, Parcial, Prácticas).
  - Rango salarial.
  - Botón para limpiar filtros.
- **Columna de Resultados (Izquierda):**
  - Tarjetas de empleo con tipografía clara (`Inter` / `Plus Jakarta Sans`), logo de la empresa, título destacado, badges de modalidad, departamento y salario.
  - Skeleton loaders fluidos mientras cargan resultados.
- **Panel de Detalle Fijo (Derecha en Desktop / Modal en Móvil):**
  - Cabecera con logo grande, nombre de empresa con badge "Verificada", ubicación y botones de acción.
  - Botón principal de acción: "Postularme".
  - Cuerpo detallado: Salario, Modalidad, Jornada, Descripción, Requisitos, Beneficios, y detalles de la empresa.

---

## 5. Flujo de Autenticación al Postular

### 5.1 Caso Visitante (Guest)
1. El visitante selecciona una oferta y presiona **"Postularme"** (o "Guardar").
2. Se abre un modal emergente moderno y amigable:
   - Título: **"Inicia sesión para postular"**
   - Mensaje: *"Para postular a la vacante **[Título]** en **[Empresa]**, necesitas acceder con tu cuenta de estudiante o egresado."*
   - Botón Principal: **"Iniciar Sesión"** (redirige a `/login?redirect_offer={id}&apply=1`).
   - Botón Secundario: **"Registrarse como Egresado"** (`/registro-egresado`).
   - Opción para empresas: *"¿Eres una empresa? Ingresa aquí"*.
3. En el controlador de login (`AuthController`), al autenticarse exitosamente, Laravel redirige a `redirect()->intended('/')` o toma el parámetro `redirect_offer`, llevando al usuario de vuelta a la oferta seleccionada y activando automáticamente el modal de postulación (`openPostularModal()`).

### 5.2 Caso Usuario Autenticado
- Si el usuario ya está logueado como estudiante (rol 3) o egresado (rol 5) (o docente habilitado), el botón "Postularme" abre de inmediato el modal de confirmación y selección de CV ya existente en la plataforma.

---

## 6. Rendimiento y Optimización
- Cero dependencias pesadas: Vanilla JavaScript nativo y CSS variables.
- Debounce en inputs de búsqueda para reducir llamadas AJAX.
- Consultas con proyecciones específicas (`select('id', 'name', 'logo', ...)`).
- Responsive design 100% adaptable a smartphones, tablets y pantallas ultra wide.

---

## 7. Plan de Pruebas y Verificación
1. **Navegación anónima (Guest):** Abrir `/` sin sesión activa y verificar que cargue sin redirección al login.
2. **Branding y Colores:** Comprobar que los colores y logo definidos en Admin se apliquen en navbar, hero, badges y botones.
3. **Búsqueda y Filtros:** Probar búsqueda por texto, departamento y filtros dinámicos.
4. **Flujo de Postulación Guest:** Dar clic en "Postularme" en una oferta, comprobar modal informativo y redirección con retorno a la oferta.
5. **Flujo de Postulación Autenticado:** Iniciar sesión y comprobar postulación directa con CV.
