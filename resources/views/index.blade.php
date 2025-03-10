@extends('layouts.app')

@push('styles-css')
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
    <div class="container">
        <div class="col-xl-12 d-flex">
            <div class="w-100">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="mt-0 col">
                                        <h5 class="card-title">Data Penjualan</h5>
                                    </div>

                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="credit-card"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ $jumlahDataPenjualan ?? '-' }}</h1>
                                {{-- <div class="mb-0">
                                    <span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i> -3.65% </span>
                                    <span class="text-muted">Since last week</span>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="mt-0 col">
                                        <h5 class="card-title">Jumlah Penjualan</h5>
                                    </div>

                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ $dataPenjualan ?? '-' }}</h1>
                                {{-- <div class="mb-0">
                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> 6.65% </span>
                                    <span class="text-muted">Since last week</span>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12">
        <div class="card flex-fill w-100">
            <div class="card-header">

                <h5 class="mb-0 card-title">Recent Movement</h5>
            </div>
            <div class="py-3 card-body">
                <div class="chart chart-sm">
                    <canvas id="chartjs-dashboard-line"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
            var gradient = ctx.createLinearGradient(0, 0, 0, 225);
            gradient.addColorStop(0, "rgba(215, 227, 244, 1)");
            gradient.addColorStop(1, "rgba(215, 227, 244, 0)");
            // Line chart
            new Chart(document.getElementById("chartjs-dashboard-line"), {
                type: "line",
                data: {
                    labels: [
                        @foreach ($dataAnalisa as $data)
                            "{{ $data->bulan }} {{ $data->tahun }}",
                        @endforeach
                    ],
                    datasets: [{
                            label: "Data Aktual",
                            fill: true,
                            backgroundColor: gradient,
                            borderColor: window.theme.primary,
                            data: [
                                @foreach ($dataAnalisa as $data)
                                    {{ $data->jumlah }},
                                @endforeach
                            ],
                            lineTension: 0.1
                        },
                        {
                            label: "Data Prediksi",
                            fill: true,
                            backgroundColor: "rgba(255, 99, 132, 0.2)",
                            borderColor: "rgba(255, 99, 132, 1)",
                            data: [
                                @foreach ($dataAnalisa as $data)
                                    {{ $data->wma }},
                                @endforeach
                            ],
                            lineTension: 0.1
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltips: {
                        intersect: false
                    },
                    hover: {
                        intersect: true
                    },
                    plugins: {
                        filler: {
                            propagate: false
                        }
                    },
                    scales: {
                        xAxes: [{
                            reverse: true,
                            // gridLines: {
                            //     color: "rgba(0,0,0,0.0)"
                            // }
                        }],
                        yAxes: [{
                            ticks: {
                                stepSize: 500,
                                callback: function(value, index, values) {
                                    return value;
                                }
                            },
                            display: true,
                            borderDash: [3, 3],
                            // gridLines: {
                            //     color: "rgba(0,0,0,0.0)"
                            // }
                        }]
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var date = new Date(Date.now() - 5 * 24 * 60 * 60 * 1000);
            var defaultDate = date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate();
            document.getElementById("datetimepicker-dashboard").flatpickr({
                inline: true,
                prevArrow: "<span title=\"Previous month\">&laquo;</span>",
                nextArrow: "<span title=\"Next month\">&raquo;</span>",
                defaultDate: defaultDate
            });
        });
    </script>
@endpush
