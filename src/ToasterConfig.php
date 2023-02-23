<?php declare(strict_types=1);

namespace MAS\Toaster;

use Illuminate\Support\Arr;

/** @internal */
final class ToasterConfig
{
    private function __construct(
        public readonly int $duration,
        public readonly string $position,
        public readonly bool $wantsAccessibility,
        public readonly bool $wantsTranslation,
    ) {}

    public static function fromArray(array $config): self
    {
        return new self(
            Arr::get($config, 'duration', 5000),
            Arr::get($config, 'position', 'right'),
            Arr::get($config, 'accessibility', true),
            Arr::get($config, 'translate', true),
        );
    }

    public function position(): Position
    {
        return Position::from($this->position);
    }

    public function toJavaScript(): array
    {
        return [
            'accessibility' => $this->wantsAccessibility,
            'duration' => $this->duration,
        ];
    }
}
