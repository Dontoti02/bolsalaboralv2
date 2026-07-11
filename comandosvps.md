# Guia de Despliegue - VPS

Pasos para sincronizar el proyecto desde GitHub al servidor VPS (cPanel).

---

## 1. Entrar al proyecto

```bash
cd /home/istpcaco/iestpcoloniadelcaco_lravel
```

## 2. Configurar directorio seguro para Git

```bash
git config --global --add safe.directory /home/istpcaco/iestpcoloniadelcaco_lravel
```

## 3. Backup del archivo .env

Siempre crear una copia antes de cualquier cambio para evitar perder configuracion.

```bash
cp .env /root/env_colonia_backup_$(date +%F_%H%M)
```

## 4. Verificar estado del repositorio

```bash
git remote -v
git branch --show-current
git status
```

## 5. Actualizar codigo desde GitHub

```bash
BRANCH=$(git branch --show-current)
git pull origin $BRANCH
```

> **Nota:** Si hay conflictos con cambios locales, ejecuta `git status` y comparte el resultado antes de forzar nada.

## 6. Instalar/actualizar dependencias PHP

```bash
composer install --no-dev --optimize-autoloader
```

## 7. Compilar frontend (Vite)

```bash
npm install
npm run build
```

Verificar que Vite genero el build correctamente:

```bash
ls -la public/build/manifest.json
```

## 8. Limpiar cache de Laravel

```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
```

## 9. Corregir permisos de archivos

```bash
chown -R istpcaco:istpcaco /home/istpcaco/iestpcoloniadelcaco_lravel

chmod -R 775 storage bootstrap/cache
chmod 644 .env
chmod 755 public
chmod 644 public/index.php
```

## 10. Reiniciar servicios del servidor

```bash
/scripts/php_fpm_config --rebuild
/scripts/restartsrv_apache_php_fpm
/scripts/restartsrv_httpd
```

Si el PHP-FPM no inicia correctamente, usar:

```bash
/scripts/restartsrv_cpanel_php_fpm
/scripts/restartsrv_httpd
```

## 11. Verificar que la web este funcionando

```bash
curl -I https://blaboral.iestpcoloniadelcaco.edu.pe
curl -I https://blaboral.iestpcoloniadelcaco.edu.pe/login
curl -I https://blaboral.iestpcoloniadelcaco.edu.pe/admin/dashboard
```

---

## Referencia rapida

| Paso | Comando clave |
|------|---------------|
| Entrar al directorio | `cd /home/istpcaco/iestpcoloniadelcaco_lravel` |
| Backup .env | `cp .env /root/env_colonia_backup_$(date +%F_%H%M)` |
| Actualizar codigo | `git pull origin $(git branch --show-current)` |
| Dependencias PHP | `composer install --no-dev --optimize-autoloader` |
| Compilar frontend | `npm run build` |
| Limpiar cache | `php artisan optimize:clear` |
| Permisos | `chmod -R 775 storage bootstrap/cache` |
| Reiniciar servicios | `/scripts/restartsrv_apache_php_fpm` |
