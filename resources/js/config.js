class Alignment {
    static Top = 'top';

    constructor(value) {
        this.value = value;
    }

    isTop() {
        return this.value === Alignment.Top;
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
