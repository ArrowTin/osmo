<div 
  x-show="$store.modalConfirm.open"
  x-transition
  class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
  style="display:none;"
>
  <div class="bg-white dark:bg-gray-800 rounded p-6 w-full max-w-md">
    <h3 class="text-lg font-semibold mb-2" x-text="$store.modalConfirm.title"></h3>
    <p class="text-sm mb-4" x-text="$store.modalConfirm.body"></p>

    <div class="flex justify-end space-x-2">
      <button 
        @click="$store.modalConfirm.close()" 
        class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700"
      >Batal</button>

      <button 
        @click="$store.modalConfirm.confirmAction()" 
        class="px-4 py-2 rounded bg-red-600 text-white"
      >Hapus</button>
    </div>
  </div>
</div>
