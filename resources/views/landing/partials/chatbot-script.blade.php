<!-- Chatbot Visual Demo Scripts with Local Intelligent Mock replies -->
<script>
    function toggleChatbot() {
        const windowEl = document.getElementById('chatbotWindow');
        const btnEl = document.getElementById('chatbotToggleBtn');
        const isOpening = !windowEl.classList.contains('active');
        windowEl.classList.toggle('active');
        btnEl.classList.toggle('active');
        
        if (isOpening) {
            checkChatbotOnboarding();
        }
    }

    const faqData = {
        daftar: {
            q: "Bagaimana cara mendaftar akun pelaku UMKM?",
            a: "Klik tombol <strong>\"Pendaftaran UMKM\"</strong> di pojok kanan atas halaman utama (/register). Isi data: Nama Lengkap Pemilik, Alamat Email, NIK (16 digit), dan Kata Sandi Anda. Setelah terdaftar, silakan lengkapi profil Anda."
        },
        ktp: {
            q: "Apa saja kelengkapan KTP yang dibutuhkan?",
            a: "Masuk ke menu <strong>\"Profil\"</strong> di dashboard pelaku (/pelaku/profil), lengkapi biodata Anda, dan <strong>unggah foto KTP</strong> yang jelas. <br><br>🛡️ <strong>Keamanan Tinggi:</strong> Sistem kami secara otomatis menempelkan watermark keamanan permanen di atas foto KTP Anda dan menyimpannya di storage tertutup (tidak dapat diakses publik) demi melindungi privasi Anda dari penyalahgunaan."
        },
        lama: {
            q: "Berapa lama proses pendaftaran?",
            a: "Akun pelaku UMKM dan data usaha langsung dibuat aktif setelah data wajib dilengkapi. Anda tidak perlu menunggu verifikasi admin untuk mulai mengelola UMKM dan produk."
        },
        usaha: {
            q: "Bagaimana cara mendaftarkan usaha baru?",
            a: "Masuk ke dashboard Pelaku, pilih menu <strong>\"Daftar UMKM Baru\"</strong> (/pelaku/umkm/create), isi seluruh informasi detail usaha Anda dengan lengkap, unggah foto utama dan galeri produk, lalu simpan. Data UMKM akan langsung aktif di sistem."
        },
        tolak: {
            q: "Mengapa data UMKM saya perlu direvisi?",
            a: "Data usaha/KTP dapat perlu diperbaiki jika informasi tidak lengkap atau foto tidak jelas. Anda dapat mengedit data langsung dari dashboard pelaku agar informasi UMKM tetap rapi dan akurat."
        },
        biaya: {
            q: "Apakah ada biaya pendaftaran?",
            a: "Seluruh proses pendaftaran akun, pelengkapan KTP, pendaftaran usaha, hingga publikasi katalog UMKM di platform MandalalokaApps adalah <strong>100% Gratis</strong> tanpa dipungut biaya apa pun."
        },
        regulasi: {
            q: "Bagaimana regulasi dan surat edaran legalitas UMKM?",
            a: "Berdasarkan Peraturan Pemerintah, Pelaku UMKM wajib memiliki Nomor Induk Berusaha (NIB) sebagai identitas resmi usaha. NIB dapat diperoleh secara online melalui sistem OSS (Online Single Submission) di oss.go.id. Aplikasi platform MandalalokaApps membantu mencatat data legalitas tersebut di tingkat kecamatan."
        },
        pengaduan: {
            q: "Bagaimana cara menyampaikan pengaduan masyarakat?",
            a: "Untuk pengaduan masyarakat terkait pelayanan UMKM, kendala pendataan, atau laporan penyimpangan di lapangan, Anda dapat menghubungi Seksi Ekbang Kecamatan Mandalajati melalui email <strong>humas@mandalajati.bandung.go.id</strong> atau mengirim pesan langsung via WhatsApp/HP ke kantor sekretariat kami di (022) 1234567."
        }
    };

    function selectFaqQuestion(key) {
        const item = faqData[key];
        if (!item) return;

        // Tampilkan pesan user
        appendMessage('user', item.q);
        showTyping(true);

        // Simulasi respon bot instan
        setTimeout(() => {
            showTyping(false);
            appendMessage('bot', item.a);
        }, 500);
    }

    function askChatbot(key) {
        const windowEl = document.getElementById('chatbotWindow');
        const btnEl = document.getElementById('chatbotToggleBtn');
        
        // Buka chatbot jika belum aktif
        if (!windowEl.classList.contains('active')) {
            windowEl.classList.add('active');
            btnEl.classList.add('active');
            checkChatbotOnboarding();
        }

        const user = window.chatbotUser;
        if (!user) {
            alert('Harap isi data diri Anda terlebih dahulu pada kotak chat untuk menggunakan asisten AI!');
            return;
        }

        selectFaqQuestion(key);
    }

    const USER_SESSION_KEY = 'mandalaloka_chatbot_user';
    const SESSION_EXPIRY_MS = 3 * 60 * 60 * 1000; // 3 Jam

    function saveChatbotUser(name, kelurahan, phone) {
        const data = {
            name: name,
            kelurahan: kelurahan,
            phone: phone,
            expiresAt: Date.now() + SESSION_EXPIRY_MS
        };
        localStorage.setItem(USER_SESSION_KEY, JSON.stringify(data));
        window.chatbotUser = data;
    }

    function loadChatbotUser() {
        const stored = localStorage.getItem(USER_SESSION_KEY);
        if (!stored) return null;
        try {
            const data = JSON.parse(stored);
            if (Date.now() > data.expiresAt) {
                localStorage.removeItem(USER_SESSION_KEY);
                localStorage.removeItem('mandalaloka_chat_history');
                return null;
            }
            window.chatbotUser = data;
            return data;
        } catch (e) {
            localStorage.removeItem(USER_SESSION_KEY);
            localStorage.removeItem('mandalaloka_chat_history');
            return null;
        }
    }

    function clearChatHistory() {
        if(confirm('Apakah Anda yakin ingin menghapus seluruh riwayat percakapan?')) {
            localStorage.removeItem('mandalaloka_chat_history');
            
            // Hapus isi DOM chat except the intro and divider
            const msgs = document.getElementById('chatbotMessages');
            const intro = msgs.querySelector('.chatbot-message.bot');
            const divider = msgs.querySelector('.chat-section-divider');
            const typing = document.getElementById('chatbotTyping');
            
            msgs.innerHTML = '';
            if (intro) msgs.appendChild(intro);
            if (divider) msgs.appendChild(divider);
            if (typing) msgs.appendChild(typing);
            
            window.chatHistoryMemory = []; // clear in-memory array if defined globally
            
            alert('Riwayat obrolan berhasil dihapus. AI telah melupakan percakapan sebelumnya.');
        }
    }

    function checkChatbotOnboarding() {
        if (!window.chatbotUser) {
            loadChatbotUser();
        }

        const user = window.chatbotUser;
        const onboardingEl = document.getElementById('chatbotOnboarding');
        const messagesEl = document.getElementById('chatbotMessages');
        const inputAreaEl = document.getElementById('chatbotInputArea');
        
        if (user) {
            onboardingEl.style.display = 'none';
            messagesEl.style.display = 'flex';
            inputAreaEl.style.display = 'flex';
            
            updateWelcomeMessage(user.name, user.kelurahan);
            loadChatHistory();
            
            setTimeout(() => {
                const input = document.getElementById('chatbotInput');
                if (input) input.focus();
            }, 300);
        } else {
            onboardingEl.style.display = 'flex';
            messagesEl.style.display = 'none';
            inputAreaEl.style.display = 'none';
        }
    }

    function submitOnboarding() {
        const name = document.getElementById('obName').value.trim();
        const kelurahan = document.getElementById('obKelurahan').value;
        const phone = document.getElementById('obPhone').value.trim();
        
        if (!name || !kelurahan || !phone) {
            alert('Harap lengkapi seluruh kolom data diri Anda!');
            return;
        }
        
        saveChatbotUser(name, kelurahan, phone);
        checkChatbotOnboarding();
    }

    function updateWelcomeMessage(name, kelurahan) {
        const messagesEl = document.getElementById('chatbotMessages');
        const firstBubble = messagesEl.querySelector('.chat-bubble.bot');
        if (firstBubble && !firstBubble.hasAttribute('data-initialized')) {
            firstBubble.innerHTML = `Halo <strong>${name}</strong> dari Kelurahan <strong>${kelurahan}</strong>! Saya adalah <strong>Mang Loka</strong>. 👋<br><br>Saya dapat membantu Anda mencari informasi seputar pendataan UMKM, perizinan, atau statistik di Kecamatan Mandalajati. Ada yang bisa saya bantu hari ini?<span class="chat-time" id="welcomeTime">${getCurrentTimeStr()}</span>`;
            firstBubble.setAttribute('data-initialized', 'true');
        }
    }

    function getCurrentTimeStr() {
        const now = new Date();
        return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    }

    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
        
        const btn = document.getElementById('chatbotSendBtn');
        btn.disabled = textarea.value.trim().length === 0;
    }

    function handleEnter(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendChatMessage();
        }
    }

    async function sendChatMessage() {
        const inputEl = document.getElementById('chatbotInput');
        const message = inputEl.value.trim();
        const sendBtn = document.getElementById('chatbotSendBtn');
        
        if (!message) return;
        
        inputEl.value = '';
        inputEl.disabled = true;
        sendBtn.disabled = true;
        inputEl.style.height = 'auto';
        
        appendMessage('user', message);
        showTyping(true);
        
        let replyText = '';
        
        try {
            const userObj = window.chatbotUser || null;

            // Coba panggil backend API terlebih dahulu
            const response = await fetch('/api/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    message: message,
                    name: userObj ? userObj.name : 'Tamu',
                    kelurahan: userObj ? userObj.kelurahan : 'Umum',
                    phone: userObj ? userObj.phone : '',
                    history: JSON.parse(localStorage.getItem(CHAT_HISTORY_KEY) || '[]')
                })
            });
            
            const data = await response.json();
            if(data.success) {
                replyText = data.reply;
            } else {
                throw new Error("API Route Disabled / n8n Off");
            }
            
        } catch (error) {
            // MOCK LOCAL RESPONSE: Jika backend dinonaktifkan, AI menjawab secara lokal dengan cerdas!
            const msgLower = message.toLowerCase();
            await new Promise(resolve => setTimeout(resolve, 1000)); // Simulasi mengetik
            
            if(msgLower.includes('daftar') || msgLower.includes('registrasi') || msgLower.includes('buat akun')) {
                replyText = '<strong>Langkah Pendaftaran Pelaku UMKM Mandalaloka:</strong><br><br>' +
                            '1. 📝 <strong>Registrasi Akun</strong>: Klik tombol <strong>"Pendaftaran UMKM"</strong> di pojok kanan atas halaman utama (atau akses /register). Isi data: Nama Lengkap Pemilik, Alamat Email, NIK (16 digit), Kata Sandi, dan Konfirmasi Sandi.<br>' +
                            '2. 👤 <strong>Lengkapi Profil</strong>: Setelah terdaftar, masuk ke menu <strong>"Profil"</strong> (/pelaku/profil). Isi data diri lengkap dan wajib <strong>mengunggah Foto KTP</strong> Anda (sistem akan otomatis memberi watermark keamanan & memproteksi file secara privat).<br>' +
                            '3. 🏬 <strong>Daftarkan Usaha</strong>: Buka menu <strong>"Daftar UMKM Baru"</strong> (/pelaku/umkm/create). Isi data detail usaha Anda dengan lengkap (Nama Usaha, Kategori, Sektor, Alamat, Foto Utama, dll.) lalu simpan.<br>' +
                            '4. ✅ <strong>Langsung Aktif</strong>: Akun dan UMKM Anda langsung aktif/terdaftar di sistem tanpa perlu menunggu verifikasi admin.';
            } else if(msgLower.includes('sistem') || msgLower.includes('mandalaloka') || msgLower.includes('apa ini') || msgLower.includes('aplikasi')) {
                replyText = '<strong>MandalalokaApps</strong> adalah platform resmi Sistem Informasi UMKM Kecamatan Mandalajati.<br><br>' +
                            'Sistem ini dirancang untuk mengintegrasikan basis data UMKM dari tingkat RT/RW dan kelurahan secara satu pintu guna membantu digitalisasi usaha lokal, kemudahan legalitas perizinan, dan pembuatan keputusan program bantuan tepat sasaran.';
            } else if(msgLower.includes('fitur') || msgLower.includes('keunggulan') || msgLower.includes('bisa apa')) {
                replyText = 'Fitur-fitur utama di <strong>MandalalokaApps</strong> meliputi:<br><br>' +
                            '- 🛡️ <strong>RBAC Login</strong>: Dashboard khusus untuk Super Admin, Admin Kecamatan, Operator Pendata, dan Pelaku UMKM.<br>' +
                            '- 📝 <strong>Pendataan Usaha</strong>: Pelaku UMKM dapat mendaftarkan usaha dan produk secara mandiri.<br>' +
                            '- 💳 <strong>Dokumen NIK & KTP Aman</strong>: Melindungi identitas pelaku usaha dengan watermarking otomatis & private storage.<br>' +
                            '- 📊 <strong>Analitik Statistik Sektoral</strong>: Pemetaan jumlah UMKM per-Kelurahan untuk pimpinan kecamatan.<br>' +
                            '- 📰 <strong>Warta Publikasi Berita</strong>: Portal info program pembinaan UMKM.';
            } else if(msgLower.includes('status') || msgLower.includes('verifikasi')) {
                replyText = 'Saat ini pelaku UMKM tidak perlu menunggu verifikasi admin. Setelah registrasi, lengkapi profil dan daftarkan UMKM Anda. Data usaha akan langsung aktif/terdaftar di sistem Mandalaloka.';
            } else {
                replyText = 'Halo! Saya adalah asisten pintar <strong>Mandalaloka AI</strong>. 🤖<br><br>' +
                            'Saat ini saya berjalan dalam <strong>Mode Demo Cerdas</strong>. Saya bisa menjawab seputar tata cara pendaftaran, fitur sistem, atau pengelolaan data usaha di MandalalokaApps.<br><br>' +
                            '<em>(Catatan: Anda dapat mengaktifkan kembali backend API / n8n workflow kapan saja untuk menghubungkan saya ke model LLM Gemini secara langsung!)</em>';
            }
        } finally {
            showTyping(false);
            appendMessage('bot', replyText);
            inputEl.disabled = false;
            inputEl.focus();
        }
    }

    // --- FITUR CHAT HISTORY ---
    const CHAT_HISTORY_KEY = 'mandalaloka_chat_history';

    function saveToHistory(sender, text, timeStr) {
        let history = JSON.parse(localStorage.getItem(CHAT_HISTORY_KEY) || '[]');
        history.push({ sender, text, timeStr });
        localStorage.setItem(CHAT_HISTORY_KEY, JSON.stringify(history));
    }

    function loadChatHistory() {
        if (window.chatbotHistoryLoaded) return;
        let history = JSON.parse(localStorage.getItem(CHAT_HISTORY_KEY) || '[]');
        history.forEach(msg => {
            appendMessage(msg.sender, msg.text, msg.timeStr, false);
        });
        window.chatbotHistoryLoaded = true;
    }

    function appendMessage(sender, text, timeStr = null, save = true) {
        const messagesDiv = document.getElementById('chatbotMessages');
        const typingIndicator = document.getElementById('chatbotTyping');
        
        if (!timeStr) {
            const now = new Date();
            timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
        }
        
        const bubble = document.createElement('div');
        bubble.className = `chat-bubble ${sender}`;
        const formattedText = text.replace(/\n/g, '<br>');
        bubble.innerHTML = `${formattedText}<span class="chat-time">${timeStr}</span>`;
        
        messagesDiv.insertBefore(bubble, typingIndicator);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
        
        if (save) {
            saveToHistory(sender, text, timeStr);
        }
    }

    function showTyping(show) {
        const indicator = document.getElementById('chatbotTyping');
        const messagesDiv = document.getElementById('chatbotMessages');
        
        if (show) {
            indicator.classList.add('active');
            messagesDiv.appendChild(indicator);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        } else {
            indicator.classList.remove('active');
        }
    }

    function scrollProductCarousel(direction) {
        const carousel = document.getElementById('homeProductCarousel');
        if (!carousel) return;

        const firstCard = carousel.querySelector('.home-product-card');
        const cardWidth = firstCard ? firstCard.getBoundingClientRect().width : 260;
        carousel.scrollBy({
            left: direction * (cardWidth + 16) * 2,
            behavior: 'smooth'
        });
    }

    // Fitur Drag to Scroll untuk Carousel Produk
    document.addEventListener('DOMContentLoaded', () => {
        const slider = document.getElementById('homeProductCarousel');
        if (!slider) return;

        let isDown = false;
        let isDragging = false;
        let startX;
        let scrollLeft;

        slider.style.cursor = 'grab';

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            isDragging = false; // Reset drag state
            slider.style.cursor = 'grabbing';
            slider.style.scrollSnapType = 'none'; 
            slider.style.scrollBehavior = 'auto'; 
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            if (!isDown) return;
            isDown = false;
            slider.style.cursor = 'grab';
            slider.style.scrollSnapType = 'x mandatory';
            slider.style.scrollBehavior = 'smooth';
        });

        slider.addEventListener('mouseup', () => {
            if (!isDown) return;
            isDown = false;
            slider.style.cursor = 'grab';
            slider.style.scrollSnapType = 'x mandatory';
            slider.style.scrollBehavior = 'smooth';
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2; // Kecepatan drag
            
            if (Math.abs(walk) > 5) {
                isDragging = true; // Tandai bahwa user benar-benar sedang nge-drag (bukan sekadar klik)
            }
            
            slider.scrollLeft = scrollLeft - walk;
        });
        
        // Mencegah link di-klik saat sedang nge-drag
        slider.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', (e) => {
                if (isDragging) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        });

        // Auto-scroll Carousel
        let autoScrollInterval;
        const startAutoScroll = () => {
            autoScrollInterval = setInterval(() => {
                if (!isDown && !isDragging) {
                    // Cek jika sudah mencapai ujung kanan (toleransi 10px)
                    if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                        slider.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        scrollProductCarousel(1);
                    }
                }
            }, 3000); // Geser setiap 3 detik
        };

        const stopAutoScroll = () => {
            clearInterval(autoScrollInterval);
        };

        // Mulai auto-scroll saat halaman dimuat
        startAutoScroll();

        // Hentikan auto-scroll saat kursor berada di atas carousel, lanjutkan saat keluar
        slider.addEventListener('mouseenter', stopAutoScroll);
        slider.addEventListener('mouseleave', () => {
            if (!isDown) {
                startAutoScroll();
            }
        });
    });
</script>
