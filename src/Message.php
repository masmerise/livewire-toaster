<?php declare(strict_types=1);

namespace MAS\Toaster;

use InvalidArgumentException;
use JsonSerializable;

final class Message implements JsonSerializable
{
    public readonly array $replace;

    public readonly string $value;

    private function __construct(string $value, array $replace = [])
    {
        if (empty($value = trim($value))) {
            throw new InvalidArgumentException('The message value cannot be empty.');
        }

        $this->value = $value;
        $this->replace = $replace;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function fromTranslatable(string $value, array $replace = []): self
    {
        return new self($value, $replace);
    }

    public function equals(Message|string $other): bool
    {
        if ($other instanceof Message) {
            $other = $other->value;
        }

        return $other === $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
