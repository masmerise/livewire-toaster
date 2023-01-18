<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\AggregateServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\LivewireManager;
use Livewire\LivewireServiceProvider;

final class ToastServiceProvider extends AggregateServiceProvider
{
    public const NAME = 'toast';

    protected $providers = [LivewireServiceProvider::class];

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', self::NAME);

        $this->callAfterResolving(BladeCompiler::class, $this->aliasToastHub(...));
        $this->callAfterResolving(Collector::class, $this->relayToasts(...));

        RedirectResponse::mixin(new ToastableMacros());
    }

    public function register(): void
    {
        $config = $this->configureService();

        parent::register();

        $this->app->singleton(Collector::class, QueuingCollector::class);
        $this->app->alias(Collector::class, self::NAME);

        if ($config->shouldTranslateMessages()) {
            $this->app->extend(Collector::class, $this->translateMessages(...));
        }
    }

    private function aliasToastHub(BladeCompiler $blade): void
    {
        $blade->component('toast-hub', ToastHub::class);
    }

    private function configureService(): ToastConfig
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/toast.php', self::NAME);

        $config = ToastConfig::fromArray($this->app['config'][self::NAME]);
        $this->app->instance(ToastConfig::class, $config);

        return $config;
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

    private function relayToasts(): void
    {
        $this->app[Dispatcher::class]->listen(RequestHandled::class, SessionRelay::class);
        $this->app[LivewireManager::class]->listen('component.dehydrate', $this->app[LivewireRelay::class]);
    }

    private function translateMessages(Collector $next): TranslatingCollector
    {
        return new TranslatingCollector($next, $this->app['translator']);
    }
}
