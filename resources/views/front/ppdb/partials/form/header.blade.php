@php
    $activeAcademicYear = \App\Models\AcademicYear::where('is_active', true)->first();
    $tahunAjaran = $activeAcademicYear?->year ?? (int) date('Y');
@endphp
<div class="text-center mb-8">
    <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-2">Formulir Pendaftaran Siswa Baru</h1>
    <span class="inline-block bg-cyan-100 text-cyan-800 text-[10px] font-bold px-3 py-1 rounded-sm uppercase tracking-widest font-mono mb-2">
        Tahun Pelajaran {{ $tahunAjaran }}/{{ $tahunAjaran + 1 }}
    </span>
    <p class="text-gray-600 text-sm sm:text-base">Silakan lengkapi data diri Anda secara jujur, benar, dan lengkap</p>
</div>
