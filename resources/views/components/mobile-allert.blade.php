{{--
|--------------------------------------------------------------------------
| MobilePopup Component — Lightweight, Tailored for Mobile Apps
|--------------------------------------------------------------------------
| Simpan di: resources/views/components/mobile-allert.blade.php
| Include di layout apps: <x-mobile-allert />
--}}

@once
<style>
@keyframes mobPopupOverlayIn  { from { opacity: 0 } to { opacity: 1 } }
@keyframes mobPopupOverlayOut { from { opacity: 1 } to { opacity: 0 } }
@keyframes mobPopupBoxIn      { from { opacity: 0; transform: scale(.9) translateY(15px) } to { opacity: 1; transform: scale(1) translateY(0) } }
@keyframes mobPopupBoxOut     { from { opacity: 1; transform: scale(1) translateY(0) } to { opacity: 0; transform: scale(.9) translateY(10px) } }
@keyframes mobPopupIconPop    { from { opacity: 0; transform: scale(.5) } to { opacity: 1; transform: scale(1) } }
@keyframes mobPopupSlideUp    { from { opacity: 0; transform: translateY(8px) } to { opacity: 1; transform: translateY(0) } }

.mob-popup-overlay-in  { animation: mobPopupOverlayIn  .2s ease both }
.mob-popup-overlay-out { animation: mobPopupOverlayOut .2s ease both }
.mob-popup-box-in      { animation: mobPopupBoxIn  .3s cubic-bezier(.34,1.3,.64,1) both }
.mob-popup-box-out     { animation: mobPopupBoxOut .2s ease both }
.mob-popup-icon        { animation: mobPopupIconPop .4s cubic-bezier(.34,1.4,.64,1) both; animation-delay: .05s }
.mob-popup-title       { animation: mobPopupSlideUp .25s ease both; animation-delay: .1s }
.mob-popup-desc        { animation: mobPopupSlideUp .25s ease both; animation-delay: .15s }
.mob-popup-actions     { animation: mobPopupSlideUp .25s ease both; animation-delay: .2s }
</style>
@endonce

<div
    id="mobile-allert"
    role="dialog"
    aria-modal="true"
    aria-labelledby="mob-popup-title"
    aria-describedby="mob-popup-desc"
    class="fixed inset-0 z-99999 hidden items-center justify-center p-6"
>
    {{-- Backdrop --}}
    <div
        id="mob-popup-backdrop"
        class="absolute inset-0 bg-slate-900/40 backdrop-blur-xs"
    ></div>

    {{-- Card --}}
    <div
        id="mob-popup-box"
        class="relative w-full max-w-xs mx-auto overflow-hidden p-6 bg-white rounded-lg border border-slate-100 shadow-2xl"
    >
        {{-- Close button --}}
        <button
            id="mob-popup-close-x"
            type="button"
            aria-label="Tutup"
            class="absolute top-4 right-4 w-7 h-7 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:text-slate-600 transition-colors"
        >
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>

        {{-- Icon --}}
        <div id="mob-popup-icon" class="flex justify-center mb-4"></div>

        {{-- Title --}}
        <h3
            id="mob-popup-title"
            class="mob-popup-title text-center text-slate-800 font-sora font-bold text-sm leading-snug mb-1.5"
        ></h3>

        {{-- Description --}}
        <p
            id="mob-popup-desc"
            class="mob-popup-desc text-center text-slate-500 font-medium text-[11px] leading-relaxed mb-5 hidden"
        ></p>

        {{-- Action buttons --}}
        <div id="mob-popup-actions" class="mob-popup-actions flex gap-2"></div>
    </div>
</div>

