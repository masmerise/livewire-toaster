<?php declare(strict_types=1);

namespace MAS\Toaster;

use InvalidArgumentException;
use JsonSerializable;

final class Duration implements JsonSerializable
{
    private const MINIMUM = 3000;

    public readonly int $value;

    private function __construct(int $value)
    {
        if ($value < self::MINIMUM) {
            throw new InvalidArgumentException('The duration value must be at least 3000 ms.');
        }

        $this->value = $value;
    }

    public static function fromMillis(int $value): self
    {
        return new self($value);
    }

    public function jsonSerialize(): int
    {
        return $this->value;
    }
}
