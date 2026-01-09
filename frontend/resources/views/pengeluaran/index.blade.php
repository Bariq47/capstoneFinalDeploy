@extends('layouts.app')

@section('title', 'Pengeluaran')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Pengeluaran</h4>
            <div class="d-flex align-items-center gap-2 text-muted">
                <i class="bi bi-cash-stack"></i>
                <span>
                    Total Pengeluaran
                    {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}
                </span>
            </div>
            <div class="fw-bold text-danger fs-4 mt-1">
                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
            </div>
        </div>

        <div>
            <a href="{{ route('pengeluaran.create') }}" class="btn btn-primary px-4">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pengeluaran
            </a>
        </div>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="row g-2 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label fw-semibold">Bulan</label>
            <select name="month" class="form-select">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ (int) $month === $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold">Tahun</label>
            <input type="number" name="year" class="form-control" min="2000" max="{{ now()->year }}"
                value="{{ $year }}">
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-dark w-100">
                <i class="bi bi-filter me-1"></i> Filter
            </button>

            <a href="{{ route('pengeluaran') }}" class="btn btn-outline-secondary w-100">
                Reset
            </a>
        </div>
    </form>

    {{-- SEARCH --}}
    <form method="GET" class="row g-2 align-items-end mb-4">
        <div class="col-md-4">
            <label class="form-label fw-semibold">Cari Deskripsi</label>
            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan deskripsi transaksi"
                value="{{ $search }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-dark w-100">
                <i class="bi bi-search me-1"></i> Cari
            </button>
        </div>
    </form>
    <a href="{{ route('transaksi.export', [
        'jenis' => 'pengeluaran',
        'year' => $year,
        'month' => $month,
        'search' => $search,
    ]) }}"
        class="btn btn-outline-dark mb-3">
        <i class="bi bi-download me-1"></i> Export
    </a>
    {{-- TABLE --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Nominal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengeluaran as $p)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($p['tanggal'])->format('d-m-Y') }}</td>
                            <td>{{ $p['deskripsi'] ?? '-' }}</td>
                            <td class="text-danger fw-semibold">
                                Rp {{ number_format($p['nominal'], 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('pengeluaran.edit', $p['id']) }}" class="btn btn-warning">
                                        Edit
                                    </a>
                                    <form action="{{ route('pengeluaran.destroy', $p['id']) }}" method="POST"
                                        class="form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-delete"
                                            data-text="Data pengeluaran ini akan dihapus permanen">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Tidak ada data pengeluaran untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
