<p align="center"><img src="https://github.com/masmerise/livewire-toaster/raw/master/art/banner.png" alt="Toaster Banner"></p>

# Beautiful toast notifications for Livewire

[![Latest Version on Packagist](https://img.shields.io/packagist/v/masmerise/livewire-toaster.svg?style=flat-square)](https://packagist.org/packages/masmerise/livewire-toaster)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/masmerise/livewire-toaster/test.yml?branch=master)](https://github.com/masmerise/livewire-toaster/actions?query=workflow%3A%22Automated+testing%22+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/masmerise/livewire-toaster.svg?style=flat-square)](https://packagist.org/packages/masmerise/livewire-toaster)

**Toaster** provides a seamless experience to display toast notifications in your Livewire powered Laravel apps.

Unlike many other toast implementations that are available, Toaster makes it effortless to dispatch a toast notification
from either a standard `Controller` or a Livewire `Component`. You don't have to think about "flashing" things to the 
session or "dispatching browser events" from your Livewire components. Just dispatch your toast and Toaster will route the message accordingly.

## Showcase

<p align="center"><img src="https://github.com/masmerise/livewire-toaster/raw/master/art/showcase.gif" alt="Toaster Demo"></p>

## Compatibility

<table>
<tr><th>Livewire</th><th>PHP</th><th>Laravel</th></tr>
<tr><td>

| | [LW2](https://laravel-livewire.com/docs/2.x) | [LW3](https://livewire.laravel.com/docs) |
|-|-|-|
| [1.x](https://github.com/masmerise/livewire-toaster/tree/1.3.0) | ✅ | ❌                                              |
| 2.x | ❌ | ✅ |


</td><td>

| | PHP 8.2 | PHP 8.3 | PHP 8.4 |
|-|-|-|-|
| 1.0 - ∞ | ✅ | ✅ | ✅ |

</td><td>

| | L10 | L11 | L12
|-|-|-|-|
| 1.0 - 2.1 * | ✅ | ❌ | ❌
| 2.2 - ∞ | ❌ | ✅ | ✅

</tr> </table>

_* feature complete_

## Contents

**Looking for v1 docs?** [Click here](https://github.com/masmerise/livewire-toaster/tree/1.3.0).

- [Installation](#installation)
  - [Preparing your template](#preparing-your-template)
  - [Configuring scripts](#configuring-scripts)
  - [Tailwind styles](#tailwind-styles)
  - [RTL support](#rtl-support)
- [Usage](#usage)
  - [Sending toasts from the back-end](#sending-toasts-from-the-back-end)
  - [Sending toasts from the front-end](#sending-toasts-from-the-front-end)
  - [Automatic translation of messages](#automatic-translation-of-messages)
  - [Accessibility](#accessibility)
  - [Replacing similar toasts](#replacing-similar-toasts)
  - [Suppressing duplicate toasts](#suppressing-duplicate-toasts)
  - [Unit testing](#unit-testing)
  - [Extending behavior](#extending-behavior)
- [View customization](#view-customization)
- [Testing](#testing)
- [Changelog](#changelog)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via [composer](https://getcomposer.org):

```bash
composer require masmerise/livewire-toaster
```

You can publish the package's config file:

```bash
php artisan vendor:publish --tag=toaster-config
```

This is the contents of the `toaster.php` config file:

```php
return [

    /**
     * Add an additional second for every 100th word of the toast messages.
     *
     * Supported: true | false
     */
    'accessibility' => true,

    /**
     * The vertical alignment of the toast container.
     *
     * Supported: "bottom", "middle" or "top"
     */
    'alignment' => 'bottom',

    /**
     * Allow users to close toast messages prematurely.
     *
     * Supported: true | false
     */
    'closeable' => true,

    /**
     * The on-screen duration of each toast.
     *
     * Minimum: 3000 (in milliseconds)
     */
    'duration' => 3000,

    /**
     * The horizontal position of each toast.
     *
     * Supported: "center", "left" or "right"
     */
    'position' => 'right',

    /**
     * New toasts immediately replace similar ones, ensuring only one toast of a kind is visible at any time.
     * Takes precedence over the "suppress" option.
     *
     * Supported: true | false
     */
    'replace' => false,

    /**
     * Prevent the display of duplicate toast messages.
     *
     * Supported: true | false
     */
    'suppress' => false,

    /**
     * Whether messages passed as translation keys should be translated automatically.
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

After that, you'll need to import `Toaster` at the top of your `resources/js/app.js` bundle to start listening to incoming toasts:

```js
import './bootstrap';
import '../../vendor/masmerise/livewire-toaster/resources/js'; // 👈

// other app stuff...
```

### Tailwind styles

> [!NOTE]
> Skip this step if you're going to customize Toaster's default view.

Toaster provides a minimal view that utilizes Tailwind CSS defaults. 

If the default toast appearances suffice your needs, you'll need to register it with Tailwind's purge list:

```js
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './vendor/masmerise/livewire-toaster/resources/views/*.blade.php', // 👈
    ],
}
```

Otherwise, please refer to [View customization](#view-customization).

### RTL support

> [!NOTE]
> **LTR** will be assumed regardless of whether you apply the `ltr` attribute or not.

If your app makes use of an **RTL** language such as Arabic and Hebrew, don't forget to add the `rtl` attribute to the document root:

```html
<!DOCTYPE html>
<html dir="rtl"> <!-- 👈 -->
    ...
</html>
```

This will make sure the UI elements (such as the close button) are flipped and the text is properly aligned.

## Usage

### Sending toasts from the back-end

> [!NOTE]
> Toaster supports the dispatch of multiple toasts at once, you are not limited to dispatching a single toast.

#### Toaster

The standard recommended way for dispatching toast messages is through the `Toaster` facade.

```php
use Masmerise\Toaster\Toaster;

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
use Masmerise\Toaster\PendingToast;

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
use Masmerise\Toaster\Toastable;

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
use Masmerise\Toaster\Collector;
use Masmerise\Toaster\ToasterConfig;
use Masmerise\Toaster\ToastBuilder;

final readonly class SendEmailVerifiedNotification
{
    public function __construct(
        private ToasterConfig $config,
        private Collector $toasts,
    ) {}
    
    public function handle(Verified $event): void
    {
        $toast = ToastBuilder::create()
            ->duration($this->config->duration)
            ->success()
            ->message("Thank you, {$event->user->name}!")
            ->get();
            
        $this->toasts->collect($toast);
    }
}
```

### Sending toasts from the front-end

You can invoke the globally available `Toaster` instance to dispatch any toast message from anywhere:

```html
<button @click="Toaster.success('Form submitted!')">
    Submit
</button>
```

Available methods: `error`, `info`, `warning` & `success`

### Automatic translation of messages

> [!NOTE]
> The `translate` configuration value must be set to `true`.

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

### Accessibility

> [!NOTE]
> The `accessibility` configuration value must be set to `true`.

Toaster will add an additional second to a toast's on-screen duration for every 100th word.
This way, your users will have enough time to read toasts that are a tad larger than usual.

So, if your base duration value is `3 seconds` and your toast contains 223 words, 
the total on-screen duration of the toast will be `3 + 2 = 5 seconds`  

### Replacing similar toasts

> [!NOTE]
> The `replace` configuration value must be set to `true`.

> [!WARNING]
> Takes precedence over `suppress`.

Toaster will dispose of any toast that is similar to the one being dispatched prior to displaying the new toast.
A toast is considered similar if it has the same `duration`, `message`, and `type`.

### Suppressing duplicate toasts

> [!NOTE]
> The `suppress` configuration value must be set to `true`.

Toaster will prevent the display of duplicate toast messages while another toast with the same message is still on-screen.
A toast is considered a duplicate if it has the same `duration`, `message`, and `type`.

### Unit testing

> [!NOTE]
> If you make use of [automatic translation of messages](#automatic-translation-of-messages), you should assert whether the **translation keys** are passed along correctly instead of the human readable messages that are replaced by Laravel's translator.
> Otherwise, your tests are going to fail as the messages are not translated during unit testing.

Toaster provides a couple of testing capabilities in order for you to build a robust application:

```php
use Masmerise\Toaster\Toaster;

final class RegisterUserControllerTest extends TestCase
{
    #[Test]
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

### Extending behavior

Imagine that you'd like to keep track of how many toasts are dispatched daily to display on an admin dashboard. 
First, create a new class that encapsulates this logic:

```php
final readonly class DailyCountingCollector implements Collector
{
    public function __construct(private Collector $next) {}

    public function collect(Toast $toast): void
    {
        // increment the counter on durable storage

        $this->next->collect($toast);
    }

    public function release(): array
    {
        return $this->next->release();
    }
}
```

After that, extend the behavior in your `AppServiceProvider`:

```php
public function register(): void
{
    $this->app->extend(Collector::class, 
        static fn (Collector $next) => new DailyCountingCollector($next)
    );
}
```

That's it!

## View customization

Even though the default toasts are pretty, they might not fit your design and you may want to customize them.

You can do so by publishing Toaster's views:

```php
php artisan vendor:publish --tag=toaster-views
```

The `hub.blade.php` view will be published to your application's `resources/views/vendor/toaster` directory. 
Feel free to modify anything to your liking.

### Available `viewData`

- `$alignment` - can be used to align the toast container vertically depending on the configuration
- `$closeable` - whether the close button should be rendered by the Blade component
- `$config` - default configuration values, used by the Alpine component
- `$position` - can be used to position the toasts depending on the configuration
- `$toasts` - toasts that were flashed to the session by Toaster, used by the Alpine component

> [!WARNING]
> You **must** keep the `x-data` and `x-init` directives and you **must** keep using the `x-for` loop.
> Otherwise, the Alpine component that powers Toaster will start malfunctioning.


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email support@muhammedsari.me instead of using the issue tracker.

## Credits

- [Muhammed Sari](https://github.com/masmerise)
- [Greg Korba](https://github.com/wirone)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
