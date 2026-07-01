<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('importManager', () => ({
            rows: [],
            hasFile: false,
            fileName: '',
            fileSize: '',
            fileExt: '',
            loading: false,
            dragOver: false,

            // Response messages
            successMessage: '',
            errorMessage: '',
            failedRows: [], // list of rows with errors from server

            // Pagination & Filter
            searchQuery: '',
            statusFilter: 'all',
            currentPage: 1,
            perPage: 10,
            expandedRow: null,
            editingRow: null,

            // Counters
            validCount: 0,
            invalidCount: 0,

            formatBytes(bytes) {
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / 1048576).toFixed(1) + ' MB';
            },

            triggerFileSelect() {
                const input = document.getElementById('file_excel');
                if (input) {
                    input.click();
                }
            },

            handleFileSelect(e) {
                const file = e.target.files[0];
                this.processFile(file);
            },

            handleFileDrop(e) {
                const file = e.dataTransfer.files[0];
                if (file) {
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    const input = document.getElementById('file_excel');
                    if (input) {
                        input.files = dt.files;
                    }
                    this.processFile(file);
                }
            },

            clearAll() {
                const input = document.getElementById('file_excel');
                if (input) {
                    input.value = '';
                }
                this.rows = [];
                this.hasFile = false;
                this.fileName = '';
                this.fileSize = '';
                this.fileExt = '';
                this.successMessage = '';
                this.errorMessage = '';
                this.failedRows = [];
                this.loading = false;
                this.validCount = 0;
                this.invalidCount = 0;
                this.searchQuery = '';
                this.statusFilter = 'all';
                this.currentPage = 1;
                this.expandedRow = null;
                this.editingRow = null;
            },

            async processFile(file) {
                if (!file) return;
                const allowed = /\.(xlsx|xls)$/i;
                if (!allowed.test(file.name)) {
                    alert('Format harus .xlsx atau .xls');
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran maksimal 5MB');
                    return;
                }

                this.hasFile = true;
                this.fileName = file.name;
                this.fileSize = this.formatBytes(file.size);
                this.fileExt = file.name.split('.').pop().toUpperCase();
                this.successMessage = '';
                this.errorMessage = '';
                this.failedRows = [];
                this.loading = true;

                const formData = new FormData();
                formData.append('file_excel', file);

                const self = this;
                try {
                    const response = await fetch("{{ route('admin.prestasi.preview') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        if (!result.preview_data || result.preview_data.length === 0) {
                            alert('Tidak ada data ditemukan setelah baris ke-4. Pastikan menggunakan template yang sesuai.');
                            self.clearAll();
                            return;
                        }

                        // Map server response to our Alpine rows
                        self.rows = result.preview_data.map(r => {
                            return {
                                row_number: r.row_number,
                                tanggal: r.tanggal_normalized || r.tanggal || '',
                                tahun: r.tahun || '',
                                peraih: r.peraih || '',
                                kelas: r.kelas || '',
                                judul: r.judul || '',
                                tingkat: r.tingkat_normalized || r.tingkat || '',
                                penyelenggara: r.penyelenggara || '',
                                is_duplicate: r.is_duplicate || false,
                                is_file_duplicate: r.is_file_duplicate || false,
                                serverErrors: []
                            };
                        });

                        self.recalculateCounts();
                    } else {
                        const errorMsg = result.errors && result.errors.length > 0
                            ? result.errors.join('\n')
                            : (result.message || 'Format template tidak sesuai.');
                        alert('Gagal membaca file:\n' + errorMsg);
                        self.clearAll();
                    }
                } catch (err) {
                    alert('Gagal mengirim file ke server: ' + err.message);
                    self.clearAll();
                } finally {
                    self.loading = false;
                }
            },

            parseDateString(v) {
                const s = String(v).trim();
                if (!s) return '';
                if (/^\d{4}-\d{2}-\d{2}$/.test(s)) return s;
                const m1 = s.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
                if (m1) return `${m1[3]}-${m1[2].padStart(2,'0')}-${m1[1].padStart(2,'0')}`;
                const m2 = s.match(/^(\d{4})\/(\d{2})\/(\d{2})$/);
                if (m2) return `${m2[1]}-${m2[2]}-${m2[3]}`;
                if (/^\d+$/.test(s)) {
                    const n = parseInt(s);
                    if (n > 30000) {
                        const d = new Date(Date.UTC(1900, 0, n - 1));
                        return d.toISOString().substring(0, 10);
                    }
                }
                return s;
            },

            normalizeTingkat(v) {
                if (!v) return '';
                const c = v.toLowerCase().replace(/[-_\/ ]/g, '');
                if (['sekolah'].includes(c)) return 'sekolah';
                if (['kabupatenkota', 'kabupaten', 'kota'].includes(c)) return 'kabupaten';
                if (['kwarda'].includes(c)) return 'kwarda';
                if (['provinsi', 'prov'].includes(c)) return 'provinsi';
                if (['nasional', 'nas'].includes(c)) return 'nasional';
                if (['internasional', 'intl', 'int'].includes(c)) return 'internasional';
                if (['umum'].includes(c)) return 'umum';
                return '';
            },

            validateRow(row) {
                const errors = {};

                if (!row.judul || !String(row.judul).trim()) {
                    errors.judul = 'Judul tidak boleh kosong';
                }
                if (!row.peraih || !String(row.peraih).trim()) {
                    errors.peraih = 'Peraih tidak boleh kosong';
                }

                const tahun = parseInt(row.tahun);
                if (!row.tahun || isNaN(tahun) || tahun < 2000 || tahun > 2100) {
                    errors.tahun = 'Tahun harus angka 2000–2100';
                }

                if (row.tanggal) {
                    if (!/^\d{4}-\d{2}-\d{2}$/.test(row.tanggal)) {
                        errors.tanggal = 'Format tanggal YYYY-MM-DD';
                    }
                }

                const validTingkat = ['sekolah', 'kabupaten', 'kwarda', 'provinsi', 'nasional', 'internasional', 'umum'];
                if (!row.tingkat || !validTingkat.includes(row.tingkat)) {
                    errors.tingkat = 'Pilih tingkat valid';
                }

                return errors;
            },

            recalculateCounts() {
                let valids = 0;
                let invalids = 0;
                this.rows.forEach(r => {
                    const errs = this.validateRow(r);
                    if (Object.keys(errs).length === 0) valids++;
                    else invalids++;
                });
                this.validCount = valids;
                this.invalidCount = invalids;
            },

            async submitImport() {
                if (this.loading) return;
                this.loading = true;
                this.successMessage = '';
                this.errorMessage = '';
                this.failedRows = [];

                try {
                    const response = await fetch("{{ route('admin.prestasi.save-preview') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            data: this.rows
                        })
                    });

                    const result = await response.json();

                    if (response.ok) {
                        if (result.success) {
                            const count = result.imported_count;
                            this.clearAll();
                            this.successMessage = `Berhasil mengimpor semua ${count} data prestasi ke database!`;
                        } else {
                            this.successMessage = `Berhasil mengimpor ${result.imported_count} data prestasi.`;
                            if (result.failed_rows && result.failed_rows.length > 0) {
                                this.failedRows = result.failed_rows;
                                this.errorMessage = `${result.failed_rows.length} baris gagal diimpor. Silakan koreksi kesalahan di bawah ini dan klik REUPLOAD.`;
                                
                                 this.rows = result.failed_rows.map(f => {
                                    return {
                                        row_number: f.row_number,
                                        tanggal: f.tanggal || '',
                                        tahun: f.tahun || '',
                                        peraih: f.peraih || '',
                                        kelas: f.kelas || '',
                                        judul: f.judul || '',
                                        tingkat: f.tingkat || '',
                                        penyelenggara: f.penyelenggara || '',
                                        is_duplicate: f.is_duplicate || false,
                                        is_file_duplicate: f.is_file_duplicate || false,
                                        serverErrors: f.errors || []
                                    };
                                });

                                this.recalculateCounts();
                            }
                        }
                    } else {
                        this.errorMessage = result.message || 'Terjadi kesalahan sistem saat memproses data.';
                    }
                } catch (err) {
                    this.errorMessage = 'Gagal mengirim data: ' + err.message;
                } finally {
                    this.loading = false;
                }
            },

            filteredRows() {
                return this.rows.filter(r => {
                    const query = this.searchQuery.toLowerCase().trim();
                    const matchesSearch = !query || 
                        String(r.judul).toLowerCase().includes(query) || 
                        String(r.peraih).toLowerCase().includes(query);

                    if (!matchesSearch) return false;
                    
                    const hasValidationErrors = Object.keys(this.validateRow(r)).length > 0;
                    const hasServerErrors = r.serverErrors && r.serverErrors.length > 0;
                    
                    if (this.statusFilter === 'invalid') {
                        return hasValidationErrors || hasServerErrors;
                    }
                    if (this.statusFilter === 'valid') {
                        return !hasValidationErrors && !hasServerErrors && !r.is_duplicate && !r.is_file_duplicate;
                    }
                    if (this.statusFilter === 'duplicate') {
                        return r.is_duplicate || r.is_file_duplicate;
                    }
                    return true;
                });
            },

            paginatedRows() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.filteredRows().slice(start, start + this.perPage);
            },

            totalPages() {
                return Math.ceil(this.filteredRows().length / this.perPage) || 1;
            },

            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                }
            },

            nextPage() {
                if (this.currentPage < this.totalPages()) {
                    this.currentPage++;
                }
            },

            setPage(page) {
                this.currentPage = page;
            },

            toggleExpand(rowNumber) {
                this.expandedRow = this.expandedRow === rowNumber ? null : rowNumber;
            },

            onRowFieldChange(row) {
                row.is_duplicate = false;
                row.is_file_duplicate = false;
                row.serverErrors = [];
                this.recalculateCounts();
            }
        }));
    });
</script>
