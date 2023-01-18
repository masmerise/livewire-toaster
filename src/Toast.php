<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Contracts\Support\Arrayable;

final class Toast implements Arrayable
{
    public function __construct(
        public readonly Message $message,
        public readonly Duration $duration,
        public readonly ToastType $type,
    ) {}

    public function clone(string $replacement): self
    {
        return new self(
            Message::fromString($replacement),
            $this->duration,
            $this->type,
        );
    }

    public function toArray(): array
    {
        return [
            'duration' => $this->duration->value,
            'message' => $this->message->value,
            'type' => $this->type->value,
        ];
    }
}
