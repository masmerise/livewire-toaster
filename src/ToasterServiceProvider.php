<?php declare(strict_types=1);

namespace Masmerise\Toaster;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Router;
use Illuminate\Support\AggregateServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\LivewireManager;
use Livewire\LivewireServiceProvider;

final class ToasterServiceProvider extends AggregateServiceProvider
{
    public const CONFIG = 'toaster.config';
    public const NAME = 'toaster';

    protected $providers = [LivewireServiceProvider::class];

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', self::NAME);

        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this->callAfterResolving(BladeCompiler::class, $this->aliasToasterHub(...));
        $this->callAfterResolving(Collector::class, $this->relayToLivewire(...));
        $this->callAfterResolving(Router::class, $this->relayToSession(...));

        Redirector::mixin($macros = new ToastableMacros());
        RedirectResponse::mixin($macros);
    }

    public function register(): void
    {
        $config = $this->configureService();

        parent::register();

        $this->app->scoped(Collector::class, QueuingCollector::class);
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
        $blade->component(ToasterHub::NAME, ToasterHub::class);
    }

    private function configureService(): ToasterConfig
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/toaster.php', self::NAME);

        $config = ToasterConfig::fromArray($this->app['config'][self::NAME] ?? []);
        $this->app->instance(ToasterConfig::class, $config);
        $this->app->alias(ToasterConfig::class, self::CONFIG);

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
        $this->app[LivewireManager::class]->listen('dehydrate', new LivewireRelay());
    }

    private function relayToSession(Router $router): void
    {
        $router->aliasMiddleware(SessionRelay::NAME, SessionRelay::class);
        $router->pushMiddlewareToGroup('web', SessionRelay::NAME);
    }
}
