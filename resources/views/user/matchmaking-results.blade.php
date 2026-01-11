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
        <div class="bg-white rounded-3xl p-8 text-center shadow-sm">
            <i class="fas fa-users text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Match</h3>
            <p class="text-gray-500 text-sm">Belum ada penghuni lain yang mengisi profil matchmaking di kost ini.</p>
        </div>
        @else
        <div class="space-y-4">
            @foreach($matches as $match)
            @php
                $partner = $match->user1_id == auth()->id() ? $match->user2 : $match->user1;
                $score = round($match->compatibility_score);
                $borderClass = $loop->first ? 'border-green-500 bg-green-50 shadow-md' : 'border-gray-100 bg-white';
                $badgeColor = $score >= 80 ? 'bg-green-500' : ($score >= 60 ? 'bg-blue-500' : 'bg-yellow-500');
            @endphp
            <div class="p-4 rounded-2xl border-2 {{ $borderClass }} shadow-sm flex gap-4 items-center">
                <div class="relative">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($partner->name) }}&background=random&color=fff" class="w-16 h-16 rounded-full border-2 border-white shadow">
                    <div class="absolute -bottom-1 -right-1 {{ $badgeColor }} text-white text-xs font-bold px-2 py-0.5 rounded-full border-2 border-white">
                        {{ $score }}%
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-800">{{ $partner->name }}</h3>
                    <p class="text-xs text-gray-500">{{ $partner->major ?? 'Mahasiswa' }}</p>
                    <div class="flex flex-wrap gap-1 mt-2">
                        <span class="bg-white border border-gray-200 text-gray-600 text-xs px-2 py-0.5 rounded-full">{{ $partner->campus }}</span>
                    </div>
                </div>
                <button class="w-10 h-10 rounded-xl bg-white border border-gray-100 text-green-500 shadow-sm flex items-center justify-center hover:bg-green-500 hover:text-white transition">
                    <i class="fas fa-comment-dots"></i>
                </button>
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
</body>
</html>
