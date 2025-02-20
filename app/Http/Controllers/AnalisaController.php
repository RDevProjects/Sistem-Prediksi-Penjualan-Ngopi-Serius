<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Penjualan;
use App\Models\Analisa;
use Illuminate\Http\Request;

class AnalisaController extends Controller
{
    public function index()
    {
        $nameUser = Auth::user()->name;

        $dataPenjualan = Penjualan::select('bulan')->distinct()->get();
        $dataPenjualanTahun = Penjualan::select('tahun')->distinct()->get();
        return view('analisa.index', compact('nameUser', 'dataPenjualan', 'dataPenjualanTahun'));
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
            'Januari' => 1,
            'Februari' => 2,
            'Maret' => 3,
            'April' => 4,
            'Mei' => 5,
            'Juni' => 6,
            'Juli' => 7,
            'Agustus' => 8,
            'September' => 9,
            'Oktober' => 10,
            'November' => 11,
            'Desember' => 12
        ];

        $bulanAwal = $months[$request->bulanAwal];
        $bulanAkhir = $months[$request->bulanAkhir];

        $penjualan = Penjualan::where(function ($query) use ($request, $bulanAwal) {
            $query->where('tahun', '>', $request->tahunAwal)
                ->orWhere(function ($query) use ($request, $bulanAwal) {
                    $query->where('tahun', '=', $request->tahunAwal)
                        ->where('bulan', '>=', $bulanAwal);
                });
        })->where(function ($query) use ($request, $bulanAkhir) {
            $query->where('tahun', '<', $request->tahunAkhir)
                ->orWhere(function ($query) use ($request, $bulanAkhir) {
                    $query->where('tahun', '=', $request->tahunAkhir)
                        ->where('bulan', '<=', $bulanAkhir);
                });
        })->orderBy('tahun')->orderBy('bulan')->get();

        $weights = [0.1, 0.2, 0.7]; // Bobot
        $result = [];
        $totalMAD = 0;
        $totalMSE = 0;
        $totalMAPE = 0;

        foreach ($penjualan as $key => $data) {
            if ($key >= 3) {
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

        $averageMAD = round($totalMAD / 9, 2);
        $averageMSE = round($totalMSE / 9, 2);
        $averageMAPE = round(($totalMAPE / 9), 3);

        // Prediksi bulan Januari berikutnya
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
            'wma' => round($prediksiJanuari, 2),
            'mad' => null,
            'mse' => null,
            'mape' => null
        ];

        $dataPenjualan = Penjualan::select('bulan')->distinct()->get();
        $dataPenjualanTahun = Penjualan::select('tahun')->distinct()->get();
        return view('analisa.index', compact('result', 'totalMAD', 'totalMSE', 'totalMAPE', 'averageMAD', 'averageMSE', 'averageMAPE', 'prediksiJanuari', 'dataPenjualan', 'dataPenjualanTahun'));
    }

    public function storeAnalisa(Request $request)
    {
        $request->validate([
            'tahun' => 'required',
            'bulan' => 'required',
            'jumlah' => 'required',
            'wma' => 'required',
            'mad' => 'required',
            'mse' => 'required',
            'mape' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // Simpan data analisa
            $tahun = $request->input('tahun');
            $bulan = $request->input('bulan');
            $jumlah = $request->input('jumlah');
            $wma = $request->input('wma');
            $mad = $request->input('mad');
            $mse = $request->input('mse');
            $mape = $request->input('mape');

            DB::table('analisa')->delete();

            foreach ($tahun as $index => $tahunValue) {
                DB::table('analisa')->insert([
                    'tahun' => $tahunValue,
                    'bulan' => $bulan[$index],
                    'jumlah' => $jumlah[$index],
                    'wma' => $wma[$index],
                    'mad' => $mad[$index],
                    'mse' => $mse[$index],
                    'mape' => $mape[$index],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // $months = [
            //     'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            //     'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            //     'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
            // ];

            // $bulanAwal = $months[$request->bulanAwal];
            // $tahunAwal = $request->tahunAwal;
            // $bulanAkhir = $months[$request->bulanAkhir];
            // $tahunAkhir = $request->tahunAkhir;

            // Penjualan::where(function ($query) use ($tahunAwal, $bulanAwal) {
            //     $query->where('tahun', '>', $tahunAwal)
            //         ->orWhere(function ($query) use ($tahunAwal, $bulanAwal) {
            //             $query->where('tahun', '=', $tahunAwal)
            //                 ->where('bulan', '>=', $bulanAwal);
            //         });
            // })->where(function ($query) use ($tahunAkhir, $bulanAkhir) {
            //     $query->where('tahun', '<', $tahunAkhir)
            //         ->orWhere(function ($query) use ($tahunAkhir, $bulanAkhir) {
            //             $query->where('tahun', '=', $tahunAkhir)
            //                 ->where('bulan', '<=', $bulanAkhir);
            //         });
            // })->delete();

            DB::commit();

            return redirect()->route('analisis')->with('success', 'Data analisa berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }


    public function show()
    {
        $dataAnalisa = DB::table('analisa')
            ->select(DB::raw('*, DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") as created_at_minute'))
            ->groupBy('created_at_minute', 'id', 'tahun', 'bulan', 'jumlah', 'wma', 'mad', 'mse', 'mape', 'created_at', 'updated_at')
            ->orderBy('created_at_minute')
            ->get();
        return view('analisa.hasil', compact('dataAnalisa'));
    }

    public function hasil_analisa()
    {
        $dataAnalisa = DB::table('analisa')->get();

        // Initialize totals
        $totalMAD = 0;
        $totalMSE = 0;
        $totalMAPE = 0;

        // Calculate totals for each metric
        foreach ($dataAnalisa as $data) {
            if ($data->mad !== null) {
                $totalMAD += $data->mad;
            }
            if ($data->mse !== null) {
                $totalMSE += $data->mse;
            }
            if ($data->mape !== null) {
                $totalMAPE += $data->mape;
            }
        }

        // Calculate averages based on the number of predicted months (9)
        $averageMAD = round($totalMAD / 9, 2);
        $averageMSE = round($totalMSE / 9, 2);
        $averageMAPE = round(($totalMAPE / 9), 3);
        return view('analisa.hasil', compact('dataAnalisa', 'averageMAD', 'averageMSE', 'averageMAPE', 'totalMAD', 'totalMSE', 'totalMAPE'));
    }
}
