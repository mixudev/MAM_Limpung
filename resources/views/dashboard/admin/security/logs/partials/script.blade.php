<script>
    // Fetch and show activity logs
    function showActivityDetails(logId) {
        const routePrefix = '{{ $routePrefix }}';
        let url = `/${routePrefix}/logs/activity/${logId}`;
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                document.getElementById('act_event').innerText = res.data.event;
                document.getElementById('act_model').innerText = res.data.model;
                document.getElementById('act_causer').innerText = res.data.causer;
                document.getElementById('act_time').innerText = res.data.timestamp;
                document.getElementById('act_ip').innerText = `IP: ${res.data.ip_address}`;
                document.getElementById('act_ua').innerText = `User Agent: ${res.data.user_agent}`;

                let body = document.getElementById('diff_body');
                body.innerHTML = '';

                if (res.data.diff && res.data.diff.length > 0) {
                    res.data.diff.forEach(item => {
                        let row = document.createElement('tr');
                        row.className = 'hover:bg-slate-50 dark:hover:bg-zinc-900/30 transition-colors border-b border-slate-100 dark:border-zinc-800';
                        
                        let oldVal = item.old !== null ? item.old : '<span class="text-slate-400 italic">kosong / NULL</span>';
                        let newVal = item.new !== null ? item.new : '<span class="text-slate-400 italic">kosong / NULL</span>';
                        
                        row.innerHTML = `
                            <td data-label="Kolom / Atribut" class="py-2.5 px-4 font-semibold text-slate-700 dark:text-zinc-400 text-xs">${item.attribute}</td>
                            <td data-label="Sebelum" class="py-2.5 px-4 bg-rose-500/5 text-rose-600 dark:text-rose-400 whitespace-pre-wrap break-all">${oldVal}</td>
                            <td data-label="Sesudah" class="py-2.5 px-4 bg-emerald-500/5 text-emerald-600 dark:text-emerald-400 whitespace-pre-wrap break-all">${newVal}</td>
                        `;
                        body.appendChild(row);
                    });
                } else {
                    body.innerHTML = `
                        <tr>
                            <td colspan="3" class="py-4 text-center text-slate-400 dark:text-zinc-500 italic">Tidak ada detail field yang diubah.</td>
                        </tr>
                    `;
                }

                document.getElementById('activityDetailsModal').classList.remove('hidden');
            }
        })
        .catch(err => {
            console.error('Gagal mengambil detail log:', err);
            alert('Gagal mengambil data log.');
        });
    }

    function closeActivityModal() {
        document.getElementById('activityDetailsModal').classList.add('hidden');
    }

    // Fetch and show failed jobs details
    function showFailedJobDetails(jobId) {
        const routePrefix = '{{ $routePrefix }}';
        let url = `/${routePrefix}/logs/failed-job/${jobId}`;
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                document.getElementById('job_uuid').innerText = res.data.uuid;
                document.getElementById('job_connection').innerText = res.data.connection;
                document.getElementById('job_queue').innerText = res.data.queue;
                document.getElementById('job_time').innerText = res.data.failed_at;
                document.getElementById('job_exception').innerText = res.data.exception;
                
                let retryForm = document.getElementById('job_retry_form');
                retryForm.action = `/${routePrefix}/logs/failed-job/${jobId}/retry`;

                document.getElementById('failedJobModal').classList.remove('hidden');
            }
        })
        .catch(err => {
            console.error('Gagal mengambil detail failed job:', err);
            alert('Gagal mengambil data pekerjaan gagal.');
        });
    }

    function closeFailedJobModal() {
        document.getElementById('failedJobModal').classList.add('hidden');
    }

    // Fetch and show backup log details (NEW)
    function showBackupDetails(logId) {
        const routePrefix = '{{ $routePrefix }}';
        // Note: The backup details endpoint is nested under /backup/log/{id}
        let url = `/admin/backup/log/${logId}`;
        if (routePrefix === 'super-admin') {
            url = `/super-admin/backup/log/${logId}`; // Just in case super-admin accesses it
        }
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                const log = res.log;
                
                // Set Status
                const statusBadge = document.getElementById('bak_status');
                statusBadge.innerText = log.status.toUpperCase();
                if (log.status === 'success') {
                    statusBadge.className = 'px-2.5 py-0.5 text-[10px] font-mono font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/20 dark:text-emerald-400';
                } else {
                    statusBadge.className = 'px-2.5 py-0.5 text-[10px] font-mono font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/20 dark:text-rose-400';
                }

                document.getElementById('bak_type').innerText = log.type;
                document.getElementById('bak_size').innerText = res.formatted_size;
                document.getElementById('bak_time').innerText = res.formatted_date;
                document.getElementById('bak_filename').innerText = log.filename;

                // Drive Upload
                const driveUploaded = document.getElementById('bak_drive_uploaded');
                if (log.drive_uploaded) {
                    driveUploaded.innerText = 'BERHASIL (YES)';
                    driveUploaded.className = 'text-emerald-600 dark:text-emerald-400 font-bold';
                    document.getElementById('bak_drive_id').innerText = log.drive_file_id || '-';
                    document.getElementById('bak_drive_error_area').classList.add('hidden');
                } else {
                    driveUploaded.innerText = 'TIDAK TERUNGGAH (NO)';
                    driveUploaded.className = 'text-slate-500 font-bold';
                    document.getElementById('bak_drive_id').innerText = '-';
                    
                    if (log.drive_error) {
                        document.getElementById('bak_drive_error').innerText = log.drive_error;
                        document.getElementById('bak_drive_error_area').classList.remove('hidden');
                    } else {
                        document.getElementById('bak_drive_error_area').classList.add('hidden');
                    }
                }

                // Error Message Area
                const errorCard = document.getElementById('bak_error_card');
                if (log.error_message) {
                    document.getElementById('bak_error_msg').innerText = log.error_message;
                    errorCard.classList.remove('hidden');
                } else {
                    errorCard.classList.add('hidden');
                }

                // Metadata JSON Details
                document.getElementById('bak_details').innerText = log.details ? JSON.stringify(log.details, null, 4) : '{}';

                document.getElementById('backupDetailsModal').classList.remove('hidden');
            }
        })
        .catch(err => {
            console.error('Gagal mengambil detail backup log:', err);
            alert('Gagal mengambil data log backup.');
        });
    }

    function closeBackupModal() {
        document.getElementById('backupDetailsModal').classList.add('hidden');
    }

    // ========== Queue Job Auto-Refresh Polling ==========
    (function() {
        const activeTab = '{{ $activeTab }}';
        if (activeTab !== 'job_queue') return;

        const routePrefix = '{{ $routePrefix }}';
        const pollUrl = `/${routePrefix}/logs/queue-data`;

        function formatUnixTs(ts) {
            if (!ts) return '-';
            const d = new Date(ts * 1000);
            return d.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' })
                + ' ' + d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }

        function updateQueueData() {
            fetch(pollUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(res => {
                if (!res.success) return;
                const d = res.data;

                // Update stats cards
                document.getElementById('stat-pending').textContent = d.stats.total_pending;
                document.getElementById('stat-processing').textContent = d.stats.total_processing;
                document.getElementById('stat-batches').textContent = d.stats.total_batches;
                document.getElementById('stat-failed').textContent = d.stats.total_failed;

                // Rebuild pending jobs table
                const queueBody = document.querySelector('#queue-tab-content table:first-child tbody');
                if (queueBody) {
                    if (d.queueJobs.length === 0) {
                        queueBody.innerHTML = '<tr><td colspan="4" class="py-8 text-center text-slate-400 dark:text-zinc-600 font-mono">Tidak ada antrean pekerjaan yang tertunda.</td></tr>';
                    } else {
                        queueBody.innerHTML = d.queueJobs.map(j => `
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-900/30 transition-colors">
                                <td class="py-3 px-4">
                                    <span class="font-mono text-[10px] bg-slate-100 dark:bg-zinc-800 px-1.5 py-0.5 font-semibold text-slate-700 dark:text-zinc-300">${j.queue}</span>
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-bold text-slate-800 dark:text-zinc-200">${Number(j.total).toLocaleString()}</td>
                                <td class="py-3 px-4 text-right font-mono text-slate-500 dark:text-zinc-400">${Number(j.total_attempts).toLocaleString()}</td>
                                <td class="py-3 px-4 font-mono text-slate-500 dark:text-zinc-400">${formatUnixTs(j.oldest)}</td>
                            </tr>
                        `).join('');
                    }
                }

                // Rebuild job batches table
                const batchBody = document.querySelector('#queue-tab-content table:last-child tbody');
                if (batchBody) {
                    if (d.jobBatches.length === 0) {
                        batchBody.innerHTML = '<tr><td colspan="4" class="py-8 text-center text-slate-400 dark:text-zinc-600 font-mono">Belum ada batch pekerjaan yang tercatat.</td></tr>';
                    } else {
                        batchBody.innerHTML = d.jobBatches.map(b => {
                            const total = Number(b.total_jobs);
                            const pending = Number(b.pending_jobs);
                            const done = total - pending;
                            const progress = total > 0 ? Math.round(done / total * 100) : 0;
                            let status, statusClass;
                            if (b.cancelled_at) {
                                status = 'cancelled';
                                statusClass = 'bg-rose-100 text-rose-800 dark:bg-rose-950/20 dark:text-rose-400';
                            } else if (b.finished_at) {
                                status = 'finished';
                                statusClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/20 dark:text-emerald-400';
                            } else {
                                status = 'running';
                                statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-950/20 dark:text-amber-400';
                            }
                            return `
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-900/30 transition-colors">
                                    <td class="py-3 px-4">
                                        <span class="font-semibold text-slate-800 dark:text-zinc-200 block truncate max-w-[120px]" title="${b.name || ''}">${b.name || '(tanpa nama)'}</span>
                                        <span class="text-[9px] font-mono text-slate-400 dark:text-zinc-500 block truncate max-w-[120px]" title="${b.id}">ID: ${(b.id || '').substring(0, 12)}...</span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <span class="font-mono font-bold text-slate-700 dark:text-zinc-300">${done}/${total}</span>
                                        <div class="w-full bg-slate-200 dark:bg-zinc-700 h-1.5 mt-1">
                                            <div class="h-1.5 bg-indigo-500 dark:bg-indigo-400 transition-all duration-500" style="width: ${progress}%"></div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 text-[9px] font-mono font-bold uppercase ${statusClass}">${status}</span>
                                        ${Number(b.failed_jobs) > 0 ? `<div class="text-[9px] text-rose-500 mt-0.5">${b.failed_jobs} gagal</div>` : ''}
                                    </td>
                                    <td class="py-3 px-4 font-mono text-slate-500 dark:text-zinc-400">${formatUnixTs(b.created_at)}</td>
                                </tr>
                            `;
                        }).join('');
                    }
                }
            })
            .catch(() => {});
        }

        // Poll every 5 seconds
        setInterval(updateQueueData, 5000);
    })();

    // ========== Clean Logs Modal ==========
    var cleanActiveTab = '{{ $activeTab }}';
    var cleanPeriod = 'today';

    var tabLabels = {
        'activity': 'Log Perubahan Data',
        'security': 'Log Keamanan',
        'failed_jobs': 'Log Failed Jobs',
        'backup': 'Log Backup',
        'job_queue': 'Log Antrian Job'
    };

    function openCleanModal() {
        cleanPeriod = 'today';
        document.getElementById('clean-tab-label').textContent = tabLabels[cleanActiveTab] || cleanActiveTab;
        selectCleanPeriod('today');
        document.getElementById('clean-start-date').value = '';
        document.getElementById('clean-end-date').value = '';
        document.getElementById('clean-summary').textContent = '';
        document.getElementById('cleanLogsModal').classList.remove('hidden');
    }

    function closeCleanModal() {
        document.getElementById('cleanLogsModal').classList.add('hidden');
    }

    function selectCleanPeriod(period) {
        cleanPeriod = period;
        document.querySelectorAll('#cleanLogsModal .grid-cols-3 button').forEach(function(btn) {
            btn.className = 'py-2.5 px-3 text-[10px] font-mono font-bold uppercase tracking-wider bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 border border-slate-200 dark:border-zinc-700 hover:bg-slate-200 dark:hover:bg-zinc-700 transition-colors';
        });
        var active = document.getElementById('period-' + period);
        active.className = 'py-2.5 px-3 text-[10px] font-mono font-bold uppercase tracking-wider bg-indigo-600 text-white border border-indigo-600 transition-colors';

        var customRange = document.getElementById('custom-date-range');
        if (period === 'custom') {
            customRange.classList.remove('hidden');
        } else {
            customRange.classList.add('hidden');
        }
    }

    function executeClean() {
        var btn = document.getElementById('clean-confirm-btn');
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Membersihkan...';

        var data = {
            tab: cleanActiveTab,
            period: cleanPeriod
        };

        if (cleanPeriod === 'custom') {
            data.start_date = document.getElementById('clean-start-date').value;
            data.end_date = document.getElementById('clean-end-date').value;
            if (!data.start_date || !data.end_date) {
                AppPopup.error({ title: 'Lengkapi Tanggal', description: 'Pilih tanggal mulai dan selesai untuk periode kustom.' });
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-trash-can"></i> Bersihkan';
                return;
            }
        }

        var routePrefix = '{{ $routePrefix }}';

        fetch('/' + routePrefix + '/logs/clean', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(data)
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                AppPopup.success({ title: 'Berhasil!', description: res.message });
                closeCleanModal();
                location.reload();
            } else {
                AppPopup.error({ title: 'Gagal', description: res.message });
            }
        })
        .catch(function() {
            AppPopup.error({ title: 'Error', description: 'Terjadi kesalahan saat membersihkan log.' });
        })
        .finally(function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-trash-can"></i> Bersihkan';
        });
    }
</script>