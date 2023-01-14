<?php declare(strict_types=1);

namespace MAS\Toast;

use Livewire\Component;
use Livewire\LivewireManager;
use Livewire\Redirector;
use Livewire\Response;

/** @internal */
final readonly class LivewireRelay
{
    public const EVENT = 'toast-message';

    public function __construct(
        private LivewireManager $livewire,
        private Collector $toasts,
    ) {}

    public function __invoke(Component $component, Response $response): Response
    {
        if (! $this->livewire->isProbablyLivewireRequest()) {
            return $response;
        }

        if ($component->redirectTo instanceof Redirector) {
            return $response;
        }

        if ($messages = $this->toasts->flush()) {
            foreach ($messages as $message) {
                $component->dispatchBrowserEvent(self::EVENT, $message);
            }
        }

        return $response;
    }
}
