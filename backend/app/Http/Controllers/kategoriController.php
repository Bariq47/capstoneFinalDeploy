<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class kategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all();
        return response()->json($kategori);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'jenis' => 'required|string|in:pendapatan,pengeluaran',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => $validator['nama_kategori'],
            'jenis' => $validator['jenis'],
            'deskripsi' => $validator['deskripsi'] ?? null,
        ]);

        return response()->json([
            'message' => 'Kategori created successfully',
            'kategori' => $kategori
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json(['message' => 'Kategori not found'], 404);
        }
        return response()->json($kategori);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json(['message' => 'Kategori not found'], 404);
        }

        $validator = $request->validate([
            'nama_kategori' => 'sometimes|required|string|max:255',
            'jenis' => 'sometimes|required|string|in:pendapatan,pengeluaran',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update($validator);

        return response()->json([
            'message' => 'Kategori updated successfully',
            'kategori' => $kategori
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json(['message' => 'Kategori not found'], 404);
        }

        $kategori->delete();

        return response()->json([
            'message' => 'Kategori deleted successfully'
        ], 200);
    }
}
