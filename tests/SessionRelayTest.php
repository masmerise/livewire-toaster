<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Http\Request;
use MAS\Toaster\Collector;
use MAS\Toaster\SessionRelay;

final class SessionRelayTest extends TestCase
{
    use CollectorFactoryMethods;
    use ToastFactoryMethods;

    /** @test */
    public function it_relays_toasts_to_the_session(): void
    {
        $session = $this->app['session.store'];
        $relay = new SessionRelay($this->app);

        $relay->handle(new Request(), function () {});

        $this->assertFalse($session->exists(SessionRelay::NAME));

        $collector = $this->app[Collector::class];
        $collector->collect($this->aToast());
        $collector->collect($this->aToast());

        $relay->handle(new Request(), function () {});

        $this->assertTrue($session->exists(SessionRelay::NAME));
        $this->assertCount(2, $toasts = $session->get(SessionRelay::NAME));
        $this->assertEmpty($collector->release());
        $this->assertIsArray($toast = $toasts[0]);
        $this->assertArrayHasKey('duration', $toast);
        $this->assertArrayHasKey('message', $toast);
        $this->assertArrayHasKey('type', $toast);
    }
}
