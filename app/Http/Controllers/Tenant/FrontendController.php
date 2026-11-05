<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    public function index()
    {
        $tenant = app('tenant');
        $user = Auth::user();

        // Obtener tutor de la BD tenant correspondiente al usuario actual
        $tutor = null;
        if (Schema::connection('tenant')->hasTable('tutors') && $user) {
            $tutor = DB::connection('tenant')->table('tutors')
                ->where('email', $user->email)
                ->first();
        }

        // Obtener alumnos vinculados al tutor
        $players = collect();
        if (Schema::connection('tenant')->hasTable('players')) {
            if ($tutor && !empty($tutor->linked_students)) {
                $linked = $tutor->linked_students;
                $names = [];
                if (is_string($linked) && str_starts_with($linked, '[')) {
                    $decoded = json_decode($linked, true);
                    if (is_array($decoded)) {
                        $names = $decoded;
                    }
                } else {
                    $names = array_map('trim', explode(',', $linked));
                }

                $players = DB::connection('tenant')->table('players')
                    ->where(function ($query) use ($names) {
                        foreach ($names as $name) {
                            $parts = explode(' ', $name, 2);
                            $first = $parts[0] ?? '';
                            $last = $parts[1] ?? '';
                            if ($first && $last) {
                                $query->orWhere(function ($q) use ($first, $last) {
                                    $q->where('first_name', 'like', "%{$first}%")
                                      ->where('last_name', 'like', "%{$last}%");
                                });
                            } elseif ($first) {
                                $query->orWhere('first_name', 'like', "%{$first}%")
                                      ->orWhere('last_name', 'like', "%{$first}%");
                            }
                        }
                    })
                    ->get();
            }

            // Si aún no hay alumnos vinculados específicamente y estamos en demo o sin filtro, mostrar los alumnos de la academia
            if ($players->isEmpty()) {
                $players = DB::connection('tenant')->table('players')->latest()->take(4)->get();
            }
        }

        // Obtener categorías y horarios
        $categories = Schema::connection('tenant')->hasTable('categories')
            ? DB::connection('tenant')->table('categories')->get()
            : collect();

        // Obtener partidos
        $matches = Schema::connection('tenant')->hasTable('matches')
            ? DB::connection('tenant')->table('matches')->latest()->take(6)->get()
            : collect();

        // Obtener pagos
        $payments = Schema::connection('tenant')->hasTable('payments')
            ? DB::connection('tenant')->table('payments')->latest()->get()
            : collect();

        // Obtener avisos y notificaciones
        $notifications = Schema::connection('tenant')->hasTable('notifications')
            ? DB::connection('tenant')->table('notifications')->latest()->take(5)->get()
            : collect();

        return view('tenant.frontend', compact(
            'tenant',
            'players',
            'categories',
            'matches',
            'payments',
            'notifications'
        ));
    }
}
