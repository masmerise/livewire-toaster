<?php declare(strict_types=1);

namespace Tests;

use Masmerise\Toaster\AccessibleCollector;
use Masmerise\Toaster\Message;

final class AccessibleCollectorTest extends TestCase
{
    use CollectorFactoryMethods;
    use ToastFactoryMethods;

    /** @test */
    public function it_adds_a_second_for_every_one_hundredth_word_floored(): void
    {
        $collector = new AccessibleCollector($this->aCollector());
        $message = Message::fromTranslatable(str_repeat('word ', 223));

        $collector->collect($this->aToast(message: $message));
        [$toast] = $collector->release();

        $this->assertSame(5000, $toast->duration->value);
    }
}
