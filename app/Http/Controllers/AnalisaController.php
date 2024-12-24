<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class AnalisaController extends Controller
{
    public function index()
    {
        $nameUser = Auth::user()->name;

        $dataPenjualan = Penjualan::select('bulan')->distinct()->get();
        $dataPenjualanTahun = Penjualan::select('tahun')->distinct()->get();
        return view('analisa.index', compact('nameUser','dataPenjualan', 'dataPenjualanTahun'));
    }

    public function analisis(Request $request)
    {
        $dataPenjualan = Penjualan::whereBetween('tahun', [$request->tahunAwal, $request->tahunAkhir])
            ->whereBetween('bulan', [$request->bulanAwal, $request->bulanAkhir])
            ->get();

        $wma = [];
        $mad = [];
        $mse = [];
        $mape = [];
        $weights = [0.70, 0.20, 0.10];
        $totalWeight = array_sum($weights);

        foreach ($dataPenjualan as $index => $penjualan) {
            if ($index >= 3) {
                $wmaValue = (
                    $dataPenjualan[$index - 1]->penjualan * $weights[0] +
                    $dataPenjualan[$index - 2]->penjualan * $weights[1] +
                    $dataPenjualan[$index - 3]->penjualan * $weights[2]
                ) / $totalWeight;

                $wma[] = $wmaValue;
                $mad[] = abs($penjualan->penjualan - $wmaValue);
                $mse[] = pow($mad[count($mad) - 1], 2);
                $mape[] = $mad[count($mad) - 1] / $penjualan->penjualan;
            }
        }

        $totalMad = array_sum($mad);
        $totalMse = array_sum($mse);
        $totalMape = array_sum($mape);

        $averageMad = $totalMad / count($mad);
        $averageMse = $totalMse / count($mse);
        $averageMape = ($totalMape / count($mape)) * 100;
        return json_decode(json_encode([
            'wma' => $wma,
            'mad' => $mad,
            'mse' => $mse,
            'mape' => $mape,
            'averageMad' => $averageMad,
            'averageMse' => $averageMse,
            'averageMape' => $averageMape,
        ]));
        return view('analisa.result', compact('wma', 'mad', 'mse', 'mape', 'averageMad', 'averageMse', 'averageMape'));
    }
}
