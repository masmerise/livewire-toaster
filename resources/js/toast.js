import { uuid41 } from './uuid41';

export class Toast {
    constructor(duration, message, type) {
        this.$el = null;
        this.id = uuid41();
        this.isVisible = false;
        this.duration = duration;
        this.message = message;
        this.timeout = null;
        this.trashed = false;
        this.type = type;
    }

    static fromJson(data) {
        return new Toast(data.duration, data.message, data.type);
    }

    dispose() {
        if (this.timeout) {
            clearTimeout(this.timeout);
        }

        this.isVisible = false;

        if (this.$el) {
            this.$el.addEventListener('transitioncancel', () => { this.trashed = true; })
            this.$el.addEventListener('transitionend', () => { this.trashed = true; })
        }
    }

    equals(other) {
        return this.duration === other.duration
            && this.message === other.message
            && this.type === other.type;
    }

    runAfterDuration(callback) {
        this.timeout = setTimeout(() => callback(this), this.duration);
    }

    select(config) {
        return config[this.type];
    }

    show($el) {
        this.$el = $el;
        this.isVisible = true;
    }
}
