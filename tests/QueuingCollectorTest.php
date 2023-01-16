<?php declare(strict_types=1);

namespace Tests;

use MAS\Toast\QueuingCollector;
use PHPUnit\Framework\TestCase;

final class QueuingCollectorTest extends TestCase
{
    use ToastFactoryMethods;

    /** @test */
    public function it_can_add_and_flush_toasts(): void
    {
        $collector = new QueuingCollector();
        $collector->add($toastA = $this->aToast());
        $collector->add($toastB = $this->aToast());

        $toasts = $collector->flush();

        $this->assertCount(2, $toasts);
        $this->assertSame($toastA, $toasts[0]);
        $this->assertSame($toastB, $toasts[1]);
        $this->assertEmpty($collector->flush());
    }
}
