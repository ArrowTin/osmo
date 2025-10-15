<section id="home" class="max-w-5xl mx-auto px-6 pt-24 pb-32 flex flex-col md:flex-row items-center gap-12">
  <div class="flex-1">
    <div class="bg-gray-200 rounded-2xl h-72 md:h-80 flex items-center justify-center text-gray-500">
      <img src="{{asset('logo.png')}}" alt="">
    </div>
  </div>  
  <div class="flex-1">
      <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">{{strtoupper(env('APP_NAME'))}} USA</h1>
      <h5 class="text-1xl md:text-2xl font-extrabold leading-tight">OLeh : </h5>
      <p class="mt-4 text-gray-600">1. Nasri Tupulu</p>
      <p class="mb-4 text-gray-600">2. Rudy Hartono</p>
      <h6 class=" font-extrabold leading-tight">Universitas Katolik Santo Agustinus Hippo</h6>
      <div class="mt-8 flex gap-3">
        {{-- <a href="{{route('login')}}" class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Mulai</a> --}}
        <a href="#about" class="px-5 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-100">Mulai</a>
      </div>
    </div>
  </section>