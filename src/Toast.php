<?php declare(strict_types=1);

namespace Masmerise\Toaster;

use Illuminate\Contracts\Support\Arrayable;

final readonly class Toast implements Arrayable
{
    public function __construct(
        public Message $message,
        public Duration $duration,
        public ToastType $type,
    ) {}

    public function toArray(): array
    {
        return [
            'duration' => $this->duration->value,
            'message' => $this->message->value,
            'type' => $this->type->value,
        ];
    }
}
