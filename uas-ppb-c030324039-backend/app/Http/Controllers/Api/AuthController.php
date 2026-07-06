<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Fungsi Register / Pendaftaran User Baru
     */
    public function register(Request $request)
    {
        // 1. Validasi inputan sesuai dengan acuan formulir UAS
        $validator = Validator::make($request->all(), [
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Pria,Wanita',
            'agama'         => 'required|string',
            'hobi'          => 'required|array', // Harus berupa array dari Vue/Flutter
            'komentar'      => 'nullable|string',
            'email'         => 'required|string|email|max:255|unique:users', // Ditambahkan untuk syarat login standar
            'password'      => 'required|string|min:6',
        ]);

        // Jika validasi gagal, kirim pesan error ke frontend
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi pendaftaran gagal.',
                'errors'  => $validator->errors()
            ], 422);
        }

        // 2. Simpan data user baru ke tabel users
        $user = User::create([
            'name'          => $request->nama_lengkap, // Kolom bawaan laravel diisi nama_lengkap
            'email'         => $request->email,
            'password'      => Hash::make($request->password), // Enkripsi password
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama'         => $request->agama,
            'hobi'          => $request->hobi, // Akan otomatis ter-cast jadi JSON jika diset di model
            'komentar'      => $request->komentar,
        ]);

        // 3. Generate Token agar setelah daftar bisa langsung otomatis login (opsional)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success'      => true,
            'message'      => 'Pendaftaran berhasil dilakukan.',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'data'         => $user
        ], 201);
    }

    /**
     * Fungsi Login untuk Web Vue dan Mobile Flutter
     */
    public function login(Request $request)
    {
        // 1. Validasi input login
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email dan password wajib diisi.',
                'errors'  => $validator->errors()
            ], 422);
        }

        // 2. Cek Kredensial Login (Email & Password)
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah. Gagal masuk.'
            ], 401);
        }

        // 3. Jika sukses, ambil data user dan buat Token Sanctum baru
        $user  = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success'      => true,
            'message'      => 'Selamat datang kembali, ' . $user->name,
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'data'         => $user // Mengirimkan data profil user untuk dashboard
        ], 200);
    }

    /**
     * Fungsi Logout untuk Menghapus Token
     */
    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout. Token telah dihapus.'
        ], 200);
    }
}