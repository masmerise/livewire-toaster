<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Support\Traits\ForwardsCalls;

/** @mixin ToastBuilder */
final class PendingToast
{
    use ForwardsCalls;

    private ToastBuilder $builder;

    private bool $dispatched = false;

    public function __construct(int $duration, string $position)
    {
        $this->builder = ToastBuilder::create()
            ->duration($duration)
            ->position($position);
    }

    public function dispatch(): void
    {
        $toast = $this->builder->get();

        app(Collector::class)->add($toast);

        $this->dispatched = true;
    }

    public function __call(string $name, array $arguments): mixed
    {
        return $this->forwardDecoratedCallTo($this->builder, $name, $arguments);
    }

    public function __destruct()
    {
        if (! $this->dispatched) {
            $this->dispatch();
        }
    }
}
