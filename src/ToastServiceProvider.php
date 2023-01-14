<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\ServiceProvider;
use Livewire\LivewireManager;

final class ToastServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public const NAME = 'toast';

    public function boot(Dispatcher $events, LivewireManager $livewire): void
    {
        $events->listen(RequestHandled::class, SessionRelay::class);
        $livewire->listen('component.dehydrate', $this->app->make(LivewireRelay::class));
    }

    public function register(): void
    {
        $this->app->singleton(Collector::class, QueuingCollector::make(...));
        $this->app->alias(Collector::class, self::NAME);

        $this->app->extend(Collector::class,
            fn (QueuingCollector $next) => new TranslatingCollector($next, $this->app->make('translator'))
        );
    }

    /** @return array<string> */
    public function provides(): array
    {
        return [Collector::class, self::NAME];
    }
}
