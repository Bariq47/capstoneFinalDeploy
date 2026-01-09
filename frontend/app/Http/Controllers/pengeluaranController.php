<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class pengeluaranController extends Controller
{
    private function token()
    {
        return session('jwt_token');
    }

    private function getKategoriPengeluaran()
    {
        $kategori = Http::withToken($this->token())
            ->get(env('API_URL') . '/kategori')
            ->json();

        $kategoriPengeluaran = collect($kategori)
            ->firstWhere('jenis', 'pengeluaran');

        return $kategoriPengeluaran['id'] ?? null;
    }

    public function index(Request $request)
    {
        $year   = (int) $request->query('year', now()->year);
        $month  = (int) $request->query('month', now()->month);
        $search = $request->query('search');

        // === SUMMARY ===
        $summaryResponse = Http::withToken($this->token())
            ->get(env('API_URL') . '/summary', [
                'year'  => $year,
                'month' => $month,
            ]);

        if (!$summaryResponse->successful()) {
            abort(500, 'Gagal mengambil summary pengeluaran');
        }

        $summary = $summaryResponse->json();

        // === DATA PENGELUARAN ===
        $pengeluaranResponse = Http::withToken($this->token())
            ->get(env('API_URL') . '/laporanPengeluaran', [
                'year' => $year,
                'month' => $month,
                'search' => $search,
            ]);

        if (!$pengeluaranResponse->successful()) {
            abort(500, 'Gagal mengambil data pengeluaran');
        }

        return view('pengeluaran.index', [
            'pengeluaran' => $pengeluaranResponse->json('data') ?? [],
            'totalPengeluaran' => $summary['totalPengeluaran'] ?? 0,
            'year' => $year,
            'month' => $month,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('pengeluaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            'nominal' => 'required|numeric',
        ]);

        $kategoriId = $this->getKategoriPengeluaran();
        if (!$kategoriId) {
            return back()->withErrors(['error' => 'Kategori pengeluaran belum tersedia']);
        }

        $response = Http::withToken($this->token())
            ->post(env('API_URL') . '/transaksi', [
                'tanggal'     => $request->tanggal,
                'deskripsi'   => $request->deskripsi,
                'nominal'     => $request->nominal,
                'kategori_id' => $kategoriId,
            ]);

        if ($response->failed()) {
            return back()
                ->withErrors($response->json('errors') ?? ['error' => 'Gagal menambah pengeluaran'])
                ->withInput();
        }

        return redirect('/pengeluaran')->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pengeluaran = Http::withToken($this->token())
            ->get(env('API_URL') . "/transaksi/$id")
            ->json('data');

        if (!$pengeluaran) {
            return redirect('/pengeluaran')
                ->withErrors(['error' => 'Pengeluaran tidak ditemukan']);
        }

        $pengeluaran['tanggal'] = Carbon::parse($pengeluaran['tanggal'])->format('Y-m-d');

        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            'nominal' => 'required|numeric',
        ]);

        $kategoriId = $this->getKategoriPengeluaran();
        if (!$kategoriId) {
            return back()->withErrors(['error' => 'Kategori pengeluaran belum tersedia']);
        }

        $response = Http::withToken($this->token())
            ->put(env('API_URL') . "/transaksi/$id", [
                'tanggal' => $request->tanggal,
                'deskripsi' => $request->deskripsi,
                'nominal' => $request->nominal,
                'kategori_id' => $kategoriId,
            ]);

        if ($response->failed()) {
            return back()
                ->withErrors($response->json('errors') ?? ['error' => 'Gagal update pengeluaran'])
                ->withInput();
        }

        return redirect('/pengeluaran')->with('success', 'Pengeluaran berhasil diupdate');
    }

    public function destroy($id)
    {
        $response = Http::withToken($this->token())
            ->delete(env('API_URL') . "/transaksi/$id");

        if ($response->failed()) {
            return redirect('/pengeluaran')
                ->withErrors(['error' => 'Gagal menghapus pengeluaran']);
        }

        return redirect('/pengeluaran')->with('success', 'Pengeluaran berhasil dihapus');
    }
}
