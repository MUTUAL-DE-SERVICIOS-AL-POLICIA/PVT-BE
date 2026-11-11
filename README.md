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
make up-dev-local

# producción (:9015, código horneado en la imagen)
make up-prod
```

El puerto se cambia con `APP_PORT`:

```sh
APP_PORT=9020 make up-dev-local   # el puerto se cambia en env/<entorno>.compose.env
```

## Migraciones

No hace falta `--force`: el comando pregunta y se confirma escribiendo `yes`.

```sh
docker compose --env-file env/prod.compose.env exec app php artisan migrate
```

`docker compose exec` abre una terminal interactiva, así que la confirmación
funciona. Si se ejecuta sin TTY (por ejemplo desde un script con `-T`), la
pregunta no se puede responder y el comando se cancela.

Para revisar qué va a ejecutar antes de aplicarlo:

```sh
docker compose --env-file env/prod.compose.env exec app php artisan migrate --pretend
```

## Verificación

```sh
curl -s -o /dev/null -w 'healthz: %{http_code}\n' http://localhost:9015/healthz
curl -s -o /dev/null -w 'login:   %{http_code}\n' http://localhost:9015/login
bash smoke.sh                  # usa APP_URL del .env
bash smoke.sh http://localhost:9015   # o se le pasa la URL
```

## Entornos

Cada entorno tiene su archivo de variables de compose en `env/`, y se opera con
`make`:

| Entorno | Comando | Puerto | Imagen | CSS | Banner |
| --- | --- | --- | --- | --- | --- |
| Producción | `make up-prod` | 9015 | `pvt-app:prod` | prod | no |
| Testing | `make up-test` | 9014 | `pvt-app:test` | prod (TODO) | **sí** |
| Desarrollo público | `make up-dev` | 9013 ⚠️ | `pvt-app:dev` | prod (TODO) | **sí** |
| Desarrollo local | `make up-dev-local` | 9014 ⚠️ | `pvt-app:dev` | prod (TODO) | **sí** |

⚠️ = puerto por confirmar.

Los mismos targets sin `make`:

```sh
docker compose --env-file env/prod.compose.env up -d --build
make config-prod        # ver la configuracion resuelta
make logs-prod          # seguir los logs
make down-prod          # apagar
```

`docker-compose.yml` es la **base** (sin montajes de código). `docker-compose.dev.yml`
se suma encima para desarrollo: monta el código y agrega el servicio `assets`,
que recompila el frontend al guardar.

### Dónde vive cada variable

Tres capas, cada variable en un solo lugar:

| Capa | Archivo | Contiene | Se lee |
| --- | --- | --- | --- |
| Orquestación y build | `env/<entorno>.compose.env` (versionado) | proyecto, puerto, imagen, `COMPOSER_FLAGS`, `MIX_*`, `DEBUGBAR_ENABLED`, `ENV_FILE` | al ejecutar `docker compose`/`make` |
| Runtime de Laravel | `env/.env.<entorno>` (no versionado) | `APP_ENV`, `APP_DEBUG`, DB, redis, LDAP, secretos | al arrancar el contenedor |
| Plantilla | `.env.example` (versionado) | todas las variables de Laravel, vacías o seguras | solo documentación |

### Valores por entorno

**Runtime de Laravel** (`env/.env.<entorno>`):

| Variable | prod | test | dev | dev-local |
| --- | --- | --- | --- | --- |
| `APP_ENV` | production | production | development | development |
| `APP_DEBUG` | false | false | true | true |
| `LOG_LEVEL` | warning | warning | debug | debug |
| `SESSION_SECURE_COOKIE` | false | false | false | false |
| `APP_URL` | `http://<host-interno>:9015` | `http://<host-interno>:9014` | por confirmar | `http://localhost:9014` |

**Orquestación y build** (`env/<entorno>.compose.env`):

| Variable | prod | test | dev | dev-local |
| --- | --- | --- | --- | --- |
| `DEBUGBAR_ENABLED` | false | true | true | true |
| `COMPOSER_FLAGS` | `--no-dev` | `--dev` | `--dev` | `--dev` |
| `MIX_APP` | prod | prod | prod | prod |
| `MIX_PVT_URL` | vacío | vacío | vacío | vacío |
| `MIX_RRHH_URL` | vacío | vacío | vacío | vacío |
| `APP_PORT` | 9015 | 9014 | 9013 ⚠️ | 9014 ⚠️ |

