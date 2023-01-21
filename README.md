# Livewire Toast

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mabdullahsari/livewire-toaster.svg?style=flat-square)](https://packagist.org/packages/mabdullahsari/livewire-toaster)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/mabdullahsari/livewire-toaster/Tests?label=tests)](https://github.com/mabdullahsari/livewire-toaster/actions?query=workflow%3ATesting+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/mabdullahsari/livewire-toaster.svg?style=flat-square)](https://packagist.org/packages/mabdullahsari/livewire-toaster)

This package provides a seamless experience to display toast notifications in your Livewire powered applications. 

## Contents

- [Installation](#installation)
- [Usage](#usage)
- [Testing](#testing)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via [composer](https://getcomposer.org):

```bash
composer require mabdullahsari/livewire-toaster
```

You can publish the package's config file:

```bash
php artisan vendor:publish --tag=toaster-config
```

This is the contents of the `toaster.php` config file:

```php
return [

    /**
     * The on-screen duration of each toast.
     *
     * Minimum: 1000 (in milliseconds)
     */
    'duration' => 5000,

    /**
     * The on-screen position of each toast.
     *
     * Supported: "center", "left" or "right"
     */
    'position' => 'right',

    /**
     * Whether messages passed as translation keys should be translated automatically.
     * While this is a very useful default behaviour, you may wish to disable this.
     *
     * Supported: true | false
     */
    'translate' => true,
];
```

### Preparing your template

Next, you'll need to use the `<x-toaster-hub />` component in your master template:

```html
<!DOCTYPE html>
<html>
<head>
    <!-- ... -->
</head>

<body>
    <!-- Application content -->

    <x-toaster-hub /> <!-- 👈 -->
</body>
</html>
```

### Configuring scripts

After that, you'll need to register the `Toaster` plugin with your `resources/js/app.js` bundle to start listening to incoming toasts:

```js
import Alpine from 'alpinejs';
import Toaster from '../../vendor/mabdullahsari/livewire-toaster/resources/js'; // 👈

Alpine.plugin(Toaster); // 👈

window.Alpine = Alpine;
Alpine.start();
```

### Tailwind styles

*Skip this step if you're going to customize the package's default view.*

This package provides a minimal view that utilizes Tailwind CSS defaults. 

If the default toast appearances suffice your needs, you'll need to register it with Tailwind's purge list:

```js
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './vendor/mabdullahsari/**/*.blade.php', // 👈
    ],
}
```

Otherwise, please refer to [Customization](#customization).

## Usage

Unlike many other toast implementations that are available, Toaster makes it very easy to dispatch a toast notification
from either a standard `Controller` or a Livewire `Component`. Just dispatch your toast and Toaster will route the 
message accordingly.

Toast away!

### Dispatching toasts

> **Note** All of the examples are applicable in, but not limited to, `Controller`s as well as Livewire `Component`s.

#### Toaster

The standard recommended way for dispatching toast messages is through the `Toaster` facade.

```php
use MAS\Toaster\Toaster;

final class RegistrationForm extends Component
{
    public function submit(): void
    {
        $this->validate();
        
        User::create($this->form);
        
        Toaster::success('User created!'); // 👈
    }
}
```

If you need fine-grained control, you can always use the `PendingToast` class directly to which `Toaster` proxies its calls to:

```php
use MAS\Toaster\PendingToast;

final class RegistrationForm extends Component
{
    public function submit(): void
    {
        $this->validate();
        
        $user = User::create($this->form);
        
        PendingToast::create()
            ->when($user->isAdmin(),
                fn (PendingToast $toast) => $toast->message('Admin created')
            )
            ->unless($user->isAdmin(),
                fn (PendingToast $toast) => $toast->message('User created')
            )
            ->success();
    }
}
```

#### Toastable

You can make any class `Toastable` to dispatch toasts from:

```php
use MAS\Toaster\Toastable;

final class ProductListing extends Component
{
    use Toastable;

    public function check(): void
    {
        $result = Product::query()
            ->tap(new Available())
            ->count();
            
        if ($result < 5) {
            $this->warning('The quantity on hand is critically low.'); // 👈
        }
    }
}
```

#### Redirects

Whenever you return a `RedirectResponse` from anywhere in your app, you can call any of the `Toaster` methods
to dispatch a toast message:

```php
final class CompanyController extends Controller
{
    /** @throws ValidationException */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [...]);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->error('The form contains several errors'); // 👈
        }
    
        Company::create($validator->validate());
        
        return Redirect::route('dashboard')
            ->info('Company created!'); // 👈
    }
}
```

#### Dependency injection

If you'd like to keep things "pure", you can also inject the `Collector` contract and use the `ToastBuilder` to dispatch your toasts.

```php
use MAS\Toaster\Collector;
use MAS\Toaster\ToastBuilder;

final readonly class SendEmailVerifiedNotification
{
    public function __construct(
        private ToastConfig $config,
        private Collector $toasts,
    ) {}
    
    public function handle(Verified $event): void
    {
        $toast = ToastBuilder::create()
            ->duration($this->config->duration())
            ->success()
            ->message("Thank you, {$event->user->name}!")
            ->get();
            
        $this->toasts->collect($toast);
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email support@muhammedsari.me instead of using the issue tracker.

## Credits

- [Muhammed Sari](https://github.com/mabdullahsari)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
