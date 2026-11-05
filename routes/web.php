<?php

use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Tenant\FrontendController;
use Illuminate\Support\Facades\Route;

// ─── SuperAdmin Auth (público) ───────────────────────────────────────────────
Route::get('/superadmin/login', [\App\Http\Controllers\SuperAdmin\AuthController::class, 'showLogin'])->name('superadmin.login');
Route::post('/superadmin/login', [\App\Http\Controllers\SuperAdmin\AuthController::class, 'login'])->name('superadmin.login.post');
Route::get('/superadmin/logout', [\App\Http\Controllers\SuperAdmin\AuthController::class, 'logout'])->name('superadmin.logout');

// ─── SuperAdmin Panel (protegido con middleware de sesión) ────────────────────
Route::middleware([\App\Http\Middleware\SuperAdminMiddleware::class])->prefix('superadmin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('superadmin.dashboard');

    // CRUD de tenants
    Route::post('/tenants/{id}/plan',   [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'updatePlan']);
    Route::post('/tenants/{id}/status', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'updateStatus']);
    Route::delete('/tenants/{id}',      [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'destroy']);
});

Route::middleware([\App\Http\Middleware\ResolveTenant::class])->group(function () {
    // Landing page principal / Tenant Frontend
    Route::get('/', function (\Illuminate\Http\Request $request) {
        if (app()->bound('tenant') && app('tenant')) {
            return redirect()->route('login');
        }
        return view('landing');
    });

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware([\App\Http\Middleware\ResolveTenant::class, 'auth'])->group(function () {
    Route::get('/padres', [\App\Http\Controllers\Tenant\FrontendController::class, 'index'])->name('tenant.frontend');
});

// Legal
Route::get('/privacidad', function () { return view('privacidad'); })->name('privacidad');
Route::get('/terminos', function () { return view('terminos'); })->name('terminos');

// Rutas de prueba rápida en desarrollo para consultar usuarios y tutores
Route::get('/debug-tutors', function () {
    return \App\Models\User::where('role', 'parent')->get();
});

Route::get('/debug-users', function () {
    return \App\Models\User::all();
});

Route::get('/reset-demo', function () {
    try {
        \App\Models\Central\Tenant::where('subdomain', 'demo')->delete();
        \App\Models\User::where('email', 'demo@academiahe5.com')->delete();
    } catch (\Exception $e) {}

    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');

    return "Cuenta demo borrada y memoria caché del servidor limpiada. Ya puedes intentar registrar la academia de nuevo.";
});

// Formulario de registro de una nueva academia
Route::get('/registro', function () {
    $plan = request('plan', 'elite');
    return view('registro', compact('plan'));
});

// Checkout Stripe
Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'create'])->name('checkout.create');
Route::get('/checkout/exitoso', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancelado', function() { return view('checkout-cancelado'); })->name('checkout.cancelled');

// Webhook Stripe
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

// Dashboard del tenant
Route::middleware([\App\Http\Middleware\ResolveTenant::class, 'auth'])->group(function () {
    // Ruta temporal para crear las tablas en Hostinger (con conexión ya configurada)
    Route::get('/crear-tablas', function () {
        try {
            if (!\Illuminate\Support\Facades\Schema::connection('tenant')->hasTable('tutors')) {
                \Illuminate\Support\Facades\Schema::connection('tenant')->create('tutors', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('phone')->nullable();
                    $table->string('email')->nullable();
                    $table->string('linked_students')->nullable();
                    $table->timestamps();
                });
                echo "Tabla tutors creada.<br>";
            } else {
                echo "La tabla tutors ya existe.<br>";
            }

            if (!\Illuminate\Support\Facades\Schema::connection('tenant')->hasTable('payments')) {
                \Illuminate\Support\Facades\Schema::connection('tenant')->create('payments', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('folio')->unique();
                    $table->string('concept');
                    $table->string('student_tutor');
                    $table->decimal('amount', 10, 2);
                    $table->string('status')->default('Pagado');
                    $table->timestamps();
                });
                echo "Tabla payments creada.<br>";
            } else {
                echo "La tabla payments ya existe.<br>";
            }

            if (!\Illuminate\Support\Facades\Schema::connection('tenant')->hasTable('matches')) {
                \Illuminate\Support\Facades\Schema::connection('tenant')->create('matches', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('opponent');
                    $table->string('category')->nullable();
                    $table->date('match_date')->nullable();
                    $table->time('match_time')->nullable();
                    $table->string('location')->nullable();
                    $table->string('result')->nullable();
                    $table->string('status')->default('Programado');
                    $table->timestamps();
                });
                echo "Tabla matches creada.<br>";
            } else {
                echo "La tabla matches ya existe.<br>";
            }

            if (!\Illuminate\Support\Facades\Schema::connection('tenant')->hasTable('categories')) {
                \Illuminate\Support\Facades\Schema::connection('tenant')->create('categories', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->integer('min_birth_year')->nullable();
                    $table->integer('max_birth_year')->nullable();
                    $table->string('schedule')->nullable();
                    $table->timestamps();
                });
                echo "Tabla categories creada.<br>";
            } else {
                echo "La tabla categories ya existe.<br>";
                if (!\Illuminate\Support\Facades\Schema::connection('tenant')->hasColumn('categories', 'schedule')) {
                    \Illuminate\Support\Facades\Schema::connection('tenant')->table('categories', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->string('schedule')->nullable();
                    });
                    echo "Columna schedule añadida a categories.<br>";
                }
            }

            if (!\Illuminate\Support\Facades\Schema::connection('tenant')->hasTable('players')) {
                \Illuminate\Support\Facades\Schema::connection('tenant')->create('players', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('first_name');
                    $table->string('last_name');
                    $table->date('birth_date')->nullable();
                    $table->string('category')->nullable();
                    $table->integer('goals')->default(0);
                    $table->string('status')->default('active');
                    $table->timestamps();
                });
                echo "Tabla players creada.<br>";
            } else {
                echo "La tabla players ya existe.<br>";
                if (!\Illuminate\Support\Facades\Schema::connection('tenant')->hasColumn('players', 'goals')) {
                    \Illuminate\Support\Facades\Schema::connection('tenant')->table('players', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->integer('goals')->default(0);
                        $table->string('status')->default('active');
                    });
                    echo "Columnas goals y status añadidas a players.<br>";
                }
            }

            if (!\Illuminate\Support\Facades\Schema::connection('tenant')->hasTable('notifications')) {
                \Illuminate\Support\Facades\Schema::connection('tenant')->create('notifications', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('title')->nullable();
                    $table->text('data')->nullable();
                    $table->timestamps();
                });
                echo "Tabla notifications creada.<br>";
            } else {
                echo "La tabla notifications ya existe.<br>";
            }
            
            return "¡Tablas creadas y actualizadas correctamente! Ya puedes usar el sistema.";
        } catch (\Exception $e) {
            return "Error creando tablas: " . $e->getMessage();
        }
    });

    Route::get('/storage-link', function () {
        try {
            $targetFolder = storage_path('app/public');
            $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/storage';
            if (file_exists($linkFolder)) {
                return 'El enlace simbólico ya existe en: ' . $linkFolder;
            }
            symlink($targetFolder, $linkFolder);
            return 'Enlace simbólico creado con éxito. Ahora las imágenes se mostrarán.';
        } catch (\Exception $e) {
            return "Error creando el enlace simbólico: " . $e->getMessage();
        }
    });

    Route::get('/dashboard', [\App\Http\Controllers\Tenant\DashboardController::class, 'index'])->name('tenant.dashboard');
    
    // Gestión de tutores y generación de accesos para padres
    Route::post('/dashboard/tutors', [\App\Http\Controllers\Tenant\DashboardController::class, 'storeTutor'])->name('tenant.tutors.store');
    Route::post('/dashboard/tutors/credentials', [\App\Http\Controllers\Tenant\DashboardController::class, 'generateTutorCredentials'])->name('tenant.tutors.credentials');

    Route::post('/dashboard/players', [\App\Http\Controllers\Tenant\DashboardController::class, 'storePlayer'])->name('tenant.players.store');
    
    // Rutas AJAX para operaciones CRUD dinámicas de tablas (alumnos, categorías, partidos, pagos)
    Route::post('/api/crud/{model}', [\App\Http\Controllers\Tenant\DashboardController::class, 'storeCrud']);
    Route::match(['post', 'put'], '/api/crud/{model}/{id}', function (\Illuminate\Http\Request $request, $model, $id) {
        if ($request->input('_method') === 'DELETE' || $request->header('X-HTTP-Method-Override') === 'DELETE') {
            return app(\App\Http\Controllers\Tenant\DashboardController::class)->deleteCrud($request, $model, $id);
        }
        return app(\App\Http\Controllers\Tenant\DashboardController::class)->updateCrud($request, $model, $id);
    });
    Route::delete('/api/crud/{model}/{id}', [\App\Http\Controllers\Tenant\DashboardController::class, 'deleteCrud']);

    Route::post('/dashboard/settings/stripe', [\App\Http\Controllers\Tenant\DashboardController::class, 'saveStripeSettings'])->name('tenant.settings.stripe');
    Route::post('/dashboard/settings/branding', [\App\Http\Controllers\Tenant\DashboardController::class, 'saveBrandingSettings'])->name('tenant.settings.branding');
    Route::post('/notifications/send', [\App\Http\Controllers\Tenant\NotificationController::class, 'send'])->name('tenant.notifications.send');
    Route::get('/api/notifications/unread', [\App\Http\Controllers\Tenant\NotificationController::class, 'unread']);

    // Procesamiento de pago de mensualidades vía pasarela Stripe
    Route::post('/pagos/checkout', [\App\Http\Controllers\Tenant\CheckoutController::class, 'create'])->name('tenant.checkout');
    Route::get('/pagos/checkout/success', [\App\Http\Controllers\Tenant\CheckoutController::class, 'success'])->name('tenant.checkout.success');

    // Mejora o cambio de plan de suscripción de la academia
    Route::post('/dashboard/upgrade-plan', [\App\Http\Controllers\Tenant\DashboardController::class, 'upgradePlan'])->name('tenant.upgrade-plan');
});
