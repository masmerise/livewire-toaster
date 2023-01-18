<?php declare(strict_types=1);

namespace MAS\Toaster;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\AggregateServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\LivewireManager;
use Livewire\LivewireServiceProvider;

final class ToasterServiceProvider extends AggregateServiceProvider
{
    public const NAME = 'toaster';

    protected $providers = [LivewireServiceProvider::class];

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', self::NAME);

        $this->callAfterResolving(BladeCompiler::class, $this->aliasToasterHub(...));
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

    private function aliasToasterHub(BladeCompiler $blade): void
    {
        $blade->component('toaster-hub', ToasterHub::class);
    }

    private function configureService(): ToasterConfig
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/toaster.php', self::NAME);

        $config = ToasterConfig::fromArray($this->app['config'][self::NAME]);
        $this->app->instance(ToasterConfig::class, $config);

        return $config;
    }

    private function registerPublishing(): void
    {
        $this->publishes([
            __DIR__ . '/../config/toaster.php' => $this->app->configPath('toaster.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../resources/views' => $this->app->resourcePath('views/vendor/toaster'),
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
