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

        /* Map container should fill its parent */
        #map { min-height: 100%; height: 100%; }
        .leaflet-container { background: #f6faf6; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 h-screen flex flex-col overflow-hidden">

    <nav class="bg-white border-b border-gray-200 z-50 shrink-0 shadow-sm">
        <div class="px-4 py-3 flex justify-between items-center max-w-7xl mx-auto w-full">
            
            <div class="flex items-center gap-8">
                <a href="index.html" class="flex items-center gap-2">
                     <div class="w-8 h-8 bg-mangkos-main rounded-lg flex items-center justify-center text-white font-bold">M</div>
                     <span class="text-xl font-bold text-mangkos-dark tracking-tight hidden lg:block">Mangkos</span>
                </a>
                
                <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-500">
                    <a href="index.html" class="hover:text-mangkos-main transition">Beranda</a>
                    <a href="search.html" class="text-mangkos-main font-semibold border-b-2 border-mangkos-main pb-0.5">Cari di Peta</a>
                    <a href="match.html" class="hover:text-mangkos-main transition">Pencocokan</a>
                    <a href="chat.html" class="hover:text-mangkos-main transition">Riwayat Chat</a>
                </div>
            </div>
            
            <div class="flex-1 max-w-md mx-4 hidden md:block">
                <div class="relative group">
                    <input type="text" value="Dago, Bandung" class="w-full bg-gray-100 border-transparent focus:bg-white focus:border-mangkos-main border rounded-full py-2 pl-10 pr-4 text-sm transition outline-none" placeholder="Cari lokasi...">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="md:hidden flex-1 mr-2">
                     <div class="bg-gray-100 rounded-full p-2 text-gray-500 text-sm flex items-center gap-2 w-48">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <span class="truncate">Cari di Dago...</span>
                     </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="profile.html" class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition">
                        <i class="fas fa-user text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="flex gap-2 px-4 py-2 border-t border-gray-100 overflow-x-auto hide-scroll bg-gray-50/50">
            <button class="px-3 py-1.5 rounded-full border border-gray-300 bg-white text-xs font-medium whitespace-nowrap">Harga</button>
            <button class="px-3 py-1.5 rounded-full border border-gray-300 bg-white text-xs font-medium whitespace-nowrap">Putra/Putri</button>
            <button class="px-3 py-1.5 rounded-full border border-gray-300 bg-white text-xs font-medium whitespace-nowrap">AC & WiFi</button>
            <button class="px-3 py-1.5 rounded-full bg-green-50 text-mangkos-dark border border-green-200 text-xs font-medium whitespace-nowrap">Booking Langsung</button>
        </div>
    </nav>

    <div class="flex flex-col md:flex-row flex-1 overflow-hidden relative pb-[70px] md:pb-0">
        
        <div class="order-1 md:order-2 w-full h-[35vh] md:h-full md:w-[45%] bg-gray-200 relative shrink-0">
             <div id="map" class="w-full h-full rounded-sm"></div>

             <button id="toggleMapBtn" class="absolute bottom-4 right-4 bg-white p-2 rounded-lg shadow-md text-gray-700 md:hidden text-xs font-bold">
                 Perbesar Peta
             </button>
        </div>

        <div class="order-2 md:order-1 w-full md:w-[55%] h-full overflow-y-auto scroller bg-white p-4 rounded-t-3xl md:rounded-none shadow-[0_-5px_15px_rgba(0,0,0,0.1)] md:shadow-none z-10 -mt-4 md:mt-0">
            
            <div class="flex justify-between items-center mb-4 sticky top-0 bg-white py-2 z-20">
                <p class="text-sm text-gray-500">Menampilkan <span class="font-bold text-gray-800">12 Kos</span> terdekat</p>
                <select class="text-xs border-none bg-gray-100 rounded px-2 py-1 outline-none">
                    <option>Termurah</option>
                    <option>Terdekat</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-10">
                
                <a href="detail.html" class="block group">
                    <div class="flex md:block border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition bg-white">
                        <div class="w-32 md:w-full h-32 md:h-48 bg-gray-200 shrink-0 relative">
                            <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover">
                            <div class="absolute top-2 left-2 bg-white/90 px-2 py-0.5 rounded text-[10px] font-bold uppercase hidden md:block">Putri</div>
                        </div>
                        <div class="p-3 flex flex-col justify-between w-full">
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm md:text-base truncate">Kos Melati Indah</h3>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-1">AC • WiFi • K.Mandi Dalam</p>
                            </div>
                            <div class="mt-2 flex justify-between items-end">
                                <span class="text-mangkos-dark font-bold text-sm md:text-base">Rp 850rb</span>
                                <span class="text-[10px] text-gray-400">0.5 km</span>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="detail.html" class="block group">
                    <div class="flex md:block border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition bg-white">
                        <div class="w-32 md:w-full h-32 md:h-48 bg-gray-200 shrink-0 relative">
                            <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover">
                        </div>
                        <div class="p-3 flex flex-col justify-between w-full">
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm md:text-base truncate">Wisma Garuda</h3>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-1">Parkir Luas • Security</p>
                            </div>
                            <div class="mt-2 flex justify-between items-end">
                                <span class="text-mangkos-dark font-bold text-sm md:text-base">Rp 1.2jt</span>
                                <span class="text-[10px] text-gray-400">1.2 km</span>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="detail.html" class="block group">
                    <div class="flex md:block border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition bg-white">
                        <div class="w-32 md:w-full h-32 md:h-48 bg-gray-200 shrink-0 relative">
                            <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover">
                        </div>
                        <div class="p-3 flex flex-col justify-between w-full">
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm md:text-base truncate">Kost Bahagia</h3>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-1">Bebas Jam Malam</p>
                            </div>
                            <div class="mt-2 flex justify-between items-end">
                                <span class="text-mangkos-dark font-bold text-sm md:text-base">Rp 900rb</span>
                                <span class="text-[10px] text-gray-400">2.0 km</span>
                            </div>
                        </div>
                    </div>
                </a>
                 </div>
        </div>
    </div>

    <nav class="md:hidden fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 px-6 py-3 z-50 flex justify-between items-center text-xs font-medium text-gray-400">
        <a href="index.html" class="flex flex-col items-center gap-1 hover:text-mangkos-main transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>Home</span>
        </a>
        <a href="search.html" class="flex flex-col items-center gap-1 text-mangkos-main">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            <span>Peta</span>
        </a>
        <div class="relative -top-5">
            <a href="match.html" class="bg-gray-100 text-gray-400 p-3 rounded-full shadow-lg border border-gray-200 flex items-center justify-center hover:text-pink-500 hover:bg-pink-50 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </a>
        </div>
        <a href="chat.html" class="flex flex-col items-center gap-1 hover:text-mangkos-main">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            <span>Chat</span>
        </a>
        <a href="profile.html" class="flex flex-col items-center gap-1 hover:text-mangkos-main">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span>Akun</span>
        </a>
    </nav>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        (function(){
            // Center around Bandung (Dago area)
            const center = [-6.8925, 107.6110];
            const map = L.map('map', {zoomControl: true}).setView(center, 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Sample/fake places around Bandung
            const places = [
                {lat:-6.8925, lon:107.6110, title:'Dago — Pusat', desc:'Simulasi: beberapa kos di area ini.'},
                {lat:-6.8940, lon:107.6090, title:'Kos Melati Indah', desc:'Rp 850rb • 0.5 km'},
                {lat:-6.9000, lon:107.6150, title:'Wisma Garuda', desc:'Rp 1.2jt • 1.2 km'},
                {lat:-6.8890, lon:107.6050, title:'Kost Bahagia', desc:'Rp 900rb • 2.0 km'}
            ];

            places.forEach(p => {
                const marker = L.marker([p.lat, p.lon]).addTo(map);
                marker.bindPopup(`<strong>${p.title}</strong><br>${p.desc}`);
            });

            // Small attribution info
            map.attributionControl.setPrefix(false);
            map.attributionControl.addAttribution('Simulasi peta: Bandung — data contoh');

            // Toggle enlarge on mobile
            const btn = document.getElementById('toggleMapBtn');
            const mapWrap = document.getElementById('map').parentElement;
            let isFull = false;
            if(btn){
                btn.addEventListener('click', () => {
                    isFull = !isFull;
                    if(isFull){
                        mapWrap.classList.add('fixed','inset-4','z-50','rounded-lg','shadow-lg','bg-white');
                        mapWrap.style.height = '90vh';
                    } else {
                        mapWrap.classList.remove('fixed','inset-4','z-50','rounded-lg','shadow-lg','bg-white');
                        mapWrap.style.height = '';
                    }
                    setTimeout(()=>map.invalidateSize(),300);
                });
            }

            // Ensure correct sizing after any layout changes
            setTimeout(()=>map.invalidateSize(),200);
        })();
    </script>

</body>
</html>