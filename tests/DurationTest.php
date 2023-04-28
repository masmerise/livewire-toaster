<?php declare(strict_types=1);

namespace Tests;

use InvalidArgumentException;
use Masmerise\Toaster\Duration;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DurationTest extends TestCase
{
    #[Test]
    public function it_requires_a_duration_to_be_at_least_1s(): void
    {
        $duration = Duration::fromMillis(3000);
        $this->assertSame(3000, $duration->value);

        $this->expectExceptionMessage(InvalidArgumentException::class);
        $this->expectExceptionMessage('The duration value must be at least 3000 ms.');

        Duration::fromMillis(999);
    }
}
