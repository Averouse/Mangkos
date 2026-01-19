<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mangkos - Solusi Kost & Roommate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer" onclick="location.href='{{ route('landing') }}'">
                    <img src="{{ asset('images/mangkos_icon.png') }}" alt="Mangkos" class="w-8 h-8 rounded-lg shadow-sm">
                    <span class="text-xl font-bold text-gray-800 tracking-tight">Mangkos</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden">
        <div class="absolute inset-0 hero-pattern -z-10"></div>
        <div class="absolute top-20 right-0 w-96 h-96 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left animate-fade-up">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                        Cari Roommate yang <span class="text-transparent bg-clip-text bg-gradient-to-r from-mangkos-main to-blue-500">Sefrekuensi</span> Itu Mudah!
                    </h1>
                    <p class="text-lg text-gray-500 mb-8 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        Jangan asal pilih teman sekamar. Gunakan sistem matematis kami untuk menemukan partner yang cocok secara gaya hidup, kebiasaan, dan budget.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
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

                <div class="relative lg:h-auto animate-float">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-gradient-to-tr from-green-200/40 to-blue-200/40 rounded-full blur-3xl -z-10"></div>
                    <img src="{{ asset('images/mangkos_icon.png') }}" alt="Mangkos App Illustration" class="w-full drop-shadow-2xl">
                    
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
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition group cursor-default">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-mangkos-main text-2xl mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Matchmaking</h3>
                    <p class="text-gray-500 leading-relaxed">
                        Algoritma AHP-TOPSIS kami menghitung kecocokan gaya hidup secara matematis agar kamu nyaman.
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition group cursor-default">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 text-2xl mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Terverifikasi Aman</h3>
                    <p class="text-gray-500 leading-relaxed">
                        Setiap pengguna wajib mengunggah Kartu Identitas mereka. Ucapkan selamat tinggal pada akun anonim dan penipu.
                    </p>
                </div>

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

    <!-- USAGE GUIDE SECTION -->
    <section id="panduan" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-base text-mangkos-main font-bold tracking-wide uppercase">Panduan Penggunaan</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Mudah Digunakan, Langsung Paham
                </p>
            </div>

            <!-- Toggle Switch (Top) -->
            <div class="flex justify-center mb-12">
                <div class="inline-flex bg-gray-100 rounded-2xl p-1.5">
                    <button onclick="switchRole('user')" id="btn-user" class="px-8 py-3 rounded-xl font-semibold transition-all duration-300 bg-mangkos-main text-white shadow-md">
                        <i class="fas fa-user-graduate mr-2"></i>Mahasiswa
                    </button>
                    <button onclick="switchRole('owner')" id="btn-owner" class="px-8 py-3 rounded-xl font-semibold transition-all duration-300 text-gray-600">
                        <i class="fas fa-home mr-2"></i>Pemilik Kost
                    </button>
                </div>
            </div>

            <!-- Content + Screenshots (Middle) -->
            <div class="max-w-5xl mx-auto mb-12">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden">
                    <!-- User Content -->
                    <div id="content-user">
                        <div id="img-user-1" class="preview-image">
                            <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 p-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-indigo-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user-edit text-3xl text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">Lengkapi Profil</h3>
                                        <p class="text-gray-700">Isi data kampus, jurusan, angkatan, dan nomor WhatsApp</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="rounded-xl overflow-hidden shadow-lg">
                                    <img src="{{ asset('images/guide/user-profile.png') }}" class="w-full" alt="Verify KTM">
                                </div>
                            </div>
                        </div>
                        <div id="img-user-2" class="preview-image hidden">
                            <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 p-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-indigo-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-id-card text-3xl text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">Verifikasi KTM</h3>
                                        <p class="text-gray-700">Upload foto KTM dan selfie dengan KTM</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="rounded-xl overflow-hidden shadow-lg">
                                    <img src="{{ asset('images/guide/user-verify.png') }}" class="w-full" alt="Verify KTM">
                                </div>
                            </div>
                        </div>
                        <div id="img-user-3" class="preview-image hidden">
                            <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 p-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-indigo-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-map-marked-alt text-3xl text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">Cari Kost</h3>
                                        <p class="text-gray-700">Temukan kost di peta berdasarkan lokasi kampus</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="rounded-xl overflow-hidden shadow-lg">
                                    <img src="{{ asset('images/guide/user-search.png') }}" class="w-full" alt="Search Kost">
                                </div>
                            </div>
                        </div>
                        <div id="img-user-4" class="preview-image hidden">
                            <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 p-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-indigo-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-paper-plane text-3xl text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">Ajukan Sewa</h3>
                                        <p class="text-gray-700">Kirim pengajuan sewa ke pemilik kost</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="rounded-xl overflow-hidden shadow-lg">
                                    <img src="{{ asset('images/guide/user-apply.png') }}" class="w-full" alt="Apply Rent">
                                </div>
                            </div>
                        </div>
                        <div id="img-user-5" class="preview-image hidden">
                            <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 p-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-indigo-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-users text-3xl text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">Matchmaking</h3>
                                        <p class="text-gray-700">Temukan teman sekamar yang cocok dengan sistem matematis</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6" x-data="{ current: 0, total: 2 }">
                                <div class="relative rounded-xl overflow-hidden shadow-lg">
                                    <img x-show="current === 0" src="{{ asset('images/guide/user-match.png') }}" class="w-full" alt="Matchmaking">
                                    <img x-show="current === 1" src="{{ asset('images/guide/user-match2.png') }}" class="w-full" alt="Match Result">
                                    <button @click="current = current > 0 ? current - 1 : total - 1" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 w-12 h-12 rounded-full shadow-xl flex items-center justify-center transition">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button @click="current = current < total - 1 ? current + 1 : 0" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 w-12 h-12 rounded-full shadow-xl flex items-center justify-center transition">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                                <div class="flex justify-center gap-2 mt-4">
                                    <template x-for="i in total" :key="i">
                                        <button @click="current = i - 1" :class="current === i - 1 ? 'bg-indigo-600 w-8' : 'bg-gray-300 w-2'" class="h-2 rounded-full transition-all"></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Owner Content -->
                    <div id="content-owner" class="hidden">
                        <div id="img-owner-1" class="preview-image">
                            <div class="bg-gradient-to-r from-orange-50 to-orange-100 p-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-phone text-3xl text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">Lengkapi Profil</h3>
                                        <p class="text-gray-700">Isi nomor WhatsApp untuk dihubungi calon penghuni</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="rounded-xl overflow-hidden shadow-lg">
                                    <img src="{{ asset('images/guide/owner-profile.png') }}" class="w-full" alt="Owner Profile">
                                </div>
                            </div>
                        </div>
                        <div id="img-owner-2" class="preview-image hidden">
                            <div class="bg-gradient-to-r from-orange-50 to-orange-100 p-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-id-card text-3xl text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">Verifikasi KTP</h3>
                                        <p class="text-gray-700">Upload foto KTP untuk verifikasi identitas</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="rounded-xl overflow-hidden shadow-lg">
                                    <img src="{{ asset('images/guide/owner-verify.png') }}" class="w-full" alt="Verify KTP">
                                </div>
                            </div>
                        </div>
                        <div id="img-owner-3" class="preview-image hidden">
                            <div class="bg-gradient-to-r from-orange-50 to-orange-100 p-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-plus-circle text-3xl text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">Tambah Kost</h3>
                                        <p class="text-gray-700">Daftarkan properti kost Anda dengan lengkap</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="rounded-xl overflow-hidden shadow-lg">
                                    <img src="{{ asset('images/guide/owner-add.png') }}" class="w-full" alt="Add Kost">
                                </div>
                            </div>
                        </div>
                        <div id="img-owner-4" class="preview-image hidden">
                            <div class="bg-gradient-to-r from-orange-50 to-orange-100 p-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-cog text-3xl text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">Kelola Properti</h3>
                                        <p class="text-gray-700">Edit dan kelola informasi kost Anda</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="rounded-xl overflow-hidden shadow-lg">
                                    <img src="{{ asset('images/guide/owner-manage.png') }}" class="w-full" alt="Manage Property">
                                </div>
                            </div>
                        </div>
                        <div id="img-owner-5" class="preview-image hidden">
                            <div class="bg-gradient-to-r from-orange-50 to-orange-100 p-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-clipboard-list text-3xl text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">Kelola Pengajuan</h3>
                                        <p class="text-gray-700">Terima atau tolak pengajuan sewa dari mahasiswa</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="rounded-xl overflow-hidden shadow-lg">
                                    <img src="{{ asset('images/guide/owner-applications.png') }}" class="w-full" alt="Manage Applications">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Steps (Bottom, Horizontal) -->
            <div class="max-w-5xl mx-auto">
                <!-- User Timeline -->
                <div id="timeline-user" class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <button onclick="showStep('user', 1)" class="step-btn p-4 rounded-xl border-2 border-mangkos-main bg-mangkos-main/5 text-left transition hover:shadow-md">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-mangkos-main text-white rounded-full flex items-center justify-center font-bold text-sm">1</div>
                            <h4 class="font-bold text-sm">Lengkapi Profil</h4>
                        </div>
                    </button>
                    <button onclick="showStep('user', 2)" class="step-btn p-4 rounded-xl border-2 border-gray-200 text-left transition hover:border-mangkos-main/50 hover:shadow-md">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-bold text-sm">2</div>
                            <h4 class="font-bold text-sm">Verifikasi KTM</h4>
                        </div>
                    </button>
                    <button onclick="showStep('user', 3)" class="step-btn p-4 rounded-xl border-2 border-gray-200 text-left transition hover:border-mangkos-main/50 hover:shadow-md">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-bold text-sm">3</div>
                            <h4 class="font-bold text-sm">Cari Kost</h4>
                        </div>
                    </button>
                    <button onclick="showStep('user', 4)" class="step-btn p-4 rounded-xl border-2 border-gray-200 text-left transition hover:border-mangkos-main/50 hover:shadow-md">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-bold text-sm">4</div>
                            <h4 class="font-bold text-sm">Ajukan Sewa</h4>
                        </div>
                    </button>
                    <button onclick="showStep('user', 5)" class="step-btn p-4 rounded-xl border-2 border-gray-200 text-left transition hover:border-mangkos-main/50 hover:shadow-md">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-bold text-sm">5</div>
                            <h4 class="font-bold text-sm">Matchmaking</h4>
                        </div>
                    </button>
                </div>

                <!-- Owner Timeline -->
                <div id="timeline-owner" class="grid grid-cols-2 md:grid-cols-5 gap-4 hidden">
                    <button onclick="showStep('owner', 1)" class="step-btn p-4 rounded-xl border-2 border-orange-500 bg-orange-50 text-left transition hover:shadow-md">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-sm">1</div>
                            <h4 class="font-bold text-sm">Lengkapi Profil</h4>
                        </div>
                    </button>
                    <button onclick="showStep('owner', 2)" class="step-btn p-4 rounded-xl border-2 border-gray-200 text-left transition hover:border-orange-500/50 hover:shadow-md">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-bold text-sm">2</div>
                            <h4 class="font-bold text-sm">Verifikasi KTP</h4>
                        </div>
                    </button>
                    <button onclick="showStep('owner', 3)" class="step-btn p-4 rounded-xl border-2 border-gray-200 text-left transition hover:border-orange-500/50 hover:shadow-md">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-bold text-sm">3</div>
                            <h4 class="font-bold text-sm">Tambah Kost</h4>
                        </div>
                    </button>
                    <button onclick="showStep('owner', 4)" class="step-btn p-4 rounded-xl border-2 border-gray-200 text-left transition hover:border-orange-500/50 hover:shadow-md">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-bold text-sm">4</div>
                            <h4 class="font-bold text-sm">Kelola Properti</h4>
                        </div>
                    </button>
                    <button onclick="showStep('owner', 5)" class="step-btn p-4 rounded-xl border-2 border-gray-200 text-left transition hover:border-orange-500/50 hover:shadow-md">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-bold text-sm">5</div>
                            <h4 class="font-bold text-sm">Kelola Pengajuan</h4>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section id="faq" class="py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h2 class="text-base text-mangkos-main font-bold tracking-wide uppercase">FAQ</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Pertanyaan yang Sering Diajukan
                </p>
            </div>

            <!-- FAQ Container -->
            <div x-data="{ category: 'umum', open: null }">
                <!-- Category Tabs -->
                <div class="flex justify-center mb-8 gap-2">
                    <button @click="category = 'umum'; open = null" :class="category === 'umum' ? 'bg-mangkos-main text-white' : 'bg-white text-gray-600'" class="px-6 py-2 rounded-xl font-semibold transition">
                        <i class="fas fa-info-circle mr-2"></i>Umum
                    </button>
                    <button @click="category = 'mahasiswa'; open = null" :class="category === 'mahasiswa' ? 'bg-mangkos-main text-white' : 'bg-white text-gray-600'" class="px-6 py-2 rounded-xl font-semibold transition">
                        <i class="fas fa-user-graduate mr-2"></i>Mahasiswa
                    </button>
                    <button @click="category = 'pemilik'; open = null" :class="category === 'pemilik' ? 'bg-orange-500 text-white' : 'bg-white text-gray-600'" class="px-6 py-2 rounded-xl font-semibold transition">
                        <i class="fas fa-home mr-2"></i>Pemilik
                    </button>
                </div>

                <!-- FAQ Items -->
                <div class="space-y-3">
                <!-- Umum -->
                <div x-show="category === 'umum'" class="space-y-3">
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <button @click="open = open === 1 ? null : 1" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-question-circle text-mangkos-main"></i>
                                <span class="font-semibold text-gray-900">Apa itu Mangkos?</span>
                            </div>
                            <i :class="open === 1 ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-gray-400 transition-transform"></i>
                        </button>
                        <div x-show="open === 1" x-collapse class="px-6 pb-4 text-gray-600">
                            Mangkos adalah platform pencarian kost dan teman sekamar berbasis AI yang membantu mahasiswa menemukan tempat tinggal dan roommate yang cocok berdasarkan gaya hidup dan preferensi.
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <button @click="open = open === 2 ? null : 2" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-shield-alt text-mangkos-main"></i>
                                <span class="font-semibold text-gray-900">Apakah Mangkos aman digunakan?</span>
                            </div>
                            <i :class="open === 2 ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-gray-400 transition-transform"></i>
                        </button>
                        <div x-show="open === 2" x-collapse class="px-6 pb-4 text-gray-600">
                            Ya, sangat aman. Setiap pengguna wajib verifikasi KTM (mahasiswa) atau KTP (pemilik) dan disetujui admin sebelum dapat mengakses fitur lengkap.
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <button @click="open = open === 3 ? null : 3" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-money-bill-wave text-mangkos-main"></i>
                                <span class="font-semibold text-gray-900">Apakah Mangkos berbayar?</span>
                            </div>
                            <i :class="open === 3 ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-gray-400 transition-transform"></i>
                        </button>
                        <div x-show="open === 3" x-collapse class="px-6 pb-4 text-gray-600">
                            Tidak, Mangkos sepenuhnya gratis untuk mahasiswa dan pemilik kost. Tidak ada biaya pendaftaran atau biaya tersembunyi.
                        </div>
                    </div>
                </div>

                <!-- Mahasiswa -->
                <div x-show="category === 'mahasiswa'" class="space-y-3">
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <button @click="open = open === 4 ? null : 4" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-id-card text-mangkos-main"></i>
                                <span class="font-semibold text-gray-900">Bagaimana cara verifikasi KTM?</span>
                            </div>
                            <i :class="open === 4 ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-gray-400 transition-transform"></i>
                        </button>
                        <div x-show="open === 4" x-collapse class="px-6 pb-4 text-gray-600">
                            Setelah melengkapi profil, upload foto KTM dan foto selfie dengan KTM. Admin akan memverifikasi dalam 1-2 hari kerja.
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <button @click="open = open === 5 ? null : 5" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-brain text-mangkos-main"></i>
                                <span class="font-semibold text-gray-900">Bagaimana cara kerja matchmaking?</span>
                            </div>
                            <i :class="open === 5 ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-gray-400 transition-transform"></i>
                        </button>
                        <div x-show="open === 5" x-collapse class="px-6 pb-4 text-gray-600">
                            Isi preferensi gaya hidup (kebiasaan tidur, merokok, kebersihan, dll). Algoritma AHP-TOPSIS akan menghitung kecocokan dan menampilkan rekomendasi teman sekamar dengan persentase kecocokan.
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <button @click="open = open === 6 ? null : 6" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-paper-plane text-mangkos-main"></i>
                                <span class="font-semibold text-gray-900">Bagaimana cara mengajukan sewa kost?</span>
                            </div>
                            <i :class="open === 6 ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-gray-400 transition-transform"></i>
                        </button>
                        <div x-show="open === 6" x-collapse class="px-6 pb-4 text-gray-600">
                            Pilih kost di peta, klik detail, lalu klik tombol "Ajukan Sewa". Pemilik akan menerima notifikasi dan dapat menerima/menolak pengajuan Anda.
                        </div>
                    </div>
                </div>

                <!-- Pemilik -->
                <div x-show="category === 'pemilik'" class="space-y-3">
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <button @click="open = open === 7 ? null : 7" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-home text-orange-500"></i>
                                <span class="font-semibold text-gray-900">Bagaimana cara menambahkan kost?</span>
                            </div>
                            <i :class="open === 7 ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-gray-400 transition-transform"></i>
                        </button>
                        <div x-show="open === 7" x-collapse class="px-6 pb-4 text-gray-600">
                            Setelah verifikasi KTP disetujui, klik "Tambah Kost" di dashboard. Isi detail (nama, alamat, harga, tipe, jumlah kamar), upload foto, pilih lokasi di peta, dan centang fasilitas yang tersedia.
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <button @click="open = open === 8 ? null : 8" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-edit text-orange-500"></i>
                                <span class="font-semibold text-gray-900">Bisakah saya edit info kost setelah ditambahkan?</span>
                            </div>
                            <i :class="open === 8 ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-gray-400 transition-transform"></i>
                        </button>
                        <div x-show="open === 8" x-collapse class="px-6 pb-4 text-gray-600">
                            Ya, Anda dapat mengedit informasi kost kapan saja melalui menu "Kelola Properti". Anda juga bisa mengatur status ketersediaan (tersedia/penuh).
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <button @click="open = open === 9 ? null : 9" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-bell text-orange-500"></i>
                                <span class="font-semibold text-gray-900">Bagaimana saya tahu ada pengajuan sewa?</span>
                            </div>
                            <i :class="open === 9 ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-gray-400 transition-transform"></i>
                        </button>
                        <div x-show="open === 9" x-collapse class="px-6 pb-4 text-gray-600">
                            Anda akan menerima notifikasi di dashboard. Buka menu "Kelola Pengajuan" untuk melihat daftar pengajuan dan dapat menerima atau menolak dengan satu klik.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        let currentRole = 'user';

        function switchRole(role) {
            currentRole = role;
            const btnUser = document.getElementById('btn-user');
            const btnOwner = document.getElementById('btn-owner');
            const contentUser = document.getElementById('content-user');
            const contentOwner = document.getElementById('content-owner');
            const timelineUser = document.getElementById('timeline-user');
            const timelineOwner = document.getElementById('timeline-owner');

            if (role === 'user') {
                btnUser.className = 'px-8 py-3 rounded-xl font-semibold transition-all duration-300 bg-mangkos-main text-white shadow-md';
                btnOwner.className = 'px-8 py-3 rounded-xl font-semibold transition-all duration-300 text-gray-600';
                contentUser.classList.remove('hidden');
                contentOwner.classList.add('hidden');
                timelineUser.classList.remove('hidden');
                timelineOwner.classList.add('hidden');
            } else {
                btnOwner.className = 'px-8 py-3 rounded-xl font-semibold transition-all duration-300 bg-orange-500 text-white shadow-md';
                btnUser.className = 'px-8 py-3 rounded-xl font-semibold transition-all duration-300 text-gray-600';
                contentOwner.classList.remove('hidden');
                contentUser.classList.add('hidden');
                timelineOwner.classList.remove('hidden');
                timelineUser.classList.add('hidden');
            }
            showStep(role, 1);
        }

        function showStep(role, step) {
            for (let i = 1; i <= 5; i++) {
                const img = document.getElementById(`img-${role}-${i}`);
                if (img) img.classList.add('hidden');
            }
            const targetImg = document.getElementById(`img-${role}-${step}`);
            if (targetImg) targetImg.classList.remove('hidden');

            const timeline = document.getElementById(`timeline-${role}`);
            const buttons = timeline.querySelectorAll('button');
            buttons.forEach((btn, idx) => {
                const stepNum = idx + 1;
                const isActive = stepNum === step;
                const color = role === 'user' ? 'mangkos-main' : 'orange-500';
                const bgColor = role === 'user' ? 'bg-mangkos-main' : 'bg-orange-500';
                const borderColor = role === 'user' ? 'border-mangkos-main' : 'border-orange-500';
                
                if (isActive) {
                    btn.className = `step-btn p-4 rounded-xl border-2 ${borderColor} ${bgColor}/5 text-left transition hover:shadow-md`;
                    btn.querySelector('.w-8').className = `w-8 h-8 ${bgColor} text-white rounded-full flex items-center justify-center font-bold text-sm`;
                } else {
                    btn.className = 'step-btn p-4 rounded-xl border-2 border-gray-200 text-left transition hover:border-' + color + '/50 hover:shadow-md';
                    btn.querySelector('.w-8').className = 'w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-bold text-sm';
                }
            });
        }
    </script>

    <!-- FOOTER -->
    <footer class="bg-gray-50 border-t border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <img src="{{ asset('images/mangkos_icon.png') }}" alt="Mangkos" class="w-8 h-8 rounded-lg">
                    <span class="text-xl font-bold text-gray-800">Mangkos</span>
                </div>
                <p class="text-gray-500 text-sm max-w-xs">Platform pencarian kost dan teman sekamar berbasis kecerdasan buatan untuk mahasiswa sekitaran Sarijadi, Sukasari, Bandung.</p>
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
