import { Toast } from './toast';

export function Hub(Alpine) {
    Alpine.data('toasterHub', (initialToasts, config) => {
        return {
            _toasts: [],

            get toasts() {
                const toasts = this._toasts.filter(t => ! t.trashed);

                if (this._toasts.length && ! toasts.length) {
                    this.$nextTick(() => { this._toasts = []; });
                }

                return toasts;
            },

            init() {
                window.addEventListener('toaster:received', event => {
                    this.show({ ...config, ...event.detail });
                });

                initialToasts.forEach(toast => this.show(toast));
            },

            show(toast) {
                toast = Alpine.reactive(Toast.fromJson(toast));
                toast.runAfterDuration(toast => toast.dispose());

                this._toasts.push(toast);
            },
        }
    });
}
