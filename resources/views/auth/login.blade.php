<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translateY(20px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      .fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
      }
    </style>
</head>

<body class="bg-gradient-to-br from-indigo-50 to-white min-h-screen flex flex-col">

  {{-- ✅ Navbar --}}
  @include('guest.navbar')

  {{-- ✅ Wrapper konten login --}}
  <div class="flex-grow flex items-center justify-center px-4 py-12 md:py-20">

    <div class="w-full max-w-5xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden grid md:grid-cols-2">
      
      {{-- Gambar Samping --}}
      <div class="hidden md:flex bg-indigo-100 items-center justify-center p-10 fade-in-up">
        <img src="{{ asset('logo.png') }}" 
             alt="Login Illustration" 
             class="w-3/4 drop-shadow-lg transform transition-all duration-700 hover:scale-105">
      </div>

      {{-- Form Login --}}
      <div class="p-10 flex flex-col justify-center fade-in-up">
        <h2 class="text-3xl font-bold text-indigo-700 text-center mb-6">Selamat Datang 👋</h2>
        <p class="text-gray-500 text-center mb-8">Silakan login untuk melanjutkan ke dashboard Anda.</p>

        @if ($errors->any())
          <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm">
            {{ $errors->first() }}
          </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
          @csrf
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input type="text" name="username" required autofocus
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all">
          </div>

          <button type="submit"
                  class="w-full bg-indigo-600 text-white py-2 rounded-lg font-medium hover:bg-indigo-700 transform hover:scale-[1.02] transition-all duration-300">
            Login
          </button>
        </form>

      </div>

    </div>
  </div>

</body>
</html>
