<?php declare(strict_types=1);

namespace MAS\Toast;

use InvalidArgumentException;
use JsonSerializable;

final readonly class Message implements JsonSerializable
{
    public string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('The message value cannot be empty.');
        }

        $this->value = $value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
