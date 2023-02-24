<?php declare(strict_types=1);

namespace Tests;

use InvalidArgumentException;
use Masmerise\Toaster\Duration;
use PHPUnit\Framework\TestCase;

final class DurationTest extends TestCase
{
    /** @test */
    public function it_requires_a_duration_to_be_at_least_1s(): void
    {
        $duration = Duration::fromMillis(3000);
        $this->assertSame(3000, $duration->value);

        $this->expectExceptionMessage(InvalidArgumentException::class);
        $this->expectExceptionMessage('The duration value must be at least 3000 ms.');

        Duration::fromMillis(999);
    }

    /** @test */
    public function it_is_json_serializable(): void
    {
        $duration = Duration::fromMillis(3000);

        $result = json_decode(json_encode($duration, JSON_THROW_ON_ERROR));

        $this->assertSame(3000, $result);
    }
}
