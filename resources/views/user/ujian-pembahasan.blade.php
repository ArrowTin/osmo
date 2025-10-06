@extends('user.layouts.app')
@section('title','Pembahasan Ujian')
@section('content')
<h1 class="text-xl font-bold mb-4">{{ $exam->name }} - Pembahasan</h1>

@foreach($questions as $index => $q)
@php
    // Ambil jawaban siswa dari tabel exam_answers
    $answer = $attempt->answers->firstWhere('question_id', $q->id);
    $opsiList = is_array($q->options) ? $q->options : json_decode($q->options, true);
@endphp

<div class="bg-white rounded-xl shadow p-4 mb-4">
    <p class="font-semibold text-gray-700 mb-2">Soal {{ $index + 1 }}</p>

    {{-- Soal berupa gambar atau teks --}}
    @if($q->question_text)
        @if(Str::endsWith($q->question_text, ['.jpg','.png','.jpeg','.gif']))
            <img src="{{ Storage::url($q->question_text) }}" class="mb-3 rounded w-full max-h-80 object-contain">
        @else
            <div class="mb-3">{!! $q->question_text !!}</div>
        @endif
    @endif

    {{-- Tampilkan opsi jawaban --}}
    <div class="space-y-2">
        @foreach($opsiList as $i => $opsi)
            @php
                $isUser = $answer && $answer->answer === (string) $opsi;
                $isCorrect = $q->correct_answer === (string) $opsi;
            @endphp
            <div class="p-2 border rounded
                @if($isCorrect) bg-green-100 border-green-400
                @elseif($isUser && !$isCorrect) bg-red-100 border-red-400
                @endif">
                
                {{-- Jika opsi berupa gambar --}}
                @if(Str::endsWith($opsi, ['.jpg','.png','.jpeg','.gif']))
                    <img src="{{ Storage::url($opsi) }}" class="w-40 rounded inline-block align-middle">
                @else
                    <span>{!! $opsi !!}</span>
                @endif

                {{-- Label tambahan --}}
                @if($isCorrect)
                    <span class="text-green-600 font-semibold ml-2">(Benar)</span>
                @elseif($isUser && !$isCorrect)
                    <span class="text-red-600 font-semibold ml-2">(Jawaban Anda)</span>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Penjelasan jika ada --}}
    @if($q->explanation)
        <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded">
            <p class="font-medium text-blue-700">Penjelasan:</p>
            @if(Str::endsWith($q->explanation, ['.jpg','.png','.jpeg','.gif']))
                <img src="{{ Storage::url($q->explanation) }}" class="mt-2 rounded w-full max-h-80 object-contain">
            @else
                <div class="mt-2">{!! $q->explanation !!}</div>
            @endif
        </div>
    @endif
</div>
@endforeach

<div class="text-center mt-6">
    <p class="text-lg font-bold">Skor Anda: {{ $attempt->score }}</p>
    <a href="{{ route('students.ujian.list') }}" 
       class="inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition">
        Kembali ke Daftar Ujian
    </a>
</div>
@endsection
