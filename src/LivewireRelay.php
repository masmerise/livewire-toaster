<?php declare(strict_types=1);

namespace MAS\Toast;

use Livewire\Component;
use Livewire\LivewireManager;
use Livewire\Redirector;
use Livewire\Response;

/** @internal */
final readonly class LivewireRelay
{
    public const EVENT = 'toast-received';

    public function __construct(
        private LivewireManager $livewire,
        private Collector $toasts,
    ) {}

    public function __invoke(Component $component, Response $response): Response
    {
        if (! $this->livewire->isLivewireRequest()) {
            return $response;
        }

        if ($component->redirectTo instanceof Redirector) {
            return $response;
        }

        if ($toasts = $this->toasts->flush()) {
            foreach ($toasts as $toast) {
                $component->dispatchBrowserEvent(self::EVENT, $toast);
            }
        }

        return $response;
    }
}
