<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class QueuingCollectorTest extends TestCase
{
    use CollectorFactoryMethods;
    use ToastFactoryMethods;

    #[Test]
    public function it_can_add_and_flush_toasts(): void
    {
        $collector = $this->aCollector();
        $collector->collect($toastA = $this->aToast());
        $collector->collect($toastB = $this->aToast());

        $toasts = $collector->release();

        $this->assertCount(2, $toasts);
        $this->assertSame($toastA, $toasts[0]);
        $this->assertSame($toastB, $toasts[1]);
        $this->assertEmpty($collector->release());
    }
}
