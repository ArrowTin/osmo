@extends('admin.layouts.app')

@section('title', 'Bank Soal')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Bank Soal</h1>
        <button onclick="addQuestion()"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah</button>
    </div>

    <section class="bg-white dark:bg-gray-800 rounded shadow p-4">
        <x-datatable 
            id="tableSoal" 
            :keys="['question_text', 'options','correct_answer','explanation','category.name']"
            :columns="['Soal', 'Opsi Jawaban','Jawaban Benar','Penjelasan','Kategori','Aksi']"
        />

    </section>
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // const tableEl = document.querySelector('#tableSoal');
        // const keys = JSON.parse(tableEl.dataset.keys);
    
        window.CRUD.loadTable('tableSoal',  ['question_text','options','correct_answer','explanation','category.name'], '/api/admin/questions');
    });
</script>
<script>
async function addQuestion() {
  try {
    // Ambil kategori dari API
    const res = await fetch('/api/admin/categories');
    if (!res.ok) throw new Error(await res.text());
    const categories = await res.json();
    

    // mapping ke opsi select
    const categoryOptions = categories.map(c => ({ value: c.id, label: c.name }));
    
    window.dispatchEvent(new CustomEvent('modal-form', {
      detail: {
        title: 'Tambah Soal',
        fields: [
          { name: 'question_text', label: 'Pertanyaan (Gambar)', type: 'file', value: '' },
          { name: 'options', label: 'Opsi Jawaban', type: 'options', value:[{value: ''}, {value: ''}] },
          { name: 'correct_answer', label: 'Jawaban Benar', type: 'correct', value: '' },
          { name: 'explanation', label: 'Pembahasan (Gambar)', type: 'file', value: '' },
          { name: 'category_id', label: 'Kategori', type: 'select', value: '', options: categoryOptions },
        ],

        onSubmit: async (payload) => {
          const formData = new FormData();

          if (payload.question_text?.file) {
            formData.append('question_text', payload.question_text.file);
          }
          if (payload.explanation?.file) {
            formData.append('explanation', payload.explanation.file);
          }

          
          if (Array.isArray(payload.options)) {
              formData.append('options', JSON.stringify(payload.options));
          }


          let correct = payload.correct_answer;
          if (typeof correct === 'object' && correct.value) {
            correct = correct.value.trim();
          }
          formData.append('correct_answer', correct);

          formData.append('category_id', payload.category_id);
          
          CRUD.save('questions', formData, null, 'tableSoal', { isFormData: true });
        }
      }
    }));
  } catch (err) {
    console.error("DEBUG kategori error:", err);
    CRUD.toast("Gagal memuat kategori", "red");
  }
}


</script>
    
@endpush