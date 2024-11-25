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
    constructor(alignment, duration, replace, suppress) {
        this.alignment = new Alignment(alignment);
        this.duration = duration;
        this.replace = replace;
        this.suppress = suppress;
    }

    static fromJson(data) {
        return new Config(data.alignment, data.duration, data.replace, data.suppress);
    }
}
