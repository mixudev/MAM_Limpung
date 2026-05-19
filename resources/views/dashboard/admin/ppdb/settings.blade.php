@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Pengaturan PPDB';
        }
    });
</script>

<div class="space-y-6">

    <!-- Header Panel -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Pengaturan & Konfigurasi PPDB</h1>
            <p class="text-sm text-slate-500 dark:text-zinc-400 mt-1">Konfigurasi alur penerimaan, checklist berkas syarat, dan kelola input formulir dinamis.</p>
        </div>
        <a href="{{ route('admin.ppdb.index') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all text-center">
            Kembali ke Pendaftar
        </a>
    </div>

    <!-- Alert Success / Errors -->
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

    <!-- Tab Selection Buttons -->
    <div class="border-b border-slate-200 dark:border-zinc-800 flex flex-wrap gap-2">
        <button onclick="switchTab('tab-umum')" id="btn-tab-umum" class="tab-btn px-5 py-3 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-[#4f45b2] text-[#4f45b2] dark:text-white transition-all rounded-none">
            1. Umum & Alur PPDB
        </button>
        <button onclick="switchTab('tab-persyaratan')" id="btn-tab-persyaratan" class="tab-btn px-5 py-3 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 transition-all rounded-none">
            2. Persyaratan Berkas
        </button>
        <button onclick="switchTab('tab-formulir')" id="btn-tab-formulir" class="tab-btn px-5 py-3 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 transition-all rounded-none">
            3. Pembangun Formulir
        </button>
    </div>

    <!-- Modular Tab Panels -->
    @include('dashboard.admin.ppdb.partials.settings.general')
    @include('dashboard.admin.ppdb.partials.settings.requirements')
    @include('dashboard.admin.ppdb.partials.settings.builder')

</div>

