<div role="status" id="toasts" x-data="toastHub(@js($toasts), @js($config))" @class([
    'fixed z-50 p-4 space-y-3 bottom-0 w-full pointer-events-none flex flex-col sm:p-6',
    'items-start' => $position->is('left'),
    'items-center' => $position->is('center'),
    'items-end' => $position->is('right'),
 ])>
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.isVisible"
             x-init="$nextTick(() => toast.show())"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 {{ $position->is('left') ? 'sm:-translate-x-10' : 'sm:translate-x-10' }}"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave-end="opacity-0"
             class="duration-300 transition ease-in-out"
             :data-toast="toast.id"
        >
            <p class="flex items-center self-center max-w-sm px-6 py-3 rounded shadow-lg text-center text-white"
               :class="toast.select({ error: 'bg-red-500', info: 'bg-gray-200 text-black', success: 'bg-green-600', warning: 'bg-orange-500' })"
            >
                <span x-text="toast.message" class="flex-1"></span>
            </p>
        </div>
    </template>
</div>
