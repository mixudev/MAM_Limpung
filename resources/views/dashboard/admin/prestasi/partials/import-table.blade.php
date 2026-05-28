<div x-show="rows.length > 0" x-cloak
    class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
    
    {{-- Header Bar --}}
    <div class="px-5 py-3 border-b border-slate-100 dark:border-zinc-800 flex items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <span
                class="text-xs font-bold font-mono uppercase tracking-wider text-slate-700 dark:text-zinc-300">Pratinjau &amp; Koreksi Data Langsung</span>
        </div>
        <div class="flex items-center gap-3">
            <span
                class="inline-flex items-center gap-1 text-[11px] font-mono font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/40 px-2.5 py-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span x-text="validCount">0</span> SIAP IMPORT
            </span>
            <span
                class="inline-flex items-center gap-1 text-[11px] font-mono font-bold text-rose-700 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800/40 px-2.5 py-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01" />
                </svg>
                <span x-text="invalidCount">0</span> PERLU DIPERBAIKI
            </span>
        </div>
    </div>

    {{-- Info bar if there are issues --}}
    <div x-show="invalidCount > 0"
        class="px-5 py-3 bg-amber-50 dark:bg-amber-950/10 border-b border-amber-200 dark:border-amber-800/30 text-xs text-amber-800 dark:text-amber-400 flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
        </svg>
        <span>Beberapa baris memiliki kesalahan input (ditandai border kuning & status PERBAIKI). Klik <strong>Edit</strong> untuk membuka formulir perbaikan.</span>
    </div>

    {{-- Filter, Search, and Pagination controls --}}
    <div class="p-4 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3">
            {{-- Search Box --}}
            <div class="relative min-w-[280px]">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" x-model="searchQuery" @input="currentPage = 1; expandedRow = null" placeholder="Cari judul prestasi atau peraih..."
                    class="w-full pl-9 pr-4 py-2 text-xs border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 rounded-none focus:outline-none focus:border-[#4f45b2] focus:ring-1 focus:ring-[#4f45b2]/40 transition-all">
            </div>

            {{-- Filter Status --}}
            <div class="flex items-center gap-2">
                <label class="text-[10px] font-bold font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">Status:</label>
                <select x-model="statusFilter" @change="currentPage = 1; expandedRow = null"
                    class="px-3 py-2 text-xs border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 rounded-none focus:outline-none focus:border-[#4f45b2] font-sans">
                    <option value="all">Semua Baris</option>
                    <option value="valid">Siap Import (Valid)</option>
                    <option value="invalid">Perlu Diperbaiki (Error)</option>
                    <option value="duplicate">Duplikat / Sudah Ada</option>
                </select>
            </div>
        </div>

        {{-- Rows per Page --}}
        <div class="flex items-center gap-2">
            <label class="text-[10px] font-bold font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">Tampilkan:</label>
            <select x-model="perPage" @change="currentPage = 1; expandedRow = null"
                class="px-2.5 py-1.5 text-xs border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 rounded-none focus:outline-none focus:border-[#4f45b2] font-sans">
                <option value="10">10 baris</option>
                <option value="25">25 baris</option>
                <option value="50">50 baris</option>
            </select>
        </div>
    </div>

    {{-- Simplified Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left min-w-[750px] table-fixed">
            <thead>
                <tr class="bg-slate-50 dark:bg-zinc-800/60 border-b border-slate-200 dark:border-zinc-700">
                    <th class="px-4 py-3.5 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400 w-16 text-center">#</th>
                    <th class="px-4 py-3.5 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400 w-48">Peraih (Siswa/Tim)</th>
                    <th class="px-4 py-3.5 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400">Judul Prestasi</th>
                    <th class="px-4 py-3.5 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400 w-24 text-center">Tahun</th>
                    <th class="px-4 py-3.5 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400 w-44 text-center">Status</th>
                    <th class="px-4 py-3.5 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400 w-32 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/60">
                <template x-for="(row, index) in paginatedRows()" :key="row.row_number">
                    <tr class="transition-colors"
                        :class="Object.keys(validateRow(row)).length > 0 || (row.serverErrors && row.serverErrors.length > 0) ? 'bg-amber-50/10 dark:bg-amber-950/5' : 'bg-white dark:bg-zinc-900 hover:bg-slate-50/30 dark:hover:bg-zinc-800/20'">
                        
                        {{-- Row index --}}
                        <td :class="Object.keys(validateRow(row)).length > 0 || (row.serverErrors && row.serverErrors.length > 0) ? 'border-l-4 border-[#f59e0b]' : ''"
                            class="px-4 py-3 text-[10px] font-mono text-slate-400 dark:text-zinc-500 text-center">
                            <span x-text="((currentPage - 1) * perPage) + index + 1"></span>
                        </td>

                        {{-- Peraih (Regular text) --}}
                        <td class="px-4 py-3 truncate">
                            <span class="font-normal text-slate-700 dark:text-zinc-300" x-text="row.peraih || '(Kosong)'"></span>
                        </td>

                        {{-- Judul (Regular text) --}}
                        <td class="px-4 py-3 truncate">
                            <span class="text-slate-700 dark:text-zinc-300 font-normal" x-text="row.judul || '(Kosong)'"></span>
                        </td>

                        {{-- Tahun --}}
                        <td class="px-4 py-3 text-center text-slate-600 dark:text-zinc-400 font-mono">
                            <span x-text="row.tahun || '-'"></span>
                        </td>

                        {{-- Status Badges --}}
                        <td class="px-4 py-3 text-center">
                            <div class="flex flex-wrap gap-1 justify-center">
                                {{-- Siap Import badge --}}
                                <template x-if="Object.keys(validateRow(row)).length === 0 && (!row.serverErrors || row.serverErrors.length === 0) && !row.is_file_duplicate && !row.is_duplicate">
                                    <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase border bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-800/40">SIAP IMPORT</span>
                                </template>
                                
                                {{-- Invalid/Missing values badge --}}
                                <template x-if="Object.keys(validateRow(row)).length > 0">
                                    <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase border bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-800/40">PERBAIKI</span>
                                </template>

                                {{-- Server Error (after import attempt) --}}
                                <template x-if="row.serverErrors && row.serverErrors.length > 0">
                                    <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase border bg-red-50 text-red-700 border-red-200 dark:bg-red-950/20 dark:text-red-400 dark:border-red-800/40">ERROR SYSTEM</span>
                                </template>

                                {{-- File Duplicate badge --}}
                                <template x-if="row.is_file_duplicate">
                                    <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase border bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-800/40">DUPLIKAT FILE</span>
                                </template>

                                {{-- DB Duplicate (update warning) --}}
                                <template x-if="row.is_duplicate && !row.is_file_duplicate">
                                    <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase border bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-800/40">UPDATE DATA</span>
                                </template>
                            </div>
                        </td>

                        {{-- Actions (Open Modal) --}}
                        <td class="px-4 py-3 text-center space-x-1 whitespace-nowrap">
                            <button type="button" @click="editingRow = row; AppModal.open('editModal')"
                                class="inline-block py-1 px-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 font-bold text-[10px] uppercase font-mono tracking-wider transition-all rounded-none">
                                Edit
                            </button>
                            <button type="button" @click="rows.splice(rows.findIndex(r => r.row_number === row.row_number), 1); recalculateCounts();"
                                class="inline-block py-1 px-2.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-900/40 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-400 font-bold text-[10px] uppercase font-mono tracking-wider transition-all rounded-none">
                                Hapus
                            </button>
                        </td>
                    </tr>
                </template>

                {{-- Empty state --}}
                <template x-if="filteredRows().length === 0">
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 dark:text-zinc-500 italic text-xs">
                            Tidak ada data pratinjau yang cocok dengan filter atau pencarian.
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Pagination Bar --}}
    <div x-show="totalPages() > 1" class="px-5 py-4 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/30 dark:bg-zinc-900/30 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-[11px] text-slate-500 dark:text-zinc-400 font-mono">
            Menampilkan baris ke <span class="font-bold" x-text="((currentPage - 1) * perPage) + 1"></span> sampai <span class="font-bold" x-text="Math.min(currentPage * perPage, filteredRows().length)"></span> dari <span class="font-bold" x-text="filteredRows().length"></span> baris data
        </div>
        <div class="flex items-center gap-1.5">
            {{-- Prev Page --}}
            <button type="button" @click="prevPage()" :disabled="currentPage === 1"
                class="py-1.5 px-3 border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider transition-all disabled:opacity-40 disabled:cursor-not-allowed rounded-none">
                Sebelumnya
            </button>
            
            {{-- Page Number Indicator --}}
            <span class="text-[11px] text-slate-600 dark:text-zinc-400 font-mono px-2">
                Halaman <span class="font-bold" x-text="currentPage"></span> dari <span class="font-bold" x-text="totalPages()"></span>
            </span>

            {{-- Next Page --}}
            <button type="button" @click="nextPage()" :disabled="currentPage === totalPages()"
                class="py-1.5 px-3 border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider transition-all disabled:opacity-40 disabled:cursor-not-allowed rounded-none">
                Berikutnya
            </button>
        </div>
    </div>

    {{-- ── Edit Modal Component (x-app-modal) ── --}}
    <x-app-modal id="editModal" title="Koreksi Data Prestasi" maxWidth="2xl" iconColor="indigo">
        <template x-if="editingRow !== null">
            <div class="space-y-4">
                {{-- Warnings block --}}
                <template x-if="Object.keys(validateRow(editingRow)).length > 0 || (editingRow.serverErrors && editingRow.serverErrors.length > 0) || editingRow.is_file_duplicate || editingRow.is_duplicate">
                    <div class="p-3.5 bg-rose-50/50 dark:bg-rose-950/15 border border-rose-200 dark:border-rose-900/45 text-[11px] text-rose-700 dark:text-rose-400 font-mono space-y-1 rounded-none">
                        <p class="font-bold uppercase tracking-wider text-rose-800 dark:text-rose-300">Pemberitahuan Status:</p>
                        <ul class="list-disc pl-4 space-y-0.5">
                            <template x-if="validateRow(editingRow).judul">
                                <li x-text="validateRow(editingRow).judul"></li>
                            </template>
                            <template x-if="validateRow(editingRow).peraih">
                                <li x-text="validateRow(editingRow).peraih"></li>
                            </template>
                            <template x-if="validateRow(editingRow).tahun">
                                <li x-text="validateRow(editingRow).tahun"></li>
                            </template>
                            <template x-if="validateRow(editingRow).tanggal">
                                <li x-text="validateRow(editingRow).tanggal"></li>
                            </template>
                            <template x-if="validateRow(editingRow).tingkat">
                                <li x-text="validateRow(editingRow).tingkat"></li>
                            </template>
                            <template x-if="validateRow(editingRow).jenis">
                                <li x-text="validateRow(editingRow).jenis"></li>
                            </template>
                            <template x-for="serr in editingRow.serverErrors">
                                <li x-text="serr"></li>
                            </template>
                            <template x-if="editingRow.is_file_duplicate">
                                <li>Baris terduplikasi dengan data lain di file Excel.</li>
                            </template>
                            <template x-if="editingRow.is_duplicate && !editingRow.is_file_duplicate">
                                <li class="text-blue-700 dark:text-blue-400">Data prestasi ini sudah ada di database. Mengimpor data ini akan <strong>memperbarui/menimpa</strong> data lama.</li>
                            </template>
                        </ul>
                    </div>
                </template>

                {{-- Detail Edit Form Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Judul Prestasi --}}
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1">Judul Prestasi / Kejuaraan *</label>
                        <input type="text" x-model="editingRow.judul" @input="onRowFieldChange(editingRow)"
                            class="w-full px-3 py-2 text-xs border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 rounded-none focus:outline-none focus:border-[#4f45b2]">
                    </div>

                    {{-- Peraih --}}
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1">Peraih (Siswa / Tim) *</label>
                        <input type="text" x-model="editingRow.peraih" @input="onRowFieldChange(editingRow)"
                            class="w-full px-3 py-2 text-xs border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 rounded-none focus:outline-none focus:border-[#4f45b2]">
                    </div>

                    {{-- Juara --}}
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1">Juara (contoh: Juara 1, Harapan 2)</label>
                        <input type="text" x-model="editingRow.juara" @input="onRowFieldChange(editingRow)"
                            class="w-full px-3 py-2 text-xs border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 rounded-none focus:outline-none focus:border-[#4f45b2]">
                    </div>

                    {{-- Tahun --}}
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1">Tahun Prestasi *</label>
                        <input type="number" x-model="editingRow.tahun" @input="onRowFieldChange(editingRow)" min="2000" max="2100"
                            class="w-full px-3 py-2 text-xs border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 rounded-none focus:outline-none focus:border-[#4f45b2]">
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1">Tanggal Prestasi (Opsional)</label>
                        <input type="date" x-model="editingRow.tanggal" @input="onRowFieldChange(editingRow)"
                            class="w-full px-3 py-2 text-xs border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 rounded-none focus:outline-none focus:border-[#4f45b2]">
                    </div>

                    {{-- Tingkat --}}
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1">Tingkat *</label>
                        <select x-model="editingRow.tingkat" @change="onRowFieldChange(editingRow)"
                            class="w-full px-3 py-2 text-xs border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 rounded-none focus:outline-none focus:border-[#4f45b2]">
                            <option value="">-- Pilih Tingkat --</option>
                            <option value="sekolah">Sekolah</option>
                            <option value="kabupaten">Kabupaten/Kota</option>
                            <option value="provinsi">Provinsi</option>
                            <option value="nasional">Nasional</option>
                            <option value="internasional">Internasional</option>
                        </select>
                    </div>

                    {{-- Jenis --}}
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1">Jenis Prestasi *</label>
                        <select x-model="editingRow.jenis" @change="onRowFieldChange(editingRow)"
                            class="w-full px-3 py-2 text-xs border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 rounded-none focus:outline-none focus:border-[#4f45b2]">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="akademik">Akademik</option>
                            <option value="non_akademik">Non-Akademik</option>
                        </select>
                    </div>

                    {{-- Penyelenggara --}}
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1">Penyelenggara Kegiatan</label>
                        <input type="text" x-model="editingRow.penyelenggara" @input="onRowFieldChange(editingRow)"
                            class="w-full px-3 py-2 text-xs border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 rounded-none focus:outline-none focus:border-[#4f45b2]">
                    </div>

                    {{-- Unggulan --}}
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1">Jadikan Prestasi Unggulan?</label>
                        <select x-model="editingRow.unggulan" @change="onRowFieldChange(editingRow)"
                            class="w-full px-3 py-2 text-xs border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 rounded-none focus:outline-none focus:border-[#4f45b2]">
                            <option value="Ya">Ya (Ditampilkan di slider utama)</option>
                            <option value="Tidak">Tidak</option>
                        </select>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1">Deskripsi & Catatan Prestasi</label>
                        <textarea x-model="editingRow.deskripsi" @input="onRowFieldChange(editingRow)" rows="2" placeholder="Detail deskripsi kegiatan prestasi..."
                            class="w-full px-3 py-2 text-xs border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 rounded-none focus:outline-none focus:border-[#4f45b2]"></textarea>
                    </div>
                </div>
            </div>
        </template>
        
        <x-slot name="footer">
            <button type="button" onclick="AppModal.close('editModal')" class="modal-btn-primary">
                TUTUP & SIMPAN
            </button>
        </x-slot>
    </x-app-modal>
</div>
