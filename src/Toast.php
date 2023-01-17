<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Contracts\Support\Arrayable;

final readonly class Toast implements Arrayable
{
    public function __construct(
        public Message $message,
        public Duration $duration,
        public Position $position,
        public ToastType $type,
    ) {}

    public function clone(string $replacement): self
    {
        return new self(
            Message::fromString($replacement),
            $this->duration,
            $this->position,
            $this->type,
        );
    }

    public function toArray(): array
    {
        return [
            'duration' => $this->duration->value,
            'message' => $this->message->value,
            'position' => $this->position->value,
            'type' => $this->type->value,
        ];
    }
}
