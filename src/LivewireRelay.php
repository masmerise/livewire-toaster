<?php declare(strict_types=1);

namespace Masmerise\Toaster;

use Livewire\Component;
use Livewire\Features\SupportEvents\Event;
use Livewire\LivewireManager;
use Livewire\Mechanisms\DataStore;
use Livewire\Mechanisms\HandleComponents\ComponentContext;

/** @internal */
final readonly class LivewireRelay
{
    public const EVENT = 'toaster:received';

    public function __construct(
        private DataStore $store,
        private LivewireManager $livewire,
        private Collector $toasts,
    ) {}

    public function __invoke(Component $component, ComponentContext $ctx): void
    {
        if (! $this->livewire->isLivewireRequest()) {
            return;
        }

        if ($this->store->get($component, 'redirect')) {
            return;
        }

        if ($toasts = $this->toasts->release()) {
            foreach ($toasts as $toast) {
                $event = new Event(self::EVENT, $toast->toArray());
                $ctx->pushEffect('dispatches', $event->serialize());
            }
        }
    }
}
