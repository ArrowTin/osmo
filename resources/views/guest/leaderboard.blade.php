@extends('guest.layouts.landing')
    
@section('content')
    {{-- Attempt terbaru --}}
    <div class="bg-white rounded-xl shadow p-4">
      <h2 class="text-lg font-semibold mb-3">Leaderboard</h2>
      <table class="w-full text-sm">
          <thead class="bg-gray-50">
              <tr>
                  <th class="px-3 py-2 text-left">Peserta</th>
                  <th class="px-3 py-2 text-center">Skor</th>
                  <th class="px-3 py-2 text-left">Ujian</th>
                  <th class="px-3 py-2 text-left">Selesai Pada</th>
              </tr>
          </thead>
          <tbody>
              @forelse($recentAttempts as $a)
              <tr class="border-t hover:bg-gray-50">
                  <td class="px-3 py-2 font-medium">{{ $a->user->name ?? '-' }}</td>
                  <td class="px-3 py-2 text-center font-semibold text-indigo-600">{{ $a->score ?? 0 }}</td>
                  <td class="px-3 py-2">{{ $a->exam->name ?? '-' }}</td>
                  <td class="px-3 py-2">{{ $a->finished_at?->format('d M Y H:i') ?? '-' }}</td>
              </tr>
              @empty
              <tr><td colspan="4" class="px-3 py-3 text-center text-gray-500">Belum ada attempt.</td></tr>
              @endforelse
          </tbody>
      </table>
  </div>
@endsection


@push('script')
      
@endpush