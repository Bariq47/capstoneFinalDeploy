@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- FILTER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Dashboard</h4>

        <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2">
            <select name="month" class="form-select form-select-sm">
                <option value="">Semua Bulan (Card)</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <select name="year" class="form-select form-select-sm">
                @for ($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>

            <button class="btn btn-primary btn-sm">Terapkan</button>
        </form>
    </div>

    {{-- CARD STATISTIK --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Total Pendapatan</small>
                    <h5 class="fw-bold text-success fs-4 mt-1">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Total Pengeluaran</small>
                    <h5 class="fw-bold text-danger fs-4 mt-1">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Saldo Bersih</small>
                    <h5 class="fw-bold mt-1">
                        Rp {{ number_format($saldoBersih, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Total Transaksi</small>
                    <h5 class="fw-bold mt-1">
                        {{ $totalTransaksi }}
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">

        {{-- CHART --}}
        <div class="col-md-8">
            @if ($month)
                <div class="card shadow-sm">
                    <div class="card-body">
                        <strong class="d-block mb-2">
                            Tren Transaksi -
                            {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                            {{ $year }}
                        </strong>

                        <div style="height:260px;">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info h-100 d-flex align-items-center">
                    Pilih bulan untuk menampilkan chart
                </div>
            @endif
        </div>

        {{-- ACTION --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    {{-- <strong class="d-block mb-3"></strong> --}}

                    <div class="d-grid gap-2">
                        <a href="{{ route('pendapatan.create') }}" class="btn btn-success">
                            + Tambah Pendapatan
                        </a>
                        <a href="{{ route('pengeluaran.create') }}" class="btn btn-danger">
                            + Tambah Pengeluaran
                        </a>
                        <a href="{{ route('laporan.export', ['year' => $year, 'month' => $month]) }}"
                            class="btn btn-outline-primary">
                            Export Laporan Excel
                        </a>

                    </div>
                </div>
            </div>
        </div>


    </div>

@endsection

@push('scripts')
    <script>
        const labels = @json($labels ?? []);
        const pendapatan = @json($dataPendapatan ?? []);
        const pengeluaran = @json($dataPengeluaran ?? []);

        if (labels.length && document.getElementById('lineChart')) {
            new Chart(document.getElementById('lineChart'), {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                            label: 'Pendapatan',
                            data: pendapatan,
                            borderWidth: 2,
                            tension: 0.4
                        },
                        {
                            label: 'Pengeluaran',
                            data: pengeluaran,
                            borderWidth: 2,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: ctx =>
                                    ctx.dataset.label + ': Rp ' +
                                    ctx.raw.toLocaleString('id-ID')
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
