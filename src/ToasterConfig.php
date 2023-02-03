<?php declare(strict_types=1);

namespace MAS\Toaster;

use Illuminate\Support\Arr;

/** @internal */
final class ToasterConfig
{
    private function __construct(
        private readonly int $duration,
        private readonly Position $position,
        private readonly bool $translate,
    ) {}

    public static function fromArray(array $config): self
    {
        return new self(
            Arr::get($config, 'duration', 5000),
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

    public function wantsTranslation(): bool
    {
        return $this->translate;
    }

    public function toJavaScript(): array
    {
        return ['duration' => $this->duration];
    }
}
