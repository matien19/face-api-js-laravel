<?php

namespace App\Http\Controllers;

use App\Models\TrainingImageModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function storeFace(Request $request, $id)
    {
        $request->validate([
            'pose' => 'required|in:depan,kiri,kanan,atas',
            'image' => 'required', // Data Base64 dari kamera
            'descriptor' => 'nullable' // Jaga-jaga jika pustaka Face Recognition di frontend langsung mengirim array descriptor
        ]);

        $user = User::findOrFail($id);

        // 1. Mengolah data Base64 Image dari Webcam
        $imageTypeAndData = explode(',', $request->image);
        $imageData = base64_decode($imageTypeAndData[1]);

        // Menentukan nama file berdasarkan pose (misal: label/1_kiri_1717300000.jpg)
        $path = 'label/' . $user->id . '_' . $request->pose . '_' . time() . '.jpg';

        // Simpan ke disk public
        Storage::disk('public')->put($path, $imageData);

        // Samakan format dengan kode lama Anda: prefix dengan 'storage/' agar bisa dibaca asset()
        $fotoPathForDb = 'storage/' . $path;

        // 2. Simpan atau Update ke database relasi wajah pengguna (kolom JSON di tabel users)
        $currentFaces = $user->faces ?? [];
        $currentFaces[$request->pose] = $fotoPathForDb;
        $user->faces = $currentFaces;

        // Opsi Tambahan: Jika pose 'depan' baru saja diambil, otomatis update foto profil utama user
        if ($request->pose === 'depan') {
            $user->foto = $fotoPathForDb;
        }
        $user->save();

        // 3. Simpan data ke TrainingImageModel (Sama seperti logikamu sebelumnya)
        // Kita gunakan updateOrCreate agar jika pose yang sama di-training ulang, datanya tidak menumpuk double
        TrainingImageModel::updateOrCreate(
            [
                'user_id' => $user->id,
                'pose' => $request->pose, // Tambahkan kolom 'pose' di migration jika ada untuk membedakan sampel
            ],
            [
                'image' => $fotoPathForDb,
                // Jika descriptor dikirim dari JS frontend, simpan datanya. Jika tidak, pakai default penanda sementara
                'descriptor' => $request->descriptor ?? json_encode(['pose' => $request->pose]),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Pose ' . $request->pose . ' berhasil disimpan dan ditraining.',
            'path' => asset($fotoPathForDb)
        ]);
    }

    public function resetFace($id)
    {
        $user = User::findOrFail($id);

        // Hapus file fisik jika diperlukan
        if ($user->faces) {
            foreach ($user->faces as $path) {
                $relativeByPath = str_replace('/storage/', '', $path);
                Storage::disk('public')->delete($relativeByPath);
            }
        }

        $user->faces = null; // atau []
        $user->save();

        return response()->json(['success' => true]);
    }
}
