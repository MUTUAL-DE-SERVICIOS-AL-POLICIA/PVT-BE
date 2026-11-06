#!/usr/bin/env bash
# Recorre las rutas GET sin parametros y reporta las que devuelven 5xx.
# Uso: PVT_USER=.. PVT_PASS=.. [PVT_ROL=..] [APP_CONTAINER=..] bash crawl.sh [url]
set -uo pipefail
BASE="${1:-http://localhost:9015}"
CT="${APP_CONTAINER:-pvt-be-prod}"
WORK="$(mktemp -d)"; trap 'rm -rf "$WORK"' EXIT
JAR="$WORK/jar"

page=$(curl -s -c "$JAR" "$BASE/login")
tok=$(echo "$page" | grep -oE 'name="_token" value="[^"]+"' | head -1 | sed 's/.*value="//;s/"$//')
curl -s -b "$JAR" -c "$JAR" -o /dev/null -X POST "$BASE/login" \
  --data-urlencode "_token=$tok" --data-urlencode "username=${PVT_USER:?definir PVT_USER}" --data-urlencode "password=${PVT_PASS:?definir PVT_PASS}"
page=$(curl -s -b "$JAR" -c "$JAR" "$BASE/changerol")
tok=$(echo "$page" | grep -oE 'name="_token"[^>]*value="[^"]+"' | head -1 | sed 's/.*value="//;s/"$//')
curl -s -b "$JAR" -c "$JAR" -o /dev/null -X POST "$BASE/postchangerol" --data-urlencode "_token=$tok" --data-urlencode "rol_id=${PVT_ROL:-53}"

docker exec "$CT" php artisan route:list --json 2>/dev/null > "$WORK/routes.json"
python3 - "$WORK/routes.json" > "$WORK/urls.txt" <<'PY'
import json,sys
d=json.load(open(sys.argv[1]))
BLOCK=('_debugbar','_ignition','logout','login','register','password','storage/','sanctum','livewire',
 'update','destroy','delete','remove','sync','send','generate','download','export','import','cambiar','cambio',
 'print','pdf','excel','report','chart','replicate')
s=set()
for r in d:
    if 'GET' not in r.get('method',''): continue
    u=r.get('uri','')
    if '{' in u or '}' in u: continue
    if any(b in u.lower() for b in BLOCK): continue
    s.add('/'+u.lstrip('/'))
print("\n".join(sorted(s)))
PY

total=0; bad=0; : > "$WORK/res"
while read -r u; do
  case "$u" in /get_all*|/get_*) q="?draw=1&start=0&length=10" ;; *) q="" ;; esac
  c=$(curl -s -b "$JAR" -o /dev/null -w '%{http_code}' --max-time 60 "$BASE$u$q")
  total=$((total+1)); [ "$c" -ge 500 ] && { bad=$((bad+1)); echo "$c $u" >> "$WORK/res"; }
done < "$WORK/urls.txt"

echo "== 5xx =="
sort -k2 "$WORK/res" 2>/dev/null || true
echo
echo "5xx: $bad de $total"
[ "$bad" -eq 0 ]
