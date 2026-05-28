<?php

namespace App\Http\Controllers;

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
}
