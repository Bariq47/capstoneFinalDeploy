<?php

namespace App\Exports;

use App\Models\Transaksi;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanKeuanganExport implements FromCollection, WithHeadings
{
    protected $year, $month;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function collection()
    {
        $query = Transaksi::with('kategori')
            ->whereYear('tanggal', $this->year);

        if ($this->month) {
            $query->whereMonth('tanggal', $this->month);
        }

        $data = $query->get();

        $rows = $data->map(function ($t) {
            return [
                'Tanggal'   => Carbon::parse($t->tanggal)->format('d-m-Y'),
                'Kategori'  => $t->kategori->nama_kategori,
                'Jenis'     => ucfirst($t->kategori->jenis),
                'Nominal'   => $t->nominal,
                'Deskripsi' => $t->deskripsi,
            ];
        });

        $totalPendapatan = $data
            ->where('kategori.jenis', 'pendapatan')
            ->sum('nominal');

        $totalPengeluaran = $data
            ->where('kategori.jenis', 'pengeluaran')
            ->sum('nominal');

        $rows->push([
            'Tanggal' => '',
            'Kategori' => '',
            'Jenis' => '',
            'Nominal' => '',
            'Deskripsi' => '',
        ]);

        $rows->push([
            'Tanggal' => '',
            'Kategori' => 'TOTAL PENDAPATAN',
            'Jenis' => '',
            'Nominal' => $totalPendapatan,
            'Deskripsi' => '',
        ]);

        $rows->push([
            'Tanggal' => '',
            'Kategori' => 'TOTAL PENGELUARAN',
            'Jenis' => '',
            'Nominal' => $totalPengeluaran,
            'Deskripsi' => '',
        ]);

        $rows->push([
            'Tanggal' => '',
            'Kategori' => 'LABA / RUGI',
            'Jenis' => '',
            'Nominal' => $totalPendapatan - $totalPengeluaran,
            'Deskripsi' => '',
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kategori',
            'Jenis',
            'Nominal',
            'Deskripsi'
        ];
    }
}
