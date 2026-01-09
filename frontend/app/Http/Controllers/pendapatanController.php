<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class pendapatanController extends Controller
{
    private function token()
    {
        return session('jwt_token');
    }
    private function getKategoriPendapatan()
    {
        $kategori = Http::withToken($this->token())
            ->get(env('API_URL') . '/kategori')
            ->json();

        $kategoriPendapatan = collect($kategori)
            ->firstWhere('jenis', 'pendapatan');

        return $kategoriPendapatan['id'] ?? null;
    }

    public function index(Request $request)
    {
        $year   = (int) $request->query('year', now()->year);
        $month  = (int) $request->query('month', now()->month);
        $search = $request->query('search');

        $summaryResponse = Http::withToken($this->token())
            ->get(env('API_URL') . '/summary', [
                'year'  => $year,
                'month' => $month,
            ]);

        if (!$summaryResponse->successful()) {
            abort(500, 'Gagal mengambil summary pendapatan');
        }

        $summary = $summaryResponse->json();

        $pendapatanResponse = Http::withToken($this->token())
            ->get(env('API_URL') . '/laporanPendapatan', [
                'year' => $year,
                'month' => $month,
                'search' => $search,
            ]);

        if (!$pendapatanResponse->successful()) {
            abort(500, 'Gagal mengambil data pendapatan');
        }

        return view('pendapatan.index', [
            'pendapatan' => $pendapatanResponse->json('data') ?? [],
            'totalPendapatan'=> $summary['totalPendapatan'] ?? 0,
            'year' => $year,
            'month' => $month,
            'search' => $search,
        ]);
    }
    public function create()
    {
        return view('pendapatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            'nominal' => 'required|numeric',
        ]);
        $kategoriId = $this->getKategoriPendapatan();
        if (!$kategoriId) {
            return back()->withErrors(['error' => 'Kategori pendapatan belum tersedia']);
        }
        $response = Http::withToken($this->token())
            ->post(env('API_URL') . '/transaksi', [
                'tanggal' => $request->tanggal,
                'deskripsi' => $request->deskripsi,
                'nominal' => $request->nominal,
                'kategori_id' => $kategoriId,
            ]);

        if ($response->failed()) {
            return back()
                ->withErrors($response->json('errors') ?? ['error' => 'Gagal menambah pendapatan'])
                ->withInput();
        }

        return redirect('/pendapatan')->with('success', 'Pendapatan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pendapatan = Http::withToken($this->token())
            ->get(env('API_URL') . "/transaksi/$id")
            ->json('data');

        if (!$pendapatan) {
            return redirect('/pendapatan')
                ->withErrors(['error' => 'Pendapatan tidak ditemukan']);
        }
        $pendapatan['tanggal'] = Carbon::parse($pendapatan['tanggal'])->format('Y-m-d');

        return view('pendapatan.edit', compact('pendapatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            'nominal' => 'required|numeric',
        ]);

        $kategoriId = $this->getKategoriPendapatan();
        if (!$kategoriId) {
            return back()->withErrors(['error' => 'Kategori pendapatan belum tersedia']);
        }

        if (!$kategoriId) {
            return back()->withErrors(['error' => 'Kategori pendapatan belum tersedia']);
        }
        $response = Http::withToken($this->token())
            ->put(env('API_URL') . "/transaksi/$id", [ // pakai PUT
                'tanggal' => $request->tanggal,
                'deskripsi' => $request->deskripsi,
                'nominal' => $request->nominal,
                'kategori_id' => $kategoriId,
            ]);

        if ($response->failed()) {
            return back()
                ->withErrors($response->json('errors') ?? ['error' => 'Gagal update pendapatan'])
                ->withInput();
        }

        return redirect('/pendapatan')->with('success', 'Pendapatan berhasil diupdate');
    }

    public function destroy($id)
    {

        $response = Http::withToken($this->token())
            ->delete(env('API_URL') . "/transaksi/$id");

        if ($response->failed()) {
            return redirect('/pendapatan')
                ->withErrors(['error' => 'Gagal menghapus pendapatan']);
        }

        return redirect('/pendapatan')->with('success', 'Pendapatan berhasil dihapus');
    }
}
