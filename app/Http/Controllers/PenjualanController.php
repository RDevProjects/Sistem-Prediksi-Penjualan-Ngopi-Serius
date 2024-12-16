<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        $nameUser = Auth::user()->name;

        $dataPenjualan = Penjualan::all();
        return view('penjualan.index', compact('nameUser', 'dataPenjualan'));
    }

    public function create()
    {
        return view('penjualan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
            'jumlah' => 'required',
        ]);

        $penjualan = new Penjualan();
        $penjualan->bulan = $request->bulan;
        $penjualan->tahun = $request->tahun;
        $penjualan->jumlah = $request->jumlah;
        $penjualan->save();

        return redirect()->route('penjualan')->with('success', 'Data penjualan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $penjualan = Penjualan::find($id);
        return view('penjualan.edit', compact('penjualan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
            'jumlah' => 'required',
        ]);

        $penjualan = Penjualan::find($id);
        $penjualan->bulan = $request->bulan;
        $penjualan->tahun = $request->tahun;
        $penjualan->jumlah = $request->jumlah;
        $penjualan->save();

        return redirect()->route('penjualan')->with('success', 'Data penjualan berhasil diubah');
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $penjualan->delete();

        return redirect()->route('penjualan')->with('success', 'Data penjualan berhasil dihapus');
    }
}
