<?php

namespace App\Http\Controllers;

use App\Models\LokasiModel;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index()
    {
        $data = LokasiModel::all();
        return view('lokasi.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lokasi' => 'required|string|max:255',
        ]);

        LokasiModel::create([
            'nama_lokasi' => $request->lokasi,
        ]);

        return redirect()->route('md.lokasi')->with('success', 'Lokasi berhasil ditambahkan');
    }
}
