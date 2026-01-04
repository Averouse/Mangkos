<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Mangkos</title>
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
<body class="bg-gray-50 text-gray-800 h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-green-50 to-blue-50 -z-20"></div>
    <div class="absolute -top-20 -right-20 w-96 h-96 bg-mangkos-light rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s"></div>

    <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 bg-white rounded-3xl shadow-2xl overflow-hidden m-4 transform transition-all hover:scale-[1.01] duration-500">
        
        <!-- BAGIAN KIRI: Banner Gambar (Dinamis) -->
        <div id="login-banner" class="hidden md:flex flex-col justify-center items-center bg-gradient-to-br from-mangkos-main to-mangkos-dark p-10 text-white relative overflow-hidden">
            <!-- Dekorasi Pattern -->
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            
            <a href="{{ route('landing') }}" class="absolute top-6 left-6 text-white/80 hover:text-white flex items-center gap-2 text-sm font-bold transition hover:-translate-x-1 z-20">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            
            <div class="relative z-10 text-center">
                <div class="mb-6 bg-white/20 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto backdrop-blur-sm border border-white/30 shadow-inner">
                    <i class="fas fa-user-graduate text-4xl" id="banner-icon-small"></i>
                </div>
                <h2 class="text-3xl font-bold mb-3" id="banner-title">Selamat Datang!</h2>
                <p class="text-green-50 text-sm leading-relaxed px-4" id="banner-desc">Temukan teman kost yang sefrekuensi dan tempat tinggal impian sekarang juga.</p>
            </div>
            
            <!-- Background Icon Besar -->
            <i class="fas fa-user-graduate text-[12rem] absolute -bottom-10 -right-10 opacity-10 transform rotate-12" id="banner-icon-bg"></i>
        </div>

        <!-- BAGIAN KANAN: Form Login -->
        <div class="p-8 md:p-12 flex flex-col justify-center relative">
            <a href="{{ route('landing') }}" class="md:hidden absolute top-4 left-4 text-gray-400 hover:text-mangkos-main transition"><i class="fas fa-arrow-left text-xl"></i></a>
            
            <div class="flex items-center gap-3 mb-8 justify-center md:justify-start">
                <div class="w-10 h-10 bg-mangkos-main rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-green-200">M</div>
                <div>
                    <span class="text-xl font-bold text-gray-800 block leading-none">Mangkos</span>
                    <span class="text-[10px] text-gray-400 font-medium tracking-wider">SECURE LOGIN</span>
                </div>
                
                <!-- Badge Role (Mahasiswa/Mitra) -->
                <span id="role-badge" class="ml-auto px-3 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 uppercase tracking-wide border border-blue-100">
                    Mahasiswa
                </span>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Masuk Akun</h2>
                <p class="text-sm text-gray-500">Silakan login untuk melanjutkan akses.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-5">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400 group-focus-within:text-mangkos-main transition"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-mangkos-main focus:ring-4 focus:ring-green-50 outline-none transition duration-200 text-sm font-medium" placeholder="email@contoh.com" required>
                        <!-- After email input -->
                        @error('email')<p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mb-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 group-focus-within:text-mangkos-main transition"></i>
                        </div>
                        <input type="password" name="password" id="password" class="w-full pl-11 pr-12 py-3.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-mangkos-main focus:ring-4 focus:ring-green-50 outline-none transition duration-200 text-sm font-medium" placeholder="••••••••" required>
                        <!-- After password input -->
                        @error('password')<p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>@enderror
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-gray-400 hover:text-gray-600 transition" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eye-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mb-8">
                    <a href="#" class="text-xs text-gray-500 font-medium hover:text-mangkos-main transition">Lupa Password?</a>
                </div>

                <button type="submit" id="btn-login" class="w-full bg-mangkos-main text-white font-bold py-4 rounded-xl shadow-lg shadow-green-200 hover:bg-mangkos-dark hover:shadow-green-300 transition transform hover:-translate-y-0.5 active:translate-y-0 flex justify-center items-center gap-2 group">
                    <span>Masuk Sekarang</span> 
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition"></i>
                </button>
            </form>

            <div class="text-center text-sm text-gray-500 mt-8 pt-6 border-t border-gray-100">
                Belum punya akun? 
                <a id="link-register" href="{{ route('register') }}" class="text-mangkos-main font-bold hover:underline hover:text-mangkos-dark transition ml-1">Daftar di sini</a>
            </div>
        </div>
    </div>

    <!-- SCRIPT LOGIKA UTAMA -->
    <script>
        // 1. AMBIL ROLE DARI URL
        // Contoh URL: loginNew.html?role=owner
        const urlParams = new URLSearchParams(window.location.search);
        const role = urlParams.get('role') || 'user'; // Default user jika tidak ada param

        // 2. MODIFIKASI TAMPILAN JIKA ROLE = OWNER
        if (role === 'owner') {
            // Ubah Badge di sebelah Logo
            const badge = document.getElementById('role-badge');
            badge.innerText = 'Mitra Pemilik';
            badge.className = 'ml-auto px-3 py-1 rounded-full text-[10px] font-bold bg-orange-50 text-orange-600 uppercase tracking-wide border border-orange-100';
            
            // Ubah Gambar & Teks Banner Kiri
            document.getElementById('banner-title').innerText = 'Kelola Bisnis Kost';
            document.getElementById('banner-desc').innerText = 'Pantau okupansi, kelola pembayaran, dan temukan penyewa terbaik untuk properti Anda.';
            
            // Ubah Ikon
            document.getElementById('banner-icon-small').className = 'fas fa-chart-line text-4xl';
            document.getElementById('banner-icon-bg').className = 'fas fa-chart-line text-[12rem] absolute -bottom-10 -right-10 opacity-10 transform rotate-12';
            
            // Ubah Link "Daftar" agar ikut menjadi Owner
            document.getElementById('link-register').href = '{{ route("register") }}?role=owner';
        }

        // Fitur Toggle Password
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if(input.type === "password") {
                input.type = "text";
                icon.className = "fas fa-eye-slash";
            } else {
                input.type = "password";
                icon.className = "fas fa-eye";
            }
        }

        // 3. LOGIKA TOMBOL LOGIN (REDIRECT)
        function handleLogin(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-login');
            const originalContent = btn.innerHTML;
            
            // Efek Loading
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i>';
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');

            // Simulasi Delay Server (1.5 detik)
            setTimeout(() => {
                // Di sinilah PENGALIHAN ARUS terjadi
                if (role === 'owner') {
                    // Jika Owner -> Ke Dashboard Admin
                    window.location.href = 'owner_dashboard.html';
                } else {
                    // Jika User -> Ke Beranda Pencarian
                    window.location.href = 'index.html';
                }
            }, 1500);
        }
    </script>
</body>
</html>