<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PanelDashboardController extends Controller
{
    public function index(): View
    {
        return view('panel.dashboard');
    }
}
