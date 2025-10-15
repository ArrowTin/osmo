@extends('admin.layouts.app')
@section('title','Ujian')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Manajem Ujian</h1>
    <button onclick="addExam()"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah</button>
</div>

<section class="bg-white dark:bg-gray-800 rounded shadow p-4">
    <x-datatable 
        id="tableExam" 
        :keys="['name','duration','start_time','questions_count','users_count']"
        :columns="['Nama', 'Nama Ujian','Waktu Mulai','Jumlah Soal', 'Jumlah Peserta']"
    />
</section>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tableEl = document.querySelector('#tableExam');
    const keys = JSON.parse(tableEl.dataset.keys);
    const columns = ['Nama', 'Nama Ujian','Waktu Mulai','Jumlah Soal', 'Jumlah Peserta'];
    
    window.CRUD.loadTable('tableExam', keys, '/api/admin/exams',columns);
});

function addExam() {
    window.dispatchEvent(new CustomEvent('modal-form', {
        detail: {
            title: 'Tambah Exam',
            fields: [
                { name: 'name', label: 'Nama', type:'text', value: '' },
                { name: 'duration', label: 'Duration (menit)', type:'number', value: '' },
                { name: 'start_time', label: 'Start Time', type:'date_time', value: '' },
            ],
            onSubmit: (payload) => {
                CRUD.save('exams', payload, null, 'tableExam');
            }
        }
    }));
}

</script>
@endpush
