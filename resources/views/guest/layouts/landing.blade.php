<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">

  <title>{{ env('APP_NAME') }}</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    html { scroll-behavior: smooth; }
  </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

  {{-- Navbar --}}
  @include('guest.navbar')

  {{-- Konten utama responsif --}}
  <main class="flex-1 w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      @yield('content')
    </div>
  </main>

  {{-- Footer --}}
  @include('guest.footer')

  @stack('scripts')

</body>
</html>
