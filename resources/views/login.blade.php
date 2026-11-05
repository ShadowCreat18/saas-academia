<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - AcademiaCloud</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Inter:wght@300;400;500;600&family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { 
            --bg: #0a0a14; 
            --surface: rgba(0,0,0,0.2); 
            --primary: {{ app()->bound('tenant') && app('tenant') ? (app('tenant')->color_palette ?? '#9D4EDD') : '#9D4EDD' }}; 
            --accent: #ffffff; 
            --text: #F0E6FF; 
            --muted: #a78bca; 
            --border: rgba(255,255,255,0.1); 
            --glow: 0 0 24px {{ app()->bound('tenant') && app('tenant') ? (app('tenant')->color_palette ?? '#9D4EDD') : '#9D4EDD' }}80; 
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background:var(--bg); color:var(--text); font-family:'Inter',sans-serif; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative; }
        body::before { content:''; position:fixed; inset:0; z-index:-1; background: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(123,47,190,0.25) 0%, transparent 70%), radial-gradient(ellipse 50% 40% at 80% 80%, rgba(157,78,221,0.12) 0%, transparent 60%); }
        .container { max-width: 400px; width: 100%; padding: 2rem; }
        .logo { text-align: center; font-family: 'Syne',sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--primary); margin-bottom: 2rem; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 2.5rem; backdrop-filter: blur(16px); box-shadow: 0 8 32px rgba(0,0,0,0.3); }
        .title { font-family: 'Syne',sans-serif; font-size: 1.75rem; margin-bottom: 0.5rem; text-align: center; }
        .subtitle { color: var(--muted); font-size: 0.9rem; text-align: center; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; font-family: 'Space Grotesk',sans-serif; font-size: 0.85rem; color: var(--accent); margin-bottom: 0.5rem; }
        input { width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--border); border-radius: 10px; padding: 0.8rem 1rem; color: var(--text); font-family: 'Inter',sans-serif; transition: all 0.2s; }
        input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 2px rgba(157,78,221,0.2); }
        .btn-submit { width: 100%; background: linear-gradient(135deg, var(--primary), #6a1eb0); color: #fff; border: none; padding: 1rem; border-radius: 10px; font-family: 'Space Grotesk',sans-serif; font-weight: 600; font-size: 1rem; cursor: pointer; box-shadow: var(--glow); transition: all 0.2s; margin-top: 1rem; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 0 40px rgba(157,78,221,0.65); }
        @media(max-width: 480px) {
            .container { padding: 1rem; }
            .card { padding: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            @if(app()->bound('tenant') && app('tenant') && app('tenant')->logo_path)
                <img src="{{ asset('storage/' . app('tenant')->logo_path) }}" alt="Logo" style="max-height: 40px; border-radius: 4px;">
            @endif
            {{ app()->bound('tenant') && app('tenant') ? app('tenant')->academy_name : 'SaaS AcademiaHE5' }}
        </div>
        <div class="card">
            <h1 class="title">Iniciar Sesión</h1>
            
            @php
                $isRealTenant = app()->bound('tenant') && app('tenant') && !in_array(app('tenant')->subdomain, ['demo', 'saas']);
            @endphp
            @if(!$isRealTenant)
            <div style="background: rgba(157,78,221,0.15); border: 1px solid var(--primary); padding: 1.2rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.85rem;">
                <div style="text-align: center; margin-bottom: 8px;">
                    <strong style="color: #FFD700; font-size: 0.95rem;">🚀 Credenciales de Prueba (Demo)</strong><br>
                    <span style="font-size: 0.75rem; color: #fff; background: var(--primary); padding: 2px 8px; border-radius: 12px; display: inline-block; margin-top: 4px;">Plan Premium Activo 💎</span>
                </div>
                
                <div style="background: rgba(0,0,0,0.3); border-radius: 8px; padding: 8px 10px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="color: #fff; font-weight: 600;">👑 Admin (Academia):</span><br>
                        <span style="color: var(--muted); font-size: 0.8rem;">demo@academiahe5.com / password</span>
                    </div>
                    <button type="button" onclick="fillDemo('demo@academiahe5.com', 'password')" style="background: var(--primary); color:#fff; border:none; border-radius:6px; padding:4px 8px; font-size:0.75rem; cursor:pointer;">Usar</button>
                </div>

                <div style="background: rgba(0,0,0,0.3); border-radius: 8px; padding: 8px 10px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="color: #fff; font-weight: 600;">👨‍👦 Tutor / Padre:</span><br>
                        <span style="color: var(--muted); font-size: 0.8rem;">carlos.tutor@ejemplo.com / 123456</span>
                    </div>
                    <button type="button" onclick="fillDemo('carlos.tutor@ejemplo.com', '123456')" style="background: rgba(255,255,255,0.15); color:#fff; border:none; border-radius:6px; padding:4px 8px; font-size:0.75rem; cursor:pointer;">Usar</button>
                </div>
            </div>

            <script>
                function fillDemo(email, pass) {
                    document.querySelector('input[name="email"]').value = email;
                    document.querySelector('input[name="password"]').value = pass;
                }
            </script>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                
                @if ($errors->any())
                    <div style="background: rgba(255, 107, 107, 0.15); border: 1px solid #FF6B6B; color: #FF6B6B; padding: 0.8rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.85rem; text-align: center;">
                        Las credenciales son incorrectas o la cuenta no existe.
                    </div>
                @endif

                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" required placeholder="admin@ejemplo.com">
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" required placeholder="ャャャャャ">
                </div>
                <button type="submit" class="btn-submit">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>