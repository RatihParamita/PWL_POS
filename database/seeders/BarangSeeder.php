<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; //untuk mengimpor kelas Hash
use Illuminate\Support\Facades\DB; //untuk mengimpor kelas DB

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'barang_id' => 1,
                'kategori_id' => 1,
                'barang_kode' => 'KM600',
                'barang_nama' => 'Kecap Manis 600mL',
                'harga_beli' => 30000,
                'harga_jual' => 34000,
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 1,
                'barang_kode' => 'SBDV600',
                'barang_nama' => 'Susu Bubuk Dewasa Vanilla 600g',
                'harga_beli' => 104000,
                'harga_jual' => 110000,
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 1,
                'barang_kode' => 'BC78',
                'barang_nama' => 'Bagelen Cheese 78g',
                'harga_beli' => 20000,
                'harga_jual' => 25000,
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 1,
                'barang_kode' => 'CSC225',
                'barang_nama' => 'Chocolate Short Cake 225g',
                'harga_beli' => 12000,
                'harga_jual' => 15000,
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 1,
                'barang_kode' => 'PCL120',
                'barang_nama' => 'Pisang Coklat Lumer 120g',
                'harga_beli' => 10000,
                'harga_jual' => 15000,
            ],
            [
                'barang_id' => 6,
                'kategori_id' => 2,
                'barang_kode' => 'ACS15',
                'barang_nama' => 'Acne Care Serum 15mL',
                'harga_beli' => 75000,
                'harga_jual' => 78000,
            ],
            [
                'barang_id' => 7,
                'kategori_id' => 2,
                'barang_kode' => 'RGM30',
                'barang_nama' => 'Radiant Glow Moisturizer 30g',
                'harga_beli' => 114000,
                'harga_jual' => 118000,
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 2,
                'barang_kode' => 'ACW100',
                'barang_nama' => 'Acne Cleansing Wash 100mL',
                'harga_beli' => 51000,
                'harga_jual' => 55000,
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 2,
                'barang_kode' => 'PHT100',
                'barang_nama' => 'Purifying Hydra Toner 100mL',
                'harga_beli' => 109000,
                'harga_jual' => 110000,
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 2,
                'barang_kode' => 'CPPAS20',
                'barang_nama' => 'Copper Peptide Pro-Collagen Advance Serum 20mL',
                'harga_beli' => 125000,
                'harga_jual' => 128000,
            ],
            [
                'barang_id' => 11,
                'kategori_id' => 3,
                'barang_kode' => 'ODB120',
                'barang_nama' => 'Outdoor Dust Broom 120cm',
                'harga_beli' => 65000,
                'harga_jual' => 68000,
            ],
            [
                'barang_id' => 12,
                'kategori_id' => 3,
                'barang_kode' => 'IB132',
                'barang_nama' => 'Indoor Broom 132cm',
                'harga_beli' => 59000,
                'harga_jual' => 62000,
            ],
            [
                'barang_id' => 13,
                'kategori_id' => 3,
                'barang_kode' => 'D76',
                'barang_nama' => 'Dustpan 76cm',
                'harga_beli' => 31000,
                'harga_jual' => 35000,
            ],
            [
                'barang_id' => 14,
                'kategori_id' => 3,
                'barang_kode' => 'UBSO113',
                'barang_nama' => 'Ultra Broom Sapu Otomatis 113cm',
                'harga_beli' => 82000,
                'harga_jual' => 85000,
            ],
            [
                'barang_id' => 15,
                'kategori_id' => 3,
                'barang_kode' => 'WT2I176',
                'barang_nama' => 'Wiper Teleskopik 2in1 76cm',
                'harga_beli' => 62000,
                'harga_jual' => 65000,
            ],
        ];
        DB::table('m_barang')->insert($data);
    }
}
