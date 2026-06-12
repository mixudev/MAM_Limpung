@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bc = document.getElementById('breadcrumb');
        if (bc) bc.textContent = 'Chatbot · Log Aktivitas';
    });
</script>

<div class="space-y-5">

    {{-- Header --}}
    @include('dashboard.admin.chatbot.partials._header', [
        'title' => 'Log Aktivitas',
        'subtitle' => 'Rekam jejak aktivitas AI chatbot — pesan, error, dan trigger FAQ.'
    ])

    {{-- Flash --}}
    @if(session('success'))
    <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 text-sm text-emerald-700 dark:text-emerald-400 font-mono">
        <i class="fa-solid fa-circle-check shrink-0"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Content --}}
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
        @include('dashboard.admin.chatbot.partials._logs')
    </div>

    {{-- Modals --}}
    @include('dashboard.admin.chatbot.partials._modals')
</div>

<script>
function openTranscript(sessionId) {
    var loadingEl   = document.getElementById('transcriptLoading');
    var contentEl   = document.getElementById('transcriptContent');
    var bubblesEl   = document.getElementById('transcriptBubbles');
    var metaTopicEl = document.getElementById('transcriptTopic');
    var metaUserEl  = document.getElementById('transcriptUser');
    var metaTimeEl  = document.getElementById('transcriptTime');

    loadingEl.style.display = '';
    contentEl.style.display = 'none';
    bubblesEl.innerHTML = '';
    AppModal.open('transcriptModal');

    fetch('{{ url('admin/chatbot/sessions') }}/' + sessionId, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        loadingEl.style.display = 'none';
        contentEl.style.display = '';

        metaTopicEl.textContent = d.topic || '—';
        metaUserEl.textContent  = d.user ? d.user.name : ('Tamu · ' + (d.user_ip || '—'));
        metaTimeEl.textContent  = d.created_at ? new Date(d.created_at).toLocaleString('id-ID') : '';

        var msgs = d.messages || [];
        if (msgs.length === 0) {
            bubblesEl.innerHTML = '<div class="flex items-center justify-center h-24 text-slate-400 text-xs font-mono">Sesi ini tidak memiliki pesan.</div>';
            return;
        }
        msgs.forEach(function(msg) {
            var isUser = msg.sender === 'user';
            var bubble = document.createElement('div');
            bubble.className = 'flex items-end gap-2 ' + (isUser ? 'justify-end' : 'justify-start');
            bubble.innerHTML = (isUser ? '' :
                '<div class="w-6 h-6 bg-[#4f45b2] text-white flex items-center justify-center text-[10px] shrink-0 mb-0.5"><i class="fa-solid fa-robot"></i></div>')
                + '<div class="max-w-[78%]">'
                    + '<div class="px-4 py-2.5 text-xs leading-relaxed ' + (isUser ? 'bg-[#4f45b2] text-white' : 'bg-slate-100 dark:bg-zinc-800 text-slate-800 dark:text-zinc-200 border border-slate-200 dark:border-zinc-700') + '">'
                        + '<p class="whitespace-pre-wrap">' + escHtml(msg.message) + '</p>'
                    + '</div>'
                    + '<div class="text-[10px] text-slate-400 font-mono mt-1 px-1 ' + (isUser ? 'text-right' : 'text-left') + '">' + (isUser ? 'Pengguna' : 'AI Chatbot') + '</div>'
                + '</div>'
                + (isUser ? '<div class="w-6 h-6 bg-slate-200 dark:bg-zinc-700 text-slate-500 flex items-center justify-center text-[10px] shrink-0 mb-0.5"><i class="fa-solid fa-user"></i></div>' : '');
            bubblesEl.appendChild(bubble);
        });
        bubblesEl.scrollTop = bubblesEl.scrollHeight;
    })
    .catch(function() {
        loadingEl.style.display = 'none';
        contentEl.style.display = '';
        bubblesEl.innerHTML = '<div class="text-center text-xs text-red-500 font-mono py-4">Gagal memuat transkrip.</div>';
    });
}

function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
}

function openPayloadModal(payload) {
    var codeEl = document.getElementById('payloadCode');
    if (codeEl) {
        if (payload && payload.fallback_sequence && payload.fallback_sequence.length > 0) {
            var html = '<div class="space-y-4 text-left">';
            html += '<h3 class="text-rose-500 font-bold mb-2 uppercase tracking-wider text-xs flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> Rantai / Urutan Percobaan API Fallback:</h3>';
            payload.fallback_sequence.forEach(function(attempt, index) {
                html += '<div class="p-3.5 bg-rose-50/40 dark:bg-rose-950/10 border border-rose-200 dark:border-rose-900/40 font-mono text-[11px] leading-relaxed rounded">';
                html += '<div class="font-bold text-rose-700 dark:text-rose-400 flex justify-between"><span>Percobaan #' + (index + 1) + ' · API Key ID: ' + attempt.api_key_id + ' (' + String(attempt.provider).toUpperCase() + ')</span><span class="text-[10px] text-slate-400">' + new Date(attempt.occurred_at).toLocaleTimeString("id-ID") + '</span></div>';
                html += '<div class="mt-1.5"><span class="text-slate-400 font-semibold">Model Name:</span> <span class="text-slate-700 dark:text-zinc-300 font-semibold">' + attempt.model_name + '</span></div>';
                html += '<div class="mt-1"><span class="text-slate-400 font-semibold">Tipe Error:</span> <span class="px-1.5 py-0.5 bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-400 font-bold uppercase rounded text-[9px]">' + attempt.error_type + '</span></div>';
                html += '<div class="mt-1.5 text-rose-600 dark:text-rose-300 break-words"><span class="text-slate-400 font-semibold">Pesan:</span> ' + escHtml(attempt.error_message) + '</div>';
                html += '</div>';
            });
            html += '<div class="mt-4 pt-3 border-t border-slate-200 dark:border-zinc-800"><span class="font-bold text-slate-500 dark:text-zinc-400 font-mono uppercase text-[10px] tracking-wider">Payload Lengkap:</span></div>';
            html += '<pre class="mt-1 p-3 bg-slate-900 dark:bg-black border border-slate-200 dark:border-zinc-800 text-[10px] text-emerald-400 whitespace-pre-wrap overflow-x-auto rounded">' + escHtml(JSON.stringify(payload, null, 2)) + '</pre>';
            html += '</div>';
            codeEl.innerHTML = html;
        } else {
            codeEl.innerHTML = '<pre class="whitespace-pre-wrap overflow-x-auto text-[11px] text-slate-700 dark:text-zinc-300">' + escHtml(JSON.stringify(payload, null, 2)) + '</pre>';
        }
    }
    AppModal.open('payloadModal');
}
</script>
@endsection
