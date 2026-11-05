<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dark Amethyst Studio — AcademiaCloud SaaS</title>
    <meta name="description" content="Plataforma SaaS white-label para academias deportivas. Lanza tu academia digital con pagos Stripe, gestión de alumnos y dominio propio.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Inter:wght@300;400;500;600&family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0a0a14;
            --surface: rgba(123,47,190,0.08);
            --primary: #9D4EDD;
            --secondary: #C77DFF;
            --accent: #E0AAFF;
            --text: #F0E6FF;
            --muted: #a78bca;
            --border: rgba(157,78,221,0.3);
            --glow: 0 0 24px rgba(157,78,221,0.4);
        }
        * { margin:0; padding:0; box-sizing:border-box; scroll-behavior: smooth; }
        body { background:var(--bg); color:var(--text); font-family:'Inter',sans-serif; overflow-x:hidden; }

        /* Particles background */
        body::before {
            content:''; position:fixed; inset:0; z-index:0;
            background: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(123,47,190,0.25) 0%, transparent 70%),
                        radial-gradient(ellipse 50% 40% at 80% 80%, rgba(157,78,221,0.12) 0%, transparent 60%);
            pointer-events:none;
        }

        /* NAV */
        nav { position:fixed; top:0; left:0; right:0; z-index:100; display:flex; align-items:center;
              justify-content:space-between; padding:1rem 4rem;
              background:rgba(10,10,20,0.85); backdrop-filter:blur(20px);
              border-bottom:1px solid var(--border); }
        .nav-logo { display:flex; align-items:center; gap:.75rem; text-decoration:none; }
        .nav-logo-icon { width:36px; height:36px; }
        .nav-logo span { font-family:'Syne',sans-serif; font-weight:800; font-size:1rem;
                         background:linear-gradient(135deg,var(--accent),var(--primary)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; letter-spacing:.05em; }
        .nav-links { display:flex; gap:2rem; list-style:none; }
        .nav-links a { color:var(--muted); text-decoration:none; font-size:.9rem; transition:color .2s; }
        .nav-links a:hover { color:var(--text); }
        .nav-cta { background:linear-gradient(135deg,var(--primary),#6a1eb0); color:#fff; border:none;
                   padding:.6rem 1.4rem; border-radius:8px; font-family:'Space Grotesk',sans-serif;
                   font-weight:600; cursor:pointer; box-shadow:var(--glow); text-decoration:none; font-size:.9rem;
                   transition:transform .2s, box-shadow .2s; }
        .nav-cta:hover { transform:translateY(-2px); box-shadow:0 0 32px rgba(157,78,221,0.6); }

        /* HERO */
        .hero { position:relative; z-index:1; min-height:100vh; display:flex; flex-direction:column;
                align-items:center; justify-content:center; text-align:center; padding:8rem 2rem 4rem; }
        .hero-badge { display:inline-flex; align-items:center; gap:.5rem; background:rgba(157,78,221,0.15);
                      border:1px solid var(--border); border-radius:100px; padding:.4rem 1.2rem;
                      font-size:.8rem; font-family:'Space Grotesk',sans-serif; color:var(--secondary); margin-bottom:2rem;
                      letter-spacing:.08em; }
        .hero h1 { font-family:'Syne',sans-serif; font-size:clamp(2.5rem,6vw,5rem); font-weight:800;
                   line-height:1.1; max-width:900px; margin-bottom:1.5rem;
                   background:linear-gradient(135deg,#fff 0%,var(--secondary) 50%,var(--primary) 100%);
                   -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .hero p { color:var(--muted); font-size:1.15rem; max-width:600px; line-height:1.7; margin-bottom:2.5rem; }
        .hero-btns { display:flex; gap:1rem; flex-wrap:wrap; justify-content:center; margin-bottom:4rem; }
        .btn-primary { background:linear-gradient(135deg,var(--primary),#6a1eb0); color:#fff;
                       padding:.85rem 2rem; border-radius:10px; font-weight:600; font-family:'Space Grotesk',sans-serif;
                       text-decoration:none; box-shadow:var(--glow); transition:all .2s; border:none; cursor:pointer; font-size:1rem; }
        .btn-primary:hover { transform:translateY(-3px); box-shadow:0 0 40px rgba(157,78,221,0.65); }
        .btn-secondary { background:transparent; color:var(--secondary); padding:.85rem 2rem;
                         border-radius:10px; font-weight:600; font-family:'Space Grotesk',sans-serif;
                         text-decoration:none; border:1px solid var(--border); transition:all .2s; font-size:1rem; }
        .btn-secondary:hover { background:rgba(157,78,221,0.1); border-color:var(--primary); }

        /* FEATURE CARDS */
        .feature-cards { display:flex; gap:1.5rem; flex-wrap:wrap; justify-content:center; max-width:900px; }
        .card { background:var(--surface); border:1px solid var(--border); border-radius:16px;
                padding:1.5rem 2rem; backdrop-filter:blur(16px); text-align:center; min-width:220px; flex:1; }
        .card-icon { font-size:2rem; margin-bottom:.75rem; }
        .card h3 { font-family:'Space Grotesk',sans-serif; font-weight:600; color:var(--accent); margin-bottom:.4rem; }
        .card p { color:var(--muted); font-size:.85rem; line-height:1.5; }

        /* SECTION */
        section { position:relative; z-index:1; padding:5rem 2rem; max-width:1200px; margin:0 auto; }
        .section-title { font-family:'Syne',sans-serif; font-size:clamp(2rem,4vw,3rem); font-weight:800;
                         text-align:center; margin-bottom:.75rem;
                         background:linear-gradient(135deg,var(--text),var(--secondary));
                         -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .section-sub { color:var(--muted); text-align:center; margin-bottom:3rem; font-size:1rem; }
        .badge-pill { display:inline-block; background:rgba(157,78,221,0.15); border:1px solid var(--border);
                      border-radius:100px; padding:.3rem 1rem; font-size:.75rem; font-family:'Space Grotesk',sans-serif;
                      color:var(--secondary); letter-spacing:.08em; margin-bottom:1rem; }

        /* PRICING */
        .pricing-toggle { display:flex; align-items:center; justify-content:center; gap:1rem; margin-bottom:2.5rem; }
        .toggle-label { font-family:'Space Grotesk',sans-serif; color:var(--muted); font-size:.9rem; }
        .toggle-label.active { color:var(--text); font-weight:600; }
        .save-badge { background:linear-gradient(135deg,var(--primary),var(--secondary)); color:#fff;
                      font-size:.7rem; padding:.2rem .6rem; border-radius:100px; font-family:'Space Grotesk',sans-serif; }
        .pricing-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1.5rem; }
        .plan-card { background:var(--surface); border:1px solid var(--border); border-radius:20px;
                     padding:2rem; backdrop-filter:blur(16px); position:relative; transition:transform .2s; }
        .plan-card:hover { transform:translateY(-4px); }
        .plan-card.featured { border-color:var(--primary); box-shadow:0 0 40px rgba(157,78,221,0.25),0 0 0 1px rgba(157,78,221,0.4); }
        .popular-badge { position:absolute; top:-12px; left:50%; transform:translateX(-50%);
                         background:linear-gradient(135deg,var(--primary),var(--secondary)); color:#fff;
                         font-size:.7rem; padding:.35rem 1.2rem; border-radius:100px;
                         font-family:'Space Grotesk',sans-serif; font-weight:600; letter-spacing:.05em; white-space:nowrap; }
        .plan-icon { font-size:2.5rem; margin-bottom:1rem; }
        .plan-name { font-family:'Syne',sans-serif; font-size:1.4rem; font-weight:800; margin-bottom:.25rem; }
        .plan-price { font-size:2.5rem; font-weight:800; font-family:'Syne',sans-serif;
                      background:linear-gradient(135deg,var(--text),var(--secondary));
                      -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .plan-price span { font-size:1rem; color:var(--muted); font-family:'Inter',sans-serif; }
        .plan-sub { color:var(--muted); font-size:.85rem; margin:.5rem 0 1.5rem; }
        .plan-features { list-style:none; margin-bottom:2rem; }
        .plan-features li { display:flex; align-items:flex-start; gap:.6rem; padding:.4rem 0;
                            font-size:.875rem; color:var(--muted); border-bottom:1px solid rgba(255,255,255,0.04); }
        .plan-features li:last-child { border-bottom:none; }
        .check { color:var(--primary); font-size:1rem; flex-shrink:0; }
        .cross { color:#4a4a5a; font-size:1rem; flex-shrink:0; }
        .feat-on { color:var(--text); }
        .plan-btn { display:block; text-align:center; padding:.85rem; border-radius:10px;
                    font-family:'Space Grotesk',sans-serif; font-weight:600; text-decoration:none;
                    transition:all .2s; font-size:.95rem; cursor:pointer; border:none; width:100%; }
        .plan-btn-outline { background:transparent; color:var(--primary); border:1px solid var(--border); }
        .plan-btn-outline:hover { background:rgba(157,78,221,0.1); border-color:var(--primary); }
        .plan-btn-fill { background:linear-gradient(135deg,var(--primary),#6a1eb0); color:#fff; box-shadow:var(--glow); }
        .plan-btn-fill:hover { box-shadow:0 0 40px rgba(157,78,221,0.65); transform:translateY(-2px); }
        .plan-btn-premium { background:linear-gradient(135deg,#6a1eb0,var(--secondary)); color:#fff; box-shadow:0 0 24px rgba(199,125,255,0.4); }
        .plan-btn-premium:hover { box-shadow:0 0 40px rgba(199,125,255,0.65); transform:translateY(-2px); }

        /* TRUST BAR */
        .trust-bar { display:flex; gap:2rem; flex-wrap:wrap; justify-content:center; padding:1.5rem 2rem;
                     background:var(--surface); border:1px solid var(--border); border-radius:16px;
                     backdrop-filter:blur(16px); margin-top:2.5rem; }
        .trust-item { display:flex; align-items:center; gap:.5rem; font-size:.85rem;
                      font-family:'Space Grotesk',sans-serif; color:var(--muted); }
        .trust-item span { font-size:1.1rem; }

        /* CONTACT FORM */
        .contact-form { max-width:500px; margin:0 auto; background:var(--surface); padding:2rem; border-radius:16px; border:1px solid var(--border); }
        .input-group { margin-bottom:1REM; text-align:left; }
        .input-group label { display:block; color:var(--accent); margin-bottom:0.5rem; font-family:'Space Grotesk'; }
        .input-group input, .input-group textarea { width:100%; padding:0.8rem; background:rgba(0,0,0,0.3); border:1px solid var(--border); border-radius:10px; color:var(--text); font-family:'Inter'; }

        /* FOOTER */
        footer { position:relative; z-index:1; text-align:center; padding:2rem;
                 border-top:1px solid var(--border); color:var(--muted); font-size:.85rem; }
        footer a { color:var(--muted); text-decoration:none; margin:0 .75rem; }
        footer a:hover { color:var(--text); }
        .footer-logo { font-family:'Syne',sans-serif; font-weight:800; font-size:1rem;
                       background:linear-gradient(135deg,var(--accent),var(--primary));
                       -webkit-background-clip:text; -webkit-text-fill-color:transparent; margin-bottom:.5rem; }

        @media(max-width:768px) {
            nav { position: relative; flex-direction: column; padding: 1.5rem 1rem; gap: 1rem; }
            .nav-links { display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; margin: 0; }
            .nav-cta { width: 100%; text-align: center; }
            .hero { padding-top: 4rem; }
            .hero h1 { font-size: 2.5rem; }
            .pricing-grid { grid-template-columns:1fr; }
            .feature-cards { flex-direction: column; }
        }
    </style>
</head>
<body>

<!-- NAV -->
<nav>
    <a href="/" class="nav-logo">
        <img src="/images/logo-real.png" alt="Logo" style="height:36px; object-fit:contain;">
        <span>DARK AMETHYST STUDIO</span>
    </a>
    <ul class="nav-links">
        <li><a href="#servicios">Servicios</a></li>
        <li><a href="#plataforma">Plataforma</a></li>
        <li><a href="#precios">Precios</a></li>
        <li><a href="#contacto">Contacto</a></li>
    </ul>
    <a href="/login" class="nav-cta">Ver Demo</a>
</nav>

<!-- HERO -->
<div class="hero" id="servicios">
    <div class="hero-badge">✨ Software Studio &amp; SaaS White-Label</div>
    <h1>Lanzamos tu Academia Digital<br>en Minutos</h1>
    <p>Plataforma SaaS white-label para academias deportivas y de formación. Con pagos, gestión de alumnos por categoría y subdominio propio — listo en un clic.</p>
    <div class="hero-btns">
        <a href="#precios" class="btn-primary">🚀 Crear Mi Academia Ahora</a>
        <a href="#plataforma" class="btn-secondary">Ver Demo en Vivo →</a>
    </div>
    <div class="feature-cards">
        <div class="card">
            <div class="card-icon">🎨</div>
            <h3>100% Personalizable</h3>
            <p>Logo, colores, fotos y textos. Tu academia, tu identidad.</p>
        </div>
        <div class="card">
            <div class="card-icon">💳</div>
            <h3>Pagos con Stripe</h3>
            <p>Cobra mensualidades online. Monedero digital incluido.</p>
        </div>
        <div class="card">
            <div class="card-icon">🔒</div>
            <h3>SSL + Seguridad</h3>
            <p>Cifrado enterprise, RBAC y protección de datos incluida.</p>
        </div>
    </div>
</div>

<!-- PLATAFORMA -->
<section id="plataforma" style="text-align:center; padding:5rem 2rem;">
    <div class="badge-pill">✨ POTENCIA DATA OPS</div>
    <h2 class="section-title">Conoce la Plataforma</h2>
    <p class="section-sub">Estructura multi-tenant con paneles de administración avanzados y notificaciones push.</p>
    <div style="background:var(--surface); border:1px solid var(--border); border-radius:16px; padding:3rem 2rem; max-width: 650px; margin: 0 auto;">
        <h3 style="font-family:'Space Grotesk',sans-serif; color:var(--text); margin-bottom:0.75rem; font-size:1.5rem;">Demo Interactiva Disponible</h3>
        <p style="color:var(--muted); margin-bottom:1.5rem;">Prueba la academia de demostración en tiempo real con todas las funciones Premium desbloqueadas:</p>
        <div style="background: rgba(0,0,0,0.4); border: 1px solid rgba(157,78,221,0.3); border-radius: 12px; padding: 1.2rem; margin-bottom: 1.5rem; text-align: left; font-size: 0.9rem;">
            <div style="margin-bottom: 0.8rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 0.5rem;">
                <span>👑 <strong>Panel Admin:</strong> <code style="color:var(--accent);">demo@academiahe5.com</code></span>
                <span>Pass: <code style="color:#FFD700;">password</code></span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>📱 <strong>App Tutores:</strong> <code style="color:var(--accent);">carlos.tutor@ejemplo.com</code></span>
                <span>Pass: <code style="color:#FFD700;">123456</code></span>
            </div>
        </div>
        <a href="https://demo.academiahe5.com/login" class="btn-primary" style="display:inline-block; padding: 0.8rem 2rem;">Acceder a la Demo en Vivo →</a>
    </div>
</section>

<!-- PRICING -->
<section id="precios">
    <div style="text-align:center">
        <div class="badge-pill">✨ PLANES TRANSPARENTES</div>
    </div>
    <h2 class="section-title">Planes para tu Academia</h2>
    <p class="section-sub">Todo lo que necesitas para gestionar tu academia profesionalmente. Sin contratos. Cancela cuando quieras.</p>

    <div class="pricing-toggle">
        <span class="toggle-label active">Mensual</span>
        <span class="toggle-label">Anual <span class="save-badge">Ahorra 20%</span></span>
    </div>

    <div class="pricing-grid">
        <!-- BÁSICO -->
        <div class="plan-card">
            <div class="plan-icon">🛡️</div>
            <div class="plan-name">Básico</div>
            <div class="plan-price">$399<span>/mes MXN</span></div>
            <div class="plan-sub">Para iniciar tu academia digital</div>
            <ul class="plan-features">
                <li><span class="check">✓</span><span class="feat-on">Hasta 3 categorías activas</span></li>
                <li><span class="check">✓</span><span class="feat-on">Hasta 40 jugadores registrados</span></li>
                <li><span class="check">✓</span><span class="feat-on">Portal de padres y tutores</span></li>
                <li><span class="check">✓</span><span class="feat-on">Cobros manuales (efectivo/transferencia)</span></li>
                <li><span class="check">✓</span><span class="feat-on">Subdominio: tunombre.academiahe5.com</span></li>
                <li><span class="check">✓</span><span class="feat-on">1 admin de la academia</span></li>
                <li><span class="check">✓</span><span class="feat-on">Soporte por email</span></li>
                <li><span class="cross">✗</span>Pagos online con Stripe</li>
                <li><span class="cross">✗</span>Monedero digital</li>
                <li><span class="cross">✗</span>Gestión de partidos y fixture</li>
                <li><span class="cross">✗</span>Reportes PDF</li>
            </ul>
            <a href="/registro?plan=basico" class="plan-btn plan-btn-outline">Empezar con Básico</a>
        </div>

        <!-- ELITE -->
        <div class="plan-card featured">
            <div class="popular-badge">⭐ MÁS POPULAR</div>
            <div class="plan-icon">👑</div>
            <div class="plan-name">Elite</div>
            <div class="plan-price">$799<span>/mes MXN</span></div>
            <div class="plan-sub">La experiencia completa de academia</div>
            <ul class="plan-features">
                <li><span class="check">✓</span><span class="feat-on">Hasta 6 categorías activas</span></li>
                <li><span class="check">✓</span><span class="feat-on">Hasta 150 jugadores registrados</span></li>
                <li><span class="check">✓</span><span class="feat-on">Portal de padres y tutores</span></li>
                <li><span class="check">✓</span><span class="feat-on">Cobros manuales + Pagos con Stripe</span></li>
                <li><span class="check">✓</span><span class="feat-on">Monedero digital (saldo a favor)</span></li>
                <li><span class="check">✓</span><span class="feat-on">Gestión de partidos, fixture y árbitros</span></li>
                <li><span class="check">✓</span><span class="feat-on">Recibos y estados de cuenta en PDF</span></li>
                <li><span class="check">✓</span><span class="feat-on">Importación masiva CSV</span></li>
                <li><span class="check">✓</span><span class="feat-on">2 temporadas activas</span></li>
                <li><span class="check">✓</span><span class="feat-on">2 admins de la academia</span></li>
                <li><span class="check">✓</span><span class="feat-on">Soporte prioritario WhatsApp</span></li>
                <li><span class="cross">✗</span>Dominio propio &amp; personalización total</li>
            </ul>
            <a href="/registro?plan=elite" class="plan-btn plan-btn-fill">Empezar con Elite</a>
        </div>

        <!-- PREMIUM -->
        <div class="plan-card">
            <div class="plan-icon">💎</div>
            <div class="plan-name">Premium</div>
            <div class="plan-price">$1,299<span>/mes MXN</span></div>
            <div class="plan-sub">Máximo poder. Sin límites.</div>
            <ul class="plan-features">
                <li><span class="check">✓</span><span class="feat-on">Categorías ilimitadas</span></li>
                <li><span class="check">✓</span><span class="feat-on">Jugadores ilimitados</span></li>
                <li><span class="check">✓</span><span class="feat-on">Todo lo del plan Elite</span></li>
                <li><span class="check">✓</span><span class="feat-on">Dominio propio (tuacademia.com)</span></li>
                <li><span class="check">✓</span><span class="feat-on">Personalización total: logo, colores, fotos</span></li>
                <li><span class="check">✓</span><span class="feat-on">Notificaciones WhatsApp automatizadas</span></li>
                <li><span class="check">✓</span><span class="feat-on">Temporadas y historial ilimitados</span></li>
                <li><span class="check">✓</span><span class="feat-on">Admins ilimitados</span></li>
                <li><span class="check">✓</span><span class="feat-on">Reportes financieros avanzados</span></li>
                <li><span class="check">✓</span><span class="feat-on">Soporte dedicado 24/7</span></li>
                <li><span class="check">✓</span><span class="feat-on">Acceso anticipado a nuevas funciones</span></li>
            </ul>
            <a href="/registro?plan=premium" class="plan-btn plan-btn-premium">Empezar con Premium</a>
        </div>
    </div>

    <div class="trust-bar">
        <div class="trust-item"><span>💳</span> Powered by Stripe</div>
        <div class="trust-item"><span>🔒</span> SSL 256-bit Incluido</div>
        <div class="trust-item"><span>🇲🇽</span> Soporte en Español</div>
        <div class="trust-item"><span>📅</span> Cancela cuando quieras</div>
        <div class="trust-item"><span>🧪</span> Modo prueba disponible</div>
    </div>
</section>

<!-- CONTACTO -->
<section id="contacto" style="max-width:600px;">
    <div style="text-align:center;">
        <div class="badge-pill">📧 HABLA CON NOSOTROS</div>
    </div>
    <h2 class="section-title">Contacto</h2>
    <p class="section-sub">¿Tienes dudas sobre nuestra plataforma? Estamos aquí para ayudarte.</p>
    <div style="background:var(--surface); padding:2rem; border-radius:16px; border:1px solid var(--border); text-align:left;">
        <div style="margin-bottom:1rem;">
            <label style="display:block; color:var(--accent); margin-bottom:.5rem; font-family:'Space Grotesk',sans-serif;">Nombre</label>
            <input type="text" placeholder="Tu nombre" style="width:100%; padding:.8rem; background:rgba(0,0,0,0.3); border:1px solid var(--border); border-radius:10px; color:var(--text);">
        </div>
        <div style="margin-bottom:1rem;">
            <label style="display:block; color:var(--accent); margin-bottom:.5rem; font-family:'Space Grotesk',sans-serif;">Correo</label>
            <input type="email" placeholder="correo@ejemplo.com" style="width:100%; padding:.8rem; background:rgba(0,0,0,0.3); border:1px solid var(--border); border-radius:10px; color:var(--text);">
        </div>
        <div style="margin-bottom:1.5rem;">
            <label style="display:block; color:var(--accent); margin-bottom:.5rem; font-family:'Space Grotesk',sans-serif;">Mensaje</label>
            <textarea rows="4" placeholder="¿En qué podemos ayudarte?" style="width:100%; padding:.8rem; background:rgba(0,0,0,0.3); border:1px solid var(--border); border-radius:10px; color:var(--text);"></textarea>
        </div>
        <button class="btn-primary" style="width:100%;">Enviar Mensaje</button>
    </div>
</section>



<!-- FOOTER -->
<footer>
    <div class="footer-logo" style="display:flex; justify-content:center; align-items:center; gap:0.5rem;">
        <img src="/images/logo-dark-amethyst.jpg" alt="Logo" style="height:24px; object-fit:contain; border-radius: 4px;"> DARK AMETHYST STUDIO
    </div>
    <div style="margin-bottom:.75rem;">
        <a href="{{ route('privacidad') }}">Aviso de Privacidad</a>
        <a href="{{ route('terminos') }}">Términos y Condiciones</a>
        <a href="#precios">Precios</a>
        <a href="#contacto">Contacto</a>
    </div>
    <p>© {{ date('Y') }} Dark Amethyst Studio. Todos los derechos reservados.</p>
</footer>

</body>
</html>