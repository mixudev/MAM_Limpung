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

    function updateSchedulePreview() {
        const schedule = document.getElementById('schedule-selector').value;
        const cronWrapper = document.getElementById('cron-expression-wrapper');
        const previewText = document.getElementById('schedule-preview-text');

        cronWrapper.classList.toggle('hidden', schedule !== 'custom');

        const presets = {
            daily:   'Setiap hari pukul 00:00 (tengah malam)',
            weekly:  'Setiap hari Minggu pukul 00:00',
            monthly: 'Setiap tanggal 1 pukul 00:00',
        };

        if (schedule !== 'custom') {
            previewText.textContent = presets[schedule] || '-';
        } else {
            updateCronPreview();
        }
    }

    function updateCronPreview() {
        const expr = (document.getElementById('cron-input')?.value || '').trim();
        const previewText = document.getElementById('schedule-preview-text');
        previewText.textContent = expr ? parseCronHuman(expr) : '—';
    }

    function setCronPreset(expr) {
        const input = document.getElementById('cron-input');
        if (input) { input.value = expr; updateCronPreview(); }
    }

    function parseCronHuman(expr) {
        const known = {
            '0 0 * * *':   'Setiap hari pukul 00:00',
            '0 2 * * *':   'Setiap hari pukul 02:00',
            '0 1 * * 0':   'Setiap Minggu pukul 01:00',
            '0 0 1 * *':   'Setiap tanggal 1 pukul 00:00',
            '0 */6 * * *': 'Setiap 6 jam sekali',
            '0 0 * * 1-5': 'Hari kerja (Senin–Jumat) pukul 00:00',
            '* * * * *':   'Setiap menit (tidak disarankan untuk backup)',
        };
        return known[expr] || 'Ekspresi kustom: ' + expr;
    }

    // Init preview on page load
    document.addEventListener('DOMContentLoaded', () => updateSchedulePreview());

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

    let progressPollingId = null;

    function startProgressPolling() {
        const progressUrl = "{{ route('admin.backup.progress') }}";
        progressPollingId = setInterval(function() {
            fetch(progressUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var stepLabel = document.getElementById('progress-step-label');
                var pctLabel = document.getElementById('progress-pct-label');
                var progressBar = document.getElementById('progress-bar-fill');
                var progressWrapper = document.getElementById('progress-bar-wrapper');

                if (stepLabel && data.step && data.step !== 'idle') {
                    progressWrapper.classList.remove('hidden');
                    var labels = {
                        'memulai': 'Memulai...',
                        'database': 'Mendump Database',
                        'storage': 'Mengompresi Storage',
                        'compressing': 'Membuat Arsip',
                        'encrypting': 'Mengenkripsi',
                        'finalizing': 'Finalisasi',
                        'drive': 'Upload Google Drive',
                        'selesai': 'Selesai!',
                        'error': 'Error'
                    };
                    stepLabel.textContent = labels[data.step] || data.step;
                    if (data.detail) {
                        stepLabel.textContent += ' — ' + data.detail;
                    }
                    if (pctLabel) {
                        pctLabel.textContent = data.percent + '%';
                    }
                    if (data.percent > 0) {
                        progressBar.style.width = data.percent + '%';
                    }
                }
            })
            .catch(function() {});
        }, 1500);
    }

    function stopProgressPolling() {
        if (progressPollingId) {
            clearInterval(progressPollingId);
            progressPollingId = null;
        }
    }

    function triggerManualBackup() {
        const btn = document.getElementById('manual-backup-btn');
        const indicator = document.getElementById('terminal-indicator');
        const statusText = document.getElementById('terminal-status-text');
        const spinner = document.getElementById('terminal-spinner-wrapper');
        const content = document.getElementById('terminal-log-content');
        const progressWrapper = document.getElementById('progress-bar-wrapper');

        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        spinner.classList.remove('hidden');
        indicator.className = 'w-2 h-2 rounded-full bg-indigo-500 animate-ping';
        statusText.innerText = 'Status: Memproses...';
        content.innerHTML = '';
        progressWrapper.classList.add('hidden');
        document.getElementById('progress-bar-fill').style.width = '0%';

        logToTerminal('Memulai inisialisasi backup manual...', 'system');
        logToTerminal('Proses dapat berlangsung beberapa menit tergantung ukuran database & storage.', 'warn');

        startProgressPolling();

        var progressUrl = "{{ route('admin.backup.progress') }}";

        fetch("{{ route('admin.backup.run') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(function(response) {
            return response.text().then(function(text) {
                var data = {};
                try {
                    data = text ? JSON.parse(text) : {};
                } catch (e) {
                    stopProgressPolling();
                    var snippet = text ? text.substring(0, 300) : '(respons kosong)';
                    logToTerminal('Respons server tidak valid: ' + snippet, 'error');
                    fetch(progressUrl).then(function(r) { return r.json(); }).then(function(p) {
                        if (p.step === 'selesai') {
                            data = { success: true, log: null, message: 'Backup selesai!' };
                            return handleBackupSuccess(data, btn, indicator, statusText, spinner);
                        }
                    });
                    throw new Error('Server tidak merespons dengan benar. Detail: ' + snippet);
                }
                if (!response.ok) throw data;
                return data;
            });
        })
        .then(function(data) {
            stopProgressPolling();
            handleBackupSuccess(data, btn, indicator, statusText, spinner);
        })
        .catch(function(err) {
            stopProgressPolling();
            const msg = typeof err === 'string' ? err : (err.message || JSON.stringify(err));
            logToTerminal('GAGAL: ' + msg, 'error');
            logToTerminal('Periksa tabel Riwayat Backup untuk melihat hasil akhir.', 'warn');
            indicator.className = 'w-2 h-2 rounded-full bg-rose-500';
            statusText.innerText = 'Status: Error!';
            if (err && err.log) appendBackupLogToTable(err.log);
            if (!btn.disabled) { btn.disabled = false; btn.classList.remove('opacity-50', 'cursor-not-allowed'); spinner.classList.add('hidden'); }
        });
    }

    function handleBackupSuccess(data, btn, indicator, statusText, spinner) {
        logToTerminal('Proses backup selesai!', 'success');
        if (data.log) {
            var log = data.log;
            logToTerminal('Tipe: ' + (log.type || '-'), 'info');
            if (log.encrypted) logToTerminal('Enkripsi AES-256: AKTIF', 'success');
            if (log.drive_uploaded) logToTerminal('Google Drive: SUKSES (ID: ' + log.drive_file_id + ')', 'success');
            else if (log.drive_error) logToTerminal('Google Drive: GAGAL (' + log.drive_error + ')', 'error');
            logToTerminal('Ukuran: ' + log.formatted_size + ' | Durasi: ' + log.duration + 's', 'system');
            logToTerminal('File: ' + log.filename, 'success');
            appendBackupLogToTable(log);
        } else {
            location.reload();
        }
        indicator.className = 'w-2 h-2 rounded-full bg-emerald-500';
        statusText.innerText = 'Status: Sukses!';
        btn.disabled = false; btn.classList.remove('opacity-50', 'cursor-not-allowed'); spinner.classList.add('hidden');
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
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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
                fetch(url, { method: 'DELETE', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(r => r.json())
                .then(data => {
                    if (data.success) { AppPopup.success({ title: 'Berhasil!', description: data.message, duration: 2000 }); setTimeout(() => window.location.reload(), 2000); }
                    else AppPopup.error({ title: 'Gagal', description: data.message });
                });
            }
        });
    }

    function showBackupLogDetails(id) {
        const url = "{{ route('admin.backup.log-details', ['id' => ':id']) }}".replace(':id', id);
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
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
            const ext = log.filename?.endsWith('.enc') ? '.enc (terenkripsi)' : '.zip';

            const rows = [
                ['Nama File',       `<span class="font-mono text-[11px] break-all">${log.filename}</span>`],
                ['Status',          statusBadge],
                ['Waktu Backup',    data.formatted_date],
                ['Tipe Backup',     log.type || '-'],
                ['Ukuran File',     data.formatted_size],
                ['Durasi Proses',   log.duration ? `${log.duration} detik` : '-'],
                ['Enkripsi',        encBadge],
                ['Google Drive',    driveBadge],
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
                errBlock.innerHTML = `<span class="font-bold block mb-1 text-rose-700 dark:text-rose-400"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Pesan Error:</span><span class="break-all">${log.error_message}</span>`;
            } else {
                errBlock.classList.add('hidden');
            }

            // Drive error block
            const driveBlock = document.getElementById('backup-log-drive-block');
            if (log.drive_file_id) {
                driveBlock.classList.remove('hidden');
                document.getElementById('backup-log-drive-id').textContent = log.drive_file_id;
            } else if (log.drive_error) {
                driveBlock.classList.remove('hidden');
                driveBlock.innerHTML = `<span class="block text-[9px] font-mono font-bold uppercase tracking-wider text-rose-500 mb-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Google Drive Error</span><span class="text-xs font-mono text-rose-600 dark:text-rose-400 break-all">${log.drive_error}</span>`;
            } else {
                driveBlock.classList.add('hidden');
            }

            AppModal.open('backupLogDetailModal');
        })
        .catch(() => AppPopup.show({ type: 'error', title: 'Gagal', description: 'Tidak dapat memuat detail log.' }));
    }

    function toggleSyncSetting(checked) {
        const toggle = document.getElementById('sync-enabled-toggle');
        toggle.disabled = true;

        fetch("{{ route('admin.backup.sync-settings') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ sync_enabled: checked })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                const badge = document.getElementById('sync-status-badge');
                if (badge) {
                    if (checked) {
                        badge.textContent = 'AKTIF';
                        badge.className = 'inline-block text-[10px] font-bold px-2 py-0.5 bg-emerald-500 text-white';
                        document.getElementById('storage-sync-btn').disabled = false;
                        document.getElementById('storage-sync-btn').classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        badge.textContent = 'NONAKTIF';
                        badge.className = 'inline-block text-[10px] font-bold px-2 py-0.5 bg-slate-400 text-white';
                        document.getElementById('storage-sync-btn').disabled = true;
                        document.getElementById('storage-sync-btn').classList.add('opacity-50', 'cursor-not-allowed');
                    }
                }
            } else {
                toggle.checked = !checked;
            }
        })
        .catch(function() {
            toggle.checked = !checked;
        })
        .finally(function() {
            toggle.disabled = false;
        });
    }

    // ===== Storage Sync Functions (paginated) =====

    var currentSyncPage = 1;
    var totalSyncPages = 1;

    function loadSyncLogs(page) {
        currentSyncPage = page || 1;
        var tbody = document.getElementById('sync-log-tbody');
        var icon = document.getElementById('sync-log-refresh-icon');
        var pagination = document.getElementById('sync-log-pagination');
        var summary = document.getElementById('sync-log-summary');
        icon.classList.add('fa-spin');

        fetch("{{ route('admin.backup.sync-logs') }}?page=" + currentSyncPage + "&per_page=15", {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            icon.classList.remove('fa-spin');
            if (!data.success) return;

            totalSyncPages = data.last_page || 1;
            var logs = data.logs || [];

            // Summary
            summary.textContent = data.total > 0 ? 'Halaman ' + data.page + ' dari ' + data.last_page + ' (' + data.total + ' file)' : '';

            // Table rows
            if (!logs.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="py-8 text-center text-slate-400 dark:text-zinc-500 font-mono text-[11px]"><i class="fa-solid fa-inbox text-lg block mb-2 opacity-50"></i> Belum ada riwayat sinkronisasi storage.</td></tr>';
                pagination.classList.add('hidden');
                return;
            }

            var startNum = (data.page - 1) * data.per_page + 1;
            tbody.innerHTML = logs.map(function(l, i) {
                var errorAttr = l.error_message ? ' title="' + l.error_message.replace(/"/g, '&quot;') + '"' : '';
                var statusBadge;
                if (l.sync_status === 'synced') {
                    statusBadge = '<span class="inline-flex items-center gap-1 text-[10px] font-bold px-1.5 py-0.5 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">SUKSES</span>';
                } else if (l.sync_status === 'failed') {
                    statusBadge = '<span' + errorAttr + ' class="inline-flex items-center gap-1 text-[10px] font-bold px-1.5 py-0.5 bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 cursor-help">GAGAL <i class="fa-solid fa-circle-exclamation text-[9px]"></i></span>';
                } else if (l.sync_status === 'removed') {
                    statusBadge = '<span class="inline-flex items-center gap-1 text-[10px] font-bold px-1.5 py-0.5 bg-slate-500/10 text-slate-500 dark:text-zinc-400 border border-slate-500/20">DIHAPUS</span>';
                } else {
                    statusBadge = '<span class="inline-flex items-center gap-1 text-[10px] font-bold px-1.5 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">' + l.sync_status.toUpperCase() + '</span>';
                }
                var time = l.synced_at || l.updated_at || '-';
                return '<tr class="hover:bg-slate-50/30 dark:hover:bg-zinc-900/20 transition-colors">'
                    + '<td class="py-2 px-3 text-center font-mono text-[10px] text-slate-400 dark:text-zinc-500">' + (startNum + i) + '</td>'
                    + '<td class="py-2 px-3 font-medium text-slate-700 dark:text-zinc-300 font-mono text-[11px] break-all max-w-[320px]">' + l.file_path + (l.error_message ? '<div class="text-[9px] text-rose-500 mt-0.5 leading-tight truncate max-w-[320px]" title="' + l.error_message.replace(/"/g, '&quot;') + '"><i class="fa-solid fa-circle-exclamation mr-0.5"></i>' + l.error_message.substring(0, 80) + '</div>' : '') + '</td>'
                    + '<td class="py-2 px-3 text-right font-mono text-[10px] text-slate-500 dark:text-zinc-400 whitespace-nowrap">' + (l.formatted_size || '-') + '</td>'
                    + '<td class="py-2 px-3 text-center whitespace-nowrap">' + statusBadge + '</td>'
                    + '<td class="py-2 px-3 font-mono text-[10px] text-slate-400 dark:text-zinc-500 whitespace-nowrap">' + time + '</td>'
                    + '</tr>';
            }).join('');

            // Pagination
            pagination.classList.remove('hidden');
            renderSyncPagination(data);
        })
        .catch(function() {
            icon.classList.remove('fa-spin');
        });
    }

    function renderSyncPagination(data) {
        document.getElementById('sync-log-prev').disabled = !data.has_prev;
        document.getElementById('sync-log-next').disabled = !data.has_next;

        var container = document.getElementById('sync-log-page-numbers');
        container.innerHTML = '';

        var page = data.page;
        var last = data.last_page;
        var start = Math.max(1, page - 2);
        var end = Math.min(last, page + 2);

        for (var i = start; i <= end; i++) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.onclick = function(p) { return function() { loadSyncLogs(p); }; }(i);
            btn.textContent = i;
            if (i === page) {
                btn.className = 'w-7 h-7 text-[10px] font-mono font-bold bg-indigo-600 text-white border border-indigo-600';
            } else {
                btn.className = 'w-7 h-7 text-[10px] font-mono font-bold bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-200 dark:hover:bg-zinc-700 transition-colors border border-slate-200 dark:border-zinc-700';
            }
            container.appendChild(btn);
        }
    }

    let syncPollingId = null;

    function startSyncPolling() {
        const progressUrl = "{{ route('admin.backup.sync-progress') }}";
        syncPollingId = setInterval(function() {
            fetch(progressUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.success) return;
                if (data.running) {
                    const badge = document.getElementById('sync-status-badge');
                    if (badge) {
                        badge.textContent = 'SYNC... (' + data.processed + '/' + data.total + ')';
                        badge.className = 'inline-block text-[10px] font-bold px-2 py-0.5 bg-amber-500 text-white';
                    }
                } else {
                    const badge = document.getElementById('sync-status-badge');
                    if (badge) {
                        var failedCount = data.failed || 0;
                        if (failedCount > 0) {
                            badge.textContent = data.enabled ? 'AKTIF (' + failedCount + ' GAGAL)' : 'NONAKTIF';
                            badge.className = 'inline-block text-[10px] font-bold px-2 py-0.5 ' + (data.enabled ? 'bg-rose-500 text-white' : 'bg-slate-400 text-white');
                        } else {
                            badge.textContent = data.enabled ? 'AKTIF' : 'NONAKTIF';
                            badge.className = 'inline-block text-[10px] font-bold px-2 py-0.5 ' + (data.enabled ? 'bg-emerald-500 text-white' : 'bg-slate-400 text-white');
                        }
                    }
                }
            })
            .catch(function() {});
        }, 2000);
    }

    function stopSyncPolling() {
        if (syncPollingId) {
            clearInterval(syncPollingId);
            syncPollingId = null;
        }
    }

    function triggerStorageSync() {
        const btn = document.getElementById('storage-sync-btn');
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        btn.innerHTML = '<svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> SYNCING...';

        const badge = document.getElementById('sync-status-badge');
        badge.textContent = 'MENJALANKAN...';
        badge.className = 'inline-block text-[10px] font-bold px-2 py-0.5 bg-indigo-500 text-white';

        logToTerminal('Memulai sinkronisasi storage ke Google Drive...', 'system');
        logToTerminal('Pastikan queue worker berjalan: php artisan queue:work', 'warn');
        startSyncPolling();

        fetch("{{ route('admin.backup.sync-run') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                logToTerminal('Sinkronisasi storage dimulai. File akan diproses via antrian.', 'success');
                logToTerminal(data.dispatched + ' file akan di-sync.', 'info');
                if (data.drive_folder) {
                    logToTerminal('Drive folder: My Drive/' + data.drive_folder.path + ' (ID: ' + data.drive_folder.id + ')', 'system');
                }
            } else {
                logToTerminal('GAGAL: ' + (data.message || JSON.stringify(data)), 'error');
            }
        })
        .catch(function(err) {
            logToTerminal('GAGAL: ' + (err.message || JSON.stringify(err)), 'error');
        })
        .finally(function() {
            setTimeout(function() {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                btn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> SYNC STORAGE SEKARANG';
                stopSyncPolling();
                loadSyncLogs(1);
            }, 5000);
        });
    }

    function confirmClearSyncLogs() {
        AppPopup.confirm({
            title: 'Bersihkan Log Sinkronisasi?',
            description: 'Semua log sinkronisasi storage akan dihapus permanen. Data di Google Drive tidak terpengaruh.',
            confirmText: 'Ya, Bersihkan',
            cancelText: 'Batal',
            onConfirm: function() {
                var btn = document.querySelector('[onclick="confirmClearSyncLogs()"]');
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-[9px]"></i>';

                fetch("{{ route('admin.backup.sync-logs.clear') }}", {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        loadSyncLogs(1);
                        AppPopup.success({ title: 'Berhasil!', description: data.message });
                    } else {
                        AppPopup.error({ title: 'Gagal', description: data.message });
                    }
                })
                .catch(function() {
                    AppPopup.error({ title: 'Error', description: 'Gagal membersihkan log.' });
                })
                .finally(function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-trash-can text-[9px]"></i> Bersihkan';
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadSyncLogs(1);
    });

</script>