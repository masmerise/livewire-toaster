<?php declare(strict_types=1);

namespace MAS\Toast;

use BadMethodCallException;
use JsonSerializable;

/**
 * @method static PendingToast error(string $message)
 * @method static PendingToast info(string $message)
 * @method static PendingToast success(string $message)
 * @method static PendingToast warning(string $message)
 */
final readonly class Toast implements JsonSerializable
{
    public function __construct(
        public Message $message,
        public Duration $duration,
        public Position $position,
        public ToastType $type,
    ) {}

    public static function create(): PendingToast
    {
        return new PendingToast(config('toast.duration'), config('toast.position'));
    }

    public function clone(string $replacement): self
    {
        return new self(
            Message::fromString($replacement),
            $this->duration,
            $this->position,
            $this->type,
        );
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public static function __callStatic(string $name, array $arguments): mixed
    {
        if (count($arguments) !== 1 || ! in_array($name, ToastType::toValues())) {
            throw new BadMethodCallException("Call to undefined method MAS\Toast\Toast::{$name}()");
        }

        return self::create()->type($name)->message($arguments[0]);
    }
}
