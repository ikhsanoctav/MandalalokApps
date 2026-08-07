@extends('layouts.petugas')
@section('content')

<div class="mb-6 flex flex-col justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Scanner Kehadiran Pelatihan</h1>
        <p class="text-sm text-slate-700 mt-1">Gunakan kamera untuk memindai tiket (QR Code) peserta.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-1">
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl border shadow-sm p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Pengaturan Pemindai</h2>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Pelatihan <span class="text-red-500">*</span></label>
                <select id="pelatihan_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-sm" required>
                    <option value="">-- Pilih Pelatihan --</option>
                    @foreach($pelatihans as $p)
                        <option value="{{ $p->id }}">{{ $p->judul }} ({{ $p->tanggal_mulai->format('d M') }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-6 p-4 bg-blue-50 text-blue-700 rounded-xl border border-blue-200 text-sm">
                <i class="fa-solid fa-circle-info mr-1"></i> Pastikan Anda telah memilih pelatihan sebelum melakukan pemindaian.
            </div>
        </div>

        <div id="scan-result-card" class="mt-6 bg-white/90 backdrop-blur-sm rounded-3xl border shadow-sm p-6 hidden">
            <h3 class="font-bold text-slate-800 mb-3 border-b pb-2">Hasil Pindai Terakhir</h3>
            <div id="scan-result-content" class="text-sm"></div>
        </div>
    </div>

    <div class="md:col-span-2">
        <div class="bg-black rounded-3xl border shadow-sm overflow-hidden flex flex-col items-center justify-center min-h-[400px] relative">
            
            <div id="reader" class="w-full h-full max-w-lg mx-auto"></div>
            
            <div id="overlay" class="absolute inset-0 bg-black/80 flex flex-col items-center justify-center z-10 p-6 text-center">
                <i class="fa-solid fa-qrcode text-5xl text-white/50 mb-4"></i>
                <p class="text-white font-medium mb-6">Pilih pelatihan terlebih dahulu untuk mengaktifkan kamera pemindai.</p>
                <button id="btn-start" class="btn-primary px-6 py-2.5 text-sm font-bold text-white rounded-xl transition-all shadow-md opacity-50 cursor-not-allowed" disabled>
                    Mulai Pemindai
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pelatihanSelect = document.getElementById('pelatihan_id');
        const btnStart = document.getElementById('btn-start');
        const overlay = document.getElementById('overlay');
        const resultCard = document.getElementById('scan-result-card');
        const resultContent = document.getElementById('scan-result-content');
        
        let html5QrcodeScanner = null;
        let isScanning = false;
        let lastScanCode = null;
        let lastScanTime = 0;

        // Cek perubahan pilihan pelatihan
        pelatihanSelect.addEventListener('change', function() {
            if (this.value) {
                btnStart.disabled = false;
                btnStart.classList.remove('opacity-50', 'cursor-not-allowed');
                overlay.querySelector('p').innerText = "Siap untuk memindai tiket peserta.";
            } else {
                btnStart.disabled = true;
                btnStart.classList.add('opacity-50', 'cursor-not-allowed');
                overlay.querySelector('p').innerText = "Pilih pelatihan terlebih dahulu untuk mengaktifkan kamera pemindai.";
                if (isScanning) {
                    stopScanner();
                }
            }
        });

        btnStart.addEventListener('click', function() {
            if (pelatihanSelect.value) {
                if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
                    alert('Perhatian: Fitur kamera browser diblokir jika situs diakses melalui HTTP biasa (tanpa SSL). Harap pasang SSL (https://) pada VPS Anda.');
                }
                overlay.classList.add('hidden');
                startScanner();
            }
        });

        function startScanner() {
            if (!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "reader", { fps: 10, qrbox: {width: 250, height: 250}, aspectRatio: 1.0 }, false);
                
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                isScanning = true;
            }
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
                isScanning = false;
                overlay.classList.remove('hidden');
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            // Anti-bounce: Jangan proses kode yang sama jika kurang dari 3 detik
            const currentTime = new Date().getTime();
            if (decodedText === lastScanCode && (currentTime - lastScanTime) < 3000) {
                return;
            }
            
            lastScanCode = decodedText;
            lastScanTime = currentTime;

            const pelatihanId = pelatihanSelect.value;
            if (!pelatihanId) return;

            // Pause scanner while processing (optional, UI feedback is enough)
            showLoadingResult();

            // Send AJAX request
            fetch('{{ route("operator.pelatihan.scanner.scan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    kode_tiket: decodedText,
                    pelatihan_id: pelatihanId
                })
            })
            .then(response => response.json())
            .then(data => {
                showScanResult(data);
            })
            .catch(error => {
                showScanError();
                console.error('Error:', error);
            });
        }

        function onScanFailure(error) {
            // silent failure (normal while scanning empty space)
        }

        function showLoadingResult() {
            resultCard.classList.remove('hidden');
            resultContent.innerHTML = `
                <div class="flex items-center justify-center p-4">
                    <i class="fa-solid fa-spinner fa-spin text-2xl text-blue-500"></i>
                    <span class="ml-3 text-slate-600 font-medium">Memproses tiket...</span>
                </div>
            `;
        }

        function showScanResult(data) {
            resultCard.classList.remove('hidden');
            
            if (data.success) {
                resultContent.innerHTML = `
                    <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                        <div class="flex items-center gap-3 mb-2">
                            <i class="fa-solid fa-circle-check text-2xl text-emerald-500"></i>
                            <div>
                                <h4 class="font-bold text-emerald-800">Berhasil!</h4>
                                <p class="text-xs text-emerald-600">${data.message}</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-emerald-200/50">
                            <p class="text-sm font-semibold text-slate-800">${data.data.nama}</p>
                            <p class="text-xs text-slate-700">Waktu Hadir: ${data.data.waktu}</p>
                        </div>
                    </div>
                `;
            } else {
                const isAlreadyPresent = data.message.includes('sebelumnya');
                const bgColor = isAlreadyPresent ? 'bg-amber-50 border-amber-200' : 'bg-red-50 border-red-200';
                const iconColor = isAlreadyPresent ? 'text-amber-500' : 'text-red-500';
                const iconClass = isAlreadyPresent ? 'fa-triangle-exclamation' : 'fa-circle-xmark';
                
                let detailHtml = '';
                if (data.data && data.data.nama) {
                    detailHtml = `
                        <div class="mt-3 pt-3 border-t border-slate-200/50">
                            <p class="text-sm font-semibold text-slate-800">${data.data.nama}</p>
                        </div>
                    `;
                }

                resultContent.innerHTML = `
                    <div class="p-4 rounded-xl border ${bgColor}">
                        <div class="flex items-center gap-3 mb-2">
                            <i class="fa-solid ${iconClass} text-2xl ${iconColor}"></i>
                            <div>
                                <h4 class="font-bold text-slate-800">${isAlreadyPresent ? 'Peringatan' : 'Gagal'}</h4>
                                <p class="text-xs text-slate-600 leading-tight">${data.message}</p>
                            </div>
                        </div>
                        ${detailHtml}
                    </div>
                `;
            }
        }

        function showScanError() {
            resultCard.classList.remove('hidden');
            resultContent.innerHTML = `
                <div class="p-4 bg-red-50 rounded-xl border border-red-200 flex items-center gap-3">
                    <i class="fa-solid fa-circle-xmark text-2xl text-red-500"></i>
                    <div>
                        <h4 class="font-bold text-slate-800">Terjadi Kesalahan</h4>
                        <p class="text-xs text-slate-600">Gagal menghubungi server. Periksa koneksi internet.</p>
                    </div>
                </div>
            `;
        }
    });
</script>
@endpush

@endsection
