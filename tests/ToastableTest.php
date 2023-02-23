<?php declare(strict_types=1);

namespace Tests;

use Livewire\Component;
use Livewire\Livewire;
use Livewire\LivewireManager;
use MAS\Toaster\LivewireRelay;
use MAS\Toaster\SessionRelay;
use MAS\Toaster\Toastable;

final class ToastableTest extends TestCase
{
    /** @test */
    public function it_can_be_invoked_from_controllers(): void
    {
        $this->app['router']->get('test', [ToastableController::class, 'index'])->middleware('web');

        $response = $this->get('test');

        $response->assertOk()->assertSessionHas(SessionRelay::NAME, [[
            'duration' => 3000,
            'message' => 'I am a crispy toast, yummy!',
            'type' => 'info',
        ]]);
    }

    /** @test */
    public function it_can_be_invoked_from_livewire_components(): void
    {
        $component = Livewire::test(ToastableComponent::class);

        LivewireManager::$isLivewireRequestTestingOverride = true;
        $component->call('bake');

        $component->assertDispatchedBrowserEvent(LivewireRelay::EVENT, [
            'duration' => 3000,
            'message' => 'I became a crispy toast, yummy!',
            'type' => 'success',
        ]);
    }
}

final class ToastableComponent extends Component
{
    use Toastable;

    public function bake(): void
    {
        $this->success('I became a crispy toast, yummy!');
    }

    public function render(): string
    {
        return '<div></div>';
    }
}

final class ToastableController
{
    use Toastable;

    public function index(): array
    {
        $this->info('I am a crispy toast, yummy!');

        return ['message' => 'success'];
    }
}
