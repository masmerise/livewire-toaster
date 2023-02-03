<?php declare(strict_types=1);

namespace MAS\Toaster;

use Illuminate\Support\Arr;

/** @internal */
final class ToasterConfig
{
    private function __construct(
        public readonly Duration $duration,
        public readonly Position $position,
        public readonly bool $wantsTranslation,
    ) {}

    public static function fromArray(array $config): self
    {
        return new self(
            Duration::fromMillis(Arr::get($config, 'duration', 5000)),
            Position::from(Arr::get($config, 'position', 'right')),
            Arr::get($config, 'translate', true),
        );
    }

    public function toJavaScript(): array
    {
        return ['duration' => $this->duration->value];
    }
}
