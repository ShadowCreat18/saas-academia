<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;

class FinanceController extends Controller
{
    public function index()
    {
        return view('tenant.finances');
    }
}
