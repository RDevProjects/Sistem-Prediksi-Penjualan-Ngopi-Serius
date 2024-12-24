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

{{-- @section('content')
    <div class="col-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('analisis.post') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="bulanAwal">Pilih Bulan:</label>
                            <select id="bulanAwal" name="bulanAwal" class="form-control">
                                <option value="" disabled selected>Pilih Bulan Awal</option>
                                @foreach ($dataAnalisaWaktu as $penjualan)
                                    <option value="{{ $penjualan->created_at_minute }}">{{ $penjualan->created_at_minute }}
                                    </option>
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
@endsection --}}

@section('content2')
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
                            <th>Penjualan Kopi</th>
                            <th>WMA</th>
                            <th>MAD</th>
                            <th>MSE</th>
                            <th>MAPE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataAnalisa as $index => $data)
                            <tr>
                                <td>{{ $data->tahun }}</td>
                                <td>{{ $data->bulan }}</td>
                                <td>{{ number_format($data->jumlah, 0, ',', '.') }}</td>
                                <td>{{ $data->wma ?? '' }}</td>
                                <td>{{ $data->mad ?? '' }}</td>
                                <td>{{ $data->mse ?? '' }}</td>
                                <td>{{ $data->mape ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">Total</th>
                            <th>{{ $totalMAD }}</th>
                            <th>{{ $totalMSE }}</th>
                            <th>{{ $totalMAPE }}</th>
                        </tr>
                        <tr>
                            <th colspan="4">Average</th>
                            <th>{{ $averageMAD }}</th>
                            <th>{{ $averageMSE }}</th>
                            <th>{{ $averageMAPE }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
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
