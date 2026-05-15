<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // Simpan data wajah saat registrasi
    public function registerFace(Request $request)
    {
        $user = User::find(Auth::id());
        $user->face_vector = json_encode($request->descriptor);
        $user->save();

        return response()->json(['message' => 'Wajah berhasil didaftarkan!']);
    }

    // Proses Verifikasi Absen
    public function verify(Request $request)
    {
        $inputDescriptor = $request->descriptor;
        $users = User::whereNotNull('face_vector')->get();

        foreach ($users as $user) {
            $dbDescriptor = json_decode($user->face_vector);

            // Logika Euclidean Distance sederhana
            $distance = $this->euclideanDistance($inputDescriptor, $dbDescriptor);

            if ($distance < 0.45) { // Semakin kecil semakin akurat (0.4 - 0.5 standar)
                return response()->json([
                    'status' => 'success',
                    'user' => $user->name,
                    'message' => 'Absensi Berhasil!'
                ]);
            }
        }

        return response()->json(['status' => 'failed', 'message' => 'Wajah tidak dikenali'], 401);
    }

    private function euclideanDistance($a, $b)
    {
        return sqrt(array_sum(array_map(fn($x, $y) => pow($x - $y, 2), $a, $b)));
    }
}
