@extends('layouts.app')

@section('content')
    <!-- HERO -->
    <section class="bg-indigo-900 text-white relative overflow-hidden">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="max-w-6xl mx-auto px-5 py-14 relative">
            <p class="text-indigo-300 text-xs font-semibold tracking-widest uppercase mb-3">Transparansi Institusi</p>
            <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight mb-4">
                Struktur Tenaga Pendidik<br class="hidden sm:block" />
                <span class="text-amber-400">&amp; Kependidikan</span>
            </h1>
            <p class="text-indigo-200 text-sm sm:text-base max-w-xl leading-relaxed">
                Kenali seluruh guru dan staf yang berdedikasi mendidik dan melayani di sekolah kita. Klik kartu untuk
                melihat profil lengkap.
            </p>
            <!-- search -->

            <!-- stats -->
            <div class="mt-6 flex flex-wrap gap-4">
                <div class="text-center">
                    <div class="text-2xl font-black text-white">16</div>
                    <div class="text-xs text-indigo-300">Total Pegawai</div>
                </div>
                <div class="w-px bg-indigo-700 self-stretch"></div>
                <div class="text-center">
                    <div class="text-2xl font-black text-amber-400">12</div>
                    <div class="text-xs text-indigo-300">Guru</div>
                </div>
                <div class="w-px bg-indigo-700 self-stretch"></div>
                <div class="text-center">
                    <div class="text-2xl font-black text-emerald-400">4</div>
                    <div class="text-xs text-indigo-300">Staf TU</div>
                </div>
            </div>
        </div>
    </section>

    <section class="w-full px-3">
        <div class="mt-8 mx-auto flex gap-3 max-w-4xl">

            <div class="relative flex-1 ">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-indigo-400" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
                <input id="searchInput" type="text" placeholder="Cari nama atau jabatan..."
                    class="w-full pl-9 pr-4 py-3 rounded-xl bg-white border border-indigo-700 text-indigo-600 placeholder-slate-500 text-sm" />
            </div>
            <button id="clearBtn" onclick="clearSearch()"
                class="hidden px-4 py-3 bg-amber-500 hover:bg-amber-400 text-amber-900 font-bold text-sm rounded-xl transition-colors">
                Hapus
            </button>
        </div>
    </section>

    {{-- FILTER & GRID PEGAWAI --}}
    <section class="max-w-6xl mx-auto px-5 py-10">

        {{-- Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4" id="staffGrid">
            @foreach ($pegawai as $p)
                @php
                    $isGuru = $p->tipe === 'guru';
                    $avatarBg = $isGuru ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800';
                    $badgeBg = $isGuru ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700';
                    $initials = collect(explode(' ', preg_replace('/[^a-zA-Z\s]/', '', $p->nama)))
                        ->filter()
                        ->take(2)
                        ->map(fn($w) => strtoupper($w[0]))
                        ->implode('');
                @endphp
                <div class="staff-card bg-white border border-gray-300 shadow-lg rounded-2xl p-5 flex flex-col items-center text-center gap-3 relative hover:-translate-y-1 hover:shadow-md transition-all cursor-pointer"
                    data-tipe="{{ $p->tipe }}" data-nama="{{ strtolower($p->nama) }}"
                    data-jabatan="{{ strtolower($p->jabatan) }}"
                    onclick="window.location='{{ route('frontend.pegawai.show', $p->id) }}'">

                    {{-- Status dot --}}
                    {{-- <span class="absolute top-3 right-3 w-2 h-2 rounded-full bg-emerald-400" title="Aktif"></span> --}}

                    {{-- Avatar --}}
                    <div
                        class="w-14 h-14 rounded-full {{ $avatarBg }} flex items-center justify-content-center font-semibold text-lg mx-auto justify-center text-center">
                        {{ $initials }}
                    </div>

                    {{-- Info --}}
                    <div>
                        <p class="font-semibold text-gray-800 text-sm leading-snug">{{ $p->nama }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $p->jabatan }}</p>
                        <p class="text-[10px] text-gray-400 font-mono mt-1">NIP {{ $p->nip }}</p>
                    </div>

                    {{-- Badge tipe --}}
                    <span class="text-[11px] font-medium px-3 py-0.5 rounded-full {{ $badgeBg }}">
                        {{ $isGuru ? 'Guru' : 'Staf TU' }}
                    </span>

                    {{-- Mata pelajaran / bidang --}}
                    <span class="text-[11px] text-gray-500 bg-gray-50 px-2 py-1 rounded-lg mt-auto">
                        <svg class="inline w-3 h-3 mr-0.5 -mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 6V4m0 2a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m-6 8a2 2 0 1 0 0-4m0 4a2 2 0 1 1 0-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 1 0 0-4m0 4a2 2 0 1 1 0-4m0 4v2m0-6V4" />
                        </svg>
                        {{ $p->mapel ?? $p->bidang }}
                    </span>
                </div>
            @endforeach

            {{-- Empty state --}}
            <div id="emptyState" class="col-span-full hidden text-center py-16 text-gray-400 text-sm">
                Tidak ada pegawai yang sesuai.
            </div>
        </div>
    </section>

    <script>
        let filterAktif = 'semua';

        function setFilter(tipe, btn) {
            filterAktif = tipe;
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('bg-indigo-700', 'text-white', 'active');
                b.classList.add('bg-white', 'text-gray-600');
            });
            btn.classList.add('bg-indigo-700', 'text-white', 'active');
            btn.classList.remove('bg-white', 'text-gray-600');
            filterCards();
        }

        function filterCards() {
            const query = document.getElementById('searchInput')?.value.toLowerCase() ?? '';
            let visible = 0;
            document.querySelectorAll('.staff-card').forEach(card => {
                const matchTipe = filterAktif === 'semua' || card.dataset.tipe === filterAktif;
                const matchSearch = !query || card.dataset.nama.includes(query) || card.dataset.jabatan.includes(
                    query);
                const show = matchTipe && matchSearch;
                card.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            document.getElementById('emptyState').classList.toggle('hidden', visible > 0);
        }

        // Sambungkan ke search input di hero
        document.getElementById('searchInput')?.addEventListener('input', filterCards);

        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('clearBtn').classList.add('hidden');
            filterCards();
        }

        document.getElementById('searchInput')?.addEventListener('input', function() {
            document.getElementById('clearBtn').classList.toggle('hidden', !this.value);
        });
    </script>
@endsection
