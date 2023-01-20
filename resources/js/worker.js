export class Worker {
    constructor(sleep) {
        this.sleep = sleep;
        this.timeoutId = null;
    }

    static configure(sleep) {
        return new Worker(sleep);
    }

    start({ onLoop, onTerminate }) {
        if (this.timeoutId) return;

        this._work(onLoop, onTerminate);
    }

    terminate() {
        if (this.timeoutId) {
            clearTimeout(this.timeoutId);
        }

        this.timeoutId = null;
    }

    _work(shouldTerminate, terminating) {
        this.timeoutId = setTimeout(() => {
            if (shouldTerminate()) {
                terminating();

                this.terminate();
            } else {
                this._work(shouldTerminate, terminating);
            }
        }, this.sleep);
    }
}
