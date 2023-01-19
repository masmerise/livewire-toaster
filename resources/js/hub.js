import { Toast } from './toast';

export function Hub(Alpine) {
    Alpine.data('toasterHub', (initialToasts, config) => ({
        toasts: [],

        init() {
            window.addEventListener('toaster:received', event => {
                this.add({ ...config.defaults, ...event.detail });
            });

            for (const toast of initialToasts) {
                this.add(toast);
            }

            this.incinerate = this.incinerate.bind(this);
            this.remove = this.remove.bind(this);
        },

        add(toast) {
            this.enforceMaxAmount();

            toast = Toast.fromJson(toast);
            toast.afterDuration(this.remove);

            this.prepend(toast);
        },

        enforceMaxAmount() {
            if (config.max > 0 && this.toasts.length >= config.max) {
                this.toasts.shift().dispose();
            }
        },

        incinerate(toast) {
            const idx = this.toasts.findIndex(t => t.is(toast));

            this.toasts.splice(idx, 1);
        },

        prepend(toast) {
            this.toasts.push(toast);

            this.$nextTick(() => { toast.$el = this.$el.querySelector(`[data-toast="${toast.id}"]`); });
        },

        remove(toast) {
            const idx = this.toasts.findIndex(t => t.is(toast));

            this.toasts[idx].hide();

            toast.$el.addEventListener('transitionend', this.incinerate);
        },
    }));
}
