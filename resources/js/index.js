export default function (Alpine) {
    Alpine.data('toastHub', (initialToasts, config) => ({
        toasts: [],

        init() {
            window.addEventListener('toast:received', event => {
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

            toast = Toast.create(toast);
            toast.afterDuration(this.remove);

            this.prepend(toast);
        },

        enforceMaxAmount() {
            if (config.max > 0 && this.toasts.length >= config.max) {
                this.toasts.pop().dispose();
            }
        },

        incinerate(toast) {
            const idx = this.toasts.findIndex(t => t.is(toast));

            this.toasts.splice(idx, 1);
        },

        prepend(toast) {
            this.toasts.unshift(toast);

            this.$nextTick(() => { toast.$el = this.$el.querySelector(`[data-toast="${toast.id}"]`); });
        },

        remove(toast) {
            const idx = this.toasts.findIndex(t => t.is(toast));

            this.toasts[idx].hide();

            toast.$el.addEventListener('transitionend', this.incinerate);
        },
    }));

    Alpine.magic('toaster', (el) => {
        const toast = (message, type) => {
            window.dispatchEvent(new CustomEvent('toast:received', { detail: { message, type }}));
        };

        return {
            error(message) {
                toast(message, 'error');
            },

            info(message) {
                toast(message, 'info');
            },

            success(message) {
                toast(message, 'success');
            },

            warning(message) {
                toast(message, 'warning');
            }
        }
    });
};

class Toast {
    constructor(duration, message, type) {
        this.id = uuid41();
        this.isVisible = false;
        this.duration = duration;
        this.message = message;
        this.type = type;
    }

    static create(data) {
        return new Toast(data.duration, data.message, data.type);
    }

    afterDuration(callback) {
        this.timeoutId = setTimeout(() => {
            callback(this);

            this.timeoutId = null;
        }, this.duration);
    }

    dispose() {
        if (this.timeoutId) {
            clearTimeout(this.timeoutId);
        }
    }

    hide() {
        this.isVisible = false;
    }

    is(other) {
        return this.id === other.id;
    }

    select(config) {
        return config[this.type];
    }

    show() {
        this.isVisible = true;
    }
}

function uuid41() {
    let d = '';

    while (d.length < 32) {
        d += Math.random().toString(16).substring(2);
    }

    const vr = ((Number.parseInt(d.substring(16, 1), 16) & 0x3) | 0x8).toString(16);

    return `${d.substring(0, 8)}-${d.substring(8, 4)}-4${d.substring(13, 3)}-${vr}${d.substring(17, 3)}-${d.substring(20, 12)}`;
}
