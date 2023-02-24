<?php declare(strict_types=1);

namespace Masmerise\Toaster;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Router;
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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', self::NAME);

        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this->relayToSession();

        $this->callAfterResolving(BladeCompiler::class, $this->aliasToasterHub(...));
        $this->callAfterResolving(Collector::class, $this->relayToLivewire(...));

        RedirectResponse::mixin(new ToastableMacros());
    }

    public function register(): void
    {
        $config = $this->configureService();

        parent::register();

        $this->app->singleton(Collector::class, QueuingCollector::class);
        $this->app->alias(Collector::class, self::NAME);

        if ($config->wantsAccessibility) {
            $this->app->extend(Collector::class, static fn (Collector $next) => new AccessibleCollector($next));
        }

        if ($config->wantsTranslation) {
            $this->app->extend(Collector::class, fn (Collector $next) => new TranslatingCollector($next, $this->app['translator']));
        }
    }

    private function aliasToasterHub(BladeCompiler $blade): void
    {
        $blade->component('toaster-hub', ToasterHub::class);
    }

    private function configureService(): ToasterConfig
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/toaster.php', self::NAME);

        $config = ToasterConfig::fromArray($this->app['config'][self::NAME] ?? []);
        $this->app->instance(ToasterConfig::class, $config);

        return $config;
    }

    private function registerPublishing(): void
    {
        $this->publishes([
            __DIR__ . '/../config/toaster.php' => $this->app->configPath('toaster.php'),
        ], 'toaster-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => $this->app->resourcePath('views/vendor/toaster'),
        ], 'toaster-views');
    }

    private function relayToLivewire(): void
    {
        $this->app[LivewireManager::class]->listen('component.dehydrate', $this->app[LivewireRelay::class]);
    }

    private function relayToSession(): void
    {
        $this->app[Router::class]->aliasMiddleware(SessionRelay::NAME, SessionRelay::class);
        $this->app[Router::class]->pushMiddlewareToGroup('web', SessionRelay::NAME);
    }
}
