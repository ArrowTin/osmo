<header class="sticky top-0 z-30 bg-white dark:bg-gray-800 border-b dark:border-gray-700">
    <div class="flex items-center justify-between px-4 py-3">
        <div class="flex items-center space-x-4">
            <button @click="$store.layout.toggleSidebar()" class="hidden lg:inline-block text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <form class="hidden md:flex items-center bg-gray-100 dark:bg-gray-700 rounded px-3 py-2">
                <input type="text" placeholder="Search..." class="bg-transparent outline-none text-sm">
                <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
        </div>
        <div class="flex items-center space-x-4">
            <button @click="$store.layout.toggleDark()" class="text-gray-500">
                <svg x-show="!$store.layout.dark" class="w-6 h-6" fill="none" stroke="currentColor"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <svg x-show="$store.layout.dark" class="w-6 h-6" fill="none" stroke="currentColor"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            </button>
            <div class="relative" x-data="{ open:false }">
                <button @click="open=!open" class="flex items-center space-x-2 text-sm">
                    <img class="w-8 h-8 rounded-full" src="https://i.pravatar.cc/40" alt="user">
                    <span class="hidden md:inline">Hi, Admin</span>
                </button>
                <div x-show="open" @click.outside="open=false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded shadow py-2">
                    <a href="#" class="block px-4 py-1 hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
                    <a href="#" class="block px-4 py-1 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>