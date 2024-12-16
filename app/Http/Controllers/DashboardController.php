<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Analisa;

class DashboardController extends Controller
{
    public function index()
    {
        $analisa = Analisa::count();
        $nameUser = Auth::user()->name;

        $jumlahDataPenjualan = Penjualan::count();
        $dataPenjualan = Penjualan::sum('jumlah');
        return view('index', compact('analisa', 'nameUser', 'jumlahDataPenjualan', 'dataPenjualan'));
    }
}
