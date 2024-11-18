<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangModel;

class BarangController extends Controller
{
    public function index()
    {
        return BarangModel::all();
    }

    public function store(Request $request)
    {
        $barang = BarangModel::create($request->all());
        return response()->json($barang, 201);
        //set validation
        $request -> validate ([
            'barang_kode'    => 'required|min:3|max:20|unique:m_barang,barang_kode',
            'barang_nama'    => 'required|string|max:100',
            'harga_beli'     => 'required|numeric',
            'harga_jual'     => 'required|numeric',
            'kategori_id'    => 'required|integer|exists:m_kategori,kategori_id',
            'image'          => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        
        $file = $request->file('image');
    
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = public_path('images/barang');
    
        $file->move($path, $filename);

        $barang = BarangModel::create([
            'barang_kode'  => $request->barang_kode,
            'barang_nama'  => $request->barang_nama,
            'harga_beli'   => $request->harga_beli,
            'harga_jual'   => $request->harga_jual,
            'kategori_id'  => $request->kategori_id,
            //'image'        => $request->image->hashName()
            'image'        => $filename
        ]);

        //return response JSON barang is created
        /*if($barang){
            return response()->json([
                'success' => true,
                'barang' => $barang,
            ], 201);
        }

        //return JSON process insert failed
        return response()->json([
            'success' => false,
        ], 409);*/
    }

    public function show($id)
    {
        $barang = BarangModel::find($id);
        
        if ($barang) {
            return response()->json($barang, 200);
        } else {
            return response()->json(['message' => 'Barang not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $barang = BarangModel::find($id);
        
        if ($barang) {
            $barang->update($request->all());
            return response()->json($barang, 200);
        } else {
            return response()->json(['message' => 'Barang not found'], 404);
        }
    }

    public function destroy($id)
    {
        $barang = BarangModel::find($id);

        if ($barang) {
            $barang->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data terhapus.',
            ], 200);
        } else {
            return response()->json(['message' => 'Barang not found'], 404);
        }
    }
}
