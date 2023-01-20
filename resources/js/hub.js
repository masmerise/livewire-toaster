import { Toast } from './toast';

export function Hub(Alpine) {
    Alpine.data('toasterHub', (initialToasts, config) => {
        return {
            _toasts: [],

            get toasts() {
                return this._toasts.filter(t => ! t.trashed);
            },

            init() {
                window.addEventListener('toaster:received', event => {
                    this.show({ ...config, ...event.detail });
                });

                for (const toast of initialToasts) {
                    this.show(toast);
                }
            },

            show(toast) {
                toast = Alpine.reactive(Toast.fromJson(toast));
                toast.runAfterDuration(toast => toast.dispose());

                this._toasts.push(toast);
            },
        }
    });
}
