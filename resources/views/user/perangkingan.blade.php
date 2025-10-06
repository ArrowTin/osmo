@extends('user.layouts.app')
@section('title','Perangkingan')
@section('content')
<h1 class="text-xl font-bold mb-4">Peringkat Global Peserta Ujian</h1>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">Nama</th>
                <th class="px-4 py-2 text-right">Skor Tertinggi</th>
                <th class="px-4 py-2 text-right">Total Ujian</th>
                <th class="px-4 py-2 text-right">Percobaan</th>
                <th class="px-4 py-2 text-right">Terakhir Ujian</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ranking as $index => $r)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $index + 1 }}</td>
                <td class="px-4 py-2 font-medium">{{ $r->user->name }}</td>
                <td class="px-4 py-2 text-right font-semibold">{{ number_format($r->max_score, 2) }}</td>
                <td class="px-4 py-2 text-right">{{ $r->exam_count }}</td>
                <td class="px-4 py-2 text-right">{{ $r->attempt_count }}</td>
                <td class="px-4 py-2 text-right">{{ \Carbon\Carbon::parse($r->last_attempt_at)->format('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4 text-gray-500">Belum ada peserta ujian.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
