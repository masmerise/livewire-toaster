import { uuid41 } from './uuid41';

export class Toast {
    constructor(duration, message, type) {
        this.id = uuid41();
        this.isVisible = false;
        this.duration = duration;
        this.message = message;
        this.type = type;
    }

    static fromJson(data) {
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
