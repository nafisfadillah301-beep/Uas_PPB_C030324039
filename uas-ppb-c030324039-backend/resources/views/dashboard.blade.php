<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sistem Pendaftaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <nav class="bg-indigo-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <span class="font-bold text-xl tracking-wide">Sistem Pendaftaran Ekskul Olahraga</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm bg-indigo-800 px-3 py-1.5 rounded-full font-medium border border-indigo-600">
                        🔑 {{ strtoupper($user->role) }} : {{ $user->name }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Yakin ingin keluar?')">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors duration-200 shadow-md shadow-red-100">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center shadow-sm">
                💡 {{ session('success') }}
            </div>
        @endif

        @if (trim(strtolower($user->role)) === 'admin')
            <div class="space-y-6">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Dashboard Panel Admin</h1>
                    <p class="text-sm text-slate-500 mt-1">Berikut adalah tabel ringkasan seluruh mahasiswa yang telah mendaftarkan diri ke sistem.</p>
                </div>

                <div class="bg-white shadow-xl shadow-slate-100 border border-slate-100 rounded-2xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-700 text-sm font-bold">
                                    <th class="p-4 w-16 text-center">No</th>
                                    <th class="p-4">Nama Lengkap</th>
                                    <th class="p-4">Agama</th>
                                    <th class="p-4">Hobi</th>
                                    <th class="p-4">Email Login</th>
                                    <th class="p-4">Pesan / Komentar</th>
                                    <th class="p-4 text-center w-40">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                                @forelse ($pendaftar as $index => $pnd)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="p-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                                        <td class="p-4 font-semibold text-slate-900">{{ $pnd->name }}</td>
                                        <td class="p-4">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                {{ $pnd->agama }}
                                            </span>
                                        </td>
                                        <td class="p-4 font-medium text-slate-700">
                                            {{ is_array($pnd->hobi) ? implode(', ', $pnd->hobi) : ($pnd->hobi ?? '-') }}
                                        </td>
                                        <td class="p-4 text-slate-500">{{ $pnd->email }}</td>
                                        <td class="p-4 italic text-slate-500 max-w-xs truncate">{{ $pnd->komentar ?? '-' }}</td>
                                        
                                        <td class="p-4 text-center flex items-center justify-center gap-2">
                                            <a href="{{ route('users.edit', $pnd->id) }}" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-semibold transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('users.destroy', $pnd->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pendaftar ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-8 text-center text-slate-400 font-medium">Belum ada mahasiswa yang mendaftar saat ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @else
            <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-xl shadow-slate-100 border border-slate-100">
                <div class="text-center pb-6 border-b border-slate-100 mb-6">
                    <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto text-3xl font-bold mb-3">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900">Selamat Pendaftaran Anda Berhasil!</h1>
                    <p class="text-sm text-slate-500 mt-1">Berikut adalah ringkasan kartu pendaftaran Ekskul Olahraga Anda</p>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between py-2 border-b border-slate-50 text-sm">
                        <span class="text-slate-400 font-medium">Nama Lengkap</span>
                        <span class="font-semibold text-slate-800">{{ $user->name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50 text-sm">
                        <span class="text-slate-400 font-medium">Jenis Kelamin</span>
                        <span class="font-semibold text-slate-800">{{ $user->jenis_kelamin ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50 text-sm">
                        <span class="text-slate-400 font-medium">Agama</span>
                        <span class="font-semibold text-slate-800">{{ $user->agama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50 text-sm">
                        <span class="text-slate-400 font-medium">Hobi</span>
                        <span class="font-semibold text-slate-800">
                            {{ is_array($user->hobi) ? implode(', ', $user->hobi) : ($user->hobi ?? '-') }}
                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-50 text-sm">
                        <span class="text-slate-400 font-medium">Email Terdaftar</span>
                        <span class="font-semibold text-slate-800">{{ $user->email }}</span>
                    </div>
                    <div class="pt-2 text-sm border-b border-slate-50 pb-4">
                        <span class="text-slate-400 font-medium block mb-1">Komentar / Pesan Anda:</span>
                        <div class="p-3 bg-slate-50 rounded-lg text-slate-600 italic border border-slate-100">
                            "{{ $user->komentar ?? 'Tidak ada komentar.' }}"
                        </div>
                    </div>

                    <div class="pt-4 flex justify-center">
                        <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('⚠️ PERINGATAN: Menghapus akun akan membatalkan pendaftaran ekskul Anda dan menghapus seluruh data secara permanen dari server. Anda yakin?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-5 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-xl text-xs font-bold transition duration-200">
                                🚨 Hapus Akun Pendaftaran Saya
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </main>

</body>
</html>