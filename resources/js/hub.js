import { Toast } from './toast';
import { Worker} from './worker';

export function Hub(Alpine) {
    Alpine.data('toasterHub', (initialToasts, config) => ({
        _toasts: [],
        _worker: new Worker(config.duration),

        get toasts() {
            return this._toasts.filter(t => ! t.trashed);
        },

        forceDelete() {
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
            this.forceDelete = this.forceDelete.bind(this);
        },

        isEmpty() {
            return ! this.toasts.length;
        },

        show(toast) {
            toast = Alpine.reactive(Toast.fromJson(toast));
            toast.runAfterDuration(toast => toast.softDelete());

            this._toasts.push(toast);
            this._worker.start(this.isEmpty, this.forceDelete);
        },
    }));
}
