<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pemilik - Mangkos</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            <div class="w-8 h-8 bg-mangkos-main rounded-lg flex items-center justify-center text-white font-bold shadow-md">M</div>
            <span class="text-lg font-bold text-gray-800">Mangkos <span class="text-xs font-normal text-gray-500 block">Owner</span></span>
        </div>
        
        <nav class="flex-1 p-4 space-y-2">
            <a href="#" class="flex items-center gap-3 px-4 py-3 bg-green-50 text-mangkos-dark rounded-xl font-medium border border-green-100">
                <i class="fas fa-chart-pie w-5"></i> Dashboard
            </a>
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
                            <p class="text-gray-400 text-xs uppercase font-bold">Kamar Terisi</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $occupiedRooms }} <span class="text-sm text-gray-400 font-normal">/ {{ $totalRooms }}</span></h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-2xl flex items-center justify-center text-xl">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs uppercase font-bold">Pending</p>
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
                            <div class="w-16 h-16 bg-gray-200 rounded-xl flex items-center justify-center">
                                <i class="fas fa-building text-gray-400 text-xl"></i>
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
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <p class="text-xs text-gray-400">Harga Sewa</p>
                                <p class="font-bold text-mangkos-dark">Rp {{ number_format($kost->price, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $kost->available_rooms }}/{{ $kost->total_rooms }} kamar tersedia</p>
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
                    
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" value="{{ Auth::user()->name }}" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-mangkos-main outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" value="{{ Auth::user()->email }}" disabled class="w-full px-4 py-3 rounded-lg border border-gray-100 bg-gray-50 text-gray-400">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp</label>
                                <input type="text" placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-mangkos-main outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                <input type="text" placeholder="Alamat lengkap" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-mangkos-main outline-none transition">
                            </div>
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
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-blue-700">Upload foto KTP untuk verifikasi identitas sebagai pemilik kost</p>
                    </div>
                    
                    <form id="ktpForm" enctype="multipart/form-data">
                        @csrf
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 hover:bg-blue-50 transition">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600 font-medium mb-1">Upload Foto KTP</p>
                            <p class="text-xs text-gray-500 mb-3">JPG, PNG maksimal 2MB</p>
                            <input type="file" name="ktp_photo" class="hidden" id="ktp-upload" accept="image/*" required>
                            <label for="ktp-upload" class="bg-blue-100 text-blue-600 px-4 py-2 rounded-lg text-sm font-medium cursor-pointer hover:bg-blue-200 transition">
                                Pilih File KTP
                            </label>
                            <p id="ktp-file-name" class="text-sm text-gray-500 mt-2 hidden"></p>
                        </div>
                        
                        <div class="mt-4">
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
            <div class="bg-white rounded-3xl w-full max-w-lg p-6 shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Tambah Kost Baru</h3>
                    <button onclick="closeModal('addKostModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="addKostForm">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Kost</label>
                            <input type="text" name="name" placeholder="Contoh: Kost Bahagia" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-mangkos-main outline-none transition" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Alamat</label>
                            <textarea name="address" placeholder="Alamat lengkap kost" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-mangkos-main outline-none transition" rows="2" required></textarea>
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
                    </div>
                    
                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="closeModal('addKostModal')" class="flex-1 py-3 text-gray-500 font-medium hover:bg-gray-50 rounded-xl transition">Batal</button>
                        <button type="submit" class="flex-1 bg-mangkos-main text-white font-bold py-3 rounded-xl hover:bg-mangkos-dark transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
        
        function showVerificationAlert() {
            alert('Silakan verifikasi KTP terlebih dahulu di menu Profil untuk dapat menambah kost.');
        }

        document.getElementById('addKostForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("owner.kosts.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        });
        // KTP verification handling
        document.getElementById('ktp-upload')?.addEventListener('change', function(e) {
            const verifyBtn = document.getElementById('ktp-verify-btn');
            const fileName = document.getElementById('ktp-file-name');
            
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                fileName.textContent = `File dipilih: ${file.name}`;
                fileName.classList.remove('hidden');
                verifyBtn.disabled = false;
            }
        });

        document.getElementById('ktpForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('ktp-verify-btn');
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
            btn.disabled = true;
            
            const formData = new FormData(this);
            
            fetch('{{ route("owner.ktp.upload") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check mr-2"></i> Terkirim';
                    btn.classList.remove('bg-blue-600');
                    btn.classList.add('bg-green-600');
                    alert('KTP berhasil dikirim! Admin akan memverifikasi dalam 1x24 jam.');
                } else {
                    alert('Error: ' + data.message);
                    btn.innerHTML = '<i class="fas fa-shield-check mr-2"></i> Kirim Verifikasi KTP';
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                btn.innerHTML = '<i class="fas fa-shield-check mr-2"></i> Kirim Verifikasi KTP';
                btn.disabled = false;
            });
        });
    </script>

</body>
</html>
