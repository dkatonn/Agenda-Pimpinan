<div class="flex flex-col h-full">

    {{-- Header --}}
    <div class="p-5 border-b border-gray-700 flex justify-between items-center">
        <div>
            <h1 class="text-lg font-bold leading-tight">Kementerian Dalam Negeri</h1>
            <p class="text-sm text-gray-400 mt-1">{{ auth()->user()->role }}</p>
        </div>

        {{-- Close button mobile --}}
        <button class="md:hidden text-gray-300 hover:text-white text-xl" 
                @click="sidebarOpen = false"
                type="button">
            ✕
        </button>
    </div>

    {{-- Role info --}}
    <div class="px-5 pt-3 pb-2 text-xs text-gray-400">
        Role : <span class="text-green-400">{{ auth()->user()->role }}</span>
    </div>

    {{-- Menu --}}
    <nav class="mt-4 space-y-1 text-sm flex-1 overflow-auto">
        @if(strtolower(auth()->user()->role) === 'superadmin')
            
            <a href="{{ route('admin.dashboard') }}" 
               @click="sidebarOpen = false"
               class="flex items-center gap-3 px-5 py-2.5 hover:bg-gray-800 rounded transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-blue-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.profile-settings') }}" 
               @click="sidebarOpen = false"
               class="flex items-center gap-3 px-5 py-2.5 hover:bg-gray-800 rounded transition-colors {{ request()->routeIs('admin.profile-settings') ? 'bg-gray-800 text-blue-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profile Pimpinan
            </a>

            <a href="{{ route('admin.video-management') }}" 
               @click="sidebarOpen = false"
               class="flex items-center gap-3 px-5 py-2.5 hover:bg-gray-800 rounded transition-colors {{ request()->routeIs('admin.video-management') ? 'bg-gray-800 text-blue-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Video
            </a>

            <a href="{{ route('admin.agenda') }}" 
               @click="sidebarOpen = false"
               class="flex items-center gap-3 px-5 py-2.5 hover:bg-gray-800 rounded transition-colors {{ request()->routeIs('admin.agenda') ? 'bg-gray-800 text-blue-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Agenda
            </a>

            <a href="{{ route('admin.running-text-edit') }}" 
               @click="sidebarOpen = false"
               class="flex items-center gap-3 px-5 py-2.5 hover:bg-gray-800 rounded transition-colors {{ request()->routeIs('admin.running-text-edit') ? 'bg-gray-800 text-blue-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
                Running Text
            </a>

            <a href="{{ route('admin.users') }}" 
               @click="sidebarOpen = false"
               class="flex items-center gap-3 px-5 py-2.5 hover:bg-gray-800 rounded transition-colors {{ request()->routeIs('admin.users') ? 'bg-gray-800 text-blue-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                User Management
            </a>

        @elseif(strtolower(auth()->user()->role) === 'admin')

            <a href="{{ route('admin.agenda') }}" 
               @click="sidebarOpen = false"
               class="flex items-center gap-3 px-5 py-2.5 hover:bg-gray-800 rounded transition-colors {{ request()->routeIs('admin.agenda') ? 'bg-gray-800 text-blue-400' : 'text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Agenda
            </a>

        @endif
    </nav>

    {{-- Logout --}}
    <div class="p-5 border-t border-gray-700 mt-auto">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" 
                    class="w-full flex items-center justify-center gap-2 py-2.5 bg-red-600 hover:bg-red-700 rounded text-white font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>