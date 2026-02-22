<div role="status" id="toaster" x-data="toasterHub(@js($toasts), @js($config))" @class([
    'fixed z-50 p-4 w-full flex flex-col pointer-events-none sm:p-6',
    'bottom-0' => $alignment->is('bottom'),
    'top-1/2 -translate-y-1/2' => $alignment->is('middle'),
    'top-0' => $alignment->is('top'),
    'items-start rtl:items-end' => $position->is('left'),
    'items-center' => $position->is('center'),
    'items-end rtl:items-start' => $position->is('right'),
 ])>
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.isVisible"
             x-init="$nextTick(() => toast.show($el))"
             @if($alignment->is('bottom'))
             x-transition:enter-start="translate-y-12 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             @elseif($alignment->is('top'))
             x-transition:enter-start="-translate-y-12 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             @else
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             @endif
             x-transition:leave-end="opacity-0 scale-90"
             @class(['relative duration-300 transform transition ease-in-out max-w-xs w-full pointer-events-auto', 'text-center' => $position->is('center')])
             :class="toast.select({ error: 'text-white', info: 'text-black', success: 'text-white', warning: 'text-white' })"
        >
            <i x-text="toast.message"
               class="inline-block select-none not-italic px-6 py-3 rounded rounded-sm shadow-lg text-sm w-full {{ $alignment->is('bottom') ? 'mt-3' : 'mb-3' }}"
               :class="toast.select({ error: 'bg-red-500', info: 'bg-gray-200', success: 'bg-green-600', warning: 'bg-orange-500' })"
            ></i>

            @includeWhen($closeable, 'toaster::close-button')
        </div>
    </template>
</div>
