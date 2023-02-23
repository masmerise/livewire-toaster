<?php declare(strict_types=1);

namespace Tests;

use Livewire\Component;
use Livewire\Livewire;
use Livewire\LivewireManager;
use MAS\Toaster\Collector;
use MAS\Toaster\LivewireRelay;

final class LivewireRelayTest extends TestCase
{
    /** @test */
    public function it_dispatches_browser_events(): void
    {
        // mount => skip
        $component = Livewire::test(TestComponent::class);
        $component->assertNotDispatchedBrowserEvent(LivewireRelay::EVENT);

        // redirect => skip
        LivewireManager::$isLivewireRequestTestingOverride = true;
        $component->call('redirectingAction');
        $component->assertNotDispatchedBrowserEvent(LivewireRelay::EVENT);

        // normal action => dispatch
        LivewireManager::$isLivewireRequestTestingOverride = true;
        $component->call('normalAction');
        $component->assertDispatchedBrowserEvent(LivewireRelay::EVENT, [
            'duration' => 3000,
            'message' => 'Crispy toasts',
            'type' => 'success',
        ]);
    }
}

final class TestComponent extends Component
{
    use ToastFactoryMethods;

    public function normalAction(): void
    {
        // noop
    }

    public function redirectingAction(): mixed
    {
        return redirect()->to('https://localhost');
    }

    public function render(Collector $toasts): string
    {
        $toasts->collect($this->aToast()); // trigger relay registration

        return '<div></div>';
    }
}
