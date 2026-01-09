<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Arvisual')</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">
    <div class="container-fluid">
        <div class="row">

            {{-- SIDEBAR --}}
            <div class="col-md-2 bg-primary text-white min-vh-100 p-4">
                <h5 class="fw-bold mb-4">Arvisual</h5>

                <a href="{{ route('dashboard') }}" class="d-block text-white text-decoration-none mb-3 fw-semibold">
                    Dashboard
                </a>

                <a href="{{ route('pendapatan') }}" class="d-block text-white text-decoration-none mb-3">
                    Pendapatan
                </a>

                <a href="{{ route('pengeluaran') }}" class="d-block text-white text-decoration-none mb-3">
                    Pengeluaran
                </a>

                @if (session('role') === 'superAdmin')
                    <a href="{{ route('akun') }}" class="d-block text-white text-decoration-none mb-3">
                        Akun
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="d-block text-white text-decoration-none mb-3 bg-transparent border-0 p-0">
                        Logout
                    </button>
                </form>
            </div>

            {{-- CONTENT --}}
            <div class="col-md-10 px-4 py-3">
                @yield('content')
            </div>

        </div>
    </div>
    @stack('scripts')
</body>

</html>
