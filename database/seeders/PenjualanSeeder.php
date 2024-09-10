<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; //untuk mengimpor kelas Hash
use Illuminate\Support\Facades\DB; //untuk mengimpor kelas DB

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'penjualan_id' => 1,
                'user_id' => 1,
                'pembeli' => 'Ratih',
                'penjualan_kode' =>  'P0001',
                'penjualan_tanggal' => '2024-01-15',

            ],
            [
                'penjualan_id' => 2,
                'user_id' => 1,
                'pembeli' => 'Ratna',
                'penjualan_kode' =>  'P0002',
                'penjualan_tanggal' => '2024-01-15',

            ],
            [
                'penjualan_id' => 3,
                'user_id' => 1,
                'pembeli' => 'Danis',
                'penjualan_kode' =>  'P0003',
                'penjualan_tanggal' => '2024-01-15',
            ],
            [
                'penjualan_id' => 4,
                'user_id' => 1,
                'pembeli' => 'Diana',
                'penjualan_kode' =>  'P0004',
                'penjualan_tanggal' => '2024-01-15',

            ],
            [
                'penjualan_id' => 5,
                'user_id' => 1,
                'pembeli' => 'Jessie',
                'penjualan_kode' =>  'P0005',
                'penjualan_tanggal' => '2024-01-15',

            ],
            [
                'penjualan_id' => 6,
                'user_id' => 1,
                'pembeli' => 'Alex',
                'penjualan_kode' =>  'P0006',
                'penjualan_tanggal' => '2024-01-16',

            ],
            [
                'penjualan_id' => 7,
                'user_id' => 1,
                'pembeli' => 'Cindy',
                'penjualan_kode' =>  'P0007',
                'penjualan_tanggal' => '2024-01-16',

            ],
            [
                'penjualan_id' => 8,
                'user_id' => 1,
                'pembeli' => 'Rosa',
                'penjualan_kode' =>  'P0008',
                'penjualan_tanggal' => '2024-01-16',

            ],
            [
                'penjualan_id' => 9,
                'user_id' => 1,
                'pembeli' => 'David',
                'penjualan_kode' =>  'P0009',
                'penjualan_tanggal' => '2024-01-16',

            ],
            [
                'penjualan_id' => 10,
                'user_id' => 1,
                'pembeli' => 'Tommy',
                'penjualan_kode' =>  'P0010',
                'penjualan_tanggal' => '2024-01-16',

            ],
        ];
        DB::table('t_penjualan')->insert($data);
    }
}
