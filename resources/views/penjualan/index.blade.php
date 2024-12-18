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
            <div class="card-header">
                <div class="card-title">Tambah Penjualan</div>
            </div>
            <div class="card-body">
                <form
                    action="{{ isset($penjualan) ? route('penjualan.update', $penjualan->id) : route('penjualan.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($penjualan))
                        @method('PUT')
                    @endif
                    @csrf
                    <div class="form-group mb-3">
                        <label for="bulan">Bulan</label>
                        <select class="form-control" id="bulan" name="bulan" required>
                            <option value="" disabled selected>Pilih Bulan</option>
                            <option value="Januari"
                                {{ isset($penjualan) && $penjualan->bulan == 'Januari' ? 'selected' : '' }}>Januari
                            </option>
                            <option value="Februari"
                                {{ isset($penjualan) && $penjualan->bulan == 'Februari' ? 'selected' : '' }}>Februari
                            </option>
                            <option value="Maret"
                                {{ isset($penjualan) && $penjualan->bulan == 'Maret' ? 'selected' : '' }}>Maret</option>
                            <option value="April"
                                {{ isset($penjualan) && $penjualan->bulan == 'April' ? 'selected' : '' }}>April</option>
                            <option value="Mei" {{ isset($penjualan) && $penjualan->bulan == 'Mei' ? 'selected' : '' }}>
                                Mei</option>
                            <option value="Juni" {{ isset($penjualan) && $penjualan->bulan == 'Juni' ? 'selected' : '' }}>
                                Juni</option>
                            <option value="Juli" {{ isset($penjualan) && $penjualan->bulan == 'Juli' ? 'selected' : '' }}>
                                Juli</option>
                            <option value="Agustus"
                                {{ isset($penjualan) && $penjualan->bulan == 'Agustus' ? 'selected' : '' }}>Agustus
                            </option>
                            <option value="September"
                                {{ isset($penjualan) && $penjualan->bulan == 'September' ? 'selected' : '' }}>
                                September</option>
                            <option value="Oktober"
                                {{ isset($penjualan) && $penjualan->bulan == 'Oktober' ? 'selected' : '' }}>Oktober
                            </option>
                            <option value="November"
                                {{ isset($penjualan) && $penjualan->bulan == 'November' ? 'selected' : '' }}>November
                            </option>
                            <option value="Desember"
                                {{ isset($penjualan) && $penjualan->bulan == 'Desember' ? 'selected' : '' }}>Desember
                            </option>
                            <option value="Desember">Desember</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="tahun">Tahun</label>
                        <select class="form-control" id="tahun" name="tahun" required>
                            <option value="">Pilih Tahun</option>
                            @foreach (range(date('Y'), date('Y') - 10) as $tahun)
                                <option value="{{ $tahun }}"
                                    {{ old('tahun', isset($penjualan) ? $penjualan->tahun : '') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Enter Jumlah"
                            value="{{ old('jumlah', isset($penjualan) ? $penjualan->jumlah : '') }}" min="0"
                            required />
                    </div>
                    <div class="form-group mb-3 d-flex justify-content-start">
                        <button type="submit" class="btn btn-success me-2">Submit</button>
                        <button type="reset" class="btn btn-secondary" id="resetButton">Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content2')
    <div class="col-12">
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
                "responsive": true,
                "lengthMenu": [5, 10, 25, 50, 100]
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
        document.getElementById('resetButton').addEventListener('click', function() {
            document.getElementById('bulan').selectedIndex = 0;
            document.getElementById('tahun').selectedIndex = 0;
            document.getElementById('jumlah').value = '';
        });
    </script>
@endpush
