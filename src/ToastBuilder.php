<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Support\Traits\Conditionable;
use UnexpectedValueException;

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
        $that = clone $this;
        $that->position = Position::Center;

        return $that;
    }

    public function duration(int $milliseconds): self
    {
        $that = clone $this;
        $that->duration = Duration::fromMillis($milliseconds);

        return $that;
    }

    public function error(): self
    {
        $that = clone $this;
        $that->type = ToastType::Error;

        return $that;
    }

    public function info(): self
    {
        $that = clone $this;
        $that->type = ToastType::Info;

        return $that;
    }

    public function left(): self
    {
        $that = clone $this;
        $that->position = Position::Left;

        return $that;
    }

    public function message(string $message): self
    {
        $that = clone $this;
        $that->message = Message::fromString($message);

        return $that;
    }

    public function position(string $position): self
    {
        $that = clone $this;
        $that->position = Position::from($position);

        return $that;
    }

    public function right(): self
    {
        $that = clone $this;
        $that->position = Position::Right;

        return $that;
    }

    public function success(): self
    {
        $that = clone $this;
        $that->type = ToastType::Success;

        return $that;
    }

    public function type(string $type): self
    {
        $that = clone $this;
        $that->type = ToastType::from($type);

        return $that;
    }

    public function warning(): self
    {
        $that = clone $this;
        $that->type = ToastType::Warning;

        return $that;
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
}
