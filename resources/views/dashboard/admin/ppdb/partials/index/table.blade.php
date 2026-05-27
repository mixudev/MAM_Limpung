<!-- Table View -->
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 dark:bg-zinc-800/40 border-b border-slate-100 dark:border-zinc-800/80">
                <!-- <th class="px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Nomor Reg</th> -->
                <th class="px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Nama Lengkap / NISN</th>
                <th class="px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Sekolah Asal</th>
                <!-- <th class="px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Status</th> -->
                <!-- <th class="px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Tanggal Daftar</th> -->
                <th class="px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/50">
            @forelse($applicants as $student)
                <tr class="hover:bg-slate-50/40 dark:hover:bg-zinc-800/20 transition-all border-l-4 
                    {{ $student->status === 'pending' ? 'border-gray-500' : ($student->status === 'diterima' ? 'border-emerald-500' : 'border-red-500') }}">
                    <!-- <td class="px-6 py-4 text-sm font-mono font-bold text-[#4f45b2] dark:text-[#8c84c8] whitespace-nowrap">
                        {{ $student->nomor_registrasi }}
                    </td> -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ $student->nama_lengkap }}</div>
                        <div class="text-xs text-slate-400 dark:text-zinc-500 font-mono mt-0.5">NISN: {{ $student->nisn }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-zinc-400 whitespace-nowrap">
                        {{ $student->sekolah_asal }}
                    </td>
                    <!-- <td class="px-6 py-4 whitespace-nowrap">
                        @if($student->status === 'diterima')
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-none bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30">
                                Terverifikasi
                            </span>
                        @elseif($student->status === 'ditolak')
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-none bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/30" title="{{ $student->catatan_admin }}">
                                Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-none bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30">
                                Menunggu
                            </span>
                        @endif
                    </td> -->
                    <!-- <td class="px-6 py-4 text-xs font-mono text-slate-500 dark:text-zinc-500 whitespace-nowrap">
                        {{ $student->submitted_at?->format('d M Y H:i') ?? '-' }}
                    </td> -->
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <div class="inline-flex items-center gap-1.5">
                            <!-- WA Button konfirmasi ke murid -->
                            @php
                                $nomor_hp = normalize_phone_id($student->nomor_hp);
                                $pesan_wa = match($student->status) {
                                    'diterima' => "Assalamu'alaikum {$student->nama_lengkap}, Selamat! Anda diterima di sekolah kami. Silakan hubungi bagian administrasi untuk informasi selanjutnya.",
                                    'ditolak' => "Assalamu'alaikum {$student->nama_lengkap}, Terima kasih telah mendaftar di sekolah kami. Saat ini, pendaftaran Anda belum dapat kami terima. Semoga kesempatan ini menjadi pembelajaran.",
                                    default => "Assalamu'alaikum {$student->nama_lengkap}, Kami ingin memberikan informasi terkait status pendaftaran Anda. Silakan menunggu pemberitahuan lebih lanjut dari pihak sekolah.",
                                };
                            @endphp

                            <a
                                href="https://wa.me/{{ $nomor_hp }}?text={{ urlencode($pesan_wa) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                title="Kirim Pesan WhatsApp"
                                class="px-2 py-1.5 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-zinc-400 hover:text-slate-950 dark:hover:text-white rounded-none transition-all active:scale-[.95]"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </a>

                            <!-- Cetak Action -->
                            <button type="button" onclick="printStudent('{{ $student->uuid }}')" title="Cetak Laporan Biodata"
                                class="px-2 py-1.5 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-zinc-400 hover:text-slate-950 dark:hover:text-white rounded-none transition-all active:scale-[.95]">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                            </button>

                            <!-- Detail Action -->
                            <button type="button" onclick="openDetails('{{ $student->uuid }}')" 
                                class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all">
                                Detail
                            </button>

                            @if($student->status === 'pending')
                                <!-- Verify Action Form -->
                                <form action="{{ route('admin.ppdb.verify', $student) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" onclick="confirmVerification(event, '{{ $student->nama_lengkap }}')"
                                        class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-none transition-all active:scale-[.98]">
                                        Verifikasi
                                    </button>
                                </form>

                                <!-- Reject Action Modal Trigger -->
                                <button type="button" onclick="openRejectionModal('{{ $student->uuid }}', '{{ $student->nama_lengkap }}')"
                                    class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white font-bold text-xs rounded-none transition-all active:scale-[.98]">
                                    Tolak
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-slate-400 dark:text-zinc-500 text-sm">
                        Tidak ada data calon siswa untuk filter ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($applicants->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/35 dark:bg-zinc-900/10">
        {{ $applicants->links() }}
    </div>
@endif
