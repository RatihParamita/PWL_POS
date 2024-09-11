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
        $data = [
            /*'username' => 'customer-1',
            'nama' => 'Pelanggan',
            'password' => Hash::make('12345'),
            'level_id' => 4*/
            'nama' => 'Pelanggan Pertama'
        ];
        //UserModel::insert($data);   //menambahkan data ke tabel m_user

        //update data user dengan Eloquent Model
        UserModel::where('username', 'customer-1')->update($data);
        
        //coba akses model UserModel
        $user = UserModel::all(); //mengambil semua data dari tabel m_user
        return view('user', ['data' => $user]);
    }
}
