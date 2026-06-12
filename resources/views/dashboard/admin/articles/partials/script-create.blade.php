<script>
    document.addEventListener("DOMContentLoaded", function() {
        // ─── QUILL EDITOR ──────────────────────────────────────────────────────
        const quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'align': [] }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        quill.root.style.fontFamily = 'inherit';

        // ─── LOCALSTORAGE AUTOSAVE ──────────────────────────────────────────────
        const DRAFT_KEY = 'article_create_draft';
        const autosaveText = document.getElementById('autosave-text');
        const btnClearDraft = document.getElementById('btn-clear-draft');
        let autosaveTimer = null;

        // Fields to persist
        const persistFields = ['judul', 'ringkasan', 'status', 'published_at',
            'seo_focus_keyword', 'seo_meta_title', 'seo_meta_description',
            'seo_meta_keywords', 'seo_canonical_url'];

        function saveDraft() {
            const draft = {};
            persistFields.forEach(name => {
                const el = document.querySelector(`[name="${name}"]`);
                if (el) { draft[name] = el.value; }
            });
            draft['konten'] = quill.root.innerHTML;
            draft['_saved_at'] = new Date().toISOString();
            localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));

            // Update indicator
            const now = new Date();
            autosaveText.textContent = `Tersimpan otomatis ${now.getHours()}:${String(now.getMinutes()).padStart(2,'0')}`;
        }

        function restoreDraft() {
            // old() from server takes priority — only restore if no server old() data
            const hasServerOld = {{ old('judul') ? 'true' : 'false' }};
            if (hasServerOld) { return; }

            const raw = localStorage.getItem(DRAFT_KEY);
            if (!raw) { return; }

            try {
                const draft = JSON.parse(raw);
                persistFields.forEach(name => {
                    const el = document.querySelector(`[name="${name}"]`);
                    if (el && draft[name] !== undefined) { el.value = draft[name]; }
                });

                // Restore Quill content
                if (draft['konten'] && draft['konten'] !== '<p><br></p>') {
                    quill.root.innerHTML = draft['konten'];
                }

                // Update status-dependent UI
                togglePublishTime();
                updateStatusHint();

                if (draft['_saved_at']) {
                    const savedAt = new Date(draft['_saved_at']);
                    autosaveText.textContent = `Draft dipulihkan dari ${savedAt.toLocaleDateString('id-ID', { day:'2-digit', month:'short' })} ${savedAt.getHours()}:${String(savedAt.getMinutes()).padStart(2,'0')}`;
                }
            } catch(e) {
                localStorage.removeItem(DRAFT_KEY);
            }
        }

        function scheduleSave() {
            clearTimeout(autosaveTimer);
            autosaveTimer = setTimeout(saveDraft, 800);
        }

        // Watch text fields
        persistFields.forEach(name => {
            const el = document.querySelector(`[name="${name}"]`);
            if (el) { el.addEventListener('input', scheduleSave); }
        });
        document.querySelector('[name="status"]').addEventListener('change', scheduleSave);

        // Watch Quill changes
        quill.on('text-change', scheduleSave);

        // Clear draft button
        btnClearDraft.addEventListener('click', function() {
            if (confirm('Hapus draft tersimpan? Data yang belum dikirim akan hilang.')) {
                localStorage.removeItem(DRAFT_KEY);
                autosaveText.textContent = 'Draft dihapus';
            }
        });

        // Clear draft on successful form submit
        const form = document.getElementById('articleForm');
        form.addEventListener('submit', function() {
            const htmlContent = quill.root.innerHTML;
            if (htmlContent === '<p><br></p>' || quill.getText().trim() === '') {
                document.getElementById('konten-input').value = '';
            } else {
                document.getElementById('konten-input').value = htmlContent;
            }
            // Remove draft so next create starts fresh
            localStorage.removeItem(DRAFT_KEY);
        });

        // Restore draft on page load
        restoreDraft();

        // ─── STATUS TOGGLE ──────────────────────────────────────────────────────
        const statusSelect = document.getElementById('status');
        const publishTimeDiv = document.getElementById('publish_time_container');
        const statusHints = {
            draft: document.getElementById('status-hint-draft'),
            pending: document.getElementById('status-hint-pending'),
            publish_now: document.getElementById('status-hint-publish_now'),
            publish_custom: document.getElementById('status-hint-publish_custom'),
            archived: document.getElementById('status-hint-archived'),
        };

        function togglePublishTime() {
            if (statusSelect.value === 'publish_custom') {
                publishTimeDiv.classList.remove('hidden');
            } else {
                publishTimeDiv.classList.add('hidden');
            }
        }

        function updateStatusHint() {
            Object.values(statusHints).forEach(el => { if (el) { el.classList.add('hidden'); } });
            const active = statusHints[statusSelect.value];
            if (active) { active.classList.remove('hidden'); }
        }

        statusSelect.addEventListener('change', function() {
            togglePublishTime();
            updateStatusHint();
        });

        togglePublishTime();
        updateStatusHint();

        // ─── DRAG & DROP THUMBNAIL UPLOAD ──────────────────────────────────────
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('thumbnail-file-input');
        const tempThumbnailInput = document.getElementById('temp_thumbnail');
        const tempThumbnailUrlInput = document.getElementById('temp_thumbnail_url');
        
        const promptState = document.getElementById('dropzone-prompt');
        const loadingState = document.getElementById('dropzone-loading');
        const previewState = document.getElementById('dropzone-preview');
        const previewImage = document.getElementById('preview-image');
        const previewFilename = document.getElementById('preview-filename');
        const btnRemove = document.getElementById('btn-remove-thumbnail');
        const errorState = document.getElementById('dropzone-error');
        const errorText = document.getElementById('dropzone-error-text');
        
        const progressBar = document.getElementById('upload-progress-bar');
        const progressText = document.getElementById('upload-progress-text');

        if (tempThumbnailInput.value && tempThumbnailUrlInput.value) {
            showPreview(tempThumbnailUrlInput.value, tempThumbnailInput.value);
        }

        dropzone.addEventListener('click', function(e) {
            if (!e.target.closest('#btn-remove-thumbnail')) {
                fileInput.click();
            }
        });

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                handleFileUpload(this.files[0]);
            }
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('border-[#4f45b2]', 'bg-[#4f45b2]/5');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('border-[#4f45b2]', 'bg-[#4f45b2]/5');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                handleFileUpload(files[0]);
            }
        });

        function handleFileUpload(file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                showError('Format berkas tidak valid. Harap pilih gambar JPG, JPEG, PNG, atau WEBP.');
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                showError('Ukuran gambar terlalu besar. Maksimum ukuran adalah 2MB.');
                return;
            }

            hideError();
            showLoading();

            const formData = new FormData();
            formData.append('thumbnail', file);
            const csrfToken = document.querySelector('input[name="_token"]').value;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("admin.articles.upload-temp") }}', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    progressText.textContent = percentComplete + '%';
                }
            });

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    tempThumbnailInput.value = response.path;
                    tempThumbnailUrlInput.value = response.url;
                    showPreview(response.url, file.name);
                } else {
                    let errMsg = 'Gagal mengunggah gambar ke server.';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) { errMsg = response.message; }
                        else if (response.error) { errMsg = response.error; }
                    } catch (e) {}
                    showError(errMsg);
                    resetDropzone();
                }
            };

            xhr.onerror = function() {
                showError('Terjadi kesalahan jaringan saat mengunggah.');
                resetDropzone();
            };

            xhr.send(formData);
        }

        function showLoading() {
            promptState.classList.add('hidden');
            previewState.classList.add('hidden');
            loadingState.classList.remove('hidden');
            progressBar.style.width = '0%';
            progressText.textContent = '0%';
        }

        function showPreview(url, filename) {
            loadingState.classList.add('hidden');
            promptState.classList.add('hidden');
            previewState.classList.remove('hidden');
            previewImage.src = url;
            previewFilename.textContent = filename;
        }

        function resetDropzone() {
            loadingState.classList.add('hidden');
            previewState.classList.add('hidden');
            promptState.classList.remove('hidden');
            fileInput.value = '';
        }

        function showError(msg) {
            errorState.classList.remove('hidden');
            errorText.textContent = msg;
        }

        function hideError() {
            errorState.classList.add('hidden');
        }

        btnRemove.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            tempThumbnailInput.value = '';
            tempThumbnailUrlInput.value = '';
            resetDropzone();
            hideError();
        });

        // ─── LIVE SEO & SEARCH ENGINE SNIPPET PREVIEW ──────────────────────────
        const judulInput = document.querySelector('input[name="judul"]');
        const seoTitleInput = document.getElementById('seo_meta_title');
        const seoDescInput = document.getElementById('seo_meta_description');
        const seoKeywordInput = document.getElementById('seo_focus_keyword');
        
        const snippetTitle = document.getElementById('snippet-title');
        const snippetDesc = document.getElementById('snippet-desc');
        const snippetUrl = document.getElementById('snippet-url');
        
        const titleCounter = document.getElementById('title-counter');
        const descCounter = document.getElementById('desc-counter');
        const btnGenerateSeo = document.getElementById('btn-generate-seo');
        
        const checkTitleLen = document.getElementById('check-title-len');
        const checkDescLen = document.getElementById('check-desc-len');
        const checkKeywordFilled = document.getElementById('check-keyword-filled');
        const checkKeywordInTitle = document.getElementById('check-keyword-in-title');
        const checkKeywordInDesc = document.getElementById('check-keyword-in-desc');
        const seoScore = document.getElementById('seo-score');

        function generateSlug(text) {
            return text.toString().toLowerCase().trim()
                .replace(/\s+/g, '-')
                .replace(/&/g, '-and-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-');
        }

        function updateSeoPreview() {
            let titleVal = seoTitleInput.value.trim() || judulInput.value.trim() || 'Judul Artikel Utama Tampil di Sini...';
            let descVal = seoDescInput.value.trim() || 'Deskripsi pencarian artikel Google. Masukkan ringkasan menarik di kolom deskripsi SEO untuk menggoda pengunjung mencet artikel ini...';
            let slugVal = generateSlug(judulInput.value.trim() || 'contoh-slug');
            
            snippetTitle.textContent = titleVal;
            snippetDesc.textContent = descVal;
            snippetUrl.textContent = '/artikel/' + slugVal;
            
            titleCounter.textContent = `${seoTitleInput.value.length} / 60 karakter`;
            descCounter.textContent = `${seoDescInput.value.length} / 160 karakter`;
            
            titleCounter.classList.toggle('text-rose-500', seoTitleInput.value.length > 60);
            titleCounter.classList.toggle('font-bold', seoTitleInput.value.length > 60);
            descCounter.classList.toggle('text-rose-500', seoDescInput.value.length > 160);
            descCounter.classList.toggle('font-bold', seoDescInput.value.length > 160);
            
            runSeoAnalysis();
        }

        function runSeoAnalysis() {
            let title = seoTitleInput.value.trim() || judulInput.value.trim() || '';
            let desc = seoDescInput.value.trim() || '';
            let keyword = seoKeywordInput.value.trim().toLowerCase();
            let passedCount = 0;

            if (title.length >= 40 && title.length <= 60) { setCheckPassed(checkTitleLen, true); passedCount++; }
            else { setCheckPassed(checkTitleLen, false, 'Panjang judul ideal: 40-60 karakter (' + title.length + ')'); }

            if (desc.length >= 110 && desc.length <= 160) { setCheckPassed(checkDescLen, true); passedCount++; }
            else { setCheckPassed(checkDescLen, false, 'Panjang deskripsi ideal: 110-160 karakter (' + desc.length + ')'); }

            if (keyword.length > 0) {
                setCheckPassed(checkKeywordFilled, true); passedCount++;
                if (title.toLowerCase().includes(keyword)) { setCheckPassed(checkKeywordInTitle, true); passedCount++; }
                else { setCheckPassed(checkKeywordInTitle, false); }
                if (desc.toLowerCase().includes(keyword)) { setCheckPassed(checkKeywordInDesc, true); passedCount++; }
                else { setCheckPassed(checkKeywordInDesc, false); }
            } else {
                setCheckPassed(checkKeywordFilled, false);
                setCheckPassed(checkKeywordInTitle, false);
                setCheckPassed(checkKeywordInDesc, false);
            }

            let scorePercent = Math.round((passedCount / 5) * 100);
            seoScore.textContent = `${scorePercent}% Score`;
            seoScore.className = scorePercent >= 80 ? 'font-bold text-emerald-500'
                : scorePercent >= 50 ? 'font-bold text-amber-500' : 'font-bold text-rose-500';
        }

        function setCheckPassed(element, isPassed, customText = null) {
            const icon = element.querySelector('i');
            const label = element.querySelector('span');
            if (isPassed) {
                element.className = "flex items-start gap-2 text-emerald-600 dark:text-emerald-500 font-medium";
                icon.className = "fa-solid fa-circle-check text-emerald-500 mt-0.5";
            } else {
                element.className = "flex items-start gap-2 text-slate-500 dark:text-zinc-450";
                icon.className = "fa-solid fa-circle-xmark text-rose-500 mt-0.5";
            }
            if (customText) { label.textContent = customText; }
        }

        btnGenerateSeo.addEventListener('click', function() {
            let cleanText = quill.getText().trim().replace(/\s+/g, ' ');
            if (cleanText.length > 0) {
                seoDescInput.value = cleanText.substring(0, 155) + (cleanText.length > 155 ? '...' : '');
                updateSeoPreview();
            } else {
                alert('Tulis isi artikel terlebih dahulu untuk men-generate deskripsi otomatis.');
            }
        });

        [judulInput, seoTitleInput, seoDescInput, seoKeywordInput].forEach(el => {
            if (el) { el.addEventListener('input', updateSeoPreview); }
        });

        const btnMobile = document.getElementById('preview-tab-mobile');
        const btnDesktop = document.getElementById('preview-tab-desktop');
        
        btnMobile.addEventListener('click', function() {
            btnMobile.classList.add('bg-slate-100', 'dark:bg-zinc-850', 'text-[#4f45b2]');
            btnMobile.classList.remove('text-slate-400');
            btnDesktop.classList.remove('bg-slate-100', 'dark:bg-zinc-850', 'text-[#4f45b2]');
            btnDesktop.classList.add('text-slate-400');
            snippetTitle.classList.replace('text-base', 'text-sm');
        });
        
        btnDesktop.addEventListener('click', function() {
            btnDesktop.classList.add('bg-slate-100', 'dark:bg-zinc-850', 'text-[#4f45b2]');
            btnDesktop.classList.remove('text-slate-400');
            btnMobile.classList.remove('bg-slate-100', 'dark:bg-zinc-850', 'text-[#4f45b2]');
            btnMobile.classList.add('text-slate-400');
            snippetTitle.classList.replace('text-sm', 'text-base');
        });

        updateSeoPreview();
    });
</script>