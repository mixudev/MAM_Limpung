@extends('mobile_apps.layouts.apps')

@section('content')
    <div class="px-5 pt-4" x-data="{ currentTab: 'semua', activeTask: null }">
        <!-- Header & Back Button -->
        <div class="flex items-center gap-3 mb-5">
            <a href="{{ route('apps.home') }}" class="w-8 h-8 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-600 shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-sora font-bold text-slate-800 text-base">Tugas & Pekerjaan Rumah</h2>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex bg-slate-100 p-1 rounded-xl mb-4">
            <button @click="currentTab = 'semua'" :class="currentTab === 'semua' ? 'bg-white text-slate-800 shadow-xs' : 'text-slate-500'"
                    class="flex-1 text-center py-2 text-xs font-bold rounded-lg transition-all">Semua</button>
            <button @click="currentTab = 'belum'" :class="currentTab === 'belum' ? 'bg-white text-slate-800 shadow-xs' : 'text-slate-500'"
                    class="flex-1 text-center py-2 text-xs font-bold rounded-lg transition-all">Belum Selesai</button>
            <button @click="currentTab = 'selesai'" :class="currentTab === 'selesai' ? 'bg-white text-slate-800 shadow-xs' : 'text-slate-500'"
                    class="flex-1 text-center py-2 text-xs font-bold rounded-lg transition-all">Selesai</button>
        </div>

        <!-- Task List -->
        <div class="space-y-3">
            @foreach($tasks as $task)
                <div x-show="currentTab === 'semua' || (currentTab === 'belum' && '{{ $task['status'] }}' === 'Belum Selesai') || (currentTab === 'selesai' && '{{ $task['status'] }}' === 'Selesai')"
                     @click="activeTask = {{ json_encode($task) }}"
                     class="bg-white border border-slate-100/80 shadow-xs rounded-2xl p-4 flex items-start gap-3 active:bg-slate-50 transition-colors cursor-pointer">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                        {{ $task['status'] === 'Selesai' ? 'bg-emerald-50 border border-emerald-100' : 'bg-amber-50 border border-amber-100' }}">
                        @if($task['status'] === 'Selesai')
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] px-2 py-0.5 rounded-full font-bold
                                {{ $task['priority'] === 'Tinggi' ? 'bg-red-50 text-red-600 border border-red-100' : ($task['priority'] === 'Sedang' ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
                                {{ $task['priority'] }}
                            </span>
                            <span class="text-[9px] font-bold uppercase tracking-wider
                                {{ $task['status'] === 'Selesai' ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $task['status'] }}
                            </span>
                        </div>
                        <h4 class="font-sora font-bold text-slate-800 text-xs mt-2">{{ $task['judul'] }}</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 font-semibold">{{ $task['mapel'] }}</p>
                        
                        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-50">
                            <span class="text-[9px] text-slate-400 font-medium">Tenggat: {{ $task['deadline'] }}</span>
                            <span class="text-[10px] text-primary-600 font-bold flex items-center gap-0.5">
                                Detail
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Task Detail Dialog / Modal (Alpine.js powered) -->
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-100 flex items-end justify-center p-0"
             x-show="activeTask !== null"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-full"
             style="display: none;">
            
            <div class="bg-white rounded-t-4xl w-full max-w-md p-6 border-t border-slate-100 shadow-2xl relative"
                 @click.away="activeTask = null">
                <!-- Close handle -->
                <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-4 cursor-pointer" @click="activeTask = null"></div>

                <div class="flex items-center justify-between mb-4">
                    <span class="text-[9px] bg-primary-50 text-primary-700 border border-primary-100 px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider"
                          x-text="activeTask ? activeTask.mapel : ''"></span>
                    <span class="text-[10px] font-bold"
                          :class="activeTask && activeTask.status === 'Selesai' ? 'text-emerald-600' : 'text-amber-600'"
                          x-text="activeTask ? activeTask.status : ''"></span>
                </div>

                <h3 class="font-sora font-bold text-slate-800 text-base" x-text="activeTask ? activeTask.judul : ''"></h3>
                <p class="text-[10px] text-slate-400 font-medium mt-1">Guru Pengampu: <span class="font-bold text-slate-600" x-text="activeTask ? activeTask.guru : ''"></span></p>

                <div class="my-5 bg-slate-50 border border-slate-100 rounded-2xl p-4">
                    <p class="text-xs font-bold text-slate-700 mb-1">Instruksi Tugas:</p>
                    <p class="text-xs text-slate-600 leading-relaxed font-semibold" x-text="activeTask ? activeTask.deskripsi : ''"></p>
                </div>

                <div class="flex items-center justify-between mb-6 text-xs border-b border-slate-50 pb-3">
                    <span class="text-slate-400 font-semibold">Tenggat Pengumpulan:</span>
                    <span class="font-bold text-rose-600" x-text="activeTask ? activeTask.deadline : ''"></span>
                </div>

                <!-- Action Button -->
                <button @click="activeTask = null; alert('Simulasi Pengunggahan Tugas: Silakan upload file jawaban melalui portal web desktop.')"
                        class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold shadow-md active:scale-98 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Kumpulkan Tugas (Unggah File)
                </button>
            </div>
        </div>
    </div>

@endsection
