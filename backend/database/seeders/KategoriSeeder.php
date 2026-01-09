<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kategori::create([
            'nama_kategori' => 'pendapatan',
            'jenis' => 'pendapatan',
            'deskripsi' => 'Pendapatan dari pekerjaan',
        ]);

        Kategori::create([
            'nama_kategori' => 'pengeluaran',
            'jenis' => 'pengeluaran',
            'deskripsi' => 'Pengeluaran',
        ]);

    }
}
