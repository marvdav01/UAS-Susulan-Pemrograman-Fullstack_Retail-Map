<?php

namespace App\Http\Controllers;

use App\Models\CabangToko;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CabangTokoController extends Controller
{
    public function index()
    {
        $tokoList = CabangToko::all();
        return Inertia::render('LokasiToko/Index', [
            'tokoList' => $tokoList
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        CabangToko::create($validated);

        return back()->with('success', 'Lokasi toko berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $toko = CabangToko::findOrFail($id);
        $toko->update($validated);

        return back()->with('success', 'Lokasi toko berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $toko = CabangToko::findOrFail($id);
        $toko->delete();

        return back()->with('success', 'Lokasi toko berhasil dihapus.');
    }
}
