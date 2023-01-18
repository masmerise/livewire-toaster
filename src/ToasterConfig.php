<?php declare(strict_types=1);

namespace MAS\Toaster;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

/** @internal */
final class ToasterConfig implements Arrayable
{
    private function __construct(
        private readonly int $duration,
        private readonly int $max,
        private readonly Position $position,
        private readonly bool $translate,
    ) {}

    public static function fromArray(array $config): self
    {
        return new self(
            Arr::get($config, 'duration', 5000),
            Arr::get($config, 'max', 5),
            Position::from(Arr::get($config, 'position', 'right')),
            Arr::get($config, 'translate', true),
        );
    }

    public function duration(): int
    {
        return $this->duration;
    }

    public function position(): Position
    {
        return $this->position;
    }

    public function shouldTranslateMessages(): bool
    {
        return $this->translate;
    }

    public function toArray(): array
    {
        return [
            'defaults' => ['duration' => $this->duration],
            'max' => $this->max,
        ];
    }
}
