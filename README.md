# PLATAFORMA VIRTUAL DE TRÁMITES - MÓDULO DE BENEFICIOS ECONÓMICOS

Sistema de administración y gestión de Fondo de Retiro, Complemento Económico,
Cuota y Auxílio Mortuorio.

Laravel 10.50.4 sobre PHP 8.3.36.

## Requisitos

- Docker y Docker Compose v2
- Git
- Acceso a PostgreSQL, LDAP y FTP del entorno

No hace falta PHP, Composer ni Node en la máquina: todo corre en la imagen.

## Arranque

```sh
cp .env.example .env          # completar credenciales y APP_KEY
bash preflight.sh             # valida el .env antes de construir

# desarrollo (:9014, código montado)
docker compose up -d

# producción (:9015, código horneado en la imagen)
docker compose -f docker-compose.prod.yml up -d --build
```

El puerto se cambia con `APP_PORT`:

```sh
APP_PORT=9020 docker compose up -d
```

## Migraciones

No hace falta `--force`: el comando pregunta y se confirma escribiendo `yes`.

```sh
docker compose -f docker-compose.prod.yml exec app php artisan migrate
```

`docker compose exec` abre una terminal interactiva, así que la confirmación
funciona. Si se ejecuta sin TTY (por ejemplo desde un script con `-T`), la
pregunta no se puede responder y el comando se cancela.

Para revisar qué va a ejecutar antes de aplicarlo:

```sh
docker compose -f docker-compose.prod.yml exec app php artisan migrate --pretend
```

## Verificación

```sh
curl -s -o /dev/null -w 'healthz: %{http_code}\n' http://localhost:9015/healthz
curl -s -o /dev/null -w 'login:   %{http_code}\n' http://localhost:9015/login
bash smoke.sh http://localhost:9015
```

Recorrido completo de rutas (reporta las que devuelvan 5xx):

```sh
APP_CONTAINER=pvt-be-prod PVT_USER=<usuario> PVT_PASS=<clave> PVT_ROL=53 \
  bash harness/crawl.sh http://localhost:9015
```

## Exportaciones en segundo plano

Las exportaciones pesadas de Excel no se generan en la petición del usuario:
se encolan y las procesa el servicio `worker`.

```sh
docker compose -f docker-compose.prod.yml logs -f worker    # ver el procesamiento
docker compose -f docker-compose.prod.yml exec app php artisan queue:failed
```

Con un worker corre **una exportación a la vez**; para permitir dos en paralelo:

```sh
docker compose -f docker-compose.prod.yml up -d --scale worker=2
```

El flujo es: se dispara el reporte → se muestra una página que consulta el
estado → cuando el archivo está listo, el navegador lo descarga solo.

Para agregar otra exportación al flujo, alcanza con encolarla:

```php
$jobKey = app(ExportController::class)->dispatchJob(
    $request, MiExport::class, [$parametro], 'archivo.xlsx'
);
return view('exports.processing', ['jobKey' => $jobKey]);
```

## Comandos frecuentes

```sh
# artisan y composer
docker compose exec app php artisan tinker
docker compose exec app php artisan route:list
docker compose exec app composer install

# logs (van a stderr)
docker compose logs -f app
docker compose -f docker-compose.prod.yml logs -f app

# limpiar cachés después de cambiar configuración
docker compose exec app php artisan optimize:clear

# apagar (conserva el volumen de storage)
docker compose down
docker compose -f docker-compose.prod.yml down
```

Después de editar el `.env` hay que recrear los contenedores, porque el archivo
se monta y el contenedor queda apuntando a la versión anterior:

```sh
docker compose down && docker compose up -d
```

## Estructura del despliegue

```
docker-compose.yml        desarrollo (código montado, :9014)
docker-compose.prod.yml   producción (código en la imagen, :9015)
Dockerfile                nginx + php-fpm 8.3.36, multi-etapa
nginx.conf                compresión y cache de estáticos
php.ini                   configuración de PHP (producción)
php-dev.ini               variante de desarrollo (opcache revalida)
entrypoint.sh             prepara storage y precalienta caché
preflight.sh              valida el .env antes de construir
smoke.sh                  verifica el contenedor ya arriba
harness/crawl.sh          recorrido de rutas: reporta las que devuelven 5xx
.env.example              plantilla con todas las claves
```

Los dos compose tienen la misma estructura: el servicio `app`, el servicio
`redis` y un volumen de storage. Solo cambian los montajes del código y las
variables `APP_ENV`, `APP_DEBUG` y `LOG_CHANNEL`.

## Notas

- **Assets versionados.** `public/js` y `public/css` están en el repositorio
  porque el layout usa `mix('/js/app.js')`: sin ellos, un clon limpio levanta
  la imagen pero ninguna pantalla autenticada carga.
- **Sesiones y caché en Redis.** El servicio `redis` del compose los conserva
  entre reinicios (`appendonly`). Para volver a archivos, poner
  `SESSION_DRIVER=file` y `CACHE_DRIVER=file` en el `.env`.
- **El `.env` no se versiona.** Contiene credenciales reales; se monta en el
  contenedor en tiempo de ejecución.
