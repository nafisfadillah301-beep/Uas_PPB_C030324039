<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran UAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-xl w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl shadow-slate-100 border border-slate-100">
        <div class="text-center">
            <h2 class="text-3xl font-bold tracking-tight text-slate-900">Formulir Pendaftaran Ekskul Olahraga</h2>
            <p class="mt-2 text-sm text-slate-500">Silakan isi data diri Anda secara lengkap di bawah ini</p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('register.store') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg mb-4">
                    <p class="text-sm text-red-700 font-medium">Mohon periksa kembali inputan Anda.</p>
                </div>
            @endif

            <div class="space-y-5">
                <div>
                    <label for="nama_lengkap" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                    <input id="nama_lengkap" name="nama_lengkap" type="text" value="{{ old('nama_lengkap') }}" required class="block w-full rounded-lg border border-slate-300 px-3 py-2.5 text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm">
                </div>

                <div>
                    <span class="block text-sm font-semibold text-slate-700 mb-2">Jenis Kelamin</span>
                    <div class="flex gap-6">
                        <label class="flex items-center text-sm font-medium text-slate-600 cursor-pointer">
                            <input type="radio" name="jenis_kelamin" value="Pria" {{ old('jenis_kelamin', 'Pria') == 'Pria' ? 'checked' : '' }} class="h-4 w-4 text-blue-600">
                            <span class="ml-2">Pria</span>
                        </label>
                        <label class="flex items-center text-sm font-medium text-slate-600 cursor-pointer">
                            <input type="radio" name="jenis_kelamin" value="Wanita" {{ old('jenis_kelamin') == 'Wanita' ? 'checked' : '' }} class="h-4 w-4 text-blue-600">
                            <span class="ml-2">Wanita</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="agama" class="block text-sm font-semibold text-slate-700 mb-1">Agama</label>
                    <select id="agama" name="agama" required class="block w-full rounded-lg border border-slate-300 px-3 py-2.5 bg-white text-slate-900 focus:border-blue-500 text-sm">
                        <option value="" disabled {{ old('agama') ? '' : 'selected' }}>-- Pilih Agama --</option>
                        @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha'] as $agm)
                            <option value="{{ $agm }}" {{ old('agama') == $agm ? 'selected' : '' }}>{{ $agm }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <span class="block text-sm font-semibold text-slate-700 mb-2">Hobi</span>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach(['Sepakbola', 'Bola Voli', 'Badminthon'] as $hb)
                            <label class="flex items-center p-2 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer text-sm font-medium text-slate-600">
                                <input type="checkbox" name="hobi[]" value="{{ $hb }}" {{ is_array(old('hobi')) && in_array($hb, old('hobi')) ? 'checked' : '' }} class="h-4 w-4 rounded text-blue-600">
                                <span class="ml-2">{{ $hb }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label for="komentar" class="block text-sm font-semibold text-slate-700 mb-1">Komentar (masukan nomor telpon dan kelas)</label>
                    <textarea id="komentar" name="komentar" rows="3" class="block w-full rounded-lg border border-slate-300 px-3 py-2.5 text-slate-900 text-sm">{{ old('komentar') }}</textarea>
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email Login</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required class="block w-full rounded-lg border border-slate-300 px-3 py-2.5 text-slate-900 text-sm">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                    <input id="password" name="password" type="password" required class="block w-full rounded-lg border border-slate-300 px-3 py-2.5 text-slate-900 text-sm">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg">Batal</a>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md">Kirim Data</button>
            </div>

            <div class="text-center pt-4 border-t border-slate-100">
                <p class="text-sm text-slate-600">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 hover:underline">Login di sini</a>
                </p>
            </div>
        </form>
    </div>

</body>
</html>