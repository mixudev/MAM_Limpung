/**
 * Cetak dokumen PPDB di latar belakang (tanpa membuka tab/halaman baru).
 */
(function (global) {
    const CONTAINER_ID = 'ppdb-print-injection-container';
    const TOAST_ID = 'ppdb-print-toast';
    const STYLE_ID = 'ppdb-print-injection-styles';

    function ensureStyles() {
        if (document.getElementById(STYLE_ID)) {
            return;
        }

        const style = document.createElement('style');
        style.id = STYLE_ID;
        style.textContent = `
            #${CONTAINER_ID} { display: none; }
            #${TOAST_ID} {
                position: fixed;
                bottom: 1.5rem;
                right: 1.5rem;
                z-index: 99999;
                background: #0f172a;
                color: #fff;
                font-family: ui-monospace, monospace;
                font-size: 10px;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                padding: 0.65rem 1rem;
                border: 1px solid #1e293b;
                box-shadow: 0 10px 25px rgba(0,0,0,.2);
                opacity: 0;
                transform: translateY(12px);
                pointer-events: none;
                transition: opacity .2s ease, transform .2s ease;
            }
            #${TOAST_ID}.is-visible {
                opacity: 1;
                transform: translateY(0);
            }
            @media print {
                body.ppdb-printing > *:not(#${CONTAINER_ID}) {
                    display: none !important;
                }
                body.ppdb-printing #${CONTAINER_ID} {
                    display: block !important;
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    background: #fff;
                }
            }
        `;
        document.head.appendChild(style);
    }

    function showToast(message) {
        ensureStyles();
        let toast = document.getElementById(TOAST_ID);
        if (!toast) {
            toast = document.createElement('div');
            toast.id = TOAST_ID;
            document.body.appendChild(toast);
        }
        toast.textContent = message;
        toast.classList.add('is-visible');
        return toast;
    }

    function hideToast(delay = 0) {
        const toast = document.getElementById(TOAST_ID);
        if (!toast) {
            return;
        }
        setTimeout(() => toast.classList.remove('is-visible'), delay);
    }

    function getContainer() {
        let container = document.getElementById(CONTAINER_ID);
        if (!container) {
            container = document.createElement('div');
            container.id = CONTAINER_ID;
            document.body.appendChild(container);
        }
        return container;
    }

    function parsePrintDocument(html) {
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const styles = Array.from(doc.querySelectorAll('head style'))
            .map((el) => el.outerHTML)
            .join('\n');
        const contentEl =
            doc.querySelector('.print-page') ||
            doc.querySelector('.print-wrapper') ||
            doc.querySelector('.print-ledger-wrapper') ||
            doc.body;

        const printContent = contentEl.classList?.contains('print-page') ||
            contentEl.classList?.contains('print-wrapper') ||
            contentEl.classList?.contains('print-ledger-wrapper')
            ? contentEl.outerHTML
            : contentEl.innerHTML;

        return { styles, printContent };
    }

    function waitForImages(root, timeoutMs = 8000) {
        const images = Array.from(root.querySelectorAll('img'));
        if (images.length === 0) {
            return Promise.resolve();
        }

        const waits = images.map(
            (img) =>
                new Promise((resolve) => {
                    if (img.complete && img.naturalWidth > 0) {
                        resolve();
                        return;
                    }
                    const done = () => resolve();
                    img.addEventListener('load', done, { once: true });
                    img.addEventListener('error', done, { once: true });
                })
        );

        return Promise.race([
            Promise.all(waits),
            new Promise((resolve) => setTimeout(resolve, timeoutMs)),
        ]);
    }

    function cleanup() {
        document.body.classList.remove('ppdb-printing');
        const container = document.getElementById(CONTAINER_ID);
        if (container) {
            container.innerHTML = '';
        }
    }

    function bindAfterPrintOnce() {
        const handler = () => {
            cleanup();
            global.removeEventListener('afterprint', handler);
        };
        global.addEventListener('afterprint', handler);
    }

    async function printHtml(html, options = {}) {
        const loadingLabel = options.loadingLabel || 'Menyiapkan dokumen cetak...';
        const errorLabel = options.errorLabel || 'Gagal memuat dokumen cetak.';

        ensureStyles();
        showToast(loadingLabel);
        bindAfterPrintOnce();

        try {
            const { styles, printContent } = parsePrintDocument(html);
            const container = getContainer();
            container.innerHTML = styles + printContent;

            await waitForImages(container);

            document.body.classList.add('ppdb-printing');
            hideToast(80);

            requestAnimationFrame(() => {
                setTimeout(() => global.print(), 120);
            });
        } catch (error) {
            console.error('PpdbBackgroundPrint:', error);
            cleanup();
            showToast(errorLabel);
            hideToast(2500);
            throw error;
        }
    }

    async function printFromUrl(url, options = {}) {
        showToast(options.loadingLabel || 'Menyiapkan dokumen cetak...');

        const response = await fetch(url, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                Accept: 'text/html',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            showToast(options.errorLabel || 'Gagal memuat dokumen cetak.');
            hideToast(2500);
            throw new Error(`Print fetch failed: ${response.status}`);
        }

        const html = await response.text();
        return printHtml(html, options);
    }

    global.PpdbBackgroundPrint = {
        printFromUrl,
        printHtml,
        cleanup,
    };
})(window);
