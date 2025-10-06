@extends('admin.layouts.app')
@section('title','User')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Manajem User</h1>
    <button onclick="addUser()"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah</button>
</div>

<section class="bg-white dark:bg-gray-800 rounded shadow p-4">
    <x-datatable 
        id="tableUser" 
        :keys="['name','username']"
        :columns="['Nama', 'Username']"
    />
</section>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tableEl = document.querySelector('#tableUser');
    const keys = JSON.parse(tableEl.dataset.keys);
    window.CRUD.loadTable('tableUser', keys, '/api/admin/users');
});

function addUser() {
    window.dispatchEvent(new CustomEvent('modal-form', {
        detail: {
            title: 'Tambah User',
            fields: [
                { name: 'name', label: 'Nama', value: '' },
                { name: 'username', label: 'Username', value: '' },
                { name: 'password', label: 'Password', value: '' },
            ],
            onSubmit: (payload) => {
                const formData = new FormData();
                formData.append('name', payload.name);
                formData.append('username', payload.username);
                if (payload.password) formData.append('password', payload.password);
                CRUD.save('users', formData, null, 'tableUser', { isFormData:true });
            }
        }
    }));
}

function resetPassword(id) {
        window.dispatchEvent(new CustomEvent('modal-form', {
            detail: {
                title: 'Reset Password',
                fields: [
                    { name: 'password', label: 'Password Baru', type: 'text', value: '' }
                ],
                onSubmit: payload => {
                    if (!payload.password) {
                        CRUD.toast('Password tidak boleh kosong', 'red');
                        return;
                    }
                    CRUD.save(`users/${id}/reset-password`, { password: payload.password }, id, 'tableUser', {
                        method: 'POST'
                    });
                }
            }
        }));
    }
</script>
@endpush
