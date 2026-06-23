@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bc = document.getElementById('breadcrumb');
        if (bc) bc.textContent = 'Chatbot · Basis Pengetahuan';
    });
</script>

<div class="space-y-5">

    {{-- Header --}}
    @include('dashboard.admin.chatbot.partials._header', [
        'title' => 'Basis Pengetahuan',
        'subtitle' => 'Informasi yang dijadikan konteks AI saat menjawab pertanyaan pengguna.'
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
        @include('dashboard.admin.chatbot.partials._knowledge')
    </div>

    {{-- Modals --}}
    @include('dashboard.admin.chatbot.partials._modals')
</div>

<script>
function openKnowledgeModal(editMode, data) {
    var form = document.getElementById('knowledgeForm');
    if (!form) return;
    var methodInput = document.getElementById('knowledgeMethodInput');
    var submitBtn   = document.getElementById('knowledgeSubmitBtn');
    if (editMode) {
        form.action = '{{ url('admin/chatbot/knowledge') }}/' + data.id;
        methodInput.disabled = false;
        submitBtn.textContent = 'Simpan Perubahan';
        document.getElementById('kbIsActive').value = data.is_active;
        document.getElementById('kbTitle').value    = data.title;
        document.getElementById('kbContent').value  = data.content;
    } else {
        form.action = '{{ route('admin.chatbot.knowledge.store') }}';
        methodInput.disabled = true;
        submitBtn.textContent = 'Tambah Pengetahuan';
        document.getElementById('kbIsActive').value = '1';
        document.getElementById('kbTitle').value    = '';
        document.getElementById('kbContent').value  = '';
    }
    AppModal.open('knowledgeModal');
}
</script>
@endsection
