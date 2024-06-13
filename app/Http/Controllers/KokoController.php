<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Koko;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'description' => 'string|nullable',
            'price' => 'required|numeric',
            'size' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Validasi gambar
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $filename);
            $data['image'] = $path;
        }

        $koko = Koko::create($data);

        return response()->json($koko, 201);
    }

    // Update barang by ID (requires auth)
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

    // Delete barang by ID (requires auth)
    public function destroy($id)
    {
        $koko = Koko::findOrFail($id);
        
        // Hapus gambar jika ada
        if ($koko->image) {
            Storage::delete($koko->image);
        }

        $koko->delete();

        return response()->json(['message' => 'Barang deleted successfully']);
    }
}

