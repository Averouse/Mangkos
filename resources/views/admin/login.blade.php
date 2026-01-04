<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Mangkos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-900 text-gray-200 h-screen flex items-center justify-center">

    <div class="w-full max-w-sm bg-gray-800 p-8 rounded-2xl shadow-2xl border border-gray-700">
        <div class="text-center mb-8">
            <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center text-white font-bold text-xl mx-auto mb-4 shadow-lg shadow-red-500/50">A</div>
            <h2 class="text-xl font-bold text-white">Admin Panel</h2>
            <p class="text-xs text-gray-400 mt-1">Hanya untuk personel berwenang</p>
        </div>

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Email Admin</label>
                <input type="email" name="email" class="w-full px-4 py-3 rounded-xl bg-gray-700 border border-gray-600 focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none text-white transition" placeholder="admin@mangkos.com" required>
                @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl bg-gray-700 border border-gray-600 focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none text-white transition" placeholder="••••••" required>
                @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-red-900/50">
                Masuk Sistem
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('landing') }}" class="text-xs text-gray-500 hover:text-gray-300">← Kembali</a>
        </div>
    </div>
</body>
</html>
