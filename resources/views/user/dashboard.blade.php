@extends('user.layouts.app')
@section('title','Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-4">Dashboard Peserta</h1>

{{-- Statistik / Ringkasan --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-indigo-50 p-4 rounded-xl shadow">
        <p class="text-gray-600 text-sm">Total Ujian Dikerjakan</p>
        <p class="text-2xl font-bold text-indigo-700">
            {{ \App\Models\ExamAttempt::where('user_id', Auth::user()->id)->count() }}
        </p>
    </div>
    <div class="bg-green-50 p-4 rounded-xl shadow">
        <p class="text-gray-600 text-sm">Skor Tertinggi</p>
        <p class="text-2xl font-bold text-green-700">
            {{ \App\Models\ExamAttempt::where('user_id', Auth::user()->id)->max('score') ?? 0 }}
        </p>
    </div>
    <div class="bg-yellow-50 p-4 rounded-xl shadow">
        <p class="text-gray-600 text-sm">Rata-rata Skor</p>
        <p class="text-2xl font-bold text-yellow-700">
            {{ number_format(\App\Models\ExamAttempt::where('user_id', Auth::user()->id)->avg('score'), 2) }}
        </p>
    </div>
</div>

{{-- Tabel Ranking --}}
<div class="bg-white rounded-xl shadow">
    <h2 class="text-lg font-bold bg-gray-100 px-4 py-2 border-b">Perangkingan Semua Ujian</h2>

    {{-- Tambahkan scroll-x di sini --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[600px]">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Peserta</th>
                    <th class="px-4 py-2 text-left">Ujian</th>
                    <th class="px-4 py-2 text-right">Skor</th>
                    <th class="px-4 py-2 text-right">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ranking as $i => $r)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $i + 1 }}</td>
                    <td class="px-4 py-2 font-medium">{{ $r->user->name }}</td>
                    <td class="px-4 py-2">{{ $r->exam->name }}</td>
                    <td class="px-4 py-2 text-right font-semibold text-indigo-600">{{ $r->score }}</td>
                    <td class="px-4 py-2 text-right text-gray-600">
                        {{ \Carbon\Carbon::parse($r->finished_at)->format('d M Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">Belum ada data ujian</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
