@extends('user.layouts.app')
@section('title','Kerjakan Ujian')
@section('content')
<form action="{{ route('students.ujian.selesai',$exam->id) }}" method="POST" class="space-y-4">
    @csrf
    <div class="flex items-center justify-between flex-wrap gap-2">
        <h1 class="text-xl font-bold">{{ $exam->name }}</h1>
        <span id="countdown" class="bg-red-100 text-red-700 px-3 py-1 rounded text-sm"></span>
    </div>

    @foreach($questions as $index => $soal)
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-sm text-gray-500 mb-2">Soal {{ $index+1 }}</p>

        {{-- tampilkan gambar soal jika ada --}}
        @if($soal->question_text)
            <img src="{{ Storage::url($soal->question_text) }}" alt="Soal" class="w-full max-h-80 object-contain rounded mb-4">
        @endif

        {{-- Ambil array opsi dari database --}}
        @php
            $opsiList = is_array($soal->options) ? $soal->options : json_decode($soal->options, true);
        @endphp

        {{-- Responsive: vertikal di mobile, 2 kolom di md+ --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($opsiList as $i => $opsi)
            <label class="cursor-pointer block">
                <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $opsi }}" class="sr-only peer">
        
                @if(Str::endsWith($opsi, ['.jpg', '.png', '.jpeg', '.gif']))
                    <img src="{{ Storage::url($opsi) }}" class="peer-checked:ring-4 peer-checked:ring-indigo-400 rounded border w-full object-contain">
                @else
                    <div class="p-2 border rounded peer-checked:ring-4 peer-checked:ring-indigo-400 bg-gray-50 text-center md:text-left option-content">
                        {!! $opsi !!}
                    </div>
                @endif
            </label>
            @endforeach
        </div>
        
    </div>
    @endforeach

    <button class="w-full bg-indigo-600 text-white py-3 rounded hover:bg-indigo-700 transition">
        Selesai & Kirim
    </button>
</form>

<!-- Timer countdown -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
    
        // === ✅ Fungsi render aman untuk KaTeX ===
        const safeRender = (el) => {
            if (typeof renderMathInElement === 'function') {
                try {
                    renderMathInElement(el, {
                        delimiters: [
                            { left: "$$", right: "$$", display: true },
                            { left: "$", right: "$", display: false },
                            { left: "\\(", right: "\\)", display: false },
                            { left: "\\[", right: "\\]", display: true }
                        ],
                    });
                } catch (e) {
                    console.error('KaTeX render error:', e);
                }
            } else {
                console.warn('renderMathInElement() belum tersedia.');
            }
        };
    
        // === 🧹 Fungsi sanitasi LaTeX (fix umum + khusus bigm error) ===
        const fixLatex = (str) => {
            if (!str) return '';
            return str
                .replace(/\r?\n/g, '') // hapus newline
                // Hapus spasi yang aneh di dalam ekspresi matematika
                .replace(/\^\s+/g, '^')
                .replace(/_\s+/g, '_')
                // Perbaiki kesalahan umum \bigm{|} dan sejenisnya
                .replace(/\\bigm\{\|/g, '\\bigm\\|')
                .replace(/\\Bigm\{\|/g, '\\Bigm\\|')
                .replace(/\\bigl\{\|/g, '\\bigl\\|')
                .replace(/\\bigr\{\|/g, '\\bigr\\|')
                .replace(/\\Bigl\{\|/g, '\\Bigl\\|')
                .replace(/\\Bigr\{\|/g, '\\Bigr\\|')
                // Hapus kurung kurawal penutup yang tersisa tanpa pasangan setelah \bigm\|
                .replace(/\\bigm\\\|\}/g, '\\Big|')
                .replace(/\\Bigm\\\|\}/g, '\\Big|')
                // Ubah \bigm\| menjadi \Big| agar lebih kompatibel
                .replace(/\\bigm\\\|/g, '\\Big|')
                .replace(/\\Bigm\\\|/g, '\\Big|')
                // Trim spasi berlebih
                .replace(/\s+/g, ' ')
                .trim();
        };
    
        // === 🔍 Tangani semua elemen opsi yang mungkin berisi LaTeX ===
        const opsiElems = document.querySelectorAll('.option-content');
    
        opsiElems.forEach(el => {
            let html = el.innerHTML.trim();
            if (!html) return;
    
            // Perbaiki kesalahan umum LaTeX
            html = fixLatex(html);
    
            // Deteksi apakah sudah memiliki delimiter math
            const hasDelimiter = /(\$.*\$|\\\[.*\\\]|\\\(.*\\\)|\$\$[\s\S]*\$\$)/m.test(html);
    
            // Deteksi apakah terlihat seperti formula
            const looksLikeTex = /\\[a-zA-Z]|[_\^]|\\frac|\\left|\\right|\{.*\}/.test(html);
    
            if (!hasDelimiter && looksLikeTex) {
                if (!el.dataset.katexWrapped) {
                    el.innerHTML = `$${html}$`;
                    el.dataset.katexWrapped = '1';
                }
            } else {
                el.innerHTML = html;
            }
        });
    
        // === 🎨 Render semua konten setelah diformat ===
        safeRender(document.body);
    
        // === ⏱️ Timer countdown ujian ===
        const cd = document.getElementById('countdown');
        if (cd) {
            let s = {{ $exam->duration * 60 }};
            const interval = setInterval(() => {
                const m = String(Math.floor(s / 60)).padStart(2, '0');
                const sec = String(s % 60).padStart(2, '0');
                cd.textContent = `${m}:${sec}`;
                if (s <= 0) {
                    clearInterval(interval);
                    alert('Waktu habis!');
                    document.querySelector('form').submit();
                }
                s--;
            }, 1000);
        }
    });
    </script>
    
    
    

@endsection
