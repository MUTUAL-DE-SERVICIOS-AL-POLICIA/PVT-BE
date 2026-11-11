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
# un archivo de runtime por entorno, copiado de la plantilla
cp .env.example env/.env.dev
bash preflight.sh dev              # valida ese entorno

make up-dev                        # desarrollo (:9012, código montado)
make up-prod                       # producción (:9014, código en la imagen)
```

El puerto y la imagen se cambian en `env/<entorno>.compose.env`, no por línea de
comandos.

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
curl -s -o /dev/null -w 'healthz: %{http_code}\n' http://localhost:9014/healthz
curl -s -o /dev/null -w 'login:   %{http_code}\n' http://localhost:9014/login
SMOKE_ENV=prod bash smoke.sh          # usa APP_URL de env/.env.prod (la URL publica)
bash smoke.sh http://localhost:9014   # para probar desde esta maquina
```

## Entornos

Cada entorno tiene su archivo de variables de compose en `env/`, y se opera con
`make`:

| Entorno | Comando | Puerto | Imagen | CSS | Banner |
| --- | --- | --- | --- | --- | --- |
| Producción | `make up-prod` | 9014 | `pvt-app:prod` | prod | no |
| Testing | `make up-test` | 9014 | `pvt-app:test` | prod (TODO) | **sí** |
| Desarrollo público | `make up-dev` | 9012 | `pvt-app:dev` | prod (TODO) | **sí** |

Testing y producción comparten el puerto 9014 porque corren en servidores
distintos.

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

| Variable | prod | test | dev |
| --- | --- | --- | --- |
| `APP_ENV` | production | production | development |
| `APP_DEBUG` | false | false | true |
| `LOG_LEVEL` | warning | warning | debug |
| `SESSION_SECURE_COOKIE` | false | false | false |
| `APP_URL` | `http://<host-interno>:9014` | `http://<host-interno>:9014` | ver nota abajo |

**Orquestación y build** (`env/<entorno>.compose.env`):

| Variable | prod | test | dev |
| --- | --- | --- | --- |
| `DEBUGBAR_ENABLED` | false | true | true |
| `COMPOSER_FLAGS` | `--no-dev` | `--dev` | `--dev` |
| `MIX_APP` | prod | prod | prod |
| `MIX_PVT_URL` | vacío | vacío | vacío |
| `MIX_RRHH_URL` | vacío | vacío | vacío |
| `APP_PORT` | 9014 | 9014 | 9012 |

`SESSION_SECURE_COOKIE` va en `true` solo cuando el sitio se sirve por HTTPS: con
http el navegador no manda la cookie y el login falla.

`dev` es el mismo entorno para el servidor y para la PC: cada máquina tiene su
propio `env/.env.dev` (no versionado) y solo cambia `APP_URL`:

```ini
# servidor
APP_URL=http://<host-interno>:9012
# PC local
APP_URL=http://localhost:9012
```

### Correr dev junto a otro entorno en la misma máquina

Si el puerto o el nombre del proyecto ya están en uso:

```sh
APP_PORT=9020 COMPOSE_PROJECT_NAME=pvt-be-dev-2 make up-dev
```

`COMPOSE_PROJECT_NAME` evita que los contenedores y volúmenes de los dos
proyectos se pisen.

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

Se compila **dentro de la imagen**, en la etapa `assets` del Dockerfile
(Node 8, porque el proyecto usa Laravel Mix 1.x). La imagen final no lleva Node
ni `node_modules`.

- **Desarrollo**: el servicio `assets` de `docker-compose.dev.yml` compila y
  queda en watch; al guardar en `resources/assets` regenera `public/`.
- **Test y producción**: lo genera el build de la imagen.

Los compilados (`public/js/app.js`, `public/css/app.css`, `mix-manifest.json`)
**ya no se versionan**. Después de un `git pull` no están, y no es un error:

- en desarrollo los regenera el servicio `assets` (`make up-dev`);
- en test y producción los genera el build (`make up-test`, `make up-prod`).

Si la interfaz aparece sin estilos después de un pull, es que faltó el `--build`
o el servicio `assets` no está corriendo.

Para recompilar una vez, sin levantar todo:

```sh
docker compose --env-file env/dev.compose.env run --rm assets
```

`MIX_APP` elige el CSS: `prod` (`app.scss` + purgecss), `test` (`app_test.scss`)
y `dev` (`app_dev.scss`). Hoy todos los entornos usan `prod`, para que el CSS sea
idéntico; un valor inválido falla el build a propósito.

Las variables `MIX_*` se pasan como build args desde `env/<entorno>.compose.env`.
Laravel Mix inyecta en el bundle **todas** las que empiezan con `MIX_`
(`MixDefinitionsPlugin`), así que no hace falta DefinePlugin. No son secretas:
terminan dentro del JS público.

| Variable | Dónde se usa | Qué hace | Si está vacía |
| --- | --- | --- | --- |
| `MIX_PVT_URL` | `Alert.vue` (detalle de ret_fun) | consulta `{URL}/loans_paid_by_guarantors/{id}` en la plataforma de préstamos y muestra el aviso de préstamos pagados por garantes | no consulta nada |
| `MIX_RRHH_URL` | `EditUser.vue` (edición de usuario) | consulta `{URL}/employee/{id}` para precargar teléfono y ciudad | la URL queda relativa y la consulta falla en silencio |

