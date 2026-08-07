# Diseño de endurecimiento de seguridad

**Fecha:** 2026-08-07

**Estado:** Aprobado por el usuario

**Entorno actual:** Desarrollo local con XAMPP

**Objetivo:** Corregir las vulnerabilidades confirmadas sin romper los flujos actuales y dejar la aplicación preparada para un despliegue seguro.

## Contexto y restricciones

- La aplicación solo se utiliza actualmente en la computadora del usuario; no se considera que haya ocurrido una filtración externa.
- El proyecto se encuentra bajo `C:\xampp\htdocs\bolsalaboralv2` y se accede mediante `public/`.
- Se conservarán y migrarán los CV existentes. No se eliminarán datos automáticamente.
- Se preservarán los cambios no relacionados que ya existen en el árbol de trabajo, especialmente el panel administrativo.
- Los cambios se implementarán en unidades pequeñas, con una prueba de regresión antes de cada corrección.
- No se mostrarán ni registrarán claves, tokens, contraseñas ni valores sensibles de archivos de entorno.

## Riesgos confirmados

### Críticos

1. Apache entrega con estado `200 OK` los archivos `.env`, `.env.caco` y `db_blaboral.sql` desde la raíz del proyecto.
2. Los CV se almacenan en `public/uploads/cvs` y pueden descargarse directamente sin pasar por autorización.
3. Datos controlables por estudiantes y empresas se insertan mediante `innerHTML` y atributos de eventos, lo que permite XSS almacenado, incluido XSS en la sesión de un administrador.
4. El inicio de sesión de empresas acepta el RUC como contraseña alternativa, incluso si la contraseña almacenada es diferente.
5. Varias cuentas utilizan DNI o RUC como contraseña inicial predecible sin un cambio obligatorio efectivo.

### Altos y medios

- Usuarios desactivados pueden conservar una sesión ya iniciada.
- Recuperación de contraseña y varias respuestas de error revelan información interna o permiten enumerar correos registrados.
- Registro, recuperación y restablecimiento no tienen límites completos contra abuso.
- La validación de arreglos de perfil, relaciones y cargas de archivos es insuficiente en varios flujos.
- Las dependencias bloqueadas contienen avisos altos vigentes en Guzzle, CommonMark, PhpSpreadsheet, Concurrently, Shell-Quote, PostCSS y NanoID.
- La suite actual tiene tres fallos y cuatro pruebas omitidas, incluidas pruebas de controladores que no crean el esquema requerido.

## Enfoque aprobado

Se aplicará endurecimiento completo por etapas. Primero se cerrarán los riesgos críticos y altos con cambios pequeños y compatibles con XAMPP. Después se reforzarán sesiones, cabeceras, CSP y pruebas. No se realizará una refactorización general de la aplicación fuera de lo necesario para eliminar las vulnerabilidades.

## 1. Perímetro web y archivos sensibles

### Desarrollo local

Se añadirá protección Apache en la raíz para denegar solicitudes a:

- archivos `.env*`, SQL, copias, registros y archivos temporales;
- manifiestos y archivos de bloqueo que no deban servirse;
- carpetas de código y datos como `app`, `bootstrap`, `config`, `database`, `resources`, `routes`, `storage`, `tests`, `vendor` y `node_modules`.

La regla permitirá conservar el acceso actual mediante `/bolsalaboralv2/public`. Una comprobación HTTP de cabeceras deberá devolver `403` o `404` para cada archivo sensible.

### Despliegue futuro

La documentación indicará que el `DocumentRoot` del dominio debe apuntar exclusivamente a `public/`. La protección en la raíz se conservará como defensa adicional y no sustituirá la configuración correcta del servidor.

## 2. Almacenamiento privado de CV

- El disco local privado almacenará CV bajo `storage/app/private/cvs`.
- La carpeta heredada `public/uploads/cvs` denegará acceso HTTP incluso mientras existan archivos pendientes de migración.
- Los nombres físicos serán UUID generados por el servidor y siempre terminarán en `.pdf` después de verificar el MIME real.
- La base de datos guardará rutas relativas privadas con formato `cvs/<uuid>.pdf`, nunca URLs públicas ni rutas absolutas.
- Se creará un comando idempotente con opción de simulación para migrar rutas que actualmente comiencen con `/uploads/cvs/`.
- Cada archivo antiguo se copiará una sola vez al almacenamiento privado y se verificará por tamaño y hash. Después, todas las referencias coincidentes en `job_opportunity_user_cv` y `job_opportunity_applications` se actualizarán dentro de una transacción. El original público solo se eliminará después de confirmar la transacción.
- Si una copia, verificación o actualización falla, el archivo original se conservará, no se modificará su referencia y el comando informará el registro afectado sin revelar datos del CV. Aunque permanezca temporalmente, la denegación HTTP de la carpeta heredada impedirá su descarga directa.
- Las descargas usarán el disco privado y una ruta obtenida exclusivamente desde la base de datos. No se concatenarán parámetros del usuario con `public_path` ni con rutas físicas.

