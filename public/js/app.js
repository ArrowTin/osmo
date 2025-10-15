document.addEventListener('alpine:init', () => {

    const safeString = v => (v === null || v === undefined ? '' : String(v));

    // ================= Toast =================
    Alpine.store('toast', {
        show:false,
        message:'',
        color:'green',
        fire(msg, color='green'){
            Object.assign(this, { message: msg, color, show: true });
            setTimeout(() => this.show = false, 2500);
        }
    });

    // ================= Layout =================
    Alpine.store('layout', {
        dark: false,
        sidebarOpen: true,
        toggleSidebar() { this.sidebarOpen = !this.sidebarOpen; },
        toggleDark() {
            this.dark = !this.dark;
            document.documentElement.classList.toggle('dark', this.dark);
        }
    });

    // ================= Image Modal =================
    Alpine.store('imageModal', {
        open: false,
        src: '',
        show(src) { this.src = src; this.open = true; },
        close() { this.open = false; this.src = ''; }
    });
    window.showImageModal = src => Alpine.store('imageModal').show(src);

    // ================= Modal Form =================
    Alpine.store('modalForm', {
        open: false,
        title: '',
        fields: [],
        onSubmit: null,

        show(title, fields = [], onSubmit = null) {
            this.title = title;
            this.fields = fields;
            this.onSubmit = onSubmit;
            this.open = true;

            // Render KaTeX setelah DOM update
            this.$nextTick(() => {
                if(typeof renderMathInElement !== 'undefined'){
                    renderMathInElement(document.querySelector('#modal-form-content'), {
                        delimiters:[
                            {left:'$$', right:'$$', display:true},
                            {left:'$', right:'$', display:false}
                        ],
                        throwOnError:false
                    });
                }
            });
        },

        close() {
            this.open = false;
            this.title = '';
            this.fields = [];
            this.onSubmit = null;
        },

        async submit() {
            if (typeof this.onSubmit !== 'function') return;

            const payload = this.fields.reduce((acc, f) => {

                switch(f.type){
                    case 'file':
                        acc[f.name] = { file: f.file || null, preview: f.preview || null };
                        break;

                    case 'options':
                        if (Array.isArray(f.value)) {
                            const opts = f.value.map(o => o.value?.trim() || '').filter(v => v !== '');
                            if(opts.length < 2){
                                alert('Harap isi minimal 2 opsi jawaban.');
                                throw new Error('Opsi tidak lengkap');
                            }
                            acc[f.name] = opts;
                        } else {
                            acc[f.name] = [];
                        }
                        break;

                    case 'correct':
                        const optsField = this.fields.find(f => f.name === 'options');
                        const optsArray = optsField ? optsField.value.map(o => o.value?.trim() || '') : [];
                        acc[f.name] = (typeof f.value === 'string' && f.value !== '' && optsArray.length)
                            ? optsArray[f.value.charCodeAt(0) - 65] || ''
                            : '';
                        break;

                    default:
                        acc[f.name] = f.value;
                }
                return acc;
            }, {});
            try {
                const result = await this.onSubmit(payload);
                // Jika onSubmit berhasil, baru tutup modal
                this.close();
            } catch (err) {
                console.error("DEBUG submit error:", err);
                Alpine.store('toast').fire('Gagal menyimpan data', 'red');
            }
        }
    });

    // ================= Modal Confirm =================
    Alpine.store('modalConfirm', {
        open: false,
        title: '',
        body: '',
        onConfirm: null,
        show(title, body, onConfirm=null){ Object.assign(this, { title, body, onConfirm, open:true }); },
        close(){ Object.assign(this, { title:'', body:'', onConfirm:null, open:false }); },
        confirmAction(){ if(typeof this.onConfirm==='function') this.onConfirm(); this.close(); }
    });

    // ================= Helper =================
    function normalizeOptions(val) {
        if (!val) return [];
        if (typeof val === 'string') {
            try { val = JSON.parse(val); } catch { return []; }
        }
        if (Array.isArray(val)) return val.map(o => typeof o === 'string' ? { value: o } : { value: o.value || '' });
        if (typeof val === 'object') return Object.values(val).map(v => typeof v==='string' ? { value: v } : { value: v.value || '' });
        return [];
    }

    function wrapKaTeX(str){
        if(!str) return '';
        str = str.trim().replace(/\r?\n/g,''); // hapus newline
        // perbaiki simbol yang bermasalah
        str = fixLatex(str);
        // bungkus dengan $…$ jika belum dibungkus
        if(!/^(\$.*\$|\\\[.*\\\]|\\\(.*\\\))$/.test(str)){
            str = `$${str}$`; // inline
        }
        return str;
    }
    
    

    function renderKaTeXInTable(tableId) {
        if(typeof renderMathInElement === 'undefined') return;
        document.querySelectorAll(`#${tableId} td`).forEach(td => {
            renderMathInElement(td, {
                delimiters: [
                    {left:'$$', right:'$$', display:true},
                    {left:'$', right:'$', display:false},
                    {left:'\\(', right:'\\)', display:false},
                    {left:'\\[', right:'\\]', display:true}
                ],
                throwOnError: false
            });
        });
    }

    // ================= CRUD =================
    window.CRUD = {
        baseUrl:'/api/admin',
        baseStorageUrl:`${window.location.origin}/storage`,
        token: document.querySelector('meta[name="csrf-token"')?.content || '',
        tables:{},
        toast(msg,color='green'){ Alpine.store('toast').fire(msg,color) },

        buildRow(row, keys, endpoint, tableId){
            const getValue = (obj,path)=>path.split('.').reduce((acc,p)=>acc && acc[p],obj);
            const rowData = keys.map(k=>{
                let val = getValue(row,k);

                if(k==='question_text' && row.question_text) 
                    return `<img src="${window.location.origin}/storage/${row.question_text}" class="w-24 h-auto object-contain cursor-pointer" onclick="showImageModal('${window.location.origin}/storage/${row.question_text}')" />`;
                if(k==='explanation' && row.explanation)
                    return `<img src="${window.location.origin}/storage/${row.explanation}" class="w-24 h-auto object-contain cursor-pointer" onclick="showImageModal('${window.location.origin}/storage/${row.explanation}')" />`;

                if(k==='options'){
                    const optsArray = normalizeOptions(val);
                    if(!optsArray.length) return '';
                    return optsArray.map((o,i)=> `${String.fromCharCode(65+i)}. ${wrapKaTeX(o.value)}`).join('<br>');
                }
                
                if(k==='correct_answer'){
                    return wrapKaTeX(val);
                }
                

                if(k==='category_id' || k==='category.name') return safeString(row.category?.name);

                return safeString(val);
            });

             // ================= Gabungkan semua aksi dalam satu kolom =================
            let actionButtons = `
            <button class="text-blue-600" onclick="CRUD.edit(${row.id},'${endpoint}','${tableId}')">Edit</button>
            <button class="text-red-600 ml-2" onclick="CRUD.confirmDelete(${row.id},'${endpoint}','${tableId}')">Hapus</button>
            `;

            if (endpoint === 'users') {
            actionButtons = `
                <button class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600"
                    onclick="resetPassword(${row.id})">Reset</button>
                <button class="bg-blue-600 text-white ml-2 px-2 py-1 rounded hover:bg-blue-600" onclick="CRUD.edit(${row.id},'${endpoint}','${tableId}')">Edit</button>
                <button class="bg-red-600 text-white ml-2 px-2 py-1 rounded hover:bg-red-600" onclick="CRUD.confirmDelete(${row.id},'${endpoint}','${tableId}')">Hapus</button>
            `;
            }

            if (endpoint === 'exams') {
                actionButtons = `
                    <button class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700" onclick="addExamMember(${row.id})">
                        + Anggota
                    </button>
                    <button class="bg-indigo-600 text-white ml-2 px-2 py-1 rounded hover:bg-indigo-700" onclick="addExamQuestion(${row.id})">
                        + Soal
                    </button>
                    <button class="bg-blue-600 text-white ml-2 px-2 py-1 rounded hover:bg-blue-700" onclick="CRUD.edit(${row.id},'${endpoint}','${tableId}')">
                        Edit
                    </button>
                    <button class="bg-red-600 text-white ml-2 px-2 py-1 rounded hover:bg-red-700" onclick="CRUD.confirmDelete(${row.id},'${endpoint}','${tableId}')">
                        Hapus
                    </button>
                `;
            }
            

            rowData.push(actionButtons);
            return rowData;

        },

        async loadTable(tableId, keys, endpoint, columns = []) {
            try {
                const res = await fetch(endpoint);
                if (!res.ok) throw new Error(await res.text());
                const data = await res.json();
                const rows = data.map(r => this.buildRow(r, keys, endpoint.replace(this.baseUrl + '/', ''), tableId));
                const tbl = $(`#${tableId}`);
        
                // Jika sudah ada instance sebelumnya
                if (this.tables[tableId]?.instance) {
                    const dt = this.tables[tableId].instance;
                    dt.clear();
                    dt.rows.add(rows);
                    dt.draw();
                    renderKaTeXInTable(tableId);
                    return;
                }
        
                // Gunakan judul kolom dari parameter "columns"
                const dt = tbl.DataTable({
                    data: rows,
                    columns: keys.map((k, i) => ({
                        title: columns[i] ?? k.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()),
                        createdCell: function (td, cellData) {
                            td.innerHTML = cellData;
                            if (typeof renderMathInElement !== 'undefined') {
                                renderMathInElement(td, {
                                    delimiters: [
                                        { left: '$$', right: '$$', display: true },
                                        { left: '$', right: '$', display: false },
                                        { left: '\\(', right: '\\)', display: false },
                                        { left: '\\[', right: '\\]', display: true }
                                    ],
                                    throwOnError: false
                                });
                            }
                        }
                    })).concat([{ title: columns[keys.length] ?? 'Aksi' }]),
                    scrollX: true,
                    searching: true,
                    pageLength: 10
                });
        
                this.tables[tableId] = { endpoint, instance: dt };
        
            } catch (err) {
                console.error("DEBUG loadTable error:", err);
                this.toast("Gagal memuat data", "red");
            }
        },
        

        async save(endpoint, payload, id = null, tableId = null, opts = {},edit=true) {
            try {
                // Tentukan URL endpoint
                let url;

                if (id && edit && (tableId == 'tableUser' || tableId == 'tableExam')) {
                    // Default: gunakan ID untuk update data biasa
                    url = `${this.baseUrl}/${endpoint}/${id}`;
                } else {
                    // Untuk tableUser & tableExam — gunakan endpoint dasar tanpa ID
                    url = `${this.baseUrl}/${endpoint}`;
                }

        
                // Siapkan opsi fetch
                const fetchOpts = {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': this.token }
                };
        
                if (opts.isFormData) {
                    fetchOpts.body = payload;
                } else {
                    fetchOpts.headers['Content-Type'] = 'application/json';
                    fetchOpts.body = JSON.stringify(payload);
                }
                
        
                // Kirim request
                const res = await fetch(url, fetchOpts);
                const data = await res.json();
        
                // Tangani error validasi atau lainnya
                if (!res.ok) {
                    if (res.status === 422) {
                        this.toast(Object.values(data.errors).flat().join('\n'), 'red');
                        return;
                    }
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
        
                // Notifikasi sukses
                this.toast(id ? 'Data diperbarui!' : 'Data tersimpan!');
        
                // ===============================
                // 🔁 Reload tabel jika diperlukan
                // ===============================
                if (tableId) {
                    const tableEl = document.querySelector(`#${tableId}`);
                    const keys = JSON.parse(tableEl.dataset.keys);
        
                    let route = `${this.baseUrl}/${endpoint}`;
        
                    if (tableId === 'tableUser') {
                        route = route.replace(`/${id}/reset-password`, '');
                    } 
                    else if (tableId === 'tableExam') {
                        // Hilangkan sub-route yang mungkin ada
                        route = route.replace(`/${id}/members`, '')
                                     .replace(`/${id}/questions`, '');
                    }
        
                    this.loadTable(tableId, keys, route);
                }
        
            } catch (err) {
                console.error('DEBUG save error:', err);
                this.toast('Gagal menyimpan data', 'red');
            }
        },
        

        confirmDelete(id,endpoint,tableId){
            window.dispatchEvent(new CustomEvent('modal-confirm',{
                detail:{title:'Konfirmasi Hapus',body:'Yakin ingin menghapus data ini?',onConfirm:()=>this.delete(endpoint,id,tableId)}
            }));
        },

        async delete(endpoint,id,tableId){
            try{
                const res=await fetch(`${this.baseUrl}/${endpoint}/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':this.token}});
                if(!res.ok) throw new Error(await res.text());
                this.toast('Data dihapus!');
                if(tableId) this.loadTable(tableId,JSON.parse(document.querySelector(`#${tableId}`).dataset.keys),`${this.baseUrl}/${endpoint}`);
            }catch(err){ console.error("DEBUG delete error:",err); this.toast("Gagal menghapus data","red"); }
        },

        async edit(id, endpoint, tableId) {
            try {
                const res = await fetch(`${this.baseUrl}/${endpoint}/${id}`);
                if(!res.ok) throw new Error(await res.text());
                const row = await res.json();
    
                let fields = [];
                if(endpoint === 'users') {
                    fields = [
                        { name: 'name', label: 'Nama', type: 'text', value: row.name ?? '' },
                        { name: 'username', label: 'Username', type: 'text', value: row.username ?? '' },
                        { name: 'password', label: 'Password', type: 'text', value: '', placeholder: 'Kosongkan jika tidak ingin mengubah' }
                    ];
                }else if(endpoint === 'categories') {
                    // Modal khusus kategori
                    fields = [
                        { name: 'name', label: 'Nama', type: 'text', value: row.name ?? '' },
                    ];
                } else if(endpoint === 'questions') {
                    // Modal khusus question
                    const catRes = await fetch('/api/admin/categories');
                    if(!catRes.ok) throw new Error(await catRes.text());
                    const categories = await catRes.json();
                    const categoryOptions = categories.map(c => ({ value: String(c.id), label: c.name }));
                    const categoryValue = row.category_id != null ? String(row.category_id) : '';
    
                    const options = normalizeOptions(row.options);
                    let correctIndex = options.findIndex(o => o.value.trim() === (row.correct_answer?.trim()||'')); 
                    if(correctIndex === -1) correctIndex = 0;
                    const correctValue = String.fromCharCode(65 + correctIndex);
    
                    fields = [
                        { name:'question_text', label:'Pertanyaan (Gambar)', type:'file', value:'', file:null, preview: row.question_text ? `${this.baseStorageUrl}/${row.question_text}` : null },
                        { name:'options', label:'Opsi Jawaban', type:'options', value: options },
                        { name:'correct_answer', label:'Jawaban Benar', type:'correct', value: correctValue },
                        { name:'explanation', label:'Pembahasan (Gambar)', type:'file', value:'', file:null, preview: row.explanation ? `${this.baseStorageUrl}/${row.explanation}` : null },
                        { name:'category_id', label:'Kategori', type:'select', value: categoryValue, options: categoryOptions }
                    ];
                }
                else if (endpoint === 'exams') {
                    fields = [
                        { name: 'name', label: 'Nama Ujian', type: 'text', value: row.name ?? '' },
                        { name: 'duration', label: 'Durasi (menit)', type: 'number', value: row.duration ?? 60 },
                        { name: 'start_time', label: 'Waktu Mulai', type: 'date_time', value: row.start_time ? row.start_time.replace(' ', 'T') : '' }
                    ];
                }
    
                window.dispatchEvent(new CustomEvent('modal-form', {
                    detail: {
                        title: endpoint === 'users' ? (id ? 'Edit User' : 'Tambah User') : (endpoint === 'categories' ? 'Edit Kategori' : 'Edit Soal'),
                        fields,
                        onSubmit: payload => {
                            const formData = new FormData();
                            
                            if(id) formData.append('_method', 'PUT'); // Laravel handle update
                            
                            if(endpoint === 'users') {
                                formData.append('name', payload.name);
                                formData.append('username', payload.username);
                                if(payload.password) formData.append('password', payload.password); // hanya simpan jika diisi
                            } 
                            else if(endpoint === 'categories') {
                                formData.append('name', payload.name);
                            } 
                            else if(endpoint === 'questions') {
                                if(payload.question_text?.file) formData.append('question_text', payload.question_text.file);
                                if(payload.explanation?.file) formData.append('explanation', payload.explanation.file);
                                if(Array.isArray(payload.options)) formData.append('options', JSON.stringify(payload.options.map(o => o.value ?? o)));
                                let correct = payload.correct_answer;
                                if(typeof correct === 'object' && correct.value) correct = correct.value.trim();
                                formData.append('correct_answer', correct);
                                formData.append('category_id', payload.category_id);
                            }

                            else if (endpoint === 'exams') {
                                formData.append('name', payload.name);
                                formData.append('duration', payload.duration);
                                formData.append('start_time', payload.start_time);
                            }
                
                            // Simpan ke endpoint
                            CRUD.save(endpoint, formData, id, tableId, { isFormData: true });
                        }
                    }
                }));
                
    
            } catch(err) {
                console.error("DEBUG edit error:", err);
                this.toast("Data tidak ditemukan", "red");
            }
        },

        questionFields: (row={}) => [
            {name:'question_text',label:'Pertanyaan',type:'file',value:'',file:null,preview:row.question_text_url??null},
            {name:'options',label:'Opsi Jawaban',type:'options',value:normalizeOptions(row.options)},
            {name:'correct_answer',label:'Jawaban Benar',type:'correct',value:row.correct_answer??''},
            {name:'explanation',label:'Penjelasan',type:'file',value:'',file:null,preview:row.explanation_url??null},
            {name:'category_id',label:'Kategori',type:'select',value:row.category_id!=null?String(row.category_id).trim():'',options:[]}
        ]

        

    };

   

    
    // ================= Show Options Button =================
    document.addEventListener('click', e=>{
        if(e.target.classList.contains('show-options-btn')){
            document.querySelectorAll('.options-select').forEach(s=>s.classList.remove('hidden'));
            document.querySelectorAll('.show-options-btn').forEach(b=>b.classList.add('hidden'));
        }
    });

    // ================= Event Listener Modal =================
    window.addEventListener('modal-confirm', e=>Object.assign(Alpine.store('modalConfirm'), {...e.detail, open:true}));
    // ================= Event Listener Modal =================
    window.addEventListener('modal-form', e => {
        console.log(e);
        
        if (!e.detail || !e.detail.fields || !Array.isArray(e.detail.fields) || e.detail.fields.length === 0) {
            console.warn('modal-form event tanpa fields diabaikan:', e.detail);
            return;
        }

        const store = Alpine.store('modalForm');
        Object.assign(store, {...e.detail, open:true});

        // Paksa rebind select setelah semua field dirender
        Alpine.nextTick(() => {
            store.fields.forEach(f => {
                const selEl = document.querySelector(`select[name="${f.name}"]`);
                if (!selEl) return;
    
                // Untuk select tunggal
                if (f.type === 'select' && f.value !== '') {
                    selEl.value = f.value;
                    selEl.dispatchEvent(new Event('change', { bubbles: true }));
                }

                 // ✅ Untuk multi-select
                if (f.type === 'multi-select' && Array.isArray(f.value)) {
                    for (const opt of selEl.options) {
                        opt.selected = f.value.includes(String(opt.value));
                    }
                    // Sinkronkan manual ke Alpine
                    selEl.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        });
    });

// ================= Tambah Anggota Ujian =================
window.addExamMember = async function(examId){
    try {
        // Ambil semua user
        const resUsers = await fetch('/api/admin/users');
        if(!resUsers.ok) throw new Error(await resUsers.text());
        const users = await resUsers.json();

        // Ambil user yang sudah jadi anggota
        const resMembers = await fetch(`/api/admin/exams/${examId}/members`);
        if(!resMembers.ok) throw new Error(await resMembers.text());
        const existingMembers = await resMembers.json();
        const selectedIds = existingMembers.map(m => String(m.id));

        // Buat opsi checkbox
        const userOptions = users.map(u => ({
            value: String(u.id),
            label: u.name
        }));

        // Buka modal
        window.dispatchEvent(new CustomEvent('modal-form', {
            detail: {
                title: 'Tambah Anggota Ujian',
                fields: [
                    {
                        name: 'user_ids',
                        label: 'Pilih User',
                        type: 'checkboxes', // 🧩 ubah ke checkboxes
                        value: selectedIds, // yang sudah ada, otomatis tercentang
                        options: userOptions
                    }
                ],
                onSubmit: (payload) => {
                    CRUD.save(`exams/${examId}/members`, payload, examId, 'tableExam',{},false);
                }
            }
        }));

    } catch (err) {
        console.error('DEBUG addExamMember error:', err);
        CRUD.toast('Gagal memuat daftar user', 'red');
    }
};



// ================= Tambah Soal ke Ujian =================
window.addExamQuestion = async function(examId){
    try {
        const baseUrl = `${window.location.origin}/storage`;

        // Ambil semua soal
        const resQuestions = await fetch('/api/admin/questions');
        if(!resQuestions.ok) throw new Error(await resQuestions.text());
        const questions = await resQuestions.json();

        // Ambil soal yang sudah ada di ujian
        const resExisting = await fetch(`/api/admin/exams/${examId}/questions`);
        if(!resExisting.ok) throw new Error(await resExisting.text());
        const existingQuestions = await resExisting.json(); 
        const selectedIds = existingQuestions.map(q => String(q.id));

        // Bangun opsi checkbox + gambar
        const questionOptions = questions.map((q,i) => {
            const imgUrl = q.question_text ? `${baseUrl}/${q.question_text}` : null; // pastikan field gambar benar
            const label = `#${i+1} - ${q.category?.name || 'Tanpa Kategori'}`;
            return {
                value: String(q.id),
                label: label,
                img: imgUrl
            };
        });

        // Tampilkan modal form dengan tipe custom
        window.dispatchEvent(new CustomEvent('modal-form', {
            detail: {
                title: 'Tambah Soal ke Ujian',
                fields: [
                    {
                        name: 'question_ids',
                        label: 'Pilih Soal (dengan gambar)',
                        type: 'checkboxes-with-preview', // 🔥 pakai tipe baru
                        value: selectedIds, // otomatis tercentang
                        options: questionOptions
                    }
                ],
                onSubmit: (payload) => {
                    CRUD.save(`exams/${examId}/questions`, payload, examId, 'tableExam',{},false);
                }
            }
        }));

    } catch (err) {
        console.error('DEBUG addExamQuestion error:', err);
        CRUD.toast('Gagal memuat bank soal', 'red');
    }
};



window.addEventListener('alpine:init', () => {
    Alpine.data('imagePreviewer', (field) => ({
      field,
      selectedImages: [],
      showModal: false,
      modalImage: '',
  
      init() {
        if (this.field.type === 'multi-select' && !Array.isArray(this.field.value)) {
          this.field.value = [];
        }
        this.updatePreview();
      },
  
      updatePreview() {
        if (!this.field || !Array.isArray(this.field.options)) return;
        if (!['select', 'multi-select'].includes(this.field.type)) return;
  
        this.selectedImages = this.field.options
          .filter(opt => {
            const valMatch = this.field.value.includes(String(opt.value));
            const hasImg = typeof opt.label === 'string' && opt.label.includes('<img');
            return valMatch && hasImg;
          })
          .map(opt => {
            const match = opt.label.match(/src=["'](.*?)["']/);
            return match ? match[1] : null;
          })
          .filter(Boolean);
      },
  
      openModal(src) {
        this.modalImage = src;
        this.showModal = true;
      },
  
      closeModal() {
        this.showModal = false;
        this.modalImage = '';
      }
    }));
  });
  

});
