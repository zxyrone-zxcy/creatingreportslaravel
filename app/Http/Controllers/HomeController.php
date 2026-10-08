<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('reports');
    }

    public function dashboard(): RedirectResponse
    {
        return redirect()->route('reports');
    }
}
