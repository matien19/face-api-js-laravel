<?php

namespace App\Http\Controllers;

use App\Models\PresensiModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('face');
    }

    public function descriptors()
    {
        $users = User::with([
            'trainingImages' => function ($q) {
                $q->whereNotNull('descriptor')
                    ->where('descriptor', '!=', '');
            }
        ])->get();

        $data = $users->map(function ($user) {
            return [
                'user_id' => $user->id,
                'label' => $user->name,
                'descriptors' => $user
                    ->trainingImages
                    ->pluck('descriptor')
                    ->filter()
                    ->values()
            ];
        })
            ->filter(function ($user) {
                return count($user['descriptors']) > 0;
            })
            ->values();

        return response()->json($data);
    }

    public function store(Request $request)
    {

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $sudahAbsen = PresensiModel::where('user_id', $user->id)
            ->whereDate('waktu_masuk', now()->toDateString())
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'status' => 'Sudah Presensi',
                'message' => 'Sudah melakukan presensi hari ini'
            ]);
        }
        
        $jamMasuk = now()->format('H:i');

        $status = 'tepat';

        if ($jamMasuk < '07:00') {
            $status = 'awal';
        } elseif ($jamMasuk > '07:15') {
            $status = 'terlambat';
        }

        PresensiModel::create([
            'user_id' => $user->id,
            'waktu_masuk' => now(),
            'status' => $status
        ]);

        $lastPresensi = PresensiModel::where('user_id', $user->id)
            ->latest('waktu_masuk')
            ->first();

        // if (
        //     $lastPresensi &&
        //     now()->diffInMinutes($lastPresensi->waktu_masuk) < 3
        // ) {
        //     return response()->json([
        //         'success' => false,
        //         'status' => 'Tunggu 3 menit',
        //         'message' => 'Tunggu 3 menit sebelum presensi lagi'
        //     ]);
        // }
        return response()->json([
            'success' => true,
            'nama' => $user->name,
            'status' => $status,
            'message' => 'Presensi berhasil'
        ]);
    }
}
