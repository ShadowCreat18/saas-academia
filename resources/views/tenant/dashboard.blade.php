<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - {{ $tenant->academy_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Inter:wght@300;400;500;600&family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/driverjs/driver.css') }}">
    <link rel="stylesheet" href="{{ asset('css/saas-tour.css') }}">
    <style>
        :root { 
            --bg: #0a0a14; 
            --surface: rgba(123,47,190,0.08); 
            --surface-hover: rgba(123,47,190,0.15); 
            --primary: {{ $tenant->color_palette ?? '#9D4EDD' }}; 
            --accent: #E0AAFF; 
            --text: #F0E6FF; 
            --muted: #a78bca; 
            --border: rgba(157,78,221,0.3); 
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background:var(--bg); color:var(--text); font-family:'Inter',sans-serif; display: flex; min-height: 100vh; overflow-x: hidden; }
        
        /* Sidebar */
        .sidebar { width: 260px; background: rgba(0,0,0,0.4); border-right: 1px solid var(--border); display: flex; flex-direction: column; padding: 2rem 1rem; transition: 0.3s; z-index: 100; }
        .logo { font-family: 'Syne'; font-weight: 800; font-size: 1.2rem; color: var(--primary); margin-bottom: 2.5rem; text-align: center; display: flex; align-items: center; justify-content: center; min-height: 50px; }
        .logo img { max-height: 40px; }
        .nav-item { display: flex; align-items: center; gap: 1rem; padding: 1rem; color: var(--muted); text-decoration: none; border-radius: 10px; margin-bottom: 0.5rem; cursor: pointer; transition: 0.2s; font-family: 'Space Grotesk'; }
        .nav-item:hover, .nav-item.active { background: var(--surface-hover); color: var(--accent); }
        .nav-item i { width: 20px; text-align: center; }
        
        /* Main Content */
        .main-content { flex: 1; padding: 2rem 3rem; overflow-y: auto; height: 100vh; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .user-menu { display: flex; align-items: center; gap: 1.5rem; }
        .notification-bell { position: relative; cursor: pointer; font-size: 1.2rem; }
        .badge { position: absolute; top: -5px; right: -8px; background: #FF6B6B; color: #fff; font-size: 0.6rem; padding: 2px 5px; border-radius: 10px; font-family: 'Space Grotesk'; font-weight: 600; }
        .btn-tutor-portal { background: rgba(255,255,255,0.1); border: 1px solid var(--muted); padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; cursor: pointer; color: var(--text); transition: 0.2s; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; }
        .btn-tutor-portal:hover { background: rgba(255,255,255,0.2); }
        
        /* Layouts */
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 15px; padding: 2rem; margin-bottom: 2rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 15px; padding: 1.5rem; text-align: center; }
        .stat-value { font-family: 'Syne'; font-size: 2.2rem; font-weight: 700; color: var(--accent); margin-bottom: 0.5rem; }
        .stat-label { font-family: 'Space Grotesk'; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; font-size: 0.75rem; }
        
        /* Forms & Tables */
        .form-group { margin-bottom: 1rem; }
        label { display:block; margin-bottom:0.5rem; color:var(--accent); font-size: 0.9rem; }
        input, select, textarea { width:100%; background:rgba(0,0,0,0.3); border:1px solid var(--border); border-radius:8px; padding:0.8rem; color:var(--text); font-family: 'Inter'; }
        .btn { background:linear-gradient(135deg,var(--primary),#6a1eb0); color:#fff; border:none; padding:0.8rem 1.5rem; border-radius:8px; cursor:pointer; font-family:'Space Grotesk',sans-serif; font-weight:600; display: inline-flex; align-items: center; gap: 0.5rem; transition: 0.2s;}
        .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(157,78,221,0.4); }
        .btn-outline { background: transparent; border: 1px solid var(--primary); }
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width:100%; border-collapse: collapse; text-align: left; min-width: 600px; }
        th { padding: 1rem; color: var(--muted); border-bottom: 1px solid var(--border); font-family: 'Space Grotesk'; font-weight: 500; font-size: 0.9rem; }
        td { padding: 1rem; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .badge-status { background: var(--primary); padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        
        /* Sections */
        .section-view { display: none; animation: fadeIn 0.3s ease-in-out; }
        .section-view.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
        
        @media(max-width:768px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; flex-direction: row; flex-wrap: wrap; justify-content: center; padding: 1rem; border-right: none; border-bottom: 1px solid var(--border); }
            .logo { width: 100%; margin-bottom: 1rem; }
            .nav-item { padding: 0.5rem 1rem; margin: 0; font-size: 0.9rem; }
            .main-content { padding: 1rem; }
            .grid-2 { grid-template-columns: 1fr; }
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>

    <style>
        /* FIX SWEETALERT OVERFLOW */
        .swal2-input, .swal2-select, .swal2-textarea {
            max-width: 100% !important;
            box-sizing: border-box !important;
            width: 100% !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
    </style>
</head>
<body>
    
    <!-- SIDEBAR -->
    <div class="sidebar" id="tour-sidebar">
        <div class="logo">
            @if($tenant->logo_path)
                <img src="{{ asset('storage/' . $tenant->logo_path) }}" alt="Logo" style="max-height: 40px; margin-right: 10px; border-radius: 5px;">
            @endif
            {{ $tenant->academy_name }}
        </div>
        
        <div class="nav-item active" data-tab="inicio" onclick="switchTab('inicio', this)">
            <i class="fa-solid fa-house"></i> Inicio
        </div>
        <div class="nav-item" data-tab="categorias" onclick="switchTab('categorias', this)">
            <i class="fa-solid fa-layer-group"></i> Categorías
        </div>
        <div class="nav-item" data-tab="alumnos" onclick="switchTab('alumnos', this)">
            <i class="fa-solid fa-users"></i> Alumnos
        </div>
        <div class="nav-item" data-tab="tutores" onclick="switchTab('tutores', this)">
            <i class="fa-solid fa-user-tie"></i> Tutores
        </div>
        
        <div class="nav-item {{ $tenant->plan === 'basico' ? 'locked' : '' }}" data-tab="partidos" onclick="{!! $tenant->plan === 'basico' ? 'showUpgradeAlert()' : "switchTab('partidos', this)" !!}">
            <i class="fa-solid fa-trophy"></i> Partidos
            @if($tenant->plan === 'basico')
                <i class="fa-solid fa-lock" style="float: right; font-size: 0.8rem; margin-top: 5px; color: #ff4757;"></i>
            @endif
        </div>
        <div class="nav-item" data-tab="pagos" onclick="switchTab('pagos', this)">
            <i class="fa-solid fa-file-invoice-dollar"></i> Finanzas
        </div>
        <div class="nav-item {{ $tenant->plan === 'basico' ? 'locked' : '' }}" data-tab="config" onclick="{!! $tenant->plan === 'basico' ? 'showUpgradeAlert()' : "switchTab('config', this)" !!}">
            <i class="fa-solid fa-gear"></i> Configuración
            @if($tenant->plan === 'basico')
                <i class="fa-solid fa-lock" style="float: right; font-size: 0.8rem; margin-top: 5px; color: #ff4757;"></i>
            @endif
        </div>

    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="topbar">
            <div id="tour-topbar-plan">
                <h1 style="font-family: 'Syne'; margin-bottom: 0.2rem;">Panel de Control</h1>
                <p style="color:var(--muted); font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                    Plan: <strong style="color: var(--accent);">{{ strtoupper($tenant->plan) }}</strong>
                    @if(strtolower($tenant->plan) === 'premium')
                        <span style="background: rgba(255, 215, 0, 0.15); border: 1px solid #FFD700; color: #FFD700; font-weight: 700; padding: 3px 10px; font-size: 0.75rem; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="fa-solid fa-crown"></i> Plan Premium Activo 💎
                        </span>
                    @else
                        <button type="button" class="btn" style="background: linear-gradient(135deg, #FFD700, #FFA500); color: #000; font-weight: 700; padding: 4px 12px; font-size: 0.8rem; border-radius: 6px; cursor: pointer; border: none; box-shadow: 0 0 12px rgba(255, 215, 0, 0.4);" onclick="openUpgradeModal()">
                            <i class="fa-solid fa-crown"></i> Mejorar a Premium
                        </button>
                    @endif
                </p>
            </div>
            <div class="user-menu" id="tour-user-menu">
                <button id="btn-start-tour" class="btn" style="background: rgba(157,78,221,0.25); border: 1px solid var(--primary); padding: 0.45rem 0.9rem; border-radius: 20px; font-size: 0.8rem; cursor: pointer; color: var(--accent); transition: 0.2s; display: inline-flex; align-items: center; gap: 0.4rem;" onclick="startAdminTutorial(true)" title="Ver Tutorial del Panel">
                    <i class="fa-solid fa-graduation-cap"></i> <span>Ver Tutorial</span>
                </button>
                @if($tenant->subdomain === 'demo')
                <button class="btn-tutor-portal" id="tour-app-tutors-btn" onclick="openParentPortalDemo()">
                    <i class="fa-solid fa-mobile-screen"></i> App Tutores (Demo)
                </button>
                @else
                <a href="{{ route('tenant.frontend') }}" target="_blank" class="btn-tutor-portal" id="tour-app-tutors-btn">
                    <i class="fa-solid fa-mobile-screen"></i> App Tutores
                </a>
                @endif
                <div class="notification-bell" onclick="alert('Notificaciones leídas')">
                    <i class="fa-regular fa-bell"></i> <span class="badge" id="notif-count">0</span>
                </div>
                <div style="font-family: 'Space Grotesk'; font-size: 0.9rem;">
                    {{ auth()->user()->name }} 
                    <a href="{{ route('logout') }}" style="color:var(--accent); text-decoration: none; margin-left:10px;"><i class="fa-solid fa-right-from-bracket"></i> Salir</a>
                </div>
            </div>
        </div>

        @if($tenant->subdomain === 'demo')
            <div style="background: linear-gradient(135deg, rgba(157,78,221,0.2), rgba(255,215,0,0.1)); border: 1px solid var(--primary); border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <strong style="color: #FFD700; font-size: 0.95rem;"><i class="fa-solid fa-circle-info"></i> Entorno de Demostración Activo (Plan Premium 💎)</strong>
                    <p style="color: var(--text); font-size: 0.85rem; margin: 4px 0 0 0;">
                        <strong>Admin:</strong> demo@academiahe5.com / password &nbsp;|&nbsp; 
                        <strong>Tutor:</strong> carlos.tutor@ejemplo.com / 123456
                    </p>
                </div>
                <div>
                    <button class="btn" style="padding: 6px 14px; font-size: 0.85rem;" onclick="openParentPortalDemo()"><i class="fa-solid fa-mobile-screen"></i> Probar App Tutores</button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div style="background: rgba(255,107,107,0.1); border: 1px solid #FF6B6B; color: #FF6B6B; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
                <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div style="background: rgba(157,78,221,0.1); border: 1px solid var(--primary); color: var(--text); padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
                <i class="fa-solid fa-check"></i> {{ session('success') }}
            </div>
        @endif

        <!-- VISTA: INICIO -->
        <div id="section-inicio" class="section-view active">
            <div class="stats-grid" id="tour-stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['players'] }}</div>
                    <div class="stat-label">Alumnos Activos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="dash-cat-count">{{ $stats['categories'] }}</div>
                    <div class="stat-label">Categorías</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="dash-tut-count">0</div>
                    <div class="stat-label">Tutores Activos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="dash-rev-count">${{ number_format($stats['revenue'], 2) }}</div>
                    <div class="stat-label">Ingresos del Mes</div>
                </div>
            </div>

            <div class="grid-2">
                <div class="card" id="tour-quick-notif">
                    <h3><i class="fa-regular fa-paper-plane"></i> Comunicación Rápida</h3>
                    <p style="color:var(--muted);margin:1rem 0;font-size:0.9rem;">Envía notificaciones push o alertas al panel de los padres.</p>
                    <form action="{{ route('tenant.notifications.send') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Tipo de Alerta</label>
                            <select name="type">
                                <option value="global">Anuncio Global (Toda la academia)</option>
                                <option value="specific">Alerta Específica (Cobro, Suspensión)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" name="title" required placeholder="Ej. Partido Cancelado por Lluvia">
                        </div>
                        <div class="form-group">
                            <label>Mensaje</label>
                            <textarea name="message" rows="3" required placeholder="Detalles..."></textarea>
                        </div>
                        <button type="submit" class="btn"><i class="fa-solid fa-paper-plane"></i> Enviar Notificación</button>
                    </form>
                </div>
                <div class="card" id="tour-recent-activity">
                    <h3><i class="fa-solid fa-chart-line"></i> Actividad Reciente</h3>
                    <p style="color:var(--muted);margin:1rem 0;font-size:0.9rem;">Últimos registros en la academia.</p>
                    <div style="display:flex; flex-direction:column; gap:1rem;">
                        @foreach($players->take(3) as $p)
                        <div style="display:flex; justify-content:space-between; padding:1rem; background:rgba(0,0,0,0.2); border-radius:8px; border:1px solid rgba(255,255,255,0.05);">
                            <div>
                                <strong>{{ $p->first_name }} {{ $p->last_name }}</strong><br>
                                <span style="font-size:0.8rem; color:var(--muted);">Nuevo alumno registrado</span>
                            </div>
                            <div style="font-size:0.8rem; color:var(--accent);">Hoy</div>
                        </div>
                        @endforeach
                        @if($players->isEmpty())
                        <div style="text-align:center; color:var(--muted); padding:2rem;">Sin actividad reciente.</div>
                        @endif
                    </div>
                </div>
            </div>

            @if(strtolower($tenant->plan) === 'premium')
            <div style="margin-top: 2rem;">
                <h3 style="font-family: 'Syne'; margin-bottom: 1rem; color: var(--accent);"><i class="fa-solid fa-wand-magic-sparkles"></i> Módulos Exclusivos Premium</h3>
                <div class="stats-grid">
                    <!-- WhatsApp -->
                    <div class="card" style="text-align: center; padding: 1.5rem;">
                        <i class="fa-brands fa-whatsapp" style="font-size: 2.5rem; color:#25D366; margin-bottom: 1rem;"></i>
                        <h4 style="margin-bottom: 0.5rem;">WhatsApp Bot</h4>
                        <p style="color: var(--muted); font-size: 0.8rem; margin-bottom: 1rem;">Cobros automáticos vía WhatsApp.</p>
                        <button class="btn btn-outline" style="width: 100%; justify-content:center; padding: 0.5rem; font-size: 0.9rem;" onclick="connectWhatsApp()">Conectar API</button>
                    </div>

                    <!-- Reportes -->
                    <div class="card" style="text-align: center; padding: 1.5rem;">
                        <i class="fa-solid fa-file-pdf" style="font-size: 2.5rem; color:#FF6B6B; margin-bottom: 1rem;"></i>
                        <h4 style="margin-bottom: 0.5rem;">Reportes PDF</h4>
                        <p style="color: var(--muted); font-size: 0.8rem; margin-bottom: 1rem;">Exporta balances y listas de asistencia.</p>
                        <button class="btn btn-outline" style="width: 100%; justify-content:center; padding: 0.5rem; font-size: 0.9rem;" onclick="generateReport()">Generar</button>
                    </div>

                    <!-- Personalización -->
                    <div class="card" style="text-align: center; padding: 1.5rem;">
                        <i class="fa-solid fa-palette" style="font-size: 2.5rem; color:var(--primary); margin-bottom: 1rem;"></i>
                        <h4 style="margin-bottom: 0.5rem;">Marca Blanca</h4>
                        <p style="color: var(--muted); font-size: 0.8rem; margin-bottom: 1rem;">Logo, colores y dominio personalizado.</p>
                        <button class="btn btn-outline" style="width: 100%; justify-content:center; padding: 0.5rem; font-size: 0.9rem;" onclick="openWhiteLabel()">Personalizar</button>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- VISTA: CATEGORIAS -->
        <div id="section-categorias" class="section-view">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
                <div>
                    <h2><i class="fa-solid fa-layer-group"></i> Gestión de Categorías</h2>
                    <p style="color:var(--muted); font-size:0.9rem;">Organiza a tus alumnos por edad o nivel.</p>
                </div>
                <button class="btn" onclick="addDemoRow('categorias')"><i class="fa-solid fa-plus"></i> Nueva Categoría</button>
            </div>
            <div class="card" style="padding:0; overflow:hidden;">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Año Mín.</th>
                                <th>Año Máx.</th>
                                <th>Horarios</th>
                                <th>Alumnos (Aprox)</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-categorias">
                            @forelse($categories as $cat)
                            <tr data-id="{{ $cat->id }}" data-model="categories">
                                <td>{{ $cat->name }}</td>
                                <td>{{ $cat->min_birth_year }}</td>
                                <td>{{ $cat->max_birth_year }}</td>
                                <td>{{ $cat->schedule ?? 'Sin asignar' }}</td>
                                <td>{{ \Illuminate\Support\Facades\DB::connection('tenant')->table('players')->where('category', $cat->name)->count() }}</td>
                                <td>
                                    <i class="fa-solid fa-pen-to-square" style="color:var(--primary); cursor:pointer; margin-right:10px;" onclick="editRow('categories', this)"></i>
                                    <i class="fa-solid fa-trash" style="color:#FF6B6B; cursor:pointer;" onclick="deleteRow(this)"></i>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding: 2.5rem; color: var(--muted);">
                                    <i class="fa-solid fa-layer-group" style="font-size:2rem; margin-bottom:10px; display:block; opacity:0.5;"></i>
                                    No hay categorías registradas aún. Haz clic en "Nueva Categoría" para crear la primera.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- VISTA: ALUMNOS -->
        <div id="section-alumnos" class="section-view">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
                <div>
                    <h2><i class="fa-solid fa-users"></i> Directorio de Alumnos</h2>
                    <p style="color:var(--muted); font-size:0.9rem;">Agrega y administra la información de los jugadores.</p>
                </div>
                <button class="btn" onclick="addDemoRow('alumnos')"><i class="fa-solid fa-plus"></i> Nuevo Alumno</button>
            </div>
            
            <div class="card">
                <h3 style="margin-bottom: 1.5rem;">Registrar Nuevo Alumno</h3>
                <form onsubmit="event.preventDefault(); 
    const data = {
        first_name: this.first_name.value,
        last_name: this.last_name.value,
        birth_date: this.birth_date.value,
        category: this.category.value,
        goals: this.goals.value || 0,
        status: 'active'
    };
    saveCrud('players', data).then(res => {
        if(res.success) {
            Swal.fire({icon: 'success', title: 'Alumno Registrado', background: '#1a1a24', color: '#fff', timer:1500, showConfirmButton:false}).then(() => window.location.reload());
        }
    });" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; align-items: end;">

                    <div class="form-group" style="margin:0;">
                        <label>Nombre(s)</label>
                        <input type="text" name="first_name" required>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label>Apellidos</label>
                        <input type="text" name="last_name" required>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label>Fecha Nacimiento</label>
                        <input type="date" name="birth_date" required>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label>Categoría Sugerida</label>
                        <select name="category">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label>Goles Iniciales</label>
                        <input type="number" name="goals" value="0" required>
                    </div>
                    <button type="submit" class="btn" style="height: 45px;"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                </form>
            </div>

            <div class="card" style="padding:0; overflow:hidden;">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre Completo</th>
                                <th>Edad / F. Nac</th>
                                <th>Categoría</th>
                                <th>Goles</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($players as $player)
                            <tr data-id="{{ $player->id }}" data-model="players">
                                <td><strong>{{ $player->first_name }} {{ $player->last_name }}</strong></td>
                                <td>{{ $player->birth_date }}</td>
                                <td><span style="color:var(--muted)"><i class="fa-solid fa-tag"></i> {{ $player->category ?? 'Por asignar' }}</span></td>
                                <td>{{ $player->goals ?? 0 }}</td>
                                <td><span class="badge-status">{{ $player->status }}</span></td>
                                <td>
                                    <i class="fa-solid fa-pen-to-square" style="color:var(--primary); cursor:pointer; margin-right:10px;" onclick="editRow('players', this)" title="Editar"></i>
                                    <i class="fa-solid fa-trash" style="color:#FF6B6B; cursor:pointer;" onclick="deleteRow(this)" title="Eliminar"></i>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding: 2.5rem; color: var(--muted);">
                                    <i class="fa-solid fa-user-plus" style="font-size:2rem; margin-bottom:10px; display:block; opacity:0.5;"></i>
                                    No hay alumnos registrados aún. ¡Registra tu primer alumno arriba!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- VISTA: TUTORES -->
        <div id="section-tutores" class="section-view">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
                <div>
                    <h2><i class="fa-solid fa-user-tie"></i> Registro de Tutores</h2>
                    <p style="color:var(--muted); font-size:0.9rem;">Padres de familia que recibirán notificaciones y realizarán pagos.</p>
                </div>
                <button class="btn" onclick="addDemoRow('tutores')"><i class="fa-solid fa-plus"></i> Añadir Tutor</button>
            </div>
            <div class="card" style="padding:0; overflow:hidden;">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre del Tutor</th>
                                <th>Teléfono / WhatsApp</th>
                                <th>Email</th>
                                <th>Alumnos Vinculados</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-tutores">
                            @forelse($tutors as $tut)
                            <tr data-id="{{ $tut->id }}" data-model="tutors">
                                <td>{{ $tut->name }}</td>
                                <td>{{ $tut->phone }}</td>
                                <td>{{ $tut->email }}</td>
                                <td>
                                    @php
                                        $linked = $tut->linked_students;
                                        if (is_string($linked) && str_starts_with($linked, '[')) {
                                            $decoded = json_decode($linked, true);
                                            if (is_array($decoded)) {
                                                $linked = implode(', ', $decoded);
                                            }
                                        }
                                    @endphp
                                    {{ $linked }}
                                </td>
                                <td>
                                    <i class="fa-solid fa-key" style="color:#F1C40F; cursor:pointer; margin-right:10px;" title="Credenciales App" onclick="manageTutorAccess(this)"></i>
                                    <i class="fa-solid fa-link" style="color:var(--accent); cursor:pointer; margin-right:10px;" title="Vincular Alumno" onclick="linkTutor(this)"></i>
                                    <i class="fa-solid fa-pen-to-square" style="color:var(--primary); cursor:pointer; margin-right:10px;" onclick="editRow('tutors', this)" title="Editar"></i>
                                    <i class="fa-solid fa-trash" style="color:#FF6B6B; cursor:pointer;" onclick="deleteRow(this)" title="Eliminar"></i>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding: 2.5rem; color: var(--muted);">
                                    <i class="fa-solid fa-user-tie" style="font-size:2rem; margin-bottom:10px; display:block; opacity:0.5;"></i>
                                    No hay tutores registrados aún. Haz clic en "Añadir Tutor" para crear uno.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- VISTA: PARTIDOS -->
        <div id="section-partidos" class="section-view">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
                <div>
                    <h2><i class="fa-solid fa-trophy"></i> Partidos y Convocatorias</h2>
                    <p style="color:var(--muted); font-size:0.9rem;">Gestiona los encuentros de las diferentes categorías y sus resultados.</p>
                </div>
                <button class="btn" onclick="addDemoRow('partidos')"><i class="fa-solid fa-plus"></i> Añadir Partido</button>
            </div>
            <div class="card" style="padding:0; overflow:hidden;">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Rival</th>
                                <th>Categoría</th>
                                <th>Fecha y Hora</th>
                                <th>Lugar</th>
                                <th>Resultado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-partidos">
                            @forelse($matches as $match)
                            <tr data-id="{{ $match->id }}" data-model="matches">
                                <td><strong>{{ $match->opponent }}</strong></td>
                                <td><span style="color:var(--muted)"><i class="fa-solid fa-tag"></i> {{ $match->category }}</span></td>
                                <td>{{ $match->match_date }} a las {{ $match->match_time }}</td>
                                <td>{{ $match->location }}</td>
                                <td><span class="badge-status">{{ $match->result ?? 'Pendiente' }}</span></td>
                                <td>
                                    <i class="fa-solid fa-pen-to-square" style="color:var(--primary); cursor:pointer; margin-right:10px;" onclick="editRow('matches', this)" title="Editar"></i>
                                    <i class="fa-solid fa-trash" style="color:#FF6B6B; cursor:pointer;" onclick="deleteRow(this)" title="Eliminar"></i>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding: 2.5rem; color: var(--muted);">
                                    <i class="fa-solid fa-trophy" style="font-size:2rem; margin-bottom:10px; display:block; opacity:0.5;"></i>
                                    No hay partidos programados aún. Haz clic en "Añadir Partido" para crear uno.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- VISTA: PAGOS -->
        <div id="section-pagos" class="section-view">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
                <div>
                    <h2><i class="fa-solid fa-file-invoice-dollar"></i> Finanzas y Cobranza</h2>
                    <p style="color:var(--muted); font-size:0.9rem;">Lleva el control de mensualidades, inscripciones y adeudos.</p>
                </div>
                <button class="btn" onclick="addDemoRow('pagos')"><i class="fa-solid fa-hand-holding-dollar"></i> Registrar Cobro</button>
            </div>
            <div class="card" style="padding:0; overflow:hidden;">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Concepto</th>
                                <th>Alumno / Tutor</th>
                                <th>Monto</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-pagos">
                            @forelse($payments as $pay)
                            <tr data-id="{{ $pay->id }}" data-model="payments">
                                <td>{{ $pay->folio }}</td>
                                <td>{{ $pay->concept }}</td>
                                <td>{{ $pay->student_tutor }}</td>
                                <td>$ {{ number_format($pay->amount, 2) }}</td>
                                <td><span class="badge-status" style="background:{{ $pay->status == 'Pagado' ? '#25D366' : '#FF6B6B' }}">{{ $pay->status }}</span></td>
                                <td>
                                    <i class="fa-solid fa-pen-to-square" style="color:var(--primary); cursor:pointer; margin-right:10px;" onclick="editRow('payments', this)" title="Editar"></i>
                                    <i class="fa-solid fa-trash" style="color:#FF6B6B; cursor:pointer;" onclick="deleteRow(this)" title="Eliminar"></i>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding: 2.5rem; color: var(--muted);">
                                    <i class="fa-solid fa-receipt" style="font-size:2rem; margin-bottom:10px; display:block; opacity:0.5;"></i>
                                    No hay cobros registrados aún. Haz clic en "Registrar Cobro" para crear uno.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- VISTA: CONFIGURACIÓN -->
        <div id="section-config" class="section-view">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
                <div>
                    <h2><i class="fa-solid fa-gear"></i> Configuración de la Academia</h2>
                    <p style="color:var(--muted); font-size:0.9rem;">Ajustes generales e integraciones.</p>
                </div>
            </div>
            
            <div class="card">
                <h3 style="margin-bottom: 1.5rem; color: var(--accent);"><i class="fa-brands fa-stripe"></i> Integración con Stripe</h3>
                <p style="color:var(--muted); font-size:0.9rem; margin-bottom: 1.5rem;">Configura tus claves de API de Stripe para que los padres puedan pagar directamente en el portal y el dinero llegue a tu cuenta bancaria.</p>
                
                <form action="{{ route('tenant.settings.stripe') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Stripe Public Key</label>
                        <input type="text" name="stripe_public_key" value="{{ $tenant->stripe_public_key ?? '' }}" placeholder="pk_test_..." required>
                    </div>
                    <div class="form-group">
                        <label>Stripe Secret Key</label>
                        <input type="password" name="stripe_secret_key" value="{{ $tenant->stripe_secret_key ?? '' }}" placeholder="sk_test_..." required>
                    </div>
                    <button type="submit" class="btn"><i class="fa-solid fa-floppy-disk"></i> Guardar Claves de Stripe</button>
                </form>
            </div>
            <div class="card">
                <h3 style="margin-bottom: 1.5rem; color: var(--accent);"><i class="fa-solid fa-palette"></i> Branding y Personalización (Exclusivo Premium)</h3>
                
                @if(strtolower($tenant->plan) === 'premium')
                    <p style="color:var(--muted); font-size:0.9rem; margin-bottom: 1.5rem;">Sube el logotipo de tu academia y elige tu color corporativo principal.</p>
                    <form action="{{ route('tenant.settings.branding') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                        <label>Nombre de la Academia</label>
                        <input type="text" name="academy_name" value="{{ $tenant->academy_name }}" class="swal2-input" style="width:100%; box-sizing: border-box;" required>
                    </div>
                    <div class="form-group">
                        <label>Logotipo (JPG, PNG)</label>
                            <input type="file" name="logo" accept="image/*">
                            @if($tenant->logo_path)
                                <img src="{{ asset('storage/' . $tenant->logo_path) }}" alt="Logo" style="max-height:50px; margin-top:10px;">
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Color Principal (Hexadecimal)</label>
                            <div style="display:flex; gap:10px; align-items:center;">
                                <input type="color" name="color_palette" value="{{ $tenant->color_palette ?? '#9D4EDD' }}" style="width:50px; height:50px; padding:0; cursor:pointer; background:transparent; border:none;">
                                <span style="color:var(--muted); font-size:0.9rem;">Selecciona el color de tus botones y portal</span>
                            </div>
                        </div>
                        <button type="submit" class="btn"><i class="fa-solid fa-floppy-disk"></i> Guardar Branding</button>
                    </form>
                @else
                    <div style="background: rgba(157,78,221,0.1); padding: 2rem; border-radius: 10px; text-align: center; border: 1px dashed var(--primary);">
                        <i class="fa-solid fa-lock" style="font-size: 2rem; color: var(--primary); margin-bottom: 1rem;"></i>
                        <h4 style="margin-bottom: 0.5rem;">Exclusivo del Plan Premium</h4>
                        <p style="color: var(--muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Actualiza a Premium para subir tu propio logotipo y modificar los colores de tu portal.</p>
                        <button type="button" class="btn" style="background: linear-gradient(135deg, #FFD700, #FFA500); color: #000; font-weight: 700;" onclick="openUpgradeModal()">
                            <i class="fa-solid fa-crown"></i> Desbloquear con Plan Premium
                        </button>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <script>
        const globalCategories = @json($categories ?? []);
        const globalPlayers = @json($players ?? []);

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.addEventListener("DOMContentLoaded", () => {
            let activeTab = localStorage.getItem('activeTabTenant') || 'inicio';
            let tabElement = document.querySelector(`.nav-item[data-tab="${activeTab}"]`);
            if(tabElement && !tabElement.classList.contains('locked')) {
                switchTab(activeTab, tabElement);
            } else {
                switchTab('inicio', document.querySelector('.nav-item[data-tab="inicio"]'));
            }
        });

        function switchTab(id, element) {
            document.querySelectorAll('.section-view').forEach(el => el.style.display = 'none');
            const targetSection = document.getElementById('section-' + id);
            if(targetSection) targetSection.style.display = 'block';
            
            document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
            if(element) element.classList.add('active');
            
            localStorage.setItem('activeTabTenant', id);
        }
        window.switchTab = switchTab;

        function showUpgradeAlert() {
            Swal.fire({
                title: 'Función Bloqueada 🔒',
                html: `Esta sección no está incluida en tu plan actual (<strong>{{ strtoupper($tenant->plan) }}</strong>).<br><br>
                       ¿Deseas mejorar al <strong>Plan Premium</strong> para desbloquear alumnos ilimitados, gestión de partidos y configuración completa?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-crown"></i> Mejorar a Premium',
                cancelButtonText: 'Más tarde',
                confirmButtonColor: '#9D4EDD',
                cancelButtonColor: '#333',
                background: '#1a1a24',
                color: '#fff'
            }).then((res) => {
                if (res.isConfirmed) {
                    openUpgradeModal();
                }
            });
        }
        window.showUpgradeAlert = showUpgradeAlert;

        function openUpgradeModal() {
            Swal.fire({
                title: '💎 Mejorar a Plan Premium',
                html: `
                    <div style="text-align: left; font-size: 0.95rem; line-height: 1.6; margin-bottom: 1rem; background: rgba(157,78,221,0.1); padding: 1rem; border-radius: 12px; border: 1px solid var(--primary);">
                        <p style="font-weight: 600; color: #fff; margin-bottom: 0.5rem;">Beneficios exclusivos de Premium:</p>
                        <ul style="margin-left: 1.2rem; color: #E0AAFF; font-size: 0.9rem;">
                            <li>👑 <strong>Alumnos Ilimitados</strong> (Sin tope de jugadores)</li>
                            <li>👑 <strong>Categorías y Horarios Ilimitados</strong></li>
                            <li>👑 <strong>Módulo de Partidos y Resultados</strong></li>
                            <li>👑 <strong>Personalización Total de Logo y Colores</strong></li>
                            <li>👑 <strong>Multi-administradores y Soporte VIP</strong></li>
                        </ul>
                    </div>
                    <p style="font-size: 0.9rem; color: var(--muted);">¿Deseas activar el <strong>Plan Premium</strong> en tu academia?</p>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-bolt"></i> ¡Activar Plan Premium Ahora!',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#9D4EDD',
                cancelButtonColor: '#333',
                background: '#1a1a24',
                color: '#fff'
            }).then((res) => {
                if (res.isConfirmed) {
                    Swal.fire({
                        title: 'Mejorando tu plan...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); },
                        background: '#1a1a24',
                        color: '#fff'
                    });

                    fetch("{{ route('tenant.upgrade-plan') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ plan: 'premium' })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Plan Actualizado!',
                                text: data.message,
                                background: '#1a1a24',
                                color: '#fff',
                                confirmButtonColor: '#9D4EDD'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message || 'No se pudo actualizar el plan', 'error');
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Ocurrió un error al procesar el cambio de plan: ' + err, 'error');
                    });
                }
            });
        }
        window.openUpgradeModal = openUpgradeModal;

        async function saveCrud(model, data) {
            try {
                let res = await fetch(`/api/crud/${model}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(data)
                });
                let json = await res.json();
                if(!res.ok) throw new Error(json.message || 'Error del servidor');
                return json;
            } catch (e) {
                Swal.fire('Error', e.message, 'error');
                return { success: false };
            }
        }

        async function updateCrud(model, id, data) {
            data._method = 'PUT'; // SPOOFING PARA HOSTINGER
            try {
                let res = await fetch(`/api/crud/${model}/${id}`, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': csrfToken,
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: JSON.stringify(data)
                });
                let json = await res.json();
                if(!res.ok) throw new Error(json.message || 'Error del servidor');
                return json;
            } catch (e) {
                Swal.fire('Error', e.message, 'error');
                return { success: false };
            }
        }

        async function deleteCrud(model, id) {
            try {
                let res = await fetch(`/api/crud/${model}/${id}`, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': csrfToken,
                        'X-HTTP-Method-Override': 'DELETE'
                    },
                    body: JSON.stringify({ _method: 'DELETE' }) // SPOOFING PARA HOSTINGER
                });
                let json = await res.json();
                if(!res.ok) throw new Error(json.message || 'Error del servidor');
                return json;
            } catch (e) {
                Swal.fire('Error', e.message, 'error');
                return { success: false };
            }
        }
function addDemoRow(type) {
            let catOptions = '<option value="">Seleccione Categoría</option>';
            if(typeof globalCategories !== 'undefined') {
                globalCategories.forEach(c => {
                    catOptions += `<option value="${c.name}">${c.name}</option>`;
                });
            }
            
            let playerOptions = '<option value="">Seleccione Alumno</option>';
            if(typeof globalPlayers !== 'undefined') {
                globalPlayers.forEach(p => {
                    playerOptions += `<option value="${p.first_name} ${p.last_name}">${p.first_name} ${p.last_name}</option>`;
                });
            }

            let yearOptions = '';
            for(let y=2010; y<=2026; y++) yearOptions += `<option value="${y}">${y}</option>`;

            if(type === 'categorias') {
                Swal.fire({
                    title: 'Añadir Categoría',
                    html: `
                        <div style="text-align:left;">
                            <label style="color:var(--muted); font-size:0.9rem;">Nombre (Ej. Sub-14)</label>
                            <input id="swal-input1" class="swal2-input" placeholder="Nombre">
                            
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Año Mínimo</label>
                            <select id="swal-input2" class="swal2-input" style="width:100%; box-sizing: border-box;">${yearOptions}</select>
                            
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Año Máximo</label>
                            <select id="swal-input3" class="swal2-input" style="width:100%; box-sizing: border-box;">${yearOptions}</select>
                            
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Días de Entrenamiento</label>
                            <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:10px; margin-top:10px;" id="swal-days">
                                <label><input type="checkbox" value="Lun"> Lunes</label>
                                <label><input type="checkbox" value="Mar"> Martes</label>
                                <label><input type="checkbox" value="Mie"> Miércoles</label>
                                <label><input type="checkbox" value="Jue"> Jueves</label>
                                <label><input type="checkbox" value="Vie"> Viernes</label>
                                <label><input type="checkbox" value="Sab"> Sábado</label>
                                <label><input type="checkbox" value="Dom"> Domingo</label>
                            </div>
                            
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Hora de Entrenamiento</label>
                            <input type="time" id="swal-time" class="swal2-input" style="width:100%; box-sizing: border-box;">
                        </div>
                    `,
                    background: '#1a1a24', color: '#fff', confirmButtonColor: '#9D4EDD', confirmButtonText: 'Guardar',
                    didOpen: () => { document.getElementById('swal-input1').dispatchEvent(new Event('change')); },
                    preConfirm: () => {
                        let selectedDays = [];
                        document.querySelectorAll('#swal-days input:checked').forEach(cb => selectedDays.push(cb.value));
                        let timeVal = document.getElementById('swal-time').value;
                        
                        let finalSchedule = '';
                        if (selectedDays.length > 0 && timeVal) {
                            let [h, m] = timeVal.split(':');
                            let ampm = h >= 12 ? 'PM' : 'AM';
                            let h12 = h % 12 || 12;
                            finalSchedule = selectedDays.join(', ') + ' ' + h12 + ':' + m + ' ' + ampm;
                        }

                        return {
                            name: document.getElementById('swal-input1').value,
                            min_birth_year: document.getElementById('swal-input2').value,
                            max_birth_year: document.getElementById('swal-input3').value,
                            schedule: finalSchedule
                        };
                    }
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        let res = await saveCrud('categories', result.value);
                        if (res.success) window.location.reload();
                        else Swal.fire('Error', res.message || 'Ocurrió un error al guardar', 'error');
                    }
                });
            } else if(type === 'tutores') {
                Swal.fire({
                    title: 'Añadir Tutor',
                    html: `
                        <div style="text-align:left;">
                            <input id="swal-input1" class="swal2-input" placeholder="Nombre Completo">
                            <input id="swal-input2" class="swal2-input" placeholder="Teléfono / WhatsApp">
                            <input id="swal-input3" class="swal2-input" placeholder="Correo Electrónico">
                            
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Vincular Alumnos (Ctrl/Cmd + Click para varios)</label>
                            <select id="swal-input4" multiple class="swal2-input" style="width:100%; box-sizing: border-box; height:100px;">${playerOptions}</select>
                        </div>
                    `,
                    background: '#1a1a24', color: '#fff', confirmButtonColor: '#9D4EDD', confirmButtonText: 'Guardar',
                    didOpen: () => { document.getElementById('swal-input1').dispatchEvent(new Event('change')); },
                    preConfirm: () => {
                        let selectedStudents = Array.from(document.getElementById('swal-input4').selectedOptions).map(opt => opt.value).join(', ');
                        return {
                            name: document.getElementById('swal-input1').value,
                            phone: document.getElementById('swal-input2').value,
                            email: document.getElementById('swal-input3').value,
                            linked_students: selectedStudents
                        };
                    }
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        let res = await saveCrud('tutors', result.value);
                        if (res.success) window.location.reload();
                        else Swal.fire('Error', res.message || 'Ocurrió un error al guardar', 'error');
                    }
                });
            } else if(type === 'partidos') {
                Swal.fire({
                    title: 'Añadir Partido',
                    html: `
                        <div style="text-align:left;">
                            <input id="swal-input1" class="swal2-input" placeholder="Rival (Ej. Club América)">
                            
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Categoría</label>
                            <select id="swal-input2" class="swal2-input" style="width:100%; box-sizing: border-box;">${catOptions}</select>
                            
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Fecha</label>
                            <input id="swal-input3" class="swal2-input" type="date">
                            
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Hora</label>
                            <input id="swal-input4" class="swal2-input" type="time">
                            
                            <input id="swal-input5" class="swal2-input" placeholder="Lugar (Ej. Cancha Local)">
                        </div>
                    `,
                    background: '#1a1a24', color: '#fff', confirmButtonColor: '#9D4EDD', confirmButtonText: 'Guardar',
                    didOpen: () => { document.getElementById('swal-input1').dispatchEvent(new Event('change')); },
                    preConfirm: () => {
                        return {
                            opponent: document.getElementById('swal-input1').value,
                            category: document.getElementById('swal-input2').value,
                            match_date: document.getElementById('swal-input3').value,
                            match_time: document.getElementById('swal-input4').value,
                            location: document.getElementById('swal-input5').value,
                            result: 'Pendiente'
                        };
                    }
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        let res = await saveCrud('matches', result.value);
                        if (res.success) window.location.reload();
                        else Swal.fire('Error', res.message || 'Ocurrió un error al guardar', 'error');
                    }
                });
            } else if(type === 'pagos') {
                Swal.fire({
                    title: 'Registrar Cobro',
                    html: `
                        <div style="text-align:left;">
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Concepto</label>
                            <select id="swal-input1" class="swal2-input" style="width:100%; box-sizing: border-box;" onchange="
                                let v = this.value; 
                                let sel = document.getElementById('swal-input3-select');
                                if(v.includes('Mensualidad')) { sel.value = '500'; }
                                else if(v.includes('Inscripción')) { sel.value = '1000'; }
                                else if(v.includes('Uniforme')) { sel.value = '1500'; }
                                else if(v.includes('Torneo')) { sel.value = '800'; }
                                else { sel.value = 'otro'; }
                                sel.dispatchEvent(new Event('change'));
                            ">
                                <option value="Mensualidad Septiembre 2026">Mensualidad Septiembre 2026</option>
                                <option value="Mensualidad Octubre 2026">Mensualidad Octubre 2026</option>
                                <option value="Inscripción 2026">Inscripción 2026</option>
                                <option value="Uniforme Completo">Uniforme Completo</option>
                                <option value="Torneo Estatal">Torneo Estatal</option>
                                <option value="Otro">Otro (Especificar en observaciones)</option>
                            </select>
                            
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Alumno a cobrar</label>
                            <select id="swal-input2" class="swal2-input" style="width:100%; box-sizing: border-box;">${playerOptions}</select>
                            
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Monto a Cobrar ($)</label>
                            <select id="swal-input3-select" class="swal2-input" style="width:100%; box-sizing: border-box; margin-bottom: 5px;" onchange="if(this.value==='otro'){document.getElementById('swal-input3').style.display='block';}else{document.getElementById('swal-input3').style.display='none'; document.getElementById('swal-input3').value=this.value;}">
                                <option value="500">$500</option>
                                <option value="800">$800</option>
                                <option value="1000">$1000</option>
                                <option value="1500">$1500</option>
                                <option value="otro">Otro Monto (Manual)</option>
                            </select>
                            <input id="swal-input3" class="swal2-input" type="number" placeholder="Monto manual" style="display:none; width:100%;" value="500">
                            
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Estado del Pago</label>
                            <select id="swal-input4" class="swal2-input" style="width:100%; box-sizing: border-box;">
                                <option value="Pendiente">Pendiente</option>
                                <option value="Pagado">Pagado</option>
                                <option value="Atrasado">Atrasado</option>
                            </select>
                        </div>
                    `,
                    background: '#1a1a24', color: '#fff', confirmButtonColor: '#9D4EDD', confirmButtonText: 'Guardar',
                    didOpen: () => { document.getElementById('swal-input1').dispatchEvent(new Event('change')); },
                    preConfirm: () => {
                        let folioStr = 'F' + Math.floor(Math.random()*10000);
                        return {
                            folio: folioStr,
                            concept: document.getElementById('swal-input1').value,
                            student_tutor: document.getElementById('swal-input2').value,
                            amount: document.getElementById('swal-input3').value,
                            status: document.getElementById('swal-input4').value
                        };
                    }
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        let res = await saveCrud('payments', result.value);
                        if (res.success) window.location.reload();
                        else Swal.fire('Error', res.message || 'Ocurrió un error al guardar', 'error');
                    }
                });
            } else if(type === 'alumnos') {
                Swal.fire({
                    title: 'Añadir Alumno',
                    html: `
                        <div style="text-align:left;">
                            <label style="color:var(--muted); font-size:0.9rem;">Nombre(s)</label>
                            <input id="swal-pl-fn" class="swal2-input" placeholder="Nombre(s)" style="width:100%; box-sizing: border-box;">
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Apellidos</label>
                            <input id="swal-pl-ln" class="swal2-input" placeholder="Apellidos" style="width:100%; box-sizing: border-box;">
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Fecha de Nacimiento</label>
                            <input id="swal-pl-dob" class="swal2-input" type="date" style="width:100%; box-sizing: border-box;">
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Categoría</label>
                            <select id="swal-pl-cat" class="swal2-input" style="width:100%; box-sizing: border-box;">${catOptions}</select>
                            <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Goles Iniciales</label>
                            <input id="swal-pl-gol" class="swal2-input" type="number" value="0" style="width:100%; box-sizing: border-box;">
                        </div>
                    `,
                    background: '#1a1a24', color: '#fff', confirmButtonColor: '#9D4EDD', confirmButtonText: 'Guardar',
                    preConfirm: () => {
                        let fn = document.getElementById('swal-pl-fn').value;
                        let ln = document.getElementById('swal-pl-ln').value;
                        if (!fn || !ln) {
                            Swal.showValidationMessage('Nombre y Apellidos son obligatorios');
                            return false;
                        }
                        return {
                            first_name: fn,
                            last_name: ln,
                            birth_date: document.getElementById('swal-pl-dob').value || '2015-01-01',
                            category: document.getElementById('swal-pl-cat').value,
                            goals: document.getElementById('swal-pl-gol').value || 0,
                            status: 'active'
                        };
                    }
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        let res = await saveCrud('players', result.value);
                        if (res.success) window.location.reload();
                        else Swal.fire('Error', res.message || 'Ocurrió un error al guardar', 'error');
                    }
                });
            }
        }

        async function deleteRow(element) {
            let row = element.closest('tr');
            let model = row.getAttribute('data-model');
            let id = row.getAttribute('data-id');
            
            if (!id || id === 'dummy' || id.startsWith('demo-')) {
                row.remove();
                Swal.fire({title: 'Borrado', text: 'Registro de demostración ocultado.', icon: 'success', background: '#1a1a24', color: '#fff'});
                return;
            }

            Swal.fire({
                title: '¿Estás seguro?', text: "Esta acción no se puede deshacer.", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#FF6B6B', cancelButtonColor: 'rgba(255,255,255,0.1)', confirmButtonText: 'Sí, borrar', background: '#1a1a24', color: '#fff'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    let data = await deleteCrud(model, id);
                    if(data.success) {
                        row.remove();
                        Swal.fire({title: 'Borrado', text: 'Registro eliminado.', icon: 'success', background: '#1a1a24', color: '#fff'});
                    }
                }
            });
        }

        function editRow(model, element) {
            let row = element.closest('tr');
            let id = row.getAttribute('data-id');
            
            if (!id || id === 'dummy' || id.startsWith('demo-')) {
                Swal.fire({title: 'Fila de Demostración', text: 'Añade un registro real para poder editarlo.', icon: 'info', background: '#1a1a24', color: '#fff', confirmButtonColor: '#9D4EDD'});
                return;
            }

            let cells = row.querySelectorAll('td');
            
            let catOptions = '<option value="">Seleccione Categoría</option>';
            if(typeof globalCategories !== 'undefined') {
                globalCategories.forEach(c => { catOptions += `<option value="${c.name}">${c.name}</option>`; });
            }
            
            let playerOptions = '<option value="">Seleccione Alumno</option>';
            if(typeof globalPlayers !== 'undefined') {
                globalPlayers.forEach(p => { playerOptions += `<option value="${p.first_name} ${p.last_name}">${p.first_name} ${p.last_name}</option>`; });
            }

            let yearOptions = '';
            for(let y=2010; y<=2026; y++) yearOptions += `<option value="${y}">${y}</option>`;

            let htmlInputs = '<div style="text-align:left; margin-top:10px;">';
            
            if(model === 'categories') {
                let currentName = cells[0].innerText.trim();
                let currentRange = cells[1].innerText.trim(); // Ej "2010 - 2014"
                let currentSchedule = cells[2].innerText.trim();
                
                let currentMin = '', currentMax = '';
                if(currentRange.includes(' - ')) {
                    [currentMin, currentMax] = currentRange.split(' - ');
                }

                htmlInputs += `
                    <label style="color:var(--muted); font-size:0.9rem;">Nombre</label>
                    <input id="edit-cat-name" class="swal2-input" value="${currentName}" style="width:100%; box-sizing: border-box;">
                    
                    <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Año Mínimo</label>
                    <select id="edit-cat-min" class="swal2-input" style="width:100%; box-sizing: border-box;">${yearOptions}</select>
                    
                    <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Año Máximo</label>
                    <select id="edit-cat-max" class="swal2-input" style="width:100%; box-sizing: border-box;">${yearOptions}</select>
                    
                    <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Días de Entrenamiento</label>
                    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:10px; margin-top:10px;" id="edit-cat-days">
                        <label><input type="checkbox" value="Lun"> Lunes</label>
                        <label><input type="checkbox" value="Mar"> Martes</label>
                        <label><input type="checkbox" value="Mie"> Miércoles</label>
                        <label><input type="checkbox" value="Jue"> Jueves</label>
                        <label><input type="checkbox" value="Vie"> Viernes</label>
                        <label><input type="checkbox" value="Sab"> Sábado</label>
                        <label><input type="checkbox" value="Dom"> Domingo</label>
                    </div>
                    
                    <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Hora de Entrenamiento</label>
                    <input type="time" id="edit-cat-time" class="swal2-input" style="width:100%; box-sizing: border-box;">
                `;
            } else if(model === 'players') {
                let currentName = cells[0].innerText.trim();
                let currentAge = cells[1].innerText.trim(); 
                let currentCat = cells[2].innerText.replace('Sub-', '').trim(); 
                // A workaround for category if it has an icon
                let catText = cells[2].textContent.trim(); 
                let goalsText = cells[3] ? cells[3].innerText.trim() : '0';
                let statusText = cells[4] ? cells[4].innerText.trim() : 'Activo';
                
                htmlInputs += `
                    <label style="color:var(--muted); font-size:0.9rem;">Nombre Completo</label>
                    <input id="edit-pl-name" class="swal2-input" value="${currentName}" style="width:100%; box-sizing: border-box;">
                    
                    <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Categoría</label>
                    <select id="edit-pl-cat" class="swal2-input" style="width:100%; box-sizing: border-box;">${catOptions}</select>
                    
                    <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Goles</label>
                    <input id="edit-pl-gol" class="swal2-input" type="number" value="${goalsText}" style="width:100%; box-sizing: border-box;">

                    <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Estado</label>
                    <select id="edit-pl-status" class="swal2-input" style="width:100%; box-sizing: border-box;">
                        <option value="active" ${statusText === 'Activo' ? 'selected' : ''}>Activo</option>
                        <option value="debt" ${statusText === 'Adeudo' ? 'selected' : ''}>Adeudo</option>
                        <option value="inactive" ${statusText === 'Inactivo' ? 'selected' : ''}>Inactivo</option>
                    </select>
                `;
            } else if(model === 'tutors') {
                let name = cells[0].innerText.trim();
                let phone = cells[1].innerText.trim();
                let email = cells[2].innerText.trim();
                let students = cells[3].innerText.trim();
                
                htmlInputs += `
                    <label style="color:var(--muted); font-size:0.9rem;">Nombre</label>
                    <input id="edit-tu-name" class="swal2-input" value="${name}" style="width:100%; box-sizing: border-box;">
                    
                    <label style="color:var(--muted); font-size:0.9rem;">Teléfono</label>
                    <input id="edit-tu-phone" class="swal2-input" value="${phone}" style="width:100%; box-sizing: border-box;">
                    
                    <label style="color:var(--muted); font-size:0.9rem;">Email</label>
                    <input id="edit-tu-email" class="swal2-input" value="${email}" style="width:100%; box-sizing: border-box;">
                    
                    <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Alumnos Vinculados (Ctrl/Cmd + Click para varios)</label>
                    <select id="edit-tu-stu" multiple class="swal2-input" style="width:100%; box-sizing: border-box; height:100px;">${playerOptions}</select>
                `;
            } else if(model === 'matches') {
                let opponent = cells[0].innerText.trim();
                let catText = cells[1].textContent.trim();
                let datetime = cells[2].innerText.trim();
                let loc = cells[3].innerText.trim();
                let result = cells[4].innerText.trim();
                
                htmlInputs += `
                    <label style="color:var(--muted); font-size:0.9rem;">Rival</label>
                    <input id="edit-ma-opp" class="swal2-input" value="${opponent}" style="width:100%; box-sizing: border-box;">
                    
                    <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Categoría</label>
                    <select id="edit-ma-cat" class="swal2-input" style="width:100%; box-sizing: border-box;">${catOptions}</select>
                    
                    <label style="color:var(--muted); font-size:0.9rem;">Fecha y Hora (Texto)</label>
                    <input id="edit-ma-dt" class="swal2-input" value="${datetime}" style="width:100%; box-sizing: border-box;">
                    
                    <label style="color:var(--muted); font-size:0.9rem;">Lugar</label>
                    <input id="edit-ma-loc" class="swal2-input" value="${loc}" style="width:100%; box-sizing: border-box;">
                    
                    <label style="color:var(--muted); font-size:0.9rem;">Resultado</label>
                    <input id="edit-ma-res" class="swal2-input" value="${result}" style="width:100%; box-sizing: border-box;">
                `;
            } else if(model === 'payments') {
                let concept = cells[1].innerText.trim();
                let stu = cells[2].innerText.trim();
                let amount = cells[3].innerText.replace('$', '').trim();
                let status = cells[4].innerText.trim();
                
                htmlInputs += `
                    <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Concepto</label>
                    <select id="edit-pa-con" class="swal2-input" style="width:100%; box-sizing: border-box;" onchange="
                        let v = this.value;
                        let amt = document.getElementById('edit-pa-amt');
                        if(v.includes('Mensualidad')) { amt.value = '500'; }
                        else if(v.includes('Inscripción')) { amt.value = '1000'; }
                        else if(v.includes('Uniforme')) { amt.value = '1500'; }
                    ">
                        <option value="Mensualidad Septiembre 2026">Mensualidad Septiembre 2026</option>
                        <option value="Mensualidad Octubre 2026">Mensualidad Octubre 2026</option>
                        <option value="Inscripción 2026">Inscripción 2026</option>
                        <option value="Uniforme Completo">Uniforme Completo</option>
                        <option value="Otro">Otro</option>
                    </select>
                    
                    <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Alumno</label>
                    <select id="edit-pa-stu" class="swal2-input" style="width:100%; box-sizing: border-box;">${playerOptions}</select>
                    
                    <label style="color:var(--muted); font-size:0.9rem;">Monto</label>
                    <input id="edit-pa-amt" class="swal2-input" value="${amount}" style="width:100%; box-sizing: border-box;">
                    
                    <label style="color:var(--muted); font-size:0.9rem; display:block; margin-top:10px;">Estado</label>
                    <select id="edit-pa-sta" class="swal2-input" style="width:100%; box-sizing: border-box;">
                        <option value="Pendiente" ${status === 'Pendiente' ? 'selected' : ''}>Pendiente</option>
                        <option value="Pagado" ${status === 'Pagado' ? 'selected' : ''}>Pagado</option>
                        <option value="Atrasado" ${status === 'Atrasado' ? 'selected' : ''}>Atrasado</option>
                    </select>
                `;
            }
            
            htmlInputs += '</div>';
            
            Swal.fire({
                title: 'Editar Registro',
                html: htmlInputs,
                background: '#1a1a24', color: '#fff', confirmButtonColor: '#9D4EDD', confirmButtonText: 'Guardar Cambios',
                showCancelButton: true, cancelButtonText: 'Cancelar', cancelButtonColor: 'rgba(255,255,255,0.1)',
                didOpen: () => {
                    // Seleccionar opciones actuales en selectores si es posible
                    if(model === 'categories') {
                        let currentRange = cells[1].innerText.trim();
                        if(currentRange.includes(' - ')) {
                            document.getElementById('edit-cat-min').value = currentRange.split(' - ')[0];
                            document.getElementById('edit-cat-max').value = currentRange.split(' - ')[1];
                        }
                        
                        // "Lun, Mar a las 4:00 PM"
                        let scheduleText = cells[2].innerText.trim();
                        if (scheduleText) {
                            let parts = scheduleText.split(' a las ');
                            if (parts.length === 2) {
                                let daysStr = parts[0];
                                let timeStr = parts[1];
                                
                                daysStr.split(', ').forEach(day => {
                                    let cb = document.querySelector(`#edit-cat-days input[value="${day}"]`);
                                    if (cb) cb.checked = true;
                                });
                                
                                // parse "4:00 PM" -> "16:00"
                                let [t, ampm] = timeStr.split(' ');
                                if (t && ampm) {
                                    let [h, m] = t.split(':');
                                    h = parseInt(h);
                                    if (ampm === 'PM' && h < 12) h += 12;
                                    if (ampm === 'AM' && h === 12) h = 0;
                                    let hStr = h < 10 ? '0' + h : h;
                                    document.getElementById('edit-cat-time').value = `${hStr}:${m}`;
                                }
                            }
                        }
                    } else if(model === 'players') {
                        document.getElementById('edit-pl-cat').value = cells[2].textContent.trim();
                    } else if(model === 'tutors') {
                        let linked = cells[3].innerText.trim().split(', ');
                        Array.from(document.getElementById('edit-tu-stu').options).forEach(opt => {
                            if (linked.includes(opt.value)) opt.selected = true;
                        });
                    } else if(model === 'matches') {
                        document.getElementById('edit-ma-cat').value = cells[1].textContent.trim();
                    } else if(model === 'payments') {
                        document.getElementById('edit-pa-con').value = cells[1].innerText.trim();
                        document.getElementById('edit-pa-stu').value = cells[2].innerText.trim();
                        // No forzamos el onchange aquí para que respete el monto que ya tenía guardado,
                        // a menos que el usuario cambie el concepto manualmente después de abrir.
                    }
                },
                preConfirm: () => {
                    let data = {};
                    if(model === 'categories') {
                        let selectedDays = [];
                        document.querySelectorAll('#edit-cat-days input:checked').forEach(cb => selectedDays.push(cb.value));
                        let timeVal = document.getElementById('edit-cat-time').value;
                        
                        let finalSchedule = '';
                        if (selectedDays.length > 0 && timeVal) {
                            let [h, m] = timeVal.split(':');
                            let ampm = h >= 12 ? 'PM' : 'AM';
                            let h12 = h % 12 || 12;
                            finalSchedule = selectedDays.join(', ') + ' a las ' + h12 + ':' + m + ' ' + ampm;
                        }

                        data = {
                            name: document.getElementById('edit-cat-name').value, 
                            min_birth_year: document.getElementById('edit-cat-min').value, 
                            max_birth_year: document.getElementById('edit-cat-max').value,
                            schedule: finalSchedule
                        };
                    } else if(model === 'players') {
                        let fullName = document.getElementById('edit-pl-name').value;
                        let fn = fullName.split(' ')[0];
                        let ln = fullName.split(' ').slice(1).join(' ');
                        data = {
                            first_name: fn, 
                            last_name: ln, 
                            category: document.getElementById('edit-pl-cat').value, 
                            goals: document.getElementById('edit-pl-gol').value,
                            status: document.getElementById('edit-pl-status').value
                        };
                    } else if (model === 'tutors') {
                        let selectedStudents = Array.from(document.getElementById('edit-tu-stu').selectedOptions).map(opt => opt.value).join(', ');
                        data = {
                            name: document.getElementById('edit-tu-name').value, 
                            phone: document.getElementById('edit-tu-phone').value, 
                            email: document.getElementById('edit-tu-email').value, 
                            linked_students: selectedStudents
                        };
                    } else if (model === 'matches') {
                        let dt = document.getElementById('edit-ma-dt').value;
                        let dtsplit = dt.split(' a las ');
                        let d = dtsplit[0] || dt;
                        let t = dtsplit[1] || '';
                        data = {
                            opponent: document.getElementById('edit-ma-opp').value, 
                            category: document.getElementById('edit-ma-cat').value, 
                            match_date: d,
                            match_time: t,
                            location: document.getElementById('edit-ma-loc').value, 
                            result: document.getElementById('edit-ma-res').value
                        };
                    } else if (model === 'payments') {
                        data = {
                            concept: document.getElementById('edit-pa-con').value, 
                            student_tutor: document.getElementById('edit-pa-stu').value, 
                            amount: document.getElementById('edit-pa-amt').value, 
                            status: document.getElementById('edit-pa-sta').value
                        };
                    }
                    return data;
                }
            }).then(async (result) => {
                if(result.isConfirmed) {
                    let res = await updateCrud(model, id, result.value);
                    if (res.success) window.location.reload();
                    else Swal.fire('Error', res.message || 'Ocurrió un error al actualizar', 'error');
                }
            });
        }

        function linkTutor(element) {
            let row = element.closest('tr');
            let id = row.getAttribute('data-id');
            if (!id || id === 'dummy') {
                Swal.fire({title: 'Fila de Demostración', text: 'Esta fila es solo un ejemplo. Añade un tutor real para vincular alumnos.', icon: 'info', background: '#1a1a24', color: '#fff', confirmButtonColor: '#9D4EDD'});
                return;
            }

            let options = `@foreach($players as $player)<option value="{{ $player->first_name }} {{ $player->last_name }}">{{ $player->first_name }} {{ $player->last_name }} ({{ $player->category ?? 'Sin Categoría' }})</option>@endforeach`;
            Swal.fire({
                title: 'Vincular Alumno',
                html: `
                    <p style="color:var(--muted); font-size:0.9rem; margin-bottom:1rem;">Selecciona un alumno para vincularlo a este tutor.</p>
                    <select id="swal-student-select" style="width:100%; padding:0.8rem; background:rgba(255,255,255,0.1); border:1px solid #444; border-radius:5px; color:#fff;">
                        ${options}
                    </select>
                `,
                background: '#1a1a24', color: '#fff',
                confirmButtonColor: '#9D4EDD',
                confirmButtonText: 'Vincular',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                cancelButtonColor: 'rgba(255,255,255,0.1)',
                preConfirm: () => {
                    return document.getElementById('swal-student-select').value;
                }
            }).then(async (result) => {
                if(result.isConfirmed) {
                    let currentText = row.querySelectorAll('td')[3].innerText.trim();
                    let newText = currentText ? currentText + ', ' + result.value : result.value;
                    if(currentText === '0' || currentText === 'Ninguno' || currentText === '') newText = result.value;
                    let res = await updateCrud('tutors', id, { linked_students: newText });
                    if(res.success) {
                        Swal.fire({
                            title: 'Vinculado Correctamente',
                            text: result.value + ' ha sido vinculado a este tutor.',
                            icon: 'success',
                            background: '#1a1a24', color: '#fff', timer:1500, showConfirmButton:false
                        }).then(() => window.location.reload());
                    }
                }
            });
        }

        // Funcionalidad Real: WhatsApp (wa.me)
        
        function manageTutorAccess(element) {
            let row = element.closest('tr');
            let emailActual = 'tutor@ejemplo.com';
            let nombreActual = 'Tutor';
            if (row && row.querySelectorAll('td').length > 2) {
                nombreActual = row.querySelectorAll('td')[0].innerText.trim();
                emailActual = row.querySelectorAll('td')[2].innerText.trim();
            }
            
            Swal.fire({
                title: 'Credenciales de Acceso',
                html: `
                    <div style="text-align:left; margin-top:10px;">
                        <p style="color:var(--muted); font-size:0.9rem; margin-bottom:1rem;">Genera o modifica la contraseña de este tutor para que pueda ingresar a la App de Padres.</p>
                        <label style="color:var(--muted); font-size:0.9rem;">Correo Electrónico (Usuario):</label><br>
                        <input type="email" id="tutor-email" value="${emailActual}" style="width:100%; padding:0.8rem; background:rgba(255,255,255,0.1); border:1px solid #444; border-radius:5px; color:#fff; margin-top:0.5rem; margin-bottom:1rem;">
                        
                        <label style="color:var(--muted); font-size:0.9rem;">Nueva Contraseña:</label><br>
                        <input type="text" id="tutor-password" placeholder="Escribe una contraseña (ej. HE5-2026)" value="HE5-2026" style="width:100%; padding:0.8rem; background:rgba(255,255,255,0.1); border:1px solid #444; border-radius:5px; color:#fff; margin-top:0.5rem;">
                    </div>
                `,
                background: '#1a1a24',
                color: '#fff',
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-paper-plane"></i> Guardar Credenciales',
                confirmButtonColor: '#9D4EDD',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const email = document.getElementById('tutor-email').value;
                    const password = document.getElementById('tutor-password').value;
                    
                    if (!email || !password) {
                        Swal.showValidationMessage('El correo y la contraseña son requeridos');
                        return false;
                    }

                    return fetch('{{ route("tenant.tutors.credentials") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            name: nombreActual,
                            email: email,
                            password: password
                        })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error("Error del servidor");
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Hubo un error al guardar: ${error}`);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Credenciales Guardadas',
                        text: 'La nueva contraseña se ha guardado correctamente. El tutor ya puede iniciar sesión.',
                        icon: 'success',
                        background: '#1a1a24',
                        color: '#fff'
                    });
                }
            });
        }

        function connectWhatsApp() {
            Swal.fire({
                title: 'Prueba de WhatsApp',
                html: `
                    <div style="text-align:left; margin-top:1rem;">
                        <p style="color:var(--muted); font-size:0.9rem; margin-bottom:1rem;">Esta es la integración que usaremos (igual a la de tu sistema original). Prepara el mensaje y abre WhatsApp directamente, sin costos ni APIs externas.</p>
                        <label style="color:var(--muted); font-size:0.9rem;">Número de prueba (ej. tuyo):</label><br>
                        <input type="text" id="wa-phone" placeholder="Ej. 4921234567" style="width:100%; padding:0.8rem; background:rgba(255,255,255,0.1); border:1px solid #444; border-radius:5px; color:#fff; margin-top:0.5rem;"><br><br>
                        
                        <label style="color:var(--muted); font-size:0.9rem;">Mensaje:</label><br>
                        <textarea id="wa-msg" rows="3" style="width:100%; padding:0.8rem; background:rgba(255,255,255,0.1); border:1px solid #444; border-radius:5px; color:#fff; margin-top:0.5rem;">¡Hola! Este es un mensaje de prueba desde mi nueva plataforma SaaS.</textarea>
                    </div>
                `,
                background: '#1a1a24',
                color: '#fff',
                confirmButtonText: '<i class="fa-brands fa-whatsapp"></i> Enviar WhatsApp',
                confirmButtonColor: '#25D366',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                cancelButtonColor: 'rgba(255,255,255,0.1)',
                preConfirm: () => {
                    const phone = document.getElementById('wa-phone').value;
                    const msg = document.getElementById('wa-msg').value;
                    if (!phone) {
                        Swal.showValidationMessage('Por favor ingresa un número de teléfono');
                    }
                    return { phone, msg };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let cleanPhone = result.value.phone.replace(/\D/g, '');
                    if (cleanPhone.length === 10) {
                        cleanPhone = '52' + cleanPhone;
                    }
                    
                    const url = `https://wa.me/${cleanPhone}?text=${encodeURIComponent(result.value.msg)}`;
                    window.open(url, '_blank');
                    
                    Swal.fire({
                        title: '¡Abriendo WhatsApp!',
                        text: 'Se abrió una nueva pestaña para enviar tu mensaje.',
                        icon: 'success',
                        background: '#1a1a24',
                        color: '#fff',
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
            });
        }

        // Funcionalidad Demo: Reporte Financiero
        function generateReport() {
            Swal.fire({
                title: 'Generando Reporte...',
                text: 'Recopilando datos financieros de alumnos y pagos...',
                icon: 'info',
                background: '#1a1a24',
                color: '#fff',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                let currentRevenue = document.getElementById('dash-rev-count').innerText;
                const reportWindow = window.open('', '_blank');
                reportWindow.document.write(`
                    <html>
                    <head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
                        <title>Reporte Financiero - {{ $tenant->academy_name }}</title>
                        <style>
                            body { font-family: sans-serif; padding: 2rem; color: #333; }
                            h1 { color: #6a1eb0; }
                            table { width: 100%; border-collapse: collapse; margin-top: 2rem; }
                            th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                            th { background-color: #f4f4f4; }
                        </style>
                    
    <style>
        /* FIX SWEETALERT OVERFLOW */
        .swal2-input, .swal2-select, .swal2-textarea {
            max-width: 100% !important;
            box-sizing: border-box !important;
            width: 100% !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
    </style>
</head>
                    <body>
                        <h1>Balance General - {{ $tenant->academy_name }}</h1>
                        <p>Fecha de emisión: ${new Date().toLocaleDateString()}</p>
                        <hr>
                        <h3>Resumen Mensual</h3>
                        <table>
                            <tr><th>Concepto</th><th>Monto</th></tr>
                            <tr><td>Mensualidades y Abonos</td><td>${currentRevenue}</td></tr>
                            <tr><td style="text-align:right"><strong>Total Ingresos:</strong></td><td><strong>${currentRevenue}</strong></td></tr>
                        </table>
                        <script>
        const globalCategories = @json($categories ?? []);
        const globalPlayers = @json($players ?? []);

                            window.onload = function() { window.print(); }
                        <\/script>
                    </body>
                    </html>
                `);
                reportWindow.document.close();
            });
        }

        // Funcionalidad Demo: Marca Blanca
        function openWhiteLabel() {
            Swal.fire({
                title: 'Personaliza tu Academia',
                html: `
                    <div style="text-align:left; margin-top:1rem;">
                        <label style="color:var(--muted); font-size:0.9rem;">Color Principal del Sistema:</label><br>
                        <input type="color" id="themeColor" value="#9D4EDD" style="width:100%; height:40px; border:none; border-radius:5px; margin-top:0.5rem; cursor:pointer;"><br><br>
                        
                        <label style="color:var(--muted); font-size:0.9rem;">Nombre Comercial:</label><br>
                        <input type="text" id="academyNameInput" value="{{ $tenant->academy_name }}" style="width:100%; padding:0.8rem; background:rgba(255,255,255,0.1); border:1px solid #444; border-radius:5px; color:#fff; margin-top:0.5rem;"><br><br>

                        <label style="color:var(--muted); font-size:0.9rem;">Logo Oficial (Sube una imagen):</label><br>
                        <input type="file" id="academyLogoInput" accept="image/*" style="width:100%; padding:0.5rem; background:rgba(255,255,255,0.1); border:1px solid #444; border-radius:5px; color:#fff; margin-top:0.5rem;">
                        <p style="font-size:0.75rem; color:var(--muted); margin-top:5px;">Sube el logo de tu academia desde tu dispositivo.</p>
                    </div>
                `,
                background: '#1a1a24',
                color: '#fff',
                confirmButtonText: 'Aplicar Cambios',
                confirmButtonColor: '#9D4EDD',
                preConfirm: () => {
                    const newColor = document.getElementById('themeColor').value;
                    const newName = document.getElementById('academyNameInput').value;
                    const fileInput = document.getElementById('academyLogoInput');
                    
                    return new Promise((resolve) => {
                        if (fileInput.files && fileInput.files[0]) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                resolve({ newColor, newName, newLogo: e.target.result });
                            };
                            reader.readAsDataURL(fileInput.files[0]);
                        } else {
                            resolve({ newColor, newName, newLogo: null });
                        }
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Aplicar cambios en tiempo real usando CSS Variables
                    document.documentElement.style.setProperty('--primary', result.value.newColor);
                    document.documentElement.style.setProperty('--accent', result.value.newColor);
                    
                    // Actualizar el texto o imagen del logo en el navbar
                    const logoEl = document.querySelector('.logo');
                    if (logoEl) {
                        if (result.value.newLogo && result.value.newLogo.trim() !== '') {
                            logoEl.innerHTML = `<img src="${result.value.newLogo}" alt="${result.value.newName}" style="max-height: 40px; margin-right: 10px;"> <span>${result.value.newName}</span>`;
                        } else {
                            logoEl.innerText = result.value.newName;
                        }
                    }

                    Swal.fire({
                        title: '¡Apariencia Actualizada!',
                        text: 'Tus clientes ahora verán el panel con tus colores y marca oficial.',
                        icon: 'success',
                        background: '#1a1a24',
                        color: '#fff',
                        confirmButtonColor: result.value.newColor
                    });
                }
            });
        }

                function openParentPortalDemo() {
            Swal.fire({
                title: 'Vista de Tutor (Portal Móvil)',
                html: `
                <div id="demo-app-container" style="width: 300px; height: 500px; background: #fff; border-radius: 30px; margin: 0 auto; border: 10px solid #222; overflow: hidden; position: relative; font-family: sans-serif; color:#333; text-align: left;">
                    
                    <!-- Login Screen -->
                    <div id="demo-login-screen" style="height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px; background: var(--primary);">
                        <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 50%; margin-bottom: 20px;">
                            <i class="fa-solid fa-user-lock" style="font-size: 40px; color: #fff;"></i>
                        </div>
                        <h3 style="color: #fff; margin-bottom: 10px; text-align: center;">{{ $tenant->academy_name }}</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.85rem; text-align: center; margin-bottom: 30px;">Ingresa para ver el perfil de tu hijo</p>
                        
                        <input type="email" placeholder="Correo del Tutor" style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 8px; border: none; outline: none; box-sizing: border-box;" value="carlos.tutor@ejemplo.com">
                        <input type="password" placeholder="Contraseña" style="width: 100%; padding: 10px; margin-bottom: 25px; border-radius: 8px; border: none; outline: none; box-sizing: border-box;" value="123456">
                        
                        <button onclick="document.getElementById('demo-login-screen').style.display='none'; document.getElementById('demo-main-screen').style.display='block';" style="width: 100%; padding: 12px; background: #fff; color: var(--primary); font-weight: bold; border: none; border-radius: 8px; cursor: pointer;">Iniciar Sesión</button>
                    </div>

                    <!-- Main App Screen (Hidden initially) -->
                    <div id="demo-main-screen" style="display: none; height: 100%;">
                        <!-- App Header -->
                        <div style="background: var(--primary); padding: 1.5rem 1rem 1rem; color: #fff; text-align:center; border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                            <h4 style="margin:0; font-size:1.1rem;">{{ $tenant->academy_name }}</h4>
                            <p style="font-size:0.7rem; margin-top:5px; opacity:0.8;">Portal de Padres</p>
                        </div>
                        <!-- App Content -->
                        <div style="padding: 1rem; overflow-y: auto; height: calc(100% - 130px);">
                            <p style="font-size: 0.9rem; color: #666; margin-bottom: 10px;">Hola, Carlos</p>
                            
                            <div style="background: #f8f9fa; border: 1px solid #eee; border-radius: 10px; padding: 10px; margin-bottom: 15px;">
                                <h5 style="margin:0 0 5px 0; color:#333; font-size:0.9rem;">⚽ Mateo Martínez</h5>
                                <p style="margin:0; font-size:0.75rem; color:#888;">Categoría Sub-12</p>
                            </div>
                            
                            <div style="background: #fff3cd; border: 1px solid #ffe69c; border-radius: 10px; padding: 10px; margin-bottom: 15px;">
                                <h5 style="margin:0 0 5px 0; color:#856404; font-size:0.9rem;">⚠️ Pago Pendiente</h5>
                                <p style="margin:0; font-size:0.75rem; color:#856404;">Mensualidad Septiembre: <strong>$800.00</strong></p>
                                <button style="width:100%; margin-top:10px; padding:5px; background:#28a745; color:#fff; border:none; border-radius:5px; cursor:pointer;" onclick="alert('Demo: Procesando Pago Seguro...')">Pagar Ahora</button>
                            </div>
                            
                            <h5 style="margin: 0 0 10px 0; font-size:0.9rem; color:#333;">Últimos Avisos</h5>
                            <div style="background: #e2e3e5; border-radius: 8px; padding: 10px; font-size: 0.8rem;">
                                <strong style="color:#383d41;">Entrenamiento Cancelado</strong><br>
                                <span style="color:#666;">Hoy debido a lluvia.</span>
                            </div>
                        </div>
                        <!-- App Tab Bar -->
                        <div style="position: absolute; bottom: 0; width: 100%; background: #f8f9fa; border-top: 1px solid #ddd; display: flex; justify-content: space-around; padding: 10px 0;">
                            <i class="fa-solid fa-house" style="color:var(--primary)"></i>
                            <i class="fa-solid fa-calendar" style="color:#999"></i>
                            <i class="fa-solid fa-credit-card" style="color:#999"></i>
                            <i class="fa-solid fa-user" style="color:#999"></i>
                        </div>
                    </div>
                </div>
                `,
                background: 'rgba(0,0,0,0.8)',
                showConfirmButton: false,
                showCloseButton: true,
                width: 400
            });
        }

        // Simulación de polling para notificaciones
        setInterval(async () => {
            try {
                const res = await fetch('/api/notifications/unread');
                const data = await res.json();
                if (data.notifications && data.notifications.length > 0) {
                    let count = parseInt(document.getElementById('notif-count').innerText);
                    count += data.notifications.length;
                    document.getElementById('notif-count').innerText = count;
                    
                    data.notifications.forEach(n => {
                        Swal.fire({
                            title: n.data.title,
                            text: n.data.message,
                            icon: 'info',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000,
                            background: '#0a0a14',
                            color: '#F0E6FF',
                        });
                    });
                }
            } catch (e) {}
        }, 3000);
    </script>

    <!-- Driver.js & SaaS Interactive Tour -->
    <script src="{{ asset('vendor/driverjs/driver.js.iife.js') }}"></script>
    <script src="{{ asset('js/saas-tour.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof initAdminTour === 'function') {
                initAdminTour({
                    academyName: "{{ $tenant->academy_name }}",
                    plan: "{{ $tenant->plan }}"
                });
            }
        });
    </script>
</body>
</html>
