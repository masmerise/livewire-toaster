<?php declare(strict_types=1);

namespace MAS\Toast;

use JsonSerializable;

final readonly class Toast implements JsonSerializable
{
    public function __construct(
        public Message $message,
        public Duration $duration,
        public ToastType $type,
    ) {}

    public static function create(): ToastBuilder
    {
        return ToastBuilder::create();
    }

    public function clone(string $replacement): self
    {
        return new self(new Message($replacement), $this->duration, $this->type);
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
