<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SMAN 1 Nusantara</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: ['Poppins', 'sans-serif'],
            body: ['Inter', 'sans-serif'],
          },
          keyframes: {
            fadein: {
              '0%': { opacity: '0', transform: 'translateY(12px)' },
              '100%': { opacity: '1', transform: 'translateY(0)' },
            },
          },
          animation: {
            'fadein-1': 'fadein 0.4s ease 0.05s both',
            'fadein-2': 'fadein 0.4s ease 0.15s both',
            'fadein-3': 'fadein 0.4s ease 0.25s both',
            'fadein-4': 'fadein 0.4s ease 0.35s both',
            'fadein-5': 'fadein 0.4s ease 0.45s both',
          },
        }
      }
    }
  </script>
</head>
<body class="font-body min-h-screen bg-stone-50 flex flex-col items-center px-4 py-12">

  <!-- HEADER -->
  <div class="w-full max-w-sm animate-fadein-1">
    <div class="border-2 border-black bg-yellow-400 px-6 py-8 text-center shadow-[4px_4px_0px_0px_#000]">
      <!-- Logo -->
      <div class="mx-auto mb-5 w-16 h-16  flex items-center justify-center">
        <img src="{{ asset('assets/img/logo.png') }}" alt="">
      </div>
      <h1 class="font-display text-2xl font-extrabold text-black uppercase leading-tight tracking-tight">{{ $siteSettings->school_name ?? '' }}</h1>
      <p class="mt-2 text-xs font-medium text-black/60 uppercase tracking-widest">Madrasah Aliyah Muhammadiyah</p>
    </div>
  </div>

  <!-- PPDB — HIGHLIGHT UTAMA -->
  <div class="w-full max-w-sm mt-4 animate-fadein-2">
    <a href="#" class="block border-2 border-black bg-black px-6 py-5 shadow-[4px_4px_0px_0px_#ca8a04] transition-all hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold text-yellow-400 uppercase tracking-widest mb-1">Pendaftaran Siswa Baru</p>
          <p class="font-display text-xl font-bold text-white">PPDB 2026/2027</p>
        </div>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#facc15" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </div>
    </a>
  </div>

  <!-- LINK UTAMA -->
  <div class="w-full max-w-sm mt-4 animate-fadein-3 space-y-3">

    <a href="#" class="flex items-center justify-between border-2 border-black bg-white px-5 py-4 shadow-[4px_4px_0px_0px_#000] transition-all hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none group">
      <div class="flex items-center gap-4">
        <div class="w-9 h-9  bg-blue-500 flex items-center justify-center shrink-0">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="0" ry="0"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
        </div>
        <span class="font-semibold text-sm text-black">Jadwal Pelajaran</span>
      </div>
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-40 group-hover:opacity-100 transition-opacity">
        <path d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </a>

    <a href="#" class="flex items-center justify-between border-2 border-black bg-white px-5 py-4 shadow-[4px_4px_0px_0px_#000] transition-all hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none group">
      <div class="flex items-center gap-4">
        <div class="w-9 h-9  bg-emerald-500 flex items-center justify-center shrink-0">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
          </svg>
        </div>
        <span class="font-semibold text-sm text-black">E-Learning</span>
      </div>
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-40 group-hover:opacity-100 transition-opacity">
        <path d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </a>

    <a href="#" class="flex items-center justify-between border-2 border-black bg-white px-5 py-4 shadow-[4px_4px_0px_0px_#000] transition-all hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none group">
      <div class="flex items-center gap-4">
        <div class="w-9 h-9  bg-violet-500 flex items-center justify-center shrink-0">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
          </svg>
        </div>
        <span class="font-semibold text-sm text-black">Rapor & Nilai</span>
      </div>
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-40 group-hover:opacity-100 transition-opacity">
        <path d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </a>

    <a href="#" class="flex items-center justify-between border-2 border-black bg-white px-5 py-4 shadow-[4px_4px_0px_0px_#000] transition-all hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none group">
      <div class="flex items-center gap-4">
        <div class="w-9 h-9  bg-rose-500 flex items-center justify-center shrink-0">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
        </div>
        <span class="font-semibold text-sm text-black">Pengumuman</span>
      </div>
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-40 group-hover:opacity-100 transition-opacity">
        <path d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </a>

    <a href="#" class="flex items-center justify-between border-2 border-black bg-white px-5 py-4 shadow-[4px_4px_0px_0px_#000] transition-all hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none group">
      <div class="flex items-center gap-4">
        <div class="w-9 h-9  bg-amber-500 flex items-center justify-center shrink-0">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
          </svg>
        </div>
        <span class="font-semibold text-sm text-black">Prestasi & Ekskul</span>
      </div>
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-40 group-hover:opacity-100 transition-opacity">
        <path d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </a>

  </div>

  <!-- SOSMED -->
  <div class="w-full max-w-sm mt-4 animate-fadein-4">
    <div class="border-2 border-black bg-white shadow-[4px_4px_0px_0px_#000] p-4">
      <p class="text-xs font-semibold text-black/40 uppercase tracking-widest mb-3">Ikuti Kami</p>
      <div class="grid grid-cols-4 gap-2">

        <!-- Instagram -->
        <a href="#" class="flex flex-col items-center gap-1.5 border-2 border-black py-3 hover:bg-black hover:text-white transition-colors group">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-black group-hover:text-white">
            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
          </svg>
          <span class="text-[10px] font-semibold uppercase tracking-wide">IG</span>
        </a>

        <!-- YouTube -->
        <a href="#" class="flex flex-col items-center gap-1.5 border-2 border-black py-3 hover:bg-black hover:text-white transition-colors group">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-black group-hover:text-white">
            <path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/>
          </svg>
          <span class="text-[10px] font-semibold uppercase tracking-wide">YT</span>
        </a>

        <!-- Twitter/X -->
        <a href="#" class="flex flex-col items-center gap-1.5 border-2 border-black py-3 hover:bg-black hover:text-white transition-colors group">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" class="text-black group-hover:text-white">
            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.261 5.632 5.903-5.632Zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
          </svg>
          <span class="text-[10px] font-semibold uppercase tracking-wide">X</span>
        </a>

        <!-- WhatsApp -->
        <a href="#" class="flex flex-col items-center gap-1.5 border-2 border-black py-3 hover:bg-black hover:text-white transition-colors group">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-black group-hover:text-white">
            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
          </svg>
          <span class="text-[10px] font-semibold uppercase tracking-wide">WA</span>
        </a>

      </div>
    </div>
  </div>

  <!-- KONTAK -->
  <div class="w-full max-w-sm mt-4 animate-fadein-5">
    <div class="border-2 border-black bg-white px-5 py-4 shadow-[4px_4px_0px_0px_#000]">
      <div class="divide-y-2 divide-black/10 space-y-0">
        <div class="flex items-center gap-3 py-3">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 opacity-50">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.77 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
          </svg>
          <span class="text-sm text-black/70">(021) 123-4567</span>
        </div>
        <div class="flex items-center gap-3 py-3">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 opacity-50">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
          </svg>
          <span class="text-sm text-black/70">info@mamlimpung.sch.id</span>
        </div>
        <div class="flex items-center gap-3 py-3">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 opacity-50">
            <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
          </svg>
          <span class="text-sm text-black/70">mamlimpung.sch.id</span>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <p class="mt-10 text-xs text-black/30 text-center animate-fadein-5">
    © 2026 MAM Limpung
  </p>

</body>
</html>
