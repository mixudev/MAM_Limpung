@extends('dashboard.layouts.main')

@section('content')
{{-- Quill Snow CSS agar class ql-* (indent, align, list, dll.) tampil benar --}}
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) breadcrumb.textContent = 'Detail Artikel';
    });
</script>

<div class="max-w-5xl space-y-6">

    {{-- ── Header ──────────────────────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 shadow-sm
                flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.articles.index', ['tab' => in_array($article->status, ['pending','revision']) ? 'pending' : ($article->status === 'published' ? 'published' : 'others')]) }}"
               class="flex-shrink-0 p-2 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors">
                <svg class="w-4 h-4 text-slate-500 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div class="min-w-0">
                <h1 class="text-lg font-bold text-slate-900 dark:text-white truncate">{{ $article->judul }}</h1>
                <div class="flex items-center gap-2 mt-1 flex-wrap">
                    @if($article->status === 'published')
                        <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/40">TERBIT</span>
                    @elseif($article->status === 'pending')
                        <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-700/40">PENDING</span>
                    @elseif($article->status === 'revision')
                        <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/40">REVISI</span>
                    @elseif($article->status === 'rejected')
                        <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900/40">DITOLAK</span>
                    @elseif($article->status === 'draft')
                        <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 border border-slate-200 dark:border-zinc-700">DRAFT</span>
                    @else
                        <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900/40">ARSIP</span>
                    @endif
                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 font-mono">{{ $article->penulis?->name ?? '—' }}</span>
                    <span class="text-[10px] text-slate-300 dark:text-zinc-600">•</span>
                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 font-mono">{{ $article->created_at->translatedFormat('d M Y') }}</span>
                </div>
            </div>
        </div>
        @can('update', $article)
        <a href="{{ route('admin.articles.edit', $article->slug) }}"
           class="flex-shrink-0 inline-flex items-center gap-2 py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs font-mono uppercase tracking-wider transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
            </svg>
            Edit
        </a>
        @endcan
    </div>

    {{-- ── Flash ───────────────────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/60">
            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs font-semibold text-emerald-800 dark:text-emerald-400">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/60">
            <p class="text-xs font-bold text-red-800 dark:text-red-400 mb-1">Terdapat kesalahan:</p>
            <ul class="list-disc list-inside space-y-0.5 text-xs text-red-700 dark:text-red-400 font-mono">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- ── Panel Tinjauan (pending / revision) ─────────────────────────────── --}}
    @if(in_array($article->status, ['pending', 'revision']) && !auth()->user()->hasRole('siswa'))
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm overflow-hidden">

        {{-- Banner --}}
        <div class="flex items-center gap-3 px-6 py-4
                    {{ $article->status === 'revision' ? 'bg-blue-50 dark:bg-blue-950/20 border-b border-blue-200 dark:border-blue-800/40' : 'bg-amber-50 dark:bg-amber-950/20 border-b border-amber-200 dark:border-amber-800/40' }}">
            @if($article->status === 'revision')
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-bold text-blue-800 dark:text-blue-300">Artikel sedang dalam proses revisi</p>
                    <p class="text-xs text-blue-700 dark:text-blue-400 mt-0.5">
                        Penulis sedang memperbaiki artikel. Setelah disubmit ulang, akan kembali ke status <strong>Pending</strong>.
                    </p>
                </div>
                @php $revCount = $article->revisions->count(); @endphp
                <span class="flex-shrink-0 px-2.5 py-1 text-[9px] font-bold font-mono tracking-widest uppercase bg-blue-200 dark:bg-blue-800/40 text-blue-800 dark:text-blue-300 border border-blue-300 dark:border-blue-600/50">
                    REVISI KE-{{ $revCount }}
                </span>
            @else
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-bold text-amber-800 dark:text-amber-300">Artikel menunggu tinjauan Anda</p>
                    <p class="text-xs text-amber-700 dark:text-amber-400 mt-0.5">Tinjau isi artikel di bawah, lalu pilih tindakan.</p>
                </div>
                <span class="flex-shrink-0 px-2.5 py-1 text-[9px] font-bold font-mono tracking-widest uppercase bg-amber-200 dark:bg-amber-800/40 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-600/50">
                    PENDING
                </span>
            @endif
        </div>

        {{-- 3 Tombol Aksi --}}
        @if($article->status === 'pending')
        <div class="px-6 py-5 flex flex-col sm:flex-row items-start sm:items-center gap-3">

            {{-- ① Setujui --}}
            <form action="{{ route('admin.articles.approve', $article->slug) }}" method="POST" id="approve-form">
                @csrf
                <button type="button"
                        onclick="AppPopup.info({
                            title: 'Setujui & Terbitkan',
                            description: 'Artikel akan langsung diterbitkan ke website. Tindakan ini tidak dapat dibatalkan.',
                            confirmText: 'Ya, Terbitkan',
                            cancelText: 'Batal',
                            onConfirm: function() { document.getElementById('approve-form').submit(); }
                        })"
                        class="inline-flex items-center gap-2 py-2.5 px-5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase font-mono tracking-wider border border-emerald-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Setujui &amp; Terbitkan
                </button>
            </form>

            {{-- ② Minta Revisi --}}
            <button type="button" onclick="AppModal.open('modal-revision')"
                    class="inline-flex items-center gap-2 py-2.5 px-5 bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/30 dark:hover:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 text-blue-700 dark:text-blue-400 font-bold text-xs uppercase font-mono tracking-wider transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                </svg>
                Minta Revisi
            </button>

            {{-- ③ Tolak --}}
            <button type="button" onclick="AppModal.open('modal-reject')"
                    class="inline-flex items-center gap-2 py-2.5 px-5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/30 dark:hover:bg-rose-900/40 border border-rose-300 dark:border-rose-700/50 text-rose-700 dark:text-rose-400 font-bold text-xs uppercase font-mono tracking-wider transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Tolak Artikel
            </button>

            <span class="hidden sm:block flex-1"></span>
            <p class="text-[10px] text-slate-400 dark:text-zinc-500 font-mono">Pilih tindakan setelah membaca artikel.</p>
        </div>
        @endif

    </div>
    @endif

    {{-- ── Alasan penolakan (jika status rejected & ada rejection_reason) ──────────── --}}
    @if($article->status === 'rejected' && $article->rejection_reason)
    <div class="flex gap-3 p-4 bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800/50">
        <svg class="w-4 h-4 text-rose-600 dark:text-rose-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
        </svg>
        <div>
            <p class="text-[10px] font-mono font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-1">Artikel Ditolak (Ke-{{ $article->rejection_count }})</p>
            <p class="text-xs text-rose-800 dark:text-rose-300 leading-relaxed">{{ $article->rejection_reason }}</p>
            @if($article->rejection_count >= 2)
                <p class="text-[10px] text-rose-600 dark:text-rose-550 font-bold uppercase tracking-wider font-mono mt-2 flex items-center gap-1">
                    <svg class="w-4 h-4 text-rose-600 dark:text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    Artikel ini telah ditolak 2 kali dan dikunci secara permanen.
                </p>
            @endif
        </div>
    </div>
    @endif

    {{-- ── Timeline Riwayat Revisi ──────────────────────────────────────── --}}
    @if($article->revisions->isNotEmpty())
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-zinc-800 flex items-center justify-between">
            <div>
                <h2 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-700 dark:text-zinc-300">Riwayat Revisi</h2>
                <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-0.5">{{ $article->revisions->count() }} revisi tercatat</p>
            </div>
        </div>
        <div class="p-6">
            <ol class="relative border-l border-slate-200 dark:border-zinc-700 space-y-0">
                @foreach($article->revisions->sortByDesc('revision_number') as $rev)
                <li class="ml-5 pb-6 last:pb-0">
                    {{-- Dot --}}
                    <span class="absolute -left-2.5 flex h-5 w-5 items-center justify-center rounded-full ring-4 ring-white dark:ring-zinc-900
                                 {{ $rev->status === 'resolved' ? 'bg-emerald-100 dark:bg-emerald-900/40' : 'bg-blue-100 dark:bg-blue-900/40' }}">
                        @if($rev->status === 'resolved')
                            <svg class="w-2.5 h-2.5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                        @else
                            <svg class="w-2.5 h-2.5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </span>

                    {{-- Card --}}
                    <div class="ml-3 p-4 border border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-800/30">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-wider
                                         {{ $rev->status === 'resolved' ? 'text-emerald-700 dark:text-emerald-400' : 'text-blue-700 dark:text-blue-400' }}">
                                Revisi #{{ $rev->revision_number }}
                            </span>
                            <span class="text-[10px] font-mono text-slate-400 dark:text-zinc-500">—</span>
                            <span class="text-[10px] font-mono text-slate-500 dark:text-zinc-400">
                                Diminta oleh <strong>{{ $rev->reviewer?->name ?? '—' }}</strong>
                            </span>
                            <span class="text-[10px] font-mono text-slate-400 dark:text-zinc-500">
                                {{ $rev->created_at->translatedFormat('d M Y, H:i') }}
                            </span>
                            @if($rev->status === 'resolved')
                                <span class="px-1.5 py-0.5 text-[9px] font-bold font-mono uppercase bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/40">
                                    Selesai
                                </span>
                            @else
                                <span class="px-1.5 py-0.5 text-[9px] font-bold font-mono uppercase bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/40">
                                    Menunggu
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-700 dark:text-zinc-300 leading-relaxed whitespace-pre-wrap">{{ $rev->notes }}</p>
                        @if($rev->resolved_at)
                            <p class="text-[10px] text-slate-400 dark:text-zinc-500 font-mono mt-2">
                                Diselesaikan {{ $rev->resolved_at->translatedFormat('d M Y, H:i') }}
                            </p>
                        @endif
                    </div>
                </li>
                @endforeach
            </ol>
        </div>
    </div>
    @endif

    {{-- ── Konten Artikel ───────────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-zinc-800 flex items-center gap-3">
            <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
            <h2 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400">Isi Artikel</h2>
        </div>

        <div class="p-6 md:p-8">

            {{-- Thumbnail --}}
            @if($article->thumbnail)
            <div class="w-full aspect-video bg-slate-100 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-800 overflow-hidden mb-6">
                <img src="{{ $article->thumbnailUrl() }}" alt="{{ $article->judul }}" class="w-full h-full object-cover">
            </div>
            @endif

            {{-- Judul --}}
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white leading-snug mb-3">
                {{ $article->judul }}
            </h1>

            {{-- Meta baris --}}
            <div class="flex flex-wrap items-center gap-3 text-[11px] text-slate-400 dark:text-zinc-500 font-mono mb-5 pb-5 border-b border-slate-100 dark:border-zinc-800">
                <span>{{ $article->category?->name ?? 'Tanpa Kategori' }}</span>
                <span>·</span>
                <span>{{ $article->penulis?->name ?? '—' }}</span>
                <span>·</span>
                <span>{{ $article->created_at->translatedFormat('d M Y') }}</span>
                @if($article->published_at)
                    <span>·</span>
                    <span class="text-emerald-600 dark:text-emerald-400">Terbit {{ $article->published_at->translatedFormat('d M Y') }}</span>
                @endif
            </div>

            {{-- Ringkasan --}}
            @if($article->ringkasan)
            <p class="text-sm text-slate-500 dark:text-zinc-400 italic border-l-4 border-slate-200 dark:border-zinc-700 pl-4 mb-6">
                {{ $article->ringkasan }}
            </p>
            @endif

            {{--
                Konten dari Quill editor — ql-snow + ql-editor menghidupkan semua
                format bawaan Quill: list, indent, align, blockquote, code block,
                table, dll. Prose dari Tailwind Typography menambahkan tipografi dasar.
            --}}
            <div class="ql-snow">
                <div class="ql-editor !p-0
                            prose prose-slate max-w-none
                            prose-p:text-slate-800 prose-p:leading-[1.8] dark:prose-p:text-zinc-300
                            prose-headings:font-bold prose-headings:text-slate-900 dark:prose-headings:text-white
                            prose-a:text-blue-700 prose-a:underline
                            prose-blockquote:border-l-4 prose-blockquote:border-amber-500
                            prose-blockquote:bg-slate-50 dark:prose-blockquote:bg-zinc-800/50
                            prose-blockquote:py-3 prose-blockquote:px-5 prose-blockquote:not-italic prose-blockquote:text-slate-900
                            prose-ul:list-disc prose-ul:pl-6
                            prose-ol:list-decimal prose-ol:pl-6
                            prose-li:text-slate-800 dark:prose-li:text-zinc-300
                            prose-table:w-full prose-th:bg-slate-100 dark:prose-th:bg-zinc-800
                            prose-strong:text-slate-900 dark:prose-strong:text-white
                            dark:prose-invert">
                    {!! $article->konten !!}
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tombol bawah ────────────────────────────────────────────────── --}}
    <div class="flex items-center justify-between gap-3 pb-4">
        <a href="{{ route('admin.articles.index', ['tab' => in_array($article->status, ['pending','revision']) ? 'pending' : ($article->status === 'published' ? 'published' : 'others')]) }}"
           class="inline-flex items-center gap-2 py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs font-mono uppercase tracking-wider transition-colors">
            ← Kembali
        </a>
        @if($article->status === 'published')
            <a href="{{ route('frontend.article.show', $article->slug) }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs font-mono uppercase tracking-wider transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                </svg>
                Lihat di Website
            </a>
        @endif
    </div>

