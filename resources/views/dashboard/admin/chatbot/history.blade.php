@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bc = document.getElementById('breadcrumb');
        if (bc) bc.textContent = 'Chatbot · Riwayat Chat';
    });
</script>

<div class="space-y-5">

    {{-- Header --}}
    @include('dashboard.admin.chatbot.partials._header', [
        'title' => 'Riwayat Chat',
        'subtitle' => 'Pantau seluruh sesi percakapan pengguna dengan AI chatbot.'
    ])

    {{-- Flash --}}
    @if(session('success'))
    <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 text-sm text-emerald-700 dark:text-emerald-400 font-mono">
        <i class="fa-solid fa-circle-check shrink-0"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Content --}}
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
        @include('dashboard.admin.chatbot.partials._history')
    </div>

    {{-- Modals --}}
    @include('dashboard.admin.chatbot.partials._modals')
</div>

<script>
function openTranscript(sessionId) {
    var loadingEl   = document.getElementById('transcriptLoading');
    var contentEl   = document.getElementById('transcriptContent');
    var bubblesEl   = document.getElementById('transcriptBubbles');
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

        metaUserEl.textContent  = d.user ? d.user.name : ('Tamu · ' + (d.user_ip || '—'));
        metaTimeEl.textContent  = d.created_at ? new Date(d.created_at).toLocaleString('id-ID') : '';

        var msgs = d.messages || [];
        if (msgs.length === 0) {
            bubblesEl.innerHTML = '<div class="flex items-center justify-center h-24 text-slate-400 text-xs font-mono">Sesi ini tidak memiliki pesan.</div>';
            return;
        }

        function parseMessageHtml(messageText) {
            var esc = escHtml(messageText);
            var buttonRegex = /\[BUTTON:\s*([^|]+)\s*\|\s*([^\]]+)\]/g;
            return esc.replace(buttonRegex, function(match, label, url) {
                return '<div class="mt-2"><a href="' + url + '" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#4f45b2] text-white rounded-lg font-bold text-[10px] tracking-wider uppercase transition-colors"><i class="fa-solid fa-arrow-up-right-from-square"></i> ' + label + '</a></div>';
            });
        }

        msgs.forEach(function(msg) {
            var isUser = msg.sender === 'user';
            var bubble = document.createElement('div');
            bubble.className = 'flex items-end gap-2 ' + (isUser ? 'justify-end' : 'justify-start');
            bubble.innerHTML = (isUser ? '' :
                '<div class="w-6 h-6 bg-[#4f45b2] text-white flex items-center justify-center text-[10px] shrink-0 mb-0.5"><i class="fa-solid fa-robot"></i></div>')
                + '<div class="max-w-[78%]">'
                    + '<div class="px-4 py-2.5 text-xs leading-relaxed ' + (isUser ? 'bg-[#4f45b2] text-white' : 'bg-slate-100 dark:bg-zinc-800 text-slate-800 dark:text-zinc-200 border border-slate-200 dark:border-zinc-700') + '">'
                        + '<p class="whitespace-pre-wrap">' + parseMessageHtml(msg.message) + '</p>'
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
</script>
@endsection
