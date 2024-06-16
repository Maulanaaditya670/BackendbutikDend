<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Koko;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KokoController extends Controller
{
    // Read all koko
    public function index()
    {
        $kokos = Koko::all();
        return response()->json($kokos);
    }

    // Create new koko (requires auth)

// app/Http/Controllers/kokoController.php

// app/Http/Controllers/kokoController.php

public function store(Request $request)
{
    $this->validate($request, [
        'name' => 'required|string|max:255',
        'kode' => 'required|string|max:255',
        'price' => 'required|numeric',
        'size' => 'required|string|max:255',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    $data = $request->all();

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads', $filename, 'public');
        $data['image'] = 'storage/' . $path; // Simpan URL relatif
    }

    $koko = Koko::create($data);

    return response()->json($koko, 201);
}




    // Update koko by ID (requires auth)
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'string|max:255',
            'description' => 'string|nullable',
            'price' => 'numeric',
            'size' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Validasi gambar
        ]);

        $koko = Koko::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($koko->image) {
                Storage::delete($koko->image);
            }

            $file = $request->file('image');
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $filename);
            $data['image'] = $path;
        }

        $koko->update($data);

        return response()->json($koko);
    }

    // Delete koko by ID (requires auth)
    public function destroy($id)
    {
        $koko = Koko::findOrFail($id);
        
        // Hapus gambar jika ada
        if ($koko->image) {
            Storage::delete($koko->image);
        }

        $koko->delete();

        return response()->json(['message' => 'koko deleted successfully']);
    }
}
