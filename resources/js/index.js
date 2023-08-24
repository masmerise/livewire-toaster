import { Hub } from './hub';
import * as Toaster from './toaster';

window.Toaster = Toaster;

document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(Hub);
});
