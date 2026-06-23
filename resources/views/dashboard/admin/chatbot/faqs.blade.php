@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bc = document.getElementById('breadcrumb');
        if (bc) bc.textContent = 'Chatbot · FAQ Cepat';
    });
</script>

<div class="space-y-5">

    {{-- Header --}}
    @include('dashboard.admin.chatbot.partials._header', [
        'title' => 'FAQ Cepat',
        'subtitle' => 'Pertanyaan pintasan di awal chatbot. Dijawab instan tanpa memanggil AI.'
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
        @include('dashboard.admin.chatbot.partials._faqs')
    </div>

    {{-- Modals --}}
    @include('dashboard.admin.chatbot.partials._modals')
</div>

<script>
function openFaqModal(editMode, data) {
    var form = document.getElementById('faqForm');
    if (!form) return;
    var methodInput = document.getElementById('faqMethodInput');
    var submitBtn   = document.getElementById('faqSubmitBtn');
    if (editMode) {
        form.action = '{{ url('admin/chatbot/faqs') }}/' + data.id;
        methodInput.disabled = false;
        submitBtn.textContent = 'Simpan Perubahan';
        document.getElementById('faqOrder').value    = data.order;
        document.getElementById('faqIsActive').value = data.is_active;
        document.getElementById('faqQuestion').value = data.question;
        document.getElementById('faqAnswer').value   = data.answer;
    } else {
        form.action = '{{ route('admin.chatbot.faqs.store') }}';
        methodInput.disabled = true;
        submitBtn.textContent = 'Tambah FAQ';
        document.getElementById('faqOrder').value    = '0';
        document.getElementById('faqIsActive').value = '1';
        document.getElementById('faqQuestion').value = '';
        document.getElementById('faqAnswer').value   = '';
    }
    AppModal.open('faqModal');
}
</script>
@endsection
