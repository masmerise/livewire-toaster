<div role="status"
     id="toasts"
     x-data="toastHub(@js($toasts)))"
     class="fixed bottom-0 z-50 p-4 space-y-3 w-full pointer-events-none sm:p-6 pointer-events-none flex flex-col items-center"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible"
             x-init="$nextTick(() => { toast.visible = true; })"
             x-transition:enter="transform ease-out duration-500 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-10"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
        >
            <p class="bg-gray-600 flex items-center self-center max-w-sm px-6 py-3 text-black rounded shadow-lg text-center">
                <span x-text="toast.message" class="flex-1"></span>
            </p>
        </div>
    </template>
</div>
