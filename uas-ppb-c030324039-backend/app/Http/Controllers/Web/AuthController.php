<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm() 
    {
        return view('auth.login');
    }

    public function showRegisterForm() 
    {
        return view('auth.register');
    }

    public function register(Request $request) 
    {
        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Pria,Wanita',
            'agama'         => 'required|string',
            'hobi'          => 'required', 
            'komentar'      => 'nullable|string',
            'email'         => 'required|string|email|max:255|unique:users,email',
            'password'      => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'          => $request->nama_lengkap,
            'email'         => $request->email,
            'password'      => Hash::make($request->password), 
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama'         => $request->agama,
            'hobi'          => is_array($request->hobi) ? json_encode($request->hobi) : $request->hobi,
            'komentar'      => $request->komentar,
        ]);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Registrasi Berhasil! Silakan Login.',
                'user' => $user
            ], 201);
        }

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login dengan akun Anda.');
    }

    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login Berhasil!',
                    'user' => $user
                ], 200);
            }

            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function dashboard() 
    {
        $user = Auth::user();

        if (trim(strtolower($user->role)) === 'admin') {
            $pendaftar = User::where('role', 'user')->get();
            return view('dashboard', [
                'user' => $user,
                'pendaftar' => $pendaftar
            ]);
        }

        return view('dashboard', [
            'user' => $user
        ]);
    }

    // FUNGSI PROFIL BARU (UNTUK WEB)
    public function profile()
    {
        $user = Auth::user();
        
        // Jika admin membuka profil, kita hitung juga total pendaftar sebagai info tambahan di kartu profilnya
        if (trim(strtolower($user->role)) === 'admin') {
            $totalPendaftar = User::where('role', 'user')->count();
            return view('profile', [
                'user' => $user,
                'totalPendaftar' => $totalPendaftar
            ]);
        }

        return view('profile', [
            'user' => $user
        ]);
    }

    public function logout(Request $request) 
    {
        Auth::logout();
        
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Logout Berhasil!'
            ]);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function getAllUsers(Request $request)
    {
        $users = User::where('role', 'user')->get();
        return response()->json([
            'success' => true,
            'data' => $users
        ], 200);
    }

    public function editUser($id)
    {
        if (trim(strtolower(Auth::user()->role)) !== 'admin') {
            abort(403, 'Akses ditolak!');
        }

        $pendaftar = User::findOrFail($id);
        return view('edit_user', compact('pendaftar'));
    }

    public function updateUser(Request $request, $id)
    {
        if (trim(strtolower(Auth::user()->role)) !== 'admin') {
            abort(403, 'Akses ditolak!');
        }

        $pendaftar = User::findOrFail($id);

        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Pria,Wanita',
            'agama'         => 'required|string',
            'hobi'          => 'required', 
            'email'         => 'required|string|email|max:255|unique:users,email,' . $id,
            'komentar'      => 'nullable|string',
        ]);

        $pendaftar->update([
            'name'          => $request->nama_lengkap,
            'email'         => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama'         => $request->agama,
            'hobi'          => is_array($request->hobi) ? json_encode($request->hobi) : $request->hobi,
            'komentar'      => $request->komentar,
        ]);

        return redirect()->route('dashboard')->with('success', 'Data pendaftar berhasil diperbarui!');
    }

    public function deleteUser($id)
    {
        if (trim(strtolower(Auth::user()->role)) !== 'admin') {
            abort(403, 'Akses ditolak!');
        }

        $pendaftar = User::findOrFail($id);
        $pendaftar->delete();

        return redirect()->route('dashboard')->with('success', 'Data pendaftar berhasil dihapus!');
    }

    public function deleteSelf(Request $request)
    {
        $userId = $request->input('id');
        
        if ($userId) {
            $user = User::find($userId);
        } else {
            $user = Auth::user();
        }

        if (!$user) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
            }
            return back()->withErrors(['error' => 'User tidak ditemukan.']);
        }

        if (Auth::check() && Auth::user()->id === $user->id) {
            Auth::logout();
        }

        $user->delete();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Akun Anda telah berhasil dihapus secara permanen.'
            ], 200);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Akun Anda telah berhasil dihapus secara permanen.');
    }
}