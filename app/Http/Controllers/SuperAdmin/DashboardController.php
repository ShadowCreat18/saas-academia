<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Central\Tenant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tenants = Tenant::orderBy('created_at', 'desc')->get();

        $stats = [
            'total'     => $tenants->count(),
            'active'    => $tenants->where('status', 'active')->count(),
            'trial'     => $tenants->where('status', 'trial')->count(),
            'suspended' => $tenants->where('status', 'suspended')->count(),
        ];

        return view('superadmin.dashboard', compact('tenants', 'stats'));
    }

    public function updatePlan(Request $request, $id)
    {
        try {
            $request->validate(['plan' => 'required|in:basico,elite,premium']);
            $tenant = Tenant::findOrFail($id);
            $tenant->update(['plan' => $request->plan]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate(['status' => 'required|in:active,suspended,cancelled,trial']);
            $tenant = Tenant::findOrFail($id);
            $tenant->update(['status' => $request->status]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $tenant = Tenant::findOrFail($id);
            $tenant->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
