<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Koko;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KokoController extends Controller
{
    // Read all barang
    public function index()
    {
        $kokos = Koko::all();
        return response()->json($kokos);
    }

    // Create new barang (requires auth)
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'string',
            'price' => 'required|integer',
            'size' => 'required|string|max:255',
        ]);

        $koko = Koko::create($request->all());

        return response()->json($koko, 201);
    }

    // Update barang by ID (requires auth)
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'string|max:255',
            'description' => 'string|nullable',
            'price' => 'numeric',
            'size' => 'string|max:255',
        ]);

        $koko = Koko::findOrFail($id);
        $koko->update($request->all());

        return response()->json($koko);
    }

    // Delete barang by ID (requires auth)
    public function destroy($id)
    {
        Koko::findOrFail($id)->delete();
        return response()->json(['message' => 'Barang deleted successfully']);
    }
}
