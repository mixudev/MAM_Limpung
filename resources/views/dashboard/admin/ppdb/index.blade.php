@extends('dashboard.layouts.main')

@section('content')
<!-- Custom Breadcrumb Override -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'PPDB Siswa';
        }
    });
</script>

<div class="space-y-6">
    @include('dashboard.admin.ppdb.partials.index.header')
    @include('dashboard.admin.ppdb.partials.index.stats')

    <!-- Interactive Data Table & Actions -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm overflow-hidden">
        @include('dashboard.admin.ppdb.partials.index.table_filters')
        @include('dashboard.admin.ppdb.partials.index.table')
    </div>
</div>

@include('dashboard.admin.ppdb.partials.index.detail_drawer')
@include('dashboard.admin.ppdb.partials.index.rejection_modal')

<!-- JavaScript Interactivity -->
<script>
    // ════════════ 1. AppPopup Confirmation Trigger ════════════
    function confirmVerification(e, studentName) {
        e.preventDefault();
        const form = e.target.closest('form');
        
        if (window.AppPopup) {
            AppPopup.info({
                title: 'Verifikasi Calon Siswa',
                description: `Apakah Anda yakin ingin menyetujui dan memverifikasi pendaftaran dari <strong>${studentName}</strong>?`,
                confirmText: 'Ya, Setujui',
                cancelText: 'Batal',
                onConfirm: () => {
                    form.submit();
                }
            });
        } else {
            if (confirm(`Apakah Anda yakin ingin menyetujui dan memverifikasi pendaftaran dari ${studentName}?`)) {
                form.submit();
            }
        }
    }

    // ════════════ 2. Slide-over Detail Drawer Controller ════════════
    function openDetails(studentId) {
        const drawer = document.getElementById('detailDrawer');
        const backdrop = document.getElementById('drawerBackdrop');
        const content = document.getElementById('drawerContent');
        const loading = document.getElementById('drawerLoading');

        if (!drawer || !backdrop || !content || !loading) return;

        // Reset forms
        const printBtn = document.getElementById('d_print_btn');
        if (printBtn) printBtn.setAttribute('onclick', `printStudent('${studentId}')`);
        
        const verifyForm = document.getElementById('drawerVerifyForm');
        if (verifyForm) verifyForm.action = `/admin/ppdb/${studentId}/verify`;
        
        const actionsEl = document.getElementById('drawerActions');
        if (actionsEl) actionsEl.classList.add('hidden');
        
        const notesSection = document.getElementById('d_notes_section');
        if (notesSection) notesSection.classList.add('hidden');

        // Show drawer shell
        drawer.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            content.classList.remove('translate-x-full');
            content.classList.add('translate-x-0');
        }, 10);

        loading.classList.remove('hidden');

        // Fetch detail candidate data
        fetch(`/admin/ppdb/${studentId}`)
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    const d = res.data;
                    const foto = document.getElementById('d_foto');
                    if (foto) foto.src = d.foto_url;
                    
                    const nama = document.getElementById('d_nama');
                    if (nama) nama.textContent = d.nama_lengkap;
                    
                    const reg = document.getElementById('d_nomor_registrasi');
                    if (reg) reg.textContent = d.nomor_registrasi;
                    
                    const nisn = document.getElementById('d_nisn');
                    if (nisn) nisn.textContent = d.nisn;
                    
                    const gen = document.getElementById('d_gender');
                    if (gen) gen.textContent = d.jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan';
                    
                    const ttl = document.getElementById('d_ttl');
                    if (ttl) ttl.textContent = `${d.tempat_lahir}, ${d.formatted_dob}`;
                    
                    const alamat = document.getElementById('d_alamat');
                    if (alamat) alamat.textContent = d.alamat_lengkap;
                    
                    const sekolah = document.getElementById('d_sekolah');
                    if (sekolah) sekolah.textContent = d.sekolah_asal;
                    
                    const ukuran = document.getElementById('d_ukuran');
                    if (ukuran) ukuran.textContent = d.ukuran_baju;
                    
                    const hp = document.getElementById('d_hp');
                    if (hp) hp.textContent = d.nomor_hp;
                    
                    const email = document.getElementById('d_email');
                    if (email) email.textContent = d.email;
                    
                    const ayah = document.getElementById('d_ayah');
                    if (ayah) ayah.textContent = d.nama_ayah;
                    
                    const ibu = document.getElementById('d_ibu');
                    if (ibu) ibu.textContent = d.nama_ibu;

                    // Populate dynamic additional fields (Informasi Tambahan)
                    const addSection = document.getElementById('d_additional_section');
                    const addGrid = document.getElementById('d_additional_grid');
                    if (addGrid) {
                        addGrid.innerHTML = '';
                        if (d.mapped_additional && d.mapped_additional.length > 0) {
                            d.mapped_additional.forEach(item => {
                                const wrapper = document.createElement('div');
                                if (item.type === 'textarea') {
                                    wrapper.className = 'col-span-2';
                                }
                                wrapper.innerHTML = `
                                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">${item.label}</span>
                                    <span class="font-semibold text-slate-800 dark:text-zinc-200">${item.value}</span>
                                `;
                                addGrid.appendChild(wrapper);
                            });
                            if (addSection) addSection.classList.remove('hidden');
                        } else {
                            if (addSection) addSection.classList.add('hidden');
                        }
                    }

                    // Populate dynamic requirements checklist (Berkas Upload)
                    const reqSection = document.getElementById('d_requirements_section');
                    const reqList = document.getElementById('d_requirements_list');
                    if (reqList) {
                        reqList.innerHTML = '';
                        if (d.mapped_requirements && d.mapped_requirements.length > 0) {
                            d.mapped_requirements.forEach(item => {
                                const div = document.createElement('div');
                                div.className = 'flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-zinc-800 last:border-0';
                                div.innerHTML = `
                                    <span class="text-xs text-slate-650 dark:text-zinc-400">${item.label}</span>
                                    <a href="${item.url}" target="_blank" class="inline-flex items-center gap-1.5 py-1 px-2.5 bg-cyan-600 hover:bg-cyan-700 text-white font-mono font-bold text-[9px] uppercase tracking-wider transition-all">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                        Lihat Berkas
                                    </a>
                                `;
                                reqList.appendChild(div);
                            });
                            if (reqSection) reqSection.classList.remove('hidden');
                        } else {
                            if (reqSection) reqSection.classList.add('hidden');
                        }
                    }

                    // Status label setup
                    const statusSpan = document.getElementById('d_status');
                    if (statusSpan) {
                        statusSpan.textContent = d.status_label;
                        statusSpan.className = `inline-flex px-2 py-0.5 text-[10px] font-bold rounded-none uppercase tracking-wider mb-1.5 `;
                        if (d.status === 'diterima') {
                            statusSpan.className += 'bg-emerald-50 text-emerald-600 border border-emerald-100';
                        } else if (d.status === 'ditolak') {
                            statusSpan.className += 'bg-red-50 text-red-600 border border-red-100';
                            const notes = document.getElementById('d_notes');
                            if (notes) notes.textContent = d.catatan_admin;
                            if (notesSection) notesSection.classList.remove('hidden');
                        } else {
                            statusSpan.className += 'bg-amber-50 text-amber-600 border border-amber-100';
                            
                            // Setup action buttons inside slide-over
                            const drawerVerifyBtn = document.getElementById('drawerVerifyBtn');
                            if (drawerVerifyBtn) drawerVerifyBtn.onclick = (e) => confirmVerification(e, d.nama_lengkap);
                            
                            const drawerRejectBtn = document.getElementById('drawerRejectBtn');
                            if (drawerRejectBtn) {
                                drawerRejectBtn.onclick = () => {
                                    closeDetails();
                                    openRejectionModal(d.id, d.nama_lengkap);
                                };
                            }
                            if (actionsEl) actionsEl.classList.remove('hidden');
                        }
                    }
                }
            })
            .catch(err => {
                console.error("Gagal memuat detail pendaftar:", err);
                closeDetails();
            })
            .finally(() => {
                loading.classList.add('hidden');
            });
    }

    function closeDetails() {
        const backdrop = document.getElementById('drawerBackdrop');
        const content = document.getElementById('drawerContent');
        const drawer = document.getElementById('detailDrawer');

        if (!backdrop || !content || !drawer) return;

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        content.classList.remove('translate-x-0');
        content.classList.add('translate-x-full');

        setTimeout(() => {
            drawer.classList.add('hidden');
        }, 300);
    }

    // ════════════ 3. Rejection Modal Controller ════════════
    function openRejectionModal(studentId, studentName) {
        const modalTitle = document.getElementById('rejectModalTitle');
        if (modalTitle) modalTitle.innerHTML = `Tolak Pendaftaran <strong>${studentName}</strong>`;
        
        const form = document.getElementById('rejectionForm');
        if (form) form.action = `/admin/ppdb/${studentId}/reject`;
        
        const notes = document.getElementById('catatan_admin');
        if (notes) notes.value = '';
        
        const modal = document.getElementById('rejectionModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeRejectionModal() {
        const modal = document.getElementById('rejectionModal');
        if (modal) modal.classList.add('hidden');
    }

    // ════════════ 4. Direct Background Print Injection ════════════
    function printStudent(studentId) {
        let toast = document.getElementById('print_toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'print_toast';
            toast.className = 'fixed bottom-6 right-6 bg-slate-900 text-white font-mono text-[10px] uppercase tracking-widest px-4 py-2 border border-slate-800 z-[9999] transition-all duration-300 transform translate-y-10 opacity-0';
            document.body.appendChild(toast);
        }
        
        toast.innerText = 'Menyiapkan Lembar Cetak...';
        toast.classList.remove('translate-y-10', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        fetch(`/admin/ppdb/${studentId}/print`)
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                const styleEl = doc.querySelector('style');
                const printStyle = styleEl ? styleEl.innerHTML : '';
                
                const contentEl = doc.querySelector('.print-wrapper');
                const printContent = contentEl ? contentEl.innerHTML : '';
                
                let container = document.getElementById('print-injection-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'print-injection-container';
                    document.body.appendChild(container);
                }
                
                container.innerHTML = `<style>${printStyle}</style><div class="print-wrapper">${printContent}</div>`;
                
                setTimeout(() => {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-10', 'opacity-0');
                    window.print();
                }, 500);
            })
            .catch(err => {
                console.error("Gagal melakukan pencetakan latar belakang:", err);
                toast.innerText = 'Gagal memuat dokumen!';
                setTimeout(() => {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-10', 'opacity-0');
                }, 2000);
            });
    }

    window.addEventListener('afterprint', () => {
        const container = document.getElementById('print-injection-container');
        if (container) {
            container.innerHTML = '';
        }
    });
</script>

<style>
    /* Direct print injection stylesheet configurations */
    #print-injection-container {
        display: none;
    }
    @media print {
        /* Force browser to hide entire admin dashboard layout */
        body > *:not(#print-injection-container) {
            display: none !important;
        }
        #print-injection-container {
            display: block !important;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: auto;
            background: #fff;
        }
    }
</style>
@endsection
