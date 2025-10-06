@extends('user.layouts.app')
@section('title','Rangking Ujian')
@section('content')
<h1 class="text-xl font-bold mb-3">Rangking: {{ $exam->name }}</h1>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">Nama</th>
                <th class="px-4 py-2 text-right">Skor Tertinggi</th>
                {{-- <th class="px-4 py-2 text-right">Percobaan</th> --}}
                <th class="px-4 py-2 text-right">Tanggal Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ranking as $idx => $r)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $idx + 1 }}</td>
                <td class="px-4 py-2 font-medium">{{ $r->user->name }}</td>
                <td class="px-4 py-2 text-right font-semibold">{{ number_format($r->score, 2) }}</td>
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
@endsection
