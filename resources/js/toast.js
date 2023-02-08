import { uuid41 } from './uuid41';

export class Toast {
    constructor(duration, message, type) {
        this.$el = null;
        this.id = uuid41();
        this.isVisible = false;
        this.duration = duration;
        this.message = message;
        this.trashed = false;
        this.type = type;
    }

    static fromJson(data) {
        return new Toast(data.duration, data.message, data.type);
    }

    dispose() {
        this.isVisible = false;

        this.$el.addEventListener('transitioncancel', () => { this.trashed = true; })
        this.$el.addEventListener('transitionend', () => { this.trashed = true; })
    }

    runAfterDuration(callback) {
        setTimeout(() => callback(this), this.duration);
    }

    select(config) {
        return config[this.type];
    }

    show($el) {
        this.$el = $el;
        this.isVisible = true;
    }
}
