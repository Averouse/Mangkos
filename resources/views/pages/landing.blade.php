<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mangkos - Solusi Kost & Roommate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mangkos: { light: '#6ee7b7', main: '#10b981', dark: '#047857', accent: '#0f766e' }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'fade-up': 'fadeUp 0.8s ease-out forwards',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        fadeUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .hero-pattern { background-image: radial-gradient(#10b981 1px, transparent 1px); background-size: 24px 24px; opacity: 0.1; }
    </style>
</head>
<body class="bg-white text-gray-800 overflow-x-hidden">

    <!-- NAVBAR -->
    <nav class="fixed top-0 w-full bg-white/90 backdrop-blur-md border-b border-gray-100 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer" onclick="location.href='{{ route('landing') }}'">
                    <div class="w-8 h-8 bg-mangkos-main rounded-lg flex items-center justify-center text-white font-bold shadow-sm">M</div>
                    <span class="text-xl font-bold text-gray-800 tracking-tight">Mangkos</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 hero-pattern -z-10"></div>
        <div class="absolute top-20 right-0 w-96 h-96 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="text-center lg:text-left animate-fade-up">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                        Cari Roommate yang <span class="text-transparent bg-clip-text bg-gradient-to-r from-mangkos-main to-blue-500">Sefrekuensi</span> Itu Mudah!
                    </h1>
                    <p class="text-lg text-gray-500 mb-8 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        Jangan asal pilih teman sekamar. Gunakan algoritma cerdas kami untuk menemukan partner yang cocok secara gaya hidup, kebiasaan, dan budget.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <!-- Tombol Pencari (Mahasiswa) -->
                        <a href="{{ route('login', ['role' => 'user']) }}" class="group relative px-8 py-4 bg-mangkos-main text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl hover:bg-mangkos-dark transition transform hover:-translate-y-1 overflow-hidden">
                            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                            <div class="relative flex items-center gap-3">
                                <i class="fas fa-search text-xl"></i>
                                <div class="text-left">
                                    <span class="block text-xs font-normal opacity-80">Saya Mahasiswa</span>
                                    <span class="block text-lg leading-none">Cari Kost & Teman</span>
                                </div>
                            </div>
                        </a>

                        <!-- Tombol Pemilik (Owner) -->
                        <a href="{{ route('login', ['role' => 'owner']) }}" class="group px-8 py-4 bg-white text-gray-800 font-bold rounded-2xl shadow-md border border-gray-100 hover:border-orange-200 hover:bg-orange-50 transition transform hover:-translate-y-1">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-orange-100 text-orange-500 rounded-xl flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="text-left">
                                    <span class="block text-xs font-normal text-gray-500 group-hover:text-orange-600">Saya Pemilik</span>
                                    <span class="block text-lg leading-none group-hover:text-orange-700">Sewakan Properti</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="relative lg:h-auto animate-float">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-gradient-to-tr from-green-200/40 to-blue-200/40 rounded-full blur-3xl -z-10"></div>
                    <img src="{{ asset('images/logo-mangkos.png') }}" alt="Mangkos App Illustration" class="w-full drop-shadow-2xl">
                    
                    <!-- Floating Card 1 -->
                    <div class="absolute top-10 left-0 bg-white p-4 rounded-2xl shadow-xl animate-bounce" style="animation-duration: 3s;">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=Raka&background=10b981&color=fff" class="w-10 h-10 rounded-full">
                            <div>
                                <p class="text-xs text-gray-400 font-bold">New Match!</p>
                                <p class="text-sm font-bold text-gray-800">Raka Pratama</p>
                            </div>
                            <span class="text-green-500 font-bold text-sm bg-green-50 px-2 py-1 rounded-lg">98%</span>
                        </div>
                    </div>

                    <!-- Floating Card 2 -->
                    <div class="absolute bottom-20 right-0 bg-white p-4 rounded-2xl shadow-xl animate-bounce" style="animation-duration: 4s; animation-delay: 1s;">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">Kamar Tersedia</p>
                                <p class="text-xs text-green-500 font-bold">Sarijadi Area</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section id="fitur" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-base text-mangkos-main font-bold tracking-wide uppercase">Mengapa Mangkos?</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Solusi Cerdas untuk Masalah Anak Kost
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    Kami menggabungkan teknologi pencarian lokasi dengan algoritma psikologi pencocokan teman.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition group cursor-default">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-mangkos-main text-2xl mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Matchmaking</h3>
                    <p class="text-gray-500 leading-relaxed">
                        Algoritma AHP-TOPSIS kami menghitung kecocokan gaya hidup (rokok, tidur, kebersihan) secara matematis agar kamu nyaman.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition group cursor-default">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 text-2xl mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Terverifikasi Aman</h3>
                    <p class="text-gray-500 leading-relaxed">
                        Setiap pengguna wajib mengunggah KTM (Kartu Tanda Mahasiswa). Ucapkan selamat tinggal pada akun anonim dan penipu.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition group cursor-default">
                    <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-500 text-2xl mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Peta Interaktif</h3>
                    <p class="text-gray-500 leading-relaxed">
                        Cari kost berdasarkan jarak ke kampus. Lihat harga, fasilitas, dan sisa kamar secara real-time di peta.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-50 border-t border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-mangkos-main rounded-lg flex items-center justify-center text-white font-bold">M</div>
                    <span class="text-xl font-bold text-gray-800">Mangkos</span>
                </div>
                <p class="text-gray-500 text-sm max-w-xs">Platform pencarian kost dan teman sekamar berbasis kecerdasan buatan untuk mahasiswa Indonesia.</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 mt-12 pt-8 border-t border-gray-200 text-center text-xs text-gray-400">
            &copy; 2025 Mangkos Project. All rights reserved.
            <div class="mt-4">
                <a href="{{ route('admin.login') }}" class="inline-block text-gray-400 hover:text-gray-600 transition text-[10px] font-medium">
                    <i class="fas fa-lock text-[8px]"></i> Admin
                </a>
            </div>
        </div>
    </footer>

</body>
</html>
