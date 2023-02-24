<?php declare(strict_types=1);

namespace Tests;

use Livewire\Component;
use Livewire\Livewire;
use Livewire\LivewireManager;
use Livewire\Testing\TestableLivewire;
use Masmerise\Toaster\Collector;
use Masmerise\Toaster\Toaster;
use Masmerise\Toaster\Toastable;
use Masmerise\Toaster\ToastBuilder;

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
        $this->assertSame('toaster:received', $effectA['event']);
        $this->assertSame('toaster:received', $effectB['event']);
        $this->assertSame([
            'duration' => 3000,
            'message' => 'The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk',
            'type' => 'warning',
        ], $effectA['data']);
        $this->assertSame([
            'duration' => 3333,
            'message' => 'Life is available only in the present moment. - Thich Nhat Hanh',
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
            'duration' => 4000,
            'message' => 'The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk',
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
            ->duration(4000)
            ->message('The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk')
            ->get();

        $toasts->collect($toast);
    }

    public function multiple(): void
    {
        $this->warning('The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk');

        Toaster::error('Life is available only in the present moment. - Thich Nhat Hanh')
            ->duration(3333)
            ->dispatch();
    }

    public function render(): string
    {
        return '<div></div>';
    }
}
