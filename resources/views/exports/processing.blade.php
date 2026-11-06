<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Generando exportación</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: system-ui, sans-serif; background: #f4f6f9; display: flex;
               align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .box { background: #fff; padding: 40px; border-radius: 8px; text-align: center;
               box-shadow: 0 2px 12px rgba(0,0,0,.08); max-width: 420px; }
        .spin { width: 36px; height: 36px; border: 4px solid #e3e8ef; border-top-color: #1c84c6;
                border-radius: 50%; animation: g 1s linear infinite; margin: 0 auto 20px; }
        @keyframes g { to { transform: rotate(360deg); } }
        .err { color: #c0392b; }
        a { color: #1c84c6; }
    </style>
</head>
<body>
    <div class="box">
        <div class="spin" id="spin"></div>
        <h3 id="titulo">Generando el archivo</h3>
        <p id="texto">La exportación se está procesando en segundo plano.<br>
           Es seguro cerrar esta ventana: el archivo se guarda igual.</p>
        <p id="error" class="err" style="display:none"></p>
    </div>

    <script>
        const jobKey  = @json($jobKey);
        const status  = "{{ route('export_status', ['jobKey' => '__K__']) }}".replace('__K__', jobKey);
        const download = "{{ route('export_download', ['jobKey' => '__K__']) }}".replace('__K__', jobKey);

        async function consultar() {
            try {
                const r = await fetch(status, { headers: { 'Accept': 'application/json' } });
                if (r.status === 404) return fallar('La exportación expiró. Volvé a generarla.');
                const d = await r.json();

                if (d.status === 'done') {
                    document.getElementById('spin').style.display = 'none';
                    document.getElementById('titulo').textContent = 'Listo';
                    document.getElementById('texto').innerHTML =
                        '<a href="' + download + '">Descargar el archivo</a>';
                    window.location.href = download;
                    return;
                }
                if (d.status === 'failed') return fallar(d.error || 'No se pudo generar el archivo.');
                setTimeout(consultar, 2000);
            } catch (e) {
                setTimeout(consultar, 3000);
            }
        }
        function fallar(msg) {
            document.getElementById('spin').style.display = 'none';
            document.getElementById('titulo').textContent = 'No se pudo generar';
            document.getElementById('texto').textContent = '';
            const el = document.getElementById('error');
            el.textContent = msg; el.style.display = 'block';
        }
        consultar();
    </script>
</body>
</html>
