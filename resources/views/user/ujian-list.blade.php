@extends('user.layouts.app')
@section('title', 'Daftar Ujian')
@section('content')

<h1 class="text-2xl font-bold mb-4">Daftar Ujian</h1>

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($exams as $u)
    <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between space-y-3">

        <!-- Header card -->
        <div>
            <h2 class="font-semibold text-lg text-gray-800">{{ $u->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">Durasi: {{ $u->duration }} menit</p>
            <p class="text-sm text-gray-500">Jumlah Soal: {{ $u->questions()->count() }}</p>
        </div>

        <!-- Action buttons -->
        <div class="flex items-center gap-2 text-sm">
            <!-- Link ke detail ujian (pengerjaan) -->
            <a href="{{ route('students.ujian.kerjakan', $u->id) }}"
               class="flex-1 text-center bg-indigo-600 text-white px-3 py-2 rounded hover:bg-indigo-700 transition">
                Detail Ujian
            </a>

            <!-- Link ke rangking ujian ini -->
            <a href="{{ route('students.ujian.rangking', $u->id) }}"
               class="flex-1 text-center bg-emerald-600 text-white px-3 py-2 rounded hover:bg-emerald-700 transition">
                Rangking
            </a>
        </div>
    </div>
    @empty
    <p class="text-sm text-gray-500 col-span-full">Belum ada ujian.</p>
    @endforelse
</div>

@endsection