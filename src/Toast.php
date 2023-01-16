<?php declare(strict_types=1);

namespace MAS\Toast;

use BadMethodCallException;
use JsonSerializable;

/**
 * @method static PendingToast error(string $message, array $replace = [])
 * @method static PendingToast info(string $message, array $replace = [])
 * @method static PendingToast success(string $message, array $replace = [])
 * @method static PendingToast warning(string $message, array $replace = [])
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

    public static function __callStatic(string $name, array $arguments): PendingToast
    {
        if (! in_array($name, ToastType::toValues()) || ! count($arguments) || count($arguments) > 2) {
            throw new BadMethodCallException("Call to undefined method MAS\Toast\Toast::{$name}()");
        }

        return self::create()->type($name)->message($arguments[0], $arguments[1] ?? []);
    }
}
