<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4" style="width: 380px; border-radius: 12px;">

            <!-- Logo -->
            <div class="text-center mb-3">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                    style="width: 50px; height: 50px; font-weight: bold;">
                    A
                </div>
            </div>

            <h5 class="text-center fw-bold">Selamat Datang</h5>
            <p class="text-center text-muted mb-4">Silahkan Masuk ke Akun Anda</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control bg-light" placeholder="Email@gmail.com"
                        required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control bg-light" placeholder="********"
                        required>
                </div>

                <button type="submit" class="btn w-100 fw-bold text-white" style="background-color:#3541EC;">
                    Masuk
                </button>
            </form>

        </div>
    </div>

</body>

</html>
