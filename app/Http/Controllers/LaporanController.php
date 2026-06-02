<?php

namespace App\Http\Controllers;

use App\Models\PresensiModel;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $presensis = PresensiModel::with('user')
            ->latest('waktu_masuk')
            ->paginate(20);

        return view('laporan.index', [
            'presensis' => $presensis,
            'totalPresensi' => PresensiModel::count(),
            'awal' => PresensiModel::where('status', 'awal')->count(),
            'tepat' => PresensiModel::where('status', 'tepat')->count(),
            'terlambat' => PresensiModel::where('status', 'terlambat')->count(),
        ]);
    }
}
