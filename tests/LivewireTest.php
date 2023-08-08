<?php declare(strict_types=1);

namespace Tests;

use Livewire\Component;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Masmerise\Toaster\Collector;
use Masmerise\Toaster\Toastable;
use Masmerise\Toaster\ToastBuilder;
use Masmerise\Toaster\Toaster;
use PHPUnit\Framework\Attributes\Test;

final class LivewireTest extends TestCase
{
    private Testable $component;

    protected function setUp(): void
    {
        parent::setUp();

        $this->component = Livewire::test(ToastComponent::class);
    }

    #[Test]
    public function multiple_toasts_can_be_dispatched(): void
    {
        $this->component->call('multiple');

        $this->assertCount(2, $events = $this->component->effects['dispatches']);

        [$eventA, $eventB] = $events;
        $this->assertSame('toaster:received', $eventA['name']);
        $this->assertSame('toaster:received', $eventB['name']);
        $this->assertSame([
            'duration' => 3000,
            'message' => 'The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk',
            'type' => 'warning',
        ], $eventA['params']);
        $this->assertSame([
            'duration' => 3333,
            'message' => 'Life is available only in the present moment. - Thich Nhat Hanh',
            'type' => 'error',
        ], $eventB['params']);
    }

    #[Test]
    public function toast_is_dispatched_to_the_browser_using_dependency_injection(): void
    {
        $this->component->call('inject');

        $this->assertCount(1, $events = $this->component->effects['dispatches']);

        [$event] = $events;
        $this->assertSame([
            'duration' => 4000,
            'message' => 'The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk',
            'type' => 'success',
        ], $event['params']);
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
