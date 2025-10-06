<div 
  x-data 
  x-show="$store.toast.show"
  x-transition
  class="fixed bottom-4 right-4 z-50 px-4 py-2 rounded shadow text-white"
  :class="$store.toast.color === 'green' ? 'bg-green-600' : 'bg-red-600'">
    <span x-text="$store.toast.message"></span>
</div>