### Matriz de autorización

Podrán descargar un CV:

- el estudiante propietario del CV o de la postulación;
- una empresa únicamente cuando la postulación corresponda a una oferta de esa empresa;
- un administrador.

Recibirán `403` otra empresa, otro estudiante o un rol no permitido; una persona no autenticada será redirigida al inicio de sesión. Un registro inexistente o eliminado devolverá `404`.

Los enlaces de correos y paneles apuntarán a la ruta autenticada de descarga por postulación, no al archivo físico.

## 3. Autenticación, contraseñas y sesiones

### Identificadores de acceso

- Empresas: RUC o correo como identificador.
- Estudiantes y docentes: DNI o correo como identificador.
- Administradores: correo.
- En todos los casos se exigirá la contraseña almacenada mediante hash.
- Se eliminará completamente la comparación directa entre contraseña ingresada y RUC.

### Cambio obligatorio

Se añadirá a usuarios un indicador booleano `must_change_password`.

- Las empresas que se autorregistren con una contraseña válida comenzarán con el indicador desactivado.
- Las cuentas creadas o importadas con DNI/RUC como contraseña temporal comenzarán con el indicador activado.
- Las cuentas existentes cuyo hash coincida con su DNI/RUC se marcarán mediante una operación de datos idempotente.
- Después de autenticarse, un usuario marcado solo podrá acceder al formulario de cambio de contraseña y cerrar sesión.
- Al establecer una contraseña válida, se desactivará el indicador y se regenerará la sesión.

### Regla uniforme de contraseña

La validación central exigirá:

- mínimo 10 caracteres;
- al menos una mayúscula, una minúscula y un número;
- confirmación en los flujos interactivos;
- valor diferente del DNI, RUC, correo y contraseña actual.

La misma regla se aplicará al registro de empresas, cambio propio, cambio administrativo y restablecimiento.

### Límites y enumeración

- Inicio de sesión: máximo 5 fallos por combinación de identificador e IP durante 60 segundos, y 20 intentos totales por IP durante 60 segundos.
- Registro de empresa: 5 solicitudes por IP durante una hora.
- Solicitud de recuperación: 5 solicitudes por combinación de correo e IP durante una hora.
- Restablecimiento: 10 solicitudes por IP durante una hora.
- La recuperación responderá siempre con el mismo mensaje y estado aceptado, exista o no el correo.
- Los tokens conservarán hash, uso único y expiración de 60 minutos.

### Sesiones y estado de cuenta

- El inicio de sesión y el autorregistro regenerarán el identificador de sesión.
- Cerrar sesión invalidará la sesión y regenerará el token CSRF.
- Un middleware comprobará `is_active` en cada solicitud autenticada; al detectar una cuenta desactivada, cerrará la sesión inmediatamente.
- En producción con HTTPS se exigirán cookies `Secure`, `HttpOnly` y `SameSite=Lax`; las sesiones almacenadas en base de datos estarán cifradas.
- Las empresas no verificadas podrán ver y completar su perfil, pero no crear, modificar o eliminar ofertas, modificar postulaciones ni administrar catálogos.

## 4. Autorización y validación

- Las rutas conservarán autenticación y rol como primera barrera.
- Las operaciones sobre recursos aplicarán además autorización por propiedad; conocer un identificador no otorgará acceso.
- Las operaciones empresariales que cambien estado compartirán una comprobación central de empresa asociada y verificada.
- Los identificadores relacionados con modalidad, categoría, jornada, contrato, oferta y CV usarán reglas `exists` contra la tabla correspondiente.
- La paginación aceptará como máximo 100 registros por solicitud; cada controlador podrá aplicar un límite menor.
- Los arreglos `skills`, `hobbies`, `education` y `experience` validarán sus elementos internos, tipos y longitudes máximas.
- Los textos de ofertas, perfiles, mensajes y comentarios tendrán límites explícitos acordes a sus columnas.

### Cargas

- CV: PDF real, máximo 5 MiB, nombre aleatorio y almacenamiento privado.
- Avatar: JPEG, PNG o WebP real, máximo 3 MiB.
- Logo: JPEG, PNG o WebP real, máximo 2 MiB; no se admitirán formatos ejecutables ni SVG.
- Excel: extensiones XLSX/XLS, MIME permitido, máximo 10 MiB, lectura en fragmentos de 250 filas y máximo 10 000 filas por importación.
- Ningún nombre original se utilizará como ruta física.

## 5. Prevención de XSS

