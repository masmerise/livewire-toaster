export class Worker {
    constructor(sleep) {
        this.sleep = sleep;
        this.timeoutId = null;
    }

    start(onPoll, onStop) {
        if (this.timeoutId) return;

        this.work(onPoll, onStop);
    }

    stop() {
        if (this.timeoutId) {
            clearTimeout(this.timeoutId);
        }

        this.timeoutId = null;
    }

    work(onPoll, onStop) {
        this.timeoutId = setTimeout(() => {
            if (onPoll()) {
                onStop();

                this.stop();
            } else {
                this.work(onPoll, onStop);
            }
        }, this.sleep);
    }
}
