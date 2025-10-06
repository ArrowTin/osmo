@extends('user.layouts.app')
@section('title','Kerjakan Ujian')
@section('content')
<form action="{{ route('students.ujian.selesai',$exam->id) }}" method="POST" class="space-y-4">
    @csrf
    <div class="flex items-center justify-between">
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

        <div class="grid grid-cols-2 gap-2">
            @foreach($opsiList as $i => $opsi)
            <label class="cursor-pointer">
                <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $opsi }}" class="sr-only peer">
                
                {{-- Jika opsi berupa gambar --}}
                @if(Str::endsWith($opsi, ['.jpg','.png','.jpeg','.gif']))
                    <img src="{{ Storage::url($opsi) }}" class="peer-checked:ring-4 peer-checked:ring-indigo-400 rounded border">
                @else
                    {{-- Jika opsi berupa teks atau rumus --}}
                    <div class="p-2 border rounded peer-checked:ring-4 peer-checked:ring-indigo-400 bg-gray-50">
                        {!! $opsi !!}
                    </div>
                @endif
            </label>
            @endforeach
        </div>
    </div>
    @endforeach



    <button class="w-full bg-indigo-600 text-white py-3 rounded hover:bg-indigo-700">Selesai & Kirim</button>
</form>

<!-- Timer countdown (opsional) -->
<script>
const durasi = {{ $exam->duration * 60 }};
let s = durasi;
const cd = document.getElementById('countdown');
const interval = setInterval(()=>{
    const m = String(Math.floor(s/60)).padStart(2,0);
    const sec = String(s%60).padStart(2,0);
    cd.textContent = `${m}:${sec}`;
    if(s<=0){ clearInterval(interval); alert('Waktu habis'); document.querySelector('form').submit(); }
    s--;
},1000);
</script>
@endsection