- Los datos dinámicos controlables por usuarios no se concatenarán en HTML.
- La interfaz utilizará `textContent`, atributos asignados mediante DOM y elementos creados explícitamente.
- Cuando una plantilla estática sea indispensable, todos los valores dinámicos pasarán por una función común de escape contextual.
- Los objetos de ofertas no se incrustarán dentro de atributos `onclick`; se asociarán mediante identificadores y listeners.
- Blade usará escape normal y `Illuminate\Support\Js::from` para datos destinados a JavaScript.
- Las URL de `src` y `href` se construirán desde rutas internas o esquemas permitidos `https`/`http`; se rechazarán esquemas ejecutables.
- Se corregirán como mínimo los renderizados dinámicos de usuarios, empresas, ofertas, postulaciones, notificaciones, metadatos y perfiles.

## 6. Errores, registros y respuestas

- Ninguna respuesta entregará `Exception::getMessage()` al navegador.
- El cliente recibirá mensajes genéricos y estados HTTP coherentes: `400`, `403`, `404`, `422`, `429` o `500`.
- Los detalles se registrarán del lado del servidor con contexto no sensible y un identificador de incidente.
- No se registrarán contraseñas, tokens de restablecimiento, cookies, contenido de CV ni valores de entorno.
- Las transacciones harán rollback ante cualquier fallo y no devolverán éxito parcial.

## 7. Cabeceras y política del navegador

El middleware de seguridad establecerá:

- `X-Content-Type-Options: nosniff`;
- `X-Frame-Options: DENY` y `frame-ancestors 'none'`;
- `Referrer-Policy: strict-origin-when-cross-origin`;
- una política mínima de permisos;
- HSTS solo cuando la solicitud sea HTTPS;
- restricciones de objetos, URL base y contenido mixto mediante CSP;
- políticas de apertura y recursos entre orígenes compatibles con los recursos usados.

La CSP se aplicará en dos pasos:

1. política de reporte compatible para identificar scripts y eventos inline restantes;
2. migración de eventos a `addEventListener`, nonce por respuesta para scripts Blade y política aplicada sin `unsafe-inline` para scripts.

Los estilos inline podrán mantenerse inicialmente si son necesarios para la interfaz; la excepción no se extenderá a scripts.

## 8. Dependencias

- Composer actualizará únicamente los paquetes afectados y sus dependencias compatibles.
- Guzzle deberá quedar en una versión no afectada por los avisos detectados, CommonMark en `>=2.9.0` y PhpSpreadsheet en una versión posterior a `1.30.5` compatible con Laravel Excel.
- npm actualizará Concurrently, Shell-Quote, PostCSS y NanoID a versiones corregidas sin cambiar de forma innecesaria el sistema de construcción.
- Los archivos de bloqueo se actualizarán de forma reproducible.
- Criterio de aceptación: `composer audit` y `npm audit` no reportarán vulnerabilidades altas ni críticas. Cualquier aviso menor remanente deberá documentarse con su alcance.

## 9. Pruebas y criterios de aceptación

Cada vulnerabilidad tendrá primero una prueba que falle y después la corrección mínima.

### Pruebas obligatorias

- archivos sensibles devuelven `403` o `404` mediante Apache;
- un CV no puede descargarse por URL pública;
- matriz completa de autorización de CV;
- archivos PDF falsos, excesivos y rutas manipuladas son rechazados;
- el RUC funciona como identificador pero no como contraseña alternativa;
- cuentas con contraseña temporal quedan limitadas hasta cambiarla;
- cuentas desactivadas pierden una sesión ya abierta;
- límites de login, registro y recuperación devuelven `429`;
- recuperación no permite enumerar correos;
- empresas no verificadas no pueden cambiar ofertas o postulaciones;
- cargas XSS almacenadas se muestran como texto inerte en paneles y listados;
- respuestas `500` no contienen mensajes internos;
- cabeceras de seguridad están presentes y la CSP final no permite scripts inline sin nonce.

### Verificación final

Se ejecutarán:

- suite completa de Laravel;
- compilación frontend de producción;
- auditorías Composer y npm;
- comprobaciones HTTP locales de archivos sensibles y cabeceras;
- prueba del comando de migración de CV en simulación y ejecución real;
- nueva ejecución de la migración para demostrar idempotencia.

Los tres fallos actuales y las cuatro pruebas omitidas se corregirán cuando correspondan a estos flujos. Ninguna prueba de seguridad podrá quedar omitida. El trabajo no se considerará completo mientras haya fallos relacionados o avisos altos/críticos.

## Fuera de alcance

- Rotación de secretos por incidente, porque el sistema no ha estado expuesto externamente.
- Autenticación multifactor, inicio de sesión social o servicios externos de identidad.
- Refactorización estética o general de controladores y vistas no necesaria para seguridad.
- Cambios funcionales al panel administrativo que no estén relacionados con los riesgos descritos.
