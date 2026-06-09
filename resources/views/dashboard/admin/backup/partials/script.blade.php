<script>
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-indigo-600', 'text-indigo-600', 'dark:text-white');
            btn.classList.add('border-transparent', 'text-slate-400', 'dark:text-zinc-500');
        });
        document.getElementById(tabId).classList.remove('hidden');
        const activeBtn = document.getElementById('btn-' + tabId);
        activeBtn.classList.remove('border-transparent', 'text-slate-400', 'dark:text-zinc-500');
        activeBtn.classList.add('border-indigo-600', 'text-indigo-600', 'dark:text-white');
    }

    function toggleCronInput() {
        const schedule = document.getElementById('schedule-selector').value;
        document.getElementById('cron-expression-wrapper').classList.toggle('hidden', schedule !== 'custom');
    }

    function toggleStorageFolders() {
        const checkbox = document.getElementById('backup-storage-checkbox');
        const wrapper = document.getElementById('storage-folders-wrapper');
        if (checkbox && wrapper) wrapper.classList.toggle('hidden', !checkbox.checked);
    }

    function logToTerminal(message, type = 'info') {
        const terminal = document.getElementById('terminal-log-content');
        const line = document.createElement('div');
        const timestamp = new Date().toLocaleTimeString();
        const colorMap = { success: 'text-emerald-400 font-semibold', error: 'text-rose-500 font-semibold', warn: 'text-amber-400', system: 'text-indigo-400', info: 'text-zinc-300' };
        line.className = `${colorMap[type] || 'text-zinc-300'} leading-relaxed py-0.5`;
        line.innerHTML = `<span class="text-zinc-650">[${timestamp}]</span> ${message}`;
        terminal.appendChild(line);
        terminal.scrollTop = terminal.scrollHeight;
    }

    function triggerManualBackup() {
        const btn = document.getElementById('manual-backup-btn');
        const indicator = document.getElementById('terminal-indicator');
        const statusText = document.getElementById('terminal-status-text');
        const spinner = document.getElementById('terminal-spinner-wrapper');
        const content = document.getElementById('terminal-log-content');

        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        spinner.classList.remove('hidden');
        indicator.className = 'w-2 h-2 rounded-full bg-indigo-500 animate-ping';
        statusText.innerText = 'Status: Memproses...';
        content.innerHTML = '';
        logToTerminal('Memulai inisialisasi backup manual...', 'system');

        fetch("{{ route('admin.backup.run') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json().then(data => ({ ok: r.ok, data })))
        .then(({ ok, data }) => {
            if (!ok) return Promise.reject(data);
            logToTerminal('Inisialisasi berhasil!', 'success');
            logToTerminal(`Tipe: ${data.log.type}`, 'info');
            if (data.log.encrypted) logToTerminal('Enkripsi AES-256: AKTIF', 'success');
            if (data.log.drive_uploaded) logToTerminal(`Google Drive: SUKSES (${data.log.drive_file_id})`, 'success');
            else if (data.log.drive_error) logToTerminal(`Google Drive: GAGAL (${data.log.drive_error})`, 'error');
            logToTerminal(`Ukuran: ${data.log.formatted_size} | Durasi: ${data.log.duration}s`, 'system');
            logToTerminal(`Selesai! File: ${data.log.filename}`, 'success');
            indicator.className = 'w-2 h-2 rounded-full bg-emerald-500';
            statusText.innerText = 'Status: Sukses!';
            appendBackupLogToTable(data.log);
            btn.disabled = false; btn.classList.remove('opacity-50', 'cursor-not-allowed'); spinner.classList.add('hidden');
        })
        .catch(err => {
            logToTerminal(`GAGAL: ${err.message || err}`, 'error');
            indicator.className = 'w-2 h-2 rounded-full bg-rose-500';
            statusText.innerText = 'Status: Error!';
            if (err.log) appendBackupLogToTable(err.log);
            btn.disabled = false; btn.classList.remove('opacity-50', 'cursor-not-allowed'); spinner.classList.add('hidden');
        });
    }

    function appendBackupLogToTable(log) {
        const tbody = document.getElementById('backup-history-tbody');
        if (tbody.children.length === 1 && tbody.innerHTML.includes('Belum ada')) tbody.innerHTML = '';
        const downloadUrl = "{{ route('admin.backup.download', ['filename' => ':filename']) }}".replace(':filename', log.filename);
        const row = document.createElement('tr');
        row.className = "hover:bg-slate-50/60 dark:hover:bg-zinc-900/40 transition-colors";
        row.innerHTML = `<td class="py-3.5 px-4 font-mono text-[11px] text-slate-500 whitespace-nowrap">${log.formatted_date}</td>
            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-800 dark:text-zinc-200 break-all max-w-[200px]">${log.filename}</td>
            <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap">${log.status === 'success' ? log.formatted_size : '-'}</td>
            <td class="py-3.5 px-4 whitespace-nowrap">${log.encrypted ? '<span class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-600 bg-indigo-500/10 px-1.5 py-0.5 border border-indigo-500/15"><i class="fa-solid fa-lock text-[9px]"></i> AES-256</span>' : '<span class="text-[10px] text-slate-400">Tidak</span>'}</td>
            <td class="py-3.5 px-4 whitespace-nowrap"><span class="text-[10px] text-slate-400">-</span></td>
            <td class="py-3.5 px-4 text-center">${log.status === 'success' ? '<span class="inline-block text-[10px] font-bold px-2 py-0.5 bg-emerald-500 text-white">SUKSES</span>' : '<span class="inline-block text-[10px] font-bold px-2 py-0.5 bg-rose-500 text-white">GAGAL</span>'}</td>
            <td class="py-3.5 px-4 text-right space-x-1.5">${log.status === 'success' ? `<a href="${downloadUrl}" class="inline-flex items-center justify-center w-7 h-7 bg-indigo-50 dark:bg-zinc-800 text-indigo-600 border border-indigo-200 hover:bg-indigo-600 hover:text-white transition-colors"><i class="fa-solid fa-download text-[11px]"></i></a>` : ''}<button type="button" onclick="deleteBackup('${log.filename}')" class="inline-flex items-center justify-center w-7 h-7 bg-rose-50 dark:bg-zinc-800 text-rose-600 border border-rose-200 hover:bg-rose-600 hover:text-white transition-colors"><i class="fa-solid fa-trash-can text-[11px]"></i></button></td>`;
        tbody.insertBefore(row, tbody.firstChild);
    }

    function prefillVerification(filename) {
        document.getElementById('verify-filename').value = filename;
        switchTab('tab-verification');
        document.getElementById('verify-passphrase').focus();
    }

    function verifyBackupIntegrity() {
        const filename = document.getElementById('verify-filename').value;
        const passphrase = document.getElementById('verify-passphrase').value;
        const btn = document.getElementById('verify-submit-btn');
        if (!filename) { alert('Pilih berkas backup.'); return; }
        if (!passphrase) { alert('Masukkan sandi enkripsi.'); return; }

        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> MEMPROSES...';

        fetch("{{ route('admin.backup.verify') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ filename, passphrase })
        })
        .then(r => r.json().then(data => ({ status: r.status, body: data })))
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-shield-check"></i> VERIFIKASI SEKARANG';
            const reportCard = document.getElementById('verify-report-card');
            reportCard.classList.remove('hidden');
            if (res.status === 200 && res.body.success) {
                reportCard.className = "p-5 border border-emerald-250 dark:border-emerald-900 bg-emerald-50/15 shadow-sm transition-all duration-300";
                document.getElementById('verify-report-header').innerHTML = `<div class="p-2 bg-emerald-500 text-white flex-shrink-0"><i class="fa-solid fa-circle-check text-lg"></i></div><div><h3 class="text-sm font-bold text-emerald-800 dark:text-emerald-400">Integritas Berkas Terbukti Sempurna!</h3><p class="text-[11px] text-emerald-600 leading-relaxed mt-0.5">${res.body.message}</p></div>`;
                let tree = '';
                res.body.report.files.forEach(f => {
                    tree += `<div class="py-0.5 border-b border-zinc-900/60 flex justify-between"><span>${f.name.startsWith('database_dump') ? '<i class="fa-solid fa-database text-indigo-500"></i>' : '<i class="fa-regular fa-file text-slate-400"></i>'} ${f.name}</span><span class="text-zinc-550">${(f.size/1024).toFixed(1)} KB</span></div>`;
                });
                document.getElementById('verify-report-tree').innerHTML = tree;
            } else {
                reportCard.className = "p-5 border border-rose-250 dark:border-rose-955 bg-rose-50/15 shadow-sm transition-all duration-300";
                document.getElementById('verify-report-header').innerHTML = `<div class="p-2 bg-rose-500 text-white flex-shrink-0"><i class="fa-solid fa-triangle-exclamation text-lg"></i></div><div><h3 class="text-sm font-bold text-rose-800 dark:text-rose-400">Dekripsi Gagal!</h3><p class="text-[11px] text-rose-600 leading-relaxed mt-0.5">${res.body.message}</p></div>`;
                document.getElementById('verify-report-tree-wrapper').classList.add('hidden');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-shield-check"></i> VERIFIKASI SEKARANG';
        });
    }

    function deleteBackup(filename) {
        AppPopup.show({
            type: 'confirm',
            title: 'Hapus Berkas Backup?',
            description: `Apakah Anda yakin ingin menghapus <strong>${filename}</strong> secara permanen?`,
            confirmText: 'Ya, Hapus', cancelText: 'Batal',
            onConfirm: () => {
                const url = "{{ route('admin.backup.delete', ['filename' => ':filename']) }}".replace(':filename', filename);
                fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(r => r.json())
                .then(data => {
                    if (data.success) { AppPopup.success({ title: 'Berhasil!', description: data.message, duration: 2000 }); setTimeout(() => window.location.reload(), 2000); }
                    else AppPopup.error({ title: 'Gagal', description: data.message });
                });
            }
        });
    }

    function scanStorageDirectories() {
        const icon = document.getElementById('scan-btn-icon');
        const list = document.getElementById('storage-folders-list');
        icon.classList.add('fa-spin');
        fetch("{{ route('admin.backup.storage-directories') }}", { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            icon.classList.remove('fa-spin');
            if (data.success) {
                list.innerHTML = data.directories.length === 0
                    ? '<div class="col-span-2 py-4 text-center text-zinc-550 font-mono text-[10px]">Tidak ada folder.</div>'
                    : data.directories.map(d => `<label class="flex items-center justify-between p-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 hover:border-indigo-500 cursor-pointer transition-all select-none"><div class="flex items-center gap-2"><input type="checkbox" name="storage_folders[]" value="${d.name}" ${(data.selected_folders||[]).includes(d.name)?'checked':''} class="text-indigo-600"><span class="text-xs font-mono text-slate-700 dark:text-zinc-300">${d.name}</span></div><span class="text-[10px] font-mono font-bold text-slate-400">${d.formatted_size}</span></label>`).join('');
            }
        })
        .catch(() => icon.classList.remove('fa-spin'));
    }

    function showBackupLogDetails(id) {
        const url = "{{ route('admin.backup.log-details', ['id' => ':id']) }}".replace(':id', id);
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            const log = data.log;
            const statusBadge = log.status === 'success'
                ? '<span class="inline-block text-[10px] font-bold px-2 py-0.5 bg-emerald-500 text-white">SUKSES</span>'
                : '<span class="inline-block text-[10px] font-bold px-2 py-0.5 bg-rose-500 text-white">GAGAL</span>';
            const encBadge = log.encrypted
                ? '<span class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-600 bg-indigo-500/10 px-1.5 py-0.5 border border-indigo-500/15"><i class="fa-solid fa-lock text-[9px]"></i> AES-256</span>'
                : '<span class="text-[10px] text-slate-400">Tidak Terenkripsi</span>';
            const driveBadge = log.drive_uploaded
                ? '<span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-500/10 px-1.5 py-0.5 border border-emerald-500/15"><i class="fa-brands fa-google-drive"></i> Terunggah</span>'
                : '<span class="text-[10px] text-slate-400">Tidak</span>';

            const rows = [
                ['Nama Berkas', `<span class="font-mono text-[11px] break-all">${log.filename}</span>`],
                ['Status', statusBadge],
                ['Tanggal', log.created_at || '-'],
                ['Tipe Backup', log.type || '-'],
                ['Ukuran', data.formatted_size || '-'],
                ['Durasi Proses', log.duration ? `${log.duration}s` : '-'],
                ['Enkripsi', encBadge],
                ['Google Drive', driveBadge],
            ];

            document.getElementById('backup-log-detail-grid').innerHTML = rows.map(([label, val]) =>
                `<div class="p-3 bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800">
                    <span class="block text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1">${label}</span>
                    <div class="text-sm text-slate-800 dark:text-zinc-200">${val}</div>
                </div>`
            ).join('');

            // Error block
            const errBlock = document.getElementById('backup-log-error-block');
            if (log.error_message) {
                errBlock.classList.remove('hidden');
                errBlock.innerHTML = `<span class="font-bold block mb-1 text-rose-700"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Pesan Error:</span>${log.error_message}`;
            } else { errBlock.classList.add('hidden'); }

            // Drive block
            const driveBlock = document.getElementById('backup-log-drive-block');
            if (log.drive_file_id) {
                driveBlock.classList.remove('hidden');
                document.getElementById('backup-log-drive-id').textContent = log.drive_file_id;
            } else { driveBlock.classList.add('hidden'); }

            AppModal.open('backupLogDetailModal');
        })
        .catch(() => AppPopup.show({ type: 'error', title: 'Gagal', description: 'Tidak dapat memuat detail log.' }));
    }

    function submitGenerateKey() { openPasswordConfirmModal('rotate'); }
    function confirmKeyDownload() {
        AppPopup.show({ type: 'custom', title: 'Unduh Kunci Enkripsi?', description: 'Kunci ini sangat rahasia. Simpan dengan aman!', confirmText: 'Unduh', cancelText: 'Batal',
            onConfirm: () => openPasswordConfirmModal('download') });
    }
    function confirmKeyRotation() {
        AppPopup.show({ type: 'warning', title: 'Rotasi Kunci?', description: 'Backup lama yang terenkripsi tidak bisa dibuka dengan kunci baru. Lanjutkan?', confirmText: 'Ya, Lanjutkan', cancelText: 'Batal',
            onConfirm: () => openPasswordConfirmModal('rotate') });
    }
    function openPasswordConfirmModal(type) {
        const form = document.getElementById('password-confirm-form');
        document.getElementById('confirm_password_input').value = '';
        form.action = type === 'download'
            ? "{{ route('admin.backup.download-key') }}"
            : "{{ route('admin.backup.generate-key') }}";
        AppModal.open('passwordConfirmModal');
    }
</script>