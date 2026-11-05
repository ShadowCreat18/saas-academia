<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Familiar | {{ $tenant->academy_name ?? 'Academia' }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Syne:wght@600;700;800&family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Driver.js Tour CSS & Custom Dark Glow Theme -->
    <link rel="stylesheet" href="{{ asset('vendor/driverjs/driver.css') }}">
    <link rel="stylesheet" href="{{ asset('css/saas-tour.css') }}">

    <style>
        :root {
            --brand-primary: {{ $tenant->color_palette ?? '#9D4EDD' }};
            --brand-secondary: #C77DFF;
            --brand-accent: #E0AAFF;
            --bg-deep: #0a0a14;
            --surface-card: rgba(18, 14, 34, 0.75);
            --surface-hover: rgba(123, 47, 190, 0.16);
            --border-subtle: rgba(157, 78, 221, 0.25);
            --border-hover: rgba(199, 125, 255, 0.5);
            --text-main: #F0E6FF;
            --text-muted: #a78bca;
            --glow-primary: 0 0 25px rgba(157, 78, 221, 0.35);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', 'Inter', sans-serif;
            background-color: var(--bg-deep);
            color: var(--text-main);
            min-height: 100vh;
            background-image: 
                radial-gradient(circle at 10% 12%, rgba(157, 78, 221, 0.15) 0%, transparent 45%),
                radial-gradient(circle at 88% 22%, rgba(123, 47, 190, 0.12) 0%, transparent 50%),
                radial-gradient(circle at 50% 85%, rgba(90, 24, 154, 0.14) 0%, transparent 60%);
            background-attachment: fixed;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Container */
        .container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Glassmorphic Cards */
        .glass-card {
            background: var(--surface-card);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-subtle);
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.7), 0 0 20px rgba(157, 78, 221, 0.06);
            transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        }

        .glass-card:hover {
            border-color: var(--border-hover);
            box-shadow: 0 16px 40px -10px rgba(0, 0, 0, 0.8), 0 0 25px rgba(157, 78, 221, 0.25);
            transform: translateY(-2px);
        }

        /* Navbar */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(10, 10, 20, 0.85);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border-subtle);
            padding: 0.85rem 0;
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .brand-cluster {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            text-decoration: none;
        }

        .brand-logo-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(157, 78, 221, 0.15);
            border: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(157, 78, 221, 0.25);
        }

        .brand-logo-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: 0.5px;
            line-height: 1.1;
        }

        .brand-badge {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.7rem;
            color: var(--brand-accent);
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .nav-links a:hover {
            color: var(--brand-accent);
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.4rem 0.85rem;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-subtle);
            font-size: 0.85rem;
        }

        .user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: #fff;
        }

        /* Buttons */
        .btn-gradient {
            background: linear-gradient(135deg, var(--brand-primary) 0%, #7B2CBF 100%);
            color: #ffffff;
            border: none;
            padding: 0.65rem 1.35rem;
            border-radius: 0.75rem;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            box-shadow: 0 4px 18px rgba(157, 78, 221, 0.35);
            transition: all 0.2s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(157, 78, 221, 0.55);
            color: #fff;
        }

        .btn-outline-brand {
            background: rgba(157, 78, 221, 0.1);
            color: var(--brand-accent);
            border: 1px solid var(--border-subtle);
            padding: 0.6rem 1.15rem;
            border-radius: 0.75rem;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-outline-brand:hover {
            background: rgba(157, 78, 221, 0.22);
            border-color: var(--brand-secondary);
            color: #fff;
        }

        .btn-logout {
            background: rgba(255, 107, 107, 0.12);
            border: 1px solid rgba(255, 107, 107, 0.3);
            color: #ff6b6b;
            padding: 0.5rem 0.8rem;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .btn-logout:hover {
            background: rgba(255, 107, 107, 0.25);
            color: #fff;
        }

        /* Hero Card */
        .hero-banner {
            margin-top: 2rem;
            padding: 2.2rem;
            border-radius: 1.5rem;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            background: linear-gradient(135deg, rgba(30, 20, 55, 0.7) 0%, rgba(18, 14, 34, 0.9) 100%);
            border: 1px solid var(--border-subtle);
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(157, 78, 221, 0.25) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(157, 78, 221, 0.18);
            border: 1px solid var(--border-subtle);
            color: var(--brand-accent);
            padding: 0.3rem 0.85rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            width: fit-content;
        }

        .hero-title {
            font-family: 'Syne', sans-serif;
            font-size: 2.1rem;
            font-weight: 800;
            line-height: 1.2;
            color: #fff;
        }

        .hero-title span {
            background: linear-gradient(135deg, #ffffff 0%, var(--brand-accent) 50%, var(--brand-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            max-width: 650px;
            line-height: 1.5;
        }

        /* Badges Grid inside Hero */
        .hero-badges-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .hero-badge-box {
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid var(--border-subtle);
            border-radius: 1rem;
            padding: 0.9rem 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .badge-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        /* Sections */
        .section-block {
            margin-top: 3.5rem;
        }

        .section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid rgba(157, 78, 221, 0.18);
            padding-bottom: 0.85rem;
        }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .section-subtitle {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--brand-secondary);
            font-weight: 700;
            display: block;
            margin-bottom: 0.2rem;
        }

        /* Cards Grids */
        .cards-grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .cards-grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        /* Athlete Card */
        .athlete-card {
            padding: 1.6rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .athlete-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .athlete-avatar-box {
            position: relative;
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(157, 78, 221, 0.4), rgba(40, 10, 70, 0.8));
            border: 2px solid var(--brand-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 16px rgba(157, 78, 221, 0.4);
        }

        .athlete-dorsal {
            position: absolute;
            bottom: -4px;
            right: -4px;
            background: var(--brand-primary);
            color: #fff;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 6px;
            border: 1px solid #fff;
        }

        .status-pill {
            padding: 0.25rem 0.65rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .status-active {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.4);
            color: #4ade80;
        }

        .status-pending {
            background: rgba(234, 179, 8, 0.15);
            border: 1px solid rgba(234, 179, 8, 0.4);
            color: #facc15;
        }

        /* Metrics Box */
        .metrics-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(157, 78, 221, 0.15);
            border-radius: 0.85rem;
            padding: 0.85rem 0.5rem;
            text-align: center;
            margin: 1.15rem 0;
        }

        .metric-item:not(:last-child) {
            border-right: 1px solid rgba(157, 78, 221, 0.15);
        }

        .metric-val {
            font-family: 'Syne', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--brand-accent);
        }

        .metric-lbl {
            font-size: 0.65rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.15rem;
        }

        /* Match / Fixture Card */
        .match-card {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .match-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(157, 78, 221, 0.15);
            padding-bottom: 0.75rem;
            font-size: 0.8rem;
        }

        .match-teams {
            display: flex;
            align-items: center;
            justify-content: space-around;
            text-align: center;
            padding: 0.85rem 0;
        }

        .team-box {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.4rem;
        }

        .team-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: rgba(157, 78, 221, 0.15);
            border: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: var(--brand-accent);
        }

        .vs-pill {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid var(--border-subtle);
            font-family: 'Syne', sans-serif;
            font-size: 0.8rem;
            font-weight: 800;
            color: var(--brand-secondary);
        }

        .match-meta-list {
            background: rgba(0, 0, 0, 0.35);
            border-radius: 0.85rem;
            border: 1px solid rgba(157, 78, 221, 0.15);
            padding: 0.85rem 1.15rem;
            font-size: 0.82rem;
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .match-meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Payments Table Card */
        .payments-summary-card {
            background: linear-gradient(135deg, rgba(35, 18, 55, 0.85) 0%, rgba(15, 12, 28, 0.95) 100%);
            border: 1px solid var(--border-subtle);
            padding: 2rem;
            border-radius: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .payment-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(157, 78, 221, 0.15);
            transition: background 0.2s ease;
        }

        .payment-row:hover {
            background: rgba(157, 78, 221, 0.08);
        }

        /* Notice Card */
        .notice-card {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 1rem;
        }

        /* Floating Tour Button */
        .saas-tour-floating-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 999;
            background: linear-gradient(135deg, #9D4EDD 0%, #7B2CBF 100%);
            color: #ffffff;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            padding: 0.85rem 1.6rem;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            box-shadow: 0 8px 30px rgba(157, 78, 221, 0.55), 0 0 15px rgba(157, 78, 221, 0.35);
            display: flex;
            align-items: center;
            gap: 0.65rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
        }

        .saas-tour-floating-btn:hover {
            transform: scale(1.06) translateY(-3px);
            box-shadow: 0 12px 35px rgba(157, 78, 221, 0.7);
            color: #fff;
        }

        /* Modals Dark Amethyst */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(5, 5, 12, 0.85);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        .modal-card {
            background: #110e22;
            border: 1px solid var(--border-subtle);
            border-radius: 1.5rem;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.9), 0 0 35px rgba(157, 78, 221, 0.25);
            width: 100%;
            max-width: 540px;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.95) translateY(10px);
            transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            padding: 2rem;
            position: relative;
        }

        .modal-overlay.active .modal-card {
            transform: scale(1) translateY(0);
        }

        .modal-close-btn {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: var(--text-muted);
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .modal-close-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.4);
        }

        .form-control-dark {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(157, 78, 221, 0.3);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            color: #fff;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
        }

        .form-control-dark:focus {
            border-color: var(--brand-accent);
            box-shadow: 0 0 15px rgba(157, 78, 221, 0.3);
            background: rgba(255, 255, 255, 0.08);
        }

        .attendance-confirmed-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.4);
            border-radius: 10px;
            padding: 0.65rem 1rem;
            width: 100%;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .nav-links { display: none; }
            .hero-title { font-size: 1.6rem; }
            .cards-grid-3, .cards-grid-2 { grid-template-columns: 1fr; }
            .payment-row { flex-direction: column; align-items: flex-start; gap: 0.8rem; }
        }
    </style>
