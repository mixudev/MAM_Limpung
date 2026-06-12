@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bc = document.getElementById('breadcrumb');
        if (bc) bc.textContent = 'Chatbot · Kunci API';
    });
</script>

<div class="space-y-5">

    {{-- Header --}}
    @include('dashboard.admin.chatbot.partials._header', [
        'title' => 'Kunci API',
        'subtitle' => 'Kelola API Key (Gemini, Groq, DeepSeek, OpenRouter) yang digunakan chatbot untuk berkomunikasi dengan model AI.'
    ])

    {{-- Flash --}}
    @if(session('success'))
    <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 text-sm text-emerald-700 dark:text-emerald-400 font-mono">
        <i class="fa-solid fa-circle-check shrink-0"></i> {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="flex items-start gap-3 px-4 py-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 text-sm text-red-700 dark:text-red-400 font-mono">
        <i class="fa-solid fa-circle-xmark shrink-0 mt-0.5"></i>
        <ul class="list-disc list-inside space-y-0.5 text-xs">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Content --}}
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
        @include('dashboard.admin.chatbot.partials._apikeys')
    </div>

    {{-- Modals --}}
    @include('dashboard.admin.chatbot.partials._modals')
</div>

<script>
function openKeyModal(editMode, data) {
    var form = document.getElementById('keyForm');
    if (!form) return;
    var methodInput = document.getElementById('keyMethodInput');
    var apiKeyLabel = document.getElementById('keyApiLabel');
    var apiKeyInput = document.getElementById('keyApiInput');
    var submitBtn   = document.getElementById('keySubmitBtn');
    if (editMode) {
        form.action = '{{ url('admin/chatbot/apikeys') }}/' + data.id;
        methodInput.value = 'PUT'; methodInput.disabled = false;
        apiKeyLabel.textContent = 'API Key (kosongkan jika tidak diubah)';
        apiKeyInput.required = false; apiKeyInput.value = '';
        submitBtn.textContent = 'Simpan Perubahan';
        document.getElementById('keyProvider').value = data.provider;
        document.getElementById('keyModelName').value = data.model_name;
    } else {
        form.action = '{{ route('admin.chatbot.apikeys.store') }}';
        methodInput.value = 'PUT'; methodInput.disabled = true;
        apiKeyLabel.textContent = 'API Key';
        apiKeyInput.required = true; apiKeyInput.value = '';
        submitBtn.textContent = 'Tambah API Key';
        document.getElementById('keyProvider').value = 'gemini';
        document.getElementById('keyModelName').value = 'gemini-2.5-flash';
    }
    AppModal.open('keyModal');
}
function toggleKeyVisibility() {
    var inp = document.getElementById('keyApiInput');
    var icon = document.getElementById('keyEyeIcon');
    if (inp.type === 'password') { inp.type = 'text'; icon.className = 'fa-solid fa-eye-slash'; }
    else { inp.type = 'password'; icon.className = 'fa-solid fa-eye'; }
}
</script>
@endsection
