<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Support\Traits\Conditionable;
use UnexpectedValueException;

/** @method void dispatch() */
final class ToastBuilder
{
    use Conditionable;

    private ?Duration $duration = null;

    private ?Message $message = null;

    private ?Position $position = null;

    private ?ToastType $type = null;

    public static function create(): self
    {
        return new self();
    }

    public function center(): self
    {
        return $this->modify('position', Position::Center);
    }

    public function duration(int $milliseconds): self
    {
        return $this->modify('duration', Duration::fromMillis($milliseconds));
    }

    public function error(): self
    {
        return $this->modify('type', ToastType::Error);
    }

    public function info(): self
    {
        return $this->modify('type', ToastType::Info);
    }

    public function left(): self
    {
        return $this->modify('position', Position::Left);
    }

    public function message(string $message, array $replace = []): self
    {
        return $this->modify('message', Message::fromTranslatable($message, $replace));
    }

    public function position(string $position): self
    {
        return $this->modify('position', Position::from($position));
    }

    public function right(): self
    {
        return $this->modify('position', Position::Right);
    }

    public function success(): self
    {
        return $this->modify('type', ToastType::Success);
    }

    public function type(string $type): self
    {
        return $this->modify('type', ToastType::from($type));
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

        if (! $this->position instanceof Position) {
            throw new UnexpectedValueException('You must choose a valid position.');
        }

        if (! $this->type instanceof ToastType) {
            throw new UnexpectedValueException('You must choose a valid type.');
        }

        return new Toast($this->message, $this->duration, $this->position, $this->type);
    }

    private function modify(string $property, mixed $value): self
    {
        $that = clone $this;
        $that->{$property} = $value;

        return $that;
    }
}
