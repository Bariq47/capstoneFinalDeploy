<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Akun</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <div class="card shadow p-4" style="width: 400px; border-radius: 12px;">

            <h5 class="text-center fw-bold mb-1">Tambah Akun</h5>
            <p class="text-center text-muted mb-4">Buat akun pengguna baru</p>

            {{-- Error --}}
            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/akun">
                @csrf

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Username</label>
                    <input type="text" name="username" class="form-control bg-light"
                        value="{{ old('username') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control bg-light"
                        value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control bg-light" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Role</label>
                    <select name="role" class="form-select bg-light" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                        <option value="superAdmin" {{ old('role') === 'superAdmin' ? 'selected' : '' }}>
                            Super Admin
                        </option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Simpan
                    </button>
                    <a href="/akun" class="btn btn-secondary w-100 fw-semibold">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</body>

</html>