@once
<script>
(function () {
    'use strict';

    const CONFIGS = {
        success: {
            iconBg: 'bg-emerald-50',
            iconColor: 'text-emerald-500',
            btnClass: 'bg-emerald-500 hover:bg-emerald-600 active:scale-95 text-white',
            svg: `<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"/>
                  </svg>`
        },
        error: {
            iconBg: 'bg-rose-50',
            iconColor: 'text-rose-500',
            btnClass: 'bg-rose-500 hover:bg-rose-600 active:scale-95 text-white',
            svg: `<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                  </svg>`
        },
        warning: {
            iconBg: 'bg-amber-50',
            iconColor: 'text-amber-500',
            btnClass: 'bg-amber-500 hover:bg-amber-600 active:scale-95 text-white',
            svg: `<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                  </svg>`
        },
        info: {
            iconBg: 'bg-indigo-50',
            iconColor: 'text-primary-600',
            btnClass: 'bg-primary-600 hover:bg-primary-700 active:scale-95 text-white',
            svg: `<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="8" stroke-width="3"/>
                    <line x1="12" y1="12" x2="12" y2="16"/>
                  </svg>`
        },
        confirm: {
            iconBg: 'bg-rose-50',
            iconColor: 'text-rose-500',
            btnClass: 'bg-rose-500 hover:bg-rose-600 active:scale-95 text-white',
            svg: `<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                  </svg>`
        }
    };

    const $overlay   = document.getElementById('mobile-allert');
    const $backdrop  = document.getElementById('mob-popup-backdrop');
    const $box       = document.getElementById('mob-popup-box');
    const $icon      = document.getElementById('mob-popup-icon');
    const $title     = document.getElementById('mob-popup-title');
    const $desc      = document.getElementById('mob-popup-desc');
    const $actions   = document.getElementById('mob-popup-actions');
    const $closeX    = document.getElementById('mob-popup-close-x');

    let _timer = null, _isOpen = false;

    function _open() {
        _isOpen = true;
        $overlay.classList.remove('hidden');
        $overlay.style.display = 'flex';
        void $overlay.offsetWidth;

        $overlay.classList.remove('mob-popup-overlay-out');
        $box.classList.remove('mob-popup-box-out');
        $overlay.classList.add('mob-popup-overlay-in');
        $box.classList.add('mob-popup-box-in');

        setTimeout(() => {
            const btn = $actions.querySelector('button');
            if (btn) btn.focus();
        }, 150);
    }

    function _close() {
        if (!_isOpen) return;
        _isOpen = false;
        clearTimeout(_timer);

        $overlay.classList.remove('mob-popup-overlay-in');
        $box.classList.remove('mob-popup-box-in');
        $overlay.classList.add('mob-popup-overlay-out');
        $box.classList.add('mob-popup-box-out');

        setTimeout(() => {
            if (!_isOpen) {
                $overlay.style.display = 'none';
                $overlay.classList.add('hidden');
                $overlay.classList.remove('mob-popup-overlay-out');
                $box.classList.remove('mob-popup-box-out');
            }
        }, 220);
    }

    function _makePrimary(text, btnClass, onClickFn) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = text;
        btn.className = 'flex-1 py-2.5 px-4 font-sora font-semibold text-xs rounded-xl shadow-xs transition-all duration-150 active:scale-95 cursor-pointer ' + btnClass;
        btn.addEventListener('click', onClickFn);
        return btn;
    }

    function _makeGhost(text, onClickFn) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = text;
        btn.className = 'flex-1 py-2.5 px-4 bg-slate-50 hover:bg-slate-100 text-slate-500 font-sora font-semibold text-xs rounded-xl transition-all duration-150 active:scale-95 border border-slate-150 cursor-pointer';
        btn.addEventListener('click', onClickFn);
        return btn;
    }

    function show(opts = {}) {
        const {
            type        = 'info',
            title       = '',
            description = '',
            confirmText = 'Oke, Mengerti',
            cancelText  = null,
            onConfirm   = null,
            onCancel    = null,
            autoClose   = null,
            showButton  = true,
            hideIcon    = false,
        } = opts;

        const cfg = CONFIGS[type] ?? CONFIGS.info;

        // Re-trigger text animations
        [$title, $desc, $actions].forEach(el => {
            el.style.animation = 'none';
            void el.offsetWidth;
            el.style.animation = '';
        });

        // Set content
        $title.textContent = title;
        $desc.innerHTML = description;
        $desc.classList.toggle('hidden', !description);

        // Render Icon
        $icon.innerHTML = '';
        $icon.classList.toggle('hidden', hideIcon);
        if (!hideIcon) {
            const iconEl = document.createElement('div');
            iconEl.className = `mob-popup-icon w-12 h-12 rounded-full flex items-center justify-center ${cfg.iconBg} ${cfg.iconColor}`;
            iconEl.innerHTML = cfg.svg;
            $icon.appendChild(iconEl);
        }

        // Render Buttons
        $actions.innerHTML = '';
        $actions.classList.toggle('hidden', !showButton);
        if (showButton) {
            if (cancelText) {
                $actions.appendChild(_makeGhost(cancelText, () => {
                    _close();
                    if (typeof onCancel === 'function') onCancel();
                }));
            }
            $actions.appendChild(_makePrimary(confirmText, cfg.btnClass, () => {
                _close();
                if (typeof onConfirm === 'function') onConfirm();
            }));
        }

        clearTimeout(_timer);
        if (typeof autoClose === 'number' && autoClose > 0) {
            _timer = setTimeout(_close, autoClose);
        }

        _open();
    }

    $closeX.addEventListener('click', _close);
    $backdrop.addEventListener('click', _close);
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && _isOpen) _close(); });

    // Session Flash Handler for Mobile
    function checkFlash() {
        const requestToken = '{{ uniqid() }}';
        
        // If we already displayed the flash for this specific render instance, skip
        if (sessionStorage.getItem('last_processed_token') === requestToken) {
            return;
        }

        function parse(raw, defaultTitle) {
            if (!raw) return { title: defaultTitle, description: '' };
            const i = raw.indexOf('|');
            return i !== -1
                ? { title: raw.slice(0, i).trim(), description: raw.slice(i + 1).trim() }
                : { title: defaultTitle, description: raw.trim() };
        }

        let flashShown = false;

        @if (session('success'))
            (function () {
                const p = parse(@json(session('success')), 'Berhasil!');
                show({ type: 'success', title: p.title, description: p.description, showButton: false, autoClose: 3000 });
                flashShown = true;
            })();
        @elseif (session('error'))
            (function () {
                const p = parse(@json(session('error')), 'Gagal');
                show({ type: 'error', title: p.title, description: p.description, confirmText: 'Tutup', showButton: true });
                flashShown = true;
            })();
        @elseif (session('warning'))
            (function () {
                const p = parse(@json(session('warning')), 'Perhatian');
                show({ type: 'warning', title: p.title, description: p.description, confirmText: 'Mengerti', showButton: true });
                flashShown = true;
            })();
        @elseif (session('info'))
            (function () {
                const p = parse(@json(session('info')), 'Informasi');
                show({ type: 'info', title: p.title, description: p.description, confirmText: 'Ok', showButton: true });
                flashShown = true;
            })();
        @elseif (isset($errors) && $errors->any())
            (function () {
                const msg = @json($errors->first());
                show({ type: 'error', title: 'Periksa Masukan Anda', description: msg, confirmText: 'Ok', showButton: true });
                flashShown = true;
            })();
        @endif

        // Mark this requestToken as shown
        if (flashShown) {
            sessionStorage.setItem('last_processed_token', requestToken);
        }
    }

    // Expose API
    window.MobilePopup = {
        show,
        success(o = {}) {
            show({ type: 'success', title: o.title ?? 'Berhasil!', description: o.description ?? '', showButton: false, autoClose: o.duration ?? 3000 });
        },
        error(o = {}) {
            show({ type: 'error', title: o.title ?? 'Gagal', description: o.description ?? '', confirmText: o.confirmText ?? 'Tutup', showButton: true, onConfirm: o.onConfirm ?? null });
        },
        warning(o = {}) {
            show({ type: 'warning', title: o.title ?? 'Perhatian', description: o.description ?? '', confirmText: o.confirmText ?? 'Tutup', cancelText: o.cancelText ?? null, showButton: true, onConfirm: o.onConfirm ?? null, onCancel: o.onCancel ?? null });
        },
        info(o = {}) {
            show({ type: 'info', title: o.title ?? 'Informasi', description: o.description ?? '', confirmText: o.confirmText ?? 'Ok', showButton: true, onConfirm: o.onConfirm ?? null });
        },
        confirm(o = {}) {
            show({ type: 'confirm', title: o.title ?? 'Konfirmasi', description: o.description ?? '', confirmText: o.confirmText ?? 'Ya', cancelText: o.cancelText ?? 'Batal', showButton: true, onConfirm: o.onConfirm ?? null, onCancel: o.onCancel ?? null });
        },
        close: _close
    };

    // Run flash checks on Turbo render (fires on first load too)
    document.addEventListener('turbo:load', checkFlash);

})();
</script>
@endonce
