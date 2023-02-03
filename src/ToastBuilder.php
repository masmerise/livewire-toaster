<?php declare(strict_types=1);

namespace MAS\Toaster;

use Illuminate\Support\Traits\Conditionable;
use UnexpectedValueException;

final class ToastBuilder
{
    use Conditionable;

    private ?Duration $duration = null;

    private ?Message $message = null;

    private ?ToastType $type = null;

    public static function create(): self
    {
        return new self();
    }

    public function duration(Duration|int $milliseconds): self
    {
        if (is_int($milliseconds)) {
            $milliseconds = Duration::fromMillis($milliseconds);
        }

        return $this->modify('duration', $milliseconds);
    }

    public function error(): self
    {
        return $this->modify('type', ToastType::Error);
    }

    public function info(): self
    {
        return $this->modify('type', ToastType::Info);
    }

    public function message(Message|string $message, array $replace = []): self
    {
        if (is_string($message)) {
            $message = Message::fromTranslatable($message, $replace);
        }

        return $this->modify('message', $message);
    }

    public function success(): self
    {
        return $this->modify('type', ToastType::Success);
    }

    public function type(ToastType|string $type): self
    {
        if (is_string($type)) {
            $type = ToastType::from($type);
        }

        return $this->modify('type', $type);
    }

    public function warning(): self
    {
        return $this->modify('type', ToastType::Warning);
    }

    public function get(): Toast
    {
        if (! $this->duration instanceof Duration) {
            throw new UnexpectedValueException('You must provide a valid duration.');
        }

        if (! $this->message instanceof Message) {
            throw new UnexpectedValueException('You must provide a valid message.');
        }

        if (! $this->type instanceof ToastType) {
            throw new UnexpectedValueException('You must choose a valid type.');
        }

        return new Toast($this->message, $this->duration, $this->type);
    }

    private function modify(string $property, mixed $value): self
    {
        $that = clone $this;
        $that->{$property} = $value;

        return $that;
    }
}
