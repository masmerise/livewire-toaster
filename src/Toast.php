<?php declare(strict_types=1);

namespace MAS\Toast;

use JsonSerializable;

final readonly class Toast implements JsonSerializable
{
    public function __construct(
        public Message $message,
        public Duration $duration,
        public Position $position,
        public ToastType $type,
    ) {}

    public static function create(): ToastBuilder
    {
        return ToastBuilder::create();
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
}
