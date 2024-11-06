<?php declare(strict_types=1);

namespace Tests;

use Livewire\Component;
use Livewire\Livewire;
use Masmerise\Toaster\Collector;
use Masmerise\Toaster\LivewireRelay;
use PHPUnit\Framework\Attributes\Test;

final class LivewireRelayTest extends TestCase
{
    #[Test]
    public function it_dispatches_events(): void
    {
        // mount => skip
        $component = Livewire::test(TestComponent::class);
        $component->assertNotDispatched(LivewireRelay::EVENT);

        // redirect => skip
        $component->call('redirectingAction');
        $component->assertNotDispatched(LivewireRelay::EVENT);

        // redirect using navigate => dispatch
        $component->call('redirectingActionUsingNavigate');
        $component->assertDispatched(LivewireRelay::EVENT,
            duration: 3000,
            message: 'Crispy toasts',
            type: 'success',
        );

        // normal action => dispatch
        $component->call('normalAction');
        $component->assertDispatched(LivewireRelay::EVENT,
            duration: 3000,
            message: 'Crispy toasts',
            type: 'success',
        );
    }
}

final class TestComponent extends Component
{
    use ToastFactoryMethods;

    public function normalAction(): void
    {
        // noop
    }

    public function redirectingAction(): void
    {
        $this->redirect('https://localhost');
    }

    public function redirectingActionUsingNavigate(): void
    {
        $this->redirect('https://localhost', true);
    }

    public function render(Collector $toasts): string
    {
        $toasts->collect($this->aToast()); // trigger relay registration

        return '<div></div>';
    }
}
