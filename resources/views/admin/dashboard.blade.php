@extends('admin.layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>

    {{-- Statistik ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">Total Pengguna</p>
            <h2 class="text-2xl font-bold text-indigo-600">{{ $totalUsers }}</h2>
        </div>
        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">Total Ujian</p>
            <h2 class="text-2xl font-bold text-indigo-600">{{ $totalExams }}</h2>
        </div>
        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">Total Attempt (Pengerjaan)</p>
            <h2 class="text-2xl font-bold text-indigo-600">{{ $totalAttempts }}</h2>
        </div>
        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-sm text-gray-500">Rata-rata Skor</p>
            <h2 class="text-2xl font-bold text-indigo-600">{{ round($avgScore, 2) }}</h2>
        </div>
    </div>

    {{-- Ujian terbaru --}}
    <div class="bg-white rounded-xl shadow p-4">
        <h2 class="text-lg font-semibold mb-3">Ujian Terbaru</h2>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left">Nama Ujian</th>
                    <th class="px-3 py-2 text-left">Durasi</th>
                    <th class="px-3 py-2 text-left">Total Attempt</th>
                    <th class="px-3 py-2 text-left">Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentExams as $exam)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-3 py-2 font-medium">{{ $exam->name }}</td>
                    <td class="px-3 py-2">{{ $exam->duration }} menit</td>
                    <td class="px-3 py-2">{{ $exam->attempts_count }}</td>
                    <td class="px-3 py-2">{{ $exam->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-3 text-center text-gray-500">Belum ada ujian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Attempt terbaru --}}
    <div class="bg-white rounded-xl shadow p-4">
        <h2 class="text-lg font-semibold mb-3">Attempt Terbaru</h2>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left">Peserta</th>
                    <th class="px-3 py-2 text-left">Ujian</th>
                    <th class="px-3 py-2 text-right">Skor</th>
                    <th class="px-3 py-2 text-left">Selesai Pada</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAttempts as $a)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-3 py-2 font-medium">{{ $a->user->name ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $a->exam->name ?? '-' }}</td>
                    <td class="px-3 py-2 text-right font-semibold text-indigo-600">{{ $a->score ?? 0 }}</td>
                    <td class="px-3 py-2">{{ $a->finished_at?->format('d M Y H:i') ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-3 text-center text-gray-500">Belum ada attempt.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
