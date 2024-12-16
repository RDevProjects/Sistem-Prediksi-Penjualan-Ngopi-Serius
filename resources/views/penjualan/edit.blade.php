@extends('layouts.app')

@push('styles-css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Tambah Penjualan</div>
                </div>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="card-body">
                    <form action="{{ route('penjualan.update', $penjualan->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-group mb-3">
                            <label for="bulan">Bulan</label>
                            <select class="form-control" id="bulan" name="bulan" required>
                                <option value="Januari" {{ $penjualan->bulan == 'Januari' ? 'selected' : '' }}>Januari
                                </option>
                                <option value="Februari" {{ $penjualan->bulan == 'Februari' ? 'selected' : '' }}>Februari
                                </option>
                                <option value="Maret" {{ $penjualan->bulan == 'Maret' ? 'selected' : '' }}>Maret</option>
                                <option value="April" {{ $penjualan->bulan == 'April' ? 'selected' : '' }}>April</option>
                                <option value="Mei" {{ $penjualan->bulan == 'Mei' ? 'selected' : '' }}>Mei</option>
                                <option value="Juni" {{ $penjualan->bulan == 'Juni' ? 'selected' : '' }}>Juni</option>
                                <option value="Juli" {{ $penjualan->bulan == 'Juli' ? 'selected' : '' }}>Juli</option>
                                <option value="Agustus" {{ $penjualan->bulan == 'Agustus' ? 'selected' : '' }}>Agustus
                                </option>
                                <option value="September" {{ $penjualan->bulan == 'September' ? 'selected' : '' }}>
                                    September</option>
                                <option value="Oktober" {{ $penjualan->bulan == 'Oktober' ? 'selected' : '' }}>Oktober
                                </option>
                                <option value="November" {{ $penjualan->bulan == 'November' ? 'selected' : '' }}>November
                                </option>
                                <option value="Desember" {{ $penjualan->bulan == 'Desember' ? 'selected' : '' }}>Desember
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
                                        {{ old('tahun', $penjualan->tahun) == $tahun ? 'selected' : '' }}>
                                        {{ $tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah"
                                placeholder="Enter Jumlah" value="{{ old('jumlah', $penjualan->jumlah) }}" min="0"
                                required />
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-success w-100">Submit</button>
                        </div>
                    </form>
                </div>
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
