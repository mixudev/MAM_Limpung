<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>MyApp</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/3.4.1/tailwind.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --bg: #0d0f14;
      --surface: #161920;
      --surface2: #1e2129;
      --accent: #5b6af0;
      --accent2: #a78bfa;
      --text: #f1f3f9;
      --muted: #6b7280;
      --card: #1a1d26;
    }

    * { -webkit-tap-highlight-color: transparent; box-sizing: border-box; }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--bg);
      color: var(--text);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 0;
    }

    .phone-wrap {
      width: 390px;
      min-height: 844px;
      background: var(--bg);
      border-radius: 48px;
      overflow: hidden;
      position: relative;
      box-shadow:
        0 0 0 1px rgba(255,255,255,0.06),
        0 30px 80px rgba(0,0,0,0.7),
        0 0 60px rgba(91,106,240,0.08);
    }

    @media (max-width: 430px) {
      body { align-items: flex-start; background: var(--bg); }
      .phone-wrap {
        width: 100vw; min-height: 100vh; border-radius: 0;
        box-shadow: none;
      }
    }

    /* Status bar */
    .status-bar {
      height: 44px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 28px;
      font-size: 12px;
      font-weight: 600;
      color: var(--text);
      font-family: 'Sora', sans-serif;
    }

    /* ─── WELCOME SCREEN ─── */
    .welcome-screen {
      padding: 0 24px 100px;
      overflow-y: auto;
      height: calc(844px - 44px);
    }

    /* Greeting */
    .greeting-section {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 20px;
      margin-bottom: 28px;
    }

    .greeting-text h2 {
      font-family: 'Sora', sans-serif;
      font-size: 13px;
      color: var(--muted);
      font-weight: 400;
      margin: 0 0 4px;
      letter-spacing: 0.03em;
    }

    .greeting-text h1 {
      font-family: 'Sora', sans-serif;
      font-size: 22px;
      font-weight: 700;
      color: var(--text);
      margin: 0;
    }

    .avatar {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Sora', sans-serif;
      font-weight: 700;
      font-size: 16px;
      color: #fff;
      flex-shrink: 0;
      box-shadow: 0 4px 16px rgba(91,106,240,0.4);
    }

    /* Hero banner */
    .hero-banner {
      background: linear-gradient(135deg, #2a2f6e 0%, #1a1d38 60%, #0d0f14 100%);
      border-radius: 24px;
      padding: 28px 24px;
      position: relative;
      overflow: hidden;
      margin-bottom: 28px;
      border: 1px solid rgba(91,106,240,0.2);
    }

    .hero-banner::before {
      content: '';
      position: absolute;
      top: -30px; right: -30px;
      width: 140px; height: 140px;
      background: radial-gradient(circle, rgba(91,106,240,0.35) 0%, transparent 70%);
      border-radius: 50%;
    }

    .hero-banner::after {
      content: '';
      position: absolute;
      bottom: -20px; right: 40px;
      width: 80px; height: 80px;
      background: radial-gradient(circle, rgba(167,139,250,0.2) 0%, transparent 70%);
      border-radius: 50%;
    }

    .hero-banner .badge {
      display: inline-block;
      background: rgba(91,106,240,0.25);
      border: 1px solid rgba(91,106,240,0.4);
      color: #a0aaff;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      padding: 4px 10px;
      border-radius: 20px;
      margin-bottom: 12px;
      font-family: 'Sora', sans-serif;
    }

    .hero-banner h3 {
      font-family: 'Sora', sans-serif;
      font-size: 20px;
      font-weight: 700;
      color: #fff;
      margin: 0 0 8px;
      line-height: 1.3;
      position: relative;
      z-index: 1;
    }

    .hero-banner p {
      font-size: 13px;
      color: rgba(255,255,255,0.55);
      margin: 0 0 18px;
      line-height: 1.5;
      position: relative;
      z-index: 1;
    }

    .hero-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      color: #fff;
      font-family: 'Sora', sans-serif;
      font-size: 13px;
      font-weight: 600;
      padding: 10px 18px;
      border-radius: 12px;
      border: none;
      cursor: pointer;
      position: relative;
      z-index: 1;
      box-shadow: 0 4px 20px rgba(91,106,240,0.4);
      transition: transform 0.15s, box-shadow 0.15s;
    }

    .hero-btn:active { transform: scale(0.96); }

    /* Section title */
    .section-title {
      font-family: 'Sora', sans-serif;
      font-size: 15px;
      font-weight: 700;
      color: var(--text);
      margin: 0 0 14px;
    }

    /* Quick access grid */
    .quick-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      margin-bottom: 28px;
    }

    .quick-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      transition: transform 0.15s;
    }

    .quick-item:active { transform: scale(0.92); }

    .quick-icon {
      width: 56px;
      height: 56px;
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      border: 1px solid rgba(255,255,255,0.06);
    }

    .quick-item span {
      font-size: 11px;
      color: var(--muted);
      font-weight: 500;
      text-align: center;
    }

    /* Activity cards */
    .activity-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-bottom: 28px;
    }

    .activity-card {
      background: var(--card);
      border: 1px solid rgba(255,255,255,0.05);
      border-radius: 18px;
      padding: 16px 18px;
      display: flex;
      align-items: center;
      gap: 14px;
      cursor: pointer;
      transition: background 0.15s;
    }

    .activity-card:active { background: var(--surface2); }

    .activity-icon {
      width: 44px;
      height: 44px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
    }

    .activity-info { flex: 1; }

    .activity-info h4 {
      font-family: 'Sora', sans-serif;
      font-size: 14px;
      font-weight: 600;
      color: var(--text);
      margin: 0 0 3px;
    }

    .activity-info p {
      font-size: 12px;
      color: var(--muted);
      margin: 0;
    }

    .activity-badge {
      font-size: 11px;
      font-weight: 700;
      font-family: 'Sora', sans-serif;
      padding: 3px 9px;
      border-radius: 20px;
    }

    /* ─── BOTTOM NAV ─── */
    .bottom-nav {
      position: absolute;
      bottom: 0; left: 0; right: 0;
      height: 80px;
      background: var(--surface);
      border-top: 1px solid rgba(255,255,255,0.06);
      display: flex;
      align-items: center;
      justify-content: space-around;
      padding: 0 8px 8px;
      backdrop-filter: blur(20px);
    }

    .nav-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
      padding: 8px 16px;
      cursor: pointer;
      position: relative;
      border-radius: 16px;
      transition: background 0.2s;
      min-width: 64px;
    }

    .nav-item:active { background: rgba(255,255,255,0.05); }

    .nav-item.active .nav-icon {
      color: var(--accent);
    }

    .nav-item.active .nav-label {
      color: var(--accent);
    }

    .nav-item.active::before {
      content: '';
      position: absolute;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 32px; height: 2px;
      background: linear-gradient(90deg, var(--accent), var(--accent2));
      border-radius: 0 0 4px 4px;
    }

    .nav-icon {
      font-size: 22px;
      color: var(--muted);
      transition: color 0.2s, transform 0.2s;
      line-height: 1;
    }

    .nav-item:active .nav-icon { transform: scale(0.88); }

    .nav-label {
      font-family: 'Sora', sans-serif;
      font-size: 10px;
      font-weight: 600;
      color: var(--muted);
      letter-spacing: 0.02em;
      transition: color 0.2s;
    }

    /* Notification dot */
    .notif-dot {
      position: absolute;
      top: 6px;
      right: 10px;
      width: 8px;
      height: 8px;
      background: #f87171;
      border-radius: 50%;
      border: 2px solid var(--surface);
    }

    /* Scrollbar hide */
    .welcome-screen::-webkit-scrollbar { display: none; }
    .welcome-screen { -ms-overflow-style: none; scrollbar-width: none; }

    /* Fade-in animation */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .greeting-section { animation: fadeUp 0.5s ease both; }
    .hero-banner       { animation: fadeUp 0.5s 0.08s ease both; }
    .quick-grid        { animation: fadeUp 0.5s 0.14s ease both; }
    .activity-list     { animation: fadeUp 0.5s 0.20s ease both; }
  </style>
