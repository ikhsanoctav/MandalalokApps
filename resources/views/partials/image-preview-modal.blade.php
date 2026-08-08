<!-- Global Image Preview Modal / Lightbox -->
<div id="globalImageModal" class="fixed inset-0 z-[999999] hidden bg-black/90 backdrop-blur-md transition-all duration-300 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-5xl max-h-[92vh] flex flex-col items-center justify-center" onclick="event.stopPropagation()">
        <button type="button" onclick="closeImageModal()" class="absolute -top-12 right-0 text-white/80 hover:text-white hover:bg-white/10 rounded-full p-2 transition-all focus:outline-none" title="Tutup (Esc)">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="globalModalImage" src="" alt="Preview Gambar" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10 select-none">
    </div>
</div>

<script>
    (function() {
        window.openImageModal = function(imageUrl) {
            if (!imageUrl) return;
            
            let modal = document.getElementById('globalImageModal') || document.getElementById('imageModal');
            let modalImage = document.getElementById('globalModalImage') || document.getElementById('modalImage');

            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'globalImageModal';
                modal.className = 'fixed inset-0 z-[999999] hidden bg-black/90 backdrop-blur-md transition-all duration-300 flex items-center justify-center p-4';
                modal.onclick = function() { window.closeImageModal(); };
                modal.innerHTML = `
                    <div class="relative max-w-5xl max-h-[92vh] flex flex-col items-center justify-center" onclick="event.stopPropagation()">
                        <button type="button" onclick="window.closeImageModal()" class="absolute -top-12 right-0 text-white/80 hover:text-white hover:bg-white/10 rounded-full p-2 transition-all focus:outline-none" title="Tutup (Esc)">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <img id="globalModalImage" src="" alt="Preview Gambar" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10 select-none">
                    </div>
                `;
                document.body.appendChild(modal);
                modalImage = document.getElementById('globalModalImage');
            }

            if (modalImage) {
                modalImage.src = imageUrl;
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.closeImageModal = function() {
            const globalModal = document.getElementById('globalImageModal');
            if (globalModal) globalModal.classList.add('hidden');

            const oldModal = document.getElementById('imageModal');
            if (oldModal) oldModal.classList.add('hidden');

            document.body.style.overflow = '';
        };

        // Keyboard navigation (Esc key to close)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.closeImageModal();
            }
        });
    })();
</script>
