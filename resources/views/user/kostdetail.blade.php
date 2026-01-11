<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kost->name }} - Mangkos</title>
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
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 pb-24 md:pb-0">

    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('kost.search') }}" class="flex items-center gap-2">
                <div class="md:hidden mr-2 text-gray-500">
                    <i class="fas fa-arrow-left text-xl"></i>
                </div>
                <div class="w-8 h-8 bg-mangkos-main rounded-lg flex items-center justify-center text-white font-bold">M</div>
                <span class="text-xl font-bold text-mangkos-dark tracking-tight hidden md:block">Mangkos</span>
            </a>
            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-mangkos-main transition">Dashboard</a>
                <a href="{{ route('kost.search') }}" class="text-mangkos-main">Cari Kost</a>
                <a href="{{ route('matchmaking.index') }}" class="hover:text-mangkos-main transition">Matchmaking</a>
            </div>
            @auth
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600 hidden sm:block">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition p-2 rounded-lg hover:bg-red-50">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-6 md:py-10">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="md:col-span-2 space-y-8">
                
                <div class="space-y-4">
                    <div class="aspect-video w-full bg-gray-200 rounded-2xl overflow-hidden shadow-sm relative">
                        @if($kost->photos && count($kost->photos) > 0)
                            <img src="/uploads/kosts/{{ $kost->photos[0] }}" class="w-full h-full object-cover" alt="{{ $kost->name }}">
                            <div class="absolute bottom-4 right-4 bg-black/50 text-white px-3 py-1 rounded-full text-xs backdrop-blur">
                                1/{{ count($kost->photos) }} Foto
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                <i class="fas fa-building text-gray-500 text-4xl"></i>
                            </div>
                        @endif
                    </div>
                    @if($kost->photos && count($kost->photos) > 1)
                    <div class="flex gap-4 overflow-x-auto pb-2">
                        @foreach($kost->photos as $index => $photo)
                        <img src="/uploads/kosts/{{ $photo }}" 
                            class="w-24 h-16 object-cover rounded-lg cursor-pointer {{ $index === 0 ? 'ring-2 ring-mangkos-main' : 'hover:opacity-80 transition' }}"
                            onclick="changeMainImage('/uploads/kosts/{{ $photo }}', {{ $index }})">
                        @endforeach
                    </div>
                    @endif
                </div>

                <div>
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="bg-{{ $kost->type === 'putra' ? 'blue' : 'pink' }}-100 text-{{ $kost->type === 'putra' ? 'blue' : 'pink' }}-600 px-3 py-1 rounded-full text-xs font-bold">
                            Khusus {{ ucfirst($kost->type) }}
                        </span>
                        @if($kost->is_full)
                            <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold">PENUH</span>
                        @else
                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-bold">TERSEDIA</span>
                        @endif
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $kost->name }}</h1>
                    <p class="text-gray-500 flex items-center gap-1 text-sm">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $kost->address }}
                    </p>
                </div>

                <hr class="border-black/50">

                @if($kost->facilities && count($kost->facilities) > 0)
                @php
                    function getFacilityIcon($facility) {
                        $icons = [
                            'wifi' => 'fas fa-wifi',
                            'ac' => 'fas fa-snowflake', 
                            'kamar' => 'fas fa-bath',
                            'mandi' => 'fas fa-bath',
                            'lemari' => 'fas fa-door-closed',
                            'kasur' => 'fas fa-bed',
                            'meja' => 'fas fa-desktop',
                            'kursi' => 'fas fa-chair',
                            'listrik' => 'fas fa-bolt',
                            'motor' => 'fas fa-motorcycle',
                            'mobil' => 'fas fa-car',
                            'dapur' => 'fas fa-utensils',
                            'laundry' => 'fas fa-shirt',
                            'keamanan' => 'fas fa-person-military-pointing',
                            'cctv' => 'fas fa-eye',
                            'tv' => 'fas fa-tv',
                            'kulkas' => 'fas fa-cube',
                            'parkir' => 'fas fa-parking',
                            'security' => 'fas fa-person-military-pointing',
                            'tamu' => 'fas fa-couch',
                            'malam' => 'fas fa-moon'
                        ];
                        
                        $facilityLower = strtolower($facility);
                        foreach ($icons as $key => $icon) {
                            if (strpos($facilityLower, $key) !== false) {
                                return $icon;
                            }
                        }
                        return 'fas fa-check';
                    }

                    function formatFacilityName($facility) {
                        return ucwords(str_replace('_', ' ', $facility));
                    }
                @endphp
                <div>
                    <h3 class="font-bold text-lg mb-4">Fasilitas Kost</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($kost->facilities as $facility)
                        <div class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl">
                            <div class="w-8 h-8 bg-green-50 rounded-full flex items-center justify-center text-mangkos-main">
                                <i class="{{ getFacilityIcon($facility) }} text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-600">{{ formatFacilityName($facility) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <hr class="border-black/50">
                
                @if($kost->description)
                <div>
                    <h3 class="font-bold text-lg mb-2">Deskripsi Kost</h3>
                    <p class="text-gray-600 leading-relaxed text-sm md:text-base">
                        {{ $kost->description }}
                    </p>
                </div>
                @endif
                
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ $kost->owner->name }}&background=0D8ABC&color=fff" class="w-12 h-12 rounded-full">
                        <div>
                            <p class="font-bold text-gray-800">{{ $kost->owner->name }}</p>
                            <p class="text-xs text-gray-500">Pemilik Kost</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="md:col-span-1 relative">
                <div class="sticky top-24 bg-white p-6 rounded-2xl shadow-xl shadow-gray-100 border border-gray-100 hidden md:block">
                    <div class="flex justify-between items-end mb-4">
                        <div>
                            <p class="text-2xl font-bold text-mangkos-main">Rp {{ number_format($kost->price, 0, ',', '.') }}</p>
                        </div>
                        <span class="text-gray-500 text-sm">/ bulan</span>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm text-gray-600 border-b border-gray-100 pb-2">
                            <span>Tipe Kost</span>
                            <span class="font-semibold">{{ ucfirst($kost->type) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600 border-b border-gray-100 pb-2">
                            <span>Total Kamar</span>
                            <span class="font-semibold">{{ $kost->total_rooms }} kamar</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600 border-b border-gray-100 pb-2">
                            <span>Status</span>
                            <span class="font-semibold {{ $kost->is_full ? 'text-red-600' : 'text-green-600' }}">
                                {{ $kost->is_full ? 'PENUH' : 'TERSEDIA' }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @auth
                        @if(!$kost->is_full)
                        <button onclick="openRentalModal()" class="w-full bg-mangkos-main hover:bg-mangkos-dark text-white font-bold py-3 rounded-xl transition shadow-lg shadow-green-200">
                            Ajukan Sewa
                        </button>
                        @else
                        <button disabled class="w-full bg-gray-300 text-gray-500 font-bold py-3 rounded-xl cursor-not-allowed">
                            Kost Penuh
                        </button>
                        @endif
                        @else
                        <a href="{{ route('login') }}" class="block w-full bg-mangkos-main hover:bg-mangkos-dark text-white font-bold py-3 rounded-xl transition shadow-lg shadow-green-200 text-center">
                            Login untuk Sewa
                        </a>
                        @endauth
                        
                        @if($kost->owner->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kost->owner->phone) }}?text=Halo, saya tertarik dengan kost {{ $kost->name }}" target="_blank" class="w-full bg-white border border-mangkos-main text-mangkos-main font-bold py-3 rounded-xl hover:bg-green-50 transition flex items-center justify-center gap-2">
                            <i class="fab fa-whatsapp"></i>
                            Chat Pemilik
                        </a>
                        @else
                        <button disabled class="w-full bg-gray-200 text-gray-400 font-bold py-3 rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                            <i class="fas fa-comment"></i>
                            No WhatsApp
                        </button>
                        @endif
                    </div>
                    
                </div>
            </div>

        </div>
    </main>

    <div class="md:hidden fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 p-4 z-50 flex items-center justify-between shadow-[0_-5px_15px_rgba(0,0,0,0.05)]">
        <div>
            <p class="text-xs text-gray-400">Harga per bulan</p>
            <p class="text-lg font-bold text-mangkos-main">Rp {{ number_format($kost->price, 0) }}</p>
        </div>

        <div class="flex gap-2">
            @if($kost->owner->phone)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kost->owner->phone) }}?text=Halo, saya tertarik dengan kost {{ $kost->name }}" target="_blank" class="p-3 border border-gray-200 rounded-xl text-mangkos-main">
                <i class="fab fa-whatsapp text-xl"></i>
            </a>
            @else
            <button disabled class="p-3 border border-gray-200 rounded-xl text-gray-400">
                <i class="fas fa-comment text-xl"></i>
            </button>
            @endif
            
            @auth
            @if(!$kost->is_full)
            <button onclick="openRentalModal()" class="bg-mangkos-main text-white font-bold px-6 py-2 rounded-xl shadow-lg shadow-green-100">
                Sewa
            </button>
            @else
            <button disabled class="bg-gray-300 text-gray-500 font-bold px-6 py-2 rounded-xl cursor-not-allowed">
                Penuh
            </button>
            @endif
            @else
            <a href="{{ route('login') }}" class="bg-mangkos-main text-white font-bold px-6 py-2 rounded-xl shadow-lg shadow-green-100">
                Login
            </a>
            @endauth
        </div>

    </div>

    <!-- Rental Application Modal -->
    <div id="rentalModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Konfirmasi Pengajuan Sewa</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin mengajukan sewa untuk kost ini?</p>
            <div class="flex gap-3">
                <button onclick="closeRentalModal()" class="flex-1 bg-gray-100 text-gray-600 font-semibold py-3 rounded-xl hover:bg-gray-200 transition">
                    Batal
                </button>
                <button onclick="submitRental()" class="flex-1 bg-mangkos-main text-white font-semibold py-3 rounded-xl hover:bg-mangkos-dark transition">
                    Ya, Ajukan
                </button>
            </div>
        </div>
    </div>

    <!-- Validation Code Modal -->
    <div id="validationModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6">
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-check-circle text-3xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Pengajuan Berhasil!</h3>
            </div>
            
            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-4 mb-4">
                <p class="text-sm text-yellow-800 font-medium mb-3">⚠️ PENTING: Screenshot kode ini dan kirim ke pemilik kost via WhatsApp</p>
                <div class="bg-white rounded-lg p-4 text-center">
                    <p class="text-xs text-gray-500 mb-1">Kode Validasi</p>
                    <p id="validationCode" class="text-2xl font-bold text-mangkos-main tracking-wider"></p>
                </div>
            </div>
            
            <div class="space-y-2 text-sm text-gray-600 mb-4">
                <p><strong>Nama Anda:</strong> <span id="userName"></span></p>
                <p><strong>Kost:</strong> <span id="kostName"></span></p>
                <p><strong>Pemilik:</strong> <span id="ownerName"></span></p>
            </div>
            
            <div class="flex gap-3">
                <button onclick="closeValidationModal()" class="flex-1 bg-gray-100 text-gray-600 font-semibold py-3 rounded-xl hover:bg-gray-200 transition">
                    Tutup
                </button>
                <a id="whatsappBtn" href="#" target="_blank" class="flex-1 bg-green-500 text-white font-semibold py-3 rounded-xl hover:bg-green-600 transition text-center">
                    <i class="fab fa-whatsapp mr-2"></i>Chat Owner
                </a>
            </div>
        </div>
    </div>

    <script>
        const kostId = {{ $kost->id }};
        
        function openRentalModal() {
            document.getElementById('rentalModal').classList.remove('hidden');
        }
        
        function closeRentalModal() {
            document.getElementById('rentalModal').classList.add('hidden');
        }
        
        function closeValidationModal() {
            document.getElementById('validationModal').classList.add('hidden');
            location.reload();
        }
        
        function submitRental() {
            fetch('{{ route("rental.apply") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ kost_id: kostId })
            })
            .then(response => response.json())
            .then(data => {
                closeRentalModal();
                
                if (data.success) {
                    document.getElementById('validationCode').textContent = data.validation_code;
                    document.getElementById('userName').textContent = data.user_name;
                    document.getElementById('kostName').textContent = data.kost_name;
                    document.getElementById('ownerName').textContent = data.owner_name;
                    
                    const waMessage = `Halo, saya ${data.user_name}. Saya ingin menyewa kost ${data.kost_name}. Kode Validasi: ${data.validation_code}`;
                    const waLink = `https://wa.me/${data.owner_phone.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(waMessage)}`;
                    document.getElementById('whatsappBtn').href = waLink;
                    
                    document.getElementById('validationModal').classList.remove('hidden');
                } else {
                    alert(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                closeRentalModal();
            });
        }
        function changeMainImage(src, index) {
            const mainImg = document.querySelector('.aspect-video img');
            const counter = document.querySelector('.aspect-video .absolute');
            const thumbnails = document.querySelectorAll('.flex.gap-4 img');
            
            mainImg.src = src;
            counter.textContent = `${index + 1}/{{ count($kost->photos) }} Foto`;
            
            thumbnails.forEach((thumb, i) => {
                thumb.className = i === index 
                    ? 'w-24 h-16 object-cover rounded-lg cursor-pointer ring-2 ring-mangkos-main'
                    : 'w-24 h-16 object-cover rounded-lg cursor-pointer hover:opacity-80 transition';
            });
        }
    </script>

</body>
</html>