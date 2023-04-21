class Alignment {
    static Bottom = 'bottom';

    constructor(value) {
        this.value = value;
    }

    isBottom() {
        return this.value === Alignment.Bottom;
    }
}

export class Config {
    constructor(alignment, duration) {
        this.alignment = new Alignment(alignment);
        this.duration = duration;
    }

    static fromJson(data) {
        return new Config(data.alignment, data.duration);
    }
}
