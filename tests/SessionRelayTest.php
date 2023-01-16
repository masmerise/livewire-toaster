<?php declare(strict_types=1);

namespace Tests;

use MAS\Toast\SessionRelay;

final class SessionRelayTest extends TestCase
{
    use CollectorFactoryMethods;
    use ToastFactoryMethods;

    /** @test */
    public function it_relays_toasts_to_the_session_if_available(): void
    {
        $collector = $this->aCollector();
        $session = $this->app['session']->driver('null');
        $relay = new SessionRelay($session, $collector);

        $relay->handle();

        $this->assertFalse($session->exists(SessionRelay::NAME));

        $collector->add($this->aToast());
        $collector->add($this->aToast());

        $relay->handle();

        $this->assertTrue($session->exists(SessionRelay::NAME));
        $this->assertCount(2, $toasts = $session->get(SessionRelay::NAME));
        $this->assertEmpty($collector->flush());
        $this->assertIsArray($toast = $toasts[0]);
        $this->assertArrayHasKey('duration', $toast);
        $this->assertArrayHasKey('message', $toast);
        $this->assertArrayHasKey('position', $toast);
        $this->assertArrayHasKey('type', $toast);
    }
}
