<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\PenjualanController;
use App\Models\KategoriModel;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});

Route::get('/level', [LevelController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/user', [UserController::class, 'index']);
Route::get('/user/tambah', [UserController::class, 'tambah']);
Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);
Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);*/

Route::pattern('id', '[0-9]+'); //ketika ada parameter {id}, maka harus berupa angka

Route::get('signup', [RegistrationController::class, 'registration'])->name('signup');
Route::post('signup', [RegistrationController::class, 'store']);

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function(){
    //semua route yang perlu otentikasi
    Route::get('/', [WelcomeController::class, 'index']);

    Route::middleware(['authorize:ADM,MNG,STF,PLG'])->group(function(){
        Route::get('/profile', [ProfileController::class, 'index']);
        Route::get('/profile/{id}/edit_ajax', [ProfileController::class, 'edit_ajax']);
        Route::put('/profile/{id}/update_ajax', [ProfileController::class, 'update_ajax']);
        Route::get('/profile/{id}/edit_foto', [ProfileController::class, 'edit_foto']);
        Route::put('/profile/{id}/update_foto', [ProfileController::class, 'update_foto']);
    });

    //Semua route di grup ini harus punya role ADM (Administrator)
    Route::group(['prefix' => 'user', 'middleware'=> 'authorize:ADM'], function(){
        Route::get('/', [UserController::class, 'index']);                          //menampilkan laman awal user
        Route::post('/list', [UserController::class, 'list']);                      //menampilkan data user dalam bentuk json untuk datatables
        Route::get('/create', [UserController::class, 'create']);                   //menampilkan laman form tambah user
        Route::post('/', [UserController::class, 'store']);                         //menyimpan data user baru
        Route::get('/create_ajax', [UserController::class, 'create_ajax']);         //menampilkan laman form tambah user AJAX
        Route::post('/ajax', [UserController::class, 'store_ajax']);                //menyimpan data user baru AJAX
        Route::get('/{id}', [UserController::class, 'show']);                       //menampilkan detail user
        Route::get('/{id}/edit', [UserController::class, 'edit']);                  //menampilkan laman form edit user
        Route::put('/{id}', [UserController::class, 'update']);                     //menyimpan perubahan data user
        Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);        //menampilkan form detil data user AJAX
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);        //menampilkan laman form edit user AJAX
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);    //menyimpan perubahan data user AJAX
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);   //menampilkan form confirm hapus data user AJAX
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); //menghapus data user AJAX
        Route::delete('/{id}', [UserController::class, 'destroy']);                 //menghapus data user
        Route::get('/export_pdf', [UserController::class, 'export_pdf']);           //mengekspor data user dalam bentuk file pdf
    });
    
    //Semua route di grup ini harus punya role ADM (Administrator)
    Route::group(['prefix' => 'level', 'middleware'=> 'authorize:ADM'], function(){
        Route::get('/', [LevelController::class, 'index']);                             //menampilkan laman awal level
        Route::post('/list', [LevelController::class, 'list']);                         //menampilkan data level dalam bentuk json untuk datatables
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']);            //menampilkan laman form tambah level AJAX
        Route::post('/ajax', [LevelController::class, 'store_ajax']);                   //menyimpan data level baru AJAX
        Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);           //menampilkan form detil data level AJAX
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);           //menampilkan laman form edit level AJAX
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);       //menyimpan perubahan data level AJAX
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);      //menampilkan form confirm hapus data level AJAX
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);    //menghapus data level AJAX
        Route::get('/import', [LevelController::class, 'import']);                      //menampilkan form impor data level
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']);           //mengimpor file excel ke daftar data level
        Route::get('/export_excel', [LevelController::class, 'export_excel']);          //mengekspor data level dalam bentuk file excel
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']);              //mengekspor data level dalam bentuk file pdf
    });
    
    //Semua route di grup ini harus punya role ADM (Administrator) dan MNG (Manager)
    Route::group(['prefix' => 'kategori', 'middleware'=> 'authorize:ADM,MNG'], function(){
        Route::get('/', [KategoriController::class, 'index']);                              //menampilkan laman awal kategori
        Route::post('/list', [KategoriController::class, 'list']);                          //menampilkan data kategori dalam bentuk json untuk datatables
        Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);             //menampilkan laman form tambah kategori AJAX
        Route::post('/ajax', [KategoriController::class, 'store_ajax']);                    //menyimpan data kategori baru AJAX
        Route::get('/{id}/show_ajax', [KategoriController::class, 'show_ajax']);            //menampilkan form detil data kategori AJAX
        Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);            //menampilkan laman form edit kategori AJAX
        Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);        //menyimpan perubahan data kategori AJAX
        Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);       //menampilkan form confirm hapus data kategori AJAX
        Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']);     //menghapus data kategori AJAX
        Route::get('/import', [KategoriController::class, 'import']);                       //menampilkan form impor data kategori
        Route::post('/import_ajax', [KategoriController::class, 'import_ajax']);            //mengimpor file excel ke daftar data kategori
        Route::get('/export_excel', [KategoriController::class, 'export_excel']);           //mengekspor data kategori dalam bentuk file excel
        Route::get('/export_pdf', [KategoriController::class, 'export_pdf']);               //mengekspor data kategori dalam bentuk file pdf
    });
    
    //Semua route di grup ini harus punya role ADM (Administrator)
    Route::group(['prefix' => 'supplier', 'middleware'=> 'authorize:ADM'], function(){
        Route::get('/', [SupplierController::class, 'index']);                              //menampilkan laman awal supplier
        Route::post('/list', [SupplierController::class, 'list']);                          //menampilkan data supplier dalam bentuk json untuk datatables
        Route::get('/create_ajax', [SupplierController::class, 'create_ajax']);             //menampilkan laman form tambah supplier AJAX
        Route::post('/ajax', [SupplierController::class, 'store_ajax']);                    //menyimpan data supplier baru AJAX
        Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax']);            //menampilkan form detil data supplier AJAX
        Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']);            //menampilkan laman form edit supplier AJAX
        Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']);        //menyimpan perubahan data supplier AJAX
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);       //menampilkan form confirm hapus data supplier AJAX
        Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']);     //menghapus data supplier AJAX
        Route::get('/import', [SupplierController::class, 'import']);                       //menampilkan form impor data supplier
        Route::post('/import_ajax', [SupplierController::class, 'import_ajax']);            //mengimpor file excel ke daftar data supplier
        Route::get('/export_excel', [SupplierController::class, 'export_excel']);           //mengekspor data supplier dalam bentuk file excel
        Route::get('/export_pdf', [SupplierController::class, 'export_pdf']);               //mengekspor data supplier dalam bentuk file pdf
    });

    //Semua route di grup ini harus punya role ADM (Administrator) dan MNG (Manager)
    Route::group(['prefix' => 'barang', 'middleware'=> 'authorize:ADM,MNG'], function(){
        Route::get('/', [BarangController::class, 'index']);                                //menampilkan laman awal barang
        Route::post('/list', [BarangController::class, 'list']);                            //menampilkan data barang dalam bentuk json untuk datatables
        Route::get('/create_ajax', [BarangController::class, 'create_ajax']);               //menampilkan laman form tambah barang AJAX
        Route::post('/ajax', [BarangController::class, 'store_ajax']);                      //menyimpan data barang baru AJAX
        Route::get('/{id}/show_ajax', [BarangController::class, 'show_ajax']);              //menampilkan form detil data barang AJAX
        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);              //menampilkan laman form edit barang AJAX
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);          //menyimpan perubahan data barang AJAX
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);         //menampilkan form confirm hapus data barang AJAX
        Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);       //menghapus data barang AJAX
        Route::get('/import', [BarangController::class, 'import']);                         //menampilkan form impor data barang
        Route::post('/import_ajax', [BarangController::class, 'import_ajax']);              //mengimpor file excel ke daftar data barang
        Route::get('/export_excel', [BarangController::class, 'export_excel']);             //mengekspor data barang dalam bentuk file excel
        Route::get('/export_pdf', [BarangController::class, 'export_pdf']);                 //mengekspor data barang dalam bentuk file pdf
    });

    //Semua route di grup ini harus punya role ADM (Administrator), MNG (Manager), dan STF (Staff/Kasir)
    Route::group(['prefix' => 'stok', 'middleware'=> 'authorize:ADM,MNG,STF'], function(){
        Route::get('/', [StokController::class, 'index']);                                //menampilkan laman awal stok
        Route::post('/list', [StokController::class, 'list']);                            //menampilkan data stok dalam bentuk json untuk datatables
        Route::get('/create_ajax', [StokController::class, 'create_ajax']);               //menampilkan laman form tambah stok AJAX
        Route::post('/ajax', [StokController::class, 'store_ajax']);                      //menyimpan data stok baru AJAX
        Route::get('/{id}/show_ajax', [StokController::class, 'show_ajax']);              //menampilkan form detil data stok AJAX
        Route::get('/{id}/edit_ajax', [StokController::class, 'edit_ajax']);              //menampilkan laman form edit stok AJAX
        Route::put('/{id}/update_ajax', [StokController::class, 'update_ajax']);          //menyimpan perubahan data stok AJAX
        Route::get('/{id}/delete_ajax', [StokController::class, 'confirm_ajax']);         //menampilkan form confirm hapus data stok AJAX
        Route::delete('/{id}/delete_ajax', [StokController::class, 'delete_ajax']);       //menghapus data stok AJAX
        Route::get('/import', [StokController::class, 'import']);                         //menampilkan form impor data stok
        Route::post('/import_ajax', [StokController::class, 'import_ajax']);              //mengimpor file excel ke daftar data stok
        Route::get('/export_excel', [StokController::class, 'export_excel']);             //mengekspor data stok dalam bentuk file excel
        Route::get('/export_pdf', [StokController::class, 'export_pdf']);                 //mengekspor data stok dalam bentuk file pdf
    });

    //Semua route di grup ini harus punya role ADM (Administrator), MNG (Manager), dan STF (Staff/Kasir)
    Route::group(['prefix' => 'penjualan', 'middleware'=> 'authorize:ADM,MNG,STF'], function(){
        Route::get('/', [PenjualanController::class, 'index']);                                //menampilkan laman awal penjualan
        Route::post('/list', [PenjualanController::class, 'list']);                            //menampilkan data penjualan dalam bentuk json untuk datatables
        Route::get('/create_ajax', [PenjualanController::class, 'create_ajax']);               //menampilkan laman form tambah penjualan AJAX
        Route::post('/ajax', [PenjualanController::class, 'store_ajax']);                      //menyimpan data penjualan baru AJAX
        Route::get('/{id}/show_ajax', [PenjualanController::class, 'show_ajax']);              //menampilkan form detil data penjualan AJAX
        Route::get('/{id}/edit_ajax', [PenjualanController::class, 'edit_ajax']);              //menampilkan laman form edit penjualan AJAX
        Route::put('/{id}/update_ajax', [PenjualanController::class, 'update_ajax']);          //menyimpan perubahan data penjualan AJAX
        Route::get('/{id}/delete_ajax', [PenjualanController::class, 'confirm_ajax']);         //menampilkan form confirm hapus data penjualan AJAX
        Route::delete('/{id}/delete_ajax', [PenjualanController::class, 'delete_ajax']);       //menghapus data penjualan AJAX
        Route::get('/import', [PenjualanController::class, 'import']);                         //menampilkan form impor data penjualan
        Route::post('/import_ajax', [PenjualanController::class, 'import_ajax']);              //mengimpor file excel ke daftar data penjualan
        Route::get('/export_excel', [PenjualanController::class, 'export_excel']);             //mengekspor data penjualan dalam bentuk file excel
        Route::get('/export_pdf', [PenjualanController::class, 'export_pdf']);                 //mengekspor data penjualan dalam bentuk file pdf
    });
});

