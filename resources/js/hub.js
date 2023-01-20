import { Toast } from './toast';
import { Worker } from './worker';

export function Hub(Alpine) {
    Alpine.data('toasterHub', (initialToasts, config) => {
        const worker = Worker.configure(config.duration);

        return {
            _toasts: [],

            get toasts() {
                return this._toasts.filter(t => ! t.trashed);
            },

            flush() {
                this._toasts = [];
            },

            init() {
                window.addEventListener('toaster:received', event => {
                    this.show({ ...config, ...event.detail });
                });

                for (const toast of initialToasts) {
                    this.show(toast);
                }

                this.isEmpty = this.isEmpty.bind(this);
                this.flush = this.flush.bind(this);
            },

            isEmpty() {
                return ! this.toasts.length;
            },

            show(toast) {
                toast = Alpine.reactive(Toast.fromJson(toast));
                this._toasts.push(toast);

                toast.runAfterDuration(toast => toast.dispose());
                worker.start({ onLoop: this.isEmpty, onTerminate: this.flush });
            },
        }
    });
}
