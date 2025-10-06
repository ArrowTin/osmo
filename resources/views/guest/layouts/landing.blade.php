<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@{{env("APP_NAME")}}</title>

  <!-- Tailwind CDN only -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    html { scroll-behavior: smooth; }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">
  @include('guest.navbar')
  <main>
    @yield('content')
  </main>
  @include('guest.footer')
</body>
</html>