@extends('layouts.app')

@push('styles-css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
@endpush

@section('title')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3"><strong>Selamat Datang di {{ env('APP_NAME', 'Aplikasi Ngopi Serius') }}</strong></h1>
            <h3 class="h4">Sistem Prediksi yang Digunakan untuk Memprediksi Penjualan di Ngopi Serius</h3>
        </div>
        <div>
            <h5 class="p-2 bg-white rounded-pill h5">
                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</h5>
        </div>
    </div>
@endsection

@section('content')
    <div class="col-8 mx-auto">
        <div class="card">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('analisis.post') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="bulanAwal">Pilih Bulan:</label>
                            <select id="bulanAwal" name="bulanAwal" class="form-control">
                                <option value="" disabled selected>Pilih Bulan Awal</option>
                                @foreach ($dataPenjualan as $penjualan)
                                    <option value="{{ $penjualan->bulan }}">{{ $penjualan->bulan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tahunAwal">Pilih Tahun:</label>
                            <select id="tahunAwal" name="tahunAwal" class="form-control">
                                <option value="" disabled selected>Pilih Tahun Awal</option>
                                @foreach ($dataPenjualanTahun as $penjualan)
                                    <option value="{{ $penjualan->tahun }}">{{ $penjualan->tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="bulanAkhir">Pilih Bulan:</label>
                            <select id="bulanAkhir" name="bulanAkhir" class="form-control">
                                <option value="" disabled selected>Pilih Bulan Akhir</option>
                                @foreach ($dataPenjualan as $penjualan)
                                    <option value="{{ $penjualan->bulan }}">{{ $penjualan->bulan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tahunAkhir">Pilih Tahun:</label>
                            <select id="tahunAkhir" name="tahunAkhir" class="form-control">
                                <option value="" disabled selected>Pilih Tahun Akhir</option>
                                @foreach ($dataPenjualanTahun as $penjualan)
                                    <option value="{{ $penjualan->tahun }}">{{ $penjualan->tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col text-center">
                            <button type="submit" class="btn btn-primary">Analisa</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if (isset($result) && count($result) > 0)
        <div class="col-12 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Penjualan</h5>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show text-center bg-success text-white p-3 rounded"
                            role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger bg-danger alert-dismissible fade show text-center bg-success text-white p-3 rounded"
                            role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <table id="penjualanTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Tahun</th>
                                <th>Bulan</th>
                                <th>No</th>
                                <th>Penjualan Kopi</th>
                                <th>WMA</th>
                                <th>MAD</th>
                                <th>MSE</th>
                                <th>MAPE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($result as $index => $data)
                                <tr>
                                    <td>{{ $data['tahun'] }}</td>
                                    <td>{{ $data['bulan'] }}</td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ number_format($data['penjualan'], 0, ',', '.') }}</td>
                                    <td>{{ $data['wma'] ?? '' }}</td>
                                    <td>{{ $data['mad'] ?? '' }}</td>
                                    <td>{{ $data['mse'] ?? '' }}</td>
                                    <td>{{ $data['mape'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5">Total</th>
                                <th>{{ $totalMAD }}</th>
                                <th>{{ $totalMSE }}</th>
                                <th>{{ $totalMAPE }}</th>
                            </tr>
                            <tr>
                                <th colspan="5">Average</th>
                                <th>{{ $averageMAD }}</th>
                                <th>{{ $averageMSE }}</th>
                                <th>{{ $averageMAPE }}</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="row mt-4">
                        <div class="col-xl-12">
                            <div class="card flex-fill w-100">
                                <div class="card-header">
                                    <h5 class="mb-0 card-title">Hasil Prediksi</h5>
                                </div>
                                <div class="py-3 text-center card-body">
                                    @php $lastResult = end($result); @endphp
                                    <p>Hasil analisa prediksi, peramalan pada bulan
                                        <b>{{ $lastResult['bulan'] }}</b> tahun<b>
                                            {{ $lastResult['tahun'] }}</b> menyediakan
                                        <b>{{ $lastResult['wma'] ?? 'Data tidak tersedia' }}</b> kopi dengan nilai
                                        <b>MAD</b>
                                        sebesar
                                        <b>{{ $averageMAD ?? 'Data tidak tersedia' }}</b>, MSE sebesar
                                        <b>{{ $averageMSE ?? 'Data tidak tersedia' }}</b>, dan nilai MAPE sebesar
                                        <b>{{ $averageMAPE ?? 'Data tidak tersedia' }}%</b>.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <form method="POST" action="{{ route('analisis.storeAnalisa') }}">
                                @csrf
                                <input type="hidden" name="bulanAwal" value="{{ request('bulanAwal') }}">
                                <input type="hidden" name="tahunAwal" value="{{ request('tahunAwal') }}">
                                <input type="hidden" name="bulanAkhir" value="{{ request('bulanAkhir') }}">
                                <input type="hidden" name="tahunAkhir" value="{{ request('tahunAkhir') }}">
                                @foreach ($result as $data)
                                    <input type="hidden" name="tahun[]" value="{{ $data['tahun'] }}">
                                    <input type="hidden" name="bulan[]" value="{{ $data['bulan'] }}">
                                    <input type="hidden" name="jumlah[]" value="{{ $data['penjualan'] }}">
                                    <input type="hidden" name="wma[]" value="{{ $data['wma'] }}">
                                    <input type="hidden" name="mad[]" value="{{ $data['mad'] }}">
                                    <input type="hidden" name="mse[]" value="{{ $data['mse'] }}">
                                    <input type="hidden" name="mape[]" value="{{ $data['mape'] }}">
                                @endforeach
                                <button type="submit" class="btn btn-success">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection


@push('scripts')
    <!-- Include jQuery and DataTables JS & CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#penjualanTable').DataTable({
                "paging": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "responsive": true,
            });
        });
    </script>
@endpush
