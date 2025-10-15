<aside class="fixed lg:sticky top-0 left-0 h-screen lg:h-auto w-64 bg-white dark:bg-gray-800 border-r dark:border-gray-700
            transform -translate-x-full lg:translate-x-0 transition-transform z-40"
       x-show="$store.layout.sidebarOpen"
       x-transition>
    <div class="p-4 flex items-center justify-between">
        <a href="{{ route('welcome') }}" class="flex items-center space-x-2 font-bold text-blue-600">
            <svg class="w-6 h-6" fill="currentColor"><path d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm2-13h4v2h-4V7zm0 4h4v6h-4v-6z"/></svg>
            <span>{{env('APP_NAME')}}</span>
        </a>
        <button @click="$store.layout.toggleSidebar()" class="lg:hidden text-gray-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <nav class="px-4 space-y-2">
        <span class="text-xs uppercase text-gray-400 dark:text-gray-500">Master</span>
        <x-nav-link href="{{ route('admin.dashboard') }}">Dashboard</x-nav-link>
        <x-nav-link href="{{ route('admin.categories') }}">Kategori</x-nav-link>
        <x-nav-link href="{{ route('admin.questions') }}">Bank Soal</x-nav-link>
        <x-nav-link href="{{ route('admin.users') }}">User</x-nav-link>
        <x-nav-link href="{{ route('admin.exams') }}">Ujian</x-nav-link>
    </nav>
</aside>