No son obligatorias: la app funciona sin ellas. Solo hacen falta si se quieren
esas dos funciones. Cambiarlas exige reconstruir la imagen (van dentro del
bundle).

**Nunca pongas secretos en variables `MIX_*`.** Laravel Mix inyecta en el bundle
todas las que empiezan con `MIX_`, y el bundle es público: cualquiera puede leer
esas cadenas. Van vacías en los tres entornos.

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
docker compose down && make up-dev
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

## Comparar entornos

La configuración resuelta de cada entorno se puede volcar y comparar:

```sh
docker compose --env-file env/prod.compose.env config > /tmp/prod.yml
docker compose --env-file env/test.compose.env config > /tmp/test.yml
diff /tmp/prod.yml /tmp/test.yml
```

Entre prod y test solo deben diferir: `name`, `APP_PORT`, `image`,
`COMPOSER_FLAGS`, `DEBUGBAR_ENABLED` y la ruta de `ENV_FILE`. Cualquier otra
diferencia es un cambio que se coló.

`make config-<env>` hace lo mismo sin redirigir.

## Problemas frecuentes

| Síntoma | Causa | Solución |
| --- | --- | --- |
| La UI aparece sin estilos o sin JS | los compilados no están y no se generaron | `make up-<env>` (con `--build`); en dev, verificar que el servicio `assets` esté arriba |
| Cambié un `.scss` y no se ve | en test/prod el frontend va dentro de la imagen | reconstruir: `make up-prod` |
| Aparece la barra del Debugbar en prod | `DEBUGBAR_ENABLED=true` o imagen con `--dev` | revisar `env/prod.compose.env`; debe ser `false` y `COMPOSER_FLAGS=--no-dev` |
| No aparece el banner en test | la imagen se construyó sin dependencias de desarrollo | `COMPOSER_FLAGS=--dev` en `env/test.compose.env` y reconstruir |
| Cambié `MIX_*` y no pasa nada | van dentro del bundle | reconstruir la imagen |
| `service "app" refers to undefined volume env/.env.prod` | `ENV_FILE` sin `./` | usar `./env/.env.prod` |
| El puerto está ocupado al levantar dev | ya hay otro entorno con ese puerto | `APP_PORT=9020 COMPOSE_PROJECT_NAME=pvt-be-dev-2 make up-dev` |

## Actualizar Node/Mix

Estado actual: **Node 8**, Laravel Mix **1.7.2** (webpack 3), `node-sass` 4.14.1.

`npm audit` (al 2026-11-11) reporta **470 vulnerabilidades** (19 críticas, 188
altas, 246 moderadas, 17 bajas) en 54 paquetes. Son de **desarrollo**: no viajan
al bundle ni a la imagen final. Pero bloquean subir Node.

### Qué bloquea la subida

| Paquete | Versión | Por qué bloquea |
| --- | --- | --- |
| `node-sass` | 4.14.1 | no compila en Node 16+; necesita 6.x/7.x o migrar a `sass` (dart) |
| `webpack` | 3.12.0 | Node 17+ rompe con OpenSSL 3 (`ERR_OSSL_EVP_UNSUPPORTED`, hash md4) |
| `laravel-mix` | 1.7.2 | ata webpack 3 |
| `pngquant-bin` / `optipng-bin` | 4.0.0 / 3.1.4 | binarios viejos, sin builds para Node moderno |
| `babel-*` | 6.x | toolchain antiguo (no bloquea, pero conviene migrar) |

### Pasos

1. **Auditoría de partida**: `npm audit > auditoria-inicial.txt` (sin `fix`).
2. **Saltos intermedios de Node**: 8 → 12 → 14 → 16. En cada salto cambiar
   `NODE_VERSION` en el Dockerfile, reconstruir y correr `npm run production`.
   No saltear directo a 18/20: cada versión destapa un problema distinto.
3. **Migrar el bundler**: Laravel Mix 6 (webpack 5), `node-sass` → `sass`,
   quitar `browser-sync` y `laravel-mix-purgecss` (Mix 6 lo trae), actualizar
   `vue-loader` (Vue 2.7 ya está instalado).
4. **Regla en cada paso**: comparar los tres artefactos antes y después

   ```
   public/css/app.css
   public/js/app.js
   public/mix-manifest.json
   ```

   Tamaño y contenido. Si `app.css` cambia mucho, revisar purgecss (puede estar
   borrando clases que se arman desde PHP). Si `app.js` cambia, revisar que no se
   haya activado el `mangle` de uglify (rompe componentes de Vue).

5. **Cerrar con el smoke**: `make up-test` y recorrer las pantallas con datos
   reales antes de tocar producción.

## Notas

- **Assets versionados.** `public/js` y `public/css` están en el repositorio
  porque el layout usa `mix('/js/app.js')`: sin ellos, un clon limpio levanta
  la imagen pero ninguna pantalla autenticada carga.
- **Sesiones y caché en Redis.** El servicio `redis` del compose los conserva
  entre reinicios (`appendonly`). Para volver a archivos, poner
  `SESSION_DRIVER=file` y `CACHE_DRIVER=file` en el `.env`.
- **El `.env` no se versiona.** Contiene credenciales reales; se monta en el
  contenedor en tiempo de ejecución.
