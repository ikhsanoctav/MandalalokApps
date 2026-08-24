<!-- AI Chatbot Widget (Floating Pop-up) -->
<div class="chatbot-btn" id="chatbotToggleBtn" onclick="toggleChatbot()">
    <img src="{{ asset('images/chatbot-avatar.png') }}" alt="AI">
</div>

<div class="chatbot-window" id="chatbotWindow">
    <div class="chatbot-header">
        <div class="chatbot-header-info">
            <div class="chatbot-avatar" style="overflow: hidden;">
                <img src="{{ asset('images/chatbot-avatar.png') }}" alt="Mandalaloka AI" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div>
                <h3 class="chatbot-title">Tanya Mang Loka</h3>
                <div class="chatbot-status">
                    <span class="chatbot-status-dot"></span> Online & Siap Membantu
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="chatbot-close" onclick="clearChatHistory()" title="Hapus Riwayat Obrolan" style="background: transparent; border: none; color: white; opacity: 0.8; cursor: pointer; font-size: 1.1rem; padding: 4px;">
                <i class="fas fa-trash-alt"></i>
            </button>
            <button class="chatbot-close" onclick="toggleChatbot()" title="Tutup Chat" style="background: transparent; border: none; color: white; opacity: 0.8; cursor: pointer; font-size: 1.2rem; padding: 4px;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    
    <!-- Onboarding Form for Chatbot -->
    <div class="chatbot-onboarding" id="chatbotOnboarding" style="display: none;">
        <h4>Lengkapi Data Diri Anda</h4>
        <div class="onboarding-field">
            <label for="obName">Nama Lengkap</label>
            <input type="text" id="obName" class="onboarding-input" placeholder="Contoh: Budi Santoso" required>
        </div>
        <div class="onboarding-field">
            <label for="obKelurahan">Kelurahan</label>
            <select id="obKelurahan" class="onboarding-input" required>
                <option value="" disabled selected>Pilih Kelurahan Anda</option>
                @if(isset($kelurahans) && $kelurahans->count() > 0)
                    @foreach($kelurahans as $kel)
                        <option value="{{ $kel->nama_kelurahan }}">{{ $kel->nama_kelurahan }}</option>
                    @endforeach
                @else
                    <option value="Jatihandap">Jatihandap</option>
                    <option value="Karangpamulang">Karangpamulang</option>
                    <option value="Pasir Impun">Pasir Impun</option>
                    <option value="Pasirlayung">Pasirlayung</option>
                @endif
            </select>
        </div>
        <div class="onboarding-field">
            <label for="obPhone">Nomor WhatsApp / HP</label>
            <input type="tel" id="obPhone" class="onboarding-input" placeholder="Contoh: 08123456789" required>
        </div>
        <button onclick="submitOnboarding()" class="onboarding-btn">Mulai Percakapan</button>
    </div>
    
    <!-- Unified Chat Window -->
    <div class="chatbot-messages" id="chatbotMessages" style="display: none;">
        <!-- Welcome Message -->
        <div class="chat-bubble bot" id="chatbotWelcomeBubble">
            Halo! Saya adalah <strong>Mang Loka</strong>. 👋<br><br>
            Saya siap membantu Anda mencari informasi seputar pendataan UMKM, perizinan, atau statistik di Kecamatan Mandalajati. Ada yang bisa saya bantu hari ini?
            <span class="chat-time" id="welcomeTime"></span>
        </div>
        
        <!-- FAQ / Pertanyaan Dasar Grid (Tinggal Pilih) -->
        <div class="faq-selection-container">
            <div class="faq-selection-title">
                <i class="fas fa-question-circle"></i> Pertanyaan Dasar (Tinggal Pilih):
            </div>
            <div class="faq-buttons-grid">
                <button class="faq-btn" onclick="selectFaqQuestion('daftar')">📝 Cara Daftar Akun</button>
                <button class="faq-btn" onclick="selectFaqQuestion('ktp')">👤 Kelengkapan KTP</button>
                <button class="faq-btn" onclick="selectFaqQuestion('lama')">⏳ Proses Pendaftaran</button>
                <button class="faq-btn" onclick="selectFaqQuestion('usaha')">🏬 Daftar Usaha Baru</button>
                <button class="faq-btn" onclick="selectFaqQuestion('tolak')">✏️ Perbaiki Data</button>
                <button class="faq-btn" onclick="selectFaqQuestion('biaya')">💳 Biaya Pendaftaran</button>
            </div>
        </div>
        
        <!-- Tanya Mang Loka Section Divider -->
        <div class="chat-section-divider">
            <span>Tanya Mang Loka (AI)</span>
        </div>
        
        <!-- Typing Indicator -->
        <div class="typing-indicator" id="chatbotTyping">
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
        </div>
    </div>
    
    <div class="chatbot-input-area" id="chatbotInputArea" style="display: none;">
        <div class="chatbot-input-wrapper">
            <textarea id="chatbotInput" class="chatbot-input" placeholder="Tulis pertanyaan Anda di sini..." rows="1" oninput="autoResize(this)" onkeypress="handleEnter(event)"></textarea>
        </div>
        <button id="chatbotSendBtn" class="chatbot-send" onclick="sendChatMessage()" disabled>
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

