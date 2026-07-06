<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Pendaftaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2=family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl shadow-slate-100 border border-slate-100">
        <div class="text-center">
            <h2 class="text-3xl font-bold tracking-tight text-slate-900">Selamat Datang</h2>
            <p class="mt-2 text-sm text-slate-500">Silakan masuk menggunakan akun Anda</p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('login.store') }}" method="POST">
            @csrf

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg mb-4">
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->has('email'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg mb-4">
                    <p class="text-sm text-red-700 font-medium">{{ $errors->first('email') }}</p>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required 
                        class="block w-full rounded-lg border border-slate-300 px-3 py-2.5 text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm" placeholder="nama@email.com">
                </div>
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                    <input id="password" name="password" type="password" required 
                        class="block w-full rounded-lg border border-slate-300 px-3 py-2.5 text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm" placeholder="••••••••">
                </div>
            </div>

            <div>
                <button type="submit" class="w-full justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition-colors duration-200 shadow-md shadow-blue-100">
                    MASUK
                </button>
            </div>

            <div class="text-center pt-2">
                <p class="text-sm text-slate-600">
                    Belum punya akun? 
                    <a href="{{ route('register.form') }}" class="font-medium text-blue-600 hover:text-blue-500 hover:underline">Daftar di sini</a>
                </p>
            </div>
        </form>
    </div>

</body>
</html>