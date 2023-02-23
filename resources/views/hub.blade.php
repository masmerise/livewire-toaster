<div role="status" id="toaster" x-data="toasterHub(@js($toasts), @js($config))" @class([
    'fixed z-50 p-4 space-y-3 bottom-0 w-full flex flex-col sm:p-6',
    'items-start' => $position->is('left'),
    'items-center' => $position->is('center'),
    'items-end' => $position->is('right'),
 ])>
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.isVisible"
             x-init="$nextTick(() => toast.show($el))"
             x-transition:enter-start="translate-y-12 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave-end="opacity-0 scale-90"
             class="relative duration-300 transform transition ease-in-out max-w-xs w-full {{ $position->is('center') ? 'text-center' : 'text-left' }}"
             :class="toast.select({ error: 'text-white', info: 'text-black', success: 'text-white', warning: 'text-white' })"
        >
            <i x-text="toast.message"
               class="inline-block select-none not-italic px-6 py-3 rounded shadow-lg text-sm w-full"
               :class="toast.select({ error: 'bg-red-500', info: 'bg-gray-200', success: 'bg-green-600', warning: 'bg-orange-500' })"
            ></i>

            <button @click="toast.dispose()" class="absolute top-0 right-0 p-2 focus:outline-none">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </template>
</div>
