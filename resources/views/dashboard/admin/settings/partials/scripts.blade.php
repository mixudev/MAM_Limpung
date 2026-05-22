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
        // If file input has files or already configured logo path, do not trigger dialog by clicking the entire area unless user reset
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

    // Signature File Upload Logic
    const signatureDropzone = document.getElementById('signature-dropzone');
    const signatureFileInput = document.getElementById('signatureFileInput');
    const signatureUploadState = document.getElementById('signature-upload-state');
    const signaturePreviewState = document.getElementById('signature-preview-state');
    const signaturePreview = document.getElementById('signature-preview');

    signatureDropzone.addEventListener('click', () => {
        if (signatureUploadState.classList.contains('hidden')) return;
        signatureFileInput.click();
    });

    signatureFileInput.addEventListener('change', handleSignatureFileSelect);

    signatureDropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        if (signatureUploadState.classList.contains('hidden')) return;
        signatureDropzone.classList.add('border-indigo-500', 'bg-indigo-50/20', 'dark:bg-zinc-900/30');
    });

    signatureDropzone.addEventListener('dragleave', () => {
        signatureDropzone.classList.remove('border-indigo-500', 'bg-indigo-50/20', 'dark:bg-zinc-900/30');
    });

    signatureDropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        signatureDropzone.classList.remove('border-indigo-500', 'bg-indigo-50/20', 'dark:bg-zinc-900/30');
        
        if (signatureUploadState.classList.contains('hidden')) return;

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            signatureFileInput.files = files;
            handleSignatureFileSelect();
        }
    });

    function handleSignatureFileSelect() {
        const file = signatureFileInput.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            signaturePreview.src = e.target.result;
            signatureUploadState.classList.add('hidden');
            signaturePreviewState.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    function resetSignatureFileSelection() {
        signatureFileInput.value = '';
        signaturePreview.src = '';
        signatureUploadState.classList.remove('hidden');
        signaturePreviewState.classList.add('hidden');
    }
</script>
