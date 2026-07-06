<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pendaftar extends Model
{
protected $table = 'pendaftar';
protected $fillable = [
'nama_lengkap',
'jenis_kelamin',
'agama',
'hobi',
'komentar',
'password'
];
// Cast hobi otomatis dari JSON ke Array PHP saat diakses
protected $casts = [
'hobi' => 'array',
];
// Menyembunyikan password saat data direturn ke API
protected $hidden = [
'password',
];
}