<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Analisa;

class DashboardController extends Controller
{
    public function index()
    {
        $analisa = Analisa::count();
        $nameUser = Auth::user()->name;
        return view('index', compact('analisa', 'nameUser'));
    }
}
