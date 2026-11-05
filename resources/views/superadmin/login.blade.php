<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin — AcademiaHE5</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Inter:wght@300;400;500;600&family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --bg: #060610; --primary: #9D4EDD; --accent: #E0AAFF; --text: #F0E6FF; --muted: #a78bca; --border: rgba(157,78,221,0.3); }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background:var(--bg); color:var(--text); font-family:'Inter',sans-serif; min-height:100vh; display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; }
        body::before { content:''; position:fixed; width:600px; height:600px; background:radial-gradient(circle, rgba(157,78,221,0.15) 0%, transparent 70%); top:-200px; left:-200px; animation:pulse 8s ease-in-out infinite; }
        body::after { content:''; position:fixed; width:400px; height:400px; background:radial-gradient(circle, rgba(224,170,255,0.1) 0%, transparent 70%); bottom:-100px; right:-100px; animation:pulse 6s ease-in-out infinite reverse; }
        @keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.2)} }
        .login-card { background:rgba(255,255,255,0.03); border:1px solid var(--border); border-radius:24px; padding:3rem 2.5rem; width:100%; max-width:420px; position:relative; z-index:1; backdrop-filter:blur(20px); box-shadow:0 25px 50px rgba(0,0,0,0.5), 0 0 0 1px rgba(157,78,221,0.1); }
        .shield-icon { width:70px; height:70px; background:linear-gradient(135deg, rgba(157,78,221,0.3), rgba(224,170,255,0.1)); border:1px solid var(--border); border-radius:20px; display:flex; align-items:center; justify-content:center; font-size:2rem; color:var(--accent); margin:0 auto 1.5rem; }
        h1 { font-family:'Syne'; font-size:1.8rem; font-weight:800; text-align:center; background:linear-gradient(135deg, var(--accent), var(--primary)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; margin-bottom:0.5rem; }
        .subtitle { text-align:center; color:var(--muted); font-size:0.85rem; margin-bottom:2rem; }
        .alert-error { background:rgba(255,107,107,0.1); border:1px solid rgba(255,107,107,0.3); color:#ff9999; padding:0.8rem 1rem; border-radius:10px; font-size:0.85rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:0.5rem; }
        .form-group { margin-bottom:1.2rem; }
        label { display:block; color:var(--accent); font-size:0.85rem; margin-bottom:0.5rem; font-family:'Space Grotesk'; }
        .input-wrap { position:relative; }
        .input-wrap i { position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--muted); font-size:0.9rem; }
        input[type="email"], input[type="password"] { width:100%; background:rgba(255,255,255,0.05); border:1px solid var(--border); border-radius:10px; padding:0.85rem 1rem 0.85rem 2.8rem; color:var(--text); font-family:'Inter'; font-size:0.95rem; transition:0.2s; }
        input[type="email"]:focus, input[type="password"]:focus { outline:none; border-color:var(--primary); background:rgba(157,78,221,0.08); }
        .btn-login { width:100%; padding:0.9rem; background:linear-gradient(135deg, var(--primary), #6a1eb0); border:none; border-radius:10px; color:#fff; font-family:'Space Grotesk'; font-weight:700; font-size:1rem; cursor:pointer; transition:0.2s; margin-top:0.5rem; display:flex; align-items:center; justify-content:center; gap:0.6rem; }
        .btn-login:hover { transform:translateY(-2px); box-shadow:0 8px 25px rgba(157,78,221,0.5); }
        .back-link { text-align:center; margin-top:1.5rem; font-size:0.82rem; color:var(--muted); }
        .back-link a { color:var(--accent); text-decoration:none; }
        .divider { border:none; border-top:1px solid var(--border); margin:1.5rem 0; }
        .security-badge { display:flex; align-items:center; justify-content:center; gap:0.4rem; font-size:0.75rem; color:var(--muted); }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="shield-icon"><i class="fa-solid fa-shield-halved"></i></div>
        <h1>SuperAdmin</h1>
        <p class="subtitle">Acceso restringido · Solo administradores autorizados</p>

        @if(session('error'))
        <div class="alert-error"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('superadmin.login.post') }}">
            @csrf
            <div class="form-group">
                <label>Correo Electrónico</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" required placeholder="admin@academiahe5.com" autocomplete="email">
                </div>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" required placeholder="••••••••" autocomplete="current-password">
                </div>
            </div>
            <button type="submit" class="btn-login">
                <i class="fa-solid fa-right-to-bracket"></i> Ingresar al Panel
            </button>
        </form>

        <hr class="divider">
        <div class="security-badge">
            <i class="fa-solid fa-lock" style="color:#25D366;"></i>
            Conexión cifrada · Acceso registrado
        </div>
        <div class="back-link"><a href="/"><i class="fa-solid fa-arrow-left"></i> Volver al sitio</a></div>
    </div>
</body>
</html>
