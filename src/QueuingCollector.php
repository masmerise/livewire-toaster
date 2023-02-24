<?php declare(strict_types=1);

namespace Masmerise\Toaster;

/** @internal */
final class QueuingCollector implements Collector
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
}
