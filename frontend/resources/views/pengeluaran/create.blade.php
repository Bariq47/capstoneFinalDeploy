<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah pengeluaran</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <div class="card shadow p-4" style="width: 380px; border-radius: 12px;">

            <h5 class="text-center fw-bold mb-1">Tambah pengeluaran</h5>
            <p class="text-center text-muted mb-4">Masukkan data pengeluaran</p>

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

            <form method="POST" action="/pengeluaran">
                @csrf

                <table class="table table-borderless align-middle mb-3">
                    <tbody>
                        <tr>
                            <td class="pb-3">
                                <label class="form-label small fw-semibold">Tanggal</label>
                                <input type="date"
                                    name="tanggal"
                                    class="form-control bg-light"
                                    value="{{ old('tanggal') }}"
                                    required>
                            </td>
                        </tr>

                        <tr>
                            <td class="pb-3">
                                <label class="form-label small fw-semibold">Deskripsi</label>
                                <input type="text"
                                    name="deskripsi"
                                    class="form-control bg-light"
                                    placeholder="Contoh: Penjualan"
                                    value="{{ old('deskripsi') }}"
                                    required>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label class="form-label small fw-semibold">Nominal</label>
                                <input type="number"
                                    name="nominal"
                                    class="form-control bg-light"
                                    placeholder="100000"
                                    value="{{ old('nominal') }}"
                                    required>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success w-100 fw-semibold">
                        Simpan
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
