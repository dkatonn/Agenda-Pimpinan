<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Kemendagri</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-zinc-800 text-white">

{{-- Alpine HANYA untuk sidebar --}}
<div x-data="{ sidebarOpen: false }" 
     @keydown.escape.window="sidebarOpen = false"
     class="flex h-screen overflow-hidden">

    {{-- Overlay untuk mobile --}}
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         x-transition.opacity.duration.300ms
         class="fixed inset-0 bg-black/50 z-40 md:hidden"
         style="display: none;">
    </div>

    {{-- Sidebar --}}
    <aside 
        class="fixed top-0 left-0 md:relative w-64 bg-[#111827] h-full z-50 transition-transform duration-300 ease-in-out"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
        @include('components.layouts.app.sidebar')
    </aside>

    {{-- Main content wrapper --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Navbar mobile --}}
        <header class="md:hidden flex items-center justify-between p-4 bg-zinc-900 border-b border-zinc-700 sticky top-0 z-30">
            <button @click="sidebarOpen = true" 
                    type="button"
                    class="text-white focus:outline-none focus:ring-2 focus:ring-blue-500 rounded p-1 hover:bg-zinc-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <h1 class="font-bold text-base">Dashboard Admin</h1>
            <div class="w-6"></div>
        </header>

        {{-- Main content area --}}
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-zinc-800">
            {{ $slot }}
        </main>
    </div>

</div>

@livewireScripts

</body>
</html>