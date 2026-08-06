<!-- Global Confirm Modal -->
<div x-data="{
    title: '',
    message: '',
    confirmText: 'Ya, Lanjutkan',
    cancelText: 'Batal',
    action: null,
    processing: false
}"
@open-confirm-modal.window="
    title = $event.detail.title;
    message = $event.detail.message;
    confirmText = $event.detail.confirmText || 'Ya, Lanjutkan';
    cancelText = $event.detail.cancelText || 'Batal';
    action = $event.detail.action;
    $dispatch('open-modal', 'globalConfirmModal');
">
    
    <x-modal name="globalConfirmModal" maxWidth="md">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-t-2xl">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-bold text-slate-900" x-text="title"></h3>
                    <div class="mt-2">
                        <p class="text-sm text-slate-500 font-medium" x-text="message"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100 rounded-b-2xl">
            <button @click="
                processing = true; 
                if(action) action().finally(() => { $dispatch('close'); processing = false; });
            "
                :disabled="processing"
                class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-all items-center gap-2">
                
                <span x-show="!processing" x-text="confirmText"></span>
                <span x-show="processing">Memproses...</span>
                <svg x-show="processing" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
            <button @click="$dispatch('close')" :disabled="processing"
                class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                <span x-text="cancelText"></span>
            </button>
        </div>
    </x-modal>
</div>
