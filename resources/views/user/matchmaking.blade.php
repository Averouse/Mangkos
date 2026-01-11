<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pencocokan - Mangkos</title>
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
                        'slide-in': 'slideIn 0.4s ease-out forwards',
                        'bounce-slow': 'bounce 3s infinite',
                    },
                    keyframes: {
                        slideIn: { '0%': { transform: 'translateX(20px)', opacity: '0' }, '100%': { transform: 'translateX(0)', opacity: '1' } }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .step-panel { display: none; }
        .step-panel.active { display: block; animation: slideIn 0.4s ease-out; }
        
        /* Custom Range Slider Styling */
        input[type=range] { -webkit-appearance: none; width: 100%; background: transparent; }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none; height: 28px; width: 28px; border-radius: 50%;
            background: #10b981; cursor: pointer; margin-top: -12px; 
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.4); border: 2px solid white;
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%; height: 6px; cursor: pointer; background: #e5e7eb; border-radius: 3px;
        }
        input[type=range]:focus { outline: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 h-screen flex flex-col overflow-hidden">

    <!-- ========================================== -->
    <!-- NAVBAR (TOP) -->
    <!-- ========================================== -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
            
            <!-- PERBAIKAN: Logo sekarang bisa diklik (kembali ke index.html) -->
            <a href="{{ route('landing') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                <div class="w-8 h-8 bg-mangkos-main rounded-lg flex items-center justify-center text-white font-bold shadow-sm">M</div>
                <span class="text-xl font-bold text-mangkos-dark tracking-tight">Mangkos</span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-500">
                <a href="{{ route('landing') }}" class="hover:text-mangkos-main transition">Beranda</a>
                <a href="{{ route('kost.search') }}" class="hover:text-mangkos-main transition">Cari di Peta</a>
                <a href="{{ route('matchmaking.index') }}" class="text-mangkos-main font-semibold border-b-2 border-mangkos-main pb-0.5">Pencocokan</a>
            </div>

            <!-- Profile Icon -->
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition">
                    <i class="fas fa-user text-sm"></i>
                </a>
            </div>
        </div>
        
        <!-- Progress Bar (Integrated) -->
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gray-100">
            <div id="progress-bar" class="h-full bg-mangkos-main transition-all duration-500 ease-out" style="width: 0%"></div>
        </div>
    </nav>

    <!-- ========================================== -->
    <!-- MAIN CONTENT (MATCHMAKING WIZARD) -->
    <!-- ========================================== -->
    <main class="flex-1 overflow-y-auto p-4 relative flex items-center justify-center">
        
        <!-- Background Elements -->
        <div class="absolute top-10 left-10 w-64 h-64 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 -z-10 animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-64 h-64 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 -z-10 animate-pulse" style="animation-delay: 1s"></div>

        <!-- FORM CONTAINER -->
        <div class="w-full max-w-md mx-auto pb-24 md:pb-0">

            <!-- KOST SELECTION -->
            @if(isset($approvedKosts) && $approvedKosts->count() > 0)
            <div class="text-center">
                <div class="bg-white p-6 rounded-3xl w-40 h-40 mx-auto shadow-xl shadow-green-100 flex items-center justify-center mb-6">
                    <i class="fas fa-home text-6xl text-mangkos-main"></i>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">Pilih <span class="text-mangkos-main">Kost</span></h1>
                <p class="text-gray-500 mb-8 text-sm leading-relaxed px-2">
                    Pilih kost untuk mencari partner yang cocok
                </p>
                
                <div class="space-y-3">
                    @foreach($approvedKosts as $rental)
                    <a href="{{ route('matchmaking.select', $rental->kost_id) }}" class="block p-4 bg-white border-2 border-gray-100 rounded-2xl hover:border-mangkos-main hover:bg-green-50 transition">
                        <h3 class="font-bold text-gray-800">{{ $rental->kost->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $rental->kost->address }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
            @else

            <!-- STEP 0: INTRO -->
            <div id="step-0" class="step-panel active text-center">
                <div class="bg-white p-6 rounded-3xl w-40 h-40 mx-auto shadow-xl shadow-green-100 flex items-center justify-center mb-6 animate-bounce-slow">
                    <i class="fas fa-handshake text-6xl text-mangkos-main"></i>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">Cari Partner <span class="text-mangkos-main">Ideal</span></h1>
                <p class="text-gray-500 mb-8 text-sm leading-relaxed px-2">
                    Jawab 7 pertanyaan singkat (satu per satu) agar kami bisa menganalisis kecocokan gaya hidupmu secara akurat.
                </p>
                <button onclick="goToStep(1)" class="w-full bg-mangkos-main text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-mangkos-dark transition transform hover:-translate-y-1">
                    Mulai Sekarang
                </button>
            </div>

            <!-- STEP 1: BUDGET (C1) -->
            <div id="step-1" class="step-panel">
                <div class="text-center mb-6">
                    <span class="text-xs font-bold text-mangkos-main tracking-widest uppercase mb-1 block">LANGKAH 1 DARI 7</span>
                    <h2 class="text-2xl font-bold">Rentang Biaya Kost</h2>
                </div>
                
                <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 text-center">
                    <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4 text-mangkos-main text-2xl">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <p class="text-gray-400 text-xs mb-2">Maksimal Patungan Per Bulan</p>
                    <h3 class="text-3xl font-bold text-mangkos-dark mb-8" id="budget-val">Rp 1.000.000</h3>
                    
                    <div class="px-2">
                        <input type="range" id="input-budget" min="1" max="5" value="2" step="1" oninput="updateLabel('budget', this.value)">
                        <div class="flex justify-between text-xs text-gray-400 mt-4 font-medium">
                            <span>Hemat</span>
                            <span>Sultan</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <button onclick="goToStep(0)" class="w-12 h-12 rounded-full border border-gray-200 text-gray-400 hover:bg-gray-50 flex items-center justify-center transition"><i class="fas fa-arrow-left"></i></button>
                    <button onclick="goToStep(2)" class="flex-1 bg-mangkos-main text-white rounded-2xl font-bold shadow-md hover:bg-mangkos-dark transition">Lanjut</button>
                </div>
            </div>

            <!-- STEP 2: ROKOK (C2) -->
            <div id="step-2" class="step-panel">
                <div class="text-center mb-6">
                    <span class="text-xs font-bold text-mangkos-main tracking-widest uppercase mb-1 block">LANGKAH 2 DARI 7</span>
                    <h2 class="text-2xl font-bold">Kebiasaan Merokok</h2>
                </div>
                
                <div class="grid grid-cols-1 gap-4">
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="smoke" value="yes" class="peer sr-only">
                        <div class="p-6 rounded-2xl border-2 border-gray-100 bg-white shadow-sm peer-checked:border-mangkos-main peer-checked:bg-green-50 peer-checked:shadow-md transition flex items-center gap-4 hover:border-green-200">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 peer-checked:bg-white peer-checked:text-mangkos-main">
                                <i class="fas fa-smoking text-xl"></i>
                            </div>
                            <div class="text-left">
                                <h4 class="font-bold text-gray-700">Perokok</h4>
                                <p class="text-xs text-gray-400">Saya merokok aktif</p>
                            </div>
                            <div class="ml-auto opacity-0 peer-checked:opacity-100 text-mangkos-main"><i class="fas fa-check-circle text-xl"></i></div>
                        </div>
                    </label>

                    <label class="cursor-pointer group relative">
                        <input type="radio" name="smoke" value="no" checked class="peer sr-only">
                        <div class="p-6 rounded-2xl border-2 border-gray-100 bg-white shadow-sm peer-checked:border-mangkos-main peer-checked:bg-green-50 peer-checked:shadow-md transition flex items-center gap-4 hover:border-green-200">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 peer-checked:bg-white peer-checked:text-mangkos-main">
                                <i class="fas fa-smoking-ban text-xl"></i>
                            </div>
                            <div class="text-left">
                                <h4 class="font-bold text-gray-700">Tidak Merokok</h4>
                                <p class="text-xs text-gray-400">Saya anti asap rokok</p>
                            </div>
                            <div class="ml-auto opacity-0 peer-checked:opacity-100 text-mangkos-main"><i class="fas fa-check-circle text-xl"></i></div>
                        </div>
                    </label>
                </div>

                <div class="mt-8 flex gap-4">
                    <button onclick="goToStep(1)" class="w-12 h-12 rounded-full border border-gray-200 text-gray-400 hover:bg-gray-50 flex items-center justify-center"><i class="fas fa-arrow-left"></i></button>
                    <button onclick="goToStep(3)" class="flex-1 bg-mangkos-main text-white rounded-2xl font-bold shadow-md hover:bg-mangkos-dark transition">Lanjut</button>
                </div>
            </div>

            <!-- STEP 3: KEBERSIHAN (C3) -->
            <div id="step-3" class="step-panel">
                <div class="text-center mb-6">
                    <span class="text-xs font-bold text-mangkos-main tracking-widest uppercase mb-1 block">LANGKAH 3 DARI 7</span>
                    <h2 class="text-2xl font-bold">Tingkat Kebersihan</h2>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 text-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-500 text-2xl">
                        <i class="fas fa-broom"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2" id="clean-label">Standar</h3>
                    <p class="text-gray-400 text-xs mb-8">Seberapa rapi Anda di kamar?</p>
                    
                    <div class="px-2">
                        <input type="range" id="input-clean" min="1" max="5" value="3" oninput="updateSimpleLabel('clean', this.value)">
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <button onclick="goToStep(2)" class="w-12 h-12 rounded-full border border-gray-200 text-gray-400 hover:bg-gray-50 flex items-center justify-center"><i class="fas fa-arrow-left"></i></button>
                    <button onclick="goToStep(4)" class="flex-1 bg-mangkos-main text-white rounded-2xl font-bold shadow-md hover:bg-mangkos-dark transition">Lanjut</button>
                </div>
            </div>

            <!-- STEP 4: TIDUR (C4) -->
            <div id="step-4" class="step-panel">
                <div class="text-center mb-6">
                    <span class="text-xs font-bold text-mangkos-main tracking-widest uppercase mb-1 block">LANGKAH 4 DARI 7</span>
                    <h2 class="text-2xl font-bold">Pola Tidur</h2>
                </div>

                <div class="flex gap-4">
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="sleep" value="early" class="peer sr-only">
                        <div class="aspect-square rounded-3xl border-2 border-gray-100 bg-white flex flex-col items-center justify-center p-4 hover:border-yellow-200 peer-checked:border-mangkos-main peer-checked:bg-green-50 transition shadow-sm">
                            <i class="fas fa-sun text-4xl text-yellow-400 mb-4 group-hover:scale-110 transition"></i>
                            <span class="font-bold text-gray-700">Early Bird</span>
                            <span class="text-xs text-gray-400 mt-1">Bangun Pagi</span>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="sleep" value="late" checked class="peer sr-only">
                        <div class="aspect-square rounded-3xl border-2 border-gray-100 bg-white flex flex-col items-center justify-center p-4 hover:border-indigo-200 peer-checked:border-mangkos-main peer-checked:bg-green-50 transition shadow-sm">
                            <i class="fas fa-moon text-4xl text-indigo-400 mb-4 group-hover:scale-110 transition"></i>
                            <span class="font-bold text-gray-700">Night Owl</span>
                            <span class="text-xs text-gray-400 mt-1">Suka Begadang</span>
                        </div>
                    </label>
                </div>

                <div class="mt-8 flex gap-4">
                    <button onclick="goToStep(3)" class="w-12 h-12 rounded-full border border-gray-200 text-gray-400 hover:bg-gray-50 flex items-center justify-center"><i class="fas fa-arrow-left"></i></button>
                    <button onclick="goToStep(5)" class="flex-1 bg-mangkos-main text-white rounded-2xl font-bold shadow-md hover:bg-mangkos-dark transition">Lanjut</button>
                </div>
            </div>

            <!-- STEP 5: NOISE (C5) -->
            <div id="step-5" class="step-panel">
                <div class="text-center mb-6">
                    <span class="text-xs font-bold text-mangkos-main tracking-widest uppercase mb-1 block">LANGKAH 5 DARI 7</span>
                    <h2 class="text-2xl font-bold">Toleransi Suara</h2>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 text-center">
                    <div class="w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center mx-auto mb-4 text-purple-500 text-2xl">
                        <i class="fas fa-volume-up"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2" id="noise-label">Biasa Saja</h3>
                    <p class="text-gray-400 text-xs mb-8">Apa Anda terganggu dengan musik/game?</p>
                    
                    <div class="px-2">
                        <input type="range" id="input-noise" min="1" max="5" value="3" oninput="updateSimpleLabel('noise', this.value)">
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <button onclick="goToStep(4)" class="w-12 h-12 rounded-full border border-gray-200 text-gray-400 hover:bg-gray-50 flex items-center justify-center"><i class="fas fa-arrow-left"></i></button>
                    <button onclick="goToStep(6)" class="flex-1 bg-mangkos-main text-white rounded-2xl font-bold shadow-md hover:bg-mangkos-dark transition">Lanjut</button>
                </div>
            </div>

            <!-- STEP 6: SOSIAL (C6) -->
            <div id="step-6" class="step-panel">
                <div class="text-center mb-6">
                    <span class="text-xs font-bold text-mangkos-main tracking-widest uppercase mb-1 block">LANGKAH 6 DARI 7</span>
                    <h2 class="text-2xl font-bold">Interaksi Sosial</h2>
                </div>

                <div class="space-y-3">
                    <label class="flex items-center p-4 bg-white border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-green-200 transition">
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-700">Introvert (Privat)</h4>
                            <p class="text-xs text-gray-400">Jarang bawa tamu, butuh *me time*</p>
                        </div>
                        <input type="radio" name="social" value="introvert" class="w-6 h-6 text-mangkos-main focus:ring-mangkos-main accent-mangkos-main">
                    </label>
                    <label class="flex items-center p-4 bg-white border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-green-200 transition">
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-700">Netral (Ambivert)</h4>
                            <p class="text-xs text-gray-400">Tamu boleh datang sesekali</p>
                        </div>
                        <input type="radio" name="social" value="ambivert" checked class="w-6 h-6 text-mangkos-main focus:ring-mangkos-main accent-mangkos-main">
                    </label>
                    <label class="flex items-center p-4 bg-white border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-green-200 transition">
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-700">Ekstrovert (Sosial)</h4>
                            <p class="text-xs text-gray-400">Sering hangout & bawa teman</p>
                        </div>
                        <input type="radio" name="social" value="extrovert" class="w-6 h-6 text-mangkos-main focus:ring-mangkos-main accent-mangkos-main">
                    </label>
                </div>

                <div class="mt-8 flex gap-4">
                    <button onclick="goToStep(5)" class="w-12 h-12 rounded-full border border-gray-200 text-gray-400 hover:bg-gray-50 flex items-center justify-center"><i class="fas fa-arrow-left"></i></button>
                    <button onclick="goToStep(7)" class="flex-1 bg-mangkos-main text-white rounded-2xl font-bold shadow-md hover:bg-mangkos-dark transition">Lanjut</button>
                </div>
            </div>

            <!-- STEP 7: IBADAH (C7) -->
            <div id="step-7" class="step-panel">
                <div class="text-center mb-6">
                    <span class="text-xs font-bold text-mangkos-main tracking-widest uppercase mb-1 block">TERAKHIR</span>
                    <h2 class="text-2xl font-bold">Praktik Ibadah</h2>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 text-center mb-6">
                     <i class="fas fa-pray text-4xl text-orange-300 mb-4"></i>
                     <p class="text-sm text-gray-600 mb-6">Seberapa penting kesamaan frekuensi ibadah bagi Anda?</p>
                     
                     <div class="flex gap-4">
                         <label class="flex-1 cursor-pointer">
                            <input type="radio" name="worship" value="flexible" checked class="peer sr-only">
                            <div class="py-4 rounded-xl border-2 border-gray-100 hover:border-orange-200 peer-checked:bg-orange-50 peer-checked:border-orange-300 peer-checked:text-orange-700 transition">
                                <span class="font-bold text-sm">Fleksibel</span>
                            </div>
                         </label>
                         <label class="flex-1 cursor-pointer">
                            <input type="radio" name="worship" value="strict" class="peer sr-only">
                            <div class="py-4 rounded-xl border-2 border-gray-100 hover:border-orange-200 peer-checked:bg-orange-50 peer-checked:border-orange-300 peer-checked:text-orange-700 transition">
                                <span class="font-bold text-sm">Prioritas</span>
                            </div>
                         </label>
                     </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <button onclick="goToStep(6)" class="w-12 h-12 rounded-full border border-gray-200 text-gray-400 hover:bg-gray-50 flex items-center justify-center"><i class="fas fa-arrow-left"></i></button>
                    <button onclick="submitProfile()" class="flex-1 bg-mangkos-main text-white rounded-2xl font-bold shadow-lg hover:bg-mangkos-dark transition transform hover:scale-105">
                        Analisis Hasil <i class="fas fa-magic ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- LOADING SCREEN -->
            <div id="step-loading" class="step-panel text-center pt-10">
                <div class="relative w-32 h-32 mx-auto mb-6">
                    <div class="absolute inset-0 border-4 border-gray-200 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-mangkos-main rounded-full border-t-transparent animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center text-4xl animate-pulse">🤖</div>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Menghitung Kecocokan...</h2>
                <p class="text-gray-500 mt-2 text-sm">Memproses 7 kriteria AHP-TOPSIS...</p>
            </div>

            <!-- RESULT SCREEN -->
            <div id="step-result" class="step-panel pb-20">
                <div class="text-center mb-6">
                    <div class="inline-block px-4 py-1 bg-green-100 text-mangkos-dark rounded-full text-xs font-bold mb-2">
                        <i class="fas fa-check-circle"></i> Hasil Rekomendasi
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Top 6 <span class="text-mangkos-main">Match</span></h2>
                </div>

                <!-- Container Hasil -->
                <div id="results-container" class="space-y-4">
                    <!-- Javascript akan mengisi ini -->
                </div>

                <div class="mt-8 text-center pb-8">
                    <button onclick="location.reload()" class="text-mangkos-main font-semibold hover:underline">
                        <i class="fas fa-redo mr-1"></i> Ulangi Kuesioner
                    </button>
                </div>
            </div>

            @endif

        </div>
    </main>

    <!-- ========================================== -->
    <!-- MOBILE BOTTOM NAVBAR (DIKEMBALIKAN) -->
    <!-- ========================================== -->
    <nav class="md:hidden fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 px-6 py-3 z-50 flex justify-between items-center text-xs font-medium text-gray-400 shadow-up">
        <a href="{{ route('landing') }}" class="flex flex-col items-center gap-1 hover:text-mangkos-main transition">
            <i class="fas fa-home text-lg"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('kost.search') }}" class="flex flex-col items-center gap-1 hover:text-mangkos-main transition">
            <i class="fas fa-map-marked-alt text-lg"></i>
            <span>Peta</span>
        </a>
        
        <!-- Tombol Match Menonjol di Tengah -->
        <div class="relative -top-6">
            <a href="{{ route('matchmaking.index') }}" class="bg-mangkos-main text-white w-14 h-14 rounded-full shadow-lg shadow-green-200 flex items-center justify-center ring-4 ring-white transform active:scale-95 transition">
                <i class="fas fa-search text-xl"></i>
            </a>
        </div>

        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 hover:text-mangkos-main transition">
            <i class="fas fa-user text-lg"></i>
            <span>Akun</span>
        </a>
    </nav>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        const kostId = {{ $kostId ?? 'null' }};
        
        function submitProfile() {
            goToStep('loading');
            
            const preferences = {
                kost_id: kostId,
                budget: parseInt(document.getElementById('input-budget').value),
                smoke: document.querySelector('input[name="smoke"]:checked').value,
                clean: parseInt(document.getElementById('input-clean').value),
                sleep: document.querySelector('input[name="sleep"]:checked').value,
                noise: parseInt(document.getElementById('input-noise').value),
                social: document.querySelector('input[name="social"]:checked').value,
                worship: document.querySelector('input[name="worship"]:checked').value
            };
            
            fetch('{{ route("matchmaking.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(preferences)
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    window.location.href = '{{ route("matchmaking.results", ":kostId") }}'.replace(':kostId', kostId);
                }
            });
        }

        // --- LOGIKA NAVIGASI ---
        function goToStep(step) {
            // Sembunyikan semua step
            document.querySelectorAll('.step-panel').forEach(el => el.classList.remove('active'));
            // Tampilkan step target
            document.getElementById('step-' + step).classList.add('active');
            
            // Hitung Progress Bar (Total 7 Langkah Input)
            // Langkah 0 = 0%
            // Langkah 1 = 14%
            // Langkah 7 = 100%
            let percent = 0;
            if (typeof step === 'number' && step > 0) {
                percent = (step / 7) * 100;
            }
            if (step === 'loading' || step === 'result') percent = 100;
            
            document.getElementById('progress-bar').style.width = percent + '%';
        }

        // Helper Update Label Slider
        function updateLabel(type, val) {
            const budgetLabels = ["< 500rb", "500rb - 1jt", "1jt - 1.5jt", "1.5jt - 2jt", "> 2jt"];
            if(type === 'budget') document.getElementById('budget-val').innerText = budgetLabels[val-1];
        }

        function updateSimpleLabel(type, val) {
            let text = "";
            if(type === 'clean') {
                const arr = ["Berantakan", "Agak Berantakan", "Standar", "Rapi", "Sangat Resik"];
                text = arr[val-1];
                document.getElementById('clean-label').innerText = text;
            }
            if(type === 'noise') {
                const arr = ["Butuh Sunyi", "Suka Tenang", "Biasa Saja", "Bisa Toleransi", "Suka Rame"];
                text = arr[val-1];
                document.getElementById('noise-label').innerText = text;
            }
        }
    </script>
</body>
</html>