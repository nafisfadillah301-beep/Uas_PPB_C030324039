<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class PendaftaranController extends Controller
{
public function store(Request $request)
{
$validator = Validator::make($request->all(), [
'nama_lengkap' => 'required|string|max:255',
'jenis_kelamin' => 'required|in:Pria,Wanita',
'agama' => 'required|string',
'hobi' => 'required|array',
'komentar' => 'nullable|string',
'password' => 'required|string|min:6',
]);
if ($validator->fails()) {
return response()->json([
'success' => false,
'message' => 'Validasi gagal',
'errors' => $validator->errors()
], 422);
}
$pendaftar = Pendaftar::create([
'nama_lengkap' => $request->nama_lengkap,
'jenis_kelamin' => $request->jenis_kelamin,
'agama' => $request->agama,
'hobi' => $request->hobi,
'komentar' => $request->komentar,
'password' => Hash::make($request->password),
]);
return response()->json([
'success' => true,
'message' => 'Pendaftaran berhasil disimpan ke database Laravel.',
'data' => $pendaftar
], 210);
}
}