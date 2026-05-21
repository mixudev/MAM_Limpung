/**
 * Wizard formulir PPDB (Alpine.js).
 * Konfigurasi dimuat dari window.ppdbFormConfig (diset di blade).
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('ppdbFormWizard', () => {
        const config = window.ppdbFormConfig ?? {
            form: {},
            errorStep: 1,
            showErrorAlert: false,
            firstErrorField: null,
        };

        return {
            step: config.errorStep,
            showErrorAlert: config.showErrorAlert,
            form: { ...config.form },

            init() {
                const oldInput = config.form;
                const hasServerOld = Object.values(oldInput).some(
                    (v) => v !== '' && v !== null && v !== undefined
                );

                if (hasServerOld) {
                    Object.keys(oldInput).forEach((key) => {
                        if (oldInput[key] !== undefined && oldInput[key] !== null) {
                            this.form[key] = oldInput[key];
                        }
                    });
                } else {
                    const saved = localStorage.getItem('ppdb_form_draft');
                    if (saved) {
                        try {
                            const parsed = JSON.parse(saved);
                            Object.keys(this.form).forEach((key) => {
                                if (parsed[key] !== undefined) {
                                    this.form[key] = parsed[key];
                                }
                            });
                        } catch (e) {
                            console.error('Gagal memuat draf formulir:', e);
                        }
                    }
                }

                this.$watch(
                    'form',
                    (value) => {
                        localStorage.setItem('ppdb_form_draft', JSON.stringify(value));
                    },
                    { deep: true }
                );

                if (config.firstErrorField) {
                    this.$nextTick(() => {
                        const el = document.querySelector(
                            '[name="' + config.firstErrorField + '"]'
                        );
                        if (el) {
                            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            if (typeof el.focus === 'function') {
                                el.focus({ preventScroll: true });
                            }
                        }
                    });
                }
            },

            nextStep() {
                const currentContainer = document.getElementById('step-' + this.step);
                if (!currentContainer) {
                    return;
                }

                const inputs = currentContainer.querySelectorAll('input, select, textarea');
                let isValid = true;

                for (const input of inputs) {
                    if (!input.checkValidity()) {
                        input.reportValidity();
                        isValid = false;
                        break;
                    }
                }

                if (isValid) {
                    this.step++;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },

            prevStep() {
                this.step--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
        };
    });
});
