<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{

    private function token()
    {
        return session('jwt_token');
    }

    public function index(Request $request)
    {
        $year  = $request->query('year', now()->year);
        $month = $request->query('month');

        $summaryResponse = Http::withToken(session('jwt_token'))
            ->get(env('API_URL') . '/summary', [
                'year' => $year,
                'month' => $month,
            ]);

        if (!$summaryResponse->successful()) {
            abort(500, 'Gagal mengambil data dashboard');
        }

        $summary = $summaryResponse->json();

        $chartResponse = Http::withToken(session('jwt_token'))
            ->get(env('API_URL') . '/chart', [
                'year' => $year,
                'month' => $month,
            ]);

        $chart = $chartResponse->successful()
            ? $chartResponse->json()
            : ['labels' => [], 'pendapatan' => [], 'pengeluaran' => []];

        return view('dashboard', [
            'totalPendapatan' => $summary['totalPendapatan'] ?? 0,
            'totalPengeluaran' => $summary['totalPengeluaran'] ?? 0,
            'saldoBersih' => $summary['saldoBersih'] ?? 0,
            'totalTransaksi' => $summary['totalTransaksi'] ?? 0,

            'labels' => $chart['labels'],
            'dataPendapatan' => $chart['pendapatan'],
            'dataPengeluaran' => $chart['pengeluaran'],

            'year' => $year,
            'month' => $month,
        ]);
    }
}
