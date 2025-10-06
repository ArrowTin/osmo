<!DOCTYPE html>
<html lang="id" class="scroll-smooth"
      x-data="{ dark: localStorage.getItem('dark') === 'true' }"
      x-init="$watch('dark', val => val 
        ? document.documentElement.classList.add('dark') 
        : document.documentElement.classList.remove('dark'))">

@include('admin.layouts._head')

<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex flex-col">
    @include('admin.layouts._header')

    <div class="flex flex-1 overflow-hidden">
        @include('admin.layouts._sidebar')

        <main class="flex-1 p-4 lg:p-6 overflow-auto">
            @yield('content')
        </main>
    </div>

    @include('admin.layouts._footer')
    @include('admin.layouts._modal-confirm')
    @include('admin.layouts._modal-form')
    @include('admin.layouts._toast')

    <!-- Modal Gambar -->
    <div x-data x-show="$store.imageModal.open" 
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" 
        style="display:none">
        <div class="relative">
        <button @click="$store.imageModal.close()" 
                class="absolute top-2 right-2 text-white text-2xl">&times;</button>
        <img :src="$store.imageModal.src" 
                class="max-w-[90vw] max-h-[90vh] rounded shadow-lg" 
                alt="Gambar"/>
        </div>
    </div>


    <script src="{{ asset('js/app.js') }}"></script>
    <!-- masukkan di <head> atau sebelum </body> -->
    <script>
        function fixLatex(latex) {
            return latex
                .replace(/\\left\s*\\\{\|/g, '\\left.\\right|')
                .replace(/\\bigm\s*\{\|}/g, '\\Big|')
                .replace(/\\bigm\|/g, '\\Big|')
                .replace(/\\bigm\s*\|/g, '\\Big|');
        }
        
        function processNode(node) {
            if (node.nodeType === Node.TEXT_NODE) {
                const fixed = fixLatex(node.textContent);
                if (fixed !== node.textContent) {
                    node.textContent = fixed;
                }
            } else {
                node.childNodes.forEach(processNode);
            }
        }
       
    
    </script>
    
    @stack('scripts')

    
</body>
</html>
