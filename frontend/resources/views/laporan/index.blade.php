<!DOCTYPE html>
<html>

<head>
    <title>Laporan Tahunan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            flex: 1;
            text-align: center;
        }
    </style>
</head>

<body>

    <h2>Laporan Tahun {{ $year }}</h2>

    <div class="row">
        <div class="card">
            <h5>Total Pendapatan</h5>
            <p>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
        <div class="card">
            <h5>Total Pengeluaran</h5>
            <p>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
        <div class="card">
            <h5>Saldo Bersih</h5>
            <p>Rp {{ number_format($saldoBersih, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="card">
        <h5>Grafik Pendapatan & Pengeluaran Bulanan</h5>
        <canvas id="grafikLaporan"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('grafikLaporan').getContext('2d');

        const labels = {!! json_encode(
            array_map(function ($d) {
                return 'Bulan ' . $d['bulan'];
            }, $dataBulan),
        ) !!};
        const dataPendapatan = {!! json_encode(array_column($dataBulan, 'totalPendapatan')) !!};
        const dataPengeluaran = {!! json_encode(array_column($dataBulan, 'totalPengeluaran')) !!};

        const grafikLaporan = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Pendapatan',
                        data: dataPendapatan,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    },
                    {
                        label: 'Pengeluaran',
                        data: dataPengeluaran,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>

</html>
