<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nikInput = document.querySelector('input[name="nik"]');
        const loadingIndicator = document.getElementById('nik-loading');
        
        if(!nikInput) return;

        let debounceTimer;
        nikInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const nik = this.value;
            
            // Unlock fields if NIK is changed / incomplete
            if(nik.length < 16) {
                unlockFields();
                if(loadingIndicator) loadingIndicator.classList.add('hidden');
            }
            
            if(nik.length === 16) {
                nikInput.classList.add('bg-blue-50');
                if(loadingIndicator) loadingIndicator.classList.remove('hidden');
                
                debounceTimer = setTimeout(() => {
                    fetch('/api/check-nik?nik=' + nik)
                        .then(res => {
                            if (!res.ok) throw new Error('API Error');
                            return res.json();
                        })
                        .then(data => {
                            nikInput.classList.remove('bg-blue-50');
                            if(loadingIndicator) loadingIndicator.classList.add('hidden');
                            
                            if(data.found) {
                                const owner = data.data;
                                
                                const setVal = (names, val) => {
                                    let el = null;
                                    for (let name of names) {
                                        el = document.querySelector(`[name="${name}"]`);
                                        if (el) break;
                                    }
                                    if(el && val !== null && val !== '') {
                                        el.value = val;
                                        lockField(el);
                                        el.dispatchEvent(new Event('input', {bubbles: true}));
                                        el.dispatchEvent(new Event('change', {bubbles: true}));
                                    }
                                };
                                
                                setVal(['nama_lengkap', 'nama_pemilik'], owner.nama_lengkap);
                                setVal(['jenis_kelamin'], owner.jenis_kelamin);
                                setVal(['tempat_lahir'], owner.tempat_lahir);
                                setVal(['tanggal_lahir'], owner.tanggal_lahir);
                                setVal(['no_hp', 'no_telepon'], owner.no_hp);
                                setVal(['email_pemilik', 'email'], owner.email);
                                setVal(['alamat_pemilik', 'alamat'], owner.alamat);
                                
                                // Alpine JS dependent selects via event
                                window.dispatchEvent(new CustomEvent('autofill-wilayah', {
                                    detail: {
                                        kelurahan: owner.kelurahan,
                                        rw: owner.rw,
                                        rt: owner.rt
                                    }
                                }));

                                // Lock alpine select elements natively too
                                setTimeout(() => {
                                    lockField(document.querySelector(`[name="kelurahan"]`));
                                    lockField(document.querySelector(`[name="rw"]`));
                                    lockField(document.querySelector(`[name="rt"]`));
                                }, 100);
                                
                                if(window.showToast) {
                                    window.showToast('success', 'Data Pemilik Terkunci', 'Data otomatis diisi dari database dan tidak dapat diedit disini.');
                                }
                            }
                        })
                        .catch(err => {
                            nikInput.classList.remove('bg-blue-50');
                            if(loadingIndicator) loadingIndicator.classList.add('hidden');
                            unlockFields();
                        });
                }, 400);
            }
        });

        const lockedFields = ['nama_lengkap', 'nama_pemilik', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'no_hp', 'no_telepon', 'email_pemilik', 'email', 'alamat_pemilik', 'alamat', 'kelurahan', 'rw', 'rt'];

        function lockField(el) {
            if(!el) return;
            el.setAttribute('readonly', true);
            if(el.tagName === 'SELECT') {
                el.classList.add('pointer-events-none');
                el.setAttribute('tabindex', '-1');
            }
            el.classList.add('bg-slate-100', 'cursor-not-allowed', 'text-slate-500');
            el.classList.remove('bg-white');
        }

        function unlockFields() {
            lockedFields.forEach(name => {
                const el = document.querySelector(`[name="${name}"]`);
                if(el) {
                    el.removeAttribute('readonly');
                    if(el.tagName === 'SELECT') {
                        el.classList.remove('pointer-events-none');
                        el.removeAttribute('tabindex');
                    }
                    el.classList.remove('bg-slate-100', 'cursor-not-allowed', 'text-slate-500');
                    el.classList.add('bg-white');
                }
            });
        }
    });
</script>
