<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
public function up(): void
{
Schema::create('pendaftar', function (Blueprint $table) {
$table->id();
$table->string('nama_lengkap');
$table->enum('jenis_kelamin', ['Pria', 'Wanita']);
$table->string('agama');
$table->json('hobi')->nullable();
$table->text('komentar')->nullable();
$table->string('password');
$table->timestamps();
});
}
public function down(): void
{
Schema::dropIfExists('pendaftar');
}
};