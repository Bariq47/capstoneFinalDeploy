<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class laporanController extends Controller
{
    private function token()
    {
        return session('jwt_token');
    }

    public function index(Request $request)
    {

        $year = $request->input('year', Carbon::now()->year);

        $response = Http::withToken($this->token())
            ->get(env('API_URL') . "/trenBulanan/$year")
            ->json();

        // dd($response);

        $dataBulan = $response['data'] ?? [];
        // dd($dataBulan);

        $totalPendapatan = collect($dataBulan)->sum('totalPendapatan');
        $totalPengeluaran = collect($dataBulan)->sum('totalPengeluaran');
        $saldoBersih = $totalPendapatan - $totalPengeluaran;

        // dd($totalPendapatan, $totalPengeluaran, $saldoBersih);

        return view('laporan.index', [
            'year' => $year,
            'dataBulan' => $dataBulan,
            'totalPendapatan' => $totalPendapatan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoBersih' => $saldoBersih,
        ]);
    }

    public function export(Request $request)
    {
        $response = Http::withToken($this->token())
            ->get(env('API_URL') . '/exportTransaksi', [
                'jenis' => $request->input('jenis'),
                'year' => $request->input('year'),
                'month' => $request->input('month'),
                'search' => $request->input('search'),
            ]);
        return response()->streamDownload(
            fn() => print($response->body()),
            'transaksi_' . $request->query('jenis') . '.xlsx',
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }

    public function exportLaporanKeuangan(Request $request)
    {
        $response = Http::withToken($this->token())
            ->get(env('API_URL') . '/exportLaporanKeuangan', [
                'year' => $request->year,
                'month' => $request->month,
            ]);

        return response()->streamDownload(
            fn() => print($response->body()),
            'laporan_keuangan.xlsx',
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }
}
