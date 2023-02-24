<?php declare(strict_types=1);

namespace Masmerise\Toaster;

use PHPUnit\Framework\Assert as PHPUnit;

final class TestableCollector implements Collector
{
    private array $toasts = [];

    public function collect(Toast $toast): void
    {
        $this->toasts[] = $toast;
    }

    public function release(): array
    {
        $toasts = $this->toasts;
        $this->toasts = [];

        return $toasts;
    }

    public function assertDispatched(string $message): void
    {
        $toasts = array_filter($this->toasts, static fn (Toast $toast) => $toast->message->equals($message));

        PHPUnit::assertNotEmpty($toasts, "A toast with the message `{$message}` was not dispatched.");
    }

    public function assertNothingDispatched(): void
    {
        $count = count($this->toasts);

        PHPUnit::assertSame(0, $count, "{$count} unexpected toasts were dispatched.");
    }
}