</div>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: Minta Revisi                                                     --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<x-app-modal id="modal-revision" maxWidth="lg" iconColor="indigo"
    title="Minta Revisi"
    description="Berikan catatan revisi yang jelas agar penulis tahu apa yang perlu diperbaiki."
    icon='<svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>'>

    <form id="revision-form" action="{{ route('admin.articles.revision', $article->slug) }}" method="POST">
        @csrf
        <div class="space-y-4">

            @php $revCount = $article->revisions->count(); @endphp
            @if($revCount > 0)
            <div class="flex items-center gap-2 p-3 bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-800/40">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
                <p class="text-xs text-blue-800 dark:text-blue-300">
                    Ini akan menjadi <strong>Revisi #{{ $revCount + 1 }}</strong>. Artikel sebelumnya sudah direvisi {{ $revCount }}x.
                </p>
            </div>
            @endif

            <div>
                <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400 mb-2">
                    Catatan Revisi <span class="text-rose-500">*</span>
                </label>
                <textarea name="revision_notes" rows="6" maxlength="2000" required
                          placeholder="Contoh: Bagian pendahuluan perlu diperjelas. Tambahkan referensi untuk data yang dicantumkan. Perbaiki struktur paragraf di bagian 2..."
                          class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:border-[#4f45b2] focus:ring-2 focus:ring-[#4f45b2]/20 resize-none">{{ old('revision_notes') }}</textarea>
                <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1.5 font-mono">
                    Maksimal 2000 karakter. Semakin spesifik catatan, semakin mudah penulis memperbaiki.
                </p>
            </div>
        </div>
    </form>

    <x-slot name="footer">
        <button type="button" onclick="AppModal.close('modal-revision')" class="modal-btn-cancel">
            Batal
        </button>
        <button type="button"
                onclick="AppPopup.info({
                    title: 'Kirim Permintaan Revisi?',
                    description: 'Artikel akan dikembalikan ke penulis dengan catatan revisi ini.',
                    confirmText: 'Ya, Kirim',
                    cancelText: 'Batal',
                    onConfirm: function() { document.getElementById('revision-form').submit(); }
                })"
                class="modal-btn-primary">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
            </svg>
            Kirim Revisi
        </button>
    </x-slot>