<script>
    // ════════════ 1. State Pelacakan Perubahan (Dirty States) ════════════
    let requirementsDirty = false;
    let formFieldsDirty = false;

    // Track dirty states on user input
    document.addEventListener("DOMContentLoaded", function() {
        const reqForm = document.getElementById('requirementsForm');
        const fieldsForm = document.getElementById('formFieldsForm');

        if (reqForm) {
            reqForm.addEventListener('input', () => { requirementsDirty = true; });
            reqForm.addEventListener('submit', () => { requirementsDirty = false; });
        }
        if (fieldsForm) {
            fieldsForm.addEventListener('input', () => { formFieldsDirty = true; });
            fieldsForm.addEventListener('submit', () => { formFieldsDirty = false; });
        }
    });

    // Native browser confirm dialog on page close or reload
    window.addEventListener('beforeunload', function (e) {
        if (requirementsDirty || formFieldsDirty) {
            e.preventDefault();
            e.returnValue = 'Perubahan Anda belum disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?';
        }
    });

    // ════════════ 2. Tab Switching Controller ════════════
    function switchTab(tabId) {
        const activeTab = localStorage.getItem('active_ppdb_setting_tab') || 'tab-umum';
        
        // Interrupt tab switching if there are unsaved changes
        if (activeTab === 'tab-persyaratan' && requirementsDirty) {
            if (!confirm('Perubahan pada Persyaratan Berkas belum disimpan. Lanjutkan ke tab lain tanpa menyimpan?')) {
                return;
            }
            requirementsDirty = false; // Reset if user chooses to proceed anyway
        }

        if (activeTab === 'tab-formulir' && formFieldsDirty) {
            if (!confirm('Perubahan pada Pembangun Formulir belum disimpan. Lanjutkan ke tab lain tanpa menyimpan?')) {
                return;
            }
            formFieldsDirty = false; // Reset if user chooses to proceed anyway
        }

        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        // Show active tab
        document.getElementById(tabId).classList.remove('hidden');

        // Reset all buttons style
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-[#4f45b2]', 'text-[#4f45b2]', 'dark:text-white');
            btn.classList.add('border-transparent', 'text-slate-400', 'dark:text-zinc-500');
        });

        // Set active button style
        const activeBtn = document.getElementById('btn-' + tabId);
        activeBtn.classList.remove('border-transparent', 'text-slate-400', 'dark:text-zinc-500');
        activeBtn.classList.add('border-[#4f45b2]', 'text-[#4f45b2]', 'dark:text-white');
        
        // Save tab state to localStorage
        localStorage.setItem('active_ppdb_setting_tab', tabId);
    }

    // Load active tab on boot
    document.addEventListener("DOMContentLoaded", function() {
        const storedTab = localStorage.getItem('active_ppdb_setting_tab');
        if (storedTab && document.getElementById(storedTab)) {
            switchTab(storedTab);
        }
    });

    // ════════════ 3. Interactive Requirements Table Manager ════════════
    let reqIndexCount = {{ count($requirements) }};

    function addNewRequirementRow() {
        const tableBody = document.getElementById('requirementsTableBody');
        const placeholder = document.getElementById('no-requirements-placeholder');
        if (placeholder) {
            placeholder.remove();
        }

        const newRow = document.createElement('tr');
        newRow.id = `req-row-${reqIndexCount}`;
        newRow.className = 'hover:bg-slate-50/20 transition-all';
        newRow.innerHTML = `
            <td class="px-4 py-3 whitespace-nowrap">
                <input type="text" name="requirements[${reqIndexCount}][id]" id="req-id-input-${reqIndexCount}" required placeholder="e.g. scan_skhun"
                    class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs font-mono text-slate-700 dark:text-zinc-300 py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]"
                    oninput="syncReqSlug(${reqIndexCount})">
            </td>
            <td class="px-4 py-3">
                <input type="text" name="requirements[${reqIndexCount}][label]" id="req-label-input-${reqIndexCount}" required placeholder="e.g. Scan SKHUN SMP/MTs"
                    class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]"
                    oninput="syncReqSlug(${reqIndexCount})">
            </td>
            <td class="px-4 py-3 whitespace-nowrap">
                <select name="requirements[${reqIndexCount}][required]" 
                    class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2">
                    <option value="1">WAJIB (Required)</option>
                    <option value="0">OPSIONAL (Optional)</option>
                </select>
            </td>
            <td class="px-4 py-3 text-right whitespace-nowrap">
                <button type="button" onclick="removeRequirementRow('${reqIndexCount}')" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-100/50 rounded-none transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </td>
        `;
        tableBody.appendChild(newRow);
        reqIndexCount++;
        requirementsDirty = true; // Mark as modified
    }

    function removeRequirementRow(rowId) {
        const row = document.getElementById(`req-row-${rowId}`);
        if (row) {
            row.remove();
        }
        requirementsDirty = true; // Mark as modified

        const tableBody = document.getElementById('requirementsTableBody');
        if (tableBody.children.length === 0) {
            tableBody.innerHTML = `
                <tr id="no-requirements-placeholder">
                    <td colspan="4" class="text-center py-8 text-slate-400 dark:text-zinc-500 text-xs">Belum ada persyaratan berkas yang didaftarkan.</td>
                </tr>
            `;
        }
    }

    function syncReqSlug(rowId) {
        const label = document.getElementById(`req-label-input-${rowId}`).value;
        const idInput = document.getElementById(`req-id-input-${rowId}`);
        if (idInput && label) {
            const slugged = label.toLowerCase()
                                 .replace(/[^a-z0-9_]+/g, '_')
                                 .replace(/^_+|_+$/g, '');
            idInput.value = slugged;
        }
    }

    // ════════════ 4. Interactive Form Builder Table Manager ════════════
    let fieldIndexCount = {{ count($formFields) }};

    function addNewFormFieldRow() {
        const tableBody = document.getElementById('formFieldsTableBody');
        const placeholder = document.getElementById('no-fields-placeholder');
        if (placeholder) {
            placeholder.remove();
        }

        const newRow = document.createElement('tr');
        newRow.id = `field-row-${fieldIndexCount}`;
        newRow.className = 'hover:bg-slate-50/20 transition-all cursor-move';
        newRow.draggable = true;
        newRow.innerHTML = `
            <!-- Drag Handle -->
            <td class="drag-handle px-2 py-3 text-center whitespace-nowrap text-slate-455 dark:text-zinc-650 cursor-grab active:cursor-grabbing">
                <svg class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </td>
            <!-- ID Slug -->
            <td class="px-4 py-3 whitespace-nowrap">
                <input type="text" name="fields[${fieldIndexCount}][id]" id="field-id-input-${fieldIndexCount}" required placeholder="e.g. pekerjaan_ibu"
                    class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs font-mono text-slate-700 dark:text-zinc-300 py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]"
                    oninput="syncFieldSlug(${fieldIndexCount})">
            </td>
            <!-- Label & Options -->
            <td class="px-4 py-3">
                <div class="space-y-2">
                    <input type="text" name="fields[${fieldIndexCount}][label]" id="field-label-input-${fieldIndexCount}" required placeholder="e.g. Pekerjaan Ibu Kandung"
                        class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]"
                        oninput="syncFieldSlug(${fieldIndexCount})">
                    
                    <div id="field-options-wrapper-${fieldIndexCount}" class="hidden">
                        <span class="text-[10px] font-mono font-bold text-slate-400 dark:text-zinc-500 block mb-1">PILIHAN DROPDOWN (Pisahkan dengan koma):</span>
                        <input type="text" name="fields[${fieldIndexCount}][options]" id="field-options-input-${fieldIndexCount}" placeholder="e.g. Pilihan 1, Pilihan 2, Pilihan 3"
                            class="w-full bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 rounded-none text-xs py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                    </div>
                </div>
            </td>
            <!-- Type -->
            <td class="px-4 py-3 whitespace-nowrap">
                <select name="fields[${fieldIndexCount}][type]" id="field-type-select-${fieldIndexCount}"
                    class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2"
                    onchange="toggleOptionsInputRow(${fieldIndexCount})">
                    <option value="text">Teks Singkat</option>
                    <option value="number">Angka</option>
                    <option value="select">Dropdown Menu</option>
                    <option value="date">Tanggal</option>
                    <option value="textarea">Teks Panjang</option>
                </select>
            </td>
            <!-- Required -->
            <td class="px-4 py-3 whitespace-nowrap">
                <select name="fields[${fieldIndexCount}][required]" 
                    class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2">
                    <option value="0">OPSIONAL</option>
                    <option value="1">WAJIB</option>
                </select>
            </td>
            <!-- Delete Action -->
            <td class="px-4 py-3 text-right whitespace-nowrap">
                <button type="button" onclick="removeFormFieldRow('${fieldIndexCount}')" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-100/50 rounded-none transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </td>
        `;
        tableBody.appendChild(newRow);
        addDragListeners(newRow);
        fieldIndexCount++;
        formFieldsDirty = true;
    }

    function removeFormFieldRow(rowId, fieldLabel = '') {
        const title = fieldLabel ? `Hapus ${fieldLabel}` : 'Hapus Kolom';
        const desc = fieldLabel 
            ? `Apakah Anda yakin ingin menghapus kolom kustom <strong>${fieldLabel}</strong> dari pendaftaran? Semua isian calon siswa pada kolom ini akan hilang setelah form disimpan.` 
            : 'Apakah Anda yakin ingin menghapus kolom kustom ini?';

        AppPopup.confirm({
            title: title,
            description: desc,
            confirmText: 'Ya, Hapus',
            cancelText: 'Batal',
            onConfirm: () => {
                const row = document.getElementById(`field-row-${rowId}`);
                if (row) {
                    row.remove();
                }
                formFieldsDirty = true; // Mark as modified
                reindexFormFields();

                const tableBody = document.getElementById('formFieldsTableBody');
                if (tableBody.children.length === 0) {
                    tableBody.innerHTML = `
                        <tr id="no-fields-placeholder">
                            <td colspan="6" class="text-center py-10 text-slate-400 dark:text-zinc-500 text-xs">Belum ada kolom kustom tambahan. Formulir pendaftaran hanya memuat kolom inti.</td>
                        </tr>
                    `;
                }
            }
        });
    }

    function syncFieldSlug(rowId) {
        const label = document.getElementById(`field-label-input-${rowId}`).value;
        const idInput = document.getElementById(`field-id-input-${rowId}`);
        if (idInput && label) {
            const slugged = label.toLowerCase()
                                 .replace(/[^a-z0-9_]+/g, '_')
                                 .replace(/^_+|_+$/g, '');
            idInput.value = slugged;
        }
    }

    function toggleOptionsInputRow(rowId) {
        const typeSelect = document.getElementById(`field-type-select-${rowId}`);
        const optionsWrapper = document.getElementById(`field-options-wrapper-${rowId}`);
        const optionsInput = document.getElementById(`field-options-input-${rowId}`);
        
        if (typeSelect && optionsWrapper && optionsInput) {
            if (typeSelect.value === 'select') {
                optionsWrapper.classList.remove('hidden');
                optionsInput.required = true;
            } else {
                optionsWrapper.classList.add('hidden');
                optionsInput.required = false;
                optionsInput.value = '';
            }
        }
    }

    function reindexFormFields() {
        const rows = document.querySelectorAll('#formFieldsTableBody tr');
        let idx = 0;
        rows.forEach(row => {
            if (row.id === 'no-fields-placeholder') return;
            
            row.id = `field-row-${idx}`;
            
            const idInput = row.querySelector('input[name$="[id]"]');
            if (idInput) {
                idInput.name = `fields[${idx}][id]`;
                idInput.id = `field-id-input-${idx}`;
                idInput.setAttribute('oninput', `syncFieldSlug(${idx})`);
            }
            
            const labelInput = row.querySelector('input[name$="[label]"]');
            if (labelInput) {
                labelInput.name = `fields[${idx}][label]`;
                labelInput.id = `field-label-input-${idx}`;
                labelInput.setAttribute('oninput', `syncFieldSlug(${idx})`);
            }
            
            const typeSelect = row.querySelector('select[name$="[type]"]');
            if (typeSelect) {
                typeSelect.name = `fields[${idx}][type]`;
                typeSelect.id = `field-type-select-${idx}`;
                typeSelect.setAttribute('onchange', `toggleOptionsInputRow(${idx})`);
            }
            
            const requiredSelect = row.querySelector('select[name$="[required]"]');
            if (requiredSelect) {
                requiredSelect.name = `fields[${idx}][required]`;
            }
            
            const optionsInput = row.querySelector('input[name$="[options]"]');
            if (optionsInput) {
                optionsInput.name = `fields[${idx}][options]`;
                optionsInput.id = `field-options-input-${idx}`;
            }
            
            const optionsWrapper = row.querySelector('[id^="field-options-wrapper-"]');
            if (optionsWrapper) {
                optionsWrapper.id = `field-options-wrapper-${idx}`;
            }
            
            const deleteBtn = row.querySelector('button[onclick^="removeFormFieldRow"]');
            if (deleteBtn) {
                const labelVal = labelInput ? labelInput.value.replace(/'/g, "\\'") : '';
                deleteBtn.setAttribute('onclick', `removeFormFieldRow(${idx}, '${labelVal}')`);
            }
            
            idx++;
        });
        fieldIndexCount = idx;
    }

    // Drag and Drop implementation for Form Fields Table Rows
    let dragSrcEl = null;

    function addDragListeners(row) {
        row.addEventListener('dragstart', handleDragStart, false);
        row.addEventListener('dragover', handleDragOver, false);
        row.addEventListener('dragenter', handleDragEnter, false);
        row.addEventListener('dragleave', handleDragLeave, false);
        row.addEventListener('drop', handleDrop, false);
        row.addEventListener('dragend', handleDragEnd, false);
    }

    function handleDragStart(e) {
        const handle = e.target.closest('td');
        if (!handle || !handle.classList.contains('drag-handle')) {
            e.preventDefault();
            return;
        }
        this.classList.add('opacity-40', 'bg-slate-100', 'dark:bg-zinc-800');
        dragSrcEl = this;
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.outerHTML);
    }

    function handleDragOver(e) {
        if (e.preventDefault) {
            e.preventDefault();
        }
        this.classList.add('border-t-2', 'border-[#4f45b2]');
        return false;
    }

    function handleDragEnter(e) {
        // Already handled by dragover styling
    }

    function handleDragLeave(e) {
        this.classList.remove('border-t-2', 'border-[#4f45b2]');
    }

    function handleDrop(e) {
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        
        this.classList.remove('border-t-2', 'border-[#4f45b2]');
        
        if (dragSrcEl !== this) {
            const list = Array.from(this.parentNode.children);
            const dragIdx = list.indexOf(dragSrcEl);
            const targetIdx = list.indexOf(this);
            
            if (dragIdx < targetIdx) {
                this.parentNode.insertBefore(dragSrcEl, this.nextSibling);
            } else {
                this.parentNode.insertBefore(dragSrcEl, this);
            }
            
            reindexFormFields();
            formFieldsDirty = true;
        }
        return false;
    }

    function handleDragEnd(e) {
        this.classList.remove('opacity-40', 'bg-slate-100', 'dark:bg-zinc-800');
        document.querySelectorAll('#formFieldsTableBody tr').forEach(row => {
            row.classList.remove('border-t-2', 'border-[#4f45b2]');
        });
    }

    // Initialize drag events for existing rows
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('#formFieldsTableBody tr').forEach(row => {
            if (row.id !== 'no-fields-placeholder') {
                addDragListeners(row);
            }
        });
    });
</script>
@endsection
