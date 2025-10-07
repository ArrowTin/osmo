<header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b">
  <nav class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
    <!-- Logo -->
    <a href="{{ url('/') }}" class="text-xl font-bold text-gray-900">
      {{ env('APP_NAME') }}
    </a>

    <!-- Tombol toggle (hanya muncul di mobile) -->
    <button id="menu-toggle" class="sm:hidden text-gray-700 focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
           viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <!-- Menu utama -->
    <ul id="menu" class="hidden sm:flex gap-6 text-sm">
      <li><a href="#home" class="hover:text-indigo-600">Home</a></li>
      <li><a href="#about" class="hover:text-indigo-600">About</a></li>
      <li><a href="#cta" class="hover:text-indigo-600">Get Started</a></li>
    </ul>
  </nav>

  <!-- Menu dropdown di mobile -->
  <div id="mobile-menu" class="sm:hidden hidden border-t bg-white/90 backdrop-blur">
    <ul class="flex flex-col px-4 py-2 text-sm space-y-2">
      <li><a href="#home" class="block py-2 hover:text-indigo-600">Home</a></li>
      <li><a href="#about" class="block py-2 hover:text-indigo-600">About</a></li>
      <li><a href="#cta" class="block py-2 hover:text-indigo-600">Get Started</a></li>
    </ul>
  </div>
</header>

<script>
  const toggleBtn = document.getElementById('menu-toggle');
  const mobileMenu = document.getElementById('mobile-menu');

  toggleBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });
</script>
