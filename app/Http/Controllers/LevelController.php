<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use App\Models\LevelModel;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;   //mengimpor validator

class LevelController extends Controller
{
    public function index()
    {
        //Menambah data
        /*DB::insert('insert into m_level(level_kode, level_nama, created_at) values(?, ?, ?)', ['CUS', 'Pelanggan', now()]);
        return 'Insert data baru berhasil';

        //Melakukan update data
        $row = DB::update('update m_level set level_nama = ? where level_kode = ?', ['Customer', 'CUS']);
        return 'Update data berhasil. Jumlah data yang di-update: ' . $row.' baris';

        //Menghapus data
        $row = DB::delete('delete from m_level where level_kode = ?', ['CUS']);
        return 'Delete data berhasil. Jumlah data yang dihapus: ' .$row.' baris';

        //Menampilkan data
        $data = DB::select('select * from m_level');
        return view('level', ['data' => $data]);*/

        $breadcrumb = (object) [
            'title' => 'Data Level',
            'list'  => ['Home', 'Level']
        ];
        $page = (object) [
            'title' => 'Daftar level yang terdaftar dalam sistem'
        ];
        $activeMenu = 'level';
        $level = LevelModel::all();
        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request) 
    { 
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');
        //Pengaturan Filter
        /*if ($request->level_id){
            $level->where('level_id', $request->level_id);
        }*/
        return DataTables::of($levels) 
            ->addIndexColumn()  
            ->addColumn('aksi', function ($level) { 
                /*$btn = '<a href="'.url('/level/' . $level->level_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('/level/' . $level->level_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'.url('/level/'.$level->level_id).'">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';*/
                    $btn = '<button onclick="modalAction(\''.url('/level/' . $level->level_id .'/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id .'/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id .'/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn; 
            }) 
            ->rawColumns(['aksi'])  
            ->make(true); 
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list'  => ['Home', 'Level', 'Tambah']
        ];
        $page = (object) [
            'title' => 'Tambah Level Baru'
        ];
        $level = LevelModel::all();
        $activeMenu = 'level';
        return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_kode'    => 'required|string|min:3', 
            'level_nama'    => 'required|string|max:100',                   
        ]);
        levelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);
        return redirect('/level')->with('success', 'Data level berhasil disimpan');
    }

    //Menambah data level AJAX
    public function create_ajax()
    {
        return view('level.create_ajax');
    }

    //Menyimpan data level baru AJAX
    public function store_ajax(Request $request) {
        //periksa bila request berupa AJAX atau tidak
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode'    => 'required|string|unique:m_level,level_kode',
                'level_nama'    => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()) {
                return response()->json([
                    'status'    => false, //response status, false: eror/gagal, true: berhasil
                    'message'   => 'Validasi Gagal',
                    'msgField'  => $validator->errors(), //pesan eror validasi
                ]);
            }

            LevelModel::create($request->all());
            return response()->json([
                'status'    => true,
                'message'   => 'Data level berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    //Menampilkan form detil data level AJAX
    public function show_ajax($id)
    {
        $level = LevelModel::find($id);
        
        return view('level.show_ajax', ['level' => $level]);
    }

    public function show(string $id)
    {
        $level = LevelModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list'  => ['Home', 'Level', 'Detail']
        ];
        $page = (object) [
            'title' => 'Detail Level'
        ];
        $activeMenu = 'level';
        return view('level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $id)
    {
        $level = LevelModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];
        $page = (object) [
            'title' => 'Edit Level'
        ];
        $activeMenu = 'level';
        return view('level.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'level_kode' => 'required|string|min:3', 
            'level_nama' => 'required|string|max:100'
        ]);
        levelModel::find($id)->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);
        return redirect('/level')->with('success', 'Data level berhasil diubah');
    }

    //Menampilkan laman form edit level AJAX
    public function edit_ajax(string $id)
    {
        $level = LevelModel::find($id);

        return view('level.edit_ajax', ['level' => $level]);
    }

    //Menyimpan perubahan data level AJAX
    public function update_ajax(Request $request, $id){
        //periksa bila request dari ajax atau bukan
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|unique:m_level,level_kode,'.$id.',level_id',
                'level_nama' => 'required|string|max:100',
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

            $check = LevelModel::find($id);
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

    //Menampilkan form confirm hapus data level AJAX
    public function confirm_ajax(string $id){
        $level = LevelModel::find($id);

        return view('level.confirm_ajax', ['level' => $level]);
    }

    //Menghapus data level AJAX
    public function delete_ajax(Request $request, $id)
    {
        //periksa bila request dari AJAX atau bukan
        if ($request->ajax() || $request->wantsJson()){
            $level = LevelModel::find($id);
            if ($level) {
                $level->delete();
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

    public function import()
    {
        return view('level.import');
    }

    public function import_ajax(Request $request)
    {
        if($request->ajax() || $request->wantsJson()){
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_level' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_level'); // ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // ambil data excel
            $insert = [];
            if(count($data) > 1){ // jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if($baris > 1){ // baris ke 1 adalah header, maka lewati
                        $insert[] = [
                            'level_kode' => $value['A'],
                            'level_nama' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }
                if(count($insert) > 0){
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    LevelModel::insertOrIgnore($insert);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data level berhasil diimpor'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data level yang diimpor'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_excel()
    {
        //ambil data level yang akan diekspor
        $level = LevelModel::select('level_kode', 'level_nama')
                    ->orderBy('level_kode')
                    ->get();
                    //dd($level);
        //load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); //ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'Kode Level');
        $sheet->setCellValue('C1', 'Nama Level');
        
        $sheet->getStyle('A1:C1')->getFont()->setBold(true); //bold header

        $no = 1; //nomor data dimulai dari 1
        $baris = 2; //baris data dimulai dari baris ke-2
        foreach($level as $key => $value){
            $sheet->setCellValue('A'.$baris,$no);
            $sheet->setCellValue('B'.$baris,$value->level_kode);
            $sheet->setCellValue('C'.$baris,$value->level_nama);
            $baris++;
            $no++;
        }
        foreach(range('A', 'C') as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true); //set auto size untuk kolom
        }
        $sheet->setTitle('Data Level'); //set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Level ' . date('Y-m-d H:i:s') . '.xlsx';
        //dd($filename);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    } //end function export_excel

    public function export_pdf()
    {
        $level = LevelModel::select('level_id', 'level_kode', 'level_nama')
                    ->orderBy('level_id')
                    ->orderBy('level_kode')
                    ->get();
                    
        //use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('level.export_pdf', ['level' => $level]);
        $pdf->setPaper('a4', 'portrait'); //set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); //set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data Level '.date('Y-m-d H:i:s').'.pdf');
    }

    public function destroy (string  $id)
    {
        $check = LevelModel::find($id);
        if (!$check) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        } 
        try{
            levelModel::destroy($id);
            return redirect('/level')->with('success', 'Data level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException){
            return redirect('/level')->with('error', 'Data level gagal dihapus karena terdapat tabel lain yang terkait dengan data ini');
        }  
    }
}
