<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Sistem Pendaftaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col justify-between">

    <div class="max-w-xl mx-auto my-12 w-full px-4">
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden">
            
            <div class="{{ trim(strtolower($user->role)) === 'admin' ? 'bg-gradient-to-r from-red-600 to-amber-500' : 'bg-gradient-to-r from-indigo-600 to-blue-500' }} h-32 relative flex justify-center">
                <div class="absolute -bottom-12 w-24 h-24 bg-white rounded-full p-1.5 shadow-md">
                    <div class="w-full h-full rounded-full {{ trim(strtolower($user->role)) === 'admin' ? 'bg-amber-100 text-amber-700' : 'bg-indigo-100 text-indigo-700' }} flex items-center justify-center text-3xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
            </div>

            <div class="pt-16 pb-8 px-6 text-center">
                <h1 class="text-2xl font-bold text-slate-800">{{ $user->name }}</h1>
                <span class="inline-block mt-1 px-3 py-1 text-xs font-bold tracking-wider rounded-full {{ trim(strtolower($user->role)) === 'admin' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-indigo-50 text-indigo-600 border border-indigo-100' }}">
                    🛡️ {{ strtoupper($user->role) }}
                </span>

                <div class="mt-8 space-y-3 text-left">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 block uppercase tracking-wide">Alamat Email</span>
                        <span class="text-sm font-semibold text-slate-700 mt-0.5 block">{{ $user->email }}</span>
                    </div>

                    @if (trim(strtolower($user->role)) === 'admin')
                        <div class="p-4 bg-amber-50/50 rounded-2xl border border-amber-100">
                            <span class="text-xs font-bold text-amber-600 block uppercase tracking-wide">Status Server</span>
                            <span class="text-sm font-semibold text-slate-700 mt-0.5 block">Total Mahasiswa Mendaftar: <strong class="text-amber-700">{{ $totalPendaftar }} Orang</strong></span>
                        </div>
                    @else
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wide">Jenis Kelamin</span>
                                <span class="text-sm font-semibold text-slate-700 mt-0.5 block">{{ $user->jenis_kelamin ?? '-' }}</span>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wide">Agama</span>
                                <span class="text-sm font-semibold text-slate-700 mt-0.5 block">{{ $user->agama ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <span class="text-xs font-bold text-slate-400 block uppercase tracking-wide">Hobi Pilihan</span>
                            <span class="text-sm font-semibold text-slate-700 mt-0.5 block">
                                {{ is_array($user->hobi) ? implode(', ', $user->hobi) : ($user->hobi ?? '-') }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="mt-8 flex items-center justify-center gap-3">
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition duration-150">
                        Kembali ke Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Yakin ingin keluar?')">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-xs font-bold transition duration-150 shadow-md shadow-red-100">
                            Log Out Akun
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</body>
</html>