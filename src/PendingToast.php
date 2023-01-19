<?php declare(strict_types=1);

namespace MAS\Toaster;

use Illuminate\Support\Traits\ForwardsCalls;

/**
 * @method PendingToast duration(int $milliseconds)
 * @method PendingToast error()
 * @method PendingToast info()
 * @method PendingToast message(string $message, array $replace = [])
 * @method PendingToast success()
 * @method PendingToast type(string $type)
 * @method PendingToast warning()
 */
final class PendingToast
{
    use ForwardsCalls;

    private ToastBuilder $builder;

    private bool $dispatched = false;

    private function __construct()
    {
        $this->builder = ToastBuilder::create();
    }

    public static function make(): self
    {
        return new self();
    }

    public static function withDefaults(): self
    {
        return self::make()->duration(app(ToasterConfig::class)->duration());
    }

    public function dispatch(): void
    {
        $toast = $this->builder->get();

        Toaster::add($toast);

        $this->dispatched = true;
    }

    public function __call(string $name, array $arguments): mixed
    {
        $result = $this->forwardCallTo($this->builder, $name, $arguments);

        if ($result instanceof ToastBuilder) {
            $this->builder = $result;

            return $this;
        }

        return $result;
    }

    public function __destruct()
    {
        if (! $this->dispatched) {
            $this->dispatch();
        }
    }
}
