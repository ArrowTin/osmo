<div 
  x-show="$store.modalForm.open"
  x-transition
  class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
  style="display:none;"
>
  <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-2xl dark:text-white flex flex-col max-h-[90vh]">
    
    <!-- Header -->
    <div class="p-4 border-b dark:border-gray-700">
      <h3 class="text-lg font-semibold" x-text="$store.modalForm.title"></h3>
    </div>

    <!-- Body -->
    <div class="p-6 overflow-y-auto flex-1" id="modal-form-content">
      <form @submit.prevent="$store.modalForm.submit()" enctype="multipart/form-data" class="space-y-4">

        <template x-for="(field, index) in $store.modalForm.fields" :key="index">
          <div class="space-y-2">

            <label class="block text-sm font-medium" x-text="field.label"></label>

            <!-- TEXT -->
            <template x-if="!field.type || field.type === 'text'">
              <input x-model="field.value" type="text"
                     class="w-full border rounded px-3 py-2 dark:bg-gray-700" />
            </template>

            <!-- NUMBER -->
            <template x-if="field.type === 'number'">
              <input x-model="field.value" type="number"
                     class="w-full border rounded px-3 py-2 dark:bg-gray-700" />
            </template>

            <!-- DATE_TIME -->
            <template x-if="field.type === 'date_time'">
              <input x-model="field.value" type="datetime-local"
                     class="w-full border rounded px-3 py-2 dark:bg-gray-700" />
            </template>

            <!-- TEXTAREA -->
            <template x-if="field.type === 'textarea'">
              <textarea x-model="field.value"
                        class="w-full border rounded px-3 py-2 dark:bg-gray-700"></textarea>
            </template>

            <!-- FILE -->
            <template x-if="field.type === 'file'">
              <div class="space-y-2">
                <input type="file" 
                       @change="
                         field.file = $event.target.files[0];
                         if(field.file){
                           const reader = new FileReader();
                           reader.onload = e => field.preview = e.target.result;
                           reader.readAsDataURL(field.file);
                         }
                       "
                       class="w-full border rounded px-3 py-2 dark:bg-gray-700" />
                <template x-if="field.preview">
                  <img :src="field.preview" alt="Preview" class="max-h-40 rounded border" />
                </template>
              </div>
            </template>

            <!-- OPTIONS -->
            <template x-if="field.type === 'options'">
              <div>
                <template x-for="(opt, idx) in field.value" :key="idx">
                  <div class="mb-3">
                    <div class="flex items-center space-x-2">
                      <math-field 
                          :value="opt.value"
                          @input="opt.value = $event.target.value"
                          class="flex-1 border rounded px-3 py-2 dark:bg-gray-700"
                      ></math-field>
                      <button type="button"
                        class="px-3 py-2 bg-red-600 text-white rounded disabled:opacity-60"
                        @click="if(field.value.length > 2) field.value.splice(idx,1)"
                        :disabled="field.value.length <= 2">
                        Hapus
                      </button>
                    </div>
                    <!-- Preview KaTeX -->
                    <div class="mt-2 text-blue-700 dark:text-blue-300"
                         x-effect="(() => {
                             const raw = opt.value || '';
                             if (typeof katex !== 'undefined') {
                                 try { $el.innerHTML = katex.renderToString(raw, { throwOnError: false }); }
                                 catch(err) { $el.textContent = raw; }
                             } else { $el.textContent = raw; }
                         })()">
                    </div>
                  </div>
                </template>
                <button type="button" class="mt-2 px-3 py-2 bg-green-600 text-white rounded"
                    @click="field.value.push({ value: '' })">+ Tambah Opsi</button>
              </div>
            </template>

            <!-- CHECKBOXES -->
            <template x-if="field.type === 'checkboxes'">
              <div class="space-y-2">
                <template x-for="opt in field.options" :key="opt.value">
                  <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox"
                          class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded"
                          :value="opt.value"
                          x-model="field.value"
                    />
                    <span x-text="opt.label"></span>
                  </label>
                </template>
              </div>
            </template>

            <!-- CHECKBOXES WITH PREVIEW -->
            <template x-if="field.type === 'checkboxes-with-preview'">
              <div 
                x-data="{
                  showModal: false,
                  modalImage: '',
                  openModal(src) { this.modalImage = src; this.showModal = true },
                  closeModal() { this.showModal = false; this.modalImage = '' }
                }"
                class="space-y-3"
              >
                <template x-for="opt in field.options" :key="opt.value">
                  <div class="flex items-start space-x-3 border rounded p-2 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <input 
                      type="checkbox" 
                      class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded mt-1"
                      :value="opt.value"
                      x-model="field.value"
                    />

                    <div class="flex flex-col space-y-1 flex-1">
                      <span class="font-medium text-sm" x-text="opt.label"></span>

                      <template x-if="opt.img">
                        <img 
                          :src="opt.img" 
                          alt="Preview" 
                          class="w-24 h-24 object-cover rounded border cursor-zoom-in hover:opacity-80 transition"
                          @click="openModal(opt.img)"
                        />
                      </template>
                    </div>
                  </div>
                </template>

                <!-- Modal Preview -->
                <template x-if="showModal">
                  <div 
                    class="fixed inset-0 bg-black/70 flex items-center justify-center z-50"
                    @click.self="closeModal"
                  >
                    <div class="relative">
                      <button 
                        @click="closeModal"
                        class="absolute -top-4 -right-4 bg-red-600 text-white rounded-full px-2 py-1 text-sm hover:bg-red-700"
                      >
                        ✕
                      </button>
                      <img 
                        :src="modalImage" 
                        class="max-w-[90vw] max-h-[80vh] rounded-lg shadow-lg border-2 border-white object-contain"
                      />
                    </div>
                  </div>
                </template>
              </div>
            </template>


        


            <!-- SELECT & MULTI-SELECT -->
            <div 
              x-data="imagePreviewer(field)" 
              x-init="init()"
            >
              <div x-show="['select','multi-select'].includes(field.type)">
                <select 
                    :multiple="field.type === 'multi-select'"
                    x-model="field.value"
                    :name="field.name"
                    @change="updatePreview($event)"
                    class="w-full border rounded px-3 py-2 dark:bg-gray-700"
                >
                  <option x-show="field.type === 'select'" value="">-- Pilih --</option>
                  <template x-for="opt in field.options" :key="opt.value">
                    <option 
                      :value="String(opt.value)" 
                      x-text="opt.label.replace(/<[^>]*>/g, '')"
                    ></option>
                  </template>
                </select>

                <!-- Preview Gambar Soal -->
                <div class="mt-2 flex flex-wrap gap-2">
                  <template x-for="src in selectedImages" :key="src">
                    <img 
                      :src="src" 
                      class="w-20 h-20 object-contain border rounded cursor-zoom-in hover:opacity-80 transition"
                      @click="openModal(src)"
                    />
                  </template>
                </div>

                <!-- Modal Preview Gambar Besar -->
                <template x-if="showModal">
                  <div 
                    class="fixed inset-0 bg-black/70 flex items-center justify-center z-50"
                    @click.self="closeModal"
                  >
                    <div class="relative">
                      <button 
                        @click="closeModal"
                        class="absolute -top-4 -right-4 bg-red-600 text-white rounded-full px-2 py-1 text-sm hover:bg-red-700"
                      >
                        ✕
                      </button>
                      <img 
                        :src="modalImage" 
                        class="max-w-[90vw] max-h-[80vh] rounded-lg shadow-lg border-2 border-white object-contain"
                      />
                    </div>
                  </div>
                </template>
              </div>
            </div>

          


            <!-- CORRECT_ANSWER -->
            <template x-if="field.type === 'correct'">
              <select x-model="field.value"
                      class="w-full border rounded px-3 py-2 dark:bg-gray-700">
                <option value="">-- Pilih Jawaban Benar --</option>
                <template x-for="(opt, idx) in ($store.modalForm.fields.find(f => f.name === 'options')?.value || [])"
                          :key="idx">
                  <option :value="String.fromCharCode(65+idx)"
                          :selected="field.value === String.fromCharCode(65+idx)"
                          x-text="String.fromCharCode(65+idx)+'. '+opt.value">
                  </option>
                </template>
              </select>
            </template>

          </div>
        </template>

      </form>
    </div>

    <!-- Footer -->
    <div class="p-4 border-t dark:border-gray-700 flex justify-end space-x-2">
      <button type="button" @click="$store.modalForm.close()"
              class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700">Batal</button>
      <button type="submit" @click="$store.modalForm.submit()"
              class="px-4 py-2 rounded bg-blue-600 text-white">Simpan</button>
    </div>
  </div>
</div>
