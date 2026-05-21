{{--
    Komponen tombol aksi cetak/tutup untuk halaman dokumen.

    Props:
      mode  (string) — 'print'   → top-bar (untuk cetak 1 halaman)
                       'preview' → floating bottom-right (untuk pratinjau laporan)
      label (string) — teks label opsional di sebelah kiri tombol (hanya mode print)

    Slot default ($slot):
      Tombol tambahan, jika dibutuhkan.
--}}
@props([
    'mode'  => 'print',   // 'print' | 'preview'
    'label' => null,
])

@if($mode === 'preview')
    {{-- Floating controls di kanan bawah untuk pratinjau laporan --}}
    <div class="preview-controls no-print">
        <button class="action-btn secondary" onclick="window.close()">&#x2715; Tutup</button>
        <button class="action-btn primary"   onclick="window.print()">&#128438; Cetak Dokumen</button>
        {{ $slot }}
    </div>
@else
    {{-- Top bar untuk cetak dokumen tunggal --}}
    <div class="action-bar no-print">
        @if($label)
            <span class="action-bar-label">{{ $label }}</span>
        @endif
        <button class="action-btn primary"   onclick="window.print()">&#128438; Cetak Dokumen</button>
        <button class="action-btn secondary" onclick="window.close()">&#x2715; Tutup Halaman</button>
        {{ $slot }}
    </div>
@endif
