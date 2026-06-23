<script>
    // Tab switching logic
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

    // Drag and Drop Zone Logic (Vanilla JS)
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');
    const uploadState = document.getElementById('upload-state');
    const previewState = document.getElementById('preview-state');
    const logoPreview = document.getElementById('logo-preview');

    dropzone.addEventListener('click', () => {
        if (uploadState.classList.contains('hidden')) return;
        fileInput.click();
    });

    fileInput.addEventListener('change', handleFileSelect);

    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        if (uploadState.classList.contains('hidden')) return;
        dropzone.classList.add('border-indigo-500', 'bg-indigo-50/20', 'dark:bg-zinc-900/30');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('border-indigo-500', 'bg-indigo-50/20', 'dark:bg-zinc-900/30');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-indigo-500', 'bg-indigo-50/20', 'dark:bg-zinc-900/30');
        
        if (uploadState.classList.contains('hidden')) return;

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect();
        }
    });

    function handleFileSelect() {
        const file = fileInput.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            logoPreview.src = e.target.result;
            uploadState.classList.add('hidden');
            previewState.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    function resetFileSelection() {
        fileInput.value = '';
        logoPreview.src = '';
        uploadState.classList.remove('hidden');
        previewState.classList.add('hidden');
    }
</script>