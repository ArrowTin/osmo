<section id="about" class="bg-white">
  <div class="max-w-5xl mx-auto px-6 py-24">
    <h2 class="text-3xl font-bold text-center">Belajar Matematika Jadi Lebih Mudah</h2>
    <p class="mt-2 text-gray-600 text-center max-w-xl mx-auto">
      Temukan fitur-fitur yang membantu kamu memahami konsep matematika dengan cara yang menyenangkan dan interaktif.
    </p>

    <div class="mt-16 grid md:grid-cols-3 gap-10 text-center">
      @foreach([
          ['icon'=>'M12 4v16m8-8H4','title'=>'Konsep Dasar yang Jelas','desc'=>'Pelajari konsep dasar seperti aljabar, geometri, dan trigonometri dengan penjelasan visual yang mudah dimengerti.'],
          ['icon'=>'M3 12l3 3l6-6l6 6l3-3','title'=>'Latihan Interaktif','desc'=>'Uji kemampuanmu dengan latihan soal interaktif dan pembahasan otomatis untuk setiap jawaban.'],
          ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','title'=>'Belajar Aman dan Nyaman','desc'=>'Data kemajuan belajarmu tersimpan aman. Kamu bisa belajar di mana saja tanpa kehilangan progres.']
      ] as $f)
      <div>
        <div class="mx-auto w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center mb-4">
          <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}" />
          </svg>
        </div>
        <h3 class="font-semibold">{{ $f['title'] }}</h3>
        <p class="text-sm text-gray-600 mt-1">{{ $f['desc'] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>
