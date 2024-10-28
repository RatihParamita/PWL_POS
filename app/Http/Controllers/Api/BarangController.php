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