</head>
<body>

<div class="phone-wrap">

  <!-- Status Bar -->
  <div class="status-bar">
    <span>9:41</span>
    <div style="display:flex;align-items:center;gap:6px;">
      <svg width="16" height="11" viewBox="0 0 16 11" fill="none"><rect x="0" y="4" width="3" height="7" rx="1" fill="currentColor" opacity="0.4"/><rect x="4.5" y="2.5" width="3" height="8.5" rx="1" fill="currentColor" opacity="0.6"/><rect x="9" y="0.5" width="3" height="10.5" rx="1" fill="currentColor"/><rect x="13.5" y="0" width="2" height="11" rx="1" fill="currentColor" opacity="0.3"/></svg>
      <svg width="15" height="11" viewBox="0 0 15 11" fill="none"><path d="M7.5 2.5C9.5 2.5 11.3 3.3 12.6 4.6L14 3.2C12.3 1.5 10 0.5 7.5 0.5C5 0.5 2.7 1.5 1 3.2L2.4 4.6C3.7 3.3 5.5 2.5 7.5 2.5Z" fill="currentColor"/><path d="M7.5 5.5C8.8 5.5 10 6 10.9 6.8L12.3 5.4C11 4.2 9.3 3.5 7.5 3.5C5.7 3.5 4 4.2 2.7 5.4L4.1 6.8C5 6 6.2 5.5 7.5 5.5Z" fill="currentColor" opacity="0.7"/><circle cx="7.5" cy="9.5" r="1.5" fill="currentColor"/></svg>
      <svg width="25" height="12" viewBox="0 0 25 12" fill="none"><rect x="0.5" y="0.5" width="21" height="11" rx="3.5" stroke="currentColor" stroke-opacity="0.35"/><rect x="2" y="2" width="17" height="8" rx="2" fill="currentColor"/><path d="M23 4.5V7.5C23.8 7.2 24.5 6.4 24.5 6C24.5 5.6 23.8 4.8 23 4.5Z" fill="currentColor" opacity="0.4"/></svg>
    </div>
  </div>

  <!-- Welcome / Home Screen -->
  <div class="welcome-screen" id="screen-home">

    <!-- Greeting -->
    <div class="greeting-section">
      <div class="greeting-text">
        <h2>Selamat Pagi 👋</h2>
        <h1>Halo, Budi!</h1>
      </div>
      <div class="avatar">B</div>
    </div>

    <!-- Hero Banner -->
    <div class="hero-banner">
      <div class="badge">✦ Fitur Baru</div>
      <h3>Kelola semua aktivitas dalam satu tempat</h3>
      <p>Nikmati pengalaman yang lebih mudah dan cepat bersama MyApp.</p>
      <button class="hero-btn">
        Mulai Sekarang
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </button>
    </div>

    <!-- Quick Access -->
    <p class="section-title">Akses Cepat</p>
    <div class="quick-grid">
      <div class="quick-item">
        <div class="quick-icon" style="background:rgba(91,106,240,0.15);">💼</div>
        <span>Tugas</span>
      </div>
      <div class="quick-item">
        <div class="quick-icon" style="background:rgba(167,139,250,0.15);">📊</div>
        <span>Laporan</span>
      </div>
      <div class="quick-item">
        <div class="quick-icon" style="background:rgba(52,211,153,0.15);">💬</div>
        <span>Pesan</span>
      </div>
      <div class="quick-item">
        <div class="quick-icon" style="background:rgba(251,191,36,0.15);">⭐</div>
        <span>Favorit</span>
      </div>
    </div>

    <!-- Recent Activity -->
    <p class="section-title">Aktivitas Terbaru</p>
    <div class="activity-list">
      <div class="activity-card">
        <div class="activity-icon" style="background:rgba(91,106,240,0.15);">📁</div>
        <div class="activity-info">
          <h4>Proyek Desain UI</h4>
          <p>Diperbarui 2 jam lalu</p>
        </div>
        <span class="activity-badge" style="background:rgba(91,106,240,0.15);color:#818cf8;">Aktif</span>
      </div>
      <div class="activity-card">
        <div class="activity-icon" style="background:rgba(52,211,153,0.15);">✅</div>
        <div class="activity-info">
          <h4>Meeting Tim</h4>
          <p>Selesai · Hari ini 10:00</p>
        </div>
        <span class="activity-badge" style="background:rgba(52,211,153,0.12);color:#34d399;">Selesai</span>
      </div>
      <div class="activity-card">
        <div class="activity-icon" style="background:rgba(251,191,36,0.15);">⚡</div>
        <div class="activity-info">
          <h4>Deadline Laporan</h4>
          <p>Besok · 17:00 WIB</p>
        </div>
        <span class="activity-badge" style="background:rgba(251,191,36,0.12);color:#fbbf24;">Segera</span>
      </div>
    </div>

  </div><!-- end welcome-screen -->

  <!-- Bottom Navigation -->
  <nav class="bottom-nav">
    <div class="nav-item active" onclick="setActive(this)">
      <div class="nav-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
      </div>
      <span class="nav-label">Beranda</span>
    </div>

    <div class="nav-item" onclick="setActive(this)">
      <div class="nav-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      </div>
      <span class="nav-label">Jelajahi</span>
    </div>

    <div class="nav-item" onclick="setActive(this)" style="position:relative;">
      <div class="nav-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      </div>
      <div class="notif-dot"></div>
      <span class="nav-label">Notifikasi</span>
    </div>

    <div class="nav-item" onclick="setActive(this)">
      <div class="nav-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      </div>
      <span class="nav-label">Pesan</span>
    </div>

    <div class="nav-item" onclick="setActive(this)">
      <div class="nav-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      </div>
      <span class="nav-label">Profil</span>
    </div>
  </nav>

</div><!-- end phone-wrap -->

<script>
  function setActive(el) {
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    el.classList.add('active');
  }
</script>

</body>
</html>