<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKeuanganExport;
use App\Exports\TransaksiExport;
use App\Exports\TransaksiPendapatanExport;
use App\Exports\TransaksiPengeluaranExport;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class transaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksi = Transaksi::all();
        return response()->json([
            'status' => 'success',
            'data' => $transaksi
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'nominal' => 'required|numeric',
            'kategori_id' => 'required|exists:kategori,id',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable|string',
        ]);
        $transaksi = Transaksi::create($validation);
        return response()->json([
            'status' => 'success',
            'data' => $transaksi
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::find($id);
        if (!$transaksi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $transaksi
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $transaksi = Transaksi::find($id);
        if (!$transaksi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi not found'
            ], 404);
        }

        $validation = $request->validate([
            'nominal' => 'required|numeric',
            'kategori_id' => 'required|exists:kategori,id',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable|string',
        ]);

        $transaksi->update($validation);

        return response()->json([
            'status' => 'success',
            'data' => $transaksi
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaksi = Transaksi::find($id);
        if (!$transaksi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi not found'
            ], 404);
        }

        $transaksi->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi deleted successfully'
        ]);
    }

    public function laporanPendapatan(Request $request)
    {
        $year = $request->query('year', now()->year);
        $month = $request->query('month');
        $search = $request->query('search');

        $query = Transaksi::whereIn('kategori_id', function ($query) {
            $query->select('id')
                ->from('kategori')
                ->where('jenis', 'pendapatan');
        })
            ->whereYear('tanggal', $year);

        if ($month) {
            $query->whereMonth('tanggal', $month);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'LIKE', "%{$search}%")
                    ->orWhere('nominal', 'LIKE', "%{$search}%")
                    ->orWhereDate('tanggal', $search);
            });
        }

        return response()->json([
            'status' => 'success',
            'year' => $year,
            'month' => $month,
            'search' => $search,
            'data' => $query->orderBy('tanggal', 'desc')->get()
        ]);
    }


    public function laporanpengeluaran(Request $request)
    {
        $year = $request->query('year', now()->year);
        $month = $request->query('month');
        $search = $request->query('search');

        $query = Transaksi::whereIn('kategori_id', function ($query) {
            $query->select('id')
                ->from('kategori')
                ->where('jenis', 'pengeluaran');
        })
            ->whereYear('tanggal', $year);

        if ($month) {
            $query->whereMonth('tanggal', $month);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'LIKE', "%{$search}%")
                    ->orWhere('nominal', 'LIKE', "%{$search}%")
                    ->orWhereDate('tanggal', $search);
            });
        }

        return response()->json([
            'status' => 'success',
            'year' => $year,
            'month' => $month,
            'search' => $search,
            'data' => $query->orderBy('tanggal', 'desc')->get()
        ]);
    }

    public function jumlahTransaski()
    {
        $jumlah = Transaksi::count();
        return response()->json([
            'status' => 'success',
            'data' => $jumlah
        ]);
    }

    public function saldoBersih()
    {
        $pendapatan = Transaksi::whereIn('kategori_id', function ($query) {
            $query->select('id')
                ->from('kategori')
                ->where('jenis', 'pendapatan');
        })->sum('nominal');

        $pengeluaran = Transaksi::whereIn('kategori_id', function ($query) {
            $query->select('id')
                ->from('kategori')
                ->where('jenis', 'pengeluaran');
        })->sum('nominal');

        $saldoBersih = $pendapatan - $pengeluaran;

        return response()->json([
            'status' => 'success',
            'data' => $saldoBersih
        ]);
    }

    public function trentahun()
    {
        $data = Transaksi::with('kategori')
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal->format('Y');
            })
            ->map(function ($group, $tahun) {
                return [
                    'tahun' => $tahun,
                    'totalPendapatan' => $group->where('kategori.jenis', 'pendapatan')->sum('nominal'),
                    'totalPengeluaran' => $group->where('kategori.jenis', 'pengeluaran')->sum('nominal'),
                ];
            })
            ->values();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function trenBulan($tahun)
    {
        $data = Transaksi::with('kategori')
            ->whereYear('tanggal', $tahun)
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal->format('m');
            })
            ->map(function ($group, $bulan) {
                return [
                    'bulan' => (int) $bulan,
                    'totalPendapatan' => $group->where('kategori.jenis', 'pendapatan')->sum('nominal'),
                    'totalPengeluaran' => $group->where('kategori.jenis', 'pengeluaran')->sum('nominal'),
                ];
            })
            ->values();

        return response()->json([
            'status' => 'success',
            'tahun' => $tahun,
            'data' => $data
        ]);
    }

    public function summary(Request $request)
    {
        $year  = $request->query('year', now()->year);
        $month = $request->query('month');

        $query = Transaksi::with('kategori')
            ->whereYear('tanggal', $year);

        if ($month) {
            $query->whereMonth('tanggal', $month);
        }

        $totalPendapatan = (clone $query)
            ->whereHas('kategori', fn($q) => $q->where('jenis', 'pendapatan'))
            ->sum('nominal');

        $totalPengeluaran = (clone $query)
            ->whereHas('kategori', fn($q) => $q->where('jenis', 'pengeluaran'))
            ->sum('nominal');

        return response()->json([
            'status' => 'success',
            'year' => $year,
            'month' => $month,
            'totalPendapatan' => $totalPendapatan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoBersih' => $totalPendapatan - $totalPengeluaran,
            'totalTransaksi' => $query->count(),
        ]);
    }

    public function export(Request $request)
    {
        $jenis = $request->query('jenis');
        $year = $request->query('year');
        $month = $request->query('month');
        $search = $request->query('search');

        return Excel::download(
            new TransaksiExport($jenis, $year, $month, $search),
            "transaksi_{$jenis}.xlsx"
        );
    }

    public function chart(Request $request)
    {
        $year  = $request->query('year', now()->year);
        $month = $request->query('month');

        $query = Transaksi::with('kategori')
            ->whereYear('tanggal', $year);

        if ($month) {
            $query->whereMonth('tanggal', $month);
            $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        } else {
            // default 31 jika tanpa bulan
            $daysInMonth = 31;
        }

        $data = $query
            ->join('kategori', 'kategori.id', '=', 'transaksi.kategori_id')
            ->selectRaw('
        DAY(transaksi.tanggal) as day,
        SUM(CASE WHEN kategori.jenis = "pendapatan" THEN transaksi.nominal ELSE 0 END) as pendapatan,
        SUM(CASE WHEN kategori.jenis = "pengeluaran" THEN transaksi.nominal ELSE 0 END) as pengeluaran
    ')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $labels = [];
        $pendapatan = [];
        $pengeluaran = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $labels[] = $i;
            $pendapatan[] = $data[$i]->pendapatan ?? 0;
            $pengeluaran[] = $data[$i]->pengeluaran ?? 0;
        }

        return response()->json([
            'status' => 'success',
            'labels' => $labels,
            'pendapatan' => $pendapatan,
            'pengeluaran' => $pengeluaran,
        ]);
    }
    public function laporanKeuangan(Request $request)
    {
        $year  = $request->query('year', now()->year);
        $month = $request->query('month');

        $query = Transaksi::with('kategori')
            ->whereYear('tanggal', $year);

        if ($month) {
            $query->whereMonth('tanggal', $month);
        }

        $pendapatan = (clone $query)
            ->whereHas('kategori', fn($q) => $q->where('jenis', 'pendapatan'))
            ->sum('nominal');

        $pengeluaran = (clone $query)
            ->whereHas('kategori', fn($q) => $q->where('jenis', 'pengeluaran'))
            ->sum('nominal');

        return response()->json([
            'status' => 'success',
            'periode' => [
                'year' => $year,
                'month' => $month,
            ],
            'totalPendapatan' => $pendapatan,
            'totalPengeluaran' => $pengeluaran,
            'labaRugi' => $pendapatan - $pengeluaran
        ]);
    }
public function exportLaporanKeuangan(Request $request)
{
    $year  = $request->query('year', now()->year);
    $month = $request->query('month');

    return Excel::download(
        new LaporanKeuanganExport($year, $month),
        "laporan_keuangan_{$year}" . ($month ? "_{$month}" : "") . ".xlsx"
    );
}

}
