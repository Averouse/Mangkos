<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pemilik - Mangkos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800 h-screen flex overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r border-gray-200 h-full shadow-sm">
        <div class="p-6 border-b border-gray-100 flex items-center gap-3">
            <img src="{{ asset('images/mangkos_icon.png') }}" alt="Mangkos" class="w-8 h-8 rounded-lg shadow-md">
            <span class="text-lg font-bold text-gray-800">Mangkos <span class="text-xs font-normal text-gray-500 block">Owner</span></span>
        </div>
        
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-green-50 text-mangkos-dark rounded-xl font-medium border border-green-100">
                <i class="fas fa-chart-pie w-5"></i> Dashboard
            </a>
            <button onclick="openModal('rentalApplicationsModal')" class="flex w-full items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-800 rounded-xl font-medium transition text-left">
                <i class="fas fa-file-contract w-5"></i> Pengajuan Sewa
                @if($pendingApplications > 0)
                <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $pendingApplications }}</span>
                @endif
            </button>
            <button onclick="openModal('profileModal')" class="flex w-full items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-800 rounded-xl font-medium transition text-left">
                <i class="fas fa-user w-5"></i> Profil Saya
            </button>
            @if(Auth::user()->status === 'approved')
                <button onclick="openModal('addKostModal')" class="flex w-full items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-800 rounded-xl font-medium transition text-left">
                    <i class="fas fa-plus w-5"></i> Tambah Kost
                </button>
            @else
                <button onclick="showVerificationAlert()" class="flex w-full items-center gap-3 px-4 py-3 text-gray-400 cursor-not-allowed rounded-xl font-medium text-left">
                    <i class="fas fa-lock w-5"></i> Tambah Kost
                </button>
            @endif
        </nav>

        <div class="p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium w-full transition">
                    <i class="fas fa-sign-out-alt w-5"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-y-auto">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Halo, {{ Auth::user()->name }} 👋</h1>
                    <p class="text-gray-500 text-sm mt-1">Kelola properti kost Anda</p>
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
                            <i class="fas fa-bell text-xl"></i>
                            <span x-show="unreadCount > 0" x-text="unreadCount" 
                                  class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold"></span>
                        </button>
                        
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
                                        <div :class="notif.type === 'rental_application' ? 'bg-orange-100 text-orange-600' : notif.type === 'kost_verification' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600'" 
                                             class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i :class="notif.type === 'rental_application' ? 'fa-file-contract' : notif.type === 'kost_verification' ? 'fa-building' : 'fa-user-check'" class="fas"></i>
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
                    
                    @if(Auth::user()->status === 'approved')
                        <button onclick="openModal('addKostModal')" class="bg-mangkos-main text-white px-5 py-2.5 rounded-xl font-medium hover:bg-mangkos-dark transition flex items-center gap-2 shadow-lg">
                            <i class="fas fa-plus"></i> Tambah Kost
                        </button>
                    @else
                        <button onclick="showVerificationAlert()" class="bg-gray-400 text-white px-5 py-2.5 rounded-xl font-medium cursor-not-allowed flex items-center gap-2">
                            <i class="fas fa-lock"></i> Perlu Verifikasi
                        </button>
                    @endif
                </div>
            </div>
        </header>

        <div class="p-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-xl">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs uppercase font-bold">Total Kost</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $kosts->count() }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center text-xl">
                            <i class="fas fa-bed"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs uppercase font-bold">Kost Aktif</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $kosts->where('status', 'approved')->count() }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-2xl flex items-center justify-center text-xl">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs uppercase font-bold">Kost Pending</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $kosts->where('status', 'pending')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kost List -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800">Daftar Kost Anda</h2>
                </div>
                @if($kosts->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($kosts as $kost)
                        <div class="p-6 flex items-center gap-4 hover:bg-gray-50 transition">
                            <div class="w-16 h-16 bg-gray-200 rounded-xl overflow-hidden">
                                @if($kost->photos && count($kost->photos) > 0)
                                    <img src="/uploads/kosts/{{ $kost->photos[0] }}" class="w-full h-full object-cover" alt="{{ $kost->name }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-building text-gray-400 text-xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-800 text-lg">{{ $kost->name }}</h3>
                                <p class="text-sm text-gray-500 mb-2">{{ $kost->address }}</p>
                                <div class="flex gap-2">
                                    @if($kost->status === 'approved')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold">Aktif</span>
                                    @elseif($kost->status === 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-bold">Pending</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold">Ditolak</span>
                                    @endif
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs">{{ ucfirst($kost->type) }}</span>
                                    @if($kost->is_full)
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold">PENUH</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <p class="text-xs text-gray-400">Harga Sewa</p>
                                <p class="font-bold text-mangkos-dark">Rp {{ number_format($kost->price, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $kost->total_rooms }} kamar total</p>
                                <div class="flex gap-2 mt-2">
                                    <button onclick="toggleFull({{ $kost->id }})" class="bg-{{ $kost->is_full ? 'green' : 'red' }}-500 text-white px-2 py-1 rounded text-xs hover:opacity-80 transition">
                                        {{ $kost->is_full ? 'Set Available' : 'Set Full' }}
                                    </button>
                                    <button onclick="viewKostDetails({{ $kost->id }})" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-bold text-gray-700 mb-2">Belum Ada Kost</h3>
                        <p class="text-gray-500 mb-6">Mulai dengan menambahkan kost pertama Anda</p>
                        @if(Auth::user()->status === 'approved')
                            <button onclick="openModal('addKostModal')" class="bg-mangkos-main text-white px-6 py-3 rounded-lg font-medium hover:bg-mangkos-dark transition">
                                <i class="fas fa-plus mr-2"></i> Tambah Kost Pertama
                            </button>
                        @else
                            <button onclick="showVerificationAlert()" class="bg-gray-400 text-white px-6 py-3 rounded-lg font-medium cursor-not-allowed">
                                <i class="fas fa-lock mr-2"></i> Perlu Verifikasi KTP
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- PROFILE MODAL -->
    <div id="profileModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('profileModal')"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl w-full max-w-2xl p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Profil & Verifikasi</h3>
                    <button onclick="closeModal('profileModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Profile Section -->
                <div class="mb-8">
                    <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-user text-mangkos-main"></i>
                        Informasi Profil
                    </h4>
                    
                    <div class="flex items-center gap-4 mb-4">
                        <div class="relative">
                            <img id="owner-profile-photo" src="{{ Auth::user()->profile_photo ? asset('uploads/profiles/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=f97316&color=fff' }}" 
                                class="w-20 h-20 rounded-full border-4 border-orange-50 shadow-sm object-cover">
                            <input type="file" id="owner-photo-upload" accept="image/*" class="hidden">
                            <button onclick="document.getElementById('owner-photo-upload').click()" type="button" class="absolute -bottom-1 -right-1 bg-white p-1.5 rounded-full border border-gray-200 shadow hover:bg-gray-50 transition">
                                <i class="fas fa-camera text-gray-500 text-xs"></i>
                            </button>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    
                    <form id="owner-profile-form" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ Auth::user()->name }}" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-mangkos-main outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" value="{{ Auth::user()->email }}" disabled class="w-full px-4 py-3 rounded-lg border border-gray-100 bg-gray-50 text-gray-400">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ Auth::user()->phone }}" placeholder="08xxxxxxxxxx" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-mangkos-main outline-none transition">
                        </div>
                        
                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="bg-mangkos-main text-white px-6 py-3 rounded-lg font-medium hover:bg-mangkos-dark transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- KTP Verification Section -->
                @if(Auth::user()->status !== 'approved')
                <div class="border-t border-gray-100 pt-6">
                    <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-id-card text-blue-600"></i>
                        Verifikasi KTP
                    </h4>
                    
                    @php
                        $profileComplete = Auth::user()->phone;
                    @endphp
                    
                    @if(!$profileComplete)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-yellow-700 font-medium"><i class="fas fa-exclamation-triangle mr-2"></i>Lengkapi nomor WhatsApp terlebih dahulu sebelum upload KTP</p>
                    </div>
                    @else
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-blue-700">Upload foto KTP untuk verifikasi identitas sebagai pemilik kost</p>
                    </div>
                    @endif
                    
                    <form id="ktpForm" enctype="multipart/form-data" {{ !$profileComplete ? 'style=pointer-events:none;opacity:0.5' : '' }}>
                        @csrf
                        <div class="space-y-6">
                            <!-- ID Card Photo -->
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 hover:bg-blue-50 transition">
                                <i class="fas fa-id-card text-3xl text-gray-400 mb-3"></i>
                                <p class="text-gray-600 font-medium mb-1">Upload Foto KTP</p>
                                <p class="text-xs text-gray-500 mb-3">Foto KTP saja (JPG, PNG maksimal 2MB)</p>
                                <input type="file" name="id_card_photo" class="hidden" id="id-card-upload" accept="image/*" required>
                                <label for="id-card-upload" class="bg-blue-100 text-blue-600 px-4 py-2 rounded-lg text-sm font-medium cursor-pointer hover:bg-blue-200 transition">
                                    Pilih Foto KTP
                                </label>
                                <p id="id-card-file-name" class="text-sm text-gray-500 mt-2 hidden"></p>
                            </div>
                            
                            <!-- Selfie with ID Photo -->
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 hover:bg-blue-50 transition">
                                <i class="fas fa-camera text-3xl text-gray-400 mb-3"></i>
                                <p class="text-gray-600 font-medium mb-1">Upload Foto Selfie dengan KTP</p>
                                <p class="text-xs text-gray-500 mb-3">Foto Anda memegang KTP (JPG, PNG maksimal 2MB)</p>
                                <input type="file" name="selfie_with_id_photo" class="hidden" id="selfie-upload" accept="image/*" required>
                                <label for="selfie-upload" class="bg-green-100 text-green-600 px-4 py-2 rounded-lg text-sm font-medium cursor-pointer hover:bg-green-200 transition">
                                    Pilih Foto Selfie
                                </label>
                                <p id="selfie-file-name" class="text-sm text-gray-500 mt-2 hidden"></p>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" id="ktp-verify-btn" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition disabled:opacity-50" disabled>
                                <i class="fas fa-shield-check mr-2"></i>
                                Kirim Verifikasi KTP
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <div class="border-t border-gray-100 pt-6">
                    <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-lg p-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        <div>
                            <p class="font-medium text-green-800">Identitas Terverifikasi</p>
                            <p class="text-sm text-green-600">KTP Anda telah diverifikasi oleh admin</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ADD KOST MODAL -->
    <div id="addKostModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('addKostModal')"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl w-full max-w-4xl p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Tambah Kost Baru</h3>
                    <button onclick="closeModal('addKostModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="addKostForm" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        <!-- Left Column: Basic Info -->
                        <div class="space-y-4">
                            <h4 class="font-bold text-gray-700 border-b pb-2">Informasi Dasar</h4>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Kost</label>
                                <input type="text" name="name" placeholder="Contoh: Kost Bahagia" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-mangkos-main outline-none transition" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Lengkap</label>
                                <textarea name="address" placeholder="Alamat lengkap kost" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-mangkos-main outline-none transition" rows="3" required></textarea>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Harga/Bulan</label>
                                    <input type="number" name="price" placeholder="500000" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-mangkos-main outline-none transition" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Tipe</label>
                                    <select name="type" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-mangkos-main outline-none transition" required>
                                        <option value="putra">Putra</option>
                                        <option value="putri">Putri</option>
                                        <option value="campur">Campur</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah Kamar</label>
                                <input type="number" name="total_rooms" placeholder="10" min="1" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-mangkos-main outline-none transition" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi</label>
                                <textarea name="description" placeholder="Deskripsi singkat tentang kost..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-mangkos-main outline-none transition" rows="3"></textarea>
                            </div>
                        </div>
                        
                        <!-- Right Column: Photos, Location, Facilities -->
                        <div class="space-y-4">
                            
                            <!-- Photo Gallery -->
                            <div>
                                <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">Galeri Foto</h4>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-mangkos-main hover:bg-green-50 transition">
                                    <i class="fas fa-images text-3xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-600 font-medium mb-1">Upload Foto Kost</p>
                                    <p class="text-xs text-gray-500 mb-3">Pilih beberapa foto (JPG, PNG maksimal 2MB per foto)</p>
                                    <input type="file" name="photos[]" class="hidden" id="photos-upload" accept="image/*" multiple>
                                    <label for="photos-upload" class="bg-mangkos-main text-white px-4 py-2 rounded-lg text-sm font-medium cursor-pointer hover:bg-mangkos-dark transition">
                                        Pilih Foto
                                    </label>
                                    <div id="photo-preview" class="mt-4 grid grid-cols-3 gap-2 hidden"></div>
                                </div>
                            </div>
                            
                            <!-- Location -->
                            <div>
                                <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">Lokasi di Peta</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Latitude</label>
                                        <input type="number" name="latitude" step="any" placeholder="-6.8925" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-mangkos-main outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Longitude</label>
                                        <input type="number" name="longitude" step="any" placeholder="107.6110" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-mangkos-main outline-none transition">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Klik pada peta atau masukkan koordinat manual</p>
                            </div>
                            
                            <!-- Facilities -->
                            <div>
                                <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">Fasilitas</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="facilities[]" value="ac" class="rounded border-gray-300 text-mangkos-main focus:ring-mangkos-main">
                                        <span class="text-sm">AC</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="facilities[]" value="wifi" class="rounded border-gray-300 text-mangkos-main focus:ring-mangkos-main">
                                        <span class="text-sm">WiFi</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="facilities[]" value="kamar_mandi_dalam" class="rounded border-gray-300 text-mangkos-main focus:ring-mangkos-main">
                                        <span class="text-sm">K.Mandi Dalam</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="facilities[]" value="kamar_mandi_luar" class="rounded border-gray-300 text-mangkos-main focus:ring-mangkos-main">
                                        <span class="text-sm">K.Mandi Luar</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="facilities[]" value="dapur" class="rounded border-gray-300 text-mangkos-main focus:ring-mangkos-main">
                                        <span class="text-sm">Dapur</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="facilities[]" value="ruang_tamu" class="rounded border-gray-300 text-mangkos-main focus:ring-mangkos-main">
                                        <span class="text-sm">Ruang Tamu</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="facilities[]" value="parkir" class="rounded border-gray-300 text-mangkos-main focus:ring-mangkos-main">
                                        <span class="text-sm">Parkir</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="facilities[]" value="security" class="rounded border-gray-300 text-mangkos-main focus:ring-mangkos-main">
                                        <span class="text-sm">Security</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="facilities[]" value="laundry" class="rounded border-gray-300 text-mangkos-main focus:ring-mangkos-main">
                                        <span class="text-sm">Laundry</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="facilities[]" value="bebas_jam_malam" class="rounded border-gray-300 text-mangkos-main focus:ring-mangkos-main">
                                        <span class="text-sm">Bebas Jam Malam</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="closeModal('addKostModal')" class="flex-1 py-3 text-gray-500 font-medium hover:bg-gray-50 rounded-xl transition">Batal</button>
                        <button type="submit" class="flex-1 bg-mangkos-main text-white font-bold py-3 rounded-xl hover:bg-mangkos-dark transition">Simpan Kost</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- KOST DETAILS MODAL -->
    <div id="kostDetailsModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeKostDetails()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl w-full max-w-4xl p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Kost Details</h3>
                    <button onclick="closeKostDetails()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="kostDetailsContent"></div>
            </div>
        </div>
    </div>

    <!-- EDIT KOST MODAL -->
    <div id="editKostModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditKost()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl w-full max-w-4xl p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Edit Kost</h3>
                    <button onclick="closeEditKost()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="editKostContent"></div>
            </div>
        </div>
    </div>

    <!-- RENTAL APPLICATIONS MODAL -->
    <div id="rentalApplicationsModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('rentalApplicationsModal')"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl w-full max-w-4xl p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Pengajuan Sewa Kost</h3>
                    <button onclick="closeModal('rentalApplicationsModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                @if($rentalApplications->count() > 0)
                <div class="space-y-4">
                    @foreach($rentalApplications as $application)
                    <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition">
                        <div class="flex items-start gap-4">
                            <img src="{{ $application->user->profile_photo ? asset('uploads/profiles/' . $application->user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($application->user->name) . '&background=random&color=fff' }}" class="w-12 h-12 rounded-full object-cover">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800">{{ $application->user->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $application->user->email }}</p>
                                <p class="text-sm text-gray-500 mt-1">Kost: <strong>{{ $application->kost->name }}</strong></p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $application->created_at->format('d M Y H:i') }}
                                </p>
                                <p class="text-xs text-gray-400">{{ $application->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                @if($application->status === 'pending')
                                <button onclick="approveRental({{ $application->id }})" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-600 transition">
                                    <i class="fas fa-check mr-1"></i> Setujui
                                </button>
                                <button onclick="rejectRental({{ $application->id }})" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition">
                                    <i class="fas fa-times mr-1"></i> Tolak
                                </button>
                                @elseif($application->status === 'approved')
                                <span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm font-bold">Disetujui</span>
                                @else
                                <span class="bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm font-bold">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <h4 class="text-lg font-bold text-gray-700 mb-2">Belum Ada Pengajuan</h4>
                    <p class="text-gray-500">Pengajuan sewa akan muncul di sini</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        // Owner profile photo upload
        document.getElementById('owner-photo-upload')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            if (file.size > 2048000) {
                alert('Ukuran file terlalu besar! Maksimal 2MB');
                e.target.value = '';
                return;
            }
            
            if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                alert('Format file tidak didukung! Gunakan JPG, PNG, atau JPEG');
                e.target.value = '';
                return;
            }
            
            const formData = new FormData();
            formData.append('photo', file);
            
            fetch('/owner/profile/photo', {
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
                    document.getElementById('owner-profile-photo').src = '/uploads/profiles/' + data.photo;
                    alert('Foto profil berhasil diperbarui!');
                } else {
                    throw new Error(data.message || 'Upload gagal');
                }
            })
            .catch(error => {
                alert('Gagal mengupload foto: ' + error.message);
            });
        });
        
        // Owner profile form submission
        document.getElementById('owner-profile-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('/owner/profile/update', {
                method: 'POST',
                body: formData,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            })
            .then(r => {
                if (!r.ok) throw new Error('Update gagal');
                return r.json();
            })
            .then(data => {
                if(data.success) {
                    alert('Profil berhasil diperbarui!');
                    location.reload();
                } else {
                    throw new Error(data.message || 'Update gagal');
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan: ' + error.message);
            });
        });
        
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
        
        function showVerificationAlert() {
            alert('Silakan verifikasi KTP terlebih dahulu di menu Profil untuk dapat menambah kost.');
        }

        // Kost Details Modal
        function viewKostDetails(kostId) {
            const kostsData = @json($kosts);
            const kost = kostsData.find(k => k.id === kostId);
            
            if (!kost) return;
            
            const content = `
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-bold text-gray-700 mb-4">Basic Information</h4>
                        <div class="space-y-3">
                            <div><strong>Name:</strong> ${kost.name}</div>
                            <div><strong>Address:</strong> ${kost.address}</div>
                            <div><strong>Price:</strong> Rp ${new Intl.NumberFormat('id-ID').format(kost.price)}/month</div>
                            <div><strong>Type:</strong> ${kost.type}</div>
                            <div><strong>Total Rooms:</strong> ${kost.total_rooms}</div>
                            <div><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs ${kost.status === 'approved' ? 'bg-green-100 text-green-700' : kost.status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700'}">${kost.status}</span></div>
                            <div><strong>Description:</strong> ${kost.description || 'No description'}</div>
                        </div>
                        
                        <h4 class="font-bold text-gray-700 mb-4 mt-6">Facilities</h4>
                        <div class="flex flex-wrap gap-2">
                            ${kost.facilities && kost.facilities.length > 0 ? 
                                kost.facilities.map(f => `<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">${f}</span>`).join('') : 
                                'No facilities listed'
                            }
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-gray-700 mb-4">Photos</h4>
                        <div class="grid grid-cols-2 gap-3">
                            ${kost.photos && kost.photos.length > 0 ? 
                                kost.photos.map(photo => `<img src="/uploads/kosts/${photo}" class="w-full h-32 object-cover rounded border cursor-pointer" onclick="openPhotoModal('/uploads/kosts/${photo}', '${kost.name} - Photo')">`).join('') : 
                                '<p class="text-gray-500 col-span-2">No photos uploaded</p>'
                            }
                        </div>
                        
                        ${kost.latitude && kost.longitude ? `
                        <h4 class="font-bold text-gray-700 mb-4 mt-6">Location</h4>
                        <div class="space-y-2">
                            <div><strong>Latitude:</strong> ${kost.latitude}</div>
                            <div><strong>Longitude:</strong> ${kost.longitude}</div>
                        </div>
                        ` : ''}
                    </div>
                </div>
                
                <div class="mt-8 flex gap-3 justify-end">
                    <button onclick="editKost(${kost.id})" class="bg-orange-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-orange-600 transition">
                        <i class="fas fa-edit mr-2"></i> Edit Kost
                    </button>
                </div>
            `;
            
            document.getElementById('kostDetailsContent').innerHTML = content;
            document.getElementById('kostDetailsModal').classList.remove('hidden');
        }

        function closeKostDetails() {
            document.getElementById('kostDetailsModal').classList.add('hidden');
        }

        function openPhotoModal(photoUrl, title) {
            // Simple photo modal - you can enhance this
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="relative max-w-4xl max-h-full">
                    <button onclick="this.parentElement.parentElement.remove()" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                    <img src="${photoUrl}" alt="${title}" class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl">
                    <p class="text-white text-center mt-4 font-medium">${title}</p>
                </div>
            `;
            document.body.appendChild(modal);
        }


        document.getElementById('addKostForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('{{ route("owner.kosts.store") }}', {
                method: 'POST',
                body: formData,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            })
            .then(response => {
                if (!response.ok) throw new Error('Gagal menambahkan kost');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Kost berhasil ditambahkan!');
                    location.reload();
                } else {
                    throw new Error(data.message || 'Gagal menambahkan kost');
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan: ' + error.message);
            });
        });

        // KTP verification handling
        // Handle both file uploads
        document.getElementById('id-card-upload')?.addEventListener('change', function(e) {
            handleFileUpload(e, 'id-card-file-name', 'ID Card');
            checkBothFiles();
        });

        document.getElementById('selfie-upload')?.addEventListener('change', function(e) {
            handleFileUpload(e, 'selfie-file-name', 'Selfie');
            checkBothFiles();
        });

        function handleFileUpload(e, fileNameId, type) {
            const fileName = document.getElementById(fileNameId);
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                fileName.textContent = `${type} dipilih: ${file.name}`;
                fileName.classList.remove('hidden');
            }
        }

        function checkBothFiles() {
            const verifyBtn = document.getElementById('ktp-verify-btn');
            const idCard = document.getElementById('id-card-upload').files[0];
            const selfie = document.getElementById('selfie-upload').files[0];
            
            verifyBtn.disabled = !(idCard && selfie);
        }

        document.getElementById('ktpForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('ktp-verify-btn');
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
            btn.disabled = true;
            
            const formData = new FormData(this);
            
            fetch('{{ route("owner.ktp.upload") }}', {
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
                    alert('Berhasil dikirim! Admin akan memverifikasi dalam 1x24 jam.');
                } else {
                    throw new Error(data.message || 'Upload gagal');
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan: ' + error.message);
                btn.innerHTML = '<i class="fas fa-shield-check mr-2"></i> Kirim Verifikasi KTP';
                btn.disabled = false;
            });
        });

        // Photo preview functionality
        document.getElementById('photos-upload').addEventListener('change', function(e) {
            const files = e.target.files;
            const previewContainer = document.getElementById('photo-preview');
            
            if (files.length > 0) {
                previewContainer.innerHTML = '';
                previewContainer.classList.remove('hidden');
                
                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const photoDiv = document.createElement('div');
                            photoDiv.className = 'relative group';
                            photoDiv.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-20 object-cover rounded-lg border border-gray-200">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                    <button type="button" onclick="removePhoto(${index})" class="text-white text-xs bg-red-500 px-2 py-1 rounded">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 truncate">${file.name}</p>
                            `;
                            previewContainer.appendChild(photoDiv);
                        };
                        reader.readAsDataURL(file);
                    }
                });
                
                // Update upload button text
                const label = document.querySelector('label[for="photos-upload"]');
                label.innerHTML = `<i class="fas fa-check mr-1"></i> ${files.length} foto dipilih`;
                label.classList.remove('bg-mangkos-main', 'hover:bg-mangkos-dark');
                label.classList.add('bg-green-500', 'hover:bg-green-600');
            }
        });

        function removePhoto(index) {
            // This is a simplified version - in a full implementation, you'd need to manage the file list
            alert('Untuk menghapus foto, pilih ulang foto yang diinginkan');
        }

        function toggleFull(kostId) {
            fetch(`/owner/kosts/${kostId}/toggle-full`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Gagal mengubah status');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    throw new Error(data.message || 'Gagal mengubah status');
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan: ' + error.message);
            });
        }

        function editKost(kostId) {
            const kostsData = @json($kosts);
            const kost = kostsData.find(k => k.id === kostId);
            
            if (!kost) return;
            
            const facilitiesOptions = ['ac', 'wifi', 'kamar_mandi_dalam', 'kamar_mandi_luar', 'dapur', 'ruang_tamu', 'parkir', 'security', 'laundry', 'bebas_jam_malam'];
            
            const content = `
                <form id="editKostForm" enctype="multipart/form-data">
                    <input type="hidden" name="kost_id" value="${kost.id}">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h4 class="font-bold text-gray-700 border-b pb-2">Basic Information</h4>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Kost</label>
                                <input type="text" name="name" value="${kost.name}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 outline-none transition" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Lengkap</label>
                                <textarea name="address" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 outline-none transition" rows="3" required>${kost.address}</textarea>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Harga/Bulan</label>
                                    <input type="number" name="price" value="${kost.price}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 outline-none transition" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Tipe</label>
                                    <select name="type" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 outline-none transition" required>
                                        <option value="putra" ${kost.type === 'putra' ? 'selected' : ''}>Putra</option>
                                        <option value="putri" ${kost.type === 'putri' ? 'selected' : ''}>Putri</option>
                                        <option value="campur" ${kost.type === 'campur' ? 'selected' : ''}>Campur</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah Kamar</label>
                                <input type="number" name="total_rooms" value="${kost.total_rooms}" min="1" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 outline-none transition" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi</label>
                                <textarea name="description" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 outline-none transition" rows="3">${kost.description || ''}</textarea>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <h4 class="font-bold text-gray-700 border-b pb-2">Photos & Location</h4>
                            
                            <!-- Current Photos -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Current Photos</label>
                                <div class="grid grid-cols-3 gap-2" id="currentPhotos">
                                    ${kost.photos && kost.photos.length > 0 ? 
                                        kost.photos.map((photo, index) => `
                                            <div class="relative group">
                                                <img src="/uploads/kosts/${photo}" class="w-full h-20 object-cover rounded border">
                                                <button type="button" onclick="removePhoto('${photo}', ${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <input type="hidden" name="existing_photos[]" value="${photo}">
                                            </div>
                                        `).join('') : 
                                        '<p class="text-gray-500 col-span-3">No photos uploaded</p>'
                                    }
                                </div>
                            </div>
                            
                            <!-- Add New Photos -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Add New Photos</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-orange-500 hover:bg-orange-50 transition">
                                    <input type="file" name="new_photos[]" class="hidden" id="edit-photos-upload" accept="image/*" multiple>
                                    <label for="edit-photos-upload" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-medium cursor-pointer hover:bg-orange-600 transition">
                                        Add Photos
                                    </label>
                                    <div id="new-photo-preview" class="mt-4 grid grid-cols-3 gap-2 hidden"></div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Latitude</label>
                                    <input type="number" name="latitude" step="any" value="${kost.latitude || ''}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Longitude</label>
                                    <input type="number" name="longitude" step="any" value="${kost.longitude || ''}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 outline-none transition">
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-bold text-gray-700 mb-4">Fasilitas</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    ${facilitiesOptions.map(facility => `
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="facilities[]" value="${facility}" ${kost.facilities && kost.facilities.includes(facility) ? 'checked' : ''} class="rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                                            <span class="text-sm">${facility.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</span>
                                        </label>
                                    `).join('')}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="closeEditKost()" class="flex-1 py-3 text-gray-500 font-medium hover:bg-gray-50 rounded-xl transition">Batal</button>
                        <button type="submit" class="flex-1 bg-orange-500 text-white font-bold py-3 rounded-xl hover:bg-orange-600 transition">Update Kost</button>
                    </div>
                </form>
            `;
            
            document.getElementById('editKostContent').innerHTML = content;
            document.getElementById('kostDetailsModal').classList.add('hidden');
            document.getElementById('editKostModal').classList.remove('hidden');
            
            // Add photo preview for new uploads
            document.getElementById('edit-photos-upload').addEventListener('change', function(e) {
                const files = e.target.files;
                const previewContainer = document.getElementById('new-photo-preview');
                
                if (files.length > 0) {
                    previewContainer.innerHTML = '';
                    previewContainer.classList.remove('hidden');
                    
                    Array.from(files).forEach((file) => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const photoDiv = document.createElement('div');
                                photoDiv.className = 'relative';
                                photoDiv.innerHTML = `
                                    <img src="${e.target.result}" class="w-full h-20 object-cover rounded border">
                                    <p class="text-xs text-gray-500 mt-1 truncate">${file.name}</p>
                                `;
                                previewContainer.appendChild(photoDiv);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }
            });
            
            // Add form submit handler
            document.getElementById('editKostForm').addEventListener('submit', handleEditKostSubmit);
        }

        function removePhoto(photoName, index) {
            if (confirm('Remove this photo?')) {
                const photoElement = document.querySelector(`input[value="${photoName}"]`).parentElement;
                photoElement.remove();
            }
        }

        function closeEditKost() {
            document.getElementById('editKostModal').classList.add('hidden');
        }

        function handleEditKostSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const kostId = formData.get('kost_id');
            
            fetch(`/owner/kosts/${kostId}/update`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Kost berhasil diupdate! Status kembali ke pending untuk review admin.');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        }

        function approveRental(applicationId) {
            if (!confirm('Setujui pengajuan sewa ini?')) return;
            
            fetch(`/owner/rental/${applicationId}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Gagal menyetujui');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Pengajuan berhasil disetujui!');
                    location.reload();
                } else {
                    throw new Error(data.message || 'Gagal menyetujui');
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan: ' + error.message);
            });
        }

        function rejectRental(applicationId) {
            const reasons = [
                'Kamar tidak tersedia',
                'Tidak memenuhi syarat',
                'Sudah ada penyewa lain',
                'Profil tidak sesuai'
            ];
            
            let reasonHtml = reasons.map(r => `<option value="${r}">${r}</option>`).join('');
            
            const customReason = prompt(
                'Pilih alasan penolakan:\n' +
                '1. Kamar tidak tersedia\n' +
                '2. Tidak memenuhi syarat\n' +
                '3. Sudah ada penyewa lain\n' +
                '4. Profil tidak sesuai\n' +
                '5. Lainnya (ketik alasan)\n\n' +
                'Masukkan nomor (1-4) atau ketik alasan custom:'
            );
            
            if (!customReason) return;
            
            let reason;
            if (customReason === '1') reason = reasons[0];
            else if (customReason === '2') reason = reasons[1];
            else if (customReason === '3') reason = reasons[2];
            else if (customReason === '4') reason = reasons[3];
            else reason = customReason;
            
            fetch(`/owner/rental/${applicationId}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }

    </script>

</body>
</html>
