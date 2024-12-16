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
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Data Penjualan</h5>
                <a href="{{ route('penjualan.create') }}" class="btn btn-primary" id="addDataBtn">Input Data</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show text-center bg-success text-white py-3"
                    role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show text-center bg-success text-white py-3"
                    role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card-body">
                <table id="penjualanTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Jumlah Penjualan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataPenjualan as $index => $penjualan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $penjualan->bulan }}</td>
                                <td>{{ $penjualan->tahun }}</td>
                                <td>{{ number_format($penjualan->jumlah, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('penjualan.edit', $penjualan->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <button class="btn btn-danger btn-sm"
                                        onclick="deletePenjualan({{ $penjualan->id }})">Delete</button>
                                    <form id="delete-form-{{ $penjualan->id }}"
                                        action="{{ route('penjualan.delete', $penjualan->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
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
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "responsive": true
            });
        });

        function deletePenjualan(id) {
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Kamu tidak akan bisa mengembalikannya!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endpush
