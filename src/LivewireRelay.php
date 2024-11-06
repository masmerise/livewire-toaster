<?php declare(strict_types=1);

namespace Masmerise\Toaster;

use Livewire\Component;
use Livewire\Features\SupportEvents\Event;
use Livewire\Livewire;
use Livewire\Mechanisms\HandleComponents\ComponentContext;

use function Livewire\store;

/** @internal */
final readonly class LivewireRelay
{
    public const EVENT = 'toaster:received';

    public function __invoke(Component $component, ComponentContext $ctx): void
    {
        if (! Livewire::isLivewireRequest()) {
            return;
        }

        $isRedirecting = store($component)->get('redirect');
        $isRedirectingUsingNavigate = store($component)->get('redirectUsingNavigate');

        if ($isRedirecting && ! $isRedirectingUsingNavigate) {
            return;
        }

        if ($toasts = Toaster::release()) {
            foreach ($toasts as $toast) {
                $event = new Event(self::EVENT, $toast->toArray());
                $ctx->pushEffect('dispatches', $event->serialize());
            }
        }
    }
}
