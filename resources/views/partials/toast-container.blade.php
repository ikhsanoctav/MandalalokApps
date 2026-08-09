    
    <div id="toastContainer" class="fixed top-20 right-6 z-[999999] flex flex-col gap-3 pointer-events-none"></div>

    <script>
        // Global function untuk show toast
        window.clearToasts = function() {
            const container = document.getElementById('toastContainer');
            if (container) {
                container.innerHTML = '';
            }
        };

        window.showToast = function(type, title, message) {
            window.clearToasts();
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const toastId = 'toast-' + type + '-' + Date.now();
            
            const bgColors = {
                success: 'bg-emerald-500',
                error: 'bg-red-500',
                danger: 'bg-red-500',
                warning: 'bg-amber-500',
                info: 'bg-blue-500'
            };
            const borderColors = {
                success: 'border-emerald-500',
                error: 'border-red-500',
                danger: 'border-red-500',
                warning: 'border-amber-500',
                info: 'border-blue-500'
            };
            const textColors = {
                success: 'text-emerald-500',
                error: 'text-red-500',
                danger: 'text-red-500',
                warning: 'text-amber-500',
                info: 'text-blue-500'
            };

            const icons = {
                success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l-2-2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                danger: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l-2-2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
                info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
            };

            const bgColor = bgColors[type] || bgColors.success;
            const borderColor = borderColors[type] || borderColors.success;
            const textColor = textColors[type] || textColors.success;
            const icon = icons[type] || icons.success;

            const toastHtml = `
            <div id="${toastId}" class="transform transition-all duration-500 translate-x-full opacity-0 pointer-events-auto" style="min-width: 320px; max-width: 420px;">
                <div class="bg-white rounded-xl shadow-2xl border-l-4 ${borderColor} overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full ${bgColor} bg-opacity-20 flex items-center justify-center">
                                    <svg class="w-5 h-5 ${textColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        ${icon}
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-slate-800">${title}</h4>
                                <p class="text-sm text-slate-600 mt-0.5">${message}</p>
                            </div>
                            <button onclick="document.getElementById('${toastId}').remove()" class="flex-shrink-0 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="h-1 ${bgColor} animate-progress" style="width: 100%; animation: progress 7s linear forwards;"></div>
                </div>
            </div>
        `;

            container.insertAdjacentHTML('beforeend', toastHtml);

            const toastElement = document.getElementById(toastId);
            setTimeout(() => {
                toastElement.classList.remove('translate-x-full', 'opacity-0');
                toastElement.classList.add('translate-x-0', 'opacity-100');
            }, 100);

            setTimeout(() => {
                if (toastElement) {
                    toastElement.classList.remove('translate-x-0', 'opacity-100');
                    toastElement.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => toastElement.remove(), 500);
                }
            }, 7500);
        };
    </script>

    @if (session('toast'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toast = @json(session('toast'));
                if (window.showToast) {
                    window.showToast(toast.type, toast.title, toast.message);
                }
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.showToast) {
                    window.showToast('success', 'Berhasil!', @json(session('success')));
                }
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.showToast) {
                    window.showToast('danger', 'Gagal!', @json(session('error')));
                }
            });
        </script>
    @endif

    @if (session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.showToast) {
                    window.showToast('warning', 'Perhatian!', @json(session('warning')));
                }
            });
        </script>
    @endif

    @if (session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.showToast) {
                    window.showToast('info', 'Informasi', @json(session('info')));
                }
            });
        </script>
    @endif

    @if (session('status'))
        @php
            $statusMessages = [
                'profile-updated' => 'Profil berhasil diperbarui!',
                'password-updated' => 'Kata sandi berhasil diperbarui!',
                'verification-link-sent' => 'Tautan verifikasi baru telah dikirim ke email Anda!',
            ];
            $statusText = $statusMessages[session('status')] ?? session('status');
        @endphp
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.showToast) {
                    window.showToast('success', 'Berhasil!', @json($statusText));
                }
            });
        </script>
    @endif

    <style>
        @keyframes progress {
            0% {
                width: 100%;
            }

            100% {
                width: 0%;
            }
        }

        .animate-progress {
            animation: progress 7s linear forwards;
        }
    </style>
