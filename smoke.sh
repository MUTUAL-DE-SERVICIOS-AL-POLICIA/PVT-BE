#!/usr/bin/env bash
# Verifica un despliegue ya levantado. Uso: bash smoke.sh [url] [contenedor]
set -uo pipefail
BASE="${1:-http://localhost:9015}"
CT="${2:-${APP_CONTAINER:-pvt-be-prod}}"
fail=0

ok()   { printf '  \033[1;32mOK\033[0m    %-32s %s\n' "$1" "$2"; }
bad()  { printf '  \033[1;31mFALLA\033[0m %-32s %s\n' "$1" "$2"; fail=1; }
info() { printf '  \033[1;33mOJO\033[0m   %-32s %s\n' "$1" "$2"; }
check() { local c; c="$(curl -s -o /dev/null -w '%{http_code}' --max-time 20 "$BASE$1")"; \
          [ "$c" = "$2" ] && ok "$1" "$c" || bad "$1" "$c (esperaba $2)"; }

echo "== HTTP contra $BASE =="
check /healthz 200
check /login 200
check / 302
check /ruta-que-no-existe 404

echo "== Formulario de login =="
html="$(curl -s --max-time 20 "$BASE/login")"
echo "$html" | grep -q 'name="_token"'     && ok "campo _token" "presente" || bad "campo _token" "ausente"
echo "$html" | grep -q 'name="csrf-token"' && ok "meta csrf-token" "presente" || bad "meta csrf-token" "ausente"

echo "== Assets =="
for a in /js/app.js /css/app.css /css/vendor.css; do check "$a" 200; done

echo "== Login con campos vacios (no debe dar 500) =="
tok="$(echo "$html" | grep -oE 'name="_token" value="[^"]+"' | head -1 | sed 's/.*value="//;s/"$//')"
if [ -n "$tok" ]; then
  c="$(curl -s -o /dev/null -w '%{http_code}' --max-time 20 -X POST "$BASE/login" \
       --data-urlencode "_token=$tok" --data-urlencode "username=" --data-urlencode "password=")"
  case "$c" in 200|302|419|422) ok "POST /login vacio" "$c" ;; *) bad "POST /login vacio" "$c" ;; esac
else
  info "POST /login vacio" "sin token, se omite"
fi

echo "== Contenedor $CT =="
if command -v docker >/dev/null 2>&1 && docker inspect "$CT" >/dev/null 2>&1; then
  st="$(docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{end}}' "$CT" 2>/dev/null)"
  if [ -n "$st" ]; then [ "$st" = "healthy" ] && ok "healthcheck" "$st" || info "healthcheck" "$st"; fi

  db="$(docker exec "$CT" php artisan tinker --execute='try { DB::connection()->getPdo(); echo "ok"; } catch (\Throwable $e) { echo "error"; }' 2>/dev/null | tail -1 | tr -d '\r')"
  [ "$db" = "ok" ] && ok "base de datos" "conecta" || bad "base de datos" "${db:-sin respuesta}"

  rd="$(docker exec "$CT" php artisan tinker --execute='try { echo app("redis")->connection()->ping() ? "ok" : "error"; } catch (\Throwable $e) { echo "error"; }' 2>/dev/null | tail -1 | tr -d '\r')"
  [ "$rd" = "ok" ] && ok "redis" "responde" || bad "redis" "${rd:-sin respuesta}"

  q="$(docker exec "$CT" php artisan tinker --execute='try { echo (int) app("redis")->connection()->command("llen", ["queues:exports"]); } catch (\Throwable $e) { echo "?"; }' 2>/dev/null | tail -1 | tr -d '\r')"
  info "cola exports" "$q pendientes"

  d="$(docker exec "$CT" sh -c 'test -f storage/logs/deprecations.log && wc -l < storage/logs/deprecations.log || echo 0' 2>/dev/null | tr -d '\r')"
  [ "${d:-0}" = "0" ] && ok "deprecaciones" "0" || info "deprecaciones" "$d lineas"
else
  info "contenedor" "no disponible; solo se probo HTTP"
fi

echo
if [ "$fail" -eq 0 ]; then echo "Smoke OK"; else echo "Smoke con fallas"; exit 1; fi
