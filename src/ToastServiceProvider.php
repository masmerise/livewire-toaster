<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\AggregateServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\LivewireManager;
use Livewire\LivewireServiceProvider;

final class ToastServiceProvider extends AggregateServiceProvider
{
    public const NAME = 'toast';

    /** @var array<string> */
    protected $providers = [LivewireServiceProvider::class];

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', self::NAME);

        $this->callAfterResolving(BladeCompiler::class, $this->registerHub(...));
        $this->callAfterResolving(Collector::class, $this->registerRelays(...));
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/toast.php', self::NAME);

        parent::register();

        $this->app->singleton(Collector::class, QueuingCollector::class);
        $this->app->alias(Collector::class, self::NAME);

        if (! $this->app['config']['toast.translate']) {
            return;
        }

        $this->app->extend(Collector::class,
            fn (Collector $next) => new TranslatingCollector($next, $this->app['translator'])
        );
    }

    private function registerHub(BladeCompiler $blade): void
    {
        $blade->component('toast-hub', ToastHub::class);
    }

    private function registerPublishing(): void
    {
        $this->publishes([
            __DIR__ . '/../config/toast.php' => $this->app->configPath('toast.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../resources/views' => $this->app->resourcePath('views/vendor/toast'),
        ], 'views');
    }

    private function registerRelays(): void
    {
        $this->app[Dispatcher::class]->listen(RequestHandled::class, SessionRelay::class);
        $this->app[LivewireManager::class]->listen('component.dehydrate', $this->app[LivewireRelay::class]);
    }
}
