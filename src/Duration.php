<?php declare(strict_types=1);

namespace MAS\Toast;

use InvalidArgumentException;
use JsonSerializable;

final readonly class Duration implements JsonSerializable
{
    public int $value;

    public function __construct(int $value)
    {
        if ($value < 1000) {
            throw new InvalidArgumentException('The duration value must be at least 1000 ms.');
        }

        $this->value = $value;
    }

    public function jsonSerialize(): int
    {
        return $this->value;
    }
}
