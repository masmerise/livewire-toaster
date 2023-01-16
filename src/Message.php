<?php declare(strict_types=1);

namespace MAS\Toast;

use InvalidArgumentException;
use JsonSerializable;

final readonly class Message implements JsonSerializable
{
    public array $replace;

    public string $value;

    private function __construct(string $value, array $replace = [])
    {
        if (empty($value)) {
            throw new InvalidArgumentException('The message value cannot be empty.');
        }

        $this->value = $value;
        $this->replace = $replace;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function fromTranslatable(string $value, array $replace): self
    {
        return new self($value, $replace);
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
