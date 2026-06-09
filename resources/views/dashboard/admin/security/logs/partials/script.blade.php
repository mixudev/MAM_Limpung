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
</script>