<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="bg-white sidebar-brand text-dark" href="{{ route('dashboard') }}">
            <div class="d-flex flex-column align-items-center">
                <img src="{{ asset('assets/img/icons/arkanza.png') }}" alt="Arkanza Logo" class="align-middle"
                    style="height: 75px; margin-right: 5px;">
                <span class="align-middle">{{ env('APP_NAME') }}</span>
            </div>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Home
            </li>

            <li class="sidebar-item {{ Request::is('dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard') }}">
                    <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-header">
                Data
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="pages-profile.html">
                    <i class="align-middle" data-feather="database"></i> <span class="align-middle">Data Batik</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="pages-sign-in.html">
                    <i class="align-middle" data-feather="database"></i> <span class="align-middle">Data
                        Penjualan</span>
                </a>
            </li>

            <li class="sidebar-header">
                Perdiksi
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="pages-profile.html">
                    <i class="align-middle" data-feather="search"></i> <span class="align-middle">Kebutuhan</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="pages-sign-in.html">
                    <i class="align-middle" data-feather="layers"></i> <span class="align-middle">Batik</span>
                </a>
            </li>

            <li class="sidebar-header">
                Analisis
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="pages-profile.html">
                    <i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Lihat Hasil
                        Analisis</span>
                </a>
            </li>

        </ul>
    </div>
</nav>
