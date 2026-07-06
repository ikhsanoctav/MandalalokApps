@extends('layouts.superadmin')

@section('title', 'Logs Aktivitas Sistem')

@section('content')
    <div class="space-y-6" x-data="logsFilter()">
        
        <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Logs & Audit Sistem</h2>
                <p class="text-xs md:text-sm text-slate-500 font-medium">Pantau dan verifikasi setiap aktivitas administratif
                    secara real-time.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-4 md:p-6 transition-all">
            <div class="flex items-center gap-2 pb-4 mb-4 border-b border-slate-100">
                <span class="mdi mdi-filter-variant text-blue-600 text-xl"></span>
                <h3 class="font-bold text-slate-800 text-sm md:text-base">Pencarian & Filter Logs</h3>
            </div>

            <form @submit.prevent="applyFilter()" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                
                <div class="col-span-1 md:col-span-8 relative">
                    <input type="text" x-model="filters.search"
                        placeholder="Cari aktivitas, tindakan, atau nama pengguna..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-250 bg-slate-50/30 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all font-medium">
                    <span class="absolute left-3.5 top-3 mdi mdi-magnify text-slate-400 text-lg"></span>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <select x-model="filters.per_page"
                        class="w-full px-3 py-2.5 rounded-lg border border-slate-250 bg-slate-50/30 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all font-semibold">
                        <option value="10">10 / Hal</option>
                        <option value="25">25 / Hal</option>
                        <option value="50">50 / Hal</option>
                        <option value="100">100 / Hal</option>
                    </select>
                </div>

                <div class="col-span-1 md:col-span-2 flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm px-4 py-2.5 rounded-md shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <span class="mdi mdi-magnify"></span>
                        <span>Cari</span>
                    </button>
                    <button type="button" @click="resetFilter()"
                        class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition-all font-bold text-sm flex items-center justify-center"
                        title="Reset Filter">
                        <span class="mdi mdi-refresh text-lg"></span>
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden"
            :class="{ 'opacity-50 pointer-events-none': loading }">
            <div id="logs-table-container">
                @include('superadmin.master.logs.table-data')
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script>
            function logsFilter() {
                return {
                    filters: {
                        search: '{{ request('search') }}',
                        per_page: '{{ request('per_page', 10) }}'
                    },
                    loading: false,

                    applyFilter() {
                        this.fetchData();
                    },

                    resetFilter() {
                        this.filters.search = '';
                        this.filters.per_page = '10';
                        this.fetchData();
                    },

                    fetchData() {
                        this.loading = true;
                        const params = new URLSearchParams(this.filters).toString();
                        const url = `{{ route('superadmin.logs') }}?${params}`;

                        // Update Address Bar URL
                        window.history.pushState({}, '', url);

                        fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('logs-table-container').innerHTML = data.html;

                                    // Re-initialize dynamic elements if needed
                                    this.initPagination();
                                }
                            })
                            .catch(err => console.error(err))
                            .finally(() => {
                                this.loading = false;
                            });
                    },

                    initPagination() {
                        // Intercept pagination clicks for smooth AJAX
                        const paginationLinks = document.querySelectorAll('#logs-table-container nav a');
                        paginationLinks.forEach(link => {
                            link.addEventListener('click', (e) => {
                                e.preventDefault();
                                const urlObj = new URL(link.getAttribute('href'));
                                const page = urlObj.searchParams.get('page');

                                const fullParams = {
                                    ...this.filters,
                                    page
                                };
                                const finalParams = new URLSearchParams(fullParams).toString();
                                const finalUrl = `{{ route('superadmin.logs') }}?${finalParams}`;

                                window.history.pushState({}, '', finalUrl);

                                this.loading = true;
                                fetch(finalUrl, {
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            document.getElementById('logs-table-container').innerHTML = data
                                                .html;
                                            this.initPagination();
                                            // Scroll table up smoothly on paginated clicks
                                            document.getElementById('logs-table-container').scrollIntoView({
                                                behavior: 'smooth'
                                            });
                                        }
                                    })
                                    .finally(() => {
                                        this.loading = false;
                                    });
                            });
                        });
                    },

                    init() {
                        this.initPagination();
                    }
                }
            }
        </script>
    @endpush
@endsection
