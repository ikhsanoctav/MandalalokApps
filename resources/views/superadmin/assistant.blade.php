@extends('layouts.superadmin')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Asisten Eksekutif AI</h1>
        <p class="text-sm text-slate-700">Tanyakan data UMKM Mandalajati dalam bahasa sehari-hari.</p>
    </div>
</div>
@endsection

@section('content')
<div class="flex flex-col h-[70vh] max-h-[800px] bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Chat Messages -->
    <div id="chatMessages" class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50">
        <!-- Welcome Message -->
        <div class="flex gap-4">
            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-robot"></i>
            </div>
            <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 max-w-[80%]">
                <p class="text-slate-700 text-sm leading-relaxed">
                    Halo, Pimpinan! Saya adalah <strong>AI Asisten Eksekutif</strong>.
                    <br><br>
                    Anda bisa menanyakan jumlah atau daftar UMKM secara langsung. Contoh pertanyaan yang bisa saya jawab:
                    <ul class="list-disc ml-5 mt-2 space-y-1 text-slate-600">
                        <li>"Ada berapa total UMKM Kuliner di Kelurahan Jatihandap?"</li>
                        <li>"Tolong tampilkan daftar UMKM Jasa di Pasirlayung."</li>
                        <li>"Berapa jumlah UMKM Kriya yang terverifikasi?"</li>
                    </ul>
                </p>
            </div>
        </div>
    </div>

    <!-- Typing Indicator -->
    <div id="typingIndicator" class="hidden px-6 py-2 bg-slate-50">
        <div class="flex items-center gap-2 text-slate-400 text-sm">
            <i class="fas fa-circle-notch fa-spin"></i> AI sedang menganalisis database...
        </div>
    </div>

    <!-- Input Area -->
    <div class="p-4 bg-white border-t border-slate-200">
        <form id="chatForm" class="flex gap-4" onsubmit="event.preventDefault(); sendQuery();">
            <input type="text" id="queryInput" required autocomplete="off"
                placeholder="Ketik pertanyaan Anda di sini..." 
                class="flex-1 px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all outline-none bg-slate-50">
            
            <button type="submit" id="sendBtn"
                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-colors shadow-sm flex items-center gap-2">
                <span>Kirim</span>
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<script>
    async function sendQuery() {
        const inputEl = document.getElementById('queryInput');
        const sendBtn = document.getElementById('sendBtn');
        const messagesEl = document.getElementById('chatMessages');
        const typingIndicator = document.getElementById('typingIndicator');
        
        const message = inputEl.value.trim();
        if (!message) return;
        
        // Append User Message
        const userHtml = `
        <div class="flex gap-4 justify-end">
            <div class="bg-blue-600 text-white p-4 rounded-2xl rounded-tr-none shadow-sm max-w-[80%]">
                <p class="text-sm">${message}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user"></i>
            </div>
        </div>`;
        messagesEl.insertAdjacentHTML('beforeend', userHtml);
        messagesEl.scrollTop = messagesEl.scrollHeight;
        
        // Clear & Disable
        inputEl.value = '';
        inputEl.disabled = true;
        sendBtn.disabled = true;
        typingIndicator.classList.remove('hidden');
        
        try {
            const response = await fetch('{{ route("superadmin.assistant.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message })
            });
            
            const data = await response.json();
            
            // Format marked down text to HTML roughly
            let replyText = data.reply.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');
            
            // Append Bot Message
            const botHtml = `
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 max-w-[80%]">
                    <p class="text-slate-700 text-sm leading-relaxed">${replyText}</p>
                </div>
            </div>`;
            messagesEl.insertAdjacentHTML('beforeend', botHtml);
            
        } catch (error) {
            console.error('Error:', error);
            const errorHtml = `
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="bg-red-50 p-4 rounded-2xl rounded-tl-none border border-red-100">
                    <p class="text-red-600 text-sm">Terjadi kesalahan saat menghubungi server AI.</p>
                </div>
            </div>`;
            messagesEl.insertAdjacentHTML('beforeend', errorHtml);
        } finally {
            typingIndicator.classList.add('hidden');
            inputEl.disabled = false;
            sendBtn.disabled = false;
            inputEl.focus();
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }
    }
</script>
@endsection
