<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;

class UserController extends Controller
{
    public function index()
    {
        return UserModel::all();
    }

    public function store(Request $request)
    {
        $user = UserModel::create($request->all());
        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = UserModel::find($id);
        
        if ($user) {
            return response()->json($user, 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $user = UserModel::find($id);
        
        if ($user) {
            $user->update($request->all());
            return response()->json($user, 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function destroy($id)
    {
        $user = UserModel::find($id);

        if ($user) {
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data terhapus.',
            ], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
}
