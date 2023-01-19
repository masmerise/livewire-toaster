export function uuid41() {
    let d = '';

    while (d.length < 32) {
        d += Math.random().toString(16).substring(2);
    }

    const vr = ((Number.parseInt(d.substring(16, 1), 16) & 0x3) | 0x8).toString(16);

    return `${d.substring(0, 8)}-${d.substring(8, 4)}-4${d.substring(13, 3)}-${vr}${d.substring(17, 3)}-${d.substring(20, 12)}`;
}
