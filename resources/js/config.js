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
    constructor(alignment, duration, suppress) {
        this.alignment = new Alignment(alignment);
        this.duration = duration;
        this.suppress = suppress;
    }

    static fromJson(data) {
        return new Config(data.alignment, data.duration, data.suppress);
    }
}
