<!DOCTYPE html>
<html lang="id" x-data="{ mobileMenu: false, isLoggedIn: @json(Auth::check()) }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MangKos - Aplikasi pencarian kos untuk mahasiswa dengan fitur matchmaking teman sekamar">
    <title>@yield('title', 'MangKos - Cari Kos & Teman Sekamar')</title>
    
    {{-- Tailwind CSS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- AlpineJS CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- Font Awesome Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800">
    
    {{-- Navbar --}}
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            {{-- Logo --}}
            <a href="/" class="flex items-center space-x-2">
                {{-- Ganti dengan path logo Anda --}}
                <img src="{{ asset('images/logo-mangkos.png') }}" alt="MangKos Logo" class="h-8 w-auto">
                <span class="text-2xl font-bold text-emerald-600">MangKos</span>
            </a>
            
            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center space-x-8">
                <a href="/search" class="text-gray-700 hover:text-emerald-600 font-medium transition">Cari Kos</a>
                @auth
                    <a href="/user/main" class="text-gray-700 hover:text-emerald-600 font-medium transition">Dashboard</a>
                    <a href="/user/profile" class="text-gray-700 hover:text-emerald-600 font-medium transition">Profil</a>
                @endauth
            </div>

            {{-- Auth Buttons & Mobile Toggle --}}
            <div class="flex items-center space-x-3">
                @guest
                    <a href="/login" class="text-gray-700 hover:text-emerald-600 font-medium transition">Login</a>
                    <a href="/register" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 font-medium transition">
                        Daftar
                    </a>
                @else
                    <span class="text-gray-700 font-medium hidden sm:inline">Halo, {{ Auth::user()->name }}</span>
                    <form method="POST" action="/logout" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium transition">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                @endguest
                
                {{-- Mobile Menu Button --}}
                <button @click="mobileMenu = !mobileMenu" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Menu Dropdown --}}
        <div x-show="mobileMenu" @click.outside="mobileMenu = false" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="md:hidden bg-white border-t shadow-lg">
            <div class="container mx-auto px-4 py-3 space-y-3">
                <a href="/search" class="block text-gray-700 hover:text-emerald-600 font-medium">Cari Kos</a>
                @auth
                    <a href="/user/main" class="block text-gray-700 hover:text-emerald-600 font-medium">Dashboard</a>
                    <a href="/user/profile" class="block text-gray-700 hover:text-emerald-600 font-medium">Profil</a>
                    <form method="POST" action="/logout" class="block">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium">Logout</button>
                    </form>
                @else
                    <a href="/login" class="block text-gray-700 hover:text-emerald-600 font-medium">Login</a>
                    <a href="/register" class="block bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 font-medium text-center">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-emerald-600 text-white py-8 mt-16">
        <div class="container mx-auto px-4 text-center">
            <div class="mb-4">
                <a href="/" class="text-2xl font-bold">
                    <img src="{{ asset('images/logo-mangkos-white.png') }}" alt="MangKos Logo" class="h-8 w-auto mx-auto">
                </a>
            </div>
            <p class="text-emerald-100">&copy; 2025 MangKos. Dibuat dengan ❤️ untuk Mahasiswa Indonesia.</p>
            <p class="text-sm text-emerald-200 mt-2">Temukan kos ideal & teman sekamar yang cocok.</p>
            
            {{-- Hidden Admin Link --}}
            <div class="mt-6">
                <a href="/admin/login" class="text-emerald-200 text-xs hover:text-white underline opacity-70">
                    Portal Admin
                </a>
            </div>
        </div>
    </footer>

</body>
</html>