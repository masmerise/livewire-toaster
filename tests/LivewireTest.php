<?php declare(strict_types=1);

namespace Tests;

use Livewire\Component;
use Livewire\Livewire;
use Livewire\LivewireManager;
use Livewire\Testing\TestableLivewire;
use MAS\Toast\Collector;
use MAS\Toast\Toaster;
use MAS\Toast\Toastable;
use MAS\Toast\ToastBuilder;

final class LivewireTest extends TestCase
{
    private TestableLivewire $component;

    protected function setUp(): void
    {
        parent::setUp();

        $this->component = Livewire::test(ToastComponent::class);
        LivewireManager::$isLivewireRequestTestingOverride = true;
    }

    /** @test */
    public function multiple_toasts_can_be_dispatched(): void
    {
        $this->component->call('multiple');

        $this->assertCount(2, $effects = $this->component->payload['effects']['dispatches']);

        [$effectA, $effectB] = $effects;
        $this->assertSame('toast-received', $effectA['event']);
        $this->assertSame('toast-received', $effectB['event']);
        $this->assertSame([
            'duration' => 5000,
            'message' => 'The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk',
            'position' => 'center',
            'type' => 'warning',
        ], $effectA['data']);
        $this->assertSame([
            'duration' => 3333,
            'message' => 'Life is available only in the present moment. - Thich Nhat Hanh',
            'position' => 'right',
            'type' => 'error',
        ], $effectB['data']);
    }

    /** @test */
    public function toast_is_dispatched_to_the_browser_using_dependency_injection(): void
    {
        $this->component->call('inject');

        $this->assertCount(1, $effects = $this->component->payload['effects']['dispatches']);

        [$effect] = $effects;
        $this->assertSame([
            'duration' => 2000,
            'message' => 'The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk',
            'position' => 'left',
            'type' => 'success',
        ], $effect['data']);
    }
}

final class ToastComponent extends Component
{
    use Toastable;

    public function inject(Collector $toasts): void
    {
        $toast = ToastBuilder::create()
            ->success()
            ->duration(2000)
            ->message('The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk')
            ->left()
            ->get();

        $toasts->add($toast);
    }

    public function multiple(): void
    {
        $this
            ->toast()
            ->center()
            ->message('The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk')
            ->warning();

        Toaster::toast()
            ->duration(3333)
            ->message('Life is available only in the present moment. - Thich Nhat Hanh')
            ->error()
            ->dispatch();
    }

    public function render(): string
    {
        return '<div></div>';
    }
}
