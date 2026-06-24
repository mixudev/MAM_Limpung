@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Pengaturan PPDB / {{ $academicYear->name }}';
        }
    });
</script>

<div class="space-y-6 max-w-7xl mx-auto">

    @if(session('success'))
    <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/60 p-4 text-emerald-800 dark:text-emerald-400 text-xs font-semibold flex items-center gap-3 animate-fadeIn">
        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>
            @php
                $parts = explode('|', session('success'));
                echo count($parts) > 1 ? "<strong>{$parts[0]}:</strong> {$parts[1]}" : session('success');
            @endphp
        </span>
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/60 p-4 text-red-800 dark:text-red-400 text-xs font-semibold rounded-none">
        <p class="font-bold mb-2">Terjadi kesalahan validasi:</p>
        <ul class="list-disc list-inside space-y-1 font-mono">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 border-t-4 border-t-[#4f45b2] border-x border-b border-slate-200 dark:border-zinc-800 shadow-sm">
        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.ppdb.settings.edit') }}" class="p-2 bg-[#4f45b2]/10 hover:bg-[#4f45b2]/20 text-[#4f45b2] rounded-none transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $academicYear->name }}</h1>
                    <p class="text-sm text-slate-500 dark:text-zinc-400 mt-1 flex items-center gap-2">
                        <span>Tahun ajaran {{ $academicYear->year }}</span>
                        <span class="text-slate-300 dark:text-zinc-700">&middot;</span>
                        <span>{{ $academicYear->waves->count() }} gelombang</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($academicYear->is_active)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-[#4f45b2]/10 text-[#4f45b2] border border-[#4f45b2]/20 rounded-none uppercase tracking-wider">
                        <span class="w-2 h-2 bg-[#4f45b2] rounded-full"></span>
                        Tahun Aktif
                    </span>
                @else
                    <form action="{{ route('admin.ppdb.settings.years.activate', $academicYear->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="py-1.5 px-3 text-xs font-bold bg-emerald-500 hover:bg-emerald-600 text-white rounded-none uppercase tracking-wider transition-all active:scale-[.97]">
                            Aktifkan Tahun Ini
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.ppdb.settings.edit') }}" class="py-1.5 px-3 bg-slate-100 dark:bg-zinc-800 hover:bg-slate-200 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 font-bold text-xs rounded-none transition-all text-center uppercase tracking-wider">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Jadwal -->
    <div class="bg-white dark:bg-zinc-900 border-t-4 border-t-[#4f45b2] border-x border-b border-slate-200 dark:border-zinc-800 shadow-sm">
        <div class="p-6 border-b border-slate-100 dark:border-zinc-850">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8]">
                        <svg class="w-4 h-4 inline -mt-0.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Jadwal PPDB
                    </h3>
                    <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Gelombang memiliki periode mulai & selesai. Lainnya cukup satu tanggal.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="AppModal.open('addDateModal')" class="py-2 px-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-all active:scale-[.98] flex items-center gap-1.5 flex-shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        + Lainnya
                    </button>
                    <button onclick="AppModal.open('addWaveModal')" class="py-2 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-all active:scale-[.98] flex items-center gap-1.5 flex-shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        + Gelombang
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#4f45b2]/5 dark:bg-[#4f45b2]/[0.04] border-b border-slate-100 dark:border-zinc-800/80">
                        <th class="px-5 py-3.5 text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Nama</th>
                        <th class="px-5 py-3.5 text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Tanggal</th>
                        <th class="px-5 py-3.5 text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 text-center">Status</th>
                        <th class="px-5 py-3.5 text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/50">
                    @if($waves->isEmpty() && $standaloneDates->isEmpty())
                    <tr>
                        <td colspan="4" class="px-5 py-14 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400 dark:text-zinc-500">
                                <svg class="w-10 h-10 text-slate-300 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium">Belum ada jadwal</p>
                                    <p class="text-xs mt-1">Klik "+ Gelombang" untuk periode pendaftaran atau "+ Lainnya" untuk tanggal penting.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @else
                        @foreach($waves as $wave)
                    <tr class="hover:bg-[#4f45b2]/[0.02] dark:hover:bg-[#4f45b2]/[0.04] transition-all group">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2.5">
                                <span class="w-1.5 h-1.5 rounded-full {{ $wave->is_active ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-zinc-600' }} flex-shrink-0"></span>
                                <span class="text-sm font-semibold text-slate-800 dark:text-zinc-200">{{ $wave->name }}</span>
                                <span class="text-[9px] font-mono uppercase tracking-wider text-[#4f45b2] dark:text-[#8c84c8] bg-[#4f45b2]/10 dark:bg-[#4f45b2]/20 px-1.5 py-0.5">Gelombang</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs text-slate-600 dark:text-zinc-400">{{ \Carbon\Carbon::parse($wave->start_date)->translatedFormat('d M Y') }}</span>
                                @if($wave->end_date)
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500">&rarr; {{ \Carbon\Carbon::parse($wave->end_date)->translatedFormat('d M Y') }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            <label class="relative inline-flex items-center cursor-pointer select-none" style="display:inline-flex!important;margin-bottom:0!important;">
                                <form action="{{ route('admin.ppdb.settings.waves.toggle', $wave->id) }}" method="POST" id="toggle-form-{{ $wave->id }}">
                                    @csrf
                                    <input type="hidden" name="is_active" value="{{ $wave->is_active ? '0' : '1' }}">
                                </form>
                                <input type="checkbox" {{ $wave->is_active ? 'checked' : '' }} onchange="document.getElementById('toggle-form-{{ $wave->id }}').submit();" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-300 dark:bg-zinc-700 rounded-full peer peer-focus:ring-2 peer-focus:ring-[#4f45b2]/20 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </td>
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-all duration-200">
                                <button onclick="openEditWave({{ $wave->id }})"
                                    class="inline-flex items-center gap-1 py-1.5 px-2.5 text-[10px] font-bold bg-white dark:bg-zinc-800 hover:bg-[#4f45b2]/10 text-slate-600 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 rounded-none uppercase tracking-wider transition-all shadow-sm">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>
                                <button onclick="confirmDeleteWave({{ $wave->id }}, '{{ $wave->name }}')"
                                    class="inline-flex items-center gap-1 py-1.5 px-2.5 text-[10px] font-bold bg-white dark:bg-zinc-800 hover:bg-red-50 dark:hover:bg-red-950/20 text-red-600 border border-slate-200 dark:border-zinc-700 rounded-none uppercase tracking-wider transition-all shadow-sm">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                        @endforeach
                        @foreach($standaloneDates as $d)
                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-emerald-950/[0.04] transition-all group">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 flex-shrink-0"></span>
                                <span class="text-sm font-semibold text-slate-800 dark:text-zinc-200">{{ $d->name }}</span>
                                <span class="text-[9px] font-mono uppercase tracking-wider text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 px-1.5 py-0.5">Lainnya</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="text-xs text-slate-600 dark:text-zinc-400">{{ \Carbon\Carbon::parse($d->date)->translatedFormat('d M Y') }}</span>
                        </td>
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            <span class="text-[10px] text-slate-300 dark:text-zinc-600">&mdash;</span>
                        </td>
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-all duration-200">
                                <form action="{{ url('admin/ppdb/settings/wave-dates') }}/{{ $d->id }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus tanggal {{ $d->name }}?')"
                                        class="inline-flex items-center gap-1 py-1.5 px-2.5 text-[10px] font-bold bg-white dark:bg-zinc-800 hover:bg-red-50 dark:hover:bg-red-950/20 text-red-600 border border-slate-200 dark:border-zinc-700 rounded-none uppercase tracking-wider transition-all shadow-sm">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Wave Modal -->
<x-app-modal id="addWaveModal" maxWidth="md" title="Tambah Gelombang Baru" description="Buat gelombang pendaftaran untuk tahun ajaran {{ $academicYear->name }}." iconColor="indigo">
    <form action="{{ route('admin.ppdb.settings.waves.store') }}" method="POST" id="addWaveForm">
        @csrf
        <input type="hidden" name="academic_year_id" value="{{ $academicYear->id }}">
        <div class="space-y-5">
            <div>
                <label class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-1.5">Nama Gelombang</label>
                <input type="text" name="name" placeholder="Gelombang {{ $academicYear->waves->count() + 1 }}" required
                    class="w-full py-2 px-3 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
            </div>
            <div>
                <h4 class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Periode Pendaftaran</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] text-slate-500 dark:text-zinc-500 block mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" required
                            class="w-full py-2 px-3 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-500 dark:text-zinc-500 block mb-1">Tanggal Selesai</label>
                        <input type="date" name="end_date"
                            class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-blue-400/20 focus:border-blue-400">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <x-slot name="footer">
        <button onclick="AppModal.close('addWaveModal')" class="modal-btn-cancel">Batal</button>
        <button onclick="document.getElementById('addWaveForm').submit();" class="modal-btn-primary">Simpan Gelombang</button>
    </x-slot>
</x-app-modal>

<!-- Edit Wave Modal -->
<x-app-modal id="editWaveModal" maxWidth="md" title="Edit Gelombang" description="Perbarui pengaturan gelombang pendaftaran." iconColor="indigo">
    <form action="{{ url('admin/ppdb/settings/waves') }}/" method="POST" id="editWaveForm">
        @csrf @method('PUT')
        <div class="space-y-5">
            <div>
                <label class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-1.5">Nama Gelombang</label>
                <input type="text" name="name" id="edit_wave_name" required
                    class="w-full py-2 px-3 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
            </div>
            <div>
                <h4 class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Periode Pendaftaran</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] text-slate-500 dark:text-zinc-500 block mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="edit_wave_start" required
                            class="w-full py-2 px-3 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-500 dark:text-zinc-500 block mb-1">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="edit_wave_end"
                            class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-blue-400/20 focus:border-blue-400">
                    </div>
                </div>
            </div>
            <div>
                <label class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-1.5">Status Gelombang</label>
                <label class="relative inline-flex items-center cursor-pointer select-none gap-3 p-3 bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-none" style="display:inline-flex!important;margin-bottom:0!important;">
                    <input type="checkbox" name="is_active" value="1" id="edit_wave_active" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-300 dark:bg-zinc-700 rounded-full peer peer-focus:ring-2 peer-focus:ring-[#4f45b2]/20 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                    <span class="text-xs text-slate-600 dark:text-zinc-400 font-medium" id="edit_wave_status_label">Nonaktif</span>
                </label>
            </div>
        </div>
    </form>
    <x-slot name="footer">
        <button onclick="AppModal.close('editWaveModal')" class="modal-btn-cancel">Batal</button>
        <button onclick="document.getElementById('editWaveForm').submit();" class="modal-btn-primary">Simpan Perubahan</button>
    </x-slot>
</x-app-modal>

<!-- Add Custom Date Modal -->
<x-app-modal id="addDateModal" maxWidth="sm" title="Tambah Tanggal Lainnya" description="Cukup nama dan satu tanggal." iconColor="emerald">
    <form action="{{ route('admin.ppdb.settings.wave-dates.store') }}" method="POST" id="addDateForm">
        @csrf
        <input type="hidden" name="academic_year_id" value="{{ $academicYear->id }}">
        <div class="space-y-4">
            <div>
                <label class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-1.5">Nama Tanggal</label>
                <input type="text" name="name" placeholder="Mis: Pengumuman, MPLS, Hari Pertama, ..." required
                    class="w-full py-2 px-3 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
            </div>
            <div>
                <label class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-1.5">Tanggal</label>
                <input type="date" name="date" required
                    class="w-full py-2 px-3 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
            </div>
        </div>
    </form>
    <x-slot name="footer">
        <button onclick="AppModal.close('addDateModal')" class="modal-btn-cancel">Batal</button>
        <button onclick="document.getElementById('addDateForm').submit();" class="modal-btn-primary">Simpan Tanggal</button>
    </x-slot>
</x-app-modal>

<script>
    const wavesData = @json($wavesData);

    function openEditWave(id) {
        const wave = wavesData.find(w => w.id === id);
        if (!wave) return;

        document.getElementById('edit_wave_name').value = wave.name;
        document.getElementById('edit_wave_start').value = wave.start_date;
        document.getElementById('edit_wave_end').value = wave.end_date;

        const checkbox = document.getElementById('edit_wave_active');
        checkbox.checked = wave.is_active;
        updateEditWaveStatusLabel();

        document.getElementById('editWaveForm').action = '{{ url('admin/ppdb/settings/waves') }}/' + id;
        AppModal.open('editWaveModal');
    }

    function updateEditWaveStatusLabel() {
        const checkbox = document.getElementById('edit_wave_active');
        const label = document.getElementById('edit_wave_status_label');
        label.textContent = checkbox.checked ? 'Aktif' : 'Nonaktif';
        label.className = 'text-xs font-medium ' + (checkbox.checked ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-zinc-500');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const toggleCheckbox = document.getElementById('edit_wave_active');
        if (toggleCheckbox) {
            toggleCheckbox.addEventListener('change', updateEditWaveStatusLabel);
        }
    });

    function confirmDeleteWave(id, name) {
        AppPopup.confirm({
            title: 'Hapus Gelombang',
            description: `Semua pendaftar di gelombang <strong>${name}</strong> akan dilepaskan. Yakin ingin menghapus?`,
            confirmText: 'Ya, Hapus',
            cancelText: 'Batal',
            onConfirm: function() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ url('admin/ppdb/settings/waves') }}/' + id;
                form.style.display = 'none';
                const csrf = document.createElement('input');
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                const method = document.createElement('input');
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection