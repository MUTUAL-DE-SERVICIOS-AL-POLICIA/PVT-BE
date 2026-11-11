#!/usr/bin/env bash
# Verificaciones previas al despliegue. No modifica nada.
set -uo pipefail
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# Cada entorno tiene su archivo de runtime: env/.env.<entorno>.
# Uso: bash preflight.sh [prod|test|dev|dev-local]   (por defecto prod)
ENTORNO="${1:-prod}"
ENVF="$ROOT/env/.env.$ENTORNO"
fail=0
ok()   { printf '  \033[1;32mOK\033[0m   %s\n' "$*"; }
bad()  { printf '  \033[1;31mFALLA\033[0m %s\n' "$*"; fail=1; }
warn() { printf '  \033[1;33mOJO\033[0m  %s\n' "$*"; }

echo "== Archivos =="
[ -f "$ENVF" ] && ok "env/.env.$ENTORNO existe" || bad "falta env/.env.$ENTORNO (copiar de .env.example)"
[ -f "$ROOT/Dockerfile" ] && ok "Dockerfile" || bad "falta Dockerfile"
if [ -f "$ROOT/public/mix-manifest.json" ]; then
  ok "assets compilados"
  # El manifiesto puede referenciar archivos que estan en .gitignore: si falta
  # alguno, la pagina carga con 404 y se ve rota sin error de servidor.
  if faltan="$(python3 -c '
import json, pathlib, sys
m = json.load(open(sys.argv[1]))
base = pathlib.Path(sys.argv[2])
print(",".join(k for k in m if not (base / k.lstrip("/")).exists()))
' "$ROOT/public/mix-manifest.json" "$ROOT/public")"; then
    [ -z "$faltan" ] && ok "todos los assets del manifiesto existen" \
      || bad "faltan assets referenciados por mix-manifest: $faltan"
  else
    warn "no se pudo verificar el manifiesto (falta python3)"
  fi
else
  bad "falta public/mix-manifest.json (la UI no renderiza)"
fi

echo "== env/.env.$ENTORNO =="
if [ -f "$ENVF" ]; then
  grep -q '^APP_KEY=base64:' "$ENVF" && ok "APP_KEY definida" || bad "APP_KEY vacia o invalida"
  grep -q '^APP_DEBUG=false' "$ENVF" && ok "APP_DEBUG=false" || bad "APP_DEBUG debe ser false en produccion"
  if grep -q '^APP_ENV=production' "$ENVF"; then
    ok "APP_ENV=production"
  elif [ "${ALLOW_NONPROD:-0}" = "1" ]; then
    warn "APP_ENV no es production (permitido por ALLOW_NONPROD=1)"
  else
    bad "APP_ENV debe ser production (o exportar ALLOW_NONPROD=1 para un ensayo)"
  fi

  # Cola: Laravel 10 lee QUEUE_CONNECTION. Si el .env define QUEUE_DRIVER con
  # otro valor, es una senal de configuracion vieja que puede confundir.
  qc="$(grep -m1 '^QUEUE_CONNECTION=' "$ENVF" | cut -d= -f2-)"
  qd="$(grep -m1 '^QUEUE_DRIVER=' "$ENVF" | cut -d= -f2-)"
  if [ -n "$qd" ] && [ "$qd" != "$qc" ]; then
    warn "QUEUE_DRIVER=$qd no se usa: Laravel 10 lee QUEUE_CONNECTION=${qc:-sync}"
  fi

  if grep -q '^BROADCAST_DRIVER=redis' "$ENVF"; then
    warn "BROADCAST_DRIVER=redis: el codigo no emite eventos, pero si se agregan, necesita un redis accesible"
  fi
  # Claves con espacios alrededor del "=": dotenv guarda la clave con el
  # espacio final ("USER_TEST_DEVICE ") y env() despues no la encuentra.
  malas="$(grep -nE '^[[:space:]]*[A-Z0-9_]+[[:space:]]+=' "$ENVF" 2>/dev/null | head -3)"
  if [ -n "$malas" ]; then
    warn "claves con espacios alrededor del '=' (env() no las encontrara):"
    echo "$malas" | sed 's/^/         /'
  fi

  for k in DB_HOST DB_DATABASE DB_USERNAME HASH_TOKEN; do
    v="$(grep -m1 "^$k=" "$ENVF" | cut -d= -f2-)"
    [ -n "$v" ] && ok "$k definida" || bad "$k vacia (rompe login o API)"
  done
fi

echo "== Compose =="
# Se valida cada entorno con su archivo de variables; COMPOSE_FILE decide si
# suma docker-compose.dev.yml.
for envf in "$ROOT"/env/*.compose.env; do
  [ -f "$envf" ] || { bad "no hay env/*.compose.env"; break; }
  nombre="$(basename "$envf" .compose.env)"
  if ( cd "$ROOT" && docker compose --env-file "env/$nombre.compose.env" config >/dev/null 2>&1 ); then
    ok "compose $nombre valido"
  else
    bad "compose $nombre no valida"
  fi
done

echo "== Cacheo de configuracion =="
warn "config:cache y route:cache NO se usan: 29 env() fuera de config/ y 19 closures en rutas."

echo
[ "$fail" -eq 0 ] && echo "Preflight OK" || { echo "Preflight con fallas"; exit 1; }
