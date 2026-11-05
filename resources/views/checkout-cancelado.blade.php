<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Cancelado - AcademiaCloud</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Inter:wght@300;400;500;600&family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #0a0a14; --surface: rgba(123,47,190,0.08); --primary: #9D4EDD; --accent: #E0AAFF; --text: #F0E6FF; --muted: #a78bca; --border: rgba(157,78,221,0.3); }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background:var(--bg); color:var(--text); font-family:'Inter',sans-serif; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1rem; }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 3rem 2rem; text-align: center; max-width:450px; width: 100%; }
        .title { font-family: 'Syne',sans-serif; font-size: 2rem; margin-bottom: 1rem; color:#FF6B6B; }
        .btn { display: block; background: transparent; color:var(--text); border: 1px solid var(--border); text-decoration:none; padding: 1rem 2rem; border-radius: 10px; margin-top: 2rem; font-family:'Space Grotesk',sans-serif; font-weight:600; width: 100%; }
        .btn:hover { background:rgba(255,255,255,0.1); }
        @media(max-width: 480px) {
            .card { padding: 1.5rem; }
            .title { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div style="font-size:3REM;margin-bottom:1REM;">■</div>
        <h1 class="title">Pago Cancelado</h1>
        <p>Tu proceso de registro ha sido cancelado. No se te ha cobrado nada.</p>
        <a href="/#precios" class="btn">Ver Planes de Nuevo</a>
    </div>
</body>
</html>