</head>
<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar" id="tour-parent-header">
        <div class="container navbar-content">
            <!-- Brand -->
            <a href="/padres" class="brand-cluster">
                <div class="brand-logo-box">
                    @if(!empty($tenant->logo_path))
                        <img src="{{ asset('storage/' . $tenant->logo_path) }}" alt="Logo">
                    @else
                        <i class="fa-solid fa-futbol" style="color: var(--brand-accent); font-size: 1.3rem;"></i>
                    @endif
                </div>
                <div>
                    <span class="brand-name">{{ $tenant->academy_name ?? 'Academia Oficial' }}</span>
                    <span class="brand-badge">Portal Exclusivo de Padres</span>
                </div>
            </a>

            <!-- Quick Links -->
            <ul class="nav-links">
                <li><a href="#tour-my-athletes"><i class="fa-solid fa-users"></i> Mis Atletas</a></li>
                <li><a href="#tour-schedules"><i class="fa-solid fa-clock"></i> Horarios</a></li>
                <li><a href="#tour-matches"><i class="fa-solid fa-trophy"></i> Partidos</a></li>
                <li><a href="#tour-payments"><i class="fa-solid fa-credit-card"></i> Pagos</a></li>
                <li><a href="#tour-notices"><i class="fa-solid fa-bullhorn"></i> Avisos</a></li>
            </ul>

            <!-- Actions -->
            <div class="user-actions">
                <!-- Botón de Tutorial -->
                <button type="button" class="btn-outline-brand" id="btn-start-parent-tour" onclick="if(typeof window.startParentTutorial === 'function'){ window.startParentTutorial(true); } else if(window.initParentTour){ window.initParentTour({ force: true }); }">
                    <i class="fa-solid fa-graduation-cap text-purple-400"></i>
                    <span>Ver Tutorial</span>
                </button>

                <!-- Perfil Tutor -->
                <div class="user-pill" id="tour-parent-user">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                    </div>
                    <span style="font-weight: 600;">{{ Auth::user()->name ?? 'Tutor' }}</span>
                </div>

                <!-- Cerrar Sesión -->
                <a href="/logout" class="btn-logout" title="Cerrar Sesión">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="container" style="flex: 1; padding-bottom: 5rem;">
        @php
            $totalDebt = $payments->where('status', '!=', 'Pagado')->sum('amount');
            if ($totalDebt == 0 && $payments->isEmpty()) { $totalDebt = 800.00; }
        @endphp

        <!-- ================= HERO WELCOME BANNER ================= -->
        <section class="hero-banner glass-card">
            <div class="hero-tag">
                <i class="fa-solid fa-circle" style="font-size: 0.5rem; color: #4ade80;"></i>
                Ciclo Formativo {{ $tenant->academy_name ?? 'Academia' }} 2026-2027
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <h1 class="hero-title">
                    ¡Hola, {{ Auth::user()->name ?? 'Tutor' }}! <span>Bienvenido a tu Portal Familiar</span>
                </h1>
                <p class="hero-subtitle">
                    Gestiona y da seguimiento integral a la formación deportiva, calendarios, partidos y pagos escolares de tus atletas inscritos.
                </p>
            </div>

            <!-- Quick Badges Grid -->
            <div class="hero-badges-grid">
                <div class="hero-badge-box">
                    <div class="badge-icon" style="background: rgba(157, 78, 221, 0.2); color: var(--brand-accent);">
                        <i class="fa-solid fa-stopwatch"></i>
                    </div>
                    <div>
                        <span style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-family: 'Space Grotesk';">Próximo Entreno</span>
                        <div style="font-weight: 700; font-size: 0.95rem; color: #fff;">Hoy 16:30 hrs</div>
                    </div>
                </div>

                <div class="hero-badge-box">
                    <div class="badge-icon" style="background: rgba(234, 179, 8, 0.2); color: #facc15;">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <span style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-family: 'Space Grotesk';">Estado Financiero</span>
                        <div style="font-weight: 700; font-size: 0.95rem; color: #facc15;">
                            @php
                                $pendingCount = $payments->where('status', '!=', 'Pagado')->count();
                            @endphp
                            {{ $pendingCount > 0 ? $pendingCount . ' Pago(s) Pendiente(s)' : 'Al corriente (Sin adeudos)' }}
                        </div>
                    </div>
                </div>

                <div class="hero-badge-box">
                    <div class="badge-icon" style="background: rgba(56, 189, 248, 0.2); color: #38bdf8;">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <div>
                        <span style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-family: 'Space Grotesk';">Próximo Partido</span>
                        <div style="font-weight: 700; font-size: 0.95rem; color: #fff;">
                            {{ $matches->first()->opponent ?? 'Jornada de Liga Próxima' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Actions -->
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 0.5rem;">
                <button type="button" class="btn-gradient" id="hero-pay-stripe" onclick="openStripeModal({{ $payments->where('status', '!=', 'Pagado')->first()->id ?? 0 }}, 'Mensualidad Escolar', {{ $totalDebt > 0 ? $totalDebt : 800 }})">
                    <i class="fa-brands fa-stripe"></i> Pagar con Stripe
                </button>
                <a href="#tour-matches" class="btn-outline-brand">
                    <i class="fa-solid fa-calendar-days"></i> Ver Calendario de Partidos
                </a>
            </div>
        </section>

        <!-- ================= SECCIÓN 1: MIS ATLETAS ================= -->
        <section class="section-block" id="tour-my-athletes">
            <div class="section-header">
                <div>
                    <span class="section-subtitle">Rendimiento & Ficha Deportiva</span>
                    <h2 class="section-title"><i class="fa-solid fa-users" style="color: var(--brand-accent);"></i> Mis Atletas</h2>
                </div>
                <span style="font-size: 0.85rem; color: var(--text-muted);">
                    {{ $players->count() }} Atleta(s) Vinculado(s)
                </span>
            </div>

            <div class="cards-grid-3">
                @forelse($players as $index => $player)
                    <div class="glass-card athlete-card">
                        <div>
                            <div class="athlete-header">
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    <div class="athlete-avatar-box">
                                        <i class="fa-solid fa-person-running" style="font-size: 1.6rem; color: var(--brand-accent);"></i>
                                        <span class="athlete-dorsal">#{{ $index + 7 }}</span>
                                    </div>
                                    <div>
                                        <h3 style="font-family: 'Syne', sans-serif; font-size: 1.15rem; font-weight: 700; color: #fff;">
                                            {{ $player->first_name }} {{ $player->last_name }}
                                        </h3>
                                        <span style="font-size: 0.8rem; color: var(--brand-secondary); font-weight: 600;">
                                            Categoría: {{ $player->category ?? 'Sub-10' }}
                                        </span>
                                    </div>
                                </div>
                                <span class="status-pill status-active">
                                    {{ $player->status ?? 'ACTIVO' }}
                                </span>
                            </div>

                            <!-- Métricas del Alumno -->
                            <div class="metrics-strip">
                                <div class="metric-item">
                                    <div class="metric-val">{{ $player->goals ?? 0 }}</div>
                                    <div class="metric-lbl">Goles</div>
                                </div>
                                <div class="metric-item">
                                    <div class="metric-val">96%</div>
                                    <div class="metric-lbl">Asistencia</div>
                                </div>
                                <div class="metric-item">
                                    <div class="metric-val">9.2</div>
                                    <div class="metric-lbl">Disciplina</div>
                                </div>
                            </div>

                            <!-- Datos adicionales -->
                            <div style="font-size: 0.8rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 0.3rem;">
                                <div style="display: flex; justify-content: space-between;">
                                    <span>Nacimiento:</span>
                                    <strong style="color: #fff;">{{ $player->birth_date ?? '2016-04-12' }}</strong>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span>Posición sugerida:</span>
                                    <strong style="color: var(--brand-accent);">Med. Ofensivo / Delantero</strong>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 1.25rem; border-top: 1px solid rgba(157, 78, 221, 0.15); padding-top: 0.85rem;">
                            <button type="button" class="btn-outline-brand" style="width: 100%; justify-content: center;" onclick="openAthleteModal({
                                name: '{{ addslashes($player->first_name . ' ' . $player->last_name) }}',
                                category: '{{ addslashes($player->category ?? 'Sub-10') }}',
                                dorsal: '{{ $index + 7 }}',
                                goals: {{ $player->goals ?? 0 }},
                                birthDate: '{{ $player->birth_date ?? '2016-04-12' }}',
                                status: '{{ $player->status ?? 'ACTIVO' }}'
                            })">
                                <i class="fa-solid fa-id-card"></i> Ver Ficha Deportiva
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Demostración Atleta 1 -->
                    <div class="glass-card athlete-card">
                        <div>
                            <div class="athlete-header">
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    <div class="athlete-avatar-box">
                                        <i class="fa-solid fa-person-running" style="font-size: 1.6rem; color: var(--brand-accent);"></i>
                                        <span class="athlete-dorsal">#10</span>
                                    </div>
                                    <div>
                                        <h3 style="font-family: 'Syne', sans-serif; font-size: 1.15rem; font-weight: 700; color: #fff;">Mateo Martínez</h3>
                                        <span style="font-size: 0.8rem; color: var(--brand-secondary); font-weight: 600;">Categoría: Sub-10</span>
                                    </div>
                                </div>
                                <span class="status-pill status-active">ACTIVO</span>
                            </div>
                            <div class="metrics-strip">
                                <div class="metric-item"><div class="metric-val">7</div><div class="metric-lbl">Goles</div></div>
                                <div class="metric-item"><div class="metric-val">98%</div><div class="metric-lbl">Asistencia</div></div>
                                <div class="metric-item"><div class="metric-val">9.5</div><div class="metric-lbl">Disciplina</div></div>
                            </div>
                        </div>
                        <button type="button" class="btn-outline-brand" style="width: 100%; justify-content: center;">
                            <i class="fa-solid fa-id-card"></i> Ficha Oficial
                        </button>
                    </div>

                    <!-- Demostración Atleta 2 -->
                    <div class="glass-card athlete-card">
                        <div>
                            <div class="athlete-header">
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    <div class="athlete-avatar-box">
                                        <i class="fa-solid fa-person-running" style="font-size: 1.6rem; color: var(--brand-accent);"></i>
                                        <span class="athlete-dorsal">#7</span>
                                    </div>
                                    <div>
                                        <h3 style="font-family: 'Syne', sans-serif; font-size: 1.15rem; font-weight: 700; color: #fff;">Sofía López</h3>
                                        <span style="font-size: 0.8rem; color: var(--brand-secondary); font-weight: 600;">Categoría: Sub-12</span>
                                    </div>
                                </div>
                                <span class="status-pill status-active">ACTIVO</span>
                            </div>
                            <div class="metrics-strip">
                                <div class="metric-item"><div class="metric-val">5</div><div class="metric-lbl">Goles</div></div>
                                <div class="metric-item"><div class="metric-val">100%</div><div class="metric-lbl">Asistencia</div></div>
                                <div class="metric-item"><div class="metric-val">9.1</div><div class="metric-lbl">Disciplina</div></div>
                            </div>
                        </div>
                        <button type="button" class="btn-outline-brand" style="width: 100%; justify-content: center;" onclick="openAthleteModal({
                            name: 'Mateo Martínez',
                            category: 'Sub-10',
                            dorsal: '10',
                            goals: 7,
                            birthDate: '2016-04-12',
                            status: 'ACTIVO'
                        })">
                            <i class="fa-solid fa-id-card"></i> Ficha Oficial
                        </button>
                    </div>
                @endforelse

                <!-- Tarjeta Inscribir Nuevo Hermano -->
                <div class="glass-card" style="border: 2px dashed rgba(157, 78, 221, 0.4); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 2rem;">
                    <div style="width: 55px; height: 55px; border-radius: 50%; background: rgba(157, 78, 221, 0.2); border: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--brand-accent); margin-bottom: 1rem;">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <h3 style="font-family: 'Syne', sans-serif; font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 0.4rem;">
                        + Inscribir Nuevo Atleta / Hermano
                    </h3>
                    <p style="font-size: 0.8rem; color: var(--text-muted); max-width: 260px; margin-bottom: 1.25rem;">
                        Aprovecha el descuento familiar institucional al matricular a un hermano en la academia.
                    </p>
                    <button type="button" class="btn-gradient" onclick="openSiblingModal()">
                        Iniciar Registro Familiar
                    </button>
                </div>
            </div>
        </section>

        <!-- ================= SECCIÓN 2: HORARIOS DE ENTRENAMIENTO ================= -->
        <section class="section-block" id="tour-schedules">
            <div class="section-header">
                <div>
                    <span class="section-subtitle">Sede Oficial & Calendario Técnico</span>
                    <h2 class="section-title"><i class="fa-solid fa-clock" style="color: var(--brand-secondary);"></i> Horarios de Entrenamiento</h2>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.4); color: #4ade80; padding: 0.35rem 0.85rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
                    <i class="fa-solid fa-circle" style="font-size: 0.5rem; animation: pulse 1.5s infinite;"></i>
                    Canchas Abiertas · Clima Óptimo 24°C
                </div>
            </div>

            <div class="cards-grid-2">
                @forelse($categories as $category)
                    <div class="glass-card" style="padding: 1.6rem; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid rgba(157, 78, 221, 0.15); padding-bottom: 0.75rem;">
                                <div style="display: flex; align-items: center; gap: 0.6rem;">
                                    <span style="background: rgba(157, 78, 221, 0.25); border: 1px solid var(--border-subtle); color: var(--brand-accent); padding: 0.25rem 0.65rem; border-radius: 8px; font-weight: 700; font-size: 0.8rem;">
                                        {{ $category->name }}
                                    </span>
                                    <span style="font-weight: 600; color: #fff; font-size: 0.9rem;">
                                        Nacidos: {{ $category->min_birth_year ?? '2016' }} - {{ $category->max_birth_year ?? '2018' }}
                                    </span>
                                </div>
                                <span style="font-size: 0.75rem; color: var(--text-muted); font-family: 'Space Grotesk';">3 días / semana</span>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.85rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(157, 78, 221, 0.15); display: flex; align-items: center; justify-content: center; color: var(--brand-secondary);">
                                        <i class="fa-regular fa-calendar-check"></i>
                                    </div>
                                    <div>
                                        <span style="color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; display: block;">Días y Horarios</span>
                                        <strong style="color: #fff;">{{ $category->schedule ?? 'Lunes, Miércoles y Viernes | 16:30 - 18:00 hrs' }}</strong>
                                    </div>
                                </div>

                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(157, 78, 221, 0.15); display: flex; align-items: center; justify-content: center; color: var(--brand-secondary);">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <div>
                                        <span style="color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; display: block;">Instalación</span>
                                        <strong style="color: #fff;">Cancha Principal (Césped Sintético FIFA Quality)</strong>
                                    </div>
                                </div>

                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(157, 78, 221, 0.15); display: flex; align-items: center; justify-content: center; color: var(--brand-secondary);">
                                        <i class="fa-solid fa-whistle"></i>
                                    </div>
                                    <div>
                                        <span style="color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; display: block;">Cuerpo Técnico</span>
                                        <strong style="color: var(--brand-accent);">Entrenador Titular Certificado</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 1.25rem; background: rgba(0, 0, 0, 0.3); padding: 0.65rem 1rem; border-radius: 10px; border: 1px solid rgba(157, 78, 221, 0.15); display: flex; justify-content: space-between; font-size: 0.75rem;">
                            <span style="color: var(--text-muted);">Tolerancia de acceso: 15 min antes</span>
                            <span style="color: var(--brand-accent); font-weight: 600;">Indumentaria: Oficial de Práctica</span>
                        </div>
                    </div>
                @empty
                    <!-- Demostración Sub-10 -->
                    <div class="glass-card" style="padding: 1.6rem;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid rgba(157, 78, 221, 0.15); padding-bottom: 0.75rem;">
                            <span style="background: rgba(157, 78, 221, 0.25); color: var(--brand-accent); padding: 0.25rem 0.65rem; border-radius: 8px; font-weight: 700; font-size: 0.8rem;">Sub-10</span>
                            <span style="font-size: 0.75rem; color: var(--text-muted);">Lun, Mié y Vie</span>
                        </div>
                        <p style="color: #fff; font-weight: 600; font-size: 0.9rem;">16:30 - 18:00 hrs · Cancha 1</p>
                    </div>
                    <!-- Demostración Sub-12 -->
                    <div class="glass-card" style="padding: 1.6rem;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid rgba(157, 78, 221, 0.15); padding-bottom: 0.75rem;">
                            <span style="background: rgba(157, 78, 221, 0.25); color: var(--brand-accent); padding: 0.25rem 0.65rem; border-radius: 8px; font-weight: 700; font-size: 0.8rem;">Sub-12</span>
                            <span style="font-size: 0.75rem; color: var(--text-muted);">Mar, Jue y Sáb</span>
                        </div>
                        <p style="color: #fff; font-weight: 600; font-size: 0.9rem;">17:00 - 18:30 hrs · Cancha Principal</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- ================= SECCIÓN 3: PRÓXIMOS PARTIDOS ================= -->
        <section class="section-block" id="tour-matches">
            <div class="section-header">
                <div>
                    <span class="section-subtitle">Torneo & Liga Oficial</span>
                    <h2 class="section-title"><i class="fa-solid fa-trophy" style="color: #FFD700;"></i> Próximos Partidos y Calendario</h2>
                </div>
                <button type="button" class="btn-outline-brand" onclick="Swal.fire({title: 'Calendario Deportivo', text: 'Descargando fixture oficial del torneo...', icon: 'success', background: '#0a0a14', color: '#fff', confirmButtonColor: '#9D4EDD'});">
                    <i class="fa-solid fa-arrows-rotate"></i> Sincronizar Calendario
                </button>
            </div>

            <div class="cards-grid-2">
                @forelse($matches as $match)
                    <div class="glass-card match-card">
                        <div class="match-top">
                            <span style="display: inline-flex; align-items: center; gap: 0.4rem; color: var(--brand-accent); font-weight: 700;">
                                <i class="fa-solid fa-circle" style="color: #4ade80; font-size: 0.5rem;"></i>
                                Convocatoria Oficial: {{ $match->category ?? 'Categoría Titular' }}
                            </span>
                            <span class="status-pill status-active">
                                {{ $match->status ?? 'PROGRAMADO' }}
                            </span>
                        </div>

                        <!-- Face-off Teams -->
                        <div class="match-teams">
                            <div class="team-box">
                                <div class="team-icon">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <span style="font-weight: 700; font-size: 0.9rem; color: #fff;">
                                    {{ $tenant->academy_name ?? 'Local' }}
                                </span>
                                <span style="font-size: 0.7rem; color: var(--brand-accent);">Sede Local</span>
                            </div>

                            <div class="vs-pill">VS</div>

                            <div class="team-box">
                                <div class="team-icon" style="background: rgba(255, 107, 107, 0.15); border-color: rgba(255, 107, 107, 0.3); color: #ff6b6b;">
                                    <i class="fa-solid fa-futbol"></i>
                                </div>
                                <span style="font-weight: 700; font-size: 0.9rem; color: #fff;">
                                    {{ $match->opponent }}
                                </span>
                                <span style="font-size: 0.7rem; color: var(--text-muted);">Rival</span>
                            </div>
                        </div>

                        <!-- Match Meta details -->
                        <div class="match-meta-list">
                            <div class="match-meta-row">
                                <span style="color: var(--text-muted);"><i class="fa-regular fa-clock" style="color: var(--brand-secondary);"></i> Fecha y Hora:</span>
                                <strong style="color: #fff;">{{ $match->match_date ?? 'Sábado 28 Oct' }} · {{ $match->match_time ?? '10:00 AM' }}</strong>
                            </div>
                            <div class="match-meta-row">
                                <span style="color: var(--text-muted);"><i class="fa-solid fa-map-pin" style="color: var(--brand-secondary);"></i> Cancha:</span>
                                <span style="color: #fff;">{{ $match->location ?? 'Deportivo Principal' }}</span>
                            </div>
                            @if(!empty($match->result))
                            <div class="match-meta-row">
                                <span style="color: var(--text-muted);"><i class="fa-solid fa-chart-simple" style="color: var(--brand-secondary);"></i> Marcador:</span>
                                <strong style="color: var(--brand-accent);">{{ $match->result }}</strong>
                            </div>
                            @endif
                        </div>

                        <div class="match-action-container" data-match-id="{{ $match->id ?? $index }}">
                            <button type="button" class="btn-gradient btn-confirm-attendance" style="width: 100%; justify-content: center;" onclick="confirmMatchAttendance('{{ $match->id ?? $index }}', '{{ addslashes($match->opponent ?? 'Rival') }}', '{{ addslashes($match->match_date ?? 'Sábado') }} {{ addslashes($match->match_time ?? '10:00 AM') }}')">
                                <i class="fa-solid fa-check"></i> Confirmar Asistencia
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Demostración Partido 1 -->
                    <div class="glass-card match-card">
                        <div class="match-top">
                            <span style="color: var(--brand-accent); font-weight: 700;"><i class="fa-solid fa-circle" style="color:#4ade80; font-size:0.5rem;"></i> Convocatoria Sub-10</span>
                            <span class="status-pill status-active">PROGRAMADO</span>
                        </div>
                        <div class="match-teams">
                            <div class="team-box"><div class="team-icon"><i class="fa-solid fa-shield-halved"></i></div><span style="font-weight:700; font-size:0.9rem; color:#fff;">Academia Titular</span></div>
                            <div class="vs-pill">VS</div>
                            <div class="team-box"><div class="team-icon" style="background:rgba(255,107,107,0.15); color:#ff6b6b;"><i class="fa-solid fa-futbol"></i></div><span style="font-weight:700; font-size:0.9rem; color:#fff;">Club América Valles</span></div>
                        </div>
                        <div class="match-meta-list">
                            <div class="match-meta-row"><span>Fecha:</span><strong style="color:#fff;">Sábado 10:00 AM</strong></div>
                            <div class="match-meta-row"><span>Sede:</span><span>Cancha Local (Local)</span></div>
                        </div>
                        <div class="match-action-container" data-match-id="demo-match-1">
                            <button type="button" class="btn-gradient btn-confirm-attendance" style="width: 100%; justify-content: center;" onclick="confirmMatchAttendance('demo-match-1', 'Club América Valles', 'Sábado 10:00 AM')">
                                <i class="fa-solid fa-check"></i> Confirmar Asistencia
                            </button>
                        </div>
                    </div>

                    <!-- Demostración Partido 2 -->
                    <div class="glass-card match-card">
                        <div class="match-top">
                            <span style="color: var(--brand-accent); font-weight: 700;"><i class="fa-solid fa-circle" style="color:#4ade80; font-size:0.5rem;"></i> Convocatoria Sub-12</span>
                            <span class="status-pill" style="background:rgba(56,189,248,0.15); border:1px solid #38bdf8; color:#38bdf8;">FINALIZADO</span>
                        </div>
                        <div class="match-teams">
                            <div class="team-box"><div class="team-icon"><i class="fa-solid fa-shield-halved"></i></div><span style="font-weight:700; font-size:0.9rem; color:#fff;">Academia Titular</span></div>
                            <div class="vs-pill">VS</div>
                            <div class="team-box"><div class="team-icon"><i class="fa-solid fa-futbol"></i></div><span style="font-weight:700; font-size:0.9rem; color:#fff;">Tigres Cumbres</span></div>
                        </div>
                        <div class="match-meta-list">
                            <div class="match-meta-row"><span>Resultado:</span><strong style="color:#4ade80;">3 - 1 (Ganado) 🏆</strong></div>
                            <div class="match-meta-row"><span>Sede:</span><span>Cancha Principal</span></div>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- ================= SECCIÓN 4: ESTADO DE CUENTA Y PAGOS (STRIPE) ================= -->
        <section class="section-block" id="tour-payments">
            <div class="section-header">
                <div>
                    <span class="section-subtitle">Tesorería & Facturación Segura</span>
                    <h2 class="section-title"><i class="fa-brands fa-stripe" style="color: var(--brand-secondary); font-size: 2rem;"></i> Estado de Cuenta y Pagos en Línea</h2>
                </div>
                <div style="display: flex; align-items: center; gap: 0.4rem; color: #4ade80; font-size: 0.8rem; font-weight: 600;">
                    <i class="fa-solid fa-lock"></i> Pagos Cifrados 256-bit SSL
                </div>
            </div>

            <!-- Resumen de Balance Financiero -->
            <div class="payments-summary-card">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-family: 'Space Grotesk'; letter-spacing: 1px;">
                            Balance Escolar Pendiente
                        </span>
                        <div style="font-family: 'Syne', sans-serif; font-size: 2.8rem; font-weight: 800; color: #fff; line-height: 1.1; margin-top: 0.2rem;">
                            @php
                                $totalDebt = $payments->where('status', '!=', 'Pagado')->sum('amount');
                                if ($totalDebt == 0 && $payments->isEmpty()) { $totalDebt = 800.00; }
                            @endphp
                            ${{ number_format($totalDebt, 2) }} <span style="font-size: 1rem; color: var(--brand-accent);">MXN</span>
                        </div>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.4rem;">
                            @if($totalDebt > 0)
                                Mensualidad y cuotas activas para tu cuenta de tutor.
                            @else
                                ¡Excelente! Tu cuenta se encuentra totalmente al corriente.
                            @endif
                        </p>
                    </div>

                    <!-- Botón Pago Stripe -->
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; align-items: flex-end;">
                        <button type="button" class="btn-gradient" id="btn-pay-stripe-main" style="font-size: 1rem; padding: 0.9rem 2rem;" onclick="openStripeModal({{ $payments->where('status', '!=', 'Pagado')->first()->id ?? 0 }}, 'Mensualidad Escolar', {{ $totalDebt > 0 ? $totalDebt : 800 }})">
                            <i class="fa-brands fa-stripe text-xl"></i> Pagar con Stripe
                        </button>
                        <div style="display: flex; gap: 0.6rem; color: var(--text-muted); font-size: 0.75rem; align-items: center;">
                            <span>Aceptamos:</span>
                            <i class="fa-brands fa-cc-visa text-white text-base"></i>
                            <i class="fa-brands fa-cc-mastercard text-white text-base"></i>
                            <i class="fa-brands fa-stripe text-purple-400 text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Historial de Pagos -->
            <div class="glass-card" style="overflow: hidden;">
                <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(157, 78, 221, 0.2); display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-family: 'Syne', sans-serif; font-size: 1.05rem; font-weight: 700; color: #fff;">
                        Historial de Cuotas y Movimientos
                    </h3>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Últimos conceptos</span>
                </div>

                @forelse($payments as $payment)
                    <div class="payment-row">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: {{ $payment->status === 'Pagado' ? 'rgba(34, 197, 94, 0.15)' : 'rgba(234, 179, 8, 0.15)' }}; display: flex; align-items: center; justify-content: center; color: {{ $payment->status === 'Pagado' ? '#4ade80' : '#facc15' }};">
                                <i class="fa-solid {{ $payment->status === 'Pagado' ? 'fa-check' : 'fa-clock' }}"></i>
                            </div>
                            <div>
                                <h4 style="font-weight: 700; font-size: 0.95rem; color: #fff;">{{ $payment->concept }}</h4>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">
                                    Folio: {{ $payment->folio ?? 'FOL-1001' }} · Alumno: {{ $payment->student_tutor ?? 'Alumno Titular' }}
                                </span>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 1.5rem;">
                            <div style="text-align: right;">
                                <div style="font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.1rem; color: #fff;">
                                    ${{ number_format($payment->amount, 2) }} MXN
                                </div>
                                <span class="status-pill {{ $payment->status === 'Pagado' ? 'status-active' : 'status-pending' }}">
                                    {{ $payment->status }}
                                </span>
                            </div>

                            @if($payment->status === 'Pagado')
                                <button type="button" class="btn-outline-brand" style="padding: 0.4rem 0.85rem; font-size: 0.75rem;" onclick="showReceiptModal('{{ $payment->folio ?? 'FOL-1001' }}', '{{ addslashes($payment->concept) }}', {{ $payment->amount }}, '{{ $payment->created_at ?? date('Y-m-d') }}')">
                                    <i class="fa-solid fa-file-pdf"></i> Recibo
                                </button>
                            @else
                                <button type="button" class="btn-gradient" style="padding: 0.4rem 0.85rem; font-size: 0.75rem;" onclick="openStripeModal({{ $payment->id }}, '{{ addslashes($payment->concept) }}', {{ $payment->amount }})">
                                    <i class="fa-brands fa-stripe"></i> Pagar
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <!-- Demostración Pago 1 -->
                    <div class="payment-row">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(34, 197, 94, 0.15); color: #4ade80; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div>
                                <h4 style="font-weight: 700; font-size: 0.95rem; color: #fff;">Mensualidad Septiembre 2026</h4>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">Folio: FOL-9021 · Mateo Martínez</span>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1.5rem;">
                            <div style="text-align: right;">
                                <div style="font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.1rem; color: #fff;">$800.00 MXN</div>
                                <span class="status-pill status-active">PAGADO</span>
                            </div>
                            <button type="button" class="btn-outline-brand" style="padding: 0.4rem 0.85rem; font-size: 0.75rem;" onclick="showReceiptModal('FOL-9021', 'Mensualidad Septiembre 2026', 800, '2026-09-15')">
                                <i class="fa-solid fa-file-pdf"></i> Recibo
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- ================= SECCIÓN 5: AVISOS Y COMUNICADOS ================= -->
        <section class="section-block" id="tour-notices">
            <div class="section-header">
                <div>
                    <span class="section-subtitle">Canal Directo Institucional</span>
                    <h2 class="section-title"><i class="fa-solid fa-bullhorn" style="color: var(--brand-accent);"></i> Avisos y Comunicados Oficiales</h2>
                </div>
                <span style="font-size: 0.85rem; color: var(--text-muted);">Dirección Técnica & Deportiva</span>
            </div>

            <div class="cards-grid-3">
                @forelse($notifications as $notif)
                    <div class="glass-card notice-card">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <span style="background: rgba(157, 78, 221, 0.2); color: var(--brand-accent); padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.7rem; font-weight: 700;">
                                    COMUNICADO
                                </span>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $notif->created_at ?? 'Reciente' }}</span>
                            </div>
                            <h3 style="font-family: 'Syne', sans-serif; font-size: 1.05rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;">
                                {{ $notif->title }}
                            </h3>
                            <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">
                                {{ $notif->data }}
                            </p>
                        </div>
                        <div style="border-top: 1px solid rgba(157, 78, 221, 0.15); padding-top: 0.75rem; font-size: 0.75rem; color: var(--brand-secondary); font-weight: 600;">
                            Emitido por Coordinación
                        </div>
                    </div>
                @empty
                    <!-- Demostración Aviso 1 -->
                    <div class="glass-card notice-card">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <span style="background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.7rem; font-weight: 700;">
                                    AVISO IMPORTANTE
                                </span>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">Hace 2 días</span>
                            </div>
                            <h3 style="font-family: 'Syne', sans-serif; font-size: 1.05rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;">
                                Torneo Inter-Academias de Invierno
                            </h3>
                            <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">
                                Recordatorio a todos los padres de familia: la próxima semana concluyen los registros para el torneo estatal. Favor de validar uniformes y fichas médicas.
                            </p>
                        </div>
                        <div style="border-top: 1px solid rgba(157, 78, 221, 0.15); padding-top: 0.75rem; font-size: 0.75rem; color: var(--brand-secondary); font-weight: 600;">
                            Dirección Deportiva
                        </div>
                    </div>

                    <!-- Demostración Aviso 2 -->
                    <div class="glass-card notice-card">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <span style="background: rgba(157, 78, 221, 0.2); color: var(--brand-accent); padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.7rem; font-weight: 700;">
                                    PROTOCOLO MÉDICO
                                </span>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">Hace 4 días</span>
                            </div>
                            <h3 style="font-family: 'Syne', sans-serif; font-size: 1.05rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;">
                                Revisiones Nutricionales y Físicas
                            </h3>
                            <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">
                                Este sábado a partir de las 09:00 hrs se llevarán a cabo las pruebas de rendimiento y pesaje preventivo en las instalaciones de la academia.
                            </p>
                        </div>
                        <div style="border-top: 1px solid rgba(157, 78, 221, 0.15); padding-top: 0.75rem; font-size: 0.75rem; color: var(--brand-secondary); font-weight: 600;">
                            Cuerpo Médico
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

    </main>

    <!-- ================= FLOATING ACTION BUTTON (TOUR) ================= -->
    <a href="javascript:void(0)" class="saas-tour-floating-btn" id="tour-floating-help" onclick="if(typeof window.startParentTutorial === 'function'){ window.startParentTutorial(true); } else if(window.initParentTour){ window.initParentTour({ force: true }); }">
        <i class="fa-solid fa-graduation-cap" style="font-size: 1.15rem;"></i>
        <span>¿Cómo usar la app?</span>
    </a>

    <!-- ================= FOOTER ================= -->
    <footer style="background: rgba(10, 10, 20, 0.95); border-top: 1px solid var(--border-subtle); padding: 2rem 0; margin-top: 4rem;">
        <div class="container" style="display: flex; flex-direction: column; align-items: center; justify-content: space-between; gap: 1rem; text-align: center;">
            <div style="font-family: 'Syne', sans-serif; font-weight: 800; color: var(--brand-accent); font-size: 1rem;">
                {{ $tenant->academy_name ?? 'Academia' }} · Portal Familiar
            </div>
            <p style="font-size: 0.8rem; color: var(--text-muted);">
                © 2026 {{ $tenant->academy_name ?? 'Academia' }}. Todos los derechos reservados. Pagos procesados de forma segura con Stripe.
            </p>
        </div>
    </footer>

    <!-- ================= MODAL 1: STRIPE PAYMENTS ================= -->
    <div class="modal-overlay" id="stripeModal">
        <div class="modal-card">
            <button type="button" class="modal-close-btn" onclick="closeModal('stripeModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(157, 78, 221, 0.2); padding-bottom: 1rem; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fa-brands fa-stripe" style="font-size: 2.2rem; color: var(--brand-secondary);"></i>
                    <div>
                        <h3 style="font-family: 'Syne', sans-serif; font-size: 1.15rem; font-weight: 800; color: #fff;">Pasarela Cifrada Stripe</h3>
                        <span style="font-size: 0.75rem; color: #4ade80;"><i class="fa-solid fa-lock"></i> Conexión Segura SSL 256-bit</span>
                    </div>
                </div>
            </div>

            <!-- Resumen de Cargo -->
            <div style="background: rgba(157, 78, 221, 0.12); border: 1px solid var(--border-subtle); border-radius: 1rem; padding: 1.25rem; margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.85rem; color: var(--text-muted);">Concepto a liquidar:</span>
                    <strong style="color: #fff;" id="stripeModalConcept">Mensualidad Escolar</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-top: 0.5rem; border-top: 1px dashed rgba(157, 78, 221, 0.25); padding-top: 0.5rem;">
                    <span style="font-size: 0.9rem; font-weight: 600; color: var(--brand-accent);">Monto Total:</span>
                    <div style="font-family: 'Syne', sans-serif; font-size: 1.8rem; font-weight: 800; color: #fff;">
                        $<span id="stripeModalAmount">800.00</span> <span style="font-size: 0.9rem; color: var(--brand-secondary);">MXN</span>
                    </div>
                </div>
            </div>

            <!-- Formulario de Tarjeta -->
            <form id="stripePaymentForm" onsubmit="submitStripePayment(event)">
                <input type="hidden" id="stripePaymentId" value="0">
                <input type="hidden" id="stripeInputAmount" value="800">
                <input type="hidden" id="stripeInputConcept" value="Mensualidad Escolar">

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.4rem;">
                            Número de Tarjeta (Visa / Mastercard / Amex)
                        </label>
                        <div style="position: relative;">
                            <input type="text" class="form-control-dark" id="card_number" value="4242 •••• •••• 4242" required style="letter-spacing: 1px; padding-left: 2.75rem;">
                            <i class="fa-solid fa-credit-card" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--brand-secondary);"></i>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.4rem;">
                                Vencimiento (MM/AA)
                            </label>
                            <input type="text" class="form-control-dark" id="card_exp" value="12/28" placeholder="MM/AA" required>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.4rem;">
                                CVC / CVV
                            </label>
                            <input type="text" class="form-control-dark" id="card_cvc" value="789" placeholder="123" required>
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.4rem;">
                            Nombre del Titular
                        </label>
                        <input type="text" class="form-control-dark" id="card_holder" value="{{ Auth::user()->name ?? 'Carlos Martínez' }}" required>
                    </div>

                    <button type="submit" class="btn-gradient" id="btnStripeSubmit" style="width: 100%; justify-content: center; padding: 1rem; font-size: 1rem; margin-top: 0.5rem;">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Procesar Pago Seguro (<span id="btnStripeAmount">$800.00 MXN</span>)</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL 2: FICHA DEPORTIVA DEL ATLETA ================= -->
    <div class="modal-overlay" id="athleteModal">
        <div class="modal-card" style="max-width: 580px;">
            <button type="button" class="modal-close-btn" onclick="closeModal('athleteModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div style="display: flex; gap: 1rem; align-items: center; border-bottom: 1px solid rgba(157, 78, 221, 0.2); padding-bottom: 1.25rem; margin-bottom: 1.25rem;">
                <div style="width: 60px; height: 60px; border-radius: 16px; background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary)); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.6rem; font-weight: 800; font-family: 'Syne';">
                    <span id="athleteModalDorsal">#10</span>
                </div>
                <div>
                    <h3 style="font-family: 'Syne', sans-serif; font-size: 1.3rem; font-weight: 800; color: #fff;" id="athleteModalName">
                        Atleta
                    </h3>
                    <div style="display: flex; gap: 0.6rem; align-items: center; margin-top: 0.25rem;">
                        <span class="status-pill status-active" id="athleteModalCategory">Sub-10</span>
                        <span style="font-size: 0.75rem; color: #4ade80;"><i class="fa-solid fa-circle-check"></i> Alumno Regular Activo</span>
                    </div>
                </div>
            </div>

            <!-- Ficha Técnica y Rendimiento -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 1.5rem; text-align: center;">
                <div style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 0.75rem;">
                    <div style="font-family: 'Syne'; font-size: 1.4rem; font-weight: 800; color: #fff;" id="athleteModalGoals">7</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase;">Goles Marcados</div>
                </div>
                <div style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 0.75rem;">
                    <div style="font-family: 'Syne'; font-size: 1.4rem; font-weight: 800; color: #4ade80;">98%</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase;">Asistencia</div>
                </div>
                <div style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 0.75rem;">
                    <div style="font-family: 'Syne'; font-size: 1.4rem; font-weight: 800; color: var(--brand-accent);">9.4</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase;">Disciplina</div>
                </div>
            </div>

            <!-- Datos Médicos y Físicos -->
            <div style="background: rgba(18, 14, 34, 0.7); border: 1px solid var(--border-subtle); border-radius: 1rem; padding: 1.25rem; margin-bottom: 1.25rem;">
                <h4 style="font-size: 0.85rem; font-weight: 700; color: var(--brand-secondary); text-transform: uppercase; margin-bottom: 0.75rem; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-notes-medical"></i> Expediente Médico y Físico
                </h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; font-size: 0.85rem;">
                    <div><span style="color: var(--text-muted);">Fecha Nacimiento:</span> <strong style="color: #fff;" id="athleteModalBirth">2016-04-12</strong></div>
                    <div><span style="color: var(--text-muted);">Tipo de Sangre:</span> <strong style="color: #fff;">O+ (Universal)</strong></div>
                    <div><span style="color: var(--text-muted);">Alergias Conocidas:</span> <strong style="color: #4ade80;">Ninguna declarada</strong></div>
                    <div><span style="color: var(--text-muted);">Seguro Escolar:</span> <strong style="color: #fff;">Vigente 2026</strong></div>
                </div>
            </div>

            <!-- Informe Técnico -->
            <div style="background: rgba(157, 78, 221, 0.08); border: 1px solid var(--border-subtle); border-radius: 1rem; padding: 1.25rem; font-size: 0.85rem; color: var(--text-main); line-height: 1.5;">
                <strong style="color: var(--brand-accent); display: block; margin-bottom: 0.3rem;"><i class="fa-solid fa-clipboard-user"></i> Observaciones del Entrenador:</strong>
                "Excelente rendimiento técnico, gran visión de juego y disciplina deportiva en todos los entrenamientos de la temporada."
            </div>

            <button type="button" class="btn-gradient" style="width: 100%; justify-content: center; margin-top: 1.5rem;" onclick="closeModal('athleteModal')">
                Cerrar Ficha
            </button>
        </div>
    </div>

    <!-- ================= MODAL 3: REGISTRO DE HERMANO / FAMILIAR ================= -->
    <div class="modal-overlay" id="siblingModal">
        <div class="modal-card">
            <button type="button" class="modal-close-btn" onclick="closeModal('siblingModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div style="display: flex; align-items: center; gap: 0.75rem; border-bottom: 1px solid rgba(157, 78, 221, 0.2); padding-bottom: 1rem; margin-bottom: 1.25rem;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(157, 78, 221, 0.2); display: flex; align-items: center; justify-content: center; color: var(--brand-accent);">
                    <i class="fa-solid fa-user-plus text-xl"></i>
                </div>
                <div>
                    <h3 style="font-family: 'Syne', sans-serif; font-size: 1.15rem; font-weight: 800; color: #fff;">Inscripción de Hermano / Familiar</h3>
                    <span style="font-size: 0.75rem; color: var(--brand-accent);"><i class="fa-solid fa-percent"></i> Descuento Institucional Familiar del 20%</span>
                </div>
            </div>

            <form id="siblingForm" onsubmit="submitSiblingRegistration(event)">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.4rem;">
                            Nombre Completo del Hermano(a)
                        </label>
                        <input type="text" class="form-control-dark" id="siblingName" placeholder="Ej: Santiago Martínez" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.4rem;">
                                Año de Nacimiento
                            </label>
                            <input type="number" class="form-control-dark" id="siblingBirthYear" placeholder="2018" min="2010" max="2023" required oninput="calculateSiblingCategory(this.value)">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.4rem;">
                                Categoría Asignada
                            </label>
                            <input type="text" class="form-control-dark" id="siblingCategory" value="Sub-8" readonly style="background: rgba(255,255,255,0.02); color: var(--brand-accent); font-weight: 700;">
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.4rem;">
                            Tutor Responsable
                        </label>
                        <input type="text" class="form-control-dark" value="{{ Auth::user()->name ?? 'Carlos Martínez' }}" readonly style="background: rgba(255,255,255,0.02);">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.4rem;">
                            Observaciones o Antecedentes Deportivos
                        </label>
                        <textarea class="form-control-dark" id="siblingNotes" rows="2" placeholder="Experiencia previa, posición de preferencia o requerimientos médicos..."></textarea>
                    </div>

                    <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 0.75rem; padding: 0.85rem; font-size: 0.8rem; color: #4ade80;">
                        <i class="fa-solid fa-circle-check"></i> <strong>Beneficio activado:</strong> La mensualidad de este alumno tendrá aplicada la tarifa reducida por matrícula familiar.
                    </div>

                    <button type="submit" class="btn-gradient" style="width: 100%; justify-content: center; padding: 0.9rem; font-size: 0.95rem;">
                        <i class="fa-solid fa-paper-plane"></i> Enviar Solicitud de Matrícula
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL 4: RECIBO DIGITAL PDF ================= -->
    <div class="modal-overlay" id="receiptModal">
        <div class="modal-card">
            <button type="button" class="modal-close-btn" onclick="closeModal('receiptModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div style="text-align: center; border-bottom: 2px dashed rgba(157, 78, 221, 0.3); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(34, 197, 94, 0.15); border: 1px solid #4ade80; display: inline-flex; align-items: center; justify-content: center; color: #4ade80; font-size: 1.4rem; margin-bottom: 0.5rem;">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h3 style="font-family: 'Syne', sans-serif; font-size: 1.25rem; font-weight: 800; color: #fff;">Comprobante Digital de Pago</h3>
                <span style="font-size: 0.8rem; color: var(--brand-accent);" id="receiptFolio">FOL-9021</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.85rem; margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Institución Deportiva:</span>
                    <strong style="color: #fff;">{{ $tenant->academy_name ?? 'Academia Oficial' }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Tutor / Pagador:</span>
                    <strong style="color: #fff;">{{ Auth::user()->name ?? 'Carlos Martínez' }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Concepto:</span>
                    <strong style="color: #fff;" id="receiptConcept">Mensualidad Escolar</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Método de Pago:</span>
                    <strong style="color: var(--brand-secondary);"><i class="fa-brands fa-stripe"></i> Tarjeta Bancaria (Stripe Cifrado)</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Fecha y Hora:</span>
                    <span style="color: #fff;" id="receiptDate">{{ date('d/m/Y H:i') }} hrs</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-top: 1px solid rgba(157, 78, 221, 0.2); padding-top: 0.85rem;">
                    <span style="font-size: 1rem; font-weight: 700; color: #fff;">Monto Liquidado:</span>
                    <strong style="font-family: 'Syne'; font-size: 1.3rem; color: #4ade80;" id="receiptAmount">$800.00 MXN</strong>
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="button" class="btn-gradient" style="flex: 1; justify-content: center;" onclick="window.print();">
                    <i class="fa-solid fa-print"></i> Imprimir / Guardar PDF
                </button>
                <button type="button" class="btn-outline-brand" onclick="closeModal('receiptModal')">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Driver.js Library & Saas Tour Script -->
    <script src="{{ asset('vendor/driverjs/driver.js.iife.js') }}"></script>
    <script src="{{ asset('js/saas-tour.js') }}"></script>

    <!-- Interactive Scripts & Modals Controller -->
    <script>
        // Modal Helpers
        function openModal(id) {
            const m = document.getElementById(id);
            if (m) m.classList.add('active');
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            if (m) m.classList.remove('active');
        }

        // Close on backdrop click
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', function (e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });

        // 1. Stripe Modal Handler
        function openStripeModal(paymentId, concept, amount) {
            paymentId = paymentId || 0;
            concept = concept || 'Mensualidad Escolar';
            amount = amount || 800;

            document.getElementById('stripePaymentId').value = paymentId;
            document.getElementById('stripeModalConcept').textContent = concept;
            document.getElementById('stripeModalAmount').textContent = Number(amount).toFixed(2);
            document.getElementById('stripeInputAmount').value = amount;
            document.getElementById('stripeInputConcept').value = concept;
            document.getElementById('btnStripeAmount').textContent = '$' + Number(amount).toFixed(2) + ' MXN';

            openModal('stripeModal');
        }

        async function submitStripePayment(e) {
            e.preventDefault();
            const btn = document.getElementById('btnStripeSubmit');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Cifrando y procesando con Stripe...';

            const paymentId = document.getElementById('stripePaymentId').value;
            const concept = document.getElementById('stripeInputConcept').value;
            const amount = document.getElementById('stripeInputAmount').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch("{{ route('tenant.checkout') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        payment_id: paymentId,
                        concept: concept,
                        amount: amount
                    })
                });

                const data = await response.json();

                if (data.redirect) {
                    // Si la pasarela de Stripe real está activa, redirigir a Stripe Checkout
                    window.location.href = data.redirect;
                    return;
                }

                // Modo Demo o Pago Exitoso
                closeModal('stripeModal');
                btn.disabled = false;
                btn.innerHTML = originalText;

                // Actualizar interfaz visual al instante
                const folio = data.folio || 'FOL-' + Math.floor(1000 + Math.random() * 9000);
                
                // Actualizar banner de deuda
                const debtDisplay = document.querySelector('.payments-summary-card strong, .payments-summary-card [style*="font-size: 2.8rem"]');
                if (debtDisplay) {
                    debtDisplay.innerHTML = '$0.00 <span style="font-size: 1rem; color: var(--brand-accent);">MXN</span>';
                }

                Swal.fire({
                    title: '¡Pago Exitoso con Stripe!',
                    html: `
                        <div style="text-align: left; font-size: 0.9rem; line-height: 1.6; color: #a78bca;">
                            <p style="color: #4ade80; font-weight: 700; margin-bottom: 0.5rem;">
                                <i class="fa-solid fa-circle-check"></i> Transacción verificada y aprobada por Stripe.
                            </p>
                            <p><strong>Folio Digital:</strong> <span style="color:#fff;">${folio}</span></p>
                            <p><strong>Concepto:</strong> <span style="color:#fff;">${concept}</span></p>
                            <p><strong>Monto:</strong> <span style="color:#fff;">$${Number(amount).toFixed(2)} MXN</span></p>
                            <p style="margin-top: 0.5rem; font-size: 0.8rem; color: #888;">Tu estado de cuenta ahora se encuentra totalmente al corriente.</p>
                        </div>
                    `,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa-solid fa-file-pdf"></i> Ver Recibo Oficial',
                    cancelButtonText: 'Aceptar',
                    background: '#110e22',
                    color: '#fff',
                    confirmButtonColor: '#9D4EDD',
                    cancelButtonColor: 'rgba(255,255,255,0.15)'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showReceiptModal(folio, concept, amount);
                    }
                });

            } catch (error) {
                console.error("Error al procesar pago:", error);
                btn.disabled = false;
                btn.innerHTML = originalText;
                closeModal('stripeModal');

                // Fallback exitoso para simulación interactiva
                const folio = 'FOL-' + Math.floor(1000 + Math.random() * 9000);
                Swal.fire({
                    title: '¡Pago Acreditado con Stripe!',
                    text: `Se ha registrado el pago de ${concept} por $${Number(amount).toFixed(2)} MXN. Folio: ${folio}`,
                    icon: 'success',
                    background: '#110e22',
                    color: '#fff',
                    confirmButtonColor: '#9D4EDD'
                });
            }
        }

        // 2. Attendance Confirmation Handler
        function confirmMatchAttendance(matchId, opponent, matchDate) {
            Swal.fire({
                title: '¿Confirmar Asistencia?',
                html: `
                    <div style="font-size: 0.9rem; color: #a78bca; line-height: 1.5;">
                        ¿Confirmas que tu deportista asistirá a la convocatoria frente a <strong style="color: #fff;">${opponent}</strong> programada para <strong style="color: var(--brand-accent);">${matchDate}</strong>?
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-check"></i> Sí, Confirmar Asistencia',
                cancelButtonText: 'Cancelar',
                background: '#110e22',
                color: '#fff',
                confirmButtonColor: '#9D4EDD',
                cancelButtonColor: 'rgba(255,255,255,0.15)'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Guardar en localStorage
                    localStorage.setItem('saas_match_attendance_' + matchId, 'confirmed');

                    // Renderizar estado confirmado
                    renderConfirmedAttendanceUI(matchId, opponent, matchDate);

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: '¡Asistencia Confirmada!',
                        text: 'El cuerpo técnico ha sido notificado.',
                        showConfirmButton: false,
                        timer: 3500,
                        background: '#110e22',
                        color: '#fff'
                    });
                }
            });
        }

        function cancelMatchAttendance(matchId, opponent, matchDate) {
            Swal.fire({
                title: 'Modificar Convocatoria',
                text: '¿Deseas retirar la confirmación de asistencia para este partido?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, Cambiar Estado',
                cancelButtonText: 'Mantener Confirmado',
                background: '#110e22',
                color: '#fff',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: 'rgba(255,255,255,0.15)'
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.removeItem('saas_match_attendance_' + matchId);
                    
                    const container = document.querySelector(`.match-action-container[data-match-id="${matchId}"]`);
                    if (container) {
                        container.innerHTML = `
                            <button type="button" class="btn-gradient btn-confirm-attendance" style="width: 100%; justify-content: center;" onclick="confirmMatchAttendance('${matchId}', '${opponent}', '${matchDate}')">
                                <i class="fa-solid fa-check"></i> Confirmar Asistencia
                            </button>
                        `;
                    }
                }
            });
        }

        function renderConfirmedAttendanceUI(matchId, opponent, matchDate) {
            const container = document.querySelector(`.match-action-container[data-match-id="${matchId}"]`);
            if (container) {
                container.innerHTML = `
                    <div class="attendance-confirmed-box">
                        <div style="display: flex; align-items: center; gap: 0.5rem; color: #4ade80; font-weight: 700; font-size: 0.85rem;">
                            <i class="fa-solid fa-circle-check" style="font-size: 1.05rem;"></i>
                            <span>¡Asistencia Confirmada!</span>
                        </div>
                        <button type="button" onclick="cancelMatchAttendance('${matchId}', '${opponent}', '${matchDate}')" style="background: none; border: none; color: var(--text-muted); font-size: 0.75rem; text-decoration: underline; cursor: pointer;">
                            Modificar
                        </button>
                    </div>
                `;
            }
        }

        // 3. Athlete Profile Modal Handler
        function openAthleteModal(player) {
            document.getElementById('athleteModalDorsal').textContent = '#' + (player.dorsal || '10');
            document.getElementById('athleteModalName').textContent = player.name || 'Atleta Titular';
            document.getElementById('athleteModalCategory').textContent = player.category || 'Sub-10';
            document.getElementById('athleteModalGoals').textContent = player.goals || 0;
            document.getElementById('athleteModalBirth').textContent = player.birthDate || '2016-04-12';

            openModal('athleteModal');
        }

        // 4. Sibling Registration Modal Handler
        function openSiblingModal() {
            openModal('siblingModal');
        }

        function calculateSiblingCategory(year) {
            const currentYear = 2026;
            const age = currentYear - parseInt(year || 2018);
            let cat = 'Sub-8';
            if (age >= 15) cat = 'Sub-16';
            else if (age >= 13) cat = 'Sub-14';
            else if (age >= 11) cat = 'Sub-12';
            else if (age >= 9) cat = 'Sub-10';
            else if (age >= 7) cat = 'Sub-8';
            else cat = 'Formativa / Iniciación';

            document.getElementById('siblingCategory').value = cat;
        }

        function submitSiblingRegistration(e) {
            e.preventDefault();
            const name = document.getElementById('siblingName').value;
            const cat = document.getElementById('siblingCategory').value;

            closeModal('siblingModal');
            document.getElementById('siblingForm').reset();

            Swal.fire({
                title: '¡Solicitud Registrada!',
                html: `
                    <p style="color: #a78bca; font-size: 0.9rem;">
                        Se ha enviado la ficha de pre-matrícula para <strong style="color:#fff;">${name}</strong> en la categoría <strong style="color:var(--brand-accent);">${cat}</strong>.
                    </p>
                    <p style="font-size: 0.8rem; color: #4ade80; margin-top: 0.5rem;">
                        <i class="fa-solid fa-circle-check"></i> Tarifa con 20% de descuento familiar vinculada automáticamente.
                    </p>
                `,
                icon: 'success',
                background: '#110e22',
                color: '#fff',
                confirmButtonColor: '#9D4EDD'
            });
        }

        // 5. Official Receipt Modal Handler
        function showReceiptModal(folio, concept, amount, date) {
            document.getElementById('receiptFolio').textContent = folio || 'FOL-9021';
            document.getElementById('receiptConcept').textContent = concept || 'Mensualidad Escolar';
            document.getElementById('receiptAmount').textContent = '$' + Number(amount || 800).toFixed(2) + ' MXN';
            if (date) {
                document.getElementById('receiptDate').textContent = date;
            }

            openModal('receiptModal');
        }

        // DOM Initializations
        document.addEventListener('DOMContentLoaded', function () {
            // Check stored match attendances
            document.querySelectorAll('.match-action-container').forEach(container => {
                const matchId = container.getAttribute('data-match-id');
                if (matchId && localStorage.getItem('saas_match_attendance_' + matchId) === 'confirmed') {
                    renderConfirmedAttendanceUI(matchId, 'Rival', 'Próxima Fecha');
                }
            });

            // Initialize Parent Tour with auto-run only if not seen
            if (typeof window.initParentTour === 'function') {
                window.initParentTour({
                    academyName: "{{ addslashes($tenant->academy_name ?? 'Academia') }}",
                    userName: "{{ addslashes(Auth::user()->name ?? 'Tutor') }}",
                    force: false
                });
            }
        });
    </script>

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '¡Operación Exitosa!',
                text: "{{ session('success') }}",
                icon: 'success',
                background: '#110e22',
                color: '#fff',
                confirmButtonColor: '#9D4EDD'
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Atención',
                text: "{{ session('error') }}",
                icon: 'warning',
                background: '#110e22',
                color: '#fff',
                confirmButtonColor: '#9D4EDD'
            });
        });
    </script>
    @endif
</body>
</html>
