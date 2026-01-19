<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mangkos</title>
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
                <img src="{{ asset('images/mangkos_icon.png') }}" alt="Mangkos" class="w-8 h-8 rounded-lg shadow-md">
                <span class="text-xl font-bold text-mangkos-dark tracking-tight">Mangkos</span>
            </div>
            
            <!-- Navigation Menu -->
            <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
                <a href="{{ route('dashboard') }}" class="text-mangkos-main font-semibold">Dashboard</a>
                <a href="{{ route('kost.search') }}" class="hover:text-mangkos-main transition">Pencarian Kos</a>
                <a href="{{ route('matchmaking.index') }}" class="hover:text-mangkos-main transition">Matchmaking</a>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Notification Bell -->
                <div class="relative" x-data="{ open: false, notifications: [], unreadCount: 0 }" 
                     x-init="
                        fetch('/notifications')
                            .then(r => r.json())
                            .then(data => {
                                notifications = data;
                                unreadCount = data.filter(n => !n.is_read).length;
                            });
                     ">
                    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-mangkos-main transition rounded-lg hover:bg-gray-50">
                        <i class="fas fa-bell text-lg"></i>
                        <span x-show="unreadCount > 0" x-text="unreadCount" 
                              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold"></span>
                    </button>
                    
                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 z-50 max-h-96 overflow-y-auto">
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800">Notifikasi</h3>
                            <button @click="fetch('/notifications/read-all', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(() => { notifications.forEach(n => n.is_read = true); unreadCount = 0; })" 
                                    class="text-xs text-mangkos-main hover:underline">Tandai Semua Dibaca</button>
                        </div>
                        <div x-show="notifications.length === 0" class="p-8 text-center text-gray-400">
                            <i class="fas fa-bell-slash text-3xl mb-2"></i>
                            <p class="text-sm">Tidak ada notifikasi</p>
                        </div>
                        <template x-for="notif in notifications" :key="notif.id">
                            <div @click="if(!notif.is_read) { fetch(`/notifications/${notif.id}/read`, {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(() => { notif.is_read = true; unreadCount--; }); }" 
                                 :class="notif.is_read ? 'bg-white' : 'bg-blue-50'" 
                                 class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition">
                                <div class="flex gap-3">
                                    <div :class="notif.type === 'rental_status' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600'" 
                                         class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i :class="notif.type === 'rental_status' ? 'fa-home' : 'fa-user-check'" class="fas"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-sm text-gray-800" x-text="notif.title"></p>
                                        <p class="text-xs text-gray-600 mt-1" x-text="notif.message"></p>
                                        <p x-show="notif.rejection_reason" class="text-xs text-red-600 mt-1 font-medium">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            <span x-text="'Alasan: ' + notif.rejection_reason"></span>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1" x-text="new Date(notif.created_at).toLocaleDateString('id-ID')"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                
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
                    <a href="{{ route('kost.search') }}" class="block w-full text-center">Mulai Pencarian</a>
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
                        <a href="{{ route('matchmaking.index') }}" class="block w-full text-center">Mulai Matchmaking</a>
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
                        <img id="profile-photo" src="{{ Auth::user()->profile_photo ? asset('uploads/profiles/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=10b981&color=fff' }}" 
                            class="w-20 h-20 rounded-full border-4 border-green-50 shadow-sm object-cover">
                        <input type="file" id="photo-upload" accept="image/*" class="hidden">
                        <button onclick="document.getElementById('photo-upload').click()" class="absolute -bottom-1 -right-1 bg-white p-1.5 rounded-full border border-gray-200 shadow hover:bg-gray-50 transition">
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
                <form id="profile-form" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ Auth::user()->name }}" required
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ Auth::user()->phone }}" placeholder="08xxxxxxxxxx" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-mangkos-main focus:ring-2 focus:ring-green-100 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kampus <span class="text-red-500">*</span></label>
                            <input type="text" name="campus" value="{{ Auth::user()->campus }}" placeholder="Nama Universitas" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-mangkos-main focus:ring-2 focus:ring-green-100 outline-none transition">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan <span class="text-red-500">*</span></label>
                            <input type="text" name="major" value="{{ Auth::user()->major }}" placeholder="Program Studi" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-mangkos-main focus:ring-2 focus:ring-green-100 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Angkatan <span class="text-red-500">*</span></label>
                            <input type="number" name="year" value="{{ Auth::user()->year }}" placeholder="2024" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-mangkos-main focus:ring-2 focus:ring-green-100 outline-none transition">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="bg-mangkos-main text-white px-6 py-3 rounded-lg font-medium hover:bg-mangkos-dark transition">
                            Simpan Perubahan
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
                        
                        @php
                            $profileComplete = Auth::user()->phone && Auth::user()->campus && Auth::user()->major && Auth::user()->year;
                        @endphp
                        
                        @if(!$profileComplete)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-yellow-700 font-medium"><i class="fas fa-exclamation-triangle mr-2"></i>Lengkapi profil terlebih dahulu sebelum upload KTM</p>
                        </div>
                        @else
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-blue-700">Upload foto KTM untuk mengakses fitur matchmaking teman sekamar</p>
                        </div>
                        @endif
                        
                        <form id="ktm-form" enctype="multipart/form-data" {{ !$profileComplete ? 'style=pointer-events:none;opacity:0.5' : '' }}>
                            @csrf
                            <div class="space-y-6">
                                <!-- KTM Card Photo -->
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-mangkos-main hover:bg-green-50 transition">
                                    <i class="fas fa-id-card text-3xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-600 font-medium mb-1">Upload Foto KTM</p>
                                    <p class="text-xs text-gray-500 mb-3">Foto KTM saja (JPG, PNG maksimal 2MB)</p>
                                    <input type="file" name="ktm_card_photo" class="hidden" id="ktm-card-upload" accept="image/*" required>
                                    <label for="ktm-card-upload" class="bg-blue-100 text-blue-600 px-4 py-2 rounded-lg text-sm font-medium cursor-pointer hover:bg-blue-200 transition">
                                        Pilih Foto KTM
                                    </label>
                                    <p id="ktm-card-file-name" class="text-sm text-gray-500 mt-2 hidden"></p>
                                </div>
                                
                                <!-- Selfie with KTM Photo -->
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-mangkos-main hover:bg-green-50 transition">
                                    <i class="fas fa-camera text-3xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-600 font-medium mb-1">Upload Foto Selfie dengan KTM</p>
                                    <p class="text-xs text-gray-500 mb-3">Foto Anda memegang KTM (JPG, PNG maksimal 2MB)</p>
                                    <input type="file" name="ktm_selfie_photo" class="hidden" id="ktm-selfie-upload" accept="image/*" required>
                                    <label for="ktm-selfie-upload" class="bg-green-100 text-green-600 px-4 py-2 rounded-lg text-sm font-medium cursor-pointer hover:bg-green-200 transition">
                                        Pilih Foto Selfie
                                    </label>
                                    <p id="ktm-selfie-file-name" class="text-sm text-gray-500 mt-2 hidden"></p>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" id="verify-btn" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition disabled:opacity-50" disabled>
                                    <i class="fas fa-shield-check mr-2"></i>
                                    Kirim Verifikasi KTM
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
                
                <!-- Matchmaking Profiles Section -->
                @php
                    $profiles = App\Models\MatchmakingProfile::where('user_id', Auth::id())->with('kost')->get();
                @endphp
                @if($profiles->count() > 0)
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <h4 class="font-medium text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-heart text-red-500"></i>
                        Profil Matchmaking Saya
                    </h4>
                    
                    <div class="space-y-4">
                        @foreach($profiles as $profile)
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h5 class="font-bold text-gray-800">{{ $profile->kost->name }}</h5>
                                    <p class="text-xs text-gray-500">{{ $profile->kost->address }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $profile->is_visible ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        <i class="fas fa-{{ $profile->is_visible ? 'eye' : 'eye-slash' }} mr-1"></i>
                                        {{ $profile->is_visible ? 'Terlihat' : 'Tersembunyi' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs mb-3">
                                <div class="bg-white p-2 rounded border border-gray-100">
                                    <span class="text-gray-500">Budget:</span>
                                    <strong class="block">{{ $profile->preferences['budget'] }}/5</strong>
                                </div>
                                <div class="bg-white p-2 rounded border border-gray-100">
                                    <span class="text-gray-500">Merokok:</span>
                                    <strong class="block">{{ $profile->preferences['smoke'] === 'yes' ? 'Ya' : 'Tidak' }}</strong>
                                </div>
                                <div class="bg-white p-2 rounded border border-gray-100">
                                    <span class="text-gray-500">Kebersihan:</span>
                                    <strong class="block">{{ $profile->preferences['clean'] }}/5</strong>
                                </div>
                                <div class="bg-white p-2 rounded border border-gray-100">
                                    <span class="text-gray-500">Tidur:</span>
                                    <strong class="block">{{ ucfirst($profile->preferences['sleep']) }}</strong>
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                <button onclick="toggleProfileVisibility({{ $profile->kost_id }}, {{ $profile->is_visible ? 'false' : 'true' }})" class="flex-1 bg-{{ $profile->is_visible ? 'gray' : 'green' }}-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-80 transition">
                                    <i class="fas fa-{{ $profile->is_visible ? 'eye-slash' : 'eye' }} mr-1"></i>
                                    {{ $profile->is_visible ? 'Sembunyikan' : 'Tampilkan' }}
                                </button>
                                <a href="{{ route('matchmaking.select', $profile->kost_id) }}" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition text-center">
                                    <i class="fas fa-edit mr-1"></i> Edit Preferensi
                                </a>
                                <a href="{{ route('matchmaking.results', $profile->kost_id) }}" class="flex-1 bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-600 transition text-center">
                                    <i class="fas fa-users mr-1"></i> Lihat Match
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>


    </main>

    <script>
    // Profile photo upload
    document.getElementById('photo-upload')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        if (file.size > 2048000) {
            alert('❌ Ukuran file terlalu besar! Maksimal 2MB');
            e.target.value = '';
            return;
        }
        
        if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
            alert('❌ Format file tidak didukung! Gunakan JPG, PNG, atau JPEG');
            e.target.value = '';
            return;
        }
        
        const formData = new FormData();
        formData.append('photo', file);
        
        fetch('{{ route("user.profile.photo") }}', {
            method: 'POST',
            body: formData,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        })
        .then(r => {
            if (!r.ok) throw new Error('Upload gagal');
            return r.json();
        })
        .then(data => {
            if(data.success) {
                document.getElementById('profile-photo').src = '/uploads/profiles/' + data.photo;
                alert('✅ Foto profil berhasil diperbarui!');
            } else {
                throw new Error(data.message || 'Upload gagal');
            }
        })
        .catch(error => {
            alert('❌ Gagal mengupload foto: ' + error.message);
        });
    });
    
    // Profile form submission
    document.getElementById('profile-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = {};
        formData.forEach((value, key) => { data[key] = value; });
        
        fetch('{{ route("user.profile.update") }}', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => {
            if (!r.ok) {
                return r.json().then(err => {
                    throw new Error(err.message || 'Validasi gagal');
                });
            }
            return r.json();
        })
        .then(data => {
            if(data.success) {
                alert('✅ Profil berhasil diperbarui!');
                location.reload();
            }
        })
        .catch(error => {
            alert('❌ Terjadi kesalahan: ' + error.message);
        });
    });
    
    function toggleProfileVisibility(kostId, visible) {
        fetch('{{ route("matchmaking.toggle-visibility") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                kost_id: kostId,
                is_visible: visible
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
    
    // Handle both file uploads for users
    document.getElementById('ktm-card-upload')?.addEventListener('change', function(e) {
        handleKtmFileUpload(e, 'ktm-card-file-name', 'KTM Card');
        checkBothKtmFiles();
    });

    document.getElementById('ktm-selfie-upload')?.addEventListener('change', function(e) {
        handleKtmFileUpload(e, 'ktm-selfie-file-name', 'KTM Selfie');
        checkBothKtmFiles();
    });

    function handleKtmFileUpload(e, fileNameId, type) {
        const fileName = document.getElementById(fileNameId);
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            fileName.textContent = `${type} dipilih: ${file.name}`;
            fileName.classList.remove('hidden');
        }
    }

    function checkBothKtmFiles() {
        const verifyBtn = document.getElementById('verify-btn');
        const ktmCard = document.getElementById('ktm-card-upload').files[0];
        const ktmSelfie = document.getElementById('ktm-selfie-upload').files[0];
        
        verifyBtn.disabled = !(ktmCard && ktmSelfie);
    }

    // Handle KTM verification submission
    document.getElementById('ktm-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('verify-btn');
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
        btn.disabled = true;
        
        const formData = new FormData(this);
        
        fetch('{{ route("user.ktm.upload") }}', {
            method: 'POST',
            body: formData,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        })
        .then(response => {
            if (!response.ok) throw new Error('Upload gagal');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                btn.innerHTML = '<i class="fas fa-check mr-2"></i> Terkirim';
                btn.classList.remove('bg-blue-600');
                btn.classList.add('bg-green-600');
                alert('KTM berhasil dikirim! Admin akan memverifikasi dalam 1x24 jam.');
            } else {
                throw new Error(data.message || 'Upload gagal');
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan: ' + error.message);
            btn.innerHTML = '<i class="fas fa-shield-check mr-2"></i> Kirim Verifikasi KTM';
            btn.disabled = false;
        });
    });
    </script>

    <!-- Mobile Bottom Navigation -->
    <nav class="md:hidden fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 px-6 py-3 z-50 flex justify-between items-center text-xs font-medium text-gray-400">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 text-mangkos-main">
            <i class="fas fa-home text-lg"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('kost.search') }}" class="flex flex-col items-center gap-1 hover:text-mangkos-main transition">
            <i class="fas fa-map-marked-alt text-lg"></i>
            <span>Peta</span>
        </a>
        <a href="{{ route('matchmaking.index') }}" class="flex flex-col items-center gap-1 hover:text-mangkos-main transition">
            <i class="fas fa-users text-lg"></i>
            <span>Match</span>
        </a>
    </nav>

</body>
</html>