/*Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function(){
    Route::get('/', [UserController::class, 'index']);                          //menampilkan laman awal user
    Route::post('/list', [UserController::class, 'list']);                      //menampilkan data user dalam bentuk json untuk datatables
    Route::get('/create', [UserController::class, 'create']);                   //menampilkan laman form tambah user
    Route::post('/', [UserController::class, 'store']);                         //menyimpan data user baru
    Route::get('/create_ajax', [UserController::class, 'create_ajax']);         //menampilkan laman form tambah user AJAX
    Route::post('/ajax', [UserController::class, 'store_ajax']);                //menyimpan data user baru AJAX
    Route::get('/{id}', [UserController::class, 'show']);                       //menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']);                  //menampilkan laman form edit user
    Route::put('/{id}', [UserController::class, 'update']);                     //menyimpan perubahan data user
    Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);        //menampilkan laman form edit user AJAX
    Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);    //menyimpan perubahan data user AJAX
    Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);   //menampilkan form confirm hapus data user AJAX
    Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); //menghapus data user AJAX
    Route::delete('/{id}', [UserController::class, 'destroy']);                 //menghapus data user
});

Route::group(['prefix' => 'level'], function(){
    Route::get('/', [LevelController::class, 'index']);                             //menampilkan laman awal level
    Route::post('/list', [LevelController::class, 'list']);                         //menampilkan data level dalam bentuk json untuk datatables
    Route::get('/create', [LevelController::class, 'create']);                      //menampilkan laman form tambah level
    Route::post('/', [LevelController::class, 'store']);                            //menyimpan data level baru
    Route::get('/create_ajax', [LevelController::class, 'create_ajax']);            //menampilkan laman form tambah level AJAX
    Route::post('/ajax', [LevelController::class, 'store_ajax']);                   //menyimpan data level baru AJAX
    Route::get('/{id}', [LevelController::class, 'show']);                          //menampilkan detail level
    Route::get('/{id}/edit', [LevelController::class, 'edit']);                     //menampilkan laman form edit level
    Route::put('/{id}', [LevelController::class, 'update']);                        //menyimpan perubahan data level
    Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);           //menampilkan laman form edit level AJAX
    Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);       //menyimpan perubahan data level AJAX
    Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);      //menampilkan form confirm hapus data level AJAX
    Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);    //menghapus data level AJAX
    Route::delete('/{id}', [LevelController::class, 'destroy']);                    //menghapus data level
});

Route::group(['prefix' => 'kategori'], function(){
    Route::get('/', [KategoriController::class, 'index']);                              //menampilkan laman awal kategori
    Route::post('/list', [KategoriController::class, 'list']);                          //menampilkan data kategori dalam bentuk json untuk datatables
    Route::get('/create', [KategoriController::class, 'create']);                       //menampilkan laman form tambah kategori
    Route::post('/', [KategoriController::class, 'store']);                             //menyimpan data kategori baru
    Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);             //menampilkan laman form tambah kategori AJAX
    Route::post('/ajax', [KategoriController::class, 'store_ajax']);                    //menyimpan data kategori baru AJAX
    Route::get('/{id}', [KategoriController::class, 'show']);                           //menampilkan detail kategori
    Route::get('/{id}/edit', [KategoriController::class, 'edit']);                      //menampilkan laman form edit kategori
    Route::put('/{id}', [KategoriController::class, 'update']);                         //menyimpan perubahan data kategori
    Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);            //menampilkan laman form edit kategori AJAX
    Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);        //menyimpan perubahan data kategori AJAX
    Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);       //menampilkan form confirm hapus data kategori AJAX
    Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']);     //menghapus data kategori AJAX
    Route::delete('/{id}', [KategoriController::class, 'destroy']);                     //menghapus data kategori
});

Route::group(['prefix' => 'supplier'], function(){
    Route::get('/', [SupplierController::class, 'index']);                              //menampilkan laman awal supplier
    Route::post('/list', [SupplierController::class, 'list']);                          //menampilkan data supplier dalam bentuk json untuk datatables
    Route::get('/create', [SupplierController::class, 'create']);                       //menampilkan laman form tambah supplier
    Route::post('/', [SupplierController::class, 'store']);                             //menyimpan data supplier baru
    Route::get('/create_ajax', [SupplierController::class, 'create_ajax']);             //menampilkan laman form tambah supplier AJAX
    Route::post('/ajax', [SupplierController::class, 'store_ajax']);                    //menyimpan data supplier baru AJAX
    Route::get('/{id}', [SupplierController::class, 'show']);                           //menampilkan detail supplier
    Route::get('/{id}/edit', [SupplierController::class, 'edit']);                      //menampilkan laman form edit supplier
    Route::put('/{id}', [SupplierController::class, 'update']);                         //menyimpan perubahan data supplier
    Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']);            //menampilkan laman form edit supplier AJAX
    Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']);        //menyimpan perubahan data supplier AJAX
    Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);       //menampilkan form confirm hapus data supplier AJAX
    Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']);     //menghapus data supplier AJAX
    Route::delete('/{id}', [SupplierController::class, 'destroy']);                     //menghapus data supplier
});

Route::group(['prefix' => 'barang'], function(){
    Route::get('/', [BarangController::class, 'index']);                                //menampilkan laman awal barang
    Route::post('/list', [BarangController::class, 'list']);                            //menampilkan data barang dalam bentuk json untuk datatables
    Route::get('/create', [BarangController::class, 'create']);                         //menampilkan laman form tambah barang
    Route::post('/', [BarangController::class, 'store']);                               //menyimpan data barang baru
    Route::get('/create_ajax', [BarangController::class, 'create_ajax']);               //menampilkan laman form tambah barang AJAX
    Route::post('/ajax', [BarangController::class, 'store_ajax']);                      //menyimpan data barang baru AJAX
    Route::get('/{id}', [BarangController::class, 'show']);                             //menampilkan detail barang
    Route::get('/{id}/edit', [BarangController::class, 'edit']);                        //menampilkan laman form edit barang
    Route::put('/{id}', [BarangController::class, 'update']);                           //menyimpan perubahan data barang
    Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);              //menampilkan laman form edit barang AJAX
    Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);          //menyimpan perubahan data barang AJAX
    Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);         //menampilkan form confirm hapus data barang AJAX
    Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);       //menghapus data barang AJAX
    Route::delete('/{id}', [BarangController::class, 'destroy']);                       //menghapus data barang
});*/