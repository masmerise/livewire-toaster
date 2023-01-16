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

    public function __call(string $name, array $arguments): self
    {
        $result = $this->forwardCallTo($this->builder, $name, $arguments);

        if ($result instanceof ToastBuilder) {
            $this->builder = $result;
        }

        return $this;
    }

    public function __destruct()
    {
        if (! $this->dispatched) {
            $this->dispatch();
        }
    }
}
