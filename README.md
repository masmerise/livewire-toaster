# Toaster

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mabdullahsari/livewire-toaster.svg?style=flat-square)](https://packagist.org/packages/mabdullahsari/livewire-toaster)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/mabdullahsari/livewire-toaster/test.yml?branch=master)](https://github.com/mabdullahsari/livewire-toaster/actions?query=workflow%3A%22Automated+testing%22+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/mabdullahsari/livewire-toaster.svg?style=flat-square)](https://packagist.org/packages/mabdullahsari/livewire-toaster)

Toaster provides a seamless experience to display toast notifications in your Livewire powered Laravel applications. 

## Contents

- [Installation](#installation)
  - [Preparing your template](#preparing-your-template)
  - [Configuring scripts](#configuring-scripts)
  - [Tailwind styles](#tailwind-styles)
- [Usage](#usage)
  - [Dispatching toasts](#dispatching-toasts)
  - [Automatic translation of messages](#automatic-translation-of-messages)
  - [Unit testing](#unit-testing)
- [Customization](#customization)
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

*Skip this step if you're going to customize Toaster's default view.*

Toaster provides a minimal view that utilizes Tailwind CSS defaults. 

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
from either a standard `Controller` or a Livewire `Component`. You don't have to think about "flashing" things to the 
session or "dispatching browser events" from your Livewire components. 
Just dispatch your toast and Toaster will route the message accordingly.

Toast away!

### Dispatching toasts

> **Note** The examples are applicable in, but not limited to, `Controller`s as well as Livewire `Component`s.
> **Note** Toaster supports the dispatch of multiple toasts at once, you are not limited to dispatching a single toast.

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

If you need fine-grained control, you can always use the `PendingToast` class directly to which `Toaster` proxies its calls:

```php
use MAS\Toaster\PendingToast;

final class RegistrationForm extends Component
{
    public function submit(): void
    {
        $this->validate();
        
        $user = User::create($this->form);
        
        // 👇
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
    use Toastable; // 👈

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

Whenever you return a `RedirectResponse` from anywhere in your app, you can chain any of the `Toaster` methods
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

This is, of course, **not** limited to `Controller`s as you can also redirect in Livewire `Component`s.

#### Dependency injection

If you'd like to keep things "pure", you can also inject the `Collector` contract 
and use the `ToastBuilder` to dispatch your toasts:

```php
use MAS\Toaster\Collector;
use MAS\Toaster\ToasterConfig;
use MAS\Toaster\ToastBuilder;

final readonly class SendEmailVerifiedNotification
{
    public function __construct(
        private ToasterConfig $config,
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

### Automatic translation of messages

> **Note** The `translate` configuration value must be set to `true`.

Instead of doing this:

```php
Toaster::success(
    Lang::get('path.to.translation', ['replacement' => 'value'])
);
```

Toaster makes it possible to do this:

```php
Toaster::success('path.to.translation', ['replacement' => 'value']);
```

You can mix and match without any problems:

```php
Toaster::info('user.created', ['name' => $user->full_name]);
Toaster::info('You now have full access!');
```

You can do whatever you want, whenever you want.

### Unit testing

Toaster provides a couple of testing capabilities in order for you to build a robust application:

```php
use MAS\Toaster\Toaster;

final class RegisterUserControllerTest extends TestCase
{
    /** @test */
    public function users_can_register(): void
    {
        // Arrange
        Toaster::fake();
        Toaster::assertNothingDispatched();
        
        // Act
        $response = $this->post('users', [ ... ]);
        
        // Assert
        $response->assertRedirect('profile');
        Toaster::assertDispatched('Welcome!');
    }
}
```

## Customization

Even though the default toasts are pretty, they might not fit your design and you may want to customize them.

You can do so by publishing Toaster's views:

```php
php artisan vendor:publish --tag=toaster-views
```

The `hub.blade.php` view will be published to your application's `resources/views/vendor/toaster` directory. 
Feel free to modify anything to your liking.

> **Warning** You **must** keep the `x-data` and `x-init` directives and you **must** keep using the `x-for` loop.
> Otherwise, the Alpine component that powers Toaster will start malfunctioning.

### Available `viewData`

- `$position` - can be used to align the toast container depending on the configuration
- `$config` - default configuration values, used by the Alpine component
- `$toasts` - toasts that were flashed to the session by Toaster, used by the Alpine component

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
