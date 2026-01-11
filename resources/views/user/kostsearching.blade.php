<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta & Pencarian - Mangkos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

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
        .scroller::-webkit-scrollbar { width: 6px; }
        .scroller::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 4px; }
        .scroller::-webkit-scrollbar-track { background-color: #f1f5f9; }
        #map { min-height: 100%; height: 100%; }
        .leaflet-container { background: #f6faf6; }
        .filter-type-btn { background: white; color: #6b7280; border-color: #e5e7eb; }
        .filter-type-btn.active { background: #10b981; color: white; border-color: #10b981; }
        .filter-type-btn:hover:not(.active) { background: #f3f4f6; }
        
        .lazy-image { opacity: 0; transition: opacity 0.3s; }
        .lazy-image.loaded { opacity: 1; }
        .lazy-image:not(.loaded) { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: loading 1.5s infinite; }
        @keyframes loading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
        
        @media (max-width: 768px) {
            #mapView.hidden-mobile { display: none !important; }
            #listView.hidden-mobile { display: none !important; }
            #mapView.show-mobile { display: block !important; height: calc(100vh - 140px) !important; }
            #listView.show-mobile { display: block !important; height: calc(100vh - 140px) !important; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 h-screen flex flex-col overflow-hidden">

    <nav class="bg-white border-b border-gray-200 z-50 shrink-0 shadow-sm">
        <div class="px-4 py-3 flex justify-between items-center max-w-7xl mx-auto w-full">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                     <div class="w-8 h-8 bg-mangkos-main rounded-lg flex items-center justify-center text-white font-bold">M</div>
                     <span class="text-xl font-bold text-mangkos-dark tracking-tight hidden lg:block">Mangkos</span>
                </a>
            </div>
            
            <div class="flex-1 max-w-md mx-4 hidden md:block">
                <div class="relative group">
                    <input type="text" class="w-full bg-gray-100 border-transparent focus:bg-white focus:border-mangkos-main border rounded-full py-2 pl-10 pr-4 text-sm transition outline-none" placeholder="Cari kost...">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div id="selectedCampusInfo" class="hidden items-center gap-2 px-3 py-2 bg-green-100 text-green-700 rounded-lg text-sm">
                    <i class="fas fa-map-marker-alt"></i>
                    <span id="selectedCampusName" class="font-medium"></span>
                    <button onclick="clearCampusSelection()" class="ml-1 hover:text-green-900" title="Hapus pilihan">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <button id="universityBtn" class="flex items-center gap-2 px-3 py-2 bg-blue-100 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-200 transition">
                    <i class="fas fa-university"></i>
                    <span class="hidden sm:inline">Pilih Kampus</span>
                </button>
                <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition">
                    <i class="fas fa-user text-sm"></i>
                </a>
            </div>
        </div>
    </nav>

    <div class="flex flex-col md:flex-row flex-1 overflow-hidden relative pb-[70px] md:pb-0">
        
        <!-- Mobile View Toggle Button -->
        <button id="viewToggleBtn" class="md:hidden fixed bottom-20 right-4 z-30 w-12 h-12 bg-mangkos-main text-white rounded-full shadow-lg flex items-center justify-center" onclick="toggleView()">
            <i id="toggleIcon" class="fas fa-map"></i>
        </button>
        
        <div id="mapView" class="order-1 md:order-2 w-full h-[35vh] md:h-full md:w-[45%] bg-gray-200 relative shrink-0">
             <div id="map" class="w-full h-full rounded-sm"></div>
             
             <!-- Map Legend -->
             <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur-sm rounded-lg shadow-lg p-3 z-[1000] text-xs">
                <div class="font-bold text-gray-700 mb-2">Legenda Peta</div>
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-gray-600">Lokasi Kost</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-gray-600">Lokasi Kampus</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-gray-600">Lokasi yang Dipilih</span>
                    </div>
                </div>
             </div>
        </div>

        <div id="listView" class="order-2 md:order-1 w-full md:w-[55%] h-full overflow-y-auto scroller bg-white p-4 rounded-t-3xl md:rounded-none shadow-[0_-5px_15px_rgba(0,0,0,0.1)] md:shadow-none z-10 -mt-4 md:mt-0">
            
            <div class="flex justify-between items-center mb-4 sticky top-0 bg-white py-2 z-20">
                <p class="text-sm text-gray-500">Menampilkan <span id="kostCount" class="font-bold text-gray-800">{{ $kosts->count() }} Kost</span> tersedia</p>
                <div class="flex gap-2">
                    <select id="sortSelect" onchange="sortKosts()" class="text-xs px-3 py-1.5 border border-gray-300 rounded-lg focus:border-mangkos-main outline-none">
                        <option value="default">Urutkan</option>
                        <option value="price-low">Harga Terendah</option>
                        <option value="price-high">Harga Tertinggi</option>
                        <option value="distance" id="sortDistance" disabled>Jarak Terdekat</option>
                        <option value="newest">Terbaru</option>
                    </select>
                    <button onclick="toggleFilter()" class="text-mangkos-main text-sm font-medium flex items-center gap-1">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>

            <!-- Filter Section -->
            <div id="filterSection" class="hidden mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Type Filter -->
                    <div>
                        <label class="text-xs font-bold text-gray-600 mb-2 block">Tipe Kost</label>
                        <div class="flex gap-2">
                            <button onclick="filterByType('all')" class="filter-type-btn active flex-1 px-3 py-2 text-xs rounded-lg border transition">
                                Semua
                            </button>
                            <button onclick="filterByType('putra')" class="filter-type-btn flex-1 px-3 py-2 text-xs rounded-lg border transition">
                                Putra
                            </button>
                            <button onclick="filterByType('putri')" class="filter-type-btn flex-1 px-3 py-2 text-xs rounded-lg border transition">
                                Putri
                            </button>
                            <button onclick="filterByType('campur')" class="filter-type-btn flex-1 px-3 py-2 text-xs rounded-lg border transition">
                                Campur
                            </button>
                        </div>
                    </div>
                    
                    <!-- Price Filter -->
                    <div>
                        <label class="text-xs font-bold text-gray-600 mb-2 block">Harga per Bulan</label>
                        <select id="priceFilter" onchange="applyFilters()" class="w-full px-3 py-2 text-xs rounded-lg border border-gray-300 focus:border-mangkos-main outline-none">
                            <option value="all">Semua Harga</option>
                            <option value="0-500000">< Rp 500rb</option>
                            <option value="500000-1000000">Rp 500rb - 1jt</option>
                            <option value="1000000-1500000">Rp 1jt - 1.5jt</option>
                            <option value="1500000-2000000">Rp 1.5jt - 2jt</option>
                            <option value="2000000-999999999">> Rp 2jt</option>
                        </select>
                    </div>
                </div>
                
                <!-- Facilities Filter -->
                <div class="mt-4">
                    <label class="text-xs font-bold text-gray-600 mb-2 block">Fasilitas</label>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                        <label class="flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" class="facility-checkbox" value="AC" onchange="applyFilters()">
                            <span>AC</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" class="facility-checkbox" value="Wifi" onchange="applyFilters()">
                            <span>WiFi</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" class="facility-checkbox" value="kamar_mandi_dalam" onchange="applyFilters()">
                            <span>K. Mandi Dalam</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" class="facility-checkbox" value="kamar_mandi_luar" onchange="applyFilters()">
                            <span>K. Mandi Luar</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" class="facility-checkbox" value="Dapur" onchange="applyFilters()">
                            <span>Dapur</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" class="facility-checkbox" value="Parkir" onchange="applyFilters()">
                            <span>Parkir</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" class="facility-checkbox" value="Laundry" onchange="applyFilters()">
                            <span>Laundry</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" class="facility-checkbox" value="ruang_tamu" onchange="applyFilters()">
                            <span>Ruang Tamu</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" class="facility-checkbox" value="Security" onchange="applyFilters()">
                            <span>Security</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" class="facility-checkbox" value="bebas_jam_malam" onchange="applyFilters()">
                            <span>Bebas Jam Malam</span>
                        </label>
                    </div>
                </div>
                
                <button onclick="resetFilters()" class="mt-3 text-xs text-red-500 hover:text-red-600 font-medium">
                    <i class="fas fa-redo"></i> Reset Filter
                </button>
            </div>
            
            <!-- No Results Message -->
            <div id="noResults" class="hidden mb-4 p-8 bg-gray-50 rounded-lg border border-gray-200 text-center">
                <i class="fas fa-search text-4xl text-gray-300 mb-3"></i>
                <h3 class="text-lg font-bold text-gray-700 mb-2">Tidak Ada Hasil</h3>
                <p class="text-sm text-gray-500">Tidak ada kost yang sesuai dengan filter Anda. Coba ubah kriteria pencarian.</p>
            </div>

            <div id="kostGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-10">
                @foreach($kosts as $kost)
                <a href="{{ route('kost.detail', $kost->id) }}" class="kost-card block group cursor-pointer" data-type="{{ $kost->type }}" data-price="{{ $kost->price }}" data-facilities='@json($kost->facilities ?? [])' data-created="{{ $kost->created_at }}" data-lat="{{ $kost->latitude ?? '' }}" data-lng="{{ $kost->longitude ?? '' }}">
                    <div class="flex md:block border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition bg-white">
                        <div class="w-32 md:w-full h-32 md:h-48 bg-gray-200 shrink-0 relative">
                                @if($kost->photos && count($kost->photos) > 0)
                                    <img data-src="/uploads/kosts/{{ $kost->photos[0] }}" class="lazy-image w-full h-full object-cover" alt="{{ $kost->name }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                        <i class="fas fa-building text-gray-500 text-2xl"></i>
                                    </div>
                                @endif
                                <div class="absolute top-2 left-2 bg-white/90 px-2 py-0.5 rounded text-[10px] font-bold uppercase">{{ ucfirst($kost->type) }}</div>
                                @if($kost->is_full)
                                    <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-0.5 rounded text-[10px] font-bold uppercase">PENUH</div>
                                @endif
                            </div>
                            <div class="p-3 flex flex-col justify-between w-full">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm md:text-base truncate">{{ $kost->name }}</h3>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-1">
                                        @if($kost->facilities && count($kost->facilities) > 0)
                                            {{ implode(' • ', array_slice($kost->facilities, 0, 3)) }}
                                        @else
                                            {{ Str::limit($kost->address, 30) }}
                                        @endif
                                    </p>
                                    <div class="distance-badge hidden mt-1"></div>
                                </div>
                                <div class="mt-2 flex justify-between items-end">
                                    <span class="text-mangkos-dark font-bold text-sm md:text-base">Rp {{ number_format($kost->price/1000, 0) }}rb</span>
                                    <span class="text-[10px] text-gray-400">{{ $kost->total_rooms }} kamar</span>
                                </div>
                            </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- University Modal -->
    <div id="universityModal" class="fixed inset-0 z-50 hidden bg-black/30 backdrop-blur-sm">
        <div class="absolute left-8 top-1/2 transform -translate-y-1/2 w-full max-w-md">
            <div class="bg-white rounded-2xl p-6 shadow-2xl max-h-[80vh] overflow-y-auto">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Pilih Kampus</h3>
                <div class="space-y-2 mb-4">
                    <div onclick="setUniversityLocation(-6.87386896090616, 107.57789228725451, 'ULBI')" class="p-3 hover:bg-gray-100 cursor-pointer border border-gray-200 rounded-lg">
                        <div class="font-medium text-sm">Universitas Logistik dan Bisnis Internasional (ULBI)</div>
                        <div class="text-xs text-gray-500">Jl. Sariasih No.54, Sarijadi, Kec. Sukasari, Kota Bandung, Jawa Barat 40151</div>
                    </div>
                    <div onclick="setUniversityLocation(-6.861034993095234, 107.592083394514, 'UPI')" class="p-3 hover:bg-gray-100 cursor-pointer border border-gray-200 rounded-lg">
                        <div class="font-medium text-sm">Universitas Pendidikan Indonesia (UPI)</div>
                        <div class="text-xs text-gray-500">Jl. Dr. Setiabudi No.229, Isola, Kec. Sukasari, Kota Bandung, Jawa Barat 40154</div>
                    </div>
                    <div onclick="setUniversityLocation(-6.871312541799948, 107.57368658336404, 'POLBAN')" class="p-3 hover:bg-gray-100 cursor-pointer border border-gray-200 rounded-lg">
                        <div class="font-medium text-sm">Politeknik Negeri Bandung (POLBAN)</div>
                        <div class="text-xs text-gray-500">Jl. Ciwaruga, Ciwaruga, Kec. Parongpong, Kabupaten Bandung Barat, Jawa Barat 40559</div>
                    </div>
                    <div onclick="setUniversityLocation(-6.868223500786581, 107.59376536567873, 'NHI')" class="p-3 hover:bg-gray-100 cursor-pointer border border-gray-200 rounded-lg">
                        <div class="font-medium text-sm">Poltekpar NHI Bandung</div>
                        <div class="text-xs text-gray-500">Jl. Dr. Setiabudi No.186, Hegarmanah, Kec. Cidadap, Kota Bandung, Jawa Barat 40141</div>
                    </div>
                    <div onclick="setUniversityLocation(-6.884003890084608, 107.56629743208624, 'Kemenkes Gizi')" class="p-3 hover:bg-gray-100 cursor-pointer border border-gray-200 rounded-lg">
                        <div class="font-medium text-sm">Kampus B Jurusan Gizi Poltekkes Bandung</div>
                        <div class="text-xs text-gray-500">Jl. Babakan Loa No.10a, Pasirkaliki, Kec. Cimahi Utara, Kota Cimahi, Jawa Barat 40514</div>
                    </div>
                    <div onclick="setUniversityLocation(-6.882048255559513, 107.56674813160866, 'Kemenkes TLM')" class="p-3 hover:bg-gray-100 cursor-pointer border border-gray-200 rounded-lg">
                        <div class="font-medium text-sm">Jurusan Analis Kesehatan (TLM) Poltekkes Bandung</div>
                        <div class="text-xs text-gray-500">Jl. Babakan Loa, Pasirkaliki, Kec. Cimahi Utara, Kota Cimahi, Jawa Barat 40514</div>
                    </div>
                    <div onclick="setUniversityLocation(-6.877182398585708, 107.58897396729976, 'STIEPAR YAPARI')" class="p-3 hover:bg-gray-100 cursor-pointer border border-gray-200 rounded-lg">
                        <div class="font-medium text-sm">Sekolah Tinggi Ilmu Ekonomi Pariwisata YAPARI (STIEPAR YAPARI)</div>
                        <div class="text-xs text-gray-500">Jl. Prof. Dr. Sutami No.81-83, Sukarasa, Kec. Sukasari, Kota Bandung, Jawa Barat 40163</div>
                    </div>
                    <div onclick="setUniversityLocation(-6.885715465364036, 107.58057700765367, 'Marnat')" class="p-3 hover:bg-gray-100 cursor-pointer border border-gray-200 rounded-lg">
                        <div class="font-medium text-sm">Universitas Kristen Maranatha</div>
                        <div class="text-xs text-gray-500">Jl. Prof. drg. Soeria Soemantri No.65, Sukawarna, Kec. Sukajadi, Kota Bandung, Jawa Barat 40164</div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button onclick="closeUniversityModal()" class="flex-1 bg-gray-100 text-gray-600 py-2 rounded-lg">Batal</button>
                    <button onclick="useCurrentLocation()" class="flex-1 bg-mangkos-main text-white py-2 rounded-lg">Gunakan Lokasi Saya</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

    <script>
    // Initialize map with default location (Bandung) - lazy load
        let map = L.map('map', {
            preferCanvas: true,
            zoomControl: true,
            attributionControl: false
        }).setView([-6.8925, 107.6110], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            minZoom: 11,
            updateWhenIdle: true,
            updateWhenZooming: false,
            keepBuffer: 2
        }).addTo(map);

    // Store user location globally
        let userLocation = null;

    // Create custom red icon for user location
        const redIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        
        const blueIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        
        const greenIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

    // University modal functions
        document.getElementById('universityBtn').addEventListener('click', function() {
            document.getElementById('universityModal').classList.remove('hidden');
        });

        function closeUniversityModal() {
            document.getElementById('universityModal').classList.add('hidden');
        }



        function setUniversityLocation(lat, lng, name) {
            userLocation = {lat: lat, lng: lng};
            map.setView([lat, lng], 14);
            
        // Remove existing user marker
            map.eachLayer(function(layer) {
                if (layer.options && layer.options.icon === redIcon) {
                    map.removeLayer(layer);
                }
            });
            
        // Add university marker
            L.marker([lat, lng], {icon: redIcon})
                .addTo(map)
                .bindPopup(`<div class="text-center"><strong>🎓 ${name}</strong></div>`)
                .openPopup();
            
        // Show selected campus in navbar
            document.getElementById('selectedCampusName').textContent = name;
            document.getElementById('selectedCampusInfo').classList.remove('hidden');
            document.getElementById('selectedCampusInfo').classList.add('flex');
            
        // Enable distance sort option
            document.getElementById('sortDistance').disabled = false;
            
        // Refresh kost markers with new distances
            refreshKostMarkers();
            closeUniversityModal();
        }
        
        function clearCampusSelection() {
            userLocation = null;
            
        // Remove red marker
            map.eachLayer(function(layer) {
                if (layer.options && layer.options.icon === redIcon) {
                    map.removeLayer(layer);
                }
            });
            
        // Hide campus info in navbar
            document.getElementById('selectedCampusInfo').classList.add('hidden');
            document.getElementById('selectedCampusInfo').classList.remove('flex');
            
        // Disable distance sort
            document.getElementById('sortDistance').disabled = true;
            document.getElementById('sortSelect').value = 'default';
            
        // Hide distance badges
            document.querySelectorAll('.distance-badge').forEach(badge => {
                badge.classList.add('hidden');
            });
            
        // Refresh markers
            refreshKostMarkers();
            
        // Reset map view
            map.setView([-6.8925, 107.6110], 13);
        }

        function useCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        setUniversityLocation(position.coords.latitude, position.coords.longitude, 'Lokasi Anda');
                    },
                    function(error) {
                        console.error('Geolocation error:', error);
                        alert('Tidak dapat mengakses lokasi Anda. Pastikan izin lokasi diaktifkan.');
                    }
                );
            } else {
                alert('Browser Anda tidak mendukung geolocation.');
            }
            closeUniversityModal();
        }

        function refreshKostMarkers() {
            markerCluster.clearLayers();
            updateKostMarkers();
        }

        // Function to calculate distance between two points
        function calculateDistance(lat1, lng1, lat2, lng2) {
            const R = 6371; // Earth's radius in kilometers
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLng/2) * Math.sin(dLng/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c; // Distance in kilometers
        }

        // Function to add/update kost markers with clustering
        let markerCluster = L.markerClusterGroup();
        map.addLayer(markerCluster);
        
        function updateKostMarkers() {
            markerCluster.clearLayers();
            const kosts = @json($kosts);
            
            kosts.forEach(kost => {
                if (kost.latitude && kost.longitude) {
                    let distanceText = '';
                    if (userLocation) {
                        const distance = calculateDistance(
                            userLocation.lat, userLocation.lng,
                            kost.latitude, kost.longitude
                        );
                        distanceText = `<p class="text-xs text-blue-600">📍 ${distance.toFixed(1)} km</p>`;
                    }
                    
                    const marker = L.marker([kost.latitude, kost.longitude], {icon: greenIcon});
                    marker.bindPopup(`
                        <div class="p-2">
                            <h4 class="font-bold text-sm">${kost.name}</h4>
                            <p class="text-xs text-gray-600">Rp ${new Intl.NumberFormat('id-ID').format(kost.price)}</p>
                            ${distanceText}
                            <a href="/kost/${kost.id}" class="inline-block mt-2 bg-green-500 text-white px-3 py-1 rounded text-xs">
                                Lihat Detail
                            </a>
                        </div>
                    `);
                    markerCluster.addLayer(marker);
                }
            });
            
            updateDistanceBadges();
        }
        
        // Add campus markers by default
        const campuses = [
            {name: 'ULBI', lat: -6.87386896090616, lng: 107.57789228725451},
            {name: 'UPI', lat: -6.861034993095234, lng: 107.592083394514},
            {name: 'POLBAN', lat: -6.871312541799948, lng: 107.57368658336404},
            {name: 'NHI', lat: -6.868223500786581, lng: 107.59376536567873},
            {name: 'Kemenkes Gizi', lat: -6.884003890084608, lng: 107.56629743208624},
            {name: 'Kemenkes TLM', lat: -6.882048255559513, lng: 107.56674813160866},
            {name: 'STIEPAR YAPARI', lat: -6.877182398585708, lng: 107.58897396729976},
            {name: 'Maranatha', lat: -6.885715465364036, lng: 107.58057700765367}
        ];
        
        campuses.forEach(campus => {
            L.marker([campus.lat, campus.lng], {icon: blueIcon})
                .addTo(map)
                .bindPopup(`<div class="text-center font-bold text-sm">🎓 ${campus.name}</div>`);
        });
        
        // Update distance badges on cards
        function updateDistanceBadges() {
            if (!userLocation) {
                document.querySelectorAll('.distance-badge').forEach(badge => {
                    badge.classList.add('hidden');
                });
                return;
            }
            
            document.querySelectorAll('.kost-card').forEach(card => {
                const lat = parseFloat(card.dataset.lat);
                const lng = parseFloat(card.dataset.lng);
                const badge = card.querySelector('.distance-badge');
                
                if (lat && lng && badge) {
                    const distance = calculateDistance(
                        userLocation.lat, userLocation.lng,
                        lat, lng
                    );
                    badge.innerHTML = `<span class="inline-flex items-center gap-1 text-xs text-blue-600 font-medium"><i class="fas fa-map-marker-alt"></i> ${distance.toFixed(1)} km dari kampus</span>`;
                    badge.classList.remove('hidden');
                }
            });
        }

        // Initialize kost markers after a short delay
        setTimeout(() => updateKostMarkers(), 100);

        // Search functionality
        const searchInput = document.querySelector('input[type="text"]');
        const kostCards = document.querySelectorAll('.kost-card');

        searchInput.addEventListener('input', function() {
            applyFilters();
        });

        // Filter functionality
        let currentTypeFilter = 'all';
        
        function toggleFilter() {
            const filterSection = document.getElementById('filterSection');
            filterSection.classList.toggle('hidden');
        }
        
        function filterByType(type) {
            currentTypeFilter = type;
            document.querySelectorAll('.filter-type-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            applyFilters();
        }
        
        function applyFilters() {
            const searchTerm = searchInput.value.toLowerCase();
            const priceRange = document.getElementById('priceFilter').value;
            const selectedFacilities = Array.from(document.querySelectorAll('.facility-checkbox:checked')).map(cb => cb.value);
            let visibleCount = 0;
            const visibleKosts = [];
            
            kostCards.forEach(card => {
                const kostName = card.querySelector('h3').textContent.toLowerCase();
                const kostType = card.dataset.type;
                const kostPrice = parseInt(card.dataset.price);
                const kostFacilities = JSON.parse(card.dataset.facilities || '[]');
                const kostLat = parseFloat(card.dataset.lat);
                const kostLng = parseFloat(card.dataset.lng);
                
                let showCard = true;
                
                // Search filter
                if (searchTerm && !kostName.includes(searchTerm)) {
                    showCard = false;
                }
                
                // Type filter
                if (currentTypeFilter !== 'all' && kostType !== currentTypeFilter) {
                    showCard = false;
                }
                
                // Price filter
                if (priceRange !== 'all') {
                    const [min, max] = priceRange.split('-').map(Number);
                    if (kostPrice < min || kostPrice > max) {
                        showCard = false;
                    }
                }
                
                // Facilities filter - kost must have ALL selected facilities
                if (selectedFacilities.length > 0) {
                    const hasAllFacilities = selectedFacilities.every(facility => 
                        kostFacilities.some(kf => kf.toLowerCase().includes(facility.toLowerCase()))
                    );
                    if (!hasAllFacilities) {
                        showCard = false;
                    }
                }
                
                card.style.display = showCard ? 'block' : 'none';
                if (showCard) {
                    visibleCount++;
                    if (kostLat && kostLng) {
                        visibleKosts.push({lat: kostLat, lng: kostLng, name: kostName});
                    }
                }
            });
            
            document.getElementById('kostCount').textContent = `${visibleCount} Kost`;
            
            // Show/hide no results message
            const noResults = document.getElementById('noResults');
            const kostGrid = document.getElementById('kostGrid');
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
                kostGrid.classList.add('hidden');
            } else {
                noResults.classList.add('hidden');
                kostGrid.classList.remove('hidden');
            }
            
            // Update map markers to show only filtered kosts
            updateFilteredMapMarkers(visibleKosts);
        }
        
        // Update map to show only filtered kosts
        function updateFilteredMapMarkers(visibleKosts) {
            markerCluster.clearLayers();
            const kosts = @json($kosts);
            
            kosts.forEach(kost => {
                if (kost.latitude && kost.longitude) {
                    // Check if this kost is in the visible list
                    const isVisible = visibleKosts.some(vk => 
                        Math.abs(vk.lat - kost.latitude) < 0.0001 && 
                        Math.abs(vk.lng - kost.longitude) < 0.0001
                    );
                    
                    if (isVisible) {
                        let distanceText = '';
                        if (userLocation) {
                            const distance = calculateDistance(
                                userLocation.lat, userLocation.lng,
                                kost.latitude, kost.longitude
                            );
                            distanceText = `<p class="text-xs text-blue-600">📍 ${distance.toFixed(1)} km</p>`;
                        }
                        
                        const marker = L.marker([kost.latitude, kost.longitude], {icon: greenIcon});
                        marker.bindPopup(`
                            <div class="p-2">
                                <h4 class="font-bold text-sm">${kost.name}</h4>
                                <p class="text-xs text-gray-600">Rp ${new Intl.NumberFormat('id-ID').format(kost.price)}</p>
                                ${distanceText}
                                <a href="/kost/${kost.id}" class="inline-block mt-2 bg-green-500 text-white px-3 py-1 rounded text-xs">
                                    Lihat Detail
                                </a>
                            </div>
                        `);
                        markerCluster.addLayer(marker);
                    }
                }
            });
            
            updateDistanceBadges();
        }
        
        function resetFilters() {
            currentTypeFilter = 'all';
            document.getElementById('priceFilter').value = 'all';
            document.getElementById('sortSelect').value = 'default';
            document.querySelectorAll('.filter-type-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelector('.filter-type-btn').classList.add('active');
            document.querySelectorAll('.facility-checkbox').forEach(cb => cb.checked = false);
            searchInput.value = '';
            applyFilters();
        }
        
        // Sort functionality
        function sortKosts() {
            const sortValue = document.getElementById('sortSelect').value;
            const grid = document.getElementById('kostGrid');
            const cards = Array.from(document.querySelectorAll('.kost-card'));
            
            cards.sort((a, b) => {
                switch(sortValue) {
                    case 'price-low':
                        return parseInt(a.dataset.price) - parseInt(b.dataset.price);
                    case 'price-high':
                        return parseInt(b.dataset.price) - parseInt(a.dataset.price);
                    case 'distance':
                        if (!userLocation) return 0;
                        const distA = calculateDistance(
                            userLocation.lat, userLocation.lng,
                            parseFloat(a.dataset.lat), parseFloat(a.dataset.lng)
                        );
                        const distB = calculateDistance(
                            userLocation.lat, userLocation.lng,
                            parseFloat(b.dataset.lat), parseFloat(b.dataset.lng)
                        );
                        return distA - distB;
                    case 'newest':
                        return new Date(b.dataset.created) - new Date(a.dataset.created);
                    default:
                        return 0;
                }
            });
            
            // Re-append sorted cards
            cards.forEach(card => grid.appendChild(card));
        }
        
        // Mobile view toggle
        let currentView = 'list'; // 'list' or 'map'
        
        function toggleView() {
            const mapView = document.getElementById('mapView');
            const listView = document.getElementById('listView');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (window.innerWidth < 768) {
                if (currentView === 'list') {
                    // Switch to map
                    listView.classList.add('hidden-mobile');
                    mapView.classList.remove('hidden-mobile');
                    mapView.classList.add('show-mobile');
                    toggleIcon.className = 'fas fa-list';
                    currentView = 'map';
                    setTimeout(() => map.invalidateSize(), 100);
                } else {
                    // Switch to list
                    mapView.classList.add('hidden-mobile');
                    mapView.classList.remove('show-mobile');
                    listView.classList.remove('hidden-mobile');
                    toggleIcon.className = 'fas fa-map';
                    currentView = 'list';
                }
            }
        }
        
        // Swipe gesture for mobile
        let touchStartY = 0;
        let touchEndY = 0;
        
        document.addEventListener('touchstart', function(e) {
            touchStartY = e.changedTouches[0].screenY;
        }, false);
        
        document.addEventListener('touchend', function(e) {
            touchEndY = e.changedTouches[0].screenY;
            handleSwipe();
        }, false);
        
        function handleSwipe() {
            if (window.innerWidth >= 768) return; // Only on mobile
            
            const swipeDistance = touchStartY - touchEndY;
            const minSwipeDistance = 50;
            
            if (Math.abs(swipeDistance) > minSwipeDistance) {
                if (swipeDistance > 0 && currentView === 'list') {
                    // Swipe up - show map
                    toggleView();
                } else if (swipeDistance < 0 && currentView === 'map') {
                    // Swipe down - show list
                    toggleView();
                }
            }
        }
        
        // Lazy loading images
        const lazyLoadImages = () => {
            const images = document.querySelectorAll('.lazy-image:not(.loaded)');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px'
            });
            
            images.forEach(img => imageObserver.observe(img));
        };
        
        // Initialize lazy loading
        lazyLoadImages();
        
        // Infinite scroll
        let isLoading = false;
        const listView = document.getElementById('listView');
        
        listView.addEventListener('scroll', function() {
            if (isLoading) return;
            
            const scrollTop = this.scrollTop;
            const scrollHeight = this.scrollHeight;
            const clientHeight = this.clientHeight;
            
            // Load more when 200px from bottom
            if (scrollTop + clientHeight >= scrollHeight - 200) {
                // Re-apply lazy loading for newly visible images
                lazyLoadImages();
            }
        });
    </script>


</body>
</html>
