<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Mangkos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mangkos: { light: '#6ee7b7', main: '#10b981', dark: '#047857', accent: '#0f766e' }
                    }
                }
            }
        }
    </script>
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex items-center justify-center py-4">

    <!-- Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-green-50 to-blue-50 -z-20"></div>
    <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-mangkos-light rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
    <div class="absolute top-0 right-0 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s"></div>

    <div class="w-full max-w-3xl grid grid-cols-1 md:grid-cols-2 bg-white rounded-2xl shadow-xl overflow-hidden m-4">
        
        <!-- BAGIAN KIRI: Form Register -->
        <div class="p-6 md:p-8 flex flex-col justify-center relative order-2 md:order-1">
            <a href="{{ route('landing') }}" class="md:hidden absolute top-4 left-4 text-gray-400 hover:text-mangkos-main transition"><i class="fas fa-arrow-left text-xl"></i></a>

            <div class="flex items-center gap-3 mb-4 justify-center md:justify-start">
                <img src="{{ asset('images/mangkos_icon.png') }}" alt="Mangkos" class="w-8 h-8 rounded-lg shadow-lg shadow-green-200">
                <div>
                    <span class="text-lg font-bold text-gray-800 block leading-none">Mangkos</span>
                    <span class="text-[9px] text-gray-400 font-medium tracking-wider">NEW ACCOUNT</span>
                </div>
                
                <!-- Badge Role (Dinamis) -->
                <span id="role-badge" class="ml-auto px-2 py-1 rounded-full text-[9px] font-bold bg-blue-50 text-blue-600 uppercase tracking-wide border border-blue-100">
                    Mahasiswa
                </span>
            </div>

            <div class="mb-4">
                <h2 class="text-xl font-bold text-gray-800 mb-1">Buat Akun Baru</h2>
                <p class="text-xs text-gray-500">Isi data diri Anda untuk memulai.</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="role" value="{{ request('role', 'user') }}">
                
                <!-- Nama -->
                <div class="mb-3">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1">Nama Lengkap</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400 group-focus-within:text-mangkos-main transition text-sm"></i>
                        </div>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-gray-200 bg-gray-50 focus:bg-white focus:border-mangkos-main focus:ring-2 focus:ring-green-50 outline-none transition duration-200 text-sm font-medium" placeholder="Budi Santoso" required>
                    </div>
                    @error('name')<p class="text-red-500 text-[10px] mt-1 ml-1">{{ $message }}</p>@enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1">Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400 group-focus-within:text-mangkos-main transition text-sm"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-gray-200 bg-gray-50 focus:bg-white focus:border-mangkos-main focus:ring-2 focus:ring-green-50 outline-none transition duration-200 text-sm font-medium" placeholder="email@contoh.com" required>
                    </div>
                    @error('email')<p class="text-red-500 text-[10px] mt-1 ml-1">{{ $message }}</p>@enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 group-focus-within:text-mangkos-main transition text-sm"></i>
                        </div>
                        <input type="password" name="password" class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-gray-200 bg-gray-50 focus:bg-white focus:border-mangkos-main focus:ring-2 focus:ring-green-50 outline-none transition duration-200 text-sm font-medium" placeholder="••••••••" required>
                    </div>
                    @error('password')<p class="text-red-500 text-[10px] mt-1 ml-1">{{ $message }}</p>@enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1">Konfirmasi Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-check-circle text-gray-400 group-focus-within:text-mangkos-main transition text-sm"></i>
                        </div>
                        <input type="password" name="password_confirmation" class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-gray-200 bg-gray-50 focus:bg-white focus:border-mangkos-main focus:ring-2 focus:ring-green-50 outline-none transition duration-200 text-sm font-medium" placeholder="Ulangi password" required>
                    </div>
                </div>

                <button type="submit" class="w-full bg-mangkos-main text-white font-bold py-3 rounded-lg shadow-lg shadow-green-200 hover:bg-mangkos-dark hover:shadow-green-300 transition transform hover:-translate-y-0.5 active:translate-y-0 flex justify-center items-center gap-2 group">
                    <span>Daftar Sekarang</span> 
                    <i class="fas fa-user-plus group-hover:scale-110 transition"></i>
                </button>
            </form>

            <div class="text-center text-xs text-gray-500 mt-4 pt-4 border-t border-gray-100">
                Sudah punya akun? <a id="link-login" href="{{ route('login', ['role' => request('role', 'user')]) }}" class="text-mangkos-main font-bold hover:underline hover:text-mangkos-dark transition ml-1">Masuk di sini</a>
            </div>
        </div>

        <!-- BAGIAN KANAN: Illustration (Dinamis) -->
        <div class="hidden md:flex flex-col justify-center items-center bg-gradient-to-tl from-mangkos-main to-mangkos-dark p-6 text-white relative overflow-hidden order-1 md:order-2">
            <!-- Dekorasi Pattern -->
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            
            <a href="{{ route('landing') }}" class="absolute top-4 right-4 text-white/80 hover:text-white flex items-center gap-2 text-xs font-bold transition hover:translate-x-1 z-20">
                Batal <i class="fas fa-times"></i>
            </a>
            
            <div class="relative z-10 text-center">
                <div class="mb-4 bg-white/20 w-16 h-16 rounded-xl flex items-center justify-center mx-auto backdrop-blur-sm border border-white/30 shadow-inner">
                    <i class="fas fa-rocket text-3xl" id="banner-icon-small"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2" id="side-title">Bergabung Sekarang!</h2>
                <p class="text-green-50 text-xs leading-relaxed px-2" id="side-text">Ribuan mahasiswa sudah menemukan partner ideal mereka di sini.</p>
            </div>
            
            <!-- Background Icon Besar -->
            <i class="fas fa-rocket text-[8rem] absolute -bottom-6 -left-6 opacity-10 transform -rotate-12" id="banner-icon-bg"></i>
        </div>
    </div>


    <!-- SCRIPT LOGIKA UTAMA -->
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const role = urlParams.get('role') || 'user';

        if (role === 'owner') {
            const badge = document.getElementById('role-badge');
            badge.innerText = 'Mitra Pemilik';
            badge.className = 'ml-auto px-3 py-1 rounded-full text-[10px] font-bold bg-orange-50 text-orange-600 uppercase tracking-wide border border-orange-100';
            
            document.getElementById('side-title').innerText = 'Mulai Bisnis Kost';
            document.getElementById('side-text').innerText = 'Kelola properti kost Anda dengan sistem manajemen modern dan jangkau lebih banyak penyewa.';
            
            document.getElementById('banner-icon-small').className = 'fas fa-building text-4xl';
            document.getElementById('banner-icon-bg').className = 'fas fa-building text-[12rem] absolute -bottom-10 -left-10 opacity-10 transform -rotate-12';
        }
    </script>
</body>
</html>
