<section id="about" class="bg-white">
  <div class="max-w-5xl mx-auto px-6 py-24">
    <h2 class="text-3xl font-bold text-center">Belajar Matematika Jadi Lebih Mudah</h2>
    <p class="mt-2 text-gray-600 text-center max-w-xl mx-auto">
      Temukan fitur-fitur yang membantu kamu memahami konsep matematika dengan cara yang menyenangkan dan interaktif.
    </p>

    <div class="mt-16 grid md:grid-cols-3 gap-10 text-center">
      @foreach([
          [
              'icon' => asset('img/help.png'),
              'title' => 'Bantuan',
              'link' => route('instruction'),
              'desc' => 'Pelajari konsep dasar seperti aljabar, geometri, dan trigonometri dengan penjelasan visual yang mudah dimengerti.'
            ],
            [
              'icon' => asset('img/game.png'),
              'link' => route('login'),
              'title' => 'Game',
              'desc' => 'Uji kemampuanmu dengan latihan soal interaktif dan pembahasan otomatis untuk setiap jawaban.'
            ],
            [
              'icon' => asset('img/leaderboard.png'),
              'link' => route('leaderboard'),
              'title' => 'Leaderboard',
              'desc' => 'Data kemajuan belajarmu tersimpan aman. Kamu bisa belajar di mana saja tanpa kehilangan progres.'
          ],
      ] as $f)
      <div>
        <div class="mx-auto w-32 h-32 rounded-full bg-indigo-100 flex items-center justify-center mb-4">
          <img src="{{ $f['icon'] }}" alt="icon" class="w-32 h-32 object-contain" />
        </div>
        <a href="{{$f['link']}}">
          <h3 class="font-semibold px-5 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">{{ $f['title'] }}</h3>
        </a>
        <p class="text-sm text-gray-600 mt-1">{{ $f['desc'] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>
