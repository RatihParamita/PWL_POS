<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; //untuk mengimpor kelas Hash
use Illuminate\Support\Facades\DB; //untuk mengimpor kelas DB

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_id' => 1,
                'kategori_kode' => 'FNB',
                'kategori_nama' => 'Food & Beverage',
            ],
            [
                'kategori_id' => 2,
                'kategori_kode' => 'BNH',
                'kategori_nama' => 'Beauty & Health',
            ],
            [
               'kategori_id' => 3,
               'kategori_kode' => 'HC',
               'kategori_nama' => 'Home Care',
            ],
            [
                'kategori_id' => 4,
                'kategori_kode' => 'BNK',
                'kategori_nama' => 'Baby & Kid',
            ],
            [
                'kategori_id' => 5,
                'kategori_kode' => 'BAT',
                'kategori_nama' => 'Buku & Alat Tulis',
            ],
        ];
        DB::table('m_kategori')->insert($data);
    }
}
