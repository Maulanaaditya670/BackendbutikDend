<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BarangController extends Controller
{
    // Read all barang
    public function index()
    {
        $barangs = Barang::all();
        return response()->json($barangs);
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

        $barang = Barang::create($data);

        return response()->json($barang, 201);
    }

    // Update barang by ID (requires auth)
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'string|max:255',
            'kode' => 'string|max:255',
            'price' => 'numeric',
            'size' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Validasi gambar
        ]);

        $barang = Barang::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($barang->image) {
                Storage::delete($barang->image);
            }

            $file = $request->file('image');
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $filename);
            $data['image'] = $path;
        }

        $barang->update($data);

        return response()->json($barang);
    }

    // Delete barang by ID (requires auth)
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        
        // Hapus gambar jika ada
        if ($barang->image) {
            Storage::delete($barang->image);
        }

        $barang->delete();

        return response()->json(['message' => 'Barang deleted successfully']);
    }
}

