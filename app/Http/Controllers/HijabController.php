<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hijab;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HijabController extends Controller
{
    // Read all hijab
    public function index()
    {
        $hijabs = Hijab::all();
        return response()->json($hijabs);
    }

    // Create new hijab (requires auth)

// app/Http/Controllers/hijabController.php

// app/Http/Controllers/hijabController.php

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

    $hijab = Hijab::create($data);

    return response()->json($hijab, 201);
}




    // Update hijab by ID (requires auth)
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'string|max:255',
            'description' => 'string|nullable',
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

    // Delete hijab by ID (requires auth)
    public function destroy($id)
    {
        $hijab = Hijab::findOrFail($id);
        
        // Hapus gambar jika ada
        if ($hijab->image) {
            Storage::delete($hijab->image);
        }

        $hijab->delete();

        return response()->json(['message' => 'hijab deleted successfully']);
    }
}
