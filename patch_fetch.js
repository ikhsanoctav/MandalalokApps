            window.performAjaxFetch = function(url) {
                const tableContainer = document.getElementById('umkmTableContainer');
                const filterForm = document.getElementById('filterForm');
                if (!tableContainer || isFetching) return;
                isFetching = true;

                tableContainer.style.opacity = '0.4';
                tableContainer.style.filter = 'blur(1px)';
                tableContainer.style.pointerEvents = 'none';

                const timestamp = new Date().getTime();
                const separator = url.includes('?') ? '&' : '?';
                const fetchUrl = url + separator + '_t=' + timestamp;

                const resetState = () => {
                    tableContainer.style.opacity = '1';
                    tableContainer.style.filter = 'none';
                    tableContainer.style.pointerEvents = 'auto';
                    isFetching = false;

                    if (filterForm) {
                        const searchInput = filterForm.querySelector('input[name="search"]');
                        if (searchInput && document.activeElement === searchInput) {
                            try {
                                const valLen = searchInput.value.length;
                                searchInput.setSelectionRange(valLen, valLen);
                            } catch(e) {}
                        }
                    }
                };

                fetch(fetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json, text/html',
                        'Cache-Control': 'no-cache'
                    }
                })
                .then(response => {
                    const contentType = response.headers.get('content-type') || '';
                    if (contentType.includes('application/json')) {
                        return response.json().then(data => ({ type: 'json', data }));
                    } else {
                        return response.text().then(html => ({ type: 'html', html }));
                    }
                })
                .then(result => {
                    try {
                        let newHtml = '';
                        if (result.type === 'json' && result.data && result.data.html) {
                            newHtml = result.data.html;
                        } else if (result.type === 'html' && result.html) {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(result.html, 'text/html');
                            const containerInDoc = doc.getElementById('umkmTableContainer');
                            newHtml = containerInDoc ? containerInDoc.innerHTML : result.html;
                        }

                        if (newHtml) {
                            tableContainer.innerHTML = newHtml;
                            if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                                window.Alpine.initTree(tableContainer);
                            }
                        }

                        const exportForm = document.querySelector('#exportModal form');
                        if (exportForm) {
                            const currentUrlParams = new URLSearchParams(new URL(url, window.location.origin).search);
                            ['search', 'kelurahan', 'kategori', 'sektor', 'status_verifikasi'].forEach(field => {
                                const input = exportForm.querySelector(`input[name="${field}"]`);
                                if (input) input.value = currentUrlParams.get(field) || '';
                            });
                        }

                        window.history.pushState({}, '', url);
                    } catch (e) {
                        console.error('Error rendering HTML:', e);
                    }
                    resetState();
                })
                .catch(err => {
                    console.error('AJAX Filter error:', err);
                    resetState();
                });
            }
