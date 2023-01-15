<?php declare(strict_types=1);

namespace MAS\Toast;

/** @internal */
final class QueuingCollector implements Collector
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
}