</x-app-modal>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: Tolak Artikel                                                    --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<x-app-modal id="modal-reject" maxWidth="lg" iconColor="red"
    title="Tolak Artikel"
    description="Artikel akan dikembalikan ke status draft. Penulis tidak dapat submit ulang tanpa mengedit terlebih dahulu."
    icon='<svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>'>

    <form id="reject-form" action="{{ route('admin.articles.reject', $article->slug) }}" method="POST">
        @csrf
        <div class="space-y-4">

            <div class="flex items-start gap-3 p-3 bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800/40">
                <svg class="w-4 h-4 text-rose-600 dark:text-rose-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                <p class="text-xs text-rose-800 dark:text-rose-300">
                    Penolakan bersifat final dan berbeda dari revisi. Gunakan <strong>Minta Revisi</strong> jika konten hanya perlu diperbaiki.
                </p>
            </div>

            <div>
                <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400 mb-2">
                    Alasan Penolakan <span class="text-rose-500">*</span>
                </label>
                <textarea name="rejection_reason" rows="5" maxlength="1000" required
                          placeholder="Jelaskan alasan penolakan secara jelas dan konstruktif..."
                          class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-300/20 resize-none">{{ old('rejection_reason') }}</textarea>
                <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1.5 font-mono">Maksimal 1000 karakter.</p>
            </div>
        </div>
    </form>

    <x-slot name="footer">
        <button type="button" onclick="AppModal.close('modal-reject')" class="modal-btn-cancel">
            Batal
        </button>
        <button type="button"
                onclick="AppPopup.confirm({
                    title: 'Tolak Artikel?',
                    description: 'Artikel akan dikembalikan ke draft dan penulis akan mendapat alasan penolakan.',
                    confirmText: 'Ya, Tolak',
                    cancelText: 'Batal',
                    onConfirm: function() { document.getElementById('reject-form').submit(); }
                })"
                class="modal-btn-danger">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Tolak Artikel
        </button>
    </x-slot>
</x-app-modal>

@endsection
