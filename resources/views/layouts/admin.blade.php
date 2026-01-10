<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIMPAS DIGITAL') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-[#0ea5e9] text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo/Brand -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <!-- Placeholder Logo Icon -->
                        <div class="bg-white p-1.5 rounded-full">
                            <img src="{{ asset('img/logo.png') }}" class="w-8 h-8 object-contain" alt="Logo">
                        </div>
                        <div>
                            <h1 class="text-xl font-bold leading-none">SIMPAS DIGITAL</h1>
                            <p class="text-xs text-blue-100 font-medium">SMP Yamis Jakarta</p>
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-xs text-blue-100">{{ ucfirst(Auth::user()->role) }}</p>
                        <p class="font-bold text-sm">{{ Auth::user()->name }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 bg-white/10 rounded-lg hover:bg-white/20 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Navigation Tabs -->
        <div class="bg-white rounded-xl shadow-sm p-2 mb-8 flex gap-2 overflow-x-auto">
            {{-- Dashboard Link --}}
            @if(Auth::user()->role === 'guru')
                <a href="{{ route('guru.dashboard') }}"
                    class="flex items-center gap-2 px-6 py-3 rounded-lg {{ request()->routeIs('guru.dashboard') ? 'bg-cyan-50 text-cyan-600 font-bold border border-cyan-100' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>
            @elseif(Auth::user()->role === 'siswa')
                <a href="{{ route('siswa.dashboard') }}"
                    class="flex items-center gap-2 px-6 py-3 rounded-lg {{ request()->routeIs('siswa.dashboard') ? 'bg-cyan-50 text-cyan-600 font-bold border border-cyan-100' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>
            @else
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-2 px-6 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-cyan-50 text-cyan-600 font-bold border border-cyan-100' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>
            @endif

            {{-- Input Poin (Admin/Guru Only) --}}
            @if(Auth::user()->role !== 'siswa')
                <a href="{{ route('poin.create') }}"
                    class="flex items-center gap-2 px-6 py-3 rounded-lg text-gray-500 hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Input Poin
                </a>
            @endif

            {{-- Other Links (Admin/Guru Only) --}}
            @if(Auth::user()->role === 'guru')
                <a href="{{ route('guru.tindak_lanjut.index') }}"
                    class="flex items-center gap-2 px-6 py-3 rounded-lg {{ request()->routeIs('guru.tindak_lanjut.index') ? 'bg-cyan-50 text-cyan-600 font-bold border border-cyan-100' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Tindak Lanjut
                </a>
                <a href="{{ route('guru.siswa.index') }}"
                    class="flex items-center gap-2 px-6 py-3 rounded-lg {{ request()->routeIs('guru.siswa.index') ? 'bg-cyan-50 text-cyan-600 font-bold border border-cyan-100' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Data Siswa
                </a>
            @elseif(Auth::user()->role === 'admin')
                <a href="{{ route('admin.tindak_lanjut.index') }}"
                    class="flex items-center gap-2 px-6 py-3 rounded-lg {{ request()->routeIs('admin.tindak_lanjut.index') ? 'bg-cyan-50 text-cyan-600 font-bold border border-cyan-100' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Tindak Lanjut
                </a>
                <a href="{{ route('admin.siswa.index') }}"
                    class="flex items-center gap-2 px-6 py-3 rounded-lg {{ request()->routeIs('admin.siswa.index') ? 'bg-cyan-50 text-cyan-600 font-bold border border-cyan-100' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Data Siswa
                </a>
            @endif
        </div>

        @yield('content')
    </main>
</body>

</html>