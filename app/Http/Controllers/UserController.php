<?php

namespace App\Http\Controllers;

use App\Models\UserModel;   //mengimpor model UserModel
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;    //mengimpor kelas Hash

class UserController extends Controller
{
    public function index()
    {
        //tambah data user dengan Eloquent ORM
        /*$data = [
            'level_id' => 2,
            'username' => 'manager_tiga',
            'nama' => 'Manager 3',
            'password' => Hash::make('12345'),
        ];
        //buat data user dengan Eloquent ORM
        UserModel::create($data);*/

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
        
        //coba akses model UserModel
        //$user = UserModel::all(); //mengambil semua data dari tabel m_user
        //$user = UserModel::find(1); //ambil model dengan primary key
        //$user = UserModel::where('level_id', 1)->first(); //ambil model pertama yang cocok dengan batasan kueri...
        //$user = UserModel::firstWhere('level_id', 1); //alternatif ambil model pertama yang cocok dengan batasan kueri...
        $user = UserModel::findOr(20, ['username', 'nama'], function() {
            abort(404);
        });
        return view('user', ['data' => $user]);
    }
}
