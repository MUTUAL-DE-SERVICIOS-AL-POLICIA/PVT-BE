# PVT-BE: cómo levantarlo

Laravel + Docker. Hay tres entornos: **dev**, **test** y **prod**. Los tres salen
del mismo `docker-compose.yml`; dev además suma `docker-compose.dev.yml`, que monta
el código y agrega el contenedor `assets`. Qué archivos se usan en cada entorno
también se decide en la carpeta `env/`.

## Para empezar (dev)

```bash
cp .env.example env/.env.dev      # configuración de Laravel (no se versiona)
# edita env/.env.dev: base de datos, LDAP, APP_URL, etc.
bash preflight.sh dev             # revisa que no falte nada
make up-dev                       # levanta todo
```

Abre http://localhost:9012. En dev el código se monta desde tu disco, así que los
cambios en PHP se ven al recargar, y un contenedor `assets` recompila el frontend
cada vez que guardas.

## Los entornos

| Entorno | Puerto | Para qué sirve |
|---|---|---|
| dev  | 9012 | Desarrollo (en tu PC o en el servidor de desarrollo) |
| test | 9014 | Pruebas, con Debugbar visible para saber que no es producción |
| prod | 9014 | Producción, sin Debugbar |

Test y prod usan el mismo puerto porque corren en servidores distintos.

Comandos (cambia `dev` por `test` o `prod`):

```bash
make up-dev        # construye y levanta
make down-dev      # baja todo
make logs-dev      # sigue los logs de la app
make config-dev    # muestra la configuración final que usaría Docker
```

¿Quieres otro puerto porque el tuyo está ocupado?

```bash
APP_PORT=9020 make up-prod
```

Si lo cambias, actualiza también `APP_URL` en `env/.env.prod`, porque Laravel la usa
para los enlaces que genera (correos, URLs firmadas).

## Dónde va cada configuración

Hay dos archivos por entorno y es fácil confundirlos:

| Archivo | Qué contiene | ¿Se versiona? |
|---|---|---|
| `env/<entorno>.compose.env` | Cosas de Docker: puerto, nombre de la imagen, `MIX_*`, si lleva Debugbar | Sí |
| `env/.env.<entorno>` | El `.env` de Laravel: base de datos, LDAP, claves, `APP_ENV`... | **No** (tiene secretos) |

`.env.example` es la plantilla para crear los `env/.env.*`.

Regla práctica: si Laravel lo lee mientras corre, va en `env/.env.<entorno>`.
Si Docker lo necesita para construir o levantar, va en `env/<entorno>.compose.env`.

Valores que cambian por entorno en `env/.env.<entorno>`:

| Variable | dev | test | prod |
|---|---|---|---|
| `APP_ENV` | development | production | production |
| `APP_DEBUG` | true | true | **false** |
| `LOG_LEVEL` | debug | warning | warning |

## El frontend

El CSS y el JS **no están en git**. Se compilan dentro del Dockerfile (etapa `assets`,
con Node 8, que es lo que pide Laravel Mix 1.x) y la imagen final solo trae el resultado.

- **En dev**, el contenedor `assets` los regenera al guardar.
- **En test y prod**, se generan al construir la imagen (`make up-*` ya incluye `--build`).
- Después de un `git pull` no verás `public/js/app.js` ni `public/css/app.css`.
  No es un error: se generan al levantar.
- Los archivos de `public/js` y `public/css` que **sí** están en git (datatables.js,
  vendor.css, inspinia.css...) son librerías copiadas a mano; no los borres.

Variables `MIX_*`: se resuelven al compilar, no al arrancar, así que cambiarlas
exige reconstruir la imagen. **Nunca pongas secretos en una variable `MIX_*`**:
terminan dentro del JS público.

## Qué hay dentro

- **app**: la aplicación (nginx + php-fpm).
- **worker**: procesa la cola (exportaciones pesadas) fuera de la petición.
- **redis**: sesiones, caché y cola.
- **assets**: solo en dev, recompila el frontend.

## Dos detalles que parecen errores y no lo son

- La variable de build se llama `COMPOSER_FLAGS` y no `COMPOSER_NO_DEV` porque Composer
  lee esa última por su cuenta y choca con `--dev`. No la renombres.
- `ENV_FILE` empieza con `./` (`./env/.env.prod`). Sin eso, Compose lo toma por un volumen
  y falla.

## Si algo no cuadra

| Síntoma | Qué mirar |
|---|---|
| La pantalla sale sin estilos | Faltó reconstruir: `make up-<entorno>` (o el contenedor `assets` no está corriendo en dev) |
| Aparece el banner de dev en prod | Imagen vieja o `DEBUGBAR_ENABLED`/`COMPOSER_FLAGS` mal en `prod.compose.env`; reconstruye |
| No aparece el Debugbar en test | Revisa `DEBUGBAR_ENABLED=true` en `env/test.compose.env` y que la imagen se construyó con `COMPOSER_FLAGS=--dev` |
| El contenedor no arranca | `bash preflight.sh <entorno>`: seguramente falta `env/.env.<entorno>` |
| "port is already allocated" | Puerto ocupado: usa `APP_PORT=XXXX make up-<entorno>` |
| Dos entornos en la misma máquina | `APP_PORT=9020 COMPOSE_PROJECT_NAME=pvt-be-dev-2 make up-dev` |

Para comparar dos entornos y ver qué los diferencia de verdad:

```bash
make config-test > /tmp/test.yml
make config-prod > /tmp/prod.yml
diff /tmp/test.yml /tmp/prod.yml
```

Solo deberían diferir nombre, puerto, imagen, `COMPOSER_FLAGS`, `DEBUGBAR_ENABLED`
y el archivo `.env` montado.

## Pendiente a futuro

- Hoy los tres entornos compilan el CSS de prod (`MIX_APP=prod`). Falta revisar a mano
  y pasar test y dev a su propio SCSS.
- Node 8, npm 6 y Mix 1.x son muy viejos. Subirlos es un proyecto aparte: auditar,
  subir Node 8 → 12 → 14 → 16, cambiar `node-sass` por `sass`, migrar el bundler, y en cada
  paso comparar `app.css`, `app.js` y `mix-manifest.json` con la versión anterior.
- `MIX_PVT_URL` y `MIX_RRHH_URL` están vacías: son integraciones opcionales (aviso de
  préstamos pagados por garantes y precarga de datos de RRHH). La app funciona sin ellas.
