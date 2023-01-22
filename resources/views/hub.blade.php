<div role="status" id="toaster" x-data="toasterHub(@js($toasts), @js($config))" @class([
    'fixed z-50 p-4 space-y-3 bottom-0 w-full pointer-events-none flex flex-col sm:p-6',
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
             class="duration-300 transform transition ease-in-out max-w-xs w-full {{ $position->is('center') ? 'text-center' : 'text-left' }}"
        >
            <i x-text="toast.message"
               class="inline-block not-italic px-6 py-3 rounded shadow-lg text-white text-sm w-full"
               :class="toast.select({ error: 'bg-red-500', info: 'bg-gray-200 text-black', success: 'bg-green-600', warning: 'bg-orange-500' })"
            ></i>
        </div>
    </template>
</div>
