@extends(auth()->user()->hasRole('super_admin') ? 'layouts.superadmin' : (auth()->user()->hasRole('admin_kecamatan') ? 'layouts.admin' : 'layouts.petugas'))

@section('title', 'Kelola Kriteria SPK')
@section('breadcrumb', 'Kelola Kriteria')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Kelola Kriteria Penilaian</h2>
            <p class="text-sm text-slate-700">Atur kriteria yang digunakan untuk pembobotan AHP dan perankingan SAW.</p>
        </div>
        <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm flex items-center gap-2">
            <i class="mdi mdi-plus"></i> Tambah Kriteria
        </button>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl flex items-center gap-3">
        <i class="mdi mdi-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-700 text-xs uppercase tracking-wider">
                    <th class="p-4 border-b border-slate-200 font-medium">Kode</th>
                    <th class="p-4 border-b border-slate-200 font-medium">Nama Kriteria</th>
                    <th class="p-4 border-b border-slate-200 font-medium">Tipe</th>
                    <th class="p-4 border-b border-slate-200 font-medium">Status</th>
                    <th class="p-4 border-b border-slate-200 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($kriterias as $k)
                <tr class="hover:bg-slate-50">
                    <td class="p-4 font-semibold text-slate-700">{{ $k->kode }}</td>
                    <td class="p-4 text-slate-600">{{ $k->nama_kriteria }}</td>
                    <td class="p-4">
                        @if($k->tipe == 'benefit')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">Benefit</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-rose-100 text-rose-700">Cost</span>
                        @endif
                    </td>
                    <td class="p-4">
                        @if($k->aktif)
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">Aktif</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-700">Non-Aktif</span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <form id="delete-form-{{ $k->id }}" action="{{ route('dss.kriteria.destroy', $k->id) }}" method="POST" class="inline-block" x-data>
                            @csrf @method('DELETE')
                            <button type="button" @click="$dispatch('open-confirm-modal', {
                                title: 'Hapus Kriteria',
                                message: 'Apakah Anda yakin ingin menghapus kriteria ini?',
                                confirmText: 'Ya, Hapus',
                                action: () => {
                                    document.getElementById('delete-form-{{ $k->id }}').submit();
                                    return new Promise(() => {});
                                }
                            })" class="text-rose-500 hover:text-rose-700 p-2"><i class="mdi mdi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-700">Belum ada kriteria.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-tambah" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="font-bold text-slate-800">Tambah Kriteria Baru</h3>
            <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="mdi mdi-times"></i>
            </button>
        </div>
        <form action="{{ route('dss.kriteria.store') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kode Kriteria</label>
                    <input type="text" name="kode" required placeholder="C1" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kriteria</label>
                    <input type="text" name="nama_kriteria" required placeholder="Contoh: Omset per bulan" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tipe</label>
                    <select name="tipe" required class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="benefit">Benefit (Semakin besar semakin baik)</option>
                        <option value="cost">Cost (Semakin kecil semakin baik)</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-md hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
