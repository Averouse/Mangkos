<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencocokan - Mangkos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('landing') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center text-white font-bold">M</div>
                <span class="text-xl font-bold text-gray-800">Mangkos</span>
            </a>
            <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition">
                <i class="fas fa-user text-sm"></i>
            </a>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto p-4 py-8">
        <div class="text-center mb-8">
            <div class="inline-block px-4 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold mb-2">
                <i class="fas fa-check-circle"></i> Hasil Rekomendasi
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Partner <span class="text-green-500">Terbaik</span></h1>
            <p class="text-gray-500 text-sm">{{ $kost->name }}</p>
        </div>

        @if($matches->isEmpty())
        <div class="bg-white rounded-3xl p-8 text-center shadow-sm mb-6">
            <i class="fas fa-users text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Match</h3>
            <p class="text-gray-500 text-sm mb-6">Belum ada penghuni lain yang mengisi profil matchmaking di kost ini.</p>
            
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <p class="text-sm text-blue-800 mb-4"><i class="fas fa-lightbulb mr-2"></i>Biarkan profil Anda terlihat agar penghuni lain bisa menemukan Anda!</p>
                <button onclick="toggleVisibility(true)" class="bg-blue-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-600 transition">
                    <i class="fas fa-eye mr-2"></i>Tampilkan Profil Saya
                </button>
            </div>
        </div>
        @else
        <div class="space-y-4">
            @foreach($matches as $match)
            @php
                $partner = $match->user1_id == auth()->id() ? $match->user2 : $match->user1;
                $score = round($match->compatibility_score);
                $borderClass = $loop->first ? 'border-green-500 bg-green-50 shadow-md' : 'border-gray-100 bg-white';
                $badgeColor = $score >= 80 ? 'bg-green-500' : ($score >= 60 ? 'bg-blue-500' : 'bg-yellow-500');
                $matchLabel = $score >= 90 ? 'Perfect Match!' : ($score >= 75 ? 'Great Match' : ($score >= 60 ? 'Good Match' : 'Fair Match'));
            @endphp
            <div class="p-4 rounded-2xl border-2 {{ $borderClass }} shadow-sm flex gap-4 items-center">
                <div class="relative">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($partner->name) }}&background=random&color=fff" class="w-16 h-16 rounded-full border-2 border-white shadow">
                    <div class="absolute -bottom-1 -right-1 {{ $badgeColor }} text-white text-xs font-bold px-2 py-0.5 rounded-full border-2 border-white">
                        {{ $score }}%
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="font-bold text-gray-800">{{ $partner->name }}</h3>
                        @if($score >= 90)
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-semibold">{{ $matchLabel }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500">{{ $partner->major ?? 'Mahasiswa' }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="bg-white border border-gray-200 text-gray-600 text-xs px-2 py-0.5 rounded-full">{{ $partner->campus }}</span>
                        <span class="text-xs text-gray-400">• {{ $score }}% Compatible</span>
                    </div>
                </div>
                <button onclick="showProfile({{ $partner->id }})" class="w-10 h-10 rounded-xl bg-white border border-gray-100 text-gray-500 shadow-sm flex items-center justify-center hover:bg-gray-100 transition">
                    <i class="fas fa-user"></i>
                </button>
                @if($partner->phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $partner->phone) }}" target="_blank" class="w-10 h-10 rounded-xl bg-white border border-gray-100 text-green-500 shadow-sm flex items-center justify-center hover:bg-green-500 hover:text-white transition">
                    <i class="fab fa-whatsapp"></i>
                </a>
                @else
                <button class="w-10 h-10 rounded-xl bg-gray-100 border border-gray-200 text-gray-400 shadow-sm flex items-center justify-center cursor-not-allowed">
                    <i class="fab fa-whatsapp"></i>
                </button>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('matchmaking.index') }}" class="text-green-500 font-semibold hover:underline">
                <i class="fas fa-redo mr-1"></i> Ulangi Kuesioner
            </a>
        </div>
    </main>
    
    <!-- Profile Modal -->
    <div id="profileModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 relative">
            <button onclick="closeProfile()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="text-center mb-6">
                <img id="profileAvatar" src="" class="w-24 h-24 rounded-full mx-auto mb-3 border-4 border-green-100">
                <h2 id="profileName" class="text-2xl font-bold text-gray-800"></h2>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600 flex-shrink-0">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500">Nomor WhatsApp</p>
                        <p id="profilePhone" class="font-semibold text-gray-800"></p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500">Jurusan</p>
                        <p id="profileMajor" class="font-semibold text-gray-800"></p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 flex-shrink-0">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500">Email</p>
                        <p id="profileEmail" class="font-semibold text-gray-800 break-all"></p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center text-yellow-600 flex-shrink-0">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500">Kampus</p>
                        <p id="profileCampus" class="font-semibold text-gray-800"></p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600 flex-shrink-0">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500">Angkatan</p>
                        <p id="profileYear" class="font-semibold text-gray-800"></p>
                    </div>
                </div>
            </div>
            
            <a id="profileWhatsApp" href="" target="_blank" class="mt-6 w-full bg-green-500 text-white py-3 rounded-xl font-semibold hover:bg-green-600 transition flex items-center justify-center gap-2">
                <i class="fab fa-whatsapp text-lg"></i>
                Chat di WhatsApp
            </a>
        </div>
    </div>
    
    <script>
        const users = {!! json_encode($matches->map(function($match) {
            $partner = $match->user1_id == auth()->id() ? $match->user2 : $match->user1;
            return [
                'id' => $partner->id,
                'name' => $partner->name,
                'phone' => $partner->phone,
                'major' => $partner->major,
                'email' => $partner->email,
                'campus' => $partner->campus,
                'year' => $partner->year
            ];
        })) !!};
        
        function showProfile(userId) {
            const user = users.find(u => u.id === userId);
            if (!user) return;
            
            document.getElementById('profileAvatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=random&color=fff&size=200`;
            document.getElementById('profileName').textContent = user.name;
            document.getElementById('profilePhone').textContent = user.phone || 'Tidak tersedia';
            document.getElementById('profileMajor').textContent = user.major || 'Tidak tersedia';
            document.getElementById('profileEmail').textContent = user.email || 'Tidak tersedia';
            document.getElementById('profileCampus').textContent = user.campus || 'Tidak tersedia';
            document.getElementById('profileYear').textContent = user.year || 'Tidak tersedia';
            
            const waBtn = document.getElementById('profileWhatsApp');
            if (user.phone) {
                const cleanPhone = user.phone.replace(/[^0-9]/g, '');
                waBtn.href = `https://wa.me/${cleanPhone}`;
                waBtn.classList.remove('hidden');
            } else {
                waBtn.classList.add('hidden');
            }
            
            document.getElementById('profileModal').classList.remove('hidden');
        }
        
        function closeProfile() {
            document.getElementById('profileModal').classList.add('hidden');
        }
        
        function toggleVisibility(visible) {
            fetch('{{ route("matchmaking.toggle-visibility") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    kost_id: {{ $kost->id }},
                    is_visible: visible
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profil Anda sekarang terlihat oleh penghuni lain!');
                    location.reload();
                }
            });
        }
    </script>
</body>
</html>
