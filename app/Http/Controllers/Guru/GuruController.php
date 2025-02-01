<?php

namespace App\Http\Controllers\Guru;

use App\Models\Guru;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuruController extends Controller
{
    public function guru(){
        return view('dashboard.guru.index');
    }

    public function index()
    {
        $data = Guru::all();
        return response()->json(['data' => $data]);
    }
    public function show($id)
    {
        $guru = Guru::find($id);
    
        if ($guru) {
            return response()->json([
                'success' => true,
                'data' => $guru,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan.',
            ], 404);
        }
    }
    
    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255']);
        Guru::create(['nama' => $request->nama]);
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama' => 'required|string|max:255']);
        $guru = Guru::find($id);
        if ($guru) {
            $guru->update(['nama' => $request->nama]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function destroy($id)
    {
        $guru = Guru::find($id);
        if ($guru) {
            $guru->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}