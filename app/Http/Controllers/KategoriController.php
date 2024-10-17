<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;   //mengimpor validator

class KategoriController extends Controller
{
    public function index()
    {
        //Menambah data
        /*$data = [
            'kategori_kode' => 'SNK',
            'kategori_nama' => 'Snack/Makanan Ringan',
            'created_at' => now()
        ];
        DB::table('m_kategori')->insert($data);
        return 'Insert data baru berhasil';

        //Melakukan update data
        $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->update(['kategori_nama' => 'Camilan']);
        return 'Update data berhasil. Jumlah data yang di-update: ' .$row.' baris';

        //Menghapus data
        $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->delete();
        return 'Delete data berhasil. Jumlah data yang dihapus: ' . $row.' baris';

        //Menampilkan data
        $data = DB::table('m_kategori')->get();
        return view('kategori', ['data' => $data]);*/

        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list'  => ['Home', 'Kategori']
        ];
        $page = (object) [
            'title' => 'Daftar kategori yang terdaftar dalam sistem'
        ];
        $activeMenu = 'kategori';
        $kategori = KategoriModel::all();
        return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request) 
    { 
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');
        
        //Pengaturan Filter
        /*if ($request->kategori_id){
            $kategori->where('kategori_id', $request->kategori_id);
        }*/
        return DataTables::of($kategori) 
            ->addIndexColumn()  
            ->addColumn('aksi', function ($kategori) { 
                /*$btn = '<a href="'.url('/kategori/' . $kategori->kategori_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('/kategori/' . $kategori->kategori_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'.url('/kategori/'.$kategori->kategori_id).'">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';*/
                $btn = '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id .'/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id .'/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id .'/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn; 
            }) 
            ->rawColumns(['aksi'])  
            ->make(true); 
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Kategori',
            'list'  => ['Home', 'Kategori', 'Tambah']
        ];
        $page = (object) [
            'title' => 'Tambah Kategori Baru'
        ];
        $kategori = KategoriModel::all();
        $activeMenu = 'kategori';
        return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_kode'    => 'required|string|min:3', 
            'kategori_nama'    => 'required|string|max:100',                   
        ]);
        KategoriModel::create([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);
        return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
    }

    //Menambah data kategori AJAX
    public function create_ajax()
    {
        return view('kategori.create_ajax');
    }

    //Menyimpan data kategori baru AJAX
    public function store_ajax(Request $request) {
        //periksa bila request berupa AJAX atau tidak
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode'    => 'required|string|unique:m_kategori,kategori_kode',
                'kategori_nama'    => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()) {
                return response()->json([
                    'status'    => false, //response status, false: eror/gagal, true: berhasil
                    'message'   => 'Validasi Gagal',
                    'msgField'  => $validator->errors(), //pesan eror validasi
                ]);
            }

            KategoriModel::create($request->all());
            return response()->json([
                'status'    => true,
                'message'   => 'Data kategori berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function show(string $id)
    {
        $kategori = KategoriModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Detail Kategori',
            'list'  => ['Home', 'Kategori', 'Detail']
        ];
        $page = (object) [
            'title' => 'Detail Kategori'
        ];
        $activeMenu = 'kategori';
        return view('kategori.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $id)
    {
        $kategori = KategoriModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Edit Kategori',
            'list' => ['Home', 'Kategori', 'Edit']
        ];
        $page = (object) [
            'title' => 'Edit Kategori'
        ];
        $activeMenu = 'kategori';
        return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_kode' => 'required|string|min:3', 
            'kategori_nama' => 'required|string|max:100'
        ]);
        KategoriModel::find($id)->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama
        ]);
        return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
    }

    //Menampilkan laman form edit kategori AJAX
    public function edit_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);

        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    //Menyimpan perubahan data kategori AJAX
    public function update_ajax(Request $request, $id){
        //periksa bila request dari ajax atau bukan
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|unique:m_kategori,kategori_kode',
                'kategori_nama' => 'required|string|max:100',
            ];

            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }

            $check = KategoriModel::find($id);
            if ($check) {
                if(!$request->filled('password') ){ // jika password tidak diisi, maka hapus dari request
                    $request->request->remove('password');
                }

                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else{
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    //Menampilkan form confirm hapus data kategori AJAX
    public function confirm_ajax(string $id){
        $kategori = KategoriModel::find($id);

        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }

    //Menghapus data kategori AJAX
    public function delete_ajax(Request $request, $id)
    {
        //periksa bila request dari AJAX atau bukan
        if ($request->ajax() || $request->wantsJson()){
            $kategori = KategoriModel::find($id);
            if ($kategori) {
                $kategori->delete();
                return response()->json([
                    'status'    => true,
                    'message'   => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_pdf()
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama')
                    ->orderBy('kategori_id')
                    ->orderBy('kategori_kode')
                    ->get();
                    
        //use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('kategori.export_pdf', ['kategori' => $kategori]);
        $pdf->setPaper('a4', 'portrait'); //set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); //set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data Kategori '.date('Y-m-d H:i:s').'.pdf');
    }

    public function destroy (string  $id)
    {
        $check = KategoriModel::find($id);
        if (!$check) {
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        } 
        try{
            KategoriModel::destroy($id);
            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
        } catch (\Illuminate\Database\QueryException){
            return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
