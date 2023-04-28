<?php declare(strict_types=1);

namespace Masmerise\Toaster;

use InvalidArgumentException;

/** @internal */
final readonly class Message
{
    public array $replace;

    public string $value;

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
}
