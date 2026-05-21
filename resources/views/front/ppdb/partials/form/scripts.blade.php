<script>
    const dropZone = document.getElementById('dropZone');
    const dropZoneContent = document.getElementById('dropZoneContent');
    const fileInput = document.getElementById('fileInput');

    function renderFotoPreviewFromUrl(url, name) {
        if (!dropZoneContent) {
            return;
        }
        dropZoneContent.innerHTML = `
            <img src="${url}" alt="Pratinjau pas foto" class="max-h-44 w-auto mx-auto object-contain border border-slate-200 mb-2">
            <p class="text-slate-800 font-bold text-xs text-center truncate max-w-[200px] mx-auto">${name}</p>
            <p class="text-emerald-800 text-[10px] mt-1 text-center font-mono font-bold">Foto tersimpan — klik untuk mengubah</p>
        `;
    }

    function renderFotoPreview(file) {
        if (!dropZoneContent) {
            return;
        }

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                dropZoneContent.innerHTML = `
                    <img src="${e.target.result}" alt="Pratinjau pas foto" class="max-h-44 w-auto mx-auto object-contain border border-slate-200 mb-2">
                    <p class="text-slate-800 font-bold text-xs text-center truncate max-w-[200px] mx-auto">${file.name}</p>
                    <p class="text-emerald-800 text-[10px] mt-1 text-center font-mono font-bold">Foto berhasil dipilih — klik untuk mengubah</p>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            dropZoneContent.innerHTML = `
                <p class="text-slate-800 font-bold text-xs text-center">${file.name}</p>
                <p class="text-slate-400 text-[10px] mt-1 text-center">Klik dropzone untuk mengubah</p>
            `;
        }
    }

    if (dropZone && fileInput) {
        if (dropZoneContent?.dataset.restoredUrl) {
            renderFotoPreviewFromUrl(
                dropZoneContent.dataset.restoredUrl,
                dropZoneContent.dataset.restoredName || 'Foto tersimpan'
            );
        }

        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-emerald-800', 'bg-slate-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-emerald-800', 'bg-slate-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-emerald-800', 'bg-slate-50');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                renderFotoPreview(files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                renderFotoPreview(e.target.files[0]);
            }
        });
    }

    function renderDocPreview(file, container) {
        container.classList.remove('hidden');
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                container.innerHTML = `
                    <p class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider mb-2">Pratinjau berkas</p>
                    <img src="${e.target.result}" alt="Pratinjau dokumen" class="max-h-32 w-auto border border-slate-200 object-contain">
                    <p class="text-xs text-slate-600 mt-2 truncate">${file.name}</p>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            container.innerHTML = `
                <p class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider mb-1">Berkas terpilih</p>
                <p class="text-xs text-slate-700 font-mono truncate">${file.name}</p>
                <p class="text-[10px] text-slate-400 mt-1">PDF — pratinjau gambar tidak tersedia</p>
            `;
        }
    }

    function renderDocPreviewFromServer(container) {
        const url = container.dataset.restoredUrl;
        const name = container.dataset.restoredName || 'Berkas tersimpan';
        const isImage = container.dataset.restoredIsImage === '1';

        container.classList.remove('hidden');

        if (isImage && url) {
            container.innerHTML = `
                <p class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider mb-2">Berkas tersimpan</p>
                <img src="${url}" alt="Pratinjau dokumen" class="max-h-32 w-auto border border-slate-200 object-contain">
                <p class="text-xs text-slate-600 mt-2 truncate">${name}</p>
            `;
        } else {
            container.innerHTML = `
                <p class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider mb-1">Berkas tersimpan</p>
                <p class="text-xs text-slate-700 font-mono truncate">${name}</p>
                <p class="text-[10px] text-slate-400 mt-1">PDF — pratinjau gambar tidak tersedia</p>
            `;
        }
    }

    document.querySelectorAll('.ppdb-doc-input').forEach((input) => {
        const preview = input.closest('.bg-slate-50')?.querySelector('.ppdb-doc-preview');
        if (!preview) {
            return;
        }

        if (preview.dataset.restoredUrl) {
            renderDocPreviewFromServer(preview);
        }

        input.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                renderDocPreview(e.target.files[0], preview);
            }
        });
    });
</script>
