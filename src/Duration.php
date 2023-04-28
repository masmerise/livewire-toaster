<?php declare(strict_types=1);

namespace Masmerise\Toaster;

use InvalidArgumentException;

/** @internal */
final readonly class Duration
{
    private const MINIMUM = 3000;

    public int $value;

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
}
