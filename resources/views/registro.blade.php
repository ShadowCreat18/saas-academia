<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - AcademiaCloud</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Inter:wght@300;400;500;600&family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0a0a14;
            --surface: rgba(123,47,190,0.08);
            --primary: #9D4EDD;
            --secondary: #C7GDFF;
            --accent: #E0AAFF;
            --text: #F0E6FF;
            --muted: #a78bca;
            --border: rgba(157,78,221,0.3);
            --glow: 0 0 24px rgba(157,78,221,0.4);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background:var(--bg); color:var(--text); font-family:'Inter',sans-serif; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative; }
        body::before {
            content:''; position:fixed; inset:0; z-index:-1;
            background: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(123,47,190,0.25) 0%, transparent 70%),
                        radial-gradient(ellipse 50% 40% at 80% 80%, rgba(157,78,221,0.12) 0%, transparent 60%);
        }
        .container { max-width: 500px; width: 100%; padding: 2rem; }
        .logo { text-align: center; font-family: 'Syne', sans-serif; font-size: 1.5rem; font-weight: 800; background: linear-gradient(135deg, var(--accent), var(--primary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 2rem; }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 2.5rem; backdrop-filter: blur(16px); box-shadow: 0 8 32px rgba(0,0,0,0.3); }
        .title { font-family: 'Syne', sans-serif; font-size: 1.75rem; margin-bottom: 0.5rem; text-align: center; }
        .subtitle { color: var(--muted); font-size: 0.9rem; text-align: center; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; font-family: 'Space Grotesk', sans-serif; font-size: 0.85rem; color: var(--accent); margin-bottom: 0.5rem; }
        input { width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--border); border-radius: 10px; padding: 0.8rem 1rem; color: var(--text); font-family: 'Inter', sans-serif; transition: all 0.2s; }
        input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 2px rgba(157,78,221,0.2); }
        .subdomain-wrapper { display: flex; align-items: center; background: rgba(0,0,0,0.3); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
        .subdomain-wrapper input { border: none; border-radius: 0; background: transparent; padding-right: 0; }
        .subdomain-wrapper input:focus { box-shadow: none; }
        .subdomain-wrapper .suffix { padding: 0.8rem 1rem 0.8rem 0.2rem; color: var(--muted); font-size: 0.9rem; }
        .btn-submit { width: 100%; background: linear-gradient(135deg, var(--primary), #6a1eb0); color: #fff; border: none; padding: 1rem; border-radius: 10px; font-family: 'Space Grotesk', sans-serif; font-weight: 600; font-size: 1rem; cursor: pointer; box-shadow: var(--glow); transition: all 0.2s; margin-top: 1rem; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 0 40px rgba(157,78,221,0.65); }
        .back-link { display: block; text-align: center; margin-top: 1.5rem; color: var(--muted); text-decoration: none; font-size: 0.85rem; }
        .back-link:hover { color: var(--text); }
        .plan-badge { display: inline-block; background: rgba(157,78,221,0.15); border: 1px solid var(--primary); border-radius: 100px; padding: 0.2rem 0.8rem; font-size: 0.75rem; color: var(--accent); font-family: 'Space Grotesk'; margin-bottom: 1rem; }
        @media(max-width: 480px) {
            .container { padding: 1rem; }
            .card { padding: 1.5rem; }
            .subdomain-wrapper { flex-direction: column; align-items: stretch; }
            .subdomain-wrapper .suffix { padding: 0.8rem; text-align: center; background: rgba(255,255,255,0.05); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">DARK AMETHYST STUDIO</div>
        <div class="card">
            <div style="text-align: center;">
                <span class="plan-badge">PLAN SELECCIONADO: {{ strtoupper($plan) }}</span>
            </div>
            <h1 class="title">Configura tu Academia</h1>
            <p class="subtitle">Ingresa los datos para crear tu espacio en la nube.</p>
            
            <form action="{{ route('checkout.create') }}" method="POST">
                @csrf
                <input type="hidden" name="plan" value="{{ $plan }}">
                
                <div class="form-group">
                    <label>Nombre de la Academia</label>
                    <input type="text" name="academy_name" required placeholder="Ej: Club Halcones F.C." value="{{ old('academy_name') }}">
                    @error('academy_name') <span style="color:#ff6b6b;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Subdominio Web</label>
                    <div class="subdomain-wrapper">
                        <input type="text" name="subdomain" required placeholder="halcones" value="{{ old('subdomain') }}">
                        <span class="suffix">.academiahe5.com</span>
                    </div>
                    @error('subdomain') <span style="color:#ff6b6b;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Nombre del Director / Dueño</label>
                    <input type="text" name="owner_name" required placeholder="Tu nombre completo" value="{{ old('owner_name') }}">
                    @error('owner_name') <span style="color:#ff6b6b;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="owner_email" required placeholder="correo@ejemplo.com" value="{{ old('owner_email') }}">
                    @error('owner_email') <span style="color:#ff6b6b;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" required placeholder="Crea una contraseña segura">
                    @error('password') <span style="color:#ff6b6b;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" required placeholder="Confirma tu contraseña">
                </div>

                <button type="submit" class="btn-submit">🔥 Proceder al Pago Seguro</button>
            </form>
        </div>
        <a href="/" class="back-link">← Volver a los planes</a>
    </div>
</body>
</html>