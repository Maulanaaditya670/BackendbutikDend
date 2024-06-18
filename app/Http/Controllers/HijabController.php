<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hijab;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HijabController extends Controller
{
    // Read all hijabs
    public function index()
    {
        $hijabs = Hijab::all();
        return response()->json($hijabs);
    }

    // Create new hijab
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

    // Update hijab by ID
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'string|max:255',
            'kode' => 'string|max:255',
            'price' => 'numeric',
            'size' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $hijab = Hijab::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($hijab->image) {
                Storage::delete($hijab->image);
            }

            $file = $request->file('image');
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $filename, 'public');
            $data['image'] = 'storage/' . $path;
        }

        $hijab->update($data);

        return response()->json($hijab);
    }

    // Delete hijab by ID
    public function destroy($id)
{
    $hijab = Hijab::findOrFail($id);

    // Delete image if exists
    if ($hijab->image) {
        Storage::delete('public/' . $hijab->image); // Update to match the storage path
    }

    $hijab->delete();

    return response()->json(['message' => 'Hijab deleted successfully']);
}

}
