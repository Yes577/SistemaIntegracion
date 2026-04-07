<?php

namespace App\Http\Controllers;

use App\Models\TipoRol;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'totalAdmins' => User::where('id_tipo_rol', TipoRol::ADMIN_ID)->count(),
            'totalStandardUsers' => User::where('id_tipo_rol', TipoRol::USER_ID)->count(),
        ]);
    }
}
