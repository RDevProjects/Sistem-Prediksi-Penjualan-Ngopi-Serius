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
        $nameUser = Auth::user()->name;

        $jumlahDataPenjualan = Penjualan::count();
        $dataPenjualan = Penjualan::sum('jumlah');

        $dataAnalisa = Analisa::get();
        $dataAnalisaCount = Analisa::count();

        return view('index', compact('dataAnalisa', 'nameUser', 'jumlahDataPenjualan', 'dataPenjualan', 'dataAnalisaCount'));
    }
}
