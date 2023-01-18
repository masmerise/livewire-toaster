<?php declare(strict_types=1);

namespace MAS\Toaster;

/** @internal */
final class QueuingCollector implements Collector
{
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
}
