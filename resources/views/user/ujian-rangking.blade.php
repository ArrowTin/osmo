@extends('user.layouts.app')
@section('title','Rangking Ujian')
@section('content')
<h1 class="text-xl font-bold mb-3">Rangking: {{ $exam->name }}</h1>

{{-- Wrapper agar tabel bisa di-scroll horizontal di mobile --}}
<div class="bg-white rounded-xl shadow overflow-x-auto">
    <div class="min-w-full inline-block align-middle">
        <table class="min-w-full text-sm text-left whitespace-nowrap">
            <thead class="bg-gray-50 border-b text-gray-700">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2 text-right">Skor Tertinggi</th>
                    {{-- <th class="px-4 py-2 text-right">Percobaan</th> --}}
                    <th class="px-4 py-2 text-right">Tanggal Terakhir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($ranking as $idx => $r)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-2 font-medium">{{ $idx + 1 }}</td>
                    <td class="px-4 py-2">{{ $r->user->name }}</td>
                    <td class="px-4 py-2 text-right font-semibold text-indigo-700">{{ number_format($r->score, 2) }}</td>
                    {{-- <td class="px-4 py-2 text-right">{{ $r->attempt_count }}</td> --}}
                    <td class="px-4 py-2 text-right">{{ \Carbon\Carbon::parse($r->last_attempt_at)->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">Belum ada peserta.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
