# Upgrade Guide

## v1 → v2

### Minimum versions

The following dependency versions have been updated:

- The minimum Livewire version is now v3.0

### Adjusting the `Toaster` import

`Alpine` is now registered by Livewire automatically. Therefore, any direct `Alpine.plugin` or `Alpine.start` call will no longer work.
You should adjust your `app.js` JavaScript entry point to account for this change. An example:

```diff
import './bootstrap';
+ import '../../vendor/masmerise/livewire-toaster/resources/js';

- import Alpine from 'alpinejs';
- import Toaster from '../../vendor/masmerise/livewire-toaster/resources/js';

- Alpine.plugin(Toaster);

- window.Alpine = Alpine;
- Alpine.start();
```
