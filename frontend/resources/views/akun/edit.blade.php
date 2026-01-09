<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Akun</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <div class="card shadow p-4" style="width: 400px; border-radius: 12px;">

            <h5 class="text-center fw-bold mb-1">Edit Akun</h5>
            <p class="text-center text-muted mb-4">Perbarui data akun pengguna</p>

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

            <form method="POST" action="/akun/{{ $user['id'] }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Username</label>
                    <input type="text" name="username" class="form-control bg-light"
                        value="{{ old('username', $user['username']) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control bg-light"
                        value="{{ old('email', $user['email']) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">
                        Password <span class="text-muted">(kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" name="password" class="form-control bg-light">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Role</label>
                    <select name="role" class="form-select bg-light" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role', $user['role']) === 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                        <option value="superAdmin" {{ old('role', $user['role']) === 'superAdmin' ? 'selected' : '' }}>
                            Super Admin
                        </option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning w-100 fw-semibold">
                        Update
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
