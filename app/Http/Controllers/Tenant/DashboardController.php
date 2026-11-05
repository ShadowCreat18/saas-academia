<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Services\PlanManager;

class DashboardController extends Controller
{
    public function index()
    {
        $tenant = app('tenant');

        // Si es la cuenta DEMO, sembrar registros reales para que los usuarios puedan probar el CRUD completo
        if ($tenant && $tenant->subdomain === 'demo') {
            if (Schema::connection('tenant')->hasTable('categories') && DB::connection('tenant')->table('categories')->count() === 0) {
                DB::connection('tenant')->table('categories')->insert([
                    ['name' => 'Sub-8',  'min_birth_year' => 2018, 'max_birth_year' => 2019, 'schedule' => 'Lun, Mie, Vie 4:00 PM', 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Sub-10', 'min_birth_year' => 2016, 'max_birth_year' => 2017, 'schedule' => 'Lun, Mie, Vie 5:00 PM', 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Sub-12', 'min_birth_year' => 2014, 'max_birth_year' => 2015, 'schedule' => 'Mar, Jue, Sab 4:30 PM', 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Sub-15', 'min_birth_year' => 2011, 'max_birth_year' => 2013, 'schedule' => 'Mar, Jue 6:00 PM',     'created_at' => now(), 'updated_at' => now()],
                ]);
            }
            if (Schema::connection('tenant')->hasTable('players') && DB::connection('tenant')->table('players')->count() === 0) {
                DB::connection('tenant')->table('players')->insert([
                    ['first_name' => 'Mateo', 'last_name' => 'Martínez', 'birth_date' => '2016-04-12', 'category' => 'Sub-10', 'goals' => 5, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
                    ['first_name' => 'Leo',   'last_name' => 'Martínez', 'birth_date' => '2018-08-20', 'category' => 'Sub-8',  'goals' => 2, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
                    ['first_name' => 'Sofía', 'last_name' => 'López',    'birth_date' => '2014-02-15', 'category' => 'Sub-12', 'goals' => 7, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
                ]);
            }
            if (Schema::connection('tenant')->hasTable('tutors') && DB::connection('tenant')->table('tutors')->count() === 0) {
                DB::connection('tenant')->table('tutors')->insert([
                    ['name' => 'Carlos Martínez', 'phone' => '5512345678', 'email' => 'carlos.tutor@ejemplo.com', 'linked_students' => 'Mateo Martínez, Leo Martínez', 'created_at' => now(), 'updated_at' => now()]
                ]);
            }
            if (Schema::connection('tenant')->hasTable('matches') && DB::connection('tenant')->table('matches')->count() === 0) {
                DB::connection('tenant')->table('matches')->insert([
                    ['opponent' => 'Club América Valles', 'category' => 'Sub-10', 'match_date' => '2026-10-18', 'match_time' => '10:00', 'location' => 'Deportivo Sur (Visitante)', 'result' => 'Pendiente', 'status' => 'Programado', 'created_at' => now(), 'updated_at' => now()],
                    ['opponent' => 'Tigres Cumbres',      'category' => 'Sub-12', 'match_date' => '2026-10-10', 'match_time' => '12:30', 'location' => 'Cancha Local (Local)',      'result' => '3 - 1 (Ganado)', 'status' => 'Finalizado', 'created_at' => now(), 'updated_at' => now()]
                ]);
            }
            if (Schema::connection('tenant')->hasTable('payments') && DB::connection('tenant')->table('payments')->count() === 0) {
                DB::connection('tenant')->table('payments')->insert([
                    ['folio' => 'FOL-1001', 'concept' => 'Mensualidad Octubre 2026', 'student_tutor' => 'Mateo Martínez', 'amount' => 800.00, 'status' => 'Pagado', 'created_at' => now(), 'updated_at' => now()]
                ]);
            }
        }
        
        $players = Schema::connection('tenant')->hasTable('players') ? DB::connection('tenant')->table('players')->latest()->get() : collect();
        $categories = Schema::connection('tenant')->hasTable('categories') ? DB::connection('tenant')->table('categories')->get() : collect();
        $tutors = Schema::connection('tenant')->hasTable('tutors') ? DB::connection('tenant')->table('tutors')->get() : collect();
        $payments = Schema::connection('tenant')->hasTable('payments') ? DB::connection('tenant')->table('payments')->latest()->get() : collect();
        $matches = Schema::connection('tenant')->hasTable('matches') ? DB::connection('tenant')->table('matches')->latest()->get() : collect();

        $stats = [
            'players' => $players->count(),
            'categories' => $categories->count(),
            'revenue' => $payments->where('status', 'Pagado')->sum('amount'),
        ];

        return view('tenant.dashboard', compact('tenant', 'stats', 'players', 'categories', 'tutors', 'payments', 'matches'));
    }

    public function storePlayer(Request $request, PlanManager $planManager)
    {
        $tenant = app('tenant');

        if (!$planManager->canAddPlayer($tenant)) {
            return back()->with('error', 'Has alcanzado el límite de alumnos de tu plan (Plan ' . strtoupper($tenant->plan) . '). Por favor mejora tu plan.');
        }

        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birth_date' => 'required|date',
        ]);

        DB::connection('tenant')->table('players')->insert([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_date' => $request->birth_date,
            'category' => $request->category,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Alumno agregado exitosamente.');
    }

    public function storeCrud(Request $request, $model)
    {
        try {
            $data = $request->except(['_token', '_method']);
            $columns = Schema::connection('tenant')->getColumnListing($model);
            $filteredData = array_intersect_key($data, array_flip($columns));
            
            if (in_array('created_at', $columns)) $filteredData['created_at'] = now();
            if (in_array('updated_at', $columns)) $filteredData['updated_at'] = now();
            
            $id = DB::connection('tenant')->table($model)->insertGetId($filteredData);
            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateCrud(Request $request, $model, $id)
    {
        try {
            $data = $request->except(['_token', '_method']);
            $columns = Schema::connection('tenant')->getColumnListing($model);
            $filteredData = array_intersect_key($data, array_flip($columns));
            
            if (in_array('updated_at', $columns)) $filteredData['updated_at'] = now();
            
            DB::connection('tenant')->table($model)->where('id', $id)->update($filteredData);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteCrud($request, $model = null, $id = null)
    {
        if (!($request instanceof Request)) {
            $id = $model;
            $model = $request;
        }
        try {
            DB::connection('tenant')->table($model)->where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function upgradePlan(Request $request)
    {
        try {
            $request->validate([
                'plan' => 'required|in:elite,premium',
            ]);

            $tenant = app('tenant');
            if ($tenant) {
                $tenant->update(['plan' => $request->plan]);
                return response()->json([
                    'success' => true,
                    'message' => '¡Plan actualizado con éxito a ' . strtoupper($request->plan) . '! Todas las funciones han sido desbloqueadas.'
                ]);
            }

            return response()->json(['success' => false, 'message' => 'No se encontró la academia.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveBrandingSettings(Request $request)
    {
        $tenant = app('tenant');
        if (!$tenant) {
            return back()->with('error', 'No se encontró la academia.');
        }

        $request->validate([
            'academy_name'  => 'required|string|max:100',
            'color_palette' => 'nullable|string|max:20',
            'logo'          => 'nullable|image|max:2048',
        ]);

        $data = [
            'academy_name'  => $request->academy_name,
            'color_palette' => $request->color_palette,
        ];

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo_path'] = $path;
        }

        $tenant->update($data);

        return back()->with('success', '¡Personalización y colores guardados con éxito!');
    }

    public function saveStripeSettings(Request $request)
    {
        $tenant = app('tenant');
        if (!$tenant) {
            return back()->with('error', 'No se encontró la academia.');
        }

        $request->validate([
            'stripe_public_key' => 'required|string',
            'stripe_secret_key' => 'required|string',
        ]);

        $tenant->update([
            'stripe_public_key' => $request->stripe_public_key,
            'stripe_secret_key' => $request->stripe_secret_key,
        ]);

        return back()->with('success', '¡Claves de Stripe guardadas con éxito!');
    }

    public function storeTutor(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:30',
        ]);

        DB::connection('tenant')->table('tutors')->insert([
            'name'            => $request->name,
            'email'           => $request->email,
            'phone'           => $request->phone,
            'linked_students' => $request->linked_students ?? '',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return back()->with('success', 'Tutor agregado exitosamente.');
    }

    public function generateTutorCredentials(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string|min:6',
                'name'     => 'nullable|string',
            ]);

            $tenant = app('tenant');

            \App\Models\User::updateOrCreate(
                ['email' => $request->email],
                [
                    'name'      => $request->name ?? 'Tutor',
                    'password'  => \Illuminate\Support\Facades\Hash::make($request->password),
                    'role'      => 'parent',
                    'tenant_id' => $tenant ? $tenant->id : null,
                ]
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
