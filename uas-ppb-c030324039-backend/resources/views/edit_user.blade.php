<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Pendaftar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-xl w-full bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
        <h2 class="text-2xl font-bold text-slate-800 mb-6 text-center">Edit Data Pendaftar</h2>

        <form action="{{ route('users.update', $pendaftar->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ $pendaftar->name }}" class="w-full p-2 border border-slate-300 rounded-lg text-sm" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ $pendaftar->email }}" class="w-full p-2 border border-slate-300 rounded-lg text-sm" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="w-full p-2 border border-slate-300 rounded-lg text-sm" required>
                    <option value="Pria" {{ $pendaftar->jenis_kelamin == 'Pria' ? 'selected' : '' }}>Pria</option>
                    <option value="Wanita" {{ $pendaftar->jenis_kelamin == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Agama</label>
                <input type="text" name="agama" value="{{ $pendaftar->agama }}" class="w-full p-2 border border-slate-300 rounded-lg text-sm" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Hobi</label>
                <input type="text" name="hobi" value="{{ is_array($pendaftar->hobi) ? implode(', ', $pendaftar->hobi) : $pendaftar->hobi }}" class="w-full p-2 border border-slate-300 rounded-lg text-sm" required>
                <p class="text-xs text-slate-400 mt-1">*Pisahkan dengan koma jika lebih dari satu</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">Komentar / Pesan</label>
                <textarea name="komentar" rows="3" class="w-full p-2 border border-slate-300 rounded-lg text-sm">{{ $pendaftar->komentar }}</textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold hover:bg-slate-300 transition">Batal</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</body>
</html>