<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','User Panel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Navbar -->
    <nav class="bg-white shadow sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center h-14">
                
                {{-- Logo / Brand --}}
                <a href="{{ route('students.dashboard') }}" 
                   class="text-xl font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                   {{env('APP_NAME')}}<span class="text-gray-700">System</span>
                </a>
    
                {{-- Menu utama (desktop) --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('students.dashboard') }}" 
                       class="font-medium text-gray-700 hover:text-indigo-600 transition-colors">
                       Dashboard
                    </a>
                    <a href="{{ route('students.ujian.list') }}" 
                       class="font-medium text-gray-700 hover:text-indigo-600 transition-colors">
                       List Ujian
                    </a>

                </div>
    
                {{-- Profil dan Logout --}}
                <div class="hidden md:flex items-center gap-3">
                    <span class="text-sm text-gray-600">👋 {{ Auth::user()->name ?? 'Siswa' }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="text-sm text-red-600 font-semibold hover:text-red-700 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
    
                {{-- Tombol menu (mobile) --}}
                <button id="mobileMenuBtn" class="md:hidden p-2 text-gray-700 hover:text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" 
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    
        {{-- Menu mobile --}}
        <div id="mobileMenu" class="md:hidden hidden bg-white border-t border-gray-100">
            <div class="flex flex-col px-4 py-2 space-y-2">
                <a href="{{ route('students.dashboard') }}" 
                   class="block py-2 text-gray-700 hover:text-indigo-600">Dashboard</a>
                <a href="{{ route('students.ujian.list') }}" 
                   class="block py-2 text-gray-700 hover:text-indigo-600">List Ujian</a>
                <a href="{{ route('students.perangkingan') }}" 
                   class="block py-2 text-gray-700 hover:text-indigo-600">Peringkat</a>
                <form action="{{ route('logout') }}" method="POST" class="border-t border-gray-100 mt-2 pt-2">
                    @csrf
                    <button type="submit" class="text-left w-full py-2 text-red-600 hover:text-red-700 font-semibold">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    
        {{-- Script toggle menu mobile --}}
        <script>
            const btn = document.getElementById('mobileMenuBtn');
            const menu = document.getElementById('mobileMenu');
            btn.addEventListener('click', () => menu.classList.toggle('hidden'));
        </script>
    </nav>
    

    <!-- Content -->
    <main class="max-w-5xl mx-auto px-4 py-6">
        @yield('content')
    </main>
</body>
</html>