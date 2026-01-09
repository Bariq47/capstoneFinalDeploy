<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit pengeluaran</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <div class="card shadow p-4" style="width: 380px; border-radius: 12px;">

            <h5 class="text-center fw-bold mb-1">Edit pengeluaran</h5>
            <p class="text-center text-muted mb-4">Perbarui data pengeluaran</p>

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

            <form method="POST" action="/pengeluaran/{{ $pengeluaran['id'] }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control bg-light"
                        value="{{ old('tanggal', $pengeluaran['tanggal']) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Deskripsi</label>
                    <input type="text" name="deskripsi" class="form-control bg-light"
                        value="{{ old('deskripsi', $pengeluaran['deskripsi']) }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Nominal</label>
                    <input type="number" name="nominal" class="form-control bg-light"
                        value="{{ old('nominal', $pengeluaran['nominal']) }}" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning w-100 fw-semibold">
                        Update
                    </button>
                    <a href="/pengeluaran" class="btn btn-secondary w-100 fw-semibold">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</body>

</html>
