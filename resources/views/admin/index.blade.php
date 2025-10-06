@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <nav class="text-sm mb-4">
        <ol class="flex space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary-600 dark:text-primary-400 font-medium">Dashboard</li>
        </ol>
    </nav>

    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-card-stats title="Total Users" value="1,234" />
        <x-card-stats title="Revenue" value="Rp 5.6 Jt" />
        <x-card-stats title="Active Sessions" value="89" />
        <x-card-stats title="Soal Tersedia" value="456" />
    </section>

    <section class="bg-white dark:bg-gray-800 rounded shadow p-4 mb-6">
        <h2 class="text-lg font-semibold mb-2">Grafik Bulanan</h2>
        <x-chart-placeholder />
    </section>

    <section class="bg-white dark:bg-gray-800 rounded shadow p-4">
        <h2 class="text-lg font-semibold mb-2">User Terbaru</h2>
        <x-datatable id="tableUsers" columns="Username,Tanggal,Aksi" />
    </section>
@stop

@push('scripts')
<script>
    new simpleDatatables.DataTable("#tableUsers", { perPage: 5 });
</script>
@endpush