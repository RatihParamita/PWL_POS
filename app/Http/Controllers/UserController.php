<?php

namespace App\Http\Controllers;

use App\Models\UserModel;   //mengimpor model UserModel
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;    //mengimpor kelas Hash

class UserController extends Controller
{
    public function index()
    {

        //tambah data user dengan Eloquent Model
        /*$data = [
            /*'username' => 'customer-1',
            'nama' => 'Pelanggan',
            'password' => Hash::make('12345'),
            'level_id' => 4
            'nama' => 'Pelanggan Pertama'
        ];*/
        //UserModel::insert($data);   //menambahkan data ke tabel m_user

        //update data user dengan Eloquent Model
        //UserModel::where('username', 'customer-1')->update($data);

        //tambah data user dengan Eloquent ORM
        /*$data = [
            'level_id' => 2,
            'username' => 'manager_tiga',
            'nama' => 'Manager 3',
            'password' => Hash::make('12345'),
        ];
        //buat data user dengan Eloquent ORM
        UserModel::create($data);*/

        /*$user = UserModel::firstOrCreate(
            [
                'username' => 'manager22',
                'nama' => 'Manager Dua Dua',
                'password' => Hash::make('12345'),
                'level_id' => 2
            ],
        );*/

        /*$user = UserModel::firstOrNew(
            [
                'username' => 'manager333',
                'nama' => 'Manager Tiga Tiga',
                'password' => Hash::make('12345'),
                'level_id' => 2
            ],
        );
        $user->save();  //menyimpan data baru yang dibuat metode firstOrNew*/

        /*$user = UserModel::create(
            [
                'username' => 'manager11',
                'nama' => 'Manager11',
                'password' => Hash::make('12345'),
                'level_id' => 2
            ]);

            $user->username = 'manager12';

            /*$user->isDirty();   //true
            $user->isDirty('username'); //true
            $user->isDirty('nama'); //false
            $user->isDirty(['nama', 'username']); //true

            $user->isClean();   //false
            $user->isClean('username'); //false
            $user->isClean('nama'); //true
            $user->isClean(['nama', 'username']); //false

            $user->save();

            /*$user->isDirty();   //false
            $user->isClean();   //true
            dd($user->isDirty());

            $user->wasChanged();    //true
            $user->wasChanged('username');    //true
            $user->wasChanged(['username', 'level_id']);    //true
            $user->wasChanged('nama');    //false
            dd($user->wasChanged(['nama', 'username']));    //true*/
        
        //coba akses model UserModel
        $user = UserModel::all(); //mengambil semua data dari tabel m_user
        //$user = UserModel::find(1); //ambil model dengan primary key
        //$user = UserModel::where('level_id', 1)->first(); //ambil model pertama yang cocok dengan batasan kueri...
        //$user = UserModel::firstWhere('level_id', 1); //alternatif ambil model pertama yang cocok dengan batasan kueri...
        /*$user = UserModel::findOr(20, ['username', 'nama'], function() {
            abort(404);
        });
        //$user = UserModel::findOrFail(1);
        //$user = UserModel::where('username', 'manager9')->firstOrFail();
        //$user = UserModel::where('level_id', 2)->count();
        dd($user);  //dd (dump and die) di sini berfungsi untuk membaca keseluruhan data pada tabel m_user*/
        return view('user', ['data' => $user]);
    }

    public function tambah()
    {
        return view('user_tambah');
    }

    public function tambah_simpan(Request $request)
    {
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make('$request->password'),
            'level_id' => $request->level_id
        ]);
        return redirect('/user');
    }

    public function ubah($id)
    {
        $user = UserModel::find($id);
        return view('user_ubah', ['data' => $user]);
    }

    public function ubah_simpan($id, Request $request)
    {
        $user = UserModel::find($id);
        
        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make('$request->password');
        $user->level_id = $request->level_id;

        $user->save();
        
        return redirect('/user');
    }

    public function hapus($id)
    {
        $user = UserModel::find($id);
        $user->delete();

        return redirect('/user');
    }
}
