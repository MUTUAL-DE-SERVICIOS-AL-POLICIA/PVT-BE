#!/bin/sh
# Prepara lo que la imagen no puede traer horneado.
set -e

mkdir -p storage/framework/cache storage/framework/sessions \
         storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# Descubrimiento de paquetes: sin esto Laravel reconstruye el manifest en el
# primer request y puede fallar si bootstrap/cache no es escribible.
if [ ! -f bootstrap/cache/packages.php ]; then
  php artisan package:discover --ansi || true
fi

# Precalienta caches costosos para que el primer usuario no pague 10s.
# Se ejecuta como www-data: si corre como root, deja archivos de cache con duenio
# root y luego PHP-FPM (www-data) no puede escribir -> fopen: No such file.
if command -v su-exec >/dev/null 2>&1; then
  su-exec www-data php artisan cache:warm --quiet 2>/dev/null || true
else
  php artisan cache:warm --quiet 2>/dev/null || true
fi
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# NO se ejecutan config:cache ni route:cache a proposito:
#  - 29 llamadas a env() fuera de config/ devolverian null con la config cacheada
#    (login LDAP, token de API y API de liveness dependen de eso).
#  - 19 closures en las rutas impiden route:cache.
# Ver README.md.

# Si piden un comando (artisan, sh, healthcheck), se respeta y no se levanta
# el servidor.
if [ "$#" -gt 0 ]; then
  exec "$@"
fi

php-fpm -D
exec nginx -g 'daemon off;'

