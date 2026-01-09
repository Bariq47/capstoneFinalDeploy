<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransaksiExport implements FromCollection, WithHeadings
{
    protected $jenis;
    protected $year;
    protected $month;
    protected $search;

    public function __construct($jenis, $year, $month, $search)
    {
        $this->jenis = $jenis;
        $this->year = $year;
        $this->month = $month;
        $this->search = $search;
    }

    public function collection()
    {
        $query = Transaksi::with('kategori')
            ->whereHas(
                'kategori',
                fn($q) =>
                $q->where('jenis', $this->jenis)
            );

        if ($this->year) {
            $query->whereYear('tanggal', $this->year);
        }

        if ($this->month) {
            $query->whereMonth('tanggal', $this->month);
        }

        if ($this->search) {
            $query->where('deskripsi', 'like', "%{$this->search}%");
        }

        return $query->get()->map(function ($item) {
            return [
                'ID'        => $item->id,
                'Nominal'   => $item->nominal,
                'Kategori'  => $item->kategori->nama_kategori,
                'Tanggal'   => $item->tanggal->format('Y-m-d'),
                'Deskripsi' => $item->deskripsi,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Nominal', 'Kategori', 'Tanggal', 'Deskripsi'];
    }
}
