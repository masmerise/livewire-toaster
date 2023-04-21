<?php declare(strict_types=1);

namespace Masmerise\Toaster;

use Illuminate\Support\Arr;

/** @internal */
final readonly class ToasterConfig
{
    private function __construct(
        public string $alignment,
        public int $duration,
        public string $position,
        public bool $wantsAccessibility,
        public bool $wantsCloseableToasts,
        public bool $wantsTranslation,
    ) {}

    public static function fromArray(array $config): self
    {
        return new self(
            Arr::get($config, 'alignment', 'bottom'),
            Arr::get($config, 'duration', 3000),
            Arr::get($config, 'position', 'right'),
            Arr::get($config, 'accessibility', true),
            Arr::get($config, 'closeable', true),
            Arr::get($config, 'translate', true),
        );
    }

    public function alignment(): Alignment
    {
        return Alignment::from($this->alignment);
    }

    public function position(): Position
    {
        return Position::from($this->position);
    }

    public function toJavaScript(): array
    {
        return ['alignment' => $this->alignment, 'duration' => $this->duration];
    }
}
