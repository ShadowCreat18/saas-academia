<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        return view('tenant.categories');
    }
}
