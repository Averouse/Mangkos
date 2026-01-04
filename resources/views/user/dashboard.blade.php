<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mangkos</title>
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
<body class="bg-gray-50 text-gray-800">

    <!-- NAVBAR -->
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 shadow-sm">
        <div class="px-4 py-3 flex justify-between items-center max-w-6xl mx-auto">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-mangkos-main rounded-lg flex items-center justify-center text-white font-bold shadow-md">M</div>
                <span class="text-xl font-bold text-mangkos-dark tracking-tight">Mangkos</span>
            </div>
            
            <!-- Navigation Menu -->
            <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
                <a href="{{ route('dashboard') }}" class="text-mangkos-main font-semibold">Dashboard</a>
                <a href="#" class="hover:text-mangkos-main transition">Pencarian Kos</a>
                <a href="#" class="hover:text-mangkos-main transition">Matchmaking</a>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600 hidden sm:block">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition p-2 rounded-lg hover:bg-red-50" title="Keluar">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>


    <main class="max-w-4xl mx-auto px-4 py-8">
        
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-mangkos-main to-mangkos-dark rounded-2xl p-8 text-white mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-8 translate-x-8"></div>
            <div class="relative">
                <h1 class="text-2xl md:text-3xl font-bold mb-2">Selamat Datang!</h1>
                <p class="text-green-100 mb-6">Temukan kost dan teman sekamar yang cocok dengan gaya hidupmu</p>
                
                <!-- Status Badge -->
                @if(Auth::user()->status === 'approved')
                    <div class="inline-flex items-center gap-2 bg-green-500/20 text-green-100 px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fas fa-check-circle"></i> Akun Terverifikasi
                    </div>
                @else
                    <div class="inline-flex items-center gap-2 bg-yellow-500/20 text-yellow-100 px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fas fa-exclamation-triangle"></i> Verifikasi Diperlukan
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition group cursor-pointer">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition">
                        <i class="fas fa-search text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Cari Kost</h3>
                        <p class="text-sm text-gray-500">Temukan kost di sekitar kampus</p>
                    </div>
                </div>
                <button class="w-full bg-blue-50 text-blue-600 py-3 rounded-lg font-medium hover:bg-blue-100 transition">
                    Mulai Pencarian
                </button>
            </div>
            
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition group cursor-pointer">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600 group-hover:scale-110 transition">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Cari Teman</h3>
                        <p class="text-sm text-gray-500">Matchmaking teman sekamar</p>
                    </div>
                </div>
                @if(Auth::user()->status === 'approved')
                    <button class="w-full bg-green-50 text-green-600 py-3 rounded-lg font-medium hover:bg-green-100 transition">
                        Mulai Matchmaking
                    </button>
                @else
                    <button disabled class="w-full bg-gray-100 text-gray-400 py-3 rounded-lg font-medium cursor-not-allowed">
                        Perlu Verifikasi KTM
                    </button>
                @endif
            </div>
        </div>

        <!-- Profile & Settings Section -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-user-circle text-mangkos-main"></i>
                    Profil & Pengaturan
                </h2>
            </div>
            
            <div class="p-6">
                <div class="flex items-start gap-6 mb-6">
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=10b981&color=fff" 
                            class="w-20 h-20 rounded-full border-4 border-green-50 shadow-sm">
                        <button class="absolute -bottom-1 -right-1 bg-white p-1.5 rounded-full border border-gray-200 shadow hover:bg-gray-50 transition">
                            <i class="fas fa-camera text-gray-500 text-xs"></i>
                        </button>
                    </div>
                    
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ Auth::user()->name }}</h3>
                        <p class="text-gray-500 mb-3">Bergabung {{ Auth::user()->created_at->format('M Y') }}</p>
                        
                        @if(Auth::user()->status === 'approved')
                            <div class="inline-flex items-center gap-2 bg-green-50 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-check-circle"></i> Terverifikasi
                            </div>
                        @else
                            <div class="inline-flex items-center gap-2 bg-yellow-50 text-yellow-700 px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-clock"></i> Belum Terverifikasi
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Profile Form -->
                <form class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" value="{{ Auth::user()->name }}" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-mangkos-main focus:ring-2 focus:ring-green-100 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" value="{{ Auth::user()->email }}" disabled
                                class="w-full px-4 py-3 rounded-lg border border-gray-100 bg-gray-50 text-gray-400">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp</label>
                            <input type="text" placeholder="08xxxxxxxxxx" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-mangkos-main focus:ring-2 focus:ring-green-100 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kampus</label>
                            <input type="text" placeholder="Nama Universitas" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-mangkos-main focus:ring-2 focus:ring-green-100 outline-none transition">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan</label>
                            <input type="text" placeholder="Program Studi" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-mangkos-main focus:ring-2 focus:ring-green-100 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Angkatan</label>
                            <input type="number" placeholder="2024" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-mangkos-main focus:ring-2 focus:ring-green-100 outline-none transition">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="bg-mangkos-main text-white px-6 py-3 rounded-lg font-medium hover:bg-mangkos-dark transition">
                            Simpan Perubahan
                        </button>
                        <button type="button" class="bg-gray-100 text-gray-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition">
                            Batal
                        </button>
                    </div>
                </form>

                @if(Auth::user()->status !== 'approved')
                    <!-- KTM Verification Section -->
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h4 class="font-medium text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-id-card text-mangkos-main"></i>
                            Verifikasi KTM
                        </h4>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-blue-700">Upload foto KTM untuk mengakses fitur matchmaking teman sekamar</p>
                        </div>
                        
                        <form id="ktm-form" enctype="multipart/form-data">
                            @csrf
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-mangkos-main hover:bg-green-50 transition">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                                <p class="text-gray-600 font-medium mb-1">Upload Foto KTM</p>
                                <p class="text-xs text-gray-500 mb-3">JPG, PNG maksimal 2MB</p>
                                <input type="file" name="ktm_photo" class="hidden" id="ktm-upload" accept="image/*" required>
                                <label for="ktm-upload" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium cursor-pointer hover:bg-gray-200 transition">
                                    Pilih File KTM
                                </label>
                                <p id="file-name" class="text-sm text-gray-500 mt-2 hidden"></p>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" id="verify-btn" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                    <i class="fas fa-shield-check mr-2"></i>
                                    Kirim Verifikasi KTM
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>


    </main>

    <script>
    // Profile form and KTM verification are separate
    document.getElementById('ktm-upload')?.addEventListener('change', function(e) {
        const verifyBtn = document.getElementById('verify-btn');
        const fileName = document.getElementById('file-name');
        
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            fileName.textContent = `File dipilih: ${file.name}`;
            fileName.classList.remove('hidden');
            verifyBtn.disabled = false;
            verifyBtn.classList.remove('bg-gray-400');
            verifyBtn.classList.add('bg-blue-600');
        }
    });

    // Handle KTM verification submission
    document.getElementById('ktm-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('verify-btn');
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
        btn.disabled = true;
        
        // Simulate upload (replace with actual form submission)
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-check mr-2"></i> Terkirim';
            btn.classList.remove('bg-blue-600');
            btn.classList.add('bg-green-600');
            alert('KTM berhasil dikirim! Admin akan memverifikasi dalam 1x24 jam.');
        }, 2000);
    });
    </script>


    <!-- Mobile Bottom Navigation -->
    <nav class="md:hidden fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 px-6 py-3 z-50 flex justify-between items-center text-xs font-medium text-gray-400">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 text-mangkos-main">
            <i class="fas fa-home text-lg"></i>
            <span>Home</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 hover:text-mangkos-main transition">
            <i class="fas fa-map-marked-alt text-lg"></i>
            <span>Peta</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 hover:text-mangkos-main transition">
            <i class="fas fa-users text-lg"></i>
            <span>Match</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 hover:text-mangkos-main transition">
            <i class="fas fa-comment-dots text-lg"></i>
            <span>Chat</span>
        </a>
    </nav>

</body>
</html>
