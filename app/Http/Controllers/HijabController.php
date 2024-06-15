<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hijab;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HijabController extends Controller
{
    // Read all barang
    public function index()
    {
        $hijabs = Hijab::all();
        return response()->json($hijabs);
    }

    // Create new barang (requires auth)
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
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

        $hijab = Hijab::create($data);

        return response()->json($hijab, 201);
    }

    // Update barang by ID (requires auth)
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'numeric',
            'size' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Validasi gambar
        ]);

        $hijab = Hijab::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($hijab->image) {
                Storage::delete($hijab->image);
            }

            $file = $request->file('image');
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $filename);
            $data['image'] = $path;
        }

        $hijab->update($data);

        return response()->json($hijab);
    }

    // Delete barang by ID (requires auth)
    public function destroy($id)
    {
        $hijab = Hijab::findOrFail($id);
        
        // Hapus gambar jika ada
        if ($hijab->image) {
            Storage::delete($hijab->image);
        }

        $hijab->delete();

        return response()->json(['message' => 'Barang deleted successfully']);
    }
}

