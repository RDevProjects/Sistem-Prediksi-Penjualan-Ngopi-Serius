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
        $request->validate([
            'bulanAwal' => 'required',
            'tahunAwal' => 'required',
            'bulanAkhir' => 'required',
            'tahunAkhir' => 'required'
        ]);

        $months = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
        ];

        $bulanAwal = $months[$request->bulanAwal];
        $bulanAkhir = $months[$request->bulanAkhir];

        $penjualan = Penjualan::where(function($query) use ($request, $bulanAwal) {
            $query->where('tahun', '>', $request->tahunAwal)
                  ->orWhere(function($query) use ($request, $bulanAwal) {
                      $query->where('tahun', '=', $request->tahunAwal)
                            ->where('bulan', '>=', $bulanAwal);
                  });
        })->where(function($query) use ($request, $bulanAkhir) {
            $query->where('tahun', '<', $request->tahunAkhir)
                  ->orWhere(function($query) use ($request, $bulanAkhir) {
                      $query->where('tahun', '=', $request->tahunAkhir)
                            ->where('bulan', '<=', $bulanAkhir);
                  });
        })->orderBy('tahun')->orderBy('bulan')->get();

        // return response()->json([
        //     'penjualan' => $penjualan,
        //     'request' => $request->all()
        // ]);

        $nameUser = Auth::user()->name;

        $dataPenjualan = Penjualan::select('bulan')->distinct()->get();
        $dataPenjualanTahun = Penjualan::select('tahun')->distinct()->get();

        $weights = [0.1, 0.2, 0.7]; // Bobot

        $result = [];
        $totalMAD = 0;
        $totalMSE = 0;
        $totalMAPE = 0;

        foreach ($penjualan as $key => $data) {
            if ($key >= 3) { // Mulai dari bulan ke-4
                $wma = (
                    ($penjualan[$key - 1]->jumlah * $weights[2]) +
                    ($penjualan[$key - 2]->jumlah * $weights[1]) +
                    ($penjualan[$key - 3]->jumlah * $weights[0])
                );

                $mad = abs($data->jumlah - $wma);
                $mse = pow($mad, 2);
                $mape = round($mad / $data->jumlah, 2);

                $totalMAD += round($mad, 2);
                $totalMSE += round($mse, 2);
                $totalMAPE += round($mape, 2);

                $result[] = [
                    'tahun' => $data->tahun,
                    'bulan' => $data->bulan,
                    'index' => $key + 1,
                    'penjualan' => $data->jumlah,
                    'wma' => $wma,
                    'mad' => $mad,
                    'mse' => $mse,
                    'mape' => $mape
                ];
            } else {
                $result[] = [
                    'tahun' => $data->tahun,
                    'bulan' => $data->bulan,
                    'index' => $key + 1,
                    'penjualan' => $data->jumlah,
                    'wma' => null,
                    'mad' => null,
                    'mse' => null,
                    'mape' => null
                ];
            }
        }

        $averageMAD = $totalMAD / 9;
        $averageMSE = $totalMSE / 9;
        $averageMAPE = round(($totalMAPE / 9) * 100, 2);
        // Prediksi bulan Januari berikutnya
        $lastData = end($penjualan);
        $prediksiJanuari = (
            ($penjualan[count($penjualan) - 1]->jumlah * $weights[2]) +
            ($penjualan[count($penjualan) - 2]->jumlah * $weights[1]) +
            ($penjualan[count($penjualan) - 3]->jumlah * $weights[0])
        );

        $result[] = [
            'tahun' => $penjualan[count($penjualan) - 1]->tahun + 1,
            'bulan' => 'Januari',
            'index' => count($penjualan) + 1,
            'penjualan' => null,
            'wma' => $prediksiJanuari,
            'mad' => null,
            'mse' => null,
            'mape' => null
        ];

        // return response()->json([
        //     'result' => $result,
        //     'totalMAD' => $totalMAD,
        //     'totalMSE' => $totalMSE,
        //     'totalMAPE' => $totalMAPE,
        //     'averageMAD' => $averageMAD,
        //     'averageMSE' => $averageMSE,
        //     'averageMAPE' => $averageMAPE,
        //     'prediksiJanuari' => $prediksiJanuari
        // ]);

        return view('analisa.index', compact('result', 'totalMAD', 'totalMSE', 'totalMAPE', 'averageMAD', 'averageMSE', 'averageMAPE', 'nameUser', 'dataPenjualan', 'dataPenjualanTahun'));
    }
}