`SESSION_SECURE_COOKIE` va en `true` solo cuando el sitio se sirve por HTTPS: con
http el navegador no manda la cookie y el login falla.

Paridad: **todos compilan con `MIX_APP=prod`** hasta que se apruebe el cambio de
test/dev a su propio scss. Por eso el CSS es idéntico entre entornos.

### Dos detalles que no hay que "arreglar"

**`COMPOSER_FLAGS`, no `COMPOSER_NO_DEV`.** Composer lee la variable de entorno
`COMPOSER_NO_DEV` y la aplica como si fuera `--no-dev`. Si el argumento se
llamara así y en test/dev se pasara `--dev`, el build falla con
`You can not use both --no-dev and --dev as they conflict with each other`.
Por eso el nombre es `COMPOSER_FLAGS` y **no debe renombrarse**.

`COMPOSER_FLAGS=--dev` es explícito y no estrictamente necesario (composer ya
incluye las dependencias de desarrollo por defecto), pero el compose usa
`${COMPOSER_FLAGS:---no-dev}` y con `:-` un valor vacío cae al default
`--no-dev`. Dejarlo vacío apagaría el Debugbar en test/dev.

**`ENV_FILE` empieza con `./`.** La ruta se usa como bind mount
(`${ENV_FILE}:/var/www/.env:ro`). Compose solo trata una ruta relativa como bind
si empieza con `./`; sin eso la interpreta como **volumen nombrado** y falla:
`service "app" refers to undefined volume env/.env.prod`.

## Por qué el banner requiere otra imagen

El Debugbar está en `require-dev`, así que la imagen de producción (`--no-dev`)
no lo incluye. El compose de testing construye con las dependencias de
desarrollo (`COMPOSER_NO_DEV=""`) para que la barra exista; el de producción
no las incluye.

Si hace falta construirla a mano:

```sh
docker build --build-arg COMPOSER_NO_DEV= -t pvt-app:test .
```

## Frontend

El bundle está compilado en **modo producción** y versionado en `public/`.
Para recompilarlo tras cambiar algo en `resources/assets`:

```sh
docker run --rm -v "$PWD:/app" -w /app node:8 \
  sh -c 'npm install && MIX_APP=prod npm run production'
```

Se usa node 8 porque el proyecto trae Laravel Mix 1.x (webpack 3).

`MIX_APP=prod` además activa **purgecss**, que recorta el CSS según las clases
usadas en las vistas. Es útil para el tamaño, pero puede borrar clases armadas
dinámicamente: revisar visualmente antes de quedarse con ese CSS.

## Exportaciones en segundo plano

Las exportaciones pesadas de Excel no se generan en la petición del usuario:
se encolan y las procesa el servicio `worker`.

```sh
docker compose --env-file env/prod.compose.env logs -f worker    # ver el procesamiento
docker compose --env-file env/prod.compose.env exec app php artisan queue:failed
```

Con un worker corre **una exportación a la vez**; para permitir dos en paralelo:

```sh
docker compose --env-file env/prod.compose.env up -d --scale worker=2
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
docker compose --env-file env/prod.compose.env logs -f app

# limpiar cachés después de cambiar configuración
docker compose exec app php artisan optimize:clear

# apagar (conserva el volumen de storage)
docker compose down
make down-prod
```

Después de editar el `.env` hay que recrear los contenedores, porque el archivo
se monta y el contenedor queda apuntando a la versión anterior:

```sh
docker compose down && make up-dev-local
```

## Estructura del despliegue

```
docker-compose.yml        base comun (sin montajes de codigo)
docker-compose.dev.yml    se suma en desarrollo: montajes + watcher de assets
env/<entorno>.compose.env variables de orquestacion y build (versionadas)
env/.env.<entorno>        variables de Laravel y secretos (no versionadas)
Dockerfile                assets (Node) + builder (PHP) + final (nginx/php-fpm)
Makefile                  atajos: up-<env>, down-<env>, logs-<env>, config-<env>
nginx.conf                compresion y cache de estaticos
php.ini                   configuracion de PHP (produccion)
php-dev.ini               variante de desarrollo (opcache revalida)
entrypoint.sh             prepara storage y precalienta cache
preflight.sh              valida el .env antes de construir
smoke.sh                  verifica el despliegue ya arriba
.env.example              plantilla de las variables de Laravel
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
