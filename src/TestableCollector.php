<?php declare(strict_types=1);

namespace MAS\Toast;

use PHPUnit\Framework\Assert as PHPUnit;

final class TestableCollector implements Collector
{
    /** @var array<Toast> */
    private array $toasts = [];

    public function add(Toast $toast): void
    {
        $this->toasts[] = $toast;
    }

    public function flush(): array
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
