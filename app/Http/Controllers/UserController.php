<?php

namespace App\Http\Controllers;

use App\Models\TrainingImageModel;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data = User::all();
        return view('user.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'foto' => 'required|image',
            'descriptor' => 'required'
        ]);

        // store file on the public disk and prefix with storage/ so it can be used with asset()
        $path = $request->file('foto')->store('label', 'public');
        $foto = 'storage/' . $path;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('password'),
            'alamat' => $request->alamat,
            'foto' => $foto,
        ]);

        TrainingImageModel::create([
            'user_id' => $user->id,
            'image' => $foto,
            'descriptor' => $request->descriptor,
        ]);

        return redirect()->back()
            ->with('success', 'User berhasil ditambahkan');
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('user.detail', compact('user'));
    }
}
