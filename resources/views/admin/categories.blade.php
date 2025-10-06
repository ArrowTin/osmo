@extends('admin.layouts.app')
@section('title','Kategori')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Kategori</h1>
    <button onclick="addCategory()"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah</button>
</div>

<section class="bg-white dark:bg-gray-800 rounded shadow p-4">
    <x-datatable 
        id="tableCat" 
        :keys="['name', 'slug']"
        :columns="['Nama', 'Slug', 'Aksi']"
    />

</section>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tableEl = document.querySelector('#tableCat');
    const keys = JSON.parse(tableEl.dataset.keys);

    window.CRUD.loadTable('tableCat', keys, '/api/admin/categories');
});

function addCategory() {
    window.dispatchEvent(new CustomEvent('modal-form', {
        detail: {
            title: 'Tambah Kategori',
            fields: [
                { name: 'name', label: 'Nama', value: '' },
            ],
            onSubmit: (p) => CRUD.save('categories', p, null, 'tableCat')
        }
    }));
}
</script>
@endpush
