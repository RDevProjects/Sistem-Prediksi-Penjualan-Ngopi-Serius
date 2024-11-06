<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.meta')
    @stack('styles-css')
</head>

<body>
    <div class="wrapper">
        @include('include.sidenav')

        <div class="main">
            @include('include.topnav')

            <main class="content">
                <div class="p-0 container-fluid">

                    <h1 class="mb-3 h3"><strong>Analytics</strong> Dashboard</h1>

                    <div class="row">
                        @yield('content')
                    </div>

                    <div class="row">
                        @yield('content2')
                    </div>

                    <div class="row">
                        @yield('content3')
                    </div>

                </div>
            </main>

            @include('include.footer')
        </div>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    @yield('scripts')

</body>

</html>
