<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Terbatas - Mangkos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center">
        <div class="bg-white rounded-3xl shadow-xl p-8">
            @if($reason === 'identity')
                <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-id-card text-4xl text-yellow-600"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-3">Verifikasi Identitas Diperlukan</h1>
            @else
                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-home text-4xl text-blue-600"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-3">Belum Memiliki Kost</h1>
            @endif
            
            <p class="text-gray-600 mb-8 leading-relaxed">{{ $message }}</p>
            
            @if($reason === 'identity')
                <a href="{{ route('dashboard') }}" class="inline-block bg-yellow-500 text-white font-semibold px-8 py-3 rounded-xl hover:bg-yellow-600 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('kost.search') }}" class="inline-block bg-blue-500 text-white font-semibold px-8 py-3 rounded-xl hover:bg-blue-600 transition">
                    <i class="fas fa-search mr-2"></i>Cari Kost
                </a>
            @endif
        </div>
    </div>
</body>
</html>
