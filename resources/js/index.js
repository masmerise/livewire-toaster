const ANIMATION_DURATION_MILLIS = 500;
const EVENT = 'toast:received';
const MAX_VISIBLE_TOASTS = 3;

export default function (Alpine) {
    Alpine.magic('toast', () => ({
        // wip
    }));

    Alpine.data('toastHub', (initialToasts = []) => ({
        toasts: [],

        init() {
            window.addEventListener(EVENT, event => {
                this.addToast(event.detail);
            });

            if (! initialToasts.length) {
                return;
            }

            window.addEventListener('load', () => {
                for (const toast of initialToasts) {
                    this.addToast(toast);
                }
            });
        },

        addToast(toast) {
            if (this.toasts.length >= MAX_VISIBLE_TOASTS) {
                this.toasts.pop();
            }

            this.toasts.unshift(toast);
            this.scheduleRemoval(toast);
        },

        removeToast(id) {
            const idx = this.getToastIdx(id);
            if (idx === -1) {
                return;
            }

            this.toasts[idx].visible = false;

            setTimeout(() => {
                this.toasts.splice(idx, 1);
            }, ANIMATION_DURATION_MILLIS);
        },

        scheduleRemoval(toast) {
            setTimeout(() => {
                this.removeToast(toast.id);
            }, toast.duration);
        },

        getToastIdx(id) {
            return this.toasts.findIndex(t => t.id === id);
        },
    }));
};
