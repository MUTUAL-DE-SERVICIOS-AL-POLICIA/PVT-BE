#!/usr/bin/env bash
# Verifica que el despliegue responde. Uso: bash smoke.sh [url]
set -uo pipefail
BASE="${1:-http://localhost:8080}"
fail=0
check() { # path, codigo esperado, descripcion
  local code
  code="$(curl -s -o /dev/null -w '%{http_code}' --max-time 30 "$BASE$1")"
  if [ "$code" = "$2" ]; then printf '  \033[1;32mOK\033[0m   %-28s %s\n' "$1" "$code"
  else printf '  \033[1;31mFALLA\033[0m %-28s %s (esperaba %s)\n' "$1" "$code" "$2"; fail=1; fi
}

echo "== Humo contra $BASE =="
check /healthz 200
check /login 200
check / 302

page="$(curl -s --max-time 30 "$BASE/login")"
echo "$page" | grep -q 'name="_token"' && printf '  \033[1;32mOK\033[0m   formulario de login con CSRF\n' \
  || { printf '  \033[1;31mFALLA\033[0m el formulario de login no trae token\n'; fail=1; }

echo
if [ "$fail" -eq 0 ]; then echo "Smoke OK"; else echo "Smoke con fallas"; exit 1; fi
