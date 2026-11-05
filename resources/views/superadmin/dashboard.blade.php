<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin Panel — AcademiaHE5</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Inter:wght@300;400;500;600&family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { --bg:#060610; --surface:rgba(157,78,221,0.07); --surface-hover:rgba(157,78,221,0.14); --primary:#9D4EDD; --accent:#E0AAFF; --text:#F0E6FF; --muted:#a78bca; --border:rgba(157,78,221,0.3); --green:#25D366; --red:#FF6B6B; --yellow:#F1C40F; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background:var(--bg); color:var(--text); font-family:'Inter',sans-serif; display:flex; min-height:100vh; overflow-x:hidden; }
        body::before { content:''; position:fixed; width:500px; height:500px; background:radial-gradient(circle, rgba(157,78,221,0.08) 0%, transparent 70%); top:-150px; right:-150px; pointer-events:none; }

        /* SIDEBAR */
        .sidebar { width:240px; background:rgba(0,0,0,0.5); border-right:1px solid var(--border); display:flex; flex-direction:column; padding:1.5rem 1rem; z-index:100; flex-shrink:0; }
        .logo { font-family:'Syne'; font-weight:800; font-size:1rem; color:var(--primary); margin-bottom:0.5rem; padding:1rem; text-align:center; }
        .logo-badge { background:rgba(157,78,221,0.2); border:1px solid var(--border); border-radius:20px; padding:3px 12px; font-size:0.7rem; color:var(--accent); text-align:center; margin-bottom:2rem; font-family:'Space Grotesk'; letter-spacing:1px; }
        .nav-item { display:flex; align-items:center; gap:0.8rem; padding:0.85rem 1rem; color:var(--muted); text-decoration:none; border-radius:10px; margin-bottom:0.4rem; cursor:pointer; transition:0.2s; font-family:'Space Grotesk'; font-size:0.9rem; }
        .nav-item:hover, .nav-item.active { background:var(--surface-hover); color:var(--accent); }
        .nav-item i { width:18px; text-align:center; }
        .sidebar-footer { margin-top:auto; border-top:1px solid var(--border); padding-top:1rem; }
        .btn-logout { display:flex; align-items:center; gap:0.8rem; padding:0.85rem 1rem; color:var(--red); border-radius:10px; cursor:pointer; font-family:'Space Grotesk'; font-size:0.9rem; text-decoration:none; transition:0.2s; }
        .btn-logout:hover { background:rgba(255,107,107,0.1); }

        /* MAIN */
        .main { flex:1; padding:2rem; overflow-y:auto; height:100vh; }
        .topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:2.5rem; }
        .page-title { font-family:'Syne'; font-size:1.6rem; font-weight:800; }
        .topbar-right { display:flex; align-items:center; gap:1rem; font-family:'Space Grotesk'; font-size:0.85rem; color:var(--muted); }
        .admin-chip { background:rgba(157,78,221,0.15); border:1px solid var(--border); border-radius:20px; padding:6px 14px; color:var(--accent); display:flex; align-items:center; gap:0.5rem; }

        /* STATS */
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:1.2rem; margin-bottom:2rem; }
        .stat-card { background:var(--surface); border:1px solid var(--border); border-radius:16px; padding:1.5rem; position:relative; overflow:hidden; transition:0.2s; }
        .stat-card:hover { border-color:var(--primary); transform:translateY(-2px); }
        .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:2px; border-radius:16px 16px 0 0; }
        .stat-card.blue::before { background:linear-gradient(90deg, var(--primary), var(--accent)); }
        .stat-card.green::before { background:linear-gradient(90deg, #25D366, #1aaa52); }
        .stat-card.yellow::before { background:linear-gradient(90deg, #F1C40F, #e67e22); }
        .stat-card.red::before { background:linear-gradient(90deg, #FF6B6B, #c0392b); }
        .stat-icon { font-size:1.5rem; margin-bottom:1rem; opacity:0.7; }
        .stat-value { font-family:'Syne'; font-size:2rem; font-weight:700; color:var(--accent); }
        .stat-label { color:var(--muted); font-size:0.75rem; text-transform:uppercase; letter-spacing:1px; font-family:'Space Grotesk'; margin-top:0.3rem; }

        /* CARD */
        .card { background:var(--surface); border:1px solid var(--border); border-radius:16px; padding:1.5rem; margin-bottom:1.5rem; }
        .card-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; }
        .card-title { font-family:'Syne'; font-size:1.1rem; font-weight:700; display:flex; align-items:center; gap:0.6rem; }

        /* TABLE */
        .table-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }
        table { width:100%; border-collapse:collapse; min-width:700px; }
        th { padding:0.9rem 1rem; color:var(--muted); border-bottom:1px solid var(--border); font-family:'Space Grotesk'; font-weight:500; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.5px; text-align:left; }
        td { padding:1rem; border-bottom:1px solid rgba(255,255,255,0.04); vertical-align:middle; }
        tr:hover td { background:rgba(157,78,221,0.04); }
        tr:last-child td { border-bottom:none; }

        /* BADGES */
        .badge { display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:20px; font-size:0.73rem; font-family:'Space Grotesk'; font-weight:600; }
        .badge-active { background:rgba(37,211,102,0.15); color:#25D366; border:1px solid rgba(37,211,102,0.3); }
        .badge-trial { background:rgba(241,196,15,0.15); color:#F1C40F; border:1px solid rgba(241,196,15,0.3); }
        .badge-suspended { background:rgba(255,107,107,0.15); color:#FF6B6B; border:1px solid rgba(255,107,107,0.3); }
        .badge-cancelled { background:rgba(150,150,150,0.15); color:#aaa; border:1px solid rgba(150,150,150,0.3); }
        .badge-plan { padding:3px 10px; border-radius:20px; font-size:0.72rem; font-family:'Space Grotesk'; font-weight:700; }
        .badge-basico { background:rgba(100,100,200,0.15); color:#8888ff; border:1px solid rgba(100,100,200,0.3); }
        .badge-elite { background:rgba(157,78,221,0.2); color:var(--accent); border:1px solid var(--border); }
        .badge-premium { background:linear-gradient(135deg, rgba(241,196,15,0.2), rgba(231,76,60,0.1)); color:#F1C40F; border:1px solid rgba(241,196,15,0.3); }

        /* BUTTONS */
        .btn { background:linear-gradient(135deg, var(--primary), #6a1eb0); color:#fff; border:none; padding:0.65rem 1.2rem; border-radius:8px; cursor:pointer; font-family:'Space Grotesk'; font-weight:600; font-size:0.85rem; display:inline-flex; align-items:center; gap:0.4rem; transition:0.2s; }
        .btn:hover { transform:translateY(-2px); box-shadow:0 4px 15px rgba(157,78,221,0.4); }
        .btn-sm { padding:5px 10px; font-size:0.78rem; border-radius:6px; }
        .btn-icon { background:transparent; border:1px solid rgba(255,255,255,0.1); color:var(--muted); padding:6px 8px; border-radius:6px; cursor:pointer; transition:0.2s; font-size:0.85rem; }
        .btn-icon:hover { border-color:var(--primary); color:var(--accent); }
        .btn-icon.danger:hover { border-color:var(--red); color:var(--red); background:rgba(255,107,107,0.1); }
        .btn-icon.success:hover { border-color:var(--green); color:var(--green); background:rgba(37,211,102,0.1); }
        .btn-icon.warning:hover { border-color:var(--yellow); color:var(--yellow); background:rgba(241,196,15,0.1); }

        /* SECTION */
        .section-view { display:none; animation:fadeIn 0.3s ease; }
        .section-view.active { display:block; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

        /* SEARCH */
        .search-bar { background:rgba(0,0,0,0.3); border:1px solid var(--border); border-radius:8px; padding:0.65rem 1rem; color:var(--text); font-family:'Inter'; font-size:0.9rem; width:240px; }
        .search-bar:focus { outline:none; border-color:var(--primary); }

        /* Empty state */
        .empty-state { text-align:center; padding:3rem; color:var(--muted); }
        .empty-state i { font-size:3rem; margin-bottom:1rem; opacity:0.4; }

        @media(max-width:768px) {
            body { flex-direction:column; }
            .sidebar { width:100%; flex-direction:row; flex-wrap:wrap; padding:0.8rem; border-right:none; border-bottom:1px solid var(--border); }
            .logo, .logo-badge, .sidebar-footer { display:none; }
            .nav-item { padding:0.5rem 0.8rem; font-size:0.8rem; }
            .main { padding:1rem; height:auto; }
            .stats-grid { grid-template-columns:1fr 1fr; }
        }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="logo"><i class="fa-solid fa-shield-halved"></i> AcademiaHE5</div>
        <div class="logo-badge">SUPERADMIN</div>

        <div class="nav-item active" onclick="switchTab('overview', this)">
            <i class="fa-solid fa-chart-pie"></i> Resumen
        </div>
        <div class="nav-item" onclick="switchTab('academias', this)">
            <i class="fa-solid fa-building"></i> Academias
        </div>
        <div class="nav-item" onclick="switchTab('planes', this)">
            <i class="fa-solid fa-tags"></i> Planes & Suscripciones
        </div>

        <div class="sidebar-footer">
            <div class="admin-chip" style="margin-bottom:1rem; font-size:0.78rem;">
                <i class="fa-solid fa-user-shield"></i> Admin
            </div>
            <a href="{{ route('superadmin.logout') }}" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
            </a>
        </div>
    </div>

    <!-- MAIN -->
    <div class="main">
        <div class="topbar">
            <div>
                <div class="page-title" id="page-title">Resumen de la Plataforma</div>
                <div style="color:var(--muted); font-size:0.82rem; margin-top:0.2rem;">AcademiaHE5 SaaS · Panel de Control Maestro</div>
            </div>
            <div class="topbar-right">
                <div class="admin-chip">
                    <i class="fa-solid fa-circle" style="color:#25D366; font-size:0.5rem;"></i>
                    Sistema Activo
                </div>
                <div>{{ now()->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        @if(session('success'))
        <div style="background:rgba(37,211,102,0.1); border:1px solid rgba(37,211,102,0.3); color:#25D366; padding:0.9rem 1.2rem; border-radius:10px; margin-bottom:1.5rem; display:flex; align-items:center; gap:0.7rem;">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <!-- ===== VISTA: OVERVIEW ===== -->
        <div id="section-overview" class="section-view active">
            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-icon"><i class="fa-solid fa-building" style="color:var(--primary)"></i></div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total Academias</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon"><i class="fa-solid fa-circle-check" style="color:#25D366"></i></div>
                    <div class="stat-value">{{ $stats['active'] }}</div>
                    <div class="stat-label">Activas</div>
                </div>
                <div class="stat-card yellow">
                    <div class="stat-icon"><i class="fa-solid fa-hourglass-half" style="color:#F1C40F"></i></div>
                    <div class="stat-value">{{ $stats['trial'] }}</div>
                    <div class="stat-label">En Prueba</div>
                </div>
                <div class="stat-card red">
                    <div class="stat-icon"><i class="fa-solid fa-ban" style="color:#FF6B6B"></i></div>
                    <div class="stat-value">{{ $stats['suspended'] }}</div>
                    <div class="stat-label">Suspendidas</div>
                </div>
            </div>

            <!-- Resumen por planes -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-tags" style="color:var(--primary)"></i> Distribución de Planes</div>
                    </div>
                    @php
                        $basicoCount = $tenants->where('plan','basico')->count();
                        $eliteCount  = $tenants->where('plan','elite')->count();
                        $premiumCount= $tenants->where('plan','premium')->count();
                        $total = max($tenants->count(), 1);
                    @endphp
                    <div style="display:flex; flex-direction:column; gap:1rem;">
                        <div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                                <span style="font-family:'Space Grotesk'; font-size:0.85rem;">Básico</span>
                                <span style="color:var(--muted); font-size:0.85rem;">{{ $basicoCount }}</span>
                            </div>
                            <div style="background:rgba(255,255,255,0.05); border-radius:20px; height:8px; overflow:hidden;">
                                <div style="background:#8888ff; height:100%; width:{{ $total>0 ? round($basicoCount/$total*100) : 0 }}%; border-radius:20px; transition:0.5s;"></div>
                            </div>
                        </div>
                        <div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                                <span style="font-family:'Space Grotesk'; font-size:0.85rem;">Elite</span>
                                <span style="color:var(--muted); font-size:0.85rem;">{{ $eliteCount }}</span>
                            </div>
                            <div style="background:rgba(255,255,255,0.05); border-radius:20px; height:8px; overflow:hidden;">
                                <div style="background:var(--primary); height:100%; width:{{ $total>0 ? round($eliteCount/$total*100) : 0 }}%; border-radius:20px; transition:0.5s;"></div>
                            </div>
                        </div>
                        <div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                                <span style="font-family:'Space Grotesk'; font-size:0.85rem;">Premium</span>
                                <span style="color:var(--muted); font-size:0.85rem;">{{ $premiumCount }}</span>
                            </div>
                            <div style="background:rgba(255,255,255,0.05); border-radius:20px; height:8px; overflow:hidden;">
                                <div style="background:linear-gradient(90deg, #F1C40F, #e67e22); height:100%; width:{{ $total>0 ? round($premiumCount/$total*100) : 0 }}%; border-radius:20px; transition:0.5s;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-clock-rotate-left" style="color:var(--primary)"></i> Últimas Altas</div>
                    </div>
                    @forelse($tenants->sortByDesc('created_at')->take(5) as $t)
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:0.7rem 0; border-bottom:1px solid rgba(255,255,255,0.04);">
                        <div>
                            <div style="font-family:'Space Grotesk'; font-size:0.9rem; font-weight:500;">{{ $t->academy_name }}</div>
                            <div style="color:var(--muted); font-size:0.75rem;">{{ $t->subdomain }}.academiahe5.com</div>
                        </div>
                        <span class="badge badge-plan badge-{{ strtolower($t->plan) }}">{{ strtoupper($t->plan) }}</span>
                    </div>
                    @empty
                    <div class="empty-state" style="padding:1.5rem;">
                        <i class="fa-solid fa-building" style="font-size:2rem; margin-bottom:0.5rem; display:block;"></i>
                        Sin academias registradas
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ===== VISTA: ACADEMIAS ===== -->
        <div id="section-academias" class="section-view">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-building" style="color:var(--primary)"></i> Todas las Academias ({{ $tenants->count() }})</div>
                    <input type="text" class="search-bar" id="search-academias" placeholder="🔍 Buscar academia..." oninput="filterTable()">
                </div>
                <div class="table-wrap">
                    <table id="tabla-academias">
                        <thead>
                            <tr>
                                <th>Academia</th>
                                <th>Subdominio</th>
                                <th>Plan</th>
                                <th>Estado</th>
                                <th>Propietario</th>
                                <th>Suscripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tenants as $t)
                            <tr data-name="{{ strtolower($t->academy_name) }} {{ strtolower($t->subdomain) }}">
                                <td>
                                    <div style="font-weight:600; font-family:'Space Grotesk';">{{ $t->academy_name }}</div>
                                    <div style="font-size:0.75rem; color:var(--muted);">ID: {{ $t->id }}</div>
                                </td>
                                <td>
                                    <a href="http://{{ $t->subdomain }}.academiahe5.com" target="_blank" style="color:var(--accent); text-decoration:none; font-size:0.85rem;">
                                        {{ $t->subdomain }}.academiahe5.com <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:0.7rem;"></i>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge badge-plan badge-{{ strtolower($t->plan) }}">{{ strtoupper($t->plan) }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($t->status) {
                                            'active' => 'badge-active',
                                            'trial'  => 'badge-trial',
                                            'suspended','cancelled' => $t->status === 'suspended' ? 'badge-suspended' : 'badge-cancelled',
                                            default  => 'badge-cancelled'
                                        };
                                        $statusIcon = match($t->status) {
                                            'active' => '●',
                                            'trial'  => '◐',
                                            'suspended' => '✕',
                                            default  => '○'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $statusIcon }} {{ strtoupper($t->status) }}</span>
                                </td>
                                <td>
                                    <div style="font-size:0.85rem;">{{ $t->owner_name }}</div>
                                    <div style="font-size:0.75rem; color:var(--muted);">{{ $t->owner_email }}</div>
                                </td>
                                <td style="font-size:0.82rem; color:var(--muted);">
                                    {{ $t->subscription_ends_at ? \Carbon\Carbon::parse($t->subscription_ends_at)->format('d/m/Y') : '—' }}
                                </td>
                                <td>
                                    <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                        <button class="btn-icon warning" title="Cambiar Plan" onclick="changePlan({{ $t->id }}, '{{ $t->plan }}', '{{ addslashes($t->academy_name) }}')">
                                            <i class="fa-solid fa-tags"></i>
                                        </button>
                                        @if($t->status === 'active' || $t->status === 'trial')
                                        <button class="btn-icon danger" title="Suspender" onclick="toggleStatus({{ $t->id }}, 'suspended', '{{ addslashes($t->academy_name) }}')">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                        @else
                                        <button class="btn-icon success" title="Activar" onclick="toggleStatus({{ $t->id }}, 'active', '{{ addslashes($t->academy_name) }}')">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </button>
                                        @endif
                                        <button class="btn-icon danger" title="Eliminar" onclick="deleteTenant({{ $t->id }}, '{{ addslashes($t->academy_name) }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-building-circle-xmark"></i>
                                        <p>No hay academias registradas todavía.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ===== VISTA: PLANES ===== -->
        <div id="section-planes" class="section-view">
            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:1.5rem; margin-bottom:2rem;">
                <!-- BÁSICO -->
                <div class="card" style="border-color:rgba(136,136,255,0.4);">
                    <div style="text-align:center; padding:0.5rem 0 1.5rem;">
                        <div style="font-size:2.5rem; margin-bottom:0.5rem;">🏫</div>
                        <div style="font-family:'Syne'; font-size:1.3rem; font-weight:800; color:#8888ff;">BÁSICO</div>
                        <div style="font-size:2rem; font-family:'Syne'; font-weight:700; margin:0.5rem 0;">$399<span style="font-size:0.9rem; color:var(--muted);">/mes</span></div>
                        <div style="color:var(--muted); font-size:0.82rem;">3 categorías · 40 alumnos</div>
                    </div>
                    <div style="background:rgba(136,136,255,0.1); border-radius:10px; padding:1rem; text-align:center;">
                        <div style="font-size:1.8rem; font-family:'Syne'; font-weight:700; color:#8888ff;">{{ $tenants->where('plan','basico')->count() }}</div>
                        <div style="color:var(--muted); font-size:0.78rem;">academias suscritas</div>
                    </div>
                </div>

                <!-- ELITE -->
                <div class="card" style="border-color:var(--border);">
                    <div style="text-align:center; padding:0.5rem 0 1.5rem;">
                        <div style="font-size:2.5rem; margin-bottom:0.5rem;">⚡</div>
                        <div style="font-family:'Syne'; font-size:1.3rem; font-weight:800; color:var(--accent);">ELITE</div>
                        <div style="font-size:2rem; font-family:'Syne'; font-weight:700; margin:0.5rem 0;">$699<span style="font-size:0.9rem; color:var(--muted);">/mes</span></div>
                        <div style="color:var(--muted); font-size:0.82rem;">6 categorías · 150 alumnos</div>
                    </div>
                    <div style="background:rgba(157,78,221,0.15); border-radius:10px; padding:1rem; text-align:center;">
                        <div style="font-size:1.8rem; font-family:'Syne'; font-weight:700; color:var(--accent);">{{ $tenants->where('plan','elite')->count() }}</div>
                        <div style="color:var(--muted); font-size:0.78rem;">academias suscritas</div>
                    </div>
                </div>

                <!-- PREMIUM -->
                <div class="card" style="border-color:rgba(241,196,15,0.4);">
                    <div style="text-align:center; padding:0.5rem 0 1.5rem;">
                        <div style="font-size:2.5rem; margin-bottom:0.5rem;">👑</div>
                        <div style="font-family:'Syne'; font-size:1.3rem; font-weight:800; color:#F1C40F;">PREMIUM</div>
                        <div style="font-size:2rem; font-family:'Syne'; font-weight:700; margin:0.5rem 0;">$1,299<span style="font-size:0.9rem; color:var(--muted);">/mes</span></div>
                        <div style="color:var(--muted); font-size:0.82rem;">Ilimitado · Todas las funciones</div>
                    </div>
                    <div style="background:rgba(241,196,15,0.1); border-radius:10px; padding:1rem; text-align:center;">
                        <div style="font-size:1.8rem; font-family:'Syne'; font-weight:700; color:#F1C40F;">{{ $tenants->where('plan','premium')->count() }}</div>
                        <div style="color:var(--muted); font-size:0.78rem;">academias suscritas</div>
                    </div>
                </div>
            </div>

            <!-- Tabla filtrada por suscripción próxima a vencer -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-calendar-xmark" style="color:#F1C40F;"></i> Suscripciones por Vencer (próximos 30 días)</div>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Academia</th>
                                <th>Plan Actual</th>
                                <th>Vence</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $expiring = $tenants->filter(function($t) {
                                    return $t->subscription_ends_at &&
                                           \Carbon\Carbon::parse($t->subscription_ends_at)->isBetween(now(), now()->addDays(30));
                                });
                            @endphp
                            @forelse($expiring as $t)
                            <tr>
                                <td><strong>{{ $t->academy_name }}</strong></td>
                                <td><span class="badge badge-plan badge-{{ strtolower($t->plan) }}">{{ strtoupper($t->plan) }}</span></td>
                                <td style="color:#F1C40F; font-size:0.85rem;"><i class="fa-solid fa-clock"></i> {{ \Carbon\Carbon::parse($t->subscription_ends_at)->diffForHumans() }}</td>
                                <td><span class="badge badge-{{ $t->status === 'active' ? 'active' : 'suspended' }}">{{ strtoupper($t->status) }}</span></td>
                                <td>
                                    <button class="btn btn-sm" onclick="changePlan({{ $t->id }}, '{{ $t->plan }}', '{{ addslashes($t->academy_name) }}')">
                                        <i class="fa-solid fa-arrows-rotate"></i> Renovar
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state" style="padding:1.5rem;">
                                        <i class="fa-solid fa-circle-check" style="color:#25D366; font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                                        <span style="color:#25D366;">Sin suscripciones por vencer en los próximos 30 días. ✓</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div><!-- end .main -->

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function switchTab(id, el) {
            document.querySelectorAll('.section-view').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
            document.getElementById('section-' + id).classList.add('active');
            el.classList.add('active');
            const titles = { overview:'Resumen de la Plataforma', academias:'Gestión de Academias', planes:'Planes & Suscripciones' };
            document.getElementById('page-title').textContent = titles[id] || '';
        }

        function filterTable() {
            const q = document.getElementById('search-academias').value.toLowerCase();
            document.querySelectorAll('#tabla-academias tbody tr[data-name]').forEach(row => {
                row.style.display = row.getAttribute('data-name').includes(q) ? '' : 'none';
            });
        }

        async function changePlan(id, currentPlan, name) {
            const { value: plan } = await Swal.fire({
                title: `Cambiar Plan: ${name}`,
                html: `
                    <p style="color:#a78bca; font-size:0.9rem; margin-bottom:1rem;">Plan actual: <strong style="color:#E0AAFF">${currentPlan.toUpperCase()}</strong></p>
                    <select id="new-plan" style="width:100%; padding:0.8rem; background:rgba(255,255,255,0.08); border:1px solid rgba(157,78,221,0.4); border-radius:8px; color:#fff; font-size:0.95rem;">
                        <option value="basico" ${currentPlan==='basico'?'selected':''}>🏫 Básico — $399/mes</option>
                        <option value="elite" ${currentPlan==='elite'?'selected':''}>⚡ Elite — $699/mes</option>
                        <option value="premium" ${currentPlan==='premium'?'selected':''}>👑 Premium — $1,299/mes</option>
                    </select>
                `,
                background: '#1a1a24', color: '#fff',
                confirmButtonColor: '#9D4EDD', confirmButtonText: 'Guardar Cambio',
                showCancelButton: true, cancelButtonText: 'Cancelar',
                cancelButtonColor: 'rgba(255,255,255,0.1)',
                preConfirm: () => document.getElementById('new-plan').value
            });
            if (plan) {
                const res = await fetch(`/superadmin/tenants/${id}/plan`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ plan })
                });
                const json = await res.json();
                if (json.success) {
                    Swal.fire({ icon:'success', title:'Plan actualizado', timer:1500, showConfirmButton:false, background:'#1a1a24', color:'#fff' })
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error', json.message || 'No se pudo actualizar', 'error');
                }
            }
        }

        async function toggleStatus(id, newStatus, name) {
            const label = newStatus === 'suspended' ? 'Suspender' : 'Activar';
            const color = newStatus === 'suspended' ? '#FF6B6B' : '#25D366';
            const result = await Swal.fire({
                title: `¿${label} Academia?`,
                text: `La academia "${name}" será ${newStatus === 'suspended' ? 'suspendida y no podrá acceder al sistema' : 'reactivada'}.`,
                icon: newStatus === 'suspended' ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonColor: color,
                cancelButtonColor: 'rgba(255,255,255,0.1)',
                confirmButtonText: label,
                cancelButtonText: 'Cancelar',
                background: '#1a1a24', color: '#fff'
            });
            if (result.isConfirmed) {
                const res = await fetch(`/superadmin/tenants/${id}/status`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ status: newStatus })
                });
                const json = await res.json();
                if (json.success) {
                    Swal.fire({ icon:'success', title:'Estado actualizado', timer:1500, showConfirmButton:false, background:'#1a1a24', color:'#fff' })
                        .then(() => location.reload());
                }
            }
        }

        async function deleteTenant(id, name) {
            const result = await Swal.fire({
                title: '⚠️ Eliminar Academia',
                html: `<p style="color:#a78bca;">Estás a punto de eliminar permanentemente <strong style="color:#FF6B6B">"${name}"</strong>.</p><br><p style="font-size:0.85rem; color:#888;">Esta acción NO se puede deshacer. Se eliminará su registro de la base de datos central.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FF6B6B',
                cancelButtonColor: 'rgba(255,255,255,0.1)',
                confirmButtonText: '<i class="fa-solid fa-trash"></i> Eliminar Permanentemente',
                cancelButtonText: 'Cancelar',
                background: '#1a1a24', color: '#fff'
            });
            if (result.isConfirmed) {
                const res = await fetch(`/superadmin/tenants/${id}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ _method: 'DELETE' })
                });
                const json = await res.json();
                if (json.success) {
                    Swal.fire({ icon:'success', title:'Academia eliminada', timer:1500, showConfirmButton:false, background:'#1a1a24', color:'#fff' })
                        .then(() => location.reload());
                }
            }
        }
    </script>
</body>
</html>
