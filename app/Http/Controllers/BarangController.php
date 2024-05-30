<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

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
        ]);

        $barang = Barang::create($request->all());

        return response()->json($barang, 201);
    }

    // Update barang by ID (requires auth)
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric',
            'size' => 'string|max:255',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return response()->json($barang);
    }

    // Delete barang by ID (requires auth)
    public function destroy($id)
    {
        Barang::findOrFail($id)->delete();
        return response()->json(['message' => 'Barang deleted successfully']);
    }
}
