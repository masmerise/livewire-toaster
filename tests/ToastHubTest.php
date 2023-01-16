<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Support\Js;
use MAS\Toast\SessionRelay;
use MAS\Toast\ToastHub;

final class ToastHubTest extends TestCase
{
    use InteractsWithViews;
    use ToastFactoryMethods;

    /** @test */
    public function it_passes_the_toasts_from_the_session(): void
    {
        $component = $this->component(ToastHub::class);
        $component->assertSee('x-data="toastHub([])"', false);

        $toasts = [$this->aToast()->toArray()];
        $this->session([SessionRelay::NAME => $toasts]);

        $component = $this->blade('<x-toast-hub />');

        $this->assertFalse($this->app['session.store']->exists(SessionRelay::NAME));
        $component->assertSee('x-data="toastHub(' . Js::from($toasts) . ')"', false);
    }
}
