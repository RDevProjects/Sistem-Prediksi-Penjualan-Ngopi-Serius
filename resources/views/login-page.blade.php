<!DOCTYPE html>
<html lang="en">

@include('include.meta')

<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="mx-auto col-sm-10 col-md-8 col-lg-6 col-xl-5 d-table h-100">
                    <div class="align-middle d-table-cell">
                        <div class="text-center">
                            <img src="{{ asset('assets/img/icons/icon.png') }}" alt="Logo Ngopi" class="w-25">
                        </div>
                        <div class="mt-4 text-center">
                            <h1 class="h2">Selamat datang kembali Admin!</h1>
                            <p class="lead">
                                Masuk ke akun Anda untuk melanjutkan
                            </p>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-3">
                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Email atau Username</label>
                                            <input class="form-control form-control-lg" type="text" name="login"
                                                placeholder="Masukkan email atau username Anda" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Kata Sandi</label>
                                            <input class="form-control form-control-lg" type="password" name="password"
                                                placeholder="Masukkan kata sandi Anda" />
                                        </div>
                                        <div>
                                            <div class="form-check align-items-center">
                                                <input id="customControlInline" type="checkbox" class="form-check-input"
                                                    value="remember-me" name="remember-me" checked>
                                                <label class="form-check-label text-small"
                                                    for="customControlInline">Ingat saya</label>
                                            </div>
                                        </div>
                                        <div class="gap-2 mt-3 d-grid">
                                            <button type="submit" class="btn btn-lg btn-primary">Masuk</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="mb-3 text-center">
                            Tidak punya akun? <a href="pages-sign-up.html">Daftar</a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/js/app.js') }}"></script>

</body>

</html>
