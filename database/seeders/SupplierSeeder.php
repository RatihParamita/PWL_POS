<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; //untuk mengimpor kelas Hash
use Illuminate\Support\Facades\DB; //untuk mengimpor kelas DB

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_id' => 1,
                'supplier_kode' => 'JUC',
                'supplier_nama' => 'PT Jaya Utama Cantik',
                'supplier_alamat' => 'Tangerang, Jakarta Barat, DKI Jakarta',
            ],
            [
                'supplier_id' => 2,
                'supplier_kode' => 'KJ',
                'supplier_nama' => 'CV. Kaya Jaya',
                'supplier_alamat' => 'Bandung, Jawa Barat',
            ],
            [
               'supplier_id' => 3,
               'supplier_kode' => 'MSM',
               'supplier_nama' => 'CV. Multi Solution Market',
               'supplier_alamat' => 'Tangerang, Jakarta Barat, DKI Jakarta',
            ],
        ];
        DB::table('m_supplier')->insert($data);
    }
}
